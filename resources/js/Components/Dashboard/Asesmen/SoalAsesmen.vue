<script setup>
import { Icon } from '@iconify/vue';
import { ElMessageBox } from 'element-plus';
import { ref, onBeforeMount } from 'vue';

const props = defineProps({asesmen: Object, siswa:Object, show: Boolean})
const emit = defineEmits(['close'])
const hasil = ref([])
const teliti = ref(false)
const skor = ref(0)

const kirim = async () => {
    hasil.value.forEach((has, h) => {
        // let benar = false
        let benar = has.teks == props.asesmen.soals[h].kunci
        has.is_benar = benar
        // console.log(benar)
        skor.value += benar ? 1 : 0
    })

    ElMessageBox.alert('Selamat Skor Ananda '+skor.value, 'Skor')
}
onBeforeMount(() => {
    props.asesmen.soals.forEach(soal => hasil.value.push({
        asesmen_id: props.asesmen.kode,
        siswa_id: props.siswa.nisn,
        soal_id: soal.id,
        teks: '',
        is_benar: false,
        proses_id: null,
    }))
})
</script>

<template>
    <el-dialog fullscreen :show-close="false" v-model="props.show">
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Asesmen: {{ props.asesmen.nama }}</h3>
                <el-button @click="emit('close')">
                    <Icon icon="mdi:close" />
                </el-button>
            </div>
        </template>
        <template #default>
            <el-affix :offset="0">
                <div class="toolbar flex items-center justify-between py-4 bg-white">
                    <h3>Jumlah Soal: {{ props.asesmen.soals.length }}</h3>
                    <Icon icon="hugeicons:menu-11" class="text-xl text-black" />
                </div>
            </el-affix>
            <el-card class="border rounded p-2" shadow="never">
                <ul class="list-[numeric] pl-4">
                    <template v-for="(soal, s) in props.asesmen.soals">
                        <li>
                            <div v-html="soal.pertanyaan"></div>

                            <ul class="flex flex-wrap">
                                <li v-for="j in ['a','b','c','d']" class="flex items-center gap-2 justify-between m-2">
                                <!-- <el-radio-group v-model="hasil[s].jawaban"> -->
                                    <input type="radio"  v-model="hasil[s].teks" :value="j">
                                    </input>
                                    <span v-html="soal[j]"></span>
                                <!-- </el-radio-group> -->
                                </li>
                            </ul>
                        </li>
                    </template>
                </ul>
                <template #footer>
                    <div class="flex justify-between">
                        <el-checkbox v-model="teliti">Sudah saya teliti</el-checkbox>
                        <el-button type="primary" :disabled="!teliti" @click="kirim">Selesai</el-button>
                    </div>
                </template>
            </el-card>
        </template>
        
    </el-dialog>
</template>