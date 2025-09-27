<script setup>
import { ref, reactive } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { Icon } from '@iconify/vue'
import DashLayout from '@/Layouts/DashLayout.vue'

const page = usePage()
const apiClients = ref(page.props.apiClients || [])

const showModal = ref(false)
const editingClient = ref(null)
const form = reactive({
    client_id: '',
    client_secret: ''
})

const showCreateModal = () => {
    editingClient.value = null
    form.client_id = ''
    form.client_secret = ''
    showModal.value = true
}

const showEditModal = (client) => {
    editingClient.value = client
    form.client_id = client.client_id
    form.client_secret = ''
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    editingClient.value = null
    form.client_id = ''
    form.client_secret = ''
}

const saveClient = () => {
    if (editingClient.value) {
        // Update existing client
        router.put(`/dashboard/api-client/${editingClient.value.id}`, form, {
            onSuccess: () => {
                closeModal()
                // Refresh page to get updated data
                router.get('/dashboard/api-client')
            },
            onError: (errors) => {
                console.error('Error updating client:', errors)
            }
        })
    } else {
        // Create new client
        router.post('/dashboard/api-client', form, {
            onSuccess: () => {
                closeModal()
                // Refresh page to get updated data
                router.get('/dashboard/api-client')
            },
            onError: (errors) => {
                console.error('Error creating client:', errors)
            }
        })
    }
}

const deleteClient = (client) => {
    if (confirm(`Apakah Anda yakin ingin menghapus client "${client.client_id}"?`)) {
        router.delete(`/dashboard/api-client/${client.id}`, {
            onSuccess: () => {
                // Refresh page to get updated data
                router.get('/dashboard/api-client')
            },
            onError: (errors) => {
                console.error('Error deleting client:', errors)
            }
        })
    }
}

const regenerateSecret = (client) => {
    if (confirm(`Apakah Anda yakin ingin generate ulang Client Secret untuk "${client.client_id}"? Client Secret yang lama tidak akan bisa digunakan lagi.`)) {
        router.post(`/dashboard/api-client/${client.id}/regenerate-secret`, {}, {
            onSuccess: () => {
                // Refresh page to get updated data
                router.get('/dashboard/api-client')
            },
            onError: (errors) => {
                console.error('Error regenerating secret:', errors)
            }
        })
    }
}

const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text).then(() => {
        // Could add a toast notification here
        alert('Client Secret berhasil disalin ke clipboard')
    })
}
</script>

<template>
<DashLayout>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen API Client</h1>
            <button
                @click="showCreateModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"
            >
                <Icon icon="mdi:plus" />
                Tambah Client
            </button>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client Secret
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Dibuat Pada
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="client in apiClients" :key="client.id">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ client.client_id }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-500 font-mono">
                                    {{ client.client_secret.substring(0, 20) }}...
                                </span>
                                <button
                                    @click="copyToClipboard(client.client_secret)"
                                    class="text-blue-600 hover:text-blue-800"
                                    title="Copy Client Secret"
                                >
                                    <Icon icon="mdi:content-copy" />
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ new Date(client.created_at).toLocaleDateString('id-ID') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex justify-end gap-2">
                                <button
                                    @click="showEditModal(client)"
                                    class="text-indigo-600 hover:text-indigo-900"
                                >
                                    <Icon icon="mdi:pencil" />
                                </button>
                                <button
                                    @click="regenerateSecret(client)"
                                    class="text-yellow-600 hover:text-yellow-900"
                                    title="Generate Ulang Secret"
                                >
                                    <Icon icon="mdi:refresh" />
                                </button>
                                <button
                                    @click="deleteClient(client)"
                                    class="text-red-600 hover:text-red-900"
                                >
                                    <Icon icon="mdi:delete" />
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="apiClients.length === 0">
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Belum ada API Client yang terdaftar
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ editingClient ? 'Edit API Client' : 'Tambah API Client Baru' }}
                    </h3>

                    <form @submit.prevent="saveClient">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Client ID
                            </label>
                            <input
                                type="text"
                                v-model="form.client_id"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                required
                            />
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Client Secret (kosongkan untuk auto-generate)
                            </label>
                            <input
                                type="text"
                                v-model="form.client_secret"
                                minlength="64"
                                maxlength="64"
                                placeholder="Akan di-generate otomatis jika kosong"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono"
                            />
                            <p class="text-xs text-gray-500 mt-1">
                                Client Secret harus 64 karakter. Biarkan kosong untuk generate otomatis.
                            </p>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600"
                            >
                                Batal
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                            >
                                {{ editingClient ? 'Update' : 'Simpan' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</DashLayout>
</template>

<style scoped>
/* Additional styles if needed */
</style>
