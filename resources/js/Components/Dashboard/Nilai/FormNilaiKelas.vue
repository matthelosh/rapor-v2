<script setup>
import { ref, computed, onBeforeMount, onMounted, markRaw } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { ElDialog, ElNotification } from "element-plus";
import { Warning } from "@element-plus/icons-vue";
import { Icon } from "@iconify/vue";
import axios from "axios";
import { read, utils, writeFile } from "xlsx";
const page = usePage();

const props = defineProps({
    rombel: Object,
    open: Boolean,
    sekolah: Object,
    mapel: Object,
    agama: String,
});
const emit = defineEmits(["close"]);
const role = page.props.auth.roles[0];

const siswas = ref([]);
const loading = ref(false);

const tps = ref([]);

const simpan = async () => {
    // console.log(siswas.value);
    router.post(
        route("dashboard.nilai.store", {
            _query: {
                rombelId: props.rombel.kode,
                tingkat: props.rombel.tingkat,
                mapelId: props.mapel?.kode,
                agama: props.agama,
                semester:
                    route().params.semester ?? page.props.periode.semester.kode,
                tapel: page.props.periode.tapel.kode,
                tipe: "all",
            },
        }),
        { siswas: siswas.value },
        {
            onStart: () => (loading.value = true),
            onSuccess: (page) => {
                router.reload({ only: ["nilais"] });
                ElNotification({
                    title: "Info",
                    message: page.props.flash.message,
                    type: "success",
                });
                // setTimeout(() => {
                //     emit("close");
                // }, 500);
            },
            onError: (errs) => {
                // console.log(errs)
                Object.keys(errs).forEach((k) => {
                    setTimeout(() => {
                        ElNotification({
                            title: "Error",
                            message: errs[k],
                            type: "error",
                        });
                    }, 500);
                });
            },
            onFinish: () => (loading.value = false),
        },
    );
};

const getTps = async () => {
    await axios
        .post(
            route("dashboard.pembelajaran.tp.index", {
                _query: {
                    tingkat: props.rombel.tingkat,
                    mapelId: props.mapel?.kode,
                    agama: props.agama,
                    semester:
                        route().params.semester ??
                        page.props.periode.semester.kode,
                },
            }),
        )
        .then((res) => {
            tps.value = res.data.tps;
            if (res.data.tps.length < 1) {
                ElMessageBox.alert(
                    `Isi dulu TP Mapel ${props.mapel.label} Semester ${page.props.periode.semester.label}`,
                    "Peringatan",
                    {
                        type: "warning",
                        icon: markRaw(Warning),
                        confirmButton: "Ya",
                        callback: () => {
                            router.visit("/dashboard/pembelajaran");
                        },
                    },
                );
            } else {
                // loading.value = false;
                let raws = [];
                const filteredSiswas = props.agama
                    ? props.rombel.siswas.filter(
                          (siswa) => siswa.agama == props.agama,
                      )
                    : props.rombel.siswas;
                filteredSiswas.forEach((siswa, s) => {
                    let ns = {};
                    tps.value.forEach((tp, t) => (ns[tp.kode] = 0));
                    ns["ts"] = 0;
                    ns["as"] = 0;
                    raws.push({
                        id: siswa.id,
                        nisn: siswa.nisn,
                        nama: siswa.nama,
                        nilais: ns,
                        jk: siswa.jk,
                        agama: siswa.agama,
                    });
                });
                siswas.value = raws.sort((a, b) =>
                    a.nama.localeCompare(b.nama),
                );
            }
        })
        .finally(() => (loading.value = false));
};

const params = route().params;

const getNilaiUh = async () => {
    await axios
        .post(
            route("dashboard.nilai.index", {
                _query: {
                    rombelId: props.rombel.kode,
                    tingkat: props.rombel.tingkat,
                    mapelId: props.mapel?.kode,
                    agama: props.agama,
                    semester:
                        params.semester ?? page.props.periode.semester.kode,
                    tapel: page.props.periode.tapel.kode,
                    tipe: "uh",
                },
            }),
        )
        .then((res) => {
            if (res.data.length > 0) {
                siswas.value.forEach((siswa) => {
                    res.data
                        .filter((nilai) => nilai.siswa_id == siswa.nisn)
                        .forEach((n) => {
                            // console.log(n)
                            siswa.nilais[n.tp_id] = n.skor;
                        });
                });
            }
        });
};

