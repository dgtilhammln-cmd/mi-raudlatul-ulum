@extends('layouts.app')
@section('title', 'Kelola Hero Section')
@section('page-title', 'Hero Section')

@push('styles')
<style>
.hero-preview {
    background: linear-gradient(140deg, var(--grad-start) 0%, #3abf5e 40%, var(--grad-end) 100%);
    border-radius: 20px; padding: 40px 48px; color: #fff;
    position: relative; overflow: hidden; margin-bottom: 28px;
    box-shadow: 0 16px 48px rgba(29,179,73,.25);
    font-family: 'Montserrat', sans-serif;
}
.hero-preview::before {
    content:''; position:absolute; top:-60px; left:-60px;
    width:220px; height:220px; background:rgba(255,255,255,.06); border-radius:50%;
}
.hero-preview::after {
    content:''; position:absolute; bottom:-40px; left:30%;
    width:180px; height:180px; background:rgba(255,255,255,.04); border-radius:50%;
}
.hero-preview-inner { position:relative; z-index:2; max-width:560px; }
.preview-badge {
    display:inline-flex; align-items:center; gap:8px;
    background:rgba(255,255,255,.15); backdrop-filter:blur(6px);
    color:#fff; padding:6px 16px; border-radius:100px;
    font-size:11px; font-weight:600; margin-bottom:20px;
    border:1px solid rgba(255,255,255,.2); width:max-content;
}
.preview-title { font-size:28px; line-height:1.3; font-weight:300; margin-bottom:12px; }
.preview-title .bold { font-weight:700; }
.preview-desc { font-size:13px; color:rgba(255,255,255,.8); line-height:1.65; margin-bottom:20px; max-width:420px; }
.preview-cta {
    display:inline-flex; align-items:center; gap:8px;
    background:#fff; color:var(--grad-start); padding:10px 24px;
    border-radius:100px; font-size:13px; font-weight:700; text-decoration:none;
    box-shadow:0 4px 16px rgba(0,0,0,.08);
}
.preview-stats {
    display:flex; gap:28px; margin-top:32px;
    padding-top:20px; border-top:1px solid rgba(255,255,255,.15);
    flex-wrap:wrap;
}
.preview-stat-value { font-size:26px; font-weight:700; line-height:1; margin-bottom:4px; }
.preview-stat-label { font-size:10px; opacity:.65; text-transform:uppercase; letter-spacing:.8px; font-weight:400; }

.section-divider {
    font-size:13px; font-weight:800; color:var(--color-primary);
    margin:28px 0 16px; padding-bottom:8px;
    border-bottom:2px solid var(--color-accent-light);
    display:flex; align-items:center; gap:8px;
}
.input-group { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
@media(max-width:640px){ .input-group { grid-template-columns:1fr; } }

.char-count { font-size:11px; color:var(--color-text-tertiary); margin-top:4px; text-align:right; }
.char-count.warn { color:#f59e0b; }
.char-count.danger { color:#ef4444; }
</style>
@endpush

@section('content')

{{-- Page Hero --}}
<div style="background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:20px;padding:28px 36px;margin-bottom:28px;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-40px;right:60px;width:100px;height:100px;background:rgba(255,255,255,.04);border-radius:50%;"></div>
    <div style="position:relative;z-index:2;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px;">
        <div>
            <h1 style="font-size:22px;font-weight:900;color:#fff;margin-bottom:6px;">
                <i class="fas fa-paint-brush" style="margin-right:10px;"></i>Hero Section Landing Page
            </h1>
            <p style="color:rgba(255,255,255,.7);font-size:13px;">Edit teks utama yang tampil di halaman beranda publik.</p>
        </div>
        <a href="{{ url('/') }}" target="_blank" style="background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;padding:8px 18px;border-radius:100px;font-size:13px;font-weight:700;text-decoration:none;display:inline-flex;align-items:center;gap:8px;">
            <i class="fas fa-external-link-alt"></i> Lihat Landing Page
        </a>
    </div>
</div>

@if(session('success'))
<div style="background:linear-gradient(135deg,#dcfce7,#bbf7d0);border:1px solid #86efac;border-radius:16px;padding:16px 20px;margin-bottom:24px;display:flex;align-items:center;gap:12px;">
    <i class="fas fa-check-circle" style="color:#16a34a;font-size:18px;"></i>
    <span style="font-weight:700;color:#15803d;">{{ session('success') }}</span>
</div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

    {{-- LEFT: FORM --}}
    <div class="card" style="border-radius:20px;overflow:hidden;">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-edit" style="color:var(--color-primary);margin-right:8px;"></i>Edit Konten Hero</h3>
        </div>
        <div style="padding:28px;">
            <form action="{{ route('organizer.web-settings.hero.update') }}" method="POST" id="heroForm">
                @csrf

                {{-- Badge --}}
                <div class="section-divider"><i class="fas fa-tag"></i> Badge / Label Kecil</div>
                <div class="input-group">
                    <div class="form-group">
                        <label class="form-label">Icon Badge (FontAwesome)</label>
                        <select name="badge_icon" id="inp_badge_icon" class="form-input" onchange="livePreview()">
                            <option value="fas fa-star" {{ ($hero['badge_icon'] ?? '') == 'fas fa-star' ? 'selected' : '' }}>★ Star</option>
                            <option value="fas fa-medal" {{ ($hero['badge_icon'] ?? '') == 'fas fa-medal' ? 'selected' : '' }}>🎖️ Medal</option>
                            <option value="fas fa-trophy" {{ ($hero['badge_icon'] ?? '') == 'fas fa-trophy' ? 'selected' : '' }}>🏆 Trophy</option>
                            <option value="fas fa-crown" {{ ($hero['badge_icon'] ?? '') == 'fas fa-crown' ? 'selected' : '' }}>👑 Crown</option>
                            <option value="fas fa-check-circle" {{ ($hero['badge_icon'] ?? '') == 'fas fa-check-circle' ? 'selected' : '' }}>✅ Check Circle</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Teks Badge</label>
                        <input type="text" name="badge" id="inp_badge" class="form-input" maxlength="120"
                            value="{{ $hero['badge'] ?? 'Kompetisi Sejarah Islam Tingkat Nasional' }}"
                            oninput="livePreview()" placeholder="Kompetisi ... Tingkat Nasional">
                        <div class="char-count" id="cc_badge">0/120</div>
                    </div>
                </div>

                {{-- Title --}}
                <div class="section-divider"><i class="fas fa-heading"></i> Judul Utama (3 Baris)</div>
                <div class="form-group">
                    <label class="form-label">Baris 1</label>
                    <input type="text" name="title_line1" id="inp_title1" class="form-input" maxlength="100"
                        value="{{ $hero['title_line1'] ?? 'Uji Wawasan,' }}"
                        oninput="livePreview()">
                    <div class="char-count" id="cc_title1">0/100</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Baris 2</label>
                    <input type="text" name="title_line2" id="inp_title2" class="form-input" maxlength="100"
                        value="{{ $hero['title_line2'] ?? 'Raih Prestasi,' }}"
                        oninput="livePreview()">
                    <div class="char-count" id="cc_title2">0/100</div>
                </div>
                <div class="form-group">
                    <label class="form-label">Baris 3 <span style="font-size:11px;color:var(--color-text-tertiary);">(Tampil Tebal/Bold)</span></label>
                    <input type="text" name="title_line3" id="inp_title3" class="form-input" maxlength="100"
                        value="{{ $hero['title_line3'] ?? '& Jadilah Juara' }}"
                        oninput="livePreview()">
                    <div class="char-count" id="cc_title3">0/100</div>
                </div>

                {{-- Description --}}
                <div class="section-divider"><i class="fas fa-align-left"></i> Deskripsi</div>
                <div class="form-group">
                    <label class="form-label">Teks Deskripsi</label>
                    <textarea name="description" id="inp_desc" class="form-input" rows="3" maxlength="400"
                        oninput="livePreview()" placeholder="Bangun prestasimu...">{{ $hero['description'] ?? '' }}</textarea>
                    <div class="char-count" id="cc_desc">0/400</div>
                </div>

                {{-- CTA Button --}}
                <div class="section-divider"><i class="fas fa-hand-pointer"></i> Tombol CTA</div>
                <div class="input-group">
                    <div class="form-group">
                        <label class="form-label">Teks Tombol</label>
                        <input type="text" name="cta_text" id="inp_cta" class="form-input" maxlength="60"
                            value="{{ $hero['cta_text'] ?? 'Daftarkan Dirimu!' }}"
                            oninput="livePreview()">
                        <div class="char-count" id="cc_cta">0/60</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Link/URL Tombol</label>
                        <input type="text" name="cta_link" class="form-input" maxlength="255"
                            value="{{ $hero['cta_link'] ?? '#register' }}"
                            placeholder="#register atau https://...">
                    </div>
                </div>

                {{-- Stats --}}
                <div class="section-divider"><i class="fas fa-chart-bar"></i> Statistik (3 Angka Bawah)</div>

                @foreach([['1','stat1'], ['2','stat2'], ['3','stat3']] as [$num, $key])
                <div style="background:var(--color-surface-soft);border-radius:14px;padding:16px;margin-bottom:14px;">
                    <div style="font-size:12px;font-weight:800;color:var(--color-primary);margin-bottom:12px;">Statistik {{ $num }}</div>
                    <div style="display:grid;grid-template-columns:1.5fr 0.7fr 2fr;gap:10px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">Angka</label>
                            <input type="text" name="{{ $key }}_value" id="inp_{{ $key }}_value" class="form-input" maxlength="20"
                                value="{{ $hero[$key.'_value'] ?? '' }}" oninput="livePreview()">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">Sufiks</label>
                            <input type="text" name="{{ $key }}_suffix" id="inp_{{ $key }}_suffix" class="form-input" maxlength="5"
                                value="{{ $hero[$key.'_suffix'] ?? '+' }}" oninput="livePreview()">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="font-size:11px;">Label</label>
                            <input type="text" name="{{ $key }}_label" id="inp_{{ $key }}_label" class="form-input" maxlength="40"
                                value="{{ $hero[$key.'_label'] ?? '' }}" oninput="livePreview()">
                        </div>
                    </div>
                </div>
                @endforeach

                <div style="margin-top:28px;display:flex;justify-content:flex-end;gap:12px;">
                    <a href="{{ url('/') }}" target="_blank" class="btn btn-secondary">
                        <i class="fas fa-eye"></i> Preview Live
                    </a>
                    <button type="submit" class="btn btn-primary" style="padding:12px 32px;font-weight:800;">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- RIGHT: LIVE PREVIEW --}}
    <div style="position:sticky;top:80px;">
        <div style="font-size:13px;font-weight:800;color:var(--color-text-secondary);margin-bottom:12px;display:flex;align-items:center;gap:8px;">
            <i class="fas fa-eye" style="color:var(--color-primary);"></i> Preview Real-time
            <span style="background:#dcfce7;color:#16a34a;font-size:10px;padding:2px 8px;border-radius:100px;font-weight:700;">LIVE</span>
        </div>

        <div class="hero-preview" id="heroPreview">
            <div class="hero-preview-inner">
                <div class="preview-badge">
                    <i class="{{ $hero['badge_icon'] ?? 'fas fa-star' }}" id="prev_badge_icon" style="font-size:10px;"></i>
                    <span id="prev_badge">{{ $hero['badge'] ?? '' }}</span>
                </div>
                <div class="preview-title">
                    <span id="prev_title1">{{ $hero['title_line1'] ?? '' }}</span><br>
                    <span id="prev_title2">{{ $hero['title_line2'] ?? '' }}</span><br>
                    <span class="bold" id="prev_title3">{{ $hero['title_line3'] ?? '' }}</span>
                </div>
                <p class="preview-desc" id="prev_desc">{{ $hero['description'] ?? '' }}</p>
                <a href="#" class="preview-cta">
                    <i class="far fa-check-circle"></i>
                    <span id="prev_cta">{{ $hero['cta_text'] ?? '' }}</span>
                </a>
                <div class="preview-stats">
                    <div>
                        <div class="preview-stat-value"><span id="prev_s1v">{{ $hero['stat1_value'] ?? '' }}</span><span style="font-size:.65em;font-weight:400;opacity:.7;" id="prev_s1s">{{ $hero['stat1_suffix'] ?? '+' }}</span></div>
                        <div class="preview-stat-label" id="prev_s1l">{{ $hero['stat1_label'] ?? '' }}</div>
                    </div>
                    <div>
                        <div class="preview-stat-value"><span id="prev_s2v">{{ $hero['stat2_value'] ?? '' }}</span><span style="font-size:.65em;font-weight:400;opacity:.7;" id="prev_s2s">{{ $hero['stat2_suffix'] ?? '+' }}</span></div>
                        <div class="preview-stat-label" id="prev_s2l">{{ $hero['stat2_label'] ?? '' }}</div>
                    </div>
                    <div>
                        <div class="preview-stat-value"><span id="prev_s3v">{{ $hero['stat3_value'] ?? '' }}</span><span style="font-size:.65em;font-weight:400;opacity:.7;" id="prev_s3s">{{ $hero['stat3_suffix'] ?? '%' }}</span></div>
                        <div class="preview-stat-label" id="prev_s3l">{{ $hero['stat3_label'] ?? '' }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Nav to other settings --}}
        <div class="card" style="border-radius:16px;padding:20px;margin-top:16px;">
            <div style="font-size:12px;font-weight:800;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:.5px;margin-bottom:14px;">Pengaturan Lainnya</div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                <a href="{{ route('organizer.web-settings.footer') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;background:var(--color-surface-soft);color:var(--color-text-primary);text-decoration:none;font-size:13px;font-weight:700;transition:.2s;" onmouseover="this.style.background='var(--color-accent-light)';this.style.color='var(--color-primary)'" onmouseout="this.style.background='var(--color-surface-soft)';this.style.color='var(--color-text-primary)'">
                    <i class="fas fa-shoe-prints" style="width:16px;color:var(--color-primary);"></i> Footer & Kontak
                </a>
                <a href="{{ route('organizer.web-settings.logos') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;background:var(--color-surface-soft);color:var(--color-text-primary);text-decoration:none;font-size:13px;font-weight:700;transition:.2s;" onmouseover="this.style.background='var(--color-accent-light)';this.style.color='var(--color-primary)'" onmouseout="this.style.background='var(--color-surface-soft)';this.style.color='var(--color-text-primary)'">
                    <i class="fas fa-images" style="width:16px;color:var(--color-primary);"></i> Partner & Sponsor
                </a>
                <a href="{{ route('organizer.web-settings.instagram') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;background:var(--color-surface-soft);color:var(--color-text-primary);text-decoration:none;font-size:13px;font-weight:700;transition:.2s;" onmouseover="this.style.background='var(--color-accent-light)';this.style.color='var(--color-primary)'" onmouseout="this.style.background='var(--color-surface-soft)';this.style.color='var(--color-text-primary)'">
                    <i class="fab fa-instagram" style="width:16px;color:var(--color-primary);"></i> Feed Instagram
                </a>
                <a href="{{ route('organizer.landing-images.index') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:10px;background:var(--color-surface-soft);color:var(--color-text-primary);text-decoration:none;font-size:13px;font-weight:700;transition:.2s;" onmouseover="this.style.background='var(--color-accent-light)';this.style.color='var(--color-primary)'" onmouseout="this.style.background='var(--color-surface-soft)';this.style.color='var(--color-text-primary)'">
                    <i class="fas fa-photo-video" style="width:16px;color:var(--color-primary);"></i> Foto Slideshow
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Map input id -> preview element id
const previewMap = {
    'inp_badge':         'prev_badge',
    'inp_title1':        'prev_title1',
    'inp_title2':        'prev_title2',
    'inp_title3':        'prev_title3',
    'inp_desc':          'prev_desc',
    'inp_cta':           'prev_cta',
    'inp_stat1_value':   'prev_s1v',
    'inp_stat1_suffix':  'prev_s1s',
    'inp_stat1_label':   'prev_s1l',
    'inp_stat2_value':   'prev_s2v',
    'inp_stat2_suffix':  'prev_s2s',
    'inp_stat2_label':   'prev_s2l',
    'inp_stat3_value':   'prev_s3v',
    'inp_stat3_suffix':  'prev_s3s',
    'inp_stat3_label':   'prev_s3l',
};

// Character count map
const charCountMap = {
    'inp_badge':   ['cc_badge', 120],
    'inp_title1':  ['cc_title1', 100],
    'inp_title2':  ['cc_title2', 100],
    'inp_title3':  ['cc_title3', 100],
    'inp_desc':    ['cc_desc', 400],
    'inp_cta':     ['cc_cta', 60],
};

function livePreview() {
    Object.entries(previewMap).forEach(([inpId, prevId]) => {
        const inp = document.getElementById(inpId);
        const prev = document.getElementById(prevId);
        if (inp && prev) prev.textContent = inp.value;
    });

    const iconSelect = document.getElementById('inp_badge_icon');
    if(iconSelect) {
        document.getElementById('prev_badge_icon').className = iconSelect.value;
    }

    Object.entries(charCountMap).forEach(([inpId, [ccId, max]]) => {
        const inp = document.getElementById(inpId);
        const cc  = document.getElementById(ccId);
        if (!inp || !cc) return;
        const len = inp.value.length;
        cc.textContent = `${len}/${max}`;
        cc.className = 'char-count';
        if (len >= max)       cc.classList.add('danger');
        else if (len >= max * 0.85) cc.classList.add('warn');
    });
}

// Init on load
document.addEventListener('DOMContentLoaded', livePreview);
</script>

@endsection
