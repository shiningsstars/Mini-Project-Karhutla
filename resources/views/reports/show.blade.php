<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - Karhutla</title>
</head>
<body>
    <h1>Detail Laporan</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if ($errors->any())
        <div>
            <p>Terjadi kesalahan:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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

    @if (
        Auth::user()->role === 'pelapor'
        && $report->user_id === Auth::id()
        && $report->status === 'submitted'
    )
        <p>
            <a href="{{ route('reports.edit', $report) }}">
                Edit Laporan
            </a>
        </p>
    @endif

    @if ($report->evidence_path)
        <p>
            <a
                href="{{ asset('storage/' . $report->evidence_path) }}"
                target="_blank"
                rel="noopener noreferrer"
            >
                Lihat Bukti Laporan
            </a>
        </p>
    @endif

    <hr>

    <h3>Catatan Review</h3>

    @forelse ($report->reviews as $review)
        <article>
            <p>Relawan: {{ $review->reviewer->name }}</p>
            <p>
                Hasil:
                {{ $review->review_result === 'valid'
                    ? 'Informasi Valid'
                    : 'Perlu Perhatian Lebih Lanjut' }}
            </p>
            <p>Catatan: {{ $review->notes }}</p>
        </article>
    @empty
        <p>Belum ada catatan review.</p>
    @endforelse

    @if (Auth::user()->role === 'relawan' && $report->status === 'submitted')
        <hr>

        <h2>Peninjauan Laporan</h2>

        <form action="{{ route('reports.review.store', $report) }}" method="POST">
            @csrf

            <div>
                <label for="notes">Catatan Peninjauan</label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="5"
                    required
                >{{ old('notes') }}</textarea>
            </div>

            <div>
                <label for="review_result">Hasil Peninjauan</label>

                <select id="review_result" name="review_result" required>
                    <option value="">Pilih hasil peninjauan</option>

                    <option value="valid" {{ old('review_result') === 'valid' ? 'selected' : '' }}>
                        Informasi Valid
                    </option>

                    <option value="needs_attention" {{ old('review_result') === 'needs_attention' ? 'selected' : '' }}>
                        Perlu Perhatian Lebih Lanjut
                    </option>
                </select>
            </div>

            <button type="submit">Simpan Peninjauan</button>
        </form>
    @endif

    <hr>

    <h3>Informasi Tindak Lanjut</h3>

    @forelse ($report->followUps as $followUp)
        <article>
            <p>Koordinator: {{ $followUp->coordinator->name }}</p>
            <p>Pihak tujuan: {{ $followUp->destination }}</p>
            <p>Catatan: {{ $followUp->notes ?? '-' }}</p>
            <p>
                Waktu penerusan:
                {{ $followUp->forwarded_at->format('d-m-Y H:i') }}
            </p>
        </article>
    @empty
        <p>Belum ada informasi tindak lanjut.</p>
    @endforelse

    @if (Auth::user()->role === 'koordinator'
        && $report->status === 'needs_attention'
        && $report->followUps->isEmpty())

        <hr>

        <h2>Pencatatan Penerusan Informasi</h2>

        <form action="{{ route('reports.follow-up.store', $report) }}" method="POST">
            @csrf

            <div>
                <label for="destination">Informasi Diteruskan Kepada</label>
                <input
                    type="text"
                    id="destination"
                    name="destination"
                    value="{{ old('destination') }}"
                    placeholder="Contoh: Pihak pengelola kawasan"
                    required
                >
            </div>

            <div>
                <label for="notes">Keterangan</label>
                <textarea
                    id="notes"
                    name="notes"
                    rows="4"
                >{{ old('notes') }}</textarea>
            </div>

            <div>
                <label for="forwarded_at">Waktu Penerusan</label>
                <input
                    type="datetime-local"
                    id="forwarded_at"
                    name="forwarded_at"
                    value="{{ old('forwarded_at', now()->format('Y-m-d\TH:i')) }}"
                    required
                >
            </div>

            <button type="submit">Simpan Penerusan</button>
        </form>
    @endif

    <hr>

    <a href="{{ route('reports.index') }}">Kembali ke Daftar Laporan</a>
</body>
</html>