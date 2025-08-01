<script setup>
import { ref, computed, defineAsyncComponent } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DashLayout from "@/Layouts/DashLayout.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { ElCard, ElNotification } from "element-plus";
import { Icon } from "@iconify/vue";
import { groupBy } from "lodash";
import { fotoGuru } from "@/helpers/Gambar.js";
import { utils, writeFile } from "xlsx";

const page = usePage();

const Pagination = defineAsyncComponent(
    () => import("@/Components/Dashboard/Pagination.vue"),
);
const formImpor = ref(false);
const FormImpor = defineAsyncComponent(
    () => import("@/Components/Dashboard/FormImpor.vue"),
);
const formGuru = ref(false);
const FormGuru = defineAsyncComponent(
    () => import("@/Components/Dashboard/Guru/FormGuru.vue"),
);
const search = ref("");
const gurus = computed(() => {
    // let datas = groupBy(page.props.gurus, 'sekolas')
    //return page.props.gurus.data.filter(guru => {
    //  return guru.nama.toLowerCase().includes(search.value.toLowerCase())
    //})
    return page.props.gurus.data;
});

const closeForm = () => {
    formGuru.value = false;
    selectedGuru.value = null;
    router.reload({ only: ["gurus"] });
};

const closeImpor = () => {
    formImpor.value = false;
    router.reload({ only: ["gurus"] });
};
const fields = ref([
    "nip",
    "gelar_depan",
    "nama",
    "gelar_belakang",
    "jk",
    "alamat",
    "hp",
    "status",
    "pangkat",
    "email",
    "agama",
    "jabatan",
]);

const cariGuru = async () => {
    router.visit(route("dashboard.guru", { _query: { q: search.value } }), {
        preserveState: true,
    });
};

const selectedGuru = ref(null);
const edit = (item) => {
    selectedGuru.value = item;
    formGuru.value = true;
};

