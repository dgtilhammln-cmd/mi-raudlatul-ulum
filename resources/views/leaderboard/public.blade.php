@extends('layouts.public')
@section('title', 'Klasemen — ' . $event->name)
@section('page-title', $event->name)
@section('page-desc', 'Saksikan klasemen lengkap perolehan poin dari para peserta secara real-time.')
@section('hide-header', true)

@section('breadcrumb')
    <a href="/">Beranda</a> <i class="fas fa-chevron-right"></i>
    <a href="{{ route('leaderboard.index') }}">Leaderboard</a> <i class="fas fa-chevron-right"></i>
    <span>{{ $event->name }}</span>
@endsection

@push('styles')
<style>
    /* ══════ TOURNAMENT BRACKET – PREMIUM ══════ */
    .bracket-wrapper {
        background: linear-gradient(135deg, #0a1f10 0%, #0f2d1a 50%, #051a0c 100%);
        border-radius: 28px;
        padding: 40px;
        margin-bottom: 32px;
        position: relative;
        overflow: hidden;
        border: 1px solid rgba(29,179,73,0.15);
        box-shadow: 0 32px 80px rgba(0,0,0,0.3);
    }
    .bracket-wrapper::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 300px; height: 300px;
        background: radial-gradient(circle, rgba(29,179,73,0.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .bracket-wrapper::after {
        content: '';
        position: absolute;
        bottom: -100px; left: -100px;
        width: 400px; height: 400px;
        background: radial-gradient(circle, rgba(165,207,54,0.07) 0%, transparent 70%);
        pointer-events: none;
    }
    .bracket-title {
        text-align: center;
        margin-bottom: 36px;
        position: relative;
        z-index: 2;
    }
    .bracket-title h2 {
        font-size: 22px;
        font-weight: 900;
        color: #fff;
        letter-spacing: -0.5px;
    }
    .bracket-title p {
        font-size: 13px;
        color: rgba(255,255,255,0.4);
        margin-top: 4px;
        font-weight: 600;
    }
    .bracket-container {
        display: flex;
        align-items: stretch;
        gap: 0;
        overflow-x: auto;
        position: relative;
        z-index: 2;
        padding-bottom: 4px;
    }
    .bracket-col-wrap {
        display: flex;
        align-items: center;
        flex-shrink: 0;
    }
    .bracket-connector {
        width: 32px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .bracket-connector::before {
        content: '';
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, rgba(29,179,73,0.5), rgba(29,179,73,0.1));
    }
    .bracket-column {
        width: 220px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .bracket-col-header {
        text-align: center;
        margin-bottom: 16px;
    }
    .bracket-col-header .col-round-name {
        font-size: 13px;
        font-weight: 900;
        color: #fff;
        letter-spacing: 0.3px;
    }
    .bracket-col-header .col-round-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 10px;
        color: rgba(255,255,255,0.4);
        font-weight: 700;
        margin-top: 4px;
        background: rgba(255,255,255,0.05);
        padding: 2px 10px;
        border-radius: 100px;
        border: 1px solid rgba(255,255,255,0.08);
    }
    .bracket-card {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 16px;
        overflow: hidden;
        transition: 0.2s;
    }
    .bracket-card:hover {
        background: rgba(255,255,255,0.07);
        border-color: rgba(29,179,73,0.3);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    }
    .bracket-card-label {
        padding: 7px 14px;
        font-size: 9px;
        font-weight: 800;
        color: rgba(255,255,255,0.3);
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        background: rgba(255,255,255,0.02);
    }
    .bp-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
        cursor: pointer;
        transition: 0.15s;
        gap: 8px;
    }
    .bp-row:last-child { border-bottom: none; }
    .bp-row:hover { background: rgba(255,255,255,0.04); }
    .bp-row.bp-winner {
        background: rgba(29,179,73,0.12);
        border-left: 3px solid #1db349;
    }
    .bp-row.bp-eliminated {
        opacity: 0.4;
    }
    .bp-row .bp-left {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        flex: 1;
    }
    .bp-row .bp-av {
        width: 26px; height: 26px;
        border-radius: 8px;
        background: rgba(255,255,255,0.08);
        flex-shrink: 0;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 900;
        color: rgba(255,255,255,0.5);
    }
    .bp-row .bp-av img { width: 100%; height: 100%; object-fit: cover; }
    .bp-row .bp-nm {
        font-size: 12px;
        font-weight: 700;
        color: rgba(255,255,255,0.8);
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .bp-row.bp-winner .bp-nm { color: #fff; }
    .bp-row .bp-sc {
        font-size: 13px;
        font-weight: 900;
        color: rgba(255,255,255,0.3);
        flex-shrink: 0;
    }
    .bp-row.bp-winner .bp-sc { color: #4ade80; }
    .bp-more {
        padding: 8px 14px;
        text-align: center;
        font-size: 10px;
        color: rgba(29,179,73,0.7);
        font-weight: 800;
        background: rgba(29,179,73,0.05);
        border-top: 1px solid rgba(29,179,73,0.1);
        letter-spacing: 0.5px;
    }
    .bp-empty {
        padding: 24px;
        text-align: center;
        font-size: 11px;
        color: rgba(255,255,255,0.2);
        font-weight: 600;
    }

    /* FINAL COLUMN – golden accent */
    .bracket-col-wrap.is-final .bracket-column { width: 240px; }
    .bracket-col-wrap.is-final .bracket-card {
        border-color: rgba(29,179,73,0.3);
        background: rgba(29,179,73,0.04);
    }
    .bracket-col-wrap.is-final .bracket-card:hover {
        border-color: rgba(29,179,73,0.5);
        box-shadow: 0 8px 32px rgba(29,179,73,0.2);
    }
    .bracket-col-wrap.is-final .bracket-col-header .col-round-name { color: #4ade80; }
    .bracket-col-wrap.is-final .bp-row.bp-winner {
        background: rgba(29,179,73,0.18);
        border-left: 3px solid #1db349;
        position: relative;
    }
    .bracket-col-wrap.is-final .bp-row.bp-winner::after {
        content: '\2605 JUARA';
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 8px;
        font-weight: 900;
        color: #1db349;
        background: rgba(29,179,73,0.15);
        padding: 2px 8px;
        border-radius: 100px;
        letter-spacing: 0.5px;
        border: 1px solid rgba(29,179,73,0.4);
        animation: winner-pulse 2s ease-in-out infinite;
    }
    @keyframes winner-pulse {
        0%, 100% { opacity: 1; box-shadow: 0 0 0 0 rgba(29,179,73,0); }
        50% { opacity: 0.85; box-shadow: 0 0 8px rgba(29,179,73,0.4); }
    }
    .bracket-col-wrap.is-final .bp-row.bp-winner .bp-nm { color: #fff; font-size: 13px; }
    .bracket-col-wrap.is-final .bp-row.bp-winner .bp-sc { color: #a5cf36; font-size: 15px; font-weight: 900; margin-right: 72px; }

    /* CHAMPION BANNER */
    .champion-banner {
        display: flex;
        align-items: center;
        gap: 20px;
        background: linear-gradient(135deg, #1db349 0%, #2ec55a 40%, #78c830 70%, #a5cf36 100%);
        border-radius: 20px;
        padding: 24px 32px;
        margin-bottom: 24px;
        border: 1px solid rgba(255,255,255,0.2);
        box-shadow: 0 16px 48px rgba(29,179,73,0.35), 0 4px 16px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    .champion-banner::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0.12) 100%);
        pointer-events: none;
    }
    .champion-banner::after {
        content: '🏆';
        position: absolute;
        right: 24px;
        font-size: 90px;
        opacity: 0.18;
        top: 50%;
        transform: translateY(-50%);
    }
    .champion-avatar {
        width: 72px; height: 72px;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.6);
        overflow: hidden;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.2);
        font-size: 26px;
        font-weight: 900;
        color: #fff;
        box-shadow: 0 0 0 6px rgba(255,255,255,0.1), 0 8px 24px rgba(0,0,0,0.15);
    }
    .champion-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .champion-info .ch-label {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 2px;
        color: rgba(255,255,255,0.75);
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .champion-info .ch-name {
        font-size: 22px;
        font-weight: 900;
        color: #fff;
        line-height: 1.2;
        letter-spacing: -0.5px;
        text-shadow: 0 1px 4px rgba(0,0,0,0.15);
    }
    .champion-info .ch-sub {
        font-size: 13px;
        color: rgba(255,255,255,0.7);
        margin-top: 4px;
        font-weight: 600;
    }
    .champion-score {
        margin-left: auto;
        text-align: right;
        flex-shrink: 0;
    }
    .champion-score .cs-val {
        font-size: 44px;
        font-weight: 900;
        color: #fff;
        line-height: 1;
        text-shadow: 0 2px 12px rgba(0,0,0,0.2);
        letter-spacing: -2px;
    }
    .champion-score .cs-label {
        font-size: 10px;
        color: rgba(255,255,255,0.65);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-top: 4px;
    }

    /* ══════ HERO BANNER ══════ */
    .lb-event-hero {
        border-radius: 28px;
        overflow: hidden;
        position: relative;
        min-height: 320px;
        display: flex;
        align-items: flex-end;
        margin-bottom: 32px;
    }
    .lb-event-hero .hero-bg {
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center;
        transition: transform 6s ease;
    }
    .lb-event-hero:hover .hero-bg { transform: scale(1.05); }
    .lb-event-hero .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, var(--grad-start) 0%, rgba(29,179,73,0.85) 40%, rgba(165,207,54,0.4) 100%);
    }
    .lb-event-hero .hero-content {
        position: relative;
        z-index: 2;
        padding: 48px;
        width: 100%;
    }
    .lb-event-hero .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(29,179,73,0.2);
        border: 1px solid rgba(29,179,73,0.4);
        color: #4ade80;
        padding: 6px 16px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 16px;
    }
    .lb-event-hero h1 {
        font-size: clamp(24px, 4vw, 36px);
        font-weight: 800;
        color: #fff;
        margin-bottom: 16px;
        letter-spacing: -0.3px;
        line-height: 1.2;
    }
    .lb-event-hero .hero-stats {
        display: flex;
        gap: 32px;
        flex-wrap: wrap;
    }
    .lb-event-hero .hero-stats .hs-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255,255,255,0.7);
        font-size: 14px;
        font-weight: 600;
    }
    .lb-event-hero .hero-stats .hs-item i {
        color: #4ade80;
    }

    /* ══════ ROUNDS TRACKER ══════ */
    .rounds-tracker {
        background: #fff;
        border-radius: 20px;
        padding: 28px 32px;
        margin-bottom: 28px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    }
    .rounds-tracker h3 {
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .rounds-tracker h3 i { color: var(--grad-start); }
    .rounds-flow {
        display: flex;
        align-items: center;
        gap: 0;
        overflow-x: auto;
        padding-bottom: 4px;
    }
    .round-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 120px;
        flex-shrink: 0;
        position: relative;
    }
    .round-step .step-circle {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: 900;
        margin-bottom: 8px;
        position: relative;
        z-index: 2;
        transition: 0.3s;
    }
    .round-step.active .step-circle {
        background: linear-gradient(135deg, var(--grad-start), var(--grad-end));
        color: #fff;
        box-shadow: 0 8px 20px rgba(29,179,73,0.3);
    }
    .round-step.done .step-circle {
        background: #f0fdf4;
        color: var(--grad-start);
        border: 2px solid var(--grad-start);
    }
    .round-step.pending .step-circle {
        background: #f1f5f9;
        color: #94a3b8;
        border: 2px solid #e2e8f0;
    }
    .round-step .step-name {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-align: center;
        max-width: 100px;
    }
    .round-step .step-count {
        font-size: 10px;
        color: #94a3b8;
        font-weight: 600;
        margin-top: 2px;
    }
    .round-connector {
        flex: 1;
        min-width: 24px;
        height: 3px;
        background: #e2e8f0;
        margin-top: -30px;
    }
    .round-connector.done { background: linear-gradient(90deg, var(--grad-start), var(--grad-end)); }

    /* ══════ PODIUM ══════ */
    .podium-section {
        display: grid;
        grid-template-columns: 1fr 1.15fr 1fr;
        gap: 16px;
        margin-bottom: 28px;
        align-items: flex-end;
    }
    .pod-card {
        border-radius: 24px;
        padding: 28px 20px;
        text-align: center;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .pod-card:hover { transform: translateY(-6px); }
    .pod-card.pod-1 {
        background: linear-gradient(135deg, #0f2e1a 0%, #1a4a28 100%);
        border: 1px solid rgba(29,179,73,0.3);
        box-shadow: 0 16px 40px rgba(29,179,73,0.2);
    }
    .pod-card.pod-2 {
        background: #fff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        border-top: 4px solid #94a3b8;
    }
    .pod-card.pod-3 {
        background: #fff;
        border: 1px solid #e2e8f0;
        box-shadow: 0 8px 24px rgba(0,0,0,0.05);
        border-top: 4px solid #d97706;
    }
    .pod-rank {
        font-size: 32px;
        font-weight: 800;
        margin-bottom: 10px;
    }
    .pod-1 .pod-rank { color: #fbbf24; }
    .pod-2 .pod-rank { color: #94a3b8; }
    .pod-3 .pod-rank { color: #d97706; }
    .pod-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        margin: 0 auto 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .pod-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .pod-avatar .initial {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 900;
        font-size: 22px;
    }
    .pod-1 .pod-avatar .initial { background: linear-gradient(135deg, #fef3c7, #fbbf24); color: #92400e; }
    .pod-2 .pod-avatar .initial { background: #f1f5f9; color: #64748b; }
    .pod-3 .pod-avatar .initial { background: linear-gradient(135deg, #ffedd5, #f59e0b); color: #78350f; }
    .pod-name { font-weight: 800; font-size: 14px; margin-bottom: 4px; }
    .pod-1 .pod-name { color: #fff; }
    .pod-inst { font-size: 11px; margin-bottom: 14px; }
    .pod-1 .pod-inst { color: rgba(255,255,255,0.5); }
    .pod-2 .pod-inst, .pod-3 .pod-inst { color: #94a3b8; }
    .pod-score { font-size: 28px; font-weight: 800; }
    .pod-1 .pod-score { color: #fbbf24; }
    .pod-2 .pod-score { color: #64748b; }
    .pod-3 .pod-score { color: #d97706; }
    .pod-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
    .pod-1 .pod-label { color: rgba(255,255,255,0.4); }
    .pod-2 .pod-label, .pod-3 .pod-label { color: #94a3b8; }

    /* ══════ TABLE ══════ */
    .lb-table-card {
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
    }
    .lb-table-header {
        padding: 20px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid #f1f5f9;
    }
    .lb-table-header h3 {
        font-size: 16px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .lb-table-header h3 i { color: var(--grad-start); }
    #lb-updated { font-size: 11px; color: #94a3b8; font-weight: 600; }

    @media (max-width: 768px) {
        .lb-event-hero .hero-content { padding: 28px; }
        .podium-section { grid-template-columns: 1fr; gap: 12px; }
        .pod-card.pod-1 { order: -1; }
    }
    @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(1.5)} }
</style>
@endpush

@section('content')

{{-- Hero Banner with Event Image --}}
@php
    $heroBg = $event->poster_image ? asset('storage/'.$event->poster_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&q=80&w=1200';
@endphp
<div class="lb-event-hero">
    <div class="hero-bg" style="background-image:url('{{ $heroBg }}');"></div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-badge">
            @if($event->status === 'ongoing')
                <span style="width:8px;height:8px;background:#4ade80;border-radius:50%;animation:pulse-dot 1.5s infinite;"></span> LIVE
            @else
                <i class="fas fa-flag-checkered" style="font-size:10px;"></i> SELESAI
            @endif
        </div>
        <h1>{{ $event->name }}</h1>
        <div class="hero-stats">
            <div class="hs-item"><i class="fas fa-users"></i> {{ $event->participants()->count() }} Peserta</div>
            <div class="hs-item"><i class="fas fa-layer-group"></i> {{ $rounds->count() }} Babak</div>
            <div class="hs-item"><i class="far fa-calendar-alt"></i> {{ $event->start_date->translatedFormat('d M Y') }}</div>
            <div class="hs-item"><i class="fas fa-sitemap"></i> {{ $event->isQualificationSystem() ? $event->getBracketModeLabel() : 'Sistem Poin' }}</div>
        </div>
    </div>
</div>

{{-- Rounds Tracker --}}
@if($rounds->count() > 0)
<div class="rounds-tracker">
    <h3><i class="fas fa-route"></i> Alur Babak Kompetisi</h3>
    <div class="rounds-flow">
        @foreach($rounds as $idx => $round)
        @php
            $hasSessions = $round->exam_sessions_count > 0;
            $isLast = $loop->last;
            $stepClass = $hasSessions ? 'done' : 'pending';
        @endphp
        <div class="round-step {{ $stepClass }}">
            <div class="step-circle">{{ $round->sequence }}</div>
            <div class="step-name">{{ $round->name }}</div>
            @if($hasSessions)
            <div class="step-count">{{ $round->exam_sessions_count }} sesi</div>
            @endif
        </div>
        @if(!$isLast)
        <div class="round-connector {{ $hasSessions ? 'done' : '' }}"></div>
        @endif
        @endforeach
    </div>
</div>
@endif

@if($event->isQualificationSystem() && $bracketData)
    {{-- Champion Banner (find champion from last round) --}}
    @php
        $lastBracketRound = collect($bracketData)->last();
        $champion = null;
        if ($lastBracketRound) {
            $champSlot = collect($lastBracketRound['slots'])->sortByDesc('score')->first();
            if ($champSlot && $champSlot['score'] !== null) {
                $champion = $champSlot;
            }
        }
    @endphp
    @if($champion)
    <div class="champion-banner" id="champion-banner">
        <div class="champion-avatar">
            @if(!empty($champion['avatar_url']))
                <img src="{{ $champion['avatar_url'] }}" alt="">
            @else
                {{ strtoupper(substr($champion['name'], 0, 1)) }}
            @endif
        </div>
        <div class="champion-info">
            <div class="ch-label"><i class="fas fa-crown" style="color:#fbbf24;"></i> Juara / Pemenang Turnamen</div>
            <div class="ch-name">{{ $champion['name'] }}</div>
            <div class="ch-sub">{{ $champion['institution'] }}{{ !empty($champion['major']) ? ' · '.$champion['major'] : '' }}</div>
        </div>
        <div class="champion-score">
            <div class="cs-val">{{ number_format($champion['score'], 1) }}</div>
            <div class="cs-label">Poin Final</div>
        </div>
    </div>
    @endif

    {{-- Tournament Bracket --}}
    <div class="bracket-wrapper">
        <div class="bracket-title">
            <h2><i class="fas fa-sitemap" style="color:#1db349;margin-right:8px;"></i> Bagan Turnamen</h2>
            <p>{{ $event->name }}</p>
        </div>
        <div class="bracket-container">
            @foreach($bracketData as $bIdx => $bRound)
            @php $isFinal = $loop->last; @endphp
            <div class="bracket-col-wrap {{ $isFinal ? 'is-final' : '' }}">
                <div class="bracket-column">
                    <div class="bracket-col-header">
                        <div class="col-round-name">{{ $bRound['name'] }}</div>
                        <div class="col-round-badge">
                            <i class="fas fa-users" style="font-size:8px;"></i>
                            {{ $bRound['total_entrants'] }} peserta
                        </div>
                    </div>
                    <div class="bracket-card">
                        <div class="bracket-card-label">{{ $bRound['round_type_label'] }}</div>
                        @if(count($bRound['slots']) == 0)
                            <div class="bp-empty">Belum ada peserta di babak ini</div>
                        @else
                            @foreach(array_slice($bRound['slots'], 0, 10) as $slot)
                            @php
                                $bpClass = '';
                                if ($slot['bracket_status'] == 'lolos') $bpClass = 'bp-winner';
                                elseif ($slot['bracket_status'] == 'gugur') $bpClass = 'bp-eliminated';
                            @endphp
                            <div class="bp-row {{ $bpClass }}" onclick='showIdCard({name:`{{ addslashes($slot["name"]) }}`, institution:`{{ addslashes($slot["institution"]) }}`, major:`{{ addslashes($slot["major"]) }}`, rank:0, avatar_url:`{{ $slot["avatar_url"] ?? "" }}`})'>
                                <div class="bp-left">
                                    <div class="bp-av">
                                        @if(!empty($slot['avatar_url']))
                                            <img src="{{ $slot['avatar_url'] }}" alt="">
                                        @else
                                            {{ strtoupper(substr($slot['name'], 0, 1)) }}
                                        @endif
                                    </div>
                                    <div class="bp-nm">
                                        {{ Str::limit($slot['name'], 18) }}
                                        @if($slot['is_champion']) <i class="fas fa-crown" style="color:#fbbf24;font-size:9px;margin-left:3px;"></i> @endif
                                        @if($slot['is_projected'] ?? false) <i class="fas fa-eye" style="color:rgba(29,179,73,0.6);font-size:9px;margin-left:3px;" title="Sementara"></i> @endif
                                    </div>
                                </div>
                                <div class="bp-sc">{{ $slot['score'] !== null ? number_format($slot['score'], 1) : '—' }}</div>
                            </div>
                            @endforeach
                            @if(count($bRound['slots']) > 10)
                                <div class="bp-more">+ {{ count($bRound['slots']) - 10 }} PESERTA LAINNYA</div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            @if(!$loop->last)
            <div class="bracket-connector"></div>
            @endif
            @endforeach
        </div>
    </div>
@else
    {{-- Top 3 Podium --}}
    @if(count($leaderboard) >= 3)
    <div class="podium-section">
    {{-- 2nd Place --}}
    <div class="pod-card pod-2" onclick='showIdCard({name:`{{ addslashes($leaderboard[1]["name"]) }}`, institution:`{{ addslashes($leaderboard[1]["institution"]) }}`, major:`{{ addslashes($leaderboard[1]["major"]) }}`, rank:2, avatar_url:`{{ $leaderboard[1]["avatar_url"] ?? "" }}`})'>
        <div class="pod-rank">2</div>
        <div class="pod-avatar">
            @if(!empty($leaderboard[1]['avatar_url']))
                <img src="{{ $leaderboard[1]['avatar_url'] }}" alt="">
            @else
                <div class="initial">{{ strtoupper(substr($leaderboard[1]['name'], 0, 1)) }}</div>
            @endif
        </div>
        <div class="pod-name">{{ $leaderboard[1]['name'] }}</div>
        <div class="pod-inst">{{ $leaderboard[1]['institution'] }}</div>
        <div class="pod-score">{{ number_format($leaderboard[1]['total_score'], 1) }}</div>
        <div class="pod-label">Poin</div>
    </div>

    {{-- 1st Place --}}
    <div class="pod-card pod-1" onclick='showIdCard({name:`{{ addslashes($leaderboard[0]["name"]) }}`, institution:`{{ addslashes($leaderboard[0]["institution"]) }}`, major:`{{ addslashes($leaderboard[0]["major"]) }}`, rank:1, avatar_url:`{{ $leaderboard[0]["avatar_url"] ?? "" }}`})'>
        <div style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;background:rgba(29,179,73,0.08);border-radius:50%;"></div>
        <div style="font-size:24px;margin-bottom:8px;"><i class="fas fa-crown" style="color:#fbbf24;filter:drop-shadow(0 4px 6px rgba(251,191,36,0.4));"></i></div>
        <div class="pod-avatar" style="width:72px;height:72px;border:3px solid rgba(251,191,36,0.5);">
            @if(!empty($leaderboard[0]['avatar_url']))
                <img src="{{ $leaderboard[0]['avatar_url'] }}" alt="">
            @else
                <div class="initial">{{ strtoupper(substr($leaderboard[0]['name'], 0, 1)) }}</div>
            @endif
        </div>
        <div class="pod-name" style="font-size:16px;">{{ $leaderboard[0]['name'] }}</div>
        <div class="pod-inst">{{ $leaderboard[0]['institution'] }}</div>
        <div class="pod-score" style="font-size:34px;">{{ number_format($leaderboard[0]['total_score'], 1) }}</div>
        <div class="pod-label">Poin</div>
    </div>

    {{-- 3rd Place --}}
    <div class="pod-card pod-3" onclick='showIdCard({name:`{{ addslashes($leaderboard[2]["name"]) }}`, institution:`{{ addslashes($leaderboard[2]["institution"]) }}`, major:`{{ addslashes($leaderboard[2]["major"]) }}`, rank:3, avatar_url:`{{ $leaderboard[2]["avatar_url"] ?? "" }}`})'>
        <div class="pod-rank">3</div>
        <div class="pod-avatar">
            @if(!empty($leaderboard[2]['avatar_url']))
                <img src="{{ $leaderboard[2]['avatar_url'] }}" alt="">
            @else
                <div class="initial">{{ strtoupper(substr($leaderboard[2]['name'], 0, 1)) }}</div>
            @endif
        </div>
        <div class="pod-name">{{ $leaderboard[2]['name'] }}</div>
        <div class="pod-inst">{{ $leaderboard[2]['institution'] }}</div>
        <div class="pod-score">{{ number_format($leaderboard[2]['total_score'], 1) }}</div>
        <div class="pod-label">Poin</div>
    </div>
</div>
@endif

{{-- Full Leaderboard Table --}}
<div class="lb-table-card">
    <div class="lb-table-header">
        <h3><i class="fas fa-list-ol"></i> Klasemen Lengkap</h3>
        <span id="lb-updated"></span>
    </div>
    <div class="table-wrapper" style="padding:0;" id="lb-table-wrapper">
        <table>
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
                    $medalColor = $rank === 1 ? '#fbbf24' : ($rank === 2 ? '#94a3b8' : ($rank === 3 ? '#d97706' : null));
                @endphp
                <tr style="transition:.2s;cursor:pointer;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''" onclick='showIdCard({name:`{{ addslashes($row["name"]) }}`, institution:`{{ addslashes($row["institution"]) }}`, major:`{{ addslashes($row["major"]) }}`, rank:{{ $rank }}, avatar_url:`{{ $row["avatar_url"] ?? "" }}`})'>
                    <td style="padding-left:28px;">
                        @if($medalColor)
                            <div style="width:30px;height:30px;background:{{ $medalColor }};border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-medal" style="color:#fff;font-size:13px;"></i>
                            </div>
                        @else
                            <span style="font-size:15px;font-weight:800;color:#94a3b8;">{{ $rank }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="flex-shrink:0;">
                                @if(!empty($row['avatar_url']))
                                    <img src="{{ $row['avatar_url'] }}" alt="{{ $row['name'] }}" style="width:36px;height:36px;border-radius:10px;object-fit:cover;border:1px solid #e2e8f0;" />
                                @else
                                    <div style="width:36px;height:36px;border-radius:10px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#64748b;font-weight:800;font-size:14px;border:1px solid #e2e8f0;">
                                        {{ strtoupper(substr($row['name'], 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:800;font-size:14px;">{{ $row['name'] }}</div>
                                <div style="font-size:11px;color:#94a3b8;font-family:monospace;">{{ $row['code'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;font-weight:600;">{{ $row['institution'] }}</td>
                    <td style="font-size:13px;">
                        {{ $row['grade'] }}
                        @if($row['major'])<span style="color:#94a3b8;"> · {{ $row['major'] }}</span>@endif
                    </td>
                    <td>
                        <span style="background:#f0fdf4;color:#166534;padding:4px 12px;border-radius:100px;font-size:12px;font-weight:700;">
                            {{ $row['rounds_done'] }} babak
                        </span>
                    </td>
                    <td style="text-align:right;padding-right:28px;">
                        <span style="font-size:20px;font-weight:800;color:{{ $rank <= 3 ? ($rank==1?'#ca8a04':($rank==2?'#64748b':'#d97706')) : 'var(--grad-start)' }};">
                            {{ number_format($row['total_score'], 1) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<script>
// Auto-refresh leaderboard every 30 seconds
const apiUrl = '{{ route("leaderboard.json", $event) }}';

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
                const medalColors = { 1: '#fbbf24', 2: '#94a3b8', 3: '#d97706' };
                const scoreColors = { 1: '#ca8a04', 2: '#64748b', 3: '#d97706' };
                const rankCell = medalColors[rank]
                    ? `<div style="width:30px;height:30px;background:${medalColors[rank]};border-radius:50%;display:flex;align-items:center;justify-content:center;"><i class="fas fa-medal" style="color:#fff;font-size:13px;"></i></div>`
                    : `<span style="font-size:15px;font-weight:800;color:#94a3b8;">${rank}</span>`;

                const avatar = row.avatar_url
                    ? `<img src="${row.avatar_url}" style="width:36px;height:36px;border-radius:10px;object-fit:cover;border:1px solid #e2e8f0;" />`
                    : `<div style="width:36px;height:36px;border-radius:10px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;color:#64748b;font-weight:800;font-size:14px;border:1px solid #e2e8f0;">${row.name.charAt(0).toUpperCase()}</div>`;

                html += `<tr style="cursor:pointer;transition:.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                    <td style="padding-left:28px;">${rankCell}</td>
                    <td>
                        <div style="display:flex;align-items:center;gap:12px;">
                            <div style="flex-shrink:0;">${avatar}</div>
                            <div>
                                <div style="font-weight:800;font-size:14px;">${row.name}</div>
                                <div style="font-size:11px;color:#94a3b8;font-family:monospace;">${row.code}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:13px;font-weight:600;">${row.institution}</td>
                    <td style="font-size:13px;">${row.grade}${row.major ? ' <span style="color:#94a3b8;">· ' + row.major + '</span>' : ''}</td>
                    <td><span style="background:#f0fdf4;color:#166534;padding:4px 12px;border-radius:100px;font-size:12px;font-weight:700;">${row.rounds_done} babak</span></td>
                    <td style="text-align:right;padding-right:28px;"><span style="font-size:20px;font-weight:900;color:${scoreColors[rank]||'var(--grad-start)'};">${parseFloat(row.total_score).toFixed(1)}</span></td>
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
@include('components.id-card-modal')
@endsection
