<script setup>
import { ref, computed, defineAsyncComponent, onBeforeMount } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import DashLayout from "@/Layouts/DashLayout.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { ElCard, ElNotification } from "element-plus";
import { Icon } from "@iconify/vue";
import axios from "axios";
const page = usePage();

const formRombel = ref(false);
const FormRombel = defineAsyncComponent(
    () => import("@/Components/Dashboard/Rombel/FormRombel.vue"),
);
const rombelSiswa = ref(false);
const RombelSiswa = defineAsyncComponent(
    () => import("@/Components/Dashboard/Rombel/RombelSiswa.vue"),
);
const DialogTutor = defineAsyncComponent(
    () => import("@/Components/Dashboard/Tutorial.vue"),
);
const search = ref("");
const rombels = ref([]);

const closeForm = () => {
    formRombel.value = false;
    selectedRombel.value = null;
    router.reload({ only: ["rombels"] });
    //init();
};

const selectedRombel = ref(null);
const edit = (item) => {
    selectedRombel.value = item;
    formRombel.value = true;
};

const mgmSiswa = (item) => {
    selectedRombel.value = item;
    rombelSiswa.value = true;
};
const closeMgmSiswa = () => {
    rombelSiswa.value = false;
    selectedRombel.value = null;
};

const reloadData = () => {
    router.reload({ only: ["rombels"] });
};

