<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\HeaderSlide;
use App\Models\LandingContent;
use App\Models\Member;
use App\Models\Post;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        // Get all landing contents
        $contents = LandingContent::all()->keyBy('key');

        $members = Member::orderBy('sort_order')->orderBy('id')->get()->map(fn (Member $member) => [
            'initial' => $member->initial ?? '',
            'name' => $member->name,
            'role' => $member->role ?? '',
            'code' => $member->code ?? '',
            'motto' => $member->motto ?? '',
            'desc' => $member->description ?? '',
            'photo' => $member->photo,
        ])->values()->all();

        $achievements = Achievement::orderBy('sort_order')->orderBy('id')->get()->map(fn (Achievement $achievement) => [
            'icon' => $achievement->icon ?? '',
            'image' => $achievement->image,
            'title' => $achievement->title,
            'year' => $achievement->year ?? '',
            'desc' => $achievement->description ?? '',
        ])->values()->all();

        // Kabar terbaru: semua post published, ditampilkan sebagai rail horizontal di header.
        $published = Post::with('user')->where('status', 'published')->latest()->get();
        $posts = $published;

        // Banner header yang berganti otomatis, dalam urutan prioritas:
        //   1. slide header yang dikelola admin (Settings > Header)
        //   2. post yang dicentang "tampilkan di banner"
        //   3. 5 kabar terbaru
        // Kalau semuanya kosong, view falls back ke teks cadangan hero_*.
        // Satu slide tetap dipakai (tapi tidak digeser), karena admin mungkin
        // sengaja menunjuk tepat satu item.
        $slides = HeaderSlide::where('is_active', true)->orderBy('sort_order')->orderBy('id')->get()
            ->map(fn (HeaderSlide $slide) => [
                'title' => $slide->title,
                'text' => $slide->excerpt(),
                'image' => $slide->image,
                'url' => $slide->hasExternalLink() ? $slide->externalLink() : null,
                'label' => $slide->buttonLabel(),
                'date' => $slide->created_at?->format('d M Y'),
            ]);

        if ($slides->isEmpty()) {
            $slides = $published->where('is_featured', true)->values();
        }

        if ($slides->isEmpty()) {
            $slides = $published->take(5);
        }

        // Bentuk seragam supaya view tidak perlu tahu asal slide tersebut.
        $slides = $slides->map(function ($slide) {
            if ($slide instanceof Post) {
                return [
                    'title' => $slide->title,
                    'text' => \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/', ' ', strip_tags($slide->content))), 150),
                    'image' => null,
                    'url' => $slide->readUrl(),
                    'label' => $contents['hero_cta_label']->value ?? 'Baca Selengkapnya',
                    'date' => $slide->created_at?->format('d M Y'),
                ];
            }

            return $slide;
        })->values();

        return view('public.landing', [
            'contents' => $contents,
            'members' => $members,
            'achievements' => $achievements,
            'posts' => $posts,
            'slides' => $slides,
        ]);
    }
}
