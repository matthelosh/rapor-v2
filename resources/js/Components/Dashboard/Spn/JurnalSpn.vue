<script setup>
import { Icon } from '@iconify/vue';
import { ref, onBeforeMount } from 'vue';
import dayjs from 'dayjs';
import 'dayjs/locale/id';
dayjs.locale('id')
import { WebCamUI } from 'vue-camera-lib';
import axios from 'axios';
import { ElNotification } from 'element-plus';

const props = defineProps({jilid: Object})
const emit = defineEmits(['close'])
const show = ref(false)
const siswas = ref([])
const showCamera = ref(false)
const photos = ref([])
const loading = ref(false)
const filePhotos = ref([])
const jurnal = ref({
    absensi: [],
})

const materis = ref(['BTQ', 'Budi Pekerti', 'Ritual Keagamaan', 'Surat Pilihan', 'Hafalan Surat Pendek', 'Shalat Dhuha', 'Shalat Dhuhur'])

const photoTaken = (data) => 
{
    photos.value.push(data.image_data_url)
    filePhotos.value.push(data.blob)
}

const simpan = async() => {
    // loading.value = true
    // let data = jurnal.value
    // data.filePhotos = filePhotos.value
    jurnal.value.jilid_id = props.jilid.id
    let fd = new FormData()
    fd.append("data", JSON.stringify(jurnal.value))
    let no = 1;
    filePhotos.value.forEach(blob => {
        fd.append('fotos[]', blob, `foto-jurnal-${props.jilid.juz}-${dayjs(new Date()).format('YYYYMMDD')}-${no}.jpg`)
        no++
    })
    // console.log(data)
    axios.post(route('dashboard.spn.jurnal.store'), fd, {
        headers: {
            'Content-Type': 'multipart/form-data'
        }
    }).then(res => {
        ElNotification({title: 'Info', message: res.data.message, type: 'success'})
        emit('close')
    })
}

onBeforeMount(() => {
    // console.log(props.jilid)
    show.value = props.jilid !== null
    siswas.value = props.jilid !== null ? props.jilid.siswas : []
})
</script>

<template>
<el-dialog fullscreen v-model="show" :show-close="false">
    <template #header>
        <div class="flex items-center justify-between">
            <h3>Jurnal SPN Jilid {{ props.jilid.juz }} | {{ dayjs(new Date()).format('dddd, DD MMM YYYY') }}</h3>

            <el-button size="small" type="danger" circle @click="emit('close')">
                <Icon icon="mdi:close" />
            </el-button>
        </div>
    </template>

    <el-card>
        <el-scrollbar max-height="75vh">
            <el-form v-model="jurnal" label-position="top" v-loading="loading">
                <el-form-item label="Materi">
                    <el-select v-model="jurnal.materi" placeholder="Pilih materi">
                        <el-option v-for="materi in materis" :value="materi"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Absensi">
                    <el-select v-model="jurnal.absensi" placeholder="Absensi Peserta" filterable clearable multiple>
                        <el-option v-for="siswa in props.jilid.siswas" :value="siswa.nisn" :label="siswa.nama"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item label="Keterangan">
                    <el-input v-model="jurnal.keterangan" type="textarea" placeholder="Isikan keterangan pelaksanaan" autosize :rows="3"></el-input>
                </el-form-item>
                <div class="text-center mb-4">
                    <el-button icon @click="showCamera = !showCamera" :type="showCamera ? 'danger': ''" text>
                        <h3 class="text-center text-xs " :class="showCamera ? 'text-red-500' :'text-sky-500'">{{ showCamera ? 'Tutup Kamera' : 'Buka Kamera' }}</h3>
                        <Icon :icon="`mdi:${showCamera ? 'camera-off' : 'camera'}`" class="ml-2 text-lg" :class="showCamera ? 'text-red-500' :'text-sky-500'" />
                    </el-button>
                </div>
                <WebCamUI :fullscreen="true" v-if="showCamera" class="transition-all duration-300 ease-in-out" @photoTaken="photoTaken" />
                <div class="photos">
                    <div class="columns-2">
                        <template v-for="foto in photos">
                            <el-image :src="foto" :preview-src-list="photos"></el-image>
                        </template>
                    </div>
                </div>
            </el-form>
        </el-scrollbar>
        <template #footer>
            <div class="flex justify-center">
                <el-button type="primary" @click="simpan" :loading="loading">Simpan</el-button>
            </div>
        </template>
    </el-card>
</el-dialog>
</template>