<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import StatCard from '@/Components/Ui/StatCard.vue';
import Badge from '@/Components/Ui/Badge.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    metrics:          { type: Object, required: true },
    bcvRate:          { type: Number, default: 36.50 },
    lowStockProducts: { type: Array, default: () => [] },
    recentMovements:  { type: Array, default: () => [] },
});

// ── Tasa BCV ─────────────────────────────────────────────────────
const editingRate  = ref(false);
const newRateInput = ref(props.bcvRate);
const rateForm     = useForm({ rate: props.bcvRate });

const startEditRate = () => {
    newRateInput.value = props.bcvRate;
    editingRate.value = true;
};

const cancelEditRate = () => {
    editingRate.value = false;
};

const submitRate = () => {
    rateForm.rate = parseFloat(newRateInput.value);
    rateForm.patch(route('settings.bcv-rate'), {
        onSuccess: () => { editingRate.value = false; },
    });
};

// ── Buscador rápido ──────────────────────────────────────────────
const quickSearch = ref('');
const doSearch = () => {
    const q = quickSearch.value.trim();
    if (!q) return;
    router.get(route('products.index'), { search: q });
};

// ── Helpers ──────────────────────────────────────────────────────
const formatVES = (usdStr) => {
    const num = parseFloat(usdStr) || 0;
    return (num * props.bcvRate).toLocaleString('es-VE', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const formatDate = (dateStr) => {
    if (!dateStr) return '—';
    return new Date(dateStr).toLocaleDateString('es-VE', {
        day: '2-digit', month: 'short', hour: '2-digit', minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Dashboard — Inventario" />
    <AuthenticatedLayout>
        <div class="p-6 space-y-5">

            <!-- ── Encabezado ──────────────────────────────────────── -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-white">Panel de Control</h1>
                    <p class="text-gray-400 text-sm mt-0.5">Resumen general del inventario</p>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <!-- Nuevo Producto (secundario) -->
                    <Link
                        :href="route('products.create')"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-gray-700 hover:bg-gray-600 text-gray-300 hover:text-white text-sm font-medium border border-gray-600 transition-all duration-150"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                        <span class="hidden sm:inline">Nuevo Producto</span>
                        <span class="sm:hidden">Producto</span>
                    </Link>
                    <!-- Nuevo Movimiento (primario) -->
                    <Link
                        :href="route('inventory-movements.index')"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-all duration-150 shadow-lg shadow-indigo-600/20"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                        </svg>
                        <span class="hidden sm:inline">Nuevo Movimiento</span>
                        <span class="sm:hidden">Movimiento</span>
                    </Link>
                </div>
            </div>

            <!-- ── Buscador rápido de productos ───────────────────── -->
            <div class="relative group">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input
                    id="quick-search"
                    v-model="quickSearch"
                    type="text"
                    placeholder="Buscar producto por nombre, SKU o código de barras..."
                    @keyup.enter="doSearch"
                    class="w-full pl-11 pr-28 py-3 rounded-xl bg-gray-800 border border-gray-700 text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-150"
                />
                <button
                    id="quick-search-btn"
                    @click="doSearch"
                    :disabled="!quickSearch.trim()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 px-3.5 py-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-medium transition-colors disabled:opacity-30 disabled:cursor-not-allowed"
                >
                    Buscar
                </button>
            </div>

            <!-- ── Tasa BCV ────────────────────────────────────────── -->
            <div class="flex flex-wrap items-center gap-4 px-5 py-3.5 rounded-xl bg-gray-800 border border-gray-700/80">
                <!-- Ícono + Tasa -->
                <div class="flex items-center gap-3 flex-shrink-0">
                    <div class="w-9 h-9 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4.5 h-4.5 text-emerald-400" style="width:1.125rem;height:1.125rem" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 leading-none">Tasa BCV Oficial</p>
                        <p class="text-sm font-bold text-white leading-tight mt-0.5 tabular-nums">
                            Bs.&nbsp;{{ Number(bcvRate).toFixed(2) }}&nbsp;<span class="font-normal text-gray-400">/ USD</span>
                        </p>
                    </div>
                </div>

                <!-- Separador -->
                <div class="hidden sm:block h-8 w-px bg-gray-700 flex-shrink-0" />

                <!-- Valor en VES -->
                <div class="flex-1 min-w-0">
                    <p class="text-xs text-gray-400">Valor del inventario en bolívares</p>
                    <p class="text-sm font-semibold text-emerald-400 tabular-nums truncate">
                        Bs.&nbsp;{{ formatVES(metrics.inventory_value_usd) }}
                    </p>
                </div>

                <!-- Editar tasa -->
                <div class="flex items-center gap-2 flex-shrink-0 ml-auto">
                    <template v-if="!editingRate">
                        <button
                            id="edit-bcv-rate-btn"
                            @click="startEditRate"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-medium text-gray-400 hover:text-white bg-gray-700 hover:bg-gray-600 border border-gray-600 transition-colors"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                            </svg>
                            Actualizar tasa
                        </button>
                    </template>
                    <template v-else>
                        <div class="flex items-center gap-1.5">
                            <span class="text-xs text-gray-400 flex-shrink-0">Bs.</span>
                            <input
                                id="bcv-rate-input"
                                v-model="newRateInput"
                                type="number"
                                step="0.01"
                                min="0.01"
                                @keyup.enter="submitRate"
                                @keyup.escape="cancelEditRate"
                                class="w-28 px-3 py-1.5 rounded-lg bg-gray-700 border border-indigo-500 text-white text-sm text-right tabular-nums focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                autofocus
                            />
                            <span class="text-xs text-gray-400 flex-shrink-0">/ USD</span>
                            <button
                                @click="submitRate"
                                :disabled="rateForm.processing"
                                title="Guardar tasa"
                                class="p-1.5 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white transition-colors disabled:opacity-50"
                            >
                                <svg v-if="rateForm.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            </button>
                            <button
                                @click="cancelEditRate"
                                title="Cancelar"
                                class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-600 transition-colors"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            <!-- ── Métricas ────────────────────────────────────────── -->
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <StatCard
                    title="Total de Productos"
                    :value="metrics.total_products"
                    subtitle="Productos activos"
                    color="indigo"
                >
                    <template #icon>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </template>
                </StatCard>

                <StatCard
                    title="Stock Bajo"
                    :value="metrics.low_stock_count"
                    subtitle="Requieren reposición"
                    color="amber"
                >
                    <template #icon>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </template>
                </StatCard>

                <StatCard
                    title="Agotados"
                    :value="metrics.out_of_stock_count"
                    subtitle="Sin existencias"
                    color="red"
                >
                    <template #icon>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </template>
                </StatCard>

                <!-- Valor inventario con dual moneda -->
                <StatCard
                    title="Valor del Inventario"
                    :value="'$\u00a0' + metrics.inventory_value_usd"
                    :subtitle="'≈\u00a0Bs.\u00a0' + formatVES(metrics.inventory_value_usd)"
                    color="emerald"
                >
                    <template #icon>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </template>
                </StatCard>
            </div>

            <!-- ── Grid: Alertas + Movimientos ─────────────────────── -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                <!-- Productos con stock bajo -->
                <div class="xl:col-span-2 rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-amber-400 animate-pulse" />
                            <h2 class="text-sm font-semibold text-white">Alertas de Stock Bajo</h2>
                        </div>
                        <Link
                            :href="route('products.index', { low_stock: 1 })"
                            class="text-xs text-indigo-400 hover:text-indigo-300 font-medium transition-colors"
                        >
                            Ver todos →
                        </Link>
                    </div>

                    <div v-if="lowStockProducts.length === 0" class="flex flex-col items-center justify-center py-12 text-center">
                        <svg class="w-10 h-10 text-gray-600 mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-gray-400 text-sm font-medium">¡Stock en óptimas condiciones!</p>
                        <p class="text-gray-600 text-xs mt-1">No hay productos con alertas de stock.</p>
                    </div>

                    <div v-else class="divide-y divide-gray-700/50">
                        <div
                            v-for="product in lowStockProducts"
                            :key="product.id"
                            class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-700/30 transition-colors"
                        >
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <Link
                                        :href="route('products.show', product.id)"
                                        class="text-sm font-medium text-white hover:text-indigo-400 transition-colors"
                                    >
                                        {{ product.name }}
                                    </Link>
                                    <Badge :text="product.sku" variant="gray" />
                                </div>
                                <p class="text-xs text-gray-400 mt-0.5">{{ product.category?.name ?? '—' }}</p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p class="text-sm font-bold" :class="product.current_stock <= 0 ? 'text-red-400' : 'text-amber-400'">
                                    {{ product.current_stock }} / {{ product.min_stock }}
                                </p>
                                <p class="text-xs text-gray-500">actual / mínimo</p>
                            </div>
                            <div class="flex-shrink-0 w-20 hidden sm:block">
                                <div class="h-1.5 rounded-full bg-gray-700 overflow-hidden">
                                    <div
                                        :class="['h-full rounded-full transition-all', product.current_stock <= 0 ? 'bg-red-500' : 'bg-amber-500']"
                                        :style="`width: ${Math.min((product.current_stock / Math.max(product.min_stock, 1)) * 100, 100)}%`"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Últimos movimientos -->
                <div class="rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden">
                    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-700">
                        <h2 class="text-sm font-semibold text-white">Últimos Movimientos</h2>
                        <Link
                            :href="route('inventory-movements.index')"
                            class="text-xs text-indigo-400 hover:text-indigo-300 font-medium transition-colors"
                        >
                            Ver todos →
                        </Link>
                    </div>

                    <div v-if="recentMovements.length === 0" class="flex flex-col items-center justify-center py-12">
                        <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                        </svg>
                        <p class="text-gray-500 text-sm">Sin movimientos aún.</p>
                    </div>

                    <div v-else class="divide-y divide-gray-700/50">
                        <div
                            v-for="movement in recentMovements"
                            :key="movement.id"
                            class="flex items-start gap-3 px-5 py-3.5 hover:bg-gray-700/20 transition-colors"
                        >
                            <div :class="['mt-0.5 flex-shrink-0 w-7 h-7 rounded-lg flex items-center justify-center',
                                movement.type === 'entry'      ? 'bg-emerald-500/10' :
                                movement.type === 'exit'       ? 'bg-red-500/10'     : 'bg-amber-500/10']">
                                <svg
                                    :class="['w-3.5 h-3.5',
                                        movement.type === 'entry' ? 'text-emerald-400' :
                                        movement.type === 'exit'  ? 'text-red-400'     : 'text-amber-400']"
                                    fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"
                                >
                                    <path v-if="movement.type === 'entry'"      stroke-linecap="round" stroke-linejoin="round" d="M4.5 10.5L12 3m0 0l7.5 7.5M12 3v18" />
                                    <path v-else-if="movement.type === 'exit'" stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3" />
                                    <path v-else                                stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm text-white font-medium truncate">{{ movement.product?.name ?? '—' }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ movement.reason }}</p>
                                <p class="text-xs text-gray-600 mt-0.5">{{ formatDate(movement.created_at) }}</p>
                            </div>
                            <div class="flex-shrink-0 text-right">
                                <p :class="['text-sm font-bold tabular-nums',
                                    movement.type === 'entry' ? 'text-emerald-400' :
                                    movement.type === 'exit'  ? 'text-red-400'     : 'text-amber-400']">
                                    {{ movement.type === 'entry' ? '+' : movement.type === 'exit' ? '−' : '±' }}{{ movement.quantity }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
