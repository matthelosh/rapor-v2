<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { Head, router, usePage } from '@inertiajs/vue3'

import DashLayout from '@/Layouts/DashLayout.vue'
const page = usePage()

const activeCollapse = ref(0)

const mapels = (tingkat) => {
    return page.props.mapels.filter(mapel => mapel.tingkat == tingkat)
}

onBeforeMount(async() => {

})
</script>

<template>
<Head title="Ledger Nilai" />

<DashLayout>
    <template #header>
        <h3>Data Ledger Nilai </h3>
    </template>
    <h2 class="text-lg font-bold text-slate-500 mb-6">Ledger Nilai</h2>
    <!-- {{page.props.mapels}} -->
    <el-collapse v-model="activeCollapse">
        <template v-for="(rombel, r) in page.props.rombels" :key="rombel.kode">
            <el-collapse-item :name="r">
                <template #title>
                    <div class="collapse-title">
                        <h3 class="uppercase">{{ rombel.label }} {{ rombel.sekolah.nama }}</h3>
                    </div>
                </template>
                <div class="collapse-body">
                    <el-table :data="rombel.siswas" height="80vh" size="small">
                        <el-table-column label="#" type="index" prop="scope.$index" />
                        <el-table-column label="NISN" prop="nisn"></el-table-column>
                        <el-table-column label="Nama" prop="nama"></el-table-column>
                        <el-table-column label="JK" prop="jk"></el-table-column>
                        <template v-for="(mapel, m) in page.props.mapels" :key="m">
                            <el-table-column>
                                <template #header>
                                    {{ mapel.kode }}
                                </template>
                            </el-table-column>
                        </template>
                    </el-table>
                </div>
            </el-collapse-item>
        </template>
    </el-collapse>

</DashLayout>
</template>