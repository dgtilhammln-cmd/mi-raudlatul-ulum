@extends('layouts.app')
@section('title', 'Setup Bagan Turnamen — ' . $event->name)
@section('page-title', 'Setup Bagan Turnamen')

@push('styles')
<style>
    .wizard-hero {
        background: linear-gradient(135deg, var(--grad-start), var(--grad-end));
        border-radius: 20px; padding: 32px 40px; margin-bottom: 28px;
        position: relative; overflow: hidden; color: #fff;
    }
    .wizard-hero::before {
        content:''; position:absolute; top:-30px; right:-30px;
        width:180px; height:180px; background:rgba(255,255,255,.07); border-radius:50%;
    }
    .wizard-hero h1 { font-size:26px; font-weight:900; margin-bottom:6px; }
    .wizard-hero p  { font-size:14px; color:rgba(255,255,255,.75); }

    .mode-badge {
        display:inline-flex; align-items:center; gap:8px;
        background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.25);
        border-radius:100px; padding:6px 18px; font-size:13px; font-weight:700;
        color:#fff; margin-top:12px;
    }

    .round-setup-card {
        background: #fff; border-radius: 20px; overflow: hidden;
        box-shadow: 0 4px 16px rgba(0,0,0,.06); margin-bottom: 20px;
        border: 1px solid rgba(0,0,0,.04);
        transition: box-shadow .2s;
    }
    .round-setup-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.1); }
    .round-card-header {
        padding: 18px 24px; display: flex; align-items: center; gap: 16px;
        cursor: pointer; border-bottom: 1px solid transparent;
        transition: border-color .2s;
    }
    .round-card-header.active { border-bottom-color: rgba(0,0,0,.06); }
    .round-badge {
        width: 40px; height: 40px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 16px; font-weight: 900; color: #fff; flex-shrink: 0;
    }
    .round-badge.qualification  { background: linear-gradient(135deg, #6366f1, #4f46e5); }
    .round-badge.group_stage    { background: linear-gradient(135deg, #3b82f6, #2563eb); }
    .round-badge.round_of_64   { background: linear-gradient(135deg, #0ea5e9, #0284c7); }
    .round-badge.round_of_32   { background: linear-gradient(135deg, #14b8a6, #0d9488); }
    .round-badge.quarter_final  { background: linear-gradient(135deg, #f59e0b, #d97706); }
    .round-badge.semi_final     { background: linear-gradient(135deg, #ef4444, #dc2626); }
    .round-badge.final          { background: linear-gradient(135deg, #f59e0b, #ca8a04); box-shadow: 0 4px 12px rgba(202,138,4,.3); }

    .round-card-title { font-size: 16px; font-weight: 800; color: var(--color-text-primary); margin-bottom: 2px; }
    .round-card-sub   { font-size: 12px; color: var(--color-text-tertiary); font-weight: 500; }
    .round-card-body  { padding: 24px; display: none; }
    .round-card-body.open { display: block; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

    .flow-preview {
        background: var(--color-surface-soft); border-radius: 16px; padding: 24px;
        margin-bottom: 24px; display: flex; align-items: center; gap: 0;
        overflow-x: auto; scrollbar-width: none;
    }
    .flow-node {
        flex-shrink: 0; text-align: center; min-width: 120px;
    }
    .flow-node-inner {
        background: #fff; border: 2px solid var(--color-border); border-radius: 14px;
        padding: 12px 14px; margin: 0 6px; transition: .2s;
    }
    .flow-node-inner.active {
        border-color: var(--grad-start);
        box-shadow: 0 4px 12px rgba(29,179,73,.15);
    }
    .flow-node-type { font-size: 10px; font-weight: 800; color: var(--color-text-tertiary); text-transform: uppercase; letter-spacing: .5px; margin-bottom: 4px; }
    .flow-node-label { font-size: 12px; font-weight: 700; color: var(--color-text-primary); margin-bottom: 4px; }
    .flow-node-count { font-size: 18px; font-weight: 900; color: var(--grad-start); }
    .flow-arrow { color: var(--color-text-tertiary); font-size: 20px; flex-shrink: 0; margin: 0 4px; }
    .flow-champion { font-size: 24px; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="wizard-hero">
    <div style="position:relative;z-index:2;">
        <div style="font-size:11px;font-weight:800;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:2px;margin-bottom:8px;">
            {{ $event->name }}
        </div>
        <h1><i class="fas fa-cogs"></i> Setup Bagan Turnamen</h1>
        <p>Konfigurasikan semua babak sekaligus. Bank soal dapat ditambahkan nanti.</p>
        <div class="mode-badge">
            <i class="fas fa-{{ $event->bracket_mode === 'full' ? 'list-ol' : 'bolt' }}"></i>
            {{ $event->getBracketModeLabel() }}
        </div>
    </div>
</div>

{{-- Flow Preview --}}
<div style="background:var(--color-surface);border-radius:20px;padding:24px;margin-bottom:24px;box-shadow:0 2px 8px rgba(0,0,0,.04);">
    <div style="font-size:12px;font-weight:700;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:.5px;margin-bottom:16px;">
        <i class="fas fa-stream" style="color:var(--color-primary);margin-right:6px;"></i> Diagram Alur Babak
    </div>
    <div class="flow-preview" id="flowPreview">
        @foreach($template as $i => $t)
        <div class="flow-node">
            <div class="flow-node-inner" id="flow-node-{{ $i }}">
                <div class="flow-node-type">Babak {{ $i + 1 }}</div>
                <div class="flow-node-label" id="flow-label-{{ $i }}">{{ $t['name'] }}</div>
                <div class="flow-node-count" id="flow-limit-{{ $i }}">
                    {{ $t['advancement_limit'] }}
                    <span style="font-size:10px;font-weight:600;color:var(--color-text-tertiary);">lolos</span>
                </div>
            </div>
        </div>
        @if(!$loop->last)
        <div class="flow-arrow"><i class="fas fa-arrow-right"></i></div>
        @else
        <div class="flow-arrow flow-champion" style="color:#ca8a04;"><i class="fas fa-trophy"></i></div>
        @endif
        @endforeach
    </div>
</div>

{{-- Setup Form --}}
<form action="{{ route('organizer.events.bracket.wizard.store', $event) }}" method="POST" id="wizardForm">
    @csrf

    @if($existingRounds->isNotEmpty())
    <div class="alert alert-warning" style="margin-bottom:20px;">
        <i class="fas fa-exclamation-triangle"></i>
        Event ini sudah memiliki {{ $existingRounds->count() }} babak. Menyimpan form ini akan <strong>mengganti semua babak yang ada</strong>.
    </div>
    @endif

    @foreach($template as $i => $t)
    @php
        $existing = $existingRounds->firstWhere('sequence', $i + 1);
        $badgeClass = $t['round_type'];
        $typeEmoji = match($t['round_type']) {
            'qualification' => '<i class="fas fa-flag"></i>',
            'group_stage'   => '<i class="fas fa-users"></i>',
            'round_of_64'  => '<i class="fas fa-th-large"></i>',
            'round_of_32'  => '<i class="fas fa-th"></i>',
            'quarter_final' => '<i class="fas fa-bolt"></i>',
            'semi_final'    => '<i class="fas fa-fire"></i>',
            'final'         => '<i class="fas fa-trophy" style="color:#ca8a04;"></i>',
            default => '<i class="fas fa-clipboard-list"></i>'
        };
    @endphp
    <div class="round-setup-card">
        <div class="round-card-header" onclick="toggleCard({{ $i }})">
            <div class="round-badge {{ $badgeClass }}">{{ $i + 1 }}</div>
            <div style="flex:1;">
                <div class="round-card-title">{!! $typeEmoji !!} {{ $t['name'] }}</div>
                <div class="round-card-sub" id="card-sub-{{ $i }}">
                    @if($existing)
                        {{ $existing->start_time?->translatedFormat('d M Y, H:i') }} — Lolos: {{ $existing->advancement_limit ?? '?' }} peserta
                    @else
                        Klik untuk mengisi detail babak ini
                    @endif
                </div>
            </div>
            <i class="fas fa-chevron-down" id="chevron-{{ $i }}" style="color:var(--color-text-tertiary);transition:.2s;"></i>
        </div>
        <div class="round-card-body {{ $i === 0 ? 'open' : '' }}" id="card-body-{{ $i }}">
            <input type="hidden" name="rounds[{{ $i }}][round_type]" value="{{ $t['round_type'] }}">

            <div class="form-group">
                <label class="form-label">Nama Babak</label>
                <input type="text" name="rounds[{{ $i }}][name]" value="{{ $existing?->name ?? $t['name'] }}"
                    class="form-input" placeholder="{{ $t['name'] }}" required
                    oninput="updateFlowLabel({{ $i }}, this.value)">
            </div>

            <div class="form-grid" style="margin-bottom:16px;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label"><i class="far fa-calendar" style="margin-right:4px;"></i>Waktu Mulai</label>
                    <input type="datetime-local" name="rounds[{{ $i }}][start_time]"
                        value="{{ $existing?->start_time?->format('Y-m-d\TH:i') }}"
                        class="form-input" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label"><i class="far fa-clock" style="margin-right:4px;"></i>Waktu Selesai</label>
                    <input type="datetime-local" name="rounds[{{ $i }}][end_time]"
                        value="{{ $existing?->end_time?->format('Y-m-d\TH:i') }}"
                        class="form-input" required>
                </div>
            </div>

            <div class="form-grid-3" style="margin-bottom:16px;">
                <div class="form-group" style="margin:0;">
                    <label class="form-label"><i class="fas fa-stopwatch" style="margin-right:4px;"></i>Durasi (menit)</label>
                    <input type="number" name="rounds[{{ $i }}][duration_minutes]"
                        value="{{ $existing?->duration_minutes ?? 60 }}"
                        class="form-input" min="5" max="480" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <label class="form-label"><i class="fas fa-question-circle" style="margin-right:4px;"></i>Jumlah Soal</label>
                    <input type="number" name="rounds[{{ $i }}][max_questions]"
                        value="{{ $existing?->max_questions ?? 30 }}"
                        class="form-input" min="1" max="200" required>
                </div>
                @if($t['round_type'] !== 'final')
                <div class="form-group" style="margin:0;">
                    <label class="form-label"><i class="fas fa-filter" style="margin-right:4px;"></i>Lolos ke Babak Berikutnya (Top-N)</label>
                    <input type="number" name="rounds[{{ $i }}][advancement_limit]"
                        value="{{ $existing?->advancement_limit ?? $t['advancement_limit'] }}"
                        class="form-input" min="1" placeholder="{{ $t['advancement_limit'] }}"
                        oninput="updateFlowLimit({{ $i }}, this.value)">
                </div>
                @else
                <div class="form-group" style="margin:0;">
                    <label class="form-label"><i class="fas fa-trophy" style="margin-right:4px;"></i>Grand Final</label>
                    <input type="hidden" name="rounds[{{ $i }}][advancement_limit]" value="1">
                    <div style="background:linear-gradient(135deg,#fffbeb,#fef9c3);border:1px solid #fde68a;border-radius:12px;padding:12px;font-size:13px;font-weight:700;color:#92400e;text-align:center;">
                        <i class="fas fa-trophy" style="color:#ca8a04;margin-right:6px;"></i> Juara ditentukan di babak ini
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    <div style="display:flex;gap:16px;justify-content:flex-end;margin-top:8px;">
        <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <button type="submit" class="btn btn-primary" style="padding:12px 32px;font-size:15px;">
            <i class="fas fa-save"></i> Simpan Semua Babak & Mulai
        </button>
    </div>
</form>

@endsection

@push('scripts')
<script>
    function toggleCard(i) {
        const body    = document.getElementById('card-body-' + i);
        const chevron = document.getElementById('chevron-' + i);
        const header  = body.previousElementSibling;
        const isOpen  = body.classList.contains('open');

        body.classList.toggle('open');
        chevron.style.transform = isOpen ? '' : 'rotate(180deg)';
        header.classList.toggle('active', !isOpen);
    }

    function updateFlowLabel(i, val) {
        const el = document.getElementById('flow-label-' + i);
        if (el) el.textContent = val || '...';
    }

    function updateFlowLimit(i, val) {
        const el = document.getElementById('flow-limit-' + i);
        if (el) {
            el.innerHTML = val + '<span style="font-size:10px;font-weight:600;color:var(--color-text-tertiary);"> lolos</span>';
        }
    }

    // Open first card by default
    document.addEventListener('DOMContentLoaded', () => {
        const header0 = document.getElementById('card-body-0')?.previousElementSibling;
        if (header0) header0.classList.add('active');
        document.getElementById('chevron-0').style.transform = 'rotate(180deg)';
    });
</script>
@endpush
