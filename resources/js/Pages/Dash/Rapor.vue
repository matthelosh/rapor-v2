<script setup>
import { ref, computed, onBeforeMount } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";

import DashLayout from "@/Layouts/DashLayout.vue";
import Cover from "@/Components/Dashboard/Rapor/Cover.vue";
import Biodata from "@/Components/Dashboard/Rapor/Biodata.vue";
import RaporPTS from "@/Components/Dashboard/Rapor/RaporPTS.vue";
import RaporPAS from "@/Components/Dashboard/Rapor/RaporPAS.vue";

const page = usePage();
const mode = ref("list");
const loading = ref(false);
const rombel = ref(null);
const selectedSiswa = ref({});

const cetak = (laman, siswa) => {
    mode.value = laman;
    selectedSiswa.value = siswa;
};

const closeLaman = () => {
    mode.value = "list";
    selectedSiswa.value = {};
};
const selectedSemester = ref("");
const selectedTapel = ref("");
const nextSiswa = () => {
    // alert('halo')
    let current = rombel.value.siswas.findIndex(
        (siswa) => siswa.id === selectedSiswa.value.id,
    );
    if (current >= rombel.value.siswas.length - 1) {
        alert("Ini sudah siswa yang terakhir");
        return false;
    } else {
        selectedSiswa.value = rombel.value.siswas[current + 1];
    }
};
const prevSiswa = () => {
    // alert('halo')
    let current = rombel.value.siswas.findIndex(
        (siswa) => siswa.id === selectedSiswa.value.id,
    );
    if (current === 0) {
        alert("Ini sudah siswa yang pertama");
        return false;
    } else {
        selectedSiswa.value = rombel.value.siswas[current - 1];
    }
};

const cetakTranskrip = async (siswa) => {
    const url = `/cetak/transkrip/${siswa.nisn}`;
    window.open(
        url,
        "_blank",
        "popup=yes,width=1024,height=1500,scrollbars=no,toolbar=no,menubar=no",
    );
};

const onSemesterChanged = (e) => {
    selectedSemester.value = e;
    router.visit(
        window.location.pathname +
            "?semester=" +
            e +
            "&tapel=" +
            selectedTapel.value,
        { preserveState: true },
    );
};

const onTapelChanged = (e) => {
    selectedTapel.value = e;
    router.visit(
        window.location.pathname +
            "?semester=" +
            selectedSemester.value +
            "&tapel=" +
            e,
        { preserveState: true },
    );
};

const dialogPermanen = ref(false);
const confirmPermanen = ref(false);
const simpanPermanen = () => {
    dialogPermanen.value = true;
};
const setujuPermanen = ref(false);
const confirmSimpan = async () => {
    router.post(
        route("dashboard.rapor.permanen"),
        {
            rombelId: rombel.value.kode,
            tapel: selectedTapel.value,
            semester: selectedSemester.value,
        },
        {
            onStart: () => (loading.value = true),
            onSuccess: () =>
                ElNotification({
                    title: "Info",
                    message: page.props.flash.message,
                    type: "success",
                }),
            onError: (errors) => {
                Object.keys(errors).forEach((k) =>
                    ElNotification({
                        title: "Error",
                        message: errors[k],
                        type: "error",
                    }),
                );
            },
            onFinish: () => (loading.value = false),
        },
    );
};
onBeforeMount(() => {
    rombel.value = page.props.rombels[0];
    selectedSemester.value =
        route().params.semester ?? page.props.periode.semester.kode;
    selectedTapel.value = route().params.tapel ?? page.props.periode.tapel.kode;
});
</script>

