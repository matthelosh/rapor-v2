<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { ElDialog } from 'element-plus';
import { Icon } from '@iconify/vue'
const page = usePage()

const props = defineProps({rombel: Object, open: Boolean, sekolah: Object, mapel: Object})
const emit = defineEmits(['close'])
const role = page.props.auth.roles[0]

const tps = ref(['TP1','TP2', 'TP3'])

const nilais = ref([])
const siswas = ref([])

const simpan  =async() => {
    console.log(siswas.value)
}

onBeforeMount(() => {
    props.rombel.siswas.forEach((siswa, s) => {
        let ns = {}
        tps.value.forEach(tp => ns[tp] = 0)
        siswas.value.push({id: siswa.id, nisn: siswa.nisn, nilais: ns})
    })
})
</script>

<template>
<el-dialog v-model="props.open" fullscreen @close="emit('close')" :show-close="false">
    <template #header="{ close, titleId, titleClass }">
        <span class="uppercase">
            <span class="flex items-center justify-between">
                <div>
                    Form Nilai Harian | 
                    <span v-if="role == 'guru_kelas'">{{ props.mapel.label }} | </span>
                    {{ props.rombel.label }} 
                    <span v-if="role !== 'guru_kelas'">{{ props.sekolah.nama }}</span>
                </div>
                <div class="items flex items-">
                    <el-button type="primary" size="small">Impor</el-button>
                    <el-button type="primary" size="small" @click="simpan">Simpan</el-button>
                    <el-button type="danger" @click="close" size="small">
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </span>
        </span>
    </template>
    <div class="dialog-body">
        <el-table :data="props.rombel.siswas" height="90vh">
            <el-table-column type="index" label="#" width="50" fixed></el-table-column>
            <el-table-column label="NISN" prop="nisn" width="120" fixed />
            <el-table-column label="Nama" prop="nama" fixed />
            <el-table-column label="JK" width="60">
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
                        <el-input v-model="siswas[scope.$index].nilais[tp]" type="number" :tabindex="(scope.$index * tp)" min="0" max="100" />
                    </template>
                </el-table-column>
            </template>
        </el-table>
    </div>
</el-dialog>

</template>