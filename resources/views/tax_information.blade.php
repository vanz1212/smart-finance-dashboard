@extends('layouts.app')

@section('title', 'Informasi Perpajakan - Smart Finance')
@section('body-class', 'tax-information-page')

@section('content')
    <style>
        .site-header,
        .site-footer { display: none !important; }

        body { background: #061316; }

        body > .container {
            width: 100%;
            max-width: none;
            min-height: 100vh;
            padding: 0;
        }

        .content { min-height: 100vh; }

        .tax-info-shell {
            min-height: 100vh;
            color: #f8fafc;
            background:
                linear-gradient(180deg, rgba(4, 17, 20, .82), #061316 680px),
                url('{{ asset('images/slidev2.jpg') }}') center top / cover no-repeat;
        }

        .tax-info-nav {
            width: min(1180px, calc(100% - 40px));
            min-height: 84px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .tax-info-brand {
            color: #fff;
            font-size: 1.05rem;
            font-weight: 900;
            text-decoration: none;
        }

        .tax-info-nav-actions { display: flex; align-items: center; gap: 12px; }

        .tax-info-nav-actions a,
        .tax-info-button {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 0 18px;
            border: 1px solid rgba(255, 255, 255, .22);
            border-radius: 8px;
            color: #fff;
            font-weight: 800;
            text-decoration: none;
        }

        .tax-info-nav-actions .primary,
        .tax-info-button.primary {
            border-color: #18bf75;
            color: #03261b;
            background: #18bf75;
        }

        .tax-info-main {
            width: min(1180px, calc(100% - 40px));
            margin: 0 auto;
            padding: 70px 0 84px;
        }

        .tax-info-hero {
            max-width: 780px;
            padding: 52px 0 100px;
        }

        .tax-info-kicker {
            color: #f3c969;
            font-size: .82rem;
            font-weight: 900;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .tax-info-hero h1 {
            max-width: 760px;
            margin: 18px 0 22px;
            font-size: clamp(2.8rem, 7vw, 5.5rem);
            line-height: 1;
            letter-spacing: 0;
        }

        .tax-info-hero p {
            max-width: 690px;
            margin: 0;
            color: rgba(248, 250, 252, .78);
            font-size: 1.08rem;
            line-height: 1.75;
        }

        .tax-info-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 30px; }

        .tax-info-section { margin-top: 42px; }
        .tax-info-section-head { max-width: 720px; margin-bottom: 24px; }
        .tax-info-section h2 { margin: 0 0 10px; font-size: clamp(1.7rem, 4vw, 2.5rem); }
        .tax-info-section-head p { margin: 0; color: rgba(248, 250, 252, .66); line-height: 1.7; }

        .tax-update-grid,
        .tax-guide-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .tax-info-card {
            min-height: 220px;
            display: flex;
            flex-direction: column;
            padding: 24px;
            border: 1px solid rgba(148, 163, 184, .2);
            border-radius: 8px;
            background: rgba(12, 34, 36, .88);
        }

        .tax-info-card .number {
            color: #18bf75;
            font-size: .82rem;
            font-weight: 900;
            letter-spacing: .08em;
        }

        .tax-info-card h3 { margin: 20px 0 10px; font-size: 1.22rem; }
        .tax-info-card p { margin: 0; color: rgba(248, 250, 252, .68); line-height: 1.65; }
        .tax-info-card a { margin-top: auto; padding-top: 24px; color: #f3c969; font-weight: 800; text-decoration: none; }

        .tax-login-panel {
            margin-top: 42px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 28px;
            padding: 30px;
            border: 1px solid rgba(24, 191, 117, .34);
            border-radius: 8px;
            background: #0d292a;
        }

        .tax-login-panel h2 { margin: 0 0 8px; font-size: 1.55rem; }
        .tax-login-panel p { margin: 0; color: rgba(248, 250, 252, .68); line-height: 1.6; }

        .tax-info-note {
            margin-top: 22px;
            color: rgba(248, 250, 252, .54);
            font-size: .84rem;
            line-height: 1.6;
        }

        @media (max-width: 820px) {
            .tax-update-grid,
            .tax-guide-grid { grid-template-columns: 1fr; }
            .tax-info-card { min-height: 0; }
            .tax-login-panel { grid-template-columns: 1fr; }
            .tax-login-panel .tax-info-button { width: 100%; }
        }

        @media (max-width: 560px) {
            .tax-info-nav,
            .tax-info-main { width: min(100% - 28px, 1180px); }
            .tax-info-nav { align-items: flex-start; flex-direction: column; padding: 18px 0; }
            .tax-info-nav-actions { width: 100%; }
            .tax-info-nav-actions a { flex: 1; padding-inline: 12px; }
            .tax-info-main { padding-top: 26px; }
            .tax-info-hero { padding: 36px 0 70px; }
            .tax-info-actions .tax-info-button { width: 100%; }
            .tax-info-card,
            .tax-login-panel { padding: 20px; }
        }
    </style>

    <div class="tax-info-shell">
        <nav class="tax-info-nav" aria-label="Navigasi informasi perpajakan">
            <a class="tax-info-brand" href="{{ route('home') }}">SMART FINANCE</a>
            <div class="tax-info-nav-actions">
                <a href="{{ route('home') }}">Beranda</a>
                @auth
                    <a class="primary" href="{{ route('perpajakan.index') }}">Buka Modul</a>
                @else
                    <a class="primary" href="{{ route('perpajakan.index') }}">Login</a>
                @endauth
            </div>
        </nav>

        <main class="tax-info-main">
            <section class="tax-info-hero">
                <span class="tax-info-kicker">Pusat Informasi Pajak</span>
                <h1>Pahami pajak sebelum mulai menghitung.</h1>
                <p>Halaman ini dapat dibaca tanpa login. Gunakan sumber resmi untuk mengikuti berita, pengumuman, dan layanan perpajakan terkini; masuk ke akun hanya saat Anda ingin memakai modul estimasi pajak.</p>
                <div class="tax-info-actions">
                    <a class="tax-info-button primary" href="{{ route('perpajakan.index') }}">Masuk ke Modul Pajak <span aria-hidden="true">&rarr;</span></a>
                    <a class="tax-info-button" href="#pembaruan">Lihat Informasi Terkini</a>
                </div>
            </section>

            <section class="tax-info-section" id="pembaruan">
                <div class="tax-info-section-head">
                    <h2>Pembaruan resmi perpajakan</h2>
                    <p>Tautan berikut mengarah langsung ke kanal resmi Direktorat Jenderal Pajak agar informasi yang Anda baca selalu mengikuti pembaruan terbaru.</p>
                </div>
                <div class="tax-update-grid">
                    <article class="tax-info-card">
                        <span class="number">01 / BERITA</span>
                        <h3>Berita Pajak Terbaru</h3>
                        <p>Ikuti kabar kebijakan, edukasi, kegiatan, dan layanan perpajakan yang diterbitkan oleh DJP.</p>
                        <a href="https://www.pajak.go.id/id/berita" target="_blank" rel="noopener noreferrer">Buka berita resmi &rarr;</a>
                    </article>
                    <article class="tax-info-card">
                        <span class="number">02 / PENGUMUMAN</span>
                        <h3>Pengumuman DJP</h3>
                        <p>Periksa pemberitahuan operasional, jadwal layanan, serta perubahan prosedur resmi sebelum melakukan pelaporan.</p>
                        <a href="https://www.pajak.go.id/id/pengumuman" target="_blank" rel="noopener noreferrer">Buka pengumuman &rarr;</a>
                    </article>
                    <article class="tax-info-card">
                        <span class="number">03 / LAYANAN</span>
                        <h3>Coretax DJP</h3>
                        <p>Akses sistem administrasi perpajakan resmi untuk layanan yang tersedia bagi wajib pajak.</p>
                        <a href="https://coretaxdjp.pajak.go.id/" target="_blank" rel="noopener noreferrer">Buka Coretax DJP &rarr;</a>
                    </article>
                </div>
            </section>

            <section class="tax-info-section">
                <div class="tax-info-section-head">
                    <h2>Sebelum menggunakan modul</h2>
                    <p>Siapkan informasi dasar berikut supaya simulasi pajak lebih mudah dipahami.</p>
                </div>
                <div class="tax-guide-grid">
                    <article class="tax-info-card">
                        <span class="number">01</span>
                        <h3>Penghasilan</h3>
                        <p>Siapkan nilai penghasilan bulanan yang akan digunakan sebagai dasar estimasi tahunan.</p>
                    </article>
                    <article class="tax-info-card">
                        <span class="number">02</span>
                        <h3>Biaya atau pengurang</h3>
                        <p>Catat biaya dan komponen pengurang yang relevan agar simulasi penghasilan neto lebih terarah.</p>
                    </article>
                    <article class="tax-info-card">
                        <span class="number">03</span>
                        <h3>Status PTKP</h3>
                        <p>Pastikan status wajib pajak dan tanggungan dipilih dengan benar pada formulir modul.</p>
                    </article>
                </div>
            </section>

            <section class="tax-login-panel">
                <div>
                    <h2>Siap membuat estimasi pajak?</h2>
                    <p>Modul perhitungan dilindungi login. Setelah berhasil masuk, Anda akan langsung diarahkan kembali ke halaman perpajakan.</p>
                </div>
                <a class="tax-info-button primary" href="{{ route('perpajakan.index') }}">Masuk dan Buka Modul</a>
            </section>

            <p class="tax-info-note">Informasi di halaman ini bersifat edukasi dan bukan nasihat pajak. Untuk keputusan pelaporan, gunakan ketentuan dan kanal resmi Direktorat Jenderal Pajak.</p>
        </main>
    </div>
@endsection
