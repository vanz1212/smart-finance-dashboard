@extends('layouts.app')

@section('title', 'Landing Page')

@section('content')
    <section class="hero">
        <h1>Selamat datang di Smart Finance Analytics Dashboard</h1>
        <p>Silakan pilih tujuan Anda untuk memulai analisis data ekonomi dan keuangan.</p>
        <div class="card-grid">
            <a href="{{ url('/smart-finance') }}" class="card card-primary">
                <h2>Smart Finance Dashboard</h2>
                <p>Masuk ke dashboard keuangan interaktif untuk analisis data, visualisasi, dan laporan.</p>
            </a>
            <a href="{{ route('perpajakan.index') }}" class="card card-primary">
                <h2>Perpajakan</h2>
                <p>Hitung estimasi pajak berdasarkan penghasilan, pengeluaran, dan status wajib pajak.</p>
            </a>
            <a href="{{ url('/stata') }}" class="card card-secondary">
                <h2>Stata-like Analysis</h2>
                <p>Masuk ke halaman analisis statistik dan ekonomi yang lebih mirip Stata.</p>
            </a>
        </div>
    </section>
@endsection
