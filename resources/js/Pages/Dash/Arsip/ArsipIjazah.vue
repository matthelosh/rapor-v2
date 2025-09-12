<script setup>
import { ref, defineAsyncComponent } from "vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import { Search } from "@element-plus/icons-vue";
import axios from "axios";
const page = usePage();
const DashLayout = defineAsyncComponent(
    () => import("@/Layouts/DashLayout.vue"),
);

const loading = ref(false);
const arsip = ref({});
const siswa = ref(null);
const formArsip = ref(false);
const tambahArsip = (tapel, item) => {
    arsip.value = { siswa_id: item.nisn, tapel: tapel.kode };
    siswa.value = item;
    formArsip.value = true;
};

const maxSize = ref(2 * 1024 * 1024);
const fileIjazah = ref(null);
const fileTranskrip = ref(null);
const fileSkl = ref(null);
const inputIjazah = ref(null);
const inputTranskrip = ref(null);
const inputSkl = ref(null);

const onIjazahPicked = (e) => {
    const file = e.target.files[0];
    if (file.size > maxSize.value) {
        ElMessageBox.alert("Ukuran File Melebihi 2MB");
        fileIjazah.value = null;
        return false;
    }
    fileIjazah.value = e.target.files[0];
};

const onTranskripPicked = (e) => {
    const file = e.target.files[0];
    if (file.size > maxSize.value) {
        ElMessageBox.alert("Ukuran File Melebihi 2MB");
        fileTranskrip.value = null;
        return false;
    }
    fileTranskrip.value = e.target.files[0];
};

const onSklPicked = (e) => {
    const file = e.target.files[0];
    if (file.size > maxSize.value) {
        ElMessageBox.alert("Ukuran File Melebihi 2MB");
        fileSkl.value = null;
        return false;
    }
    fileSkl.value = e.target.files[0];
};

const editArsip = (item) => {
    // console.log(item)
    siswa.value = item;
    arsip.value = item.ijazah;
    formArsip.value = true;
};

const hapusArsip = (id) => {
    const elLoading = ElLoading.service({
        lock: true,
        text: "Loading",
        background: "rgba(0, 0, 0, 0.7)",
    });
    loading.value = true;
    router.delete(route("dashboard.arsip.ijazah.destroy", { id: id }), {
        onStart: () => {
            loading.value = true;
        },
        onSuccess: () => {
            router.reload({ only: ["tapels"] });
        },
        onFinish: () => {
            loading.value = false;
            elLoading.close();
        },
    });
};

const simpanArsip = () => {
    const elLoading = ElLoading.service({
        lock: true,
        text: "Loading",
        background: "rgba(0, 0, 0, 0.7)",
    });
    loading.value = true;
    const formData = new FormData();
    if (arsip.value.id) {
        formData.append("id", arsip.value.id);
        formData.append("_method", "PUT");
    }
    formData.append("no_seri", arsip.value.no_seri);
    formData.append("keterangan", arsip.value.keterangan);
    formData.append("siswa_id", arsip.value.siswa_id);
    formData.append("tapel", arsip.value.tapel);
    formData.append("file_ijazah", fileIjazah.value);
    formData.append("file_transkrip", fileTranskrip.value);
    formData.append("file_skl", fileSkl.value);
    axios.defaults.headers.common["Content-Type"] = "multipart/form-data";
    axios
        .post(
            route(
                arsip.value.id
                    ? "dashboard.arsip.ijazah.update"
                    : "dashboard.arsip.ijazah.store",
            ),
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            },
        )
        .then((res) => {
            loading.value = false;
            router.reload({ only: ["tapels"] });
        })
        .catch((err) => {
            loading.value = false;
        })
        .finally(() => {
            arsip.value = {};
            fileIjazah.value = null;
            fileTranskrip.value = null;
            fileSkl.value = null;
            formArsip.value = false;
            elLoading.close();
        });
};
</script>

