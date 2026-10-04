<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/Ui/PageHeader.vue';
import Badge from '@/Components/Ui/Badge.vue';
import ConfirmModal from '@/Components/Ui/ConfirmModal.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    products:   { type: Object, required: true },
    categories: { type: Array, default: () => [] },
    filters:    { type: Object, default: () => ({}) },
});

const search      = ref(props.filters.search ?? '');
const categoryId  = ref(props.filters.category_id ?? '');
const lowStock    = ref(!!props.filters.low_stock);

let searchTimer = null;
watch([search, categoryId, lowStock], () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        router.get(route('products.index'), {
            search:      search.value || undefined,
            category_id: categoryId.value || undefined,
            low_stock:   lowStock.value ? 1 : undefined,
        }, { preserveState: true, replace: true });
    }, 350);
});

const deleteTarget     = ref(null);
const deleteProcessing = ref(false);

const handleDelete = () => {
    deleteProcessing.value = true;
    router.delete(route('products.destroy', deleteTarget.value.id), {
        onFinish: () => { deleteProcessing.value = false; deleteTarget.value = null; },
    });
};

const stockVariant = (product) => {
    if (product.current_stock <= 0) return 'danger';
    if (product.current_stock <= product.min_stock) return 'warning';
    return 'success';
};

const stockLabel = (product) => {
    if (product.current_stock <= 0) return 'Agotado';
    if (product.current_stock <= product.min_stock) return 'Stock bajo';
    return 'En stock';
};
</script>

<template>
    <Head title="Productos — Inventario" />
    <AuthenticatedLayout>
        <div class="p-6 space-y-5">
            <PageHeader title="Productos" subtitle="Gestiona el catálogo de productos del inventario">
                <template #actions>
                    <Link
                        :href="route('products.create')"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-colors shadow-lg shadow-indigo-600/20"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Nuevo Producto
                    </Link>
                </template>
            </PageHeader>

            <!-- Filtros -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative flex-1 min-w-48">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input v-model="search" type="text" placeholder="Buscar por nombre, SKU o código de barras..."
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-800 border border-gray-700 text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors" />
                </div>
                <select v-model="categoryId"
                    class="px-4 py-2.5 rounded-xl bg-gray-800 border border-gray-700 text-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors min-w-40">
                    <option value="">Todas las categorías</option>
                    <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                </select>
                <button @click="lowStock = !lowStock"
                    :class="['inline-flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium border transition-colors',
                        lowStock ? 'bg-amber-500/10 border-amber-500/30 text-amber-400' : 'bg-gray-800 border-gray-700 text-gray-400 hover:text-white']">
                    <span :class="['w-2 h-2 rounded-full', lowStock ? 'bg-amber-400' : 'bg-gray-600']" />
                    Solo stock bajo
                </button>
            </div>

            <!-- Tabla -->
            <div class="rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Producto</th>
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Categoría</th>
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden xl:table-cell">Proveedor</th>
                                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Precio USD</th>
                                <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Stock</th>
                                <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden sm:table-cell">Estado</th>
                                <th class="px-5 py-3.5" />
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            <tr v-if="products.data.length === 0">
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <svg class="w-10 h-10 text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                    </svg>
                                    <p class="text-gray-400 font-medium">
                                        {{ search || categoryId || lowStock ? 'Sin resultados para los filtros aplicados' : 'Sin productos registrados' }}
                                    </p>
                                </td>
                            </tr>
                            <tr
                                v-for="product in products.data"
                                :key="product.id"
                                class="hover:bg-gray-700/30 transition-colors group"
                            >
                                <td class="px-5 py-3.5">
                                    <Link :href="route('products.show', product.id)" class="block group/link">
                                        <p class="font-medium text-white group-hover/link:text-indigo-400 transition-colors">{{ product.name }}</p>
                                        <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                            <span class="font-mono text-xs text-gray-500">{{ product.sku }}</span>
                                            <span v-if="product.barcode" class="font-mono text-xs text-gray-600">{{ product.barcode }}</span>
                                        </div>
                                    </Link>
                                </td>
                                <td class="px-5 py-3.5 hidden lg:table-cell">
                                    <span class="text-gray-300 text-sm">{{ product.category?.name ?? '—' }}</span>
                                </td>
                                <td class="px-5 py-3.5 hidden xl:table-cell">
                                    <span class="text-gray-400 text-sm">{{ product.supplier?.name ?? '—' }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <span class="text-white font-semibold tabular-nums">${{ Number(product.price_usd).toFixed(2) }}</span>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <div class="flex flex-col items-center">
                                        <span :class="['text-sm font-bold tabular-nums', product.current_stock <= 0 ? 'text-red-400' : product.current_stock <= product.min_stock ? 'text-amber-400' : 'text-white']">
                                            {{ product.current_stock }}
                                        </span>
                                        <span class="text-xs text-gray-600">mín: {{ product.min_stock }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-center hidden sm:table-cell">
                                    <Badge :variant="stockVariant(product)" :text="stockLabel(product)" :dot="true" />
                                </td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <Link :href="route('products.show', product.id)"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-indigo-400 hover:bg-indigo-400/10 transition-colors" title="Ver detalle">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </Link>
                                        <Link :href="route('products.edit', product.id)"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-600 transition-colors" title="Editar">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                            </svg>
                                        </Link>
                                        <button @click="deleteTarget = product"
                                            class="p-1.5 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-400/10 transition-colors" title="Eliminar">
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
                <div v-if="products.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-gray-700">
                    <p class="text-xs text-gray-400">Mostrando {{ products.from }}–{{ products.to }} de {{ products.total }} productos</p>
                    <div class="flex gap-1">
                        <Link v-for="link in products.links" :key="link.label" :href="link.url ?? '#'" v-html="link.label"
                            :class="['px-3 py-1.5 rounded-lg text-xs font-medium transition-colors', link.active ? 'bg-indigo-600 text-white' : link.url ? 'text-gray-400 hover:text-white hover:bg-gray-700' : 'text-gray-600 cursor-not-allowed']" />
                    </div>
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="!!deleteTarget"
            :title="`¿Eliminar &quot;${deleteTarget?.name}&quot;?`"
            message="Esta acción eliminará el producto y no podrá recuperarse. El historial de movimientos asociado se mantendrá."
            :processing="deleteProcessing"
            @confirm="handleDelete"
            @cancel="deleteTarget = null"
        />
    </AuthenticatedLayout>
</template>
