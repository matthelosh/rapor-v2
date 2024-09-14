<script setup>
import { Icon } from '@iconify/vue';
import { router, usePage } from '@inertiajs/vue3';
import { ElMessageBox, ElNotification } from 'element-plus';
import { ref, onBeforeMount, computed, reactive } from 'vue';
import dayjs from 'dayjs';
import 'dayjs/locale/id'
dayjs.locale('id')

const page = usePage()
const props = defineProps({asesmen: Object, siswa:Object, show: Boolean, proses: Object })
const emit = defineEmits(['close'])
const hasil = ref([])
const teliti = ref(false)
const mode = ref('list')
const skor = ref(0)
const showSide = ref(false)
const totalSkor = computed(() => {

    let total = 0 
    props.asesmen.soals.forEach((soal) => {
        total += (soal.level == 'hot' ? 3 : (soal.level == 'mot' ? 2 : 1))
    })
    return total
})
const minutes = ref(1)
const seconds = ref(60)
const runTimer = () => {
    setInterval(() => {
        if (durasi.seconds <= 0) {
            if (durasi.minutes === 0 && durasi.seconds === 0) {
                if (durasi.hours > 0 ) {
                    durasi.hours--
                    durasi.minutes = 60
                } else {
                    if (durasi.hours === 0 && durasi.minutes === 0 && durasi.seconds === 0) {
                        if (localStorage.getItem("durasi")) 
                            timesUp()
                    }
                }
            }
            if (durasi.minutes > 0) {
                durasi.minutes--
                durasi.seconds = 60
            }
        }
        if (durasi.seconds > 0) {
            durasi.seconds--
            localStorage.setItem('durasi', JSON.stringify(durasi))
        }
        }, 1000)
}

const durasi = reactive({
    totalMinutes: 0,
    hours: 0,
    minutes: 0,
    seconds: 0
})
const initDurasi = async() => {
    if (localStorage.getItem("durasi")) {
        const dur = JSON.parse(localStorage.getItem("durasi"))
        // durasi.totalMinutes = localStorage.getItem("hours")
        durasi.hours = dur.hours
        durasi.minutes = dur.minutes
        durasi.seconds = dur.seconds
        // durasi.minutes = localStorage.getItem("minutes")
    } else {
        const start = props.asesmen.proses?.jawabans.length > 0 ? dayjs(props.asesmen.proses.updated_at).locale('Asia/Jakarta') :dayjs(props.asesmen.mulai)
        const end = dayjs(props.asesmen.selesai)
        let diff = end.diff(start) / 1000
        let totalMinutes = diff / 60
        durasi.totalMinutes = totalMinutes
        durasi.hours = Math.floor(totalMinutes / 60)
        localStorage.setItem("hours", durasi.hours)
        durasi.minutes = Math.floor(totalMinutes % 60)
    }
    runTimer()
}
const timesUp = () => {
    clearInterval(runTImer)
    ElMessageBox.alert('Maaf! Waktu habis. Jawaban kamu akan dikirim,', 'Info', {
        confirmButtonText: 'Ya',
        callback: (action) => {
            kirim()
        }
    })
}

const currentCard = ref(0)

const kirim = async () => {
    hasil.value.forEach((has, h) => {
        // let benar = false
        let benar = has.teks == props.asesmen.soals[h].kunci
        has.is_benar = benar
        // console.log(benar)
        skor.value += benar ? (props.asesmen.soals[h].level == 'hot' ? 3 : (props.asesmen.soals[h].level == 'mot' ? 2 : 1)) : 0
    })

    ElMessageBox.alert('Selamat Skor Ananda '+((skor.value / totalSkor.value)*100), 'Skor')
}

const onStickyToolbar = (fixed) => {
    const toolbar  =document.querySelector('.toolbar')
    if (fixed) {
        toolbar.classList.add('sticky')
    } else {
        toolbar.classList.remove('sticky')

    }
}

const onJawab = (jawaban) => {
    const soal = props.asesmen.soals.find(soal => soal.id === jawaban.soal_id)
    jawaban.is_benar = soal.tipe == 'pilihan' ? (jawaban.teks == soal.kunci) : false
    // console.log(jawaban)
    router.post(route('asesmen.siswa.jawaban.savetemp'), jawaban, {
        onSuccess: () => {
            router.reload()
            ElNotification({ title: 'Info', message: page.props.flash.message, type: 'success'})
        }, onError: errs => console.log(errs)
    })
}
onBeforeMount(() => {
    props.asesmen.soals.forEach(soal => hasil.value.push({
        asesmen_id: props.asesmen.kode,
        siswa_id: props.siswa.nisn,
        soal_id: soal.id,
        teks: !props.asesmen.proses[0]?.jawabans ? '' : ( props.asesmen.proses[0].jawabans.find(j => j.soal_id == soal.id) ? props.asesmen.proses[0].jawabans.find(j => j.soal_id == soal.id).teks : ''),
        is_benar: false,
        proses_id: props.proses.id,
    }))

    initDurasi()
})
</script>

