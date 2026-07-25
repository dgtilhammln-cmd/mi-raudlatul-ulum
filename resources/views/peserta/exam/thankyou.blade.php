@extends('layouts.app')

@section('title', 'Ujian Selesai')
@section('page-title', 'Ujian Selesai')

@section('content')
<div style="max-width:500px;margin:40px auto;text-align:center;">
    <div style="margin-bottom:24px;display:flex;justify-content:center;">
        <div style="width:80px;height:80px;background:linear-gradient(135deg,#dcfce7,#16a34a);border-radius:50%;display:flex;align-items:center;justify-content:center;box-shadow:0 8px 24px rgba(22,163,74,.3);">
            <i class="fas fa-check" style="font-size:36px;color:#fff;"></i>
        </div>
    </div>
    <h2 style="font-size:24px;font-weight:800;margin-bottom:12px;">Ujian Berhasil Dikumpulkan!</h2>
    <p style="font-size:13px;color:var(--color-text-secondary);line-height:1.7;margin-bottom:8px;">
        {{ $session->round->name }} — {{ $session->round->event->name }}
    </p>
    <p style="font-size:12px;color:var(--color-text-tertiary);margin-bottom:32px;">
        Dikumpulkan pada {{ $session->submitted_at->format('d M Y H:i:s') }} WIB
        @if($session->status === 'auto_submitted')
            <br><span style="color:var(--color-warning)"><i class="fas fa-exclamation-triangle"></i> Di-submit otomatis oleh sistem</span>
        @endif
    </p>

    <div class="card" style="text-align:left;margin-bottom:24px;">
        <div class="grid grid-3">
            <div class="stat-card">
                <span class="stat-value" style="font-size:20px;color:var(--color-success)">{{ $session->correct_count }}</span>
                <span class="stat-label">Benar</span>
            </div>
            <div class="stat-card">
                <span class="stat-value" style="font-size:20px;color:var(--color-danger)">{{ $session->wrong_count }}</span>
                <span class="stat-label">Salah</span>
            </div>
            <div class="stat-card">
                <span class="stat-value" style="font-size:20px;">{{ $session->unanswered_count }}</span>
                <span class="stat-label">Kosong</span>
            </div>
        </div>
    </div>

    @if($session->result_status === 'essay_pending')
        <div class="alert alert-warning" style="justify-content:center;">
            <i class="fas fa-hourglass-half"></i> Soal esai sedang dinilai oleh penyelenggara.
        </div>
    @endif

    <div class="flex gap-4" style="justify-content:center;">
        <a href="{{ route('peserta.result', $session) }}" class="btn btn-primary"><i class="fas fa-chart-bar"></i> Lihat Detail Hasil</a>
        <a href="{{ route('peserta.dashboard') }}" class="btn btn-secondary"><i class="fas fa-home"></i> Dashboard</a>
    </div>
</div>
@endsection
