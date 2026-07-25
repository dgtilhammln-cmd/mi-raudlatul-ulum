@extends('layouts.app')
@section('title', 'Bagan Turnamen — ' . $event->name)
@section('page-title', 'Bagan Turnamen')

@push('styles')
<style>
.psc-hero {
    background: linear-gradient(135deg, #16a34a, #1db349 45%, #a5cf36);
    border-radius: 24px; padding: 36px 40px; margin-bottom: 28px;
    position: relative; overflow: hidden; color: #fff;
    box-shadow: 0 8px 32px rgba(29,179,73,0.35);
}
.psc-hero::after {
    content:''; position:absolute; inset:0;
    background-image: radial-gradient(rgba(255,255,255,.15) 1px, transparent 1px);
    background-size: 28px 28px; pointer-events:none;
}
.psc-hero-content { position:relative; z-index:2; }

/* My Status Banner */
.my-status-banner {
    border-radius: 20px; padding: 24px 28px; margin-bottom: 24px;
    display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
}
.my-status-banner.status-active    { background: linear-gradient(135deg,#dcfce7,#bbf7d0); border: 1.5px solid #86efac; }
.my-status-banner.status-champion  { background: linear-gradient(135deg,#fffbeb,#fef9c3); border: 1.5px solid #fde68a; animation: sparkle 2s ease-in-out infinite; }
.my-status-banner.status-eliminated { background: linear-gradient(135deg,#fff5f5,#fee2e2); border: 1.5px solid #fecaca; }
.my-status-banner.status-pending   { background: linear-gradient(135deg,#dcfce7,#bbf7d0); border: 1.5px solid #86efac; }

@keyframes sparkle { 0%,100%{transform:scale(1);} 50%{transform:scale(1.01);} }
@keyframes pulse-dot { 0%,100%{opacity:1;} 50%{opacity:.3;} }

/* ═══ BRACKET FLOW ════════════════════════════════════════════════════════ */
.p-bracket-container {
    overflow-x: auto; padding: 40px 24px 60px;
    -webkit-overflow-scrolling: touch;
    background: radial-gradient(circle at top, #ffffff 0%, #f8fafc 100%);
}
.p-bracket-flow {
    display: flex; align-items: stretch; gap: 0;
    min-width: max-content; margin: 0 auto;
}

/* Each Round Column */
.p-bracket-col {
    display: flex; flex-direction: column;
    align-items: center; width: 260px; position: relative;
    z-index: 2;
}
.p-bracket-col-header {
    text-align: center; margin-bottom: 30px;
    position: relative; width: 100%;
}
.p-col-title {
    font-size: 15px; font-weight: 900; color: #0f172a;
    margin-bottom: 6px; letter-spacing: -0.5px;
}
.p-col-meta {
    font-size: 12px; color: #64748b; font-weight: 600;
}
.round-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #fff; border: 1px solid #e2e8f0; border-radius: 100px;
    padding: 4px 12px; font-size: 11px; font-weight: 800; box-shadow: 0 2px 8px rgba(0,0,0,.05);
    margin-bottom: 12px;
}
.round-status-dot { width: 8px; height: 8px; border-radius: 50%; }

/* Slots */
.p-bracket-slots { 
    display: flex; flex-direction: column; gap: 12px; width: 100%;
}
.p-bracket-slot {
    background: #fff; border: 1.5px solid #e2e8f0; border-radius: 16px;
    padding: 12px 16px; display: flex; align-items: center; gap: 12px;
    cursor: pointer; transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    box-shadow: 0 4px 12px rgba(0,0,0,.02);
}
.p-bracket-slot::before {
    content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
    background: #cbd5e1; transition: .3s; border-radius: 16px 0 0 16px;
}
.p-bracket-slot.is-me {
    border-color: var(--color-primary); box-shadow: 0 0 0 3px rgba(29,179,73,.15);
}
.p-bracket-slot.is-me::before { background: var(--color-primary); }
.p-bracket-slot.s-submitted::before { background: #3b82f6; }
.p-bracket-slot.s-eliminated::before { background: #ef4444; }
.p-bracket-slot.s-champion::before { background: linear-gradient(180deg, #f59e0b, #d97706); }
.p-bracket-slot.s-projected { border-color: #ddd6fe; background: #faf5ff; }
.p-bracket-slot.s-projected::before { background: #8b5cf6; }
.projected-badge {
    position: absolute; top: -8px; right: -8px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff;
    font-size: 9px; font-weight: 800; padding: 3px 8px; border-radius: 100px;
    letter-spacing: .5px; box-shadow: 0 4px 12px rgba(139,92,246,.4); z-index:10;
}

.p-bracket-slot:hover { 
    border-color: var(--color-primary); box-shadow: 0 8px 24px rgba(29,179,73,.15); transform: translateY(-3px); 
}

.p-bracket-slot.s-eliminated { opacity: .6; filter: grayscale(50%); }
.p-bracket-slot.s-champion { background: linear-gradient(135deg,#fffbeb,#fef9c3); border-color: #fde68a; }

.p-slot-avatar {
    width: 36px; height: 36px; border-radius: 12px; flex-shrink: 0;
    object-fit: cover; background: var(--color-surface-soft);
    display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: 14px; color: var(--color-text-secondary);
    border: 1px solid #e2e8f0;
}
.p-slot-name { font-size: 13px; font-weight: 800; color: #0f172a; line-height: 1.2; margin-bottom:2px; }
.p-slot-inst { font-size: 10px; color: #64748b; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.p-slot-major { font-size: 10px; color: #94a3b8; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.p-slot-score { font-size: 16px; font-weight: 900; color: var(--color-primary); margin-left: auto; flex-shrink:0; background:#f0fdf4; padding:4px 8px; border-radius:8px; }

/* Empty slots */
.p-bracket-slot.s-upcoming.empty {
    background: #f8fafc; border-style: dashed; border-color: #cbd5e1;
    pointer-events: none; opacity: .7; box-shadow:none;
}
.p-bracket-slot.s-upcoming.empty::before { display:none; }

/* Pipeline Arrow */
.bracket-connector {
    display: flex; align-items: center; justify-content: center;
    width: 60px; position: relative; flex-shrink: 0; margin-top: 60px;
}
.bracket-connector::after {
    content: ''; position: absolute; left: 0; right: 0; top: 50%; height: 2px;
    background: linear-gradient(90deg, #cbd5e1 0%, var(--color-primary) 100%);
    z-index: 1; transform: translateY(-50%);
}
.bracket-connector i {
    position: relative; z-index: 2; background: #fff; border-radius: 50%;
    padding: 6px; color: var(--color-primary); box-shadow: 0 2px 8px rgba(29,179,73,.2);
    font-size: 12px;
}
/* Timeline */
.timeline-row {
    display: grid; gap: 12px;
}
.timeline-item {
    background: #fff; border-radius: 16px; padding: 18px 20px;
    border: 1px solid rgba(0,0,0,.04); box-shadow: 0 2px 8px rgba(0,0,0,.04);
    display: flex; align-items: center; gap: 16px;
}
.timeline-num {
    width: 36px; height: 36px; border-radius: 10px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: 14px; color: #fff;
}
</style>
@endpush

@section('content')

@php
    $myId = $myParticipant?->id;
    $myStatus = 'pending';
    if ($myParticipant) {
        if ($myParticipant->is_champion) $myStatus = 'champion';
        elseif ($myParticipant->isEliminated()) $myStatus = 'eliminated';
        elseif ($myParticipant->current_round_sequence > 0) $myStatus = 'active';
    }
@endphp

{{-- Hero --}}
<div class="psc-hero mobile-p-4 mobile-stack" style="gap:16px;">
    <div class="psc-hero-content">
        <div style="font-size:11px;font-weight:800;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:2px;margin-bottom:8px;">Bagan Turnamen</div>
        <h1 style="font-size:24px;font-weight:900;color:#fff;margin-bottom:6px;line-height:1.2;">{{ $event->name }}</h1>
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
            <span style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:100px;padding:4px 14px;font-size:12px;font-weight:700;color:#fff;">
                {{ $event->getBracketModeLabel() }}
            </span>
            <span style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:100px;padding:4px 14px;font-size:12px;font-weight:700;color:#fff;">
                {{ count($bracketData) }} Babak
            </span>
            <span id="liveStatus" style="background:rgba(74,222,128,.2);border:1px solid rgba(74,222,128,.3);border-radius:100px;padding:4px 14px;font-size:12px;font-weight:700;color:#4ade80;">
                <i class="fas fa-circle" style="font-size:8px;animation:pulse-dot 1.5s infinite;margin-right:4px;"></i> Live
            </span>
        </div>
    </div>
</div>

{{-- My Status Banner --}}
@if($myParticipant)
<div class="my-status-banner status-{{ $myStatus }}">
    @if($myStatus === 'champion')
        <div style="width:48px;height:48px;background:linear-gradient(135deg,#fef08a,#eab308);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;box-shadow:0 4px 12px rgba(234,179,8,.3);color:#fff;"><i class="fas fa-trophy"></i></div>
        <div>
            <div style="font-size:20px;font-weight:900;color:#92400e;margin-bottom:4px;">Selamat! Anda Menjadi JUARA!</div>
            <div style="font-size:14px;color:#a16207;">Prestasi luar biasa di {{ $event->name }}</div>
        </div>
    @elseif($myStatus === 'eliminated')
        <div style="width:48px;height:48px;background:linear-gradient(135deg,#fca5a5,#ef4444);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;box-shadow:0 4px 12px rgba(239,68,68,.3);color:#fff;"><i class="fas fa-fist-raised"></i></div>
        <div>
            <div style="font-size:17px;font-weight:800;color:#991b1b;margin-bottom:4px;">Anda Gugur di Babak ke-{{ $myParticipant->eliminated_at_round }}</div>
            <div style="font-size:13px;color:#b91c1c;">Jangan menyerah! Terus semangat belajar untuk kesempatan berikutnya.</div>
        </div>
    @elseif($myStatus === 'active')
        <div style="width:48px;height:48px;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;box-shadow:0 4px 12px rgba(29,179,73,.3);color:#fff;"><i class="fas fa-check-circle"></i></div>
        <div style="flex:1;">
            <div style="font-size:17px;font-weight:800;color:#166534;margin-bottom:4px;">Anda Aktif di Babak ke-{{ $myParticipant->current_round_sequence }}</div>
            <div style="font-size:13px;color:#15803d;">Terus semangat! Persiapkan diri untuk babak berikutnya.</div>
        </div>
    @else
        <div style="width:48px;height:48px;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;box-shadow:0 4px 12px rgba(29,179,73,.3);color:#fff;"><i class="fas fa-bullseye"></i></div>
        <div>
            <div style="font-size:17px;font-weight:800;color:#166534;margin-bottom:4px;">Anda Terdaftar di Event Ini</div>
            <div style="font-size:13px;color:#15803d;">Pantau alur turnamen di bawah untuk mengikuti perkembangan babak.</div>
        </div>
    @endif
</div>
@endif

{{-- Bracket Visual --}}
<div style="background:#fff;border-radius:24px;box-shadow:0 4px 16px rgba(0,0,0,.06);margin-bottom:24px;overflow:hidden;">
    <div style="padding:20px 24px;border-bottom:1px solid var(--color-border);display:flex;align-items:center;justify-content:space-between;">
        <h3 style="font-size:16px;font-weight:800;margin:0;"><i class="fas fa-sitemap" style="color:var(--color-primary);margin-right:8px;"></i>Bagan Turnamen</h3>
        <div style="display:flex;align-items:center;gap:10px;">
            <div style="display:flex;align-items:center;gap:6px;font-size:11px;color:var(--color-text-tertiary);">
                <div style="width:12px;height:12px;border-radius:4px;background:var(--grad-start);"></div> Posisi Anda
                <div style="width:12px;height:12px;border-radius:4px;background:#22c55e;margin-left:8px;"></div> Lolos
                <div style="width:12px;height:12px;border-radius:4px;background:#ef4444;margin-left:8px;"></div> Gugur
                <div style="font-size:14px;margin-left:8px;color:#ca8a04;"><i class="fas fa-trophy"></i></div> Juara
            </div>
            <span id="pLastUpdated" style="font-size:11px;color:var(--color-text-tertiary);font-weight:600;"></span>
        </div>
    </div>
    <div class="p-bracket-container">
        <div class="p-bracket-flow" id="pBracketFlow"></div>
    </div>
</div>

{{-- Timeline --}}
<div class="mobile-p-4" style="background:#fff;border-radius:24px;padding:24px 28px;box-shadow:0 4px 16px rgba(0,0,0,.04);margin-bottom:24px;">
    <h3 style="font-size:16px;font-weight:800;margin-bottom:20px;"><i class="fas fa-calendar-alt" style="color:var(--color-primary);margin-right:8px;"></i>Timeline Babak</h3>
    <div class="timeline-row">
        @foreach($bracketData as $round)
        @php
            $roundStatus = $round['status'];
            $colors = match($roundStatus) {
                'completed' => ['bg' => 'linear-gradient(135deg,#64748b,#475569)', 'border' => '#e2e8f0'],
                'ongoing'   => ['bg' => 'linear-gradient(135deg,#22c55e,#16a34a)', 'border' => '#bbf7d0'],
                default     => ['bg' => 'linear-gradient(135deg,#94a3b8,#64748b)', 'border' => '#e2e8f0'],
            };
        @endphp
        <div class="timeline-item" style="border-left:4px solid {{ $colors['border'] }};">
            <div class="timeline-num" style="background:{{ $colors['bg'] }};">{{ $round['sequence'] }}</div>
            <div style="flex:1;">
                <div style="font-size:14px;font-weight:800;color:var(--color-text-primary);margin-bottom:3px;">{{ $round['name'] }}</div>
                <div style="font-size:12px;color:var(--color-text-tertiary);">
                    {{ $round['start_time_label'] ?? 'Belum dijadwalkan' }}
                    @if($round['advancement_limit'] && $round['round_type'] !== 'final')
                        &bull; Top {{ $round['advancement_limit'] }} lolos ke babak berikutnya
                    @elseif($round['round_type'] === 'final')
                        &bull; Babak puncak — Juara ditentukan
                    @endif
                </div>
            </div>
            <div>
                @if($roundStatus === 'completed')
                    <span style="background:#f1f5f9;color:#475569;padding:3px 10px;border-radius:100px;font-size:11px;font-weight:800;"><i class="fas fa-check-circle" style="margin-right:4px;"></i> Selesai</span>
                @elseif($roundStatus === 'ongoing')
                    <span style="background:#dcfce7;color:#16a34a;padding:3px 10px;border-radius:100px;font-size:11px;font-weight:800;"><i class="fas fa-circle" style="font-size:7px;animation:pulse-dot 1.5s infinite;vertical-align:middle;"></i> Berlangsung</span>
                @else
                    <span style="background:#f1f5f9;color:#64748b;padding:3px 10px;border-radius:100px;font-size:11px;font-weight:800;">⏳ Mendatang</span>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<script>
const myParticipantId = {{ $myParticipant?->id ?? 'null' }};
let bracketData = @json($bracketData);
const bracketJsonUrl = '{{ route("organizer.events.bracket.json", $event) }}';

function renderParticipantBracket(data) {
    const flow = document.getElementById('pBracketFlow');
    flow.innerHTML = '';

    data.forEach((round, idx) => {
        if (idx > 0) {
            const connector = document.createElement('div');
            connector.className = 'bracket-connector';
            connector.innerHTML = round.is_final_round
                ? '<i class="fas fa-trophy" style="color:#f59e0b;font-size:16px;"></i>'
                : '<i class="fas fa-chevron-right"></i>';
            flow.appendChild(connector);
        }

        const col = document.createElement('div');
        col.className = 'p-bracket-col';

        const statusColors = { upcoming: '#94a3b8', ongoing: '#f59e0b', completed: '#10b981' };
        const statusColor = statusColors[round.status] || '#94a3b8';
        const statusText = round.status === 'upcoming' ? 'Mendatang' : (round.status === 'ongoing' ? 'Berlangsung' : 'Selesai');

        col.innerHTML = `
            <div class="p-bracket-col-header">
                <div class="round-badge" style="color:${statusColor};">
                    <span class="round-status-dot dot-${round.status}" style="background:${statusColor};${round.status==='ongoing'?'animation:pulse-dot 1.5s infinite;':''}"></span> ${statusText}
                </div>
                <div class="p-col-title">${escapeHtml(round.name)}</div>
                <div class="p-col-meta">
                    <i class="far fa-calendar-alt"></i> ${round.start_time_label||'—'}<br>
                    <i class="fas fa-users" style="margin-top:4px;"></i> ${round.total_entrants} peserta
                </div>
                ${round.advancement_status === 'done' ? '<div style="margin-top:6px;font-size:11px;font-weight:800;color:#10b981;"><i class="fas fa-check-circle"></i> Hasil Diproses</div>' : ''}
            </div>
        `;

        const slotsDiv = document.createElement('div');
        slotsDiv.className = 'p-bracket-slots';

        if (round.slots.length === 0) {
            const placeholderCount = round.advancement_limit || 2;
            for (let i = 0; i < placeholderCount; i++) {
                slotsDiv.innerHTML += `
                    <div class="p-bracket-slot s-upcoming empty">
                        <div class="p-slot-avatar" style="background:#f8fafc;color:#cbd5e1;">?</div>
                        <div>
                            <div class="p-slot-name" style="color:#94a3b8;">Menunggu Peserta</div>
                            <div class="p-slot-inst">Akan ditentukan kemudian</div>
                        </div>
                    </div>`;
            }
        } else {
            round.slots.forEach((slot, i) => {
                let statusClass = 's-' + slot.bracket_status;
                let extraIcon = '';
                if (slot.is_champion)    extraIcon = '<span class="p-slot-champ" style="color:#f59e0b;position:absolute;top:-6px;right:-6px;font-size:18px;"><i class="fas fa-crown"></i></span>';
                if (slot.is_eliminated)  extraIcon = '<i class="fas fa-times" style="color:#ef4444;font-size:14px;margin-left:8px;opacity:0.8;"></i>';
                if (slot.is_projected)   extraIcon += '<span class="projected-badge"><i class="fas fa-eye" style="margin-right:3px;"></i> SEMENTARA</span>';

                let isMeClass = slot.participant_id === myParticipantId ? 'is-me' : '';

                const avatarHtml = slot.avatar_url
                    ? `<img src="${escapeHtml(slot.avatar_url)}" class="p-slot-avatar" alt="Avatar">`
                    : `<div class="p-slot-avatar" style="background:linear-gradient(135deg,#dcfce7,#10b981);color:#fff;">${escapeHtml(slot.name[0]||'?').toUpperCase()}</div>`;

                const meTag = (slot.participant_id === myParticipantId) ? '<span style="background:var(--color-primary);color:#fff;font-size:9px;font-weight:800;border-radius:4px;padding:2px 6px;margin-top:4px;display:inline-block;">ANDA</span>' : '';
                const scoreHtml = slot.score !== null && !slot.is_eliminated
                    ? `<div class="p-slot-score">${parseFloat(slot.score).toFixed(1)}</div>` : '';

                const safeName = escapeJS(slot.name);
                const safeInst = escapeJS(slot.institution || '');
                const safeMajor = escapeJS(slot.major || '');
                const safeAvatar = escapeJS(slot.avatar_url || '');
                const rank = i + 1;

                slotsDiv.innerHTML += `
                    <div class="p-bracket-slot ${statusClass} ${isMeClass}" onclick="showIdCard({name:'${safeName}', institution:'${safeInst}', major:'${safeMajor}', rank:${rank}, avatar_url:'${safeAvatar}'})">
                        ${extraIcon}
                        ${avatarHtml}
                        <div style="flex:1;min-width:0;">
                            <div class="p-slot-name">${escapeHtml(slot.name)}</div>
                            <div class="p-slot-inst">${escapeHtml(slot.institution)}</div>
                            <div class="p-slot-major">${escapeHtml(slot.major)} ${meTag}</div>
                        </div>
                        ${scoreHtml}
                    </div>`;
            });
        }

        col.appendChild(slotsDiv);
        flow.appendChild(col);
    });

    document.getElementById('pLastUpdated').textContent = new Date().toLocaleTimeString('id-ID');
}

// Helpers
function escapeHtml(unsafe) {
    if (!unsafe) return '';
    return unsafe.toString()
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
}

function escapeJS(unsafe) {
    if (!unsafe) return '';
    return unsafe.toString().replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '\\"').replace(/\n/g, '\\n').replace(/\r/g, '\\r');
}

// Initial render
renderParticipantBracket(bracketData);

// Realtime polling every 30s
setInterval(() => {
    fetch(bracketJsonUrl)
        .then(r => r.json())
        .then(data => {
            renderParticipantBracket(data.bracket);
        });
}, 30000);
</script>
@endpush
