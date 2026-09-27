@extends('admin.layouts.app')

@section('title', 'Members Settings - SIGMA Admin')

@section('content')
    <div class="page-header">
        <div>
            <h1 class="page-title">Members Settings</h1>
            <p class="page-subtitle">Tambah, edit, hapus, dan pantau riwayat member landing page.</p>
        </div>
        <a href="{{ route('admin.settings.members.print') }}" target="_blank" rel="noopener" class="btn btn-secondary">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
            Preview &amp; Cetak Card
        </a>
    </div>

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
        <form method="POST" action="{{ route('admin.settings.members.section') }}">
            @csrf
            <div class="f-grid-2">
                <div>
                    <label class="f-label">Section Title</label>
                    <input type="text" name="member_section_title" value="{{ old('member_section_title', $contents['member_section_title']->value ?? '') }}" class="f-input">
                </div>
                <div>
                    <label class="f-label">Section Subtitle</label>
                    <input type="text" name="member_section_subtitle" value="{{ old('member_section_subtitle', $contents['member_section_subtitle']->value ?? '') }}" class="f-input">
                </div>
            </div>
            <button type="submit" class="btn-save"><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Save Section Header</span></button>
        </form>
    </div>

    <div class="card mb-lg">
        <div class="stat-label f-subhead">Tambah Member</div>
        <p class="f-hint">Member baru langsung tampil di landing page.</p>
        <form method="POST" action="{{ route('admin.settings.members.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="f-grid-150">
                <div>
                    <label class="f-label">Photo</label>
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="f-input f-input-sm">
                    <p class="f-hint-sm f-file-hint">JPG, PNG, WebP. Maks 5 MB.</p>
                </div>
                <div class="f-grid-4">
                    <div>
                        <label class="f-label f-label-sm">Initial</label>
                        <input type="text" name="initial" value="{{ old('initial') }}" maxlength="20" placeholder="AI" class="f-input f-input-sm">
                    </div>
                    <div>
                        <label class="f-label f-label-sm">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Member Name" class="f-input f-input-sm">
                    </div>
                    <div>
                        <label class="f-label f-label-sm">Role</label>
                        <input type="text" name="role" value="{{ old('role') }}" placeholder="Position" class="f-input f-input-sm">
                    </div>
                    <div>
                        <label class="f-label f-label-sm">Code</label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="Giyama/2025/001" class="f-input f-input-sm">
                    </div>
                    <div class="f-span-rest">
                        <label class="f-label f-label-sm">Motto</label>
                        <input type="text" name="motto" value="{{ old('motto') }}" maxlength="200" placeholder="&ldquo;Belajar dulu,/Askepan kemudian.&rdquo;" class="f-input f-input-sm">
                        <p class="f-hint f-hint-sm">Kata motivasi, tampil di kartu member landing page.</p>
                    </div>
                    <div class="f-span-rest">
                        <label class="f-label f-label-sm">Description</label>
                        <textarea name="description" rows="2" placeholder="Short description..." style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; resize: vertical;">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-save btn-save-success"><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Add Member</span></button>
        </form>
    </div>

    <h2 class="f-h2">Member List ({{ $members->count() }})</h2>
    @forelse($members as $member)
        <div class="card mb-md">
            <div class="f-row">
                <div class="f-strong">{{ $member->name }}</div>
                <form method="POST" action="{{ route('admin.settings.members.destroy', $member) }}" onsubmit="return confirm('Hapus member ini? Riwayat tetap tersimpan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="f-submit f-submit-danger">Hapus</button>
                </form>
            </div>
            <form method="POST" action="{{ route('admin.settings.members.update', $member) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="f-grid-150">
                    <div>
                        @if($member->photo)
                            <img src="{{ $member->photo }}" alt="{{ $member->name }}" class="f-thumb">
                        @endif
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" class="f-input f-input-xs">
                    </div>
                    <div class="f-grid-4">
                        <div><label class="f-label f-label-sm">Initial</label><input type="text" name="initial" value="{{ old('initial', $member->initial) }}" maxlength="20" class="f-input f-input-sm"></div>
                        <div><label class="f-label f-label-sm">Name</label><input type="text" name="name" value="{{ old('name', $member->name) }}" required class="f-input f-input-sm"></div>
                        <div><label class="f-label f-label-sm">Role</label><input type="text" name="role" value="{{ old('role', $member->role) }}" class="f-input f-input-sm"></div>
                        <div><label class="f-label f-label-sm">Code</label><input type="text" name="code" value="{{ old('code', $member->code) }}" class="f-input f-input-sm"></div>
                        <div class="f-span-rest"><label class="f-label f-label-sm">Motto</label><input type="text" name="motto" value="{{ old('motto', $member->motto) }}" maxlength="200" class="f-input f-input-sm"></div>
                        <div class="f-span-rest"><label class="f-label f-label-sm">Description</label><textarea name="description" rows="2" class="f-input f-input-sm">{{ old('description', $member->description) }}</textarea></div>
                    </div>
                </div>
                <button type="submit" class="btn-save"><svg class="btn-save-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8"/><path d="M7 3v5h8"/></svg><span class="btn-save-label">Save Changes</span></button>
            </form>
        </div>
    @empty
        <div class="card f-muted">Belum ada member. Tambahkan member pertama melalui form di atas.</div>
    @endforelse

    <div class="card mt-xl">
        <div class="stat-label f-subhead">Member History ({{ $histories->count() }})</div>
        @forelse($histories as $history)
            <div class="f-history-row">
                <div>
                    <strong class="f-white">{{ $history->name }}</strong>
                    <span class="f-history-tag">{{ $history->action }}</span>
                    @if($history->role)<span class="f-history-sub">{{ $history->role }}</span>@endif
                </div>
                <div class="f-history-date">{{ $history->created_at?->format('d M Y H:i') }} @if($history->creator) · {{ $history->creator->name }} @endif</div>
            </div>
        @empty
            <div class="f-muted-md">Belum ada riwayat member.</div>
        @endforelse
    </div>
@endsection
