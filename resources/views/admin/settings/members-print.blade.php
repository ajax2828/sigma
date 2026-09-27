<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Card Member - SIGMA</title>
    <style>
        /* Preview di layar: kartu白雪 atas background gelap, sama seperti kartu di landing.
           Saat dicetak (@media print) semua chrome hilang, kartu jadi 3.5 x 2 inci (ID card). */
        :root {
            --ink: #181818;
            --muted: #6b6b6b;
            --line: #d4d4d4;
            --accent: #9b1c1c;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Source Sans 3', -apple-system, Segoe UI, sans-serif; background: #0e1116; color: #e6e9ee; padding: 2rem 1.5rem 4rem; }

        .toolbar { position: sticky; top: 0; z-index: 10; display: flex; align-items: center; justify-content: space-between; gap: 1rem; max-width: 1100px; margin: 0 auto 2rem; padding: 0.875rem 1.25rem; background: #151a21; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; }
        .toolbar h1 { font-size: 1.125rem; color: #fff; }
        .toolbar p { font-size: 0.8125rem; color: #9aa4b2; margin-top: 0.125rem; }
        .toolbar-actions { display: flex; gap: 0.625rem; }
        .btn { display: inline-flex; align-items: center; gap: 0.4rem; padding: 0.55rem 1.1rem; border: 1px solid transparent; border-radius: 8px; background: #d4a24c; color: #1a1207; font-size: 0.8125rem; font-weight: 700; text-decoration: none; cursor: pointer; }
        .btn-ghost { background: transparent; color: #9aa4b2; border-color: rgba(255,255,255,0.16); }
        .btn svg { width: 15px; height: 15px; }
        .btn:disabled { opacity: 0.45; cursor: not-allowed; }
        .pick-all { display: flex; align-items: center; gap: 0.4rem; font-size: 0.8125rem; color: #9aa4b2; cursor: pointer; white-space: nowrap; }
        .pick-all input { accent-color: #d4a24c; cursor: pointer; }

        /* Ukuran dalam mm, bukan px, supaya preview = hasil cetak persis.
           A4 (210x297mm) dengan margin 10mm -> area cetak 190 x 277mm.
           Kartu 91 x 52mm, gap 8mm: 2 kolom x 4 baris = 8 kartu per halaman. */
        .sheet { max-width: 190mm; margin: 0 auto; }
        .grid { display: grid; grid-template-columns: repeat(2, 91mm); gap: 8mm; }

        /* Checkbox pilihan overlay di atas kartu: tidak menambah tinggi, hitungan A4 tetap. */
        .card-wrap { position: relative; width: 91mm; height: 52mm; }
        .pick { position: absolute; top: -3mm; right: -3mm; z-index: 2; display: flex; align-items: center; justify-content: center; width: 7mm; height: 7mm; border-radius: 50%; background: #151a21; border: 0.3mm solid rgba(255,255,255,0.35); cursor: pointer; }
        .pick input { width: 3.4mm; height: 3.4mm; margin: 0; accent-color: #d4a24c; cursor: pointer; }
        .card-wrap:not(.is-picked) .card { opacity: 0.3; }
        .card-wrap:not(.is-picked) { outline: 0.3mm dashed rgba(255,255,255,0.25); outline-offset: 1mm; border-radius: 3mm; }

        .card { width: 91mm; height: 52mm; background: #fffdf8; color: var(--ink); border: 1px solid var(--line); border-radius: 3mm; padding: 5mm 6mm; display: flex; align-items: center; gap: 5mm; overflow: hidden; }
        .photo { flex: 0 0 22mm; width: 22mm; height: 22mm; border-radius: 50%; overflow: hidden; background: var(--ink); color: #f4f0e6; display: flex; align-items: center; justify-content: center; font-size: 9mm; font-weight: 700; }
        .photo img { width: 100%; height: 100%; object-fit: cover; }
        .info { min-width: 0; }
        .no { font-size: 2.6mm; font-weight: 700; letter-spacing: 0.08em; text-transform: uppercase; color: var(--accent); }
        .name { font-size: 5mm; font-weight: 700; line-height: 1.2; margin-top: 0.6mm; }
        .role { display: inline-block; font-size: 2.5mm; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--muted); background: #ece7dc; padding: 0.7mm 1.6mm; border-radius: 1mm; margin-top: 1.2mm; }
        .motto { font-size: 3mm; color: #4a4a4a; line-height: 1.35; font-style: italic; margin-top: 1.8mm; padding-left: 2mm; border-left: 0.6mm solid var(--accent); }
        .motto:empty { display: none; }
        .empty { text-align: center; color: #9aa4b2; padding: 3rem; }

        @media print {
            /* Cetak di atas kertas putih: chrome dan background gelap dibuang. */
            body { background: #fff; padding: 0; }
            .toolbar { display: none; }
            .sheet { max-width: none; }
            .pick { display: none; }
            /* Kartu tidak terpilih tidak boleh ikut tercetak. */
            .card-wrap:not(.is-picked) { display: none; }
            .card-wrap { width: 91mm; height: 52mm; }
            .card { break-inside: avoid; page-break-inside: avoid; border: 1px solid #c0c0c0; }
        }
        @page { size: A4; margin: 10mm; }
    </style>
</head>
<body>
    <div class="toolbar no-print">
        <div>
            <h1>Preview &amp; Cetak Card Member</h1>
            <p>{{ $members->count() }} kartu &middot; 91 &times; 52 mm per kartu &middot; 8 kartu per halaman A4</p>
        </div>
        <div class="toolbar-actions">
            <a href="{{ route('admin.settings.members') }}" class="btn btn-ghost">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5"/><path d="M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <label class="pick-all">
                <input type="checkbox" id="pickAll" checked>
                Pilih semua
            </label>
            <button type="button" class="btn" id="printBtn" onclick="window.print()">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><path d="M6 14h12v8H6z"/></svg>
                Cetak <span id="printCount">({{ $members->count() }})</span>
            </button>
        </div>
    </div>

    <div class="sheet grid">
        @forelse($members as $member)
            <div class="card-wrap is-picked">
                <label class="pick">
                    <input type="checkbox" class="pick-input" checked
                           aria-label="Cetak {{ $member->name }}">
                </label>
                <div class="card">
                    <div class="photo">
                        @if($member->photo)
                            <img src="{{ $member->photo }}" alt="{{ $member->name }}">
                        @else
                            {{ $member->initial ?: mb_substr($member->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="info">
                        <div class="no">{{ $member->code }}</div>
                        <div class="name">{{ $member->name }}</div>
                        @if($member->role)<div class="role">{{ $member->role }}</div>@endif
                        <p class="motto">{{ $member->motto }}</p>
                    </div>
                </div>
            </div>
        @empty
            <p class="empty">Belum ada member untuk dicetak.</p>
        @endforelse
    </div>

    <script>
        // Kartu yang tidak dicentang hilang saat dicetak; di layar tetap terlihat redup
        // supaya user tahu member itu ada dan bisa dicentang lagi.
        (function () {
            var wraps = Array.from(document.querySelectorAll('.card-wrap'));
            var pickAll = document.getElementById('pickAll');
            var printBtn = document.getElementById('printBtn');
            var count = document.getElementById('printCount');
            if (!wraps.length || !pickAll || !printBtn || !count) return;

            function sync() {
                var picked = 0;
                wraps.forEach(function (wrap) {
                    var on = wrap.querySelector('.pick-input').checked;
                    wrap.classList.toggle('is-picked', on);
                    if (on) picked++;
                });
                // Indeterminate saat sebagian terpilih: lebih jujur daripada "Pilih semua" unchecked.
                pickAll.checked = picked === wraps.length;
                pickAll.indeterminate = picked > 0 && picked < wraps.length;
                count.textContent = '(' + picked + ')';
                printBtn.disabled = picked === 0;
            }

            pickAll.addEventListener('change', function () {
                wraps.forEach(function (wrap) { wrap.querySelector('.pick-input').checked = pickAll.checked; });
                sync();
            });

            wraps.forEach(function (wrap) {
                wrap.querySelector('.pick-input').addEventListener('change', sync);
            });

            sync();
        })();
    </script>
</body>
</html>
