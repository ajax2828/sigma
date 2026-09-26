@extends('admin.layouts.app')

@section('title', 'Hero Settings - SIGMA Admin')

@section('content')
    <h1 class="page-title">Hero Settings</h1>
    <p class="page-subtitle">Manage the content displayed in the landing page hero section</p>

    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #f87171; color: #fca5a5; padding: 0.875rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.875rem;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.hero.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="stat-label" style="margin-bottom: 1rem; font-size: 1rem;">Hero Content</div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Title</label>
                <input type="text" name="hero_title" value="{{ old('hero_title', $contents['hero_title']->value ?? 'SIGMA') }}" maxlength="100" required style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Tagline</label>
                <input type="text" name="hero_tagline" value="{{ old('hero_tagline', $contents['hero_tagline']->value ?? '') }}" maxlength="200" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Description</label>
                <textarea name="hero_description" rows="4" maxlength="1000" placeholder="Describe the organization..." style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; resize: vertical;">{{ old('hero_description', $contents['hero_description']->value ?? '') }}</textarea>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Button Label</label>
                <input type="text" name="hero_cta_label" value="{{ old('hero_cta_label', $contents['hero_cta_label']->value ?? 'Pelajari Lebih') }}" maxlength="50" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
            </div>

            <div>
                <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Background Image</label>
                @if($contents['hero_background_image']->value ?? null)
                    <div style="width: 100%; max-width: 420px; height: 180px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; margin-bottom: 0.75rem;">
                        <img src="{{ $contents['hero_background_image']->value }}" alt="Current hero background" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                @endif
                <input type="file" name="hero_background_image" accept="image/jpeg,image/png,image/webp" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                <p style="margin: 0.5rem 0 0; color: rgba(255,255,255,0.45); font-size: 0.75rem;">Format JPG, PNG, atau WebP. Maksimal 5 MB.</p>
            </div>
        </div>

        <button type="submit" class="btn-login" style="border: none; padding: 0.75rem 2rem; font-size: 0.875rem; border-radius: 8px; cursor: pointer;">Save Hero Settings</button>
    </form>
@endsection
