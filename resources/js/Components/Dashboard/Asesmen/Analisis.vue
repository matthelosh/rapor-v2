<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { router, usePage } from "@inertiajs/vue3"
import axios from 'axios'
import { utils, writeFile } from 'xlsx'

const page = usePage()
const props = defineProps({ asesmen: Object, open: Boolean });
const emit = defineEmits(["close"]);
const show = ref(false);
const loading = ref(false)
const rombel = ref({})
const siswas = ref([])

const kunci = ref({})
const analisis = ref([])
const getSiswa = () => {

    axios.get(
        route(
            "dashboard.rombel.show",
            {
                kode: props.asesmen.rombel_id ?? 'kosong',
                tingkat: props.asesmen.kelas
            }
        ))
        .then(res => {
            siswas.value = res.data.siswas
            let datas = []
            res.data.siswas.forEach((siswa,s) => {
                datas.push({
                    nisn: siswa.nisn,
                    nama: siswa.nama,
                    rombelId: props.asesmen.rombel_id,
                    pg: {
                        jawabans: [],
                        skor: 0
                    },
                    pgk: {
                        jawabans: [],
                        skor: 0,
                    },
                    ps: {
                        jawabans: [],
                        skor:0,
                    },
                    is: {
                        jawabans: [],
                        skor: 0,
                    },
                    ur: {
                        jawabans: [],
                        skor: 0,
                    }
                })
            })
            analisis.value = datas
        })
}
const skorPg = (s) => {
    let skor = 0
    for (let i=0; i < analisis.value[s].pg.jawabans.length; i++) {
        skor += analisis.value[s].pg.jawabans[i].toUpperCase() == kunci.value.pg.kunci[i].toUpperCase()
    }

    return skor
}

const skorPgk = (s) => {
    let skor = 0
    for ( let i = 0; i < analisis.value[s].pgk.jawabans.length; i++) {
        const kunciSet = new Set(kunci.value.pgk.kunci[i].toUpperCase())
        const jawab = new Set(analisis.value[s].pgk.jawabans[i].toUpperCase())
        skor += [...kunciSet].every(char => jawab.has(char)) ? 2 : ([...kunciSet].filter(char => jawab.has(char)).length === 1 ? 1 : 0);
    }

    return skor
}
const skorPs = (s) => {
    let skor = 0
    for ( let i = 0; i < analisis.value[s].ps.jawabans.length; i++) {
        skor +=  kunci.value.ps.kunci[i].toUpperCase() == analisis.value[s].ps.jawabans[i].toUpperCase() ? 2 : 0
    }

    return skor
}

// const skorBs = (s) => {
//     let skor = 0;
//     for ( let i = 0; i < analisis.value[s].bs.jawabans.length; i++) {
//         skor += kunci.value.bs.kunci[i].toUpperCase() == analisis.value[s].bs.jawabans[i].toUpperCase()
//     }
//     return skor;
// }

const skorIs = (s) => {
    let skor = 0;
    //console.log(analisis.value[s].is.jawabans.length);
    for (let i=0; i < analisis.value[s].is.jawabans.length; i++) {
        skor += parseFloat(analisis.value[s].is.jawabans[i])
        //console.log(analisis.value[s].is.jawabans[i])
    }

    return skor * 2
}

const skorUr = (s) => {
    let skor = 0;
    for (let i=0; i < analisis.value[s].ur.jawabans.length; i++) {
        skor += analisis.value[s].ur.jawabans[i]
    }

    return skor * 4

}
const skorTotal = (s) => {
    let total = 0
    total = skorPg(s) + skorPgk(s) + skorPs(s) + skorIs(s) + skorUr(s)
    return total
}

const cetak = () => {
    // window.open(`/cetak/analisis-asesmen/${props.asesmen.kode}`, "_blank", "width=900,height=800")

}

