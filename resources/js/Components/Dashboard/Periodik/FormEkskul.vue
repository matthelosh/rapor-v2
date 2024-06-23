<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import axios from 'axios'

const page = usePage()
const props = defineProps({siswa: Object, rombel: Object, open: Boolean})
const emit = defineEmits(['close', 'nextSiswa', 'prevSiswa'])
const ekskuls = ref([])
const nilais = ref([

])

const getEkskuls = async() => {
    await axios.get(route('dashboard.pembelajaran.ekskul', {
            _query: {
                sekolahId: page.props.sekolahs[0].npsn
            }
        })).then(res => {
            ekskuls.value = res.data.ekskuls
            res.data.ekskuls.forEach(item => {
                nilais.value.push({kode: item.kode, nilai: 0, deskripsi: 'Halo'})
            })
        }).catch(err => console.log(err))

}


onBeforeMount(() => {
    getEkskuls()
})
</script>

<template>
    <el-dialog v-model="props.open" :show-close="false" :body-style="{background: '#aaa'}">
        <template #header="{close}">
            <div class="toolbar flex items-center justify-between">
                <span class="uppercase">Isi Nilai Ekstrakurikuler {{ props.siswa.nama }}</span>
                <div class="toolbar-items flex items-center">
                    <el-button type="danger" @click="emit('close')" size="small">
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </div>
        </template>
        <div class="dialog-body">
            <el-table :data="ekskuls">
                <el-table-column label="Nama Ekskul" prop="nama"></el-table-column>
                <el-table-column label="Nilai" >
                    <template #default="scope">
                        <el-input v-model="nilais[scope.$index].nilai"></el-input>
                    </template>
                </el-table-column>
                <el-table-column label="Deskripsi" >
                    <template #default="scope">
                        <el-input type="textarea" rows="1" autosize v-model="nilais[scope.$index].deskripsi"></el-input>
                    </template>
                </el-table-column>
            </el-table>
        </div>
        <template #footer>
            <div class="flex justify-end px-4">
                <el-button type="primary">Simpan</el-button>
            </div>
        </template>
    </el-dialog>
</template>