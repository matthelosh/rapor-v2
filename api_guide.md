# API Guide for PKG Wagir System

## Overview

API PKG Wagir System terbagi menjadi 3 kategori utama:

1. **Public API** - Untuk aplikasi eksternal (statistik, data dasar)
2. **Protected API** - Butuh login user 
3. **Bearer Token API** - Menggunakan bearer token sederhana

---

## 1. Public API

Public API digunakan untuk aplikasi eksternal yang membutuhkan akses ke data dasar tanpa perlu login user. Contoh: aplikasi sekolah.test untuk menampilkan statistik di halaman depan.

### Authentication

Public API menggunakan **Client Credentials** authentication:

```bash
Headers:
- X-CLIENT-ID: your_client_id
- X-CLIENT-SECRET: your_client_secret
```

### Available Endpoints

#### Base URL: `/api/public`

#### 1.1 Data Rombel
**GET** `/api/public/rombel`

Get data rombel untuk statistik

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "kode": "X IPA 1",
      "label": "X IPA 1",
      "tingkat": "X",
      "jurusan": "IPA"
    }
  ]
}
```

#### 1.2 Data TPs (Tujuan Pembelajaran)
**GET** `/api/public/tps`

Get data tujuan pembelajaran

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "kode": "3.1",
      "teks": "Menganalisis struktur sel"
    }
  ]
}
```

#### 1.3 Data Asesmen
**GET** `/api/public/asesmens`

Get data asesmen

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "nama": "Ulangan Harian 1",
      "jenis": "uh",
      "mapel_id": 1
    }
  ]
}
```

#### 1.4 Data Kalender Pendidikan
**GET** `/api/public/kaldik`

Get data kalender pendidikan

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "tanggal": "2024-07-01",
      "kegiatan": "Hari Pertama Masuk Sekolah",
      "jenis": "libur"
    }
  ]
}
```

#### 1.5 Data Sekolah
**GET** `/api/public/sekolah`

Get data sekolah

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "nama": "PKG Wagir",
      "npsn": "12345678",
      "alamat": "Jl. Contoh No. 123"
    }
  ]
}
```

**GET** `/api/public/sekolah/subdomain/{subdomain}`

Get data sekolah by subdomain

#### 1.6 Data Posts
**GET** `/api/public/posts`

Get data posts/berita

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "judul": "Pengumuman Libur",
      "konten": "...",
      "kategori_id": 1
    }
  ]
}
```

#### 1.7 Sync Data

##### 1.7.1 Sync Nilai PTS
**POST** `/api/public/sync/nilai/pts/store`

Sync data nilai PTS

**Request:**
```json
{
  "siswa_id": 1,
  "mapel_id": 1,
  "nilai": 85,
  "semester": "1",
  "tapel": "2024/2025"
}
```

##### 1.7.2 Sync Data Dapodik
**POST** `/api/public/sync/dapo/sekolah/sync`
**POST** `/api/public/sync/dapo/guru/sync`
**POST** `/api/public/sync/dapo/rombel/sync`
**POST** `/api/public/sync/dapo/siswa/sync`

Sync data dapodik dari aplikasi eksternal

---

## 2. Protected API

Protected API membutuhkan authentication dengan JWT token (user login).

### Authentication

Gunakan endpoint `/api/login` untuk mendapatkan token:

```bash
POST /api/login
Headers:
- Content-Type: application/json

Body:
{
  "email": "user@example.com",
  "password": "password"
}
```

**Response:**
```json
{
  "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
  "token_type": "bearer",
  "expires_in": 3600
}
```

Gunakan token di header untuk request selanjutnya:

```bash
Headers:
- Authorization: Bearer {access_token}
```

### Available Endpoints

#### 2.1 User Info
**GET** `/api/user`
**GET** `/api/me`

Get informasi user yang sedang login

#### 2.2 Data Mapel
**GET** `/api/mapels`

Get data mata pelajaran

#### 2.3 Kaih (Siswa Only)
**GET** `/api/kaih`
**POST** `/api/kaih/store`

Endpoint untuk manajemen Kaih (hanya role:siswa)

#### 2.4 Presensi (Guru Only)
**GET** `/api/presensi`
**POST** `/api/presensi/store`
**GET** `/api/presensi/rombels`
**GET** `/api/presensi/harian`
**POST** `/api/presensi/rekap-presensi-siswa`
**POST** `/api/presensi/rekap-bulan`

Endpoint untuk manajemen presensi (hanya role:guru_kelas|guru_agama|guru_pjok|guru_inggris)

#### 2.5 Jurnal Mengajar (Guru Only)
**GET** `/api/jurnal-mengajar`
**POST** `/api/jurnal-mengajar/store`
**GET** `/api/jurnal-mengajar/{id}`
**PUT** `/api/jurnal-mengajar/{id}`
**DELETE** `/api/jurnal-mengajar/{id}`

Endpoint untuk manajemen jurnal mengajar (hanya role:guru_kelas|guru_agama|guru_pjok|guru_inggris)

---

## 3. Bearer Token API

API yang menggunakan bearer token sederhana (bukan JWT).

