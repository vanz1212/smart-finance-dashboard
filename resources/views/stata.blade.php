@extends('layouts.app')

@section('title', __('stata.page_title'))
@section('body-class', 'module-page')

@section('content')
    @php
        $stataCommandGroups = [
            'Data dan File' => [
                ['cmd' => 'use', 'desc' => 'Membuka file data Stata berekstensi .dta.', 'example' => 'use data_keuangan.dta, clear'],
                ['cmd' => 'import excel', 'desc' => 'Mengambil data dari file Excel ke Stata.', 'example' => 'import excel "data.xlsx", firstrow clear'],
                ['cmd' => 'import delimited', 'desc' => 'Mengambil data CSV, TSV, atau file teks berdelimiter.', 'example' => 'import delimited "data.csv", clear'],
                ['cmd' => 'save', 'desc' => 'Menyimpan dataset aktif menjadi file .dta.', 'example' => 'save data_bersih.dta, replace'],
                ['cmd' => 'describe', 'desc' => 'Menampilkan struktur dataset, nama variabel, tipe data, dan label.', 'example' => 'describe'],
                ['cmd' => 'codebook', 'desc' => 'Memeriksa isi variabel, missing value, rentang nilai, dan label.', 'example' => 'codebook income expense'],
                ['cmd' => 'list', 'desc' => 'Menampilkan observasi tertentu dalam bentuk tabel.', 'example' => 'list income expense in 1/10'],
                ['cmd' => 'browse', 'desc' => 'Membuka data editor dalam mode lihat data.', 'example' => 'browse income expense saving'],
            ],
            'Membuat dan Mengubah Variabel' => [
                ['cmd' => 'generate', 'desc' => 'Membuat variabel baru dari rumus atau nilai tertentu.', 'example' => 'generate saving_rate = saving / income'],
                ['cmd' => 'replace', 'desc' => 'Mengubah nilai variabel yang sudah ada.', 'example' => 'replace saving_rate = 0 if saving_rate < 0'],
                ['cmd' => 'egen', 'desc' => 'Membuat variabel baru dengan fungsi tambahan seperti mean grup atau total.', 'example' => 'bysort region: egen avg_income = mean(income)'],
                ['cmd' => 'recode', 'desc' => 'Mengelompokkan ulang nilai variabel numerik.', 'example' => 'recode age (18/25=1) (26/40=2), gen(age_group)'],
                ['cmd' => 'encode', 'desc' => 'Mengubah variabel string kategori menjadi numerik berlabel.', 'example' => 'encode province, gen(province_id)'],
                ['cmd' => 'rename', 'desc' => 'Mengganti nama variabel.', 'example' => 'rename monthly_income income'],
                ['cmd' => 'label variable', 'desc' => 'Memberi label deskriptif pada variabel.', 'example' => 'label variable income "Pendapatan bulanan"'],
                ['cmd' => 'keep / drop', 'desc' => 'Memilih variabel atau observasi yang dipertahankan atau dihapus.', 'example' => 'keep income expense saving'],
            ],
            'Membersihkan dan Menyusun Data' => [
                ['cmd' => 'sort', 'desc' => 'Mengurutkan data berdasarkan satu atau beberapa variabel.', 'example' => 'sort year province'],
                ['cmd' => 'bysort', 'desc' => 'Menjalankan command per kelompok setelah data diurutkan.', 'example' => 'bysort province: summarize income'],
                ['cmd' => 'duplicates', 'desc' => 'Mendeteksi atau menghapus data duplikat.', 'example' => 'duplicates report id year'],
                ['cmd' => 'merge', 'desc' => 'Menggabungkan dataset berdasarkan key atau ID yang sama.', 'example' => 'merge 1:1 id using data_demografi.dta'],
                ['cmd' => 'append', 'desc' => 'Menambahkan baris observasi dari dataset lain.', 'example' => 'append using data_2025.dta'],
                ['cmd' => 'reshape', 'desc' => 'Mengubah format data wide ke long atau sebaliknya.', 'example' => 'reshape long income expense, i(id) j(year)'],
                ['cmd' => 'collapse', 'desc' => 'Membuat dataset ringkasan berdasarkan statistik tertentu.', 'example' => 'collapse (mean) income expense, by(province)'],
                ['cmd' => 'compress', 'desc' => 'Menghemat ukuran dataset dengan menyesuaikan tipe data.', 'example' => 'compress'],
            ],
            __('stata.desc_stats') . ' dan Tabel' => [
                ['cmd' => 'summarize', 'desc' => 'Menghasilkan mean, standar deviasi, minimum, maksimum, dan jumlah observasi.', 'example' => 'summarize income expense saving'],
                ['cmd' => 'tabulate', 'desc' => 'Membuat tabel frekuensi satu atau dua variabel.', 'example' => 'tabulate education gender, row'],
                ['cmd' => 'tabstat', 'desc' => 'Membuat tabel statistik ringkas yang lebih fleksibel.', 'example' => 'tabstat income, stat(mean sd min max) by(region)'],
                ['cmd' => 'table', 'desc' => 'Membuat tabel modern untuk frekuensi, ringkasan, dan hasil command.', 'example' => 'table region, statistic(mean income) statistic(sd income)'],
                ['cmd' => 'correlate', 'desc' => 'Menghitung korelasi antar variabel numerik.', 'example' => 'correlate gdp inflation unemployment'],
                ['cmd' => 'pwcorr', 'desc' => 'Menghitung korelasi pairwise, sering dipakai dengan signifikansi.', 'example' => 'pwcorr income saving expense, sig'],
            ],
            'Grafik' => [
                ['cmd' => 'histogram', 'desc' => 'Membuat histogram distribusi variabel numerik.', 'example' => 'histogram income, normal'],
                ['cmd' => 'scatter', 'desc' => 'Membuat scatter plot dua variabel.', 'example' => 'scatter saving income'],
                ['cmd' => 'twoway', 'desc' => 'Membuat grafik gabungan seperti scatter dan garis fitted.', 'example' => 'twoway (scatter saving income) (lfit saving income)'],
                ['cmd' => 'graph bar', 'desc' => 'Membuat grafik batang berdasarkan kategori.', 'example' => 'graph bar income, over(region)'],
                ['cmd' => 'graph box', 'desc' => 'Membuat box plot untuk melihat sebaran dan outlier.', 'example' => 'graph box income, over(region)'],
            ],
            'Regresi, Uji, dan Postestimation' => [
                ['cmd' => 'regress', 'desc' => 'Menjalankan regresi linear OLS.', 'example' => 'regress saving income expense'],
                ['cmd' => 'logit', 'desc' => 'Model regresi logistik untuk variabel dependen biner.', 'example' => 'logit default income debt_ratio'],
                ['cmd' => 'probit', 'desc' => 'Model probit untuk outcome biner.', 'example' => 'probit default income debt_ratio'],
                ['cmd' => 'poisson', 'desc' => 'Model regresi untuk data hitungan.', 'example' => 'poisson claims income age'],
                ['cmd' => 'anova', 'desc' => 'Analisis varians untuk membandingkan rata-rata antar grup.', 'example' => 'anova income region education'],
                ['cmd' => 'ttest', 'desc' => 'Uji beda rata-rata satu sampel, dua sampel, atau berpasangan.', 'example' => 'ttest income, by(gender)'],
                ['cmd' => 'swilk', 'desc' => 'Uji normalitas Shapiro-Wilk.', 'example' => 'swilk income'],
                ['cmd' => 'test', 'desc' => 'Uji hipotesis linear setelah estimasi model.', 'example' => 'test income = expense'],
                ['cmd' => 'margins', 'desc' => 'Menghitung prediksi, marginal means, atau marginal effects setelah model.', 'example' => 'margins, dydx(income)'],
                ['cmd' => 'predict', 'desc' => 'Membuat nilai prediksi atau residual setelah estimasi.', 'example' => 'predict yhat, xb'],
            ],
            'Panel dan Time Series' => [
                ['cmd' => 'xtset', 'desc' => 'Mendeklarasikan struktur data panel.', 'example' => 'xtset firm_id year'],
                ['cmd' => 'xtreg', 'desc' => 'Regresi data panel fixed effects atau random effects.', 'example' => 'xtreg profit investment inflation, fe'],
                ['cmd' => 'hausman', 'desc' => 'Membandingkan estimator fixed effects dan random effects.', 'example' => 'hausman fixed random'],
                ['cmd' => 'tsset', 'desc' => 'Mendeklarasikan struktur data time series.', 'example' => 'tsset year'],
                ['cmd' => 'arima', 'desc' => 'Model ARIMA untuk analisis deret waktu.', 'example' => 'arima inflation, arima(1,1,1)'],
                ['cmd' => 'dfuller', 'desc' => 'Augmented Dickey-Fuller test untuk uji unit root.', 'example' => 'dfuller inflation, lags(1)'],
            ],
            'Workflow dan Output' => [
                ['cmd' => 'log using', 'desc' => 'Merekam output sesi Stata ke file log.', 'example' => 'log using hasil_analisis.log, replace'],
                ['cmd' => 'do', 'desc' => 'Menjalankan file do-file berisi kumpulan command.', 'example' => 'do analisis_keuangan.do'],
                ['cmd' => 'set more off', 'desc' => 'Mencegah output berhenti per halaman saat script berjalan.', 'example' => 'set more off'],
                ['cmd' => 'estimates store', 'desc' => 'Menyimpan hasil estimasi model untuk dibandingkan.', 'example' => 'estimates store model1'],
                ['cmd' => 'estimates table', 'desc' => 'Menampilkan beberapa hasil estimasi dalam satu tabel.', 'example' => 'estimates table model1 model2, stats(N r2)'],
                ['cmd' => 'help', 'desc' => 'Membuka dokumentasi command dari dalam Stata.', 'example' => 'help regress'],
            ],
        ];
    @endphp

    <style>
        .stata-workspace {
            margin: -24px;
            min-height: calc(100vh - 1px);
            padding: 34px 24px 56px;
            color: var(--text-main);
            background:
                linear-gradient(180deg, var(--bg-primary), var(--bg-primary)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;
        }

        .stata-inner { width: min(1180px, 100%); margin: 0 auto; }

        .stata-hero {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(280px, .45fr);
            gap: 24px;
            align-items: stretch;
            margin-bottom: 24px;
        }

        .stata-panel {
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(13,47,51,.78), rgba(6,24,32,.84));
            box-shadow: 0 28px 80px rgba(0,0,0,.34);
            backdrop-filter: blur(16px);
        }

        .stata-panel-inner { padding: 26px; }
        .stata-kicker { color: var(--accent-primary); font-size: .8rem; font-weight: 900; letter-spacing: .12em; text-transform: uppercase; }
        .stata-hero h1 { margin: 14px 0 0; font-size: clamp(2.4rem, 6vw, 5rem); line-height: .98; letter-spacing: 0; }
        .stata-hero p { max-width: 720px; margin: 18px 0 0; color: rgba(248,250,252,.72); line-height: 1.7; }
        .stata-action { display: inline-flex; align-items: center; justify-content: center; min-height: 48px; margin-top: 26px; padding: 0 20px; border-radius: 999px; background: var(--accent-primary); color: var(--accent-hover); text-decoration: none; font-weight: 900; }
        .stata-stat { display: grid; align-content: center; gap: 18px; }
        .stata-stat strong { display: block; font-size: 2.4rem; color: #fff; }
        .stata-stat span { color: rgba(248,250,252,.66); line-height: 1.5; }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .feature-card {
            min-height: 230px;
            padding: 24px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 14px;
            background: linear-gradient(180deg, rgba(255,255,255,.07), rgba(243,201,105,.07));
            box-shadow: 0 22px 60px rgba(0,0,0,.24);
        }

        .feature-card small { color: var(--accent-primary); font-weight: 900; }
        .feature-card h2 { margin: 42px 0 12px; font-size: 1.45rem; line-height: 1.05; }
        .feature-card p { margin: 0; color: rgba(248,250,252,.68); line-height: 1.65; }

        .stata-console {
            margin-top: 22px;
            padding: 22px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 14px;
            background: rgba(0,0,0,.34);
            font-family: Consolas, 'Courier New', monospace;
            color: rgba(248,250,252,.82);
            line-height: 1.7;
        }

        .stata-console span { color: var(--accent-primary); }

        .stata-data-panel {
            margin-top: 22px;
        }

        .stata-data-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-top: 16px;
        }

        .stata-data-card {
            padding: 16px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 12px;
            background: rgba(255,255,255,.06);
        }

        .stata-data-card span {
            display: block;
            color: rgba(248,250,252,.56);
            font-size: .82rem;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .stata-data-card strong {
            color: #fff;
            font-size: 1.25rem;
        }

        .stata-output-table {
            width: 100%;
            margin-top: 18px;
            border-collapse: collapse;
            overflow: hidden;
            border-radius: 12px;
        }

        .stata-output-table th,
        .stata-output-table td {
            padding: 12px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            text-align: left;
            color: rgba(248,250,252,.76);
        }

        .stata-output-table th {
            color: #fff;
            background: rgba(255,255,255,.06);
        }

        .stata-command-reference {
            margin-top: 22px;
        }

        .stata-tutorial {
            margin-top: 22px;
        }

        .tutorial-intro {
            max-width: 760px;
            margin: 12px 0 24px;
            color: rgba(248,250,252,.72);
            line-height: 1.7;
        }

        .tutorial-steps {
            display: grid;
            gap: 14px;
        }

        .tutorial-step {
            display: grid;
            grid-template-columns: 48px minmax(0, 1fr);
            gap: 16px;
            padding: 20px;
            border: 1px solid rgba(255,255,255,.13);
            border-radius: 14px;
            background: rgba(255,255,255,.045);
        }

        .tutorial-number {
            width: 48px;
            height: 48px;
            display: grid;
            place-items: center;
            border-radius: 8px;
            background: var(--accent-primary);
            color: var(--accent-hover);
            font-weight: 900;
        }

        .tutorial-content h3 {
            margin: 2px 0 8px;
            color: var(--text-main);
            font-size: 1.12rem;
        }

        .tutorial-content p {
            margin: 0;
            color: rgba(248,250,252,.7);
            line-height: 1.65;
        }

        .tutorial-code {
            margin: 14px 0 0;
            overflow-x: auto;
            padding: 14px 16px;
            border-radius: 10px;
            background: rgba(0,0,0,.42);
            color: var(--text-main);
            font-family: Consolas, 'Courier New', monospace;
            font-size: .9rem;
            line-height: 1.65;
            white-space: pre;
        }

        .tutorial-tip {
            margin-top: 12px;
            padding: 11px 13px;
            border-left: 3px solid var(--accent-primary);
            background: rgba(20,184,166,.08);
            color: rgba(248,250,252,.74);
            line-height: 1.6;
        }

        .tutorial-checklist {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 18px;
        }

        .tutorial-checklist div {
            padding: 14px;
            border: 1px solid rgba(20,184,166,.26);
            border-radius: 10px;
            background: rgba(20,184,166,.07);
            color: rgba(248,250,252,.78);
            line-height: 1.55;
        }

        .stata-section-heading {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: end;
            margin-bottom: 18px;
        }

        .stata-section-heading h2 {
            margin: 8px 0 0;
            font-size: clamp(1.6rem, 4vw, 2.7rem);
            line-height: 1;
            letter-spacing: 0;
        }

        .stata-section-heading p {
            max-width: 520px;
            margin: 0;
            color: rgba(248,250,252,.68);
            line-height: 1.65;
        }

        .stata-command-groups {
            display: grid;
            gap: 18px;
        }

        .stata-command-group {
            padding: 20px;
            border: 1px solid rgba(255,255,255,.14);
            border-radius: 14px;
            background: rgba(255,255,255,.055);
        }

        .stata-command-group h3 {
            margin: 0 0 16px;
            color: var(--accent-primary);
            font-size: 1.12rem;
        }

        .stata-command-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .stata-command-item {
            display: grid;
            gap: 10px;
            padding: 15px;
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px;
            background: rgba(5, 12, 15, .34);
        }

        .stata-command-item code {
            width: fit-content;
            padding: 6px 9px;
            border-radius: 8px;
            background: rgba(20, 184, 166, .14);
            color: #5eead4;
            font-family: Consolas, 'Courier New', monospace;
            font-weight: 900;
        }

        .stata-command-item p {
            margin: 0;
            color: rgba(248,250,252,.7);
            line-height: 1.55;
        }

        .stata-command-item pre {
            margin: 0;
            overflow-x: auto;
            padding: 11px 12px;
            border-radius: 8px;
            background: rgba(0,0,0,.36);
            color: var(--text-main);
            font-family: Consolas, 'Courier New', monospace;
            font-size: .88rem;
            line-height: 1.55;
        }

        .stata-source-note {
            margin-top: 16px;
            padding: 14px 16px;
            border: 1px solid rgba(20, 184, 166, .32);
            border-radius: 12px;
            background: rgba(20, 184, 166, .08);
            color: rgba(248,250,252,.72);
            line-height: 1.65;
        }

        .stata-source-note a {
            color: var(--accent-primary);
            font-weight: 900;
            text-decoration: none;
        }

        .stata-source-note a:hover {
            text-decoration: underline;
        }

        @media (max-width: 900px) {
            .stata-topbar { align-items: flex-start; flex-direction: column; }
            .stata-hero, .feature-grid, .stata-data-grid, .stata-command-list, .tutorial-checklist { grid-template-columns: 1fr; }
            .stata-section-heading { align-items: flex-start; flex-direction: column; }
        }

        @media (max-width: 620px) {
            .stata-workspace { margin: -24px; padding-inline: 14px; }
            .tutorial-step { grid-template-columns: 1fr; }
        }
        /* Full-page refinement: keep the standalone Stata page aligned with the main selector UI. */
        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            overflow-x: hidden;
            background:
                radial-gradient(circle at 82% 0%, rgba(24, 191, 117, .16), transparent 34%),
                linear-gradient(135deg, #06191b 0%, #071f22 48%, #091011 100%) !important;
            color: var(--text-main);
        }

        body {
            display: block;
        }

        body::before {
            opacity: .16 !important;
        }

        .page-shell,
        .container,
        .dashboard-shell,
        .stata-shell,
        main {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            box-sizing: border-box;
        }

        .page-shell,
        .container,
        .dashboard-shell,
        .stata-shell {
            padding-left: clamp(18px, 4vw, 56px) !important;
            padding-right: clamp(18px, 4vw, 56px) !important;
        }

        header,
        .topbar,
        .navbar,
        nav {
            max-width: none !important;
            width: 100% !important;
            box-sizing: border-box;
        }

        .hero,
        .hero-grid,
        .stats-hero,
        .content-grid,
        .feature-grid,
        .cards-grid,
        .module-grid {
            width: 100% !important;
            max-width: none !important;
            box-sizing: border-box;
        }

        .hero,
        .stats-hero {
            min-height: auto !important;
            margin-top: clamp(18px, 3vw, 36px) !important;
        }

        .hero h1,
        .stats-hero h1,
        h1 {
            max-width: 100%;
            font-size: clamp(42px, 8vw, 96px) !important;
            line-height: .95 !important;
            letter-spacing: -.06em;
        }

        .panel,
        .card,
        .feature-card,
        .dataset-card,
        .metric-card,
        .hero-card {
            border: 1px solid rgba(148, 163, 184, .22) !important;
            background: rgba(12, 34, 36, .88) !important;
            box-shadow: none !important;
            backdrop-filter: blur(12px);
        }

        .nav-links,
        .menu,
        .tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: flex-end;
        }

        .nav-links a,
        .menu a,
        .tabs a,
        .btn,
        button,
        [role="button"] {
            white-space: normal;
        }

        table {
            width: 100%;
        }

        .table-wrap,
        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 900px) {
            .hero,
            .hero-grid,
            .stats-hero,
            .content-grid {
                grid-template-columns: 1fr !important;
            }

            header,
            .topbar,
            .navbar {
                align-items: flex-start !important;
                gap: 14px !important;
            }

            .nav-links,
            .menu,
            .tabs {
                justify-content: flex-start;
            }
        }

        @media (max-width: 620px) {
            .page-shell,
            .container,
            .dashboard-shell,
            .stata-shell {
                padding-left: 14px !important;
                padding-right: 14px !important;
            }

            .hero h1,
            .stats-hero h1,
            h1 {
                font-size: clamp(34px, 13vw, 56px) !important;
            }
        }

        .stata-workbench {
            margin-top: 24px;
            scroll-margin-top: 100px;
        }

        .workbench-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 24px;
            margin-bottom: 22px;
        }

        .workbench-heading h2 {
            margin: 10px 0 0;
            font-size: clamp(1.8rem, 4vw, 3rem);
        }

        .workbench-heading p {
            max-width: 680px;
            margin: 0;
            color: rgba(248,250,252,.72);
            line-height: 1.65;
        }

        .stata-flash,
        .stata-error {
            margin-bottom: 16px;
            padding: 13px 15px;
            border: 1px solid rgba(32,189,122,.36);
            border-radius: 10px;
            background: rgba(32,189,122,.1);
            color: #d1fae5;
            font-weight: 800;
        }

        .stata-error {
            border-color: rgba(251,113,133,.38);
            background: rgba(251,113,133,.1);
            color: #ffe4e6;
        }

        .stata-import-grid {
            display: grid;
            grid-template-columns: minmax(0,1.35fr) minmax(280px,.65fr);
            gap: 18px;
        }

        .stata-upload-card,
        .stata-dataset-card,
        .stata-variable-panel,
        .stata-editor-panel,
        .stata-result-panel {
            padding: 22px;
            border: 1px solid rgba(164,190,190,.2);
            border-radius: 14px;
            background: rgba(8,34,37,.96);
        }

        .stata-upload-card h3,
        .stata-dataset-card h3,
        .stata-variable-panel h3,
        .stata-editor-panel h3,
        .stata-result-panel h3 {
            margin: 0;
        }

        .stata-file-drop {
            min-height: 150px;
            display: grid;
            place-items: center;
            margin-top: 16px;
            padding: 24px;
            border: 1px dashed rgba(230,196,109,.55);
            border-radius: 12px;
            background: rgba(230,196,109,.055);
            text-align: center;
            cursor: pointer;
        }

        .stata-file-drop input {
            width: min(100%, 460px);
            color: #c8d4d3;
        }

        .stata-file-drop small {
            display: block;
            margin-top: 10px;
            color: #9fb0af;
        }

        .stata-import-button,
        .stata-clear-button,
        .stata-command-button,
        .variable-tool {
            min-height: 42px;
            border: 1px solid rgba(230,196,109,.45);
            border-radius: 10px;
            padding: 0 16px;
            background: #e6c46d;
            color: #092c2d;
            font: inherit;
            font-weight: 900;
            cursor: pointer;
        }

        .stata-import-button {
            width: 100%;
            margin-top: 14px;
        }

        .stata-clear-button,
        .variable-tool {
            border-color: rgba(164,190,190,.22);
            background: rgba(255,255,255,.06);
            color: #dbe5e4;
        }

        .dataset-facts {
            display: grid;
            grid-template-columns: repeat(2,minmax(0,1fr));
            gap: 10px;
            margin: 16px 0;
        }

        .dataset-facts div {
            padding: 13px;
            border: 1px solid rgba(164,190,190,.16);
            border-radius: 10px;
            background: rgba(255,255,255,.045);
        }

        .dataset-facts span,
        .dataset-facts strong {
            display: block;
        }

        .dataset-facts span {
            margin-bottom: 5px;
            color: #9fb0af;
            font-size: .76rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .stata-command-layout {
            display: grid;
            grid-template-columns: minmax(260px,.32fr) minmax(0,1fr);
            gap: 18px;
            margin-top: 18px;
        }

        .variable-tools,
        .stata-command-bar {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .variable-tools { margin: 14px 0; }

        .variable-tool {
            min-height: 34px;
            padding-inline: 10px;
            font-size: .78rem;
        }

        .stata-variable-list {
            max-height: 390px;
            display: grid;
            gap: 7px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .stata-variable-option {
            display: grid;
            grid-template-columns: auto minmax(0,1fr) auto;
            gap: 10px;
            align-items: center;
            padding: 10px;
            border: 1px solid rgba(164,190,190,.13);
            border-radius: 9px;
            background: rgba(255,255,255,.035);
            cursor: pointer;
        }

        .stata-variable-option input { accent-color: #20bd7a; }
        .stata-variable-option strong { overflow: hidden; text-overflow: ellipsis; }
        .stata-variable-option small { color: #91a5a4; }
        .variable-type { color: #e6c46d; font-family: Consolas,monospace; font-size: .75rem; }

        .stata-editor-panel { min-width: 0; }
        .stata-command-bar { margin: 16px 0 18px; }

        .stata-command-button {
            min-height: 40px;
            background: rgba(230,196,109,.1);
            color: #f0d98f;
        }

        .stata-command-button:hover {
            border-color: #e6c46d;
            background: rgba(230,196,109,.17);
        }

        .stata-sort-direction {
            min-height: 40px;
            border: 1px solid rgba(164,190,190,.2);
            border-radius: 10px;
            padding: 0 10px;
            background: rgba(3,20,22,.8);
            color: var(--text-main);
        }

        .stata-table-wrap {
            width: 100%;
            overflow: auto;
            border: 1px solid rgba(164,190,190,.16);
            border-radius: 11px;
        }

        .stata-live-table {
            width: 100%;
            border-collapse: collapse;
            white-space: nowrap;
        }

        .stata-live-table th,
        .stata-live-table td {
            padding: 10px 12px;
            border-right: 1px solid rgba(164,190,190,.1);
            border-bottom: 1px solid rgba(164,190,190,.1);
            text-align: left;
        }

        .stata-live-table th {
            position: sticky;
            top: 0;
            z-index: 1;
            background: #173c3e;
            color: var(--text-main);
            font-size: .78rem;
        }

        .stata-live-table td { color: #c7d4d3; }

        .stata-result-panel {
            margin-top: 18px;
            scroll-margin-top: 100px;
        }

        .stata-result-command {
            display: block;
            margin: 14px 0 8px;
            padding: 11px 13px;
            border-radius: 9px;
            background: #031416;
            color: #5eead4;
            font-family: Consolas,'Courier New',monospace;
        }

        @media (max-width: 900px) {
            .workbench-heading { align-items: flex-start; flex-direction: column; }
            .stata-import-grid, .stata-command-layout { grid-template-columns: 1fr; }
        }

        @media (max-width: 560px) {
            .dataset-facts { grid-template-columns: 1fr; }
            .stata-upload-card, .stata-dataset-card, .stata-variable-panel, .stata-editor-panel, .stata-result-panel { padding: 16px; }
        }
    </style>

    @include('partials.module-shell-styles')

    <main class="stata-workspace">
        <div class="stata-inner">
            <section class="stata-hero">
                <div class="stata-panel stata-panel-inner">
                    <span class="stata-kicker">Economic Analysis</span>
                    <h1>{{ __('stata.title') }}</h1>
                    <p>{{ __('stata.hero_desc') }}</p>
                    <a class="stata-action" href="{{ route('dashboard.user') }}">{{ __('stata.back_to_selector') }}</a>
                </div>

                <aside class="stata-panel stata-panel-inner stata-stat">
                    <div>
                        <strong>3+</strong>
                        <span>{{ __('stata.main_module_desc') }}</span>
                    </div>
                </aside>
            </section>

            <section class="feature-grid">
                <article class="feature-card">
                    <small>01</small>
                    <h2>{{ __('stata.correlation') }}</h2>
                    <p>{{ __('stata.correlation_desc') }}</p>
                </article>
                <article class="feature-card">
                    <small>02</small>
                    <h2>{{ __('stata.linear_regression') }}</h2>
                    <p>{{ __('stata.linear_regression_desc') }}</p>
                </article>
                <article class="feature-card">
                    <small>03</small>
                    <h2>{{ __('stata.desc_stats') }}</h2>
                    <p>{{ __('stata.desc_stats_desc') }}</p>
                </article>
            </section>

            <section id="stata-workbench" class="stata-workbench">
                <div class="workbench-heading">
                    <div>
                        <span class="stata-kicker">DTA Workspace</span>
                        <h2>Data Editor & Instant Commands</h2>
                    </div>
                    <p>{{ __('stata.import_desc') }}</p>
                </div>

                @if (session('stata_status'))
                    <div class="stata-flash">{{ session('stata_status') }}</div>
                @endif

                @if ($errors->has('stata_file'))
                    <div class="stata-error">{{ $errors->first('stata_file') }}</div>
                @endif

                @if ($errors->has('stata_command'))
                    <div class="stata-error">{{ $errors->first('stata_command') }}</div>
                @endif

                <div class="stata-import-grid">
                    <form class="stata-upload-card" action="{{ route('stata.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <h3>{{ __('stata.import_file') }}</h3>
                        <label class="stata-file-drop">
                            <span>
                                <input type="file" name="stata_file" accept=".dta,application/octet-stream" required>
                                <small>Format .dta, maksimum 25 MB. File disimpan privat dan hanya terhubung ke sesi akun ini.</small>
                            </span>
                        </label>
                        <button class="stata-import-button" type="submit">Import dan Baca Dataset</button>
                    </form>

                    <aside class="stata-dataset-card">
                        <h3>{{ __('stata.active_dataset') }}</h3>
                        @if ($stataDataset)
                            <p>{{ $stataDataset['name'] }}</p>
                            <div class="dataset-facts">
                                <div><span>Observasi</span><strong>{{ number_format($stataDataset['summary']['rows'], 0, ',', '.') }}</strong></div>
                                <div><span>Variabel</span><strong>{{ $stataDataset['summary']['columns'] }}</strong></div>
                                <div><span>Numerik</span><strong>{{ $stataDataset['summary']['numeric_columns'] }}</strong></div>
                                <div><span>Ukuran</span><strong>{{ number_format($stataDataset['size'] / 1048576, 2, ',', '.') }} MB</strong></div>
                            </div>
                            <p>{{ $stataDataset['summary']['data_label'] }}</p>
                            <form action="{{ route('stata.clear') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="stata-clear-button" type="submit">Tutup Dataset</button>
                            </form>
                        @else
                            <p>Belum ada dataset aktif. Pilih file `.dta` untuk membuka workspace.</p>
                        @endif
                    </aside>
                </div>

                @if ($stataDataset)
                    <form class="stata-command-layout" action="{{ route('stata.command') }}" method="POST">
                        @csrf
                        <aside class="stata-variable-panel">
                            <h3>Variables</h3>
                            <div class="variable-tools">
                                <button class="variable-tool" type="button" data-select-variables="all">Pilih Semua</button>
                                <button class="variable-tool" type="button" data-select-variables="numeric">Numerik</button>
                                <button class="variable-tool" type="button" data-select-variables="none">Bersihkan</button>
                            </div>
                            <div class="stata-variable-list">
                                @foreach ($stataDataset['variables'] as $variable)
                                    <label class="stata-variable-option">
                                        <input type="checkbox" name="variables[]" value="{{ $variable['name'] }}" data-numeric="{{ $variable['numeric'] ? '1' : '0' }}">
                                        <span>
                                            <strong>{{ $variable['name'] }}</strong>
                                            @if ($variable['label'])<small>{{ $variable['label'] }}</small>@endif
                                        </span>
                                        <span class="variable-type">{{ $variable['type'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </aside>

                        <section class="stata-editor-panel">
                            <h3>Command Toolbar</h3>
                            <div class="stata-command-bar">
                                <button class="stata-command-button" name="command" value="describe" type="submit">Describe</button>
                                <button class="stata-command-button" name="command" value="summarize" type="submit">Summarize</button>
                                <button class="stata-command-button" name="command" value="list" type="submit">List Data</button>
                                <button class="stata-command-button" name="command" value="missing" type="submit">Missing Values</button>
                                <button class="stata-command-button" name="command" value="correlate" type="submit">Correlate</button>
                                <button class="stata-command-button" name="command" value="tabulate" type="submit">Tabulate</button>
                                <select class="stata-sort-direction" name="direction" aria-label="Arah urutan">
                                    <option value="asc">Ascending</option>
                                    <option value="desc">Descending</option>
                                </select>
                                <button class="stata-command-button" name="command" value="sort" type="submit">Sort Preview</button>
                                <button class="stata-command-button" name="command" value="regress" type="submit">Regress</button>
                            </div>

                            <h3>Data Preview</h3>
                            <p>Maksimal 20 observasi pertama dari file yang diimpor.</p>
                            <div class="stata-table-wrap">
                                <table class="stata-live-table">
                                    <thead><tr>@foreach ($stataDataset['preview']['columns'] as $column)<th>{{ $column }}</th>@endforeach</tr></thead>
                                    <tbody>
                                        @foreach ($stataDataset['preview']['rows'] as $row)
                                            <tr>@foreach ($row as $value)<td>{{ $value === null ? '.' : $value }}</td>@endforeach</tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </form>
                @endif

                @if ($stataOutput)
                    <section id="stata-output" class="stata-result-panel">
                        <span class="stata-kicker">Results Window</span>
                        <h3>{{ $stataOutput['title'] }}</h3>
                        <code class="stata-result-command">{{ $stataOutput['command'] }}</code>
                        <p>{{ $stataOutput['message'] }}</p>
                        <div class="stata-table-wrap">
                            <table class="stata-live-table">
                                <thead><tr>@foreach ($stataOutput['table']['columns'] as $column)<th>{{ $column }}</th>@endforeach</tr></thead>
                                <tbody>
                                    @foreach ($stataOutput['table']['rows'] as $row)
                                        <tr>@foreach ($row as $value)<td>{{ $value === null ? '.' : $value }}</td>@endforeach</tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </section>
                @endif
            </section>

            <section class="stata-panel stata-panel-inner stata-data-panel">
                <span class="stata-kicker">Dataset Preview</span>
                <h2>Data Analisis Ekonomi</h2>
                <p>Ringkasan data contoh tetap tersedia sebagai dasar pengembangan modul Stata: GDP, inflasi, pengangguran, dan investasi.</p>

                <div class="stata-data-grid">
                    <div class="stata-data-card"><span>Observasi</span><strong>5 tahun</strong></div>
                    <div class="stata-data-card"><span>GDP rata-rata</span><strong>1.156</strong></div>
                    <div class="stata-data-card"><span>Inflasi rata-rata</span><strong>2,78%</strong></div>
                    <div class="stata-data-card"><span>Pengangguran rata-rata</span><strong>5,36%</strong></div>
                </div>

                <table class="stata-output-table">
                    <thead>
                        <tr>
                            <th>Variabel</th>
                            <th>Obs</th>
                            <th>Mean</th>
                            <th>Min</th>
                            <th>Max</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td>GDP</td><td>5</td><td>1.156</td><td>1.080</td><td>1.250</td></tr>
                        <tr><td>Inflasi</td><td>5</td><td>2,78</td><td>1,80</td><td>3,50</td></tr>
                        <tr><td>Pengangguran</td><td>5</td><td>5,36</td><td>5,00</td><td>6,00</td></tr>
                        <tr><td>Investasi</td><td>5</td><td>273</td><td>250</td><td>300</td></tr>
                    </tbody>
                </table>
            </section>

            <section class="stata-panel stata-panel-inner stata-tutorial">
                <span class="stata-kicker">Tutorial Pemula</span>
                <h2>Belajar Stata dari Awal</h2>
                <p class="tutorial-intro">Ikuti langkah berikut secara berurutan melalui Command Window Stata. Contoh ini memakai data ekonomi dengan variabel tahun, GDP, inflasi, pengangguran, dan investasi.</p>

                <div class="tutorial-steps">
                    <article class="tutorial-step">
                        <div class="tutorial-number">01</div>
                        <div class="tutorial-content">
                            <h3>Tentukan folder kerja</h3>
                            <p>Atur folder tempat dataset dan hasil analisis disimpan. Gunakan tanda kutip jika alamat folder mengandung spasi.</p>
                            <pre class="tutorial-code">. cd "D:\Data Penelitian"
. pwd
. dir</pre>
                            <div class="tutorial-tip"><strong>Hasil yang diharapkan:</strong> <code>pwd</code> menampilkan folder aktif dan <code>dir</code> menampilkan daftar file di dalamnya.</div>
                        </div>
                    </article>

                    <article class="tutorial-step">
                        <div class="tutorial-number">02</div>
                        <div class="tutorial-content">
                            <h3>Buka atau impor dataset</h3>
                            <p>Gunakan <code>use</code> untuk file Stata dan <code>import excel</code> untuk file Excel. Opsi <code>clear</code> membersihkan data yang sedang terbuka.</p>
                            <pre class="tutorial-code">. use "data_ekonomi.dta", clear

atau

. import excel "data_ekonomi.xlsx", firstrow clear</pre>
                            <div class="tutorial-tip"><strong>Catatan:</strong> <code>firstrow</code> menjadikan baris pertama Excel sebagai nama variabel.</div>
                        </div>
                    </article>

                    <article class="tutorial-step">
                        <div class="tutorial-number">03</div>
                        <div class="tutorial-content">
                            <h3>Kenali struktur dan kualitas data</h3>
                            <p>Periksa nama variabel, tipe data, contoh observasi, nilai yang hilang, dan kemungkinan data ganda sebelum analisis.</p>
                            <pre class="tutorial-code">. describe
. list in 1/10
. codebook gdp inflasi pengangguran investasi
. misstable summarize
. duplicates report tahun</pre>
                            <div class="tutorial-tip"><strong>Periksa:</strong> variabel angka jangan terbaca sebagai teks, nilai kosong harus dikenali, dan variabel tahun sebaiknya tidak memiliki duplikasi yang tidak disengaja.</div>
                        </div>
                    </article>

                    <article class="tutorial-step">
                        <div class="tutorial-number">04</div>
                        <div class="tutorial-content">
                            <h3>Bersihkan dan siapkan variabel</h3>
                            <p>Ubah tipe variabel bila diperlukan, beri label yang jelas, dan buat variabel turunan untuk kebutuhan analisis.</p>
                            <pre class="tutorial-code">. destring gdp inflasi investasi, replace
. label variable gdp "Produk Domestik Bruto"
. label variable inflasi "Inflasi tahunan (%)"
. generate pertumbuhan_investasi = 100 * (investasi - investasi[_n-1]) / investasi[_n-1]</pre>
                            <div class="tutorial-tip"><strong>Tip:</strong> jalankan <code>summarize pertumbuhan_investasi</code> setelah membuat variabel baru untuk memastikan hasilnya masuk akal.</div>
                        </div>
                    </article>

                    <article class="tutorial-step">
                        <div class="tutorial-number">05</div>
                        <div class="tutorial-content">
                            <h3>Lakukan analisis deskriptif dan korelasi</h3>
                            <p>Mulai dari ringkasan data sebelum membangun model. Mean menunjukkan nilai rata-rata, sedangkan standard deviation menunjukkan penyebaran data.</p>
                            <pre class="tutorial-code">. summarize gdp inflasi pengangguran investasi, detail
. tabstat gdp inflasi pengangguran investasi, statistics(n mean sd min max)
. pwcorr gdp inflasi pengangguran investasi, sig obs</pre>
                            <div class="tutorial-tip"><strong>Membaca korelasi:</strong> nilai mendekati 1 berarti hubungan positif kuat, mendekati -1 berarti hubungan negatif kuat, dan mendekati 0 berarti hubungan linear lemah.</div>
                        </div>
                    </article>

                    <article class="tutorial-step">
                        <div class="tutorial-number">06</div>
                        <div class="tutorial-content">
                            <h3>Jalankan regresi linear</h3>
                            <p>Contoh berikut menguji pengaruh investasi, inflasi, dan pengangguran terhadap GDP. Opsi <code>robust</code> menghasilkan standard error yang lebih tahan terhadap heteroskedastisitas.</p>
                            <pre class="tutorial-code">. regress gdp investasi inflasi pengangguran, robust
. estat vif
. predict gdp_prediksi
. predict residual, residuals</pre>
                            <div class="tutorial-tip"><strong>Membaca output:</strong> lihat tanda dan nilai koefisien, <code>P&gt;|t|</code> untuk signifikansi, <code>R-squared</code> untuk kemampuan model menjelaskan variasi GDP, dan VIF untuk memeriksa multikolinearitas.</div>
                        </div>
                    </article>

                    <article class="tutorial-step">
                        <div class="tutorial-number">07</div>
                        <div class="tutorial-content">
                            <h3>Buat grafik dan simpan pekerjaan</h3>
                            <p>Visualisasikan hubungan variabel, simpan dataset yang sudah dibersihkan, dan rekam output agar analisis dapat ditinjau kembali.</p>
                            <pre class="tutorial-code">. twoway (scatter gdp investasi) (lfit gdp investasi)
. graph export "grafik_gdp_investasi.png", replace
. save "data_ekonomi_bersih.dta", replace
. log using "hasil_analisis.log", replace
. regress gdp investasi inflasi pengangguran, robust
. log close</pre>
                            <div class="tutorial-tip"><strong>Praktik baik:</strong> simpan perintah dalam Do-file agar seluruh analisis dapat dijalankan ulang dan diperiksa langkah demi langkah.</div>
                        </div>
                    </article>
                </div>

                <div class="tutorial-checklist">
                    <div><strong>Sebelum analisis</strong><br>Periksa tipe data, missing value, duplikasi, dan satuan variabel.</div>
                    <div><strong>Saat analisis</strong><br>Mulai dari statistik deskriptif, lalu korelasi, grafik, dan model regresi.</div>
                    <div><strong>Setelah analisis</strong><br>Simpan Do-file, dataset bersih, grafik, log output, dan interpretasi hasil.</div>
                </div>
            </section>

            <section class="stata-panel stata-panel-inner stata-command-reference">
                <div class="stata-section-heading">
                    <div>
                        <span class="stata-kicker">Command Library</span>
                        <h2>Command Stata Umum</h2>
                    </div>
                    <p>Daftar ini merangkum command yang paling sering dipakai dalam workflow Stata: impor data, cleaning, statistik deskriptif, grafik, regresi, panel, time series, dan output.</p>
                </div>

                <div class="stata-command-groups">
                    @foreach ($stataCommandGroups as $groupName => $commands)
                        <article class="stata-command-group">
                            <h3>{{ $groupName }}</h3>

                            <div class="stata-command-list">
                                @foreach ($commands as $command)
                                    <div class="stata-command-item">
                                        <code>{{ $command['cmd'] }}</code>
                                        <p>{{ $command['desc'] }}</p>
                                        <pre>. {{ $command['example'] }}</pre>
                                    </div>
                                @endforeach
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="stata-source-note">
                    Referensi disusun dari dokumentasi resmi Stata seperti
                    <a href="https://www.stata.com/bookstore/base-reference-manual/" target="_blank" rel="noopener">Base Reference Manual</a>
                    dan
                    <a href="https://www.stata.com/bookstore/data-management-reference-manual/" target="_blank" rel="noopener">Data Management Reference Manual</a>.
                </div>
            </section>

            <section class="stata-console">
                <div><span>.</span> summarize income expense saving</div>
                <div><span>.</span> correlate gdp inflation unemployment</div>
                <div><span>.</span> regress growth investment inflation</div>
            </section>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var variableInputs = Array.from(document.querySelectorAll('.stata-variable-option input[type="checkbox"]'));

            document.querySelectorAll('[data-select-variables]').forEach(function (button) {
                button.addEventListener('click', function () {
                    var mode = button.dataset.selectVariables;

                    variableInputs.forEach(function (input) {
                        input.checked = mode === 'all' || (mode === 'numeric' && input.dataset.numeric === '1');
                    });
                });
            });
        });
    </script>
@endsection