const hapus = async (id) => {
    router.delete(route("dashboard.rombel.destroy", { id: id }), {
        onSuccess: (page) => {
            ElNotification({
                title: "Info",
                message: "Data Rombel dihapus",
                type: "success",
            });
            init();
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

const tutor = ref(false);
const showTutor = () => (tutor.value = true);
const closeTutor = () => (tutor.value = false);

const kktps = ref([]);
const simpanKktp = async (rombel) => {
    console.log(rombel.kktps);
    router.post(
        route("dashboard.rombel.kktp.store", {
            _query: {
                sekolahId: page.props.sekolahs[0].npsn,
                semester: page.props.periode.semester.kode,
                tapel: page.props.periode.tapel.kode,
                rombelId: rombel.kode,
                tingkat: rombel.tingkat,
            },
        }),
        { datas: rombel.kktps },
        {
            onSuccess: (page) => {
                ElNotification({
                    title: "Info",
                    message: page.props.flash.message,
                    type: "success",
                });
            },
        },
    );
};

const defaultFoto = (guru) => {
    return guru.jk == "Laki-laki"
        ? "/img/user_l.png"
        : guru.agama == "Islam"
          ? "/img/user_p_is.png"
          : "/img/user_p.png";
};
const fotoGuru = (guru) => {
    return guru.foto
        ? guru.foto
        : guru.jk == "Laki-laki"
          ? "/img/user_l.png"
          : guru.agama == "Islam"
            ? "/img/user_p_is.png"
            : "/img/user_p.png";
};
const init = async () => {
    await page.props.rombels.forEach((rombel, r) => {
        if (rombel.kktps.length > 0) {
            rombels.value.push(rombel);
        } else {
            rombels.value.push(rombel);
            page.props.sekolahs[0].mapels.forEach((mapel) => {
                rombels.value[r].kktps.push({
                    mapel_id: mapel.kode,
                    mapel: { label: mapel.label },
                    minimal: 0,
                });
            });
        }
    });
};
onBeforeMount(async () => {
    //init();
});
</script>
<template>
    <Head title="Data Rombel" />

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
        </template>
        <div class="page">
            <!-- {{ page.props.rombels }} -->
            <el-card>
                <template #header>
                    <div class="card-toolbar flex items-center justify-between">
                        <div class="card-title flex items-center">
                            <span class="uppercase"
                                >Data Rombel/Kelas
                                {{
                                    page.props.auth.roles[0] !== "admin"
                                        ? ""
                                        : "Semua Sekolah"
                                }}</span
                            >
                        </div>
                        <div
                            class="card-toolbar--items flex items-center gap-1"
                            v-if="page.props.auth.roles[0] == 'ops'"
                        >
                            <el-button-group class="flex-grow">
                                <el-button
                                    type="primary"
                                    @click="formRombel = true"
                                >
                                    <Icon icon="mdi-plus" />
                                    Baru
                                </el-button>
                            </el-button-group>
                            <el-input
                                v-model="search"
                                placeholder="Cari Rombel Berdasarkan Label"
                                clearable
                                class="hidden-sm-and-down"
                            >
                                <template #suffix>
                                    <Icon icon="mdi:magnify" />
                                </template>
                            </el-input>
                            <el-button type="success" text @click="showTutor">
                                <Icon
                                    icon="mdi:information-variant-circle"
                                    class="text-2xl"
                                />
                            </el-button>
                        </div>
                    </div>
                </template>
                <el-table
                    :data="page.props.rombels"
                    height="80vh"
                    size="small"
                    :default-sort="{ prop: 'label', order: 'ascending' }"
                >
                    <el-table-column
                        label="Sekolah"
                        v-if="page.props.auth.roles.includes('admin')"
                        width="150"
                    >
                        <template #default="scope">
                            <div>
                                {{ scope.row.sekolah.nama }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Kode" width="200">
                        <template #default="scope">
                            <el-button
                                type="primary"
                                plain
                                size="small"
                                @click="edit(scope.row)"
                                >{{ scope.row.kode }}</el-button
                            >
                        </template>
                    </el-table-column>
                    <el-table-column label="Label" prop="label" width="150" />

                    <el-table-column label="Wali Kelas" width="250">
                        <template #default="scope">
                            <p>
                                {{ scope.row.wali_kelas?.nama }}
                            </p>
                        </template>
                    </el-table-column>
                    <el-table-column label="Guru Pengajar" width="300">
                        <template #default="scope">
                            <ul class="list-decimal pl-4">
                                <li v-for="(gp, i) in scope.row.gurus" :key="i">
                                    <el-popover>
                                        <div>
                                            <img
                                                :src="fotoGuru(gp)"
                                                alt="Foto Guru"
                                                :onerror="
                                                    (e) =>
                                                        (e.target.src =
                                                            defaultFoto(gp))
                                                "
                                            />
                                        </div>
                                        <template #reference>
                                            {{ gp.nama }} [{{ gp.jabatan }}]
                                        </template>
                                    </el-popover>
                                </li>
                            </ul>
                        </template>
                    </el-table-column>
                    <el-table-column label="KKTP" width="100">
                        <template #default="scope">
                            <el-popover width="400px" trigger="click">
                                <template #reference>
                                    <el-button type="primary" size="small"
                                        >KKTP</el-button
                                    >
                                </template>
                                <div>
                                    <ul>
                                        <li
                                            v-for="(kktp, k) in scope.row.kktps"
                                            :key="k"
                                            class="flex items-center justify-between mb-2"
                                        >
                                            <span>{{ kktp.mapel?.label }}</span>
                                            <span class="w-[80px]">
                                                <el-input
                                                    type="number"
                                                    size="small"
                                                    v-model="kktp.minimal"
                                                ></el-input>
                                            </span>
                                        </li>
                                    </ul>
                                    <el-divider>
                                        <el-button
                                            type="primary"
                                            size="small"
                                            @click="simpanKktp(scope.row)"
                                            >Simpan</el-button
                                        >
                                    </el-divider>
                                </div>
                            </el-popover>
                        </template>
                    </el-table-column>
                    <el-table-column label="Anggota Rombel" width="180">
                        <template #default="scope">
                            <div class="text-center">
                                <el-tag type="primary"
                                    >Lk:
                                    {{
                                        scope.row.siswas?.filter(
                                            (sa) => sa.jk == "Laki-laki",
                                        ).length
                                    }},
                                </el-tag>
                                <el-tag type="danger"
                                    >Pr:
                                    {{
                                        scope.row.siswas?.filter(
                                            (sa) => sa.jk == "Perempuan",
                                        ).length
                                    }},
                                </el-tag>
                                <el-tag type="info"
                                    >Jml: {{ scope.row.siswas?.length }}</el-tag
                                >
                                <div class="my-2">
                                    <el-tooltip
                                        size="small"
                                        :content="`Masukkan Siswa ke ${scope.row.label}?`"
                                        placement="left"
                                    >
                                        <el-button
                                            type="primary"
                                            size="small"
                                            @click="mgmSiswa(scope.row)"
                                            :disabled="
                                                page.props.auth.roles[0] !==
                                                'ops'
                                            "
                                        >
                                            <Icon
                                                icon="mdi:account-plus-outline"
                                                class="mr-1"
                                            />
                                            Anggota
                                        </el-button>
                                    </el-tooltip>
                                </div>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Status" width="200">
                        <template #default="scope">
                            <span class="flex gap-2">
                                <Icon
                                    :icon="
                                        scope.row.is_active == '1'
                                            ? 'mdi:check-circle'
                                            : 'mdi:close-circle'
                                    "
                                    :class="
                                        scope.row.is_active == '1'
                                            ? 'text-green-600'
                                            : 'text-red-600'
                                    "
                                    class="text-xl"
                                />
                                {{ scope.row.is_active ? "AKTIF" : "NONAKTIF" }}
                            </span>
                        </template>
                    </el-table-column>
                    <el-table-column label="Opsi" width="150" fixed="right">
                        <template #default="scope">
                            <div class="flex items-center gap-1">
                                <el-popconfirm
                                    size="small"
                                    :title="`Yakin menghapus data ${scope.row.nama}?`"
                                    @confirm="hapus(scope.row.id)"
                                    v-if="page.props.auth.roles[0] == 'ops'"
                                >
                                    <template #reference>
                                        <el-button type="danger" size="small">
                                            <Icon
                                                icon="mdi:delete"
                                                class="mr-1"
                                            />
                                            Hapus
                                        </el-button>
                                    </template>
                                </el-popconfirm>
                            </div>
                        </template>
                    </el-table-column>
                </el-table>
            </el-card>
        </div>
        <FormRombel
            :open="formRombel"
            @close="closeForm"
            :selectedRombel="selectedRombel"
            v-if="formRombel"
        />
        <RombelSiswa
            :show="rombelSiswa"
            @close="closeMgmSiswa"
            :selectedRombel="selectedRombel"
            v-if="rombelSiswa"
            @refresh="reloadData"
        />
        <DialogTutor
            url="/videos/input-rombel.mp4"
            @close="closeTutor"
            :show="tutor"
        />
        <!-- <FormImpor :open="formImpor" @close="closeImpor" :fields="fields" v-if="formImpor" url="dashboard.guru.impor" title="Guru" /> -->
    </DashLayout>
</template>
