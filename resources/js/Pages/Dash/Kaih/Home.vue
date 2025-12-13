<script setup>
import { ref, defineAsyncComponent, computed, watch, onBeforeMount } from "vue";
import { router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import dayjs from "dayjs";
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
        wali_kelas: rombel.wali_kelas,
    };
};
const showDetail = (item, rombel) => {
    selectedSiswa.value = item;
    formDetail.value = true;
    selectedRombel.value = {
        kode: rombel.kode,
        label: rombel.label,
        tapel: rombel.tapel,
        wali_kelas: rombel.wali_kelas,
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
        wali_kelas: rombel.wali_kelas,
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
        wali_kelas: rombel.wali_kelas,
    };
};

const resetForm = () => {
    selectedKebiasaan.value = null;
    selectedSiswa.value = null;
    selectedRombel.value = null;
    // formPerBulan.value = false;
    // formPerKebiasaan.value = false;
    // formDetail.value = false;
};
const tahuns = computed(() => {
    const currentYear = new Date().getFullYear();
    const years = [];
    for (let i = currentYear - 5; i <= currentYear + 5; i++) {
        years.push(i);
    }
    return years;
});
const prosentase = (value) => {
    const day = dayjs(
        (tahun.value ?? new Date().getFullYear()) +
            "-" +
            (bulan.value ?? new Date().getMonth() + 1),
    );
    const daysCount = day.daysInMonth();
    return Math.round((value / daysCount) * 100);
};

const fetchData = () => {
    try {
        router.visit(route('dashboard.kaih.home', {
            bulan: bulan.value,
            tahun: tahun.value,
        }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
            method: "get",
        });
        // return response.data;
    } catch (error) {
        console.error(error);
    }
};

const bulanList = ref([
    { value: 1, label: "Januari" },
    { value: 2, label: "Februari" },
    { value: 3, label: "Maret" },
    { value: 4, label: "April" },
    { value: 5, label: "Mei" },
    { value: 6, label: "Juni" },
    { value: 7, label: "Juli" },
    { value: 8, label: "Agustus" },
    { value: 9, label: "September" },
    { value: 10, label: "Oktober" },
    { value: 11, label: "November" },
    { value: 12, label: "Desember" },
])

watch(
    () => bulan.value,
    () => {
        fetchData();
    },
);
watch(
    () => tahun.value,
    () => {
        fetchData();
    },
);

onBeforeMount(() => {
    bulan.value = new Date().getMonth() + 1;
    tahun.value = new Date().getFullYear();
})

</script>

<template>
    <DashLayout title="7 KAIH">
        <template #header>
            <h3>7 KAIH</h3>
        </template>
        <template #default>
            <el-card>
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
                                <template v-for="bulan in bulanList" :key="bulan.value">

                                <el-option
                                    :label="bulan.label"
                                    :value="bulan.value"

                                ></el-option>
                                </template>
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
                    <el-collapse
                        style="
                            background: transparent;
                            border-radius: 5px;
                            overflow: hidden;
                        "
                        accordion
                    >
                        <template
                            v-for="(rombel, r) in rombels"
                            :key="`rombel-${r}`"
                        >
                            <el-collapse-item
                                style="background: transparent !important"
                            >
                                <template #title>
                                    <div class="p-4 text-lg font-black">
                                        {{ rombel.label }} |
                                        {{ rombel.wali_kelas.nama }}
                                    </div>
                                </template>
                                <div class="px-6">
                                    <el-table
                                        :data="rombel.siswas"
                                        max-height="600"
                                        style="
                                            background: transparent !important;
                                        "
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
                                                                class="bar-item w-[50px] flex items-end justify-center cursor-pointer font-bold text-slate-600 rounded hover:shadow hover:-translate-y-1 transition-transform duration-300"
                                                                :class="
                                                                    prosentase(
                                                                        row
                                                                            .kebiasaan_count[
                                                                            k
                                                                        ],
                                                                    ) <= 30
                                                                        ? 'bg-red-300'
                                                                        : prosentase(
                                                                                row
                                                                                    .kebiasaan_count[
                                                                                    k
                                                                                ],
                                                                            ) <=
                                                                            60
                                                                          ? 'bg-yellow-300'
                                                                          : 'bg-green-400'
                                                                "
                                                                :style="`height: ${prosentase(row.kebiasaan_count[k])}px;`"
                                                                @click="
                                                                    showPerKebiasaan(
                                                                        row,
                                                                        rombel,
                                                                        k,
                                                                    )
                                                                "
                                                            >
                                                                {{
                                                                    prosentase(
                                                                        row
                                                                            .kebiasaan_count[
                                                                            k
                                                                        ],
                                                                    ) ?? "0"
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
    <el-dialog v-model="formSemester" fullscreen v-if="formSemester">
        <template #default>
            <FormSemester :siswa="selectedSiswa" :rombel="selectedRombel" />
        </template>
    </el-dialog>
    <el-dialog v-model="formDetail" fullscreen v-if="formDetail">
        <template #header>
            <h2>{{ selectedSiswa.nama }}</h2>
            <p>Kelas: {{ selectedRombel.label }}</p>
        </template>
        <template #default>
            <KaihDetail
                :siswa="selectedSiswa"
                :rombel="selectedRombel"
                :bulanTahun="bulanTahun"
            />
        </template>
    </el-dialog>
    <el-dialog
        v-model="formPerKebiasaan"
        fullscreen
        @closed="resetForm"
        v-if="formPerKebiasaan"
    >
        <template #header>
            <h2>{{ selectedKebiasaan }}: {{ selectedSiswa.nama }}</h2>
        </template>
        <template #default>
            <PerKebiasaan
                :siswa="selectedSiswa"
                :rombel="selectedRombel"
                :kebiasaan="selectedKebiasaan"
                :bulanTahun="bulanTahun"
            />
        </template>
    </el-dialog>
    <el-dialog
        v-model="formPerBulan"
        fullscreen
        @closed="resetForm"
        v-if="formPerBulan"
        style="background: #ffffff78; backdrop-filter: blur(5px)"
        :show-close="false"
    >
        <template #header="{close}">
            <div class="flex justify-between">
                <h2>Detail Per Bulan {{ selectedSiswa?.nama }}</h2>

                <el-button :native-type="null" @click="close" type="danger" :circle="true">
                    <Icon icon="mdi:close" class="text-lg" />
                </el-button>
            </div>
        </template>
        <template #default>
            <PerBulan
                :siswa="selectedSiswa"
                :rombel="selectedRombel"
                :bulanTahun="bulanTahun"
            />
        </template>
    </el-dialog>
</template>
