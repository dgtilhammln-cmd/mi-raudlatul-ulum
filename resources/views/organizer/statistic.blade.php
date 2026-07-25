@extends('layouts.app')
@section('page-title', 'Statistik Event')
@section('content')

<div class="stats-header" style="background:var(--color-surface);border:1px solid var(--color-border);border-radius:24px;padding:32px;margin-bottom:24px;box-shadow:var(--shadow-sm);position:relative;overflow:hidden;">
    <div style="position:absolute;top:-40px;right:-40px;width:150px;height:150px;background:var(--color-primary);filter:blur(80px);opacity:0.2;border-radius:50%;"></div>
    
    <div style="display:flex;align-items:center;gap:20px;position:relative;z-index:2;">
        <div style="width:64px;height:64px;background:linear-gradient(135deg, var(--color-primary), var(--color-accent));border-radius:20px;display:flex;align-items:center;justify-content:center;color:#fff;font-size:28px;box-shadow:0 8px 24px rgba(29,179,73,0.3);">
            <i class="fas fa-chart-line"></i>
        </div>
        <div>
            <h1 style="font-size:24px;font-weight:900;color:var(--color-text-primary);margin-bottom:4px;">Statistik Pertumbuhan Event</h1>
            <p style="font-size:14px;color:var(--color-text-secondary);margin:0;">Pantau konsistensi dan tren peserta pada semua event.</p>
        </div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr;gap:24px;margin-bottom:24px;">
    {{-- Chart Card --}}
    <div style="background:var(--color-surface);border:1px solid var(--color-border);border-radius:24px;padding:24px;box-shadow:var(--shadow-sm);">
        <h3 style="font-size:16px;font-weight:800;color:var(--color-text-primary);margin-bottom:20px;"><i class="fas fa-chart-area" style="color:var(--color-primary);margin-right:8px;"></i>Grafik Tren Peserta</h3>
        
        <div style="height:300px;position:relative;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
</div>

