@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')
<style>
    .site-header, .site-footer { display: none !important; }
    
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
        margin-bottom: 10px;
        font-weight: 700;
    }

    .form-main p.subtitle {
        color: rgba(255,255,255,0.6);
        margin-bottom: 30px;
        line-height: 1.5;
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
        right: 30px;
        color: rgba(255,255,255,0.5);
        text-decoration: none;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        transition: color 0.2s;
        z-index: 10;
    }
    .back-home:hover {
        color: white;
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
            <h1>Reset Password</h1>
            <p>Lupa kata sandi Anda? Jangan khawatir. Masukkan email Anda dan kami akan mengirimkan kode OTP untuk mengatur ulang sandi Anda.</p>
        </div>
        <div class="presented-by">
            presented by <img src="{{ asset('images/nexio_logo.png') }}" alt="Nexio">
        </div>
    </div>
    <div class="split-right">
        <a href="{{ route('login') }}" class="back-home">✕</a>
        <div class="form-container">
            <div class="form-main">
                <h2>Forgot Password?</h2>
                <p class="subtitle">Kami akan mengirimkan kode OTP ke email Anda.</p>
                @if($errors->any())
                    <div style="background: rgba(239, 68, 68, 0.1); color: #ef4444; padding: 12px; border-radius: 4px; margin-bottom: 20px; font-size: 0.9rem;">
                        {{ $errors->first() }}
                    </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="minimal-field" style="margin-bottom: 30px;">
                        <input type="email" name="email" id="email" required placeholder=" " value="{{ old('email') }}">
                        <label for="email">Alamat Email Terdaftar</label>
                    </div>
        
                    <button type="submit" class="submit-btn">Kirim Kode OTP</button>

                    <div class="form-footer">
                        Kembali ke <a href="{{ route('login') }}">Halaman Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
