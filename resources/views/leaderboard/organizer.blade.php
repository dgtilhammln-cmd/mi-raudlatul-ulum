@extends('layouts.app')
@section('title', 'Live Leaderboard — Penyelenggara')
@section('page-title', 'Live Leaderboard')

@section('sidebar')
    <a href="{{ route('organizer.leaderboard') }}" class="nav-item active">
        <i class="fas fa-trophy"></i> Leaderboard
    </a>
@endsection

@section('content')

{{-- Page Header --}}
<div style="background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:20px;padding:28px 36px;margin-bottom:28px;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-40px;right:60px;width:100px;height:100px;background:rgba(255,255,255,.04);border-radius:50%;"></div>
    <div style="position:relative;z-index:2;">
        <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.2);padding:5px 14px;border-radius:100px;margin-bottom:12px;">
            <span style="width:8px;height:8px;background:#4ade80;border-radius:50%;display:inline-block;animation:pulse 1.5s infinite;"></span>
            <span style="color:#fff;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">Live Sync</span>
        </div>
        <h1 style="font-size:26px;font-weight:900;color:#fff;margin-bottom:6px;">Klasemen & Leaderboard</h1>
        <p style="color:rgba(255,255,255,.65);font-size:13px;">Sinkronisasi real-time. Diperbarui setiap 30 detik secara otomatis.</p>
    </div>
</div>

{{-- EVENT LIST --}}
@if($allEvents->isNotEmpty())
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:24px;margin-bottom:28px;">
    @foreach($allEvents as $ev)
    <div class="card" style="border-radius:20px;overflow:hidden;box-shadow:var(--shadow-lg);transition:.2s;border:1px solid var(--color-border);display:flex;flex-direction:column;cursor:pointer;" onmouseover="this.style.transform='translateY(-5px)';this.style.borderColor='var(--color-primary)'" onmouseout="this.style.transform='translateY(0)';this.style.borderColor='var(--color-border)'" onclick="window.location.href='{{ $ev->scoring_system === 'point' ? route('organizer.events.leaderboard', $ev) : route('organizer.events.bracket', $ev) }}'">
        <div style="aspect-ratio:4/5;background:{{ $ev->banner_url ? 'url('.Storage::url($ev->banner_url).') center/cover' : 'linear-gradient(135deg,var(--grad-start),var(--grad-end))' }};position:relative;">
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
        <div style="padding:20px;flex:1;display:flex;flex-direction:column;">
            <h3 style="font-size:16px;font-weight:800;color:var(--color-text-primary);margin-bottom:8px;line-height:1.4;">{{ $ev->name }}</h3>
            <div style="font-size:12px;color:var(--color-text-tertiary);margin-bottom:16px;">
                <i class="far fa-calendar-alt" style="margin-right:6px;"></i> {{ $ev->start_date->format('d M Y') }}
            </div>
            <div style="margin-top:auto;display:flex;align-items:center;justify-content:space-between;">
                <div style="font-size:11px;font-weight:700;color:var(--color-text-secondary);background:var(--color-surface-soft);padding:6px 12px;border-radius:8px;">
                    <i class="fas fa-users" style="margin-right:4px;"></i> {{ $ev->participants_count }} Peserta
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
<div style="background:var(--color-surface);border-radius:20px;padding:60px 40px;text-align:center;border:2px dashed var(--color-border);margin-bottom:28px;">
    <i class="fas fa-calendar-times" style="font-size:48px;color:var(--color-text-tertiary);margin-bottom:16px;display:block;"></i>
    <h3 style="font-size:18px;font-weight:800;color:var(--color-text-secondary);margin-bottom:8px;">Belum Ada Event</h3>
    <p style="color:var(--color-text-tertiary);font-size:14px;">Buat event terlebih dahulu untuk melihat leaderboard.</p>
</div>
@endif

{{-- EVENT HISTORY --}}
<div class="card" style="border-radius:20px;overflow:hidden;">
    <div class="card-header">
        <div style="display:flex;align-items:center;gap:10px;">
            <i class="fas fa-history" style="color:var(--color-primary);font-size:16px;"></i>
            <h3 class="card-title">Riwayat Semua Event</h3>
        </div>
        <span style="font-size:12px;color:var(--color-text-tertiary);">{{ $allEvents->count() }} event</span>
    </div>
    <div class="table-wrapper" style="padding:0;">
        <table>
            <thead>
                <tr>
                    <th>Nama Event</th>
                    <th>Kategori</th>
                    <th>Sistem</th>
                    <th>Periode</th>
                    <th>Peserta</th>
                    <th>Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allEvents as $ev)
                @php
                    $statusMap = ['draft'=>['default','Draft'],'published'=>['info','Publik'],'ongoing'=>['success','Berlangsung'],'completed'=>['warning','Selesai'],'cancelled'=>['danger','Dibatalkan']];
                    $st = $statusMap[$ev->status] ?? ['default',$ev->status];
                @endphp
                <tr>
                    <td style="font-weight:700;color:var(--color-text-primary);">{{ $ev->name }}</td>
                    <td>{{ $ev->category ?? '—' }}</td>
                    <td>
                        <span style="background:{{ $ev->scoring_system==='point'?'var(--color-accent-light)':'#fef3c7' }};color:{{ $ev->scoring_system==='point'?'var(--color-primary)':'#d97706' }};padding:3px 10px;border-radius:100px;font-size:11px;font-weight:700;">
                            <i class="fas fa-{{ $ev->scoring_system==='point'?'chart-line':'filter' }}" style="margin-right:4px;"></i>{{ $ev->scoring_system === 'point' ? 'Poin' : 'Kualifikasi' }}
                        </span>
                    </td>
                    <td style="font-size:12px;color:var(--color-text-tertiary);">{{ $ev->start_date->format('d M Y') }} — {{ $ev->end_date->format('d M Y') }}</td>
                    <td style="font-weight:700;color:var(--color-primary);">{{ $ev->participants_count }}</td>
                    <td><span class="badge badge-{{ $st[0] }}">{{ $st[1] }}</span></td>
                    <td style="text-align:right;">
                        <a href="{{ route('organizer.events.show', $ev) }}" style="color:var(--color-primary);font-size:12px;font-weight:700;text-decoration:none;">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        @if($ev->scoring_system === 'point' && $ev->leaderboard_visible)
                        &nbsp;&nbsp;
                        <a href="{{ route('organizer.events.leaderboard', $ev) }}" style="color:var(--color-warning);font-size:12px;font-weight:700;text-decoration:none;">
                            <i class="fas fa-trophy"></i> Klasemen
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
