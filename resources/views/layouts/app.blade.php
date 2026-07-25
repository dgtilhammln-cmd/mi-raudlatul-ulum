<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php $siteLogoPath = \App\Models\WebSetting::get('site_logo'); $faviconUrl = $siteLogoPath ? asset('storage/' . $siteLogoPath) : asset('images/logo-uinsa.png'); @endphp
    <link rel="icon" type="image/png" href="{{ $faviconUrl }}">
    <title>@yield('title', 'MIS Raudlatul Ulum</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" rel="stylesheet">
    <style>
        /* NProgress Customization */
        #nprogress .bar {
            background: #1db349 !important;
            height: 4px !important;
        }
        #nprogress .peg {
            box-shadow: 0 0 10px #1db349, 0 0 5px #1db349 !important;
        }
        #nprogress .spinner-icon {
            border-top-color: #1db349 !important;
            border-left-color: #1db349 !important;
        }
        :root {
            --font-primary: 'Montserrat', sans-serif;
            --grad-start: #1db349;
            --grad-end: #a5cf36;
            --color-surface: #ffffff;
            --color-surface-soft: #f8fafc;
            --color-text-primary: #0f172a;
            --color-text-secondary: #475569;
            --color-text-tertiary: #94a3b8;
            --color-accent: #1db349;
            --color-primary: #1db349;
            --color-accent-light: rgba(29,179,73,0.08);
            --color-success: #10b981;
            --color-danger: #ef4444;
            --color-warning: #f59e0b;
            --color-info: #0ea5e9;
            --color-border: #e2e8f0;
            --color-surface-hover: #f1f5f9;
            --color-surface-card: #ffffff;
            --radius-sm: 8px; --radius-md: 12px; --radius-lg: 16px; --radius-xl: 20px;
            --shadow-sm: 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.05);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.06);
            --transition: 250ms ease;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: var(--font-primary);
            font-weight: 400;
            font-size: 14px;
            line-height: 1.5;
            background: var(--color-surface-soft);
            color: var(--color-text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }
        a { color: var(--color-accent); text-decoration: none; transition: var(--transition); }
        a:hover { color: #16943d; }

        /* ═══ LAYOUT ═══ */
        .app-container { display: flex; min-height: 100vh; }

        .sidebar {
            width: 260px;
            background: var(--color-surface);
            border-right: 1px solid rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; height: 100vh; z-index: 50;
            box-shadow: var(--shadow-sm);
        }
        .sidebar-brand {
            padding: 20px 24px;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .sidebar-brand img { height: 32px; }
        .sidebar-brand-text h2 { font-size: 14px; font-weight: 700; color: var(--color-text-primary); letter-spacing: -0.3px; }
        .sidebar-brand-text small { display: block; font-size: 10px; color: var(--color-text-tertiary); font-weight: 500; }

        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; display: flex; flex-direction: column; gap: 2px; }
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 14px; color: var(--color-text-secondary);
            font-size: 13px; font-weight: 500; border-radius: var(--radius-md); transition: var(--transition);
        }
        .nav-item:hover { background: var(--color-surface-soft); color: var(--color-text-primary); }
        .nav-item.active {
            background: linear-gradient(135deg, var(--grad-start), var(--grad-end));
            color: #fff; font-weight: 600;
            box-shadow: 0 2px 8px rgba(29,179,73,0.2);
        }
        .nav-item i { width: 18px; text-align: center; font-size: 14px; }

        .sidebar-footer { padding: 16px 12px; border-top: 1px solid rgba(0,0,0,0.05); }
        .btn-logout {
            width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;
            padding: 10px; background: rgba(239,68,68,0.06); color: var(--color-danger);
            border: none; border-radius: var(--radius-md); font-family: var(--font-primary);
            font-weight: 600; font-size: 13px; cursor: pointer; transition: var(--transition);
        }
        .btn-logout:hover { background: var(--color-danger); color: #fff; }

        .main-content { flex: 1; margin-left: 260px; display: flex; flex-direction: column; min-height: 100vh; }

        .topbar {
            height: 56px;
            background: rgba(255,255,255,0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0,0,0,0.04);
            padding: 0 28px;
            display: flex; justify-content: space-between; align-items: center;
            position: sticky; top: 0; z-index: 40;
        }
        .topbar-title { font-size: 16px; font-weight: 700; letter-spacing: -0.3px; }
        .user-badge {
            display: flex; align-items: center; gap: 8px;
            background: var(--color-surface-soft); padding: 6px 14px;
            border-radius: 100px; font-size: 12px; font-weight: 600; color: var(--color-text-secondary);
            border: 1px solid rgba(0,0,0,0.04);
        }

        .content-area { flex: 1; padding: 28px; max-width: 1100px; margin: 0 auto; width: 100%; }

        /* ═══ COMPONENTS ═══ */
        .btn {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 9px 18px; border-radius: 100px; font-family: var(--font-primary);
            font-weight: 600; font-size: 13px; cursor: pointer; border: 1px solid transparent;
            transition: var(--transition); text-decoration: none;
        }
        .btn-primary { background: linear-gradient(135deg, var(--grad-start), var(--grad-end)); color: #fff; }
        .btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(29,179,73,0.25); filter: brightness(1.05); }
        .btn-secondary { background: var(--color-surface-soft); color: var(--color-text-primary); border: 1px solid rgba(0,0,0,0.08); }
        .btn-secondary:hover { border-color: var(--color-text-primary); }
        .btn-danger { background: var(--color-danger); color: #fff; }
        .btn-success { background: var(--color-success); color: #fff; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }

        .card {
            background: var(--color-surface);
            border: 1px solid rgba(0,0,0,0.04);
            border-radius: var(--radius-xl);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .card:hover { box-shadow: var(--shadow-md); }
        .card-header {
            display: flex; justify-content: space-between; align-items: center;
            margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px solid rgba(0,0,0,0.04);
        }
        .card-title { font-size: 15px; font-weight: 700; letter-spacing: -0.3px; }

        .stat-card {
            background: var(--color-surface); border: 1px solid rgba(0,0,0,0.04);
            border-radius: var(--radius-xl); padding: 20px; box-shadow: var(--shadow-sm);
            position: relative; overflow: hidden;
        }
        .stat-card::after {
            content: ''; position: absolute; top: 0; right: 0; width: 48px; height: 48px;
            background: var(--color-accent-light); border-radius: 0 0 0 100%;
        }
        .stat-value { font-size: 28px; font-weight: 700; display: block; line-height: 1; }
        .stat-label { font-size: 11px; color: var(--color-text-tertiary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-top: 6px; display: block; }

        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 12px; font-weight: 600; color: var(--color-text-primary); margin-bottom: 6px; }
        .form-input, .form-select, .form-textarea {
            width: 100%; padding: 10px 14px; background: var(--color-surface-soft);
            border: 1.5px solid rgba(0,0,0,0.06); border-radius: var(--radius-md);
            color: var(--color-text-primary); font-family: var(--font-primary);
            font-size: 13px; font-weight: 400; transition: var(--transition);
        }
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            outline: none; border-color: var(--color-accent);
            box-shadow: 0 0 0 3px rgba(29,179,73,0.08);
        }
        .form-textarea { resize: vertical; min-height: 80px; }

        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 14px; text-align: left; font-size: 13px; border-bottom: 1px solid rgba(0,0,0,0.04); }
        th { color: var(--color-text-tertiary); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 11px; background: var(--color-surface-soft); }
        td { color: var(--color-text-secondary); font-weight: 500; }
        tr:hover td { background: var(--color-surface-soft); }

        .badge { display: inline-flex; padding: 3px 10px; border-radius: 100px; font-size: 11px; font-weight: 700; }
        .badge-success { background: #dcfce7; color: #16a34a; }
        .badge-warning { background: #fef3c7; color: #d97706; }
        .badge-danger { background: #fee2e2; color: #dc2626; }
        .badge-info { background: #e0f2fe; color: #0284c7; }
        .badge-default { background: #f1f5f9; color: #475569; }

        .alert { padding: 14px 18px; border-radius: var(--radius-lg); font-size: 13px; font-weight: 500; margin-bottom: 20px; display: flex; align-items: center; gap: 10px; }
        .alert-success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; }
        .alert-danger { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; }
        .alert-warning { background: #fffbeb; border: 1px solid #fde68a; color: #d97706; }

        .grid { display: grid; gap: 20px; }
        .grid-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-4 { grid-template-columns: repeat(4, 1fr); }

        .empty-state { text-align: center; padding: 40px 20px; color: var(--color-text-tertiary); }
        .empty-state i { font-size: 40px; margin-bottom: 12px; display: block; color: var(--color-accent-light); }
        .empty-state p { font-size: 13px; font-weight: 500; color: var(--color-text-secondary); margin-bottom: 16px; }

        .watermark { text-align: center; padding: 16px; font-size: 11px; font-weight: 400; color: var(--color-text-tertiary); border-top: 1px solid rgba(0,0,0,0.04); margin-top: auto; }
        .watermark a { color: var(--color-accent); }

        .mt-2{margin-top:8px}.mt-4{margin-top:16px}.mt-6{margin-top:24px}
        .mb-4{margin-bottom:16px}.mb-6{margin-bottom:24px}
        .text-center{text-align:center}.text-right{text-align:right}
        .flex{display:flex}.items-center{align-items:center}.justify-between{justify-content:space-between}
        .gap-2{gap:8px}.gap-4{gap:16px}
        
        .dashboard-grid { display: grid; grid-template-columns: 1fr; gap: 16px; align-items: start; }
        @media (min-width: 769px) {
            .dashboard-grid.has-sidebar { grid-template-columns: 1fr 380px; }
        }

        /* Bottom Nav Mobile (Premium Luxury) */
        .bottom-nav {
            display: none;
            position: fixed;
            bottom: 0; left: 0; right: 0;
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-top: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 -4px 20px rgba(0,0,0,0.03);
            z-index: 100;
            padding-bottom: env(safe-area-inset-bottom);
        }
        .bottom-nav-inner {
            display: flex;
            justify-content: space-around;
            align-items: center;
            height: 65px;
        }
        .bnav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex: 1;
            height: 100%;
            text-decoration: none;
            color: var(--color-text-tertiary);
            transition: 0.3s;
            position: relative;
        }
        .bnav-item i { font-size: 20px; margin-bottom: 4px; transition: 0.3s; }
        .bnav-item span { font-size: 10px; font-weight: 600; }
        .bnav-item.active { color: var(--color-primary); }
        .bnav-item.active i { transform: translateY(-2px); }
        .bnav-item.active::after {
            content: ''; position: absolute; top: 0; left: 50%;
            transform: translateX(-50%); width: 30px; height: 3px;
            background: linear-gradient(90deg, var(--grad-start), var(--grad-end));
            border-radius: 0 0 4px 4px;
        }

        @media (max-width: 768px) {
            .grid-2,.grid-3,.grid-4{grid-template-columns:1fr; gap:12px;}
            .sidebar{display:none;} /* Completely hide sidebar on mobile */
            .main-content{margin-left:0; padding-bottom: 80px;} /* Extra padding for bottom nav */
            .bottom-nav { display: block; } /* Show bottom nav on mobile */
            
            /* Hide hamburger menu button on topbar if any */
            .topbar .menu-btn { display: none !important; }
            
            /* Notification Dropdown Mobile Fix */
            #notif-panel {
                width: 300px !important;
                right: -60px !important;
            }
            
            /* Premium Mobile Adjustments */
            .content-area { padding: 16px; }
            .card { padding: 16px; border-radius: var(--radius-lg); }
            .card-header { padding-bottom: 12px; margin-bottom: 12px; flex-direction: column; align-items: flex-start; gap: 8px; }
            .card-title { font-size: 16px; }
            .topbar { padding: 0 16px; height: 50px; }
            .topbar-title { font-size: 14px; }
            .stat-card { padding: 16px; border-radius: var(--radius-lg); }
            .stat-value { font-size: 24px; }
            
            /* Mobile Helpers for Inline Styles */
            .mobile-stack { flex-direction: column !important; align-items: flex-start !important; }
            .mobile-wrap { flex-wrap: wrap !important; }
            .mobile-p-4 { padding: 16px !important; }
            .mobile-text-sm { font-size: 12px !important; }
            .mobile-hide { display: none !important; }
            .mobile-center { text-align: center !important; justify-content: center !important; }
            .mobile-gap-2 { gap: 8px !important; }
            
            /* Mobile Buttons */
            .btn { padding: 8px 16px; font-size: 12px; }
            .table-wrapper { border-radius: var(--radius-sm); border: none; }
            th, td { padding: 10px 12px; font-size: 12px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="app-container">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-brand">
                @php $sidebarLogo = \App\Models\WebSetting::get('site_logo'); @endphp
                <img src="{{ $sidebarLogo ? asset('storage/' . $sidebarLogo) : asset('images/logo-uinsa.png') }}" alt="Logo" style="height:36px;object-fit:contain;border-radius:8px;">
                <div class="sidebar-brand-text">
                    <h2>MTI Dashboard</h2>
                    <small>Panel Pengelolaan</small>
                </div>
            </div>
            <nav class="sidebar-nav">
                @if(auth()->check() && auth()->user()->isOrganizer())
                    <a href="{{ route('organizer.dashboard') }}" class="nav-item {{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="{{ route('organizer.events.index') }}" class="nav-item {{ request()->routeIs('organizer.events.*') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i> Event & Babak
                    </a>
                    <a href="{{ route('organizer.statistik.index') }}" class="nav-item {{ request()->routeIs('organizer.statistik.*') ? 'active' : '' }}">
                        <i class="fas fa-chart-line"></i> Statistik
                    </a>
                    <a href="{{ route('organizer.certificates.index') }}" class="nav-item {{ request()->routeIs('organizer.certificates.*') ? 'active' : '' }}">
                        <i class="fas fa-award"></i> E-Sertifikat
                    </a>
                    <a href="{{ route('organizer.leaderboard') }}" class="nav-item {{ request()->routeIs('organizer.leaderboard') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i> Leaderboard
                    </a>
                    <div class="nav-group-title" style="font-size:11px;font-weight:800;color:var(--color-text-tertiary);text-transform:uppercase;letter-spacing:1px;margin:16px 20px 8px;">Kelola Website</div>
                    <a href="{{ route('organizer.landing-images.index') }}" class="nav-item {{ request()->routeIs('organizer.landing-images.*') ? 'active' : '' }}">
                        <i class="fas fa-photo-video"></i> Foto Slideshow
                    </a>
                    <a href="{{ route('organizer.web-settings.hero') }}" class="nav-item {{ request()->routeIs('organizer.web-settings.hero') ? 'active' : '' }}">
                        <i class="fas fa-paint-brush"></i> Teks Hero
                    </a>
                    
                    {{-- Reports Menu --}}
                    <div style="margin: 20px 0 8px 16px; font-size: 11px; font-weight: 800; color: var(--color-text-tertiary); text-transform: uppercase; letter-spacing: 1px;">Laporan</div>
                    <a href="{{ route('organizer.tickets.index') }}" class="nav-item {{ request()->routeIs('organizer.tickets.*') ? 'active' : '' }}">
                        <i class="fas fa-headset"></i> Kendala Peserta
                        @php $openTickets = \App\Models\ParticipantTicket::where('status', 'open')->count(); @endphp
                        @if($openTickets > 0)
                            <span style="margin-left:auto;font-size:10px;background:#ef4444;color:#fff;padding:2px 6px;border-radius:10px;font-weight:800;">{{ $openTickets }}</span>
                        @endif
                    </a>
                    
                    <a href="{{ route('organizer.artikel.index') }}" class="nav-item {{ request()->routeIs('organizer.artikel.*') ? 'active' : '' }}">
                        <i class="fas fa-newspaper"></i> Artikel <span style="margin-left:auto;font-size:9px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:2px 6px;border-radius:4px;font-weight:800;">PRO</span>
                    </a>
                    <a href="{{ route('organizer.anggota.index') }}" class="nav-item {{ request()->routeIs('organizer.anggota.*') ? 'active' : '' }}">
                        <i class="fas fa-users-cog"></i> Anggota <span style="margin-left:auto;font-size:9px;background:linear-gradient(135deg,#f59e0b,#d97706);color:#fff;padding:2px 6px;border-radius:4px;font-weight:800;">PRO</span>
                    </a>
                    <a href="{{ route('organizer.web-settings.logos') }}" class="nav-item {{ request()->routeIs('organizer.web-settings.logos') ? 'active' : '' }}">
                        <i class="fas fa-handshake"></i> Partner & Sponsor
                    </a>
                    <a href="{{ route('organizer.web-settings.instagram') }}" class="nav-item {{ request()->routeIs('organizer.web-settings.instagram') ? 'active' : '' }}">
                        <i class="fab fa-instagram"></i> Instagram Feeds
                    </a>
                    <a href="{{ route('organizer.web-settings.footer') }}" class="nav-item {{ request()->routeIs('organizer.web-settings.footer') ? 'active' : '' }}">
                        <i class="fas fa-shoe-prints"></i> Footer & Kontak
                    </a>
                @elseif(auth()->check() && auth()->user()->isParticipant())
                    <a href="{{ route('peserta.dashboard') }}" class="nav-item {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> Dashboard Peserta
                    </a>
                    <a href="{{ route('peserta.events') }}" class="nav-item {{ request()->routeIs('peserta.events') ? 'active' : '' }}">
                        <i class="fas fa-certificate"></i> Event & Sertifikat
                    </a>
                    <a href="{{ route('peserta.leaderboard') }}" class="nav-item {{ request()->routeIs('peserta.leaderboard*') ? 'active' : '' }}">
                        <i class="fas fa-trophy"></i> Leaderboard
                    </a>
                @endif
            </nav>
            <div class="sidebar-footer">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Keluar</button>
                </form>
            </div>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div class="topbar-title">@yield('page-title', 'Dashboard')</div>
                <div style="display:flex;align-items:center;gap:16px;">
                    {{-- Notification Bell --}}
                    <div style="position:relative;" id="notif-wrapper">
                        <button id="notif-bell" onclick="toggleNotifPanel()" style="background:var(--color-surface-soft);border:1px solid var(--color-border);border-radius:14px;width:40px;height:40px;display:flex;align-items:center;justify-content:center;cursor:pointer;position:relative;transition:.2s;" title="Notifikasi">
                            <i class="fas fa-bell" style="font-size:15px;color:var(--color-text-secondary);"></i>
                            <span id="notif-badge" style="display:none;position:absolute;top:6px;right:6px;width:8px;height:8px;background:#ef4444;border-radius:50%;border:1.5px solid #fff;"></span>
                        </button>

                        {{-- Dropdown Panel --}}
                        <div id="notif-panel" style="display:none;position:absolute;top:calc(100% + 12px);right:0;width:360px;background:#fff;border-radius:20px;box-shadow:0 24px 48px rgba(0,0,0,.14),0 0 0 1px rgba(0,0,0,.06);z-index:9000;overflow:hidden;">
                            <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--color-border);">
                                <h4 style="font-size:15px;font-weight:800;color:var(--color-text-primary);margin:0;"><i class="fas fa-bell" style="color:var(--color-primary);margin-right:8px;"></i>Notifikasi</h4>
                                <button onclick="markAllRead()" style="font-size:12px;font-weight:700;color:var(--color-primary);background:none;border:none;cursor:pointer;padding:4px 8px;border-radius:8px;" title="Tandai semua dibaca">Baca Semua</button>
                            </div>
                            <div id="notif-list" style="max-height:380px;overflow-y:auto;"></div>
                            <div style="padding:12px 20px;border-top:1px solid var(--color-border);text-align:center;">
                                <span style="font-size:11px;color:var(--color-text-tertiary);">Hanya 20 notifikasi terbaru yang ditampilkan</span>
                            </div>
                        </div>
                    </div>

                    <div class="user-badge">
                        <i class="fas fa-user-circle" style="color:var(--color-accent);font-size:16px;"></i>
                        {{ auth()->user()->name }}
                    </div>
                </div>
            </header>
            <div class="content-area">
                @if(session('success'))
                    <div class="alert alert-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger"><i class="fas fa-exclamation-triangle"></i> {{ $errors->first() }}</div>
                @endif
                @yield('content')
            </div>
            <div class="watermark">Developed by <a href="https://hvmdigital.id/jasa-pembuatan-website-surabaya-murah" target="_blank" rel="noopener">hvmdigital.id</a></div>
        </main>
    </div>

    {{-- HUBUNGI KAMI WIDGET (Hanya untuk Peserta) --}}
    @if(auth()->check() && auth()->user()->isParticipant())
    <div id="contact-widget-container" style="position:fixed;bottom:30px;right:30px;z-index:9999;font-family:'Montserrat',sans-serif;">
        {{-- Floating Button --}}
        <button id="contact-fab" onclick="toggleContactForm()" style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--grad-start),var(--grad-end));color:#fff;border:none;box-shadow:0 8px 24px rgba(29,179,73,.3);cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .3s cubic-bezier(.4,0,.2,1);position:relative;z-index:10;">
            <i class="fas fa-headset" style="font-size:24px;transition:.3s;" id="contact-icon"></i>
            <span style="position:absolute;top:-4px;right:-4px;width:14px;height:14px;background:#ef4444;border-radius:50%;border:2px solid #fff;animation:pulse-lp 2s infinite;"></span>
        </button>

        {{-- Contact Form Form / Modal --}}
        <div id="contact-form-card" style="position:absolute;bottom:80px;right:0;width:340px;background:#fff;border-radius:20px;box-shadow:0 12px 40px rgba(0,0,0,.15);border:1px solid var(--color-border);overflow:hidden;transform-origin:bottom right;transform:scale(0.8);opacity:0;pointer-events:none;transition:all .3s cubic-bezier(.4,0,.2,1);">
            <div style="background:linear-gradient(135deg,var(--text-dark),#1e293b);padding:20px;color:#fff;position:relative;">
                <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;background:rgba(255,255,255,.05);border-radius:50%;"></div>
                <h4 style="font-size:16px;font-weight:800;margin-bottom:4px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-headset" style="color:var(--grad-start);"></i> Butuh Bantuan?
                </h4>
                <p style="font-size:12px;color:rgba(255,255,255,.7);margin:0;line-height:1.4;">Kirimkan kendala atau pertanyaan Anda, tim kami siap membantu.</p>
            </div>
            <div style="padding:20px;">
                <form id="contact-form" action="{{ route('peserta.tickets.store') }}" method="POST">
                    @csrf
                    <div style="margin-bottom:12px;">
                        <label style="display:block;font-size:11px;font-weight:700;color:var(--color-text-secondary);margin-bottom:4px;">Nama Lengkap</label>
                        <input type="text" name="name" required style="width:100%;padding:10px 14px;border:1px solid var(--color-border);border-radius:10px;font-size:13px;font-family:inherit;" placeholder="Masukkan nama" value="{{ auth()->user()->name }}">
                    </div>
                    <div style="margin-bottom:12px;">
                        <label style="display:block;font-size:11px;font-weight:700;color:var(--color-text-secondary);margin-bottom:4px;">Nomor WhatsApp</label>
                        <input type="text" name="wa_number" required style="width:100%;padding:10px 14px;border:1px solid var(--color-border);border-radius:10px;font-size:13px;font-family:inherit;" placeholder="08...">
                    </div>
                    <div style="margin-bottom:12px;">
                        <label style="display:block;font-size:11px;font-weight:700;color:var(--color-text-secondary);margin-bottom:4px;">Kebutuhan</label>
                        <select name="needs" required style="width:100%;padding:10px 14px;border:1px solid var(--color-border);border-radius:10px;font-size:13px;font-family:inherit;background:#fff;">
                            <option value="Kendala Login / Ujian">Kendala Login / Ujian</option>
                            <option value="Pertanyaan Seputar Event">Pertanyaan Seputar Event</option>
                            <option value="Perubahan Data">Perubahan Data</option>
                            <option value="Lainnya">Lainnya</option>
                        </select>
                    </div>
                    <div style="margin-bottom:16px;">
                        <label style="display:block;font-size:11px;font-weight:700;color:var(--color-text-secondary);margin-bottom:4px;">Pesan / Kendala</label>
                        <textarea name="message" required rows="3" style="width:100%;padding:10px 14px;border:1px solid var(--color-border);border-radius:10px;font-size:13px;font-family:inherit;resize:none;" placeholder="Tuliskan pesan Anda secara detail..."></textarea>
                    </div>
                    <button type="submit" id="btn-submit-ticket" style="width:100%;padding:12px;background:var(--grad-start);color:#fff;border:none;border-radius:10px;font-weight:700;font-size:13px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;transition:.2s;">
                        <i class="fab fa-whatsapp" style="font-size:16px;"></i> Kirim Pesan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
    @keyframes pulse-lp { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.3)} }
    #contact-widget-container.active #contact-form-card { transform:scale(1);opacity:1;pointer-events:auto; }
    #contact-widget-container.active #contact-fab { transform:rotate(90deg);background:var(--color-surface);color:var(--color-text-primary);box-shadow:0 4px 12px rgba(0,0,0,.1); }
    </style>

    <script>
    function toggleContactForm() {
        const container = document.getElementById('contact-widget-container');
        const card = document.getElementById('contact-form-card');
        const fab = document.getElementById('contact-fab');
        const icon = document.getElementById('contact-icon');
        container.classList.toggle('active');
        
        if (container.classList.contains('active')) {
            icon.className = 'fas fa-times';
            card.style.opacity = '1';
            card.style.transform = 'scale(1)';
            card.style.pointerEvents = 'auto';
            fab.style.transform = 'rotate(90deg)';
        } else {
            icon.className = 'fas fa-headset';
            card.style.opacity = '0';
            card.style.transform = 'scale(0.8)';
            card.style.pointerEvents = 'none';
            fab.style.transform = 'rotate(0deg)';
        }
    }

    document.getElementById('contact-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const btn = document.getElementById('btn-submit-ticket');
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
        btn.disabled = true;

        fetch(this.action, {
            method: 'POST',
            body: new FormData(this),
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            btn.innerHTML = '<i class="fas fa-check"></i> Terkirim';
            setTimeout(() => {
                toggleContactForm();
                this.reset();
                btn.innerHTML = originalText;
                btn.disabled = false;
                if (typeof showAlert === 'function') {
                    showAlert('Laporan Terkirim! <i class="fas fa-paper-plane" style="color:var(--grad-start);"></i>', 'Laporan Anda sudah masuk dan akan segera dihubungi oleh pihak penyelenggara.', 'success');
                } else {
                    alert('Laporan Anda sudah masuk dan akan segera dihubungi oleh pihak penyelenggara.');
                }
            }, 1000);
        })
        .catch(err => {
            alert('Terjadi kesalahan. Silakan coba lagi.');
            btn.innerHTML = originalText;
            btn.disabled = false;
        });
    });
    </script>
    @endif

    @stack('scripts')
<script>
// ─── Scoring System Selector (create/edit event forms) ───
document.addEventListener('DOMContentLoaded', function () {
    const radios = document.querySelectorAll('.scoring-radio');
    if (!radios.length) return;

    function updateCards() {
        radios.forEach(radio => {
            const card = radio.closest('label').querySelector('.scoring-option');
            if (!card) return;
            if (radio.checked) {
                card.style.border = '2px solid var(--color-primary)';
                card.style.background = '#f0fdf4';
                card.style.boxShadow = '0 4px 16px rgba(22,163,74,.15)';
            } else {
                card.style.border = '2px solid var(--color-border)';
                card.style.background = 'var(--color-surface-hover)';
                card.style.boxShadow = 'none';
            }
        });
    }

    radios.forEach(radio => {
        radio.addEventListener('change', updateCards);
        // Clicking on the card div also selects
        const card = radio.closest('label').querySelector('.scoring-option');
        if (card) {
            card.addEventListener('click', () => {
                if (!radio.checked) {
                    radio.checked = true;
                    radio.dispatchEvent(new Event('change', { bubbles: true }));
                }
                updateCards();
            });
            card.style.cursor = 'pointer';
        }
    });

    updateCards(); // initial state
});

// ─── Custom Confirm Interceptor ───
document.addEventListener('submit', function(e) {
    const form = e.target;
    if (form.hasAttribute('data-confirm')) {
        e.preventDefault();
        const message = form.getAttribute('data-confirm');
        showConfirm('Konfirmasi', message, 'warning').then(function(result) {
            if (result) {
                form.removeAttribute('data-confirm'); // prevent infinite loop
                form.submit();
            }
        });
    }
});

// ─── Auto-dismiss alerts ───
setTimeout(function () {
    document.querySelectorAll('.alert-success').forEach(el => {
        el.style.transition = 'opacity .5s';
        el.style.opacity = '0';
        setTimeout(() => el.remove(), 500);
    });
}, 4000);
</script>
@include('components.modal-confirm')

<script>
// ─── Notification System ───
const NOTIF_URL = '/api/notifications';
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';

let notifPanelOpen = false;

function toggleNotifPanel() {
    const panel = document.getElementById('notif-panel');
    notifPanelOpen = !notifPanelOpen;
    panel.style.display = notifPanelOpen ? 'block' : 'none';
    if (notifPanelOpen) fetchNotifications();
}

// Close panel when clicking outside
document.addEventListener('click', function(e) {
    const wrapper = document.getElementById('notif-wrapper');
    if (wrapper && !wrapper.contains(e.target) && notifPanelOpen) {
        notifPanelOpen = false;
        document.getElementById('notif-panel').style.display = 'none';
    }
});

const typeStyles = {
    success: { bg: '#dcfce7', border: '#bbf7d0', color: '#166534', dot: '#16a34a' },
    danger:  { bg: '#fee2e2', border: '#fecaca', color: '#991b1b', dot: '#dc2626' },
    warning: { bg: '#fef9c3', border: '#fde68a', color: '#92400e', dot: '#d97706' },
    info:    { bg: '#e0f2fe', border: '#bae6fd', color: '#0369a1', dot: '#0284c7' },
};

function renderNotifications(data) {
    const list = document.getElementById('notif-list');
    const badge = document.getElementById('notif-badge');
    if (!list) return;

    // Update badge
    badge.style.display = data.unread_count > 0 ? 'block' : 'none';

    if (!data.notifications.length) {
        list.innerHTML = `<div style="padding:40px 20px;text-align:center;">
            <i class="fas fa-bell-slash" style="font-size:32px;color:var(--color-border);margin-bottom:12px;display:block;"></i>
            <p style="font-size:13px;color:var(--color-text-tertiary);">Tidak ada notifikasi</p>
        </div>`;
        return;
    }

    list.innerHTML = data.notifications.map(n => {
        const s = typeStyles[n.type] || typeStyles.info;
        const unreadStyle = n.is_read ? '' : `border-left:3px solid ${s.dot};background:${s.bg};`;
        const actionBtn = (n.action_url && n.action_label)
            ? `<a href="${n.action_url}" onclick="markRead(${n.id})" style="display:inline-block;margin-top:8px;font-size:12px;font-weight:700;color:${s.dot};text-decoration:none;">${n.action_label} →</a>`
            : '';
        return `<div class="notif-item" data-id="${n.id}" onclick="markRead(${n.id})" style="padding:14px 20px;border-bottom:1px solid var(--color-border);cursor:pointer;transition:.15s;${unreadStyle}">
            <div style="display:flex;gap:12px;align-items:flex-start;">
                <div style="width:36px;height:36px;background:${s.bg};border:1px solid ${s.border};border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    ${(n.icon && (n.icon.startsWith('fa-') || n.icon.startsWith('fas ') || n.icon.startsWith('fab '))) ? `<i class="${n.icon}" style="color:${s.dot};font-size:14px;"></i>` : `<span style="font-size:16px;">${n.icon || '🔔'}</span>`}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:800;color:var(--color-text-primary);margin-bottom:3px;">${n.title}</div>
                    <div style="font-size:12px;color:var(--color-text-secondary);line-height:1.5;">${n.body}</div>
                    ${actionBtn}
                    <div style="font-size:11px;color:var(--color-text-tertiary);margin-top:6px;">${n.created_at}</div>
                </div>
                ${!n.is_read ? `<span style="width:8px;height:8px;background:${s.dot};border-radius:50%;flex-shrink:0;margin-top:4px;"></span>` : ''}
            </div>
        </div>`;
    }).join('');
}

function fetchNotifications() {
    fetch(NOTIF_URL)
        .then(r => r.json())
        .then(data => renderNotifications(data))
        .catch(() => {});
}

function markRead(id) {
    const item = document.querySelector(`.notif-item[data-id="${id}"]`);
    if (item) {
        item.style.borderLeft = 'none';
        item.style.background = '';
        // Remove unread dot
        const dot = item.querySelector('span[style*="border-radius:50%"]');
        if (dot) dot.remove();
    }
    fetch(`${NOTIF_URL}/${id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } }).catch(() => {});
}

function markAllRead() {
    fetch(`${NOTIF_URL}/read-all`, { method: 'POST', headers: { 'X-CSRF-TOKEN': CSRF_TOKEN } })
        .then(() => {
            document.getElementById('notif-badge').style.display = 'none';
            document.querySelectorAll('.notif-item').forEach(el => {
                el.style.borderLeft = 'none';
                el.style.background = '';
                const dot = el.querySelector('span[style*="border-radius:50%"]');
                if (dot) dot.remove();
            });
        }).catch(() => {});
}

// Poll for new notifications every 60 seconds (background, regardless of panel)
fetchNotifications();
setInterval(fetchNotifications, 60000);
</script>

{{-- Mobile Bottom Navbar --}}
<nav class="bottom-nav">
    <div class="bottom-nav-inner">
        @if(auth()->check() && auth()->user()->isOrganizer())
            <a href="{{ route('organizer.dashboard') }}" class="bnav-item {{ request()->routeIs('organizer.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('organizer.events.index') }}" class="bnav-item {{ request()->routeIs('organizer.events.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt"></i><span>Event</span>
            </a>
            <a href="{{ route('organizer.statistik.index') }}" class="bnav-item {{ request()->routeIs('organizer.statistik.*') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i><span>Statistik</span>
            </a>
            <a href="{{ route('organizer.leaderboard') }}" class="bnav-item {{ request()->routeIs('organizer.leaderboard') ? 'active' : '' }}">
                <i class="fas fa-trophy"></i><span>Klasemen</span>
            </a>
            <a href="javascript:void(0)" onclick="document.getElementById('sidebar').classList.toggle('open'); event.stopPropagation();" class="bnav-item">
                <i class="fas fa-bars"></i><span>Lainnya</span>
            </a>
        @elseif(auth()->check() && auth()->user()->isParticipant())
            <a href="{{ route('peserta.dashboard') }}" class="bnav-item {{ request()->routeIs('peserta.dashboard') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
            <a href="{{ route('peserta.events') }}" class="bnav-item {{ request()->routeIs('peserta.events') ? 'active' : '' }}">
                <i class="fas fa-certificate"></i><span>Sertifikat</span>
            </a>
            <a href="{{ route('peserta.leaderboard') }}" class="bnav-item {{ request()->routeIs('peserta.leaderboard*') ? 'active' : '' }}">
                <i class="fas fa-trophy"></i><span>Leaderboard</span>
            </a>
            <a href="javascript:void(0)" onclick="document.querySelector('.btn-logout').click()" class="bnav-item">
                <i class="fas fa-sign-out-alt"></i><span>Keluar</span>
            </a>
        @endif
    </div>
</nav>
<script>
    // Close sidebar if clicking outside on mobile
    document.addEventListener('click', function(e) {
        const sidebar = document.getElementById('sidebar');
        if (sidebar && sidebar.classList.contains('open')) {
            if (!sidebar.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        }
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
<script>
    NProgress.configure({ showSpinner: false, speed: 400, minimum: 0.1 });
    
    // Start NProgress when an internal link is clicked
    document.addEventListener('click', function(e) {
        let target = e.target.closest('a');
        if (!target || !target.href) return;
        
        // Only trigger on internal links that aren't empty hashes or target="_blank"
        let isInternal = target.hostname === window.location.hostname;
        let isNotAnchor = !target.href.includes('#') || target.href.split('#')[0] !== window.location.href.split('#')[0];
        let isNotNewTab = target.target !== '_blank';
        let isNotDownload = !target.hasAttribute('download');
        
        if (isInternal && isNotAnchor && isNotNewTab && isNotDownload) {
            NProgress.start();
        }
    });

    // Also handle form submissions
    document.addEventListener('submit', function(e) {
        if (!e.defaultPrevented) NProgress.start();
    });

    // Handle back/forward cache
    window.addEventListener('pageshow', function(e) {
        if (e.persisted) NProgress.done();
    });
</script>

@stack('scripts')
</body>
</html>
