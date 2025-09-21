# Presensi API Guide for Frontend Developers

This guide provides documentation for consuming the Presensi API endpoints using Axios and Vue.js.

## API Endpoints

### 1. Rekap Presensi Siswa (Monthly/Semester Recap)

**Endpoint:** `POST /api/presensi/rekap-presensi-siswa`

**Description:** Get student attendance recap per month and semester

**Filtering Logic:**
- **Jika parameter `bulan` diisi** → Data presensi per bulan tersebut
- **Jika parameter `bulan` tidak diisi** → Data presensi per semester (seluruh bulan dalam semester)
- **Parameter `tahun` opsional** → Jika tidak diisi, menggunakan tahun dari created_at

**Request Parameters:**
```javascript
{
  rombel_id: "required|string",     // Rombel kode
  tapel: "required|string",         // Tahun pelajaran (e.g., "2024/2025")
  semester: "required|string",      // Semester (e.g., "1" or "2")
  bulan: "optional|integer",        // Month (1-12) - jika tidak diisi = seluruh semester
  tahun: "optional|integer"         // Year (e.g., 2024) - opsional
}
```

**Usage Examples:**
```javascript
// Data per semester (seluruh bulan)
{
  rombel_id: "X IPA 1",
  tapel: "2024/2025",
  semester: "1"
  // bulan dan tahun tidak diisi
}

// Data per bulan tertentu
{
  rombel_id: "X IPA 1",
  tapel: "2024/2025",
  semester: "1",
  bulan: 9,  // September
  tahun: 2024
}
```

**Response Structure:**
```json
{
  "data": [
    {
      "nisn": "1234567890",
      "nama": "John Doe",
      "rombel": "X IPA 1",
      "hadir": 20,
      "sakit": 2,
      "izin": 1,
      "alpa": 0
    }
  ]
}
```

### 2. Rekap Bulan (Monthly Detail)

**Endpoint:** `POST /api/presensi/rekap-bulan`

**Description:** Get detailed monthly attendance breakdown

**Request Parameters:**
```javascript
{
  rombel_id: "required|string",     // Rombel kode
  tapel: "required|string",         // Tahun pelajaran
  semester: "required|string",      // Semester
  bulan: "required|integer",        // Month (1-12)
  tahun: "required|integer"         // Year
}
```

**Response Structure:**
```json
{
  "data": [
    {
      "nisn": "1234567890",
      "nama": "John Doe",
      "rombel": "X IPA 1",
      "daily_status": {
        "1": "h",
        "2": "h",
        "3": "s",
        "5": "i"
      },
      "jml_h": 20,
      "jml_s": 2,
      "jml_i": 1,
      "jml_a": 0
    }
  ]
}
```

## Vue.js Implementation Examples

### 1. API Service Setup

Create an API service file:

```javascript
// resources/js/services/api.js
import axios from 'axios';

const api = axios.create({
  baseURL: '/api',
  headers: {
    'Content-Type': 'application/json',
    'X-Requested-With': 'XMLHttpRequest'
  }
});

// Add CSRF token
api.interceptors.request.use(config => {
  config.headers['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').content;
  return config;
});

export default {
  // Rekap Presensi Siswa
  getRekapPresensiSiswa(params) {
    return api.post('/presensi/rekap-presensi-siswa', params);
  },

  // Rekap Bulan
  getRekapBulan(params) {
    return api.post('/presensi/rekap-bulan', params);
  }
}
```

### 2. Vue Component Example

