@extends('admin.layouts.app')

@section('title', 'Background Settings - SIGMA Admin')

@section('content')
    <h1 class="page-title">Background Settings</h1>
    <p class="page-subtitle">Atur gambar dan gradient untuk seluruh halaman atau setiap section landing page.</p>

    @if($errors->any())
        <div style="background: rgba(239, 68, 68, 0.15); border: 1px solid #f87171; color: #fca5a5; padding: 0.875rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.875rem;">
            <ul style="margin: 0; padding-left: 1.25rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $activeSection = $activeSection ?? 'hero';
        $sections = [
            [
                'label' => 'Full Page',
                'description' => 'Background untuk body seluruh landing page.',
                'image' => 'page_background_image',
                'start' => 'page_gradient_start',
                'end' => 'page_gradient_end',
                'angle' => 'page_gradient_angle',
                'default_start' => '#f4f0e6',
                'default_end' => '#e8e0d1',
                'default_angle' => '180',
            ],
            [
                'label' => 'Hero',
                'description' => 'Background untuk bagian hero.',
                'image' => 'hero_background_image',
                'start' => 'hero_gradient_start',
                'end' => 'hero_gradient_end',
                'angle' => 'hero_gradient_angle',
                'default_start' => '#f4f0e6',
                'default_end' => '#faf7ef',
                'default_angle' => '90',
            ],
            [
                'label' => 'About',
                'description' => 'Background untuk section Tentang Kami.',
                'image' => 'about_background_image',
                'start' => 'about_gradient_start',
                'end' => 'about_gradient_end',
                'angle' => 'about_gradient_angle',
                'default_start' => '#fffaf0',
                'default_end' => '#f4f0e6',
                'default_angle' => '180',
            ],
            [
                'label' => 'Members',
                'description' => 'Background untuk section Anggota.',
                'image' => 'members_background_image',
                'start' => 'members_gradient_start',
                'end' => 'members_gradient_end',
                'angle' => 'members_gradient_angle',
                'default_start' => '#e8e0d1',
                'default_end' => '#f8f5ed',
                'default_angle' => '180',
            ],
            [
                'label' => 'Achievements',
                'description' => 'Background untuk section Prestasi.',
                'image' => 'achievements_background_image',
                'start' => 'achievements_gradient_start',
                'end' => 'achievements_gradient_end',
                'angle' => 'achievements_gradient_angle',
                'default_start' => '#f8f5ed',
                'default_end' => '#f4f0e6',
                'default_angle' => '180',
            ],
            [
                'label' => 'Footer',
                'description' => 'Background untuk footer landing page.',
                'image' => 'footer_background_image',
                'start' => 'footer_gradient_start',
                'end' => 'footer_gradient_end',
                'angle' => 'footer_gradient_angle',
                'default_start' => '#e8e0d1',
                'default_end' => '#f4f0e6',
                'default_angle' => '90',
            ],
        ];
    @endphp

    <form method="POST" action="{{ route('admin.settings.backgrounds.update') }}" enctype="multipart/form-data">
        @csrf

        @foreach($sections as $section)
            @php
                $currentImage = $contents[$section['image']]->value ?? null;
                $start = old($section['start'], $contents[$section['start']]->value ?? $section['default_start']);
                $end = old($section['end'], $contents[$section['end']]->value ?? $section['default_end']);
                $angle = old($section['angle'], $contents[$section['angle']]->value ?? $section['default_angle']);
            @endphp

            <div class="card" style="margin-bottom: 1.5rem;">
                <div class="stat-label" style="margin-bottom: 0.35rem; font-size: 1rem; color: #fff;">{{ $section['label'] }} Background</div>
                <p style="color: rgba(255,255,255,0.55); font-size: 0.8125rem; margin: 0 0 1.25rem;">{{ $section['description'] }}</p>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; align-items: end;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Gradient Start</label>
                        <input type="color" name="{{ $section['start'] }}" value="{{ $start }}" style="width: 100%; height: 42px; padding: 0.25rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Gradient End</label>
                        <input type="color" name="{{ $section['end'] }}" value="{{ $end }}" style="width: 100%; height: 42px; padding: 0.25rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Gradient Angle</label>
                        <input type="number" name="{{ $section['angle'] }}" value="{{ $angle }}" min="0" max="360" step="1" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                    </div>
                </div>

                <div style="margin-top: 1rem;">
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Background Image</label>
                    @if($currentImage)
                        <div style="width: 100%; max-width: 520px; height: 180px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; margin-bottom: 0.75rem;">
                            <img src="{{ $currentImage }}" alt="Current {{ $section['label'] }} background" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endif
                    <input type="file" name="{{ $section['image'] }}" accept="image/jpeg,image/png,image/webp" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                    <p style="margin: 0.5rem 0 0; color: rgba(255,255,255,0.45); font-size: 0.75rem;">JPG, PNG, atau WebP. Maksimal 5 MB. Kosongkan jika tidak ingin mengganti image.</p>
                </div>
            </div>
        @endforeach

        <button type="submit" class="btn-login" style="border: none; padding: 0.75rem 2rem; font-size: 0.875rem; border-radius: 8px; cursor: pointer;">Save Background Settings</button>
    </form>
@endsection
