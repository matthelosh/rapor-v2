<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import { ElNotification } from 'element-plus';

const page = usePage()
const props = defineProps({ proyek: Object})
const emit = defineEmits(['close'])
const show = ref(false)

const loading = ref({
    nilais: false,
})

    const nilais = ref([])
    const getNilaiP5 = async () => {
        loading.value.nilais = true
        axios.post(route('dashboard.p5.nilai.index', {
            _query: {
                rombel: props.proyek.rombel_id,
                proyek_id: props.proyek.id
            }
        }))
        .then(res => {
            nilais.value = res.data.nilais
            loading.value.nilais = false
        }).catch(err => console.log(err))
    }

const simpanNilaiP5 = async () => {
    router.post(route('dashboard.p5.nilai.store'), 
        { 
            datas: nilais.value
        },
        {
            onStart: () => loading.value.nilais = true,
            onSuccess: () => ElNotification({ title: 'Info', message: page.props.flash.message, type: 'success'}),
            onError: errs => ElNotification({ title: 'Error', message: errs[0], type: 'error'}),
            onFinish: () => loading.value.nilais = false
        }
    )
}

const skors = ref([
        {kode: '-', label: ''},
        {kode: 'BB', label: 'Belum Berkembang'},
        {kode: 'MB', label: 'Mulai Berkembang'},
        {kode: 'BSH', label: 'Berkembang Sesuai Harapan'},
        {kode: 'SB', label: 'Sangat Berkembang'}
])


onBeforeMount(() => {
    show.value = props.proyek !== null
    getNilaiP5()
})
</script>

<template>
<el-dialog fullscreen :show-close="false" v-model="show">
    <template #header>
        <div class="header flex items-center justify-between">
            <h3>Input Nilai P5 {{ props.proyek.nama }}</h3>
            <el-button circle type="danger" @click="emit('close')">
                <Icon icon="mdi:close" />
            </el-button>
        </div>
    </template>
    <template #default>
        <el-card v-loading="loading.nilais">
            <!-- <el-table :data="nilais" max-height="80vh">
                <el-table-column label="No" type="index"></el-table-column>
                <el-table-column label="NISN" prop="siswa_id" width="150"></el-table-column>
                <el-table-column label="Nama" prop="nama"></el-table-column>
                <template v-for="(apd, a) in props.proyek.apds" :key="a">
                    <el-table-column :label="apd.teks">
                        <template #default="scope">
                            <template v-for="(nilai, s) in scope.row.nilais">
                                <span v-if="nilai.apd_id == apd.id">
                                    <el-select v-model="nilai.skor">
                                        <el-option v-for="skor in skors" :value="skor.kode" :label="skor.label"></el-option>
                                    </el-select>
                                </span>
                            </template>
                        </template>
                    </el-table-column>
                </template>
                <el-table-column label="Proses" type="expand">
                    <template #default="{row}">
                        <div class="m-4">
                            <h3 class="font-bold text-sky-700 mb-2">Keterangan Proses:</h3>
                            <el-input type="textarea" autosize v-model="row.proses" :rows="2"></el-input>
                        </div>
                    </template>
                </el-table-column>
            </el-table> -->
            <table class="border">
                <thead>
                    <tr>
                        <th class="border border-black">No</th>
                        <th class="border border-black">NISN</th>
                        <th class="border border-black">Nama</th>
                        <template v-for="(apd, a) in props.proyek.apds" :key="a">
                            <th class="border border-black">{{ apd.teks }}</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(data, s) in nilais">
                        <tr>
                            <td rowspan="2" class="border border-black py-1 px-2 text-center">{{ s+1 }}</td>
                            <td rowspan="2" class="border border-black py-1 px-2 text-center">{{ data.siswa_id }}</td>
                            <td rowspan="2" class="border border-black py-1 px-2">{{ data.nama }}</td>
                            <template v-for="(nilai,n) in data.nilais">
                                <template v-for="(apd, a) in props.proyek.apds" :key="a">
                                    <td class="border border-black py-1 px-2" v-if="nilai.apd_id == apd.id">
                                        <el-select v-model="nilai.skor">
                                        <el-option v-for="skor in skors" :value="skor.kode" :label="skor.label"></el-option>
                                    </el-select>
                                    </td>
                                </template>
                            </template>
                        </tr>
                        <tr>
                            <td :colspan="props.proyek.apds.length" class="p-4 border border-black">
                                <h3>Keterangan Proses:</h3>
                                <el-input v-model="data.proses"></el-input>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
            <template #footer>
                <div class="flex items-center justify-end">
                    <el-button type="primary" @click="simpanNilaiP5">Simpan</el-button>
                </div>
            </template>
        </el-card>
    </template>
</el-dialog>
</template>