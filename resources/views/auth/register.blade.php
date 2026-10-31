<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Karhutla</title>
</head>
<body>
    <h1>Registrasi Akun</h1>
    <p>Buat akun untuk melaporkan kejadian kebakaran hutan dan lahan.</p>

    <form action="{{ url('/register') }}" method="POST">
        @csrf

        <div>
            <label for="name">Nama</label>
            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >
            @error('name')
                <p>{{ $message }}</p>
            @enderror
        </div>

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

        <div>
            <label for="password_confirmation">Konfirmasi Password</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
            >
        </div>

        <button type="submit">Daftar</button>
    </form>

    <p>
        Sudah punya akun?
        <a href="{{ route('login') }}">Login di sini</a>
    </p>
</body>
</html>