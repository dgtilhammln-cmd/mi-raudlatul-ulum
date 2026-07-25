@extends('layouts.app')

@section('title', 'Kelola Landing Page')
@section('page-title', 'Kelola Foto Animasi Landing Page')

@section('content')
<div class="grid grid-2">
    <!-- Form Upload -->
    <div class="card">
        <h3 class="card-title">Upload Foto Baru</h3>
        <p style="font-size:13px;color:var(--color-text-secondary);margin-bottom:24px;">Pilih foto terbaik untuk ditampilkan di halaman depan. Foto akan otomatis masuk ke grid animasi berjalan.</p>
        
        <form action="{{ route('organizer.landing-images.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Posisi Animasi</label>
                <select name="column_position" class="form-select" required>
                    <option value="left">Kolom Kiri (Animasi Turun)</option>
                    <option value="right">Kolom Kanan (Animasi Naik)</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Pilih File Foto</label>
                <input type="file" name="image" class="form-input" accept="image/*" required style="padding:10px;">
                <small style="color:var(--color-text-tertiary);margin-top:6px;display:block;">Format JPG/PNG/WEBP. Max 2MB. (Rasio 4:5 disarankan)</small>
            </div>
            
            <button type="submit" class="btn btn-primary" style="width:100%;margin-top:8px;">
                <i class="fas fa-upload"></i> Unggah Foto
            </button>
        </form>
    </div>

    <!-- Preview/Manajemen -->
    <div class="card" style="grid-row: span 2;">
        <h3 class="card-title">Daftar Foto Saat Ini</h3>
        
        <div style="display:flex;gap:24px;">
            <!-- Kolom Kiri -->
            <div style="flex:1;">
                <div class="badge badge-info mb-4" style="display:flex;justify-content:center;font-size:10px;"><i class="fas fa-arrow-down" style="margin-right:6px"></i> Kiri (Animasi Turun)</div>
                @foreach($images->where('column_position', 'left') as $img)
                    <div style="position:relative;margin-bottom:16px;border-radius:12px;overflow:hidden;box-shadow:var(--shadow-sm);">
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="Img" style="width:100%;aspect-ratio:4/5;object-fit:cover;display:block;">
                        <form action="{{ route('organizer.landing-images.destroy', $img) }}" method="POST" style="position:absolute;top:8px;right:8px;" data-confirm="Hapus foto ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="padding:4px 8px;border-radius:6px;"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                @endforeach
                @if($images->where('column_position', 'left')->isEmpty())
                    <p class="text-center" style="font-size:12px;color:var(--color-text-tertiary)">Belum ada foto</p>
                @endif
            </div>

            <!-- Kolom Kanan -->
            <div style="flex:1;">
                <div class="badge badge-warning mb-4" style="display:flex;justify-content:center;font-size:10px;"><i class="fas fa-arrow-up" style="margin-right:6px"></i> Kanan (Animasi Naik)</div>
                @foreach($images->where('column_position', 'right') as $img)
                    <div style="position:relative;margin-bottom:16px;border-radius:12px;overflow:hidden;box-shadow:var(--shadow-sm);">
                        <img src="{{ asset('storage/' . $img->image_path) }}" alt="Img" style="width:100%;aspect-ratio:4/5;object-fit:cover;display:block;">
                        <form action="{{ route('organizer.landing-images.destroy', $img) }}" method="POST" style="position:absolute;top:8px;right:8px;" data-confirm="Hapus foto ini?">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" style="padding:4px 8px;border-radius:6px;"><i class="fas fa-trash"></i></button>
                        </form>
                    </div>
                @endforeach
                @if($images->where('column_position', 'right')->isEmpty())
                    <p class="text-center" style="font-size:12px;color:var(--color-text-tertiary)">Belum ada foto</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
