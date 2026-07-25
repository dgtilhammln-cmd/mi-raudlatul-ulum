@extends('layouts.app')

@section('title', 'Hasil Ujian — ' . $session->round->name)
@section('page-title', 'Hasil Ujian')

@section('content')
<a href="{{ route('peserta.dashboard') }}" class="btn btn-secondary btn-sm mb-6"><i class="fas fa-arrow-left"></i> Kembali</a>

<div style="background:linear-gradient(135deg,#16a34a,#1db349 50%,#a5cf36);border-radius:28px;padding:48px 32px 40px;margin-bottom:24px;text-align:center;position:relative;overflow:hidden;box-shadow:0 12px 40px rgba(29,179,73,0.4);">
    {{-- Decorative circles --}}
    <div style="position:absolute;top:-40px;right:-40px;width:160px;height:160px;background:rgba(255,255,255,0.08);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-60px;left:-20px;width:200px;height:200px;background:rgba(255,255,255,0.05);border-radius:50%;"></div>
    <div style="position:absolute;top:20px;left:30px;width:40px;height:40px;background:rgba(255,255,255,0.1);border-radius:50%;"></div>

    <p style="font-size:11px;color:rgba(255,255,255,0.75);font-weight:700;letter-spacing:3px;margin-bottom:4px;position:relative;">BABAK</p>
    <h2 style="font-size:22px;font-weight:900;color:#fff;margin-bottom:4px;position:relative;">{{ $session->round->name }}</h2>
    <p style="font-size:13px;color:rgba(255,255,255,0.7);margin-bottom:32px;position:relative;">{{ $session->round->event->name }}</p>

    <div style="display:inline-block;position:relative;">
        <div style="background:rgba(255,255,255,0.18);backdrop-filter:blur(12px);border:2px solid rgba(255,255,255,0.35);border-radius:20px;padding:28px 56px;">
            <p style="font-size:11px;color:rgba(255,255,255,0.85);font-weight:700;letter-spacing:3px;margin-bottom:10px;">TOTAL SKOR</p>
            <div style="font-size:64px;font-weight:900;color:#fff;line-height:1;text-shadow:0 4px 16px rgba(0,0,0,0.15);">{{ number_format($session->total_score, 1) }}</div>
        </div>
    </div>

    @if($session->result_status === 'essay_pending')
        <div style="margin-top:24px;display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.3);border-radius:100px;padding:8px 20px;font-size:13px;font-weight:700;color:#fff;position:relative;">
            <i class="fas fa-hourglass-half"></i> Skor ini belum final — Esai sedang dinilai
        </div>
    @elseif($session->result_status === 'final')
        <div style="margin-top:24px;display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,0.2);border:1px solid rgba(255,255,255,0.3);border-radius:100px;padding:8px 20px;font-size:13px;font-weight:700;color:#fff;position:relative;">
            <i class="fas fa-check-circle"></i> Skor Final — Semua Penilaian Selesai
        </div>
    @endif
</div>