<template>
    <el-dialog fullscreen :show-close="false" v-model="props.show">
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Asesmen: {{ props.asesmen.nama }}</h3>
                <el-button @click="emit('close')" type="danger">
                    <Icon icon="mdi:close" />
                </el-button>
            </div>
            <el-affix :offset="0" @change="onStickyToolbar">
                <div class="toolbar flex items-center justify-between py-4 bg-white">
                    <Icon v-if="mode == 'card'" icon="mdi:list-box-outline" class="text-xl text-black" @click="mode = 'list'" />
                    <Icon v-if="mode == 'list'" icon="mdi:card-bulleted-outline" class="text-xl text-black" @click="mode = 'card'" />
                    <div class="flex items-center">
                        <h3>
                            Proses : {{ props.proses.id }} | 
                            Jumlah Soal: {{ props.asesmen.soals.length }} |
                            Waktu: <span class="font-bold">{{ durasi.hours }}:{{ durasi.minutes }}:{{ durasi.seconds }}</span>
                        </h3>
                    </div>
                    <Icon icon="hugeicons:menu-11" class="text-xl text-black hidden-md-and-up" @click="showSide = true" />
                </div>
            </el-affix>
        </template>
        <template #default>
            <el-card class="border rounded" shadow="never"  :body-style="{ minHeight: '70vh'}">
                <el-row :gutter="20">
                    <el-col :span="18" :xs="24">
                        <div v-if="mode == 'list'">
                            <el-divider>Pilihan Ganda</el-divider>
                            <h3 class="font-bold text-sky-700 mb-4">Pilih jawaban yang tepat!</h3>
                            <ul class="list-[numeric] pl-6" >
                                <template v-for="(soal, s) in props.asesmen.soals">
                                    <li class="mb-4">
                                        <div v-html="soal.pertanyaan"></div>
        
                                        <ul class="flex flex-wrap gap-2" v-if="soal.tipe == 'pilihan'">
                                            <li v-for="j in ['a','b','c','d']" class="flex items-center gap-2 justify-between m-2">
                                                <label class="flex gap-1 items-center">
                                                    <input type="radio"  v-model="hasil[s].teks" :value="j" @change="onJawab(hasil[s])">
                                                    </input>
                                                    <span v-html="soal[j]"></span>
        
                                                </label>
                                            </li>
                                        </ul>
                                        <el-input v-else autosize type="textarea" placeholder="Isikan jawabanmu" v-model="hasil[s].teks" @change="onJawab(hasil[s])"></el-input>
                                    </li>
                                </template>
                            </ul>
                        </div>
                        <div v-if="mode == 'card'">
                            <Transition name="soal" ease-in-out>
                                <el-card>
                                    <el-divider >
                                        <span class="font-bold text-sky-700 text-lg text-center border border-sky-700 py-1 px-4">
                                            No. {{ currentCard +1 }}
                                        </span>
                                    </el-divider>
                                    <p class="my-4 pt-4" v-html="props.asesmen.soals[currentCard].pertanyaan"></p>
                                    <span>
                                    <ul class="mt-4" v-if="props.asesmen.soals[currentCard].tipe == 'pilihan'">
                                        <li v-for="j in ['a','b','c','d']" class="flex items-center gap-2 justify-between m-2">
                                            <label class="flex gap-1 items-center">
                                                <input type="radio"  v-model="hasil[currentCard].teks" :value="j">
                                                </input>
                                                <span v-html="props.asesmen.soals[currentCard][j]"></span>
        
                                            </label>
                                        </li>
                                    </ul>
                                    <el-input autosize type="textarea" placeholder="Isikan jawabanmu" v-model="hasil[currentCard].teks" v-else></el-input>
                                </span>
                                    <template #footer>
                                        <div class="flex justify-between">
                                            <el-button @click="currentCard--" :disabled="currentCard === 0">
                                                <Icon icon="mdi:chevron-left" class="text-xl" />
                                            </el-button>
                                            <el-button @click="currentCard++" :disabled="currentCard === (props.asesmen.soals.length - 1)">
                                                <Icon icon="mdi:chevron-right" class="text-xl" />
                                            </el-button>
                                        </div>
                                    </template>
                                </el-card>
                            </Transition>
                        </div>
                    </el-col>
                    <el-col :span="6" class="hidden-sm-and-down">
                        <el-card>
                            <h3 class="font-bold text-sky-700 mb-1">Nomor Soal:</h3>
                            <template v-for="n of props.asesmen.soals.length">
                                <el-button-group>
                                    <el-button :type="hasil[n-1].teks !== '' ? 'primary' : ''" @click="currentCard = (n-1)">{{ n }}</el-button>
                                </el-button-group>
                            </template>
                        </el-card>
                    </el-col>
                </el-row>
                <template #footer>
                    <div class="flex justify-between">
                        <el-checkbox v-model="teliti">Sudah saya teliti</el-checkbox>
                        <el-button type="primary" :disabled="!teliti" @click="kirim">Kirim</el-button>
                    </div>
                </template>
            </el-card>
        </template>
    </el-dialog>
    <el-drawer v-model="showSide" size="60%" class="hidden-md-and-up">
        <h3 class="font-bold text-sky-700 mb-1">Nomor Soal:</h3>
        <template v-for="n of props.asesmen.soals.length">
            <el-button-group>
                <el-button :type="hasil[n-1].teks !== '' ? 'primary' : ''" @click="currentCard = (n-1)">{{ n }}</el-button>
            </el-button-group>
        </template>
    </el-drawer>
</template>

<style scoped>
.soal-leave-to {
    opacity: 0;
    transform: translateX(50px);
    transition: all .35s ease-out;
}

.sticky {
    /* background: rgb(192, 219, 255); */
    /* box-shadow: 0 3px 10px rgba(0,0,0,0.3) */
}

.el-dialog__header {
    padding:0!important;
    background: blue;
}

</style>