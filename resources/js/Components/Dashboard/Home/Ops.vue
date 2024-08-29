<script setup>
import { ref, computed } from 'vue'
import { usePage, Head, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import axios from 'axios';

const page = usePage()

const data = computed(() => page.props.data)

// curl -v \
// 	GET \
// 	-H "User-Agent: HTTPBot-iOS/2024.0.1" \
// 	-H "Authorization: Bearer QteRgcGaC8TGojF" \
// 	"http://192.168.1.14:5774/WebService/getPengguna?npsn=20518848&access_token="

const results = ref(null)

const tesDapodik = async() => {
    let config = {
        method: 'get',
        maxBodyLength: Infinity,
        url: 'http://192.168.1.14:5774/WebService/getPengguna?npsn=20518848',
        headers: { 
            'Authorization': 'Bearer QteRgcGaC8TGojF', 
            'Content-Type': 'application/json',
            "Accept": "/",
            "Cache-Control": "no-cache",
            // 'user-agent':'curl/7.79.1'
            // "Cookie": document.cookie
        },
        creadentials: 'same-origin'
    };

    axios.request(config)
    .then((response) => {
        console.log(JSON.stringify(response.data));
    })
    .catch((error) => {
        console.log(error);
    });
}
</script>

<template>
<el-row :gutter="20">
    <el-col :span="6" :xs="24">
        <el-card>
            <template #header>
                <span>Data Sekolah</span>
            </template>
            <div class="card-body">
                <table>
                    <tr>
                        <td>Nama Sekolah</td>
                        <td class="px-1">:</td>
                        <td>{{ data.sekolah.nama }}</td>
                    </tr>
                    <tr>
                        <td>NPSN</td>
                        <td class="px-1">:</td>
                        <td>{{ data.sekolah.npsn }}</td>
                    </tr>
                    <tr>
                        <td>Alamat</td>
                        <td class="px-1">:</td>
                        <td>{{ data.sekolah.alamat }}</td>
                    </tr>
                    <tr>
                        <td>No. Telp</td>
                        <td class="px-1">:</td>
                        <td>{{ data.sekolah.telp }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td class="px-1">:</td>
                        <td>{{ data.sekolah.email }}</td>
                    </tr>
                    <tr>
                        <td>Website</td>
                        <td class="px-1">:</td>
                        <td>{{ data.sekolah.website }}</td>
                    </tr>
                </table>
            </div>
        </el-card>
        <el-card class="mt-2">
            <template #header>
                <span>Data Mapel</span>
            </template>
            <div class="card-body">
                <el-table :data="data.sekolah.mapels">
                    <el-table-column label="#" type="index"></el-table-column>
                    <el-table-column label="Nama" prop="label"></el-table-column>
                </el-table>
            </div>
        </el-card>
    </el-col>
    <el-col :span="10" :xs="24">
        <el-card>
            <template #header>
                <span>Data Rombel</span>
            </template>
            <div class="card-body">
                <el-table :data="data.sekolah.rombels" >
                    <el-table-column label="#" type="index"></el-table-column>
                    <el-table-column label="Rombel" prop="label"></el-table-column>
                    <el-table-column label="Fase/Tingkat">
                        <template #default="scope">
                            <span>{{ scope.row.fase }} / {{ scope.row.tingkat }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Wali Kelas" prop="guru.nama"></el-table-column>
                    <el-table-column label="Jml Siswa" >
                        <template #default="scope">
                            {{ scope.row.siswas?.length }}
                        </template>
                    </el-table-column>
                </el-table>

            </div>
        </el-card>

        <!-- <el-card class="my-4">
            <template #header>
                <div class="flex items-center justify-between">
                    <h3>Tes Dapodik</h3>

                    <el-button @click="tesDapodik">Tes</el-button>
                </div>
            </template>
            <div class="card-body">
                {{ results }}
            </div>
        </el-card> -->
    </el-col>
    <el-col :span="8" :xs="24">
        <el-card>
            <template #header>
                <span>Data Guru</span>
            </template>
            <div class="card-body">
                <el-table :data="data.sekolah.gurus" >
                    <el-table-column label="#" type="index"></el-table-column>
                    <el-table-column label="NIP" prop="nip"></el-table-column>
                    <el-table-column label="Nama" prop="nama"></el-table-column>
                </el-table>
            </div>
        </el-card>
    </el-col>
</el-row>
</template>