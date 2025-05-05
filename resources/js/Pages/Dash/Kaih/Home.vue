<script setup>
import { ref, defineAsyncComponent, computed } from "vue";
import { router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import DashLayout from "@/Layouts/DashLayout.vue";

const FormSemester = defineAsyncComponent(
    () => import("@/Components/Dashboard/Kaih/Semester.vue"),
);
const KaihDetail = defineAsyncComponent(
    () => import("@/Components/Dashboard/Kaih/KaihDetail.vue"),
);
const PerKebiasaan = defineAsyncComponent(
    () => import("@/Components/Dashboard/Kaih/PerKebiasaan.vue"),
);
const PerBulan = defineAsyncComponent(
    () => import("@/Components/Dashboard/Kaih/PerBulan.vue"),
);
defineProps({ rombels: Array });

const bulan = ref(null);
const tahun = ref(null);
const selectedRombel = ref(null);
const formSemester = ref(false);
const formDetail = ref(false);
const selectedSiswa = ref(null);
const bulanTahun = computed(() => {
    return (
        (tahun.value ?? new Date().getFullYear()) +
        "-" +
        (bulan.value ?? new Date().getMonth() + 1)
    );
});
const showSemester = (item, rombel) => {
    selectedSiswa.value = item;
    formSemester.value = true;
    selectedRombel.value = {
        kode: rombel.kode,
        label: rombel.label,
        tapel: rombel.tapel,
    };
};
const showDetail = (item, rombel) => {
    selectedSiswa.value = item;
    formDetail.value = true;
    selectedRombel.value = {
        kode: rombel.kode,
        label: rombel.label,
        tapel: rombel.tapel,
    };
};
const formPerKebiasaan = ref(false);
const selectedKebiasaan = ref(null);
const showPerKebiasaan = (row, rombel, kebiasaan) => {
    // console.log(kebiasaan);
    selectedSiswa.value = row;
    formPerKebiasaan.value = true;
    selectedKebiasaan.value = kebiasaan;
    selectedRombel.value = {
        kode: rombel.kode,
        label: rombel.label,
        tapel: rombel.tapel,
    };
};

const formPerBulan = ref(false);
const showPerBulan = (row, rombel) => {
    selectedSiswa.value = row;
    formPerBulan.value = true;
    selectedRombel.value = {
        kode: rombel.kode,
        label: rombel.label,
        tapel: rombel.tapel,
    };
};

const resetForm = () => {
    selectedKebiasaan.value = null;
    selectedSiswa.value = null;
    selectedRombel.value = null;
};
const tahuns = computed(() => {
    const currentYear = new Date().getFullYear();
    const years = [];
    for (let i = currentYear - 5; i <= currentYear + 5; i++) {
        years.push(i);
    }
    return years;
});

const fetchData = () => {
    try {
        router.get(
            route("dashboard.kaih.home", {
                _query: { tahun: tahun.value, bulan: bulan.value },
            }),
            {
                reload: { only: ["rombels"] },
                preserveState: true,
            },
        );
        // return response.data;
    } catch (error) {
        console.error(error);
    }
};
</script>

<template>
    <DashLayout title="7 KAIH">
        <template #header>
            <h3>7 KAIH</h3>
        </template>
        <template #default>
            <el-card body-class="bg-slate-200">
                <template #header>
                    <div class="toolbar flex items-center justify-between">
                        <h3>Monitor Progress 7 KAIH</h3>
                        <div
                            class="toolbar-items flex items-center gap-2 min-w-[400px]"
                        >
                            <el-select
                                placeholder="Pilih Bulan"
                                v-model="bulan"
                                class="w-40"
                            >
                                <el-option
                                    label="Januari"
                                    value="1"
                                ></el-option>
                                <el-option
                                    label="Februari"
                                    value="2"
                                ></el-option>
                                <el-option label="Maret" value="3"></el-option>
                                <el-option label="April" value="4"></el-option>
                                <el-option label="Mei" value="5"></el-option>
                                <el-option label="Juni" value="6"></el-option>
                                <el-option label="Juli" value="7"></el-option>
                                <el-option
                                    label="Agustus"
                                    value="8"
                                ></el-option>
                                <el-option
                                    label="September"
                                    value="9"
                                ></el-option>
                                <el-option
                                    label="Oktober"
                                    value="10"
                                ></el-option>
                                <el-option
                                    label="November"
                                    value="11"
                                ></el-option>
                                <el-option
                                    label="Desember"
                                    value="12"
                                ></el-option>
                            </el-select>

                            <el-select
                                placeholder="Pilih Tahun"
                                v-model="tahun"
                                class="w-40"
                            >
                                <el-option
                                    v-for="year in tahuns"
                                    :key="year"
                                    :label="year"
                                    :value="year"
                                ></el-option>
                            </el-select>
                            <el-button @click="fetchData">Lihat Data</el-button>
                        </div>
                    </div>
                </template>
                <template #default>
                    <el-collapse>
                        <template
                            v-for="(rombel, r) in rombels"
                            :key="`rombel-${r}`"
                        >
                            <el-collapse-item>
                                <template #title>
                                    <div class="p-4 text-lg font-black">
                                        {{ rombel.label }}
                                    </div>
                                </template>
                                <div class="px-6">
                                    <el-table
                                        :data="rombel.siswas"
                                        max-height="600"
                                    >
                                        <el-table-column
                                            label="#"
                                            type="index"
                                        />
                                        <el-table-column
                                            label="NISN"
                                            prop="nisn"
                                        />
                                        <el-table-column
                                            label="Nama"
                                            prop="nama"
                                        />
                                        <el-table-column label="Progress KAIH">
                                            <template #default="{ row }">
                                                <div
                                                    class="bar flex gap-2 items-end bg-sky-100 p-2 rounded min-h-[50px]"
                                                >
                                                    <template
                                                        v-for="k in Object.keys(
                                                            row.kebiasaan_count,
                                                        )"
                                                        :key="`kaih-${k}`"
                                                    >
                                                        <ElTooltip :content="k">
                                                            <div
                                                                class="bar-item w-[50px] flex items-end justify-center cursor-pointer font-bold text-slate-600 rounded hover:shadow hover:-translate-y-2 transition-transform duration-300"
                                                                :class="
                                                                    row
                                                                        .kebiasaan_count[
                                                                        k
                                                                    ] <= 30
                                                                        ? 'bg-red-300'
                                                                        : row
                                                                                .kebiasaan_count[
                                                                                k
                                                                            ] <=
                                                                            60
                                                                          ? 'bg-yellow-300'
                                                                          : 'bg-green-400'
                                                                "
                                                                :style="`height: ${row.kebiasaan_count[k]}px;`"
                                                                @click="
                                                                    showPerKebiasaan(
                                                                        row,
                                                                        rombel,
                                                                        k,
                                                                    )
                                                                "
                                                            >
                                                                {{
                                                                    row
                                                                        .kebiasaan_count[
                                                                        k
                                                                    ] ?? "0"
                                                                }}
                                                            </div>
                                                        </ElTooltip>
                                                    </template>
                                                </div>
                                            </template>
                                        </el-table-column>
                                        <el-table-column label="Opsi">
                                            <template #default="{ row }">
                                                <div class="flex">
                                                    <el-button-group>
                                                        <el-button
                                                            size="small"
                                                            class="group"
                                                            @click="
                                                                showDetail(
                                                                    row,
                                                                    rombel,
                                                                )
                                                            "
                                                        >
                                                            <Icon
                                                                icon="mdi:chart-doughnut"
                                                                class="group-hover:text-orange-400"
                                                            />
                                                            <span
                                                                class="ml-[0.1rem]"
                                                            >
                                                                Grafik
                                                            </span>
                                                        </el-button>
                                                        <el-button
                                                            size="small"
                                                            @click="
                                                                showPerBulan(
                                                                    row,
                                                                    rombel,
                                                                )
                                                            "
                                                        >
                                                            <Icon
                                                                icon="mdi:calendar"
                                                            />
                                                            <span
                                                                class="ml-[0.1rem]"
                                                            >
                                                                Bulan
                                                            </span>
                                                        </el-button>
                                                        <el-button
                                                            size="small"
                                                            @click="
                                                                showSemester(
                                                                    row,
                                                                    rombel,
                                                                )
                                                            "
                                                        >
                                                            <Icon
                                                                icon="mdi:calendar"
                                                            />
                                                            <span
                                                                class="ml-[0.1rem]"
                                                            >
                                                                Semester
                                                            </span>
                                                        </el-button>
                                                    </el-button-group>
                                                </div>
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                </div>
                            </el-collapse-item>
                        </template>
                    </el-collapse>
                </template>
            </el-card>
        </template>
    </DashLayout>
    <el-dialog v-model="formSemester" fullscreen>
        <template #default>
            <FormSemester
                :siswa="selectedSiswa"
                :rombel="selectedRombel"
                v-if="formSemester"
            />
        </template>
    </el-dialog>
    <el-dialog v-model="formDetail" fullscreen>
        <template #header>
            <h2>{{ selectedSiswa.nama }}</h2>
            <p>Kelas: {{ selectedRombel.label }}</p>
        </template>
        <template #default>
            <KaihDetail
                :siswa="selectedSiswa"
                :rombel="selectedRombel"
                :bulanTahun="bulanTahun"
                v-if="formDetail"
            />
        </template>
    </el-dialog>
    <el-dialog v-model="formPerKebiasaan" fullscreen @closed="resetForm">
        <template #header>
            <h2>{{ selectedKebiasaan }}: {{ selectedSiswa.nama }}</h2>
        </template>
        <template #default>
            <PerKebiasaan
                :siswa="selectedSiswa"
                :rombel="selectedRombel"
                :kebiasaan="selectedKebiasaan"
                :bulanTahun="bulanTahun"
                v-if="formPerKebiasaan"
            />
        </template>
    </el-dialog>
    <el-dialog v-model="formPerBulan" fullscreen @closed="resetForm">
        <template #header>
            <h2>Detail Per Bulan {{ selectedSiswa.nama }}</h2>
        </template>
        <template #default>
            <PerBulan
                :siswa="selectedSiswa"
                :rombel="selectedRombel"
                :bulanTahun="bulanTahun"
                v-if="formPerBulan"
            />
        </template>
    </el-dialog>
</template>
