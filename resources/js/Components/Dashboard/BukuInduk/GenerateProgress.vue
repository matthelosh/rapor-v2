<script setup>
import { ref, onMounted } from "vue";
import { Icon } from "@iconify/vue";

const props = defineProps({
    show: Boolean,
    total: Number,
    current: Number,
    status: String,
    message: String
});

const emit = defineEmits(['close']);

const progress = computed(() => {
    if (!props.total || props.total === 0) return 0;
    return Math.round((props.current / props.total) * 100);
});

const statusConfig = {
    'processing': {
        color: 'primary',
        icon: 'material-symbols:autorenew'
    },
    'success': {
        color: 'success',
        icon: 'material-symbols:check-circle'
    },
    'error': {
        color: 'danger',
        icon: 'material-symbols:error'
    }
};

const currentConfig = computed(() => {
    return statusConfig[props.status] || statusConfig.processing;
});

const handleClose = () => {
    if (props.status !== 'processing') {
        emit('close');
    }
};
</script>

<template>
    <el-dialog
        :model-value="show"
        @update:model-value="handleClose"
        title="Progress Generate Buku Induk"
        width="400px"
        :close-on-click-modal="false"
        :show-close="status !== 'processing'"
    >
        <div class="text-center space-y-4">
            <!-- Icon -->
            <div>
                <Icon
                    :icon="currentConfig.icon"
                    class="text-6xl"
                    :class="`text-${currentConfig.color}-500`"
                />
            </div>

            <!-- Progress -->
            <div>
                <el-progress
                    :percentage="progress"
                    :status="currentConfig.color"
                    :stroke-width="8"
                />
                <p class="mt-2 text-sm text-gray-600">
                    {{ current }} dari {{ total }} siswa
                </p>
            </div>

            <!-- Status Message -->
            <div>
                <p class="font-semibold">{{ message }}</p>
                <p v-if="status === 'processing'" class="text-sm text-gray-500">
                    Mohon tunggu, jangan tutup halaman ini...
                </p>
            </div>
        </div>

        <template #footer v-if="status !== 'processing'">
            <div class="text-center">
                <el-button
                    type="primary"
                    @click="handleClose"
                >
                    Tutup
                </el-button>
            </div>
        </template>
    </el-dialog>
</template>
