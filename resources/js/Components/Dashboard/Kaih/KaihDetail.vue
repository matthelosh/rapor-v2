<script setup>
const props = defineProps({
    siswa: Object,
    rombel: Object,
});
</script>

<template>
    <div>
        {{ siswa.kaihs }}
    </div>
</template>
