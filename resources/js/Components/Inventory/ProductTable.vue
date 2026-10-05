<script setup>
import { Link } from '@inertiajs/vue3';
import StatusBadge from '@/Components/Inventory/StatusBadge.vue';
import { formatUsd } from '@/utils/format';

defineProps({
    products: { type: Array, default: () => [] },
});

const emit = defineEmits(['delete']);

const stockPercent = (product) => {
    const reference = Math.max(product.min_stock * 2, 1);
    return Math.min((Math.max(product.current_stock, 0) / reference) * 100, 100);
};

const barColor = (status) => ({
    in_stock: 'bg-emerald-500',
    low_stock: 'bg-amber-500',
    out_of_stock: 'bg-rose-500',
}[status] ?? 'bg-slate-400');
</script>

<template>
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <!-- Estado vacío -->
        <div v-if="products.length === 0" class="flex flex-col items-center justify-center px-6 py-16 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-slate-700">No se encontraron productos</p>
            <p class="mt-1 text-xs text-slate-500">Prueba con otra búsqueda o cambia los filtros.</p>
        </div>

        <div v-else class="overflow-x-auto">
            <table id="products-table" class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50/80">
                    <tr class="text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                        <th scope="col" class="px-5 py-3">Producto</th>
                        <th scope="col" class="hidden px-5 py-3 md:table-cell">Categoría</th>
                        <th scope="col" class="px-5 py-3">Stock</th>
                        <th scope="col" class="hidden px-5 py-3 text-right sm:table-cell">Precio</th>
                        <th scope="col" class="px-5 py-3">Estado</th>
                        <th scope="col" class="px-5 py-3 text-right"><span class="sr-only">Acciones</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr
                        v-for="product in products"
                        :key="product.id"
                        class="group transition-colors duration-150 hover:bg-slate-50/80"
                    >
                        <td class="px-5 py-3.5">
                            <Link :href="route('products.show', product.id)" class="block min-w-0">
                                <p class="max-w-[16rem] truncate font-medium text-slate-900 group-hover:text-blue-700">{{ product.name }}</p>
                                <p class="mt-0.5 truncate text-xs text-slate-500">
                                    <span class="font-mono">{{ product.sku }}</span>
                                    <template v-if="product.supplier"> · {{ product.supplier }}</template>
                                </p>
                            </Link>
                        </td>
                        <td class="hidden px-5 py-3.5 text-slate-600 md:table-cell">
                            <span class="inline-flex rounded-md bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-700">
                                {{ product.category ?? 'Sin categoría' }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                <span class="w-10 font-semibold text-slate-900 tabular-nums">{{ product.current_stock }}</span>
                                <div class="hidden h-1.5 w-20 overflow-hidden rounded-full bg-slate-100 lg:block">
                                    <div
                                        :class="['h-full rounded-full transition-all duration-500', barColor(product.status)]"
                                        :style="{ width: `${stockPercent(product)}%` }"
                                    />
                                </div>
                            </div>
                            <p class="mt-0.5 text-[11px] text-slate-400">mín. {{ product.min_stock }}</p>
                        </td>
                        <td class="hidden px-5 py-3.5 text-right font-medium text-slate-900 tabular-nums sm:table-cell">
                            {{ formatUsd(product.price_usd) }}
                        </td>
                        <td class="px-5 py-3.5">
                            <StatusBadge :status="product.status" />
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-end gap-1">
                                <Link
                                    :href="route('products.edit', product.id)"
                                    :id="`edit-product-${product.id}`"
                                    title="Editar"
                                    class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-blue-50 hover:text-blue-700"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
                                    </svg>
                                </Link>
                                <button
                                    type="button"
                                    :id="`delete-product-${product.id}`"
                                    title="Eliminar"
                                    class="rounded-lg p-2 text-slate-400 transition-colors hover:bg-rose-50 hover:text-rose-600"
                                    @click="emit('delete', product)"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
