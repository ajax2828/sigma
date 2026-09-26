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
        <div style="display:flex;gap:0.75rem;">
            <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-primary">Edit</a>
            <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?');">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">Back</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div style="line-height:1.8;color:#374151;font-size:1.0625rem;max-width:720px;">{{ $post->content }}</div>
        </div>
    </div>
@endsection
