@extends('layouts.app')

@section('title', 'Smart Finance Dashboard')

@section('content')
    <section class="page-section">
        <div class="section-header">
            <h1>Smart Finance Dashboard</h1>
            <p>Halaman ini akan menjadi pusat analisis keuangan dan performa ekonomi untuk perusahaan atau portofolio.</p>
        </div>

        <div class="overview-grid">
            <div class="overview-card">
                <strong>Analisis Keuangan</strong>
                <p>Dashboard ringkas dengan statistik utama, grafik, dan laporan yang mudah dibaca.</p>
            </div>
            <div class="overview-card">
                <strong>Visualisasi Data</strong>
                <p>Grafik bar, pie, histogram, line, dan scatter untuk memahami pola data.</p>
            </div>
            <div class="overview-card">
                <strong>Data Import</strong>
                <p>Upload file CSV atau Excel dan lakukan eksplorasi data langsung di browser.</p>
            </div>
        </div>

        <div class="placeholder-box">
            <p>Halaman ini akan dikembangkan menjadi modul dashboard lengkap Laravel + PostgreSQL.</p>
        </div>
    </section>
@endsection
