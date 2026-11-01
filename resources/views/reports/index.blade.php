<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        form {
            display: flex;
            flex-direction: column;
            max-width: 400px;
            gap: 6px;
        }

        form div {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
    </style>
    <title>Daftar Laporan Karhutla</title>
</head>

<body>
    <h2>Pencarian dan Filter Laporan</h2>

    <form action="{{ route('reports.index') }}" method="GET">
        <div>
            <label for="search">Cari laporan</label>
            <input
                type="text"
                id="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Masukkan judul, alamat, atau deskripsi"
            >
        </div>

        <div>
            <label for="area_id">Area</label>
            <select name="area_id" id="area_id">
                <option value="">Semua Area</option>
                @foreach ($areas as $area)
                    <option value="{{ $area->id }}"
                        {{ request('area_id') == $area->id ? 'selected' : '' }}>
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="status">Status</label>
            <select name="status" id="status">
                <option value="">Semua Status</option>
                <option value="submitted" {{ request('status') === 'submitted' ? 'selected' : '' }}>Menunggu Review</option>
                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Sudah Direview</option>
                <option value="needs_attention" {{ request('status') === 'needs_attention' ? 'selected' : '' }}>Perlu Perhatian</option>
                <option value="forwarded" {{ request('status') === 'forwarded' ? 'selected' : '' }}>Sudah Diteruskan</option>
            </select>
        </div>

        <button type="submit">Terapkan Filter</button>
        <a href="{{ route('reports.index') }}">Reset</a>
    </form>

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