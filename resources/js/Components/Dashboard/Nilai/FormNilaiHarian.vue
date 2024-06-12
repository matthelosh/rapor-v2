<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { ElDialog } from 'element-plus';
import { Icon } from '@iconify/vue'
const page = usePage()

const props = defineProps({rombel: Object, open: Boolean, sekolah: Object})
const emit = defineEmits(['close'])

const tps = ref(['TP1','TP2', 'TP3'])

const nilais = ref([])

onMounted(() => {
    props.rombel.siswas.forEach((siswa, s) => {
        nilais.value.push({id: siswa.id, nisn: siswa.nisn, TP1: 0, TP2:0, TP3: 0})
    })
})
</script>

<template>
<el-dialog v-model="props.open" fullscreen @close="emit('close')" :show-close="false">
    <template #header="{ close, titleId, titleClass }">
        <span class="uppercase">
            <span class="flex items-center justify-between">
                Form Nilai Harian {{ props.rombel.label }} {{ props.sekolah.nama }}
                <div class="items flex items-">
                    <el-button type="primary" size="small">Impor</el-button>
                    <el-button type="primary" size="small">Simpan</el-button>
                    <el-button type="danger" @click="close" size="small">
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </span>
        </span>
    </template>
    <div class="dialog-body">
        <el-table :data="props.rombel.siswas" height="90vh">
            <el-table-column type="index" label="#" width="50"></el-table-column>
            <el-table-column label="NISN" prop="nisn" width="120" />
            <el-table-column label="Nama" prop="nama" />
            <el-table-column label="JK" width="50">
                <template #default="scope">
                    {{ scope.row.jk.substring(0,1) }}
                </template>
            </el-table-column>
            <el-table-column label="Agm" width="55">
                <template #default="scope">
                    {{ scope.row.agama.substring(0,2) }}
                </template>
            </el-table-column>
            <template v-for="(tp,t) in tps" :key="t">
                <el-table-column :label="tp" width="90">
                    <template #default="scope">
                        <el-input v-model="nilais[scope.$index][tp]" type="number" />
                    </template>
                </el-table-column>
            </template>
        </el-table>
    </div>
</el-dialog>

</template>