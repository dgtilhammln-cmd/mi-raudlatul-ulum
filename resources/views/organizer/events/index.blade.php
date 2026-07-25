@extends('layouts.app')

@section('title', 'Daftar Event')
@section('page-title', 'Daftar Event')

@section('content')
{{-- Page Header --}}
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:28px;">
    <div>
        <h1 style="font-size:26px;font-weight:900;color:var(--color-text-primary);letter-spacing:-.5px;margin-bottom:4px;">Semua Event</h1>
        <p style="font-size:13px;color:var(--color-text-tertiary);">Kelola seluruh kompetisi dan olimpiade Anda</p>
    </div>
    <a href="{{ route('organizer.events.create') }}" class="btn btn-primary" style="padding:12px 24px;font-size:14px;font-weight:700;border-radius:12px;">
        <i class="fas fa-plus"></i> Buat Event Baru
    </a>
</div>

@if($events->isEmpty())
    <div style="text-align:center;padding:80px 40px;background:var(--color-surface);border-radius:20px;border:2px dashed var(--color-border);">
        <div style="width:72px;height:72px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <i class="fas fa-calendar-plus" style="font-size:28px;color:#166534;"></i>
        </div>
        <h3 style="font-size:20px;font-weight:800;color:var(--color-text-primary);margin-bottom:8px;">Belum Ada Event</h3>
        <p style="color:var(--color-text-tertiary);margin-bottom:24px;">Mulai dengan membuat event atau olimpiade pertama Anda.</p>
        <a href="{{ route('organizer.events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Buat Event Sekarang
        </a>
    </div>