const hapus = async (id) => {
    router.delete(route("dashboard.guru.destroy", { id: id }), {
        onSuccess: (page) => {
            ElNotification({
                title: "Info",
                message: page.props.flash.message,
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

const createAccount = async (id) => {
    router.post(
        route("dashboard.guru.account.add"),
        { id: id },
        {
            onSuccess: (page) => {
                ElNotification({
                    title: "Info",
                    message: page.props.flash.message,
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
        },
    );
};

const unduhFormat = async () => {
    let data = [
        {
            nip: "",
            nuptk: "",
            gelar_depan: "",
            nama: "",
            gelar_belakang: "",
            jk: "",
            alamat: "",
            hp: "",
            status: "",
            email: "",
            agama: "",
            pangkat: "",
            jabatan: "",
        },
    ];

    const ws = utils.json_to_sheet(data);
    const wb = utils.book_new();
    utils.book_append_sheet(wb, ws, "ORTU");
    writeFile(wb, "Format Impor Guru " + page.props.sekolahs[0].nama + ".xlsx");
};
</script>
<template>
    <Head title="Data Guru" />

    <DashLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 leading-tight uppercase"
            >
                {{ page.props.auth.roles[0] }}
            </h2>
        </template>

        <div class="page">
            <el-card>
                <template #header>
                    <div class="card-toolbar flex items-center justify-between">
                        <div class="card-title flex items-center">
                            <Icon icon="mdi:caccount-tie" class="mb-1" />
                            <span class="uppercase">Data Guru</span>
                        </div>
                        <div
                            class="card-toolbar--items flex items-center gap-1 px-2 bg-slate-100"
                        >
                            <el-button-group size="small" class="flex-grow-1">
                                <el-button
                                    type="primary"
                                    @click="formGuru = true"
                                >
                                    <Icon icon="mdi-plus" />
                                    Baru
                                </el-button>
                                <el-button
                                    type="warning"
                                    @click="unduhFormat"
                                    class="hidden-sm-and-down"
                                >
                                    <Icon icon="mdi-file-download" />
                                    Unduh Format
                                </el-button>
                                <el-button
                                    type="success"
                                    @click="formImpor = true"
                                    class="hidden-sm-and-down"
                                >
                                    <Icon icon="mdi-file-excel" />
                                    Impor
                                </el-button>
                            </el-button-group>
                            <el-input
                                size="small"
                                v-model="search"
                                placeholder="Cari Guru Berdasarkan Nama"
                                clearable
                                @change="cariGuru"
                                class="hidden-sm-and-down"
                                style="max-width: 35%"
                            >
                                <template #suffix>
                                    <Icon icon="mdi:magnify" />
                                </template>
                            </el-input>
                        </div>
                    </div>
                </template>
                <el-table
                    :data="gurus"
                    size="small"
                    :default-sort="{ prop: 'sekolahs', order: 'descending' }"
                    max-height="70vh"
                >
                    <el-table-column label="Foto" width="60">
                        <template #default="scope">
                            <el-avatar>
                                <img :src="fotoGuru(scope.row)" class="w-10" />
                            </el-avatar>
                        </template>
                    </el-table-column>
                    <el-table-column
                        label="Sekolah"
                        v-if="page.props.auth.roles.includes('admin')"
                    >
                        <template #default="scope">
                            <div>
                                <ul class="list-decimal pl-2">
                                    <li
                                        v-for="(
                                            sekolah, s
                                        ) in scope.row.sekolahs.map(
                                            (sk) => sk.nama,
                                        )"
                                        :key="s"
                                    >
                                        {{ sekolah }}
                                    </li>
                                </ul>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="NIP" width="150" :fixed="true">
                        <template #default="scope">
                            <el-button
                                type="primary"
                                text
                                size="small"
                                @click="edit(scope.row)"
                                >{{ scope.row.nip }}</el-button
                            >
                        </template>
                    </el-table-column>
                    <el-table-column label="Nama Guru">
                        <template #default="scope">
                            <p>
                                {{ scope.row.gelar_depan }}
                                {{ scope.row.nama }},
                                {{ scope.row.gelar_belakang }}
                            </p>
                        </template>
                    </el-table-column>
                    <el-table-column prop="status" label="Status" width="65" />
                    <el-table-column prop="alamat" label="Alamat" />
                    <el-table-column label="Jabatan" width="100">
                        <template #default="scope">
                            {{ scope.row.jabatan }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Akun" width="100">
                        <template #default="scope">
                            {{ scope.row.user?.name }}
                        </template>
                    </el-table-column>
                    <el-table-column prop="hp" label="Nomor HP" width="150" />
                    <el-table-column label="Opsi" width="80">
                        <template #default="scope">
                            <div class="flex items-center gap-1">
                                <span>
                                    <el-popconfirm
                                        v-if="!scope.row.user"
                                        size="small"
                                        :title="`Buatkan akun untuk ${scope.row.nama}?`"
                                        @confirm="createAccount(scope.row.id)"
                                    >
                                        <template #reference>
                                            <el-button
                                                circle
                                                type="primary"
                                                size="small"
                                            >
                                                <Icon
                                                    icon="mdi:account-plus-outline"
                                                />
                                            </el-button>
                                        </template>
                                    </el-popconfirm>
                                    <el-popconfirm
                                        v-else
                                        size="small"
                                        :title="`Reset Password ${scope.row.nama}?`"
                                        @confirm="createAccount(scope.row.id)"
                                    >
                                        <template #reference>
                                            <el-button
                                                circle
                                                type="warning"
                                                size="small"
                                            >
                                                <Icon
                                                    icon="mdi:account-reactivate"
                                                />
                                            </el-button>
                                        </template>
                                    </el-popconfirm>
                                </span>
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
                                        >
                                            <Icon icon="mdi:delete" />
                                        </el-button>
                                    </template>
                                </el-popconfirm>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
                <template #footer>
                    <Pagination :data="page.props.gurus" dataName="gurus" />
                </template>
            </el-card>
            <!-- <p>{{ gurus }}</p> -->
        </div>
        <FormGuru
            :open="formGuru"
            @close="closeForm"
            :selectedGuru="selectedGuru"
            v-if="formGuru"
        />
        <FormImpor
            :open="formImpor"
            @close="closeImpor"
            :fields="fields"
            v-if="formImpor"
            url="dashboard.guru.impor"
            title="Guru"
        />
    </DashLayout>
</template>
