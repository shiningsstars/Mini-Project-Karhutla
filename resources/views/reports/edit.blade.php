<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan - Karhutla</title>
</head>
<body>
    <h1>Edit Laporan</h1>

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

    <form
        action="{{ route('reports.update', $report) }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div>
            <label for="title">Judul Laporan</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title', $report->title) }}"
                required
            >
        </div>

        <div>
            <label for="area_id">Area Kejadian</label>

            <select id="area_id" name="area_id" required>
                <option value="">Pilih Area</option>

                @foreach ($areas as $area)
                    <option
                        value="{{ $area->id }}"
                        {{ old('area_id', $report->area_id) == $area->id ? 'selected' : '' }}
                    >
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>

            <p id="area-info"></p>
        </div>

        <div>
            <label for="description">Kondisi yang Terlihat</label>
            <textarea
                id="description"
                name="description"
                rows="5"
                required
            >{{ old('description', $report->description) }}</textarea>
        </div>

        <div>
            <label for="incident_at">Waktu Kejadian</label>
            <input
                type="datetime-local"
                id="incident_at"
                name="incident_at"
                value="{{ old('incident_at', $report->incident_at->format('Y-m-d\TH:i')) }}"
                required
            >
        </div>

        <div>
            <label for="address">Alamat atau Keterangan Lokasi</label>
            <input
                type="text"
                id="address"
                name="address"
                value="{{ old('address', $report->address) }}"
                required
            >
        </div>

        <div>
            <label for="latitude">Latitude</label>
            <input
                type="number"
                step="any"
                id="latitude"
                name="latitude"
                value="{{ old('latitude', $report->latitude) }}"
                min="-90"
                max="90"
                required
            >
        </div>

        <div>
            <label for="longitude">Longitude</label>
            <input
                type="number"
                step="any"
                id="longitude"
                name="longitude"
                value="{{ old('longitude', $report->longitude) }}"
                min="-180"
                max="180"
                required
            >
        </div>

        <div>
            <label for="condition_level">Tingkat Kondisi</label>

            <select
                id="condition_level"
                name="condition_level"
                required
            >
                <option value="low"
                    {{ old('condition_level', $report->condition_level) === 'low' ? 'selected' : '' }}>
                    Rendah
                </option>

                <option value="medium"
                    {{ old('condition_level', $report->condition_level) === 'medium' ? 'selected' : '' }}>
                    Sedang
                </option>

                <option value="high"
                    {{ old('condition_level', $report->condition_level) === 'high' ? 'selected' : '' }}>
                    Tinggi
                </option>
            </select>
        </div>

        <hr>

        <h3>Bukti Pendukung</h3>

        @if ($report->evidence_path)
            <p>Bukti yang saat ini tersimpan:</p>

            <a
                href="{{ asset('storage/' . $report->evidence_path) }}"
                target="_blank"
                rel="noopener noreferrer"
            >
                Lihat Bukti Lama
            </a>
        @else
            <p>Belum ada bukti pendukung.</p>
        @endif

        <div>
            <label for="evidence">Unggah Bukti Baru (Opsional)</label>

            <input
                type="file"
                id="evidence"
                name="evidence"
                accept=".jpg,.jpeg,.png,.pdf"
            >

            <p>Format JPG, JPEG, PNG, atau PDF. Maksimal 2 MB.</p>
            <p>Kosongkan jika tidak ingin mengganti bukti lama.</p>
        </div>

        <hr>

        <button type="submit">Simpan Perubahan</button>

        <a href="{{ route('reports.show', $report) }}">
            Batal
        </a>
    </form>

    <script>
        const areaSelect = document.getElementById('area_id');
        const areaInfo = document.getElementById('area-info');

        async function loadAreaInfo(areaId) {
            if (!areaId) {
                areaInfo.textContent = '';
                return;
            }

            try {
                const response = await fetch(`/areas/${areaId}`);

                if (!response.ok) {
                    throw new Error('Gagal mengambil informasi area.');
                }

                const area = await response.json();

                areaInfo.textContent = area.description || 'Tidak ada keterangan area.';
            } catch (error) {
                areaInfo.textContent = 'Informasi area tidak dapat dimuat.';
            }
        }

        areaSelect.addEventListener('change', function () {
            loadAreaInfo(this.value);
        });

        if (areaSelect.value) {
            loadAreaInfo(areaSelect.value);
        }
    </script>
</body>
</html>