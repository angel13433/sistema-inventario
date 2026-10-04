<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Badge from '@/Components/Ui/Badge.vue';
import PageHeader from '@/Components/Ui/PageHeader.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    product:   { type: Object, required: true },
    movements: { type: Object, required: true },
});

const movementMeta = {
    entry:      { label: 'Entrada',  variant: 'success', sign: '+' },
    exit:       { label: 'Salida',   variant: 'danger',  sign: '-' },
    adjustment: { label: 'Ajuste',   variant: 'warning', sign: '±' },
};

const formatDate = (d) => d ? new Date(d).toLocaleString('es-VE', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '—';

const stockPercent = Math.min(
    Math.round((props.product.current_stock / Math.max(props.product.min_stock, 1)) * 100),
    200
);
</script>

<template>
    <Head :title="`${product.name} — Inventario`" />
    <AuthenticatedLayout>
        <div class="p-6 space-y-6">
            <PageHeader
                :title="product.name"
                :subtitle="`SKU: ${product.sku}`"
                :back-href="route('products.index')"
            >
                <template #actions>
                    <Link
                        :href="route('inventory-movements.index')"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium border border-gray-600 transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                        </svg>
                        Registrar Movimiento
                    </Link>
                    <Link
                        :href="route('products.edit', product.id)"
                        class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-colors shadow-lg shadow-indigo-600/20"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                        </svg>
                        Editar
                    </Link>
                </template>
            </PageHeader>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                <!-- Información del producto -->
                <div class="space-y-5">
                    <!-- Stock card -->
                    <div :class="['rounded-2xl border p-5', product.current_stock <= 0 ? 'bg-red-500/5 border-red-500/20' : product.current_stock <= product.min_stock ? 'bg-amber-500/5 border-amber-500/20' : 'bg-emerald-500/5 border-emerald-500/20']">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-medium text-gray-300">Stock Actual</p>
                            <Badge
                                :variant="product.current_stock <= 0 ? 'danger' : product.current_stock <= product.min_stock ? 'warning' : 'success'"
                                :text="product.current_stock <= 0 ? 'Agotado' : product.current_stock <= product.min_stock ? 'Stock bajo' : 'En stock'"
                                :dot="true"
                            />
                        </div>
                        <p :class="['text-4xl font-bold tabular-nums', product.current_stock <= 0 ? 'text-red-400' : product.current_stock <= product.min_stock ? 'text-amber-400' : 'text-white']">
                            {{ product.current_stock }}
                        </p>
                        <p class="text-gray-400 text-sm mt-1">Mínimo requerido: <span class="text-white font-medium">{{ product.min_stock }}</span></p>
                        <div class="mt-3 h-2 rounded-full bg-gray-700 overflow-hidden">
                            <div
                                :class="['h-full rounded-full transition-all', product.current_stock <= 0 ? 'bg-red-500' : product.current_stock <= product.min_stock ? 'bg-amber-500' : 'bg-emerald-500']"
                                :style="`width: ${Math.min(stockPercent, 100)}%`"
                            />
                        </div>
                    </div>

                    <!-- Detalles -->
                    <div class="rounded-2xl bg-gray-800 border border-gray-700 p-5 space-y-3.5">
                        <h3 class="text-sm font-semibold text-white">Detalles del Producto</h3>
                        <div class="space-y-2.5">
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-700/50">
                                <span class="text-xs text-gray-400">Precio USD</span>
                                <span class="text-sm font-bold text-emerald-400">${{ Number(product.price_usd).toFixed(2) }}</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-700/50">
                                <span class="text-xs text-gray-400">Categoría</span>
                                <span class="text-sm text-white">{{ product.category?.name ?? '—' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5 border-b border-gray-700/50">
                                <span class="text-xs text-gray-400">Proveedor</span>
                                <span class="text-sm text-white">{{ product.supplier?.name ?? '—' }}</span>
                            </div>
                            <div v-if="product.barcode" class="flex justify-between items-center py-1.5 border-b border-gray-700/50">
                                <span class="text-xs text-gray-400">Código de Barras</span>
                                <span class="font-mono text-xs text-gray-300 bg-gray-700 px-2 py-0.5 rounded">{{ product.barcode }}</span>
                            </div>
                            <div class="flex justify-between items-center py-1.5">
                                <span class="text-xs text-gray-400">Estado</span>
                                <Badge :variant="product.is_active ? 'success' : 'gray'" :text="product.is_active ? 'Activo' : 'Inactivo'" :dot="true" />
                            </div>
                        </div>
                    </div>

                    <div v-if="product.description" class="rounded-2xl bg-gray-800 border border-gray-700 p-5">
                        <h3 class="text-sm font-semibold text-white mb-2">Descripción</h3>
                        <p class="text-sm text-gray-400 leading-relaxed">{{ product.description }}</p>
                    </div>
                </div>

                <!-- Historial de movimientos -->
                <div class="xl:col-span-2 rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">
                        <h2 class="text-sm font-semibold text-white">Historial de Movimientos</h2>
                        <span class="text-xs text-gray-500">{{ movements.total }} movimientos registrados</span>
                    </div>

                    <div v-if="movements.data.length === 0" class="flex flex-col items-center justify-center py-16">
                        <svg class="w-10 h-10 text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                        </svg>
                        <p class="text-gray-400 text-sm">Sin movimientos registrados aún.</p>
                    </div>

                    <div v-else class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-700">
                                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipo</th>
                                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Cantidad</th>
                                    <th class="text-center px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Stock</th>
                                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden sm:table-cell">Motivo</th>
                                    <th class="text-left px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Usuario</th>
                                    <th class="text-right px-5 py-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">Fecha</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-700/50">
                                <tr v-for="m in movements.data" :key="m.id" class="hover:bg-gray-700/20 transition-colors">
                                    <td class="px-5 py-3">
                                        <Badge :variant="movementMeta[m.type]?.variant ?? 'gray'" :text="movementMeta[m.type]?.label ?? m.type" :dot="true" />
                                    </td>
                                    <td class="px-5 py-3 text-center">
                                        <span :class="['font-bold tabular-nums', m.type === 'entry' ? 'text-emerald-400' : m.type === 'exit' ? 'text-red-400' : 'text-amber-400']">
                                            {{ movementMeta[m.type]?.sign }}{{ m.quantity }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3 text-center hidden md:table-cell">
                                        <span class="text-gray-500 tabular-nums">{{ m.previous_stock }}</span>
                                        <span class="text-gray-600 mx-1">→</span>
                                        <span class="text-white font-medium tabular-nums">{{ m.new_stock }}</span>
                                    </td>
                                    <td class="px-5 py-3 hidden sm:table-cell">
                                        <p class="text-gray-300 truncate max-w-[180px]">{{ m.reason }}</p>
                                    </td>
                                    <td class="px-5 py-3 hidden lg:table-cell">
                                        <p class="text-gray-400 text-xs">{{ m.user?.name ?? '—' }}</p>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <p class="text-gray-500 text-xs whitespace-nowrap">{{ formatDate(m.created_at) }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div v-if="movements.last_page > 1" class="flex items-center justify-between px-5 py-3 border-t border-gray-700">
                        <p class="text-xs text-gray-400">{{ movements.from }}–{{ movements.to }} de {{ movements.total }}</p>
                        <div class="flex gap-1">
                            <Link v-for="link in movements.links" :key="link.label" :href="link.url ?? '#'" v-html="link.label"
                                :class="['px-3 py-1.5 rounded-lg text-xs font-medium transition-colors', link.active ? 'bg-indigo-600 text-white' : link.url ? 'text-gray-400 hover:text-white hover:bg-gray-700' : 'text-gray-600 cursor-not-allowed']" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
