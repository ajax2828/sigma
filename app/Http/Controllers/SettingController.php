<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\HeaderSlide;
use App\Models\LandingContent;
use App\Models\Member;
use App\Models\MemberHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class SettingController extends Controller
{
    public function index()
    {
        $contents = LandingContent::all()->keyBy('key');
        return view('admin.settings.landing', compact('contents'));
    }

    /** Header banner: satu baris = satu slide, urut dari sort_order. */
    public function header()
    {
        $contents = LandingContent::all()->keyBy('key');
        $slides = HeaderSlide::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.settings.header', compact('contents', 'slides'));
    }

    public function backgrounds()
    {
        $contents = LandingContent::all()->keyBy('key');
        $activeSection = request()->query('section', 'hero');
        $allowedSections = ['page', 'hero', 'about', 'members', 'achievements', 'footer'];

        if (! in_array($activeSection, $allowedSections, true)) {
            $activeSection = 'hero';
        }

        return view('admin.settings.backgrounds', compact('contents', 'activeSection'));
    }

    public function updateBackgrounds(Request $request)
    {
        $imageRule = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];
        $colorRule = ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'];
        $angleRule = ['nullable', 'integer', 'between:0,360'];

        $validated = $request->validate([
            'page_background_image' => $imageRule,
            'page_gradient_start' => $colorRule,
            'page_gradient_end' => $colorRule,
            'page_gradient_angle' => $angleRule,
            'hero_background_image' => $imageRule,
            'hero_gradient_start' => $colorRule,
            'hero_gradient_end' => $colorRule,
            'hero_gradient_angle' => $angleRule,
            'about_background_image' => $imageRule,
            'about_gradient_start' => $colorRule,
            'about_gradient_end' => $colorRule,
            'about_gradient_angle' => $angleRule,
            'members_background_image' => $imageRule,
            'members_gradient_start' => $colorRule,
            'members_gradient_end' => $colorRule,
            'members_gradient_angle' => $angleRule,
            'achievements_background_image' => $imageRule,
            'achievements_gradient_start' => $colorRule,
            'achievements_gradient_end' => $colorRule,
            'achievements_gradient_angle' => $angleRule,
            'footer_background_image' => $imageRule,
            'footer_gradient_start' => $colorRule,
            'footer_gradient_end' => $colorRule,
            'footer_gradient_angle' => $angleRule,
        ]);

        $gradientFields = [
            'page_gradient_start', 'page_gradient_end', 'page_gradient_angle',
            'hero_gradient_start', 'hero_gradient_end', 'hero_gradient_angle',
            'about_gradient_start', 'about_gradient_end', 'about_gradient_angle',
            'members_gradient_start', 'members_gradient_end', 'members_gradient_angle',
            'achievements_gradient_start', 'achievements_gradient_end', 'achievements_gradient_angle',
            'footer_gradient_start', 'footer_gradient_end', 'footer_gradient_angle',
        ];

        foreach ($gradientFields as $field) {
            if ($request->has($field)) {
                LandingContent::updateOrCreate(
                    ['key' => $field],
                    ['value' => $validated[$field] ?? $request->$field, 'type' => 'text']
                );
            }
        }

        $imageFields = [
            'page_background_image' => ['page-background', 'backgrounds'],
            'hero_background_image' => ['hero-background', 'hero'],
            'about_background_image' => ['about-background', 'backgrounds'],
            'members_background_image' => ['members-background', 'backgrounds'],
            'achievements_background_image' => ['achievements-background', 'backgrounds'],
            'footer_background_image' => ['footer-background', 'backgrounds'],
        ];

        foreach ($imageFields as $field => [$prefix, $directory]) {
            $this->storeBackgroundImage($request, $field, $prefix, $directory);
        }

        return redirect()->route('admin.settings.backgrounds', ['section' => request()->query('section', 'hero')])
            ->with('success', 'Background settings updated successfully!');
    }

    /**
     * Teks cadangan untuk header. Dipakai hanya kalau tidak ada slide header
     * dan tidak ada kabar published, jadi banner tidak pernah kosong.
     */
    public function updateHeaderFallback(Request $request)
    {
        $validated = $request->validate([
            'hero_title' => ['required', 'string', 'max:100'],
            'hero_tagline' => ['nullable', 'string', 'max:200'],
            'hero_description' => ['nullable', 'string', 'max:1000'],
            'hero_cta_label' => ['nullable', 'string', 'max:50'],
        ]);

        foreach ([
            'hero_title' => 'text',
            'hero_tagline' => 'text',
            'hero_description' => 'textarea',
            'hero_cta_label' => 'text',
        ] as $key => $type) {
            LandingContent::updateOrCreate(
                ['key' => $key],
                ['value' => $validated[$key] ?? null, 'type' => $type]
            );
        }

        return redirect()->route('admin.settings.header')
            ->with('success', 'Teks cadangan header berhasil disimpan.');
    }

    public function storeHeaderSlide(Request $request)
    {
        HeaderSlide::create($this->validateHeaderSlide($request) + [
            'sort_order' => ((int) HeaderSlide::max('sort_order')) + 1,
        ]);

        return redirect()->route('admin.settings.header')
            ->with('success', 'Slide header berhasil ditambahkan.');
    }

    public function updateHeaderSlide(Request $request, HeaderSlide $headerSlide)
    {
        $headerSlide->update($this->validateHeaderSlide($request));

        return redirect()->route('admin.settings.header')
            ->with('success', 'Slide header berhasil diperbarui.');
    }

    public function destroyHeaderSlide(HeaderSlide $headerSlide)
    {
        $headerSlide->delete();

        return redirect()->route('admin.settings.header')
            ->with('success', 'Slide header berhasil dihapus.');
    }

    public function update(Request $request)
    {
        // Field hero_* dan achievement_section_* sengaja tidak ada di sini:
        // keduanya sudah punya halaman sendiri (Hero, Achievements).
        $fields = [
            ['key' => 'site_title', 'type' => 'text'],
            ['key' => 'nav_brand', 'type' => 'text'],
            ['key' => 'nav_about_label', 'type' => 'text'],
            ['key' => 'nav_members_label', 'type' => 'text'],
            ['key' => 'nav_achievements_label', 'type' => 'text'],
            ['key' => 'nav_login_label', 'type' => 'text'],
            ['key' => 'footer_text', 'type' => 'text'],
            ['key' => 'about_title', 'type' => 'text'],
            ['key' => 'about_subtitle', 'type' => 'text'],
            ['key' => 'about_organization_title', 'type' => 'text'],
            ['key' => 'about_slide_title', 'type' => 'text'],
            ['key' => 'vision_mission_title', 'type' => 'text'],
            ['key' => 'about_description', 'type' => 'textarea'],
            ['key' => 'vision', 'type' => 'textarea'],
            ['key' => 'mission', 'type' => 'textarea'],
            ['key' => 'stat_active_members', 'type' => 'text'],
            ['key' => 'stat_projects', 'type' => 'text'],
            ['key' => 'stat_awards', 'type' => 'text'],
            ['key' => 'stat_years', 'type' => 'text'],
            ['key' => 'stat_partnerships', 'type' => 'text'],
            ['key' => 'stat_dedication', 'type' => 'text'],
            ['key' => 'stat_active_members_label', 'type' => 'text'],
            ['key' => 'stat_projects_label', 'type' => 'text'],
            ['key' => 'stat_awards_label', 'type' => 'text'],
            ['key' => 'stat_years_label', 'type' => 'text'],
            ['key' => 'stat_partnerships_label', 'type' => 'text'],
            ['key' => 'stat_dedication_label', 'type' => 'text'],
        ];

        foreach ($fields as $field) {
            if ($request->has($field['key'])) {
                LandingContent::updateOrCreate(
                    ['key' => $field['key']],
                    ['value' => $request->{$field['key']}, 'type' => $field['type']]
                );
            }
        }

        return redirect()->route('admin.settings.landing')
            ->with('success', 'Landing page settings updated successfully!');
    }

    public function members()
    {
        $contents = LandingContent::all()->keyBy('key');
        $members = Member::orderBy('sort_order')->orderBy('id')->get();
        $histories = MemberHistory::with('creator')->latest('id')->limit(50)->get();

        return view('admin.settings.members', compact('contents', 'members', 'histories'));
    }

    public function membersPrint()
    {
        $members = Member::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.settings.members-print', compact('members'));
    }

    public function storeMember(Request $request)
    {
        $data = $this->validateMember($request);
        $data['photo'] = $this->storeMemberPhoto($request);
        $data['sort_order'] = ((int) Member::max('sort_order')) + 1;

        $member = Member::create($data);
        $this->recordMemberHistory($member, 'created');

        return redirect()->route('admin.settings.members')
            ->with('success', 'Member berhasil ditambahkan.');
    }

    public function updateMember(Request $request, Member $member)
    {
        $data = $this->validateMember($request);
        $photo = $this->storeMemberPhoto($request);
        if ($photo !== null) {
            $data['photo'] = $photo;
        }

        $member->update($data);
        $this->recordMemberHistory($member->fresh(), 'updated');

        return redirect()->route('admin.settings.members')
            ->with('success', 'Member berhasil diperbarui.');
    }

    public function destroyMember(Member $member)
    {
        $this->recordMemberHistory($member, 'deleted');
        $member->delete();

        return redirect()->route('admin.settings.members')
            ->with('success', 'Member berhasil dihapus dan riwayat tersimpan.');
    }

    public function restoreMember(MemberHistory $history)
    {
        $member = Member::create([
            'initial' => $history->initial,
            'name' => $history->name,
            'role' => $history->role,
            'code' => $history->code,
            'description' => $history->description,
            'motto' => $history->motto,
            'photo' => $history->photo,
            'sort_order' => ((int) Member::max('sort_order')) + 1,
        ]);
        $this->recordMemberHistory($member, 'restored');

        return redirect()->route('admin.settings.members')
            ->with('success', 'Member berhasil dikembalikan dari riwayat.');
    }
    public function updateMemberSection(Request $request)
    {
        $data = $request->validate([
            'member_section_title' => ['nullable', 'string', 'max:150'],
            'member_section_subtitle' => ['nullable', 'string', 'max:300'],
        ]);

        foreach ($data as $key => $value) {
            LandingContent::updateOrCreate(['key' => $key], ['value' => $value, 'type' => 'text']);
        }

        return redirect()->route('admin.settings.members')
            ->with('success', 'Judul section member berhasil disimpan.');
    }

    private function validateMember(Request $request): array
    {
        return $request->validate([
            'initial' => ['nullable', 'string', 'max:20'],
            'name' => ['required', 'string', 'max:100'],
            'role' => ['nullable', 'string', 'max:100'],
            'code' => ['nullable', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'motto' => ['nullable', 'string', 'max:200'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);
    }

    private function storeMemberPhoto(Request $request): ?string
    {
        if (! $request->hasFile('photo')) {
            return null;
        }

        File::ensureDirectoryExists(public_path('images/members'), 0775);
        $file = $request->file('photo');
        $filename = 'member-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $file->extension();
        $file->move(public_path('images/members'), $filename);

        return '/images/members/' . $filename;
    }

    private function recordMemberHistory(Member $member, string $action): void
    {
        MemberHistory::create([
            'member_id' => $member->id,
            'action' => $action,
            'initial' => $member->initial,
            'name' => $member->name,
            'role' => $member->role,
            'code' => $member->code,
            'description' => $member->description,
            'motto' => $member->motto,
            'photo' => $member->photo,
            'created_by' => auth()->id(),
        ]);
    }

    private function storeBackgroundImage(Request $request, string $field, string $prefix, string $directory): void
    {
        if (! $request->hasFile($field)) {
            return;
        }

        File::ensureDirectoryExists(public_path('images/' . $directory), 0775);
        $file = $request->file($field);
        $filename = $prefix . '-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $file->extension();
        $file->move(public_path('images/' . $directory), $filename);

        LandingContent::updateOrCreate(
            ['key' => $field],
            ['value' => '/images/' . $directory . '/' . $filename, 'type' => 'image']
        );
    }

    private function storeHeroBackgroundImage(Request $request): void
    {
        $this->storeBackgroundImage($request, 'hero_background_image', 'hero-background', 'hero');
    }

    public function achievements()
    {
        $contents = LandingContent::all()->keyBy('key');
        $achievements = Achievement::orderBy('sort_order')->orderBy('id')->get();

        return view('admin.settings.achievements', compact('contents', 'achievements'));
    }

    public function storeAchievement(Request $request)
    {
        $achievement = Achievement::create($this->validateAchievement($request) + [
            'sort_order' => ((int) Achievement::max('sort_order')) + 1,
        ]);

        return redirect()->route('admin.settings.achievements')
            ->with('success', 'Achievement berhasil ditambahkan.');
    }

    public function updateAchievement(Request $request, Achievement $achievement)
    {
        $achievement->update($this->validateAchievement($request));

        return redirect()->route('admin.settings.achievements')
            ->with('success', 'Achievement berhasil diperbarui.');
    }

    public function destroyAchievement(Achievement $achievement)
    {
        $achievement->delete();

        return redirect()->route('admin.settings.achievements')
            ->with('success', 'Achievement berhasil dihapus.');
    }

    public function updateAchievementSection(Request $request)
    {
        $data = $request->validate([
            'achievement_section_title' => ['nullable', 'string', 'max:150'],
            'achievement_section_subtitle' => ['nullable', 'string', 'max:300'],
        ]);

        foreach ($data as $key => $value) {
            LandingContent::updateOrCreate(['key' => $key], ['value' => $value, 'type' => 'text']);
        }

        return redirect()->route('admin.settings.achievements')
            ->with('success', 'Judul section achievement berhasil disimpan.');
    }

    private function validateAchievement(Request $request): array
    {
        $data = $request->validate([
            'icon' => ['nullable', 'string', 'max:20'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'title' => ['required', 'string', 'max:200'],
            'year' => ['nullable', 'string', 'max:20'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $image = $this->storeAchievementImage($request);
        if ($image !== null) {
            $data['image'] = $image;
        }

        return $data;
    }

    private function storeAchievementImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        File::ensureDirectoryExists(public_path('images/achievements'), 0775);
        $file = $request->file('image');
        $filename = 'achievement-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $file->extension();
        $file->move(public_path('images/achievements'), $filename);

        return '/images/achievements/' . $filename;
    }

    private function validateHeaderSlide(Request $request): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string', 'max:1000'],
            'link' => ['nullable', 'string', 'max:2048'],
            'cta_label' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            // Absen di form tambah (urutan auto), jadi hanya ikut ter-update
            // kalau field-nya benar-benar dikirim dari form edit.
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999'],
            // Checkbox tidak mengirim apa pun kalau tidak dicentang, jadi harus
            // selalu ditulis eksplisit — kalau tidak, status lama ikut hilang.
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $image = $this->storeHeaderSlideImage($request);
        if ($image !== null) {
            $data['image'] = $image;
        } else {
            // Tidak ada file baru: jangan sentuh kolom image, supaya gambar
            // lama tidak terhapus setiap kali judul diedit.
            unset($data['image']);
        }

        return $data;
    }

    private function storeHeaderSlideImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        File::ensureDirectoryExists(public_path('images/header'), 0775);
        $file = $request->file('image');
        $filename = 'header-slide-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $file->extension();
        $file->move(public_path('images/header'), $filename);

        return '/images/header/' . $filename;
    }
}