<template>
    <Head title="Rapor Siswa" />

    <DashLayout>
        <template #header>
            <h3 class="uppercase">Rapor Siswa {{ selectedSiswa?.nama }}</h3>
        </template>
        <el-card v-if="mode == 'list'">
            <template #header>
                <div class="flex items-center justify-between w-full">
                    <h3 class="uppercase font-bold text-slate-600">
                        Rapor Siswa {{ rombel.label }} {{ rombel.sekolah.nama }}
                    </h3>
                    <div
                        class="header-items flex-grow flex items-center gap-2 justify-end"
                    >
                        <p>Semester:</p>
                        <el-select
                            v-model="selectedSemester"
                            placeholder="Pilih Semester"
                            style="width: 100px"
                            @change="onSemesterChanged"
                        >
                            <el-option
                                v-for="sem in ['1', '2']"
                                :key="`sem${sem}`"
                                :value="sem"
                                :label="`Sem ${sem}`"
                            />
                        </el-select>
                        <p>Tapel:</p>
                        <el-select
                            v-model="selectedTapel"
                            placeholder="Pilih Tapel"
                            style="width: 130px"
                            @change="onTapelChanged"
                        >
                            <el-option
                                v-for="tapel in page.props.tapels"
                                :key="`tapel-${tapel.kode}`"
                                :value="tapel.kode"
                                :label="tapel.label"
                            />
                        </el-select>
                        <el-button
                            @click="simpanPermanen"
                            :native-type="null"
                            type="danger"
                        >
                            Simpan Permanen
                        </el-button>
                    </div>
                </div>
            </template>
            <div class="card-body">
                <!--   {{page.props.tapels}} -->
                <el-table :data="rombel.siswas" height="80vh" size="small">
                    <el-table-column
                        label="#"
                        type="index"
                        prop="scope.$index"
                    />
                    <el-table-column label="NISN" prop="nisn" width="150" />
                    <el-table-column label="Nama" prop="nama" />
                    <el-table-column label="JK" prop="jk" width="100" />
                    <el-table-column label="Opsi Cetak" fixed="right">
                        <template #default="scope">
                            <div>
                                <el-button-group size="small">
                                    <el-button
                                        type="primary"
                                        @click="cetak('cover', scope.row)"
                                        >Cover</el-button
                                    >
                                    <el-button
                                        type="primary"
                                        @click="cetak('biodata', scope.row)"
                                        >Biodata</el-button
                                    >
                                    <el-button
                                        type="primary"
                                        @click="cetak('pts', scope.row)"
                                        >PTS</el-button
                                    >
                                    <el-button
                                        type="primary"
                                        @click="cetak('pas', scope.row)"
                                        >PAS</el-button
                                    >
                                    <el-button
                                        v-if="scope.row.tingkat == '6'"
                                        type="primary"
                                        @click="cetakTranskrip(scope.row)"
                                        >Transkrip</el-button
                                    >
                                </el-button-group>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </div>
        </el-card>
        <Cover
            v-if="mode == 'cover'"
            :siswa="selectedSiswa"
            @close="closeLaman"
            @nextSiswa="nextSiswa"
            @prevSiswa="prevSiswa"
            :rombel="rombel"
        />
        <Biodata
            v-if="mode == 'biodata'"
            :siswa="selectedSiswa"
            @close="closeLaman"
            @nextSiswa="nextSiswa"
            @prevSiswa="prevSiswa"
            :rombel="rombel"
        />
        <RaporPTS
            v-if="mode == 'pts'"
            :siswa="selectedSiswa"
            @close="closeLaman"
            @nextSiswa="nextSiswa"
            @prevSiswa="prevSiswa"
            :rombel="rombel"
        />
        <RaporPAS
            v-if="mode == 'pas'"
            :siswa="selectedSiswa"
            @close="closeLaman"
            @nextSiswa="nextSiswa"
            @prevSiswa="prevSiswa"
            :rombel="rombel"
        />
    </DashLayout>
    <Teleport to="body">
        <el-dialog
            v-model="dialogPermanen"
            title="Konfirmasi Simpan Rapor secara permanen"
        >
            <el-alert type="error" class="text-lg" :closable="false">
                <div class="flex gap-2">
                    <label class="text-lg flex items-start gap-2">
                        <el-checkbox
                            v-model="setujuPermanen"
                            size="large"
                            type="error"
                        />
                        <span class="font-bold">Yakin?</span> Perubahan nilai
                        tidak dapat dilakukan setelah rapor menjadi permanen
                    </label>
                </div>
            </el-alert>
            <div class="flex justify-end gap-2 mt-4">
                <el-button
                    :native-type="null"
                    type="primary"
                    @click="confirmSimpan"
                    :disabled="!setujuPermanen"
                >
                    Ya, Simpan permanen
                </el-button>
            </div>
        </el-dialog>
    </Teleport>
</template>
