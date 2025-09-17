# Panduan Login API

## Endpoint

`POST /api/login`

## Deskripsi

Endpoint ini digunakan untuk autentikasi pengguna (khusus siswa/ortu dan guru kelas/PABP/PJOK/Inggris) dan mendapatkan access token JWT.

## Request

### Headers

- `Content-Type: application/json`

### Body

```json
{
  "name": "username",
  "password": "password"
}
```

### Parameter

- `name`: Nama pengguna (string, required)
- `password`: Kata sandi (string, required)

## Response

### Success (200)

```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600,
  "user": {
    "id": 1,
    "name": "username",
    "email": "user@example.com",
    "role": "siswa"
  }
}
```

### Error (401)

```json
{
  "success": false,
  "message": "Username atau password tidak sesuai."
}
```

### Error (403)

```json
{
  "success": false,
  "message": "Maaf, Laman ini khusus Siswa/Ortu dan Guru Kelas/PABP/PJOK/Inggris."
}
```

## Penggunaan Token

Untuk request selanjutnya yang memerlukan autentikasi, sertakan token di header `Authorization`:

```
Authorization: Bearer {access_token}
```

Contoh:

```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...
```

## Endpoint yang Dilindungi

- `GET /api/me` (menggunakan middleware `auth:api`)
- `GET /api/kaih/*` (menggunakan middleware `auth:api` dan `role:siswa`)
- `GET /api/presensi/*` (menggunakan middleware `auth:api` dan `role:guru`)
- `GET /api/asesmen/*` (menggunakan middleware `auth.bearer`)

## Contoh Penggunaan dengan cURL

```bash
curl -X POST http://your-domain.com/api/login \
  -H "Content-Type: application/json" \
  -d '{"name": "siswa123", "password": "password123"}'
```

## Catatan

- API ini hanya untuk pengguna dengan role "siswa".
- Token JWT memiliki waktu kadaluarsa sesuai konfigurasi (`jwt.ttl` dalam detik).