{{-- Interactive Table --}}
<div style="background:var(--color-surface);border:1px solid var(--color-border);border-radius:24px;padding:24px;box-shadow:var(--shadow-sm);">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h3 style="font-size:16px;font-weight:800;color:var(--color-text-primary);margin:0;"><i class="fas fa-table" style="color:var(--color-primary);margin-right:8px;"></i>Detail Event</h3>
    </div>

    <div class="table-responsive">
        <table style="width:100%;border-collapse:separate;border-spacing:0 8px;">
            <thead>
                <tr>
                    <th style="text-align:left;padding:0 16px 12px;font-size:12px;font-weight:700;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid var(--color-border);">Nama Event</th>
                    <th style="text-align:left;padding:0 16px 12px;font-size:12px;font-weight:700;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid var(--color-border);">Tgl Pelaksanaan</th>
                    <th style="text-align:center;padding:0 16px 12px;font-size:12px;font-weight:700;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid var(--color-border);">Jumlah Peserta</th>
                    <th style="text-align:center;padding:0 16px 12px;font-size:12px;font-weight:700;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid var(--color-border);">Tren</th>
                    <th style="text-align:right;padding:0 16px 12px;font-size:12px;font-weight:700;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:1px;border-bottom:1px solid var(--color-border);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                <tr class="event-row" onclick="window.location='{{ route('organizer.events.show', $event) }}'" style="background:var(--color-surface-soft);cursor:pointer;transition:all .3s cubic-bezier(.4,0,.2,1);">
                    <td style="padding:16px;border-radius:16px 0 0 16px;border:1px solid transparent;border-right:none;">
                        <div style="font-weight:800;color:var(--color-text-primary);font-size:15px;letter-spacing:-0.3px;">{{ $event->name }}</div>
                        <div style="font-size:12px;color:var(--color-text-secondary);margin-top:6px;font-weight:600;"><i class="fas fa-layer-group" style="margin-right:4px;opacity:0.7;"></i>{{ $event->isPointSystem() ? 'Sistem Poin' : 'Sistem Gugur' }}</div>
                    </td>
                    <td style="padding:16px;border-top:1px solid transparent;border-bottom:1px solid transparent;">
                        <div style="display:inline-flex;align-items:center;gap:6px;background:#fff;padding:6px 12px;border-radius:100px;font-size:12px;font-weight:600;color:var(--color-text-secondary);border:1px solid var(--color-border);">
                            <i class="far fa-calendar-alt"></i>
                            {{ \Carbon\Carbon::parse($event->start_date)->translatedFormat('d M Y') }}
                        </div>
                    </td>
                    <td style="padding:16px;text-align:center;border-top:1px solid transparent;border-bottom:1px solid transparent;">
                        <span style="font-size:16px;font-weight:800;color:var(--color-text-primary);">{{ number_format($event->participants_count) }}</span>
                    </td>
                    <td style="padding:16px;text-align:center;border-top:1px solid transparent;border-bottom:1px solid transparent;">
                        @if($event->trend > 0)
                            <div style="display:inline-flex;align-items:center;gap:4px;color:#16a34a;background:#dcfce7;padding:4px 10px;border-radius:100px;font-size:12px;font-weight:700;animation:slideUp 0.5s ease-out;">
                                <i class="fas fa-arrow-trend-up"></i> +{{ $event->trend_percentage }}%
                            </div>
                        @elseif($event->trend < 0)
                            <div style="display:inline-flex;align-items:center;gap:4px;color:#dc2626;background:#fee2e2;padding:4px 10px;border-radius:100px;font-size:12px;font-weight:700;animation:slideDown 0.5s ease-out;">
                                <i class="fas fa-arrow-trend-down"></i> {{ $event->trend_percentage }}%
                            </div>
                        @else
                            <div style="display:inline-flex;align-items:center;gap:4px;color:var(--color-text-secondary);background:var(--color-surface);border:1px solid var(--color-border);padding:4px 10px;border-radius:100px;font-size:12px;font-weight:700;">
                                <i class="fas fa-minus"></i> 0%
                            </div>
                        @endif
                    </td>
                    <td style="padding:16px;text-align:right;border-radius:0 16px 16px 0;border-top:1px solid transparent;border-bottom:1px solid transparent;border-right:1px solid transparent;">
                        <div class="action-btn" style="display:inline-flex;align-items:center;justify-content:center;width:36px;height:36px;background:#fff;border:1px solid var(--color-border);border-radius:10px;color:var(--color-text-secondary);transition:.3s;">
                            <i class="fas fa-chevron-right"></i>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;padding:40px;color:var(--color-text-tertiary);">Belum ada event yang tersedia.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
    .event-row { outline: 1px solid transparent; }
    .event-row:hover { 
        background: #fff !important; 
        box-shadow: 0 12px 24px rgba(0,0,0,0.06), 0 4px 8px rgba(0,0,0,0.02); 
        transform: scale(1.01);
        outline: 1px solid rgba(29,179,73,0.15);
    }
    .event-row:hover .action-btn {
        background: var(--color-primary) !important;
        border-color: var(--color-primary) !important;
        color: #fff !important;
        transform: translateX(4px);
    }
    
    @keyframes slideUp {
        from { transform: translateY(10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes slideDown {
        from { transform: translateY(-10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rawData = {!! json_encode($chartData) !!};
    const labels = rawData.map(d => d.name);
    const data = rawData.map(d => d.count);

    const ctx = document.getElementById('trendChart').getContext('2d');
    
    // Gradient for line area
    let gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, 'rgba(29, 179, 73, 0.4)');
    gradient.addColorStop(1, 'rgba(29, 179, 73, 0.0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Peserta',
                data: data,
                borderColor: '#1db349',
                backgroundColor: gradient,
                borderWidth: 3,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#1db349',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // curve
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleFont: { size: 13, family: "'Nunito', sans-serif" },
                    bodyFont: { size: 14, weight: 'bold', family: "'Nunito', sans-serif" },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    ticks: { font: { family: "'Nunito', sans-serif" }, color: '#64748b' }
                },
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: { font: { family: "'Nunito', sans-serif" }, color: '#64748b' }
                }
            },
            animation: {
                y: { duration: 2000, easing: 'easeOutQuart' }
            }
        }
    });
});
</script>
@endpush
