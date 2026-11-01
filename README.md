# Sistem Pelaporan dan Pemantauan Kebakaran Hutan dan Lahan (Karhutla)

Aplikasi web sederhana untuk membantu masyarakat melaporkan indikasi kebakaran hutan dan lahan (Karhutla), membantu relawan meninjau laporan, serta membantu koordinator mencatat penerusan informasi kepada pihak terkait.

## Identitas

| Keterangan | Isi |
|---|---|
| Nama | **Hasya Nadana Hakim** |
| NIM | **251511040** |
| Kelas | **D3 - 2B** |
| Kasus | **Kasus B: Karhutla** |
| Repository GitHub | **https://github.com/shiningsstars/Mini-Project-Karhutla.git** |

## Fitur Aplikasi

Aplikasi membedakan tiga fungsi pengguna:

### 1. Pelapor
- Membuat laporan indikasi Karhutla.
- Mengisi informasi area, koordinat, alamat, waktu kejadian, kondisi yang terlihat, dan tingkat kondisi.
- Mengunggah satu bukti pendukung (opsional).
- Melihat daftar dan perkembangan laporan miliknya.
- Mengubah laporan selama statusnya masih `submitted`.

### 2. Relawan Pemantau
- Melihat laporan yang masuk.
- Meninjau kelengkapan dan kewajaran informasi.
- Memberikan catatan hasil peninjauan.
- Menentukan hasil peninjauan berupa informasi valid atau perlu perhatian lebih lanjut.

### 3. Koordinator Relawan
- Melihat rekap laporan.
- Memfilter rekap berdasarkan area.
- Melihat jumlah seluruh laporan, laporan aktif, dan laporan yang telah diteruskan.
- Mencatat penerusan informasi kepada pihak terkait untuk laporan yang memerlukan perhatian lebih lanjut.

## Struktur Data

Data utama aplikasi terdiri dari:
- `users`: pengguna dan role.
- `areas`: wilayah kejadian.
- `reports`: laporan indikasi Karhutla.
- `report_reviews`: catatan peninjauan laporan.
- `report_follow_ups`: catatan penerusan informasi.
- `report_status_histories`: pencatatan perubahan status laporan oleh Observer.

## Status Laporan

Alur status yang digunakan dalam aplikasi:

| Status | Keterangan |
|---|---|
| `submitted` | Laporan baru dikirim dan menunggu peninjauan. |
| `reviewed` | Laporan telah ditinjau dan informasinya dinilai valid oleh relawan. |
| `needs_attention` | Laporan memerlukan perhatian atau tindak lanjut lebih lanjut. |
| `forwarded` | Informasi laporan telah dicatat sebagai diteruskan kepada pihak terkait. |

## Business Rule

Aturan proses yang diterapkan:

1. Pelapor hanya dapat melihat dan mengubah laporan miliknya sendiri.
2. Pelapor hanya dapat mengubah laporan ketika statusnya masih `submitted`.
3. Relawan hanya dapat meninjau laporan yang masih berstatus `submitted`.
4. Koordinator hanya dapat mencatat penerusan untuk laporan berstatus `needs_attention`.
5. Satu laporan hanya dapat memiliki satu catatan penerusan melalui alur aplikasi.

## Teknologi dan Kebutuhan Environment

Aplikasi dikembangkan menggunakan:
- PHP.
- Laravel.
- MySQL.
- Composer.
- GitHub.
- Blade.
- XAMPP dapat digunakan untuk menjalankan MySQL secara lokal.

Pastikan PHP, Composer, dan MySQL sudah terpasang dan dapat diakses melalui terminal.

## Instalasi

### 1. Clone repository

Ganti alamat repository di bawah dengan URL repository GitHub yang digunakan.

```bash
git clone https://github.com/shiningsstars/Mini-Project-Karhutla.git
```

### 2. Install dependency

```bash
composer install
```

### 3. Buat file environment

Untuk Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Untuk CMD:

```cmd
copy .env.example .env
```

### 4. Buat application key

```bash
php artisan key:generate
```

## Konfigurasi Database

Buat database dengan nama:

```sql
CREATE DATABASE db_karhutla;
```

Kemudian sesuaikan konfigurasi database pada file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_karhutla
DB_USERNAME=root
DB_PASSWORD=
```

Setelah mengubah `.env`, bersihkan cache konfigurasi:

```bash
php artisan config:clear
```

## Migration dan Seeding

Jalankan migration untuk membuat tabel:

```bash
php artisan migrate
```

Jalankan seeder untuk membuat akun demo dan data area:

```bash
php artisan db:seed
```

## Akun Demo

Akun berikut disediakan oleh `UserSeeder`:

| Role | Nama | Email | Password |
|---|---|---|---|
| Pelapor | Andi | `andi@karhutla.test` | `password123` |
| Relawan | Sari | `sari@karhutla.test` | `password123` |
| Koordinator | Budi | `budi@karhutla.test` | `password123` |

## Menjalankan Aplikasi

Pastikan MySQL sudah berjalan, kemudian jalankan:

```bash
php artisan serve
```

Buka alamat berikut di browser:

```text
http://127.0.0.1:8000
```

Login menggunakan salah satu akun demo sesuai role untuk mencoba fungsi aplikasi.