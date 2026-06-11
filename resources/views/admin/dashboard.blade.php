@extends('layouts.app')

@section('title', 'Admin Dashboard - Smart Finance')

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

        .admin-page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 28px;
            color: #f8fafc;
            background: linear-gradient(180deg, rgba(5, 12, 15, 0.72), rgba(5, 12, 15, 0.96));
        }

        .admin-card {
            width: min(780px, 100%);
            padding: 34px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 16px;
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.82), rgba(6, 24, 32, 0.88));
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.42);
        }

        .admin-card span {
            color: #f3c969;
            font-size: 0.8rem;
            font-weight: 900;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .admin-card h1 {
            margin: 12px 0;
            font-size: clamp(2rem, 5vw, 3.4rem);
            line-height: 1;
        }

        .admin-card p {
            color: rgba(248, 250, 252, 0.72);
            line-height: 1.7;
        }

        .admin-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .admin-actions a {
            min-height: 46px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border-radius: 999px;
            background: #14b86f;
            color: #052e2b;
            font-weight: 900;
            text-decoration: none;
        }
    </style>

    <main class="admin-page">
        <section class="admin-card">
            <span>Admin Access</span>
            <h1>Dashboard Admin</h1>
            <p>Selamat datang. Akun ini memiliki role admin dan dapat diarahkan ke pengelolaan data, user, atau modul internal lain ke depannya.</p>
            <div class="admin-actions">
                <a href="{{ route('dashboard.admin') }}">Ke Dashboard Admin</a>
                <a href="{{ route('profile') }}">Lihat Profile</a>
            </div>
        </section>
    </main>
@endsection
