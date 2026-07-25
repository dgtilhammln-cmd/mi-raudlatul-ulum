@extends('layouts.app')

@section('title', 'Buat Event')
@section('page-title', 'Buat Event Baru')

@section('content')
<div style="max-width:700px;">
    <a href="{{ route('organizer.events.index') }}" class="btn btn-secondary btn-sm mb-6">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-calendar-plus" style="color:var(--color-accent)"></i> Buat Event Baru</h3>
        </div>

        <form method="POST" action="{{ route('organizer.events.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-2">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Nama Event <span style="color:var(--color-danger)">*</span></label>
                    <input type="text" name="name" class="form-input" required
                        placeholder="Contoh: Platform Ujian Digital 2025"
                        value="{{ old('name') }}">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-textarea" rows="4"
                        placeholder="Deskripsi singkat event...">{{ old('description') }}</textarea>
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Poster Event (Rasio 4:5)</label>
                    <input type="file" name="poster_image" class="form-input" accept="image/jpeg,image/png,image/webp">
                    <small style="color:var(--color-text-tertiary)">Format: JPG, PNG, WEBP. Maks: 2MB.</small>
                    @error('poster_image')<span class="text-danger" style="font-size:12px;display:block">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-input"
                        placeholder="Contoh: Sejarah Islam, Penyisihan"
                        value="{{ old('category') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai <span style="color:var(--color-danger)">*</span></label>
                    <input type="date" name="start_date" class="form-input" required value="{{ old('start_date') }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Selesai <span style="color:var(--color-danger)">*</span></label>
                    <input type="date" name="end_date" class="form-input" required value="{{ old('end_date') }}">
                </div>

                {{-- Sistem Penilaian --}}
                <div class="form-group" style="grid-column:1/-1">
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:12px;">Sistem Penilaian <span style="color:var(--color-danger)">*</span></label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        <label style="cursor:pointer;">
                            <input type="radio" name="scoring_system" value="qualification" {{ old('scoring_system','qualification')=='qualification'?'checked':'' }} style="display:none;" class="scoring-radio">
                            <div class="scoring-option {{ old('scoring_system','qualification')=='qualification'?'selected':'' }}" data-val="qualification"
                                 style="padding:16px 20px;border:2px solid {{ old('scoring_system','qualification')=='qualification'?'var(--color-primary)':'var(--color-border)' }};border-radius:14px;background:{{ old('scoring_system','qualification')=='qualification'?'#f0fdf4':'var(--color-surface-hover)' }};transition:.2s;">
                                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                                    <div style="width:36px;height:36px;background:linear-gradient(135deg,#dcfce7,#16a34a);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-filter" style="color:#fff;font-size:15px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight:800;font-size:14px;color:var(--color-text-primary);">Kualifikasi</div>
                                        <div style="font-size:11px;color:var(--color-text-tertiary);">Sistem eliminasi per babak</div>
                                    </div>
                                </div>
                                <p style="font-size:12px;color:var(--color-text-secondary);line-height:1.6;margin:0;">Peserta lolos ke babak berikutnya berdasarkan passing score. Cocok untuk olimpiade berjenjang.</p>
                            </div>
                        </label>
                        <label style="cursor:pointer;">
                            <input type="radio" name="scoring_system" value="point" {{ old('scoring_system','qualification')=='point'?'checked':'' }} style="display:none;" class="scoring-radio">
                            <div class="scoring-option {{ old('scoring_system','qualification')=='point'?'selected':'' }}" data-val="point"
                                 style="padding:16px 20px;border:2px solid {{ old('scoring_system','qualification')=='point'?'var(--color-primary)':'var(--color-border)' }};border-radius:14px;background:{{ old('scoring_system','qualification')=='point'?'#f0fdf4':'var(--color-surface-hover)' }};transition:.2s;">
                                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                                    <div style="width:36px;height:36px;background:linear-gradient(135deg,#fef9c3,#ca8a04);border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas fa-trophy" style="color:#fff;font-size:15px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight:800;font-size:14px;color:var(--color-text-primary);">Akumulasi Poin</div>
                                        <div style="font-size:11px;color:var(--color-text-tertiary);">Klasemen total skor semua babak</div>
                                    </div>
                                </div>
                                <p style="font-size:12px;color:var(--color-text-secondary);line-height:1.6;margin:0;">Skor semua babak dijumlah. Tampil leaderboard realtime seperti klasemen sepak bola.</p>
                            </div>
                        </label>
                    </div>

                    {{-- Bracket Mode Selection (Muncul jika Kualifikasi dipilih) --}}
                    <div id="bracketModeSection" style="margin-top:16px;padding-top:16px;border-top:1px dashed var(--color-border);display:{{ old('scoring_system', 'qualification') == 'qualification' ? 'block' : 'none' }};">
                        <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:12px;">Mode Alur Turnamen <span style="color:var(--color-danger)">*</span></label>
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <label style="cursor:pointer;">
                                <input type="radio" name="bracket_mode" value="full" {{ old('bracket_mode','full')=='full'?'checked':'' }} style="display:none;" class="bracket-radio">
                                <div class="bracket-option {{ old('bracket_mode','full')=='full'?'selected':'' }}" data-val="full"
                                     style="padding:16px 20px;border:2px solid {{ old('bracket_mode','full')=='full'?'var(--color-primary)':'var(--color-border)' }};border-radius:14px;background:{{ old('bracket_mode','full')=='full'?'#f0fdf4':'var(--color-surface-hover)' }};transition:.2s;">
                                    <div style="font-weight:800;font-size:14px;color:var(--color-text-primary);margin-bottom:4px;">Sistem Penuh (6 Babak)</div>
                                    <p style="font-size:12px;color:var(--color-text-secondary);line-height:1.4;margin:0;">Penyisihan → 64 Besar → 32 Besar → Perempat Final → Semifinal → Final.</p>
                                </div>
                            </label>
                            <label style="cursor:pointer;">
                                <input type="radio" name="bracket_mode" value="express" {{ old('bracket_mode')=='express'?'checked':'' }} style="display:none;" class="bracket-radio">
                                <div class="bracket-option {{ old('bracket_mode')=='express'?'selected':'' }}" data-val="express"
                                     style="padding:16px 20px;border:2px solid {{ old('bracket_mode')=='express'?'var(--color-primary)':'var(--color-border)' }};border-radius:14px;background:{{ old('bracket_mode')=='express'?'#f0fdf4':'var(--color-surface-hover)' }};transition:.2s;">
                                    <div style="font-weight:800;font-size:14px;color:var(--color-text-primary);margin-bottom:4px;">Sistem Praktis (3 Babak)</div>
                                    <p style="font-size:12px;color:var(--color-text-secondary);line-height:1.4;margin:0;">Penyisihan → Semifinal → Grand Final. Cocok untuk menyaring cepat.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Leaderboard Visibility --}}
                <div class="form-group" style="grid-column:1/-1">
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;padding:16px;border:1px solid var(--color-border);border-radius:12px;background:var(--color-surface-hover);">
                        <input type="checkbox" name="leaderboard_visible" value="1" {{ old('leaderboard_visible','1')?'checked':'' }}
                               style="width:20px;height:20px;accent-color:var(--color-primary);flex-shrink:0;">
                        <div>
                            <div style="font-weight:700;font-size:14px;"><i class="fas fa-chart-bar" style="color:var(--color-primary);margin-right:6px;"></i> Tampilkan Leaderboard ke Peserta</div>
                            <div style="font-size:12px;color:var(--color-text-tertiary);margin-top:2px;">Peserta bisa melihat klasemen di dashboard mereka</div>
                        </div>
                    </label>
                </div>

                {{-- Anti-Cheat Detector --}}
                <div class="form-group" style="grid-column:1/-1">
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;padding:16px;border:1px solid var(--color-border);border-radius:12px;background:var(--color-surface-hover);">
                        <input type="checkbox" name="anti_cheat_enabled" value="1" {{ old('anti_cheat_enabled','1')?'checked':'' }}
                               style="width:20px;height:20px;accent-color:var(--color-primary);flex-shrink:0;">
                        <div>
                            <div style="font-weight:700;font-size:14px;"><i class="fas fa-shield-halved" style="color:var(--color-primary);margin-right:6px;"></i> Aktifkan Anti-Kecurangan (Anti-Cheat)</div>
                            <div style="font-size:12px;color:var(--color-text-tertiary);margin-top:2px;">Deteksi pindah tab, copy-paste, dan hilangnya fokus layar saat ujian</div>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Simpan Event
                </button>
                <a href="{{ route('organizer.events.index') }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const scoringRadios = document.querySelectorAll('.scoring-radio');
    const bracketSection = document.getElementById('bracketModeSection');

    function toggleBracketSection() {
        const selected = document.querySelector('.scoring-radio:checked');
        if (selected && selected.value === 'qualification') {
            bracketSection.style.display = 'block';
        } else {
            bracketSection.style.display = 'none';
        }
    }

    scoringRadios.forEach(radio => {
        radio.addEventListener('change', toggleBracketSection);
    });

    // Initial check
    toggleBracketSection();

    // Also handle bracket option selection globally (since we removed it from global layout for bracket specifically)
    const bracketRadios = document.querySelectorAll('.bracket-radio');
    function updateBracketCards() {
        bracketRadios.forEach(radio => {
            const card = radio.closest('label').querySelector('.bracket-option');
            if (!card) return;
            if (radio.checked) {
                card.style.borderColor = 'var(--color-primary)';
                card.style.background = '#f0fdf4';
            } else {
                card.style.borderColor = 'var(--color-border)';
                card.style.background = 'var(--color-surface-hover)';
            }
        });
    }

    bracketRadios.forEach(radio => {
        radio.addEventListener('change', updateBracketCards);
        const card = radio.closest('label').querySelector('.bracket-option');
        if (card) {
            card.addEventListener('click', () => {
                if (!radio.checked) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
            card.style.cursor = 'pointer';
        }
    });

    updateBracketCards();
});
</script>
@endpush
