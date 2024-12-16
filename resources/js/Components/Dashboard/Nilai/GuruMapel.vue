<script setup>
import { ref, computed } from "vue";
import { usePage } from "@inertiajs/vue3";
import { ElCard } from "element-plus";
const page = usePage();
import FormNilaiHarian from "./FormNilaiHarian.vue";
import FormNilaiTS from "./FormNilaiTS.vue";
import FormNilaiAS from "./FormNilaiAS.vue";

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
</script>

<template>
    <div>
        <el-card>
            <template #header>
                <div class="card-toolbar flex items-center justify-between">
                    <div class="title">
                        <h3 class="text-lg font-bold">
                            Penilaian Semester
                            {{ page.props.periode.semester.label }}
                            {{ page.props.periode.tapel.deskripsi }}
                        </h3>
                    </div>
                    <div class="toolbar-items"></div>
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
                                <el-table-column label="Rombel" prop="label" />
                                <el-table-column label="Jumlah Siswa">
                                    <template #default="scope">
                                        {{ scope.row.siswas?.length }}
                                    </template>
                                </el-table-column>
                                <el-table-column label="Entri Nilai">
                                    <template #default="scope">
                                        <span class="flex items-center">
                                            <el-button
                                                style="
                                                    background: linear-gradient(
                                                        105deg,
                                                        #225588 70%,
                                                        #cdaa77dd 15%
                                                    );
                                                    color: white;
                                                "
                                                rounded
                                                size="small"
                                                @click="
                                                    open(
                                                        scope.row,
                                                        'harian',
                                                        sekolah
                                                    )
                                                "
                                                >Nilai Harian</el-button
                                            >
                                            <el-button
                                                type="primary"
                                                rounded
                                                size="small"
                                                @click="
                                                    open(
                                                        scope.row,
                                                        'sts',
                                                        sekolah
                                                    )
                                                "
                                                >PTS</el-button
                                            >
                                            <el-button
                                                type="primary"
                                                rounded
                                                size="small"
                                                @click="
                                                    open(
                                                        scope.row,
                                                        'sas',
                                                        sekolah
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