```vue
<template>
  <div>
    <!-- Filter Form -->
    <div class="mb-4">
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label>Rombel</label>
          <select v-model="filters.rombel_id" class="w-full border rounded p-2">
            <option value="">Pilih Rombel</option>
            <option v-for="rombel in rombels" :key="rombel.kode" :value="rombel.kode">
              {{ rombel.label }}
            </option>
          </select>
        </div>
        <div>
          <label>Bulan</label>
          <select v-model="filters.bulan" class="w-full border rounded p-2">
            <option value="">Semua Bulan</option>
            <option v-for="n in 12" :key="n" :value="n">{{ $moment().month(n-1).format('MMMM') }}</option>
          </select>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4 mt-2">
        <div>
          <label>Tahun Pelajaran</label>
          <input v-model="filters.tapel" type="text" class="w-full border rounded p-2" placeholder="2024/2025">
        </div>
        <div>
          <label>Semester</label>
          <select v-model="filters.semester" class="w-full border rounded p-2">
            <option value="1">1</option>
            <option value="2">2</option>
          </select>
        </div>
      </div>
      <button @click="loadRekap" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">
        Tampilkan Rekap
      </button>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-4">
      <span>Loading...</span>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
      {{ error }}
    </div>

    <!-- Rekap Presensi Table -->
    <div v-if="rekapData.length > 0">
      <h3 class="text-lg font-semibold mb-2">Rekap Presensi Siswa</h3>
      <table class="w-full border-collapse border">
        <thead>
          <tr class="bg-gray-100">
            <th class="border p-2">NISN</th>
            <th class="border p-2">Nama</th>
            <th class="border p-2">Rombel</th>
            <th class="border p-2">Hadir</th>
            <th class="border p-2">Sakit</th>
            <th class="border p-2">Izin</th>
            <th class="border p-2">Alpa</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="siswa in rekapData" :key="siswa.nisn">
            <td class="border p-2">{{ siswa.nisn }}</td>
            <td class="border p-2">{{ siswa.nama }}</td>
            <td class="border p-2">{{ siswa.rombel }}</td>
            <td class="border p-2 text-center">{{ siswa.hadir }}</td>
            <td class="border p-2 text-center">{{ siswa.sakit }}</td>
            <td class="border p-2 text-center">{{ siswa.izin }}</td>
            <td class="border p-2 text-center">{{ siswa.alpa }}</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Monthly Detail Table -->
    <div v-if="detailData.length > 0" class="mt-6">
      <h3 class="text-lg font-semibold mb-2">Detail Harian Bulan {{ filters.bulan }}</h3>
      <table class="w-full border-collapse border">
        <thead>
          <tr class="bg-gray-100">
            <th class="border p-2">NISN</th>
            <th class="border p-2">Nama</th>
            <th class="border p-2">Rombel</th>
            <th v-for="day in getDaysInMonth()" :key="day" class="border p-2 text-center">
              {{ day }}
            </th>
            <th class="border p-2">H</th>
            <th class="border p-2">S</th>
            <th class="border p-2">I</th>
            <th class="border p-2">A</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="siswa in detailData" :key="siswa.nisn">
            <td class="border p-2">{{ siswa.nisn }}</td>
            <td class="border p-2">{{ siswa.nama }}</td>
            <td class="border p-2">{{ siswa.rombel }}</td>
            <td v-for="day in getDaysInMonth()" :key="day" class="border p-2 text-center">
              <span :class="getStatusClass(siswa.daily_status[day])">
                {{ getStatusText(siswa.daily_status[day]) }}
              </span>
            </td>
            <td class="border p-2 text-center">{{ siswa.jml_h }}</td>
            <td class="border p-2 text-center">{{ siswa.jml_s }}</td>
            <td class="border p-2 text-center">{{ siswa.jml_i }}</td>
            <td class="border p-2 text-center">{{ siswa.jml_a }}</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script>
import api from '@/services/api';

export default {
  data() {
    return {
      filters: {
        rombel_id: '',
        tapel: '2024/2025',
        semester: '1',
        bulan: '',
        tahun: new Date().getFullYear()
      },
      rombels: [],
      rekapData: [],
      detailData: [],
      loading: false,
      error: null
    };
  },
  mounted() {
    this.loadRombels();
  },
  methods: {
    async loadRombels() {
      try {
        const response = await api.get('/presensi/get-rombels');
        this.rombels = response.data.rombels;
      } catch (error) {
        console.error('Error loading rombels:', error);
      }
    },
    
    async loadRekap() {
      this.loading = true;
      this.error = null;
      
      try {
        // Load rekap presensi siswa
        const rekapResponse = await api.getRekapPresensiSiswa(this.filters);
        this.rekapData = rekapResponse.data.data;
        
        // Load detail if month is selected
        if (this.filters.bulan) {
          const detailResponse = await api.getRekapBulan({
            ...this.filters,
            tahun: this.filters.tahun
          });
          this.detailData = detailResponse.data.data;
        } else {
          this.detailData = [];
        }
      } catch (error) {
        this.error = error.response?.data?.error || 'Terjadi kesalahan saat memuat data';
        console.error('Error loading rekap:', error);
      } finally {
        this.loading = false;
      }
    },
    
    getDaysInMonth() {
      if (!this.filters.bulan) return [];
      const days = new Date(this.filters.tahun, this.filters.bulan, 0).getDate();
      return Array.from({length: days}, (_, i) => i + 1);
    },
    
    getStatusClass(status) {
      const classes = {
        'h': 'text-green-600 font-semibold',
        's': 'text-yellow-600',
        'i': 'text-blue-600',
        'a': 'text-red-600 font-semibold'
      };
      return classes[status] || '';
    },
    
    getStatusText(status) {
      const texts = {
        'h': 'H',
        's': 'S',
        'i': 'I',
        'a': 'A'
      };
      return texts[status] || '-';
    }
  }
};
</script>
```

## Error Handling

### Common Error Responses

```javascript
// Validation Error (422)
{
  "message": "The given data was invalid.",
  "errors": {
    "rombel_id": ["The rombel id field is required."]
  }
}

// Not Found Error (404)
{
  "error": "Rombel tidak ditemukan"
}

// Server Error (500)
{
  "error": "Error message details"
}
```

### Error Handling Example

```javascript
async loadRekap() {
  this.loading = true;
  this.error = null;
  
  try {
    const response = await api.getRekapPresensiSiswa(this.filters);
    this.rekapData = response.data.data;
  } catch (error) {
    if (error.response?.status === 422) {
      // Validation error
      const errors = error.response.data.errors;
      this.error = Object.values(errors).flat().join(', ');
    } else if (error.response?.status === 404) {
      // Not found
      this.error = error.response.data.error;
    } else {
      // Other errors
      this.error = 'Terjadi kesalahan saat memuat data';
    }
    console.error('Error:', error);
  } finally {
    this.loading = false;
  }
}
```

## Authentication

Make sure to include the CSRF token in your requests. The API service example above includes this automatically.

## Status Codes

- **H** (Hadir) - Present
- **S** (Sakit) - Sick
- **I** (Izin) - Permission
- **A** (Alpa) - Absent without permission

## Tips

1. Always validate user input before sending API requests
2. Implement proper loading states for better UX
3. Handle errors gracefully and show user-friendly messages
4. Use moment.js or similar library for date formatting
5. Consider implementing pagination for large datasets
6. Add refresh functionality to reload data
7. Implement export functionality for reports if needed
