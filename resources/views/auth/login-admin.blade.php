<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — MIS Raudlatul Ulum</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --grad-start: #1db349;
            --grad-end: #a5cf36;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        /* Panel Kiri */
        .left-panel {
            background: linear-gradient(145deg, var(--grad-start) 0%, var(--grad-end) 100%);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }
        .left-panel::before {
            content: '';
            position: absolute;
            top: -80px; right: -80px;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            bottom: -100px; left: -80px;
            width: 350px; height: 350px;
            background: rgba(255,255,255,0.07);
            border-radius: 50%;
        }
        .panel-brand { position: relative; z-index: 2; }
        .panel-brand img { height: 48px; }

        .panel-headline { position: relative; z-index: 2; }
        .panel-headline h2 {
            color: #fff;
            font-size: 36px;
            font-weight: 300;
            line-height: 1.3;
            letter-spacing: -0.5px;
            margin-bottom: 16px;
        }
        .panel-headline h2 span { font-weight: 700; }
        .panel-headline p {
            color: rgba(255,255,255,0.8);
            font-size: 15px;
            font-weight: 300;
            line-height: 1.6;
        }

        .panel-footer {
            position: relative; z-index: 2;
            color: rgba(255,255,255,0.6);
            font-size: 11px; font-weight: 400;
        }

        /* Panel Kanan */
        .right-panel {
            background: #f8fafc;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 80px 64px;
        }
        .admin-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(29,179,73,0.08);
            color: var(--grad-start);
            padding: 6px 14px;
            border-radius: 100px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 16px;
            width: max-content;
        }
        .login-header { margin-bottom: 40px; }
        .login-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }
        .login-header p { font-size: 14px; color: var(--text-muted); font-weight: 400; }

        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .form-input {
            width: 100%;
            padding: 14px 18px;
            background: #fff;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            color: var(--text-dark);
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            font-weight: 400;
            transition: 0.3s;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--grad-start);
            box-shadow: 0 0 0 3px rgba(29, 179, 73, 0.1);
        }
        .form-input::placeholder { color: #cbd5e1; }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--grad-start), var(--grad-end));
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Montserrat', sans-serif;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 8px;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(29, 179, 73, 0.25);
            filter: brightness(1.05);
        }

        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 32px;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: 0.3s;
        }
        .back-link:hover { color: var(--grad-start); }

        .watermark { margin-top: 48px; font-size: 11px; font-weight: 400; color: #cbd5e1; }
        .watermark a { color: var(--grad-start); text-decoration: none; }

        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .left-panel { padding: 32px; min-height: 280px; }
            .panel-headline h2 { font-size: 24px; }
            .right-panel { padding: 40px 24px; }
        }
    </style>
</head>
<body>
    <!-- Panel Kiri -->
    <div class="left-panel">
        <div class="panel-brand">
            <img src="{{ asset('images/logo.png') }}" alt="Logo MI Raudlatul Ulum">
        </div>
        <div class="panel-headline">
            <h2>Kelola Olimpiade<br>dengan <span>Mudah & Cepat</span></h2>
            <p>Dashboard penyelenggara untuk mengelola event, soal, peserta, dan penilaian ujian kompetisi sejarah Islam secara terpusat.</p>
        </div>
        <div class="panel-footer">
            &copy; {{ date('Y') }} HM SPI MI Raudlatul Ulum
        </div>
    </div>

    <!-- Panel Kanan -->
    <div class="right-panel">
        <div class="login-header">
            <div class="admin-badge"><i class="fas fa-shield-halved"></i> Area Penyelenggara</div>
            <h1>Login Admin</h1>
            <p>Masuk ke dashboard penyelenggara dengan email dan password Anda.</p>
        </div>

        @if($errors->has('login'))
            <div class="alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ $errors->first('login') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-input" placeholder="admin@musabaqahtarikhislam.com" required value="{{ old('email') }}" autofocus>
            </div>
            <div class="form-group">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-input" placeholder="Masukkan password admin" required>
            </div>
            <button type="submit" class="btn-submit">
                <i class="fas fa-lock"></i> &nbsp;Masuk Dashboard
            </button>
        </form>

        <a href="/" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Beranda
        </a>

        <div class="watermark">
            Developed by <a href="https://hvmdigital.id/jasa-pembuatan-website-surabaya-murah" target="_blank" rel="noopener">hvmdigital.id</a>
        </div>
    </div>
</body>
</html>
