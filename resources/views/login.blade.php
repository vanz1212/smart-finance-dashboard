@extends('layouts.app')

@section('title', __('auth.page_title_login'))

@section('content')
<style>
    .site-header, .site-footer { display: none !important; }
    
    .toggle-password {
        position: absolute;
        right: 0;
        top: 8px;
        cursor: pointer;
        color: rgba(255,255,255,0.4);
        transition: color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 4px;
        z-index: 10;
    }
    .toggle-password:hover {
        color: rgba(255,255,255,0.8);
    }
    .minimal-field input[type="password"],
    .minimal-field input[type="text"].pass-visible {
        padding-right: 36px;
    }

    
    body, html { 
        margin: 0; 
        padding: 0; 
        height: 100%; 
        font-family: 'Inter', sans-serif; 
        background: #070b14; 
    }
    
    body > .container { 
        max-width: none; 
        width: 100%; 
        min-height: 100vh; 
        padding: 0; 
    }
    
    .content { 
        min-height: 100vh; 
        display: flex; 
        align-items: center; 
        justify-content: center;
        padding: 40px 20px;
        background: url('{{ asset('images/Loginbackground.jpg') }}') center / cover fixed no-repeat;
        position: relative;
    }
    
    .content::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(5, 10, 20, 0.4); 
    }
    
    .split-layout {
        display: flex;
        width: 100%;
        max-width: 1300px;
        min-height: 600px;
        height: auto;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 30px 60px -12px rgba(0, 0, 0, 0.7);
        position: relative;
        z-index: 1;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    .split-left {
        flex: 1.2;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 60px;
        color: white;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.6), rgba(11, 17, 32, 0.85));
        backdrop-filter: blur(12px);
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
        max-width: 520px;
    }
    
    .hero-content h1 {
        font-size: clamp(2.5rem, 5vw, 4.2rem);
        font-weight: 800;
        margin-bottom: 20px;
        line-height: 1.1;
        letter-spacing: -0.5px;
    }
    
    .hero-content p {
        font-size: 1.1rem;
        color: rgba(255,255,255,0.7);
        line-height: 1.6;
    }
    
    .presented-by {
        position: absolute;
        bottom: 40px;
        left: 60px;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(8px);
        padding: 10px 24px;
        border-radius: 999px;
        color: rgba(255,255,255,0.9);
        font-weight: 500;
        font-size: 1rem;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .presented-by img {
        height: 22px;
        object-fit: contain;
    }

    .split-right {
        flex: 1.1;
        background: rgba(7, 10, 19, 0.95);
        backdrop-filter: blur(24px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 40px;
        position: relative;
        border-left: 1px solid rgba(255,255,255,0.05);
    }

    .form-container {
        width: 100%;
        max-width: 480px;
        display: flex;
        flex-direction: column;
        padding: 20px 0;
    }
    
    .form-main h2 {
        color: white;
        font-size: 2.2rem;
        margin-bottom: 30px;
        font-weight: 700;
    }

    .minimal-field {
        position: relative;
    }

    .minimal-field input {
        width: 100%;
        background: transparent;
        border: none;
        border-bottom: 1px solid rgba(255,255,255,0.15);
        color: white;
        font-size: 1rem;
        padding: 10px 0;
        transition: border-color 0.3s;
    }
    .minimal-field input:focus {
        outline: none;
        border-bottom-color: #6366f1;
    }
    .minimal-field input::placeholder {
        color: transparent;
    }

    /* Fix for Chrome autofill background */
    .minimal-field input:-webkit-autofill,
    .minimal-field input:-webkit-autofill:hover, 
    .minimal-field input:-webkit-autofill:focus, 
    .minimal-field input:-webkit-autofill:active {
        -webkit-box-shadow: 0 0 0 30px #0a1120 inset !important;
        -webkit-text-fill-color: white !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    .minimal-field label {
        position: absolute;
        left: 0;
        top: 10px;
        color: rgba(255,255,255,0.5);
        font-size: 0.95rem;
        pointer-events: none;
        transition: 0.3s ease all;
    }

    .minimal-field input:focus ~ label,
    .minimal-field input:not(:placeholder-shown) ~ label {
        top: -16px;
        font-size: 0.75rem;
        color: #6366f1;
    }

    .submit-btn {
        width: 100%;
        background: #3b82f6;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        margin-top: 10px;
        transition: background 0.2s, transform 0.1s;
    }
    .submit-btn:hover {
        background: #2563eb;
    }
    .submit-btn:active {
        transform: scale(0.98);
    }

    .separator {
        display: flex;
        align-items: center;
        text-align: center;
        margin: 20px 0;
        color: rgba(255,255,255,0.4);
        font-size: 0.85rem;
    }
    .separator::before, .separator::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    .separator:not(:empty)::before {
        margin-right: .25em;
    }
    .separator:not(:empty)::after {
        margin-left: .25em;
    }

    .google-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        background: transparent;
        border: 1px solid rgba(255,255,255,0.2);
        color: white;
        padding: 12px;
        border-radius: 6px;
        font-size: 0.95rem;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
    }
    .google-btn:hover {
        background: rgba(255,255,255,0.05);
    }
    .google-btn img {
        height: 20px;
    }

    .form-footer {
        margin-top: 24px;
        text-align: center;
        color: rgba(255,255,255,0.5);
        font-size: 0.9rem;
    }
    .form-footer a {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
    }
    .form-footer a:hover {
        text-decoration: underline;
    }

    .back-home {
        position: absolute;
        top: 24px;
        left: 30px;
        color: rgba(255,255,255,0.7);
        text-decoration: none;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: color 0.2s;
        z-index: 10;
        background: rgba(255,255,255,0.05);
        padding: 8px 16px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.1);
    }
    .back-home:hover {
        color: white;
        background: rgba(255,255,255,0.1);
    }

    @media (max-width: 900px) {
        .split-layout { flex-direction: column; height: auto; max-height: none; min-height: 100vh; border-radius: 0; }
        .content { padding: 0; }
        .split-left { padding: 60px 30px; flex: none; min-height: 300px; }
        .presented-by { position: relative; bottom: auto; left: auto; margin-top: 30px; width: fit-content; }
        .split-right { flex: 1; padding: 40px 30px; }
        .form-container { max-width: 100%; }
        .back-home { top: 15px; right: 20px; }
    }
</style>

<div class="split-layout">
    <div class="split-left">
        <div class="hero-content">
            <h1>Welcome Back</h1>
            <p>Akses kembali Nexio Dashboard untuk melanjutkan analisis arus kas, perpajakan, dan makroekonomi Anda dengan cerdas dan mudah.</p>
        </div>
        <div class="presented-by">
            presented by <img src="{{ asset('images/nexio_logo.png') }}" alt="Nexio">
        </div>
    </div>
    <div class="split-right">
        <a href="{{ route('home') }}" class="back-home">
            <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            Kembali ke Beranda
        </a>
        <div class="form-container">
            <div class="form-main">
                <h2>Sign in</h2>
                @if($errors->any())
                    <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9rem;">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form method="POST" action="{{ route('login.process') }}">
                    @csrf

                    <div class="minimal-field" style="margin-bottom: 30px;">
                        <input type="email" name="email" id="email" required placeholder=" " value="{{ old('email') }}">
                        <label for="email">Your Email</label>
                    </div>
                    <div class="minimal-field" style="margin-bottom: 30px;">
                        <input type="password" name="password" id="password" required placeholder=" ">
                        <label for="password">Password</label>
                        <span class="toggle-password" onclick="togglePass(this, 'password')">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </span>
                    </div>

                    <div style="text-align: right; margin-top: -20px; margin-bottom: 20px;">
                        <a href="{{ route('password.request') }}" style="color: #3b82f6; text-decoration: none; font-size: 0.85rem; font-weight: 500;">Lupa Password?</a>
                    </div>
        
                    <button type="submit" class="submit-btn">Sign in</button>
                    
                    <div class="separator">OR</div>
                    
                    <a href="{{ route('google.login') }}" class="google-btn" data-no-spa>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20px" height="20px">
                            <path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"/>
                            <path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"/>
                            <path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"/>
                            <path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"/>
                        </svg>
                        Sign in with Google
                    </a>

                    <div class="form-footer">
                        Not a Member? <a href="{{ route('signup') }}">Sign up here</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
function togglePass(el, inputId) {
    const input = document.getElementById(inputId);
    const eyeOpen = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
    const eyeClosed = '<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
    
    if (input.type === 'password') {
        input.type = 'text';
        input.classList.add('pass-visible');
        el.innerHTML = eyeClosed;
    } else {
        input.type = 'password';
        input.classList.remove('pass-visible');
        el.innerHTML = eyeOpen;
    }
}
</script>
