<script setup>
import { ref, computed, defineAsyncComponent } from 'vue'
import { usePage, router, Head } from '@inertiajs/vue3'
import { ElCard, ElAlert } from 'element-plus';
import DashLayout from '@/Layouts/DashLayout.vue'
const page = usePage()

import NilaiKelas from '@/Components/Dashboard/Nilai/GuruKelas.vue'
import NilaiMapel from '@/Components/Dashboard/Nilai/GuruMapel.vue'

const komponen = computed(() => {
    return page.props.auth.roles[0] === 'guru_kelas' ? NilaiKelas : NilaiMapel
})
const notGuru = computed(() => {
    return ['admin','ops','kepala_sekolah'].includes(page.props.auth.roles[0])
})

</script>

<template>
<Head title="Penilaian" />
<DashLayout>
    <template #header>
        <div>
            <h3 class="font-black text-xl text-slate-700" v-if="page.props.auth.roles.includes('guru_kelas')">{{ page.props.sekolahs[0].nama }}</h3>
        </div>
    </template>
    <component :is="komponen" v-if="!notGuru"/>
    <el-card v-else>
        <el-alert type="warning">
            <h3 class="text-2xl font-bold tracking-wide">Laman ini hanya untuk Guru</h3>
        </el-alert>
    </el-card>
</DashLayout>
</template>