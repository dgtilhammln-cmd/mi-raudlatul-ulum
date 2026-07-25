@extends('layouts.app')

@section('title', 'Dashboard — Organizer')
@section('page-title', 'Dashboard')

@section('content')
    {{-- Stat Cards --}}
    <div class="grid grid-4 mb-6">
        <div class="stat-card">
            <span class="stat-value">{{ $totalEvents }}</span>
            <span class="stat-label">Total Event</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">{{ $totalParticipants }}</span>
            <span class="stat-label">Total Peserta</span>
        </div>
        <div class="stat-card">
            <span class="stat-value" style="color:var(--color-success)">{{ $activeSessions }}</span>
            <span class="stat-label">Sesi Aktif</span>
        </div>
        <div class="stat-card">
            <span class="stat-value" style="color:var(--color-warning)">{{ $pendingEssays }}</span>
            <span class="stat-label">Esai Belum Dinilai</span>
        </div>
    </div>

    {{-- Event List --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Event Saya</h3>
            <a href="{{ route('organizer.events.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Buat Event
            </a>
        </div>

        @if($events->isEmpty())
            <div class="empty-state">
                <i class="fas fa-calendar-plus"></i>
                <p>Belum ada event. Buat event pertama Anda!</p>
                <a href="{{ route('organizer.events.create') }}" class="btn btn-primary">Buat Event</a>
            </div>
        @else
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Event</th>
                            <th>Babak</th>
                            <th>Peserta</th>
                            <th>Periode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $event)
                        <tr>
                            <td style="font-weight:800; color:var(--color-text-primary);">{{ $event->name }}</td>
                            <td>{{ $event->rounds_count }}</td>
                            <td>{{ $event->participants_count }}</td>
                            <td style="font-size:11px;">
                                {{ $event->start_date->format('d M Y') }} —
                                {{ $event->end_date->format('d M Y') }}
                            </td>
                            <td>
                                @php
                                    $badges = ['draft'=>'default','published'=>'info','ongoing'=>'success','completed'=>'default','cancelled'=>'danger'];
                                @endphp
                                <span class="badge badge-{{ $badges[$event->status] ?? 'default' }}">{{ $event->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
