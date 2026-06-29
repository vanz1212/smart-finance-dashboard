@extends('layouts.app')

@section('title', 'Edit User - Smart Finance')

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
        .edit-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
            margin-top: 24px;
        }
        .field {
            display: grid;
            gap: 10px;
        }
        .field.full {
            grid-column: 1 / -1;
        }
        .field span {
            color: rgba(248, 250, 252, 0.72);
            font-weight: 800;
        }
        .field input,
        .field select {
            min-height: 50px;
            width: 100%;
            padding: 0 16px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.14);
            background: rgba(255, 255, 255, 0.06);
            color: #fff;
            outline: none;
        }
        .field input::placeholder {
            color: rgba(248, 250, 252, 0.45);
        }
        .note {
            margin-top: 14px;
            color: rgba(248, 250, 252, 0.62);
            line-height: 1.7;
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
        .actions .primary {
            background: #14b86f;
            border-color: #14b86f;
            color: #052e2b;
        }
        .error-box {
            margin-top: 16px;
            padding: 14px 16px;
            border-radius: 12px;
            background: rgba(239, 68, 68, 0.14);
            border: 1px solid rgba(239, 68, 68, 0.32);
            color: #ffe4e6;
        }
        @media (max-width: 640px) {
            .edit-grid { grid-template-columns: 1fr; }
            .field.full { grid-column: auto; }
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

        [data-theme="light"] .field span,
        [data-theme="light"] .note,
        [data-theme="light"] p[style*='rgba(248,250,252'] {
            color: #64748b !important;
        }

        [data-theme="light"] .field input,
        [data-theme="light"] .field select {
            border-color: rgba(148, 163, 184, 0.22);
            background: rgba(255, 255, 255, 0.8);
            color: #0f172a;
        }

        [data-theme="light"] .field input::placeholder {
            color: #94a3b8;
        }
    </style>

    <main class="admin-shell">
        <section class="admin-card">
            <div class="topbar">
                <a class="primary" href="{{ route('admin.users.show', $user) }}">Kembali ke Detail</a>
                <a href="{{ route('admin.users.index') }}">Daftar User</a>
            </div>

            <span style="color:#f3c969;font-weight:900;letter-spacing:.12em;text-transform:uppercase;">Edit User</span>
            <h1 style="margin:12px 0 8px;font-size:clamp(2rem,5vw,3.2rem);line-height:1;">{{ $user->name }}</h1>
            <p style="color:rgba(248,250,252,.72);line-height:1.7;">Ubah profil user, role, atau password dari sini.</p>

            @if ($errors->any())
                <div class="error-box">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="edit-grid">
                    <label class="field">
                        <span>Nama Lengkap</span>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
                    </label>

                    <label class="field">
                        <span>Username</span>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" required>
                    </label>

                    <label class="field">
                        <span>Email</span>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
                    </label>

                    <label class="field">
                        <span>Role</span>
                        <select name="role" required>
                            <option value="user" @selected(old('role', $user->role) === 'user')>User</option>
                            <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                        </select>
                    </label>

                    <label class="field">
                        <span>Password Baru</span>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diganti">
                    </label>

                    <label class="field">
                        <span>Konfirmasi Password</span>
                        <input type="password" name="password_confirmation" placeholder="Ulangi password baru">
                    </label>
                </div>

                <p class="note">Jika password kosong, password lama tetap digunakan. Role hanya bisa diubah oleh admin.</p>

                <div class="actions">
                    <button class="primary" type="submit">Simpan Perubahan</button>
                    <a href="{{ route('admin.users.show', $user) }}">Batal</a>
                </div>
            </form>
        </section>
    </main>
@endsection
