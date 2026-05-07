<script setup>
    import { ref, onBeforeMount } from 'vue';
    const props = defineProps({
        open: Boolean, rombel: Object, sekolah: Object, mapel: Object
    })
    const emit = defineEmits(['close'])

    const show = ref(false)
    onBeforeMount(() => {
        show.value = props.open
    })
</script>

<template>
    <div>
        <el-dialog v-model="show" fullscreen @close="emit('close')">
            <template #header>
                <h3>Halo</h3>
            </template>
        </el-dialog>
    </div>
</template>