const getNilaiAs = async () => {
    await axios
        .post(
            route("dashboard.nilai.index", {
                _query: {
                    rombelId: props.rombel.kode,
                    tingkat: props.rombel.tingkat,
                    mapelId: props.mapel?.kode,
                    agama: props.agama,
                    semester:
                        params.semester ?? page.props.periode.semester.kode,
                    tapel: page.props.periode.tapel.kode,
                    tipe: "as",
                },
            }),
        )
        .then((res) => {
            if (res.data.length > 0) {
                siswas.value.forEach((siswa) => {
                    res.data
                        .filter((nilai) => nilai.siswa_id == siswa.nisn)
                        .forEach((n) => {
                            // console.log(n)
                            siswa.nilais["as"] = n.skor;
                        });
                });
            }
        });
};

const getNilaiTs = async () => {
    await axios
        .post(
            route("dashboard.nilai.index", {
                _query: {
                    rombelId: props.rombel.kode,
                    tingkat: props.rombel.tingkat,
                    mapelId: props.mapel?.kode,
                    agama: props.agama,
                    semester:
                        params.semester ?? page.props.periode.semester.kode,
                    tapel: page.props.periode.tapel.kode,
                    tipe: "ts",
                },
            }),
        )
        .then((res) => {
            if (res.data.length > 0) {
                siswas.value.forEach((siswa) => {
                    res.data
                        .filter((nilai) => nilai.siswa_id == siswa.nisn)
                        .forEach((n) => {
                            // console.log(n)
                            siswa.nilais["ts"] = n.skor;
                        });
                });
            }
        });
};

const onFileNilaiPicked = async (e) => {
    const file = e.target.files[0];
    const ab = await file.arrayBuffer();

    const wb = read(ab);

    const ws = wb.Sheets[wb.SheetNames[0]];
    if (wb.SheetNames[0] !== props.mapel.kode) {
        ElNotification({
            title: "Error! Mapel Tidak Sesuai",
            message: "Mapel Nilai yang Anda impor tidak sesuai",
            type: "error",
        });
        return false;
    }
    const datas = utils.sheet_to_json(ws);
    // console.log(datas)
    siswas.value.forEach((siswa) => {
        datas.forEach((data) => {
            if (siswa.nisn == data.nisn) {
                Object.keys(data).forEach((k) => {
                    if (!["no", "nisn", "nama", "jk", "agama"].includes(k)) {
                        siswa.nilais[k] = data[k];
                        // console.log(k)
                    }
                });
            }
        });
    });
};

const unduhFormat = async () => {
    let data = [];
    siswas.value.forEach((siswa) => {
        let item = {
            nisn: siswa.nisn,
            nama: siswa.nama,
            jk: siswa.jk,
            agama: siswa.agama,
        };
        Object.keys(siswa.nilais).forEach((k) => {
            item[k] = siswa.nilais[k];
        });
        item.ts = siswa.nilais["ts"];
        item.as = siswa.nilais["as"];
        data.push(item);
        // console.log(siswa.nilais)
    });
    const ws = utils.json_to_sheet(data);
    const wb = utils.book_new();
    utils.book_append_sheet(wb, ws, props.mapel.kode);
    writeFile(
        wb,
        "Impor Nilai Harian " +
            props.mapel.label +
            " Kelas " +
            props.rombel.label +
            " Semester " +
            page.props.periode.semester.label +
            " " +
            page.props.periode.tapel.label +
            ".xlsx",
    );
};

