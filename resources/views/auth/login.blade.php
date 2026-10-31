<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Karhutla</title>
</head>
<body>
    <h1>Login</h1>
    <p>Masuk ke sistem pelaporan dan pemantauan Karhutla.</p>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    <form action="{{ url('/login') }}" method="POST">
        @csrf

        <div>
            <label for="email">Email</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
            >
            @error('email')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password">Password</label>
            <input
                type="password"
                id="password"
                name="password"
                required
            >
            @error('password')
                <p>{{ $message }}</p>
            @enderror
        </div>

        <button type="submit">Login</button>
    </form>

    <p>
        Belum punya akun?
        <a href="{{ route('register') }}">Daftar di sini</a>
    </p>
</body>
</html>