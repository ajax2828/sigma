@extends('admin.layouts.app')

@section('title', $post->title . ' - SIGMA Admin')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">{{ $post->title }}</h1>
            <p class="page-subtitle">
                By {{ $post->user->name }} · {{ $post->created_at->format('M d, Y \a\t h:i A') }}
                · <span class="badge badge-{{ $post->status }}">{{ ucfirst($post->status) }}</span>
            </p>
        </div>
        <div class="form-actions">
            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Hapus post &quot;{{ Str::limit($post->title, 30) }}&quot;? Tindakan ini tidak bisa dibatalkan.');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Hapus</button>
            </form>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="post-body">{{ $post->content }}</div>
    </div>
@endsection
