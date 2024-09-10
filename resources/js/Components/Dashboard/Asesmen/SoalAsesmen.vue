<script setup>
import { Icon } from '@iconify/vue';
import { ElMessageBox } from 'element-plus';
import { ref, onBeforeMount, computed } from 'vue';

const props = defineProps({asesmen: Object, siswa:Object, show: Boolean})
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
let runTImer = setInterval(() => {
    if (seconds.value <= 0) {
        if (minutes.value === 0 && seconds.value === 0) {
            timesUp()
        }
        minutes.value--
        seconds.value = 60
    }
    seconds.value--
    }, 1000)


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
onBeforeMount(() => {
    props.asesmen.soals.forEach(soal => hasil.value.push({
        asesmen_id: props.asesmen.kode,
        siswa_id: props.siswa.nisn,
        soal_id: soal.id,
        teks: '',
        is_benar: false,
        proses_id: null,
    }))

    // runTImer()
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
                    <Icon v-if="mode == 'card'" icon="mdi:list-box-outline" class="text-xl text-black" @click="mode = 'list'" />
                    <Icon v-if="mode == 'list'" icon="mdi:card-bulleted-outline" class="text-xl text-black" @click="mode = 'card'" />
                    <div class="flex items-center">
                        <h3>
                            Jumlah Soal: {{ props.asesmen.soals.length }} |
                            {{ minutes }}:{{ seconds }}
                        </h3>
                    </div>
                    <Icon icon="hugeicons:menu-11" class="text-xl text-black" @click="showSide = true" />
                </div>
            </el-affix>
            <el-card class="border rounded" shadow="never"  :body-style="{padding: 0, minHeight: '70vh'}">
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
                                            <input type="radio"  v-model="hasil[s].teks" :value="j">
                                            </input>
                                            <span v-html="soal[j]"></span>

                                        </label>
                                    </li>
                                </ul>
                                <el-input v-else autosize type="textarea" placeholder="Isikan jawabanmu" v-model="hasil[s].teks"></el-input>
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
                <template #footer>
                    <div class="flex justify-between">
                        <el-checkbox v-model="teliti">Sudah saya teliti</el-checkbox>
                        <el-button type="primary" :disabled="!teliti" @click="kirim">Kirim</el-button>
                    </div>
                </template>
            </el-card>
        </template>
    </el-dialog>
    <el-drawer v-model="showSide" size="60%">
        <h3 class="font-bold text-sky-700 mb-1">Nomor Soal:</h3>
        <template v-for="n of props.asesmen.soals.length">
            <el-button-group>
                <el-button :type="hasil[n-1].teks !== '' ? 'primary' : ''" @click="currentCard = (n-1)">{{ n }}</el-button>
            </el-button-group>
        </template>
    </el-drawer>
</template>

<style scope>
.soal-leave-to {
    opacity: 0;
    transform: translateX(50px);
    transition: all .35s ease-out;
}
</style>