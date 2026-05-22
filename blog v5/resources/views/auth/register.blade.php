@extends('layouts.app')

@section('title', 'Rejestracja')

@push('styles')
<style>
    .auth-box {
        max-width: 420px;
        margin: 0 auto;
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 2.5rem;
    }

    .auth-box h1 { font-size: 1.75rem; margin-bottom: 0.5rem; }
    .auth-box > p { color: var(--muted); font-size: 0.9rem; margin-bottom: 2rem; }

    .form-group { margin-bottom: 1.25rem; }

    label {
        display: block;
        margin-bottom: 0.4rem;
        color: var(--muted);
        font-family: monospace;
        font-size: 0.85rem;
    }

    input[type="email"],
    input[type="password"],
    input[type="text"] {
        width: 100%;
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 0.65rem 0.9rem;
        color: var(--text);
        font-size: 0.95rem;
        transition: border-color 0.2s;
    }

    input:focus { outline: none; border-color: var(--accent); }
    input.valid { border-color: #4ade80; }
    input.invalid { border-color: #f87171; }

    .error {
        color: #f87171;
        font-size: 0.8rem;
        margin-top: 0.35rem;
        font-family: monospace;
    }

    .hint {
        color: var(--muted);
        font-size: 0.78rem;
        margin-top: 0.35rem;
        font-family: monospace;
    }

    .requirements {
        list-style: none;
        margin-top: 0.5rem;
    }

    .requirements li {
        font-size: 0.78rem;
        font-family: monospace;
        color: var(--muted);
        margin-bottom: 0.2rem;
    }

    .requirements li.ok { color: #4ade80; }
    .requirements li.ok::before { content: '✓ '; }
    .requirements li:not(.ok)::before { content: '✗ '; color: #f87171; }

    .btn-submit {
        width: 100%;
        padding: 0.75rem;
        background: var(--accent);
        color: #000;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        margin-top: 0.5rem;
        transition: opacity 0.2s;
    }

    .btn-submit:hover { opacity: 0.9; }

    .auth-footer {
        text-align: center;
        margin-top: 1.5rem;
        color: var(--muted);
        font-size: 0.85rem;
    }

    .auth-footer a { color: var(--accent); text-decoration: none; }
</style>
@endpush

@section('content')
    <div class="auth-box">
        <h1>Rejestracja</h1>
        <p>Utwórz konto żeby móc komentować.</p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-group">
                <label>Nazwa użytkownika</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                <p class="hint">Litery, cyfry, myślnik, podkreślnik. 3–30 znaków.</p>
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Hasło</label>
                <input type="password" name="password" id="password" required>
                <ul class="requirements" id="password-requirements">
                    <li id="req-length">minimum 8 znaków</li>
                    <li id="req-upper">wielka litera</li>
                    <li id="req-lower">mała litera</li>
                    <li id="req-digit">cyfra</li>
                </ul>
                @error('password') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Powtórz hasło</label>
                <input type="password" name="password_confirmation" id="password_confirmation" required>
            </div>

            <button type="submit" class="btn-submit">Zarejestruj się</button>
        </form>

        <div class="auth-footer">
            Masz już konto? <a href="{{ route('login') }}">Zaloguj się</a>
        </div>
    </div>

    <script>
        const passwordInput = document.getElementById('password');
        const reqs = {
            'req-length': v => v.length >= 8,
            'req-upper':  v => /[A-Z]/.test(v),
            'req-lower':  v => /[a-z]/.test(v),
            'req-digit':  v => /\d/.test(v),
        };

        passwordInput.addEventListener('input', function () {
            const val = this.value;
            for (const [id, test] of Object.entries(reqs)) {
                document.getElementById(id).classList.toggle('ok', test(val));
            }
        });
    </script>
@endsection
