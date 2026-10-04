<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/Ui/PageHeader.vue';
import Badge from '@/Components/Ui/Badge.vue';
import ConfirmModal from '@/Components/Ui/ConfirmModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    categories: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const deleteTarget = ref(null);
const deleteProcessing = ref(false);

const confirmDelete = (category) => {
    deleteTarget.value = category;
};

const handleDelete = () => {
    deleteProcessing.value = true;
    router.delete(route('categories.destroy', deleteTarget.value.id), {
        onFinish: () => {
            deleteProcessing.value = false;
            deleteTarget.value = null;
        },
    });
};
</script>

<template>
    <Head title="Categorías — Inventario" />
    <AuthenticatedLayout>
        <div class="p-6">
            <PageHeader title="Categorías" subtitle="Gestiona las categorías de tus productos">
                <template #actions>
                    <Link
                        :href="route('categories.create')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-colors shadow-lg shadow-indigo-600/20"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Nueva Categoría
                    </Link>
                </template>
            </PageHeader>

            <!-- Tabla -->
            <div class="rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Nombre</th>
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Descripción</th>
                                <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Productos</th>
                                <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Estado</th>
                                <th class="px-5 py-3.5" />
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            <tr v-if="categories.data.length === 0">
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <svg class="w-10 h-10 text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                    </svg>
                                    <p class="text-gray-400 font-medium">Sin categorías registradas</p>
                                    <p class="text-gray-600 text-xs mt-1">Crea tu primera categoría para organizar el inventario.</p>
                                </td>
                            </tr>
                            <tr
                                v-for="category in categories.data"
                                :key="category.id"
                                class="hover:bg-gray-700/30 transition-colors group"
                            >
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-white">{{ category.name }}</p>
                                </td>
                                <td class="px-5 py-3.5 hidden md:table-cell">
                                    <p class="text-gray-400 truncate max-w-xs">{{ category.description || '—' }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span class="text-white font-semibold tabular-nums">{{ category.products_count ?? 0 }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <Badge
                                        :variant="category.is_active ? 'success' : 'gray'"
                                        :text="category.is_active ? 'Activa' : 'Inactiva'"
                                        :dot="true"
                                    />
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <Link
                                            :href="route('categories.edit', category.id)"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-600 transition-colors"
                                            title="Editar"
                                        >
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </Link>
                                        <button
                                            @click="confirmDelete(category)"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-400/10 transition-colors"
                                            title="Eliminar"
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

                <!-- Paginación -->
                <div v-if="categories.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-gray-700">
                    <p class="text-xs text-gray-400">
                        Mostrando {{ categories.from }}–{{ categories.to }} de {{ categories.total }} registros
                    </p>
                    <div class="flex gap-1">
                        <Link
                            v-for="link in categories.links"
                            :key="link.label"
                            :href="link.url ?? '#'"
                            v-html="link.label"
                            :class="[
                                'px-3 py-1.5 rounded-lg text-xs font-medium transition-colors',
                                link.active
                                    ? 'bg-indigo-600 text-white'
                                    : link.url
                                        ? 'text-gray-400 hover:text-white hover:bg-gray-700'
                                        : 'text-gray-600 cursor-not-allowed'
                            ]"
                        />
                    </div>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="!!deleteTarget"
            :title="`¿Eliminar categoría &quot;${deleteTarget?.name}&quot;?`"
            message="Si la categoría tiene productos asociados, no podrá ser eliminada. Esta acción no se puede deshacer."
            :processing="deleteProcessing"
            @confirm="handleDelete"
            @cancel="deleteTarget = null"
        />
    </AuthenticatedLayout>
</template>