// Unduh Excel
const unduhExcel = () => {
    const headers = [
        "No", "Nama",
        ...kunci.value.pg.kunci.map((_, i) => `PG ${i + 1}`),
        "SUB PG",
        ...kunci.value.pgk.kunci.map((_, i) => `PGK ${i + 1}`),
        "SUB PGK",
        ...kunci.value.ps.kunci.map((_, i) => `PS ${i + 1}`),
        "SUB PS",
        ...kunci.value.is.kunci.map((_, i) => `IS ${i + 1}`),
        "SUB IS",
        ...kunci.value.ur.kunci.map((_, i) => `UR ${i + 1}`),
        "SUB UR",
        "TOTAL SKOR"
    ];

    const data = analisis.value.map((a, index) => {
        return [
            index + 1,
            a.nama,
            ...a.pg.jawabans,
            skorPg(index),
            ...a.pgk.jawabans,
            skorPgk(index),
            ...a.ps.jawabans,
            skorPs(index),
            ...a.is.jawabans,
            skorIs(index),
            ...a.ur.jawabans,
            skorUr(index),
            skorTotal(index)
        ];
    });

    const ws = utils.aoa_to_sheet([headers, ...data]);
    const wb = utils.book_new();
    utils.book_append_sheet(wb, ws, "Analisis");

    writeFile(wb, `Analisis-Asesmen-${props.asesmen.nama}.xlsx`);

}

const simpan = async() => {
    router.post(route('dashboard.analisis.store'), {
        asesmen_id: props.asesmen.kode,
        hasil: analisis.value
    }, {
            onStart: () => loading.value = true,
            onSuccess: () => {
                ElNotification({title: "Info", message: page.props.flash.message, type: "success"})
            },
            onFinish: () => loading.value = false
        })
}


onBeforeMount(() => {
    show.value = props.open;
    kunci.value = {
        pg: JSON.parse(props.asesmen.kunci.pg),
        pgk: JSON.parse(props.asesmen.kunci.pgk),
        ps: JSON.parse(props.asesmen.kunci.ps),
        is: JSON.parse(props.asesmen.kunci.is),
        ur: JSON.parse(props.asesmen.kunci.ur),

    }
    getSiswa()
});
</script>

