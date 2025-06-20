<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DashLayout from "@/Layouts/DashLayout.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { ElCard, ElNotification } from "element-plus";
import { Icon } from "@iconify/vue";

const page = usePage();
const role = computed(() => page.props.auth.roles[0]);
const formImpor = ref(false);
const FormImpor = defineAsyncComponent(
    () => import("@/Components/Dashboard/FormImpor.vue"),
);
const formSekolah = ref(false);
const FormSekolah = defineAsyncComponent(
    () => import("@/Components/Dashboard/Sekolah/FormSekolah.vue"),
);

const DialogTutor = defineAsyncComponent(
    () => import("@/Components/Dashboard/Tutorial.vue"),
);
const tutor = ref(false);
const showTutor = () => (tutor.value = true);
const closeTutor = () => (tutor.value = false);

const search = ref("");
const sekolahs = computed(() => {
    return page.props.sekolahs.filter((sekolah) =>
        sekolah.nama.toLowerCase().includes(search.value.toLowerCase()),
    );
});

const closeForm = () => {
    formSekolah.value = false;
    selectedSekolah.value = null;
    router.reload({ only: ["sekolahs"] });
};

const closeImpor = () => {
    formImpor.value = false;
    router.reload({ only: ["sekolahs"] });
};
const fields = ref([
    "npsn",
    "nama",
    "logo",
    "alamat",
    "desa",
    "kecamatan",
    "kabupaten",
    "kode_pos",
    "telp",
    "email",
    "website",
    "ks_id",
]);

const selectedSekolah = ref(null);
const edit = (item) => {
    selectedSekolah.value = item;
    formSekolah.value = true;
};

