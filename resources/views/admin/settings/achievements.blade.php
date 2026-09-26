@extends('admin.layouts.app')

@section('title', 'Achievements Settings - SIGMA Admin')

@section('content')
    <h1 class="page-title">Achievements Settings</h1>
    <p class="page-subtitle">Tambah, edit, dan hapus achievement yang tampil di landing page.</p>

    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #f87171; color: #fca5a5; padding: 0.875rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.875rem;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="stat-label" style="margin-bottom: 1rem; font-size: 1rem;">Section Header</div>
        <form method="POST" action="{{ route('admin.settings.achievements.section') }}">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Section Title</label>
                    <input type="text" name="achievement_section_title" value="{{ old('achievement_section_title', $contents['achievement_section_title']->value ?? '') }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Section Subtitle</label>
                    <input type="text" name="achievement_section_subtitle" value="{{ old('achievement_section_subtitle', $contents['achievement_section_subtitle']->value ?? '') }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
            </div>
            <button type="submit" style="padding: 0.65rem 1.25rem; background: #1e40af; color: #fff; border: 0; border-radius: 8px; cursor: pointer;">Save Section Header</button>
        </form>
    </div>

    @php
        $iconOptions = ['🏆', '🥈', '🥉', '🎖️', '🌟', '📜', '🚀', '💡', '🏅', '✅'];
    @endphp

    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="stat-label" style="margin-bottom: 0.35rem; font-size: 1rem;">Tambah Achievement</div>
        <p style="color: rgba(255,255,255,0.55); font-size: 0.8125rem; margin: 0 0 1.25rem;">Achievement baru langsung tampil di landing page.</p>
        <form method="POST" action="{{ route('admin.settings.achievements.store') }}">
            @csrf
            <div style="display: grid; grid-template-columns: 60px 1fr 140px; gap: 0.75rem; align-items: start;">
                <div>
                    <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Icon</label>
                    <select name="icon" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem;">
                        @foreach($iconOptions as $icon)
                            <option value="{{ $icon }}" {{ old('icon') === $icon ? 'selected' : '' }}>{{ $icon }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required maxlength="200" placeholder="Juara 1 - ensuing Innovation Challenge" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Year</label>
                    <input type="text" name="year" value="{{ old('year') }}" maxlength="20" placeholder="2025" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem;">
                </div>
                <div style="grid-column: 1 / -1;">
                    <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Description</label>
                    <textarea name="description" rows="2" maxlength="1000" placeholder="Achievement description..." style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem; resize: vertical;">{{ old('description') }}</textarea>
                </div>
            </div>
            <button type="submit" style="margin-top: 1rem; padding: 0.7rem 1.5rem; background: #15803d; color: #fff; border: 0; border-radius: 8px; cursor: pointer;">Add Achievement</button>
        </form>
    </div>

    <h2 style="font-size: 1.125rem; color: #fff; margin-bottom: 1rem;">Achievement List ({{ $achievements->count() }})</h2>
    @forelse($achievements as $achievement)
        <div class="card" style="margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="font-size: 1rem; font-weight: 700; color: #fff;">{{ $achievement->icon }} {{ $achievement->title }}</div>
                <form method="POST" action="{{ route('admin.settings.achievements.destroy', $achievement) }}" onsubmit="return confirm('Hapus achievement ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding: 0.45rem 0.8rem; background: rgba(239, 68, 68, 0.15); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 6px; cursor: pointer;">Hapus</button>
                </form>
            </div>
            <form method="POST" action="{{ route('admin.settings.achievements.update', $achievement) }}">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: 60px 1fr 140px; gap: 0.75rem; align-items: start;">
                    <div>
                        <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Icon</label>
                        <select name="icon" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem;">
                            @foreach($iconOptions as $icon)
                                <option value="{{ $icon }}" {{ old('icon', $achievement->icon) === $icon ? 'selected' : '' }}>{{ $icon }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Title</label>
                        <input type="text" name="title" value="{{ old('title', $achievement->title) }}" required maxlength="200" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Year</label>
                        <input type="text" name="year" value="{{ old('year', $achievement->year) }}" maxlength="20" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem;">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Description</label>
                        <textarea name="description" rows="2" maxlength="1000" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem; resize: vertical;">{{ old('description', $achievement->description) }}</textarea>
                    </div>
                </div>
                <button type="submit" style="margin-top: 1rem; padding: 0.6rem 1.25rem; background: #1d4ed8; color: #fff; border: 0; border-radius: 8px; cursor: pointer;">Save Changes</button>
            </form>
        </div>
    @empty
        <div class="card" style="color: rgba(255,255,255,0.6);">Belum ada achievement. Tambahkan achievement pertama melalui form di atas.</div>
    @endforelse
@endsection
