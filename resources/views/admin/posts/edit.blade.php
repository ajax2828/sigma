@extends('admin.layouts.app')

@section('title', 'Edit Post - SIGMA Admin')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Post</h1>
            <p class="page-subtitle">Update your article or publication</p>
        </div>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancel</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.posts.update', $post) }}" method="POST">
                @csrf @method('PUT')
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title', $post->title) }}" placeholder="Enter post title..." required>
                    @error('title')<div class="alert alert-danger" style="margin-top:0.75rem;">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="content">Content</label>
                    <textarea id="content" name="content" placeholder="Write your content here..." required>{{ old('content', $post->content) }}</textarea>
                    @error('content')<div class="alert alert-danger" style="margin-top:0.75rem;">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" required>
                        <option value="draft" @selected(old('status', $post->status) === 'draft')>Draft</option>
                        <option value="published" @selected(old('status', $post->status) === 'published')>Published</option>
                        <option value="archived" @selected(old('status', $post->status) === 'archived')>Archived</option>
                    </select>
                    @error('status')<div class="alert alert-danger" style="margin-top:0.75rem;">{{ $message }}</div>@enderror
                </div>
                <div style="display:flex;gap:0.75rem;">
                    <button type="submit" class="btn btn-primary">Update Post</button>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
@endsection
