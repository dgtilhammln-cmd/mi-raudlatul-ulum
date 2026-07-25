{{-- Komponen Modal ID Card --}}
<style>
    #idCardModal {
        --grad-start: #1db349;
        --grad-end: #a5cf36;
        --color-primary: #1db349;
    }
</style>
<div id="idCardModal"
    style="display:none;position:fixed;inset:0;background:rgba(15,23,42,.8);z-index:9999;backdrop-filter:blur(5px);align-items:center;justify-content:center;padding:20px;opacity:0;transition:.3s;">

    <div style="position:relative;max-width:380px;width:100%;transform:scale(0.9);transition:.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);"
        id="idCardModalContent">

        {{-- Area Kartu Utama (Bisa di-download ke PDF) --}}
        <div id="idCardDownloadArea"
            style="background:#ffffff;border-radius:24px;overflow:hidden;position:relative;box-shadow:0 24px 48px rgba(0,0,0,.4);">

            {{-- Background Texture & Geometry --}}
            <div
                style="position:absolute;top:-50%;left:-50%;width:200%;height:200%;background:radial-gradient(circle at center, rgba(29,179,73,.05) 0%, transparent 50%);z-index:0;pointer-events:none;">
            </div>
            <div
                style="position:absolute;top:0;right:0;width:150px;height:150px;background:var(--grad-end);filter:blur(80px);opacity:.1;z-index:0;">
            </div>

            {{-- Header (Logo & Badge) --}}
            <div
                style="position:relative;z-index:10;padding:24px;display:flex;justify-content:space-between;align-items:flex-start;">
                {{-- Logo Penyelenggara --}}
                <div
                    style="background:#f8fafc;padding:6px 12px;border-radius:100px;display:inline-flex;align-items:center;gap:6px;border:1px solid #e2e8f0;">
                    <img src="{{ asset('images/logo.png') }}" alt="MI Raudlatul Ulum" style="height:20px;">
                    <span style="font-size:12px;font-weight:800;color:#0f172a;letter-spacing:-.5px;">MIS Raudlatul Ulum</span>
                </div>

                {{-- Badge Juara (Hanya Tampil Jika Juara 1, 2, atau 3) --}}
                <div id="icRankBadge"
                    style="display:none;background:linear-gradient(135deg, #fbbf24, #ca8a04);color:#fff;padding:6px 14px;border-radius:100px;font-size:11px;font-weight:800;letter-spacing:.5px;text-transform:uppercase;box-shadow:0 4px 10px rgba(0,0,0,.15);">
                    <i class="fas fa-crown" style="margin-right:4px;"></i> <span id="icRankText">Juara 1</span>
                </div>
            </div>

            {{-- Foto Profil Besar --}}
            <div
                style="position:relative;z-index:5;height:180px;width:100%;display:flex;align-items:flex-end;justify-content:center;margin-top:0px;margin-bottom:20px;padding:0 24px;">
                <img id="icAvatarImg" src="" alt="Peserta"
                    style="width:140px;height:140px;border-radius:50%;object-fit:cover;object-position:top;border:4px solid #ffffff;box-shadow:0 10px 25px rgba(0,0,0,.1);">

                {{-- Initial Fallback jika tanpa foto --}}
                <div id="icAvatarFallback"
                    style="display:none;width:140px;height:140px;background:var(--color-primary);border:4px solid #ffffff;border-radius:50%;align-items:center;justify-content:center;font-size:54px;font-weight:900;color:#ffffff;box-shadow:0 10px 25px rgba(0,0,0,.1);">
                    A
                </div>
            </div>

            {{-- Detail Peserta --}}
            <div style="position:relative;z-index:10;padding:0 24px 30px;text-align:center;">
                <div
                    style="display:inline-block;padding-bottom:12px;border-bottom:2px solid var(--grad-start);margin-bottom:16px;">
                    <h2 id="icName"
                        style="font-size:26px;font-weight:900;color:#0f172a;line-height:1.2;margin:0;letter-spacing:-.5px;">
                        Nama Peserta</h2>
                </div>
                <div id="icInstitution"
                    style="font-size:14px;color:#475569;font-weight:600;margin-bottom:4px;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="fas fa-university" style="color:var(--grad-end);font-size:12px;"></i>
                    <span>Institusi</span>
                </div>
                <div id="icMajor"
                    style="font-size:13px;color:#64748b;display:flex;align-items:center;justify-content:center;gap:8px;">
                    <i class="fas fa-book-open" style="color:var(--grad-start);font-size:12px;"></i> <span>
                        Kelas</span>
                </div>
            </div>

            {{-- Footer Card --}}
            <div style="background:linear-gradient(90deg, var(--grad-start), var(--grad-end));padding:14px 24px;text-align:center;">
                <span style="font-size:10px;color:#fff;font-weight:600;letter-spacing:.5px;opacity:.9;">Supported &
                    Developed by hvmdigital.id</span>
            </div>

        </div>

        {{-- Tombol Aksi (Tidak ikut ter-download di PDF) --}}
        <div style="margin-top:20px;display:flex;gap:12px;">
            <button onclick="closeIdCard()"
                style="flex:1;padding:12px;border-radius:12px;background:rgba(255,255,255,.1);color:#fff;border:none;font-weight:700;font-size:14px;cursor:pointer;transition:.2s;"
                onmouseover="this.style.background='rgba(255,255,255,.15)'"
                onmouseout="this.style.background='rgba(255,255,255,.1)'">
                Tutup
            </button>
            <button onclick="downloadIdCard()" id="btnDownloadCard"
                style="flex:2;padding:12px;border-radius:12px;background:var(--grad-start);color:#fff;border:none;font-weight:700;font-size:14px;cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:center;gap:8px;"
                onmouseover="this.style.background='#16943d'" onmouseout="this.style.background='var(--grad-start)'">
                <i class="fas fa-download"></i> Unduh PDF
            </button>
        </div>

    </div>
