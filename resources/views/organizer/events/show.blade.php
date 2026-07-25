@extends('layouts.app')

@section('title', $event->name)
@section('page-title', $event->name)

@section('content')
{{-- Breadcrumb --}}
<nav style="font-size:13px;color:var(--color-text-tertiary);margin-bottom:20px;display:flex;align-items:center;gap:8px;">
    <a href="{{ route('organizer.events.index') }}" style="color:var(--color-primary);text-decoration:none;font-weight:600;">Events</a>
    <i class="fas fa-chevron-right" style="font-size:9px;opacity:.5;"></i>
    <span style="font-weight:600;color:var(--color-text-secondary);">{{ Str::limit($event->name, 40) }}</span>
</nav>

{{-- Hero Banner --}}
<div style="border-radius:24px;overflow:hidden;margin-bottom:28px;position:relative;min-height:220px;background:linear-gradient(135deg,var(--grad-start) 0%,var(--grad-end) 60%,#16a34a 100%);">
    @if($event->poster_image)
    <img src="{{ Storage::url($event->poster_image) }}" alt="Poster"
         style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.25;">
    @endif
    <div style="position:relative;z-index:2;padding:36px 40px;display:flex;justify-content:space-between;align-items:flex-end;min-height:220px;">
        <div>
            <div style="margin-bottom:12px;">
                @php $badges=['draft'=>'rgba(255,255,255,.2)','published'=>'rgba(99,179,255,.3)','ongoing'=>'rgba(52,211,153,.3)','completed'=>'rgba(255,255,255,.15)','cancelled'=>'rgba(248,113,113,.3)']; @endphp
                <span style="background:{{ $badges[$event->status] ?? 'rgba(255,255,255,.2)' }};color:#fff;border:1px solid rgba(255,255,255,.3);padding:4px 14px;border-radius:100px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;">
                    {{ $event->status }}
                </span>
            </div>
            <h1 style="font-size:32px;font-weight:900;color:#fff;letter-spacing:-1px;margin-bottom:8px;line-height:1.2;">{{ $event->name }}</h1>
            <p style="color:rgba(255,255,255,.7);font-size:14px;font-weight:500;">
                <i class="fas fa-calendar-alt" style="margin-right:6px;"></i>
                {{ $event->start_date->format('d M Y') }} — {{ $event->end_date->format('d M Y') }}
                @if($event->category)
                <span style="margin-left:16px;"><i class="fas fa-tag" style="margin-right:4px;"></i>{{ $event->category }}</span>
                @endif
            </p>
        </div>

        {{-- Poster Thumbnail --}}
        @if($event->poster_image)
        <div style="flex-shrink:0;display:none;" class="poster-thumb">
            <img src="{{ Storage::url($event->poster_image) }}" alt="Poster"
                 style="height:160px;width:auto;aspect-ratio:4/5;object-fit:cover;border-radius:16px;box-shadow:0 20px 40px rgba(0,0,0,.4);border:3px solid rgba(255,255,255,.2);">
        </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex gap-2" style="position:absolute;top:28px;right:32px;">
            <a href="{{ route('organizer.participants.index', $event) }}" class="btn btn-sm"
               style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.25);backdrop-filter:blur(8px);">
                <i class="fas fa-users"></i> Peserta
            </a>
            @if($event->isPointSystem())
            <a href="{{ route('organizer.events.leaderboard', $event) }}" class="btn btn-sm"
               style="background:var(--color-warning);color:#fff;font-weight:700;">
                <i class="fas fa-trophy"></i> Live Leaderboard
            </a>
            @endif
            <a href="{{ route('organizer.questions.index', $event) }}" class="btn btn-sm"
               style="background:rgba(255,255,255,.15);color:#fff;border:1px solid rgba(255,255,255,.25);backdrop-filter:blur(8px);">
                <i class="fas fa-list-ul"></i> Soal
            </a>
            @if($event->isQualificationSystem())
            <a href="{{ route('organizer.events.bracket', $event) }}" class="btn btn-sm"
               style="background:linear-gradient(135deg, #1d4ed8, #2563eb);color:#fff;font-weight:700;border:1px solid #3b82f6;box-shadow:0 4px 12px rgba(37,99,235,.3);">
                <i class="fas fa-sitemap"></i> Kelola Bagan Turnamen
            </a>
            @endif
            <a href="{{ route('organizer.events.edit', $event) }}" class="btn btn-sm"
               style="background:#fff;color:var(--grad-start);font-weight:700;">
                <i class="fas fa-pencil"></i> Edit
            </a>
            <a href="{{ route('organizer.events.index') }}" class="btn btn-sm"
               style="background:rgba(255,255,255,.1);color:#fff;border:1px solid rgba(255,255,255,.2);">
                <i class="fas fa-arrow-left"></i>
            </a>
        </div>
    </div>
