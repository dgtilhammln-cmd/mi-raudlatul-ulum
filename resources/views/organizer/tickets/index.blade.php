@extends('layouts.app')
@section('title', 'Laporan Kendala Peserta')
@section('page-title', 'Kendala Peserta')

@section('content')
<div style="background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:20px;padding:28px 36px;margin-bottom:28px;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
    <div style="position:relative;z-index:2;">
        <h1 style="font-size:24px;font-weight:900;color:#fff;margin-bottom:6px;">Laporan Kendala Peserta</h1>
        <p style="color:rgba(255,255,255,.7);font-size:13px;">Daftar pesan dan kendala yang dikirimkan oleh peserta melalui fitur "Hubungi Kami".</p>
    </div>
</div>

<div class="card">
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th style="width:60px;text-align:center;">#</th>
                    <th>Nama & WA</th>
                    <th>Kebutuhan</th>
                    <th>Pesan / Kendala</th>
                    <th>Tanggal</th>
                    <th style="text-align:center;">Status</th>
                    <th style="text-align:right;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $i => $ticket)
                <tr>
                    <td style="text-align:center;">{{ $tickets->firstItem() + $i }}</td>
                    <td>
                        <div style="font-weight:700;color:var(--color-text-primary);">{{ $ticket->name }}</div>
                        <div style="font-size:12px;color:var(--color-text-tertiary);"><i class="fab fa-whatsapp" style="color:#25d366;margin-right:4px;"></i>{{ $ticket->wa_number }}</div>
                    </td>
                    <td style="font-weight:600;">
                        <span style="background:var(--color-surface-hover);padding:4px 10px;border-radius:6px;font-size:12px;">{{ $ticket->needs }}</span>
                    </td>
                    <td>
                        <div style="font-size:13px;color:var(--color-text-secondary);max-width:300px;line-height:1.5;">
                            {{ $ticket->message }}
                        </div>
                    </td>
                    <td style="font-size:12px;color:var(--color-text-tertiary);">{{ $ticket->created_at->format('d M Y H:i') }}</td>
                    <td style="text-align:center;">
                        @if($ticket->status === 'open')
                            <span style="background:#fefce8;color:#ca8a04;padding:4px 10px;border-radius:100px;font-size:11px;font-weight:700;">Open</span>
                        @else
                            <span style="background:#f1f5f9;color:#64748b;padding:4px 10px;border-radius:100px;font-size:11px;font-weight:700;">Closed</span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <div style="display:flex;justify-content:flex-end;gap:8px;">
                            @php
                                $cleanWa = preg_replace('/[^0-9]/', '', $ticket->wa_number);
                                if(substr($cleanWa, 0, 1) === '0') $cleanWa = '62' . substr($cleanWa, 1);
                            @endphp
                            <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="btn btn-sm" style="background:#25d366;color:#fff;border:none;" title="Balas via WA">
                                <i class="fab fa-whatsapp"></i> Balas
                            </a>
                            @if($ticket->status === 'open')
                            <form action="{{ route('organizer.tickets.close', $ticket) }}" method="POST" style="display:inline;" data-confirm="Tutup tiket kendala ini?">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background:var(--color-surface-hover);color:var(--color-text-secondary);border:1px solid var(--color-border);" title="Tandai Selesai">
                                    <i class="fas fa-check"></i> Selesai
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--color-text-tertiary);">
                        <i class="fas fa-inbox" style="font-size:32px;margin-bottom:12px;display:block;"></i>
                        Belum ada laporan kendala dari peserta.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tickets->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--color-border);">
        {{ $tickets->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
