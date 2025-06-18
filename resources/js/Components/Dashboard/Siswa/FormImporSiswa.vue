<script setup>
import { ref, computed } from "vue";
import { read, utils } from "xlsx";
import { router, usePage } from "@inertiajs/vue3";
import { ElNotification } from "element-plus";
import { Icon } from "@iconify/vue";

const page = usePage();
const loading = ref(false);
const progress = ref(0);
const props = defineProps({
    open: Boolean,
    fields: Array,
    title: String,
    url: String,
    query: Object,
});

const fields = [
    "no",
    "nama",
    "nipd",
    "jk",
    "nisn",
    "tempat_lahir",
    "tanggal_lahir",
    "nik",
    "agama",
    "alamat",
    "rt",
    "rw",
    "dusun",
    "kelurahan",
    "kecamatan",
    "kode_pos",
    "jenis_tinggal",
    "alat_transportasi",
    "telepon",
    "hp",
    "email",
    "skhun",
    "penerima_kps",
    "no_kps",
    "nama_ayah",
    "tahun_lahir_ayah",
    "pendidikan_ayah",
    "pekerjaan_ayah",
    "penghasilan_ayah",
    "nik_ayah",
    "nama_ibu",
    "tahun_lahir_ibu",
    "pendidikan_ibu",
    "pekerjaan_ibu",
    "penghasilan_ibu",
    "nik_ibu",
    "nama_wali",
    "tahun_lahir_wali",
    "pendidikan_wali",
    "pekerjaan_wali",
    "penghasilan_wali",
    "nik_wali",
    "rombel_saat_ini",
    "no_un",
    "no_ijazah",
    "penerima_kip",
    "nomor_kip",
    "nama_kip",
    "no_kks",
    "no_akta_lahir",
    "bank",
    "no_rekening_bank",
    "rekening_atas_nama",
    "layak_pip",
    "alasan_layak_pip",
    "kebutuhan_khusus",
    "sekolah_asal",
    "anak_ke",
    "lintang",
    "bujur",
    "no_kk",
    "berat_badan",
    "tinggi_badan",
    "lingkar_kepala",
    "jml_saudara",
    "jarak_rumah",
];
const emit = defineEmits(["close"]);
const show = computed(() => props.open);
const datas = ref([]);
// const totalRows = ref(0);

const parseSheet = async (sheet, range, headers, onProgress) => {
    loading.value = true;
    const rows = [];
    const startRow = 6;
    const totalRows = range.e.r - startRow + 1;

    const batchSize = 100;

    for (let i = 0; i < totalRows; i += batchSize) {
        const batchEnd = Math.min(i + batchSize, totalRows);

        for (let r = i + startRow; r < batchEnd + startRow; r++) {
            const rowData = {};
            let isEmptyRow = true;
            for (let col = 0; col < fields.length; col++) {
                const cellAddress = utils.encode_cell({ r, c: col });
                const cell = sheet[cellAddress];
                const value = cell ? cell.v : null;

                rowData[fields[col]] = value;
                if (value !== null && value !== "") {
                    isEmptyRow = false;
                }
            }
            if (!isEmptyRow) {
                rows.push(rowData);
            }
        }

        onProgress(Math.round(((i + batchSize) / totalRows) * 100));

        await new Promise((resolve) => setTimeout(resolve, 0));
    }

    return rows;
};

const onFilePicked = async (e) => {
    // loading.value = true;
    const file = e.target.files[0];
    const ab = await file.arrayBuffer();
    const wb = read(ab);
    const sheet = wb.Sheets[wb.SheetNames[0]];

    const range = utils.decode_range(sheet["!ref"]);
    const rows = await parseSheet(sheet, range, props.fields, (p) => {
        progress.value = p;
    });
    datas.value = rows;
    loading.value = false;
    // console.log(datas.value);
};

const kirim = async () => {
    router.post(
        route(props.url, { _query: props.query ?? null }),
        {
            sekolah:
                page.props.auth.roles[0] === "admin"
                    ? null
                    : page.props.sekolahs[0].id,
            datas: datas.value,
        },
        {
            onStart: () => (loading.value = true),
            onSuccess: (page) => {
                ElNotification({
                    title: "Info",
                    message: page.props.flash.message,
                    type: "success",
                });
                datas.value = [];
                emit("close");
            },
            onError: (err) => {
                Object.keys(err).forEach((k) => {
                    setTimeout(() => {
                        ElNotification({
                            title: "Error",
                            message: err[k],
                            type: "error",
                        });
                    }, 500);
                });
            },
            onFinish: () => {
                loading.value = false;
                emit("close");
            },
        },
    );
};

const closeMe = () => {
    datas.value = [];
    emit("close");
};
</script>

<template>
    <!-- <h1>Form Sekolah {{ props.open }}</h1> -->
    <el-dialog v-model="show" @close="closeMe" :fullscreen="true">
        <template #header>
            <div class="toolbar flex items-center justify-between">
                <h3 class="title">Impor Data {{ props.title }}</h3>
                <div class="toolbar-items flex items-center">
                    <input
                        type="file"
                        @change="onFilePicked"
                        accept=".xlsx, .xls, .ods, .csv"
                        class="mr-4 border border-sky-600 bg-slate-100 border-dashed"
                    />
                    <el-button
                        type="primary"
                        @click="kirim"
                        v-if="datas.length > 0"
                        :loading="loading"
                        class="mr-4"
                        >Simpan</el-button
                    >
                </div>
            </div>
        </template>
        <div
            class="body max-w-screen mx-h-[80vh] overflow-auto p-2 bg-slate-50"
        >
            <!-- <el-table
                :data="datas"
                max-height="78vh"
                size="small"
                :loading="loading"
            >
                <el-table-column
                    v-for="(field, f) in fields"
                    :key="f"
                    :prop="field"
                    :label="field.toUpperCase()"
                />
            </el-table> -->
            <table class="bg-white">
                <thead>
                    <tr>
                        <template
                            v-for="(col, c) in fields"
                            :key="`kolom-${c}`"
                        >
                            <th class="border border-black p-2">{{ col }}</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="(data, d) in datas" :key="`data-${d}`">
                        <tr class="even:bg-slate-100">
                            <template
                                v-for="(col, c) in fields"
                                :key="`data-c${c}`"
                            >
                                <td class="border border-black p-2">
                                    {{ data[col] }}
                                </td>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </el-dialog>
    <Teleport to="body">
        <div
            class="fixed top-0 right-0 bottom-0 left-0 z-40 bg-white bg-opacity-10 backdrop-blur-sm flex items-center justify-center"
            v-if="loading"
        >
            <Icon
                icon="mdi:loading"
                class="text-8xl animate-spin text-indigo-600"
            />
        </div>
    </Teleport>
</template>