</div>

{{-- Stats Row --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:28px;">
    <div class="card" style="padding:20px 24px;border-radius:16px;display:flex;align-items:center;gap:16px;">
        <div style="width:44px;height:44px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-radius:12px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-users" style="color:#166534;font-size:18px;"></i>
        </div>
        <div>
            <div style="font-size:26px;font-weight:900;color:var(--color-text-primary);line-height:1;">{{ $event->participants->count() }}</div>
            <div style="font-size:12px;color:var(--color-text-tertiary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-top:2px;">Peserta</div>
        </div>
    </div>
    <div class="card" style="padding:20px 24px;border-radius:16px;display:flex;align-items:center;gap:16px;">
        <div style="width:44px;height:44px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-radius:12px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-layer-group" style="color:#16a34a;font-size:18px;"></i>
        </div>
        <div>
            <div style="font-size:26px;font-weight:900;color:var(--color-text-primary);line-height:1;">{{ $event->rounds->count() }}</div>
            <div style="font-size:12px;color:var(--color-text-tertiary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-top:2px;">Babak</div>
        </div>
    </div>
    <div class="card" style="padding:20px 24px;border-radius:16px;display:flex;align-items:center;gap:16px;">
        <div style="width:44px;height:44px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-radius:12px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-book" style="color:#15803d;font-size:18px;"></i>
        </div>
        <div>
            <div style="font-size:26px;font-weight:900;color:var(--color-text-primary);line-height:1;">{{ $event->questionBanks->count() }}</div>
            <div style="font-size:12px;color:var(--color-text-tertiary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-top:2px;">Bank Soal</div>
        </div>
    </div>
    <div class="card" style="padding:20px 24px;border-radius:16px;display:flex;align-items:center;gap:16px;">
        <div style="width:44px;height:44px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-radius:12px;display:flex;align-items:center;justify-content:center;">
            <i class="fas fa-question-circle" style="color:#14532d;font-size:18px;"></i>
        </div>
        <div>
            <div style="font-size:26px;font-weight:900;color:var(--color-text-primary);line-height:1;">{{ $event->questionBanks->sum(fn($b) => $b->questions->count()) }}</div>
            <div style="font-size:12px;color:var(--color-text-tertiary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-top:2px;">Total Soal</div>
        </div>
    </div>
</div>

{{-- Description (if any) --}}
@if($event->description)
<div class="card" style="margin-bottom:28px;border-radius:16px;padding:24px 28px;background:linear-gradient(135deg, #fff, #f8fafc);box-shadow:0 8px 16px rgba(0,0,0,.02);border:1px solid rgba(0,0,0,.04);">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:12px;">
        <i class="fas fa-align-left" style="color:var(--color-primary);font-size:14px;"></i>
        <span style="font-size:12px;font-weight:800;color:var(--text-dark);letter-spacing:1px;text-transform:uppercase;">Deskripsi Event</span>
    </div>
    <p style="font-size:14px;color:var(--color-text-secondary);line-height:1.8;">{{ $event->description }}</p>
</div>
@endif

{{-- Rounds Table --}}
<div class="card" style="border-radius:20px;overflow:hidden;">
    <div class="card-header" style="padding:20px 28px;">
        <div style="display:flex;align-items:center;gap:12px;">
            <div style="width:36px;height:36px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-radius:10px;display:flex;align-items:center;justify-content:center;">
                <i class="fas fa-layer-group" style="color:#166534;font-size:15px;"></i>
            </div>
            <h3 class="card-title" style="font-size:17px;">Babak Ujian</h3>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('modal-round').style.display='flex'" style="font-size:14px;padding:10px 20px;">
            <i class="fas fa-plus"></i> Tambah Babak
        </button>
    </div>

    @if($event->rounds->isEmpty())
        <div style="padding:60px;text-align:center;color:var(--color-text-tertiary);">
            <div style="width:64px;height:64px;background:var(--color-surface-hover);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <i class="fas fa-layer-group" style="font-size:24px;opacity:.4;"></i>
            </div>
            <p style="font-weight:600;font-size:15px;margin-bottom:4px;">Belum ada Babak</p>
            <p style="font-size:13px;opacity:.6;">Klik "+ Tambah Babak" untuk membuat babak ujian pertama.</p>
        </div>
    @else
        <div class="table-wrapper" style="padding:0;">
            <table>
                <thead>
                    <tr>
                        <th style="padding-left:28px;">Babak</th>
                        <th>Jadwal</th>
                        <th>Durasi</th>
                        <th>Max Soal</th>
                        <th>Bank Soal</th>
                        <th>Status</th>
                        <th style="text-align:right;padding-right:28px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($event->rounds as $round)
                    @php
                        $bankCount = $round->questionBanks->first()?->questions->count() ?? 0;
                        $bankName = $round->questionBanks->first()?->name ?? '—';
                        $statusColors = ['ongoing' => '#16a34a', 'completed' => '#6b7280', 'upcoming' => '#2563eb'];
                        $statusBg = ['ongoing' => '#dcfce7', 'completed' => '#f3f4f6', 'upcoming' => '#dbeafe'];
                    @endphp
                    <tr style="transition:.2s;" onmouseover="this.style.background='var(--color-surface-hover)'" onmouseout="this.style.background=''">
                        <td style="padding-left:28px;font-weight:800;font-size:15px;">
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-radius:8px;font-size:12px;font-weight:900;color:#166534;margin-right:10px;">{{ $round->sequence }}</span>
                            {{ $round->name }}
                        </td>
                        <td>
                            <div style="font-size:13px;font-weight:600;">{{ $round->start_time->format('d M Y') }}</div>
                            <div style="font-size:11px;color:var(--color-text-tertiary);">{{ $round->start_time->format('H:i') }} — {{ $round->end_time->format('H:i') }}</div>
                        </td>
                        <td><span style="font-weight:700;">{{ $round->duration_minutes }}</span> menit</td>
                        <td><span style="font-weight:700;">{{ $round->max_questions }}</span> soal</td>
                        <td>
                            <div style="font-size:12px;font-weight:600;color:var(--color-primary);">{{ $bankName }}</div>
                            @if($bankCount > 0)<div style="font-size:11px;color:var(--color-text-tertiary);">{{ $bankCount }} soal</div>@endif
                        </td>
                        <td>
                            <span style="background:{{ $statusBg[$round->status] ?? '#f3f4f6' }};color:{{ $statusColors[$round->status] ?? '#374151' }};padding:5px 14px;border-radius:100px;font-size:12px;font-weight:700;display:inline-flex;align-items:center;gap:6px;">
                                @if($round->status == 'ongoing')
                                    <i class="fas fa-circle" style="font-size:8px;"></i> Berlangsung
                                @elseif($round->status == 'completed')
                                    <i class="fas fa-check-circle"></i> Selesai
                                @else
                                    <i class="fas fa-clock"></i> Belum Mulai
                                @endif
                            </span>
                        </td>
                        <td style="text-align:right;padding-right:28px;">
                            <div class="flex gap-2" style="justify-content:flex-end;">
                                <a href="{{ route('organizer.rounds.participants', $round) }}" class="btn btn-secondary btn-sm" title="Kelola Peserta Babak"><i class="fas fa-users"></i></a>
                                <a href="{{ route('organizer.reports.ranking', $round) }}" class="btn btn-secondary btn-sm" title="Ranking"><i class="fas fa-trophy"></i></a>
                                <a href="{{ route('organizer.grading.index', $round) }}" class="btn btn-secondary btn-sm" title="Nilai Esai"><i class="fas fa-pen-ruler"></i></a>
                                <a href="javascript:void(0)" onclick="document.getElementById('modal-round-edit-{{ $round->id }}').style.display='flex'" class="btn btn-secondary btn-sm" title="Edit Babak"><i class="fas fa-pencil"></i></a>
                                <form method="POST" action="{{ route('organizer.rounds.destroy', $round) }}" style="display:inline;" data-confirm="Hapus babak {{ $round->name }}?">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm" title="Hapus"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>

                            {{-- Modal Edit Babak --}}
                            <div id="modal-round-edit-{{ $round->id }}" style="display:none;position:fixed;inset:0;z-index:300;background:rgba(5,46,22,.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:24px;text-align:left;">
                                <div style="background:var(--color-surface);border-radius:24px;width:640px;max-width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 32px 64px rgba(0,0,0,.25);border:1px solid var(--color-border);">
                                    {{-- Modal Header --}}
                                    <div style="padding:24px 32px;border-bottom:1px solid var(--color-border);display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;background:var(--color-surface);z-index:10;border-radius:24px 24px 0 0;">
                                        <div style="display:flex;align-items:flex-start;gap:16px;">
                                            <div style="width:40px;height:40px;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:12px;display:flex;align-items:center;justify-content:center;">
                                                <i class="fas fa-pencil" style="color:#fff;font-size:16px;"></i>
                                            </div>
                                            <div>
                                                <h3 style="font-size:17px;font-weight:800;color:var(--color-text-primary);margin:0;">Edit Babak Ujian</h3>
                                                <p style="font-size:12px;color:var(--color-text-tertiary);margin:0;">{{ $round->name }}</p>
                                            </div>
                                        </div>
                                        <button onclick="document.getElementById('modal-round-edit-{{ $round->id }}').style.display='none'"
                                                style="width:32px;height:32px;border-radius:50%;background:var(--color-surface-hover);border:none;cursor:pointer;font-size:18px;color:var(--color-text-tertiary);display:flex;align-items:center;justify-content:center;transition:.2s;"
                                                onmouseover="this.style.background='var(--color-danger)';this.style.color='#fff'"
                                                onmouseout="this.style.background='var(--color-surface-hover)';this.style.color='var(--color-text-tertiary)'">&times;</button>
                                    </div>

                                    {{-- Form --}}
                                    <form method="POST" action="{{ route('organizer.rounds.update', $round) }}" style="padding:24px 32px;">
                                        @csrf @method('PUT')
                                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
                                            <div style="grid-column:1/-1;">
                                                <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Nama Babak <span style="color:var(--color-danger)">*</span></label>
                                                <input type="text" name="name" class="form-input" value="{{ $round->name }}" required style="font-size:15px;font-weight:600;">
                                            </div>

                                            @if($event->isQualificationSystem())
                                            <div>
                                                <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Tipe Babak</label>
                                                <select name="round_type" class="form-input">
                                                    <option value="">— Pilih Tipe —</option>
                                                    <option value="qualification" {{ $round->round_type == 'qualification' ? 'selected' : '' }}>Kualifikasi Massal</option>
                                                    <option value="group_stage" {{ $round->round_type == 'group_stage' ? 'selected' : '' }}>Penyisihan / Grup</option>
                                                    <option value="round_of_64" {{ $round->round_type == 'round_of_64' ? 'selected' : '' }}>64 Besar</option>
                                                    <option value="round_of_32" {{ $round->round_type == 'round_of_32' ? 'selected' : '' }}>32 Besar</option>
                                                    <option value="quarter_final" {{ $round->round_type == 'quarter_final' ? 'selected' : '' }}>Perempat Final (8 Besar)</option>
                                                    <option value="semi_final" {{ $round->round_type == 'semi_final' ? 'selected' : '' }}>Semifinal (4 Besar)</option>
                                                    <option value="final" {{ $round->round_type == 'final' ? 'selected' : '' }}>Grand Final</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Lolos ke Berikutnya (Top-N)</label>
                                                <input type="number" name="advancement_limit" class="form-input" value="{{ $round->advancement_limit }}" min="1">
                                            </div>
                                            @endif

                                            <div style="grid-column:1/-1;">
                                                <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Bank Soal</label>
                                                <select name="bank_id" class="form-input">
                                                    <option value="">— Pilih Bank Soal (opsional) —</option>
                                                    @foreach($event->questionBanks as $bank)
                                                        <option value="{{ $bank->id }}" {{ ($round->questionBanks->first()?->id == $bank->id) ? 'selected' : '' }}>
                                                            {{ $bank->name }} ({{ $bank->questions->count() }} soal)
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div>
                                                <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Waktu Mulai <span style="color:var(--color-danger)">*</span></label>
                                                <input type="datetime-local" name="start_time" class="form-input" value="{{ $round->start_time->format('Y-m-d\TH:i') }}" required>
                                            </div>
                                            <div>
                                                <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Waktu Selesai <span style="color:var(--color-danger)">*</span></label>
                                                <input type="datetime-local" name="end_time" class="form-input" value="{{ $round->end_time->format('Y-m-d\TH:i') }}" required>
                                            </div>

                                            <div>
                                                <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Durasi (menit) <span style="color:var(--color-danger)">*</span></label>
                                                <input type="number" name="duration_minutes" class="form-input" value="{{ $round->duration_minutes }}" min="5" max="480" required>
                                            </div>
                                            <div>
                                                <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Maks. Soal <span style="color:var(--color-danger)">*</span></label>
                                                <input type="number" name="max_questions" class="form-input" value="{{ $round->max_questions }}" min="1" max="200" required>
                                            </div>

                                            <div>
                                                <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Warning Pelanggaran</label>
                                                <input type="number" name="warning_threshold" class="form-input" value="{{ $round->warning_threshold }}" min="1">
                                            </div>
                                            <div>
                                                <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Auto-Submit (ke-)</label>
                                                <input type="number" name="auto_submit_threshold" class="form-input" value="{{ $round->auto_submit_threshold }}" min="2">
                                            </div>

                                            <div style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                                                <input type="checkbox" name="randomize_questions" id="rq-{{ $round->id }}" value="1" {{ $round->randomize_questions ? 'checked' : '' }} style="width:18px;height:18px;">
                                                <label for="rq-{{ $round->id }}" style="font-size:14px;font-weight:600;">Acak Urutan Soal</label>
                                            </div>
                                            <div style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                                                <input type="checkbox" name="randomize_options" id="ro-{{ $round->id }}" value="1" {{ $round->randomize_options ? 'checked' : '' }} style="width:18px;height:18px;">
                                                <label for="ro-{{ $round->id }}" style="font-size:14px;font-weight:600;">Acak Pilihan</label>
                                            </div>
                                        </div>
                                        <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:28px;padding-top:20px;border-top:1px solid var(--color-border);">
                                            <button type="button" onclick="document.getElementById('modal-round-edit-{{ $round->id }}').style.display='none'" class="btn btn-secondary" style="padding:12px 24px;">Batal</button>
                                            <button type="submit" class="btn btn-primary" style="padding:12px 28px;font-size:15px;font-weight:700;">Simpan Perubahan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Modal Tambah Babak --}}
