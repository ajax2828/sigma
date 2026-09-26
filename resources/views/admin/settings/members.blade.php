@extends('admin.layouts.app')

@section('title', 'Members Settings - SIGMA Admin')

@section('content')
    <h1 class="page-title">Members Settings</h1>
    <p class="page-subtitle">Tambah, edit, hapus, dan pantau riwayat member landing page.</p>

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
        <form method="POST" action="{{ route('admin.settings.members.section') }}">
            @csrf
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Section Title</label>
                    <input type="text" name="member_section_title" value="{{ old('member_section_title', $contents['member_section_title']->value ?? '') }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Section Subtitle</label>
                    <input type="text" name="member_section_subtitle" value="{{ old('member_section_subtitle', $contents['member_section_subtitle']->value ?? '') }}" style="width: 100%; padding: 0.625rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; color: #fff; font-size: 0.875rem;">
                </div>
            </div>
            <button type="submit" style="padding: 0.65rem 1.25rem; background: #1e40af; color: #fff; border: 0; border-radius: 8px; cursor: pointer;">Save Section Header</button>
        </form>
    </div>

    <div class="card" style="margin-bottom: 1.5rem;">
        <div class="stat-label" style="margin-bottom: 0.35rem; font-size: 1rem;">Tambah Member</div>
        <p style="color: rgba(255,255,255,0.55); font-size: 0.8125rem; margin: 0 0 1.25rem;">Member baru langsung tampil di landing page.</p>
        <form method="POST" action="{{ route('admin.settings.members.store') }}" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1.25rem; align-items: start;">
                <div>
                    <label style="display: block; font-size: 0.75rem; color: rgba(255,255,255,0.5); margin-bottom: 0.5rem;">Photo</label>
                    <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" style="width: 100%; font-size: 0.75rem; color: rgba(255,255,255,0.7);">
                    <p style="margin-top: 0.5rem; color: rgba(255,255,255,0.45); font-size: 0.7rem;">JPG, PNG, WebP. Maks 5 MB.</p>
                </div>
                <div style="display: grid; grid-template-columns: 100px 1fr 1fr 1fr; gap: 0.75rem;">
                    <div>
                        <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Initial</label>
                        <input type="text" name="initial" value="{{ old('initial') }}" maxlength="20" placeholder="AI" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Name</label>
                        <input type="text" name="name" value="{{ old('name') }}" required placeholder="Member Name" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Role</label>
                        <input type="text" name="role" value="{{ old('role') }}" placeholder="Position" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Code</label>
                        <input type="text" name="code" value="{{ old('code') }}" placeholder="Giyama/2025/001" style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff;">
                    </div>
                    <div style="grid-column: 2 / -1;">
                        <label style="display: block; font-size: 0.6875rem; color: rgba(255,255,255,0.5); margin-bottom: 0.375rem;">Description</label>
                        <textarea name="description" rows="2" placeholder="Short description..." style="width: 100%; padding: 0.5rem; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; color: #fff; resize: vertical;">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>
            <button type="submit" style="margin-top: 1rem; padding: 0.7rem 1.5rem; background: #15803d; color: #fff; border: 0; border-radius: 8px; cursor: pointer;">Add Member</button>
        </form>
    </div>

    <h2 style="font-size: 1.125rem; color: #fff; margin-bottom: 1rem;">Member List ({{ $members->count() }})</h2>
    @forelse($members as $member)
        <div class="card" style="margin-bottom: 1rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                <div style="font-size: 1rem; font-weight: 700; color: #fff;">{{ $member->name }}</div>
                <form method="POST" action="{{ route('admin.settings.members.destroy', $member) }}" onsubmit="return confirm('Hapus member ini? Riwayat tetap tersimpan.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="padding: 0.45rem 0.8rem; background: rgba(239, 68, 68, 0.15); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.35); border-radius: 6px; cursor: pointer;">Hapus</button>
                </form>
            </div>
            <form method="POST" action="{{ route('admin.settings.members.update', $member) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1.25rem; align-items: start;">
                    <div>
                        @if($member->photo)
                            <img src="{{ $member->photo }}" alt="{{ $member->name }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 12px; margin-bottom: 0.5rem;">
                        @endif
                        <input type="file" name="photo" accept="image/jpeg,image/png,image/webp" style="width: 100%; font-size: 0.7rem; color: rgba(255,255,255,0.7);">
                    </div>
                    <div style="display: grid; grid-template-columns: 100px 1fr 1fr 1fr; gap: 0.75rem;">
                        <div><label style="display:block;font-size:0.6875rem;color:rgba(255,255,255,0.5);margin-bottom:0.375rem;">Initial</label><input type="text" name="initial" value="{{ old('initial', $member->initial) }}" maxlength="20" style="width:100%;padding:0.5rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:6px;color:#fff;"></div>
                        <div><label style="display:block;font-size:0.6875rem;color:rgba(255,255,255,0.5);margin-bottom:0.375rem;">Name</label><input type="text" name="name" value="{{ old('name', $member->name) }}" required style="width:100%;padding:0.5rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:6px;color:#fff;"></div>
                        <div><label style="display:block;font-size:0.6875rem;color:rgba(255,255,255,0.5);margin-bottom:0.375rem;">Role</label><input type="text" name="role" value="{{ old('role', $member->role) }}" style="width:100%;padding:0.5rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:6px;color:#fff;"></div>
                        <div><label style="display:block;font-size:0.6875rem;color:rgba(255,255,255,0.5);margin-bottom:0.375rem;">Code</label><input type="text" name="code" value="{{ old('code', $member->code) }}" style="width:100%;padding:0.5rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:6px;color:#fff;"></div>
                        <div style="grid-column: 2 / -1;"><label style="display:block;font-size:0.6875rem;color:rgba(255,255,255,0.5);margin-bottom:0.375rem;">Description</label><textarea name="description" rows="2" style="width:100%;padding:0.5rem;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);border-radius:6px;color:#fff;resize:vertical;">{{ old('description', $member->description) }}</textarea></div>
                    </div>
                </div>
                <button type="submit" style="margin-top: 1rem; padding: 0.6rem 1.25rem; background: #1d4ed8; color: #fff; border: 0; border-radius: 8px; cursor: pointer;">Save Changes</button>
            </form>
        </div>
    @empty
        <div class="card" style="color: rgba(255,255,255,0.6);">Belum ada member. Tambahkan member pertama melalui form di atas.</div>
    @endforelse

    <div class="card" style="margin-top: 2rem;">
        <div class="stat-label" style="margin-bottom: 1rem; font-size: 1rem;">Member History ({{ $histories->count() }})</div>
        @forelse($histories as $history)
            <div style="display: flex; justify-content: space-between; gap: 1rem; padding: 0.75rem 0; border-bottom: 1px solid rgba(255,255,255,0.08); font-size: 0.8125rem;">
                <div>
                    <strong style="color: #fff;">{{ $history->name }}</strong>
                    <span style="margin-left: 0.5rem; color: rgba(255,255,255,0.55);">{{ $history->action }}</span>
                    @if($history->role)<span style="margin-left: 0.5rem; color: rgba(255,255,255,0.45);">{{ $history->role }}</span>@endif
                </div>
                <div style="color: rgba(255,255,255,0.45); white-space: nowrap;">{{ $history->created_at?->format('d M Y H:i') }} @if($history->creator) · {{ $history->creator->name }} @endif</div>
            </div>
        @empty
            <div style="color: rgba(255,255,255,0.55); font-size: 0.875rem;">Belum ada riwayat member.</div>
        @endforelse
    </div>
@endsection