<template>
    <el-dialog v-model="show" fullscreen @close="emit('close')">
        <template #header>
            <div>Analisis</div>
        </template>
        <div class="h-10 bg-slate-200 py-2 flex items-center justify-between">
            <el-button-group size="small">
                <el-button size="small" @click="unduhExcel">Export Excel</el-button>
                <el-button size="small" @click="cetak">Cetak</el-button>
                <el-button size="small" @click="simpan">Simpan Analisis</el-button>
                <el-button size="small">Simpan Nilai</el-button>
            </el-button-group>
        </div>
        <div class="max-w-screen overflow-x-auto max-h-[80vh] overflow-y-auto">
            <table>
                <thead>
                    <tr class="bg-sky-600 text-white">
                        <th class="border py-1 px-2">No</th><th class="border py-1 px-2">Nama</th>
                        <template v-for="(p, i) in kunci.pg.kunci">
                            <th class="border-b p-1 ">{{i+1}}</th>
                        </template>
                        <th class="border-b p-1">
                            SUB PG
                        </th>
                        <template v-for="(p, i) in kunci.pgk.kunci">
                            <th class="border-b p-1 ">{{i+1}}</th>
                        </template>
                        <th class="border-b p-1 ">
                            SUB PGK
                        </th>
                        <template v-for="(p, i) in kunci.ps.kunci">
                            <th class="border-b p-1 ">{{i+1}}</th>
                        </template>
                        <th class="border-b p-1 ">
                            SUB PS
                        </th>
                        <template v-for="(p, i) in kunci.is.kunci">
                            <th class="border-b p-1 ">{{i+1}}</th>
                        </template>
                        <th class="border-b p-1 ">
                            SUB Isian
                        </th>
                        <template v-for="(p, i) in kunci.ur.kunci">
                            <th class="border py-1 px-2">{{i+1}}</th>
                        </template>
                        <th class="border-b p-1 ">
                            SUB Uraian
                        </th>
                        <th class="border-b p-1 ">
                            SKOR
                        </th>


                    </tr>
                </thead>
                <tbody>
                    <template v-for="(siswa,s) in siswas">
                        <tr class="even:bg-slate-100">
                            <td class="border py-1 px-2 text-center">{{s+1}}</td>
                            <td class="border py-1 px-2">{{siswa.nama}}</td>
                            <template v-for="(pg, p) in kunci.pg.kunci">
                                <td class="border py-1 px-2">
                                    <input v-model="analisis[s]['pg']['jawabans'][p]" maxlength="1" required class="w-6 text-center p-0 border-t-0 border-r-0 border-l-0 border-b outline-none active:outline-none focus:outline-none focus:bg-sky-50" />
                                </td>
                            </template>
                            <td class="border py-1 px-2 text-center font-bold">
                                {{skorPg(s)}}
                            </td>
                             <template v-for="(pg, p) in kunci.pgk.kunci">
                                <td class="border py-1 px-2">
                                    <input v-model="analisis[s]['pgk']['jawabans'][p]" maxlength="2" required class="w-8 text-center p-0 border-t-0 border-r-0 border-l-0 border-b outline-none active:outline-none focus:outline-none focus:bg-sky-50" />
                                </td>
                            </template>
                            <td class="border py-1 px-2 text-center font-bold ">
                                {{skorPgk(s)}}
                            </td>
                            <template v-for="(ps, p) in kunci.ps.kunci">
                                <td class="border py-1 px-2">
                                    <input v-model="analisis[s]['ps']['jawabans'][p]" maxlength="2" required class="w-8 text-center p-0 border-t-0 border-r-0 border-l-0 border-b outline-none active:outline-none focus:outline-none focus:bg-sky-50" />
                                </td>
                            </template>
                            <td class="border py-1 px-2 text-center font-bold">
                                {{skorPs(s)}}
                            </td>

                            <template v-for="(ps, p) in kunci.is.kunci">
                                <td class="border py-1 px-2">
                                    <input v-model="analisis[s]['is']['jawabans'][p]" maxlength="2" required class="w-8 text-center p-0 border-t-0 border-r-0 border-l-0 border-b outline-none active:outline-none focus:outline-none focus:bg-sky-50" min="0" max="1" step="0.1" type="number" />
                                </td>
                            </template>
                            <td class="border py-1 px-2 text-center font-bold">
                                {{skorIs(s)}}
                            </td>

                            <template v-for="(ps, p) in kunci.ur.kunci">
                                <td class="border py-1 px-2">
                                    <input v-model="analisis[s]['ur']['jawabans'][p]" maxlength="2" required class="w-8 text-center p-0 border-t-0 border-r-0 border-l-0 border-b outline-none active:outline-none focus:outline-none focus:bg-sky-50" min="0" max="1" step="0.1" type="number" />
                                </td>
                            </template>
                             <td class="border py-1 px-2 text-center font-bold">
                                {{skorUr(s)}}
                            </td>
                            <td class="border py-1 px-2 text-center font-bold">
                                {{skorTotal(s)}}
                            </td>


                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </el-dialog>
</template>

<style scoped>
    /* input { */
    /*     padding: 2px; */
    /*     border: none; */
    /*     border-bottom: 1px dotted #789890; */
    /* } */
    /* input:focus { */
    /*     border: none; */
    /*     outline: none!important; */
    /* } */
    input[type="number"]::-webkit-outer-spin-button,
    input[type="number"]::-webkit-inner-spin-button {
        -webkit-appearance: none; /* Menghilangkan tampilan default browser */
        margin: 0; /* Penting: margin perlu diatur ke 0 untuk mencegah ruang kosong */
    }
</style>
