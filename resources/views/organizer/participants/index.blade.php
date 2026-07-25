@extends('layouts.app')

@section('title', 'Peserta — ' . $event->name)
@section('page-title', 'Peserta: ' . $event->name)

@section('content')
{{-- Import --}}
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title"><i class="fas fa-file-excel" style="color:var(--color-success)"></i> Import Peserta dari Excel</h3>
    </div>
    <div style="margin-bottom:20px;">
        <p style="font-size:13px;color:var(--color-text-secondary);margin-bottom:12px;">Pastikan file Excel Anda memiliki <strong>baris header (baris pertama)</strong> dengan nama kolom persis seperti berikut:</p>
        <div class="table-wrapper" style="border:1px solid rgba(0,0,0,0.08);border-radius:var(--radius-sm);background:#fff;">
            <table style="width:100%;text-align:left;font-size:13px;">
                <thead style="background:#f8fafc;color:var(--color-text-primary);">
                    <tr>
                        <th style="padding:10px 16px;border-bottom:1px solid #e2e8f0;">participant_id</th>
                        <th style="padding:10px 16px;border-bottom:1px solid #e2e8f0;">name</th>
                        <th style="padding:10px 16px;border-bottom:1px solid #e2e8f0;">access_code</th>
                        <th style="padding:10px 16px;border-bottom:1px solid #e2e8f0;">institution</th>
                        <th style="padding:10px 16px;border-bottom:1px solid #e2e8f0;">grade</th>
                        <th style="padding:10px 16px;border-bottom:1px solid #e2e8f0;">major</th>
                    </tr>
                </thead>
                <tbody style="color:var(--color-text-secondary);">
                    <tr>
                        <td style="padding:10px 16px;border-bottom:1px solid #f1f5f9;">P001</td>
                        <td style="padding:10px 16px;border-bottom:1px solid #f1f5f9;">Ahmad Subarjo</td>
                        <td style="padding:10px 16px;border-bottom:1px solid #f1f5f9;">AC-AHM123</td>
                        <td style="padding:10px 16px;border-bottom:1px solid #f1f5f9;">SMAN 1 SBY</td>
                        <td style="padding:10px 16px;border-bottom:1px solid #f1f5f9;">12</td>
                        <td style="padding:10px 16px;border-bottom:1px solid #f1f5f9;">IPA</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <ul style="font-size:12px;color:var(--color-text-tertiary);margin-top:12px;padding-left:16px;line-height:1.6;">
            <li><strong>participant_id</strong> dan <strong>name</strong> wajib diisi. Kolom lainnya opsional.</li>
            <li>Kosongkan <strong>access_code</strong> jika ingin sistem *generate* otomatis.</li>
        </ul>
    </div>
    <form method="POST" action="{{ route('organizer.participants.import', $event) }}" enctype="multipart/form-data" class="flex items-center gap-4">
        @csrf
        <input type="file" name="file" accept=".xlsx,.xls,.csv" required class="form-input" style="max-width:400px;">
        <button type="submit" class="btn btn-success"><i class="fas fa-upload"></i> Import</button>
    </form>
</div>

{{-- Import Logs --}}
@if($importLogs->isNotEmpty())
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Riwayat Import</h3>
    </div>
    <div class="table-wrapper">
        <table>
            <thead><tr><th>File</th><th>Total</th><th>Berhasil</th><th>Gagal</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @foreach($importLogs as $log)
                <tr>
                    <td style="font-weight:600;color:#fff">{{ $log->filename }}</td>
                    <td>{{ $log->total_rows }}</td>
                    <td style="color:var(--color-success)">{{ $log->success_count }}</td>
                    <td style="color:var(--color-danger)">{{ $log->failed_count }}</td>
                    <td><span class="badge badge-{{ $log->status=='done'?'success':($log->status=='failed'?'danger':'warning') }}">{{ $log->status }}</span></td>
                    <td>
                        @if($log->access_codes)
                        <a href="{{ route('organizer.participants.export-access', [$event, $log]) }}" class="btn btn-secondary btn-sm"><i class="fas fa-download"></i> Akses</a>
                        @endif
                        <a href="{{ route('organizer.participants.import.result', [$event, $log]) }}" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- Participants List --}}
