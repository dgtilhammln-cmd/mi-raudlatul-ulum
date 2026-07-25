@extends('layouts.app')

@section('title', 'Log Pelanggaran - ' . $round->name)
@section('page-title', 'Log Pelanggaran: ' . $round->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <a href="{{ route('organizer.reports.ranking', $round) }}" class="btn btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Kembali ke Ranking</a>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Catatan Pelanggaran Anti-Cheating</h3>
    </div>

    @if($violations->isEmpty())
        <div class="empty-state">
            <i class="fas fa-shield-alt" style="color:var(--color-success)"></i>
            <p>Tidak ada pelanggaran yang tercatat di babak ini. Semua berjalan aman.</p>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Peserta</th>
                        <th>Tipe Pelanggaran</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($violations as $violation)
                    <tr>
                        <td style="font-size:12px">{{ $violation->created_at->format('d M Y H:i:s') }}</td>
                        <td>
                            <div style="font-weight:600;color:var(--color-text-primary)">{{ $violation->examSession->participant->user->name }}</div>
                            <div style="font-size:11px;color:var(--color-text-tertiary)">{{ $violation->examSession->participant->participant_code }}</div>
                        </td>
                        <td>
                            <span class="badge badge-danger" style="text-transform:uppercase;">{{ str_replace('_', ' ', $violation->type) }}</span>
                        </td>
                        <td style="font-size:12px;color:var(--color-text-secondary)">{{ $violation->description }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $violations->links() }}</div>
    @endif
</div>
@endsection
