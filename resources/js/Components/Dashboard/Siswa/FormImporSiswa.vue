<script setup>
import { ref, computed } from "vue";
import { read, utils } from "xlsx";
import { router, usePage } from "@inertiajs/vue3";
import { ElNotification } from "element-plus";

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
            for (let col = 0; col < props.fields.length; col++) {
                const cellAddress = utils.encode_cell({ r, c: col });
                const cell = sheet[cellAddress];
                rowData[props.fields[col]] = cell ? cell.v : null;
            }
            rows.push(rowData);
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
    console.log(datas.value);
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
                    />
                    <el-button
                        type="primary"
                        @click="kirim"
                        v-if="datas.length > 0"
                        :loading="loading"
                        >Simpan</el-button
                    >
                </div>
            </div>
        </template>
        <div class="body">
            <el-table
                :data="datas"
                max-height="78vh"
                size="small"
                :loading="loading"
            >
                <el-table-column
                    v-for="(field, f) in props.fields"
                    :key="f"
                    :prop="field"
                    :label="field.toUpperCase()"
                />
            </el-table>
            <!-- <el-alert v-else type="success">
                <span class="font-bold text-sky-600 text-xl"
                    >Pastikan File Excel dengan format kolom</span
                >
                <span class="text-sky-800 text-xl">{{ props.fields }}</span>
            </el-alert> -->
        </div>
    </el-dialog>
</template>
