<script setup>
import { ref, computed } from 'vue'
import { usePage, Head, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import axios from 'axios';

const page = usePage()

const data = computed(() => page.props.data)


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
const agamas = ref(['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha', 'Konghuchu']);
const colorAgama = ref(["#8dbd05","#00a1ae","#5e36cc","#fe318e","#ff7540","#fd9800"])
const jmlAgama = (agama) => {
    let siswas = data.value.sekolah.rombels.flatMap(rombel => rombel.siswas)
    // data.value.sekolah.rombels.forEach(rombel => siswas.push(rombel.siswas))
    return siswas.filter(siswa => siswa.agama == agama).length
    // return siswas.length
}
const jmlSiswas = computed(() => {
    let jml = 0;
    data.value.sekolah.rombels.forEach(rombel => {
        jml += rombel.siswas.length
    })
    return jml
})
const bgBars = ref(["#0cb2af","#a1c65d","#fac723","#f29222","#e95e50","#936fac"]);
</script>

<template>
<div>
<el-row :gutter="24">
    <el-col :span="8">
        <el-card title="Data Siswa tiap Rombel">
                        <div class="bars flex items-end gap-4 justify-center">
                <template v-for="(rombel, r) in data.sekolah.rombels" :key="r">
                            <div class="w-14 p-2  text-center relative flex flex-col rounded" :style="`background: ${bgBars[r]};min-height: 50px!important; height: ${(rombel.siswas.length / jmlSiswas * 100)*8}px;`">

                                <span class="bg-white rounded shadow">{{rombel.siswas.length}}</span>
                                <span class="w-full vertical-lr font-bold text-white drop-shadow">
                                    {{rombel.label}}
                                </span>
                            </div>
                </template>

            </div>
            <h3 class="text-center font-bold mt-2 text-sky-700">Data Siswa tiap Rombel</h3>

        </el-card>
    </el-col>
    <el-col :span="10">
        <el-card title="Data Siswa tiap Rombel">
                        <div class="bars flex items-end gap-4 justify-center">
                <template v-for="(agama, a) in agamas" :key="a">
                            <div class="w-18 p-2  text-center relative flex flex-col rounded" :style="`min-height: 60px;height: ${jmlAgama(agama)}px; background: ${colorAgama[a]};`" >

                                <span class="bg-white rounded shadow">{{jmlAgama(agama)}}</span>
                                <span class="w-full vertical-lr font-bold text-white drop-shadow">
                                    {{agama}}
                                </span>
                            </div>
                </template>

            </div>
            <h3 class="text-center font-bold mt-2 text-sky-700">Data Siswa Berdasarkan Agama</h3>

        </el-card>
    </el-col>
</el-row>
<div class="my-4" />
<el-row :gutter="20">
    <el-col :span="6" :xs="24">
        <el-card>
            <template #header>
                <span>Data Sekolah</span>
            </template>
            <div class="card-body">
                <table>
                    <tbody>
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
                </tbody>
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
                    <el-table-column label="Rombel" prop="label" width="120"></el-table-column>
                    <el-table-column label="Fase /Tingkat" width="80">
                        <template #default="scope">
                            <span>{{ scope.row.fase }} / {{ scope.row.tingkat }}</span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Wali Kelas" >
                        <template #default="{row}">
                            {{row.wali_kelas[0].nama}}
                        </template>
                    </el-table-column>
                    <el-table-column label="Jml Siswa" width="80">
                        <template #default="scope">
                            {{ scope.row.siswas?.length }}
                        </template>
                    </el-table-column>
                </el-table>

            </div>
        </el-card>

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
</div>
</template>
