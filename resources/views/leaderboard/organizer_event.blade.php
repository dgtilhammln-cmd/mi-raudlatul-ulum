@extends('layouts.app')
@section('title', 'Klasemen — ' . $event->name)
@section('page-title', 'Klasemen')

@push('styles')
<style>
/* ═══ HERO ═══════════════════════════════════════════════════════════════ */
.lb-hero {
    background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%);
    border-radius: 24px; padding: 36px 40px; margin-bottom: 28px;
    position: relative; overflow: hidden; color: #fff;
    box-shadow: 0 12px 32px rgba(29,179,73,.3);
}
.lb-hero::before {
    content:''; position:absolute; top:-30px; right:-30px;
    width:200px; height:200px; background:rgba(255,255,255,.06); border-radius:50%;
}
.lb-hero::after {
    content:''; position:absolute; bottom:-40px; left:30%;
    width:150px; height:150px; background:rgba(255,255,255,.04); border-radius:50%;
}
.lb-hero-content { position:relative; z-index:2; }

/* ═══ PODIUM ═════════════════════════════════════════════════════════════ */
.podium-card {
    border-radius: 20px; padding: 24px 20px; text-align: center;
    position: relative; overflow: hidden; cursor: pointer; transition: .3s cubic-bezier(0.4, 0, 0.2, 1);
}
.podium-card:hover { transform: translateY(-5px); box-shadow: 0 16px 32px rgba(0,0,0,.08); }

