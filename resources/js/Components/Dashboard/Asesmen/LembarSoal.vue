<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue';
import dayjs from 'dayjs';
import 'dayjs/locale/id'
dayjs.locale('id')


const page = usePage()
const props = defineProps({ selectedAsesmen: Object })
const emit = defineEmits(['close'])
const show = ref(false)
const soals = ref([])

import Kop from '@/Components/Umum/Kop.vue';

const allSoals = ref([])
const getAllSoals = async () => {
    await axios.get(route('dashboard.soal.all', {
        tingkat: props.selectedAsesmen.rombel.tingkat,
        mapel_id: props.selectedAsesmen.mapel_id,
        agama: props.selectedAsesmen.mapel_id == 'pabp' ? page.props.auth.user.userable.agama : null,
        asesmen_id : props.selectedAsesmen.id
    })).then(res => {
        allSoals.value = res.data.soals
    })
}

const cetakLembarSoal = async () => {
    let win = window.open("", "_blank", "width=1024,height=1080")
    const cssUrl = page.props.app_env == 'local' ? 'http://localhost:5173/resources/css/app.css' : '/assets/css/app.css'
    let elemen = document.querySelector(".cetak")

    let html = `
        <!doctype html>
        <html>
            <head>
                <title>Lembar Soal ${props.selectedAsesmen.nama}</title>   
                <link href="${cssUrl}" rel="stylesheet" />
            </head>

            <body>
                ${elemen.outerHTML}
            </body>
        </html>    
    `
    win.document.write(html)
    setTimeout(() => {
        win.print()
        win.close()
    }, 1000)
}

const drag = (ev, item) => {
    // ev.dataTransfer.set("")
    // console.log(ev.target)
    ev.dataTransfer.dropEffect = 'move'
    ev.dataTransfer.effectAllowed = 'move'
    ev.dataTransfer.setData('itemId', item.id)
}
const dragOver = (ev) => {
    const dropZone = document.querySelector(".drop-zone")
    dropZone.classList.toggle("bg-sky-100")
}
const drop = (ev) => {
    const itemID = ev.dataTransfer.getData('itemID')
    const item = allSoals.value.find(soal => soal.id == itemID)

    // console.log(item)
    soals.value.push(item)
    router.post(route('dashboard.asesmen.soal.attach', {id: props.selectedAsesmen.id}), 
                {soalId: item.id}, {
                    onFinish: () => router.reload({only: ['asesmens'], preserveState: true})
                })
    const dropZone = document.querySelector(".drop-zone")
    dropZone.classList.toggle("bg-sky-100")
}

onBeforeMount(() => {
    show.value = props.selectedAsesmen !== null
    getAllSoals()
    soals.value = props.selectedAsesmen.soals
})
</script>

