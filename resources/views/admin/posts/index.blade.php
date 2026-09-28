@extends('admin.layouts.app')

@section('title', 'Posts - SIGMA Admin')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Posts <span class="badge-count">{{ $posts->total() }}</span></h1>
            <p class="page-subtitle">Kelola artikel dan publikasi organisasi.</p>
        </div>
        <div class="page-actions">
            <a href="{{ route('landing') }}" class="btn btn-secondary" target="_blank" rel="noopener">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="width: 15px; height: 15px;"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><path d="M15 3h6v6"/><path d="M10 14 21 3"/></svg>
                <span>Lihat Landing Page</span>
            </a>
            <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ New Post</a>
        </div>
    </div>

    <div class="card flush">
        <form method="GET" action="{{ route('admin.posts.index') }}" class="toolbar">
            <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul atau isi post..." aria-label="Cari post">
            <select name="status" onchange="this.form.submit()" aria-label="Filter status">
                <option value="">Semua status</option>
                @foreach(['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $value => $label)
                    <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary">Cari</button>
            @if(request()->hasAny(['q', 'status']))
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Reset</a>
            @endif
        </form>

        @if ($posts->count())
            <table class="table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Author</th>
                        <th>Created</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>
                                <a href="{{ route('admin.posts.show', $post) }}" class="post-title">{{ $post->title }}</a>
                                <div class="post-excerpt">{{ Str::limit(strip_tags($post->content), 70) }}</div>
                            </td>
                            <td><span class="badge badge-{{ $post->status }}">{{ ucfirst($post->status) }}</span></td>
                            <td>{{ $post->user->name }}</td>
                            <td>{{ $post->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Hapus post &quot;{{ Str::limit($post->title, 30) }}&quot;? Tindakan ini tidak bisa dibatalkan.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding: 1.25rem; border-top: 1px solid rgba(255,255,255,0.08);">
                {{ $posts->links() }}
            </div>
        @else
            <div class="empty-state">
                @if(request()->hasAny(['q', 'status']))
                    <p>Tidak ada post yang cocok dengan filter ini.</p>
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Reset Filter</a>
                @else
                    <p>Belum ada post. Buat post pertama Anda untuk memulai.</p>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Create Post</a>
                @endif
            </div>
        @endif
    </div>
@endsection
