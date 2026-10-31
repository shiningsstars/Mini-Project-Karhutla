<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Karhutla</title>
</head>
<body>
    <h1>Dashboard Pelaporan Karhutla</h1>

    <p>
        Selamat datang, {{ Auth::user()->name }}!
    </p>

    <p>
        Role akun: {{ Auth::user()->role }}
    </p>

    <hr>

    <h2>Sistem Pelaporan dan Pemantauan Kebakaran Hutan dan Lahan</h2>

    <p>
        Platform ini menyediakan sarana bagi masyarakat untuk 
        melaporkan indikasi kebakaran hutan dan lahan serta 
        mendukung relawan dalam memantau dan menindaklanjuti laporan.
    </p>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>