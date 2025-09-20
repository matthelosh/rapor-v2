<script setup>
import { ref, computed } from "vue";
import { usePage, Head, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import axios from "axios";

const page = usePage();

const data = computed(() => page.props.data);

const results = ref(null);

// const tesDapodik = async() => {
//     let config = {
//         method: 'get',
//         maxBodyLength: Infinity,
//         url: 'http://192.168.1.14:5774/WebService/getPengguna?npsn=20518848',
//         headers: {
//             'Authorization': 'Bearer QteRgcGaC8TGojF',
//             'Content-Type': 'application/json',
//             "Accept": "/",
//             "Cache-Control": "no-cache",
//             // 'user-agent':'curl/7.79.1'
//             // "Cookie": document.cookie
//         },
//         creadentials: 'same-origin'
//     };

//     axios.request(config)
//     .then((response) => {
//         console.log(JSON.stringify(response.data));
//     })
//     .catch((error) => {
//         console.log(error);
//     });
// }
const agamas = ref([
    "Islam",
    "Kristen",
    "Katolik",
    "Hindu",
    "Budha",
    "Konghuchu",
]);
const colorAgama = ref([
    "#8dbd05",
    "#00a1ae",
    "#5e36cc",
    "#fe318e",
    "#ff7540",
    "#fd9800",
]);
const jmlAgama = (agama) => {
    let siswas = data.value.sekolah.rombels.flatMap((rombel) => rombel.siswas);
    // data.value.sekolah.rombels.forEach(rombel => siswas.push(rombel.siswas))
    return siswas.filter((siswa) => siswa.agama == agama).length;
    // return siswas.length
};
const jmlSiswas = computed(() => {
    let jml = 0;
    data.value.sekolah.rombels.forEach((rombel) => {
        jml += rombel.siswas.length;
    });
    return jml;
});

const byGender = computed(() => {
    let res = {
        total: { lk: 0, pr: 0 },
        rombels: [
            // {label: '', lk: 0, pr: 0, total: 0}
        ]
    };
    data.value.sekolah.rombels.forEach((rombel) => {
        rombel.siswas.forEach((siswa) => {
            if (siswa.jk == 'Laki-laki') {
                res.total.lk++;
            } else {
                res.total.pr++;
            }
        })
        res.rombels.push({label: rombel.tingkat + (rombel.pararel != '0' ? rombel.pararel.toUpperCase() : ''), lk: rombel.siswas.filter((siswa) => siswa.jk == 'Laki-laki').length, pr: rombel.siswas.filter((siswa) => siswa.jk == 'Perempuan').length, total: rombel.siswas.length})
    });
    return res;
})

const maxSiswaInRombel = computed(() => {
    if (!data.value.sekolah.rombels || data.value.sekolah.rombels.length === 0) return 0;
    return Math.max(...data.value.sekolah.rombels.map(r => r.siswas.length));
});

const maxSiswaInAgama = computed(() => {
    if (!agamas.value || agamas.value.length === 0) return 0;
    const counts = agamas.value.map(a => jmlAgama(a));
    return Math.max(...counts);
});

const bgBars = ref([
    "#0cb2af",
    "#a1c65d",
    "#fac723",
    "#f29222",
    "#e95e50",
    "#936fac",
]);
</script>

<template>
    <div>
        <el-row :gutter="24">
            <el-col :span="8" :xs="24">
                <el-card>
                    <template #header>
                        <h3 class="font-bold text-slate-700">Siswa per Rombel</h3>
                    </template>
                    <div class="h-[220px] flex items-end justify-around gap-2 text-center">
                        <template v-for="(rombel, r) in data.sekolah.rombels" :key="r">
                            <div class="w-14">
                                <span class="text-sm font-bold text-slate-600">{{ rombel.siswas.length }}</span>
                                <div
                                    class="w-10 mx-auto rounded-t-md mt-1"
                                    :style="{
                                        background: bgBars[r % bgBars.length],
                                        height: maxSiswaInRombel > 0 ? (rombel.siswas.length / maxSiswaInRombel * 180) + 'px' : '0px'
                                    }"
                                ></div>
                                <span class="text-xs font-medium text-slate-500 mt-1 w-full font-bold truncate">
                                    {{ rombel.tingkat }} {{ rombel.pararel != '0' ? rombel.pararel.toUpperCase() : '' }}
                                </span>
                            </div>
                        </template>
                    </div>
                </el-card>
            </el-col>
            <el-col :span="6" :xs="24">
                <el-card>
                    <template #header>
                        <h3 class="font-bold text-slate-700">Siswa per Agama</h3>
                    </template>
                    <div class="h-[220px] flex items-end justify-around gap-2 text-center">
                        <template v-for="(agama, a) in agamas" :key="a">
                            <div v-if="jmlAgama(agama) > 0" class="w-14">
                                <span class="text-sm font-bold text-slate-600">{{ jmlAgama(agama) }}</span>
                                <div
                                    class="w-10 mx-auto rounded-t-md mt-1"
                                    :style="{
                                        background: colorAgama[a % colorAgama.length],
                                        height: maxSiswaInAgama > 0 ? (jmlAgama(agama) / maxSiswaInAgama * 180) + 'px' : '0px'
                                    }"
                                ></div>
                                <span class="text-xs font-medium text-slate-500 mt-1 w-full truncate">
                                    {{ agama }}
                                </span>
                            </div>
                        </template>
                    </div>
                </el-card>
            </el-col>
            <el-col :span="10" :xs="24">
                <el-card title="Data siswa berdasarkan jenis kelamin">
                    <h3>Data siswa berdasarkan jenis kelamin</h3>
                    <div class="grid grid-cols-12 h-[240px] gap-4">
                        <!-- Bar charts for each class -->
                        <div class="col-span-8 grid  gap-x-4 gap-y-2 items-end" :class="`grid-cols-${byGender.rombels.length}`">
                            <template v-for="(rombel, r) in byGender.rombels" :key="r">
                                <div class="flex flex-col items-center text-center w-full">
                                    <div v-if="rombel.total > 0" class="w-full h-[180px] bg-slate-200 rounded-md flex flex-col bar-rombel overflow-hidden">
                                        <div
                                            class="bg-purple-500 bar-pr flex justify-center items-center text-white  font-bold"
                                            :style="{ height: (rombel.pr / rombel.total * 100) + '%' }"
                                        >
                                            <span v-if="(rombel.pr / rombel.total * 100) > 15">{{ rombel.pr }}</span>
                                        </div>
                                        <div
                                            class="bg-teal-500 bar-lk flex justify-center items-center text-white  font-bold"
                                            :style="{ height: (rombel.lk / rombel.total * 100) + '%' }"
                                        >
                                            <span v-if="(rombel.lk / rombel.total * 100) > 15">{{ rombel.lk }}</span>
                                        </div>
                                    </div>
                                    <!-- Placeholder for empty class -->
                                    <div v-else class="w-full h-[180px] bg-slate-200 rounded-md flex justify-center items-center">
                                        <span class="text-slate-400 text-xs">0</span>
                                    </div>
                                    <span class="text-xs font-medium text-slate-600 mt-1 w-full truncate">{{ rombel.label }}</span>
                                </div>
                            </template>
                        </div>

                        <!-- Total/Summary Box -->
                        <div class="col-span-4 total-by-gender flex flex-col justify-center p-4 bg-slate-50 rounded-lg">
                            <h4 class="font-bold text-slate-700 mb-4 text-center">Total Siswa</h4>
                            <div class="flex justify-around w-full">
                                <div class="text-center">
                                    <Icon icon="mdi:gender-male" class="text-teal-500 text-4xl mx-auto" />
                                    <div class="font-bold text-2xl text-slate-800">{{ byGender.total.lk }}</div>
                                    <div class="text-sm text-slate-500">Laki-laki</div>
                                </div>
                                <div class="text-center">
                                    <Icon icon="mdi:gender-female" class="text-purple-500 text-4xl mx-auto" />
                                    <div class="font-bold text-2xl text-slate-800">{{ byGender.total.pr }}</div>
                                    <div class="text-sm text-slate-500">Perempuan</div>
                                </div>
                            </div>
                            <div v-if="(byGender.total.lk + byGender.total.pr) > 0" class="w-full bg-slate-200 rounded-full h-2.5 mt-4 flex">
                                <div class="bg-teal-500 h-2.5 rounded-l-full" :style="{ width: (byGender.total.lk / (byGender.total.lk + byGender.total.pr) * 100) + '%' }"></div>
                                <div class="bg-purple-500 h-2.5 rounded-r-full" :style="{ width: (byGender.total.pr / (byGender.total.lk + byGender.total.pr) * 100) + '%' }"></div>
                            </div>
                            <h1 class="font-bold text-4xl text-slate-700 mt-4 text-center">{{ jmlSiswas }}</h1>
                        </div>
                    </div>
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
                            <el-table-column
                                label="#"
                                type="index"
                            ></el-table-column>
                            <el-table-column
                                label="Nama"
                                prop="label"
                            ></el-table-column>
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
                        <el-table :data="data.sekolah.rombels">
                            <el-table-column
                                label="#"
                                type="index"
                            ></el-table-column>
                            <el-table-column
                                label="Rombel"
                                prop="label"
                                width="120"
                            ></el-table-column>
                            <el-table-column label="Fase /Tingkat" width="80">
                                <template #default="scope">
                                    <span
                                        >{{ scope.row.fase }} /
                                        {{ scope.row.tingkat }}</span
                                    >
                                </template>
                            </el-table-column>
                            <el-table-column label="Wali Kelas">
                                <template #default="{ row }">
                                    {{ row.wali_kelas.nama }}
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
                        <el-table :data="data.sekolah.gurus">
                            <el-table-column
                                label="#"
                                type="index"
                            ></el-table-column>
                            <el-table-column
                                label="NIP"
                                prop="nip"
                            ></el-table-column>
                            <el-table-column
                                label="Nama"
                                prop="nama"
                            ></el-table-column>
                        </el-table>
                    </div>
                </el-card>
            </el-col>
        </el-row>
    </div>
</template>
