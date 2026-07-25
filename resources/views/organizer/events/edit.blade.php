@extends('layouts.app')

@section('title', 'Edit Event')
@section('page-title', 'Edit: ' . $event->name)

@section('content')
<div style="max-width:700px;">
    <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-secondary btn-sm mb-6"><i class="fas fa-arrow-left"></i> Kembali</a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-pencil" style="color:var(--color-accent)"></i> Edit Event</h3>
        </div>
        <form method="POST" action="{{ route('organizer.events.update', $event) }}" enctype="multipart/form-data">
            @csrf @method('PUT')
            <input type="hidden" name="bracket_mode" value="{{ old('bracket_mode', $event->bracket_mode) }}">
            <div class="grid grid-2">
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Nama Event</label>
                    <input type="text" name="name" class="form-input" required value="{{ old('name', $event->name) }}">
                </div>
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-textarea" rows="4">{{ old('description', $event->description) }}</textarea>
                </div>
                
                <div class="form-group" style="grid-column:1/-1">
                    <label class="form-label">Poster Event (Rasio 4:5)</label>
                    @if($event->poster_image)
                        <div style="margin-bottom:8px;">
                            <img src="{{ Storage::url($event->poster_image) }}" alt="Poster" style="max-height:150px;border-radius:8px;">
                        </div>
                    @endif
                    <input type="file" name="poster_image" class="form-input" accept="image/jpeg,image/png,image/webp">
                    <small style="color:var(--color-text-tertiary)">Biarkan kosong jika tidak ingin mengubah poster.</small>
                    @error('poster_image')<span class="text-danger" style="font-size:12px;display:block">{{ $message }}</span>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" class="form-input" value="{{ old('category', $event->category) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['draft','published','ongoing','completed','cancelled'] as $st)
                            <option value="{{ $st }}" {{ old('status', $event->status)==$st?'selected':'' }}>{{ ucfirst($st) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-input" required value="{{ old('start_date', $event->start_date->format('Y-m-d')) }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-input" required value="{{ old('end_date', $event->end_date->format('Y-m-d')) }}">
                </div>

                {{-- Sistem Penilaian --}}
                <div class="form-group" style="grid-column:1/-1">
                    <label style="font-size:12px;font-weight:700;color:var(--color-text-secondary);letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:12px;">Sistem Penilaian <span style="color:var(--color-danger)">*</span></label>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                        @foreach(['qualification' => ['icon'=>'fa-filter','label'=>'Kualifikasi','sub'=>'Sistem eliminasi per babak','color'=>'#16a34a','bg'=>'linear-gradient(135deg,#dcfce7,#16a34a)'], 'point' => ['icon'=>'fa-trophy','label'=>'Akumulasi Poin','sub'=>'Klasemen total skor realtime','color'=>'#ca8a04','bg'=>'linear-gradient(135deg,#fef9c3,#ca8a04)']] as $val => $opt)
                        @php $selected = old('scoring_system', $event->scoring_system) === $val; @endphp
                        <label style="cursor:pointer;">
                            <input type="radio" name="scoring_system" value="{{ $val }}" {{ $selected?'checked':'' }} style="display:none;" class="scoring-radio">
                            <div class="scoring-option" style="padding:16px 20px;border:2px solid {{ $selected?'var(--color-primary)':'var(--color-border)' }};border-radius:14px;background:{{ $selected?'#f0fdf4':'var(--color-surface-hover)' }};transition:.2s;">
                                <div style="display:flex;align-items:center;gap:12px;margin-bottom:6px;">
                                    <div style="width:34px;height:34px;background:{{ $opt['bg'] }};border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                        <i class="fas {{ $opt['icon'] }}" style="color:#fff;font-size:14px;"></i>
                                    </div>
                                    <div>
                                        <div style="font-weight:800;font-size:14px;">{{ $opt['label'] }}</div>
                                        <div style="font-size:11px;color:var(--color-text-tertiary);">{{ $opt['sub'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Leaderboard Visibility --}}
                <div class="form-group" style="grid-column:1/-1">
                    <label style="display:flex;align-items:center;gap:12px;cursor:pointer;padding:16px;border:1px solid var(--color-border);border-radius:12px;background:var(--color-surface-hover);">
                        <input type="checkbox" name="leaderboard_visible" value="1"
                               {{ old('leaderboard_visible', $event->leaderboard_visible) ? 'checked' : '' }}
                               style="width:20px;height:20px;accent-color:var(--color-primary);flex-shrink:0;">
                        <div>
                            <div style="font-weight:700;font-size:14px;"><i class="fas fa-chart-bar" style="color:var(--color-primary);margin-right:6px;"></i> Tampilkan Leaderboard ke Peserta</div>
                            <div style="font-size:12px;color:var(--color-text-tertiary);margin-top:2px;">Peserta bisa melihat klasemen di dashboard mereka</div>
                        </div>
                    </label>
                </div>
            </div>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
                <a href="{{ route('organizer.events.show', $event) }}" class="btn btn-secondary">Batal</a>
            </div>
        </form>

        <div style="margin-top:40px;padding-top:24px;border-top:1px solid #1e1e1e;">
            <h4 style="font-size:12px;color:var(--color-danger);margin-bottom:12px;">Zona Bahaya</h4>
            <form method="POST" action="{{ route('organizer.events.destroy', $event) }}" data-confirm="Yakin hapus event ini? Semua data peserta, soal, dan sesi akan terhapus!">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus Event Ini</button>
            </form>
        </div>
    </div>
</div>
@endsection
