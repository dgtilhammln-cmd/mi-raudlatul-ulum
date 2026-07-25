@extends('layouts.app')

@section('title', 'Hasil Import')
@section('page-title', 'Hasil Import: ' . $importLog->filename)

@section('content')
<a href="{{ route('organizer.participants.index', $event) }}" class="btn btn-secondary btn-sm mb-6"><i class="fas fa-arrow-left"></i> Kembali</a>

<div class="grid grid-3 mb-6">
    <div class="stat-card"><span class="stat-value">{{ $importLog->total_rows }}</span><span class="stat-label">Total Baris</span></div>
    <div class="stat-card"><span class="stat-value" style="color:var(--color-success)">{{ $importLog->success_count }}</span><span class="stat-label">Berhasil</span></div>
    <div class="stat-card"><span class="stat-value" style="color:var(--color-danger)">{{ $importLog->failed_count }}</span><span class="stat-label">Gagal</span></div>
</div>

@if($importLog->access_codes)
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-key" style="color:var(--color-accent)"></i> Daftar Kode Akses</h3>
        <a href="{{ route('organizer.participants.export-access', [$event, $importLog]) }}" class="btn btn-primary btn-sm"><i class="fas fa-download"></i> Download CSV</a>
    </div>
    <div class="alert alert-warning"><i class="fas fa-exclamation-triangle"></i> Simpan daftar ini! Kode akses hanya tersedia di halaman ini dan file CSV.</div>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>No</th><th>ID Peserta</th><th>Nama</th><th>Kode Akses</th><th>Institusi</th></tr></thead>
            <tbody>
                @foreach($importLog->access_codes as $i => $ac)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td style="font-family:monospace;color:var(--color-accent)">{{ $ac['participant_id'] }}</td>
                    <td style="font-weight:600;color:#fff">{{ $ac['name'] }}</td>
                    <td style="font-family:monospace;color:var(--color-success);font-weight:700;font-size:14px">{{ $ac['access_code'] }}</td>
                    <td>{{ $ac['institution'] ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

@if($importLog->errors && count($importLog->errors) > 0)
<div class="card">
    <div class="card-header"><h3 class="card-title" style="color:var(--color-danger)">Error ({{ count($importLog->errors) }})</h3></div>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>Baris</th><th>Pesan Error</th></tr></thead>
            <tbody>
                @foreach($importLog->errors as $err)
                <tr><td>{{ $err['row'] }}</td><td style="color:var(--color-danger)">{{ $err['message'] }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
