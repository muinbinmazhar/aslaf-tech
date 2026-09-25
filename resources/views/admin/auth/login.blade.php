<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#243456">

    <title>Admin Login | ASLAF TECH</title>

    <link rel="stylesheet" href="{{ asset('css/fonts.css') }}?v={{ filemtime(public_path('css/fonts.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}?v={{ filemtime(public_path('css/theme.css')) }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            background: var(--background);
            color: var(--text);
            font-family: var(--ui-font);
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 36px;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 16px 45px rgba(var(--brand-navy-rgb), .08);
        }

        .brand {
            margin: 0 0 8px;
            color: var(--brand-navy);
            font-family: var(--ui-font);
            font-size: 26px;
            font-weight: 800;
            text-align: center;
        }

        .subtitle {
            margin: 0 0 30px;
            color: var(--muted);
            font-size: 16px;
            text-align: center;
        }

        .session-notice {
            margin: 0 0 20px;
            padding: 11px 13px;
            border: 1px solid var(--border-color);
            border-radius: 9px;
            background: var(--brand-navy-soft);
            color: var(--brand-navy-dark);
            font-size: 14px;
        }

        .field {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 9px;
            background: var(--surface);
            color: var(--text);
            font: inherit;
            outline: none;
        }

        input:focus {
            border-color: var(--brand-navy);
            box-shadow: 0 0 0 3px rgba(var(--brand-navy-rgb), .12);
        }

        .password-field {
            position: relative;
        }

        .password-field input {
            padding-right: 58px;
        }

        .password-toggle {
            position: absolute;
            right: 4px;
            top: 50%;
            transform: translateY(-50%);
            display: grid;
            place-items: center;
            width: 44px;
            height: 44px;
            padding: 0;
            border: 0;
            border-radius: 6px;
            background: transparent;
            color: var(--muted);
            cursor: pointer;
        }

        .password-toggle:hover {
            color: var(--brand-navy);
        }

        .password-toggle:focus-visible {
            outline: 2px solid var(--brand-navy);
            outline-offset: 1px;
        }

        .password-toggle [hidden] {
            display: none;
        }

        .remember-label {
            display: flex;
            align-items: flex-start;
            gap: 9px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 400;
            cursor: pointer;
        }

        .remember-label input {
            width: 16px;
            height: 16px;
            margin: 3px 0 0;
            padding: 0;
            flex-shrink: 0;
            accent-color: var(--brand-navy);
        }

        .error {
            margin-top: 7px;
            color: #B91C1C;
            font-size: 14px;
        }

        .login-button {
            width: 100%;
            padding: 13px;
            border: 0;
            border-radius: 9px;
            background: var(--brand-navy);
            color: var(--surface);
            font-family: var(--ui-font);
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        :focus-visible {
            outline: 3px solid var(--focus-navy);
            outline-offset: 3px;
        }

        .login-button:hover {
            background: var(--brand-navy-dark);
        }
    </style>
</head>

<body>
    <main class="login-card">
        <h1 class="brand">ASLAF TECH</h1>

        <p class="subtitle">Admin Panel-এ প্রবেশ করুন</p>

        @if(request()->boolean('expired'))
            <p class="session-notice" role="status">Your session expired. Please sign in again.</p>
        @endif

        <form method="POST" action="{{ route('admin.login.store') }}">
            @csrf

            <div class="field">
                <label for="email">Email</label>

                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    autocomplete="username"
                    required
                    autofocus
                >

                @error('email')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <div class="field">
                <label for="password">Password</label>

                <div class="password-field">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        autocomplete="current-password"
                        required
                    >
                    <button class="password-toggle" id="password-toggle" type="button"
                        aria-label="পাসওয়ার্ড দেখান" aria-controls="password" aria-pressed="false">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" />
                            <circle cx="12" cy="12" r="3" />
                            <path id="password-eye-slash" d="m3 3 18 18" hidden />
                        </svg>
                    </button>
                </div>

                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="remember" value="0">
            <label class="remember-label" for="remember">
                <input id="remember" type="checkbox" name="remember" value="1" @checked(old('remember', '1') === '1')>
                <span>এই ডিভাইসে ৩৬৫ দিন লগইন রাখুন</span>
            </label>

            <button class="login-button" type="submit">
                Login
            </button>
        </form>
    </main>
    <script>
        const passwordInput = document.getElementById('password');
        const passwordToggle = document.getElementById('password-toggle');
        const passwordEyeSlash = document.getElementById('password-eye-slash');

        passwordToggle.addEventListener('click', () => {
            const showPassword = passwordInput.type === 'password';

            passwordInput.type = showPassword ? 'text' : 'password';
            passwordToggle.setAttribute('aria-pressed', String(showPassword));
            passwordToggle.setAttribute('aria-label', showPassword ? 'পাসওয়ার্ড লুকান' : 'পাসওয়ার্ড দেখান');
            passwordEyeSlash.toggleAttribute('hidden', !showPassword);
        });
    </script>
</body>
</html>
