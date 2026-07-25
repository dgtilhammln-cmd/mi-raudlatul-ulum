<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- SEO & Meta Tags -->
    <title>@yield('title', 'MIS Raudlatul Ulum')</title>
    
    <!-- Favicon -->
    @php $siteLogoPath = \App\Models\WebSetting::get('site_logo'); $faviconUrl = $siteLogoPath ? asset('storage/' . $siteLogoPath) : asset('images/logo-uinsa.png'); @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #f8fafc;
            --grad-start: #1db349;
            --grad-end: #a5cf36;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            
            /* Add missing variables for cards in public.blade.php */
            --color-primary: #1db349;
            --color-surface: #ffffff;
            --color-surface-soft: #f8fafc;
            --color-surface-hover: #f1f5f9;
            --color-text-primary: #0f172a;
            --color-text-secondary: #475569;
            --color-text-tertiary: #94a3b8;
            --color-border: #e2e8f0;
            --color-accent-light: rgba(29,179,73,0.08);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }
        
        /* ═══ NAVBAR (Identik dengan Home) ═══ */
        .navbar {
            height: 64px;
            padding: 0 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            background: rgba(248, 250, 252, 0.92);
            backdrop-filter: blur(12px);
            z-index: 100;
            border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .nav-logo { display: flex; align-items: center; text-decoration: none; }
        .nav-logo img { height: 36px; }
        .nav-links { display: flex; gap: 28px; }
        .nav-links a {
            text-decoration: none; color: var(--text-muted);
            font-size: 13px; font-weight: 500; transition: 0.3s;
        }
        .nav-links a:hover { color: var(--grad-start); }
        .btn-login {
            background: linear-gradient(135deg, var(--grad-start), var(--grad-end));
            color: #fff; padding: 9px 22px; border-radius: 100px;
            text-decoration: none; font-size: 13px; font-weight: 600; transition: 0.3s;
            box-shadow: 0 2px 8px rgba(29,179,73,0.2);
        }
        .btn-login:hover { transform: translateY(-1px); box-shadow: 0 6px 16px rgba(29,179,73,0.3); }

        /* Header */
        .page-header {
            background: linear-gradient(135deg, var(--grad-start), var(--grad-end)); padding: 80px 40px; text-align: center; color: #fff;
            position: relative; overflow: hidden;
        }
        .page-header::before {
            content:''; position:absolute; inset:0; opacity:.1;
            background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;
        }
        .page-title { font-size: clamp(28px, 4vw, 42px); font-weight: 900; margin-bottom: 12px; position: relative; z-index: 2; }
        .page-desc { font-size: 16px; color: rgba(255,255,255,.7); max-width: 600px; margin: 0 auto; line-height: 1.6; position: relative; z-index: 2; }

        /* Breadcrumbs */
        .breadcrumb {
            max-width: 1200px; margin: 20px auto; padding: 0 20px;
            font-size: 12px; font-weight: 600; color: var(--text-muted);
            display: flex; gap: 8px; align-items: center;
        }
        .breadcrumb a { text-decoration: none; color: var(--text-dark); transition: 0.2s; }
        .breadcrumb a:hover { color: var(--grad-start); }
        .breadcrumb i { font-size: 10px; color: #cbd5e1; }

        /* Content */
        .content-container { max-width: 1200px; margin: 0 auto 60px; padding: 0 20px; position: relative; z-index: 10; }
        
        /* Utility Classes used by public.blade.php */
        .card {
            background: var(--color-surface);
            border: 1px solid rgba(0,0,0,0.04);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 14px; text-align: left; font-size: 13px; border-bottom: 1px solid rgba(0,0,0,0.04); }
        th { color: var(--color-text-tertiary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 11px; background: var(--color-surface-soft); }
        td { color: var(--color-text-secondary); font-weight: 500; }
        tr:hover td { background: var(--color-surface-soft); }

        .table-wrapper { overflow-x: auto; }

        @media (max-width: 768px) {
            .page-header { padding: 60px 20px; }
            .navbar { padding: 0 20px; }
            .nav-links { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="/" class="nav-logo">
            <img src="{{ $faviconUrl }}" alt="Logo" style="height:36px;object-fit:contain;border-radius:8px;">
        </a>
        <div class="nav-links">
            <a href="/">Beranda</a>
            <a href="#">Panduan</a>
            <a href="#">Ketentuan</a>
            <a href="#">Pusat Bantuan</a>
        </div>
        <div>
            @auth
                <a href="{{ auth()->user()->isOrganizer() || auth()->user()->isAdmin() ? route('organizer.dashboard') : route('peserta.dashboard') }}" class="btn-login">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Login Peserta</a>
            @endauth
        </div>
    </nav>

    @if(View::hasSection('breadcrumb'))
        <div class="breadcrumb">
            @yield('breadcrumb')
        </div>
    @endif

    @unless(View::hasSection('hide-header'))
    @if(View::hasSection('page-title'))
    <div class="page-header">
        <h1 class="page-title">@yield('page-title')</h1>
        <p class="page-desc">@yield('page-desc')</p>
    </div>
    @endif
    @endunless

    <main class="content-container" style="{{ View::hasSection('hide-header') ? 'margin-top: 20px;' : 'margin-top: -40px;' }}">
        @yield('content')
    </main>

    @php
        $footerData = \App\Models\WebSetting::get('footer', [
            'description' => 'Musabaqah Tarikh Islam adalah kompetisi sejarah peradaban Islam tingkat nasional.',
            'phone' => '+62 812-3456-7890',
            'email' => 'panitia@musabaqahtarikhislam.com',
            'address' => 'Jl. A. Yani No.117, Surabaya',
            'socials' => ['instagram' => '', 'youtube' => '', 'tiktok' => '']
        ]);
    @endphp

    <footer class="main-footer">
        <div class="footer-top">
            <div class="footer-brand">
                <div class="footer-logo-box">
                    <img src="{{ $faviconUrl }}" alt="Logo MTI" class="footer-logo">
                </div>
                <p class="footer-desc">{{ $footerData['description'] ?? 'Musabaqah Tarikh Islam adalah kompetisi sejarah peradaban Islam tingkat nasional.' }}</p>
                <div class="footer-socials">
                    @if(!empty($footerData['socials']['instagram']))
                    <a href="{{ $footerData['socials']['instagram'] }}" target="_blank" class="social-btn"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(!empty($footerData['socials']['youtube']))
                    <a href="{{ $footerData['socials']['youtube'] }}" target="_blank" class="social-btn"><i class="fab fa-youtube"></i></a>
                    @endif
                    @if(!empty($footerData['socials']['tiktok']))
                    <a href="{{ $footerData['socials']['tiktok'] }}" target="_blank" class="social-btn"><i class="fab fa-tiktok"></i></a>
                    @endif
                </div>
            </div>
            
            <div class="footer-links">
                <h4>Navigasi</h4>
                <a href="/">Beranda</a>
                <a href="/#tentang">Ketentuan Kompetisi</a>
                <a href="/leaderboard">Leaderboard Publik</a>
                <a href="{{ route('login') }}">Login Peserta</a>
            </div>

            <div class="footer-contact">
                <h4>Hubungi Kami</h4>
                @if(!empty($footerData['phone']))
                <p><i class="fas fa-phone-alt"></i> {{ $footerData['phone'] }}</p>
                @endif
                @if(!empty($footerData['email']))
                <p><i class="fas fa-envelope"></i> {{ $footerData['email'] }}</p>
                @endif
                @if(!empty($footerData['address']))
                <p><i class="fas fa-map-marker-alt"></i> {{ $footerData['address'] }}</p>
                @endif
            </div>
        </div>
        <div class="footer-bottom">
            <div class="copyright">&copy; {{ date('Y') }} MIS Raudlatul Ulum. All rights reserved.</div>
            <div class="watermark">Developed by <a href="https://hvmdigital.id/jasa-pembuatan-website-surabaya-murah" target="_blank" rel="noopener">hvmdigital.id</a></div>
        </div>
    </footer>

    <style>
    /* Footer */
    .main-footer { 
        background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%); 
        color: #fff; padding: 80px 40px 20px; font-family: 'Montserrat', sans-serif; position: relative; overflow: hidden;
        margin-top: 60px;
    }
    .main-footer::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: rgba(255,255,255,0.2);
    }
    .footer-top { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 40px; margin-bottom: 60px; position: relative; z-index: 2; }
    .footer-logo-box { background: #fff; display: inline-block; padding: 8px 16px; border-radius: 12px; margin-bottom: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.1); }
    .footer-logo { height: 48px; object-fit: contain; }
    .footer-desc { color: rgba(255,255,255,.9); font-size: 14px; line-height: 1.8; margin-bottom: 24px; max-width: 320px; }
    .footer-socials { display: flex; gap: 12px; }
    .social-btn {
        width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
        display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; transition: .3s; text-decoration: none;
    }
    .social-btn:hover { background: #fff; color: var(--grad-start); border-color: transparent; transform: translateY(-3px); box-shadow: 0 8px 16px rgba(29,179,73,.3); }
    
    .footer-links h4, .footer-contact h4 { font-size: 16px; font-weight: 800; margin-bottom: 24px; color: #fff; position: relative; display: inline-block; }
    .footer-links h4::after, .footer-contact h4::after {
        content: ''; position: absolute; bottom: -8px; left: 0; width: 24px; height: 3px; border-radius: 2px; background: #fff;
    }
    .footer-links a { display: block; color: rgba(255,255,255,.7); text-decoration: none; margin-bottom: 14px; font-size: 14px; transition: .2s; font-weight: 500; }
    .footer-links a:hover { color: #fff; padding-left: 6px; }
    
    .footer-contact p { display: flex; gap: 14px; align-items: flex-start; color: rgba(255,255,255,.7); font-size: 14px; margin-bottom: 16px; line-height: 1.6; }
    .footer-contact i { color: #fff; margin-top: 4px; font-size: 16px; }

    .footer-bottom {
        max-width: 1100px; margin: 0 auto; padding-top: 24px; border-top: 1px solid rgba(255,255,255,.08);
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; position: relative; z-index: 2;
    }
    .copyright { color: rgba(255,255,255,.8); font-size: 13px; font-weight: 500; }
    .watermark { font-size: 13px; color: rgba(255,255,255,.8); font-weight: 500; }
    .watermark a { color: #fff; text-decoration: none; font-weight: 700; transition: .2s; text-decoration: underline; }
    .watermark a:hover { color: #fff; text-shadow: 0 0 8px rgba(255,255,255,.5); }
    
    @media (max-width: 768px) {
        .footer-top { grid-template-columns: 1fr; gap: 40px; }
        .footer-bottom { flex-direction: column; text-align: center; justify-content: center; }
    }
    </style>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
