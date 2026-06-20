@extends('layouts.app')

@section('title', 'Tutorial Stata - Smart Finance')
@section('body-class', 'stata-information-page')

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

        .stata-info-shell {
            min-height: 100vh;
            color: #f8fafc;
            background:
                linear-gradient(180deg, rgba(4, 17, 20, .78), #061316 680px),
                url('{{ asset('images/slidev3.jpg') }}') center top / cover no-repeat;
        }

        .stata-info-nav,
        .stata-info-main {
            width: min(1180px, calc(100% - 40px));
            margin: 0 auto;
        }

        .stata-info-nav {
            min-height: 84px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .stata-info-brand {
            color: #fff;
            font-size: 1.05rem;
            font-weight: 900;
            text-decoration: none;
        }

        .stata-info-nav-actions,
        .stata-info-actions {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
        }

        .stata-info-nav-actions a,
        .stata-info-button {
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

        .stata-info-nav-actions .primary,
        .stata-info-button.primary {
            border-color: #18bf75;
            color: #03261b;
            background: #18bf75;
        }

        .stata-info-main { padding: 70px 0 84px; }

        .stata-info-hero {
            max-width: 790px;
            padding: 52px 0 100px;
        }

        .stata-info-kicker {
            color: #f3c969;
            font-size: .82rem;
            font-weight: 900;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        .stata-info-hero h1 {
            max-width: 780px;
            margin: 18px 0 22px;
            font-size: clamp(2.8rem, 7vw, 5.5rem);
            line-height: 1;
            letter-spacing: 0;
        }

        .stata-info-hero p {
            max-width: 700px;
            margin: 0;
            color: rgba(248, 250, 252, .8);
            font-size: 1.08rem;
            line-height: 1.75;
        }

        .stata-info-actions { margin-top: 30px; }
        .stata-info-section { margin-top: 48px; }
        .stata-info-section-head { max-width: 760px; margin-bottom: 24px; }
        .stata-info-section h2 { margin: 0 0 10px; font-size: clamp(1.7rem, 4vw, 2.5rem); }
        .stata-info-section-head p { margin: 0; color: rgba(248, 250, 252, .66); line-height: 1.7; }

        .stata-info-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .stata-info-grid.four { grid-template-columns: repeat(4, minmax(0, 1fr)); }

        .stata-info-card {
            min-height: 220px;
            display: flex;
            flex-direction: column;
            padding: 24px;
            border: 1px solid rgba(148, 163, 184, .2);
            border-radius: 8px;
            background: rgba(12, 34, 36, .9);
        }

        .stata-info-card .number {
            color: #18bf75;
            font-size: .78rem;
            font-weight: 900;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .stata-info-card h3 { margin: 18px 0 10px; font-size: 1.18rem; }
        .stata-info-card p { margin: 0; color: rgba(248, 250, 252, .68); line-height: 1.65; }
        .stata-info-card a { margin-top: auto; padding-top: 24px; color: #f3c969; font-weight: 800; text-decoration: none; }

        .stata-command {
            display: inline-flex;
            width: fit-content;
            margin-top: 16px;
            padding: 7px 10px;
            border: 1px solid rgba(24, 191, 117, .24);
            border-radius: 6px;
            color: #8cf0bf;
            background: #071b1e;
            font: 700 .84rem Consolas, monospace;
        }

        .stata-learning-path {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            border: 1px solid rgba(148, 163, 184, .2);
            border-radius: 8px;
            overflow: hidden;
            background: rgba(12, 34, 36, .88);
        }

        .stata-step { padding: 24px; border-right: 1px solid rgba(148, 163, 184, .18); }
        .stata-step:last-child { border-right: 0; }
        .stata-step strong { display: block; color: #f3c969; font-size: .8rem; margin-bottom: 14px; }
        .stata-step h3 { margin: 0 0 8px; font-size: 1.05rem; }
        .stata-step p { margin: 0; color: rgba(248, 250, 252, .64); line-height: 1.55; }

        .stata-login-panel {
            margin-top: 48px;
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 28px;
            padding: 30px;
            border: 1px solid rgba(24, 191, 117, .34);
            border-radius: 8px;
            background: #0d292a;
        }

        .stata-login-panel h2 { margin: 0 0 8px; font-size: 1.55rem; }
        .stata-login-panel p { margin: 0; color: rgba(248, 250, 252, .68); line-height: 1.6; }

        .stata-info-note {
            margin-top: 22px;
            color: rgba(248, 250, 252, .5);
            font-size: .84rem;
            line-height: 1.6;
        }

        @media (max-width: 940px) {
            .stata-info-grid,
            .stata-info-grid.four { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .stata-learning-path { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .stata-step:nth-child(2) { border-right: 0; }
            .stata-step:nth-child(-n+2) { border-bottom: 1px solid rgba(148, 163, 184, .18); }
        }

        @media (max-width: 680px) {
            .stata-info-grid,
            .stata-info-grid.four,
            .stata-learning-path,
            .stata-login-panel { grid-template-columns: 1fr; }
            .stata-info-card { min-height: 0; }
            .stata-step { border-right: 0; border-bottom: 1px solid rgba(148, 163, 184, .18); }
            .stata-step:last-child { border-bottom: 0; }
            .stata-login-panel .stata-info-button { width: 100%; }
        }

        @media (max-width: 560px) {
            .stata-info-nav,
            .stata-info-main { width: min(100% - 28px, 1180px); }
            .stata-info-nav { align-items: flex-start; flex-direction: column; padding: 18px 0; }
            .stata-info-nav-actions { width: 100%; }
            .stata-info-nav-actions a { flex: 1; padding-inline: 12px; }
            .stata-info-main { padding-top: 26px; }
            .stata-info-hero { padding: 36px 0 70px; }
            .stata-info-actions .stata-info-button { width: 100%; }
            .stata-info-card,
            .stata-login-panel { padding: 20px; }
        }
    </style>

    <div class="stata-info-shell">
        <nav class="stata-info-nav" aria-label="Navigasi tutorial Stata">
            <a class="stata-info-brand" href="{{ route('home') }}">SMART FINANCE</a>
            <div class="stata-info-nav-actions">
                <a href="{{ route('home') }}">Beranda</a>
                @auth
                    <a class="primary" href="{{ route('stata') }}">Buka Modul</a>
                @else
                    <a class="primary" href="{{ route('stata') }}">Login</a>
                @endauth
            </div>
        </nav>

        <main class="stata-info-main">
            <section class="stata-info-hero">
                <span class="stata-info-kicker">Panduan Analisis Data</span>
                <h1>Kenali Stata, lalu mulai menganalisis.</h1>
                <p>Stata adalah perangkat lunak statistik untuk mengelola data, menjalankan analisis, membuat grafik, dan mendokumentasikan proses penelitian. Pelajari dasarnya di halaman publik ini, lalu login ketika ingin membuka modul praktik.</p>
                <div class="stata-info-actions">
                    <a class="stata-info-button primary" href="{{ route('stata') }}">Masuk ke Modul Stata <span aria-hidden="true">&rarr;</span></a>
                    <a class="stata-info-button" href="#materi">Mulai dari Materi Dasar</a>
                </div>
            </section>

            <section class="stata-info-section">
                <div class="stata-info-section-head">
                    <h2>Fungsi utama Stata</h2>
                    <p>Dari pembersihan data sampai pemodelan ekonometrika, Stata membantu menjaga proses analisis tetap terstruktur dan dapat diulang.</p>
                </div>
                <div class="stata-info-grid">
                    <article class="stata-info-card">
                        <span class="number">01 / Data Management</span>
                        <h3>Mengelola dan membersihkan data</h3>
                        <p>Mengimpor data, mengubah variabel, menangani nilai kosong, menggabungkan dataset, dan menyiapkan data untuk dianalisis.</p>
                    </article>
                    <article class="stata-info-card">
                        <span class="number">02 / Statistics</span>
                        <h3>Analisis statistik</h3>
                        <p>Menjalankan statistik deskriptif, uji hipotesis, korelasi, regresi, data panel, deret waktu, dan metode lainnya.</p>
                    </article>
                    <article class="stata-info-card">
                        <span class="number">03 / Reporting</span>
                        <h3>Grafik dan pelaporan</h3>
                        <p>Membuat visualisasi, tabel hasil, serta do-file agar langkah penelitian dapat diperiksa dan dijalankan kembali.</p>
                    </article>
                </div>
            </section>

            <section class="stata-info-section" id="materi">
                <div class="stata-info-section-head">
                    <h2>Materi yang perlu dipelajari</h2>
                    <p>Urutan ini cocok untuk pengguna baru, mahasiswa ekonomi, maupun peneliti yang ingin membangun fondasi analisis yang rapi.</p>
                </div>
                <div class="stata-info-grid">
                    <article class="stata-info-card"><span class="number">Materi 01</span><h3>Antarmuka dan do-file</h3><p>Mengenal Command, Results, Variables, Properties, serta cara menyimpan rangkaian perintah dalam do-file.</p><code class="stata-command">do analysis.do</code></article>
                    <article class="stata-info-card"><span class="number">Materi 02</span><h3>Impor dan inspeksi data</h3><p>Membuka dataset, membaca struktur variabel, dan memeriksa kualitas awal data.</p><code class="stata-command">describe</code></article>
                    <article class="stata-info-card"><span class="number">Materi 03</span><h3>Pembersihan data</h3><p>Menyaring observasi, membuat variabel, mengganti nilai, dan memberi label yang mudah dipahami.</p><code class="stata-command">generate</code></article>
                    <article class="stata-info-card"><span class="number">Materi 04</span><h3>Statistik deskriptif</h3><p>Meringkas distribusi data melalui mean, median, standar deviasi, frekuensi, dan tabulasi.</p><code class="stata-command">summarize</code></article>
                    <article class="stata-info-card"><span class="number">Materi 05</span><h3>Uji dan regresi</h3><p>Mempelajari korelasi, uji beda, regresi linear, interpretasi koefisien, dan diagnostik model.</p><code class="stata-command">regress y x1 x2</code></article>
                    <article class="stata-info-card"><span class="number">Materi 06</span><h3>Visualisasi hasil</h3><p>Mengubah pola data dan hasil analisis menjadi grafik yang lebih mudah dibaca.</p><code class="stata-command">twoway scatter y x</code></article>
                </div>
            </section>

            <section class="stata-info-section">
                <div class="stata-info-section-head">
                    <h2>Tools dan kelompok command</h2>
                    <p>Beberapa command dasar berikut menjadi bekal utama sebelum beralih ke analisis yang lebih kompleks.</p>
                </div>
                <div class="stata-info-grid four">
                    <article class="stata-info-card"><span class="number">Dataset</span><h3>Buka dan simpan</h3><p><strong>use</strong>, <strong>save</strong>, <strong>import excel</strong>, dan <strong>export excel</strong> digunakan untuk perpindahan data.</p></article>
                    <article class="stata-info-card"><span class="number">Variabel</span><h3>Ubah data</h3><p><strong>generate</strong>, <strong>replace</strong>, <strong>rename</strong>, <strong>recode</strong>, dan <strong>label</strong> mengelola variabel.</p></article>
                    <article class="stata-info-card"><span class="number">Observasi</span><h3>Pilih data</h3><p><strong>keep</strong>, <strong>drop</strong>, <strong>sort</strong>, <strong>by</strong>, dan kondisi <strong>if</strong> membantu memilih observasi.</p></article>
                    <article class="stata-info-card"><span class="number">Analisis</span><h3>Uji dan model</h3><p><strong>tabulate</strong>, <strong>ttest</strong>, <strong>correlate</strong>, <strong>regress</strong>, dan <strong>predict</strong> digunakan dalam analisis.</p></article>
                </div>
            </section>

            <section class="stata-info-section">
                <div class="stata-info-section-head">
                    <h2>Alur kerja yang disarankan</h2>
                    <p>Gunakan tahapan singkat ini agar analisis mudah ditelusuri dan hasilnya tidak bergantung pada langkah manual.</p>
                </div>
                <div class="stata-learning-path">
                    <article class="stata-step"><strong>LANGKAH 01</strong><h3>Tentukan pertanyaan</h3><p>Tulis tujuan analisis, variabel, dan hipotesis sebelum menyentuh data.</p></article>
                    <article class="stata-step"><strong>LANGKAH 02</strong><h3>Periksa data</h3><p>Kenali struktur, tipe variabel, nilai kosong, dan kemungkinan kesalahan.</p></article>
                    <article class="stata-step"><strong>LANGKAH 03</strong><h3>Jalankan analisis</h3><p>Pilih uji atau model yang sesuai dengan pertanyaan dan karakteristik data.</p></article>
                    <article class="stata-step"><strong>LANGKAH 04</strong><h3>Simpan proses</h3><p>Gunakan do-file, log, tabel, dan grafik agar analisis dapat direplikasi.</p></article>
                </div>
            </section>

            <section class="stata-info-section">
                <div class="stata-info-section-head">
                    <h2>Sumber belajar resmi</h2>
                    <p>Gunakan dokumentasi resmi untuk memeriksa sintaks, contoh, dan kemampuan Stata yang tersedia pada versi Anda.</p>
                </div>
                <div class="stata-info-grid">
                    <article class="stata-info-card"><span class="number">Official</span><h3>Fitur Stata</h3><p>Pelajari cakupan analisis, manajemen data, visualisasi, dan fitur penelitian lainnya.</p><a href="https://www.stata.com/features/" target="_blank" rel="noopener noreferrer">Lihat fitur resmi &rarr;</a></article>
                    <article class="stata-info-card"><span class="number">Official</span><h3>Learning Resources</h3><p>Akses materi belajar, webinar, video, dan panduan untuk berbagai tingkat kemampuan.</p><a href="https://www.stata.com/learn/" target="_blank" rel="noopener noreferrer">Buka pusat belajar &rarr;</a></article>
                    <article class="stata-info-card"><span class="number">Official</span><h3>Dokumentasi</h3><p>Temukan manual referensi command dan penjelasan metode langsung dari Stata.</p><a href="https://www.stata.com/features/documentation/" target="_blank" rel="noopener noreferrer">Buka dokumentasi &rarr;</a></article>
                </div>
            </section>

            <section class="stata-login-panel">
                <div>
                    <h2>Siap mencoba analisis?</h2>
                    <p>Modul Stata dilindungi login. Setelah berhasil masuk, Anda akan langsung diarahkan kembali ke ruang praktik Stata.</p>
                </div>
                <a class="stata-info-button primary" href="{{ route('stata') }}">Masuk dan Buka Modul</a>
            </section>

            <p class="stata-info-note">Stata adalah merek dagang StataCorp LLC. Halaman ini merupakan materi pengantar independen untuk tujuan pembelajaran.</p>
        </main>
    </div>
@endsection