<div class="card">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h3 class="card-title">Daftar Peserta ({{ $participants->total() }})</h3>
        <div style="display:flex;gap:12px;align-items:center;">
            <span style="font-size:12px;color:var(--color-text-tertiary);">Klik ikon kunci untuk ubah kode akses</span>
            @if($participants->total() > 0)
            <form method="POST" action="{{ route('organizer.participants.destroyAll', $event) }}" data-confirm="PERINGATAN: Hapus SEMUA peserta dari event ini? Aksi ini tidak dapat dibatalkan!">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm" style="font-weight:700;"><i class="fas fa-trash-alt"></i> Hapus Semua Peserta</button>
            </form>
            @endif
        </div>
    </div>

    @if($participants->isEmpty())
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <p>Belum ada peserta. Import dari Excel di atas.</p>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead><tr>
                    <th>ID Peserta</th>
                    <th>Nama</th>
                    <th>Institusi / Asal</th>
                    <th>Jurusan</th>
                    <th>Kode Akses</th>
                    <th>Status Event</th>
                    <th style="text-align:right">Aksi</th>
                </tr></thead>
                <tbody>
                    @foreach($participants as $p)
                    <tr id="row-{{ $p->id }}">
                        <td style="font-family:monospace;color:var(--color-accent);font-weight:700;">{{ $p->participant_code }}</td>
                        <td style="color:var(--color-text-primary);font-weight:700">{{ $p->user->name }}</td>
                        <td>{{ $p->institution ?? '—' }}</td>
                        <td>{{ $p->major ?? '—' }}</td>
                        <td>
                            {{-- Kode akses dengan toggle visibility & inline edit --}}
                            <div style="display:flex;align-items:center;gap:8px;" id="code-display-{{ $p->id }}">
                                <span id="code-val-{{ $p->id }}" style="font-family:monospace;font-weight:700;font-size:13px;background:var(--color-surface-soft);padding:4px 10px;border-radius:8px;border:1px solid var(--color-border);letter-spacing:1px;">
                                    {{ $p->access_code ?? '—' }}
                                </span>
                                <button onclick="openEditCode({{ $p->id }}, '{{ $p->access_code }}')"
                                    style="background:none;border:1px solid var(--color-border);border-radius:8px;padding:4px 8px;cursor:pointer;color:var(--color-primary);transition:.2s;"
                                    title="Ubah kode akses"
                                    onmouseover="this.style.background='var(--color-accent-light)'"
                                    onmouseout="this.style.background='none'">
                                    <i class="fas fa-key" style="font-size:12px;"></i>
                                </button>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $p->status=='active'?'success':($p->status=='disqualified'?'danger':'default') }}">{{ $p->status }}</span>
                        </td>
                        <td style="text-align:right;white-space:nowrap;">
                            <form method="POST" action="{{ route('organizer.participants.destroy', $p) }}" style="display:inline" data-confirm="Hapus peserta {{ $p->user->name }}?">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" title="Hapus peserta"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4" style="padding:0 20px 16px;">{{ $participants->links() }}</div>
    @endif
</div>