<div id="modal-round" style="{{ $errors->any() ? 'display:flex' : 'display:none' }};position:fixed;inset:0;z-index:300;background:rgba(5,46,22,.7);backdrop-filter:blur(6px);align-items:center;justify-content:center;padding:24px;">
    <div style="background:var(--color-surface);border-radius:24px;width:640px;max-width:100%;max-height:90vh;overflow-y:auto;box-shadow:0 32px 64px rgba(0,0,0,.25);border:1px solid var(--color-border);">

        {{-- Modal Header --}}
        <div style="padding:24px 32px;border-bottom:1px solid var(--color-border);display:flex;justify-content:space-between;align-items:center;position:sticky;top:0;background:var(--color-surface);z-index:10;border-radius:24px 24px 0 0;">
            <div style="display:flex;align-items:flex-start;gap:16px;">
                <div style="width:40px;height:40px;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:12px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-layer-group" style="color:#fff;font-size:16px;"></i>
                </div>
                <div>
                    <h3 style="font-size:17px;font-weight:800;color:var(--color-text-primary);margin:0;">Tambah Babak Ujian</h3>
                    <p style="font-size:12px;color:var(--color-text-tertiary);margin:0;">{{ $event->name }}</p>
                </div>
            </div>
            <button onclick="document.getElementById('modal-round').style.display='none'"
                    style="width:32px;height:32px;border-radius:50%;background:var(--color-surface-hover);border:none;cursor:pointer;font-size:18px;color:var(--color-text-tertiary);display:flex;align-items:center;justify-content:center;transition:.2s;"
                    onmouseover="this.style.background='var(--color-danger)';this.style.color='#fff'"
                    onmouseout="this.style.background='var(--color-surface-hover)';this.style.color='var(--color-text-tertiary)'">&times;</button>
        </div>

        {{-- Errors --}}
        @if($errors->any())
        <div style="padding:16px 32px 0;">
            <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:12px;padding:14px 16px;display:flex;gap:12px;align-items:flex-start;">
                <i class="fas fa-exclamation-circle" style="color:#dc2626;margin-top:2px;flex-shrink:0;"></i>
                <ul style="margin:0;padding-left:16px;color:#b91c1c;font-size:13px;line-height:1.8;">
                    @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
                </ul>
            </div>
        </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="{{ route('organizer.rounds.store', $event) }}" style="padding:24px 32px;">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                <div style="grid-column:1/-1;">
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Nama Babak <span style="color:var(--color-danger)">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}"
                           placeholder="contoh: Penyisihan, Semifinal, Final" required
                           style="font-size:15px;font-weight:600;">
                </div>

                @if($event->isQualificationSystem())
                <div>
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Tipe Babak</label>
                    <select name="round_type" class="form-input">
                        <option value="">— Pilih Tipe —</option>
                        <option value="qualification">Kualifikasi Massal</option>
                        <option value="group_stage">Penyisihan / Grup</option>
                        <option value="round_of_64">64 Besar</option>
                        <option value="round_of_32">32 Besar</option>
                        <option value="quarter_final">Perempat Final (8 Besar)</option>
                        <option value="semi_final">Semifinal (4 Besar)</option>
                        <option value="final">Grand Final</option>
                    </select>
                </div>
                <div>
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Lolos ke Berikutnya (Top-N)</label>
                    <input type="number" name="advancement_limit" class="form-input" value="{{ old('advancement_limit') }}" min="1" placeholder="Kosongkan jika final">
                </div>
                @endif

                <div style="grid-column:1/-1;">
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Bank Soal</label>
                    <select name="bank_id" class="form-input">
                        <option value="">— Pilih Bank Soal (opsional) —</option>
                        @foreach($event->questionBanks as $bank)
                            <option value="{{ $bank->id }}" {{ old('bank_id') == $bank->id ? 'selected' : '' }}>
                                {{ $bank->name }} ({{ $bank->questions->count() }} soal)
                            </option>
                        @endforeach
                    </select>
                    @if($event->questionBanks->isEmpty())
                    <p style="margin-top:6px;font-size:12px;color:#d97706;">
                        <i class="fas fa-info-circle"></i> Belum ada Bank Soal.
                        <a href="{{ route('organizer.questions.index', $event) }}" style="color:var(--color-primary);font-weight:600;">Buat sekarang →</a>
                    </p>
                    @endif
                </div>

                <div>
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Waktu Mulai <span style="color:var(--color-danger)">*</span></label>
                    <input type="datetime-local" name="start_time" class="form-input" value="{{ old('start_time') }}" required>
                </div>
                <div>
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Waktu Selesai <span style="color:var(--color-danger)">*</span></label>
                    <input type="datetime-local" name="end_time" class="form-input" value="{{ old('end_time') }}" required>
                    @error('end_time')<p style="font-size:12px;color:var(--color-danger);margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Durasi (menit) <span style="color:var(--color-danger)">*</span></label>
                    <input type="number" name="duration_minutes" class="form-input" value="{{ old('duration_minutes', 60) }}" min="5" max="480" required>
                </div>
                <div>
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Maks. Soal <span style="color:var(--color-danger)">*</span></label>
                    <input type="number" name="max_questions" class="form-input" value="{{ old('max_questions', 30) }}" min="1" max="200" required>
                </div>

                <div>
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Warning Pelanggaran (ke-)</label>
                    <input type="number" name="warning_threshold" class="form-input" value="{{ old('warning_threshold', 3) }}" min="1">
                </div>
                <div>
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:6px;">Auto-Submit (ke-)</label>
                    <input type="number" name="auto_submit_threshold" class="form-input" value="{{ old('auto_submit_threshold', 5) }}" min="2">
                </div>

                <div style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="randomize_questions" id="rq" value="1" {{ old('randomize_questions','1') ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--color-primary);">
                    <label for="rq" style="font-size:14px;font-weight:600;cursor:pointer;">Acak Urutan Soal</label>
                </div>
                <div style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                    <input type="checkbox" name="randomize_options" id="ro" value="1" {{ old('randomize_options','1') ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--color-primary);">
                    <label for="ro" style="font-size:14px;font-weight:600;cursor:pointer;">Acak Pilihan Jawaban</label>
                </div>
            </div>

            <div style="display:flex;gap:12px;justify-content:flex-end;margin-top:28px;padding-top:20px;border-top:1px solid var(--color-border);">
                <button type="button" onclick="document.getElementById('modal-round').style.display='none'" class="btn btn-secondary" style="padding:12px 24px;">Batal</button>
                <button type="submit" class="btn btn-primary" style="padding:12px 28px;font-size:15px;font-weight:700;">
                    <i class="fas fa-save"></i> Simpan Babak
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('modal-round').addEventListener('click', function(e) {
    if (e.target === this) this.style.display = 'none';
});
</script>
@endsection
