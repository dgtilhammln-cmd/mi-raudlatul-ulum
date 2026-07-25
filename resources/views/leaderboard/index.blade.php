@extends('layouts.public')
@section('title', 'Live Leaderboard — Musabaqah Tarikh Islam')
@section('page-title', 'Live Leaderboard')
@section('page-desc', 'Pilih event dan saksikan klasemen perolehan poin secara real-time.')
@section('hide-header', true)

@section('breadcrumb')
    <a href="/">Beranda</a> <i class="fas fa-chevron-right"></i>
    <span>Leaderboard</span>
@endsection

@push('styles')
<style>
    .lb-hero {
        background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%);
        border-radius: 28px;
        padding: 56px 48px;
        margin-bottom: 40px;
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    .lb-hero::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle at 30% 50%, rgba(29,179,73,0.15) 0%, transparent 50%),
                    radial-gradient(circle at 70% 80%, rgba(165,207,54,0.1) 0%, transparent 40%);
        animation: heroGlow 8s ease-in-out infinite alternate;
    }
    @keyframes heroGlow {
        0% { transform: translate(0, 0); }
        100% { transform: translate(-5%, 5%); }
    }
    .lb-hero .hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.4);
        color: #fff;
        padding: 8px 20px;
        border-radius: 100px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 20px;
        position: relative;
        z-index: 2;
    }
    .lb-hero h1 {
        font-size: clamp(28px, 4vw, 42px);
        font-weight: 800;
        color: #fff;
        margin-bottom: 12px;
        letter-spacing: -0.5px;
        position: relative;
        z-index: 2;
    }
    .lb-hero p {
        font-size: 16px;
        color: rgba(255,255,255,0.6);
        max-width: 500px;
        margin: 0 auto;
        line-height: 1.6;
        position: relative;
        z-index: 2;
    }
    .lb-hero .stat-row {
        display: flex;
        justify-content: center;
        gap: 48px;
        margin-top: 32px;
        position: relative;
        z-index: 2;
    }
    .lb-hero .stat-item .stat-val {
        font-size: 28px;
        font-weight: 800;
        color: #fff;
    }
    .lb-hero .stat-item .stat-lbl {
        font-size: 12px;
        color: rgba(255,255,255,0.8);
        font-weight: 600;
    }

    .event-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 24px;
    }

    .ev-card {
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 8px 32px rgba(0,0,0,0.04);
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        text-decoration: none;
        color: inherit;
    }
    .ev-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 48px rgba(29,179,73,0.12);
        border-color: rgba(29,179,73,0.3);
    }
    .ev-card .card-img {
        height: 200px;
        position: relative;
        overflow: hidden;
    }
    .ev-card .card-img img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }
    .ev-card:hover .card-img img {
        transform: scale(1.08);
    }
    .ev-card .card-img .overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 60%);
    }
    .ev-card .card-img .badge-live {
        position: absolute;
        top: 16px;
        right: 16px;
        background: rgba(0,0,0,0.4);
        backdrop-filter: blur(8px);
        color: #fff;
        padding: 6px 14px;
        border-radius: 100px;
        font-size: 11px;
        font-weight: 700;
        border: 1px solid rgba(255,255,255,0.15);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .ev-card .card-img .badge-live .dot {
        width: 8px;
        height: 8px;
        background: #4ade80;
        border-radius: 50%;
        animation: pulse-dot 1.5s infinite;
    }
    @keyframes pulse-dot { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:0.4;transform:scale(1.5)} }

    .ev-card .card-img .trophy {
        position: absolute;
        bottom: 16px;
        left: 16px;
        width: 44px;
        height: 44px;
        background: linear-gradient(135deg, var(--grad-start), var(--grad-end));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 18px;
        box-shadow: 0 8px 16px rgba(29,179,73,0.4);
    }

    .ev-card .card-body {
        padding: 24px 28px 28px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .ev-card .card-body h3 {
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 8px;
        line-height: 1.4;
    }
    .ev-card .card-body .meta {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
    }
    .ev-card .card-body .meta i {
        color: var(--grad-start);
        margin-right: 4px;
    }
    .ev-card .card-body .bracket-info {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ev-card .card-body .bracket-info .icon-box {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: rgba(29,179,73,0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--grad-start);
        font-size: 14px;
        flex-shrink: 0;
    }
    .ev-card .card-body .bracket-info .info-text {
        font-size: 12px;
        color: #64748b;
        font-weight: 600;
    }
    .ev-card .card-body .bracket-info .info-text strong {
        color: #0f172a;
    }

    .ev-card .card-body .btn-klasemen {
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 14px;
        background: linear-gradient(135deg, var(--grad-start), var(--grad-end));
        color: #fff;
        border-radius: 14px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none;
        transition: 0.2s;
    }
    .ev-card .card-body .btn-klasemen:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }

    /* Podium Mini */
    .podium-mini {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        gap: 6px;
        margin-bottom: 20px;
    }
    .podium-mini .pm-bar {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
    }
    .podium-mini .pm-bar .pm-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 10px;
        font-weight: 800;
        color: #64748b;
        overflow: hidden;
    }
    .podium-mini .pm-bar .pm-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .podium-mini .pm-bar .pm-block {
        width: 56px;
        border-radius: 6px 6px 0 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 900;
        color: #fff;
    }
    .podium-mini .pm-1 .pm-block { height: 48px; background: linear-gradient(135deg, #fbbf24, #f59e0b); }
    .podium-mini .pm-2 .pm-block { height: 36px; background: linear-gradient(135deg, #94a3b8, #64748b); }
    .podium-mini .pm-3 .pm-block { height: 28px; background: linear-gradient(135deg, #f59e0b, #d97706); }

    @media (max-width: 768px) {
        .lb-hero { padding: 36px 24px; }
        .lb-hero .stat-row { gap: 24px; flex-wrap: wrap; }
        .event-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
    {{-- Hero Section --}}
    <div class="lb-hero">
        <div class="hero-badge">
            <span style="width:10px;height:10px;background:#fff;border-radius:50%;animation:pulse-dot 1.5s infinite;"></span>
            LIVE LEADERBOARD
        </div>
        <h1>Galeri Juara & Klasemen</h1>
        <p>Pilih event di bawah ini untuk melihat klasemen perolehan poin lengkap secara real-time.</p>
        <div class="stat-row">
            <div class="stat-item">
                <div class="stat-val">{{ $events->count() }}</div>
                <div class="stat-lbl">Total Event</div>
            </div>
            <div class="stat-item">
                <div class="stat-val">{{ $events->sum(fn($e) => $e->participants->count()) }}</div>
                <div class="stat-lbl">Total Peserta</div>
            </div>
        </div>
    </div>

    {{-- Event Cards Grid --}}
    @if($events->isEmpty())
        <div style="background:#fff;border-radius:24px;padding:60px 40px;text-align:center;box-shadow:0 12px 32px rgba(0,0,0,.05);">
            <i class="fas fa-box-open" style="font-size:48px;color:#cbd5e1;margin-bottom:16px;display:block;"></i>
            <h3 style="font-size:18px;font-weight:800;color:var(--text-dark);margin-bottom:8px;">Belum ada event</h3>
            <p style="color:var(--text-muted);font-size:14px;">Data klasemen akan muncul ketika event telah berlangsung.</p>
        </div>
    @else
        <div class="event-grid">
            @foreach($events as $event)
            @php
                $bgImage = $event->poster_image ? asset('storage/'.$event->poster_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&q=80&w=800';
                $roundCount = $event->rounds->count();
                $bracketLabel = $event->isQualificationSystem() ? $event->getBracketModeLabel() : 'Sistem Poin';
            @endphp
            <div class="ev-card">
                <div class="card-img">
                    <img src="{{ $bgImage }}" alt="{{ $event->name }}" loading="lazy">
                    <div class="overlay"></div>
                    @if($event->status === 'ongoing')
                    <div class="badge-live"><span class="dot"></span> LIVE</div>
                    @else
                    <div class="badge-live" style="background:rgba(0,0,0,0.5);border-color:rgba(255,255,255,0.1);">
                        <i class="fas fa-check-circle" style="color:#4ade80;font-size:10px;"></i> Selesai
                    </div>
                    @endif
                    <div class="trophy"><i class="fas fa-trophy"></i></div>
                </div>
                <div class="card-body">
                    <h3>{{ $event->name }}</h3>
                    <div class="meta">
                        <span><i class="far fa-calendar-alt"></i> {{ $event->start_date->translatedFormat('d M Y') }}</span>
                        <span><i class="fas fa-users"></i> {{ $event->participants->count() }} Peserta</span>
                    </div>

                    <div class="bracket-info">
                        <div class="icon-box"><i class="fas fa-sitemap"></i></div>
                        <div class="info-text">
                            <strong>{{ $bracketLabel }}</strong><br>
                            {{ $roundCount }} Babak Kompetisi
                        </div>
                    </div>

                    {{-- Mini Podium --}}
                    @if($event->winners->isNotEmpty())
                    <div class="podium-mini">
                        @if(isset($event->winners[1]))
                        <div class="pm-bar pm-2">
                            <div class="pm-avatar">
                                @if(!empty($event->winners[1]['avatar_url']))
                                    <img src="{{ $event->winners[1]['avatar_url'] }}" alt="">
                                @else
                                    {{ strtoupper(substr($event->winners[1]['name'], 0, 1)) }}
                                @endif
                            </div>
                            <div class="pm-block">2</div>
                        </div>
                        @endif
                        @if(isset($event->winners[0]))
                        <div class="pm-bar pm-1">
                            <div class="pm-avatar">
                                @if(!empty($event->winners[0]['avatar_url']))
                                    <img src="{{ $event->winners[0]['avatar_url'] }}" alt="">
                                @else
                                    {{ strtoupper(substr($event->winners[0]['name'], 0, 1)) }}
                                @endif
                            </div>
                            <div class="pm-block"><i class="fas fa-crown" style="font-size:10px;"></i></div>
                        </div>
                        @endif
                        @if(isset($event->winners[2]))
                        <div class="pm-bar pm-3">
                            <div class="pm-avatar">
                                @if(!empty($event->winners[2]['avatar_url']))
                                    <img src="{{ $event->winners[2]['avatar_url'] }}" alt="">
                                @else
                                    {{ strtoupper(substr($event->winners[2]['name'], 0, 1)) }}
                                @endif
                            </div>
                            <div class="pm-block">3</div>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($event->leaderboard_visible)
                    <a href="{{ route('leaderboard.public', $event) }}" class="btn-klasemen">
                        Lihat Klasemen Lengkap <i class="fas fa-arrow-right"></i>
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
@endsection

@push('scripts')
    @include('components.id-card-modal')
@endpush
