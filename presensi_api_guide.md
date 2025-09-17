# Panduan API Presensi

## Deskripsi

API ini memungkinkan guru untuk mengelola presensi siswa.

## Endpoint

### 1. Mengambil Rombel dan Siswa

`GET /api/presensi/rombels`

Mengambil daftar rombel yang diajar oleh guru yang sedang login untuk periode saat ini, beserta data siswa di dalamnya.

#### Headers

- `Authorization: Bearer {access_token}`

#### Response (200)

```json
{
  "rombels": [
    {
      "id": 1,
      "tapel": "2024/2025",
      "kode": "1A",
      "label": "Kelas 1A",
      "tingkat": "1",
      "pararel": "A",
      "siswas": [
        {
          "id": "123456789",
          "nisn": "123456789",
          "nama": "Nama Siswa",
          "jk": "L"
        }
      ]
    }
  ]
}
```

### 2. Mengisi Presensi Siswa

`POST /api/presensi/store`

Menyimpan atau memperbarui presensi siswa.

#### Headers

- `Authorization: Bearer {access_token}`
- `Content-Type: application/json`

#### Body

```json
{
  "siswa_id": "123456789",
  "tapel": "2024/2025",
  "semester": "1",
  "rombel_id": "1A",
  "status": "h"
}
```

#### Parameter

- `siswa_id`: NISN siswa (string, required)
- `tapel`: Tahun pelajaran (string, required)
- `semester`: Semester (string, required)
- `rombel_id`: Kode rombel (string, required)
- `status`: Status presensi - h (hadir), s (sakit), i (izin), a (alpha) (string, required)

#### Response (201)

```json
{
  "message": "Presensi berhasil disimpan",
  "presensi": {
    "id": 1,
    "siswa_id": "123456789",
    "tapel": "2024/2025",
    "semester": "1",
    "rombel_id": "1A",
    "guru_id": "12345",
    "status": "h",
    "created_at": "2025-09-17T01:56:31.000000Z",
    "updated_at": "2025-09-17T01:56:31.000000Z"
  }
}
```

### 3. Mengambil Rekap Presensi per Tanggal

`GET /api/presensi/`

Mengambil rekap presensi per tanggal untuk rombel tertentu pada tapel dan semester tertentu.

#### Headers

- `Authorization: Bearer {access_token}`

#### Query Parameters

- `rombel_id`: Kode rombel (required)
- `tapel`: Tahun pelajaran (required)
- `semester`: Semester (required)

#### Response (200)

```json
{
  "rekap": [
    {
      "tanggal": "2025-09-17",
      "hadir": 25,
      "izin": 2,
      "sakit": 1,
      "alpa": 0
    },
    {
      "tanggal": "2025-09-16",
      "hadir": 24,
      "izin": 1,
      "sakit": 2,
      "alpa": 1
    }
  ]
}
```

## Middleware

Semua endpoint presensi menggunakan middleware `auth:api` dan `role:guru_kelas|guru_pabp|guru_pjok|guru_inggris`, sehingga hanya guru kelas/PABP/PJOK/Inggris yang dapat mengakses.

## Catatan

- Pastikan token JWT valid dan memiliki role "guru".
- Status presensi: h = hadir, s = sakit, i = izin, a = alpha.
