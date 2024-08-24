<script setup>
import { ref, computed } from 'vue'
import { router, Head } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import { read, utils } from 'xlsx'

import DashLayout from '@/Layouts/DashLayout.vue';
import { ElNotification } from 'element-plus';
const props = defineProps({p5s: Array})
const loading = ref(false)

const apds = ref([])
const imporApd = async(e) => {
    loading.value = true
    const file = e.target.files[0]
    const ab = await file.arrayBuffer()
    const wb = read(ab)
    const ws = wb.Sheets[wb.SheetNames[0]]
    const items = utils.sheet_to_json(ws)
    // console.log(datas)
    await router.post(route('dashboard.apd.impor'), {datas: items}, {
        onSuccess: (page) => {
            ElNotification({ title: 'Info', message: page.props.flash.message, type: 'success'})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({ title: 'Error', message: errs[k], type: 'error' })
                }, 500)
            })
        },
        onFinish: () => loading.value = false
    })

}
</script>

<template>
<Head title="P5" />

<DashLayout>
    <template #header>
        Manajemen P5
    </template>
    <el-card class="body" v-loading="loading">
        <template #header>
            <div class="flex items-center justify-between">
                <h3 class="font-bold text-sky-800">Proyek Penguatan Profil Pelajar Pancasila (P5)</h3>
                <div class="flex items-center gap-2">
                    <input type="file" ref="fileApd" accept=".xls,.xlsx,.csv,.ods" @change="imporApd" class="hidden">
                    <el-button type="primary" size="small" @click="$refs.fileApd.click()">
                        Impor APD
                    </el-button>
                </div>
            </div>
        </template>
        <el-collapse>
            <template v-for="(p5,p) in props.p5s" :key="p">
                <el-collapse-item :title="`${p+1}. ${p5.dimensi} (${p5.kode}) `">
                    <el-table :data="p5.elemens">
                        <el-table-column label="#" type="index"></el-table-column>
                        <el-table-column label="Teks" prop="teks"></el-table-column>
                        <el-table-column label="APD" type="expand" width="200">
                            <template #default="scope">
                                <div class="pl-8">
                                    <h3 class="font-bold">Alur Perkembangan Dimensi {{ p5.kode }} </h3>
                                    <el-table :data="scope.row.apds">
                                        <el-table-column label="#" type="index"></el-table-column>
                                        <el-table-column label="Teks" prop="teks"></el-table-column>
                                        <el-table-column label="Fase" prop="fase" width="100"></el-table-column>
                                    </el-table>
                                </div>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-collapse-item>
            </template>
        </el-collapse>
    </el-card>
</DashLayout>
</template>