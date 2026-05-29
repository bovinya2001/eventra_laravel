<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Eventra') }} - Register</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=dm-sans:400,500,600,700&family=dm-serif-display:400&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #eeeaf8;
            --card: #ffffff;
            --purple: #7c3aed;
            --purple-light: #ede9fe;
            --purple-mid: #8b5cf6;
            --text-dark: #1a1523;
            --text-mid: #6b7280;
            --text-light: #9ca3af;
            --border: #e5e7eb;
            --input-bg: #f5f3ff;
            --input-border: #ddd6fe;
            --radius: 16px;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background-image:
                radial-gradient(ellipse 60% 40% at 20% 10%, rgba(167,139,250,0.18) 0%, transparent 70%),
                radial-gradient(ellipse 50% 50% at 80% 90%, rgba(124,58,237,0.12) 0%, transparent 60%);
        }

        .wrapper {
            width: 100%;
            max-width: 400px;
            animation: fadeUp 0.5s ease-out both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo ── */
        .logo-area {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 52px;
            height: 52px;
            background: var(--purple-light);
            border-radius: 14px;
            margin-bottom: 12px;
        }

        .logo-icon svg {
            width: 26px;
            height: 26px;
            color: var(--purple);
            stroke: var(--purple);
        }

        .logo-name {
            font-family: 'DM Serif Display', serif;
            font-size: 28px;
            color: var(--text-dark);
            letter-spacing: -0.3px;
            line-height: 1;
            margin-bottom: 6px;
        }

        .logo-tagline {
            font-size: 13.5px;
            color: var(--text-mid);
            font-weight: 400;
        }

        /* ── Tab Toggle ── */
        .tab-toggle {
            display: flex;
            background: #e5e0f5;
            border-radius: 50px;
            padding: 4px;
            margin-bottom: 20px;
        }

        .tab-btn {
            flex: 1;
            text-align: center;
            padding: 9px 0;
            border-radius: 46px;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-mid);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .tab-btn.active {
            background: #fff;
            color: var(--text-dark);
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .tab-btn:not(.active):hover { color: var(--purple); }

        /* ── Card ── */
        .card {
            background: var(--card);
            border-radius: var(--radius);
            padding: 28px 28px 24px;
            box-shadow: 0 4px 24px rgba(100,70,200,0.07), 0 1px 4px rgba(0,0,0,0.04);
        }

        .card-title {
            font-size: 17px;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 3px;
        }

        .card-sub {
            font-size: 13px;
            color: var(--text-mid);
            margin-bottom: 22px;
        }

        /* ── Form ── */
        .field { margin-bottom: 14px; }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .field input[type="text"],
        .field input[type="email"],
        .field input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            background: var(--input-bg);
            border: 1.5px solid var(--input-border);
            border-radius: 10px;
            font-family: inherit;
            font-size: 14px;
            color: var(--text-dark);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .field input::placeholder { color: var(--text-light); }

        .field input:focus {
            border-color: var(--purple-mid);
            box-shadow: 0 0 0 3px rgba(139,92,246,0.12);
        }

        /* ── Terms ── */
        .terms-row {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            margin-bottom: 18px;
            margin-top: 4px;
        }

        .terms-row input[type="checkbox"] {
            width: 15px;
            height: 15px;
            margin-top: 2px;
            accent-color: var(--purple);
            cursor: pointer;
            flex-shrink: 0;
        }

        .terms-row label {
            font-size: 12.5px;
            color: var(--text-mid);
            cursor: pointer;
            line-height: 1.5;
        }

        .terms-row a {
            color: var(--purple);
            font-weight: 500;
            text-decoration: none;
        }

        .terms-row a:hover { text-decoration: underline; }

        /* ── Submit ── */
        .btn-submit {
            width: 100%;
            padding: 13px;
            background: var(--text-dark);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.2px;
            transition: background 0.2s, transform 0.15s;
        }

        .btn-submit:hover {
            background: #2d1f4e;
            transform: translateY(-1px);
        }

        .btn-submit:active { transform: translateY(0); }

        /* ── Footer ── */
        .footer-link {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: var(--text-mid);
        }

        .footer-link a {
            color: var(--purple);
            font-weight: 600;
            text-decoration: none;
        }

        .footer-link a:hover { text-decoration: underline; }

        /* ── Alerts ── */
        .alert-error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 8px;
            padding: 10px 12px;
            color: #dc2626;
            font-size: 13px;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="wrapper">

        <!-- Logo -->
        <div class="logo-area">
            <div class="logo-icon">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.8" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="4" width="18" height="18" rx="3" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M16 2v4M8 2v4M3 10h18" stroke-linecap="round" stroke-linejoin="round"/>
                    <circle cx="8" cy="15" r="1" fill="currentColor" stroke="none"/>
                    <circle cx="12" cy="15" r="1" fill="currentColor" stroke="none"/>
                    <circle cx="16" cy="15" r="1" fill="currentColor" stroke="none"/>
                </svg>
            </div>
            <div class="logo-name">Eventra</div>
            <div class="logo-tagline">Your gateway to amazing events</div>
        </div>

        <!-- Tab Toggle -->
        <div class="tab-toggle">
            <a href="{{ route('login') }}" class="tab-btn">Login</a>
            <a href="{{ route('register') }}" class="tab-btn active">Register</a>
        </div>

        <!-- Card -->
        <div class="card">

            <x-validation-errors class="alert-error" />

            <div class="card-title">Create an account</div>
            <div class="card-sub">Join Eventra and discover amazing events</div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="field">
                    <label for="name">Full Name</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        placeholder="John Doe"
                    />
                </div>

                <div class="field">
                    <label for="email">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        placeholder="you@example.com"
                    />
                </div>

                <div class="field">
                    <label for="password">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        placeholder="••••••••"
                    />
                </div>

                @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                    <div class="terms-row">
                        <input type="checkbox" id="terms" name="terms" required>
                        <label for="terms">
                            I agree to the
                            <a target="_blank" href="{{ route('terms.show') }}">Terms of Service</a>
                            and
                            <a target="_blank" href="{{ route('policy.show') }}">Privacy Policy</a>
                        </label>
                    </div>
                @endif

                <button type="submit" class="btn-submit">Create Account</button>
            </form>
        </div>

        <!-- Footer -->
        <div class="footer-link">
            Already have an account? <a href="{{ route('login') }}">Sign in here</a>
        </div>

    </div>
</body>
</html>