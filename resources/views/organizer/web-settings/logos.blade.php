@extends('layouts.app')
@section('title', 'Partner & Sponsor')
@section('page-title', 'Kelola Partner & Sponsor')

@section('content')

<div style="background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:20px;padding:28px 36px;margin-bottom:28px;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
    <div style="position:relative;z-index:2;">
        <h1 style="font-size:24px;font-weight:900;color:#fff;margin-bottom:6px;">Logo Partner & Sponsor</h1>
        <p style="color:rgba(255,255,255,.7);font-size:13px;">Kelola logo institusi pendidikan dan sponsor yang tampil di halaman utama.</p>
    </div>
</div>

<div style="display:flex;gap:24px;flex-wrap:wrap;align-items:flex-start;">

    {{-- Left Column (Forms) --}}
    <div style="flex:1;min-width:320px;display:flex;flex-direction:column;gap:24px;">

        {{-- Form Upload Logo Utama --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Logo Utama Website</h3>
            </div>
            <div style="padding:16px;text-align:center;">
                @if(isset($siteLogo))
                <div style="background:#f8fafc;border:1px dashed var(--color-border);border-radius:12px;padding:16px;margin-bottom:16px;">
                    <img src="{{ asset('storage/' . $siteLogo) }}" alt="Site Logo" style="max-height:80px;object-fit:contain;">
                </div>
                @endif
                <form action="{{ route('organizer.web-settings.site-logo.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group" style="text-align:left;">
                        <label class="form-label">Upload Logo Baru (.PNG/.ICO)</label>
                        <input type="file" name="site_logo" class="form-input" required accept="image/png,image/x-icon,image/jpeg">
                        <div style="font-size:11px;color:var(--color-text-tertiary);margin-top:4px;">Akan otomatis terganti di Header, Footer, Favicon, dan OpenGraph/SEO. Disarankan menggunakan file <strong>.png</strong> transparan.</div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fas fa-save"></i> Simpan Logo Utama</button>
                </form>
            </div>
        </div>
    <div class="card" style="flex:1;min-width:320px;">
        <div class="card-header">
            <h3 class="card-title">Tambah Logo Baru</h3>
        </div>
        <form action="{{ route('organizer.web-settings.logos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Tipe Logo <span class="text-danger">*</span></label>
                <select name="type" class="form-input" required onchange="toggleUrlField(this.value)">
                    <option value="partner">Partner (Institusi / Kampus / Sekolah)</option>
                    <option value="sponsor">Sponsor (Supported By)</option>
                </select>
                <div style="font-size:11px;color:var(--color-text-tertiary);margin-top:4px;">Partner tampil di marquee kecil. Sponsor tampil di card premium besar.</div>
            </div>

            <div class="form-group">
                <label class="form-label">Nama Instansi / Sponsor <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-input" required placeholder="Contoh: MI Raudlatul Ulum">
            </div>

            <div class="form-group" id="url-group" style="display:none;">
                <label class="form-label">URL Website (Opsional)</label>
                <input type="url" name="url" class="form-input" placeholder="https://...">
                <div style="font-size:11px;color:var(--color-text-tertiary);margin-top:4px;">Link akan dibuka saat logo sponsor diklik.</div>
            </div>

            <div class="form-group">
                <label class="form-label">File Logo (JPG/PNG) <span class="text-danger">*</span></label>
                <input type="file" name="image" class="form-input" required accept="image/jpeg,image/png,image/webp">
                <div style="font-size:11px;color:var(--color-text-tertiary);margin-top:4px;">Sistem akan otomatis mengkompres gambar ke WebP untuk performa maksimal. Maks 2MB.</div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fas fa-upload"></i> Upload Logo</button>
        </form>
    </div>
    </div>

    {{-- Daftar Logo --}}
    <div style="flex:2;min-width:400px;display:flex;flex-direction:column;gap:24px;">

        {{-- Sponsors --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div class="card-header" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border-bottom:1px solid var(--color-border);">
                <h3 class="card-title"><i class="fas fa-gem" style="color:var(--color-primary);margin-right:6px;"></i>Daftar Sponsor (Supported By)</h3>
            </div>
            <div style="padding:24px;">
                @if($sponsors->isEmpty())
                <div style="text-align:center;color:var(--color-text-tertiary);font-size:13px;padding:20px;">Belum ada logo sponsor.</div>
                @else
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:16px;">
                    @foreach($sponsors as $logo)
                    <div style="position:relative;background:#fff;border:1px solid var(--color-border);border-radius:12px;padding:16px;text-align:center;box-shadow:var(--shadow-sm);">
                        <img src="{{ Storage::url($logo->image_path) }}" style="max-width:100%;max-height:60px;object-fit:contain;margin-bottom:8px;">
                        <div style="font-size:11px;font-weight:700;color:var(--color-text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $logo->name }}</div>
                        <form action="{{ route('organizer.web-settings.logos.destroy', $logo) }}" method="POST" data-confirm="Hapus logo sponsor ini?">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" style="position:absolute;top:-8px;right:-8px;width:24px;height:24px;padding:0;border-radius:50%;box-shadow:0 2px 4px rgba(220,38,38,.3);"><i class="fas fa-times" style="font-size:10px;"></i></button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Partners --}}
        <div class="card" style="padding:0;overflow:hidden;">
            <div class="card-header" style="background:linear-gradient(135deg,#f8fafc,#f1f5f9);border-bottom:1px solid var(--color-border);">
                <h3 class="card-title"><i class="fas fa-handshake" style="color:var(--color-primary);margin-right:6px;"></i>Daftar Partner Institusi</h3>
            </div>
            <div style="padding:24px;">
                @if($partners->isEmpty())
                <div style="text-align:center;color:var(--color-text-tertiary);font-size:13px;padding:20px;">Belum ada logo partner.</div>
                @else
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(100px,1fr));gap:12px;">
                    @foreach($partners as $logo)
                    <div style="position:relative;background:#fff;border:1px solid var(--color-border);border-radius:8px;padding:12px;text-align:center;">
                        <img src="{{ Storage::url($logo->image_path) }}" style="max-width:100%;max-height:40px;object-fit:contain;">
                        <form action="{{ route('organizer.web-settings.logos.destroy', $logo) }}" method="POST" data-confirm="Hapus logo partner ini?">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm" style="position:absolute;top:-8px;right:-8px;width:20px;height:20px;padding:0;border-radius:50%;"><i class="fas fa-times" style="font-size:9px;"></i></button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
function toggleUrlField(type) {
    document.getElementById('url-group').style.display = type === 'sponsor' ? 'block' : 'none';
}
</script>

@endsection
