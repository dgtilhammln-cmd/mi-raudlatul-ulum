@extends('layouts.app')
@section('title', 'Bagan Turnamen — ' . $event->name)
@section('page-title', 'Bagan Turnamen')

@push('styles')
<style>
/* ═══ HERO ═══════════════════════════════════════════════════════════════ */
.bracket-hero {
    background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%);
    border-radius: 24px; padding: 36px 40px; margin-bottom: 28px;
    position: relative; overflow: hidden; color: #fff;
    box-shadow: 0 12px 32px rgba(29,179,73,.3);
}
.bracket-hero::before {
    content:''; position:absolute; top:-30px; right:-30px;
    width:200px; height:200px; background:rgba(255,255,255,.06); border-radius:50%;
}
.bracket-hero::after {
    content:''; position:absolute; bottom:-40px; left:30%;
    width:150px; height:150px; background:rgba(255,255,255,.04); border-radius:50%;
}
.bracket-hero-content { position:relative; z-index:2; }

/* ═══ BRACKET FLOW ════════════════════════════════════════════════════════ */
.bracket-container {
    overflow-x: auto; padding: 40px 24px 60px;
    -webkit-overflow-scrolling: touch;
    background: radial-gradient(circle at top, #ffffff 0%, #f8fafc 100%);
}
.bracket-flow {
    display: flex; align-items: stretch; gap: 0;
    min-width: max-content; margin: 0 auto;
}

/* Each Round Column */
.bracket-col {
    display: flex; flex-direction: column;
    align-items: center; width: 260px; position: relative;
    z-index: 2;
}
.bracket-col-header {
    text-align: center; margin-bottom: 30px;
    position: relative; width: 100%;
}
.bracket-col-title {
    font-size: 15px; font-weight: 900; color: #0f172a;
    margin-bottom: 6px; letter-spacing: -0.5px;
}
.bracket-col-meta {
    font-size: 12px; color: #64748b; font-weight: 600;
}
.round-badge {
    display: inline-flex; align-items: center; gap: 6px;
    background: #fff; border: 1px solid #e2e8f0; border-radius: 100px;
    padding: 4px 12px; font-size: 11px; font-weight: 800; box-shadow: 0 2px 8px rgba(0,0,0,.05);
    margin-bottom: 12px;
}
.round-status-dot {
    width: 8px; height: 8px; border-radius: 50%;
}
.dot-upcoming  { background: #94a3b8; }
.dot-ongoing   { background: #f59e0b; animation: pulse-dot 1.5s infinite; }
.dot-completed { background: #10b981; }

/* Slots */
.bracket-slots { 
    display: flex; flex-direction: column; gap: 12px; width: 100%;
}
.bracket-slot {
    background: #fff; border: 1.5px solid #e2e8f0; border-radius: 16px;
    padding: 12px 16px; display: flex; align-items: center; gap: 12px;
    cursor: pointer; transition: all .3s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    box-shadow: 0 4px 12px rgba(0,0,0,.02);
}
.bracket-slot::before {
    content:''; position:absolute; left:0; top:0; bottom:0; width:4px;
    background: #cbd5e1; transition: .3s; border-radius: 16px 0 0 16px;
}
.bracket-slot.status-submitted::before,
.bracket-slot.status-ongoing::before  { background: #3b82f6; }
.bracket-slot.status-eliminated::before { background: #ef4444; }
.bracket-slot.status-advanced::before  { background: #10b981; }
.bracket-slot.status-champion::before  { background: linear-gradient(180deg, #f59e0b, #d97706); }
.bracket-slot.status-projected { border-color: #ddd6fe; background: #faf5ff; }
.bracket-slot.status-projected::before { background: #8b5cf6; }
.projected-badge {
    position: absolute; top: -8px; right: -8px; background: linear-gradient(135deg, #8b5cf6, #7c3aed); color: #fff;
    font-size: 9px; font-weight: 800; padding: 3px 8px; border-radius: 100px;
    letter-spacing: .5px; box-shadow: 0 4px 12px rgba(139,92,246,.4); z-index:10;
}

.bracket-slot:hover { 
    border-color: var(--color-primary); box-shadow: 0 8px 24px rgba(29,179,73,.15); transform: translateY(-3px); 
}

.bracket-slot.status-eliminated { opacity: .6; filter: grayscale(50%); }
.bracket-slot.status-champion   { background: linear-gradient(135deg,#fffbeb,#fef9c3); border-color: #fde68a; }

.slot-avatar {
    width: 36px; height: 36px; border-radius: 12px; flex-shrink: 0;
    object-fit: cover; background: var(--color-surface-soft);
    display: flex; align-items: center; justify-content: center;
    font-weight: 900; font-size: 14px; color: var(--color-text-secondary);
    border: 1px solid #e2e8f0;
}
.slot-name  { font-size: 13px; font-weight: 800; color: #0f172a; line-height: 1.2; margin-bottom:2px; }
.slot-inst  { font-size: 10px; color: #64748b; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.slot-major { font-size: 10px; color: #94a3b8; font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.slot-score { font-size: 16px; font-weight: 900; color: var(--color-primary); margin-left: auto; flex-shrink:0; background:#f0fdf4; padding:4px 8px; border-radius:8px; }

.slot-champ-icon { position: absolute; top: -6px; right: -6px; font-size: 20px; text-shadow: 0 4px 8px rgba(202,138,4,.3); }

/* Empty slots */
.bracket-slot.empty {
    background: #f8fafc; border-style: dashed; border-color: #cbd5e1;
    pointer-events: none; opacity: .7; box-shadow:none;
}
.bracket-slot.empty::before { display:none; }

/* Arrow between columns - Sleek Pipeline */
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

/* ═══ ACTION PANEL ════════════════════════════════════════════════════════ */
.action-panel {
    background: #fff; border-radius: 20px; padding: 24px 28px;
    box-shadow: 0 4px 16px rgba(0,0,0,.06); margin-bottom: 24px;
    border: 1px solid rgba(0,0,0,.04);
}
.action-panel h3 { font-size: 16px; font-weight: 800; margin-bottom: 16px; }

.round-action-row {
    display: flex; align-items: center; justify-content: space-between;
    padding: 14px 18px; background: var(--color-surface-soft);
    border-radius: 14px; margin-bottom: 10px; gap: 12px; flex-wrap: wrap;
    transition: .2s; border: 1px solid transparent;
}
.round-action-row:hover { border-color: #e2e8f0; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,.03); }

/* ═══ CHAMPION BANNER ═════════════════════════════════════════════════════ */
.champion-banner {
    background: linear-gradient(135deg, #fef3c7, #fef9c3, #fffbeb);
    border: 2px solid #fde68a; border-radius: 24px; padding: 32px;
    text-align: center; margin-bottom: 28px;
    box-shadow: 0 8px 32px rgba(202,138,4,.15);
    position: relative; overflow: hidden;
}
.champion-banner::before, .champion-banner::after {
    content: '\f005'; font-family: 'Font Awesome 5 Free'; font-weight: 900; position: absolute; 
    font-size: 40px; opacity: .15; color: #ca8a04;
}
.champion-banner::before { top: -10px; left: -10px; }
.champion-banner::after { bottom: -10px; right: -10px; }

@keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1);} 50%{opacity:.5;transform:scale(1.2);} }
@keyframes sparkle { 0%,100%{transform:scale(1);} 50%{transform:scale(1.02);} }
@keyframes float { 0%,100%{transform:translateY(0);} 50%{transform:translateY(-5px);} }
</style>
@endpush

@section('content')

{{-- Champion Banner --}}
@if($champion)
<div class="champion-banner" style="animation:sparkle 2s ease-in-out infinite;">
    @if($champion->user->getAvatarUrl())
        <img src="{{ $champion->user->getAvatarUrl() }}" alt="{{ $champion->user->name }}"
            style="width:80px;height:80px;border-radius:20px;object-fit:cover;border:4px solid #fde68a;box-shadow:0 4px 16px rgba(202,138,4,.3);margin-bottom:16px;">
    @else
        <div style="width:80px;height:80px;border-radius:20px;background:linear-gradient(135deg,#fef3c7,#ca8a04);display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:900;color:#fff;margin:0 auto 16px;">
            {{ strtoupper(substr($champion->user->name ?? 'J', 0, 1)) }}
        </div>
    @endif
    <div style="font-size:13px;font-weight:800;color:#a16207;text-transform:uppercase;letter-spacing:1px;margin-bottom:8px;"><i class="fas fa-trophy"></i> JUARA {{ $event->name }}</div>
    <div style="font-size:28px;font-weight:900;color:#0f172a;margin-bottom:6px;">{{ $champion->user->name }}</div>
    <div style="font-size:14px;color:#78716c;font-weight:600;">{{ $champion->institution ?? '' }}</div>
</div>
@endif

{{-- Hero --}}
<div class="bracket-hero">
    <div class="bracket-hero-content">
        <div style="display:flex;align-items:flex-start;justify-content:space-between;flex-wrap:wrap;gap:20px;">
            <div>
                <div style="font-size:11px;font-weight:800;color:rgba(255,255,255,.5);text-transform:uppercase;letter-spacing:2px;margin-bottom:8px;">Bagan Turnamen</div>
                <h2 style="font-size:24px;font-weight:900;color:#fff;margin-bottom:6px;">{{ $event->name }}</h2>
                <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                    <span style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:100px;padding:4px 14px;font-size:12px;font-weight:700;color:#fff;">
                        <i class="fas fa-{{ $event->bracket_mode === 'full' ? 'list-ol' : 'bolt' }}" style="margin-right:6px;"></i>
                        {{ $event->getBracketModeLabel() }}
                    </span>
                    <span style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);border-radius:100px;padding:4px 14px;font-size:12px;font-weight:700;color:#fff;">
                        <i class="fas fa-layer-group" style="margin-right:6px;"></i>
                        {{ count($bracketData) }} Babak
                    </span>
                </div>
            </div>
            <div style="display:flex;gap:12px;flex-wrap:wrap;">
                <a href="{{ route('organizer.events.bracket.wizard', $event) }}" class="btn btn-secondary" style="background:rgba(255,255,255,.15);border-color:rgba(255,255,255,.2);color:#fff;">
                    <i class="fas fa-cog"></i> Edit Setup
                </a>
                <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-secondary" style="background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);color:rgba(255,255,255,.8);">
                    <i class="fas fa-arrow-left"></i> Event
                </a>
            </div>
        </div>

        {{-- Stats --}}
        <div style="display:flex;gap:20px;margin-top:24px;flex-wrap:wrap;">
            @php
                $totalParticipants = collect($bracketData)->first()['total_entrants'] ?? 0;
                $currentRound = collect($bracketData)->filter(fn($r) => $r['status'] === 'ongoing')->first();
                $completedRounds = collect($bracketData)->filter(fn($r) => $r['status'] === 'completed')->count();
            @endphp
            <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:14px;padding:14px 20px;">
                <div style="font-size:22px;font-weight:900;color:#fff;">{{ $totalParticipants }}</div>
                <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.6);text-transform:uppercase;">Total Peserta</div>
            </div>
            <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:14px;padding:14px 20px;">
                <div style="font-size:22px;font-weight:900;color:#4ade80;">{{ $completedRounds }}</div>
                <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.6);text-transform:uppercase;">Babak Selesai</div>
            </div>
            <div style="background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.15);border-radius:14px;padding:14px 20px;">
                <div style="font-size:22px;font-weight:900;color:#fbbf24;">{{ $champion ? 'Ada' : 'Belum' }}</div>
                <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.6);text-transform:uppercase;">Juara</div>
            </div>
        </div>
    </div>
</div>

{{-- Bracket Visual --}}
<div style="background:#fff;border-radius:24px;box-shadow:0 4px 16px rgba(0,0,0,.06);margin-bottom:24px;overflow:hidden;">
    <div style="padding:20px 28px 0;border-bottom:1px solid var(--color-border);display:flex;align-items:center;justify-content:space-between;">
        <h3 style="font-size:16px;font-weight:800;margin:0;"><i class="fas fa-sitemap" style="color:var(--color-primary);margin-right:8px;"></i>Bagan Turnamen Real-time</h3>
        <div style="display:flex;align-items:center;gap:8px;">
            <span id="lastUpdated" style="font-size:11px;color:var(--color-text-tertiary);font-weight:600;"></span>
            <button onclick="refreshBracket()" style="background:var(--color-surface-soft);border:none;border-radius:10px;padding:6px 12px;cursor:pointer;font-size:12px;font-weight:700;color:var(--color-primary);">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
    </div>
    <div class="bracket-container" id="bracketContainer">
        <div class="bracket-flow" id="bracketFlow">
            {{-- Rendered by JS --}}
        </div>
    </div>
</div>

{{-- Round Actions Panel --}}
<div class="action-panel">
    <h3><i class="fas fa-tasks" style="color:var(--color-primary);margin-right:8px;"></i>Aksi per Babak</h3>
    @foreach($bracketData as $round)
    <div class="round-action-row">
        <div style="display:flex;align-items:center;gap:14px;">
            <div style="width:36px;height:36px;background:{{ match($round['status']) {
                'ongoing' => 'linear-gradient(135deg,#f59e0b,#d97706)',
                'completed' => 'linear-gradient(135deg,#22c55e,#16a34a)',
                default => 'linear-gradient(135deg,#94a3b8,#64748b)'
            } }};border-radius:10px;display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:13px;">
                {{ $round['sequence'] }}
            </div>
            <div>
                <div style="font-size:14px;font-weight:800;color:var(--color-text-primary);">{{ $round['name'] }}</div>
                <div style="font-size:12px;color:var(--color-text-tertiary);">
                    {{ $round['start_time_label'] }} &bull;
                    <span class="round-status-dot dot-{{ $round['status'] }}"></span>
                    {{ ucfirst($round['status']) }} &bull;
                    {{ $round['total_submitted'] }}/{{ $round['total_entrants'] }} submit
                    @if($round['pending_essays'] > 0)
                        &bull; <span style="color:#f59e0b;font-weight:700;">{{ $round['pending_essays'] }} esai belum dinilai</span>
                    @endif
                </div>
            </div>
        </div>
        <div style="display:flex;gap:8px;flex-wrap:wrap;">
            @if($round['status'] === 'completed' && $round['advancement_status'] !== 'done' && !$round['is_final_round'])
            <button onclick="previewAdvance({{ $round['id'] }}, '{{ addslashes($round['name']) }}')"
                class="btn btn-primary btn-sm" style="animation:float 3s ease-in-out infinite;">
                <i class="fas fa-play"></i> Proses Hasil
            </button>
            @elseif($round['advancement_status'] === 'done')
            <span style="background:#dcfce7;color:#16a34a;padding:4px 12px;border-radius:100px;font-size:12px;font-weight:700;">
                <i class="fas fa-check"></i> Selesai Diproses
            </span>
            @endif
            <a href="{{ route('organizer.events.show', $event) }}#round-{{ $round['id'] }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-cog"></i> Detail
            </a>
        </div>
    </div>
    @endforeach
</div>



{{-- Preview Advance Modal --}}
<div class="slot-modal" id="advanceModal" onclick="if(event.target===this) closeAdvanceModal()">
    <div class="slot-modal-content" style="max-width:560px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
            <h3 style="font-size:18px;font-weight:900;margin:0;" id="advanceTitle">Preview Hasil Babak</h3>
            <button onclick="closeAdvanceModal()" style="background:var(--color-surface-soft);border:none;width:32px;height:32px;border-radius:10px;cursor:pointer;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="advanceContent"></div>
        <div id="advanceActions" style="margin-top:20px;display:flex;gap:12px;justify-content:flex-end;"></div>
    </div>
</div>

@endsection

@push('scripts')
<script>
const bracketData = @json($bracketData);
const eventSlug   = '{{ $event->slug }}';
const bracketJsonUrl = '{{ route("organizer.events.bracket.json", $event) }}';
let currentRoundId = null;

// ─── Render Bracket ──────────────────────────────────────────────────────────
function renderBracket(data) {
    const flow = document.getElementById('bracketFlow');
    flow.innerHTML = '';

    data.forEach((round, idx) => {
        // Add connector between columns
        if (idx > 0) {
            const connector = document.createElement('div');
            connector.className = 'bracket-connector';
            connector.innerHTML = round.is_final_round
                ? '<i class="fas fa-trophy" style="color:#f59e0b;font-size:16px;"></i>'
                : '<i class="fas fa-chevron-right"></i>';
            flow.appendChild(connector);
        }

        // Column
        const col = document.createElement('div');
        col.className = 'bracket-col';

        // Header
        const statusColors = { upcoming: '#94a3b8', ongoing: '#f59e0b', completed: '#10b981' };
        const statusColor = statusColors[round.status] || '#94a3b8';
        const statusText = round.status === 'upcoming' ? 'Mendatang' : (round.status === 'ongoing' ? 'Berlangsung' : 'Selesai');
        col.innerHTML = `
            <div class="bracket-col-header">
                <div class="round-badge" style="color:${statusColor};">
                    <span class="round-status-dot dot-${round.status}"></span> ${statusText}
                </div>
                <div class="bracket-col-title">${escapeHtml(round.name)}</div>
                <div class="bracket-col-meta">
                    <i class="far fa-calendar-alt"></i> ${round.start_time_label || '—'}<br>
                    <i class="fas fa-users" style="margin-top:4px;"></i> ${round.total_entrants} peserta
                </div>
                ${round.advancement_status === 'done' ? '<div style="margin-top:6px;font-size:11px;font-weight:800;color:#10b981;"><i class="fas fa-check-circle"></i> Hasil Diproses</div>' : ''}
            </div>
        `;

        // Slots
        const slotsDiv = document.createElement('div');
        slotsDiv.className = 'bracket-slots';

        if (round.slots.length === 0) {
            // Empty placeholder
            for (let i = 0; i < (round.advancement_limit || 2); i++) {
                slotsDiv.innerHTML += `
                    <div class="bracket-slot empty">
                        <div class="slot-avatar">?</div>
                        <div>
                            <div class="slot-name" style="color:#94a3b8;">Menunggu Peserta</div>
                            <div class="slot-inst">Akan ditentukan kemudian</div>
                        </div>
                    </div>`;
            }
        } else {
            round.slots.forEach((slot, i) => {
                let statusClass = 'status-' + slot.bracket_status;
                let extraIcon = '';
                if (slot.is_champion)    extraIcon = '<span class="slot-champ-icon" style="color:#f59e0b;"><i class="fas fa-crown"></i></span>';
                if (slot.is_eliminated)  extraIcon = '<i class="fas fa-times" style="color:#ef4444;font-size:14px;margin-left:8px;opacity:0.8;"></i>';
                if (slot.is_projected)   extraIcon += '<span class="projected-badge"><i class="fas fa-eye" style="margin-right:3px;"></i> SEMENTARA</span>';

                const avatarHtml = slot.avatar_url
                    ? `<img src="${escapeHtml(slot.avatar_url)}" class="slot-avatar" alt="Avatar">`
                    : `<div class="slot-avatar" style="background:linear-gradient(135deg,#dcfce7,#10b981);color:#fff;">${escapeHtml(slot.name[0]||'?').toUpperCase()}</div>`;

                const scoreHtml = slot.score !== null
                    ? `<div class="slot-score">${parseFloat(slot.score).toFixed(1)}</div>`
                    : '';

                // Using showIdCard instead of showSlotDetail, pass the rank as (i+1)
                const safeName = escapeJS(slot.name);
                const safeInst = escapeJS(slot.institution || '');
                const safeMajor = escapeJS(slot.major || '');
                const safeAvatar = escapeJS(slot.avatar_url || '');
                const rank = i + 1;

                slotsDiv.innerHTML += `
                    <div class="bracket-slot ${statusClass}" onclick="showIdCard({name:'${safeName}', institution:'${safeInst}', major:'${safeMajor}', rank:${rank}, avatar_url:'${safeAvatar}'})">
                        ${extraIcon}
                        ${avatarHtml}
                        <div style="flex:1;min-width:0;">
                            <div class="slot-name">${escapeHtml(slot.name)}</div>
                            <div class="slot-inst">${escapeHtml(slot.institution)}</div>
                            <div class="slot-major">${escapeHtml(slot.major)}</div>
                        </div>
                        ${scoreHtml}
                    </div>`;
            });
        }

        col.appendChild(slotsDiv);
        flow.appendChild(col);
    });

    document.getElementById('lastUpdated').textContent = 'Update: ' + new Date().toLocaleTimeString('id-ID');
}

// Initial render
renderBracket(bracketData);

// ─── Realtime Polling ─────────────────────────────────────────────────────────
function refreshBracket() {
    fetch(bracketJsonUrl)
        .then(r => r.json())
        .then(data => {
            renderBracket(data.bracket);
            if (data.champion) {
                document.getElementById('lastUpdated').textContent = 'Update: ' + new Date().toLocaleTimeString('id-ID') + ' — Ada Juara!';
            }
        });
}
setInterval(refreshBracket, 30000);

// Helper for safe HTML rendering
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

// ─── Preview & Confirm Advance ────────────────────────────────────────────────
function previewAdvance(roundId, roundName) {
    currentRoundId = roundId;
    document.getElementById('advanceTitle').textContent = 'Preview: ' + roundName;
    document.getElementById('advanceContent').innerHTML = '<div style="text-align:center;padding:32px;"><i class="fas fa-circle-notch fa-spin" style="font-size:28px;color:var(--color-primary);"></i></div>';
    document.getElementById('advanceActions').innerHTML = '';
    document.getElementById('advanceModal').classList.add('active');

    fetch(`/organizer/rounds/${roundId}/advance-preview`)
        .then(r => r.json())
        .then(data => {
            let html = '';

            if (!data.ready) {
                html = `<div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Babak belum siap diproses. Pastikan waktu ujian sudah selesai dan semua esai sudah dinilai.</div>`;
                document.getElementById('advanceContent').innerHTML = html;
                return;
            }

            html += `<div style="margin-bottom:16px;">
                <div style="font-size:13px;font-weight:800;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">
                    <i class="fas fa-check-circle" style="color:#22c55e;margin-right:6px;"></i> LOLOS (${data.will_advance.length} peserta)
                </div>`;
            data.will_advance.forEach((p, i) => {
                html += `<div style="display:flex;align-items:center;gap:12px;padding:10px;background:#f0fdf4;border-radius:10px;margin-bottom:6px;">
                    <div style="width:24px;height:24px;background:linear-gradient(135deg,#22c55e,#16a34a);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:10px;font-weight:900;flex-shrink:0;">${i+1}</div>
                    <div style="flex:1;"><div style="font-size:13px;font-weight:700;">${p.name}</div><div style="font-size:11px;color:var(--color-text-tertiary);">${p.institution}</div></div>
                    <div style="font-size:15px;font-weight:900;color:#16a34a;">${parseFloat(p.score).toFixed(1)}</div>
                </div>`;
            });
            html += `</div>`;

            if (data.will_eliminate.length > 0) {
                html += `<div>
                    <div style="font-size:13px;font-weight:800;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">
                        <i class="fas fa-times-circle" style="color:#ef4444;margin-right:6px;"></i> GUGUR (${data.will_eliminate.length} peserta)
                    </div>`;
                data.will_eliminate.forEach(p => {
                    html += `<div style="display:flex;align-items:center;gap:12px;padding:10px;background:#fff5f5;border-radius:10px;margin-bottom:6px;">
                        <div style="width:24px;height:24px;background:#fee2e2;border-radius:8px;display:flex;align-items:center;justify-content:center;color:#ef4444;font-size:14px;flex-shrink:0;">✕</div>
                        <div style="flex:1;"><div style="font-size:13px;font-weight:700;">${p.name}</div><div style="font-size:11px;color:var(--color-text-tertiary);">${p.institution}</div></div>
                    </div>`;
                });
                html += `</div>`;
            }

            document.getElementById('advanceContent').innerHTML = html;
            document.getElementById('advanceActions').innerHTML = `
                <button onclick="closeAdvanceModal()" class="btn btn-secondary">Batal</button>
                <form method="POST" action="/organizer/rounds/${roundId}/advance" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-play"></i> Konfirmasi & Proses
                    </button>
                </form>
            `;
        });
}

function closeAdvanceModal() {
    document.getElementById('advanceModal').classList.remove('active');
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
@include('components.id-card-modal')
@endpush
