<script setup>
import { ref, computed, onMounted } from "vue";
import { useForm, Head, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import DashLayout from "@/Layouts/DashLayout.vue";
import { ElMessage, ElMessageBox } from "element-plus";
import { fotoSiswa } from "@/helpers/Gambar";

const props = defineProps({
    sekolah: Object,
    siswaTanpaBukuInduk: Number,
    preview: Array,
    errors: Object
});

const form = useForm({
    tanggal_masuk: '',
    asal_sekolah: '',
    status: 'aktif',
    use_custom_no_induk: false,
    keterangan: 'Generated otomatis dari data siswa',
    confirm: false
});

const loading = ref(false);
const previewData = ref(props.preview || []);

const statusOptions = [
    { label: 'Aktif', value: 'aktif' },
    { label: 'Lulus', value: 'lulus' },
    { label: 'Pindah', value: 'pindah' },
    { label: 'Keluar', value: 'keluar' }
];

const refreshPreview = async () => {
    loading.value = true;
    try {
        const response = await axios.get(route('dashboard.bukuinduk.preview'));
        previewData.value = response.data.preview;
    } catch (error) {
        ElMessage.error('Gagal memuat preview');
    } finally {
        loading.value = false;
    }
};

const handleGenerate = () => {
    if (props.siswaTanpaBukuInduk === 0) {
        ElMessage.warning('Tidak ada siswa yang perlu di-generate buku induknya');
        return;
    }

    ElMessageBox.confirm(
        `Apakah Anda yakin ingin generate ${props.siswaTanpaBukuInduk} buku induk secara otomatis?`,
        'Konfirmasi Generate',
        {
            confirmButtonText: 'Ya, Generate',
            cancelButtonText: 'Batal',
            type: 'warning',
            dangerouslyUseHTMLString: true
        }
    ).then(() => {
        form.confirm = true;
        form.post(route('dashboard.bukuinduk.generate-bulk'), {
            onSuccess: () => {
                ElMessage.success('Generate buku induk berhasil');
            },
            onError: () => {
                ElMessage.error('Gagal generate buku induk');
            }
        });
    });
};

const generateSingle = async (siswa_nisn) => {
    try {
        loading.value = true;
        const response = await axios.post(
            route('dashboard.bukuinduk.generate-single', siswa_nisn),
            {
                tanggal_masuk: form.tanggal_masuk,
                asal_sekolah: form.asal_sekolah,
                status: form.status,
                use_custom_no_induk: form.use_custom_no_induk,
                keterangan: form.keterangan
            }
        );

        ElMessage.success('Buku induk berhasil dibuat');
        await refreshPreview();

    } catch (error) {
        ElMessage.error(error.response?.data?.message || 'Gagal membuat buku induk');
    } finally {
        loading.value = false;
    }
};

const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('id-ID');
};

onMounted(() => {
    // Set default tanggal masuk ke awal tahun ajaran
    const now = new Date();
    const tahunAjaran = now.getMonth() >= 6 ? now.getFullYear() : now.getFullYear() - 1;
    form.tanggal_masuk = `${tahunAjaran}-07-01`;
});
</script>