<div class="grid grid-2 mb-6">
    <div class="card">
        <h3 class="card-title" style="margin-bottom:16px;">Rincian Skor</h3>
        <table style="width:100%;font-size:13px;">
            <tr><td style="padding:8px 0;color:var(--color-text-secondary)">Pilihan Ganda</td><td style="text-align:right;font-weight:700;">{{ number_format($session->score_pg, 1) }}</td></tr>
            <tr><td style="padding:8px 0;color:var(--color-text-secondary)">Esai</td><td style="text-align:right;font-weight:700;">{{ $session->result_status==='essay_pending' ? '(Menunggu)' : number_format($session->score_essay, 1) }}</td></tr>
            <tr style="border-top:1px solid var(--color-border)"><td style="padding:12px 0;color:var(--color-primary);font-weight:700;">Total</td><td style="text-align:right;font-weight:800;color:var(--color-primary);">{{ number_format($session->total_score, 1) }}</td></tr>
        </table>
    </div>
    
    <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:2px solid #bbf7d0;border-radius:20px;overflow:hidden;">
        <div style="background:linear-gradient(135deg,#1db349,#a5cf36);padding:16px 20px;">
            <h3 style="font-size:14px;font-weight:800;color:#fff;margin:0;"><i class="fas fa-certificate" style="margin-right:6px;"></i>E-Sertifikat / Raport</h3>
        </div>
        <div style="padding:24px;">
        @if($session->certificate)
            <div style="text-align:center;padding:12px 0;">
                <div style="width:64px;height:64px;background:linear-gradient(135deg,#1db349,#a5cf36);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;box-shadow:0 6px 20px rgba(29,179,73,0.3);">
                    <i class="fas fa-award" style="font-size:28px;color:#fff;"></i>
                </div>
                <p style="font-size:13px;color:#15803d;font-weight:600;margin-bottom:16px;">Sertifikat / Raport Anda sudah siap!</p>
                <a href="{{ route('peserta.certificate.download', $session->certificate) }}" class="btn btn-primary" target="_blank" style="background:linear-gradient(135deg,#1db349,#a5cf36);border:none;box-shadow:0 4px 16px rgba(29,179,73,0.35);">
                    <i class="fas fa-download"></i> Unduh Sertifikat / Raport
                </a>
            </div>
        @else
            <div style="text-align:center;padding:12px 0;">
                <div style="width:56px;height:56px;background:#dcfce7;border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                    <i class="fas fa-award" style="font-size:24px;color:#86efac;"></i>
                </div>
                <p style="font-size:13px;color:#16a34a;font-weight:600;margin-bottom:4px;">Sertifikat / Raport belum tersedia</p>
                <p style="font-size:12px;color:#4ade80;margin-bottom:16px;">Akan muncul jika penyelenggara sudah mengirimkannya.</p>
                <a href="{{ route('peserta.events') }}" style="display:inline-flex;align-items:center;gap:6px;padding:8px 20px;background:linear-gradient(135deg,#1db349,#a5cf36);color:#fff;border-radius:100px;font-size:13px;font-weight:700;text-decoration:none;box-shadow:0 4px 16px rgba(29,179,73,0.3);">
                    <i class="fas fa-search"></i> Lihat Event &amp; E-Raport
                </a>
            </div>
        @endif
        </div>
    </div>
</div>

@if($session->round->allow_review)
<div class="card">
    <div class="card-header"><h3 class="card-title">Review Jawaban</h3></div>
    
    @foreach($session->answers as $i => $answer)
    <div style="padding:20px;margin-bottom:16px;background:var(--color-surface-card);border-radius:16px;border:1px solid rgba(0,0,0,0.05);box-shadow:var(--shadow-sm);">
        <div class="flex justify-between items-start mb-4">
            <span style="font-size:12px;font-weight:700;color:var(--color-text-tertiary)">SOAL {{ $i+1 }}</span>
            @if($answer->question->type === 'multiple_choice')
                <span class="badge badge-{{ $answer->is_correct ? 'success' : 'danger' }}">
                    <i class="fas fa-{{ $answer->is_correct ? 'check' : 'times' }}"></i> {{ $answer->is_correct ? 'Benar' : 'Salah' }}
                </span>
            @else
                <span class="badge badge-{{ $answer->essay_status=='graded'?'success':'warning' }}">
                    {{ $answer->essay_status=='graded' ? 'Dinilai ('.$answer->score.')' : 'Menunggu Penilaian' }}
                </span>
            @endif
        </div>
        
        <p style="font-size:13px;font-weight:600;margin-bottom:16px;line-height:1.6">{{ $answer->question->content }}</p>
        
        @if($answer->question->type === 'multiple_choice')
            <div style="padding:12px;background:var(--color-surface-soft);border-radius:6px;font-size:12px;color:var(--color-text-primary);">
                <strong style="color:var(--color-text-primary);">Jawaban Anda:</strong><br>
                {{ $answer->selectedOption->content ?? '(Kosong)' }}
            </div>
        @else
            <div style="padding:12px;background:var(--color-surface-soft);border-radius:6px;font-size:12px;color:var(--color-text-primary);margin-bottom:12px;">
                <strong style="color:var(--color-text-primary);">Jawaban Anda:</strong><br>
                {{ $answer->essay_answer ?? '(Kosong)' }}
            </div>
            @if($answer->essay_feedback)
            <div style="padding:12px;background:rgba(200,169,81,0.1);border:1px solid rgba(200,169,81,0.2);border-radius:6px;font-size:12px;color:var(--color-accent);">
                <strong>Feedback Penilai:</strong><br>
                {{ $answer->essay_feedback }}
            </div>
            @endif
        @endif
    </div>
    @endforeach
</div>
@endif
@endsection
