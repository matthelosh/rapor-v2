<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import { ElCard } from "element-plus";
const page = usePage();

const FormNilaiHarian = defineAsyncComponent(
    () => import("@/Components/Dashboard/Nilai/FormNilaiHarian.vue"),
);
const FormNilaiTS = defineAsyncComponent(
    () => import("@/Components/Dashboard/Nilai/FormNilaiTS.vue"),
);
const FormNilaiAS = defineAsyncComponent(
    () => import("@/Components/Dashboard/Nilai/FormNilaiAS.vue"),
);
const FormNilaiP5 = defineAsyncComponent(
    () => import("@/Components/Dashboard/Nilai/FormNilaiP5.vue"),
);
const FormNilaiSPN = defineAsyncComponent(
    () => import("@/Components/Dashboard/Nilai/FormNilaiSPN.vue"),
);
const FormNilaiKelas = defineAsyncComponent(
    () => import("@/Components/Dashboard/Nilai/FormNilaiKelas.vue"),
);

const mode = ref("home");
const selectedRombel = ref({});
const selectedMapel = ref({});
const selectedSemester = ref('')

const params = route().params

const guruKelas = () => {
    return page.props.auth.roles[0].includes("guru_kelas");
};

const openForm = (mapel, rombel, komponen) => {
    // console.log(rombel, mapel, komponen)
    selectedRombel.value = rombel;
    selectedMapel.value = mapel;
    mode.value = komponen;
};

const closeForm = () => {
    selectedRombel.value = {};
    selectedMapel.value = {};
    mode.value = "home";
};

const onSemesterChanged = (e) => {
    router.visit(window.location.pathname + '?semester='+selectedSemester.value, {reload: { only: ['datas', 'rombels']}, preserveState: true})
}

onBeforeMount(() => {
    selectedSemester.value = params.semester ?? page.props.periode.semester.kode
})
</script>

<template>
    <div>
        <el-card>
            <template #header>
                <div class="card-toolbar flex items-center justify-between">
                    <div class="title">
                        <h3 class="text-lg font-bold">
                            Penilaian {{ page.props.rombels[0].label }} Semester
                            {{ page.props.periode.semester.label }}
                            {{ page.props.periode.tapel.deskripsi }}
                        </h3>
                    </div>
                    <div class="toolbar-items flex items-center gap-2">
                        <p>Semester:</p>
                        <el-select v-model="selectedSemester" placeholder="Pilih Semester" @change="onSemesterChanged" style="width:100px">
                            <el-option v-for="sem in ['1','2']" :key="`sem${sem}`" :value="sem" :label="`Sem ${sem}`" />
                        </el-select>

                    </div>
                </div>
            </template>
            <div class="card-body">
                <input
                    type="file"
                    accept=".xlsx, .xls, .ods, .csv"
                    ref="inputFileNilai"
                    class="hidden"
                    @change="onFileNilaiChange($event)"
                />
                <el-collapse accordion>
                    <template
                        v-for="(rombel, r) in page.props.rombels"
                        :key="r"
                    >
                        <el-collapse-item>
                            <template #title>
                                <span
                                    >{{ rombel.label }} |
                                    {{ rombel.sekolah.nama }} |
                                    {{ rombel.siswas.length }} Siswa</span
                                >
                            </template>

                            <el-table :data="page.props.datas">
                                <el-table-column
                                    label="Mata Pelajaran"
                                    prop="label"
                                />
                                <el-table-column
                                    label="Kategori"
                                    prop="kategori"
                                    width="100"
                                />
                                <el-table-column label="Entri Nilai">
                                    <template #default="scope">
                                        <span class="flex items-center">
                                            <el-button
                                                type="primary"
                                                rounded
                                                size="small"
                                                :disabled="
                                                    guruKelas &&
                                                    scope.row.kode == 'pabp'
                                                "
                                                @click="
                                                    openForm(
                                                        scope.row,
                                                        rombel,
                                                        'nilai-kelas',
                                                    )
                                                "
                                                >Nilai Kelas</el-button
                                            >
                                            <el-button
                                                type="primary"
                                                :disabled="
                                                    guruKelas &&
                                                    scope.row.kode == 'pabp'
                                                "
                                                rounded
                                                size="small"
                                                @click="
                                                    openForm(
                                                        scope.row,
                                                        rombel,
                                                        'harian',
                                                    )
                                                "
                                                >Nilai Harian</el-button
                                            >
                                            <el-button
                                                type="primary"
                                                :disabled="
                                                    guruKelas &&
                                                    scope.row.kode == 'pabp'
                                                "
                                                rounded
                                                size="small"
                                                @click="
                                                    openForm(
                                                        scope.row,
                                                        rombel,
                                                        'sts',
                                                    )
                                                "
                                                >PTS</el-button
                                            >
                                            <el-button
                                                type="primary"
                                                :disabled="
                                                    guruKelas &&
                                                    scope.row.kode == 'pabp'
                                                "
                                                rounded
                                                size="small"
                                                @click="
                                                    openForm(
                                                        scope.row,
                                                        rombel,
                                                        'sas',
                                                    )
                                                "
                                                >PAS</el-button
                                            >
                                            <el-button
                                                type="primary"
                                                :disabled="
                                                    guruKelas &&
                                                    scope.row.kode == 'pabp'
                                                "
                                                rounded
                                                size="small"
                                                v-if="rombel.tingkat == '6'"
                                                @click="
                                                    openForm(
                                                        scope.row,
                                                        rombel,
                                                        'psaj',
                                                    )
                                                "
                                                >PSAJ</el-button
                                            >
                                            <el-button
                                                type="primary"
                                                :disabled="
                                                    guruKelas &&
                                                    scope.row.kode == 'pabp'
                                                "
                                                rounded
                                                size="small"
                                                v-if="page.props.auth.roles.includes('guru_agama')"
                                                @click="
                                                    openForm(
                                                        scope.row,
                                                        rombel,
                                                        'spn',
                                                    )
                                                "
                                                >SPN</el-button
                                            >
                                        </span>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-collapse-item>
                    </template>
                </el-collapse>
                <!-- {{ page.props.rombels }} -->
            </div>
        </el-card>
        <FormNilaiHarian
            v-if="mode == 'harian'"
            :rombel="selectedRombel"
            :mapel="selectedMapel"
            @close="closeForm"
            :open="mode == 'harian'"
        />
        <FormNilaiTS
            v-if="mode == 'sts'"
            :rombel="selectedRombel"
            :mapel="selectedMapel"
            @close="closeForm"
            :open="mode == 'sts'"
        />
        <FormNilaiAS
            v-if="mode == 'sas'"
            :rombel="selectedRombel"
            :mapel="selectedMapel"
            @close="closeForm"
            :open="mode == 'sas'"
        />
        <FormNilaiP5
            v-if="mode == 'p5'"
            :rombel="selectedRombel"
            :mapel="selectedMapel"
            @close="closeForm"
            :open="mode == 'p5'"
        />
        <FormNilaiP5
            v-if="mode == 'spn'"
            :rombel="selectedRombel"
            :mapel="selectedMapel"
            @close="closeForm"
            :open="mode == 'spn'"
        />
        <FormNilaiKelas
            v-if="mode == 'nilai-kelas'"
            :rombel="selectedRombel"
            :mapel="selectedMapel"
            @close="closeForm"
            :open="mode == 'nilai-kelas'"
        />
    </div>
</template>
