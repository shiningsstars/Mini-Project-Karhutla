<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laporan Karhutla</title>
</head>
<body>
    <h1>Daftar Laporan Karhutla</h1>

    <p>Selamat datang, {{ Auth::user()->name }}.</p>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (Auth::user()->role === 'pelapor')
        <a href="{{ route('reports.create') }}">Buat Laporan</a>
    @endif

    <p>
        <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
    </p>

    @forelse ($reports as $report)
        <article>
            <h2>{{ $report->title }}</h2>

            <p>Area: {{ $report->area->name }}</p>
            <p>Waktu kejadian: {{ $report->incident_at->format('d-m-Y H:i') }}</p>
            <p>Status: {{ $report->status }}</p>

            @if (Auth::user()->role !== 'pelapor')
                <p>Pelapor: {{ $report->user->name }}</p>
            @endif

            <a href="{{ route('reports.show', $report) }}">
                Lihat Detail
            </a>
        </article>
        <hr>
    @empty
        <p>Belum ada laporan yang tersedia.</p>
    @endforelse

    {{ $reports->links() }}

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>