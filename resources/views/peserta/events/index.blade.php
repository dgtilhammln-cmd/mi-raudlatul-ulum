@extends('layouts.app')

@section('title', 'Event & E-Raport')
@section('page-title', 'Event & E-Raport')

@push('styles')
<style>
    .event-card {
        background: var(--color-surface);
        border-radius: 24px;
        padding: 32px;
        box-shadow: 0 12px 32px rgba(0,0,0,.04);
        margin-bottom: 24px;
        border: 1px solid rgba(0,0,0,.04);
        display: flex;
        flex-direction: column;
        gap: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .event-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 40px rgba(0,0,0,.08);
    }
    .event-header {
        display: flex;
        gap: 24px;
        align-items: center;
        border-bottom: 1px solid rgba(0,0,0,0.04);
        padding-bottom: 20px;
    }
    .event-icon {
        width: 64px;
        height: 64px;
        background: linear-gradient(135deg, var(--grad-start), var(--grad-end));
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 24px;
        flex-shrink: 0;
        box-shadow: 0 8px 16px rgba(29,179,73,0.2);
    }
    .event-info h3 {
        font-size: 20px;
        font-weight: 800;
        color: var(--color-text-primary);
        margin-bottom: 4px;
    }
    .event-info p {
        font-size: 13px;
        color: var(--color-text-tertiary);
        margin-bottom: 8px;
    }
    .event-meta {
        display: flex;
        gap: 16px;
        font-size: 12px;
        color: var(--color-text-secondary);
        font-weight: 600;
    }
    .cert-section {
        background: var(--color-surface-soft);
        border-radius: 16px;
        padding: 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        border: 1px solid rgba(0,0,0,0.04);
    }
    .cert-info h4 {
        font-size: 16px;
        font-weight: 800;
        color: var(--color-text-primary);
        margin-bottom: 4px;
    }
    .cert-info p {
        font-size: 13px;
        color: var(--color-text-secondary);
    }
    .cert-actions {
        display: flex;
        gap: 12px;
    }
    
    .btn-gradient {
        background: linear-gradient(135deg, var(--grad-start), var(--grad-end));
        color: #fff;
        border: none;
    }
    .btn-gradient:hover {
        box-shadow: 0 6px 16px rgba(29,179,73,0.3);
        transform: translateY(-2px);
    }

    @media (max-width: 768px) {
        .event-card { padding: 16px; border-radius: 16px; gap: 12px; margin-bottom: 16px; }
        .event-header { flex-direction: column; align-items: flex-start; gap: 12px; padding-bottom: 12px; }
        .event-icon { width: 48px; height: 48px; font-size: 20px; border-radius: 12px; }
        .event-info h3 { font-size: 16px; }
        .event-meta { flex-wrap: wrap; gap: 8px; }
        .cert-section { padding: 16px; flex-direction: column; align-items: flex-start; gap: 12px; }
        .cert-actions { width: 100%; flex-wrap: wrap; }
        .cert-actions .btn { flex: 1; min-width: 120px; justify-content: center; }
    }

    /* Modal for Preview */
    .preview-modal {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(6px);
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .preview-modal.active {
        display: flex;
        animation: fadeIn 0.2s ease;
    }
    .preview-content {
        background: #fff;
        width: 100%;
        max-width: 900px;
        height: 80vh;
        border-radius: 24px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        box-shadow: 0 32px 64px rgba(0,0,0,0.3);
    }
    .preview-header {
        padding: 16px 24px;
        background: var(--color-surface);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .preview-header h3 {
        font-size: 16px;
        font-weight: 800;
        margin: 0;
    }
    .btn-close-modal {
        background: var(--color-surface-soft);
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 10px;
        cursor: pointer;
        color: var(--color-text-secondary);
        transition: 0.2s;
    }
    .btn-close-modal:hover {
        background: #fee2e2;
        color: #ef4444;
    }
    .preview-body {
        flex: 1;
        background: #f1f5f9;
        position: relative;
    }
    .preview-iframe {
        width: 100%;
        height: 100%;
        border: none;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
</style>
@endpush

@section('content')

{{-- Hero Banner --}}
<div class="mobile-p-4" style="background:linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%);border-radius:24px;padding:36px 40px;margin-bottom:32px;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30px;right:-30px;width:200px;height:200px;background:rgba(255,255,255,.07);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-40px;right:80px;width:120px;height:120px;background:rgba(255,255,255,.05);border-radius:50%;"></div>
    <div class="mobile-stack" style="position:relative;z-index:2;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:20px;">
        <div>
            <div style="font-size:11px;font-weight:800;color:rgba(255,255,255,.7);text-transform:uppercase;letter-spacing:2px;margin-bottom:8px;">Dashboard Peserta</div>
            <h1 style="font-size:28px;font-weight:900;color:#fff;margin-bottom:8px;line-height:1.2;">Event & E-Raport</h1>
            <p style="font-size:14px;color:rgba(255,255,255,.75);max-width:500px;line-height:1.4;">Riwayat keikutsertaan event kompetisi Anda dan sertifikat/raport penghargaan yang telah diterbitkan.</p>
        </div>
        <div class="mobile-wrap" style="display:flex;gap:20px;">
            <div style="background:rgba(255,255,255,.15);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.2);border-radius:16px;padding:16px 24px;text-align:center;">
                <div style="font-size:28px;font-weight:900;color:#fff;">{{ $participants->count() }}</div>
                <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.7);text-transform:uppercase;">Event Diikuti</div>
            </div>
            <div style="background:rgba(255,255,255,.15);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.2);border-radius:16px;padding:16px 24px;text-align:center;">
                <div style="font-size:28px;font-weight:900;color:#fff;">{{ $participants->sum(fn($p) => $p->certificates->count() + (!empty($p->certificate_link) ? 1 : 0)) }}</div>
                <div style="font-size:11px;font-weight:700;color:rgba(255,255,255,.7);text-transform:uppercase;">Sertif/Raport</div>
            </div>
        </div>
    </div>
</div>

@if($participants->isEmpty())
    <div style="text-align:center;padding:80px 40px;background:var(--color-surface);border-radius:24px;border:2px dashed var(--color-border);">
        <div style="width:72px;height:72px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <i class="fas fa-folder-open" style="font-size:28px;color:var(--grad-start);"></i>
        </div>
        <h3 style="font-size:20px;font-weight:800;color:var(--color-text-primary);margin-bottom:8px;">Belum Ada Riwayat Event</h3>
        <p style="color:var(--color-text-tertiary);">Anda belum mengikuti event kompetisi manapun. Hubungi panitia untuk didaftarkan.</p>
    </div>
@else
    @foreach($participants as $participant)
    @php
        $hasCertificateFile = $participant->certificates->isNotEmpty();
        $hasCertificateLink = !empty($participant->certificate_link);
        $hasCert = $hasCertificateFile || $hasCertificateLink;
    @endphp
    <div class="event-card">
        {{-- Event Header --}}
        <div class="event-header">
            <div class="event-icon">
                @if($hasCert)
                    <i class="fas fa-trophy"></i>
                @else
                    <i class="fas fa-calendar-check"></i>
                @endif
            </div>
            <div class="event-info" style="flex:1;">
                <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                    <h3>{{ $participant->event->name }}</h3>
                    @if($participant->status === 'active')
                        <span style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);color:#166534;padding:3px 12px;border-radius:100px;font-size:11px;font-weight:800;text-transform:uppercase;"><i class="fas fa-circle" style="font-size:7px;vertical-align:middle;animation:pulse-dot 1.5s infinite;"></i> Aktif</span>
                    @elseif($participant->status === 'completed')
                        <span style="background:linear-gradient(135deg,#dbeafe,#bfdbfe);color:#1d4ed8;padding:3px 12px;border-radius:100px;font-size:11px;font-weight:800;text-transform:uppercase;"><i class="fas fa-flag-checkered" style="font-size:9px;"></i> Selesai</span>
                    @elseif($participant->status === 'disqualified')
                        <span style="background:#fee2e2;color:#dc2626;padding:3px 12px;border-radius:100px;font-size:11px;font-weight:800;text-transform:uppercase;"><i class="fas fa-times-circle" style="font-size:9px;"></i> Diskualifikasi</span>
                    @endif
                </div>
                <p style="margin-top:6px;">
                    <i class="far fa-calendar-alt" style="color:var(--color-primary);margin-right:6px;"></i>
                    {{ $participant->event->start_date->translatedFormat('d F Y') }} — {{ $participant->event->end_date->translatedFormat('d F Y') }}
                </p>
                <div class="event-meta">
                    <span><i class="fas fa-id-card" style="color:var(--color-primary);margin-right:4px;"></i> {{ $participant->participant_code }}</span>
                    @if($participant->institution)
                        <span><i class="fas fa-school" style="color:var(--color-primary);margin-right:4px;"></i> {{ $participant->institution }}</span>
                    @endif
                    @if($participant->grade)
                        <span><i class="fas fa-layer-group" style="color:var(--color-primary);margin-right:4px;"></i> {{ $participant->grade }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Certificates Section --}}
        <div>
            <div style="font-size:12px;font-weight:700;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:1px;margin-bottom:12px;">
                <i class="fas fa-certificate" style="color:var(--color-primary);margin-right:6px;"></i> Sertifikat / E-Raport
            </div>

            @if(!$hasCert)
                <div class="cert-section" style="background:var(--color-surface-soft);justify-content:center;text-align:center;flex-direction:column;padding:32px;">
                    <div style="width:52px;height:52px;background:#f1f5f9;border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;">
                        <i class="fas fa-hourglass-half" style="font-size:22px;color:#94a3b8;"></i>
                    </div>
                    <h4 style="font-size:15px;font-weight:700;color:var(--color-text-secondary);margin-bottom:4px;">Sertifikat/Raport Belum Tersedia</h4>
                    <p style="font-size:12px;color:var(--color-text-tertiary);">Penyelenggara belum menerbitkan sertifikat/raport untuk event ini. Harap tunggu pengumuman dari panitia.</p>
                </div>
            @else
                @if($hasCertificateFile)
                    @foreach($participant->certificates as $cert)
                    @php $downloadUrl = route('peserta.certificate.download', $cert); @endphp
                    <div class="cert-section" style="background:linear-gradient(135deg,#fffbeb,#fef9c3);border:1px solid #fde68a;margin-bottom:10px;">
                        <div style="display:flex;gap:16px;align-items:center;">
                            <div style="width:52px;height:52px;background:linear-gradient(135deg,#fef3c7,#ca8a04);border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;box-shadow:0 4px 12px rgba(202,138,4,.3);">
                                <i class="fas fa-award"></i>
                            </div>
                            <div class="cert-info">
                                <h4>E-Sertifikat / Raport Resmi</h4>
                                <p style="color:#92400e;">No. {{ $cert->certificate_number }}</p>
                                @if($cert->issued_at)
                                    <p style="color:#a16207;font-size:11px;margin-top:2px;"><i class="far fa-calendar-check" style="margin-right:4px;"></i>Diterbitkan {{ $cert->issued_at->translatedFormat('d F Y') }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="cert-actions">
                            <button type="button" class="btn btn-secondary" style="border-color:#fde68a;" onclick="openPreview('{{ $downloadUrl }}', 'E-Sertifikat No. {{ $cert->certificate_number }}')">
                                <i class="fas fa-eye"></i> Preview
                            </button>
                            <a href="{{ $downloadUrl }}" class="btn btn-gradient" style="background:linear-gradient(135deg,#d97706,#ca8a04);">
                                <i class="fas fa-download"></i> Unduh PDF
                            </a>
                        </div>
                    </div>
                    @endforeach
                @endif

                @if($hasCertificateLink)
                @php
                    $driveUrl = $participant->certificate_link;
                    $previewUrl = str_replace('/view', '/preview', $driveUrl);
                    // Handle shared Google Drive links: convert to embed/preview format
                    if (strpos($driveUrl, 'drive.google.com') !== false && strpos($driveUrl, '/preview') === false && strpos($driveUrl, 'export=') === false) {
                        // Extract file ID from common patterns
                        preg_match('/\/d\/([a-zA-Z0-9_-]+)/', $driveUrl, $matches);
                        if (!empty($matches[1])) {
                            $fileId = $matches[1];
                            $previewUrl = "https://drive.google.com/file/d/{$fileId}/preview";
                        }
                    }
                @endphp
                <div class="cert-section" style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #bbf7d0;">
                    <div style="display:flex;gap:16px;align-items:center;">
                        <div style="width:52px;height:52px;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:14px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:24px;box-shadow:0 4px 12px rgba(29,179,73,.3);">
                            <i class="fab fa-google-drive"></i>
                        </div>
                        <div class="cert-info">
                            <h4>Sertifikat via Google Drive</h4>
                            <p style="color:#166534;">Sertifikat dikirim oleh panitia melalui Google Drive</p>
                        </div>
                    </div>
                    <div class="cert-actions">
                        <button type="button" class="btn btn-secondary" style="border-color:#bbf7d0;color:#166534;" onclick="openPreview('{{ $previewUrl }}', 'Preview Sertifikat Google Drive')">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                        <a href="{{ $driveUrl }}" target="_blank" rel="noopener" class="btn btn-gradient" style="background:linear-gradient(135deg,var(--grad-start),var(--grad-end));">
                            <i class="fas fa-external-link-alt"></i> Buka Drive
                        </a>
                    </div>
                </div>
                @endif
            @endif
        </div>
    </div>
    @endforeach
@endif

<!-- Preview Modal -->
<div id="certPreviewModal" class="preview-modal" onclick="if(event.target===this) closePreview()">
    <div class="preview-content">
        <div class="preview-header">
            <h3 id="previewTitle">Preview Sertifikat</h3>
            <button type="button" class="btn-close-modal" onclick="closePreview()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="preview-body">
            <div id="previewLoader" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:#f8fafc;z-index:1;">
                <div style="text-align:center;color:var(--color-text-tertiary);">
                    <i class="fas fa-circle-notch fa-spin" style="font-size:32px;color:var(--color-primary);margin-bottom:12px;display:block;"></i>
                    <p style="font-size:13px;font-weight:600;">Memuat pratinjau...</p>
                </div>
            </div>
            <iframe id="previewFrame" class="preview-iframe" src="" onload="document.getElementById('previewLoader').style.display='none'"></iframe>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openPreview(url, title) {
        document.getElementById('previewTitle').innerText = title;
        document.getElementById('previewFrame').src = url;
        document.getElementById('previewLoader').style.display = 'flex';
        document.getElementById('certPreviewModal').classList.add('active');
        document.body.style.overflow = 'hidden'; // Prevent background scrolling
    }

    function closePreview() {
        document.getElementById('certPreviewModal').classList.remove('active');
        document.getElementById('previewFrame').src = '';
        document.body.style.overflow = '';
    }
</script>
@endpush
