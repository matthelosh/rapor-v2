<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import axios from 'axios'
import { ElNotification } from 'element-plus';

const page = usePage()
const props = defineProps({siswa: Object, rombel: Object, open: Boolean})
const emit = defineEmits(['close', 'nextSiswa', 'prevSiswa'])
const absen = ref([])


const getAbsen = async() => {
    await axios.get(route('dashboard.nilai.absen.index', {
            _query: {
                sekolahId: page.props.sekolahs[0].npsn,
                siswaId: props.siswa.nisn,
                tapel: page.props.periode.tapel.kode,
                semester: page.props.periode.semester.kode,
            }
        })).then(res => {
            if (res.data.absen !== null) {
                absen.value = res.data.absen
            } else {
                absen.value = {
                    ijin: 0, sakit: 0, alpa: 0
                }
            }
        }).catch(err => console.log(err))
}

const simpan = async() => {
    router.post(route('dashboard.nilai.absen.store', {
        _query: {
            siswaId: props.siswa.nisn,
            tapel: page.props.periode.tapel.kode,
            semester: page.props.periode.semester.kode,
            rombelId: props.rombel.kode
        }
    }), {absen: absen.value}, {
        onSuccess: page => {
            ElNotification({title: 'Info', message: page.props.flash.message, type:'success'})
        },
        onError: errs => {
            Object.keys(errs).forEach(k => {
                setTimeout(() => {
                    ElNotification({title: 'Error', message: errs[k], type: 'error'})
                }, 500)
            })
        }
    })
}


onBeforeMount(async () => {
    getAbsen()
})
</script>

<template>
    <el-dialog v-model="props.open" :show-close="false" :body-style="{background: '#aaa'}" width="450">
        <template #header="{close}">
            <div class="toolbar flex items-center justify-between">
                <span class="uppercase">
                    Isi Ketidakhadiran <br /><small>{{ props.siswa.nama }}</small></span>
                <div class="toolbar-items flex items-center">
                    <el-button type="danger" @click="emit('close')" size="small">
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </div>
        </template>
        <div class="dialog-body flex justify-center items-center p-4 border-t-2 border-slate-400 border-t">
            <table width="100%">
                <tr>
                    <td class="p-2 border border-slate-400">IZIN</td>
                    <td class="p-2 border border-slate-400"><el-input v-model="absen.ijin" type="number"></el-input></td>
                </tr>
                <tr>
                    <td class="p-2 border border-slate-400">SAKIT</td>
                    <td class="p-2 border border-slate-400"><el-input v-model="absen.sakit" type="number"></el-input></td>
                </tr>
                <tr>
                    <td class="p-2 border border-slate-400">TANPA KETERANGAN</td>
                    <td class="p-2 border border-slate-400"><el-input v-model="absen.alpa" type="number"></el-input></td>
                </tr>
            </table>
        </div>
        <template #footer>
            <div class="flex justify-end px-4">
                <el-button type="primary" @click="simpan">Simpan</el-button>
            </div>
        </template>
    </el-dialog>
</template>