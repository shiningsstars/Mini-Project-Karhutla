<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - Karhutla</title>
</head>
<body>
    <h1>Detail Laporan</h1>

    <h2>{{ $report->title }}</h2>

    <p>Pelapor: {{ $report->user->name }}</p>
    <p>Area: {{ $report->area->name }}</p>
    <p>Kondisi: {{ $report->description }}</p>
    <p>Waktu kejadian: {{ $report->incident_at->format('d-m-Y H:i') }}</p>
    <p>Alamat: {{ $report->address }}</p>
    <p>Latitude: {{ $report->latitude }}</p>
    <p>Longitude: {{ $report->longitude }}</p>

    <p>
        Tingkat kondisi:
        {{ [
            'low' => 'Rendah',
            'medium' => 'Sedang',
            'high' => 'Tinggi',
        ][$report->condition_level] ?? $report->condition_level }}
    </p>

    <p>Status: {{ $report->status }}</p>

    @if ($report->evidence_path)
        <p>
            <a
                href="{{ asset('storage/' . $report->evidence_path) }}"
                target="_blank"
            >
                Lihat Bukti Laporan
            </a>
        </p>
    @endif

    <h3>Catatan Review</h3>

    @forelse ($report->reviews as $review)
        <article>
            <p>Relawan: {{ $review->reviewer->name }}</p>
            <p>Hasil: {{ $review->review_result }}</p>
            <p>Catatan: {{ $review->notes }}</p>
        </article>
    @empty
        <p>Belum ada catatan review.</p>
    @endforelse

    <h3>Informasi Tindak Lanjut</h3>

    @forelse ($report->followUps as $followUp)
        <article>
            <p>Koordinator: {{ $followUp->coordinator->name }}</p>
            <p>Pihak tujuan: {{ $followUp->destination }}</p>
            <p>Catatan: {{ $followUp->notes }}</p>
            <p>Waktu penerusan: {{ $followUp->forwarded_at->format('d-m-Y H:i') }}</p>
        </article>
    @empty
        <p>Belum ada informasi tindak lanjut.</p>
    @endforelse

    <a href="{{ route('reports.index') }}">Kembali ke Daftar Laporan</a>
</body>
</html>