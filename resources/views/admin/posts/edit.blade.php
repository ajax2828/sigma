@extends('admin.layouts.app')

@section('title', 'Edit Post - SIGMA Admin')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Post</h1>
            <p class="page-subtitle">Perbarui artikel atau publikasi.</p>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancel</a>
    </div>

    <div class="card">
        <form action="{{ route('admin.posts.update', $post) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" placeholder="Enter post title..." maxlength="255" required>
                <p class="field-hint">Maksimal 255 karakter.</p>
                @error('title')<div class="alert alert-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="author">Author</label>
                <input type="text" id="author" name="author" value="{{ old('author', $post->author) }}" placeholder="Nama penulis artikel" maxlength="100">
                <p class="field-hint">Ditampilkan di kartu Kabar Terbaru. Kosongkan untuk memakai nama pembuat post.</p>
                @error('author')<div class="alert alert-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <textarea id="content" name="content" placeholder="Write your content here..." required>{{ old('content', $post->content) }}</textarea>
                @error('content')<div class="alert alert-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="link">Link Blog</label>
                <input type="text" id="link" name="link" value="{{ old('link', $post->link) }}" placeholder="https://blog.sigma.id/artikel" maxlength="2048">
                <p class="field-hint">Jika diisi, klik &quot;Baca&quot; di Kabar Terbaru langsung menuju link ini. Kosongkan untuk memakai halaman detail di situs.</p>
                @error('link')<div class="alert alert-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft</option>
                    <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
                    <option value="archived" @selected(old('status', $post->status) === 'archived')>Archived</option>
                </select>
                @error('status')<div class="alert alert-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label for="is_featured">Tampilkan di Banner</label>
                <label class="checkbox-row"><input type="checkbox" id="is_featured" name="is_featured" value="1" @checked(old('is_featured', $post->is_featured))> Masukkan banner hero yang berganti otomatis</label>
                <p class="field-hint">Kalau ada post yang dicentang, banner hero memakai post-post itu. Kalau tidak ada, banner memakai 5 kabar terbaru.</p>
                @error('is_featured')<div class="alert alert-danger">{{ $message }}</div>@enderror
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-save"><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Update Post</span></button>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