<template>
    <el-dialog v-model="show" fullscreen :show-close="false">
        <template #header>
            <div class="flex items-center h-full w-full justify-between">
                <h3>Lembar Soal Asesmen: {{ props.selectedAsesmen.nama }}</h3>
                <div class="flex items-center">
                    <el-button class="mr-1" type="primary" @click="cetakLembarSoal">
                        <Icon icon="mdi-printer" class="mr-1" />
                        Cetak
                    </el-button>
                    <el-button circle type="danger" @click="emit('close')">
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </div>
        </template>
        <template #default>
            <el-row :gutter="20">
                <el-col :span="16">
                    <div class="cetak">
                        <el-card class="mb-4 break-after-page">
                            <div class="text-black">
                                <div class="soal">
                                    <Kop />
                                    <h3 class="text-center text-lg print:text-md print:leading-5 font-bold font-serif mt-8">Lembar Soal {{ props.selectedAsesmen.nama }}</h3>
                                    <h3 class="text-center text-lg print:text-md print:leading-5 font-bold font-serif">Semester {{ props.selectedAsesmen.semester.label }} {{ props.selectedAsesmen.tapel.deskripsi }}</h3>

                                    <table class="w-[40%] print:w-[60%] border mx-auto my-4">
                                        <tr>
                                            <td>Mata Pelajaran</td>
                                            <td>:</td>
                                            <td>{{ props.selectedAsesmen.mapel.label }}</td>
                                        </tr>
                                        <tr>
                                            <td>Kelas</td>
                                            <td>:</td>
                                            <td>{{ props.selectedAsesmen.rombel.label }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tanggal</td>
                                            <td>:</td>
                                            <td>{{ dayjs(props.selectedAsesmen.tanggal).format('DD MMM YYYY')}} </td>
                                        </tr>
                                        <tr>
                                            <td>Alokasi Waktu</td>
                                            <td>:</td>
                                            <td>{{ props.selectedAsesmen.mulai }} s/d {{ props.selectedAsesmen.selesai }}</td>
                                        </tr>
                                        <tr>
                                            <td>Jumlah Soal</td>
                                            <td>:</td>
                                            <td>{{ props.selectedAsesmen.soals.length }} Butir</td>
                                        </tr>
                                    </table>

                                    <p class="italic mb-4 mt-8 text-md font-serif">Petunjuk: Pilih jawaban yang benar!</p>
                                    <div class="drop-zone" @drop="drop($event)" @dragover.prevent @dragenter.prevent="dragOver($event)">
                                        <template v-for="(soal, s) in props.selectedAsesmen.soals">
                                            <ul class="tes">
                                                <li class="mb-4">
                                                    <div class="flex gap-2">
                                                        {{ s+1 }}. 
                                                        <span v-html="soal.pertanyaan"></span>
                                                    </div>
                                                    <!-- <div class="flex">
                                                        <el-radio :value="'a'">
                                                            <span v-html="soal.a"></span>
                                                        </el-radio>
                                                        <el-radio :value="'b'">
                                                            <span v-html="soal.b"></span>
                                                        </el-radio>
                                                        <el-radio :value="'c'">
                                                            <span v-html="soal.c"></span>
                                                        </el-radio>
                                                        <el-radio :value="'d'">
                                                            <span v-html="soal.d"></span>
                                                        </el-radio>
                                                    </div> -->
                                                    <span class="flex gap-6 ml-4">
                                                        <div class="flex gap-1">a. <span v-html="soal.a"></span></div>
                                                        <div class="flex gap-1">b. <span v-html="soal.b"></span></div>
                                                        <div class="flex gap-1">c. <span v-html="soal.c"></span></div>
                                                        <div class="flex gap-1">d. <span v-html="soal.d"></span></div>
                                                    </span>
                                                </li>
                                            </ul>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </el-card>
                        <el-card class="mb-4 break-after-page">
                            <h3>Kunci Jawaban {{ props.selectedAsesmen.nama }}</h3>
                            <div class="columns-4">
                                <div v-for="(soal,s) in props.selectedAsesmen.soals">
                                    {{ s+1 }}. {{ soal.kunci }}
                                </div>
                            </div>

                        </el-card>
                        <el-card class="mb-4 break-after-page relative">
                            <Kop />
                            
                            <h3 class="text-center uppercase mt-4 font-bold">Lembar Jawaban</h3>
                            <h3 class="text-center uppercase font-bold">Asesmen: {{ props.selectedAsesmen.nama }} Semester {{ props.selectedAsesmen.semester.label }} {{ props.selectedAsesmen.tapel.deskripsi }}</h3>
                            <h3 class="text-center uppercase font-bold">Mata Pelajaran: {{ props.selectedAsesmen.mapel.label }} </h3>
                            <div class="flex justify-between mt-4">
                                <table>
                                    <tr>
                                        <td class="py-1">No. Absen</td>
                                        <td class="py-1">:</td>
                                        <td class="py-1">....................</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">NISN</td>
                                        <td class="py-1">:</td>
                                        <td class="py-1">..............................................</td>
                                    </tr>
                                    <tr>
                                        <td class="py-1">Nama Siswa</td>
                                        <td class="py-1">:</td>
                                        <td class="py-1">..............................................</td>
                                    </tr>
                                </table>
                                <div class="nilai w-[300px] grid grid-cols-2 h-[100px]">
                                    <div class="border border-black skor col-span-1">
                                        <h3 class="text-center bg-slate-800 text-white">SKOR</h3>
                                    </div>
                                    <div class="border border-black ttd col-span-1">
                                        <h3 class="text-center bg-slate-800 text-white">TTD</h3>
                                    </div>
                                </div>
                            </div>
                            <h3 class="text-center bg-slate-500 uppercase text-white py-2 font-bold mt-2">Jawaban</h3>
                            <p class="text-center">Beri tanda silang (X) pada pilihan jawaban yang benar!</p>
                            <div class="grid grid-cols-4 gap-6">
                                <div>
                                    <table class="border w-full">
                                        <thead>
                                            <tr>
                                                <th class="bg-slate-400 border border-black print:w-[50px] w-[80px]">No.</th>
                                                <th class="bg-slate-400 border border-black text-center" colspan="4">Pilhan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="i of 5">
                                                <td class="border border-black text-center bg-slate-300">{{ i }}.</td>
                                                <td class="border border-black text-center">a</td>
                                                <td class="border border-black text-center">b</td>
                                                <td class="border border-black text-center">c</td>
                                                <td class="border border-black text-center">d</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <table class="border w-full">
                                        <thead>
                                            <tr>
                                                <th class="bg-slate-400 border border-black print:w-[50px] w-[80px]">No.</th>
                                                <th class="bg-slate-400 border border-black text-center" colspan="4">Pilhan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="i of 5">
                                                <td class="border border-black text-center bg-slate-300">{{ i+5 }}.</td>
                                                <td class="border border-black text-center">a</td>
                                                <td class="border border-black text-center">b</td>
                                                <td class="border border-black text-center">c</td>
                                                <td class="border border-black text-center">d</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <table class="border w-full">
                                        <thead>
                                            <tr>
                                                <th class="bg-slate-400 border border-black print:w-[50px] w-[80px]">No.</th>
                                                <th class="bg-slate-400 border border-black text-center" colspan="4">Pilhan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="i of 5">
                                                <td class="border border-black text-center bg-slate-300">{{ i+10 }}.</td>
                                                <td class="border border-black text-center">a</td>
                                                <td class="border border-black text-center">b</td>
                                                <td class="border border-black text-center">c</td>
                                                <td class="border border-black text-center">d</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div>
                                    <table class="border w-full">
                                        <thead>
                                            <tr>
                                                <th class="bg-slate-400 border border-black print:w-[50px] w-[80px]">No.</th>
                                                <th class="bg-slate-400 border border-black text-center" colspan="4">Pilhan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="i of 5">
                                                <td class="border border-black text-center bg-slate-300">{{ i+15 }}.</td>
                                                <td class="border border-black text-center">a</td>
                                                <td class="border border-black text-center">b</td>
                                                <td class="border border-black text-center">c</td>
                                                <td class="border border-black text-center">d</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <h3 class="text-center bg-slate-500 uppercase text-white py-2 font-bold mt-6 my-4">Jawaban Isian</h3>

                            <ul class="list-decimal list-inside">
                                <li v-for="item of 5" class="w-full border-b border-b-slate-600 border-dotted mb-4">
                                </li>
                            </ul>

                            <h3 class="text-center bg-slate-500 uppercase text-white py-2 font-bold mt-8">Jawaban Uraian (Essay)</h3>
                            <ul class="list-decimal list-inside mt-10">
                                <li v-for="item of 5" class="w-full border-b border-b-slate-600 border-dotted mb-4">
                                </li>
                            </ul>
                        </el-card>
                    </div>
                </el-col>
                <el-col :span="8">
                    <el-affix :offset="70">
                        <el-card>
                            <h3 class="font-black text-sky-800">Bank Soal Kelas {{ props.selectedAsesmen.rombel.tingkat }}</h3>

                            <ul>
                                <li v-for="(soal, s) in allSoals" class="flex gap-1 mb-1 py-1 cursor-pointer hover:bg-sky-50" draggable="true" @dragstart="drag($event, soal)">
                                    {{ s+1 }}. 
                                    <span v-html="soal.pertanyaan"></span>
                                </li>
                            </ul>
                        </el-card>
                    </el-affix>
                </el-col>
            </el-row>
        </template>
    </el-dialog>
</template>

<style>
header.el-dialog__header {
    background: rgb(249, 251, 252);
    position: sticky;
    top: 0;
    box-shadow: 0 3px 10px rgba(0,0,0,0.3);
    z-index: 9999;
}
</style>