</div>

<script>
    let currentParticipantName = 'Peserta';

    function showIdCard(data) {
        if (data.rank && data.rank > 10) {
            if (typeof showAlert === 'function') {
                showAlert('Eksklusif! <i class="fas fa-star" style="color:#fbbf24;"></i>', 'ID Card ini eksklusif hanya untuk Top 10 Peringkat Teratas.', 'warning');
            } else {
                alert('ID Card eksklusif hanya untuk Top 10 Peringkat Teratas.');
            }
            return;
        }

        // Set Data
        currentParticipantName = data.name;
        document.getElementById('icName').textContent = data.name;
        document.getElementById('icInstitution').innerHTML = `<i class="fas fa-university" style="color:var(--grad-end);font-size:12px;"></i> <span>${data.institution || '—'}</span>`;
        document.getElementById('icMajor').innerHTML = `<i class="fas fa-book-open" style="color:var(--grad-start);font-size:12px;"></i> <span>${data.major || data.grade || '—'}</span>`;

        // Handle Avatar
        const imgEl = document.getElementById('icAvatarImg');
        const fbEl = document.getElementById('icAvatarFallback');
        if (data.avatar_url) {
            imgEl.src = data.avatar_url;
            imgEl.style.display = 'block';
            fbEl.style.display = 'none';
        } else {
            imgEl.style.display = 'none';
            fbEl.style.display = 'flex';
            fbEl.textContent = data.name.charAt(0).toUpperCase();
        }

        // Handle Badge
        const badgeEl = document.getElementById('icRankBadge');
        const badgeText = document.getElementById('icRankText');
        if (data.rank && data.rank <= 3) {
            badgeEl.style.display = 'inline-block';
            badgeText.textContent = 'Juara ' + data.rank;
            // Set Color based on rank
            if (data.rank == 1) badgeEl.style.background = 'linear-gradient(135deg, #fbbf24, #ca8a04)';
            if (data.rank == 2) badgeEl.style.background = 'linear-gradient(135deg, #e2e8f0, #94a3b8)';
            if (data.rank == 3) badgeEl.style.background = 'linear-gradient(135deg, #fcd34d, #d97706)';
            badgeEl.style.color = (data.rank == 2) ? '#0f172a' : '#fff';
        } else {
            badgeEl.style.display = 'none';
        }

        // Tampilkan Modal dengan animasi
        const modal = document.getElementById('idCardModal');
        const content = document.getElementById('idCardModalContent');
        modal.style.display = 'flex';
        setTimeout(() => {
            modal.style.opacity = '1';
            content.style.transform = 'scale(1)';
        }, 10);
    }

    function closeIdCard() {
        const modal = document.getElementById('idCardModal');
        const content = document.getElementById('idCardModalContent');
        modal.style.opacity = '0';
        content.style.transform = 'scale(0.9)';
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300);
    }

    function downloadIdCard() {
        if (typeof html2pdf === 'undefined') {
            alert("Sedang memuat library PDF, silakan coba beberapa detik lagi.");
            return;
        }

        const element = document.getElementById('idCardDownloadArea');
        const btn = document.getElementById('btnDownloadCard');

        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        btn.disabled = true;

        const opt = {
            margin: 0,
            filename: `ID_Card_${currentParticipantName.replace(/\s+/g, '_')}.pdf`,
            image: { type: 'jpeg', quality: 1 },
            html2canvas: { scale: 3, useCORS: true, logging: false },
            jsPDF: { unit: 'px', format: [element.offsetWidth, element.offsetHeight], orientation: 'portrait' }
        };

        html2pdf().set(opt).from(element).save().then(() => {
            btn.innerHTML = '<i class="fas fa-check"></i> Berhasil';
            setTimeout(() => {
                btn.innerHTML = '<i class="fas fa-download"></i> Unduh PDF';
                btn.disabled = false;
            }, 2000);
        });
    }

    // Close on background click
    document.getElementById('idCardModal').addEventListener('click', function (e) {
        if (e.target === this) closeIdCard();
    });
</script>