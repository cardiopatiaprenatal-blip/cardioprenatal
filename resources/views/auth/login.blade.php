@extends('layouts.auth')

@section('title', 'Login')

@section('subtitle', 'Acesse sua conta')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap');

    :root {
        --primary:      #7f0c1a;
        --accent:       #c0392b;
        --accent-mid:   #e74c3c;
        --accent-light: #fdf0f0;
        --success:      #27ae60;
        --danger:       #c0392b;
        --text:         #1c1a1a;
        --muted:        #8a6f6f;
        --border:       #f0d5d5;
        --surface:      #ffffff;
        --bg:           #fdf5f5;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'DM Sans', sans-serif;
        background: var(--bg);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    body::before {
        content: '';
        position: fixed;
        inset: 0;
        background:
            radial-gradient(ellipse 80% 60% at 75% -10%, rgba(192,57,43,.15) 0%, transparent 70%),
            radial-gradient(ellipse 60% 50% at -10% 85%, rgba(127,12,26,.12) 0%, transparent 65%);
        pointer-events: none;
        z-index: 0;
    }

    .ecg-bg {
        position: fixed;
        bottom: 18%;
        left: 0;
        width: 100%;
        opacity: .055;
        pointer-events: none;
        z-index: 0;
    }

    .deco-circle {
        position: fixed;
        border-radius: 50%;
        pointer-events: none;
        z-index: 0;
    }
    .deco-circle-1 { width: 380px; height: 380px; border: 1.5px solid rgba(192,57,43,.13); top: -120px; right: -90px; }
    .deco-circle-2 { width: 220px; height: 220px; border: 1.5px solid rgba(127,12,26,.09); bottom: 50px; left: -70px; }
    .deco-circle-3 { width: 100px; height: 100px; background: rgba(231,76,60,.06); top: 35%; left: 4%; border-radius: 50%; }

    .login-card {
        position: relative;
        z-index: 1;
        background: var(--surface);
        border-radius: 28px;
        box-shadow:
            0 2px 4px rgba(127,12,26,.04),
            0 16px 48px rgba(127,12,26,.12),
            0 0 0 1px rgba(240,213,213,.8);
        padding: 54px 52px 46px;
        width: 100%;
        max-width: 480px;
        animation: cardIn .55s cubic-bezier(.22,.97,.58,1) both;
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(28px) scale(.97); }
        to   { opacity: 1; transform: translateY(0) scale(1); }
    }

    .login-header { text-align: center; margin-bottom: 38px; }

    .login-logo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 68px; height: 68px;
        background: linear-gradient(135deg, var(--primary), var(--accent-mid));
        border-radius: 20px;
        margin-bottom: 20px;
        box-shadow: 0 6px 20px rgba(192,57,43,.35);
        position: relative;
    }

    .login-logo::after {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 24px;
        border: 2px solid rgba(192,57,43,.3);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: .6; }
        50%       { transform: scale(1.08); opacity: .15; }
    }

    .login-logo svg { width: 34px; height: 34px; color: #fff; filter: drop-shadow(0 2px 4px rgba(0,0,0,.2)); }

    .login-title {
        font-family: 'DM Serif Display', serif;
        font-size: 28px;
        color: var(--primary);
        letter-spacing: -.4px;
        line-height: 1.2;
    }

    .login-subtitle { font-size: 14.5px; color: var(--muted); margin-top: 7px; font-weight: 400; }

    .login-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: var(--accent-light);
        border: 1px solid rgba(192,57,43,.18);
        color: var(--accent);
        font-size: 12px;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 99px;
        margin-top: 12px;
        letter-spacing: .04em;
        text-transform: uppercase;
    }
    .login-badge svg { width: 11px; height: 11px; }

    .field { margin-bottom: 22px; animation: fieldIn .5s cubic-bezier(.22,.97,.58,1) both; }
    .field:nth-child(1) { animation-delay: .10s; }
    .field:nth-child(2) { animation-delay: .18s; }

    @keyframes fieldIn {
        from { opacity: 0; transform: translateX(-10px); }
        to   { opacity: 1; transform: translateX(0); }
    }

    .field-label { display: block; font-size: 13px; font-weight: 600; color: var(--text); margin-bottom: 8px; letter-spacing: .01em; }

    .field-wrap { position: relative; }

    .field-icon {
        position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
        color: var(--muted); width: 18px; height: 18px; pointer-events: none; transition: color .2s;
    }
    .field-wrap:focus-within .field-icon { color: var(--accent); }

    .field-input {
        width: 100%;
        padding: 13px 14px 13px 42px;
        border: 1.5px solid var(--border);
        border-radius: 12px;
        font-size: 15px;
        font-family: 'DM Sans', sans-serif;
        color: var(--text);
        background: #fffafa;
        outline: none;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }

    .field-input:focus {
        border-color: var(--accent);
        background: var(--surface);
        box-shadow: 0 0 0 3.5px rgba(192,57,43,.12);
    }

    .field-input.is-error { border-color: var(--danger); box-shadow: 0 0 0 3px rgba(192,57,43,.10); }

    .field-error { font-size: 12.5px; color: var(--danger); margin-top: 6px; display: flex; align-items: center; gap: 5px; }

    .btn-eye {
        position: absolute; right: 13px; top: 50%; transform: translateY(-50%);
        background: none; border: none; cursor: pointer; color: var(--muted); padding: 2px; transition: color .2s; display: flex;
    }
    .btn-eye:hover { color: var(--accent); }

    .btn-primary {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 14px;
        background: linear-gradient(135deg, var(--primary) 0%, var(--accent-mid) 100%);
        color: #fff; border: none; border-radius: 12px;
        font-size: 15.5px; font-weight: 600; font-family: 'DM Sans', sans-serif;
        cursor: pointer; box-shadow: 0 4px 18px rgba(192,57,43,.32);
        transition: opacity .2s, transform .15s, box-shadow .2s;
        animation: fieldIn .5s .30s cubic-bezier(.22,.97,.58,1) both;
        letter-spacing: .01em;
    }

    .btn-primary:hover { opacity: .92; transform: translateY(-1px); box-shadow: 0 7px 24px rgba(192,57,43,.38); }
    .btn-primary:active { transform: translateY(0); opacity: 1; }
    .btn-primary:disabled { opacity: .65; cursor: not-allowed; transform: none; }

    .btn-spinner {
        display: none; width: 17px; height: 17px;
        border: 2.5px solid rgba(255,255,255,.4); border-top-color: #fff;
        border-radius: 50%; animation: spin .7s linear infinite;
    }
    .btn-primary.loading .btn-spinner { display: block; }
    .btn-primary.loading .btn-label   { opacity: .7; }

    @keyframes spin { to { transform: rotate(360deg); } }

    .divider {
        display: flex; align-items: center; gap: 12px; margin: 28px 0;
        animation: fieldIn .5s .38s cubic-bezier(.22,.97,.58,1) both;
    }
    .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: var(--border); }
    .divider span { font-size: 12px; color: var(--muted); white-space: nowrap; font-weight: 500; }

    .register-row { text-align: center; animation: fieldIn .5s .44s cubic-bezier(.22,.97,.58,1) both; }
    .register-row p { font-size: 14px; color: var(--muted); }
    .link-register { color: var(--accent); font-weight: 600; text-decoration: none; transition: color .2s; }
    .link-register:hover { color: var(--primary); text-decoration: underline; }

    @media (max-width: 520px) {
        .login-card { padding: 38px 24px 32px; border-radius: 20px; }
        .login-title { font-size: 23px; }
    }
