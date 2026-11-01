<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Laporan Karhutla</title>
</head>
<body>
    <h1>Rekap Laporan Karhutla</h1>

    <p>Halaman ini menampilkan ringkasan laporan berdasarkan area kejadian.</p>

    <hr>

    <h2>Filter Rekap</h2>

    <form action="{{ route('reports.recap') }}" method="GET">
        <label for="area_id">Pilih Area:</label>

        <select name="area_id" id="area_id">
            <option value="">Semua Area</option>

            @foreach ($areas as $area)
                <option value="{{ $area->id }}"
                    {{ request('area_id') == $area->id ? 'selected' : '' }}>
                    {{ $area->name }}
                </option>
            @endforeach
        </select>

        <button type="submit">Terapkan Filter</button>
        <a href="{{ route('reports.recap') }}">Reset</a>
    </form>

    <hr>

    <h2>Ringkasan Laporan</h2>

    <section>
        <div>
            <h3>Total Laporan</h3>
            <p>{{ $totalReports }}</p>
        </div>

        <div>
            <h3>Laporan Aktif</h3>
            <p>{{ $activeReports }}</p>
        </div>

        <div>
            <h3>Laporan Diteruskan</h3>
            <p>{{ $forwardedReports }}</p>
        </div>
    </section>

    <hr>

    <h2>Rekap Laporan per Area</h2>

    @if ($recaps->isEmpty())
        <p>Belum ada laporan pada area yang dipilih.</p>
    @else
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Area</th>
                    <th>Total Laporan</th>
                    <th>Laporan Aktif</th>
                    <th>Laporan Diteruskan</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($recaps as $index => $recap)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $recap->name }}</td>
                        <td>{{ $recap->total_reports }}</td>
                        <td>{{ $recap->active_reports }}</td>
                        <td>{{ $recap->forwarded_reports }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <hr>

    <a href="{{ route('reports.index') }}">Kembali ke Daftar Laporan</a>
    |
    <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
</body>
</html>