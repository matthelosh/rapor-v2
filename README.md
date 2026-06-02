# Sistem Informasi Sekolah

Aplikasi manajemen sekolah berbasis web untuk mengelola data akademik, nilai, rapor, buku induk, dan administrasi sekolah secara terpusat.

## 📋 Tentang Proyek

Sistem Informasi Sekolah (SIS) adalah platform terintegrasi yang dirancang untuk memudahkan proses administrasi dan akademik di sekolah. Sistem ini mendukung pengelolaan data siswa, guru, nilai, rapor, serta fitur-fitur pendukung lainnya dengan antarmuka yang modern dan user-friendly.

## ✨ Fitur Utama

### 🎓 Modul Akademik

- **Rapor Nilai Siswa**
  - Manajemen nilai PTS (Penilaian Tengah Semester)
  - Manajemen nilai PAS (Penilaian Akhir Semester)
  - Cetak rapor PDF (cover, biodata, nilai akademik, ekstrakurikuler)
  - Penyimpanan permanen data rapor
  - Manajemen tanggal penetapan rapor

- **Buku Induk Siswa**
  - Generate buku induk otomatis untuk semua siswa aktif
  - Generate buku induk individual
  - Export data ke format Excel
  - Kelola status siswa (aktif, lulus, pindah, keluar)

- **Nilai & Penilaian**
  - Input nilai per rombel dan mata pelajaran
  - Nilai kokurikuler (nilai karakter)
  - Nilai ekstrakurikuler
  - Data absensi siswa
  - Catatan akademik

- **Mata Pelajaran & Pembelajaran**
  - Manajemen elemen, CP (Capaian Pembelajaran), TP (Target Pembelajaran)
  - Import data dari sistem Dapodik
  - Manajemen ekstrakurikuler

### 👥 Modul Pengguna

- **Data Sekolah**
  - Manajemen informasi sekolah
  - Kelola kepala sekolah
  - Manajemen operator sekolah

- **Data Guru**
  - Manajemen profil guru
  - Penuguan guru ke rombel (wali kelas)
  - Import data guru dari Dapodik

- **Data Siswa**
  - Manajemen biodata siswa
  - Registrasi akun siswa
  - Keluar masuk antar rombel
  - Data orang tua/wali siswa

### 📊 Modul Asesmen

- **Manajemen Asesmen**
  - Buat dan kelola ujian
  - Upload soal dengan gambar
  - Manajemen kunci jawaban
  - Analisis hasil asesmen

- **Soal & Jawaban**
  - Manajemen bank soal
  - Import soal dari berkas
  - Editor teks rich text untuk soal

### 🏫 Modul Administrasi

- **Presensi Guru**
  - Rekap presensi mengajar
  - Export data presensi

- **Administrasi Akhir Jenjang**
  - Keluar masuk siswa
  - Verifikasi ijazah

- **Workshop & Sertifikat**
  - Manajemen kegiatan workshop
  - Generate dan verifikasi sertifikat dengan QR Code

### 🔧 Fitur Tambahan

- **Backup Database** - Backup otomatis dengan Spatie Laravel Backup
- **API Client** - REST API untuk integrasi eksternal
- **Verifikasi Rapor/Ijazah** - Verifikasi dokumen melalui QR Code
- **Ledger Nilai** - Rekap dan peringkat nilai siswa
- **P5 (Projek Penguatan Profil Pelajar Pancasila)**
  - Manajemen proyek P5
  - Input nilai P5

## 💻 Persyaratan Sistem

- **PHP** >= 8.2
- **Composer** >= 2.0
- **Node.js** >= 18.x
- **NPM** >= 9.x
- **Database**: MySQL 8.x / MariaDB / SQLite

## 🚀 Instalasi

### 1. Clone Repository

```bash
git clone <repository-url>
cd pkg
```

### 2. Install Dependensi PHP

```bash
composer install
```

### 3. Install Dependensi JavaScript

```bash
npm install
npm run dev
```

### 4. Konfigurasi Environment

Salin file `.env.example` ke `.env`:

```bash
cp .env.example .env
```

Edit file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=user_database
DB_PASSWORD=password_database
```

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Migrasi Database

```bash
php artisan migrate
```

### 7. Seeder Data Awal (Opsional)

```bash
php artisan db:seed
```

### 8. Jalankan Server Development

```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

## 🔧 Konfigurasi Tambahan

### Queue (Untuk Proses Background)

Untuk proses yang memerlukan queue (backup, import data):

```bash
php artisan queue:work
```

### Pusher (Notifikasi Real-time)

Edit konfigurasi di `.env`:

```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=mt1
```

### Storage (S3 untuk Backup)

```env
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=your_bucket
AWS_USE_PATH_STYLE_ENDPOINT=false
```

## 📁 Struktur Proyek

```
├── app/
│   ├── Http/Controllers/     # Controller aplikasi
│   ├── Models/               # Model Eloquent
│   ├── Services/             # Service class
│   └── Helpers/              # Helper functions
├── resources/
│   ├── js/                   # Frontend Vue 3
│   └── views/                # Blade templates
├── routes/
│   ├── web.php               # Web routes
│   └── auth.php              # Auth routes
└── database/
    ├── migrations/           # Database migrations
    └── seeders/              # Database seeders
```

## 🔐 Hak Akses

Sistem menggunakan role-based access control:

- **superadmin** - Akses penuh sistem
- **admin** - Manajemen sekolah dan pengguna
- **ops** (Operator Sekolah) - Administrasi sekolah
- **guru_kelas** - Wali kelas, mengelola nilai dan rapor
- **guru_agama/guru_pjok/guru_inggris** - Guru mata pelajaran
- **org** - Pengguna eksternal (organisasi)

## 📦 Paket Utama

- `laravel/fortify` - Autentikasi dan manajemen pengguna
- `laravel/sanctum` - API authentication
- `spatie/laravel-permission` - Role dan permission management
- `spatie/laravel-backup` - Backup database
- `inertiajs/inertia-laravel` - Frontend Vue dengan Inertia.js
- `barryvdh/laravel-dompdf` - Generate PDF
- `tymon/jwt-auth` - JWT authentication

## 🤝 Kontribusi

Silakan ajukan *pull request* atau laporkan *issue* di halaman repository ini.

## 📄 Lisensi

Proyek ini dilisensikan under [MIT License](LICENSE).