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
                    <h3>Jumlah Soal: {{ props.asesmen.soals.length }} </h3>
                    <Icon icon="hugeicons:menu-11" class="text-xl text-black" @click="showSide = true" />
                </div>
            </el-affix>
            <el-card class="border rounded p-2" shadow="never">
                <ul class="list-[numeric] pl-4" v-if="mode == 'list'">
                    <template v-for="(soal, s) in props.asesmen.soals">
                        <li>
                            <!-- {{ soal.level }} -->
                            <div v-html="soal.pertanyaan"></div>

                            <ul class="flex flex-wrap gap-2">
                                <li v-for="j in ['a','b','c','d']" class="flex items-center gap-2 justify-between m-2">
                                <!-- <el-radio-group v-model="hasil[s].jawaban"> -->
                                    <label class="flex gap-1 items-center">
                                        <input type="radio"  v-model="hasil[s].teks" :value="j">
                                        </input>
                                        <span v-html="soal[j]"></span>

                                    </label>
                                <!-- </el-radio-group> -->
                                </li>
                            </ul>
                        </li>
                    </template>
                </ul>
                <div v-if="mode == 'card'">
                    <Transition name="soal" ease-in-out>
                        <el-card>
                            <p>No. {{ currentCard +1 }}</p>
                            <p v-html="props.asesmen.soals[currentCard].pertanyaan"></p>
                            <ul class="mt-4">
                                <li v-for="j in ['a','b','c','d']" class="flex items-center gap-2 justify-between m-2">
                                <!-- <el-radio-group v-model="hasil[s].jawaban"> -->
                                    <label class="flex gap-1 items-center">
                                        <input type="radio"  v-model="hasil[currentCard].teks" :value="j">
                                        </input>
                                        <span v-html="props.asesmen.soals[currentCard][j]"></span>

                                    </label>
                                <!-- </el-radio-group> -->
                                </li>
                            </ul>
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