### Authentication

```bash
Headers:
- Authorization: Bearer {bearer_token}
```

Bearer token disetting di environment variable `API_BEARER_TOKEN`.

### Available Endpoints

#### 3.1 Asesmen
**GET** `/api/asesmen`
**POST** `/api/asesmen/store`
**GET** `/api/asesmen/syncsekolah`
**GET** `/api/asesmen/periode`

Endpoint untuk manajemen asesmen dengan bearer token authentication.

---

## Setup API Client

### 1. Mendapatkan Client Credentials

Untuk menggunakan Public API, anda membutuhkan client credentials. Jalankan seeder:

```bash
php artisan db:seed --class=ApiClientSeeder
```

Atau jalankan full seeder:

```bash
php artisan db:seed
```

Seeder akan membuat 3 client:
- `sekolah_test_app` - untuk aplikasi sekolah.test
- `presensi_siswa_app` - untuk aplikasi presensi siswa
- `asesmen_app` - untuk aplikasi asesmen

### 2. Menggunakan API di JavaScript

#### Public API Example
```javascript
// Setup axios
const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    'X-CLIENT-ID': 'sekolah_test_app',
    'X-CLIENT-SECRET': 'your_client_secret_here'
  }
});

// Get data rombel
async function getRombel() {
  try {
    const response = await api.get('/public/rombel');
    return response.data.data;
  } catch (error) {
    console.error('Error:', error.response.data);
  }
}
```

#### Protected API Example
```javascript
// Login
async function login(email, password) {
  try {
    const response = await axios.post('/api/login', { email, password });
    localStorage.setItem('token', response.data.access_token);
    return response.data;
  } catch (error) {
    console.error('Login error:', error.response.data);
  }
}

// Setup authenticated API
const authApi = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    'Authorization': `Bearer ${localStorage.getItem('token')}`
  }
});

// Get user data
async function getUserData() {
  try {
    const response = await authApi.get('/user');
    return response.data;
  } catch (error) {
    console.error('Error:', error.response.data);
  }
}
```

---

## Error Handling

### Common Error Responses

#### 401 Unauthorized
```json
{
  "error": "Unauthorized",
  "message": "Invalid client credentials"
}
```

#### 403 Forbidden
```json
{
  "message": "User does not have the right roles"
}
```

#### 404 Not Found
```json
{
  "error": "Resource not found"
}
```

#### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

#### 500 Server Error
```json
{
  "error": "Internal server error"
}
```

---

## CORS Configuration

Pastikan domain aplikasi eksternal anda sudah ditambahkan di CORS configuration:

```bash
# .env
CORS_ALLOWED_ORIGINS='https://sekolah.test,https://your-app-domain.com'
```

---

## Best Practices

1. **Security**: Jangan pernah expose client secret di frontend
2. **Error Handling**: Selalu handle error dengan baik di aplikasi
3. **Rate Limiting**: Pertimbangkan untuk implement rate limiting di production
4. **HTTPS**: Selalu gunakan HTTPS di production
5. **Token Management**: Refresh token JWT sebelum expired
6. **Validation**: Validasi input data sebelum dikirim ke API
7. **Logging**: Log semua API request untuk debugging

---

## Testing API

Gunakan tools seperti Postman atau curl untuk testing API:

### Testing Public API
```bash
curl -X GET "http://localhost:8000/api/public/rombel" \
  -H "X-CLIENT-ID: sekolah_test_app" \
  -H "X-CLIENT-SECRET: your_client_secret_here" \
  -H "Content-Type: application/json"
```

### Testing Protected API
```bash
# Login dulu
curl -X POST "http://localhost:8000/api/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Gunakan token untuk request
curl -X GET "http://localhost:8000/api/user" \
  -H "Authorization: Bearer your_jwt_token_here" \
  -H "Content-Type: application/json"
```

---

## Troubleshooting

### Common Issues

1. **401 Unauthorized on Public API**
   - Check X-CLIENT-ID and X-CLIENT-SECRET headers
   - Verify client credentials in database
   - Run ApiClientSeeder if no clients exist

2. **401 Unauthorized on Protected API**
   - Check JWT token in Authorization header
   - Verify token is not expired
   - Login again to get fresh token

3. **403 Forbidden**
   - Check user roles and permissions
   - Verify endpoint requires specific roles

4. **CORS Issues**
   - Check CORS_ALLOWED_ORIGINS in .env
   - Verify domain is properly whitelisted

5. **404 Not Found**
   - Check endpoint URL
   - Verify HTTP method (GET/POST/PUT/DELETE)

### Debug Commands

```bash
# Check registered routes
php artisan route:list

# Check middleware
php artisan make:middleware DebugMiddleware

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## Changelog

### v2.0.0 (Current)
- Restructured API into Public, Protected, and Bearer Token categories
- Implemented Client Credentials authentication for Public API
- Added ApiClient model and seeder
- Improved middleware for better security
- Updated route organization with clear prefixes

### v1.0.0 (Previous)
- Basic API structure
- Mixed authentication methods
- Inconsistent route organization
