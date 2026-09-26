@extends('admin.layouts.app')

@section('title', 'Posts - SIGMA Admin')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Posts <span class="badge-count">{{ $posts->total() }}</span></h1>
            <p class="page-subtitle">Manage your content and publications</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">+ New Post</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        @if ($posts->count())
            <table>
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Status</th>
                        <th>Author</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>
                                <a href="{{ route('admin.posts.show', $post) }}" style="color:#1e293b;text-decoration:none;font-weight:600;">
                                    {{ Str::limit($post->title, 50) }}
                                </a>
                            </td>
                            <td>
                                <span class="badge badge-{{ $post->status }}">{{ ucfirst($post->status) }}</span>
                            </td>
                            <td>{{ $post->user->name }}</td>
                            <td>{{ $post->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-secondary">View</a>
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">Edit</a>
                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="padding:1.25rem;border-top:1px solid #f1f5f9;">
                {{ $posts->links() }}
            </div>
        @else
            <div class="empty-state">
                <p>No posts yet. Create your first post to get started.</p>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">Create Post</a>
            </div>
        @endif
    </div>
@endsection