</style>

<div class="deco-circle deco-circle-1"></div>
<div class="deco-circle deco-circle-2"></div>
<div class="deco-circle deco-circle-3"></div>

<svg class="ecg-bg" viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
    <polyline points="0,60 180,60 210,60 225,20 240,100 255,10 270,110 285,60 310,60 420,60 435,60 450,20 465,100 480,10 495,110 510,60 530,60 640,60 655,60 670,20 685,100 700,10 715,110 730,60 750,60 860,60 875,60 890,20 905,100 920,10 935,110 950,60 970,60 1080,60 1095,60 1110,20 1125,100 1140,10 1155,110 1170,60 1190,60 1300,60 1315,60 1330,20 1345,100 1360,10 1375,110 1390,60 1440,60"
        stroke="#c0392b" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
</svg>

<div class="login-card">

    <div class="login-header">
        <div class="login-logo">
            <svg viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
            </svg>
        </div>
        <h1 class="login-title">Bem-vindo de volta</h1>
        <p class="login-subtitle">Acesse sua conta com seu CRM</p>
        <span class="login-badge">
            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
            Cardiologia
        </span>
    </div>

    <form method="POST" id="loginForm" novalidate>
        @csrf

        <div class="field">
            <label class="field-label" for="crm">CRM</label>
            <div class="field-wrap">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/>
                </svg>
                <input type="text" id="crm" name="crm" value="{{ old('crm') }}"
                    placeholder="Digite seu CRM" autocomplete="username"
                    class="field-input {{ $errors->has('crm') ? 'is-error' : '' }}">
            </div>
            @error('crm')
                <p class="field-error">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <div class="field">
            <label class="field-label" for="password">Senha</label>
            <div class="field-wrap">
                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
                <input type="password" id="password" name="password"
                    placeholder="Digite sua senha" autocomplete="current-password"
                    class="field-input {{ $errors->has('password') ? 'is-error' : '' }}">
                <button type="button" class="btn-eye" id="togglePassword" aria-label="Mostrar/ocultar senha">
                    <svg id="eyeIcon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="field-error">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button type="submit" class="btn-primary" id="submitBtn">
            <span class="btn-spinner"></span>
            <span class="btn-label">Entrar</span>
        </button>
    </form>

    <div class="divider"><span>ou</span></div>

    <div class="register-row">
        <p>Não tem uma conta? <a href="{{ route('register') }}" class="link-register">Cadastre-se</a></p>
    </div>

</div>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput  = document.getElementById('password');
    const eyeIcon        = document.getElementById('eyeIcon');

    const eyeOpen  = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
    const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>`;

    togglePassword.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        eyeIcon.innerHTML  = isPassword ? eyeClosed : eyeOpen;
    });

    const loginForm = document.getElementById('loginForm');
    const submitBtn = document.getElementById('submitBtn');

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        submitBtn.classList.add('loading');
        submitBtn.disabled = true;

        const formData = new FormData(this);

        fetch('/login', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(async response => {
            const data = await response.json();
            if (!response.ok) throw data;

            new Noty({
                type: 'success',
                layout: 'topRight',
                text: 'Login realizado com sucesso!',
                timeout: 2000
            }).show();

            setTimeout(() => {
                window.location.href = '/dashboard';
            }, 2000);
        })
        .catch(error => {
            submitBtn.classList.remove('loading');
            submitBtn.disabled = false;

            if (error.errors) {
                Object.values(error.errors).forEach(messages => {
                    messages.forEach(msg => {
                        new Noty({ type: 'error', layout: 'topRight', text: msg, timeout: 3000 }).show();
                    });
                });
                return;
            }

            new Noty({
                type: 'error',
                layout: 'topRight',
                text: error.message || 'Erro ao tentar login',
                timeout: 3000
            }).show();
        });
    });
</script>

@endsection