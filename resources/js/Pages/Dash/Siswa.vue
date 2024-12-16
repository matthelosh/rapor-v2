<script setup>
import {
    ref,
    computed,
    defineAsyncComponent,
    onBeforeMount,
    onMounted,
} from "vue";
import DashLayout from "@/Layouts/DashLayout.vue";
import { Head, usePage, router } from "@inertiajs/vue3";
import { ElCard, ElNotification } from "element-plus";
import { Icon } from "@iconify/vue";
import { groupBy } from "lodash";
import { utils, writeFile } from "xlsx";
import { fotoSiswa } from "@/helpers/Gambar";
import dayjs from "dayjs";
import "dayjs/locale/id";
dayjs.locale("id");

const page = usePage();

const formImpor = ref({
    show: false,
    url: "",
    fields: [],
});
const FormImpor = defineAsyncComponent(() =>
    import("@/Components/Dashboard/FormImpor.vue")
);
const SiswaDapodik = defineAsyncComponent(() =>
    import("@/Components/Dashboard/Siswa/SiswaDapodik.vue")
);
const formSiswa = ref(false);
const FormSiswa = defineAsyncComponent(() =>
    import("@/Components/Dashboard/Siswa/FormSiswa.vue")
);
const formOrtu = ref(false);
const FormOrtu = defineAsyncComponent(() =>
    import("@/Components/Dashboard/Siswa/FormOrtu.vue")
);
const search = ref(null);
const siswas = computed(() => {
    return page.props.siswas;
    // return !search.value
    //     ? page.props.siswas
    //     : siswa.nama.toLowerCase().includes(search.value.toLowerCase());
});

const loading = ref(false);

const closeForm = () => {
    formSiswa.value = false;
    selectedSiswa.value = null;
    router.reload({ only: ["siswas"] });
};

const closeImpor = () => {
    formImpor.value = {
        show: false,
        title: "",
        fields: [],
        url: "",
    };
    router.reload({ only: ["siswas"] });
};
const fieldSiswa = ref([
    "nisn",
    "nis",
    "nik",
    "nama",
    "jk",
    "tempat_lahir",
    "tanggal_lahir",
    "alamat",
    "rt",
    "rw",
    "desa",
    "kecamatan",
    "kode_pos",
    "kabupaten",
    "provinsi",
    "hp",
    "email",
    "foto",
    "agama",
    "angkatan",
    "sekolah_id",
]);

const fieldOrtu = ref([
    "nisn",
    "nama",
    "nik_ayah",
    "nama_ayah",
    "alamat_ayah",
    "hp_ayah",
    "nik_ibu",
    "nama_ibu",
    "hp_ibu",
    "alamat_ibu",
    "nik_wali",
    "nama_wali",
    "alamat_wali",
    "alamat_wali",
]);

const imporSiswa = () => {
    formImpor.value = {
        show: true,
        url: "dashboard.siswa.impor",
        title: "Siswa",
        fields: fieldSiswa.value,
    };
};
const imporOrtu = () => {
    formImpor.value = {
        show: true,
        url: "dashboard.siswa.ortu.impor",
        query: { rombelId: page.props.rombels[0].kode },
        title: "Ortu",
        fields: fieldOrtu.value,
    };
};

const selectedSiswa = ref(null);
const edit = (item) => {
    selectedSiswa.value = item;
    formSiswa.value = true;
};

const fotoUrl = (item) => {
    // console.log(item.foto)
    let foto = item.foto
        ? item.foto
        : item.jk == "Perempuan"
        ? item.agama == "Islam"
            ? "/img/siswi-is.png"
            : "/img/siswi.png"
        : "/img/siswa.png";
    return foto;
};