const hapus = async (id) => {
    router.delete(route("dashboard.sekolah.destroy", { id: id }), {
        onSuccess: (page) => {
            ElNotification({
                title: "Info",
                message: "Data Sekolah dihapus",
                type: "success",
            });
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
    });
};

const addOps = async (id) => {
    // alert(id)
    router.post(route("dashboard.sekolah.ops.add", { id: id }), null, {
        onSuccess: (page) => {
            ElNotification({
                title: "Info",
                message: "Data Operator dibuat",
                type: "success",
            });
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
    });
};

// Show Gurus
const dialogGurus = ref(false);
const showGurus = (item) => {
    selectedSekolah.value = item;
    dialogGurus.value = true;
};
const onClosedDialogGurus = () => {
    selectedSekolah.value = {};
};

const Cetak = () => {
    let win = window.open(
        "/cetak/rekap/sekolah/rombel/siswa",
        "_blank",
        "width=1024,height=900",
    );
};
</script>
<template>
    <Head title="Data Sekolah" />

    <DashLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 leading-tight uppercase"
            >
                {{
                    page.props.auth.roles[0] !== "admin"
                        ? page.props.sekolahs[0]?.nama
                        : "Admin"
                }}
            </h2>
            {{ !page.props.auth.can.includes("add_guru") }}
        </template>

        <div class="page">
            <el-card>
                <template #header>
                    <div class="card-toolbar flex items-center justify-between">
                        <div class="card-title flex items-center">
                            <Icon icon="mdi:city-variant" class="mb-1" />
                            <span>Data Sekolah</span>
                        </div>
                        <div
                            class="card-toolbar--items flex justify-end items-center gap-1 flex-grow"
                        >
                            <el-input
                                v-model="search"
                                placeholder="Cari Sekolah"
                                clearable
                                class="hidden-sm-and-down"
                                style="width: 200px"
                            >
                                <template #suffix>
                                    <Icon icon="mdi:magnify" />
                                </template>
                            </el-input>
                            <el-button-group>
                                <el-button
                                    type="primary"
                                    @click="formSekolah = true"
                                    :disabled="
                                        !page.props.auth.can.includes(
                                            'add_school',
                                        )
                                    "
                                >
                                    <Icon icon="mdi-plus" />
                                    Baru
                                </el-button>
                                <el-button
                                    type="success"
                                    @click="formImpor = true"
                                    :disabled="
                                        !page.props.auth.can.includes(
                                            'add_school',
                                        )
                                    "
                                    class="hidden-sm-and-down"
                                >
                                    <Icon icon="mdi-file-excel" />
                                    Impor
                                </el-button>
                                <el-button
                                    type="danger"
                                    @click="Cetak"
                                    :disabled="
                                        !page.props.auth.can.includes(
                                            'add_school',
                                        )
                                    "
                                    class="hidden-sm-and-down"
                                >
                                    <Icon icon="mdi-printer" />
                                    Cetak
                                </el-button>
                            </el-button-group>
                            <el-button type="success" text @click="showTutor">
                                <Icon
                                    icon="mdi:information-variant-circle"
                                    class="text-2xl"
                                />
                            </el-button>
                        </div>
                    </div>
                </template>
                <el-table :data="sekolahs" height="80vh" size="small">
                    <el-table-column label="#" type="index"></el-table-column>
                    <el-table-column label="Logo">
                        <template #default="scope">
                            <img :src="scope.row.logo" class="w-10" />
                        </template>
                    </el-table-column>
                    <el-table-column label="NPSN">
                        <template #default="scope">
                            <el-button
                                :disabled="
                                    !page.props.auth.can.includes(
                                        'update_school',
                                    )
                                "
                                type="primary"
                                text
                                size="small"
                                @click="edit(scope.row)"
                                >{{ scope.row.npsn }}</el-button
                            >
                        </template>
                    </el-table-column>
                    <el-table-column prop="nama" label="Nama Sekolah" />
                    <el-table-column prop="alamat" label="Alamat" />
                    <el-table-column prop="desa" label="Desa" />
                    <el-table-column label="Jumlah Guru">
                        <template #default="scope">
                            <el-tag
                                class="cursor-pointer"
                                @click="showGurus(scope.row)"
                            >
                                {{ scope.row.gurus?.length }}
                            </el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="Jumlah Siswa">
                        <template #default="scope">
                            <el-tag class="cursor-pointer">
                                {{ scope.row.siswas?.length }}
                            </el-tag>
                        </template>
                    </el-table-column>
                    <el-table-column label="Kepala Sekolah">
                        <template #default="scope">
                            <div>
                                {{ scope.row.ks?.nama }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="OPS">
                        <template #default="scope">
                            <div>
                                {{ scope.row.ops?.nama }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Opsi">
                        <template #default="scope">
                            <span class="flex items-center gap-1">
                                <el-button
                                    circle
                                    :type="
                                        scope.row.ops ? 'warning' : 'primary'
                                    "
                                    size="small"
                                    @click="addOps(scope.row.id)"
                                    :disabled="
                                        !page.props.auth.can.includes(
                                            'add_ops',
                                        ) || !!scope.row.ops
                                    "
                                >
                                    <Icon icon="mdi:laptop-account" />
                                </el-button>
                                <el-popconfirm
                                    size="small"
                                    :title="`Yakin menghapus data ${scope.row.nama}?`"
                                    @confirm="hapus(scope.row.id)"
                                >
                                    <template #reference>
                                        <el-button
                                            circle
                                            type="danger"
                                            size="small"
                                            :disabled="
                                                !page.props.auth.can.includes(
                                                    'delete_school',
                                                )
                                            "
                                        >
                                            <Icon icon="mdi:delete" />
                                        </el-button>
                                    </template>
                                </el-popconfirm>
                            </span>
                        </template>
                    </el-table-column>
                </el-table>
            </el-card>
        </div>
        <FormSekolah
            :open="formSekolah"
            @close="closeForm"
            :selectedSekolah="selectedSekolah"
            v-if="formSekolah"
        />
        <FormImpor
            :open="formImpor"
            @close="closeImpor"
            :fields="fields"
            v-if="formImpor"
            title="Sekolah"
            url="dashboard.sekolah.impor"
        />
        <DialogTutor
            url="/videos/update-sekolah.mp4"
            @close="closeTutor"
            :show="tutor"
        />

        <el-dialog
            v-model="dialogGurus"
            @closed="onClosedDialogGurus"
            :title="`DATA GURU ${selectedSekolah?.nama}`"
        >
            <el-table :data="selectedSekolah.gurus">
                <el-table-column label="No" type="index" />
                <el-table-column label="NIP" prop="nip" />
                <el-table-column label="Nama" prop="nama" />
                <el-table-column label="Jabatan" prop="jabatan" />
            </el-table>
        </el-dialog>
    </DashLayout>
</template>
