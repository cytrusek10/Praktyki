@extends('layouts.app')

@section('title', 'Logowanie')

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

    .auth-box h1 {
        font-size: 1.75rem;
        margin-bottom: 0.5rem;
    }

    .auth-box p {
        color: var(--muted);
        font-size: 0.9rem;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

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

    input:focus {
        outline: none;
        border-color: var(--accent);
    }

    .error {
        color: #f87171;
        font-size: 0.8rem;
        margin-top: 0.35rem;
        font-family: monospace;
    }

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

    .auth-footer a {
        color: var(--accent);
        text-decoration: none;
    }
</style>
@endpush

@section('content')
    <div class="auth-box">
        <h1>Zaloguj się</h1>
        <p>Tylko zalogowani mogą komentować.</p>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label>Hasło</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn-submit">Zaloguj</button>
        </form>

        <div class="auth-footer">
            Nie masz konta? <a href="{{ route('register') }}">Zarejestruj się</a>
        </div>
    </div>
@endsection
