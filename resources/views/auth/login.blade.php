@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <section class="auth-page">
        <div class="auth-panel">
            <div class="auth-copy">
                <div class="auth-brand-mark">SF</div>
                <span class="eyebrow">Akses dashboard</span>
                <h1>Smart Finance Analytics</h1>
                <p>Masuk untuk mengelola analisis keuangan, perpajakan, dan data ekonomi dalam satu ruang kerja.</p>

                <div class="auth-stat-grid">
                    <div>
                        <span>Cashflow</span>
                        <strong>Realtime</strong>
                    </div>
                    <div>
                        <span>Tax Tools</span>
                        <strong>Ready</strong>
                    </div>
                </div>
            </div>

            <form action="{{ route('login.submit') }}" method="POST" class="auth-form">
                @csrf

                <div class="auth-form-header">
                    <span class="eyebrow">Login</span>
                    <h2>Selamat datang kembali</h2>
                </div>

                @if ($errors->any())
                    <div class="alert-box">
                        {{ $errors->first() }}
                    </div>
                @endif

                <label>
                    <span>Email</span>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        placeholder="admin@smartfinance.test"
                        required
                        autofocus
                    >
                </label>

                <label>
                    <span>Password</span>
                    <input
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                        required
                    >
                </label>

                <label class="check-row">
                    <input type="checkbox" name="remember" value="1">
                    <span>Ingat saya</span>
                </label>

                <button type="submit" class="primary-action">Login</button>
            </form>
        </div>
    </section>
@endsection
