<script setup>
import { Head, router, usePage } from "@inertiajs/vue3";
import DashLayout from "@/Layouts/DashLayout.vue";
import { onBeforeMount, onMounted, ref } from "vue";

const page = usePage();

const formNilai = ref(false);
const siswas = ref([]);
const nilaiIndex = ref(0);
const selectedSiswa = ref(null);
const selectedRombel = ref(null);
const inputNilai = (index, siswa, rombel) => {
    formNilai.value = true;
    nilaiIndex.value = index;
    selectedSiswa.value = siswa;
    selectedRombel.value = rombel;
    selectedNilai.value = {
        id: siswa.kokurikulers[0]?.id ?? null,
        index: index,
        siswa_id: siswa.nisn,
        rombel_id: rombel.kode,
        guru_id: page.props.auth.userable?.nip,
        tapel: page.props.periode.tapel.kode,
        semester: page.props.periode.semester.kode,
        deskripsi: siswa.kokurikulers[0]?.deskripsi,
    };
};

const nilais = ref([]);
const selectedNilai = ref({
    id: "",
    index: "",
    siswa_id: "",
    rombel_id: "",
    guru_id: "",
    deskripsi: "",
    tapel: "",
    semester: "",
});

const loading = ref(false);
const simpanKokurikuler = async () => {
    router.post(route("dashboard.kokurikuler.store"), selectedNilai.value, {
        onStart: () => (loading.value = true),
        onSuccess: () => {
            router.reload({ only: ["rombel"] });
            ElNotification({
                type: "info",
                message: page.props.flash.message,
                title: "Info",
            });
            formNilai.value = false;
        },
        onError: () => {
            Object.entries(page.props.errors).forEach((err) => {
                ElNotification({ type: "error", message: err, title: "Error" });
            });
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};

onBeforeMount(() => {
    // [...page.props.rombels.siswas].forEach((siswa) => {
    //     nilais.value.push({ siswa_id: siswa.nisn, nilai: "" });
    // });
});
</script>

<template>
    <Head title="Nilai Kokurikuler" />
    <DashLayout>
        <!-- {{ page.props.rombels }} -->
        <el-collapse accordion>
            <template v-for="(rombel, r) in page.props.rombels">
                <el-collapse-item>
                    <template #title>
                        <div class="px-4">
                            Input nilai Kokurikuler {{ rombel.label }} | Tapel
                            {{ rombel.tapel }}
                        </div>
                    </template>
                    <div>
                        <el-table :data="rombel.siswas">
                            <el-table-column label="NISN">
                                <template #default="{ row }">
                                    {{ row.nisn }} /
                                    {{ row.nis ?? "-" }}
                                </template>
                            </el-table-column>
                            <el-table-column label="NAMA">
                                <template #default="{ row }">
                                    {{ row.nama }}
                                </template>
                            </el-table-column>
                            <el-table-column label="Kokurikuler">
                                <template #default="{ row }">
                                    {{ row.kokurikulers[0]?.deskripsi }}
                                </template>
                            </el-table-column>
                            <el-table-column label="AKSI">
                                <template #default="scope">
                                    <el-button
                                        type="primary"
                                        :native-type="null"
                                        @click="
                                            inputNilai(
                                                scope.$index,
                                                scope.row,
                                                rombel,
                                            )
                                        "
                                    >
                                        Input Kokurikuler
                                    </el-button>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </el-collapse-item>
            </template>
        </el-collapse>
        <el-dialog v-model="formNilai">
            <template #header>
                Pencapaian Kokurikuler
                {{ selectedSiswa.nama }}
            </template>
            <div>
                <el-input type="textarea" v-model="selectedNilai.deskripsi" />
            </div>
            <template #footer>
                <el-button
                    :native-type="null"
                    type="primary"
                    @click="simpanKokurikuler"
                    >Simpan</el-button
                >
            </template>
        </el-dialog>
    </DashLayout>
</template>
