@extends('layouts.app')

@section('title', 'Stata-like Analysis')

@section('content')
    <section class="page-section">
        <div class="section-header">
            <h1>Stata-like Analysis</h1>
            <p>Halaman ini dirancang untuk analisis statistik yang mirip Stata dengan interface yang lebih modern dan mudah.</p>
        </div>

        <div class="overview-grid">
            <div class="overview-card">
                <strong>Korelasi</strong>
                <p>Analisa hubungan antar variabel numerik dan identifikasi pola penting.</p>
            </div>
            <div class="overview-card">
                <strong>Regresi Linear</strong>
                <p>Bangun model regresi sederhana untuk memperkirakan hubungan antar data.</p>
            </div>
            <div class="overview-card">
                <strong>Statistik Deskriptif</strong>
                <p>Output seperti Stata: Mean, Std Dev, Min, Max, dan banyak lagi.</p>
            </div>
        </div>

        <div class="placeholder-box">
            <p>Modul Stata akan dikembangkan sebagai halaman analisis yang mudah digunakan oleh mahasiswa ekonomi.</p>
        </div>
    </section>
@endsection
