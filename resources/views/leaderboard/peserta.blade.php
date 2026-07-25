@extends('layouts.app')
@section('title', 'Leaderboard Saya')
@section('page-title', 'Leaderboard & Riwayat')

@section('content')

{{-- Header Banner --}}
<div class="mobile-p-4 mobile-stack" style="background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:20px;padding:24px;margin-bottom:24px;position:relative;overflow:hidden;display:flex;align-items:center;gap:16px;">
    <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
    <div style="position:relative;z-index:2;">
        <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);padding:5px 14px;border-radius:100px;margin-bottom:12px;">
            <span style="width:8px;height:8px;background:#4ade80;border-radius:50%;display:inline-block;animation:pulse 1.5s infinite;"></span>
            <span style="color:#fff;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Live Realtime</span>
        </div>
        <h1 style="font-size:24px;font-weight:900;color:#fff;margin-bottom:6px;line-height:1.2;">Klasemen Saya</h1>
        <p class="mobile-text-sm" style="color:rgba(255,255,255,.65);font-size:13px;line-height:1.4;">Posisi terkini dan riwayat event yang pernah Anda ikuti.</p>
    </div>
</div>

{{-- EVENT LIST --}}
@if($allParticipations->isNotEmpty())
<div class="grid grid-3" style="margin-bottom:28px;">
    @foreach($allParticipations as $p)
    @php
        $ev = $p->event;
    @endphp
    <div class="card" style="border-radius:20px;overflow:hidden;box-shadow:var(--shadow-lg);transition:.2s;border:1px solid var(--color-border);display:flex;flex-direction:column;cursor:pointer;" onmouseover="this.style.transform='translateY(-5px)';this.style.borderColor='var(--color-primary)'" onmouseout="this.style.transform='translateY(0)';this.style.borderColor='var(--color-border)'" onclick="window.location.href='{{ $ev->scoring_system === 'point' ? route('peserta.leaderboard.event', $ev) : route('peserta.bracket.show', $ev) }}'">
        <div style="height:100px;background:{{ $ev->banner_url ? 'url('.Storage::url($ev->banner_url).') center/cover' : 'linear-gradient(135deg,var(--grad-start),var(--grad-end))' }};position:relative;">
            <div style="position:absolute;top:12px;right:12px;background:{{ $ev->scoring_system==='point'?'rgba(255,255,255,.9)':'rgba(255,255,255,.9)' }};color:{{ $ev->scoring_system==='point'?'var(--color-primary)':'#d97706' }};padding:4px 12px;border-radius:100px;font-size:11px;font-weight:800;box-shadow:0 2px 8px rgba(0,0,0,.1);">
                @if($ev->scoring_system === 'point')
                    <i class="fas fa-list-ol" style="margin-right:4px;"></i> Klasemen
                @else
                    <i class="fas fa-sitemap" style="margin-right:4px;"></i> Bagan Turnamen
                @endif
            </div>
            @if(in_array($ev->status, ['ongoing', 'published']))
            <div style="position:absolute;top:12px;left:12px;background:#4ade80;color:#fff;padding:4px 12px;border-radius:100px;font-size:11px;font-weight:800;box-shadow:0 2px 8px rgba(0,0,0,.1);display:flex;align-items:center;gap:6px;">
                <span style="width:6px;height:6px;background:#fff;border-radius:50%;display:inline-block;animation:pulse 1.5s infinite;"></span> Live
            </div>
            @endif
        </div>
        <div class="mobile-p-4" style="padding:16px 20px;flex:1;display:flex;flex-direction:column;">
            <h3 style="font-size:16px;font-weight:800;color:var(--color-text-primary);margin-bottom:8px;line-height:1.4;">{{ $ev->name }}</h3>
            <div style="font-size:12px;color:var(--color-text-tertiary);margin-bottom:16px;">
                <i class="far fa-calendar-alt" style="margin-right:6px;"></i> {{ $ev->start_date->format('d M Y') }}
            </div>
            <div style="margin-top:auto;display:flex;align-items:center;justify-content:space-between;">
                <div style="font-size:11px;font-weight:700;color:var(--color-text-secondary);background:var(--color-surface-soft);padding:6px 12px;border-radius:8px;">
                    <i class="fas fa-hashtag" style="margin-right:4px;"></i> {{ $p->participant_code }}
                </div>
                <div style="color:var(--color-primary);font-size:13px;font-weight:800;">
                    Lihat <i class="fas fa-arrow-right" style="margin-left:4px;"></i>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@else
