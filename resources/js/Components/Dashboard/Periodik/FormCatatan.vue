<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import axios from "axios";
import { ElNotification } from "element-plus";

const page = usePage();
const props = defineProps({ siswa: Object, rombel: Object, open: Boolean });
const emit = defineEmits(["close", "nextSiswa", "prevSiswa"]);
const catatan = ref("");
const isTuntas = ref(false);
const tes = ref(null);

const getCatatan = async () => {
    await axios
        .get(
            route("dashboard.nilai.catatan.index", {
                _query: {
                    sekolahId: page.props.sekolahs[0].npsn,
                    siswaId: props.siswa.nisn,
                    tapel: page.props.periode.tapel.kode,
                    semester: page.props.periode.semester.kode,
                },
            }),
        )
        .then((res) => {
            console.log(res.data);
            catatan.value = res.data.catatan?.teks || "";
            isTuntas.value = res.data.catatan?.is_tuntas === 1 ? true : false;
        })
        .catch((err) => console.log(err));
};

const simpan = async () => {
    router.post(
        route("dashboard.nilai.catatan.store", {
            _query: {
                siswaId: props.siswa.nisn,
                tapel: page.props.periode.tapel.kode,
                semester: page.props.periode.semester.kode,
                rombelId: props.rombel.kode,
            },
        }),
        { catatan: catatan.value, is_tuntas: isTuntas.value },
        {
            onSuccess: (page) => {
                ElNotification({
                    title: "Info",
                    message: page.props.flash.message,
                    type: "success",
                });
            },
            onError: (errs) => {
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
        },
    );
};

onBeforeMount(async () => {
    getCatatan();
});
</script>

<template>
    <el-dialog
        v-model="props.open"
        :show-close="false"
        :body-style="{ background: '#aaa' }"
        width="650"
    >
        <template #header="{ close }">
            <div class="toolbar flex items-start justify-between">
                <span class="uppercase">
                    Catatan Rapor <br /><small>{{
                        props.siswa.nama
                    }}</small></span
                >
                <div class="toolbar-items flex items-center">
                    <el-button
                        type="danger"
                        @click="emit('close')"
                        size="small"
                    >
                        <Icon icon="mdi:close" />
                    </el-button>
                </div>
            </div>
        </template>
        <!-- {{ rombel }} -->
        <div
            class="dialog-body flex flex-col justify-center items-center py-4 border-t-2 border-slate-400"
        >
            <el-input
                type="textarea"
                placeholder="Catatan Rapor"
                v-model="catatan"
            ></el-input>
            <br />
            <el-checkbox v-model="isTuntas">Tuntas / Lulus</el-checkbox>
        </div>
        <template #footer>
            <div class="flex justify-end px-4">
                <el-button type="primary" @click="simpan">Simpan</el-button>
            </div>
        </template>
    </el-dialog>
</template>
