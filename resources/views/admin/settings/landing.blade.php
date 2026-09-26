@extends('admin.layouts.app')

@section('title', 'Landing Page Settings - SIGMA Admin')

@section('content')
    <h1 class="page-title">Landing Page Settings</h1>
    <p class="page-subtitle">Manage your public landing page content</p>

    <form method="POST" action="{{ route('admin.settings.landing.update') }}" enctype="multipart/form-data">
        @csrf

        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="stat-label" style="margin-bottom: 1rem; font-size: 1rem;">Site Identity &amp; Navigation</div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Site Title</label>
                    <input type="text" name="site_title" value="{{ $contents['site_title']->value ?? 'SIGMA - Sistem Informasi Terpadu' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Navigation Brand</label>
                    <input type="text" name="nav_brand" value="{{ $contents['nav_brand']->value ?? 'SIGMA' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">About Menu Label</label>
                    <input type="text" name="nav_about_label" value="{{ $contents['nav_about_label']->value ?? 'About' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Members Menu Label</label>
                    <input type="text" name="nav_members_label" value="{{ $contents['nav_members_label']->value ?? 'Anggota' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Achievements Menu Label</label>
                    <input type="text" name="nav_achievements_label" value="{{ $contents['nav_achievements_label']->value ?? 'Prestasi' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Login Menu Label</label>
                    <input type="text" name="nav_login_label" value="{{ $contents['nav_login_label']->value ?? 'Login' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
            </div>
            <div style="margin-top: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Footer Text</label>
                <input type="text" name="footer_text" value="{{ $contents['footer_text']->value ?? 'Organisasi SIGMA. All rights reserved.' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
            </div>
        </div>

        <!-- Hero Section -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="stat-label" style="margin-bottom: 1rem; font-size: 1rem;">Hero Section</div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Title</label>
                    <input type="text" name="hero_title" value="{{ $contents['hero_title']->value ?? '' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Tagline</label>
                    <input type="text" name="hero_tagline" value="{{ $contents['hero_tagline']->value ?? '' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
            </div>
            <div style="margin-top: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Description</label>
                <textarea name="hero_description" rows="3" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; resize: vertical;">{{ $contents['hero_description']->value ?? '' }}</textarea>
            </div>
            <div style="margin-top: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Button Label</label>
                <input type="text" name="hero_cta_label" value="{{ $contents['hero_cta_label']->value ?? 'Pelajari Lebih' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
            </div>
            <div style="margin-top: 1rem;">
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

        <!-- About Section -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="stat-label" style="margin-bottom: 1rem; font-size: 1rem;">About Section</div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Section Title</label>
                    <input type="text" name="about_title" value="{{ $contents['about_title']->value ?? 'Tentang Kami' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Organization Title</label>
                    <input type="text" name="about_organization_title" value="{{ $contents['about_organization_title']->value ?? 'Organisasi SIGMA' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Section Subtitle</label>
                <input type="text" name="about_subtitle" value="{{ $contents['about_subtitle']->value ?? '' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
            </div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">First Slide Title</label>
                    <input type="text" name="about_slide_title" value="{{ $contents['about_slide_title']->value ?? 'Tentang Kami' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Second Slide Title</label>
                    <input type="text" name="vision_mission_title" value="{{ $contents['vision_mission_title']->value ?? 'Visi & Misi' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Description</label>
                <textarea name="about_description" rows="4" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; resize: vertical;">{{ $contents['about_description']->value ?? '' }}</textarea>
            </div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Vision</label>
                    <textarea name="vision" rows="3" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; resize: vertical;">{{ $contents['vision']->value ?? '' }}</textarea>
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Mission</label>
                    <textarea name="mission" rows="3" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; resize: vertical;">{{ $contents['mission']->value ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="stat-label" style="margin-bottom: 1rem; font-size: 1rem;">Statistics</div>
            <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Active Members</label>
                    <input type="text" name="stat_active_members" value="{{ $contents['stat_active_members']->value ?? '' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Projects</label>
                    <input type="text" name="stat_projects" value="{{ $contents['stat_projects']->value ?? '' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Awards</label>
                    <input type="text" name="stat_awards" value="{{ $contents['stat_awards']->value ?? '' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Years</label>
                    <input type="text" name="stat_years" value="{{ $contents['stat_years']->value ?? '' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Partnerships</label>
                    <input type="text" name="stat_partnerships" value="{{ $contents['stat_partnerships']->value ?? '15+' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Dedication</label>
                    <input type="text" name="stat_dedication" value="{{ $contents['stat_dedication']->value ?? '100%' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Active Members Label</label>
                    <input type="text" name="stat_active_members_label" value="{{ $contents['stat_active_members_label']->value ?? 'Anggota Aktif' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Projects Label</label>
                    <input type="text" name="stat_projects_label" value="{{ $contents['stat_projects_label']->value ?? 'Proyek Selesai' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Awards Label</label>
                    <input type="text" name="stat_awards_label" value="{{ $contents['stat_awards_label']->value ?? 'Penghargaan' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Years Label</label>
                    <input type="text" name="stat_years_label" value="{{ $contents['stat_years_label']->value ?? 'Tahun Berdiri' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Partnerships Label</label>
                    <input type="text" name="stat_partnerships_label" value="{{ $contents['stat_partnerships_label']->value ?? 'Kerjasama' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Dedication Label</label>
                    <input type="text" name="stat_dedication_label" value="{{ $contents['stat_dedication_label']->value ?? 'Dedikasi' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
            </div>
        </div>

        <!-- Achievements Section -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="stat-label" style="margin-bottom: 1rem; font-size: 1rem;">Achievements Section</div>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Section Title</label>
                    <input type="text" name="achievement_section_title" value="{{ $contents['achievement_section_title']->value ?? '' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Section Subtitle</label>
                    <input type="text" name="achievement_section_subtitle" value="{{ $contents['achievement_section_subtitle']->value ?? '' }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
            </div>
        </div>

        <button type="submit" class="btn-login" style="border: none; padding: 0.75rem 2rem; font-size: 0.875rem; border-radius: 8px; cursor: pointer;">Save Settings</button>
    </form>
@endsection
