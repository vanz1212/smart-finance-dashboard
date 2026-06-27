@extends('layouts.app')

@section('title', __('auth.page_title_login'))

@section('content')
    <style>
        .site-header,
        .site-footer {
            display: none !important;
        }

        body {
            background: #111827;
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

        .auth-scene {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 32px 18px;
            position: relative;
            overflow: hidden;
            background:
                linear-gradient(180deg, rgba(7, 22, 28, 0.18), rgba(5, 18, 24, 0.66)),
                url('{{ asset('images/backgroundfinance.jpg') }}') center / cover no-repeat;
        }

        .auth-scene::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                linear-gradient(90deg, rgba(4, 18, 22, 0.34), rgba(10, 72, 67, 0.16), rgba(4, 18, 22, 0.34)),
                radial-gradient(circle at 50% 8%, rgba(245, 199, 92, 0.22), transparent 24%);
            pointer-events: none;
        }

        .auth-card {
            width: min(520px, 100%);
            position: relative;
            z-index: 1;
            padding: 34px 38px 30px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 24px;
            background: linear-gradient(180deg, rgba(13, 47, 51, 0.74), rgba(6, 24, 32, 0.78));
            box-shadow: 0 28px 80px rgba(3, 16, 22, 0.5);
            backdrop-filter: blur(18px);
            color: #f8fafc;
        }

        .back-home {
            position: absolute;
            top: 22px;
            left: 22px;
            z-index: 2;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            min-height: 40px;
            padding: 0 14px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            background: rgba(6, 24, 32, 0.44);
            color: #ffffff;
            text-decoration: none;
            font-weight: 800;
            backdrop-filter: blur(12px);
        }

        .back-home:hover {
            border-color: rgba(243, 201, 105, 0.75);
            color: #f3c969;
        }

        .auth-mark {
            width: 42px;
            height: 42px;
            margin: 0 auto 18px;
            border-radius: 50%;
            border: 2px dashed rgba(255, 255, 255, 0.78);
            box-shadow: 0 0 28px rgba(245, 199, 92, 0.32);
        }

        .auth-card h1 {
            margin: 0;
            text-align: center;
            font-size: clamp(2.2rem, 6vw, 3.4rem);
            line-height: 1.05;
            font-weight: 600;
            letter-spacing: 0;
        }

        .auth-card h1 span {
            color: #f3c969;
        }

        .auth-card .subtitle {
            max-width: 390px;
            margin: 14px auto 28px;
            color: rgba(248, 250, 252, 0.72);
            text-align: center;
            line-height: 1.55;
        }

        .modern-login-form {
            display: grid;
            gap: 18px;
        }

        .modern-field {
            display: grid;
            gap: 8px;
        }

        .modern-field span {
            color: rgba(248, 250, 252, 0.72);
            font-size: 0.82rem;
            font-weight: 600;
        }

        .input-shell {
            display: flex;
            align-items: center;
            min-height: 54px;
            border: 1px solid rgba(255, 255, 255, 0.22);
            border-radius: 18px;
            background: rgba(21, 24, 56, 0.34);
            transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
        }

        .input-shell:focus-within {
            border-color: rgba(20, 184, 166, 0.95);
            box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.18);
            background: rgba(21, 24, 56, 0.52);
        }

        .input-shell input {
            width: 100%;
            min-width: 0;
            border: 0;
            outline: 0;
            padding: 0 18px;
            background: transparent;
            color: #ffffff;
            font: inherit;
        }

        .input-shell input::placeholder {
            color: rgba(248, 250, 252, 0.58);
        }

        .input-shell input:-webkit-autofill,
        .input-shell input:-webkit-autofill:hover,
        .input-shell input:-webkit-autofill:focus,
        .input-shell input:-webkit-autofill:active {
            -webkit-text-fill-color: #ffffff;
            caret-color: #ffffff;
            border-radius: 18px;
            -webkit-box-shadow: 0 0 0 1000px rgba(21, 24, 56, 0.52) inset;
            box-shadow: 0 0 0 1000px rgba(21, 24, 56, 0.52) inset;
            transition: background-color 9999s ease-in-out 0s;
        }

        .password-toggle {
            flex: 0 0 auto;
            margin-right: 10px;
            padding: 8px 10px;
            border: 0;
            background: transparent;
            color: rgba(248, 250, 252, 0.72);
            cursor: pointer;
            font: inherit;
            font-size: 0.82rem;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            color: rgba(248, 250, 252, 0.78);
            font-size: 0.9rem;
        }

        .remember {
            display: inline-flex;
            gap: 9px;
            align-items: center;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: #14b8a6;
        }

        .form-options a,
        .auth-footer a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
        }

        .login-submit {
            min-height: 56px;
            margin-top: 2px;
            border: 0;
            border-radius: 999px;
            background: #ffffff;
            color: #1f2937;
            cursor: pointer;
            font: inherit;
            font-weight: 800;
            box-shadow: 0 18px 35px rgba(20, 184, 166, 0.14);
        }

        .quick-home {
            min-height: 50px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(243, 201, 105, 0.42);
            border-radius: 999px;
            background: rgba(243, 201, 105, 0.08);
            color: #f3c969;
            text-decoration: none;
            font-weight: 800;
        }

        .quick-home:hover {
            background: rgba(243, 201, 105, 0.15);
            color: #ffffff;
        }

        .divider {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            gap: 18px;
            align-items: center;
            margin: 8px 0;
            color: rgba(248, 250, 252, 0.56);
        }

        .divider::before,
        .divider::after {
            content: "";
            height: 1px;
            background: rgba(255, 255, 255, 0.18);
        }

        .google-button {
            min-height: 54px;
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.04);
            color: #ffffff;
            font: inherit;
            font-weight: 700;
        }

        .quick-home,
        .divider,
        .google-button {
            display: none;
        }

        .auth-footer {
            margin: 26px 0 0;
            color: rgba(248, 250, 252, 0.68);
            text-align: center;
            font-size: 0.9rem;
        }

        .login-note {
            margin: 0;
            color: rgba(248, 250, 252, 0.68);
            text-align: center;
            font-size: 0.88rem;
            line-height: 1.55;
        }

        .auth-alert {
            padding: 12px 14px;
            border: 1px solid rgba(252, 165, 165, 0.55);
            border-radius: 16px;
            background: rgba(127, 29, 29, 0.32);
            color: #fee2e2;
            font-weight: 700;
        }

        @media (max-width: 620px) {
            .auth-card {
                padding: 28px 22px 24px;
                border-radius: 20px;
            }

            .form-options {
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>

    <section class="auth-scene">
        <a class="back-home" href="{{ route('home') }}">← Beranda</a>

        <div class="auth-card">
            <div class="auth-mark" aria-hidden="true"></div>
            <h1>Welcome <span>back!</span></h1>
            <p class="subtitle">Sign in to access your finance dashboard, tax tools, and personal analysis workspace.</p>

            <form class="modern-login-form" action="{{ route('login.process') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="auth-alert">Email atau password belum benar.</div>
                @endif

                <label class="modern-field">
                    <span>Email</span>
                    <div class="input-shell">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required autofocus>
                    </div>
                </label>

                <label class="modern-field">
                    <span>Password</span>
                    <div class="input-shell">
                        <input id="passwordInput" type="password" name="password" placeholder="Enter your password" required>
                        <button class="password-toggle" type="button" onclick="toggleLoginPassword()">Show</button>
                    </div>
                </label>

                <div class="form-options">
                    <label class="remember">
                        <input type="checkbox" name="remember" value="1" checked>
                        <span>Remember me</span>
                    </label>
                    <a href="{{ route('login') }}">Forgot password?</a>
                </div>

                <button type="submit" class="login-submit">Log In</button>

                <p class="login-note">Akun baru menggunakan password minimal 8 karakter yang memiliki huruf kapital, angka, dan simbol.</p>

                <a class="quick-home" href="{{ route('dashboard') }}">Masuk Ke Halaman Utama</a>

                <div class="divider">Or</div>

                <button type="button" class="google-button">Sign In with Google</button>
            </form>

            <p class="auth-footer">Don't have an account? <a href="{{ route('signup') }}">Sign Up</a></p>
        </div>
    </section>

    <script>
        function toggleLoginPassword() {
            const input = document.getElementById('passwordInput');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
@endsection