<template>
    <Head title="Arsip Ijazah" />
    <DashLayout title="Arsip Ijazah">
        <template #header>
            <p class="uppercase">Arsip Ijazah</p>
        </template>
        <el-card>
            <template #header>
                <div class="flex justify-between">
                    <h3 class="font-bold text-sky-800">Pilih Tahun Ijazah</h3>
                </div>
            </template>
            <el-collapse accordion>
                <template v-for="(tapel, t) in page.props.tapels" :key="t">
                    <el-collapse-item>
                        <template #title>
                            <div class="p-2">
                                {{ tapel.label }}
                            </div>
                        </template>
                        <div class="collapse-body p-4 bg-slate-100">
                            <div class="content py-4">
                                <el-collapse accordion>
                                    <template
                                        v-for="(rombel, r) in tapel.rombels"
                                        :key="r"
                                    >
                                        <el-collapse-item>
                                            <template #title>
                                                <div class="p-2">
                                                    {{ rombel.label }}
                                                </div>
                                            </template>
                                            <div
                                                class="collapse-body p-4 bg-slate-100"
                                            >
                                                <div class="content py-4">
                                                    <el-table
                                                        :data="rombel.siswas"
                                                        max-height="400"
                                                    >
                                                        <el-table-column
                                                            label="#"
                                                            type="index"
                                                        />
                                                        <el-table-column
                                                            label="NISN"
                                                            prop="nisn"
                                                            width="160"
                                                        ></el-table-column>
                                                        <el-table-column
                                                            label="Nama Siswa"
                                                            prop="nama"
                                                        ></el-table-column>
                                                        <el-table-column
                                                            label="Arsip"
                                                        >
                                                            <template
                                                                #default="{
                                                                    row,
                                                                }"
                                                            >
                                                                <a
                                                                    v-if="
                                                                        row
                                                                            .ijazah
                                                                            ?.url_public
                                                                    "
                                                                    :href="
                                                                        row
                                                                            .ijazah
                                                                            ?.url_public
                                                                    "
                                                                    target="_blank"
                                                                    class="py-1 my-1 text-sky-500 flex gap-1 items-center"
                                                                >
                                                                    <Icon
                                                                        icon="mdi-magnify"
                                                                    />
                                                                    Ijazah
                                                                </a>
                                                                <a
                                                                    v-if="
                                                                        row
                                                                            .ijazah
                                                                            ?.url_transkrip_public
                                                                    "
                                                                    :href="
                                                                        row
                                                                            .ijazah
                                                                            .url_transkrip_public
                                                                    "
                                                                    target="_blank"
                                                                    class="py-1 my-1 text-sky-500 flex gap-1 items-center"
                                                                >
                                                                    <Icon
                                                                        icon="mdi-magnify"
                                                                    />
                                                                    Transkrip
                                                                </a>
                                                                <a
                                                                    v-if="
                                                                        row
                                                                            .ijazah
                                                                            ?.url_skl_public
                                                                    "
                                                                    :href="
                                                                        row
                                                                            .ijazah
                                                                            .url_skl_public
                                                                    "
                                                                    target="_blank"
                                                                    class="py-1 my-1 text-sky-500 flex gap-1 items-center"
                                                                >
                                                                    <Icon
                                                                        icon="mdi-magnify"
                                                                    />
                                                                    SKL
                                                                </a>
                                                            </template>
                                                        </el-table-column>
                                                        <el-table-column
                                                            label="Opsi"
                                                            fixed="right"
                                                            width="200"
                                                        >
                                                            <template
                                                                #default="{
                                                                    row,
                                                                }"
                                                            >
                                                                <div>
                                                                    <el-button-group
                                                                        size="small"
                                                                    >
                                                                        <el-button
                                                                            type="primary"
                                                                            @click="
                                                                                tambahArsip(
                                                                                    tapel,
                                                                                    row,
                                                                                )
                                                                            "
                                                                            v-if="
                                                                                !row.ijazah
                                                                            "
                                                                        >
                                                                            <Icon
                                                                                icon="mdi-plus"
                                                                            />
                                                                            Upload
                                                                        </el-button>
                                                                        <el-button
                                                                            type="warning"
                                                                            @click="
                                                                                editArsip(
                                                                                    row,
                                                                                )
                                                                            "
                                                                            v-else
                                                                        >
                                                                            <Icon
                                                                                icon="mdi-pencil"
                                                                            />
                                                                            Edit
                                                                        </el-button>
                                                                        <el-button
                                                                            type="danger"
                                                                            @click="
                                                                                hapusArsip(
                                                                                    row
                                                                                        .ijazah
                                                                                        .id,
                                                                                )
                                                                            "
                                                                        >
                                                                            <Icon
                                                                                icon="mdi-trash"
                                                                            />
                                                                            Hapus
                                                                        </el-button>
                                                                    </el-button-group>
                                                                </div>
                                                            </template>
                                                        </el-table-column>
                                                    </el-table>
                                                </div>
                                            </div>
                                        </el-collapse-item>
                                    </template>
                                </el-collapse>
                            </div>
                        </div>
                    </el-collapse-item>
                </template>
            </el-collapse>
        </el-card>

        <el-dialog
            v-model="formArsip"
            @close="formArsip = false"
            :loading="loading"
        >
            <template #header>
                <span class="flex items-center justify-between">
                    <h3>
                        Tambah Arsip Ijazah <b>{{ siswa?.nama }}</b>
                    </h3>
                </span>
            </template>
            <template #default>
                <el-form
                    v-model="arsip"
                    label-position="top"
                    :disabled="loading"
                >
                    <el-card>
                        <el-form-item label="No. Seri">
                            <el-input
                                v-model="arsip.no_seri"
                                placeholder="Masukkan No. Seri"
                            />
                        </el-form-item>
                        <el-form-item label="Keterangan">
                            <el-input
                                v-model="arsip.keterangan"
                                type="textarea"
                                placeholder="Keterangan [Boleh Kosong]"
                            />
                        </el-form-item>
                        <el-form-item label="Upload File">
                            <input
                                hidden
                                type="file"
                                @change="onIjazahPicked"
                                ref="inputIjazah"
                                :disabled="loading"
                                accept=".pdf,.jpg,.jpeg, .PDF, .JPG, .JPEG, .png, .PNG"
                            />
                            <input
                                hidden
                                type="file"
                                @change="onTranskripPicked"
                                ref="inputTranskrip"
                                :disabled="loading"
                                accept=".pdf,.jpg,.jpeg, .PDF, .JPG, .JPEG, .png, .PNG"
                            />
                            <input
                                hidden
                                type="file"
                                @change="onSklPicked"
                                ref="inputSkl"
                                :disabled="loading"
                                accept=".pdf,.jpg,.jpeg, .PDF, .JPG, .JPEG, .png, .PNG"
                            />
                            <el-button
                                :native-type="null"
                                :type="fileIjazah ? 'primary' : 'default'"
                                size="small"
                                block
                                @click.prevent="inputIjazah.click()"
                            >
                                <Icon
                                    icon="mdi-check-circle"
                                    class="mr-1"
                                    v-if="fileIjazah"
                                />
                                File Ijazah
                            </el-button>
                            <el-button
                                :native-type="null"
                                :type="fileTranskrip ? 'primary' : 'default'"
                                size="small"
                                block
                                @click.prevent="inputTranskrip.click()"
                            >
                                <Icon
                                    icon="mdi-check-circle"
                                    class="mr-1"
                                    v-if="fileTranskrip"
                                />
                                File Transkrip
                            </el-button>
                            <el-button
                                :native-type="null"
                                :type="fileSkl ? 'primary' : 'default'"
                                size="small"
                                block
                                @click.prevent="inputSkl.click()"
                            >
                                <Icon
                                    icon="mdi-check-circle"
                                    class="mr-1"
                                    v-if="fileSkl"
                                />
                                File SKL
                            </el-button>
                            <div class="w-full">
                                <ul class="list-disc text-slate-600 pl-4">
                                    <li v-if="fileIjazah">
                                        {{ fileIjazah.name }}
                                    </li>
                                    <li v-if="fileTranskrip">
                                        {{ fileTranskrip.name }}
                                    </li>
                                    <li v-if="fileSkl">
                                        {{ fileSkl.name }}
                                    </li>
                                </ul>
                            </div>
                        </el-form-item>
                        <template #footer>
                            <div class="flex justify-end">
                                <el-button
                                    type="primary"
                                    @click="formArsip = false"
                                    >Batal</el-button
                                >
                                <el-button type="primary" @click="simpanArsip"
                                    >Simpan</el-button
                                >
                            </div>
                        </template>
                    </el-card>
                </el-form>
            </template>
        </el-dialog>
    </DashLayout>
</template>
