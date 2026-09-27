@extends('admin.layouts.app')

@section('title', 'Landing Page Settings - SIGMA Admin')

@section('content')
    <h1 class="page-title">Landing Page Settings</h1>
    <p class="page-subtitle">Manage your public landing page content</p>

    <form method="POST" action="{{ route('admin.settings.landing.update') }}">
        @csrf

        <div class="card mb-lg">
            <div class="stat-label f-subhead">Site Identity &amp; Navigation</div>
            <div class="f-grid-2">
                <div>
                    <label class="f-label">Site Title</label>
                    <input type="text" name="site_title" value="{{ $contents['site_title']->value ?? 'SIGMA - Sistem Informasi Terpadu' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Navigation Brand</label>
                    <input type="text" name="nav_brand" value="{{ $contents['nav_brand']->value ?? 'SIGMA' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">About Menu Label</label>
                    <input type="text" name="nav_about_label" value="{{ $contents['nav_about_label']->value ?? 'About' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Members Menu Label</label>
                    <input type="text" name="nav_members_label" value="{{ $contents['nav_members_label']->value ?? 'Anggota' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Achievements Menu Label</label>
                    <input type="text" name="nav_achievements_label" value="{{ $contents['nav_achievements_label']->value ?? 'Prestasi' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Login Menu Label</label>
                    <input type="text" name="nav_login_label" value="{{ $contents['nav_login_label']->value ?? 'Login' }}" class="f-input">
                </div>
            </div>
            <div class="mt-md">
                <label class="f-label">Footer Text</label>
                <input type="text" name="footer_text" value="{{ $contents['footer_text']->value ?? 'Organisasi SIGMA. All rights reserved.' }}" class="f-input">
            </div>
        </div>

        <!-- About Section -->
        <div class="card mb-lg">
            <div class="stat-label f-subhead">About Section</div>
            <div class="f-grid-2">
                <div>
                    <label class="f-label">Section Title</label>
                    <input type="text" name="about_title" value="{{ $contents['about_title']->value ?? 'Tentang Kami' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Organization Title</label>
                    <input type="text" name="about_organization_title" value="{{ $contents['about_organization_title']->value ?? 'Organisasi SIGMA' }}" class="f-input">
                </div>
            </div>
            <div class="mb-md">
                <label class="f-label">Section Subtitle</label>
                <input type="text" name="about_subtitle" value="{{ $contents['about_subtitle']->value ?? '' }}" class="f-input">
            </div>
            <div class="f-grid-2">
                <div>
                    <label class="f-label">First Slide Title</label>
                    <input type="text" name="about_slide_title" value="{{ $contents['about_slide_title']->value ?? 'Tentang Kami' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Second Slide Title</label>
                    <input type="text" name="vision_mission_title" value="{{ $contents['vision_mission_title']->value ?? 'Visi & Misi' }}" class="f-input">
                </div>
            </div>
            <div class="mb-md">
                <label class="f-label">Description</label>
                <textarea name="about_description" rows="4" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; resize: vertical;">{{ $contents['about_description']->value ?? '' }}</textarea>
            </div>
            <div class="f-grid-2">
                <div>
                    <label class="f-label">Vision</label>
                    <textarea name="vision" rows="3" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; resize: vertical;">{{ $contents['vision']->value ?? '' }}</textarea>
                </div>
                <div>
                    <label class="f-label">Mission</label>
                    <textarea name="mission" rows="3" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem; resize: vertical;">{{ $contents['mission']->value ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="card mb-lg">
            <div class="stat-label f-subhead">Statistics</div>
            <div class="f-grid-5">
                <div>
                    <label class="f-label">Active Members</label>
                    <input type="text" name="stat_active_members" value="{{ $contents['stat_active_members']->value ?? '' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Projects</label>
                    <input type="text" name="stat_projects" value="{{ $contents['stat_projects']->value ?? '' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Awards</label>
                    <input type="text" name="stat_awards" value="{{ $contents['stat_awards']->value ?? '' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Years</label>
                    <input type="text" name="stat_years" value="{{ $contents['stat_years']->value ?? '' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Partnerships</label>
                    <input type="text" name="stat_partnerships" value="{{ $contents['stat_partnerships']->value ?? '15+' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Dedication</label>
                    <input type="text" name="stat_dedication" value="{{ $contents['stat_dedication']->value ?? '100%' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Active Members Label</label>
                    <input type="text" name="stat_active_members_label" value="{{ $contents['stat_active_members_label']->value ?? 'Anggota Aktif' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Projects Label</label>
                    <input type="text" name="stat_projects_label" value="{{ $contents['stat_projects_label']->value ?? 'Proyek Selesai' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Awards Label</label>
                    <input type="text" name="stat_awards_label" value="{{ $contents['stat_awards_label']->value ?? 'Penghargaan' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Years Label</label>
                    <input type="text" name="stat_years_label" value="{{ $contents['stat_years_label']->value ?? 'Tahun Berdiri' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Partnerships Label</label>
                    <input type="text" name="stat_partnerships_label" value="{{ $contents['stat_partnerships_label']->value ?? 'Kerjasama' }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Dedication Label</label>
                    <input type="text" name="stat_dedication_label" value="{{ $contents['stat_dedication_label']->value ?? 'Dedikasi' }}" class="f-input">
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save" ><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Save Settings</span></button>
    </form>
@endsection