.rank-1 {
    background: linear-gradient(135deg, var(--color-primary), #a5cf36);
    border: none; box-shadow: 0 12px 32px rgba(29,179,73,.3); color: #fff;
    padding: 32px 20px;
}
.rank-1::before {
    content:''; position:absolute; top:-20px; right:-20px; width:100px; height:100px;
    background:rgba(255,255,255,.08); border-radius:50%;
}
.rank-1 .score-val { color: #fbbf24; }
.rank-1 .score-lbl { color: rgba(255,255,255,.6); }
.rank-1 .inst-lbl { color: rgba(255,255,255,.8); }
.rank-1 .avatar-ring { background: linear-gradient(135deg,#fef9c3,#ca8a04); box-shadow: 0 4px 12px rgba(202,138,4,.4); width: 64px; height: 64px; border: none; }

.rank-2 {
    background: #fff; border: 1.5px solid #e2e8f0; border-top: 5px solid #94a3b8;
}
.rank-2 .score-val { color: #64748b; }
.rank-2 .score-lbl { color: var(--color-text-tertiary); }
.rank-2 .inst-lbl { color: var(--color-text-secondary); }
.rank-2 .avatar-ring { background: linear-gradient(135deg,#e2e8f0,#cbd5e1); border: 2px solid #fff; box-shadow: 0 4px 12px rgba(148,163,184,.2); width: 56px; height: 56px; }

.rank-3 {
    background: #fff; border: 1.5px solid #e2e8f0; border-top: 5px solid #f59e0b;
}
.rank-3 .score-val { color: #d97706; }
.rank-3 .score-lbl { color: var(--color-text-tertiary); }
.rank-3 .inst-lbl { color: var(--color-text-secondary); }
.rank-3 .avatar-ring { background: linear-gradient(135deg,#fef3c7,#fde68a); border: 2px solid #fff; box-shadow: 0 4px 12px rgba(217,119,6,.2); width: 56px; height: 56px; }

.podium-avatar {
    width: 100%; height: 100%; border-radius: 50%; object-fit: cover;
}
.podium-avatar-placeholder {
    width: 100%; height: 100%; border-radius: 50%; background: #f8fafc;
    display: flex; align-items: center; justify-content: center;
    color: var(--color-text-secondary); font-weight: 900; font-size: 20px;
}

/* ═══ TABLE ══════════════════════════════════════════════════════════════ */
.lb-table-wrapper {
    background: #fff; border-radius: 24px; box-shadow: 0 4px 16px rgba(0,0,0,.06);
    overflow: hidden; border: 1px solid rgba(0,0,0,.04);
}
.lb-table-header {
    padding: 20px 28px; border-bottom: 1px solid var(--color-border);
    display: flex; align-items: center; justify-content: space-between;
}
.lb-row { transition: .2s; cursor: pointer; }
.lb-row:hover { background: #f8fafc; }

@keyframes pulse-dot { 0%,100%{opacity:1;} 50%{opacity:.3;} }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="lb-hero">
    <div class="lb-hero-content">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:20px;">
            <div>
                <div style="font-size:11px;font-weight:800;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:2px;margin-bottom:8px;">Klasemen Sistem Poin</div>
                <h2 style="font-size:24px;font-weight:900;color:#fff;margin-bottom:6px;">{{ $event->name }}</h2>
                <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                    <span style="background:rgba(74,222,128,.2);border:1px solid rgba(74,222,128,.3);border-radius:100px;padding:4px 14px;font-size:12px;font-weight:700;color:#4ade80;">
                        <i class="fas fa-circle" style="font-size:8px;animation:pulse-dot 1.5s infinite;margin-right:4px;"></i> Live Sync
                    </span>
                    <span style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:100px;padding:4px 14px;font-size:12px;font-weight:700;color:#fff;">
                        <i class="fas fa-users" style="margin-right:6px;"></i>
                        {{ count($leaderboard) }} Peserta
                    </span>
                </div>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-secondary" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);color:rgba(255,255,255,.8);">
                    <i class="fas fa-arrow-left"></i> Kembali ke Event
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Top 3 Podium --}}
@if(count($leaderboard) >= 3)
<div style="display:grid;grid-template-columns:1fr 1.15fr 1fr;gap:20px;margin-bottom:32px;align-items:flex-end;">

    {{-- 2nd Place --}}
    <div class="podium-card rank-2" onclick="showIdCard({name:'{{ addslashes($leaderboard[1]['name']) }}', institution:'{{ addslashes($leaderboard[1]['institution']) }}', major:'{{ addslashes($leaderboard[1]['major']) }}', rank:2, avatar_url:'{{ $leaderboard[1]['avatar_url'] ?? '' }}'})">
        <div style="font-size:28px;font-weight:900;color:#94a3b8;margin-bottom:12px;">2</div>
        <div class="avatar-ring" style="border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;overflow:hidden;padding:3px;">
            @if(!empty($leaderboard[1]['avatar_url']))
                <img src="{{ $leaderboard[1]['avatar_url'] }}" alt="{{ $leaderboard[1]['name'] }}" class="podium-avatar" />
            @else
                <div class="podium-avatar-placeholder">{{ strtoupper(substr($leaderboard[1]['name'], 0, 1)) }}</div>
            @endif
        </div>
        <div style="font-weight:900;font-size:15px;color:var(--color-text-primary);margin-bottom:4px;">{{ $leaderboard[1]['name'] }}</div>
        <div class="inst-lbl" style="font-size:11px;margin-bottom:14px;font-weight:600;">{{ $leaderboard[1]['institution'] }}</div>
        <div class="score-val" style="font-size:28px;font-weight:900;line-height:1;">{{ number_format($leaderboard[1]['total_score'], 1) }}</div>
        <div class="score-lbl" style="font-size:10px;font-weight:800;text-transform:uppercase;margin-top:4px;">poin</div>
    </div>

    {{-- 1st Place --}}
    <div class="podium-card rank-1" onclick="showIdCard({name:'{{ addslashes($leaderboard[0]['name']) }}', institution:'{{ addslashes($leaderboard[0]['institution']) }}', major:'{{ addslashes($leaderboard[0]['major']) }}', rank:1, avatar_url:'{{ $leaderboard[0]['avatar_url'] ?? '' }}'})">
        <div style="font-size:24px;margin-bottom:12px;text-shadow:0 4px 12px rgba(251,191,36,.5);"><i class="fas fa-crown" style="color:#fbbf24;"></i></div>
        <div class="avatar-ring" style="border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;overflow:hidden;padding:3px;">
            @if(!empty($leaderboard[0]['avatar_url']))
                <img src="{{ $leaderboard[0]['avatar_url'] }}" alt="{{ $leaderboard[0]['name'] }}" class="podium-avatar" />
            @else
                <div class="podium-avatar-placeholder">{{ strtoupper(substr($leaderboard[0]['name'], 0, 1)) }}</div>
            @endif
        </div>
        <div style="font-weight:900;font-size:17px;color:#fff;margin-bottom:4px;">{{ $leaderboard[0]['name'] }}</div>
        <div class="inst-lbl" style="font-size:12px;margin-bottom:16px;font-weight:600;">{{ $leaderboard[0]['institution'] }}</div>
        <div class="score-val" style="font-size:36px;font-weight:900;line-height:1;">{{ number_format($leaderboard[0]['total_score'], 1) }}</div>
        <div class="score-lbl" style="font-size:10px;font-weight:800;text-transform:uppercase;letter-spacing:.5px;margin-top:4px;">poin</div>
    </div>

    {{-- 3rd Place --}}
    <div class="podium-card rank-3" onclick="showIdCard({name:'{{ addslashes($leaderboard[2]['name']) }}', institution:'{{ addslashes($leaderboard[2]['institution']) }}', major:'{{ addslashes($leaderboard[2]['major']) }}', rank:3, avatar_url:'{{ $leaderboard[2]['avatar_url'] ?? '' }}'})">
        <div style="font-size:28px;font-weight:900;color:#f59e0b;margin-bottom:12px;">3</div>
        <div class="avatar-ring" style="border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;overflow:hidden;padding:3px;">
            @if(!empty($leaderboard[2]['avatar_url']))
                <img src="{{ $leaderboard[2]['avatar_url'] }}" alt="{{ $leaderboard[2]['name'] }}" class="podium-avatar" />
            @else
                <div class="podium-avatar-placeholder">{{ strtoupper(substr($leaderboard[2]['name'], 0, 1)) }}</div>
            @endif
        </div>
        <div style="font-weight:900;font-size:15px;color:var(--color-text-primary);margin-bottom:4px;">{{ $leaderboard[2]['name'] }}</div>
        <div class="inst-lbl" style="font-size:11px;margin-bottom:14px;font-weight:600;">{{ $leaderboard[2]['institution'] }}</div>
        <div class="score-val" style="font-size:28px;font-weight:900;line-height:1;">{{ number_format($leaderboard[2]['total_score'], 1) }}</div>
        <div class="score-lbl" style="font-size:10px;font-weight:800;text-transform:uppercase;margin-top:4px;">poin</div>
    </div>
</div>
@endif

{{-- Full Table --}}
<div class="lb-table-wrapper">
    <div class="lb-table-header">
        <h3 style="font-size:16px;font-weight:900;margin:0;color:#0f172a;"><i class="fas fa-list-ol" style="color:var(--color-primary);margin-right:8px;"></i>Klasemen Lengkap</h3>
        <span id="lb-updated" style="font-size:11px;color:var(--color-text-tertiary);font-weight:700;"></span>
    </div>
    <div class="table-wrapper" style="padding:0;border:none;box-shadow:none;">
        <table style="margin:0;">
            <thead>
                <tr>
                    <th style="padding-left:28px;width:60px;">#</th>
                    <th>Peserta</th>
                    <th>Institusi</th>
                    <th>Kelas / Jurusan</th>
                    <th>Babak Selesai</th>
                    <th style="text-align:right;padding-right:28px;">Total Poin</th>
                </tr>
            </thead>
            <tbody id="lb-tbody">
                @foreach($leaderboard as $i => $row)
                @php
                    $rank = $i + 1;
                    $medalColor = $rank === 1 ? '#f59e0b' : ($rank === 2 ? '#94a3b8' : ($rank === 3 ? '#d97706' : null));
                @endphp
                <tr class="lb-row" onclick="showIdCard({name:'{{ addslashes($row['name']) }}', institution:'{{ addslashes($row['institution']) }}', major:'{{ addslashes($row['major']) }}', rank:{{ $rank }}, avatar_url:'{{ $row['avatar_url'] ?? '' }}'})">
                    <td style="padding-left:28px;">
                        @if($medalColor)
                            <div style="width:32px;height:32px;background:{{ $medalColor }};border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 8px rgba(0,0,0,.1);">
                                <i class="fas fa-medal" style="color:#fff;font-size:14px;"></i>
                            </div>
                        @else
                            <span style="font-size:15px;font-weight:900;color:#94a3b8;width:32px;display:inline-block;text-align:center;">{{ $rank }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="flex-shrink:0;">
                                @if(!empty($row['avatar_url']))
                                    <img src="{{ $row['avatar_url'] }}" alt="{{ $row['name'] }}" style="width:40px;height:40px;border-radius:12px;object-fit:cover;border:1px solid #e2e8f0;" />
                                @else
                                    <div style="width:40px;height:40px;border-radius:12px;background:#f8fafc;display:flex;align-items:center;justify-content:center;color:#64748b;font-weight:900;font-size:15px;border:1px solid #e2e8f0;">
                                        {{ strtoupper(substr($row['name'], 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:800;font-size:14px;color:#0f172a;margin-bottom:2px;">{{ $row['name'] }}</div>
                                <div style="font-size:11px;color:var(--color-text-tertiary);font-family:monospace;font-weight:600;">{{ $row['code'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;font-weight:600;color:#334155;">{{ $row['institution'] }}</td>
                    <td style="font-size:13px;color:#64748b;font-weight:500;">
                        {{ $row['grade'] }}
                        @if($row['major'])<span style="color:#94a3b8;"> &bull; {{ $row['major'] }}</span>@endif
                    </td>
                    <td>
                        <span style="background:#f0fdf4;color:#16a34a;padding:4px 12px;border-radius:100px;font-size:12px;font-weight:800;border:1px solid #bbf7d0;">
                            {{ $row['rounds_done'] }} babak
                        </span>
                    </td>
                    <td style="text-align:right;padding-right:28px;">
                        <span style="font-size:20px;font-weight:900;color:{{ $rank <= 3 ? ($rank==1?'#f59e0b':($rank==2?'#64748b':'#d97706')) : 'var(--color-primary)' }};">
                            {{ number_format($row['total_score'], 1) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
// Auto-refresh leaderboard every 30 seconds
const eventId = {{ $event->id }};
const apiUrl  = '{{ route("leaderboard.json", $event) }}';

function escapeJS(unsafe) {
    if (!unsafe) return '';
    return unsafe.toString().replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"').replace(/\n/g, '\\n').replace(/\r/g, '\\r');
}
function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe.toString().replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
}

function refreshLeaderboard() {
    fetch(apiUrl)
        .then(r => r.json())
        .then(json => {
            if (!json.visible || !json.data.length) return;
            const tbody = document.getElementById('lb-tbody');
            if (!tbody) return;

            let html = '';
            json.data.forEach((row, i) => {
                const rank = i + 1;
                const medalColors = { 1: '#f59e0b', 2: '#94a3b8', 3: '#d97706' };
                const scoreColors = { 1: '#f59e0b', 2: '#64748b', 3: '#d97706' };
                const rankCell = medalColors[rank]
                    ? `<div style="width:32px;height:32px;background:${medalColors[rank]};border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 8px rgba(0,0,0,.1);"><i class="fas fa-medal" style="color:#fff;font-size:14px;"></i></div>`
                    : `<span style="font-size:15px;font-weight:900;color:#94a3b8;width:32px;display:inline-block;text-align:center;">${rank}</span>`;

                const avatar = row.avatar_url 
                    ? `<img src="${escapeHtml(row.avatar_url)}" style="width:40px;height:40px;border-radius:12px;object-fit:cover;border:1px solid #e2e8f0;" />`
                    : `<div style="width:40px;height:40px;border-radius:12px;background:#f8fafc;display:flex;align-items:center;justify-content:center;color:#64748b;font-weight:900;font-size:15px;border:1px solid #e2e8f0;">${escapeHtml(row.name.charAt(0).toUpperCase())}</div>`;

                const safeName = escapeJS(row.name);
                const safeInst = escapeJS(row.institution || '');
                const safeMajor = escapeJS(row.major || '');
                const safeAvatar = escapeJS(row.avatar_url || '');

                html += `<tr class="lb-row" onclick="showIdCard({name:'${safeName}', institution:'${safeInst}', major:'${safeMajor}', rank:${rank}, avatar_url:'${safeAvatar}'})">
                    <td style="padding-left:28px;">${rankCell}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="flex-shrink:0;">${avatar}</div>
                            <div>
                                <div style="font-weight:800;font-size:14px;color:#0f172a;margin-bottom:2px;">${escapeHtml(row.name)}</div>
                                <div style="font-size:11px;color:var(--color-text-tertiary);font-family:monospace;font-weight:600;">${escapeHtml(row.code)}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;font-weight:600;color:#334155;">${escapeHtml(row.institution)}</td>
                    <td style="font-size:13px;color:#64748b;font-weight:500;">${escapeHtml(row.grade)}${row.major ? ' <span style="color:#94a3b8;">&bull; ' + escapeHtml(row.major) + '</span>' : ''}</td>
                    <td><span style="background:#f0fdf4;color:#16a34a;padding:4px 12px;border-radius:100px;font-size:12px;font-weight:800;border:1px solid #bbf7d0;">${row.rounds_done} babak</span></td>
                    <td style="text-align:right;padding-right:28px;"><span style="font-size:20px;font-weight:900;color:${scoreColors[rank]||'var(--color-primary)'};">${parseFloat(row.total_score).toFixed(1)}</span></td>
                </tr>`;
            });
            tbody.innerHTML = html;

            const el = document.getElementById('lb-updated');
            if (el) el.textContent = 'Diperbarui: ' + json.updated_at;
        })
        .catch(() => {});
}

document.getElementById('lb-updated').textContent = 'Diperbarui: {{ now()->format("H:i:s") }}';
setInterval(refreshLeaderboard, 30000);
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
@include('components.id-card-modal')

@endsection
