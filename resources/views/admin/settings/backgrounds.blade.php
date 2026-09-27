@extends('admin.layouts.app')

@section('title', 'Background Settings - SIGMA Admin')

@section('content')
    <h1 class="page-title">Background Settings</h1>
    <p class="page-subtitle">Atur gambar dan gradient untuk seluruh halaman atau setiap section landing page.</p>

    @if($errors->any())
        <div class="f-error">
            <ul class="f-error-list">
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
                'where' => 'Latar belakang seluruh halaman, di balik semua section (elemen <body>).',
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
                'where' => 'Header paling atas, di belakang judul besar dan tagline (site header).',
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
                'where' => 'Section "Tentang Kami" yang memuat profil, visi & misi, dan statistik.',
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
                'where' => 'Section "Anggota" berisi kartu-kartu member.',
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
                'where' => 'Section "Prestasi" berisi daftar achievement.',
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
                'where' => 'Footer paling bawah halaman.',
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

            <div class="card mb-lg">
                <div class="stat-label f-subhead">{{ $section['label'] }} Background</div>
                <p class="f-where"><strong class="f-white">Dipakai di:</strong> {{ $section['where'] }}</p>

                <div class="f-grid-3 f-gradient-row">
                    <div>
                        <label class="f-label" for="{{ $section['start'] }}">Gradient Start</label>
                        <input type="color" id="{{ $section['start'] }}" name="{{ $section['start'] }}" value="{{ $start }}" class="f-color f-h-md">
                        <p class="f-hint-sm">Warna awal gradient, tampil lebih dulu sesuai arah angle.</p>
                    </div>
                    <div>
                        <label class="f-label" for="{{ $section['end'] }}">Gradient End</label>
                        <input type="color" id="{{ $section['end'] }}" name="{{ $section['end'] }}" value="{{ $end }}" class="f-color f-h-md">
                        <p class="f-hint-sm">Warna akhir gradient. Samakan dengan Start untuk warna solid.</p>
                    </div>
                    <div>
                        <label class="f-label" for="{{ $section['angle'] }}">Gradient Angle</label>
                        <input type="number" id="{{ $section['angle'] }}" name="{{ $section['angle'] }}" value="{{ $angle }}" min="0" max="360" step="1" class="f-input f-h-md">
                        <p class="f-hint-sm">Arah penyinaran (derajat). 0 = bawah ke atas, 90 = kiri ke kanan, 180 = atas ke bawah, 270 = kanan ke kiri.</p>
                    </div>
                </div>

                <div class="f-swatch-row">
                    <span class="f-swatch-label">Pratinjau</span>
                    <span class="f-swatch" data-gradient-preview
                        data-start="{{ $section['start'] }}"
                        data-end="{{ $section['end'] }}"
                        data-angle="{{ $section['angle'] }}"
                        style="background-image: linear-gradient({{ $angle }}deg, {{ $start }}, {{ $end }});"></span>
                </div>

                <label class="f-label f-label-sm" for="{{ $section['image'] }}">Background Image</label>
                <p class="f-hint-sm">Gambar foto di atas gradient untuk area {{ $section['label'] }}. Dibiarkan kosong, section memakai gradient Start &rarr; End.</p>
                @if($currentImage)
                    <div class="f-preview f-preview-wide">
                        <img src="{{ $currentImage }}" alt="Current {{ $section['label'] }} background" class="f-cover">
                    </div>
                    <p class="f-hint-sm">Gambar saat ini sudah dipakai di {{ $section['where'] }}</p>
                @endif
                <input type="file" id="{{ $section['image'] }}" name="{{ $section['image'] }}" accept="image/jpeg,image/png,image/webp" class="f-input f-file f-h-md">
                <p class="f-hint-sm">JPG, PNG, atau WebP. Maksimal 5 MB. File disimpan di storage publik, tidak menimpa file lama. Kosongkan jika tidak ingin mengganti image.</p>
            </div>
        @endforeach

        <button type="submit" class="btn-save" ><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Save Background Settings</span></button>
    </form>

    <script>
        // Pratinjau gradient: delegation di document karena layout mengganti
        // <main> setelah simpan AJAX, jadi listener tidak boleh terikat per-node.
        (function () {
            const HEX = /^#[0-9a-fA-F]{6}$/;

            function paint(preview) {
                const form = preview.closest('form') || document;
                const read = function (name) {
                    const field = form.querySelector('[name="' + name + '"]');
                    return field ? field.value : '';
                };

                const start = read(preview.dataset.start);
                const end = read(preview.dataset.end) || start;
                const angleInput = read(preview.dataset.angle);
                const angle = angleInput === '' ? 180 : parseInt(angleInput, 10);

                if (!HEX.test(start) || !HEX.test(end) || isNaN(angle)) {
                    return;
                }

                preview.style.backgroundImage = 'linear-gradient(' + angle + 'deg, ' + start + ', ' + end + ')';
            }

            document.addEventListener('input', function (event) {
                const target = event.target;
                const name = target instanceof HTMLInputElement ? target.name : '';

                if (name === '') {
                    return;
                }

                const preview = document.querySelector('[data-gradient-preview][data-start="' + name + '"]')
                    || document.querySelector('[data-gradient-preview][data-end="' + name + '"]')
                    || document.querySelector('[data-gradient-preview][data-angle="' + name + '"]');

                if (preview) {
                    paint(preview);
                }
            });
        })();
    </script>
@endsection
