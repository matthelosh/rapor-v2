<script setup>
import { ref } from "vue";
import { useForm, Head, router } from "@inertiajs/vue3";
import { Icon } from "@iconify/vue";
import DashLayout from "@/Layouts/DashLayout.vue";
import { ElMessage } from "element-plus";

const props = defineProps({
    siswas: Array,
    sekolah: Object,
    errors: Object
});

const form = useForm({
    siswa_id: '',
    no_induk: '',
    tanggal_masuk: '',
    asal_sekolah: '',
    status: 'aktif',
    tanggal_keluar: '',
    alasan_keluar: '',
    sekolah_tujuan: '',
    keterangan: ''
});

const statusOptions = [
    { label: 'Aktif', value: 'aktif' },
    { label: 'Lulus', value: 'lulus' },
    { label: 'Pindah', value: 'pindah' },
    { label: 'Keluar', value: 'keluar' }
];

const handleSubmit = () => {
    form.post(route('dashboard.bukuinduk.store'), {
        onSuccess: () => {
            ElMessage.success('Data buku induk berhasil ditambahkan');
        },
        onError: () => {
            ElMessage.error('Gagal menambahkan data buku induk');
        }
    });
};

const handleCancel = () => {
    router.visit(route('dashboard.bukuinduk.home'));
};
</script>

<template>
    <DashLayout>
        <Head title="Tambah Buku Induk" />
        <template #header>TAMBAH DATA BUKU INDUK</template>

        <div>
            <el-card>
                <template #header>
                    <div class="flex items-center justify-between">
                        <span class="font-semibold">Form Tambah Buku Induk</span>
                        <el-button
                            @click="handleCancel"
                            type="info"
                            plain
                        >
                            <Icon icon="material-symbols:arrow-back" class="mr-1" />
                            Kembali
                        </el-button>
                    </div>
                </template>

                <el-form
                    :model="form"
                    label-width="150px"
                    @submit.prevent="handleSubmit"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Kiri -->
                        <div>
                            <el-form-item
                                label="Pilih Siswa"
                                required
                                :error="errors.siswa_id"
                            >
                                <el-select
                                    v-model="form.siswa_id"
                                    placeholder="Pilih siswa"
                                    filterable
                                    class="w-full"
                                    :disabled="form.processing"
                                >
                                    <el-option
                                        v-for="siswa in siswas"
                                        :key="siswa.nisn"
                                        :label="`${siswa.nama} (${siswa.nisn})`"
                                        :value="siswa.nisn"
                                    />
                                </el-select>
                            </el-form-item>

                            <el-form-item
                                label="No. Induk"
                                :error="errors.no_induk"
                            >
                                <el-input
                                    v-model="form.no_induk"
                                    placeholder="Masukkan nomor induk"
                                    :disabled="form.processing"
                                />
                            </el-form-item>

                            <el-form-item
                                label="Tanggal Masuk"
                                required
                                :error="errors.tanggal_masuk"
                            >
                                <el-date-picker
                                    v-model="form.tanggal_masuk"
                                    type="date"
                                    placeholder="Pilih tanggal masuk"
                                    format="DD/MM/YYYY"
                                    value-format="YYYY-MM-DD"
                                    class="w-full"
                                    :disabled="form.processing"
                                />
                            </el-form-item>

                            <el-form-item
                                label="Asal Sekolah"
                                :error="errors.asal_sekolah"
                            >
                                <el-input
                                    v-model="form.asal_sekolah"
                                    placeholder="Masukkan asal sekolah"
                                    :disabled="form.processing"
                                />
                            </el-form-item>

                            <el-form-item
                                label="Status"
                                required
                                :error="errors.status"
                            >
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
                        </div>

                        <!-- Kolom Kanan -->
                        <div>
                            <el-form-item
                                label="Tanggal Keluar"
                                :error="errors.tanggal_keluar"
                            >
                                <el-date-picker
                                    v-model="form.tanggal_keluar"
                                    type="date"
                                    placeholder="Pilih tanggal keluar"
                                    format="DD/MM/YYYY"
                                    value-format="YYYY-MM-DD"
                                    class="w-full"
                                    :disabled="form.processing || form.status === 'aktif'"
                                />
                                <small class="text-gray-500">Kosongkan jika siswa masih aktif</small>
                            </el-form-item>

                            <el-form-item
                                label="Alasan Keluar"
                                :error="errors.alasan_keluar"
                            >
                                <el-input
                                    v-model="form.alasan_keluar"
                                    placeholder="Masukkan alasan keluar"
                                    :disabled="form.processing || form.status === 'aktif'"
                                />
                            </el-form-item>

                            <el-form-item
                                label="Sekolah Tujuan"
                                :error="errors.sekolah_tujuan"
                            >
                                <el-input
                                    v-model="form.sekolah_tujuan"
                                    placeholder="Masukkan sekolah tujuan"
                                    :disabled="form.processing || !['pindah', 'lulus'].includes(form.status)"
                                />
                                <small class="text-gray-500">Khusus untuk status pindah/lulus</small>
                            </el-form-item>

                            <el-form-item
                                label="Keterangan"
                                :error="errors.keterangan"
                            >
                                <el-input
                                    v-model="form.keterangan"
                                    type="textarea"
                                    :rows="4"
                                    placeholder="Masukkan keterangan tambahan"
                                    :disabled="form.processing"
                                />
                            </el-form-item>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex justify-end space-x-3 mt-6 pt-6 border-t">
                        <el-button
                            @click="handleCancel"
                            :disabled="form.processing"
                        >
                            Batal
                        </el-button>
                        <el-button
                            type="primary"
                            @click="handleSubmit"
                            :loading="form.processing"
                        >
                            <Icon icon="material-symbols:save" class="mr-1" />
                            Simpan Data
                        </el-button>
                    </div>
                </el-form>
            </el-card>
        </div>
    </DashLayout>
</template>

<style scoped>
.el-form-item {
    margin-bottom: 22px;
}
</style>
