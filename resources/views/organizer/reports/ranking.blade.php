@extends('layouts.app')

@section('title', 'Ranking - ' . $round->name)
@section('page-title', 'Ranking: ' . $round->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <a href="{{ route('organizer.events.show', $round->event) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali ke Event</a>
    <div>
        <a href="{{ route('organizer.reports.violations', $round) }}" class="btn btn-warning btn-sm"><i class="fas fa-exclamation-triangle"></i> Log Pelanggaran</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Peringkat Peserta ({{ $sessions->count() }})</h3>
    </div>

    @if($sessions->isEmpty())
        <div class="empty-state">
            <i class="fas fa-trophy"></i>
            <p>Belum ada peserta yang menyelesaikan ujian di babak ini.</p>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;text-align:center">Rank</th>
                        <th>Peserta</th>
                        <th>Waktu Selesai</th>
                        <th>Durasi Pengerjaan</th>
                        <th style="text-align:center">Pelanggaran</th>
                        <th style="text-align:right">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $i => $session)
                    <tr>
                        <td style="text-align:center;font-weight:700;color:var(--color-accent)">#{{ $i+1 }}</td>
                        <td>
                            <div style="font-weight:600;color:var(--color-text-primary)">{{ $session->participant->user->name }}</div>
                            <div style="font-size:11px;color:var(--color-text-tertiary)">{{ $session->participant->participant_code }}</div>
                        </td>
                        <td style="font-size:12px">{{ $session->submitted_at ? $session->submitted_at->format('d M Y H:i:s') : '—' }}</td>
                        <td style="font-size:12px">
                            @if($session->started_at && $session->submitted_at)
                                {{ $session->started_at->diffInMinutes($session->submitted_at) }} menit
                            @else
                                —
                            @endif
                        </td>
                        <td style="text-align:center">
                            @if($session->violations->count() > 0)
                                <span class="badge badge-danger">{{ $session->violations->count() }}x</span>
                            @else
                                <span style="color:var(--color-success)"><i class="fas fa-check-circle"></i></span>
                            @endif
                        </td>
                        <td style="text-align:right">
                            <span style="font-size:16px;font-weight:700;color:{{ $session->status == 'disqualified' ? 'var(--color-danger)' : 'var(--color-text-primary)' }}">
                                {{ number_format($session->total_score, 2) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