<div style="background:var(--color-surface);border-radius:20px;padding:48px 40px;text-align:center;border:2px dashed var(--color-border);margin-bottom:28px;">
    <i class="fas fa-trophy" style="font-size:40px;color:var(--color-text-tertiary);margin-bottom:14px;display:block;"></i>
    <h3 style="font-size:17px;font-weight:800;color:var(--color-text-secondary);margin-bottom:8px;">Belum ada riwayat event</h3>
    <p style="color:var(--color-text-tertiary);font-size:13px;">Anda belum mengikuti event apapun, atau event belum di-publish oleh penyelenggara.</p>
</div>
@endif

{{-- HISTORY --}}
<div class="card" style="border-radius:20px;overflow:hidden;width:100%;">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:10px;">
            <i class="fas fa-history" style="color:var(--color-primary);font-size:16px;"></i>
            <h3 class="card-title">Riwayat Keikutsertaan</h3>
        </div>
        <span style="font-size:12px;color:var(--color-text-tertiary);">{{ $allParticipations->count() }} event</span>
    </div>
    <div class="table-wrapper" style="padding:0;">
        <table>
            <thead>
                <tr>
                    <th>Event</th>
                    <th>Kategori</th>
                    <th>Sistem</th>
                    <th>Periode</th>
                    <th>Status Saya</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allParticipations as $p)
                @php
                    $ev = $p->event;
                    $statusLabel = ['active'=>['success','Aktif'],'disqualified'=>['danger','Tereliminasi'],'registered'=>['warning','Terdaftar'],'completed'=>['info','Selesai']];
                    $sl = $statusLabel[$p->status] ?? ['default',$p->status];
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:700;color:var(--color-text-primary);">{{ $ev->name }}</div>
                        <div style="font-size:11px;color:var(--color-text-tertiary);font-family:monospace;">{{ $p->participant_code }}</div>
                    </td>
                    <td style="font-size:13px;">{{ $ev->category ?? '—' }}</td>
                    <td>
                        <span style="background:{{ $ev->scoring_system==='point'?'var(--color-accent-light)':'#fef3c7' }};color:{{ $ev->scoring_system==='point'?'var(--color-primary)':'#d97706' }};padding:3px 10px;border-radius:100px;font-size:11px;font-weight:700;">
                            {{ $ev->scoring_system === 'point' ? 'Poin' : 'Kualifikasi' }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:var(--color-text-tertiary);">{{ $ev->start_date->format('d M Y') }}</td>
                    <td><span class="badge badge-{{ $sl[0] }}">{{ $sl[1] }}</span></td>
                    <td style="text-align:right;">
                        @if($p->status === 'disqualified')
                        <span style="font-size:12px;color:var(--color-text-tertiary);">
                            <i class="fas fa-heart" style="color:#ef4444;margin-right:4px;"></i>Tetap Semangat!
                        </span>
                        @elseif($ev->leaderboard_visible && $ev->scoring_system === 'point')
                        <a href="{{ route('peserta.leaderboard.event', $ev) }}" style="color:var(--color-primary);font-size:12px;font-weight:700;text-decoration:none;">
                            <i class="fas fa-trophy"></i> Klasemen
                        </a>
                        @else
                        <a href="{{ route('peserta.dashboard') }}" style="color:var(--color-primary);font-size:12px;font-weight:700;text-decoration:none;">
                            <i class="fas fa-arrow-right"></i> Dashboard
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
@keyframes pulse { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.3)} }
</style>

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
@include('components.id-card-modal')

@endsection
