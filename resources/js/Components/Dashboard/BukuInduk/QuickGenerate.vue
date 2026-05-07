<script setup>
import { ref } from "vue";
import { Icon } from "@iconify/vue";
import { ElMessage } from "element-plus";
import axios from "axios";

const props = defineProps({
    siswa: Object,
    show: Boolean
});

const emit = defineEmits(['close', 'success']);

const loading = ref(false);
const form = ref({
    tanggal_masuk: '',
    asal_sekolah: '',
    status: 'aktif',
    use_custom_no_induk: false,
    keterangan: ''
});

const statusOptions = [
    { label: 'Aktif', value: 'aktif' },
    { label: 'Lulus', value: 'lulus' },
    { label: 'Pindah', value: 'pindah' },
    { label: 'Keluar', value: 'keluar' }
];

const handleGenerate = async () => {
    try {
        loading.value = true;

        const response = await axios.post(
            route('dashboard.bukuinduk.generate-single', props.siswa.nisn),
            form.value
        );

        ElMessage.success('Buku induk berhasil dibuat');
        emit('success', response.data.data);
        emit('close');

    } catch (error) {
        ElMessage.error(error.response?.data?.message || 'Gagal membuat buku induk');
    } finally {
        loading.value = false;
    }
};

const handleClose = () => {
    emit('close');
};

// Set default values when dialog opens
const initializeForm = () => {
    if (!props.siswa) return;

    // Set default tanggal masuk
    const now = new Date();
    const tahunAjaran = now.getMonth() >= 6 ? now.getFullYear() : now.getFullYear() - 1;
    form.value.tanggal_masuk = `${tahunAjaran}-07-01`;

    // Estimate asal sekolah
    form.value.asal_sekolah = `TK/PAUD ${props.siswa.kecamatan}`;
    form.value.keterangan = `Generated untuk ${props.siswa.nama}`;
};

// Watch for dialog open
watch(() => props.show, (newVal) => {
    if (newVal) {
        initializeForm();
    }
});
</script>

<template>
    <el-dialog
        :model-value="show"
        @update:model-value="handleClose"
        title="Generate Buku Induk"
        width="500px"
        :close-on-click-modal="false"
    >
        <div v-if="siswa" class="space-y-4">
            <!-- Siswa Info -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold mb-2">Data Siswa</h4>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div>
                        <span class="text-gray-600">Nama:</span>
                        <span class="font-semibold ml-2">{{ siswa.nama }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">NISN:</span>
                        <span class="font-semibold ml-2">{{ siswa.nisn }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">NIS:</span>
                        <span class="font-semibold ml-2">{{ siswa.nis || '-' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-600">Status:</span>
                        <span class="font-semibold ml-2">{{ siswa.status }}</span>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <el-form :model="form" label-width="120px" size="small">
                <el-form-item label="No. Induk">
                    <div class="w-full">
                        <el-input
                            :value="siswa.nis || 'Auto Generate'"
                            disabled
                            class="mb-2"
                        />
                        <el-checkbox v-model="form.use_custom_no_induk">
                            Generate nomor custom (jika NIS kosong)
                        </el-checkbox>
                    </div>
                </el-form-item>

                <el-form-item label="Tanggal Masuk" required>
                    <el-date-picker
                        v-model="form.tanggal_masuk"
                        type="date"
                        placeholder="Pilih tanggal"
                        format="DD/MM/YYYY"
                        value-format="YYYY-MM-DD"
                        class="w-full"
                        :disabled="loading"
                    />
                </el-form-item>

                <el-form-item label="Asal Sekolah">
                    <el-input
                        v-model="form.asal_sekolah"
                        placeholder="Masukkan asal sekolah"
                        :disabled="loading"
                    />
                </el-form-item>

                <el-form-item label="Status" required>
                    <el-select
                        v-model="form.status"
                        placeholder="Pilih status"
                        class="w-full"
                        :disabled="loading"
                    >
                        <el-option
                            v-for="option in statusOptions"
                            :key="option.value"
                            :label="option.label"
                            :value="option.value"
                        />
                    </el-select>
                </el-form-item>

                <el-form-item label="Keterangan">
                    <el-input
                        v-model="form.keterangan"
                        type="textarea"
                        :rows="2"
                        placeholder="Keterangan tambahan"
                        :disabled="loading"
                    />
                </el-form-item>
            </el-form>
        </div>

        <template #footer>
            <div class="flex justify-end space-x-2">
                <el-button @click="handleClose" :disabled="loading">
                    Batal
                </el-button>
                <el-button
                    type="primary"
                    @click="handleGenerate"
                    :loading="loading"
                >
                    <Icon icon="material-symbols:auto-awesome" class="mr-1" />
                    Generate
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>
