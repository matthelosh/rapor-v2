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

const selectedRombel = ref({});
const selectedSekolah = ref({});

const mode = ref("home");

const mapel = computed(() => {
    const mapels = ["pabp", "pjok", "bing"];
    const roles = ["guru_agama", "guru_pjok", "guru_inggris"];
    return {
        kode: mapels[
            roles.findIndex((role) => role === page.props.auth.roles[0])
        ],
    };
});

const closeForm = () => {
    mode.value = "home";
    selectedRombel.value = {};
    selectedSekolah.value = {};
};

const open = (rombel, komponen, sekolah) => {
    selectedRombel.value = rombel;
    selectedSekolah.value = sekolah;

    mode.value = komponen;
};
const params = route().params;
const persen = (scope) => {};
const selectedSemester = ref("");

const onSemesterChanged = (e) => {
    selectedSemester.value = e;
    router.visit(
        `${window.location.pathname}?semester=${selectedSemester.value}`,
        {
            reload: { only: ["nilais"] },
            preserveState: true,
        },
    );
};
onBeforeMount(() => {
    selectedSemester.value = params.semester | page.props.periode.semester.kode;
});
</script>

<template>
    <div>
        <el-card>
            <template #header>
                <div class="card-toolbar flex items-center justify-between">
                    <div class="title">
                        <h3 class="text-lg font-bold">
                            Penilaian Semester
                            {{
                                params.semester ??
                                page.props.periode.semester.kode
                            }}
                            {{ page.props.periode.tapel.label }}
                        </h3>
                    </div>
                    <div
                        class="toolbar-items flex items-center justify-end gap-2"
                    >
                        <p>Semester:</p>
                        <el-select
                            v-model="selectedSemester"
                            placeholder="Pilih semester"
                            style="width: 60px"
                            @change="onSemesterChanged"
                        >
                            <el-option
                                v-for="sem in ['1', '2']"
                                :key="`sem${sem}`"
                                :value="sem"
                                :label="sem"
                            />
                        </el-select>
                    </div>
                </div>
            </template>
            <div class="card-body">
                <!-- {{ page.props.datas }} -->
                <el-collapse>
                    <template v-for="(sekolah, s) in page.props.datas" :key="s">
                        <el-collapse-item>
                            <template #title>
                                <span class="text-sky-700 font-bold"
                                    >{{ sekolah.npsn }} | {{ sekolah.nama }} |
                                    {{ sekolah.ks?.nama }}</span
                                >
                            </template>
                            <el-table :data="sekolah.rombels">
                                <el-table-column
                                    label="Rombel"
                                    prop="label"
                                    width="100"
                                />
                                <el-table-column label="Jml Siswa" width="80">
                                    <template #default="scope">
                                        {{ scope.row.siswas?.length }}
                                    </template>
                                </el-table-column>
                                <el-table-column label="% Penilaian">
                                    <template #default="scope">
                                        <p>
                                            UH:
                                            {{
                                                Math.round(
                                                    (parseInt(
                                                        scope.row.nilais
                                                            ? scope.row
                                                                  .nilais[0]?.uh
                                                            : 0,
                                                    ) /
                                                        scope.row.siswas
                                                            ?.length) *
                                                        100,
                                                )
                                            }}
                                            % | PTS:
                                            {{
                                                Math.round(
                                                    (parseInt(
                                                        scope.row.nilais
                                                            ? scope.row
                                                                  .nilais[0]
                                                                  ?.pts
                                                            : 0,
                                                    ) /
                                                        scope.row.siswas
                                                            ?.length) *
                                                        100,
                                                )
                                            }}
                                            % | PAS:
                                            {{
                                                Math.round(
                                                    (parseInt(
                                                        scope.row.nilais
                                                            ? scope.row
                                                                  .nilais[0]?.as
                                                            : 0,
                                                    ) /
                                                        scope.row.siswas
                                                            ?.length) *
                                                        100,
                                                )
                                            }}
                                            %
                                        </p>
                                    </template>
                                </el-table-column>
                                <el-table-column label="Entri Nilai">
                                    <template #default="scope">
                                        <span class="flex items-center">
                                            <el-button
                                                :native-type="null"
                                                type="primary"
                                                rounded
                                                size="small"
                                                @click="
                                                    open(
                                                        scope.row,
                                                        'harian',
                                                        sekolah,
                                                    )
                                                "
                                                >Nilai Harian</el-button
                                            >
                                            <el-button
                                                :native-type="null"
                                                type="primary"
                                                rounded
                                                size="small"
                                                @click="
                                                    open(
                                                        scope.row,
                                                        'sts',
                                                        sekolah,
                                                    )
                                                "
                                                >PTS</el-button
                                            >
                                            <el-button
                                                :native-type="null"
                                                type="primary"
                                                rounded
                                                size="small"
                                                @click="
                                                    open(
                                                        scope.row,
                                                        'sas',
                                                        sekolah,
                                                    )
                                                "
                                                >PAS</el-button
                                            >
                                        </span>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-collapse-item>
                    </template>
                </el-collapse>
            </div>
        </el-card>
        <FormNilaiHarian
            v-if="mode == 'harian'"
            :open="mode == 'harian'"
            :rombel="selectedRombel"
            :sekolah="selectedSekolah"
            :mapel="mapel"
            @close="closeForm"
        />
        <FormNilaiTS
            v-if="mode == 'sts'"
            :open="mode == 'sts'"
            :rombel="selectedRombel"
            :sekolah="selectedSekolah"
            :mapel="mapel"
            @close="closeForm"
        />
        <FormNilaiAS
            v-if="mode == 'sas'"
            :open="mode == 'sas'"
            :rombel="selectedRombel"
            :sekolah="selectedSekolah"
            :mapel="mapel"
            @close="closeForm"
        />
    </div>
</template>
