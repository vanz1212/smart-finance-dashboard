@extends('layouts.app')

@section('title', 'Detail User - Smart Finance')

@section('content')
    <style>
        .site-header,
        .site-footer { display: none !important; }
        body { background: var(--bg-primary); }
        body > .container { max-width: none; width: 100%; min-height: 100vh; padding: 0; }
        .content { min-height: 100vh; }
        .admin-shell {
            min-height: 100vh;
            padding: 28px;
            color: #f8fafc;
            background:
                linear-gradient(180deg, rgba(5, 12, 15, 0.72), rgba(5, 12, 15, 0.96)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;
        }
        .admin-card {
            width: min(980px, 100%);
            margin: 0 auto;
            padding: 30px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.86), rgba(6, 24, 32, 0.92));
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.42);
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .topbar a {
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #fff;
            text-decoration: none;
            font-weight: 900;
            background: rgba(255, 255, 255, 0.06);
        }
        .topbar a.primary {
            background: #14b86f;
            border-color: #14b86f;
            color: #052e2b;
        }
        .profile-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 24px;
        }
        .profile-item {
            padding: 18px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.06);
        }
        .profile-item small {
            display: block;
            margin-bottom: 8px;
            color: rgba(248, 250, 252, 0.48);
            font-weight: 900;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }
        .profile-item strong {
            color: #fff;
            word-break: break-word;
        }
        .role-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 34px;
            padding: 0 14px;
            border-radius: 999px;
            font-size: 0.82rem;
            font-weight: 900;
            text-transform: uppercase;
            background: rgba(243, 201, 105, 0.96);
            color: #052e2b;
        }
        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 24px;
        }
        .actions a,
        .actions button {
            min-height: 44px;
            padding: 0 16px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            text-decoration: none;
            font: inherit;
            font-weight: 900;
            cursor: pointer;
        }
        .actions .danger {
            background: rgba(239, 68, 68, 0.14);
            border-color: rgba(239, 68, 68, 0.32);
        }
        @media (max-width: 640px) {
            .profile-grid { grid-template-columns: 1fr; }
        }

        [data-theme="light"] .admin-shell {
            color: #0f172a;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.7), rgba(241, 245, 249, 0.88)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;
        }

        [data-theme="light"] .admin-card {
            border-color: rgba(148, 163, 184, 0.22);
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.92), rgba(241, 245, 249, 0.96));
            box-shadow: 0 24px 60px rgba(15, 23, 42, 0.14);
        }

        [data-theme="light"] .topbar a,
        [data-theme="light"] .actions a,
        [data-theme="light"] .actions button {
            border-color: rgba(148, 163, 184, 0.22);
            background: rgba(255, 255, 255, 0.8);
            color: #0f172a;
        }

        [data-theme="light"] .profile-item {
            border-color: rgba(148, 163, 184, 0.2);
            background: rgba(255, 255, 255, 0.8);
        }

        [data-theme="light"] .profile-item small,
        [data-theme="light"] p[style*='rgba(248,250,252'] {
            color: #64748b !important;
        }

        [data-theme="light"] .profile-item strong {
            color: #0f172a;
        }
    </style>

    <main class="admin-shell">
        <section class="admin-card">
            <div class="topbar">
                <a class="primary" href="{{ route('admin.users.index') }}">Kembali ke Daftar User</a>
                <a href="{{ route('dashboard.admin') }}">Dashboard Admin</a>
            </div>

            <span style="color:#f3c969;font-weight:900;letter-spacing:.12em;text-transform:uppercase;">Profil User</span>
            <h1 style="margin:12px 0 8px;font-size:clamp(2rem,5vw,3.2rem);line-height:1;">{{ $user->name }}</h1>
            <p style="color:rgba(248,250,252,.72);line-height:1.7;">Detail akun user yang bisa diedit atau dihapus oleh admin.</p>

            <div class="profile-grid">
                <div class="profile-item">
                    <small>Nama Lengkap</small>
                    <strong>{{ $user->name }}</strong>
                </div>
                <div class="profile-item">
                    <small>Role</small>
                    <strong class="role-badge">{{ ucfirst($user->role) }}</strong>
                </div>
                <div class="profile-item">
                    <small>Username</small>
                    <strong>{{ $user->username }}</strong>
                </div>
                <div class="profile-item">
                    <small>Email</small>
                    <strong>{{ $user->email }}</strong>
                </div>
                <div class="profile-item">
                    <small>Bergabung Sejak</small>
                    <strong>{{ optional($user->created_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</strong>
                </div>
                <div class="profile-item">
                    <small>Update Terakhir</small>
                    <strong>{{ optional($user->updated_at)->timezone('Asia/Jakarta')->format('d M Y H:i') }} WIB</strong>
                </div>
            </div>

            <div class="actions">
                <a href="{{ route('admin.users.edit', $user) }}">Edit Profil</a>
                @if (! auth()->user()->is($user))
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
                        @csrf
                        @method('DELETE')
                        <button class="danger" type="submit">Hapus User</button>
                    </form>
                @endif
            </div>
        </section>
    </main>
@endsection
