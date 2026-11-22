# SiPakar Sapi (Laravel + Tailwind CSS)

Sistem Pakar Diagnosis Penyakit Hewan Ternak Sapi Berdasarkan Gejala Menggunakan Metode Certainty Factor Berbasis Web.

Folder ini berisi **file aplikasi saja** (tanpa `vendor/` dan `node_modules/`). Letakkan di atas proyek Laravel baru.

## Kebutuhan
PHP 8.2+, Composer, Node.js 18+, dan database (SQLite bawaan Laravel atau MySQL).

## Cara pasang

```bash
# 1. buat proyek Laravel 12 (sudah termasuk Vite + Tailwind CSS v4)
composer create-project laravel/laravel sipakar-sapi
cd sipakar-sapi

# 2. salin seluruh isi folder ini ke dalam proyek, timpa file yang sama
#    (routes/web.php dan database/seeders/DatabaseSeeder.php akan tertimpa)

# 3. atur .env
#    APP_NAME="SiPakar Sapi"
#    APP_LOCALE=id          <- agar tanggal tampil dalam bahasa Indonesia
#    DB_CONNECTION=sqlite   <- atau mysql (isi DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 4. buat tabel, isi basis pengetahuan, dan impor dataset CSV
php artisan migrate --seed
php artisan sipakar:import-dataset

# 5. jalankan
npm install
npm run dev          # terminal 1
php artisan serve    # terminal 2  -> http://localhost:8000
```

Untuk produksi: `npm run build`.

## Struktur penting

| File | Fungsi |
|---|---|
| `app/Services/CertaintyFactor.php` | Mesin inferensi CF (rumus ada di sini) |
| `app/Http/Controllers/DiagnosaController.php` | Beranda, proses diagnosis, hasil, riwayat |
| `app/Console/Commands/ImportDataset.php` | Impor CSV dan hitung frekuensi gejala |
| `database/migrations/*` | Tabel penyakit, gejala, aturan, diagnosa, kasus_dataset |
| `database/seeders/DatabaseSeeder.php` | 5 penyakit, 24 gejala, bobot CF pakar, dan saran penanganan |
| `resources/views/*` | Tampilan Blade + Tailwind |
| `resources/css/app.css` | Tema, gradasi, dan animasi |

## Database
- `penyakit`: nama, deskripsi, status, wajib lapor, dan saran penanganan (JSON)
- `gejala`: kode (sama dengan CSV), nama, kelompok, warna
- `aturan`: penyakit + gejala + `cf_pakar` + `frekuensi` (dari dataset CSV)
- `diagnosa`: riwayat diagnosis (masukan pengguna disimpan sebagai JSON)
- `kasus_dataset`: baris dataset CSV untuk sapi (11.254 kasus)

## Mengubah bobot CF pakar
Ubah nilai di `database/seeders/DatabaseSeeder.php`, lalu jalankan `php artisan db:seed`.
Bobot bawaan adalah **contoh awal** dan perlu divalidasi dokter hewan atau pakar.

## Foto gejala
Foto dibaca dari `public/images/gejala/{nama-gejala}.jpg`. Yang sudah ada: `painless-lumps.jpg`
(dari Lumpy Skin Images Dataset). Gejala tanpa foto menampilkan kotak gradasi "Foto belum tersedia".
Untuk menambah, taruh file dengan nama berikut:

```
blisters-on-mouth  blisters-on-tongue  blisters-on-gums  sores-on-mouth  sores-on-tongue  sores-on-gums
blisters-on-hooves  sores-on-hooves  lameness  difficulty-walking  crackling-sound
swelling-in-muscle  swelling-in-limb  swelling-in-neck  swelling-in-abdomen  swelling-in-extremities
shortness-of-breath  chest-discomfort  loss-of-appetite  depression  fatigue  sweats  chills
```

Galeri penyakit LSD memakai semua file `.jpg` di `public/images/lsd/`.
Periksa lisensi dan cantumkan sumber foto di skripsi.

## Catatan
Kode sudah diperiksa sintaksnya (PHP lint) dan CSS Tailwind sudah diuji kompilasi, tetapi aplikasi
belum dijalankan penuh di dalam proyek Laravel karena lingkungan penyusun tidak punya akses Composer.
Jika ada error saat `migrate`, `seed`, atau tampilan, kirim pesan error-nya agar diperbaiki.
