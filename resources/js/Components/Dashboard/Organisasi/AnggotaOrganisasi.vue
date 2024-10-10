<script setup>
import { Icon } from '@iconify/vue';
import axios from 'axios';
import { onBeforeMount, ref } from 'vue';

const props = defineProps({org: Object, open: Boolean})
const emit = defineEmits(['close'])
const show = ref(false)
const loading = ref(false)
const nonMembers = ref([])
const members = ref([])

const getNonMember = async() => {
    loading.value = true
    await axios.get(route('dashboard.organisasi.nonmember', {
        _query: {orgId: props.org.id}
    }))
    .then(res => nonMembers.value = res.data.nonmembers)
    .finally(() => loading.value = false)
}
const getMember = async() => {
    await axios.get(route('dashboard.organisasi.member', {
        _query: {orgId: props.org.id}
    }))
    .then(res => members.value = res.data.members)
    .finally(() => loading.value = false)
}

const simpanMember = async() => {
    console.log(members.value)
    await axios.post(route('dashboard.organisasi.member.store', {
        _query: {orgId: props.org.id}
    }), {data: members.value})
    .then(res => members.value = res.data.members)
    .finally(() => loading.value = false)
}
onBeforeMount(() => {
    show.value = props.open
    getNonMember()
    members.value = props.org.members.length > 0 ? props.org.members.map(member => member.id) : []
})
</script>

<template>
    <el-dialog v-model="show" :show-close="false">
        <template #header>
            <div class="flex items-center justify-between">
                <h3>Form Anggota {{ props.org.nama }}</h3>
                <el-button circle type="danger" @click="emit('close')">
                    <Icon icon="mdi:close" />
                </el-button>
            </div>
        </template>
        <div class="flex gap-1 flex-wrap">
            <el-checkbox-group v-model="members">
            <template v-for="(guru,g) in nonMembers">
                <el-checkbox :value="guru.id" :label="guru.nama"></el-checkbox>
            </template>
        </el-checkbox-group>
        </div>
        <template #footer>
            <el-button type="primary" @click="simpanMember">Simpan</el-button>
        </template>
    </el-dialog>
</template>