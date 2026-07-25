<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Platform Ujian Digital</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;800;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --grad-start: #1db349;
            --grad-end: #a5cf36;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: #f8fafc;
            color: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 24px;
        }
        .error-container {
            background: #ffffff;
            padding: 48px;
            border-radius: 32px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
            max-width: 500px;
            width: 100%;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(0,0,0,0.04);
        }
        .error-container::before {
            content: '';
            position: absolute;
            top: 0; left: 0; width: 100%; height: 8px;
            background: linear-gradient(135deg, var(--grad-start), var(--grad-end));
        }
        .error-icon {
            font-size: 64px;
            margin-bottom: 24px;
            background: -webkit-linear-gradient(135deg, var(--grad-start), var(--grad-end));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .error-code {
            font-size: 80px;
            font-weight: 900;
            line-height: 1;
            margin-bottom: 8px;
            letter-spacing: -2px;
            color: #0f172a;
        }
        .error-title {
            font-size: 20px;
            font-weight: 800;
            margin-bottom: 16px;
            color: #475569;
        }
        .error-desc {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 32px;
        }
        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: linear-gradient(135deg, var(--grad-start), var(--grad-end));
            color: #fff;
            text-decoration: none;
            padding: 14px 32px;
            border-radius: 100px;
            font-weight: 700;
            font-size: 14px;
            transition: 0.3s;
            box-shadow: 0 4px 16px rgba(29,179,73,0.25);
        }
        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(29,179,73,0.35);
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="@yield('icon', 'fas fa-exclamation-triangle')"></i>
        </div>
        <div class="error-code">@yield('code')</div>
        <div class="error-title">@yield('message')</div>
        <div class="error-desc">@yield('description')</div>
        <a href="{{ url('/') }}" class="btn-back">
            <i class="fas fa-home"></i> Kembali ke Beranda
        </a>
    </div>
</body>
</html>