{{-- Modal Edit Kode Akses --}}
<div id="modal-edit-code" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);backdrop-filter:blur(4px);align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:20px;padding:28px 32px;width:100%;max-width:400px;box-shadow:0 24px 48px rgba(0,0,0,.15);animation:slideUp .25s ease;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <div>
                <h3 style="font-size:16px;font-weight:800;color:var(--color-text-primary);margin-bottom:2px;">Ubah Kode Akses</h3>
                <p style="font-size:12px;color:var(--color-text-tertiary);">Kode akses digunakan peserta untuk login.</p>
            </div>
            <button onclick="closeEditCode()" style="background:var(--color-surface-soft);border:none;border-radius:10px;width:32px;height:32px;cursor:pointer;font-size:16px;color:var(--color-text-tertiary);">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="form-group">
            <label class="form-label">Kode Akses Baru <span style="color:var(--color-danger);">*</span></label>
            <div style="position:relative;">
                <input type="text" id="new-access-code" class="form-input" placeholder="Min. 6 karakter" minlength="6" maxlength="20"
                    style="font-family:monospace;font-size:16px;letter-spacing:2px;padding-right:46px;">
                <button type="button" onclick="genCode()" title="Generate otomatis"
                    style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:var(--color-primary);font-size:14px;">
                    <i class="fas fa-random"></i>
                </button>
            </div>
            <div style="margin-top:6px;font-size:11px;color:var(--color-text-tertiary);">
                <i class="fas fa-info-circle" style="margin-right:4px;"></i>Gunakan kombinasi huruf dan angka. Min. 6 karakter.
            </div>
        </div>

        <div id="modal-alert" style="display:none;padding:10px 14px;border-radius:10px;font-size:12px;margin-bottom:16px;"></div>

        <div style="display:flex;gap:10px;">
            <button onclick="closeEditCode()" class="btn btn-secondary" style="flex:1;">Batal</button>
            <button onclick="saveCode()" class="btn" id="btn-save-code" style="flex:2;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));color:#fff;font-weight:700;">
                <i class="fas fa-save" style="margin-right:6px;"></i>Simpan Kode
            </button>
        </div>
    </div>
</div>

@push('styles')
<style>
@keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
</style>
@endpush

@push('scripts')
<script>
let currentParticipantId = null;

function openEditCode(id, currentCode) {
    currentParticipantId = id;
    document.getElementById('new-access-code').value = currentCode || '';
    document.getElementById('modal-alert').style.display = 'none';
    const modal = document.getElementById('modal-edit-code');
    modal.style.display = 'flex';
    setTimeout(() => document.getElementById('new-access-code').focus(), 100);
}

function closeEditCode() {
    document.getElementById('modal-edit-code').style.display = 'none';
    currentParticipantId = null;
}

function genCode() {
    const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
    let code = '';
    for (let i = 0; i < 8; i++) code += chars[Math.floor(Math.random() * chars.length)];
    document.getElementById('new-access-code').value = code;
}

function saveCode() {
    const code = document.getElementById('new-access-code').value.trim();
    const alertEl = document.getElementById('modal-alert');
    const btnSave = document.getElementById('btn-save-code');

    if (code.length < 6) {
        alertEl.style.display = 'block';
        alertEl.style.background = '#fef2f2';
        alertEl.style.color = '#dc2626';
        alertEl.innerHTML = '<i class="fas fa-exclamation-triangle" style="margin-right:6px;"></i>Kode akses minimal 6 karakter.';
        return;
    }

    btnSave.disabled = true;
    btnSave.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i>Menyimpan...';

    fetch(`/organizer/participants/${currentParticipantId}/access-code`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ access_code: code })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            // Update tampilan di tabel
            document.getElementById('code-val-' + currentParticipantId).textContent = code;

            alertEl.style.display = 'block';
            alertEl.style.background = '#f0fdf4';
            alertEl.style.color = '#16a34a';
            alertEl.innerHTML = '<i class="fas fa-check-circle" style="margin-right:6px;"></i>Kode akses berhasil diperbarui!';

            setTimeout(() => closeEditCode(), 1200);
        } else {
            throw new Error(data.message || 'Gagal menyimpan');
        }
    })
    .catch(err => {
        alertEl.style.display = 'block';
        alertEl.style.background = '#fef2f2';
        alertEl.style.color = '#dc2626';
        alertEl.innerHTML = '<i class="fas fa-exclamation-triangle" style="margin-right:6px;"></i>' + err.message;
    })
    .finally(() => {
        btnSave.disabled = false;
        btnSave.innerHTML = '<i class="fas fa-save" style="margin-right:6px;"></i>Simpan Kode';
    });
}

// Close modal on backdrop click
document.getElementById('modal-edit-code').addEventListener('click', function(e) {
    if (e.target === this) closeEditCode();
});

// Keyboard shortcut: Enter to save, Esc to close
document.addEventListener('keydown', function(e) {
    if (document.getElementById('modal-edit-code').style.display === 'flex') {
        if (e.key === 'Enter') saveCode();
        if (e.key === 'Escape') closeEditCode();
    }
});
</script>
@endpush

@endsection
