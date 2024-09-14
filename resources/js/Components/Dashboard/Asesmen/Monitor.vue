<script setup>
import { ref, computed, onMounted, onBeforeMount } from 'vue'
import { usePage, router } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import { ElNotification } from 'element-plus';
import axios from 'axios';

const props =defineProps({selectedAsesmen: Object})
const emit = defineEmits(['close', 'update-data'])
const page = usePage()

const processes = ref([])
const colors = [
  { color: '#f56c6c', percentage: 20 },
  { color: '#e6a23c', percentage: 40 },
  { color: '#5cb87a', percentage: 60 },
  { color: '#1989fa', percentage: 80 },
  { color: '#6fff63', percentage: 100 },
]
const reloadData = async() => {
    axios.post(route('dashboard.asesmen.monitor.reload', {_query: {asesmenId: props.selectedAsesmen.id}}))
            .then(res => {
                processes.value = res.data.asesmen.proses
            })
}

const percentage = (total) => {
    return Math.round(total / props.selectedAsesmen.soals.length * 100)
}

onBeforeMount(() => {
    processes.value = props.selectedAsesmen.proses
    window.Echo.channel("tes")
            .listen("JawabanReceived", (notif) => {
                ElNotification({title: 'Info', message: notif.data, type: 'info'})
                // emit('update-data')
                reloadData()
            })
})
</script>

<template>
    <el-card>
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Monitoring Asesmen {{ props.selectedAsesmen.nama }}</h3>
                <div class="flex items-center gap-1">
                    <el-button @click="emit('update-data')">
                        <Icon icon="mdi:reload-alert" class="text-xl" />
                    </el-button>
                    <el-button @click="emit('close')" type="danger" circle>
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </div>
        </template>
        <!-- {{ processes }} -->
        <table>
            <thead>
                <tr>
                    <th class="border border-black p-2 bg-slate-100">No</th>
                    <th class="border border-black p-2 bg-slate-100">Siswa</th>
                    <th class="border border-black p-2 bg-slate-100">Jawaban</th>
                    <th class="border border-black p-2 bg-slate-100">Progress</th>
                    <th class="border border-black p-2 bg-slate-100">Skor</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(proses, p) in processes" :key="p">
                    <tr>
                        <td class="border border-black p-2">{{ p+1 }}</td>
                        <td class="border border-black p-2">{{ proses.siswa?.nama }}</td>
                        <td class="border border-black p-2">{{ proses.jawabans?.length }}</td>
                        <td class="border border-black p-2">
                            <el-progress type="circle" :width="80" :stroke-width="10" :percentage="percentage(proses.jawabans.length)" :color="colors" :type="'success'" />
                        </td>
                        <td class="border border-black p-2">{{ proses.jawabans.length > 0 ? proses.jawabans.reduce((a,c) => a+=c.is_benar,0) : 0 }}</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </el-card>
</template>