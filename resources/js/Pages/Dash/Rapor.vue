<script setup>
import { ref, computed } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'

import DashLayout from '@/Layouts/DashLayout.vue'
import Cover from '@/Components/Dashboard/Rapor/Cover.vue'
import Biodata from '@/Components/Dashboard/Rapor/Biodata.vue'
import RaporPTS from '@/Components/Dashboard/Rapor/RaporPTS.vue'
import RaporPAS from '@/Components/Dashboard/Rapor/RaporPAS.vue'

const page = usePage()
const mode = ref('list')

const rombel = page.props.rombels[0]
const selectedSiswa = ref({})

const cetak = (laman, siswa) => {
    mode.value = laman
    selectedSiswa.value = siswa
}

const closeLaman = () => {
    mode.value = 'list'
    selectedSiswa.value = {}
}

const nextSiswa = () => {
    // alert('halo')
    let current = rombel.siswas.findIndex(siswa => siswa.id === selectedSiswa.value.id)
    if(current >= rombel.siswas.length-1 ) {
        alert('Ini sudah siswa yang terakhir')
        return false
    } else {
        selectedSiswa.value = rombel.siswas[current+1]
    }
}
const prevSiswa = () => {
    // alert('halo')
    let current = rombel.siswas.findIndex(siswa => siswa.id === selectedSiswa.value.id)
    if(current === 0 ) {
        alert('Ini sudah siswa yang pertama')
        return false
    } else {
        selectedSiswa.value = rombel.siswas[current-1]
    }
}
</script>

<template>
<Head title="Rapor Siswa" />

<DashLayout>
    <template #header>
        <h3 class="uppercase">Rapor Siswa {{ selectedSiswa?.nama }}</h3>
    </template>
    <el-card v-if="mode == 'list'">
        <template #header>
            <div>
                <h3 class="uppercase font-bold text-slate-600">Rapor Siswa {{ rombel.label }} {{ rombel.sekolah.nama }}</h3>
            </div>
        </template>
        <div class="card-body">
            <el-table :data="rombel.siswas" height="700" size="small">
                <el-table-column label="#" type="index" prop="scope.$index" />
                <el-table-column label="NISN" prop="nisn" />
                <el-table-column label="Nama" prop="nama" />
                <el-table-column label="JK" prop="jk" />
                <el-table-column label="Opsi Cetak">
                    <template #default="scope">
                        <div>
                            <el-button-group>
                                <el-button type="primary" @click="cetak('cover', scope.row)">Cover</el-button>
                                <el-button type="primary" @click="cetak('biodata', scope.row)">Biodata</el-button>
                                <el-button type="primary" @click="cetak('pts', scope.row)">PTS</el-button>
                                <el-button type="primary" @click="cetak('pas', scope.row)">PAS</el-button>
                            </el-button-group>
                        </div>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </el-card>
    <Cover v-if="mode == 'cover'" :siswa="selectedSiswa" @close="closeLaman" @nextSiswa="nextSiswa" @prevSiswa="prevSiswa" :rombel="rombel" />
    <Biodata v-if="mode == 'biodata'" :siswa="selectedSiswa" @close="closeLaman" @nextSiswa="nextSiswa" @prevSiswa="prevSiswa" :rombel="rombel" />
    <RaporPTS v-if="mode == 'pts'" :siswa="selectedSiswa" @close="closeLaman" @nextSiswa="nextSiswa" @prevSiswa="prevSiswa" :rombel="rombel" />
    <RaporPAS v-if="mode == 'pas'" :siswa="selectedSiswa" @close="closeLaman" @nextSiswa="nextSiswa" @prevSiswa="prevSiswa" :rombel="rombel" />
</DashLayout>

</template>
