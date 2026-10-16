<x-guest-layout>

    {{-- Session Status Toast --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Form Header text styling --}}
    <div class="form-header">
        <h2 class="form-title">Welcome back</h2>
        <p class="form-subtitle">Sign in to continue your academic journey</p>
    </div>

    {{-- Submission Form Architecture --}}
    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- ── Email Row Element ───────────────────────────────────── --}}
        <div class="field-group">
            <x-input-label for="email" :value="__('Email')" class="field-label" />

            <div class="field-wrap">
                <span class="field-icon">
                    <svg viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m2 7 10 7 10-7"/></svg>
                </span>

                <x-text-input
                    id="email"
                    class="field-input"
                    type="email"
                    name="email"
                    :value="old('email')"
                    placeholder="{{ __('Enter your email') }}"
                    required
                    autofocus
                    autocomplete="username"
                />
            </div>

            <x-input-error :messages="$errors->get('email')" class="field-error" />
        </div>

        {{-- ── Password Row Element ────────────────────────────────── --}}
        <div class="field-group" x-data="{ show: false }">
            <x-input-label for="password" :value="__('Password')" class="field-label" />

            <div class="field-wrap">
                <span class="field-icon">
                    <svg viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                </span>

                <x-text-input
                    id="password"
                    class="field-input"
                    ::type="show ? 'text' : 'password'"
                    name="password"
                    placeholder="{{ __('Enter your password') }}"
                    required
                    autocomplete="current-password"
                />

                {{-- Interactive Visibility Toggle Button --}}
                <button
                    type="button"
                    @click="show = !show"
                    class="field-toggle"
                    :aria-label="show ? 'Hide password' : 'Show password'"
                >
                    {{-- Visibility Active (Eye Opened) --}}
                    <svg x-show="show" x-cloak viewBox="0 0 24 24">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    {{-- Visibility Blocked (Eye Slashed) --}}
                    <svg x-show="!show" viewBox="0 0 24 24">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                        <line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="field-error" />
        </div>

        {{-- ── Contextual Actions Row ──────────────────────────────── --}}
        <div class="inline-row">
            <div class="remember-row">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                >
                <label for="remember_me">{{ __('Remember me') }}</label>
            </div>

            @if (Route::has('password.request'))
                <a class="forgot-link" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        {{-- ── CTA Engine with Alpine Submitting State ─────────────── --}}
        <div x-data="{ loading: false }" @submit.window="loading = true">
            <button
                type="submit"
                class="btn-primary"
                :disabled="loading"
                :class="loading ? 'btn-loading' : ''"
            >
                <span x-show="!loading">{{ __('Sign In') }}</span>
                <span x-show="loading" x-cloak class="btn-spinner">
                    <svg viewBox="0 0 24 24" fill="none" class="spin">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="3" stroke-dasharray="31.4" stroke-dashoffset="10" stroke-linecap="round"/>
                    </svg>
                    {{ __('Signing in…') }}
                </span>
            </button>
        </div>

    </form>

    {{-- Registration Link --}}
    <p class="auth-footer">
        {{ __("Don't have an account?") }}&nbsp;
        <a href="{{ route('register') }}">{{ __('Sign up now') }}</a>
    </p>

</x-guest-layout>
