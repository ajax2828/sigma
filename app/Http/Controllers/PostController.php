<?php

namespace App\Http\Controllers;

use App\Models\LandingContent;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::with('user')
            ->when($request->filled('q'), fn ($query) => $query->where(
                fn ($q) => $q->where('title', 'like', '%' . $request->string('q') . '%')
                    ->orWhere('content', 'like', '%' . $request->string('q') . '%')
            ))
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function publicShow(Post $post): View
    {
        abort_unless($post->isPublished(), 404);

        $contents = LandingContent::all()->keyBy('key');

        return view('public.post', compact('post', 'contents'));
    }

    public function create(): View
    {
        return view('admin.posts.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request);

        $validated['user_id'] = $request->user()->id;
        $validated['image'] = $this->storeImage($request);

        Post::create($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post created successfully.');
    }

    public function show(Post $post): View
    {
        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $this->validated($request);

        if ($request->hasFile('image')) {
            $this->deleteImage($post->image);
            $validated['image'] = $this->storeImage($request);
        } else {
            // Tidak ada file baru: jangan sentuh kolom image supaya gambar
            // lama tidak hilang setiap kali judul/isi diedit.
            unset($validated['image']);
        }

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->deleteImage($post->image);

        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
    }

    private function storeImage(Request $request): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        File::ensureDirectoryExists(public_path('images/posts'), 0775);
        $file = $request->file('image');
        $filename = 'post-' . time() . '-' . bin2hex(random_bytes(4)) . '.' . $file->extension();
        $file->move(public_path('images/posts'), $filename);

        return '/images/posts/' . $filename;
    }

    private function deleteImage(?string $path): void
    {
        // Hapus hanya file yang memang dibuat di direktori ini.
        if (! $path || dirname($path) !== '/images/posts') {
            return;
        }

        $full = public_path('images/posts/' . basename($path));
        if (is_file($full)) {
            File::delete($full);
        }
    }

    /**
     * Validasi + normalisasi untuk store dan update, supaya field "link"
     * tidak bisa lolos di salah satu jalur dan hilang di jalur lain.
     * Link kosong dari form disimpan sebagai null, bukan string kosong.
     *
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'nullable|string|max:100',
            'content' => 'required|string',
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'link' => 'nullable|string|max:2048',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['link'] = blank($validated['link'] ?? null) ? null : trim($validated['link']);

        // Penulis kosong = pakai nama pembuat post, bukan string kosong yang
        // akan tampil sebagai kartu tanpa nama.
        $author = trim((string) ($validated['author'] ?? ''));
        $validated['author'] = $author === '' ? null : $author;

        // Checkbox yang tidak dicentang tidak mengirim nilai sama sekali, jadi
        // harus selalu ditulis eksplisit — kalau tidak, centang lama ikut hilang
        // setiap kali post disimpan.
        $validated['is_featured'] = $request->boolean('is_featured');

        return $validated;
    }
}
