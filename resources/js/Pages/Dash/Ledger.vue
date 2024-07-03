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
    <el-card>
        <template #header>
            <h2 class="text-lg font-bold text-slate-500">Ledger Nilai</h2>
        </template>
        <div class="card-body">
            <el-collapse v-model="activeCollapse">
                <template v-for="(rombel, r) in page.props.rombels" :key="rombel.kode">
                    <el-collapse-item :name="r">
                        <template #title>
                            <div class="collapse-title">
                                <h3 class="uppercase">{{ rombel.label }} {{ rombel.sekolah.nama }}</h3>
                            </div>
                        </template>
                        <div class="collapse-body">
                            <el-table :data="rombel.siswas" height="70vh" size="small">
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
            </div>
    </el-card>
    <!-- {{page.props.mapels}} -->

</DashLayout>
</template>