// onMounted(() => {
//     if (tps.value.length < 1) ElMessageBox.alert(
//         `Isi dulu TP untuk mapel ${props.mapel.label} semester ${page.props.periode.semester.label}`,
//         'Peringatan'
//     )
// })
const hapusNilai = (jenis, tpId = null) => {
    const elloading1 = ElLoading.service({
                text: "Loading...",
                fullscreen: true,
                background: "rgba(0, 0, 0, 0.7)",
            });
    ElMessageBox.confirm(
        `Yakin hapus nilai ${jenis} untuk mapel ${props.mapel.label} kelas ${props.rombel.label} semester ${page.props.periode.semester.label}`,
        "Peringatan",
        {
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal",
            type: "warning",
        },
    )
        .then(() => {
            const elloading = ElLoading.service({
                text: "Loading...",
                fullscreen: true,
                background: "rgba(0, 0, 0, 0.7)",
            });
            router.post(route('dashboard.nilai.hapus.bulk', {
                rombelId: props.rombel.kode,
                mapelId: props.mapel.kode,
                jenis: jenis,
            }), 
            { tpIp: tpId }, 
            {
                 onStart: () => {
                            // elloading.start();
                        },
                        onSuccess: () => {
                            ElMessage({
                                type: "success",
                                message: "Nilai berhasil dihapus",
                            });
                        },
                        onFinish: () => {
                            elloading.close();
                        },
            })
        })
        .catch((err) => {
            console.log(err)
            ElMessage({
                type: "info",
                message: "Hapus nilai dibatalkan",
            });
        })
        .finally(() => elloading1.close());
};

const nilaiAkhir = (indexSiswa) => {
    const { ts, as, ...rest } = siswas.value[indexSiswa].nilais;
    let notNull = [];
    Object.values(rest).forEach((v) => {
        if (v !== 0) {
            notNull.push(v);
        }
    });
    const avgUh = notNull.reduce((acc, cur) => acc + cur, 0) / notNull.length;

    // return Object.values(rest).reduce((acc, cur) => acc + cur, 0);
    return Math.ceil((avgUh + as) / 2);
};

onBeforeMount(async () => {
    loading.value = true;
    await getTps();
    await getNilaiUh();
    await getNilaiTs();
    await getNilaiAs();
});
</script>

