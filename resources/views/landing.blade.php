<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    
    <!-- SEO & Meta Tags -->
    <title>CBT - MIS Raudlatul Ulum</title>
    <meta name="description" content="Tingkatkan prestasimu di kompetisi sejarah peradaban Islam tingkat nasional. Uji wawasan, raih prestasi, & jadilah juara di Platform Ujian Digital MI Raudlatul Ulum.">
    <meta name="keywords" content="olimpiade sejarah, sejarah islam, musabaqah tarikh islam, Platform Ujian Digital, kompetisi sejarah nasional, olimpiade islam">
    <meta name="author" content="HM SPI MI Raudlatul Ulum">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:title" content="Platform Ujian Digital — Jadilah Juara">
    <meta property="og:description" content="Bangun prestasimu di kompetisi sejarah peradaban Islam tingkat nasional. Daftar sekarang!">
    @php $logoSetting = \App\Models\WebSetting::get('site_logo'); $ogImage = $logoSetting ? asset('storage/' . $logoSetting) : asset('images/logo.png'); @endphp
    <meta property="og:image" content="{{ $ogImage }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="Platform Ujian Digital — Jadilah Juara">
    <meta property="twitter:description" content="Bangun prestasimu di kompetisi sejarah peradaban Islam tingkat nasional.">
    <meta property="twitter:image" content="{{ $ogImage }}">
    
    <!-- Geo Tags -->
    <meta name="geo.region" content="ID-JI">
    <meta name="geo.placename" content="Surabaya">
    <meta name="geo.position" content="-7.3190;112.7300">
    <meta name="ICBM" content="-7.3190, 112.7300">

    <!-- Favicon -->
    @php $faviconPath = \App\Models\WebSetting::get('site_logo'); @endphp
    <link rel="icon" type="image/png" href="{{ $faviconPath ? asset('storage/' . $faviconPath) : asset('images/logo.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-color: #f8fafc;
            --grad-start: #1db349;
            --grad-end: #a5cf36;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: var(--bg-color);
            color: var(--text-dark);
            min-height: 100vh;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
            font-weight: 400;
        }

        /* ═══ NAVBAR ═══ */
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

        /* ═══ HERO SECTION ═══ */
        .hero-wrapper {
            /* Pas untuk layar 14 inch: viewport dikurangi navbar + padding atas-bawah */
            height: calc(100vh - 64px);
            max-height: 720px;
            min-height: 520px;
            padding: 20px 40px 28px;
            display: flex;
            justify-content: center;
        }
        .hero-card {
            background: linear-gradient(140deg, var(--grad-start) 0%, #3abf5e 40%, var(--grad-end) 100%);
            width: 100%;
            height: 100%;
            border-radius: 28px;
            display: flex;
            overflow: hidden;
            box-shadow: 0 16px 48px rgba(29,179,73,0.12);
            position: relative;
        }
        /* Decorative circles */
        .hero-card::before {
            content: '';
            position: absolute;
            top: -60px; left: -60px;
            width: 220px; height: 220px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
            z-index: 1;
        }
        .hero-card::after {
            content: '';
            position: absolute;
            bottom: -40px; left: 30%;
            width: 180px; height: 180px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            z-index: 1;
        }

        /* ── Left Content ── */
        .hero-content {
            flex: 1.1;
            padding: 48px 56px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 2;
        }
        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(6px);
            color: #fff;
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 500;
            margin-bottom: 24px;
            width: max-content;
            border: 1px solid rgba(255,255,255,0.2);
        }
        .hero-title {
            color: #fff;
            font-size: clamp(26px, 3.2vw, 38px);
            line-height: 1.25;
            font-weight: 300;
            margin-bottom: 16px;
        }
        .hero-title span { font-weight: 700; }
        .hero-desc {
            color: rgba(255,255,255,0.85);
            font-size: clamp(13px, 1.2vw, 15px);
            font-weight: 300;
            max-width: 420px;
            line-height: 1.65;
            margin-bottom: 28px;
        }
        .btn-light {
            background: #fff;
            color: var(--grad-start);
            padding: 12px 28px;
            border-radius: 100px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: max-content;
            transition: 0.3s;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .btn-light:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,0.12); }

        /* ── Stats Bar ── */
        .hero-stats {
            display: flex;
            gap: 32px;
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,0.15);
        }
        .stat-item { color: #fff; }
        .stat-value {
            font-size: clamp(22px, 2.5vw, 30px);
            font-weight: 700;
            line-height: 1;
            margin-bottom: 4px;
            display: flex;
            align-items: baseline;
        }
        .stat-value .plus {
            font-size: 0.65em;
            font-weight: 400;
            margin-left: 2px;
            opacity: 0.7;
        }
        .stat-label {
            font-size: 10px;
            font-weight: 400;
            opacity: 0.65;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        /* ── Right Marquee ── */
        .hero-images {
            flex: 0.9;
            display: flex;
            gap: 14px;
            padding: 14px;
            overflow: hidden;
            position: relative;
            background: rgba(0,0,0,0.03);
            border-left: 1px solid rgba(255,255,255,0.1);
        }
        .marquee-col { flex: 1; display: flex; flex-direction: column; gap: 14px; }
        .marquee-col-inner { display: flex; flex-direction: column; gap: 14px; will-change: transform; }
        .scroll-down { animation: scrollDown 30s linear infinite; }
        .scroll-up { animation: scrollUp 30s linear infinite; }
        @keyframes scrollDown { 0%{transform:translateY(-50%)} 100%{transform:translateY(0%)} }
        @keyframes scrollUp { 0%{transform:translateY(0%)} 100%{transform:translateY(-50%)} }
        .marquee-img {
            width: 100%;
            border-radius: 14px;
            object-fit: cover;
            aspect-ratio: 4/5;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            transition: 0.4s;
        }
        .faq-toggle i { transition: transform 0.3s ease; }
        .faq-answer { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
        
        /* Live Leaderboard Card Hover */
        .event-leaderboard-card:hover { transform: translateY(-8px); }
        .event-leaderboard-card:hover .card-bg { transform: scale(1.08); }

        /* ═══ RESPONSIVE ═══ */
        @media (max-width: 1024px) {
            .hero-wrapper { max-height: none; height: auto; padding: 16px 24px 24px; }
            .hero-card { flex-direction: column; height: auto; }
            .hero-content { padding: 40px 32px; align-items: center; text-align: center; }
            .hero-stats { justify-content: center; }
            .hero-images { width: 100%; height: 420px; border-left: none; border-top: 1px solid rgba(255,255,255,0.1); }
            .nav-links { display: none; }
        }
        @media (max-width: 480px) {
            .navbar { padding: 0 16px; height: 56px; }
            .nav-logo img { height: 28px; }
            .btn-login { padding: 8px 16px; font-size: 12px; }
            .hero-wrapper { padding: 8px 12px 12px; }
            .hero-card { border-radius: 18px; }
            .hero-content { padding: 28px 20px; }
            .hero-title { font-size: 22px; }
            .hero-desc { font-size: 12px; margin-bottom: 20px; }
            .btn-light { font-size: 13px; padding: 10px 24px; }
            .hero-stats { gap: 16px; flex-wrap: wrap; justify-content: center; }
            .stat-value { font-size: 20px; }
            .hero-images { height: 300px; gap: 8px; padding: 8px; }
            .marquee-col { gap: 8px; }
            .marquee-col-inner { gap: 8px; }
            .marquee-img { border-radius: 10px; }
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar">
        <a href="/" class="nav-logo">
            @php $siteLogo = \App\Models\WebSetting::get('site_logo'); @endphp
            <img src="{{ $siteLogo ? asset('storage/' . $siteLogo) : asset('images/logo.png') }}" alt="Logo" style="height:36px;object-fit:contain;border-radius:8px;">
        </a>
        <div class="nav-links">
            <a href="#">Beranda</a>
            <a href="#">Panduan</a>
            <a href="#">Ketentuan</a>
            <a href="#">Pusat Bantuan</a>
        </div>
        <div>
            <a href="{{ route('login') }}" class="btn-login">Login Peserta</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-wrapper">
        <div class="hero-card">

            <!-- Kiri: Teks -->
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="{{ $hero['badge_icon'] ?? 'fas fa-star' }}"></i> {{ $hero['badge'] ?? 'Kompetisi Sejarah Islam Tingkat Nasional' }}
                </div>

                <h1 class="hero-title">
                    {{ $hero['title_line1'] ?? 'Uji Wawasan,' }}<br>
                    {{ $hero['title_line2'] ?? 'Raih Prestasi,' }}<br>
                     <span>{{ $hero['title_line3'] ?? 'Jadilah Juara' }}</span>
                </h1>

                <p class="hero-desc">
                    {{ $hero['description'] ?? 'Bangun prestasimu di kompetisi sejarah peradaban Islam tingkat nasional.' }}
                </p>

                <a href="{{ $hero['cta_link'] ?? route('login') }}" class="btn-light">
                    <i class="far fa-check-circle"></i> <strong>{{ $hero['cta_text'] ?? 'Daftarkan Dirimu!' }}</strong>
                </a>

                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-value">{{ $hero['stat1_value'] ?? '5.000' }}<span class="plus">{{ $hero['stat1_suffix'] ?? '+' }}</span></div>
                        <div class="stat-label">{{ $hero['stat1_label'] ?? 'Total Peserta' }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $hero['stat2_value'] ?? '50' }}<span class="plus">{{ $hero['stat2_suffix'] ?? '+' }}</span></div>
                        <div class="stat-label">{{ $hero['stat2_label'] ?? 'Institusi Pendidikan' }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">{{ $hero['stat3_value'] ?? '100' }}<span class="plus">{{ $hero['stat3_suffix'] ?? '%' }}</span></div>
                        <div class="stat-label">{{ $hero['stat3_label'] ?? 'Online & Realtime' }}</div>
                    </div>
                </div>
            </div>

            <!-- Kanan: Animasi Foto -->
            <div class="hero-images">
                <div class="marquee-col">
                    <div class="marquee-col-inner scroll-down">
                        @if(isset($leftImages) && $leftImages->count() > 0)
                            @foreach($leftImages as $img) <img src="{{ Storage::url($img->image_path) }}" class="marquee-img" alt="Dokumentasi"> @endforeach
                            @foreach($leftImages as $img) <img src="{{ Storage::url($img->image_path) }}" class="marquee-img" alt="Dokumentasi"> @endforeach
                        @else
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=400" class="marquee-img" alt="Placeholder">
                            <img src="https://images.unsplash.com/photo-1544531586-fde5298cdd40?auto=format&fit=crop&q=80&w=400" class="marquee-img" alt="Placeholder">
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80&w=400" class="marquee-img" alt="Placeholder">
                            <img src="https://images.unsplash.com/photo-1544531586-fde5298cdd40?auto=format&fit=crop&q=80&w=400" class="marquee-img" alt="Placeholder">
                        @endif
                    </div>
                </div>
                <div class="marquee-col">
                    <div class="marquee-col-inner scroll-up">
                        @if(isset($rightImages) && $rightImages->count() > 0)
                            @foreach($rightImages as $img) <img src="{{ Storage::url($img->image_path) }}" class="marquee-img" alt="Dokumentasi"> @endforeach
                            @foreach($rightImages as $img) <img src="{{ Storage::url($img->image_path) }}" class="marquee-img" alt="Dokumentasi"> @endforeach
                        @else
                            <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&q=80&w=400" class="marquee-img" alt="Placeholder">
                            <img src="https://images.unsplash.com/photo-1546410531-bea4d3983d30?auto=format&fit=crop&q=80&w=400" class="marquee-img" alt="Placeholder">
                            <img src="https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&q=80&w=400" class="marquee-img" alt="Placeholder">
                            <img src="https://images.unsplash.com/photo-1546410531-bea4d3983d30?auto=format&fit=crop&q=80&w=400" class="marquee-img" alt="Placeholder">
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- PARTNER & SPONSOR SECTION --}}
    @if((isset($partners) && $partners->isNotEmpty()) || (isset($sponsors) && $sponsors->isNotEmpty()))
    <section style="padding:80px 40px;background:#fff;border-bottom:1px solid #f1f5f9;position:relative;">
        <div style="max-width:1200px;margin:0 auto;display:flex;flex-wrap:wrap;gap:60px;align-items:center;">
            
            {{-- Partners (Left) --}}
            @if(isset($partners) && $partners->isNotEmpty())
            <div style="flex:1;min-width:300px;">
                <h3 style="font-size:14px;font-weight:800;color:var(--text-muted);text-transform:uppercase;letter-spacing:1px;margin-bottom:24px;border-left:4px solid var(--grad-start);padding-left:12px;">Partner Institusi</h3>
                <div style="display:flex;flex-wrap:wrap;gap:24px;align-items:center;">
                    @foreach($partners as $partner)
                        <img src="{{ Storage::url($partner->image_path) }}" alt="{{ $partner->name }}" title="{{ $partner->name }}" style="height:40px;object-fit:contain;filter:grayscale(1) opacity(0.7);transition:0.3s;" onmouseover="this.style.filter='grayscale(0) opacity(1)'" onmouseout="this.style.filter='grayscale(1) opacity(0.7)'">
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Sponsors (Right) --}}
            @if(isset($sponsors) && $sponsors->isNotEmpty())
            <div style="flex:1;min-width:300px;background:#f8fafc;padding:32px;border-radius:24px;border:1px solid #e2e8f0;">
                <h3 style="font-size:14px;font-weight:800;color:var(--grad-start);text-transform:uppercase;letter-spacing:2px;margin-bottom:24px;text-align:center;">Supported By</h3>
                <div style="display:flex;flex-wrap:wrap;justify-content:center;gap:16px;">
                    @foreach($sponsors as $sponsor)
                        @if($sponsor->url)
                        <a href="{{ $sponsor->url }}" target="_blank" rel="noopener" class="sponsor-card" style="width:140px;height:80px;padding:12px;">
                            <img src="{{ Storage::url($sponsor->image_path) }}" alt="{{ $sponsor->name }}">
                        </a>
                        @else
                        <div class="sponsor-card" style="width:140px;height:80px;padding:12px;">
                            <img src="{{ Storage::url($sponsor->image_path) }}" alt="{{ $sponsor->name }}">
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </section>
    @endif

    {{-- Live Leaderboard Section (Event Cards) --}}
    @if(isset($featuredEvents) && $featuredEvents->isNotEmpty())
    <section style="padding:80px 40px;background:linear-gradient(to bottom, #f8fafc, #ffffff);">
        <div style="max-width:1100px;margin:0 auto;">

            <div style="text-align:center;margin-bottom:56px;">
                <div style="display:inline-flex;align-items:center;gap:8px;background:rgba(29,179,73,0.1);color:var(--grad-start);padding:8px 20px;border-radius:100px;font-size:13px;font-weight:800;letter-spacing:1px;margin-bottom:16px;border:1px solid rgba(29,179,73,0.2);">
                    <span style="width:10px;height:10px;background:#1db349;border-radius:50%;animation:pulse-lp 1.5s infinite;"></span>
                    LIVE LEADERBOARD
                </div>
                <h2 style="font-size:clamp(28px,4vw,36px);font-weight:900;color:#0f172a;margin-bottom:12px;letter-spacing:-0.5px;">Pantau Klasemen Real-time</h2>
                <p style="font-size:16px;color:#64748b;max-width:600px;margin:0 auto;line-height:1.6;">Lihat perolehan poin dari para peserta yang sedang berlaga secara langsung.</p>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(320px, 1fr));gap:24px;">
                @foreach($featuredEvents->take(3) as $event)
                @php
                    $bgImage = $event->poster_image ? asset('storage/'.$event->poster_image) : 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&q=80&w=800';
                @endphp
                <a href="{{ route('leaderboard.public', $event) }}" class="event-leaderboard-card" style="display:block;height:420px;border-radius:24px;position:relative;overflow:hidden;box-shadow:0 12px 32px rgba(0,0,0,0.1);transition:transform 0.3s;text-decoration:none;">
                    <div class="card-bg" style="position:absolute;inset:0;background:url('{{ $bgImage }}') center/cover;transition:transform 0.5s;"></div>
                    <div style="position:absolute;inset:0;background:linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.5) 50%, rgba(0,0,0,0.1) 100%);"></div>
                    
                    <div style="position:absolute;top:24px;left:24px;">
                        <div style="width:48px;height:48px;background:var(--grad-start);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:20px;box-shadow:0 8px 16px rgba(29,179,73,0.4);">
                            <i class="fas fa-trophy"></i>
                        </div>
                    </div>
                    
                    <div style="position:absolute;top:24px;right:24px;">
                        <span style="background:rgba(0,0,0,0.3);backdrop-filter:blur(8px);color:#fff;padding:6px 12px;border-radius:100px;font-size:12px;font-weight:700;border:1px solid rgba(255,255,255,0.2);display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-circle" style="font-size:8px;color:#4ade80;animation:pulse-lp 1.5s infinite;"></i> Aktif
                        </span>
                    </div>
                    
                    <div style="position:absolute;bottom:24px;left:24px;right:24px;">
                        <h3 style="font-size:22px;font-weight:800;color:#fff;margin-bottom:12px;line-height:1.3;text-shadow:0 2px 4px rgba(0,0,0,0.5);">{{ $event->name }}</h3>
                        <p style="font-size:13px;color:rgba(255,255,255,0.8);margin-bottom:24px;line-height:1.5;">Lihat perolehan poin dan klasemen sementara dari {{ $event->participants_count }} peserta.</p>
                        <div style="display:flex;align-items:center;justify-content:space-between;border-top:1px solid rgba(255,255,255,0.1);padding-top:16px;">
                            <div style="font-size:13px;font-weight:700;color:#fff;display:flex;align-items:center;gap:6px;">
                                <i class="fas fa-layer-group" style="color:#4ade80;"></i> {{ $event->rounds()->count() }} Babak
                            </div>
                            <div style="font-size:13px;font-weight:800;color:#4ade80;display:flex;align-items:center;gap:6px;text-transform:uppercase;letter-spacing:1px;">
                                Klasemen <i class="fas fa-arrow-right"></i>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            
            <div style="text-align:center;margin-top:48px;">
                <a href="{{ route('leaderboard.index') }}" style="display:inline-flex;align-items:center;gap:8px;color:var(--grad-start);font-size:15px;font-weight:700;text-decoration:none;border-bottom:2px solid transparent;padding-bottom:2px;transition:0.2s;" onmouseover="this.style.borderBottomColor='var(--grad-start)'" onmouseout="this.style.borderBottomColor='transparent'">
                    Lihat Semua Event <i class="fas fa-chevron-right" style="font-size:12px;"></i>
                </a>
            </div>
            
        </div>
    </section>
    @endif

    {{-- INSTAGRAM FEEDS SECTION --}}
    @if(isset($instagramFeeds) && $instagramFeeds->isNotEmpty())
    <section style="padding:80px 0;background:#fff;overflow:hidden;">
        <div style="text-align:center;margin-bottom:48px;padding:0 20px;">
            <div style="display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;background:linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);border-radius:12px;margin-bottom:16px;">
                <i class="fab fa-instagram" style="color:#fff;font-size:24px;"></i>
            </div>
            <h2 style="font-size:clamp(24px,3vw,32px);font-weight:900;color:var(--text-dark);margin-bottom:8px;">Ikuti Keseruannya</h2>
            <p style="font-size:14px;color:var(--text-muted);">Pantau terus update terbaru kami di Instagram</p>
        </div>

        <div class="ig-scroll-container">
            @foreach($instagramFeeds as $feed)
            <a href="{{ $feed->link_url }}" target="_blank" rel="noopener" class="ig-card">
                <img src="{{ Storage::url($feed->image_path) }}" alt="Instagram Post">
                <div class="ig-overlay">
                    <i class="fab fa-instagram" style="font-size:32px;margin-bottom:8px;"></i>
                    <span style="font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;">Lihat di Instagram</span>
                </div>
            </a>
            @endforeach
        </div>
    </section>
    @endif

    {{-- PREMIUM FOOTER --}}
    <footer class="main-footer">
        <div class="footer-top">
            <div class="footer-brand">
                @php $footerLogo = \App\Models\WebSetting::get('site_logo'); @endphp
                <div class="footer-logo-box">
                    <img src="{{ $footerLogo ? asset('storage/' . $footerLogo) : asset('images/logo.png') }}" alt="Logo" class="footer-logo">
                </div>
                <p class="footer-desc">{{ $footer['description'] ?? 'Platform Ujian Digital adalah kompetisi sejarah peradaban Islam tingkat nasional.' }}</p>
                <div class="footer-socials">
                    @if(!empty($footer['socials']['instagram']))
                    <a href="{{ $footer['socials']['instagram'] }}" target="_blank" class="social-btn"><i class="fab fa-instagram"></i></a>
                    @endif
                    @if(!empty($footer['socials']['youtube']))
                    <a href="{{ $footer['socials']['youtube'] }}" target="_blank" class="social-btn"><i class="fab fa-youtube"></i></a>
                    @endif
                    @if(!empty($footer['socials']['tiktok']))
                    <a href="{{ $footer['socials']['tiktok'] }}" target="_blank" class="social-btn"><i class="fab fa-tiktok"></i></a>
                    @endif
                </div>
            </div>
            
            <div class="footer-links">
                <h4>Navigasi</h4>
                <a href="#">Beranda</a>
                <a href="#">Ketentuan Kompetisi</a>
                <a href="/leaderboard">Leaderboard Publik</a>
                <a href="{{ route('login') }}">Login Peserta</a>
            </div>

            <div class="footer-contact">
                <h4>Hubungi Kami</h4>
                @if(!empty($footer['phone']))
                <p><i class="fas fa-phone-alt" style="color:#fff !important;"></i> {{ $footer['phone'] }}</p>
                @endif
                @if(!empty($footer['email']))
                <p><i class="fas fa-envelope" style="color:#fff !important;"></i> {{ $footer['email'] }}</p>
                @endif
                @if(!empty($footer['address']))
                <p><i class="fas fa-map-marker-alt" style="color:#fff !important;"></i> {{ $footer['address'] }}</p>
                @endif
            </div>
        </div>
        <div class="footer-bottom">
            <div class="copyright">&copy; {{ date('Y') }} MIS Raudlatul Ulum. All rights reserved.</div>
            <div class="watermark">Developed by <a href="https://hvmdigital.id/jasa-pembuatan-website-surabaya-murah" target="_blank" rel="noopener" style="color:#fff !important;font-weight:700;">hvmdigital.id</a></div>
        </div>
    </footer>

    <style>
    @keyframes pulse-lp { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.5;transform:scale(1.3)} }
    
    /* Marquee Logos */
    .logo-marquee-wrapper { overflow: hidden; width: 100%; white-space: nowrap; position: relative; }
    .logo-marquee-wrapper::before, .logo-marquee-wrapper::after {
        content: ''; position: absolute; top: 0; bottom: 0; width: 100px; z-index: 2; pointer-events: none;
    }
    .logo-marquee-wrapper::before { left: 0; background: linear-gradient(to right, #fff, transparent); }
    .logo-marquee-wrapper::after { right: 0; background: linear-gradient(to left, #fff, transparent); }
    
    .logo-marquee { display: inline-flex; align-items: center; gap: 40px; animation: scrollMarquee 30s linear infinite; }
    .logo-marquee:hover { animation-play-state: paused; }
    .partner-logo { height: 40px; object-fit: contain; filter: grayscale(1) opacity(0.6); transition: .3s; }
    .partner-logo:hover { filter: grayscale(0) opacity(1); }
    
    @keyframes scrollMarquee { 0% { transform: translateX(0); } 100% { transform: translateX(-50%); } }

    /* Sponsor Cards */
    .sponsor-card {
        background: rgba(255,255,255,0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.9);
        border-radius: 16px; padding: 20px; box-shadow: 0 8px 24px rgba(0,0,0,0.04);
        transition: all .3s cubic-bezier(.4,0,.2,1); text-decoration: none;
        display: flex; align-items: center; justify-content: center;
        width: 160px; height: 100px; flex-shrink: 0;
    }
    .sponsor-card img { width: 100%; height: 100%; object-fit: contain; transition: .3s; }
    .sponsor-card:hover { transform: translateY(-4px); box-shadow: 0 12px 32px rgba(29,179,73,0.15); border-color: rgba(29,179,73,0.3); }

    /* Instagram Scroll */
    .ig-scroll-container {
        display: flex; gap: 16px; padding: 0 20px 20px; overflow-x: auto; scroll-snap-type: x mandatory;
        scrollbar-width: none; /* Firefox */
    }
    .ig-scroll-container::-webkit-scrollbar { display: none; /* Chrome */ }
    .ig-card {
        flex: 0 0 calc(25% - 12px); min-width: 240px; max-width: 320px; aspect-ratio: 4/5; border-radius: 20px; overflow: hidden;
        position: relative; scroll-snap-align: center; box-shadow: 0 12px 24px rgba(0,0,0,.08); display: block;
    }
    .ig-card img { width: 100%; height: 100%; object-fit: cover; transition: .5s; }
    .ig-overlay {
        position: absolute; inset: 0; background: rgba(0,0,0,.6); color: #fff; display: flex; flex-direction: column;
        align-items: center; justify-content: center; opacity: 0; transition: .3s; backdrop-filter: blur(4px);
    }
    .ig-card:hover img { transform: scale(1.05); }
    .ig-card:hover .ig-overlay { opacity: 1; }
    
    @media (max-width: 1024px) { .ig-card { flex: 0 0 calc(33.333% - 11px); } }
    @media (max-width: 768px) { .ig-card { flex: 0 0 calc(50% - 8px); } }
    @media (max-width: 480px) { .ig-card { flex: 0 0 80%; } }

    /* Footer */
    .main-footer { 
        background: linear-gradient(135deg, var(--grad-start) 0%, var(--grad-end) 100%); 
        color: #fff; padding: 80px 40px 20px; font-family: 'Montserrat', sans-serif; position: relative; overflow: hidden;
    }
    .main-footer::before {
        content: ''; position: absolute; top: 0; left: 0; right: 0; height: 4px;
        background: rgba(255,255,255,0.2);
    }
    .footer-top { max-width: 1100px; margin: 0 auto; display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 40px; margin-bottom: 60px; position: relative; z-index: 2; }
    .footer-logo-box {
        background: #fff;
        display: inline-block;
        padding: 8px 16px;
        border-radius: 12px;
        margin-bottom: 20px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
    }
    .footer-logo { height: 48px; object-fit: contain; }
    .footer-desc { color: rgba(255,255,255,.9); font-size: 14px; line-height: 1.8; margin-bottom: 24px; max-width: 320px; }
    .footer-socials { display: flex; gap: 12px; }
    .social-btn {
        width: 40px; height: 40px; border-radius: 12px; background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.1);
        display: flex; align-items: center; justify-content: center; color: #fff; font-size: 16px; transition: .3s; text-decoration: none;
    }
    .social-btn:hover { background: linear-gradient(135deg, var(--grad-start), var(--grad-end)); border-color: transparent; transform: translateY(-3px); box-shadow: 0 8px 16px rgba(29,179,73,.3); }
    
    .footer-links h4, .footer-contact h4 { font-size: 16px; font-weight: 800; margin-bottom: 24px; color: #fff; position: relative; display: inline-block; }
    .footer-links h4::after, .footer-contact h4::after {
        content: ''; position: absolute; bottom: -8px; left: 0; width: 24px; height: 3px; border-radius: 2px; background: var(--grad-start);
    }
    .footer-links a { display: block; color: rgba(255,255,255,.7); text-decoration: none; margin-bottom: 14px; font-size: 14px; transition: .2s; font-weight: 500; }
    .footer-links a:hover { color: var(--grad-end); padding-left: 6px; }
    
    .footer-contact p { display: flex; gap: 14px; align-items: flex-start; color: rgba(255,255,255,.7); font-size: 14px; margin-bottom: 16px; line-height: 1.6; }
    .footer-contact i { color: var(--grad-end); margin-top: 4px; font-size: 16px; }

    .footer-bottom {
        max-width: 1100px; margin: 0 auto; padding-top: 24px; border-top: 1px solid rgba(255,255,255,.08);
        display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; position: relative; z-index: 2;
    }
    .copyright { color: rgba(255,255,255,.5); font-size: 13px; font-weight: 500; }
    .watermark { font-size: 13px; color: rgba(255,255,255,.5); font-weight: 500; }
    .watermark a { color: var(--grad-end); text-decoration: none; font-weight: 700; transition: .2s; }
    .watermark a:hover { color: #fff; text-shadow: 0 0 8px rgba(165,207,54,.5); }
    
    @media (max-width: 768px) {
        .footer-top { grid-template-columns: 1fr; gap: 40px; }
        .footer-bottom { flex-direction: column; text-align: center; justify-content: center; }
    }
    </style>

</body>
</html>
