<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/Ui/PageHeader.vue';
import Badge from '@/Components/Ui/Badge.vue';
import ConfirmModal from '@/Components/Ui/ConfirmModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    suppliers: { type: Object, required: true },
});

const deleteTarget = ref(null);
const deleteProcessing = ref(false);

const handleDelete = () => {
    deleteProcessing.value = true;
    router.delete(route('suppliers.destroy', deleteTarget.value.id), {
        onFinish: () => {
            deleteProcessing.value = false;
            deleteTarget.value = null;
        },
    });
};
</script>

<template>
    <Head title="Proveedores — Inventario" />
    <AuthenticatedLayout>
        <div class="p-6">
            <PageHeader title="Proveedores" subtitle="Gestiona los proveedores de tu inventario">
                <template #actions>
                    <Link
                        :href="route('suppliers.create')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-colors shadow-lg shadow-indigo-600/20"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Nuevo Proveedor
                    </Link>
                </template>
            </PageHeader>

            <div class="rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Proveedor</th>
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">RIF</th>
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Teléfono</th>
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden xl:table-cell">Contacto</th>
                                <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Productos</th>
                                <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Estado</th>
                                <th class="px-5 py-3.5" />
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            <tr v-if="suppliers.data.length === 0">
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <svg class="w-10 h-10 text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
                                    </svg>
                                    <p class="text-gray-400 font-medium">Sin proveedores registrados</p>
                                </td>
                            </tr>
                            <tr
                                v-for="supplier in suppliers.data"
                                :key="supplier.id"
                                class="hover:bg-gray-700/30 transition-colors group"
                            >
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-white">{{ supplier.name }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ supplier.email || '—' }}</p>
                                </td>
                                <td class="px-5 py-3.5 hidden lg:table-cell">
                                    <span class="font-mono text-gray-300 text-xs bg-gray-700 px-2 py-0.5 rounded">{{ supplier.rif }}</span>
                                </td>
                                <td class="px-5 py-3.5 hidden md:table-cell">
                                    <p class="text-gray-300">{{ supplier.phone || '—' }}</p>
                                </td>
                                <td class="px-5 py-3.5 hidden xl:table-cell">
                                    <p class="text-gray-400">{{ supplier.contact_person || '—' }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-white font-semibold tabular-nums">{{ supplier.products_count ?? 0 }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <Badge
                                        :variant="supplier.is_active ? 'success' : 'gray'"
                                        :text="supplier.is_active ? 'Activo' : 'Inactivo'"
                                        :dot="true"
                                    />
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <Link
                                            :href="route('suppliers.edit', supplier.id)"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-600 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </Link>
                                        <button
                                            @click="deleteTarget = supplier"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-400/10 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="suppliers.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-gray-700">
                    <p class="text-xs text-gray-400">Mostrando {{ suppliers.from }}–{{ suppliers.to }} de {{ suppliers.total }} registros</p>
                    <div class="flex gap-1">
                        <Link v-for="link in suppliers.links" :key="link.label" :href="link.url ?? '#'" v-html="link.label"
                            :class="['px-3 py-1.5 rounded-lg text-xs font-medium transition-colors', link.active ? 'bg-indigo-600 text-white' : link.url ? 'text-gray-400 hover:text-white hover:bg-gray-700' : 'text-gray-600 cursor-not-allowed']" />
                    </div>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="!!deleteTarget"
            :title="`¿Eliminar proveedor &quot;${deleteTarget?.name}&quot;?`"
            message="Los productos asociados perderán su referencia de proveedor. Esta acción no se puede deshacer."
            :processing="deleteProcessing"
            @confirm="handleDelete"
            @cancel="deleteTarget = null"
        />
    </AuthenticatedLayout>
</template>
