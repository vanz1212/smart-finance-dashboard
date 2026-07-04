@extends('layouts.app')

@section('title', __('profile.page_title'))

@section('content')
    <style>
        .site-header,
        .site-footer {
            display: none !important;
        }

        body {
            background: var(--bg-primary);
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
                radial-gradient(ellipse at 80% 0%, rgba(16, 185, 129, 0.12), transparent 50%),
                radial-gradient(ellipse at 20% 100%, rgba(99, 102, 241, 0.08), transparent 50%),
                linear-gradient(180deg, var(--bg-primary), var(--bg-secondary));
        }

        .profile-dashboard {
            display: flex;
            width: min(1200px, 100%);
            min-height: 650px;
            border-radius: 20px;
            overflow: hidden;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 20px 60px rgba(0,0,0,.25);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }

        /* Sidebar Styling */
        .profile-sidebar {
            width: 280px;
            background: linear-gradient(180deg, #4f46e5 0%, #3b82f6 100%);
            padding: 40px 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            flex-shrink: 0;
            position: relative;
        }

        .profile-sidebar::after {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.1), transparent);
            pointer-events: none;
        }

        .avatar-container {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            border: 3px solid rgba(255, 255, 255, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            position: relative;
            z-index: 1;
        }

        .avatar-container > svg {
            width: 40px;
            height: 40px;
            fill: #fff;
            opacity: 0.8;
        }.profile-sidebar h2 {
            margin: 0;
            font-size: 1.4rem;
            font-weight: 800;
            color: #ffffff;
            text-align: center;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
        }

        .profile-sidebar p {
            margin: 6px 0 30px;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            position: relative;
            z-index: 1;
        }

        .sidebar-menu {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 12px;
            position: relative;
            z-index: 1;
        }

        .sidebar-menu a, .sidebar-menu button {
            width: 100%;
            padding: 14px 20px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            border: 1px solid rgba(255, 255, 255, 0.15);
            text-align: left;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .sidebar-menu a:hover, .sidebar-menu button:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateX(4px);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .sidebar-menu a.active {
            background: #ffffff;
            color: #3b82f6;
            border-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .sidebar-menu svg {
            width: 18px;
            height: 18px;
            opacity: 0.8;
        }

        /* Main Content Styling */
        .profile-main {
            flex: 1;
            padding: 40px;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .profile-header {
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            padding-bottom: 20px;
        }

        .profile-header span {
            display: block;
            color: #818cf8;
            font-size: 0.8rem;
            font-weight: 900;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .profile-header h1 {
            margin: 0;
            font-size: 2.2rem;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: 0.02em;
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 16px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .glass-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            border-color: rgba(255, 255, 255, 0.15);
        }

        .card-title {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.5);
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .card-value {
            font-size: 1.3rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
            word-break: break-word;
        }

        .card-form {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-top: auto;
        }

        .card-input {
            width: 100%;
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            padding: 12px 16px;
            border-radius: 10px;
            font-size: 0.95rem;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }

        .card-input:focus {
            border-color: #6366f1;
            background: rgba(0, 0, 0, 0.3);
        }

        .card-btn {
            background: #6366f1;
            color: #ffffff;
            border: none;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            text-align: center;
        }

        .card-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(99, 102, 241, 0.3);
            filter: brightness(1.1);
        }

        .card-btn-secondary {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .card-btn-secondary:hover {
            background: rgba(255, 255, 255, 0.15);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.2);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
            font-weight: 800;
        }

        .badge-success {
            background: rgba(16, 185, 129, 0.2);
            color: #34d399;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.2);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .alert-error {
            color: #f87171;
            font-size: 0.85rem;
            margin-top: 6px;
            font-weight: 600;
        }

        .alert-success {
            color: #34d399;
            font-size: 0.85rem;
            margin-top: 6px;
            font-weight: 600;
        }

        @media (max-width: 860px) {
            .profile-dashboard {
                flex-direction: column;
                min-height: auto;
            }
            .profile-sidebar {
                width: 100%;
                padding: 30px 20px;
            }
            .sidebar-menu {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }
            .sidebar-menu a, .sidebar-menu button {
                width: auto;
                flex: 1;
                min-width: 140px;
                justify-content: center;
            }
            .profile-main {
                padding: 30px 20px;
            }
        }
    </style>

    <main class="profile-page">
        @if ($isLoggedIn)
            <div class="profile-dashboard">
                <!-- Sidebar Component -->
                <aside class="profile-sidebar">
                    <div class="avatar-container" onclick="openAvatarModal()" style="cursor:pointer; position:relative;" title="Ubah Avatar">
                        @if($user->avatar)
                            <img src="{{ asset($user->avatar) }}" alt="Avatar" style="width:100%;height:100%;border-radius:50%;object-fit:cover;z-index:2;position:relative;">
                        @else
                            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        @endif
                        <div style="position:absolute;bottom:0px;right:0px;background:#6366f1;border-radius:50%;width:24px;height:24px;box-shadow:0 2px 10px rgba(0,0,0,0.5);z-index:3;display:flex;align-items:center;justify-content:center;">
                            <svg style="width:12px; height:12px; fill:none; stroke:white; stroke-width:2.5;" viewBox="0 0 24 24"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg>
                        </div>
                    </div>
                    <h2 style="text-transform: uppercase;">Halo, {{ $user->username }}!</h2>
                    <p>Role: {{ ucfirst($user->role ?? 'user') }}</p>
                    
                    <div class="sidebar-menu">
                        <a href="#" class="active">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                            Data Diri
                        </a>
                        <a href="{{ route('dashboard.user') }}">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><line x1="3" y1="9" x2="21" y2="9"></line><line x1="9" y1="21" x2="9" y2="9"></line></svg>
                            Dashboard Utama
                        </a>
                        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </aside>

                <!-- Main Content Component -->
                <section class="profile-main">
                    <div class="profile-header">
                        <span>Personal Information</span>
                        <h1>Profil Pengguna</h1>
                    </div>

                    <div class="cards-grid">
                        <!-- Card: Nama Lengkap -->
                        <div class="glass-card">
                            <div class="card-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                Nama Lengkap
                            </div>
                            <div class="card-value">{{ $user->name }}</div>
                            
                            <div class="card-title" style="margin-top: 20px;">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                Bergabung Sejak
                            </div>
                            <div class="card-value">{{ optional($user->created_at)->format('d M Y') ?? '-' }}</div>
                        </div>

                        <!-- Card: Username -->
                        <div class="glass-card">
                            <div class="card-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"></circle><path d="M16 8v5a3 3 0 0 0 6 0v-1a10 10 0 1 0-3.92 7.94"></path></svg>
                                Username
                            </div>
                            <div class="card-value">{{ $user->username }}</div>
                            <form action="{{ route('profile.update-username') }}" method="POST" class="card-form">
                                @csrf
                                <input type="text" name="username" class="card-input" placeholder="Username baru..." value="{{ old('username') }}" required>
                                <button type="submit" class="card-btn">Perbarui Username</button>
                            </form>
                            @error('username')
                                <div class="alert-error">{{ $message }}</div>
                            @enderror
                            @if(session('success'))
                                <div class="alert-success">{{ session('success') }}</div>
                            @endif
                        </div>

                        <!-- Card: Email & 2FA -->
                        <div class="glass-card" style="grid-column: 1 / -1;">
                            <div class="card-title">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                Alamat Email & Keamanan
                            </div>
                            
                            @if($user->email_verified_at)
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 20px;">
                                    <div class="card-value" style="margin: 0;">{{ $user->email }}</div>
                                    <span class="badge badge-success">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                        Terverifikasi
                                    </span>
                                </div>
                            @else
                                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 20px;">
                                    <div class="card-value" style="margin: 0;">{{ $user->email }}</div>
                                    <span class="badge badge-danger">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        Belum Terverifikasi
                                    </span>
                                </div>

                                <div style="display: flex; gap: 20px; flex-wrap: wrap;">
                                    <form action="{{ route('profile.update-email') }}" method="POST" class="card-form" style="flex: 1; min-width: 280px; margin-top: 0;">
                                        @csrf
                                        <div style="display: flex; gap: 10px;">
                                            <input type="email" name="email" class="card-input" value="{{ old('email', $user->email) }}" required>
                                            <button type="submit" class="card-btn" style="white-space: nowrap;">Ubah Email</button>
                                        </div>
                                    </form>

                                    @if(!$user->otp_code)
                                        <form action="{{ route('profile.send-otp') }}" method="POST" class="card-form" style="margin-top: 0; align-items: flex-start;">
                                            @csrf
                                            <button type="submit" class="card-btn card-btn-secondary" style="height: 100%;">Minta Kode OTP</button>
                                        </form>
                                    @else
                                        <form action="{{ route('profile.verify-otp') }}" method="POST" class="card-form" style="flex: 1; min-width: 280px; margin-top: 0;">
                                            @csrf
                                            <div style="display: flex; gap: 10px;">
                                                <input type="text" name="otp" class="card-input" placeholder="Masukkan 6 Digit OTP" required style="letter-spacing: 2px; text-align: center;">
                                                <button type="submit" class="card-btn badge-success" style="color: #fff; background: #10b981; border:none; white-space: nowrap;">Verifikasi</button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            @endif

                            @error('email')
                                <div class="alert-error">{{ $message }}</div>
                            @enderror
                            @if(session('success_email'))
                                <div class="alert-success">{{ session('success_email') }}</div>
                            @endif
                            @error('otp')
                                <div class="alert-error">{{ $message }}</div>
                            @enderror
                            @if(session('success_otp'))
                                <div class="alert-success">{{ session('success_otp') }}</div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        @else
            <section class="profile-card" style="text-align: center; background: rgba(7, 10, 19, 0.85); padding: 40px; border-radius: 20px; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(20px);">
                <span style="color:#818cf8;font-weight:900;letter-spacing:.12em;text-transform:uppercase;display:block;margin-bottom:12px;">Peringatan</span>
                <h1 style="color:#fff;font-size:2.4rem;margin:0 0 16px;">User Belum Login</h1>
                <p style="color:rgba(255,255,255,0.7);line-height:1.7;max-width:500px;margin:0 auto 24px;">Silakan login terlebih dahulu untuk memuat profile dan mengakses informasi akun Anda.</p>
                <div style="display: flex; gap: 14px; justify-content: center;">
                    <a href="{{ route('login') }}" style="background: #6366f1; color: #fff; padding: 12px 24px; border-radius: 12px; font-weight: 700; text-decoration: none; transition: transform 0.2s;">Login Sekarang</a>
                    <a href="{{ route('home') }}" style="background: rgba(255,255,255,0.1); color: #fff; padding: 12px 24px; border-radius: 12px; font-weight: 700; text-decoration: none; border: 1px solid rgba(255,255,255,0.2); transition: transform 0.2s;">{{ __('app.back_to_home') }}</a>
                </div>
            </section>
        @endif

        <!-- Avatar Selection Modal -->
        <div id="avatarModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); z-index:9999; backdrop-filter:blur(8px); align-items:center; justify-content:center;">
            <div style="background:rgba(15, 23, 42, 0.95); border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; padding: 30px; width: min(600px, 90%); box-shadow: 0 20px 60px rgba(0,0,0,0.5);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                    <h2 style="margin:0; color:#fff; font-size:1.5rem;">Pilih Avatar</h2>
                    <button onclick="closeAvatarModal()" style="background:transparent; border:none; color:#fff; cursor:pointer; font-size:1.5rem;">&times;</button>
                </div>
                <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(80px, 1fr)); gap:16px; margin-bottom:30px; max-height: 400px; overflow-y:auto; padding-right:10px;">
                    @for($i = 1; $i <= 10; $i++)
                        <div class="avatar-option" onclick="selectAvatar('images/avatars/avatar{{$i}}.svg')" style="cursor:pointer; border-radius:50%; overflow:hidden; border: 3px solid transparent; transition: all 0.2s;">
                            <img src="{{ asset('images/avatars/avatar' . $i . '.svg') }}" alt="Avatar {{$i}}" style="width:100%; height:auto; display:block;">
                        </div>
                    @endfor
                </div>
                
                <div id="avatarPreviewContainer" style="display:none; align-items:center; gap:20px; background:rgba(255,255,255,0.05); padding:16px; border-radius:12px; margin-bottom:20px;">
                    <div style="width:60px; height:60px; border-radius:50%; overflow:hidden; border:2px solid #6366f1;">
                        <img id="avatarPreviewImage" src="" alt="Preview" style="width:100%; height:100%; object-fit:cover;">
                    </div>
                    <div>
                        <strong style="display:block; color:#fff;">Pratinjau Avatar</strong>
                        <span style="color:rgba(255,255,255,0.6); font-size:0.85rem;">Avatar ini akan ditampilkan di Dashboard dan halaman lainnya.</span>
                    </div>
                </div>

                <form action="{{ route('profile.update-avatar') }}" method="POST" id="avatarForm">
                    @csrf
                    <input type="hidden" name="avatar" id="selectedAvatarInput" required>
                    <div style="display:flex; justify-content:flex-end; gap:12px;">
                        <button type="button" onclick="closeAvatarModal()" style="padding:10px 20px; border-radius:10px; background:transparent; border:1px solid rgba(255,255,255,0.2); color:#fff; cursor:pointer; font-weight:bold;">Batal</button>
                        <button type="submit" id="submitAvatarBtn" disabled style="padding:10px 20px; border-radius:10px; background:#6366f1; border:none; color:#fff; cursor:pointer; font-weight:bold; opacity:0.5; transition: all 0.2s;">Gunakan Avatar</button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openAvatarModal() {
                document.getElementById('avatarModal').style.display = 'flex';
            }

            function closeAvatarModal() {
                document.getElementById('avatarModal').style.display = 'none';
            }

            function selectAvatar(path) {
                // Remove highlight from all
                document.querySelectorAll('.avatar-option').forEach(el => {
                    el.style.borderColor = 'transparent';
                    el.style.transform = 'scale(1)';
                });
                
                // Add highlight to selected
                const selectedOption = event.currentTarget;
                selectedOption.style.borderColor = '#6366f1';
                selectedOption.style.transform = 'scale(1.1)';

                // Set input value
                document.getElementById('selectedAvatarInput').value = path;
                
                // Show preview
                document.getElementById('avatarPreviewContainer').style.display = 'flex';
                document.getElementById('avatarPreviewImage').src = '{{ asset('') }}' + path;
                
                // Enable button
                const submitBtn = document.getElementById('submitAvatarBtn');
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
            }
        </script>
    </main>
@endsection
