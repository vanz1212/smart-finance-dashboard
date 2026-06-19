@extends('layouts.app')

@section('title', 'Admin Users - Smart Finance')

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

        .admin-shell {
            min-height: 100vh;
            padding: 28px;
            color: #f8fafc;
            background:
                linear-gradient(180deg, rgba(5, 12, 15, 0.72), rgba(5, 12, 15, 0.96)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover fixed no-repeat;
        }

        .admin-card {
            width: min(1280px, 100%);
            margin: 0 auto;
            padding: 28px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            border-radius: 18px;
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.86), rgba(6, 24, 32, 0.92));
            box-shadow: 0 30px 90px rgba(0, 0, 0, 0.42);
            backdrop-filter: blur(14px);
        }

        .admin-header {
            display: flex;
            justify-content: space-between;
            gap: 18px;
            align-items: end;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .admin-header h1 {
            margin: 10px 0 8px;
            font-size: clamp(2rem, 5vw, 3.2rem);
            line-height: 1;
        }

        .admin-header p {
            margin: 0;
            max-width: 760px;
            color: rgba(248, 250, 252, 0.72);
            line-height: 1.6;
        }

        .admin-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        .admin-actions a {
            min-height: 48px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 24px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #ffffff;
            text-decoration: none;
            font-weight: 900;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            background: rgba(255, 255, 255, 0.06);
        }

        .admin-actions a.primary {
            background: #14b86f;
            border-color: #14b86f;
            color: #052e2b;
        }

        .flash {
            margin-bottom: 18px;
            padding: 14px 18px;
            border-radius: 12px;
            background: rgba(20, 184, 111, 0.14);
            border: 1px solid rgba(20, 184, 111, 0.35);
            color: #d9fbe8;
            font-weight: 700;
        }

        .user-table {
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.04);
        }

        .user-row,
        .user-head {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1.2fr 0.7fr 1.4fr;
            gap: 16px;
            align-items: center;
        }

        .user-head {
            padding: 16px 18px;
            background: rgba(255, 255, 255, 0.08);
            color: rgba(248, 250, 252, 0.68);
            font-size: 0.9rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .user-row {
            padding: 18px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .user-row strong {
            display: block;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .user-row span {
            color: rgba(248, 250, 252, 0.68);
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
            letter-spacing: 0.04em;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            text-transform: uppercase;
        }

        .role-admin {
            background: rgba(243, 201, 105, 0.96);
            color: #052e2b;
        }

        .role-user {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }

        .row-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
            align-items: center;
        }

        .row-actions a,
        .row-actions button {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0 18px;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
            text-decoration: none;
            font: inherit;
            font-weight: 900;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            cursor: pointer;
        }

        .row-actions .edit {
            background: rgba(20, 184, 111, 0.16);
            border-color: rgba(20, 184, 111, 0.36);
        }

        .row-actions .danger {
            background: rgba(239, 68, 68, 0.14);
            border-color: rgba(239, 68, 68, 0.32);
        }

        .role-form {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
            margin-top: 10px;
        }

        .role-form select,
        .role-form button {
            min-height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 999px;
            border: 1px solid rgba(255, 255, 255, 0.16);
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            font: inherit;
            font-weight: 900;
            line-height: 1;
            white-space: nowrap;
        }

        .role-form select {
            min-width: 124px;
            padding: 0 38px 0 18px;
        }

        .role-form button {
            padding: 0 22px;
            cursor: pointer;
            background: rgba(20, 184, 111, 0.16);
            border-color: rgba(20, 184, 111, 0.36);
        }

        .admin-footer {
            margin-top: 18px;
        }

        .pagination-wrap {
            margin-top: 18px;
        }

        .log-section {
            margin-top: 28px;
            padding: 22px;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.04);
        }

        .log-section h2 {
            margin: 0 0 8px;
            font-size: 1.35rem;
        }

        .log-section p {
            margin: 0 0 18px;
            color: rgba(248, 250, 252, 0.68);
            line-height: 1.6;
        }

        .log-table {
            width: 100%;
            border-collapse: collapse;
            overflow: hidden;
        }

        .log-table th,
        .log-table td {
            padding: 14px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            vertical-align: top;
            text-align: left;
        }

        .log-table th {
            color: rgba(248, 250, 252, 0.68);
            font-size: 0.86rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            border-top: 0;
        }

        .log-table td {
            color: #ffffff;
        }

        .log-table small {
            display: block;
            color: rgba(248, 250, 252, 0.62);
            margin-top: 4px;
        }

        .empty-state {
            padding: 26px;
            color: rgba(248, 250, 252, 0.72);
        }

        @media (max-width: 980px) {
            .user-head {
                display: none;
            }

            .user-row {
                grid-template-columns: 1fr;
            }

            .row-actions {
                justify-content: flex-start;
            }
        }
    </style>

    <main class="admin-shell">
        <section class="admin-card">
            <div class="admin-header">
                <div>
                    <span style="color:#f3c969;font-weight:900;letter-spacing:.12em;text-transform:uppercase;">Admin Access</span>
                    <h1>Manajemen User</h1>
                    <p>Lihat profil user, buka detail akun, edit data, ubah role, atau hapus user langsung dari panel ini.</p>
                </div>
                <div class="admin-actions">
                    <a class="primary" href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
                    <a href="{{ route('profile') }}">Profile Saya</a>
                </div>
            </div>

            @if (session('status'))
                <div class="flash">{{ session('status') }}</div>
            @endif

            @if ($errors->any())
                <div class="flash" style="background:rgba(239,68,68,.14);border-color:rgba(239,68,68,.32);color:#ffe4e6;">
                    {{ $errors->first() }}
                </div>
            @endif

            <div class="user-table">
                <div class="user-head">
                    <span>Nama</span>
                    <span>Username / Email</span>
                    <span>Role</span>
                    <span>Bergabung</span>
                    <span>Aksi</span>
                </div>

                @forelse ($users as $user)
                    <div class="user-row">
                        <div>
                            <strong>{{ $user->name }}</strong>
                            <span>ID #{{ $user->id }}</span>
                        </div>
                        <div>
                            <strong>{{ $user->username }}</strong>
                            <span>{{ $user->email }}</span>
                        </div>
                        <div>
                            <span class="role-badge {{ $user->role === 'admin' ? 'role-admin' : 'role-user' }}">
                                {{ ucfirst($user->role) }}
                            </span>
                            <form class="role-form" action="{{ route('admin.users.role', $user) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="role" aria-label="Ubah role {{ $user->name }}">
                                    <option value="user" @selected($user->role === 'user')>User</option>
                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                </select>
                                <button type="submit">Simpan Role</button>
                            </form>
                        </div>
                        <div>
                            <strong>{{ optional($user->created_at)->timezone('Asia/Jakarta')->format('d M Y') }}</strong>
                            <span>{{ optional($user->created_at)->timezone('Asia/Jakarta')->format('H:i') }} WIB</span>
                        </div>
                        <div class="row-actions">
                            <a href="{{ route('dashboard.user') }}">Akses Halaman User</a>
                            <a href="{{ route('admin.users.show', $user) }}">Lihat Profil</a>
                            <a class="edit" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                            @if (! auth()->user()->is($user))
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="danger" type="submit">Hapus</button>
                                </form>
                            @else
                                <span class="role-badge role-admin">Akun Aktif</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="empty-state">Belum ada user yang terdaftar.</div>
                @endforelse
            </div>

            <div class="pagination-wrap">
                {{ $users->links() }}
            </div>

            <section class="log-section">
                <h2>Activity Log</h2>
                <p>Riwayat login, logout, dan halaman utama yang dibuka user.</p>

                <table class="log-table">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Aksi</th>
                            <th>Halaman</th>
                            <th>Tanggal</th>
                            <th>Waktu</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($activityLogs as $log)
                            <tr>
                                <td>
                                    <strong>{{ $log->user?->name ?? 'Guest' }}</strong>
                                    <small>{{ $log->user?->username ?? '-' }}</small>
                                </td>
                                <td>{{ strtoupper($log->action) }}</td>
                                <td>{{ $log->page_label ?? $log->page_key ?? '-' }}</td>
                                <td>{{ $log->created_at?->timezone('Asia/Jakarta')->format('d M Y') }}</td>
                                <td>{{ $log->created_at?->timezone('Asia/Jakarta')->format('H:i:s') }} WIB</td>
                                <td>{{ $log->ip_address ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="empty-state">Belum ada activity log.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </section>
        </section>
    </main>
@endsection
