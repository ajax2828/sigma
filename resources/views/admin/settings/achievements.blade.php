@extends('admin.layouts.app')

@section('title', 'Achievements Settings - SIGMA Admin')

@section('content')
    <h1 class="page-title">Achievements Settings</h1>
    <p class="page-subtitle">Tambah, edit, dan hapus achievement yang tampil di landing page.</p>

    @if($errors->any())
        <div class="f-error">
            <ul class="f-error-list">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-lg">
        <div class="stat-label f-subhead">Section Header</div>
        <form method="POST" action="{{ route('admin.settings.achievements.section') }}">
            @csrf
            <div class="f-grid-2">
                <div>
                    <label class="f-label">Section Title</label>
                    <input type="text" name="achievement_section_title" value="{{ old('achievement_section_title', $contents['achievement_section_title']->value ?? '') }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Section Subtitle</label>
                    <input type="text" name="achievement_section_subtitle" value="{{ old('achievement_section_subtitle', $contents['achievement_section_subtitle']->value ?? '') }}" class="f-input">
                </div>
            </div>
            <button type="submit" class="btn-save"><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Save Section Header</span></button>
        </form>
    </div>

    @php
        $iconOptions = ['🏆', '🥈', '🥉', '🎖️', '🌟', '📜', '🚀', '💡', '🏅', '✅'];
    @endphp

    <div class="card mb-lg">
        <div class="stat-label f-subhead">Tambah Achievement</div>
        <p class="f-hint">Achievement baru langsung tampil di landing page.</p>
        <form method="POST" action="{{ route('admin.settings.achievements.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="f-grid-3">
                <div>
                    <label class="f-label f-label-sm">Icon</label>
                    <select name="icon" class="f-input f-input-sm">
                        @foreach($iconOptions as $icon)
                            <option value="{{ $icon }}" {{ old('icon') === $icon ? 'selected' : '' }}>{{ $icon }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="f-label f-label-sm">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required maxlength="200" placeholder="Juara 1 - ensuing Innovation Challenge" class="f-input f-input-sm">
                </div>
                <div>
                    <label class="f-label f-label-sm">Year</label>
                    <input type="text" name="year" value="{{ old('year') }}" maxlength="20" placeholder="2025" class="f-input f-input-sm">
                </div>
                <div class="f-span-all">
                    <label class="f-label f-label-sm">Image</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="f-input f-file">
                    <p class="f-hint f-hint-sm">JPG/PNG/WEBP, maks 5MB. Tampil di atas kartu prestasi.</p>
                </div>
                <div class="f-span-all">
                    <label class="f-label f-label-sm">Description</label>
                    <textarea name="description" rows="2" maxlength="1000" placeholder="Achievement description..." style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem; resize: vertical;">{{ old('description') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn-save btn-save-success"><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Add Achievement</span></button>
        </form>
    </div>

    <h2 class="f-h2">Achievement List ({{ $achievements->count() }})</h2>
    @forelse($achievements as $achievement)
        <div class="card mb-md">
            <div class="f-row">
                <div class="f-strong">{{ $achievement->icon }} {{ $achievement->title }}</div>
                <form method="POST" action="{{ route('admin.settings.achievements.destroy', $achievement) }}" onsubmit="return confirm('Hapus achievement ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="f-submit f-submit-danger">Hapus</button>
                </form>
            </div>
            <form method="POST" action="{{ route('admin.settings.achievements.update', $achievement) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="f-grid-3">
                    <div>
                        <label class="f-label f-label-sm">Icon</label>
                        <select name="icon" class="f-input f-input-sm">
                            @foreach($iconOptions as $icon)
                                <option value="{{ $icon }}" {{ old('icon', $achievement->icon) === $icon ? 'selected' : '' }}>{{ $icon }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="f-label f-label-sm">Title</label>
                        <input type="text" name="title" value="{{ old('title', $achievement->title) }}" required maxlength="200" class="f-input f-input-sm">
                    </div>
                    <div>
                        <label class="f-label f-label-sm">Year</label>
                        <input type="text" name="year" value="{{ old('year', $achievement->year) }}" maxlength="20" class="f-input f-input-sm">
                    </div>
                    <div class="f-span-all">
                        <label class="f-label f-label-sm">Image</label>
                        @if($achievement->image)
                            <img src="{{ $achievement->image }}" alt="{{ $achievement->title }}" style="display: block; width: 160px; height: 100px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255,255,255,0.15); margin-bottom: 0.5rem;">
                        @else
                            <p style="color: rgba(255,255,255,0.4); font-size: 0.6875rem; margin: 0 0 0.5rem;">Belum ada gambar. Kartu akan pakai ikon.</p>
                        @endif
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="f-input f-file">
                    </div>
                    <div class="f-span-all">
                        <label class="f-label f-label-sm">Description</label>
                        <textarea name="description" rows="2" maxlength="1000" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem; resize: vertical;">{{ old('description', $achievement->description) }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn-save"><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Save Changes</span></button>
            </form>
        </div>
    @empty
        <div class="card f-muted">Belum ada achievement. Tambahkan achievement pertama melalui form di atas.</div>
    @endforelse
@endsection
