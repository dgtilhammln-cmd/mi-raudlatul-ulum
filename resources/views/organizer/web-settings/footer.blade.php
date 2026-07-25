@extends('layouts.app')
@section('title', 'Footer & Kontak')
@section('page-title', 'Kelola Footer Web')

@section('content')

<div style="background:linear-gradient(135deg,var(--grad-start),var(--grad-end));border-radius:20px;padding:28px 36px;margin-bottom:28px;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;background:rgba(255,255,255,.06);border-radius:50%;"></div>
    <div style="position:relative;z-index:2;">
        <h1 style="font-size:24px;font-weight:900;color:#fff;margin-bottom:6px;"><i class="fas fa-shoe-prints" style="margin-right:8px;"></i>Footer & Kontak</h1>
        <p style="color:rgba(255,255,255,.7);font-size:13px;">Kelola informasi yang tampil di bagian paling bawah landing page.</p>
    </div>
</div>

<div class="card" style="max-width:800px;margin:0 auto;">
    <div class="card-header">
        <h3 class="card-title">Pengaturan Footer</h3>
    </div>
    <div style="padding:24px;">
        <form action="{{ route('organizer.web-settings.footer.update') }}" method="POST">
            @csrf

            <h4 style="font-size:14px;font-weight:800;color:var(--color-primary);margin-bottom:16px;border-bottom:1px solid var(--color-border);padding-bottom:8px;">Informasi Utama</h4>
            
            <div class="form-group">
                <label class="form-label">Deskripsi Singkat Acara</label>
                <textarea name="description" class="form-input" rows="3" required placeholder="Platform Ujian Digital adalah...">{{ $footer['description'] ?? '' }}</textarea>
                <div style="font-size:11px;color:var(--color-text-tertiary);margin-top:4px;">Tampil di bawah logo MI Raudlatul Ulum di footer kiri.</div>
            </div>

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                <div class="form-group">
                    <label class="form-label">Nomor WhatsApp / Telp</label>
                    <input type="text" name="phone" class="form-input" value="{{ $footer['phone'] ?? '' }}" placeholder="+62 812...">
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ $footer['email'] ?? '' }}" placeholder="email@contoh.com">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Alamat Lengkap</label>
                <input type="text" name="address" class="form-input" value="{{ $footer['address'] ?? '' }}" placeholder="Jl. Ahmad Yani No.117...">
            </div>

            <h4 style="font-size:14px;font-weight:800;color:var(--color-primary);margin:32px 0 16px;border-bottom:1px solid var(--color-border);padding-bottom:8px;">Sosial Media Link</h4>

            <div class="form-group">
                <label class="form-label"><i class="fab fa-instagram" style="color:#e1306c;margin-right:6px;"></i>Link Instagram</label>
                <input type="url" name="socials[instagram]" class="form-input" value="{{ $footer['socials']['instagram'] ?? '' }}" placeholder="https://instagram.com/...">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fab fa-youtube" style="color:#ff0000;margin-right:6px;"></i>Link YouTube</label>
                <input type="url" name="socials[youtube]" class="form-input" value="{{ $footer['socials']['youtube'] ?? '' }}" placeholder="https://youtube.com/...">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="fab fa-tiktok" style="color:#000;margin-right:6px;"></i>Link TikTok</label>
                <input type="url" name="socials[tiktok]" class="form-input" value="{{ $footer['socials']['tiktok'] ?? '' }}" placeholder="https://tiktok.com/...">
            </div>

            <div style="margin-top:32px;display:flex;justify-content:flex-end;">
                <button type="submit" class="btn btn-primary" style="padding:12px 32px;font-weight:700;"><i class="fas fa-save"></i> Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>

@endsection
