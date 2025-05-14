<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";

import DashLayout from "@/Layouts/DashLayout.vue";
import { ElNotification } from "element-plus";
import dayjs from "dayjs";
import "dayjs/locale/id";
dayjs.locale("id");
const LembarSoal = defineAsyncComponent(
    () => import("@/Components/Dashboard/Asesmen/LembarSoal.vue"),
);
const Monitor = defineAsyncComponent(
    () => import("@/Components/Dashboard/Asesmen/Monitor.vue"),
);

const page = usePage();
const mode = ref("list");
const loading = ref(false);
defineProps({ canAddAsesmen: Boolean });

const asesmens = computed(() => page.props.asesmens);

const asesmen = ref({});
const addAsesmen = () => {
    mode.value = "form";
};

const simpanAsesmen = async () => {
    let data = asesmen.value;
    // console.log(asesmen.value)
    data.sekolah_id = page.props.sekolahs[0].npsn;
    data.tapel = page.props.periode.tapel.kode;
    data.mulai = data.durasi[0];
    data.selesai = data.durasi[1];
    data.tanggal = dayjs(data.durasi[0]).format("YYYY-MM-DD");
    data.guru_id =
        page.props.auth.roles[0] !== "admin"
            ? page.props.auth.user.userable.nip
            : page.props.auth.user.id;
    data._method = asesmen.value.id ? "PUT" : "POST";
    // console.log(data)
    router.post(
        route(`dashboard.asesmen.${data.id ? "update" : "store"}`, {
            id: data.id ?? null,
        }),
        data,
        {
            onStart: () => (loading.value = true),
            onSuccess: () => {
                router.reload({ only: ["asesmens"] });
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
            onFinish: () => (loading.value = false),
        },
    );
};

const rules = ref({
    nama: [{ required: true }],
});

const selectedAsesmen = ref(null);
const showLembarSoal = (item) => {
    selectedAsesmen.value = item;
    mode.value = "lembar-soal";
};

const closeLembarSoal = () => {
    selectedAsesmen.value = null;
    mode.value = "list";
};

const edit = (item) => {
    item.durasi = [item.mulai, item.selesai];
    asesmen.value = item;
    asesmen.value.semester = item.semester.kode;
    // console.log(asesmen.value)
    mode.value = "form";
};

const mapels = computed(() => {
    return page.props.auth.roles[0] == "admin"
        ? page.props.mapels
        : page.props.sekolahs[0].mapels;
});

const hapus = async (item) => {
    router.delete(route("dashboard.asesmen.destroy", { id: item.id }), {
        onStart: () => (loading.value = true),
        onSuccess: () => {
            router.reload({ only: ["soals"] });
            mode.value = "list";
            asesmen.value = {};
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
        onFinish: () => (loading.value = false),
    });
};

const reloadData = async () => {
    router.reload();
};
const showMonitor = (item) => {
    selectedAsesmen.value = item;
    mode.value = "monitor";
};

const closeForm = () => {
    router.reload({ only: ["soals"] });
    mode.value = "list";
};
</script>

<template>
    <Head title="Asesmen" />
    <DashLayout title="Asesmen">
        <template #header>Asesmen</template>
        <el-card>
            <template #header>
                <div class="flex items-center justify-between">
                    <h3>Data Asesmen</h3>
                    <div class="flex items-center gap-1">
                        <el-button
                            type="primary"
                            size="small"
                            @click="addAsesmen"
                            :disabled="!canAddAsesmen"
                            v-if="mode == 'list'"
                            >Buat Asesmen</el-button
                        >
                        <el-button
                            type="danger"
                            size="small"
                            @click="closeForm"
                            :disabled="!canAddAsesmen"
                            v-if="mode == 'form'"
                            >Tutup</el-button
                        >
                    </div>
                </div>
            </template>
            <div class="list" v-if="mode == 'list'">
                <el-card shadow="never" class="mb-4">
                    <h3 class="text-blue-700 font-bold mb-4">
                        Tingkat Lembaga
                    </h3>
                    <el-table
                        :data="
                            asesmens.filter((ases) => ases.tingkat == 'lembaga')
                        "
                        border
                    >
                        <el-table-column
                            label="#"
                            type="index"
                            align="center"
                        ></el-table-column>
                        <el-table-column
                            label="Nama"
                            prop="nama"
                        ></el-table-column>
                        <el-table-column
                            label="Kelas"
                            width="100"
                            align="center"
                        >
                            <template #default="scope">
                                {{
                                    scope.row.rombel
                                        ? scope.row.rombel?.label
                                        : scope.row.kelas
                                }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Mapel">
                            <template #default="scope">
                                {{ scope.row.mapel.label }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Peserta"
                            width="100"
                            v-if="page.props.auth.roles[0] == 'admin'"
                            align="center"
                        >
                            <template #default="scope">
                                {{ scope.row.pesertas?.length }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Semester" width="100">
                            <template #default="scope">
                                {{ scope.row.semester.label }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Jml Soal"
                            width="100"
                            align="center"
                        >
                            <template #default="scope">
                                {{ scope.row.soals?.length }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Opsi" width="275" fixed="right">
                            <template #default="{ row }">
                                <div class="flex items-center">
                                    <el-button-group size="small">
                                        <el-button @click="edit(row)"
                                            >Edit</el-button
                                        >
                                        <el-button @click="showLembarSoal(row)"
                                            >Atur Soal</el-button
                                        >
                                        <el-button
                                            type="success"
                                            @click="showMonitor(row)"
                                            >Monitor</el-button
                                        >
                                        <el-popconfirm
                                            title="Hapus Asesmen?"
                                            confirm-text="OK"
                                            @confirm="hapus(row)"
                                        >
                                            <template #reference>
                                                <el-button type="danger"
                                                    >Hapus</el-button
                                                >
                                            </template>
                                        </el-popconfirm>
                                    </el-button-group>
                                </div>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-card>
                <el-card shadow="never" class="mb-4">
                    <h3 class="text-blue-700 font-bold mb-4">Tingkat Gugus</h3>
                    <el-table
                        :data="
                            asesmens.filter((ases) => ases.tingkat == 'gugus')
                        "
                        border
                    >
                        <el-table-column
                            label="#"
                            type="index"
                            align="center"
                        ></el-table-column>
                        <el-table-column
                            label="Nama"
                            prop="nama"
                        ></el-table-column>
                        <el-table-column
                            label="Kelas"
                            width="100"
                            align="center"
                        >
                            <template #default="scope">
                                {{
                                    scope.row.rombel
                                        ? scope.row.rombel?.label
                                        : scope.row.kelas
                                }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Mapel">
                            <template #default="scope">
                                {{ scope.row.mapel.label }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Peserta"
                            width="100"
                            v-if="page.props.auth.roles[0] == 'admin'"
                            align="center"
                        >
                            <template #default="scope">
                                {{ scope.row.pesertas?.length }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Semester" width="100">
                            <template #default="scope">
                                {{ scope.row.semester.label }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Jml Soal"
                            width="100"
                            align="center"
                        >
                            <template #default="scope">
                                {{ scope.row.soals?.length }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Opsi" width="275" fixed="right">
                            <template #default="{ row }">
                                <div class="flex items-center">
                                    <el-button-group size="small">
                                        <el-button
                                            @click="edit(row)"
                                            :disabled="
                                                row.guru_id !==
                                                page.props.auth.user.id
                                            "
                                            >Edit</el-button
                                        >
                                        <el-button @click="showLembarSoal(row)"
                                            >Atur Soal</el-button
                                        >
                                        <el-button
                                            type="success"
                                            @click="showMonitor(row)"
                                            >Monitor</el-button
                                        >
                                        <el-popconfirm
                                            title="Hapus Asesmen?"
                                            confirm-text="OK"
                                            @confirm="hapus(row)"
                                        >
                                            <template #reference>
                                                <el-button type="danger"
                                                    >Hapus</el-button
                                                >
                                            </template>
                                        </el-popconfirm>
                                    </el-button-group>
                                </div>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-card>
                <el-card shadow="never" class="mb-4">
                    <h3 class="text-blue-700 font-bold mb-4">
                        Tingkat Kecamatan
                    </h3>
                    <el-table
                        :data="
                            asesmens.filter(
                                (ases) => ases.tingkat == 'kecamatan',
                            )
                        "
                        border
                    >
                        <el-table-column
                            label="#"
                            type="index"
                            align="center"
                        ></el-table-column>
                        <el-table-column
                            label="Nama"
                            prop="nama"
                        ></el-table-column>
                        <el-table-column
                            label="Kelas"
                            width="100"
                            align="center"
                        >
                            <template #default="scope">
                                {{
                                    scope.row.rombel
                                        ? scope.row.rombel?.label
                                        : scope.row.kelas
                                }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Mapel">
                            <template #default="scope">
                                {{ scope.row.mapel.label }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Peserta"
                            width="100"
                            v-if="page.props.auth.roles[0] == 'admin'"
                            align="center"
                        >
                            <template #default="scope">
                                {{ scope.row.pesertas?.length }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Semester" width="100">
                            <template #default="scope">
                                {{ scope.row.semester.label }}
                            </template>
                        </el-table-column>
                        <el-table-column
                            label="Jml Soal"
                            width="100"
                            align="center"
                        >
                            <template #default="scope">
                                {{ scope.row.soals?.length }}
                            </template>
                        </el-table-column>
                        <el-table-column label="Opsi" width="275" fixed="right">
                            <template #default="{ row }">
                                <div class="flex items-center">
                                    <el-button-group size="small">
                                        <el-button
                                            @click="edit(row)"
                                            :disabled="
                                                parseInt(row.guru_id) !==
                                                parseInt(
                                                    page.props.auth.user.id,
                                                )
                                            "
                                            >Edit</el-button
                                        >
                                        <el-button
                                            @click="showLembarSoal(row)"
                                            :disabled="
                                                parseInt(row.guru_id) !==
                                                parseInt(
                                                    page.props.auth.user.id,
                                                )
                                            "
                                            >Atur Soal</el-button
                                        >
                                        <el-button
                                            type="success"
                                            @click="showMonitor(row)"
                                            >Monitor</el-button
                                        >
                                        <el-popconfirm
                                            title="Hapus Asesmen?"
                                            confirm-text="OK"
                                            @confirm="hapus(row)"
                                        >
                                            <template #reference>
                                                <el-button
                                                    type="danger"
                                                    :disabled="
                                                        parseInt(
                                                            row.guru_id,
                                                        ) !==
                                                        parseInt(
                                                            page.props.auth.user
                                                                .id,
                                                        )
                                                    "
                                                    >Hapus</el-button
                                                >
                                            </template>
                                        </el-popconfirm>
                                    </el-button-group>
                                </div>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-card>
            </div>
            <div
                class="form w-full lg:w-[90%] bg-slate-100 shadow p-2 md:p-4 mx-auto"
                v-if="mode == 'form'"
            >
                <h1
                    class="text-lg font-bold text-sky-700 text-center uppercase mb-4"
                >
                    Formulir Asesmen
                </h1>
                <el-form
                    v-model="asesmen"
                    label-position="top"
                    v-loading="loading"
                    :rules="rules"
                >
                    <el-row :gutter="20">
                        <!-- <el-col :span="6" :xs="24">
                        <el-form-item label="Kode">
                            <el-input :input-style="{border: 'red', background: '#ffefee', outline: 'red'}" v-model="asesmen.kode" placeholder="Kode Asesmen" :readonly="true"></el-input>
                        </el-form-item>
                    </el-col> -->
                        <el-col :span="8" :xs="24">
                            <el-form-item label="Nama Asesmen">
                                <el-input
                                    v-model="asesmen.nama"
                                    placeholder="Nama Asesmen"
                                ></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="10" :xs="24">
                            <el-form-item label="Petunjuk Pengerjaan">
                                <el-input
                                    type="textarea"
                                    autosize
                                    v-model="asesmen.deskripsi"
                                    placeholder="Keterangan"
                                    :readonly="false"
                                ></el-input>
                            </el-form-item>
                        </el-col>
                        <el-col :span="6" :xs="24">
                            <el-form-item label="Mapel">
                                <el-select
                                    v-model="asesmen.mapel_id"
                                    placeholder="Mapel"
                                    :readonly="false"
                                >
                                    <el-option
                                        v-for="(mapel, m) in mapels"
                                        :value="mapel.kode"
                                        :label="mapel.label"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="20">
                        <el-col
                            :span="3"
                            :xs="24"
                            v-if="asesmen.mapel_id == 'pabp'"
                        >
                            <el-form-item label="Agama">
                                <el-select
                                    v-model="asesmen.agama"
                                    placeholder="Agama"
                                    :readonly="false"
                                >
                                    <el-option
                                        v-for="(agama, a) in [
                                            'Islam',
                                            'Kristen',
                                            'Katolik',
                                            'Hindu',
                                            'Budha',
                                            'Konghuchu',
                                        ]"
                                        :value="agama"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col
                            :span="3"
                            :xs="24"
                            v-if="page.props.auth.roles[0] !== 'admin'"
                        >
                            <el-form-item label="Rombel">
                                <el-select
                                    v-model="asesmen.rombel_id"
                                    placeholder="Rombel"
                                    :readonly="false"
                                >
                                    <el-option
                                        v-for="(rombel, r) in page.props
                                            .sekolahs[0].rombels"
                                        :value="rombel.kode"
                                        :label="rombel.label"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="3" :xs="24">
                            <el-form-item label="Kelas">
                                <el-select
                                    v-model="asesmen.kelas"
                                    placeholder="Kelas"
                                    :readonly="false"
                                >
                                    <el-option
                                        v-for="(kelas, t) in [
                                            '1',
                                            '2',
                                            '3',
                                            '4',
                                            '5',
                                            '6',
                                        ]"
                                        :value="kelas"
                                        :label="`Kelas ${kelas}`"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="3" :xs="24">
                            <el-form-item label="Semester">
                                <el-select
                                    v-model="asesmen.semester"
                                    placeholder="Semester"
                                    :readonly="false"
                                >
                                    <el-option
                                        v-for="sem in ['1', '2']"
                                        :value="sem"
                                        :label="`Semester ${sem}`"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <el-col :span="3" :xs="24">
                            <el-form-item label="Tingkat">
                                <el-select
                                    v-model="asesmen.tingkat"
                                    placeholder="Tingkat"
                                    :readonly="false"
                                >
                                    <el-option
                                        v-for="(tingkat, t) in [
                                            'lembaga',
                                            'gugus',
                                            'kecamatan',
                                        ]"
                                        :value="tingkat"
                                        :label="`${tingkat[0].toUpperCase() + tingkat.substring(1)}`"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                        <!-- <el-col :span="3" :xs="24">
                        <el-form-item label="Tanggal">
                            <el-date-picker v-model="asesmen.tanggal" placeholder="Tanggal Pelaksanaan" :value-format="'YYYY-MM-DD'" ></el-date-picker>
                        </el-form-item>
                    </el-col> -->
                        <el-col :span="10" :xs="24">
                            <el-form-item label="Waktu Pelaksanaan">
                                <el-date-picker
                                    type="datetimerange"
                                    range-separator="s/d"
                                    start-placeholder="Mulai"
                                    end-placeholder="Selesai"
                                    v-model="asesmen.durasi"
                                    placeholder="Mulai"
                                    value-format="YYYY-MM-DD h:m:s"
                                ></el-date-picker>
                            </el-form-item>
                        </el-col>
                        <!-- <el-col :span="6" :xs="24">
                        <el-form-item label="Selesai">
                            <el-date-picker type="datetime" v-model="asesmen.selesai" placeholder="Selesai"  value-format="YYYY-MM-DD h:m:s"></el-date-picker>
                        </el-form-item>
                    </el-col> -->
                        <el-col :span="4" :xs="24">
                            <el-form-item label="Jenis Asesmen">
                                <el-select
                                    v-model="asesmen.jenis"
                                    placeholder="Jenis Asesmen"
                                >
                                    <el-option
                                        v-for="jenis in [
                                            'uh',
                                            'pts',
                                            'pas',
                                            'lainnya',
                                        ]"
                                        :value="jenis"
                                        :label="jenis.toUpperCase()"
                                    ></el-option>
                                </el-select>
                            </el-form-item>
                        </el-col>
                    </el-row>
                    <el-row :gutter="20" justify="center">
                        <el-button type="primary" @click="simpanAsesmen"
                            >Simpan</el-button
                        >
                    </el-row>
                </el-form>
            </div>
            <Teleport to="body">
                <LembarSoal
                    v-if="mode == 'lembar-soal' && selectedAsesmen !== null"
                    :selectedAsesmen="selectedAsesmen"
                    @close="closeLembarSoal"
                />
            </Teleport>
            <Teleport to="body">
                <Monitor
                    v-if="mode == 'monitor' && selectedAsesmen !== null"
                    :selectedAsesmen="selectedAsesmen"
                    @close="closeLembarSoal"
                    :show="mode == 'monitor'"
                    @update-data="reloadData"
                />
            </Teleport>
        </el-card>
    </DashLayout>
</template>

<style scoped>
.el-card__body {
    padding: 0 !important;
}

@media screen and(min-width: 414px) {
    .el-card__body {
        padding: 20px;
    }
}
</style>
