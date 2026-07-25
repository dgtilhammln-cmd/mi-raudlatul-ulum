@extends('layouts.app')

@section('title', 'Kelola Peserta Babak')
@section('page-title', 'Kelola Peserta Babak')

@section('content')
<div class="mb-6">
    <div style="font-size:13px;color:var(--color-text-secondary);margin-bottom:12px;">
        <a href="{{ route('organizer.events.index') }}" style="color:var(--color-primary);text-decoration:none;">Events</a> 
        <i class="fas fa-chevron-right" style="font-size:10px;margin:0 8px;"></i>
        <a href="{{ route('organizer.events.show', $event) }}" style="color:var(--color-primary);text-decoration:none;">{{ $event->name }}</a>
        <i class="fas fa-chevron-right" style="font-size:10px;margin:0 8px;"></i>
        <span>Babak {{ $round->name }}</span>
    </div>
    
    <div class="flex justify-between items-center">
        <div>
            <h2 style="font-size:24px;font-weight:800;color:var(--color-text-primary);">Kelola Peserta Babak: {{ $round->name }}</h2>
            <p style="color:var(--color-text-tertiary);font-size:14px;margin-top:4px;">Pilih peserta yang berhak mengikuti babak ini.</p>
        </div>
        <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
    </div>
</div>

<form method="POST" action="{{ route('organizer.rounds.participants.sync', $round) }}">
    @csrf
    <div class="card">
        <div class="card-header flex justify-between items-center">
            <h3 class="card-title">Daftar Peserta Event</h3>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Akses Peserta</button>
        </div>
        
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th style="width:50px;text-align:center;">
                            <input type="checkbox" id="selectAll" onchange="toggleAll(this)">
                        </th>
                        <th>Peserta</th>
                        <th>ID Login</th>
                        <th>Institusi / Asal</th>
                        <th style="text-align:center;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($allParticipants as $p)
                    <tr>
                        <td style="text-align:center;">
                            <input type="checkbox" name="participant_ids[]" value="{{ $p->id }}" class="participant-cb" 
                                {{ in_array($p->id, $assignedParticipantIds) ? 'checked' : '' }}>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:12px;">
                                {{-- Avatar --}}
                                <div style="position:relative;flex-shrink:0;">
                                    @if($p->user && $p->user->getAvatarUrl())
                                        <img src="{{ $p->user->getAvatarUrl() }}" alt="{{ $p->user->name }}" 
                                            style="width:36px;height:36px;border-radius:10px;object-fit:cover;border:2px solid var(--color-border);cursor:pointer;"
                                            onclick="showAvatarModal('{{ $p->user->getAvatarUrl() }}', '{{ $p->user->name }}')" />
                                    @else
                                        <div style="width:36px;height:36px;border-radius:10px;background:linear-gradient(135deg,#e0e7ff,#c7d2fe);display:flex;align-items:center;justify-content:center;color:#4f46e5;font-weight:800;font-size:14px;border:2px solid var(--color-border);">
                                            {{ strtoupper(substr($p->user->name ?? '?', 0, 1)) }}
                                        </div>
                                    @endif
                                    {{-- Online/Offline Dot --}}
                                    @if($p->user)
                                    <span style="position:absolute;bottom:-1px;right:-1px;width:10px;height:10px;border-radius:50%;border:2px solid #fff;{{ $p->user->isOnline() ? 'background:#22c55e;' : 'background:#d1d5db;' }}"></span>
                                    @endif
                                </div>
                                {{-- Name --}}
                                <div>
                                    <div style="font-weight:700;font-size:14px;color:var(--color-text-primary);">{{ $p->user->name ?? '-' }}</div>
                                    <div style="font-size:11px;color:var(--color-text-tertiary);margin-top:2px;">
                                        @if($p->user && $p->user->isOnline())
                                            <span style="color:#16a34a;font-weight:600;"><i class="fas fa-circle" style="font-size:6px;margin-right:4px;"></i>Online</span>
                                        @else
                                            <span style="color:#9ca3af;font-weight:600;"><i class="fas fa-circle" style="font-size:6px;margin-right:4px;"></i>Offline</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span style="font-family:monospace;font-size:13px;font-weight:600;color:var(--color-text-secondary);background:var(--color-surface-soft);padding:4px 10px;border-radius:6px;border:1px solid var(--color-border);">
                                {{ $p->user->participant_id ?? $p->participant_code ?? '-' }}
                            </span>
                        </td>
                        <td style="font-size:13px;color:var(--color-text-secondary);">{{ $p->institution ?? '-' }}</td>
                        <td style="text-align:center;"><span class="badge badge-{{ $p->status == 'active' ? 'success' : 'default' }}">{{ $p->status }}</span></td>
                    </tr>
                    @endforeach
                    @if($allParticipants->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align:center;padding:32px;">Belum ada peserta di event ini.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</form>

{{-- Avatar Preview Modal --}}
<div id="avatarModal" onclick="this.style.display='none'" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.6);backdrop-filter:blur(8px);align-items:center;justify-content:center;cursor:pointer;">
    <div style="background:#fff;border-radius:20px;padding:12px;box-shadow:0 32px 64px rgba(0,0,0,.2);max-width:340px;text-align:center;" onclick="event.stopPropagation()">
        <img id="avatarModalImg" src="" alt="" style="width:300px;height:300px;object-fit:cover;border-radius:14px;margin-bottom:12px;" />
        <div id="avatarModalName" style="font-weight:800;font-size:16px;color:var(--color-text-primary);"></div>
    </div>
</div>

<script>
function toggleAll(source) {
    const checkboxes = document.querySelectorAll('.participant-cb');
    checkboxes.forEach(cb => cb.checked = source.checked);
}

function showAvatarModal(url, name) {
    document.getElementById('avatarModalImg').src = url;
    document.getElementById('avatarModalName').textContent = name;
    const modal = document.getElementById('avatarModal');
    modal.style.display = 'flex';
}
</script>
@endsection
