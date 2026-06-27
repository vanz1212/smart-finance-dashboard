@extends('layouts.app')

@section('title', __('auth.page_title_signup'))

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
            width: min(560px, 100%);
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
            max-width: 420px;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 46px;
            height: 46px;
            margin-right: 6px;
            border: 0;
            border-radius: 14px;
            background: transparent;
            color: rgba(248, 250, 252, 0.78);
            cursor: pointer;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .password-toggle:hover {
            background: rgba(255, 255, 255, 0.08);
            color: #ffffff;
        }

        .password-toggle svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
        }

        .password-requirements {
            display: none;
            gap: 8px;
            margin: -2px 0 0;
            padding: 14px 16px;
            border: 1px solid rgba(20, 184, 166, 0.24);
            border-radius: 14px;
            background: rgba(20, 184, 166, 0.08);
            color: rgba(248, 250, 252, 0.76);
            font-size: 0.84rem;
            line-height: 1.45;
        }

        .password-requirements.is-visible {
            display: grid;
        }

        .password-requirements strong {
            color: #ffffff;
        }

        .password-requirements span::before {
            content: "✕";
            margin-right: 8px;
            color: #fca5a5;
            font-weight: 900;
        }

        .password-requirements span.is-valid::before {
            content: "✓";
            color: #5eead4;
        }

        .password-requirements span.is-valid {
            color: #ecfeff;
        }

        .password-requirements span.is-invalid {
            color: rgba(248, 250, 252, 0.76);
        }

        .password-match {
            display: none;
            margin: -4px 0 0;
            font-size: 0.84rem;
            line-height: 1.45;
            color: rgba(248, 250, 252, 0.76);
        }

        .password-match.is-visible {
            display: block;
        }

        .password-match.is-valid {
            color: #5eead4;
        }

        .password-match.is-invalid {
            color: #fca5a5;
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

        .auth-footer {
            margin: 26px 0 0;
            color: rgba(248, 250, 252, 0.68);
            text-align: center;
            font-size: 0.9rem;
        }

        .auth-footer a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 700;
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
        }
    </style>

    <section class="auth-scene">
        <a class="back-home" href="{{ route('home') }}">← Beranda</a>

        <div class="auth-card">
            <div class="auth-mark" aria-hidden="true"></div>
            <h1>Buat <span>Akun</span></h1>
            <p class="subtitle">Daftarkan akun baru untuk mengakses dashboard, Smart Finance, Perpajakan, dan Stata.</p>

            <form class="modern-login-form" action="{{ route('signup.process') }}" method="POST">
                @csrf

                @if ($errors->any())
                    <div class="auth-alert">{{ $errors->first() }}</div>
                @endif

                <label class="modern-field">
                    <span>Nama Lengkap</span>
                    <div class="input-shell">
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama kamu" required autofocus>
                    </div>
                </label>

                <label class="modern-field">
                    <span>Username</span>
                    <div class="input-shell">
                        <input type="text" name="username" value="{{ old('username') }}" placeholder="username_kamu" required>
                    </div>
                </label>

                <label class="modern-field">
                    <span>Email</span>
                    <div class="input-shell">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                    </div>
                </label>

                <label class="modern-field">
                    <span>Password</span>
                    <div class="input-shell">
                        <input
                            id="signupPassword"
                            type="password"
                            name="password"
                            minlength="8"
                            pattern="(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{8,}"
                            title="Minimal 8 karakter dan wajib memiliki huruf kapital, angka, serta simbol."
                            autocomplete="new-password"
                            required
                        >
                        <button type="button" class="password-toggle" data-password-toggle="signupPassword" aria-label="Tampilkan password">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M2 12C4.6 7.8 8 5.7 12 5.7C16 5.7 19.4 7.8 22 12C19.4 16.2 16 18.3 12 18.3C8 18.3 4.6 16.2 2 12Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="3.2" stroke-width="1.8"/>
                            </svg>
                        </button>
                    </div>
                </label>

                <label class="modern-field">
                    <span>Konfirmasi Password</span>
                    <div class="input-shell">
                        <input id="signupPasswordConfirmation" type="password" name="password_confirmation" autocomplete="new-password" required>
                        <button type="button" class="password-toggle" data-password-toggle="signupPasswordConfirmation" aria-label="Tampilkan konfirmasi password">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M2 12C4.6 7.8 8 5.7 12 5.7C16 5.7 19.4 7.8 22 12C19.4 16.2 16 18.3 12 18.3C8 18.3 4.6 16.2 2 12Z" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                                <circle cx="12" cy="12" r="3.2" stroke-width="1.8"/>
                            </svg>
                        </button>
                    </div>
                </label>

                <div class="password-requirements" id="passwordRequirements">
                    <strong>Syarat password:</strong>
                    <span data-rule="length">Minimal 8 karakter</span>
                    <span data-rule="uppercase">Memiliki setidaknya satu huruf kapital</span>
                    <span data-rule="number">Memiliki setidaknya satu angka</span>
                    <span data-rule="symbol">Memiliki setidaknya satu simbol, seperti ! @ # $ %</span>
                </div>

                <p class="password-match" id="passwordMatchMessage"></p>

                <button type="submit" class="login-submit">Daftar Sekarang</button>
            </form>

            <p class="auth-footer">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
        </div>
    </section>

    <script>
        (() => {
            const passwordInput = document.getElementById('signupPassword');
            const confirmationInput = document.getElementById('signupPasswordConfirmation');
            const requirements = document.getElementById('passwordRequirements');
            const matchMessage = document.getElementById('passwordMatchMessage');

            if (!passwordInput || !confirmationInput || !requirements || !matchMessage) {
                return;
            }

            const rules = {
                length: (value) => value.length >= 8,
                uppercase: (value) => /[A-Z]/.test(value),
                number: (value) => /[0-9]/.test(value),
                symbol: (value) => /[^A-Za-z0-9]/.test(value),
            };

            const ruleElements = Object.fromEntries(
                Object.keys(rules).map((rule) => [rule, requirements.querySelector(`[data-rule="${rule}"]`)])
            );

            const updateRequirements = () => {
                const value = passwordInput.value;
                const shouldShow = value.length > 0 || document.activeElement === passwordInput;

                requirements.classList.toggle('is-visible', shouldShow);

                Object.entries(rules).forEach(([rule, validator]) => {
                    const element = ruleElements[rule];
                    if (!element) {
                        return;
                    }

                    const isValid = validator(value);
                    element.classList.toggle('is-valid', isValid);
                    element.classList.toggle('is-invalid', !isValid);
                });
            };

            const updateConfirmation = () => {
                const passwordValue = passwordInput.value;
                const confirmationValue = confirmationInput.value;
                const shouldShow = confirmationValue.length > 0 || document.activeElement === confirmationInput;

                matchMessage.classList.toggle('is-visible', shouldShow);

                if (!shouldShow) {
                    matchMessage.textContent = '';
                    matchMessage.classList.remove('is-valid', 'is-invalid');
                    return;
                }

                const matches = passwordValue.length > 0 && confirmationValue === passwordValue;
                matchMessage.textContent = matches ? '✓ Konfirmasi password sudah sesuai.' : '✕ Konfirmasi password belum sama.';
                matchMessage.classList.toggle('is-valid', matches);
                matchMessage.classList.toggle('is-invalid', !matches);
            };

            passwordInput.addEventListener('focus', updateRequirements);
            passwordInput.addEventListener('input', () => {
                updateRequirements();
                updateConfirmation();
            });
            passwordInput.addEventListener('blur', updateRequirements);

            confirmationInput.addEventListener('focus', updateConfirmation);
            confirmationInput.addEventListener('input', updateConfirmation);
            confirmationInput.addEventListener('blur', updateConfirmation);

            document.querySelectorAll('[data-password-toggle]').forEach((button) => {
                button.addEventListener('click', () => {
                    const input = document.getElementById(button.dataset.passwordToggle);

                    if (!input) {
                        return;
                    }

                    const showing = input.type === 'text';
                    input.type = showing ? 'password' : 'text';
                    button.setAttribute('aria-label', showing ? 'Tampilkan password' : 'Sembunyikan password');
                });
            });

            updateRequirements();
            updateConfirmation();
        })();
    </script>
@endsection