<template>
    <el-dialog
        v-model="props.open"
        fullscreen
        @close="emit('close')"
        :show-close="false"
        style="background: #efefef"
    >
        <template #header="{ close, titleId, titleClass }">
            <div class="uppercase border-b bg-sky-100 py-1 px-2 shadow-lg">
                <span class="flex items-center justify-between">
                    <div>
                        <p>
                            Nilai Harian
                            {{
                                props.mapel != "pabp" && !props.agama
                                    ? props.mapel.label
                                    : `PENDIDIKAN AGAMA ${props.agama}`
                            }}
                        </p>
                        <p>
                            {{ props.rombel.label }}
                            <span>{{
                                // role !== "guru_kelas"
                                //     ? props.sekolah.nama
                                //     : page.props.sekolahs[0].nama
                                // role +
                                // " | " +
                                page.props.datas["sekolah"][0]["nama"]
                            }}</span>
                        </p>
                    </div>
                    <div class="items flex items-center gap-6">
                        <input
                            type="file"
                            ref="fileNilai"
                            accept=".xls,.xlsx,.ods"
                            @change="onFileNilaiPicked"
                            class="hidden"
                        />
                        <el-button-group>
                            <el-button
                                type="success"
                                size="small"
                                @click="unduhFormat"
                                :disabled="loading"
                                >Unduh Format</el-button
                            >
                            <el-button
                                type="warning"
                                size="small"
                                :disabled="loading"
                                @click="$refs.fileNilai.click()"
                                >Impor</el-button
                            >
                            <el-button
                                type="primary"
                                size="small"
                                :disabled="loading"
                                @click="simpan"
                                >Simpan</el-button
                            >
                        </el-button-group>
                        <el-button type="danger" @click="close" size="small">
                            <Icon icon="mdi:close" />
                        </el-button>
                    </div>
                </span>
            </div>
        </template>
        <template #default>
            <!-- <el-scrollbar max-height="90vh"> -->
            <el-card
                class="dialog-content"
                style="border-radius: 10px; max-height: 90vh; overflow-y: auto"
            >
                <div class="w-full overflow-x-auto">
                    <table class="w-full">
                        <thead class="sticky top-0 bg-white z-30">
                            <tr class="">
                                <th
                                    class="border-e border-b border-b-double p-2 w-[80px]"
                                    rowspan="2"
                                >
                                    No
                                </th>
                                <th
                                    class="border-e border-b border-b-double p-2 w-[200px]"
                                    rowspan="2"
                                >
                                    NISN
                                </th>
                                <th
                                    class="border-e border-b border-b-double p-2"
                                    rowspan="2"
                                >
                                    Nama
                                </th>
                                <th
                                    class="border-e border-b border-b-double p-2 w-[200px]"
                                    rowspan="2"
                                >
                                    JK
                                </th>
                                <th
                                    v-if="tps.length > 0"
                                    class="border-e border-b p-2"
                                    :colspan="tps.length"
                                >
                                    Nilai Harian
                                </th>
                                <th
                                    class="border-e border-b p-2 w-[100px]"
                                    rowspan="2"
                                >
                                    Nilai PTS

                                    <el-button
                                        size="small"
                                        :native-type="null"
                                        type="danger"
                                        @click="hapusNilai('ts')"
                                        >Hapus</el-button
                                    >
                                </th>
                                <th
                                    class="end border-b p-2 w-[100px]"
                                    rowspan="2"
                                >
                                    Nilai PAS
                                    <el-button
                                        size="small"
                                        :native-type="null"
                                        type="danger"
                                        @click="hapusNilai('as')"
                                        >Hapus</el-button
                                    >
                                </th>
                                <th
                                    class="end border-b p-2 w-[100px]"
                                    rowspan="2"
                                >
                                    N. Akhir
                                </th>
                            </tr>
                            <tr>
                                <th
                                    class="border-e border-b p-2 w-[100px]"
                                    v-for="(tp, t) in tps"
                                    :key="`tp-${tp.kode}`"
                                >
                                    <el-popover
                                        placement="bottom"
                                        :title="tp.kode"
                                        :content="tp.teks"
                                    >
                                        <template #reference>
                                            <span
                                                class="text-sky-700 font-bold cursor-pointer"
                                                >{{ tp.kode }}</span
                                            >
                                        </template>
                                    </el-popover>
                                    <el-button
                                        size="small"
                                        :native-type="null"
                                        type="danger"
                                        @click="hapusNilai('uh', tp.kode)"
                                        >Hapus</el-button
                                    >
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(siswa, s) in siswas"
                                :key="`sis-${s}`"
                                class="hover:bg-slate-200"
                            >
                                <td
                                    class="p-3 text-center border-b bg-slate-50"
                                >
                                    {{ s + 1 }}
                                </td>
                                <td
                                    class="p-3 text-center border-b bg-slate-100"
                                >
                                    {{ siswa.nisn }}
                                </td>
                                <td class="p-3 border-b bg-slate-50">
                                    {{ siswa.nama }}
                                </td>
                                <td class="p-3 border-b bg-slate-100">
                                    {{ siswa.jk }}
                                </td>
                                <template
                                    v-for="(tp, t) in tps"
                                    :key="`tp-${tp.kode}`"
                                >
                                    <td
                                        class="p-3 border-b"
                                        :class="
                                            t % 2 !== 0
                                                ? 'bg-slate-100'
                                                : 'bg-slate-50'
                                        "
                                    >
                                        <el-input
                                            v-model="siswas[s].nilais[tp.kode]"
                                            type="number"
                                            :tabindex="t"
                                            min="0"
                                            max="100"
                                            size="small"
                                        />
                                    </td>
                                </template>
                                <td class="p-3 border-b bg-sky-100">
                                    <el-input
                                        v-model="siswas[s].nilais['ts']"
                                        type="number"
                                        :tabindex="tps.length + s"
                                        min="0"
                                        max="100"
                                        size="small"
                                    />
                                </td>
                                <td class="p-3 border-b bg-sky-100">
                                    <el-input
                                        v-model="siswas[s].nilais['as']"
                                        :tabindex="
                                            tps.length * siswas.length + s
                                        "
                                        type="number"
                                        min="0"
                                        max="100"
                                        size="small"
                                    />
                                    <!-- {{ siswas[s] }} -->
                                </td>
                                <td
                                    class="p-3 border-b bg-sky-100 text-center font-bold text-xl"
                                >
                                    <el-tag type="primary">
                                        {{ nilaiAkhir(s) }}
                                    </el-tag>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </el-card>
            <!-- </el-scrollbar> -->
        </template>
    </el-dialog>
    <Teleport to="body">
        <div
            class="z-[999999] backdrop-blur fixed top-0 right-0 bottom-0 left-0 bg-slate-700 bg-opacity-30 flex items-center justify-center"
            v-if="loading"
        >
            <Icon icon="mdi:loading" class="animate-spin text-8xl text-white" />
        </div>
    </Teleport>
</template>

<style>
header.el-dialog__header {
    background: pink !important;
    padding: 0 !important;
}
</style>
