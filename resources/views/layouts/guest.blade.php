<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'PeakScore') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Tokens ───────────────────────────────────────────────── */
        :root {
            --bg:            #f7f7f8;
            --surface:       #ffffff;
            --surface-2:     #f2f2f4;
            --panel:         #1c1f23;
            --border:        #e4e4e7;
            --border-strong: #d1d1d6;
            --text-primary:  #111214;
            --text-secondary:#5a5c63;
            --text-tertiary: #9499a3;
            --text-inverted: #f5f5f7;
            --accent:        #1c1f23;
            --accent-hover:  #2e3238;
            --ring:          rgba(28,31,35,.18);
            --t:             160ms cubic-bezier(.4,0,.2,1);
        }

        /* ── Reset ────────────────────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; }

        html, body {
            margin: 0; padding: 0;
            min-height: 100vh;
            background: var(--bg);
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
        }

        /* ── Shell ────────────────────────────────────────────────── */
        .auth-shell {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem 1rem;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* ── Card ─────────────────────────────────────────────────── */
        .auth-card {
            width: 100%;
            max-width: 880px;
            background: var(--surface);
            border-radius: 20px;
            border: 1px solid var(--border);
            box-shadow:
                0 1px 2px rgba(0,0,0,.04),
                0 8px 32px rgba(0,0,0,.07),
                0 32px 64px rgba(0,0,0,.05);
            display: grid;
            grid-template-columns: 320px 1fr;
            overflow: hidden;
            animation: cardIn 420ms cubic-bezier(.22,1,.36,1) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(18px) scale(.985); }
            to   { opacity: 1; transform: translateY(0)    scale(1); }
        }

        /* ── Left Panel ───────────────────────────────────────────── */
        .auth-panel {
            background: var(--panel);
            padding: 2.75rem 2rem 2.5rem;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        .auth-panel::before {
            content: ''; position: absolute;
            right: -80px; bottom: -80px;
            width: 260px; height: 260px;
            border-radius: 50%;
            background: rgba(255,255,255,.035);
            pointer-events: none;
        }
        .auth-panel::after {
            content: ''; position: absolute;
            right: -40px; bottom: -40px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,.05);
            pointer-events: none;
        }

        .panel-logo {
            display: flex; align-items: center;
            gap: .625rem; margin-bottom: 2.25rem;
        }
        .panel-logo img,
        .panel-logo svg {
            width: 30px; height: 30px;
            fill: var(--text-inverted); flex-shrink: 0;
        }
        .panel-logo-name {
            font-family: 'DM Serif Display', serif;
            font-size: 1.2rem;
            color: var(--text-inverted);
            letter-spacing: .01em;
        }
        .panel-eyebrow {
            font-size: .7rem; font-weight: 500;
            letter-spacing: .12em; text-transform: uppercase;
            color: var(--text-tertiary); margin-bottom: .5rem;
        }
        .panel-heading {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem; line-height: 1.15;
            color: var(--text-inverted);
            margin: 0 0 1rem; letter-spacing: -.01em;
        }
        .panel-desc {
            font-size: .85rem; line-height: 1.65;
            color: rgba(245,245,247,.45);
            max-width: 240px;
        }
        .panel-features {
            margin-top: auto; padding-top: 2.5rem;
            display: flex; flex-direction: column; gap: 1.1rem;
        }
        .panel-feature { display: flex; align-items: flex-start; gap: .875rem; }
        .feature-icon {
            width: 36px; height: 36px; border-radius: 9px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.1);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; color: rgba(245,245,247,.75);
        }
        .feature-icon svg {
            width: 16px; height: 16px;
            stroke: currentColor; fill: none;
            stroke-width: 1.75; stroke-linecap: round; stroke-linejoin: round;
        }
        .feature-text strong {
            display: block; font-size: .825rem; font-weight: 600;
            color: rgba(245,245,247,.9); margin-bottom: .15rem;
        }
        .feature-text span {
            font-size: .75rem; color: rgba(245,245,247,.4); line-height: 1.4;
        }

        /* ── Right / Form section ─────────────────────────────────── */
        .auth-form-section {
            padding: 3rem;
            display: flex; flex-direction: column; justify-content: center;
        }

        .form-header   { margin-bottom: 2.25rem; }
        .form-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.85rem; font-weight: 400;
            color: var(--text-primary);
            margin: 0 0 .35rem; letter-spacing: -.02em; line-height: 1.2;
        }
        .form-subtitle { font-size: .84rem; color: var(--text-secondary); }

        /* ── Unified Form Fields & Breeze Component Overrides ─────── */
        .field-label {
            display: block !important;
            font-size: .7rem !important;
            font-weight: 600 !important;
            letter-spacing: .1em !important;
            text-transform: uppercase !important;
            color: var(--text-secondary) !important;
            margin-bottom: .5rem !important;
            font-family: 'DM Sans', sans-serif !important;
        }

        .field-input {
            width: 100% !important;
            height: 46px !important;
            padding: 0 2.75rem !important;
            background: var(--surface-2) !important;
            border: 1.5px solid var(--border) !important;
            border-radius: 10px !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: .88rem !important;
            color: var(--text-primary) !important;
            transition: border-color var(--t), box-shadow var(--t), background var(--t) !important;
            outline: none !important;
            appearance: none !important;
            box-shadow: none !important;
            --tw-ring-shadow: none !important; /* Resets Tailwind ring presets */
        }
        .field-input::placeholder { color: var(--text-tertiary) !important; }
        .field-input:focus {
            background: var(--surface) !important;
            border-color: var(--text-primary) !important;
            box-shadow: 0 0 0 3.5px var(--ring) !important;
            outline: none !important;
        }

        .field-error {
            font-size: .75rem !important;
            color: #d1464a !important;
            margin-top: .375rem !important;
            font-family: 'DM Sans', sans-serif !important;
        }

        /* ── Field groups layout scaffolding ──────────────────────── */
        .field-group   { margin-bottom: 1.125rem; }
        .field-wrap    { position: relative; }

        .field-icon {
            position: absolute; left: .9rem; top: 50%;
            transform: translateY(-50%);
            width: 16px; height: 16px;
            color: var(--text-tertiary);
            pointer-events: none;
            display: flex; align-items: center;
        }
        .field-icon svg {
            width: 16px; height: 16px;
            stroke: currentColor; fill: none;
            stroke-width: 1.75; stroke-linecap: round; stroke-linejoin: round;
        }

        .field-toggle {
            position: absolute; right: .875rem; top: 50%;
            transform: translateY(-50%);
            background: none; border: none; cursor: pointer;
            color: var(--text-tertiary); padding: 0;
            display: flex; align-items: center;
            transition: color var(--t);
        }
        .field-toggle:hover { color: var(--text-secondary); }
        .field-toggle svg {
            width: 17px; height: 17px;
            stroke: currentColor; fill: none;
            stroke-width: 1.75; stroke-linecap: round; stroke-linejoin: round;
        }

        /* ── Remember + inline row ────────────────────────────────── */
        .inline-row {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .remember-row { display: flex; align-items: center; gap: .5rem; }
        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            border: 1.5px solid var(--border-strong) !important;
            border-radius: 4px; appearance: none !important;
            background: var(--surface-2) !important;
            cursor: pointer; flex-shrink: 0;
            transition: background var(--t), border-color var(--t);
            position: relative;
            accent-color: transparent;
        }
        .remember-row input[type="checkbox"]:checked {
            background: var(--accent) !important;
            border-color: var(--accent) !important;
        }
        .remember-row input[type="checkbox"]:checked::after {
            content: '';
            position: absolute; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 16 16' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M3 8l3.5 3.5L13 5' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' fill='none'/%3E%3C/svg%3E");
            background-size: 11px; background-repeat: no-repeat; background-position: center;
        }
        .remember-row label {
            font-size: .82rem; color: var(--text-secondary);
            cursor: pointer; user-select: none;
        }

        .forgot-link {
            font-size: .78rem; color: var(--text-secondary);
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: color var(--t), border-color var(--t);
        }
        .forgot-link:hover { color: var(--text-primary); border-color: var(--border-strong); }

        /* ── CTA Primary Button & Loader ──────────────────────────── */
        .btn-primary {
            width: 100%; height: 46px;
            background: var(--accent) !important;
            color: var(--text-inverted) !important;
            border: none !important; border-radius: 10px !important;
            font-family: 'DM Sans', sans-serif !important;
            font-size: .9rem !important; font-weight: 600 !important;
            letter-spacing: .025em !important;
            cursor: pointer;
            transition: background var(--t), transform var(--t), box-shadow var(--t);
            box-shadow: 0 1px 3px rgba(0,0,0,.18), 0 4px 12px rgba(0,0,0,.1) !important;
            margin-bottom: 1.5rem;
            display: flex; align-items: center; justify-content: center; gap: .5rem;
        }
        .btn-primary:hover {
            background: var(--accent-hover) !important;
            box-shadow: 0 2px 8px rgba(0,0,0,.22), 0 8px 24px rgba(0,0,0,.12) !important;
            transform: translateY(-1px);
        }
        .btn-primary:active { transform: translateY(0); }
        .btn-primary:disabled, .btn-loading { opacity: .72; cursor: not-allowed; transform: none; }

        .btn-spinner { display: inline-flex; align-items: center; gap: .5rem; }
        .spin { width: 16px; height: 16px; animation: spin 800ms linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ── Footer ───────────────────────────────────────────────── */
        .auth-footer { text-align: center; font-size: .82rem; color: var(--text-secondary); }
        .auth-footer a {
            color: var(--text-primary); font-weight: 600;
            text-decoration: none;
            border-bottom: 1.5px solid var(--border-strong);
            transition: border-color var(--t);
        }
        .auth-footer a:hover { border-color: var(--text-primary); }

        /* ── Alpine Cloak ─────────────────────────────────────────── */
        [x-cloak] { display: none !important; }

        /* ── Responsive breakpoint ────────────────────────────────── */
        @media (max-width: 680px) {
            .auth-card { grid-template-columns: 1fr; max-width: 420px; }
            .auth-panel { display: none; }
            .auth-form-section { padding: 2.25rem 1.75rem; }
        }
    </style>

    @stack('styles')
</head>
<body>
    <div class="auth-shell">
        <div class="auth-card">

            {{-- Left Banner Panel --}}
            <aside class="auth-panel">
                <div class="panel-logo">
                    <img src="{{ asset('images/logo.svg') }}" alt="PeakScore Logo" style="height: 30px; width: auto; object-fit: contain;">
                    <span class="panel-logo-name">PeakScore</span>
                </div>

                <p class="panel-eyebrow">Welcome to</p>
                <h1 class="panel-heading">Academic<br>Excellence<br>Platform</h1>
                <p class="panel-desc">Modern testing infrastructure, intelligent analytics, and seamless participant access.</p>

                <div class="panel-features">
                    <div class="panel-feature">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"/><path d="M9 9h6M9 13h4"/></svg>
                        </div>
                        <div class="feature-text">
                            <strong>Question Bank</strong>
                            <span>Verbal, Numerical &amp; Logical</span>
                        </div>
                    </div>
                    <div class="panel-feature">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg>
                        </div>
                        <div class="feature-text">
                            <strong>Student Access</strong>
                            <span>Secure test participation</span>
                        </div>
                    </div>
                    <div class="panel-feature">
                        <div class="feature-icon">
                            <svg viewBox="0 0 24 24"><path d="M3 3v18h18"/><path d="M7 16l4-4 4 4 4-4"/></svg>
                        </div>
                        <div class="feature-text">
                            <strong>Analytics</strong>
                            <span>Detailed performance reports</span>
                        </div>
                    </div>
                </div>
            </aside>

            {{-- Main Form Body Injection Slot --}}
            <main class="auth-form-section">
                {{ $slot }}
            </main>

        </div>
    </div>
</body>
</html>
