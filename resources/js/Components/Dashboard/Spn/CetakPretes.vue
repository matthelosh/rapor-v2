<script setup>
import { Icon } from '@iconify/vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import dayjs from 'dayjs';
import { defineAsyncComponent, onBeforeMount, onMounted, ref } from 'vue';

const Kop = defineAsyncComponent(() => import('@/Components/Dashboard/Kop.vue'))
const page = usePage()
const props = defineProps({rombel: Object, jilids: Array})
const emit = defineEmits(['close'])
const show = ref(false)
const siswas = ref([])
const loading = ref(false)

const getData = async() => {
    let datas = []
    loading.value = true
    axios.post(route('dashboard.spn.pretes.siswa', {_query: {rombelId: props.rombel.kode}}))
        .then(res => {
            res.data.siswas.forEach(siswa => {

                datas.push({
                    nisn: siswa.nisn,
                    nama: siswa.nama,
                    jilid: siswa.jilids[0]?.id ?? props.jilids.find(jilid => jilid.juz == 1).id
                })
            siswas.value = datas
            loading.value = false
        })
    })
}

const cetak = async() => {
    const el = document.querySelector(".cetak")

    let cssUrl = page.props.app_env == 'local' ? 'https://localhost:5173/resources/css/app.css' : `/build/assets/app.css`
    let html = `
        <!doctype html>
        <html>
            <head>
                <title>Jurnal SPN</title>  
                <link rel="stylesheet" href="${cssUrl}" />  
                <style>
                    table { border: 1px!important; border-collapse: collapse}
                </style>
            </head>
            <body>
                ${el.outerHTML}
            </body>
        </html>

    `
    let win = window.open("", "_blank", "width=800,height=1336")
    win.document.write(html)

    setTimeout(() => {
        win.print()
        // win.close()
    }, 1000)
}

onBeforeMount(() => {
    show.value = props.rombel !== null
    getData()
})

// onMounted(() => {
//     cetak()
// })
</script>

<template>
<el-dialog fullscreen :show-close="false" v-model="show">
    <template #header>
        <el-affix :offset="0">
            <div class="toolbar flex items-center justify-between">
                <h3>Rubrik Pretes SPN Kelas {{ props.rombel.label }}</h3>
                <div class="flex items-center">
                    <el-button type="primary" @click="cetak">Cetak</el-button>
                    <el-button circle type="danger" @click="emit('close')">
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </div>
        </el-affix>
    </template>

    <div class="cetak">
        <!-- <el-table :data="siswas" v-loading="loading" height="80vh" max-height="80vh" border>
            <el-table-column label="NISN" prop="nisn"></el-table-column>
            <el-table-column label="Nama" prop="nama"></el-table-column>
            <el-table-column v-for="jilid in props.jilids" :label="jilid.juz" width="100">
                <template #default="{row}">
                    <Icon v-if="row.jilid == jilid.id" icon="mdi:check-decagram" />
                </template>
            </el-table-column>
        </el-table> -->
        <Kop />
        <h3 class="text-center font-bold text-lg uppercase mt-4">Hasil Seleksi Pemetaan Jilid SPN</h3>
        <h3 class="text-center font-bold text-lg uppercase">Kelas {{ props.rombel.label }}</h3>
        <table class="border w-[75%] mx-auto mt-4">
            <thead>
                <tr>
                    <th class="border p-2 font-bold uppercase border-black">No</th>
                    <th class="border p-2 font-bold uppercase border-black">NISN</th>
                    <th class="border p-2 font-bold uppercase border-black">Nama</th>
                    <th class="border p-2 font-bold uppercase border-black" v-for="jilid in props.jilids">{{ jilid.juz }}</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(siswa, s) in siswas">
                    <tr>
                        <td class="border border-black text-center">{{ s+1 }}</td>
                        <td class="border border-black text-center">{{ siswa.nisn }}</td>
                        <td class="border border-black px-2">{{ siswa.nama }}</td>
                        <td class="border border-black text-center" v-for="jilid in props.jilids">
                            <Icon v-if="siswa.jilid == jilid.id" icon="mdi:check-decagram" class="mx-auto text-lg text-slate-600" />
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>

        <div class="grid grid-cols-3 mt-8">
            <div class="text-center">
                <p>Mengetahui,</p>
                <p>Kepala Sekolah</p>

                <p class="mt-14 underline font-bold"><span class="uppercase">{{ page.props.sekolahs[0].ks.nama }}</span>, {{ page.props.sekolahs[0].ks.gelar_belakang }}</p>
                <p class="leading-4">NIP. {{ page.props.sekolahs[0].ks.nip }}</p>
            </div>
            <div></div>
            <div class="text-center">
                <p>Dalisodo, {{ dayjs(new Date()).locale('id').format('DD MMMM YYYY') }}</p>
                <p>Guru PAI</p>

                <p class="mt-14 underline font-bold"><span class="uppercase">{{ page.props.auth.user.userable.nama }}</span>, {{ page.props.auth.user.userable.gelar_belakang }}</p>
                <p class="leading-4">NIP. {{ page.props.auth.user.userable.nip }}</p>
            </div>
        </div>
    </div>
</el-dialog>
</template>

<style scope>
.el-dialog__header {
    background: white;
}
.el-dialog__body {
    /* background: lime!important; */
    padding-top: 10px;
}

</style>