<template>
    <DashLayout>
        <Head title="Generate Buku Induk" />
        <template #header>GENERATE BUKU INDUK OTOMATIS</template>

        <div class="space-y-6">
            <!-- Statistics Card -->
            <el-card>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-6 bg-blue-50 rounded-lg">
                        <Icon icon="material-symbols:person-add" class="text-4xl text-blue-600 mb-2" />
                        <h3 class="text-2xl font-bold text-blue-600">{{ siswaTanpaBukuInduk }}</h3>
                        <p class="text-sm text-gray-600">Siswa Tanpa Buku Induk</p>
                    </div>

                    <div class="text-center p-6 bg-green-50 rounded-lg">
                        <Icon icon="material-symbols:auto-awesome" class="text-4xl text-green-600 mb-2" />
                        <h3 class="text-lg font-semibold text-green-600">Generate Otomatis</h3>
                        <p class="text-sm text-gray-600">Buat buku induk dari data siswa</p>
                    </div>

                    <div class="text-center p-6 bg-yellow-50 rounded-lg">
                        <Icon icon="material-symbols:edit-note" class="text-4xl text-yellow-600 mb-2" />
                        <h3 class="text-lg font-semibold text-yellow-600">Dapat Diedit</h3>
                        <p class="text-sm text-gray-600">Data dapat dilengkapi kemudian</p>
                    </div>
                </div>
            </el-card>

            <!-- Generate Form -->
            <el-card>
                <template #header>
                    <div class="flex items-center justify-between">
                        <span class="font-semibold">Pengaturan Generate</span>
                        <el-button
                            @click="refreshPreview"
                            :loading="loading"
                            type="info"
                            size="small"
                        >
                            <Icon icon="material-symbols:refresh" class="mr-1" />
                            Refresh Preview
                        </el-button>
                    </div>
                </template>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Pengaturan -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Pengaturan Default</h3>

                        <el-form :model="form" label-width="140px">
                            <el-form-item label="Tanggal Masuk" required>
                                <el-date-picker
                                    v-model="form.tanggal_masuk"
                                    type="date"
                                    placeholder="Pilih tanggal masuk"
                                    format="DD/MM/YYYY"
                                    value-format="YYYY-MM-DD"
                                    class="w-full"
                                    :disabled="form.processing"
                                />
                                <small class="text-gray-500">Default: Awal tahun ajaran</small>
                            </el-form-item>

                            <el-form-item label="Asal Sekolah">
                                <el-input
                                    v-model="form.asal_sekolah"
                                    placeholder="Kosongkan untuk auto-generate"
                                    :disabled="form.processing"
                                />
                                <small class="text-gray-500">Kosongkan untuk estimasi otomatis</small>
                            </el-form-item>

                            <el-form-item label="Status" required>
                                <el-select
                                    v-model="form.status"
                                    placeholder="Pilih status"
                                    class="w-full"
                                    :disabled="form.processing"
                                >
                                    <el-option
                                        v-for="option in statusOptions"
                                        :key="option.value"
                                        :label="option.label"
                                        :value="option.value"
                                    />
                                </el-select>
                            </el-form-item>

                            <el-form-item label="No. Induk">
                                <el-checkbox
                                    v-model="form.use_custom_no_induk"
                                    :disabled="form.processing"
                                >
                                    Generate custom (jika NIS kosong)
                                </el-checkbox>
                                <small class="block text-gray-500">
                                    Default: Gunakan NIS siswa sebagai No. Induk
                                </small>
                            </el-form-item>

                            <el-form-item label="Keterangan">
                                <el-input
                                    v-model="form.keterangan"
                                    type="textarea"
                                    :rows="3"
                                    placeholder="Keterangan tambahan"
                                    :disabled="form.processing"
                                />
                            </el-form-item>
                        </el-form>
                    </div>

                    <!-- Info & Actions -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Informasi</h3>

                        <div class="space-y-4">
                            <el-alert
                                title="Cara Kerja Generate"
                                type="info"
                                :closable="false"
                            >
                                <ul class="text-sm space-y-1">
                                    <li>• No. Induk = NIS siswa (atau auto-generate jika kosong)</li>
                                    <li>• Tanggal masuk diestimasikan dari angkatan/umur</li>
                                    <li>• Asal sekolah diestimasikan dari alamat</li>
                                    <li>• Status default: Aktif</li>
                                    <li>• Data dapat diedit setelah generate</li>
                                </ul>
                            </el-alert>

                            <div class="border-2 border-dashed border-gray-300 p-4 rounded-lg text-center">
                                <Icon icon="material-symbols:batch-prediction" class="text-3xl text-gray-400 mb-2" />
                                <p class="text-lg font-semibold">{{ siswaTanpaBukuInduk }} Siswa</p>
                                <p class="text-sm text-gray-600 mb-4">Siap untuk di-generate</p>

                                <el-button
                                    type="primary"
                                    size="large"
                                    @click="handleGenerate"
                                    :disabled="siswaTanpaBukuInduk === 0 || form.processing"
                                    :loading="form.processing"
                                >
                                    <Icon icon="material-symbols:play-arrow" class="mr-1" />
                                    Generate Semua
                                </el-button>
                            </div>

                            <el-button
                                @click="router.visit(route('dashboard.bukuinduk.home'))"
                                class="w-full"
                                :disabled="form.processing"
                            >
                                <Icon icon="material-symbols:arrow-back" class="mr-1" />
                                Kembali ke Buku Induk
                            </el-button>
                        </div>
                    </div>
                </div>
            </el-card>

            <!-- Preview -->
            <el-card v-if="previewData && previewData.length > 0">
                <template #header>
                    <span class="font-semibold">Preview Data ({{ previewData.length }} siswa pertama)</span>
                </template>

                <el-table :data="previewData" stripe>
                    <el-table-column label="Foto" width="70">
                        <template #default="{ row }">
                            <el-avatar size="small">
                                <img :src="fotoSiswa(row.siswa)" />
                            </el-avatar>
                        </template>
                    </el-table-column>

                    <el-table-column label="Nama Siswa" width="200">
                        <template #default="{ row }">
                            <div>
                                <p class="font-semibold">{{ row.siswa.nama.toUpperCase() }}</p>
                                <p class="text-sm text-gray-500">{{ row.siswa.nisn }}</p>
                            </div>
                        </template>
                    </el-table-column>

                    <el-table-column label="No. Induk" width="100">
                        <template #default="{ row }">
                            <el-tag type="info">{{ row.preview_data.no_induk }}</el-tag>
                        </template>
                    </el-table-column>

                    <el-table-column label="Tanggal Masuk" width="120">
                        <template #default="{ row }">
                            {{ formatDate(row.preview_data.tanggal_masuk) }}
                        </template>
                    </el-table-column>

                    <el-table-column label="Asal Sekolah" width="200">
                        <template #default="{ row }">
                            <span class="text-sm text-gray-600">
                                {{ row.preview_data.asal_sekolah || '-' }}
                            </span>
                        </template>
                    </el-table-column>

                    <el-table-column label="Status" width="80">
                        <template #default="{ row }">
                            <el-tag type="success">{{ row.preview_data.status.toUpperCase() }}</el-tag>
                        </template>
                    </el-table-column>

                    <el-table-column label="Aksi" width="120" fixed="right">
                        <template #default="{ row }">
                            <el-button
                                size="small"
                                type="primary"
                                @click="generateSingle(row.siswa.nisn)"
                                :loading="loading"
                            >
                                <Icon icon="material-symbols:add" class="mr-1" />
                                Generate
                            </el-button>
                        </template>
                    </el-table-column>
                </el-table>

                <div class="mt-4 text-center">
                    <el-alert
                        type="warning"
                        :closable="false"
                        show-icon
                    >
                        <template #title>
                            Ini hanya preview {{ previewData.length }} siswa pertama dari {{ siswaTanpaBukuInduk }} total siswa
                        </template>
                    </el-alert>
                </div>
            </el-card>

            <!-- Empty State -->
            <el-card v-else-if="siswaTanpaBukuInduk === 0">
                <div class="text-center py-12">
                    <Icon icon="material-symbols:check-circle" class="text-6xl text-green-500 mb-4" />
                    <h3 class="text-xl font-semibold mb-2">Semua Siswa Sudah Memiliki Buku Induk</h3>
                    <p class="text-gray-600 mb-6">Tidak ada siswa yang perlu di-generate buku induknya</p>
                    <el-button
                        type="primary"
                        @click="router.visit(route('dashboard.bukuinduk.home'))"
                    >
                        <Icon icon="material-symbols:list" class="mr-1" />
                        Lihat Daftar Buku Induk
                    </el-button>
                </div>
            </el-card>
        </div>
    </DashLayout>
</template>

<style scoped>
.el-form-item {
    margin-bottom: 18px;
}
</style>
