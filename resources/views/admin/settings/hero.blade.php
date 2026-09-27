@extends('admin.layouts.app')

@section('title', 'Hero Settings - SIGMA Admin')

@section('content')
    <h1 class="page-title">Hero Settings</h1>
    <p class="page-subtitle">Manage the content displayed in the landing page hero section</p>

    @if($errors->any())
        <div class="f-error">
            <ul class="f-error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.hero.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="card mb-lg">
            <div class="stat-label f-subhead">Hero Content</div>

            <div class="mb-md">
                <label class="f-label">Title</label>
                <input type="text" name="hero_title" value="{{ old('hero_title', $contents['hero_title']->value ?? 'SIGMA') }}" maxlength="100" required class="f-input">
            </div>

            <div class="mb-md">
                <label class="f-label">Tagline</label>
                <input type="text" name="hero_tagline" value="{{ old('hero_tagline', $contents['hero_tagline']->value ?? '') }}" maxlength="200" class="f-input">
            </div>

            <div class="mb-md">
                <label class="f-label">Description</label>
                <textarea name="hero_description" rows="4" maxlength="1000" placeholder="Describe the organization..." style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; resize: vertical;">{{ old('hero_description', $contents['hero_description']->value ?? '') }}</textarea>
            </div>

            <div class="mb-md">
                <label class="f-label">Button Label</label>
                <input type="text" name="hero_cta_label" value="{{ old('hero_cta_label', $contents['hero_cta_label']->value ?? 'Pelajari Lebih') }}" maxlength="50" class="f-input">
            </div>

            <div>
                <label class="f-label">Background Image</label>
                @if($contents['hero_background_image']->value ?? null)
                    <div style="width: 100%; max-width: 420px; height: 180px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; margin-bottom: 0.75rem;">
                        <img src="{{ $contents['hero_background_image']->value }}" alt="Current hero background" class="f-cover">
                    </div>
                @endif
                <input type="file" name="hero_background_image" accept="image/jpeg,image/png,image/webp" class="f-input">
                <p style="margin: 0.5rem 0 0; color: rgba(255,255,255,0.45); font-size: 0.75rem;">Format JPG, PNG, atau WebP. Maksimal 5 MB.</p>
            </div>
        </div>

        <button type="submit" class="btn-save" ><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Save Hero Settings</span></button>
    </form>
@endsection
