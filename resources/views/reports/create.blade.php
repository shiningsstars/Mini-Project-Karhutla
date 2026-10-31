<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan Karhutla</title>
</head>
<body>
    <h1>Buat Laporan Karhutla</h1>

    <p>Isi informasi berdasarkan kondisi yang kamu lihat.</p>

    @if ($errors->any())
        <div>
            <p>Periksa kembali data yang kamu masukkan:</p>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('reports.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div>
            <label for="title">Judul Laporan</label>
            <input
                type="text"
                id="title"
                name="title"
                value="{{ old('title') }}"
                required
            >
        </div>

        <div>
            <label for="area_id">Area/Wilayah</label>
            <select id="area_id" name="area_id" required>
                <option value="">Pilih area</option>
                @foreach ($areas as $area)
                    <option
                        value="{{ $area->id }}"
                        {{ old('area_id') == $area->id ? 'selected' : '' }}
                    >
                        {{ $area->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="description">Kondisi yang Terlihat</label>
            <textarea
                id="description"
                name="description"
                required
            >{{ old('description') }}</textarea>
        </div>

        <div>
            <label for="incident_at">Waktu Kejadian</label>
            <input
                type="datetime-local"
                id="incident_at"
                name="incident_at"
                value="{{ old('incident_at') }}"
                required
            >
        </div>

        <div>
            <label for="address">Alamat/Keterangan Lokasi</label>
            <textarea
                id="address"
                name="address"
                required
            >{{ old('address') }}</textarea>
        </div>

        <div>
            <label for="latitude">Latitude</label>
            <input
                type="number"
                id="latitude"
                name="latitude"
                step="any"
                min="-90"
                max="90"
                value="{{ old('latitude') }}"
                required
            >
        </div>

        <div>
            <label for="longitude">Longitude</label>
            <input
                type="number"
                id="longitude"
                name="longitude"
                step="any"
                min="-180"
                max="180"
                value="{{ old('longitude') }}"
                required
            >
        </div>

        <div>
            <label for="condition_level">Tingkat Kondisi</label>
            <select id="condition_level" name="condition_level" required>
                <option value="">Pilih tingkat kondisi</option>
                <option value="low" {{ old('condition_level') == 'low' ? 'selected' : '' }}>
                    Rendah
                </option>
                <option value="medium" {{ old('condition_level') == 'medium' ? 'selected' : '' }}>
                    Sedang
                </option>
                <option value="high" {{ old('condition_level') == 'high' ? 'selected' : '' }}>
                    Tinggi
                </option>
            </select>
        </div>

        <div>
            <label for="evidence">Foto Bukti (JPG, JPEG, PNG, atau PDF; maksimal 2 MB)</label>
            <input
                type="file"
                id="evidence"
                name="evidence"
                accept=".jpg,.jpeg,.png,.pdf"
                required
            >
        </div>

        <button type="submit">Kirim Laporan</button>
        <a href="{{ route('reports.index') }}">Batal</a>
    </form>
</body>
</html>