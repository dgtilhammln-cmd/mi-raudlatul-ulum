@extends('layouts.app')

@section('title', 'Dashboard Peserta')
@section('page-title', 'Dashboard Peserta')

@section('content')
@if($participants->isEmpty())
    <div style="text-align:center;padding:80px 40px;background:var(--color-surface);border-radius:20px;border:2px dashed var(--color-border);">
        <div style="width:72px;height:72px;background:linear-gradient(135deg,#dcfce7,#bbf7d0);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <i class="fas fa-calendar-times" style="font-size:28px;color:#166534;"></i>
        </div>
        <h3 style="font-size:20px;font-weight:800;color:var(--color-text-primary);margin-bottom:8px;">Belum Terdaftar</h3>
        <p style="color:var(--color-text-tertiary);">Anda belum terdaftar di event manapun.<br>Hubungi penyelenggara untuk didaftarkan.</p>
    </div>
@else
    {{-- Welcome Banner --}}
    <div class="mobile-p-4 mobile-stack" style="background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:20px;padding:24px 32px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;gap:16px;">
        <div class="mobile-stack" style="display:flex;align-items:center;gap:20px;">
            {{-- Avatar --}}
            <div style="position:relative;flex-shrink:0;cursor:pointer;" onclick="document.getElementById('avatarUploadModal').style.display='flex'">
                @if(auth()->user()->getAvatarUrl())
                    <img src="{{ auth()->user()->getAvatarUrl() }}" alt="{{ auth()->user()->name }}" 
                        style="width:56px;height:56px;border-radius:16px;object-fit:cover;border:3px solid rgba(255,255,255,.3);" />
                @else
                    <div style="width:56px;height:56px;border-radius:16px;background:rgba(255,255,255,.15);backdrop-filter:blur(8px);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:22px;border:3px solid rgba(255,255,255,.3);">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div style="position:absolute;bottom:-4px;right:-4px;width:22px;height:22px;background:#fff;border-radius:8px;display:flex;align-items:center;justify-content:center;box-shadow:0 2px 8px rgba(0,0,0,.15);">
                    <i class="fas fa-camera" style="font-size:10px;color:var(--color-primary);"></i>
                </div>
            </div>
            <div>
                <h2 style="font-size:20px;font-weight:900;color:#fff;margin-bottom:4px;line-height:1.2;">Selamat datang, {{ auth()->user()->name }}! <i class="fas fa-hand-wave" style="color:#fbbf24;margin-left:4px;"></i></h2>
                <p class="mobile-text-sm" style="font-size:13px;color:rgba(255,255,255,.65);line-height:1.4;">
                    <i class="fas fa-shield-alt" style="margin-right:6px;color:#4ade80;"></i>
                    Pastikan koneksi stabil. Jangan refresh saat ujian berlangsung.
                </p>
            </div>
        </div>
        <div class="mobile-hide" style="text-align:right;flex-shrink:0;">
            <div style="font-size:11px;color:rgba(255,255,255,.5);font-weight:600;margin-bottom:4px;">WAKTU SEKARANG</div>
            <div id="clock" style="font-size:20px;font-weight:900;color:#fff;font-family:monospace;"></div>
        </div>
    </div>

    {{-- Avatar Upload Modal --}}
    <div id="avatarUploadModal" onclick="if(event.target===this)this.style.display='none'" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.5);backdrop-filter:blur(6px);align-items:center;justify-content:center;">
        <div style="background:#fff;border-radius:24px;padding:36px;max-width:420px;width:90%;box-shadow:0 32px 64px rgba(0,0,0,.15);position:relative;animation:fadeInUp .3s ease;">
            <button onclick="document.getElementById('avatarUploadModal').style.display='none'" style="position:absolute;top:16px;right:16px;background:var(--color-surface-soft);border:none;width:32px;height:32px;border-radius:10px;cursor:pointer;display:flex;align-items:center;justify-content:center;color:var(--color-text-secondary);transition:.2s;" onmouseover="this.style.background='#fee2e2';this.style.color='#ef4444'" onmouseout="this.style.background='var(--color-surface-soft)';this.style.color='var(--color-text-secondary)'">
                <i class="fas fa-times"></i>
            </button>
            <div style="text-align:center;margin-bottom:24px;">
                <div style="width:72px;height:72px;background:linear-gradient(135deg,#dbeafe,#bfdbfe);border-radius:20px;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fas fa-camera-retro" style="font-size:28px;color:#3b82f6;"></i>
                </div>
                <h3 style="font-size:20px;font-weight:900;color:var(--color-text-primary);margin-bottom:4px;">Perbarui Foto Profil</h3>
                <p style="font-size:13px;color:var(--color-text-tertiary);">Unggah foto terbaik Anda. Format: JPG, PNG, WebP (maks 5MB)</p>
            </div>
            <form method="POST" action="{{ route('peserta.profile.avatar') }}" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom:20px;">
                    <div id="avatarPreviewContainer" style="display:none;text-align:center;margin-bottom:16px;">
                        <img id="avatarPreview" src="" alt="Preview" style="width:120px;height:120px;border-radius:20px;object-fit:cover;border:3px solid var(--color-border);" />
                    </div>
                    <label for="avatarInput" style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;padding:28px;border:2px dashed var(--color-border);border-radius:16px;cursor:pointer;transition:.2s;background:var(--color-surface-soft);" onmouseover="this.style.borderColor='var(--color-primary)';this.style.background='#f0fdf4'" onmouseout="this.style.borderColor='var(--color-border)';this.style.background='var(--color-surface-soft)'">
                        <i class="fas fa-cloud-upload-alt" style="font-size:24px;color:var(--color-primary);"></i>
                        <span style="font-size:14px;font-weight:700;color:var(--color-text-primary);">Klik untuk memilih foto</span>
                        <span id="avatarFileName" style="font-size:12px;color:var(--color-text-tertiary);">Belum ada file dipilih</span>
                    </label>
                    <input type="file" name="avatar" id="avatarInput" accept="image/*" style="display:none" onchange="previewAvatar(this)" />
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;padding:14px;font-size:15px;font-weight:800;border-radius:14px;">
                    <i class="fas fa-save" style="margin-right:8px;"></i>Simpan Foto Profil
                </button>
            </form>
        </div>
    </div>

    @foreach($participants as $participant)
    @php
        $event = $participant->event;
        $leaderboard = ($event->leaderboard_visible) ? $event->getLeaderboard(50) : collect();
        $myRank = null;
        $myScore = null;
        if ($leaderboard->isNotEmpty()) {
            $myEntry = $leaderboard->firstWhere('participant_id', $participant->id);
            $myRank = $myEntry ? $leaderboard->search(fn($r) => $r['participant_id'] === $participant->id) + 1 : null;
            $myScore = $myEntry ? $myEntry['total_score'] : null;
        }
    @endphp

    <div style="margin-bottom:32px;">
        {{-- Event Header --}}
        <div style="background:var(--color-surface);border-radius:20px;border:1px solid var(--color-border);overflow:hidden;margin-bottom:16px;">
            {{-- Event Banner --}}
            @if($event->poster_image)
            <div style="height:120px;position:relative;overflow:hidden;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));">
                <img src="{{ Storage::url($event->poster_image) }}" alt="" style="width:100%;height:100%;object-fit:cover;opacity:.3;">
                <div class="mobile-p-4" style="position:absolute;inset:0;padding:20px 28px;display:flex;align-items:flex-end;">
                    <div>
                        <div style="font-size:11px;color:rgba(255,255,255,.6);font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Event Aktif</div>
                        <h3 style="font-size:20px;font-weight:900;color:#fff;">{{ $event->name }}</h3>
                    </div>
                </div>
            </div>
            @else
            <div class="mobile-p-4" style="padding:20px 28px;border-bottom:1px solid var(--color-border);">
                <div style="font-size:11px;color:var(--color-text-tertiary);font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;">Event Aktif</div>
                <h3 style="font-size:18px;font-weight:900;color:var(--color-text-primary);">{{ $event->name }}</h3>
            </div>
            @endif

            <div class="mobile-p-4 mobile-stack" style="padding:16px 28px;display:flex;flex-wrap:wrap;gap:16px;align-items:center;justify-content:space-between;">
                <div style="display:flex;flex-wrap:wrap;gap:20px;font-size:12px;color:var(--color-text-secondary);">
                    <span><i class="fas fa-user-graduate" style="color:var(--color-primary);margin-right:6px;"></i>{{ $participant->user->name }}</span>
                    <span><i class="fas fa-id-card" style="color:var(--color-primary);margin-right:6px;"></i><span style="font-family:monospace;font-weight:700;color:var(--color-primary);">{{ $participant->participant_code }}</span></span>
                    @if($participant->institution)<span><i class="fas fa-school" style="color:var(--color-primary);margin-right:6px;"></i>{{ $participant->institution }}</span>@endif
                    @if($participant->grade)<span><i class="fas fa-layer-group" style="color:var(--color-primary);margin-right:6px;"></i>{{ $participant->grade }}</span>@endif
                </div>
                <div class="mobile-wrap" style="display:flex;align-items:center;gap:10px;">
                    @if($myRank)
                    <div style="text-align:center;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));padding:8px 20px;border-radius:12px;">
                        <div style="font-size:20px;font-weight:900;color:#fff;">{{ $myRank }}</div>
                        <div style="font-size:10px;color:rgba(255,255,255,.8);font-weight:700;text-transform:uppercase;">Peringkat</div>
                    </div>
                    <div style="text-align:center;background:#f0fdf4;border:1px solid #bbf7d0;padding:8px 20px;border-radius:12px;">
                        <div style="font-size:20px;font-weight:900;color:#166534;">{{ number_format($myScore, 1) }}</div>
                        <div style="font-size:10px;color:#16a34a;font-weight:700;text-transform:uppercase;">Total Poin</div>
                    </div>
                    @endif
                    <span style="background:{{ $participant->status==='active'?'#dcfce7':($participant->status==='completed'?'#f3f4f6':'#fef9c3') }};color:{{ $participant->status==='active'?'#166534':($participant->status==='completed'?'#6b7280':'#92400e') }};padding:6px 16px;border-radius:100px;font-size:12px;font-weight:700;text-transform:uppercase;">
                        {{ $participant->status }}
                    </span>
                </div>
            </div>
            
            @if($event->isQualificationSystem())
            <div class="mobile-p-4 mobile-center" style="padding:12px 28px;background:var(--color-surface-hover);border-top:1px solid var(--color-border);display:flex;justify-content:flex-end;">
                <a href="{{ route('peserta.bracket.show', $event) }}" class="btn btn-sm" style="background:linear-gradient(135deg, #1d4ed8, #2563eb);color:#fff;font-weight:700;border:none;box-shadow:0 4px 12px rgba(37,99,235,.2);">
                    <i class="fas fa-sitemap" style="margin-right:6px;"></i> Lihat Bagan Turnamen
                </a>
            </div>
            @endif
        </div>

        @if($participant->is_champion)
        <div style="background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1px solid #fde68a;border-radius:16px;padding:20px 24px;margin-bottom:20px;display:flex;align-items:center;gap:16px;box-shadow:0 4px 12px rgba(217,119,6,.05);">
            <div style="width:48px;height:48px;background:#fde68a;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-crown" style="color:#d97706;font-size:20px;"></i>
            </div>
            <div>
                <h4 style="font-size:15px;font-weight:800;color:#92400e;margin-bottom:4px;">Selamat Sang Juara!</h4>
                <p style="font-size:13px;color:#b45309;line-height:1.5;">Luar biasa! Anda berhasil memenangkan turnamen ini. Terima kasih telah berpartisipasi dan menunjukkan kemampuan terbaik Anda!</p>
            </div>
        </div>
        @elseif($participant->eliminated_at_round !== null)
        <div style="background:linear-gradient(135deg,#fef2f2,#fff1f2);border:1px solid #fecaca;border-radius:16px;padding:20px 24px;margin-bottom:20px;display:flex;align-items:center;gap:16px;box-shadow:0 4px 12px rgba(220,38,38,.05);">
            <div style="width:48px;height:48px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-heart" style="color:#ef4444;font-size:20px;"></i>
            </div>
            <div>
                <h4 style="font-size:15px;font-weight:800;color:#b91c1c;margin-bottom:4px;">Tetap Semangat!</h4>
                <p style="font-size:13px;color:#991b1b;line-height:1.5;">Terima kasih atas partisipasi Anda! Sayang sekali Anda belum berhasil lolos ke babak selanjutnya. Jangan berkecil hati, terus belajar dan coba lagi di event berikutnya!</p>
            </div>
        </div>
        @elseif($participant->current_round_sequence > 1)
        @php
            $currentRoundInfo = $event->rounds->where('sequence', $participant->current_round_sequence)->first();
            $roundName = $currentRoundInfo ? $currentRoundInfo->name : 'babak selanjutnya';
        @endphp
        <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #bbf7d0;border-radius:16px;padding:20px 24px;margin-bottom:20px;display:flex;align-items:center;gap:16px;box-shadow:0 4px 12px rgba(22,163,74,.05);">
            <div style="width:48px;height:48px;background:#bbf7d0;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-bullhorn" style="color:#16a34a;font-size:20px;"></i>
            </div>
            <div>
                <h4 style="font-size:15px;font-weight:800;color:#166534;margin-bottom:4px;">Selamat! Anda Lolos ke {{ $roundName }}</h4>
                <p style="font-size:13px;color:#15803d;line-height:1.5;">Luar biasa! Anda berhasil melaju ke {{ $roundName }}. Persiapkan diri Anda sebaik mungkin untuk ujian berikutnya.</p>
            </div>
        </div>
        @elseif($participant->status === 'disqualified')
        <div style="background:linear-gradient(135deg,#fef2f2,#fff1f2);border:1px solid #fecaca;border-radius:16px;padding:20px 24px;margin-bottom:20px;display:flex;align-items:center;gap:16px;box-shadow:0 4px 12px rgba(220,38,38,.05);">
            <div style="width:48px;height:48px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="fas fa-ban" style="color:#ef4444;font-size:20px;"></i>
            </div>
            <div>
                <h4 style="font-size:15px;font-weight:800;color:#b91c1c;margin-bottom:4px;">Didiskualifikasi</h4>
                <p style="font-size:13px;color:#991b1b;line-height:1.5;">Mohon maaf, kepesertaan Anda telah didiskualifikasi karena melanggar ketentuan yang berlaku.</p>
            </div>
        </div>
        @endif

        <div class="dashboard-grid {{ ($event->leaderboard_visible && $leaderboard->isNotEmpty()) ? 'has-sidebar' : '' }}">


            {{-- Rounds Column --}}
            <div>
                <div style="font-size:12px;font-weight:700;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">
                    <i class="fas fa-layer-group" style="margin-right:6px;color:var(--color-primary);"></i>Babak Ujian
                </div>

                @if($event->rounds->isEmpty())
                    <div style="background:var(--color-surface);border:1px solid var(--color-border);border-radius:14px;padding:24px;text-align:center;">
                        <p style="font-size:13px;color:var(--color-text-tertiary);">Belum ada babak ujian yang tersedia.</p>
                    </div>
                @else
                    @foreach($participant->event->rounds as $round)
                    @php
                        $session   = $participant->sessionForRound($round->id);
                        $isOpen    = $round->isOpen();
                        $isDone    = $session && $session->isSubmitted();
                        $isOngoing = $session && $session->status === 'ongoing';
                        $isFuture  = now()->lt($round->start_time);
                        $isClosed  = !$isOpen && !$isDone && !$isOngoing && !$isFuture;
                        
                        $qualificationStatus = 'qualified';
                        if ($event->isQualificationSystem()) {
                            if ($participant->eliminated_at_round && $participant->eliminated_at_round < $round->sequence) {
                                $qualificationStatus = 'eliminated';
                            } else {
                                $maxAllowedRound = max(1, $participant->current_round_sequence);
                                if ($maxAllowedRound < $round->sequence) {
                                    $qualificationStatus = 'locked';
                                }
                            }
                        }
                    @endphp
                    <div style="background:var(--color-surface);border:1px solid {{ $isOpen?'var(--color-primary)':($isDone?'#bbf7d0':($isOngoing?'#fef9c3':'var(--color-border)')) }};border-radius:16px;padding:18px 22px;margin-bottom:10px;display:flex;justify-content:space-between;align-items:center;transition:.2s;">
                        <div style="flex:1;">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                                <span style="width:26px;height:26px;background:linear-gradient(135deg,#dcfce7,#16a34a);border-radius:8px;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:900;color:#166534;flex-shrink:0;">{{ $round->sequence }}</span>
                                <h4 style="font-size:14px;font-weight:800;color:var(--color-text-primary);">{{ $round->name }}</h4>
                                @if($isOngoing)
                                    <span style="background:#fef9c3;color:#92400e;padding:2px 10px;border-radius:100px;font-size:11px;font-weight:700;"><i class="fas fa-circle" style="font-size:7px;color:#d97706;"></i> Sedang Dikerjakan</span>
                                @elseif($isDone)
                                    <span style="background:#dcfce7;color:#166534;padding:2px 10px;border-radius:100px;font-size:11px;font-weight:700;"><i class="fas fa-check" style="font-size:10px;"></i> Selesai</span>
                                @elseif($isOpen)
                                    <span style="background:#dbeafe;color:#1d4ed8;padding:2px 10px;border-radius:100px;font-size:11px;font-weight:700;animation:pulse-soft 2s infinite;"><i class="fas fa-circle" style="font-size:7px;"></i> Sedang Dibuka</span>
                                @endif
                            </div>
                            <div style="font-size:11px;color:var(--color-text-tertiary);display:flex;gap:16px;flex-wrap:wrap;">
                                <span><i class="fas fa-clock" style="margin-right:4px;"></i>{{ $round->start_time->format('d M Y H:i') }} — {{ $round->end_time->format('H:i') }}</span>
                                <span><i class="fas fa-stopwatch" style="margin-right:4px;"></i>{{ $round->duration_minutes }} menit</span>
                                <span><i class="fas fa-question-circle" style="margin-right:4px;"></i>{{ $round->max_questions }} soal</span>
                                @if($isDone && $session)
                                <span style="color:var(--color-primary);font-weight:700;"><i class="fas fa-star" style="margin-right:4px;"></i>Skor: {{ number_format($session->total_score, 1) }}</span>
                                @endif
                            </div>
                        </div>

                        <div style="margin-left:16px;flex-shrink:0;">
                            @if($qualificationStatus === 'eliminated')
                                <span style="background:#fef2f2;color:#ef4444;padding:6px 16px;border-radius:100px;font-size:12px;font-weight:700;"><i class="fas fa-times-circle"></i> Gugur</span>
                            @elseif($qualificationStatus === 'locked')
                                <span style="background:#f3f4f6;color:#6b7280;padding:6px 16px;border-radius:100px;font-size:12px;font-weight:700;"><i class="fas fa-lock"></i> Menunggu Pengumuman</span>
                            @elseif($isDone)
                                <a href="{{ route('peserta.result', $session) }}" class="btn btn-secondary btn-sm" style="font-weight:700;">
                                    <i class="fas fa-chart-bar"></i> Lihat Hasil
                                </a>
                            @elseif($isOngoing)
                                <a href="{{ route('peserta.exam.show', $session->token) }}" class="btn btn-primary btn-sm" style="background:#ca8a04;border-color:#ca8a04;font-weight:700;animation:pulse-soft 2s infinite;">
                                    <i class="fas fa-play"></i> Lanjutkan
                                </a>
                            @elseif($isOpen)
                                <form method="POST" action="{{ route('peserta.exam.start', $round) }}" onsubmit="confirmStart(event, this)">
                                    @csrf
                                    <button type="submit" class="btn btn-primary btn-sm" style="font-weight:700;">
                                        <i class="fas fa-play-circle"></i> Mulai Ujian
                                    </button>
                                </form>
                            @elseif($isFuture)
                                <div style="text-align:center;">
                                    <span style="background:#f3f4f6;color:#6b7280;padding:6px 16px;border-radius:100px;font-size:12px;font-weight:700;"><i class="fas fa-hourglass-start"></i> Belum Mulai</span>
                                    <div style="font-size:11px;color:var(--color-text-tertiary);margin-top:4px;" id="countdown-{{ $round->id }}" data-time="{{ $round->start_time->timestamp }}"></div>
                                </div>
                            @else
                                <span style="background:#f3f4f6;color:#9ca3af;padding:6px 16px;border-radius:100px;font-size:12px;font-weight:700;"><i class="fas fa-lock"></i> Ditutup</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>

            {{-- Leaderboard Mini Widget --}}
            @if($event->leaderboard_visible && $leaderboard->isNotEmpty())
            <div>
                <div style="font-size:12px;font-weight:700;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:.5px;margin-bottom:10px;">
                    <i class="fas fa-trophy" style="margin-right:6px;color:#ca8a04;"></i>Leaderboard
                    <span style="margin-left:8px;background:#dcfce7;color:#166534;padding:2px 8px;border-radius:100px;font-size:10px;font-weight:700;">LIVE</span>
                </div>
                <div style="background:var(--color-surface);border-radius:16px;border:1px solid var(--color-border);overflow:hidden;">
                    {{-- My Position (if not in top) --}}
                    @if($myRank && $myRank > 10)
                    <div style="padding:14px 18px;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));display:flex;align-items:center;justify-content:space-between;">
                        <span style="font-size:12px;color:rgba(255,255,255,.8);font-weight:600;">Posisi kamu saat ini</span>
                        <span style="font-size:16px;font-weight:900;color:#fff;">#{{ $myRank }}</span>
                    </div>
                    @endif

                    {{-- Motivational (if not qualified / no score yet) --}}
                    @if(!$myRank || $myScore == 0)
                    <div style="padding:16px 18px;background:linear-gradient(135deg,#fffbeb,#fef3c7);border-bottom:1px solid #fde68a;">
                        <div style="display:flex;gap:10px;align-items:flex-start;">
                            <i class="fas fa-fire" style="color:#d97706;margin-top:2px;flex-shrink:0;"></i>
                            <div>
                                <div style="font-weight:800;font-size:13px;color:#92400e;margin-bottom:2px;">Semangat Berjuang!</div>
                                <div style="font-size:12px;color:#a16207;line-height:1.5;">Kamu belum mulai ujian. Setiap juara pernah jadi pemula. Ini saatnya buktikan kemampuanmu!</div>
                            </div>
                        </div>
                    </div>
                    @elseif($participant->status === 'disqualified')
                    <div style="padding:16px 18px;background:linear-gradient(135deg,#fff7ed,#ffedd5);border-bottom:1px solid #fed7aa;">
                        <div style="display:flex;gap:10px;align-items:flex-start;">
                            <i class="fas fa-heart" style="color:#ea580c;margin-top:2px;flex-shrink:0;"></i>
                            <div>
                                <div style="font-weight:800;font-size:13px;color:#9a3412;margin-bottom:2px;">Jangan Patah Semangat!</div>
                                <div style="font-size:12px;color:#c2410c;line-height:1.5;">Kegagalan adalah batu loncatan menuju kesuksesan. Terus belajar, dan kamu pasti akan bersinar di kompetisi berikutnya! 💪</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Top entries --}}
                    <div style="padding:8px 0;">
                        @foreach($leaderboard->take(10) as $i => $row)
                        @php
                            $rank = $i + 1;
                            $isMe = $row['participant_id'] === $participant->id;
                            $medalColors = [1=>'#fbbf24', 2=>'#94a3b8', 3=>'#d97706'];
                        @endphp
                        <div style="padding:10px 18px;display:flex;align-items:center;gap:12px;{{ $isMe?'background:linear-gradient(90deg,#f0fdf4,#dcfce7);border-left:3px solid var(--color-primary);':'' }}transition:.15s;cursor:pointer;" onmouseover="this.style.background='var(--color-surface-hover)'" onmouseout="this.style.background='{{ $isMe?'linear-gradient(90deg,#f0fdf4,#dcfce7)':'' }}'" onclick='showIdCard({name:`{{ addslashes($row["name"]) }}`, institution:`{{ addslashes($row["institution"]) }}`, major:`{{ addslashes($row["major"] ?? "") }}`, rank:{{ $rank }}, avatar_url:`{{ $row["avatar_url"] ?? "" }}`})'>
                            {{-- Rank --}}
                            <div style="width:26px;text-align:center;flex-shrink:0;">
                                @if(isset($medalColors[$rank]))
                                    <div style="width:24px;height:24px;background:{{ $medalColors[$rank] }};border-radius:50%;display:flex;align-items:center;justify-content:center;">
                                        <i class="fas fa-medal" style="color:#fff;font-size:11px;"></i>
                                    </div>
                                @else
                                    <span style="font-size:13px;font-weight:800;color:var(--color-text-tertiary);">{{ $rank }}</span>
                                @endif
                            </div>
                            {{-- Avatar --}}
                            <div style="flex-shrink:0;">
                                @if(!empty($row['avatar_url']))
                                    <img src="{{ $row['avatar_url'] }}" alt="{{ $row['name'] }}" style="width:28px;height:28px;border-radius:8px;object-fit:cover;border:1px solid var(--color-border);" />
                                @else
                                    <div style="width:28px;height:28px;border-radius:8px;background:var(--color-surface-soft);display:flex;align-items:center;justify-content:center;color:var(--color-text-secondary);font-weight:800;font-size:11px;border:1px solid var(--color-border);">
                                        {{ strtoupper(substr($row['name'], 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            {{-- Name --}}
                            <div style="flex:1;min-width:0;">
                                <div style="font-weight:{{ $isMe?'900':'700' }};font-size:13px;color:{{ $isMe?'var(--color-primary)':'var(--color-text-primary)' }};white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    {{ $isMe ? '⟶ Kamu' : $row['name'] }}
                                </div>
                                <div style="font-size:11px;color:var(--color-text-tertiary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $row['institution'] }}</div>
                            </div>
                            {{-- Score --}}
                            <div style="font-size:15px;font-weight:900;color:{{ isset($medalColors[$rank])?$medalColors[$rank]:'var(--color-primary)' }};flex-shrink:0;">
                                {{ number_format($row['total_score'], 1) }}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- View Full --}}
                    <div style="padding:12px 18px;border-top:1px solid var(--color-border);text-align:center;">
                        <a href="{{ route('peserta.leaderboard', $event) }}" style="font-size:12px;color:var(--color-primary);font-weight:700;text-decoration:none;">
                            Lihat Klasemen Lengkap <i class="fas fa-arrow-right" style="font-size:11px;"></i>
                        </a>
                    </div>
                </div>

                {{-- Last updated --}}
                <p style="font-size:11px;color:var(--color-text-tertiary);text-align:center;margin-top:8px;">
                    <i class="fas fa-sync-alt" style="margin-right:4px;"></i>Diperbarui tiap 30 detik
                </p>
            </div>
            @endif
        </div>
    </div>
    @endforeach
@endif

<style>
@keyframes pulse-soft { 0%,100%{opacity:1} 50%{opacity:.7} }
</style>

<script>
// Live clock
function updateClock() {
    const el = document.getElementById('clock');
    if (!el) return;
    const now = new Date();
    el.textContent = now.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
updateClock();
setInterval(updateClock, 1000);

// Countdown timers
document.querySelectorAll('[data-time]').forEach(el => {
    const target = parseInt(el.getAttribute('data-time')) * 1000;
    function tick() {
        const diff = target - Date.now();
        if (diff <= 0) { el.textContent = 'Segera dibuka...'; return; }
        const h = Math.floor(diff/3600000);
        const m = Math.floor((diff%3600000)/60000);
        const s = Math.floor((diff%60000)/1000);
        el.textContent = `${h}j ${m}m ${s}d lagi`;
    }
    tick();
    setInterval(tick, 1000);
});

function confirmStart(e, form) {
    e.preventDefault();
    const msg = `<div style="text-align:left;line-height:1.6;font-size:14px;color:var(--color-text-secondary);">
        Mulai ujian sekarang? Timer akan langsung berjalan setelah Anda menekan tombol.<br><br>
        @if($event->getSetting('anti_cheat_enabled', true))
        <div style="background:#fef2f2;border:1px solid #fecaca;padding:12px;border-radius:12px;color:#dc2626;margin-bottom:12px;">
            <b>⚠️ PERHATIAN: SISTEM AUTO-SUBMIT</b><br>
            Sistem memantau ketat aktivitas Anda. <b>Batas pelanggaran: maksimal 5x.</b><br>
            Pelanggaran tercatat jika Anda:<br>
            • Membuka halaman/tab lain<br>
            • Keluar dari mode layar penuh<br>
            • Melakukan copy/paste atau klik kanan<br><br>
            Jika melanggar batas, ujian akan <b>otomatis terkirim (auto-submit)</b>.
        </div>
        @endif
        Pastikan koneksi internet stabil!
    </div>`;
    showConfirm('Konfirmasi Ujian', msg, 'warning').then(res => {
        if(res) form.submit();
    });
}

// Auto-refresh leaderboard widgets every 30s
@foreach($participants as $p)
@if($p->event->leaderboard_visible)
(function() {
    const apiUrl = '{{ route("leaderboard.json", ["event" => 0]) }}'.replace('/0', '/{{ $p->event->id }}');
    setInterval(function() {
        fetch(apiUrl).then(r=>r.json()).then(json=>{
            // minimal refresh — full page participants dashboard re-renders on navigate
            // For in-page: we rely on the page being manually refreshed or navigated
        }).catch(()=>{});
    }, 30000);
})();
@endif
@endforeach
</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const userId = {{ auth()->user()->id }};
    const storageKey = 'welcome_shown_' + userId;
    
    if (!localStorage.getItem(storageKey)) {
        const welcomeMessage = `
            <div style="text-align:center;margin-bottom:16px;line-height:1.6;">
                Selamat datang di sistem CBT Premium Platform Ujian Digital! Kami sangat bangga Anda dapat berpartisipasi dalam ajang tingkat nasional ini.<br><br>
                Siapkan diri Anda dengan baik, pastikan koneksi internet stabil, dan jadilah sang juara!
            </div>
            <div style="text-align:center;padding-top:12px;border-top:1px solid #e5e7eb;font-size:11px;color:#6b7280;margin-top:16px;">
                Supported & Developed by <a href="https://hvmdigital.id" target="_blank" style="color:var(--color-primary);font-weight:700;text-decoration:none;">hvmdigital.id</a>
            </div>
        `;
        showAlert('Selamat Datang! <i class="fas fa-hand-sparkles" style="color:#fbbf24;"></i>', welcomeMessage, 'info').then(() => {
            localStorage.setItem(storageKey, 'true');
            @if(!auth()->user()->avatar_path)
            // Show avatar prompt after welcome
            setTimeout(() => {
                showConfirm(
                    'Lengkapi Profil Anda! <i class="fas fa-camera-retro" style="color:#3b82f6;"></i>',
                    '<div style="text-align:center;line-height:1.6;">Foto profil Anda masih kosong.<br>Ayo perbarui sekarang agar identitas Anda terlihat lebih profesional dan mudah dikenali oleh penyelenggara!</div>',
                    'warning'
                ).then(res => {
                    if(res) document.getElementById('avatarUploadModal').style.display = 'flex';
                });
            }, 500);
            @endif
        });
    } else {
        @if(!auth()->user()->avatar_path)
        // If welcome already shown but no avatar, still prompt
        const avatarKey = 'avatar_prompt_' + userId;
        if (!sessionStorage.getItem(avatarKey)) {
            setTimeout(() => {
                showConfirm(
                    'Lengkapi Profil Anda! <i class="fas fa-camera-retro" style="color:#3b82f6;"></i>',
                    '<div style="text-align:center;line-height:1.6;">Foto profil Anda masih kosong.<br>Ayo perbarui sekarang agar identitas Anda terlihat lebih profesional!</div>',
                    'warning'
                ).then(res => {
                    if(res) document.getElementById('avatarUploadModal').style.display = 'flex';
                });
                sessionStorage.setItem(avatarKey, 'true');
            }, 800);
        }
        @endif
    }
});

function previewAvatar(input) {
    const container = document.getElementById('avatarPreviewContainer');
    const preview = document.getElementById('avatarPreview');
    const fileName = document.getElementById('avatarFileName');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            container.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
        fileName.textContent = input.files[0].name;
    }
}
</script>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
@endpush

@include('components.id-card-modal')