const hapus = async (id) => {
    router.delete(route("dashboard.siswa.destroy", { id: id }), {
        onSuccess: (page) => {
            ElNotification({
                title: "Info",
                message: "Data Siswa dihapus",
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
        route("dashboard.siswa.account.add"),
        { id: id },
        {
            onSuccess: (page) => {
                ElNotification({
                    title: "Info",
                    message: "Akun Berhasil dibuat",
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
        }
    );
};

// router.on('start', () => {
//     // alert('hi')
// })
const changeStatus = (e, siswa) => {
    siswa.status = e;
    // console.log(siswa)
    router.put(route("dashboard.siswa.update"), siswa, {
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

const openFormOrtu = (siswa) => {
    selectedSiswa.value = siswa;
    formOrtu.value = true;
};

const closeFormOrtu = () => {
    selectedSiswa.value = {};
    formOrtu.value = false;
};

const unduhFormatSiswa = async () => {
    let data = [
        {
            nisn: "",
            nis: "",
            nik: "",
            nama: "",
            jk: "",
            tempat_lahir: "",
            tanggal_lahir: "",
            alamat: "",
            rt: "",
            rw: "",
            desa: "",
            kecamatan: "",
            kode_pos: "",
            kabupaten: "",
            provinsi: "",
            hp: "",
            email: "",
            agama: "",
            angkatan: "",
            sekolah_id: "",
        },
    ];

    if (page.props.siswas && page.props.siswas.length > 0) {
        page.props.siswas.forEach((siswa) => {
            data.push({
                nisn: siswa.nisn,
                nis: siswa.nis,
                nik: siswa.nik,
                nama: siswa.nama,
                jk: siswa.jk,
                tempat_lahir: siswa.tempat_lahir,
                tanggal_lahir: siswa.tanggal_lahir,
                alamat: siswa.alamat,
                hp: siswa.hp,
                email: siswa.email,
                agama: siswa.agama,
                angkatan: siswa.angkatan,
                sekolah_id: siswa.sekolah_id,
            });
        });
    }
    const ws = utils.json_to_sheet(data);
    const wb = utils.book_new();
    utils.book_append_sheet(wb, ws, "SISWA");
    writeFile(
        wb,
        "Format Impor Siswa " + page.props.sekolahs[0].nama + ".xlsx"
    );
};

const unduhFormat = async () => {
    let data = [];
    if (page.props.siswas && page.props.siswas.length > 0) {
        page.props.siswas.forEach((siswa) => {
            data.push({
                nisn: siswa.nisn,
                nama: siswa.nama,
                nama_ayah:
                    siswa.ortus.map((ortu) =>
                        ortu.relasi == "Ayah" ? ortu.nama : ""
                    ) ?? "",
                alamat_ayah:
                    siswa.ortus.map((ortu) =>
                        ortu.relasi == "Ayah" ? ortu.alamat : ""
                    ) ?? "",
                hp_ayah:
                    siswa.ortus.map((ortu) =>
                        ortu.relasi == "Ayah" ? ortu.hp : ""
                    ) ?? "",
                nama_ibu:
                    siswa.ortus.map((ortu) =>
                        ortu.relasi == "Ibu" ? ortu.nama : ""
                    ) ?? "",
                alamat_ibu:
                    siswa.ortus.map((ortu) =>
                        ortu.relasi == "Ibu" ? ortu.alamat : ""
                    ) ?? "",
                hp_ibu:
                    siswa.ortus.map((ortu) =>
                        ortu.relasi == "Ibu" ? ortu.hp : ""
                    ) ?? "",
                nama_wali:
                    siswa.ortus.map((ortu) =>
                        ortu.relasi == "Wali" ? ortu.nama : ""
                    ) ?? "",
                alamat_wali:
                    siswa.ortus.map((ortu) =>
                        ortu.relasi == "Wali" ? ortu.alamat : ""
                    ) ?? "",
                hp_wali:
                    siswa.ortus.map((ortu) =>
                        ortu.relasi == "Wali" ? ortu.hp : ""
                    ) ?? "",
            });
        });
    }
    const ws = await utils.json_to_sheet(data);
    const wb = await utils.book_new();
    await utils.book_append_sheet(wb, ws, "ORTU");
    writeFile(wb, "Format Impor Ortu " + page.props.sekolahs[0].nama + ".xlsx");
};

const onFotoError = () => true;
// onMounted(async() => {
//     await router.reload({only: ['siswas']})
// })

const onCurrentChange = (num) => {
    router.visit(siswas.value.path + "?page=" + num, { preserveState: true });
};

const onSearchChanged = async () => {
    router.visit(siswas.value.path + "?q=" + search.value, {
        preserveState: true,
    });
};

const siswaDapodik = ref(false);
const closeSiswaDapodik = () => {
    siswaDapodik.value = false;
};
</script>
<template>
    <Head title="Data Siswa" />

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
            <el-card>
                <template #header>
                    <div class="card-toolbar flex items-center justify-between">
                        <div class="card-title flex items-center">
                            <span class="uppercase"
                                >Data Siswa
                                {{
                                    page.props.auth.roles[0] !== "admin"
                                        ? ""
                                        : "Semua Sekolah"
                                }}</span
                            >
                        </div>
                        <div
                            class="card-toolbar--items flex items-center justify-end px-2 md:w-[60%]"
                        >
                            <el-button-group
                                class="hidden-sm-and-down"
                                size="small"
                            >
                                <el-button type="success" @click="unduhFormat">
                                    <Icon icon="mdi:file-excel" />
                                    Unduh Format
                                </el-button>
                                <el-button type="warning" @click="imporOrtu">
                                    <Icon icon="mdi:human-male-female-child" />
                                    Impor Ortu
                                </el-button>
                            </el-button-group>
                            <el-button-group class="mx-2" size="small">
                                <el-button
                                    type="primary"
                                    @click="formSiswa = true"
                                >
                                    <Icon icon="mdi-plus" />
                                    Baru
                                </el-button>
                                <el-button
                                    type="danger"
                                    @click="siswaDapodik = true"
                                >
                                    <Icon icon="mdi:web-sync" />
                                    Impor Dapodik
                                </el-button>
                                <el-button
                                    type="success"
                                    @click="unduhFormatSiswa"
                                    class="hidden-sm-and-down"
                                >
                                    <Icon icon="mdi:file-excel-box" />
                                    Unduh Format
                                </el-button>
                                <el-button
                                    type="warning"
                                    @click="imporSiswa"
                                    class="hidden-sm-and-down"
                                >
                                    <Icon icon="mdi-file-excel" />
                                    Impor
                                </el-button>
                            </el-button-group>
                            <el-input
                                v-model="search"
                                placeholder="Cari Siswa Berdasarkan Nama"
                                clearable
                                style="width: 300px"
                                @change="onSearchChanged"
                                class="hidden-sm-and-down"
                                size="small"
                            >
                                <template #suffix>
                                    <Icon icon="mdi:magnify" />
                                </template>
                            </el-input>
                        </div>
                    </div>
                </template>
                <el-table
                    :data="siswas.data"
                    max-height="75vh"
                    v-loading="!siswas"
                >
                    <el-table-column label="#" type="index"></el-table-column>
                    <el-table-column
                        label="Sekolah"
                        v-if="page.props.auth.roles.includes('admin')"
                    >
                        <template #default="scope">
                            <div>
                                {{ scope.row.sekolah?.nama }}
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="NISN">
                        <template #default="{ row }">
                            <el-button type="primary" text @click="edit(row)">{{
                                row.nisn
                            }}</el-button>
                        </template>
                    </el-table-column>
                    <el-table-column label="Foto">
                        <template #default="scope">
                            <el-avatar
                                :src="scope.row.foto"
                                @error="onFotoError"
                            >
                                <img :src="fotoSiswa(scope.row)" />
                            </el-avatar>
                        </template>
                    </el-table-column>
                    <el-table-column label="Nama" prop="nama"></el-table-column>
                    <el-table-column label="JK" prop="jk"></el-table-column>
                    <el-table-column label="Tempat, Tanggal Lahir">
                        <template #default="scope">
                            {{ scope.row.tempat_lahir }},
                            {{
                                dayjs(scope.row.tanggal_lahir)
                                    .locale("id")
                                    .format("DD MMM YYYY")
                            }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Kelas">
                        <template #default="scope">
                            {{ scope.row.rombels[0]?.label }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Akun" width="100">
                        <template #default="scope">
                            {{ scope.row.user?.name }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Angkatan" width="100">
                        <template #default="scope">
                            {{ scope.row.angkatan }}
                        </template>
                    </el-table-column>
                    <el-table-column label="Orang Tua">
                        <template #default="scope">
                            <div>
                                <p>
                                    Ayah:
                                    {{
                                        scope.row.ortus.filter(
                                            (ayah) => ayah.relasi == "Ayah"
                                        )[0]?.nama
                                    }}
                                </p>
                                <p>
                                    Ibu:
                                    {{
                                        scope.row.ortus.filter(
                                            (ibu) => ibu.relasi == "Ibu"
                                        )[0]?.nama
                                    }}
                                </p>
                                <p
                                    v-if="
                                        scope.row.ortus.filter(
                                            (ortu) => ortu.relasi == 'Wali'
                                        ).length > 0
                                    "
                                >
                                    Wali:
                                    {{
                                        scope.row.ortus.filter(
                                            (wali) => wali.relasi == "Wali"
                                        )[0]?.nama
                                    }}
                                </p>
                            </div>
                        </template>
                    </el-table-column>
                    <el-table-column label="Status" width="130">
                        <template #default="scope">
                            <el-select
                                v-model="scope.row.status"
                                size="small"
                                @change="changeStatus($event, scope.row)"
                            >
                                <el-option
                                    v-for="s in [
                                        'aktif',
                                        'lulus',
                                        'do',
                                        'mutasi',
                                    ]"
                                    :key="s"
                                    :value="s"
                                    :label="s.toUpperCase()"
                                ></el-option>
                            </el-select>
                        </template>
                    </el-table-column>
                    <el-table-column label="Opsi" width="100" fixed="right">
                        <template #default="scope">
                            <div class="flex items-center gap-1">
                                <el-tooltip
                                    :content="`Ortu ${scope.row.nama}`"
                                    placement="left"
                                >
                                    <el-button
                                        circle
                                        type="success"
                                        size="small"
                                        @click="openFormOrtu(scope.row)"
                                    >
                                        <Icon
                                            icon="mdi:human-male-female-child"
                                        />
                                    </el-button>
                                </el-tooltip>
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
                    <div class="w-full flex items-center justify-between">
                        <p>Total: {{ siswas.total }}</p>
                        <el-pagination
                            :total="siswas.total"
                            layout="prev,pager,next"
                            background
                            @current-change="onCurrentChange"
                        ></el-pagination>
                    </div>
                </template>
            </el-card>
        </div>
        <FormSiswa
            :open="formSiswa"
            @close="closeForm"
            :selectedSiswa="selectedSiswa"
            v-if="formSiswa"
        />
        <FormImpor
            :open="formImpor.show"
            @close="closeImpor"
            :fields="formImpor.fields"
            v-if="formImpor.show"
            :url="formImpor.url"
            :query="formImpor.query"
            :title="formImpor.title"
        />
        <FormOrtu
            :open="formOrtu"
            @close="closeFormOrtu"
            v-if="formOrtu"
            :siswa="selectedSiswa"
        />
        <SiswaDapodik
            v-if="siswaDapodik"
            :show="siswaDapodik"
            @close="closeSiswaDapodik"
        />
    </DashLayout>
</template>
