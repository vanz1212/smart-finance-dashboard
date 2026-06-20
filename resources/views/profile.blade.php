@extends('layouts.app')

@section('title', 'Profile - Smart Finance')

@section('content')
    <style>
        .site-header,
        .site-footer {
            display: none !important;
        }

        body {
            background: #061418;
        }

        body > .container {
            max-width: none;
            width: 100%;
            min-height: 100vh;
            padding: 0;
        }

        .content {
            min-height: 100vh;
        }

        .profile-page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 28px;
            color: #f8fafc;
            background:
                linear-gradient(180deg, rgba(5, 12, 15, 0.72), rgba(5, 12, 15, 0.96)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;
        }

        .profile-card {
            width: min(760px, 100%);
            padding: 34px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.82), rgba(6, 24, 32, 0.88));
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.42);
            backdrop-filter: blur(16px);
        }

        .profile-card span {
            color: #f3c969;
            font-size: 0.8rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .profile-card h1 {
            margin: 12px 0 12px;
            font-size: clamp(2rem, 5vw, 3.4rem);
            line-height: 1;
            letter-spacing: 0;
        }

        .profile-card p {
            color: rgba(248, 250, 252, 0.72);
            line-height: 1.7;
        }

        .profile-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 24px;
        }

        .profile-info-item {
            padding: 16px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.06);
        }

        .profile-info-item small {
            display: block;
            margin-bottom: 8px;
            color: rgba(248, 250, 252, 0.48);
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .profile-info-item strong {
            color: #ffffff;
            font-size: 1.05rem;
            line-height: 1.4;
            word-break: break-word;
        }

        .profile-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 24px;
        }

        .profile-actions a,
        .profile-actions button {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
            cursor: pointer;
            font: inherit;
            font-weight: 900;
            text-decoration: none;
        }

        .profile-actions .primary {
            background: #14b86f;
            border-color: #14b86f;
            color: #052e2b;
        }

        @media (max-width: 640px) {
            .profile-info {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <main class="profile-page">
        <section class="profile-card">
            <span>User Profile</span>
            @if ($isLoggedIn)
                <h1>Profil Aktif</h1>
                <p>Informasi akun yang sedang login. Gunakan halaman utama untuk membuka Smart Finance, Perpajakan, atau Stata.</p>
                <div class="profile-info">
                    <div class="profile-info-item">
                        <small>Nama Lengkap</small>
                        <strong>{{ $user->name }}</strong>
                    </div>
                    <div class="profile-info-item">
                        <small>Role</small>
                        <strong>{{ ucfirst($user->role ?? 'user') }}</strong>
                    </div>
                    <div class="profile-info-item">
                        <small>Username</small>
                        <strong>{{ $user->username ?? '-' }}</strong>
                    </div>
                    <div class="profile-info-item">
                        <small>Email</small>
                        <strong>{{ $user->email }}</strong>
                    </div>
                    <div class="profile-info-item">
                        <small>Bergabung Sejak</small>
                        <strong>{{ optional($user->created_at)->format('d M Y') ?? '-' }}</strong>
                    </div>
                </div>
                <div class="profile-actions">
                    <a class="primary" href="{{ route('dashboard.user') }}">Ke Dashboard Utama</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                </div>
            @else
                <h1>User Belum Login</h1>
                <p>User belum login. Silakan login terlebih dahulu untuk memuat profile dan mengakses informasi akun.</p>
                <div class="profile-actions">
                    <a class="primary" href="{{ route('login') }}">Login Sekarang</a>
                    <a href="{{ route('home') }}">Kembali ke Beranda</a>
                </div>
            @endif
        </section>
    </main>
@endsection
