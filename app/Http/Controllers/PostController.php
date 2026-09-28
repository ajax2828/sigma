<?php

namespace App\Http\Controllers;

use App\Models\LandingContent;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        Post::create($validated + ['user_id' => auth()->id()]);

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

        $post->update($validated);

        return redirect()->route('admin.posts.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post deleted successfully.');
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
            'content' => 'required|string',
            'link' => 'nullable|string|max:2048',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['link'] = blank($validated['link'] ?? null) ? null : trim($validated['link']);

        // Checkbox yang tidak dicentang tidak mengirim nilai sama sekali, jadi
        // harus selalu ditulis eksplisit — kalau tidak, centang lama ikut hilang
        // setiap kali post disimpan.
        $validated['is_featured'] = $request->boolean('is_featured');

        return $validated;
    }
}
