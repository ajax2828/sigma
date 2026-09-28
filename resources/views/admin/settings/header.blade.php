@extends('admin.layouts.app')

@section('title', 'Header - SIGMA Admin')

@section('content')
    <h1 class="page-title">Header</h1>
    <p class="page-subtitle">Kelola slide banner di header landing page. Setiap slide berganti otomatis, dan boleh punya gambarnya sendiri.</p>

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
        <div class="stat-label f-subhead">Tambah Slide</div>
        <p class="f-hint">Slide baru langsung tampil di banner. Kosongkan gambar kalau mau pakai warna background dari halaman Background.</p>
        <form method="POST" action="{{ route('admin.settings.header.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="f-grid-2">
                <div>
                    <label class="f-label f-label-sm">Title</label>
                    <input type="text" name="title" value="{{ old('title') }}" required maxlength="200" placeholder="Judul banner" class="f-input f-input-sm">
                </div>
                <div>
                    <label class="f-label f-label-sm">Label Tombol</label>
                    <input type="text" name="cta_label" value="{{ old('cta_label') }}" maxlength="50" placeholder="Pelajari Lebih" class="f-input f-input-sm">
                    <p class="f-hint f-hint-sm">Hanya muncul kalau Link di bawah diisi.</p>
                </div>
                <div class="f-span-all">
                    <label class="f-label f-label-sm">Deskripsi</label>
                    <textarea name="description" rows="2" maxlength="1000" placeholder="Ringkasan singkat yang tampil di bawah judul" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem; resize: vertical;">{{ old('description') }}</textarea>
                </div>
                <div>
                    <label class="f-label f-label-sm">Link (opsional)</label>
                    <input type="text" name="link" value="{{ old('link') }}" maxlength="2048" placeholder="blog.sigma.id/artikel" class="f-input f-input-sm">
                    <p class="f-hint f-hint-sm">Tanpa skema akan jadi https://. Kalau dikosongkan, slide ini tampil tanpa tombol — label tombol di atas ikut tidak dipakai.</p>
                </div>
                <div>
                    <label class="f-label f-label-sm">Gambar</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="f-input f-file">
                    <p class="f-hint f-hint-sm">JPG/PNG/WEBP, maks 5MB. Dipakai sebagai latar slide ini.</p>
                </div>
                <div class="f-span-all">
                    <label class="checkbox-row">
                        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))>
                        Tampilkan langsung di banner
                    </label>
                </div>
            </div>
            <button type="submit" class="btn-save btn-save-success"><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Add Slide</span></button>
        </form>
    </div>

    <h2 class="f-h2">Slide ({{ $slides->count() }})</h2>
    @forelse($slides as $slide)
        <div class="card mb-md">
            <div class="f-row">
                <div class="f-strong">{{ $slide->title }} @unless($slide->is_active)<span class="f-muted">(nonaktif)</span>@endunless</div>
                <form method="POST" action="{{ route('admin.settings.header.destroy', $slide) }}" onsubmit="return confirm('Hapus slide ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="f-submit f-submit-danger">Hapus</button>
                </form>
            </div>
            <form method="POST" action="{{ route('admin.settings.header.update', $slide) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="f-grid-2">
                    <div>
                        <label class="f-label f-label-sm">Title</label>
                        <input type="text" name="title" value="{{ old('title', $slide->title) }}" required maxlength="200" class="f-input f-input-sm">
                    </div>
                    <div>
                        <label class="f-label f-label-sm">Label Tombol</label>
                        <input type="text" name="cta_label" value="{{ old('cta_label', $slide->cta_label) }}" maxlength="50" placeholder="Pelajari Lebih" class="f-input f-input-sm">
                    </div>
                    <div class="f-span-all">
                        <label class="f-label f-label-sm">Deskripsi</label>
                        <textarea name="description" rows="2" maxlength="1000" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem; resize: vertical;">{{ old('description', $slide->description) }}</textarea>
                    </div>
                    <div>
                        <label class="f-label f-label-sm">Link (opsional)</label>
                        <input type="text" name="link" value="{{ old('link', $slide->link) }}" maxlength="2048" class="f-input f-input-sm">
                    </div>
                    <div>
                        <label class="f-label f-label-sm">Urutan</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', $slide->sort_order) }}" min="0" max="999" class="f-input f-input-sm">
                        <p class="f-hint f-hint-sm">Kecil tampil lebih dulu di banner.</p>
                    </div>
                    <div class="f-span-all">
                        <label class="f-label f-label-sm">Gambar</label>
                        @if($slide->image)
                            <img src="{{ $slide->image }}" alt="{{ $slide->title }}" style="display: block; width: 220px; height: 120px; object-fit: cover; border-radius: 6px; border: 1px solid rgba(255,255,255,0.15); margin-bottom: 0.5rem;">
                        @else
                            <p style="color: rgba(255,255,255,0.4); font-size: 0.6875rem; margin: 0 0 0.5rem;">Belum ada gambar. Slide ini memakai warna background dari halaman Background.</p>
                        @endif
                        <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="f-input f-file">
                        <p class="f-hint f-hint-sm">Unggah file baru hanya kalau ingin mengganti gambar; gambar lama tidak hilang kalau form disimpan tanpa memilih file.</p>
                    </div>
                    <div class="f-span-all">
                        <label class="checkbox-row">
                            <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $slide->is_active))>
                            Tampilkan slide ini di banner
                        </label>
                    </div>
                </div>
                <button type="submit" class="btn-save"><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Save Changes</span></button>
            </form>
        </div>
    @empty
        <div class="card f-muted">Belum ada slide. Banner memakai 5 kabar terbaru sampai slide pertama ditambahkan.</div>
    @endforelse

    <div class="card mb-lg">
        <div class="stat-label f-subhead">Teks Cadangan</div>
        <p class="f-hint">Dipakai hanya kalau tidak ada slide header dan tidak ada kabar published, jadi banner tidak pernah kosong. Warna dan gambar latar diatur di halaman Background.</p>
        <form method="POST" action="{{ route('admin.settings.header.fallback') }}">
            @csrf
            <div class="f-grid-2">
                <div>
                    <label class="f-label f-label-sm">Title</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $contents['hero_title']->value ?? 'SIGMA') }}" maxlength="100" required class="f-input f-input-sm">
                </div>
                <div>
                    <label class="f-label f-label-sm">Tagline</label>
                    <input type="text" name="hero_tagline" value="{{ old('hero_tagline', $contents['hero_tagline']->value ?? '') }}" maxlength="200" class="f-input f-input-sm">
                </div>
                <div>
                    <label class="f-label f-label-sm">Label Tombol</label>
                    <input type="text" name="hero_cta_label" value="{{ old('hero_cta_label', $contents['hero_cta_label']->value ?? 'Pelajari Lebih') }}" maxlength="50" class="f-input f-input-sm">
                </div>
                <div class="f-span-all">
                    <label class="f-label f-label-sm">Description</label>
                    <textarea name="hero_description" rows="3" maxlength="1000" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; font-size: 0.8125rem; resize: vertical;">{{ old('hero_description', $contents['hero_description']->value ?? '') }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn-save"><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Save Fallback Text</span></button>
        </form>
    </div>
@endsection
