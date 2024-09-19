<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3';
import DashLayout from '@/Layouts/DashLayout.vue';

const page = usePage()
const props = defineProps({ jilids: Array})
const RubrikPretes = defineAsyncComponent(() => import('@/Components/Dashboard/Spn/RubrikPretes.vue'))
const JurnalSpn = defineAsyncComponent(() => import('@/Components/Dashboard/Spn/JurnalSpn.vue'))

const rombels = computed(() => page.props.rombels)
const selectedRombel = ref(null)
const selectedJilid = ref(null)
const openRubrik = (rombel) => {
    selectedRombel.value = rombel
}

const openJurnal = (jilid) => {
    selectedJilid.value = jilid
}

</script>

<template>
<Head title="Sekolah plus Ngaji" />
<DashLayout>
    <template #header>
        Sekolah Plus Ngaji
    </template>
    <el-card>
        <h3 class="mb-4 font-bold text-sky-700">Alur Pelaksanaan</h3>
        
        <el-collapse>
            <el-collapse-item title="1. Pretes">
                <p class="text-justify">Pretes dilakukan untuk memetakan siapa saja peserta didik yang masuk pada jilid 1, 2 atau 3. Ada kemungkinan peserta didik Fase C yang belum bisa sama sekali membaca Al-Quran, bahkan belum mengenal huruf hijaiah. Maka seyogyanya ia harus masuk ke jilid 1.</p>
                <p class="text-justify">Guru dapat menggunakan metode yang sesuai untuk melakukan pretes ini. Kita bisa menggunakan halaman ebta di kitab Iqra, karena Iqra umum digunakan di berbagai TPQ. Guru memberikan tes kemudian mengkategorikan peserta didik sesuai kemampuannya di Jilid yang ditentukan. Unduh instrumen pretes untuk masing-masing rombel di bawah ini.</p>
                <div class="lembar-ebta md:flex gap-4 hidden">
                    <div class="juz-2 text-center">
                        <h3>Pretes Jilid 1</h3>
                        <el-image src="/img/ebta-juz-2.png"></el-image>
                    </div>
                    <div class="juz-2 text-center">
                        <h3>Pretes Jilid 2</h3>
                        <el-image src="/img/ebta-juz-4.png"></el-image>
                    </div>
                    <div class="juz-2 text-center">
                        <h3>Pretes Jilid 3</h3>
                        <el-image src="/img/ebta-juz-6-a.png"></el-image>
                        <el-image src="/img/ebta-juz-6-b.png"></el-image>
                        <el-image src="/img/ebta-juz-6-c.png"></el-image>
                    </div>
                </div>
                <el-table :data="rombels">
                    <el-table-column label="Kelas" prop="label"></el-table-column>
                    <el-table-column label="Opsi">
                        <template #default="{row}">
                            <el-button-group size="small">
                                <el-button size="small" @click="openRubrik(row)">Buka</el-button>
                                <el-button size="small">Cetak</el-button>
                            </el-button-group>
                        </template>
                    </el-table-column>
                </el-table>
            </el-collapse-item>
            <el-collapse-item title="2. Pelaksanaan">
                <h3 class="font-bold text-sky-700">Jurnal Pelaksanaan SPN</h3>
                <el-table :data="props.jilids">
                    <el-table-column label="Nama" prop="nama"></el-table-column>
                    <el-table-column label="Peserta">
                        <template #default="{row}">
                            {{ row.siswas?.length }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Opsi">
                        <template #default="{row}">
                            <el-button size="small" @click="openJurnal(row)">Isi Jurnal</el-button>
                        </template>
                    </el-table-column>
                </el-table>
            </el-collapse-item>
            <el-collapse-item title="3. Evaluasi">

            </el-collapse-item>
            <el-collapse-item title="4. Pelaporan">

            </el-collapse-item>
        </el-collapse>
    </el-card>
    <RubrikPretes v-if="selectedRombel !== null" @close="selectedRombel = null" :rombel="selectedRombel" :jilids="props.jilids" /> 
    <JurnalSpn v-if="selectedJilid !== null" @close="selectedJilid = null" :jilid="selectedJilid" /> 
</DashLayout>
</template>