@else
    {{-- Card Grid --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;">
        @foreach($events as $event)
        @php
            $statusColor = ['draft'=>'#6b7280','published'=>'#2563eb','ongoing'=>'#16a34a','completed'=>'#059669','cancelled'=>'#dc2626'];
            $statusBg = ['draft'=>'#f3f4f6','published'=>'#dbeafe','ongoing'=>'#dcfce7','completed'=>'#d1fae5','cancelled'=>'#fee2e2'];
        @endphp
        <div style="border-radius:20px;overflow:hidden;background:var(--color-surface);box-shadow:0 2px 12px rgba(0,0,0,.06);border:1px solid var(--color-border);transition:.25s;cursor:pointer;display:flex;flex-direction:column;"
             onclick="window.location='{{ route('organizer.events.show', $event) }}'"
             onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 12px 32px rgba(0,0,0,.12)'"
             onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.06)'">

            {{-- Poster Image --}}
            <div style="position:relative;aspect-ratio:4/5;overflow:hidden;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));">
                @if($event->poster_image)
                    <img src="{{ Storage::url($event->poster_image) }}" alt="{{ $event->name }}"
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
                    <span style="background:{{ $statusBg[$event->status] ?? '#f3f4f6' }};color:{{ $statusColor[$event->status] ?? '#374151' }};padding:4px 12px;border-radius:100px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">
                        {{ $event->status }}
                    </span>
                </div>

                {{-- Actions --}}
                <div style="position:absolute;top:8px;right:8px;" onclick="event.stopPropagation()">
                    <div style="position:relative;">
                        <button id="dd-{{ $event->id }}" onclick="toggleMenu({{ $event->id }})"
                                style="width:32px;height:32px;border-radius:50%;background:rgba(255,255,255,.2);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.3);color:#fff;cursor:pointer;font-size:14px;display:flex;align-items:center;justify-content:center;transition:.2s;"
                                onmouseover="this.style.background='rgba(255,255,255,.35)'"
                                onmouseout="this.style.background='rgba(255,255,255,.2)'">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <div id="menu-{{ $event->id }}" style="display:none;position:absolute;right:0;top:38px;background:var(--color-surface);box-shadow:0 8px 24px rgba(0,0,0,.15);border-radius:12px;padding:8px;min-width:160px;z-index:50;border:1px solid var(--color-border);">
                            <a href="{{ route('organizer.events.edit', $event) }}"
                               style="display:flex;align-items:center;gap:10px;padding:10px 14px;color:var(--color-text-primary);text-decoration:none;border-radius:8px;font-size:13px;font-weight:600;transition:.15s;"
                               onmouseover="this.style.background='var(--color-surface-hover)'"
                               onmouseout="this.style.background=''">
                                <i class="fas fa-pencil" style="color:var(--color-primary);width:16px;"></i> Edit Event
                            </a>
                            <a href="{{ route('organizer.participants.index', $event) }}"
                               style="display:flex;align-items:center;gap:10px;padding:10px 14px;color:var(--color-text-primary);text-decoration:none;border-radius:8px;font-size:13px;font-weight:600;transition:.15s;"
                               onmouseover="this.style.background='var(--color-surface-hover)'"
                               onmouseout="this.style.background=''">
                                <i class="fas fa-users" style="color:var(--color-primary);width:16px;"></i> Kelola Peserta
                            </a>
                            <div style="height:1px;background:var(--color-border);margin:6px 0;"></div>
                            <form method="POST" action="{{ route('organizer.events.destroy', $event) }}" data-confirm="Yakin hapus event ini?" style="margin:0;">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        style="width:100%;display:flex;align-items:center;gap:10px;padding:10px 14px;color:var(--color-danger);background:none;border:none;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:.15s;"
                                        onmouseover="this.style.background='#fef2f2'"
                                        onmouseout="this.style.background=''">
                                    <i class="fas fa-trash" style="width:16px;"></i> Hapus Event
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Event name overlay on image --}}
                @if($event->poster_image)
                <div style="position:absolute;bottom:0;left:0;right:0;padding:16px 20px;">
                    <h3 style="font-size:16px;font-weight:800;color:#fff;margin:0;line-height:1.3;text-shadow:0 1px 4px rgba(0,0,0,.5);">{{ $event->name }}</h3>
                </div>
                @endif
            </div>

            {{-- Card Body --}}
            <div style="padding:20px;flex:1;display:flex;flex-direction:column;gap:12px;">
                @if(!$event->poster_image)
                <h3 style="font-size:17px;font-weight:800;color:var(--color-text-primary);margin:0;line-height:1.4;">{{ $event->name }}</h3>
                @endif

                @if($event->category)
                <div style="display:inline-flex;align-items:center;gap:6px;font-size:12px;color:var(--color-primary);font-weight:700;background:#f0fdf4;padding:4px 10px;border-radius:100px;width:fit-content;">
                    <i class="fas fa-tag" style="font-size:10px;"></i> {{ $event->category }}
                </div>
                @endif

                <div style="display:flex;gap:6px;font-size:12px;color:var(--color-text-tertiary);font-weight:600;">
                    <i class="fas fa-calendar-alt" style="margin-top:2px;"></i>
                    {{ $event->start_date->format('d M Y') }} — {{ $event->end_date->format('d M Y') }}
                </div>

                {{-- Quick Stats --}}
                <div style="display:flex;gap:0;border:1px solid var(--color-border);border-radius:12px;overflow:hidden;margin-top:4px;">
                    <div style="flex:1;text-align:center;padding:10px 4px;border-right:1px solid var(--color-border);">
                        <div style="font-size:18px;font-weight:900;color:var(--color-primary);">{{ $event->participants_count }}</div>
                        <div style="font-size:10px;color:var(--color-text-tertiary);font-weight:700;text-transform:uppercase;letter-spacing:.3px;">Peserta</div>
                    </div>
                    <div style="flex:1;text-align:center;padding:10px 4px;border-right:1px solid var(--color-border);">
                        <div style="font-size:18px;font-weight:900;color:var(--color-primary);">{{ $event->rounds_count }}</div>
                        <div style="font-size:10px;color:var(--color-text-tertiary);font-weight:700;text-transform:uppercase;letter-spacing:.3px;">Babak</div>
                    </div>
                    <div style="flex:1;text-align:center;padding:10px 4px;">
                        <div style="font-size:18px;font-weight:900;color:var(--color-primary);">{{ $event->question_banks_count ?? 0 }}</div>
                        <div style="font-size:10px;color:var(--color-text-tertiary);font-weight:700;text-transform:uppercase;letter-spacing:.3px;">Bank Soal</div>
                    </div>
                </div>

                <a href="{{ route('organizer.events.show', $event) }}"
                   onclick="event.stopPropagation()"
                   style="display:flex;align-items:center;justify-content:center;gap:8px;padding:10px;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));color:#fff;border-radius:12px;text-decoration:none;font-size:13px;font-weight:700;transition:.2s;margin-top:auto;"
                   onmouseover="this.style.opacity='.85'"
                   onmouseout="this.style.opacity='1'">
                    Kelola Event <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top:24px;">{{ $events->links() }}</div>
@endif

<script>
function toggleMenu(id) {
    const menu = document.getElementById('menu-' + id);
    document.querySelectorAll('[id^="menu-"]').forEach(m => {
        if (m.id !== 'menu-' + id) m.style.display = 'none';
    });
    menu.style.display = menu.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('[id^="dd-"]') && !e.target.closest('[id^="menu-"]')) {
        document.querySelectorAll('[id^="menu-"]').forEach(m => m.style.display = 'none');
    }
});
</script>
@endsection
