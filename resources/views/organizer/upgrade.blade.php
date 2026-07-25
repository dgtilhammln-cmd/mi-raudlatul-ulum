@extends('layouts.app')
@section('page-title', 'Fitur Terkunci')
@section('content')

    <div style="min-height:75vh;display:flex;align-items:center;justify-content:center;padding:24px;">
        <div class="upgrade-card-landscape">
            {{-- Decorative Glow --}}
            <div class="glow-bg"></div>

            <div class="upgrade-grid">
                {{-- Left Side: Copywriting & CTA --}}
                <div class="upgrade-left">
                    <div class="crown-icon">
                        <i class="fas fa-crown"></i>
                        <span class="pulse-dot"></span>
                    </div>

                    <h2 class="upgrade-title">Fitur Belum Tersedia!</h2>

                    @if(isset($feature) && strtolower($feature) == 'artikel')
                        <p class="upgrade-desc">
                            Konten adalah raja! Fitur <strong>Artikel</strong> memungkinkan Anda menerbitkan berita, panduan, dan jurnal resmi langsung di platform ini — <strong style="color:var(--color-text-primary);">menjangkau ribuan pembaca, membangun kredibilitas, dan memperkuat brand event Anda di mata peserta maupun sponsor.</strong>
                        </p>
                    @elseif(isset($feature) && strtolower($feature) == 'anggota')
                        <p class="upgrade-desc">
                            Siapa yang tidak ingin tampil di depan publik? Fitur <strong>Anggota</strong> memungkinkan seluruh pengurus dan anggota organisasi Anda <strong style="color:var(--color-text-primary);">punya profil resmi yang bisa dilihat semua orang</strong> — narsis yang produktif dan membangun eksistensi nyata!
                        </p>
                    @else
                        <p class="upgrade-desc">
                            Menu <strong>{{ $feature ?? 'ini' }}</strong> adalah investasi krusial. Tingkatkan kapabilitas
                            platform Anda sekarang untuk melipatgandakan performa dan efisiensi manajemen event Anda!
                        </p>
                    @endif

                    <div class="urgency-box">
                        <i class="fas fa-clock urgency-icon"></i>
                        <div>
                            <div class="urgency-title">Ambil Langkah Sekarang!</div>
                            <div class="urgency-desc">Jadikan Organisasimu tampil lebih baik di depan. Aktifkan Fitur ini
                                dan
                                jadikan sistem Anda tak tertandingi.</div>
                        </div>
                    </div>

                    <a href="https://wa.me/6285179982373?text=Halo%20HVM%20Digital%20(Office),%20saya%20ingin%20konsultasi%20untuk%20upgrade%20fitur%20PRO:%20{{ $feature ?? 'Premium' }}%20pada%20aplikasi%20ini."
                        target="_blank" rel="noopener" class="btn-upgrade">
                        <span class="btn-upgrade-glow"></span>
                        <i class="fab fa-whatsapp" style="font-size:20px;position:relative;z-index:2;"></i>
                        <span style="position:relative;z-index:2;">Konsultasi Upgrade Sekarang</span>
                    </a>
                </div>

                {{-- Right Side: Impact Features --}}
                <div class="upgrade-right">
                    <div class="impact-box">
                        @if(isset($feature) && strtolower($feature) == 'artikel')
                            <div class="impact-item">
                                <div class="impact-icon"><i class="fas fa-rocket"></i></div>
                                <div>
                                    <div class="impact-title">Dominasi SEO & Trafik</div>
                                    <div class="impact-desc">Muncul di halaman pertama pencarian dan raih atensi publik
                                        seketika.</div>
                                </div>
                            </div>
                            <div class="impact-item">
                                <div class="impact-icon"><i class="fas fa-bullhorn"></i></div>
                                <div>
                                    <div class="impact-title">Publikasi Terjadwal</div>
                                    <div class="impact-desc">Bagikan berita event, rilis pers, dan jurnal secara profesional &
                                        elegan.</div>
                                </div>
                            </div>
                            <div class="impact-item">
                                <div class="impact-icon"><i class="fas fa-star"></i></div>
                                <div>
                                    <div class="impact-title">Branding Eksklusif</div>
                                    <div class="impact-desc">Bangun otoritas platform di mata sponsor dan partner nasional.
                                    </div>
                                </div>
                            </div>
                        @elseif(isset($feature) && strtolower($feature) == 'anggota')
                            <div class="impact-item">
                                <div class="impact-icon"><i class="fas fa-star"></i></div>
                                <div>
                                    <div class="impact-title">Eksistensi Nyata di Publik</div>
                                    <div class="impact-desc">Setiap anggota punya profil resmi yang bisa dilihat seluruh pengunjung website — narsis yang bermakna!</div>
                                </div>
                            </div>
                            <div class="impact-item">
                                <div class="impact-icon"><i class="fas fa-id-card-clip"></i></div>
                                <div>
                                    <div class="impact-title">Profil Digital Profesional</div>
                                    <div class="impact-desc">Tampilkan foto, jabatan, dan divisi tiap anggota dengan tampilan kartu yang premium dan elegan.</div>
                                </div>
                            </div>
                            <div class="impact-item">
                                <div class="impact-icon"><i class="fas fa-users-viewfinder"></i></div>
                                <div>
                                    <div class="impact-title">Struktur Organisasi Dinamis</div>
                                    <div class="impact-desc">Atur tampilan berdasarkan divisi, angkatan, atau jabatan — tidak ada lagi bingung siapa itu siapa.</div>
                                </div>
                            </div>
                        @else
                            <div class="impact-item">
                                <div class="impact-icon"><i class="fas fa-bolt"></i></div>
                                <div>
                                    <div class="impact-title">Skalabilitas Maksimal</div>
                                    <div class="impact-desc">Bawa pengelolaan Anda ke level profesional tertinggi.</div>
                                </div>
                            </div>
                            <div class="impact-item">
                                <div class="impact-icon"><i class="fas fa-headset"></i></div>
                                <div>
                                    <div class="impact-title">Dukungan Prioritas</div>
                                    <div class="impact-desc">Layanan bantuan khusus dan cepat dari tim developer kami.</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .upgrade-card-landscape {
            background: var(--color-surface);
            border: 1px solid var(--color-border);
            border-radius: 32px;
            padding: 56px 64px;
            max-width: 1040px;
            width: 100%;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.02);
            position: relative;
            overflow: hidden;
            animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .upgrade-grid {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 64px;
            align-items: center;
            position: relative;
            z-index: 2;
        }

        .upgrade-left {
            text-align: left;
        }

        .upgrade-right {
            position: relative;
        }

        .glow-bg {
            position: absolute;
            top: -100px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: var(--color-accent);
            filter: blur(150px);
            opacity: 0.15;
            border-radius: 50%;
            pointer-events: none;
        }

        .crown-icon {
            width: 72px;
            height: 72px;
            margin-bottom: 24px;
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border-radius: 24px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #d97706;
            font-size: 28px;
            box-shadow: 0 12px 24px rgba(217, 119, 6, 0.25);
            position: relative;
            animation: float 3.5s ease-in-out infinite;
        }

        .pulse-dot {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 12px;
            height: 12px;
            background: #ef4444;
            border-radius: 50%;
            border: 2px solid #fff;
            animation: pulse-dot 2s infinite;
        }

        .upgrade-title {
            font-size: 36px;
            font-weight: 900;
            background: linear-gradient(135deg, #d97706, #f59e0b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 16px;
            line-height: 1.2;
        }

        .upgrade-desc {
            font-size: 16px;
            color: var(--color-text-secondary);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .urgency-box {
            background: rgba(239, 68, 68, 0.05);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 36px;
            display: flex;
            gap: 16px;
            align-items: center;
        }

        .urgency-icon {
            font-size: 24px;
            color: #ef4444;
            animation: pulse-dot 2s infinite;
        }

        .urgency-title {
            font-size: 14px;
            font-weight: 800;
            color: #ef4444;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .urgency-desc {
            font-size: 13px;
            color: var(--color-text-secondary);
            margin-top: 4px;
            line-height: 1.5;
        }

        .impact-box {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .impact-item {
            background: var(--color-surface-soft);
            border: 1px solid transparent;
            padding: 20px 24px;
            border-radius: 20px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            text-align: left;
            transition: 0.3s;
            cursor: default;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.02);
        }

        .impact-item:hover {
            background: #fff;
            border-color: var(--color-border);
            box-shadow: 0 12px 32px rgba(0, 0, 0, 0.06);
            transform: translateX(-8px);
        }

        .impact-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--color-primary), var(--color-accent));
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 14px;
            font-size: 18px;
            flex-shrink: 0;
            box-shadow: 0 8px 16px rgba(29, 179, 73, 0.2);
        }

        .impact-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--color-text-primary);
            margin-bottom: 6px;
        }

        .impact-desc {
            font-size: 13px;
            color: var(--color-text-secondary);
            line-height: 1.5;
        }

        .btn-upgrade {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: #25D366;
            color: #fff;
            padding: 18px 40px;
            border-radius: 100px;
            font-size: 16px;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 12px 32px rgba(37, 211, 102, 0.3);
            transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            width: max-content;
            position: relative;
            overflow: hidden;
        }

        .btn-upgrade-glow {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, transparent, rgba(255, 255, 255, 0.4), transparent);
            transform: skewX(-25deg);
            animation: shine 3s infinite;
            z-index: 1;
        }

        .btn-upgrade:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 40px rgba(37, 211, 102, 0.4);
            background: #22c55e;
        }

        /* Responsive */
        @media(max-width: 992px) {
            .upgrade-grid {
                grid-template-columns: 1fr;
                gap: 48px;
            }

            .upgrade-card-landscape {
                padding: 40px 32px;
            }

            .btn-upgrade {
                width: 100%;
            }

            .impact-item:hover {
                transform: translateY(-4px);
            }
        }

        @media(max-width: 480px) {
            .upgrade-card-landscape {
                padding: 32px 24px;
            }

            .upgrade-title {
                font-size: 28px;
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        @keyframes pulse-dot {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            }

            70% {
                transform: scale(1);
                box-shadow: 0 0 0 8px rgba(239, 68, 68, 0);
            }

            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        @keyframes shine {
            0% {
                left: -100%;
            }

            20% {
                left: 200%;
            }

            100% {
                left: 200%;
            }
        }
    </style>

@endsection