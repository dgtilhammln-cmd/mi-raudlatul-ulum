@extends('layouts.app')

@section('title', 'Kelola E-Sertifikat')
@section('page-title', 'Kelola E-Sertifikat')

@section('content')
<div class="grid grid-2">
    <!-- Panduan -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Panduan Import Link Sertifikat</h3>
        </div>
        <div class="alert alert-info" style="background:#e0f2fe;border:1px solid #bae6fd;color:#0369a1;margin-top:16px;">
            <i class="fas fa-info-circle"></i> Gunakan file Excel (.xlsx, .xls) atau .csv untuk mengimpor link Google Drive sertifikat peserta secara massal.
        </div>
        
        <p style="font-size:13px;color:var(--color-text-secondary);margin-bottom:12px;">Pastikan file Excel Anda memiliki <strong>baris header (baris pertama)</strong> dengan nama kolom yang persis seperti berikut:</p>
        
        <div class="table-wrapper" style="margin-bottom:16px;border:1px solid rgba(0,0,0,0.08);border-radius:var(--radius-sm);">
            <table>
                <thead>
                    <tr>
                        <th>kode_peserta</th>
                        <th>link_drive</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PESERTA-001</td>
                        <td>https://drive.google.com/file/d/xxxx/view</td>
                    </tr>
                    <tr>
                        <td>PESERTA-002</td>
                        <td>https://drive.google.com/file/d/yyyy/view</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <ul style="font-size:12px;color:var(--color-text-secondary);padding-left:16px;line-height:1.6;">
            <li><strong style="color:var(--color-text-primary);">kode_peserta:</strong> ID Peserta yang sudah terdaftar di event (misal: PESERTA-001).</li>
            <li><strong style="color:var(--color-text-primary);">link_drive:</strong> Link Google Drive yang dapat diakses publik (Anyone with the link can view).</li>
            <li>Sistem akan mengabaikan peserta yang tidak ditemukan di dalam event yang Anda pilih.</li>
        </ul>
    </div>

    <!-- Form Upload -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Upload File Excel</h3>
        </div>
        
        <form action="{{ route('organizer.certificates.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group mt-4">
                <label class="form-label">Pilih Event</label>
                <select name="event_id" class="form-select" required>
                    <option value="">-- Pilih Event --</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ (isset($selectedEvent) && $selectedEvent->id == $event->id) ? 'selected' : '' }}>{{ $event->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group mt-4">
                <label class="form-label">Pilih File Excel (.xlsx / .csv)</label>
                <input type="file" name="file" class="form-input" accept=".xlsx,.xls,.csv" required style="padding:8px 14px;">
            </div>
            
            <div class="mt-6">
                <button type="submit" class="btn btn-primary" style="width:100%;">
                    <i class="fas fa-upload"></i> Proses Import
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card mt-6">
    <div class="card-header" style="flex-wrap: wrap; gap: 16px;">
        <h3 class="card-title">Riwayat Sertifikat Terkirim</h3>
        
        <form method="GET" action="{{ route('organizer.certificates.index') }}" class="flex items-center gap-2" style="flex-wrap: wrap;">
            <select name="event_id" class="form-select" style="width: auto; min-width: 250px;" required>
                <option value="">-- Lihat Riwayat Event --</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}" {{ (isset($selectedEvent) && $selectedEvent->id == $event->id) ? 'selected' : '' }}>{{ $event->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-secondary"><i class="fas fa-search"></i> Tampilkan</button>
        </form>
    </div>

    @if(isset($participants))
        @if($participants->count() > 0)
            <div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
                <span class="badge badge-success">Total: {{ $participants->total() }} Sertifikat</span>
                <form action="{{ route('organizer.certificates.destroyAll', $selectedEvent->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mereset/menghapus SEMUA link sertifikat untuk event ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i> Hapus Semua Sertifikat Event Ini</button>
                </form>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Peserta</th>
                            <th>Nama Akun</th>
                            <th>Link Google Drive</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $idx => $p)
                        <tr>
                            <td>{{ $participants->firstItem() + $idx }}</td>
                            <td><strong>{{ $p->participant_code }}</strong></td>
                            <td>{{ $p->user ? $p->user->name : 'N/A' }}</td>
                            <td>
                                <a href="{{ $p->certificate_link }}" target="_blank" style="font-size: 12px; max-width: 200px; display: inline-block; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $p->certificate_link }}">
                                    <i class="fas fa-external-link-alt"></i> {{ $p->certificate_link }}
                                </a>
                            </td>
                            <td>
                                <div class="flex items-center gap-2" style="justify-content: center;">
                                    <button type="button" class="btn btn-secondary btn-sm" onclick="editCert('{{ $p->id }}', '{{ $p->certificate_link }}')" title="Edit Link">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('organizer.certificates.destroy', $p->id) }}" method="POST" onsubmit="return confirm('Hapus link sertifikat untuk peserta ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus Link"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4">
                {{ $participants->appends(request()->query())->links() }}
            </div>
        @else
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>Belum ada link sertifikat yang diimport untuk event ini.</p>
            </div>
        @endif
    @else
        <div style="margin-top: 24px;">
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;">
                @foreach($events as $ev)
                @php
                    $statusColor = ['draft'=>'#6b7280','published'=>'#2563eb','ongoing'=>'#16a34a','completed'=>'#059669','cancelled'=>'#dc2626'];
                    $statusBg = ['draft'=>'#f3f4f6','published'=>'#dbeafe','ongoing'=>'#dcfce7','completed'=>'#d1fae5','cancelled'=>'#fee2e2'];
                @endphp
                <div style="border-radius:20px;overflow:hidden;background:var(--color-surface);box-shadow:0 2px 12px rgba(0,0,0,.06);border:1px solid var(--color-border);transition:.25s;cursor:pointer;display:flex;flex-direction:column;"
                     onclick="window.location='{{ route('organizer.certificates.index', ['event_id' => $ev->id]) }}'"
                     onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,.12)'"
                     onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.06)'">
        
                    {{-- Poster Image --}}
                    <div style="position:relative;aspect-ratio:4/5;overflow:hidden;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));">
                        @if($ev->poster_image)
                            <img src="{{ Storage::url($ev->poster_image) }}" alt="{{ $ev->name }}"
                                 style="width:100%;height:100%;object-fit:cover;transition:.4s;"
                                 onmouseover="this.style.transform='scale(1.05)'"
                                 onmouseout="this.style.transform='scale(1)'">
                            <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(5,46,22,.8) 0%,transparent 60%);"></div>
                        @else
                            <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;flex-direction:column;gap:8px;">
                                <i class="fas fa-trophy" style="font-size:36px;color:rgba(255,255,255,.4);"></i>
                                <span style="font-size:12px;color:rgba(255,255,255,.3);font-weight:600;">Belum ada poster</span>
                            </div>
                        @endif
        
                        {{-- Status badge overlay --}}
                        <div style="position:absolute;top:12px;left:12px;">
                            <span style="background:{{ $statusBg[$ev->status] ?? '#f3f4f6' }};color:{{ $statusColor[$ev->status] ?? '#374151' }};padding:4px 12px;border-radius:100px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">
                                {{ $ev->status }}
                            </span>
                        </div>
        
                        {{-- Event name overlay on image --}}
                        @if($ev->poster_image)
                        <div style="position:absolute;bottom:0;left:0;right:0;padding:16px 20px;">
                            <h3 style="font-size:16px;font-weight:800;color:#fff;margin:0;line-height:1.3;text-shadow:0 1px 4px rgba(0,0,0,.5);">{{ $ev->name }}</h3>
                        </div>
                        @endif
                    </div>
        
                    {{-- Card Body --}}
                    <div style="padding:20px;flex:1;display:flex;flex-direction:column;gap:12px;">
                        @if(!$ev->poster_image)
                        <h3 style="font-size:17px;font-weight:800;color:var(--color-text-primary);margin:0;line-height:1.4;">{{ $ev->name }}</h3>
                        @endif
        
                        @if($ev->category)
                        <div style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--color-primary);font-weight:700;background:#f0fdf4;padding:4px 10px;border-radius:100px;width:fit-content;">
                            <i class="fas fa-tag" style="font-size:10px;"></i> {{ $ev->category }}
                        </div>
                        @endif
        
                        <div style="display:flex;gap:6px;font-size:12px;color:var(--color-text-tertiary);font-weight:600;">
                            <i class="fas fa-calendar-alt" style="margin-top:2px;"></i>
                            {{ $ev->start_date->format('d M Y') }} — {{ $ev->end_date->format('d M Y') }}
                        </div>
                        
                        <div style="display:flex;align-items:center;justify-content:space-between;margin-top:auto;padding-top:12px;border-top:1px solid var(--color-border);">
                            <div style="font-size:12px;font-weight:700;color:var(--color-text-secondary);">
                                <i class="fas fa-users" style="margin-right:4px;"></i> {{ $ev->participants_count }} Peserta
                            </div>
                            <div style="color:var(--color-primary);font-size:12px;font-weight:800;">
                                Pilih Event <i class="fas fa-arrow-right" style="margin-left:4px;"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

{{-- Edit Modal --}}
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:100; align-items:center; justify-content:center;">
    <div class="card" style="width: 100%; max-width: 400px; margin: 20px;">
        <div class="card-header">
            <h3 class="card-title">Edit Link Sertifikat</h3>
            <button type="button" onclick="closeEditModal()" style="background:none;border:none;cursor:pointer;font-size:16px;color:var(--color-text-secondary);"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Link Google Drive</label>
                <input type="url" name="certificate_link" id="editCertLink" class="form-input" required>
            </div>
            <div class="flex justify-between mt-6">
                <button type="button" class="btn btn-secondary" onclick="closeEditModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editCert(id, currentLink) {
    document.getElementById('editCertLink').value = currentLink;
    document.getElementById('editForm').action = "{{ url('organizer/certificates') }}/" + id;
    document.getElementById('editModal').style.display = 'flex';
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}
</script>

@endsection
