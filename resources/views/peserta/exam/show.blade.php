<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ujian — {{ $session->round->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --kinetic-orange: #f59e0b; --kinetic-blue: #10b981; --kinetic-dark: #064e3b;
            --kinetic-gray: #475569; --kinetic-light: #f0fdf4; --kinetic-white: #ffffff;
            --shadow-float: 0 10px 30px rgba(0,0,0,0.05);
            --color-primary: #10b981; --color-surface: #ffffff; --color-surface-soft: #f8fafc;
            --color-border: #e2e8f0; --color-text-primary: #0f172a; --color-text-secondary: #475569;
            --color-text-tertiary: #94a3b8;
        }
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:'Manrope',sans-serif;background:var(--kinetic-light);color:var(--kinetic-dark);min-height:100vh;user-select:none;-webkit-user-select:none;}

        .exam-layout{display:flex;height:100vh;}

        /* Sidebar */
        .exam-sidebar{width:300px;background:var(--kinetic-white);border-right:1px solid rgba(0,0,0,0.05);display:flex;flex-direction:column;overflow:hidden;box-shadow:var(--shadow-float);z-index:20;}
        .sidebar-header{padding:24px;border-bottom:1px solid rgba(0,0,0,0.05);text-align:center;}
        .sidebar-header h3{font-size:14px;color:var(--kinetic-dark);letter-spacing:-0.5px;font-weight:900;}
        .sidebar-header p{font-size:12px;color:var(--kinetic-gray);margin-top:4px;font-weight:600;}
        
        .timer-box{padding:24px;text-align:center;border-bottom:1px solid rgba(0,0,0,0.05);background:var(--kinetic-light);}
        .timer{font-size:40px;font-weight:900;color:var(--kinetic-blue);font-family:monospace;letter-spacing:-1px;}
        .timer.danger{color:#dc2626;animation:pulse 1s infinite;}
        @keyframes pulse{0%,100%{opacity:1}50%{opacity:0.5}}
        .timer-label{font-size:11px;color:var(--kinetic-gray);font-weight:800;text-transform:uppercase;letter-spacing:1px;margin-top:4px;}
        
        .question-nav{flex:1;overflow-y:auto;padding:24px;}
        .question-nav-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:8px;}
        .q-btn{width:100%;aspect-ratio:1;display:flex;align-items:center;justify-content:center;background:var(--kinetic-light);border:2px solid transparent;border-radius:12px;color:var(--kinetic-gray);font-size:14px;font-weight:800;cursor:pointer;transition:.3s;}
        .q-btn:hover{background:var(--kinetic-white);border-color:var(--kinetic-blue);color:var(--kinetic-blue);transform:scale(1.05);}
        .q-btn.active{background:#fff;color:var(--kinetic-dark);border-color:var(--kinetic-dark);transform:scale(1.05);}
        .q-btn.answered{background:var(--kinetic-blue);color:var(--kinetic-white);border-color:var(--kinetic-blue);}
        .q-btn.active.answered{background:var(--kinetic-dark);color:var(--kinetic-white);border-color:var(--kinetic-dark);transform:scale(1.05);}
        
        .sidebar-footer{padding:24px;border-top:1px solid rgba(0,0,0,0.05);background:var(--kinetic-white);}
        .sidebar-footer .stat{display:flex;justify-content:space-between;font-size:12px;font-weight:700;color:var(--kinetic-gray);margin-bottom:8px;}

        /* Main Content */
        .exam-main{flex:1;display:flex;flex-direction:column;overflow:hidden;position:relative;}
        .blob-1 { position: absolute; border-radius: 50%; filter: blur(80px); z-index: 0; opacity: 0.6; top:-10%; right:-10%; width:40vw; height:40vw; background:#e0f2fe;}
        .exam-topbar{padding:16px 32px;border-bottom:1px solid rgba(0,0,0,0.05);display:flex;justify-content:space-between;align-items:center;background:rgba(255,255,255,0.8);backdrop-filter:blur(10px);z-index:10;}
        .violation-counter{font-size:12px;color:#dc2626;font-weight:800;background:#fee2e2;padding:6px 16px;border-radius:100px;}
        
        .exam-content{flex:1;overflow-y:auto;padding:48px;max-width:1000px;margin:0 auto;width:100%;z-index:10;position:relative;}

        .question-card{background:rgba(255,255,255,0.9);backdrop-filter:blur(20px);border:1px solid rgba(255,255,255,0.5);border-radius:32px;padding:48px;box-shadow:var(--shadow-float);}
        .q-number{display:inline-flex;align-items:center;gap:8px;background:var(--kinetic-light);color:var(--kinetic-blue);padding:8px 20px;border-radius:100px;font-size:12px;font-weight:800;margin-bottom:32px;}
        .q-text{font-size:20px;font-weight:800;line-height:1.6;margin-bottom:40px;color:var(--kinetic-dark);letter-spacing:-0.5px;}
        
        .option-item{display:flex;align-items:flex-start;gap:16px;padding:20px 24px;background:var(--kinetic-white);border:2px solid rgba(0,0,0,0.05);border-radius:16px;margin-bottom:16px;cursor:pointer;transition:.3s;box-shadow:var(--shadow-float);}
        .option-item:hover{border-color:var(--kinetic-blue);transform:translateY(-2px);}
        .option-item.selected{border-color:var(--kinetic-blue);background:#eff6ff;}
        .option-radio{width:24px;height:24px;min-width:24px;border:2px solid rgba(0,0,0,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-top:2px;transition:.3s;background:var(--kinetic-white);}
        .option-item.selected .option-radio{border-color:var(--kinetic-blue);}
        .option-item.selected .option-radio::after{content:'';width:12px;height:12px;background:var(--kinetic-blue);border-radius:50%;}
        .option-label{font-size:16px;font-weight:600;color:var(--kinetic-dark);line-height:1.6;}
        
        .essay-textarea{width:100%;padding:24px;background:var(--kinetic-light);border:2px solid rgba(0,0,0,0.05);border-radius:16px;color:var(--kinetic-dark);font-family:'Manrope',sans-serif;font-size:16px;font-weight:500;resize:vertical;min-height:200px;transition:.3s;}
        .essay-textarea:focus{outline:none;border-color:var(--kinetic-blue);background:var(--kinetic-white);box-shadow:0 0 0 4px #e0f2fe;}

        .q-nav-btns{display:flex;justify-content:space-between;margin-top:40px;}
        .btn{display:inline-flex;align-items:center;gap:8px;padding:14px 32px;border-radius:100px;font-family:'Manrope',sans-serif;font-weight:800;font-size:14px;cursor:pointer;border:none;transition:.3s;}
        .btn-blue{background:var(--kinetic-blue);color:var(--kinetic-white);}
        .btn-blue:hover{background:#2563eb;transform:translateY(-2px);box-shadow:0 10px 20px rgba(59,130,246,0.2);}
        .btn-outline{background:var(--kinetic-white);color:var(--kinetic-gray);border:2px solid rgba(0,0,0,0.05);}
        .btn-outline:hover{border-color:var(--kinetic-dark);color:var(--kinetic-dark);}
        .btn-submit{background:#10b981;color:#fff;padding:16px 40px;font-size:15px;}
        .btn-submit:hover{background:#059669;transform:translateY(-2px);box-shadow:0 10px 20px rgba(16,185,129,0.2);}
        
        .save-indicator{font-size:12px;font-weight:700;color:var(--kinetic-gray);text-align:center;margin-top:16px;}
        .save-indicator.saved{color:#10b981;}

        /* Warning Modal */
        .warning-overlay{display:none;position:fixed;inset:0;z-index:999;background:rgba(255,255,255,0.9);backdrop-filter:blur(10px);align-items:center;justify-content:center;}
        .warning-box{background:var(--kinetic-white);border:2px solid #fee2e2;border-radius:32px;padding:48px;text-align:center;max-width:500px;box-shadow:0 40px 80px rgba(0,0,0,0.1);}
        .warning-box h2{color:#dc2626;font-size:28px;font-weight:900;margin-bottom:16px;letter-spacing:-1px;}
        .warning-box p{font-size:16px;color:var(--kinetic-gray);font-weight:600;margin-bottom:32px;line-height:1.6;}

        @media(max-width:992px){.exam-sidebar{display:none;}.exam-content{padding:24px;}}
    </style>
</head>
<body>
    <div class="exam-layout">
        {{-- Sidebar --}}
        <div class="exam-sidebar">
            <div class="sidebar-header">
                <h3>{{ $session->round->name }}</h3>
                <p>{{ $session->round->event->name ?? '' }}</p>
            </div>
            <div class="timer-box">
                <div class="timer" id="timer">--:--</div>
                <div class="timer-label">Sisa Waktu</div>
            </div>
            <div class="question-nav">
                <div class="question-nav-grid">
                    @foreach($session->examQuestions as $i => $eq)
                    <button class="q-btn {{ $i==0?'active':'' }} {{ $answers->has($eq->question_id)?'answered':'' }}"
                        onclick="goToQuestion({{ $i }})" id="qbtn-{{ $i }}">
                        {{ $i+1 }}
                    </button>
                    @endforeach
                </div>
            </div>
            <div class="sidebar-footer">
                <div class="stat"><span>Dijawab</span><span id="answeredCount" style="color:var(--kinetic-blue)">{{ $answers->count() }}</span></div>
                <div class="stat"><span>Belum</span><span id="unansweredCount">{{ $session->examQuestions->count() - $answers->count() }}</span></div>
                <div class="stat"><span>Total</span><span style="color:var(--kinetic-dark)">{{ $session->examQuestions->count() }}</span></div>
            </div>
        </div>

        {{-- Main --}}
        <div class="exam-main">
            <div class="blob-1"></div>
            <div class="exam-topbar">
                <span style="font-size:14px;font-weight:800;color:var(--kinetic-dark)">
                    <i class="fas fa-user-circle" style="color:var(--kinetic-blue);font-size:18px;margin-right:4px;vertical-align:middle;"></i> {{ auth()->user()->name }}
                </span>
                <div class="violation-counter" id="violationCounter">
                    <i class="fas fa-shield-halved"></i> Pelanggaran: <span id="vCount">{{ $session->violation_count }}</span>/{{ $session->round->auto_submit_threshold }}
                </div>
            </div>

            <div class="exam-content">
                @foreach($session->examQuestions as $i => $eq)
                @php $question = $eq->question; $answer = $answers->get($question->id); @endphp
                <div class="question-card" id="question-{{ $i }}" style="{{ $i>0?'display:none':'' }}">
                    <div class="q-number">
                        <i class="fas fa-{{ $question->isMultipleChoice()?'list-ul':'pen-nib' }}"></i>
                        Soal {{ $i+1 }} / {{ $session->examQuestions->count() }}
                    </div>
                    <div class="q-text">{!! nl2br(e($question->content)) !!}</div>

                    @if($question->isMultipleChoice())
                        @php
                            $optionIds = $eq->shuffled_options ?? $question->options->pluck('id')->toArray();
                            $allOptions = $question->options->keyBy('id');
                        @endphp
                        @foreach($optionIds as $optId)
                            @php $opt = $allOptions->get($optId); @endphp
                            @if($opt)
                            <div class="option-item {{ $answer && $answer->selected_option_id==$opt->id ? 'selected' : '' }}"
                                onclick="selectOption({{ $question->id }}, {{ $opt->id }}, this)">
                                <div class="option-radio"></div>
                                <div class="option-label">{!! nl2br(e($opt->content)) !!}</div>
                            </div>
                            @endif
                        @endforeach
                    @else
                        <textarea class="essay-textarea" id="essay-{{ $question->id }}"
                            placeholder="Tulis jawaban esai Anda di sini..."
                            oninput="saveEssayDebounced({{ $question->id }})">{{ $answer->essay_answer ?? '' }}</textarea>
                    @endif

                    <div class="q-nav-btns">
                        @if($i > 0)
                        <button class="btn btn-outline" onclick="goToQuestion({{ $i-1 }})"><i class="fas fa-arrow-left"></i> Sebelumnya</button>
                        @else
                        <div></div>
                        @endif

                        @if($i < $session->examQuestions->count()-1)
                        <button class="btn btn-blue" onclick="goToQuestion({{ $i+1 }})">Selanjutnya <i class="fas fa-arrow-right"></i></button>
                        @else
                        <button class="btn btn-submit" onclick="confirmSubmit()"><i class="fas fa-paper-plane"></i> Selesai & Kumpulkan</button>
                        @endif
                    </div>
                    <div class="save-indicator" id="save-status-{{ $question->id }}"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Warning Modal --}}
    <div class="warning-overlay" id="warningModal">
        <div class="warning-box">
            <h2><i class="fas fa-exclamation-triangle"></i> PERINGATAN</h2>
            <p id="warningText">Anda terdeteksi melakukan pelanggaran.</p>
            <button class="btn btn-blue" onclick="document.getElementById('warningModal').style.display='none'">Kembali ke Ujian</button>
        </div>
    </div>

    {{-- Submit Form --}}
    <form id="submitForm" method="POST" action="{{ route('peserta.exam.submit', $session->token) }}" style="display:none;">
        @csrf
    </form>

    {{-- Audio Peringatan --}}
    <audio id="warningSound" src="https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3" preload="auto"></audio>

    <script>
        const TOKEN = "{{ $session->token }}";
        const SAVE_URL = "{{ route('peserta.exam.save', $session->token) }}";
        const VIOLATION_URL = "{{ route('peserta.exam.violation', $session->token) }}";
        const CSRF = document.querySelector('meta[name="csrf-token"]').content;
        let currentQ = 0;
        let totalQ = {{ $session->examQuestions->count() }};
        let remainingSeconds = {{ $session->getRemainingSeconds() }};
        let answeredSet = new Set({!! json_encode($answers->keys()->toArray()) !!});
        let essayTimers = {};

        function updateTimer() {
            if (remainingSeconds <= 0) {
                document.getElementById('submitForm').submit();
                return;
            }
            remainingSeconds--;
            const h = String(Math.floor(remainingSeconds/3600)).padStart(2,'0');
            const m = String(Math.floor((remainingSeconds%3600)/60)).padStart(2,'0');
            const s = String(remainingSeconds%60).padStart(2,'0');
            const el = document.getElementById('timer');
            el.textContent = `${h}:${m}:${s}`;
            el.classList.toggle('danger', remainingSeconds < 300);
        }
        updateTimer();
        setInterval(updateTimer, 1000);

        function goToQuestion(idx) {
            document.getElementById('question-'+currentQ).style.display='none';
            document.getElementById('question-'+idx).style.display='block';
            document.getElementById('qbtn-'+currentQ).classList.remove('active');
            document.getElementById('qbtn-'+idx).classList.add('active');
            currentQ = idx;
        }

        function selectOption(qid, oid, el) {
            el.parentElement.querySelectorAll('.option-item').forEach(o=>o.classList.remove('selected'));
            el.classList.add('selected');
            saveAnswer(qid, oid, null);
        }

        function saveEssayDebounced(qid) {
            clearTimeout(essayTimers[qid]);
            essayTimers[qid] = setTimeout(()=>{
                const val = document.getElementById('essay-'+qid).value;
                saveAnswer(qid, null, val);
            }, 1500);
        }

        function saveAnswer(qid, oid, essay) {
            const body = { question_id: qid };
            if(oid) body.option_id = oid;
            if(essay !== null) body.essay_answer = essay;

            const statusEl = document.getElementById('save-status-'+qid);
            statusEl.textContent = 'Menyimpan...';
            statusEl.className = 'save-indicator';

            fetch(SAVE_URL, {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
                body:JSON.stringify(body)
            }).then(r=>r.json()).then(d=>{
                if(d.saved) {
                    answeredSet.add(qid);
                    document.getElementById('qbtn-'+currentQ).classList.add('answered');
                    updateNav();
                    statusEl.textContent = '✓ Jawaban tersimpan otomatis';
                    statusEl.className = 'save-indicator saved';
                }
            }).catch(()=>{
                statusEl.textContent = '✗ Gagal menyimpan, cek koneksi Anda';
                statusEl.className = 'save-indicator';
                statusEl.style.color = '#dc2626';
            });
        }

        function updateNav() {
            document.getElementById('answeredCount').textContent = answeredSet.size;
            document.getElementById('unansweredCount').textContent = totalQ - answeredSet.size;
        }

        function confirmSubmit() {
            const unanswered = totalQ - answeredSet.size;
            let msg = '<div style="text-align:left;line-height:1.6;font-size:14px;color:var(--color-text-secondary);">Apakah Anda yakin ingin mengumpulkan ujian? <b>Anda tidak bisa mengubah jawaban lagi setelah ini.</b></div>';
            if(unanswered > 0) {
                msg = `<div style="text-align:left;line-height:1.6;font-size:14px;color:var(--color-text-secondary);"><div style="background:#fef2f2;border:1px solid #fecaca;padding:12px;border-radius:12px;color:#dc2626;margin-bottom:12px;"><b>⚠️ Peringatan!</b><br>Masih ada <b>${unanswered} soal yang BELUM dijawab</b>.</div>Apakah Anda yakin ingin mengumpulkan ujian sekarang?</div>`;
            }
            showConfirm('Konfirmasi Kumpul', msg, unanswered > 0 ? 'danger' : 'info').then(res => {
                if(res) document.getElementById('submitForm').submit();
            });
        }

        /**
         * ══════════════════════════════════════════════════════════
         * VIOLATION SYSTEM (with debounce to prevent multi-count)
         * ══════════════════════════════════════════════════════════
         * 
         * BUG FIX: Saat peserta buka tab baru, 3 event browser 
         * terpicu sekaligus: visibilitychange, blur, fullscreenchange.
         * Tanpa debounce, 1 aksi = 3 pelanggaran tercatat.
         * 
         * Solusi: "focus-loss" events (tab_switch, window_blur, 
         * fullscreen_exit) di-group dengan cooldown 3 detik.
         * Event lain (copy, paste, dll) tetap langsung tercatat.
         */
        
        @if($session->round->event->getSetting('anti_cheat_enabled', true))
        // Track focus-loss cooldown
        let lastFocusLossTime = 0;
        const FOCUS_LOSS_COOLDOWN_MS = 3000; // 3 seconds
        const FOCUS_LOSS_TYPES = ['tab_switch', 'window_blur', 'fullscreen_exit'];

        // Prevent duplicate sends while a request is in-flight
        let violationInFlight = false;

        function reportViolation(type) {
            // Debounce focus-loss events (tab_switch, window_blur, fullscreen_exit)
            // These 3 events all fire simultaneously when user switches tab
            if (FOCUS_LOSS_TYPES.includes(type)) {
                const now = Date.now();
                if (now - lastFocusLossTime < FOCUS_LOSS_COOLDOWN_MS) {
                    console.log(`[Anti-Cheat] Skipped duplicate focus-loss event: ${type} (cooldown active)`);
                    return; // Skip — already counted this tab-switch action
                }
                lastFocusLossTime = now;
                type = 'tab_switch'; // Normalize all focus-loss to one type
            }

            // Prevent rapid-fire duplicate requests
            if (violationInFlight) {
                console.log(`[Anti-Cheat] Skipped: request already in-flight`);
                return;
            }
            violationInFlight = true;

            fetch(VIOLATION_URL, {
                method:'POST',
                headers:{'Content-Type':'application/json','X-CSRF-TOKEN':CSRF,'Accept':'application/json'},
                body:JSON.stringify({type:type})
            }).then(r=>r.json()).then(d=>{
                violationInFlight = false;
                document.getElementById('vCount').textContent = d.total;
                
                // Play warning sound
                const audio = document.getElementById('warningSound');
                audio.play().catch(e=>console.log("Audio play failed:", e));

                if(d.auto_submit) {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ type: type })
            })
            .then(r => r.json())
            .then(data => {
                if(data.status === 'success') {
                    document.getElementById('vCount').innerText = data.violation_count;
                    if(typeof showAlert === 'function') {
                        showAlert('⚠️ Peringatan Pelanggaran!', `Aktivitas mencurigakan terdeteksi (${data.violation_count}/${data.max_violations}). Jika melewati batas, ujian akan otomatis diakhiri.`, 'warning');
                    } else {
                        alert(`PERINGATAN! Aktivitas mencurigakan terdeteksi. Pelanggaran ke-${data.violation_count}`);
                    }

                    if(data.action === 'auto_submitted') {
                        isSubmitting = true;
                        window.location.href = "{{ route('peserta.result', $session->id) }}";
                    }
                }
            })
            .catch(err => console.error('Violation Error:', err))
            .finally(() => {
                // Beri jeda 1.5 detik sebelum bisa kirim pelanggaran lagi (debounce)
                setTimeout(() => {
                    violationInFlight = false;
                }, 1500);
            });
        }

        document.addEventListener('visibilitychange', ()=>{ if(document.hidden) reportViolation('tab_switch'); });
        window.addEventListener('blur', ()=> reportViolation('window_blur'));
        document.addEventListener('fullscreenchange', ()=>{
            if(!document.fullscreenElement) reportViolation('fullscreen_exit');
        });

        // ─── Other violation events (each fires independently) ───
        document.addEventListener('copy', e=>{ e.preventDefault(); reportViolation('copy_attempt'); });
        document.addEventListener('paste', e=>{ e.preventDefault(); reportViolation('paste_attempt'); });
        document.addEventListener('contextmenu', e=>{ e.preventDefault(); reportViolation('right_click'); });

        document.addEventListener('keydown', e=>{
            if((e.ctrlKey || e.metaKey) && ['c','v','x','p','s','u'].includes(e.key.toLowerCase())){
                e.preventDefault(); reportViolation('keyboard_shortcut');
            }
            if(e.key === 'F12'){
                e.preventDefault(); reportViolation('keyboard_shortcut');
            }
        });
        @else
        console.log("Anti-cheat detector is disabled for this event.");
        @endif

        // ─── Fullscreen on load ───
        document.addEventListener('DOMContentLoaded', ()=>{
            if(document.documentElement.requestFullscreen) {
                document.documentElement.requestFullscreen().catch(()=>{});
            }
        });
    </script>
    <x-modal-confirm />
</body>
</html>
