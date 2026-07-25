@extends('layouts.app')
@section('title', 'Instagram Feeds')
@section('page-title', 'Kelola Instagram Feeds')

@section('content')

<div style="background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:20px;padding:28px 36px;margin-bottom:28px;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
    <div style="position:relative;z-index:2;">
        <h1 style="font-size:24px;font-weight:900;color:#fff;margin-bottom:6px;"><i class="fab fa-instagram" style="margin-right:8px;"></i>Instagram Feeds</h1>
        <p style="color:rgba(255,255,255,.7);font-size:13px;">Tampilkan postingan Instagram terbaru di halaman utama. Disarankan 4-6 foto.</p>
    </div>
</div>

<div style="display:flex;gap:24px;flex-wrap:wrap;align-items:flex-start;">

    {{-- Form Upload --}}
    <div class="card" style="flex:1;min-width:320px;">
        <div class="card-header">
            <h3 class="card-title">Tambah Feed</h3>
        </div>
        <form action="{{ route('organizer.web-settings.instagram.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">URL Link Postingan <span class="text-danger">*</span></label>
                <input type="url" name="link_url" class="form-input" required placeholder="https://instagram.com/p/..." autocomplete="off">
            </div>

            <div class="form-group">
                <label class="form-label">Screenshot/Gambar Feed <span class="text-danger">*</span></label>
                <input type="file" name="image" class="form-input" required accept="image/jpeg,image/png,image/webp">
                <div style="font-size:11px;color:var(--color-text-tertiary);margin-top:4px;">Disarankan rasio kotak (1:1) atau vertikal (4:5). Akan dikompres otomatis ke WebP. Maks 3MB.</div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fas fa-upload"></i> Tambahkan Feed</button>
        </form>
    </div>

    {{-- Preview Grid --}}
    <div class="card" style="flex:2;min-width:400px;padding:0;overflow:hidden;">
        <div class="card-header" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border-bottom:1px solid var(--color-border);display:flex;justify-content:space-between;">
            <h3 class="card-title">Feed Aktif ({{ $feeds->count() }})</h3>
        </div>
        <div style="padding:24px;">
            @if($feeds->isEmpty())
            <div style="text-align:center;color:var(--color-text-tertiary);font-size:13px;padding:40px;">Belum ada postingan Instagram.</div>
            @else
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;">
                @foreach($feeds as $feed)
                <div style="position:relative;background:#000;border-radius:12px;overflow:hidden;aspect-ratio:4/5;box-shadow:var(--shadow-md);group">
                    <img src="{{ Storage::url($feed->image_path) }}" style="width:100%;height:100%;object-fit:cover;opacity:.8;transition:.3s;" class="hover-zoom">
                    
                    {{-- Overlay Icon --}}
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;">
                        <i class="fab fa-instagram" style="color:#fff;font-size:32px;opacity:.5;"></i>
                    </div>

                    <form action="{{ route('organizer.web-settings.instagram.destroy', $feed) }}" method="POST" data-confirm="Hapus feed Instagram ini?">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" style="position:absolute;top:8px;right:8px;padding:6px 10px;border-radius:8px;backdrop-filter:blur(4px);background:rgba(220,38,38,.8);border:1px solid rgba(255,255,255,.2);"><i class="fas fa-trash"></i></button>
                    </form>
                    
                    <a href="{{ $feed->link_url }}" target="_blank" style="position:absolute;bottom:12px;left:12px;right:12px;background:rgba(255,255,255,.2);backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.3);color:#fff;text-decoration:none;font-size:11px;font-weight:700;padding:6px 12px;border-radius:100px;text-align:center;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                        <i class="fas fa-external-link-alt" style="margin-right:4px;"></i>Buka Link
                    </a>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.hover-zoom:hover { transform: scale(1.05); opacity: 1 !important; }
</style>

@endsection
