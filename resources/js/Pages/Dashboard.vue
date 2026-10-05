<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SummaryCards from '@/Components/Inventory/SummaryCards.vue';
import ProductTable from '@/Components/Inventory/ProductTable.vue';
import StockAlerts from '@/Components/Inventory/StockAlerts.vue';
import ConfirmModal from '@/Components/Ui/ConfirmModal.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { computed, nextTick, ref } from 'vue';
import { formatNumber } from '@/utils/format';

const props = defineProps({
    metrics:    { type: Object, required: true },
    products:   { type: Array, default: () => [] },
    alerts:     { type: Array, default: () => [] },
    categories: { type: Array, default: () => [] },
    bcvRate:    { type: Number, default: 36.5 },
});

// ── Filtros (búsqueda, categoría y estado) ──────────────────────
const search = ref('');
const categoryId = ref('');
const statusFilter = ref('all');

const statusTabs = [
    { value: 'all',          label: 'Todos' },
    { value: 'in_stock',     label: 'En stock' },
    { value: 'low_stock',    label: 'Stock bajo' },
    { value: 'out_of_stock', label: 'Agotados' },
];

const countByStatus = computed(() => {
    const counts = { all: props.products.length, in_stock: 0, low_stock: 0, out_of_stock: 0 };
    props.products.forEach((p) => { counts[p.status] = (counts[p.status] ?? 0) + 1; });
    return counts;
});

const filteredProducts = computed(() => {
    const term = search.value.trim().toLowerCase();

    return props.products.filter((p) => {
        const matchesSearch = !term || [p.name, p.sku, p.supplier]
            .some((field) => field?.toLowerCase().includes(term));
        const matchesCategory = !categoryId.value || String(p.category_id) === String(categoryId.value);
        const matchesStatus = statusFilter.value === 'all' || p.status === statusFilter.value;

        return matchesSearch && matchesCategory && matchesStatus;
    });
});

// ── Exportar CSV de los productos filtrados ─────────────────────
const statusLabels = { in_stock: 'En stock', low_stock: 'Stock bajo', out_of_stock: 'Agotado' };

const exportCsv = () => {
    const header = ['SKU', 'Producto', 'Categoría', 'Proveedor', 'Stock', 'Stock mínimo', 'Precio USD', 'Estado'];
    const escape = (value) => `"${String(value ?? '').replace(/"/g, '""')}"`;
    const rows = filteredProducts.value.map((p) => [
        p.sku, p.name, p.category, p.supplier, p.current_stock, p.min_stock, p.price_usd.toFixed(2), statusLabels[p.status],
    ].map(escape).join(';'));

    const blob = new Blob(['\uFEFF' + [header.map(escape).join(';'), ...rows].join('\n')], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `inventario-${new Date().toISOString().slice(0, 10)}.csv`;
    link.click();
    URL.revokeObjectURL(url);
};

// ── Tasa BCV ────────────────────────────────────────────────────
const editingRate = ref(false);
const rateInput = ref(null);
const rateForm = useForm({ rate: props.bcvRate });

const startEditRate = async () => {
    rateForm.rate = props.bcvRate;
    editingRate.value = true;
    await nextTick();
    rateInput.value?.focus();
};

const submitRate = () => {
    rateForm.patch(route('settings.bcv-rate'), {
        preserveScroll: true,
        onSuccess: () => { editingRate.value = false; },
    });
};

// ── Eliminar producto ───────────────────────────────────────────
const productToDelete = ref(null);
const deleting = ref(false);

const confirmDelete = () => {
    if (!productToDelete.value) return;
    deleting.value = true;
    router.delete(route('products.destroy', productToDelete.value.id), {
        preserveScroll: true,
        onFinish: () => {
            deleting.value = false;
            productToDelete.value = null;
        },
    });
};
</script>

<template>
    <Head title="Control de Inventario">
        <meta name="description" content="Panel de control del inventario: métricas, alertas de stock y listado de productos." />
    </Head>

    <AuthenticatedLayout>
        <div class="min-h-full bg-slate-50 text-slate-900">
            <!-- ── Encabezado ─────────────────────────────────────── -->
            <header class="border-b border-slate-200 bg-white/80 backdrop-blur">
                <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-5 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
                    <div class="flex items-center gap-3">
                        <div class="flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-blue-800 text-white shadow-lg shadow-blue-700/25">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl font-semibold tracking-tight text-slate-900">Control de Inventario</h1>
                            <p class="text-sm text-slate-500">Gestión de productos y stock</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Tasa BCV -->
                        <div class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm">
                            <span class="text-slate-500">BCV</span>
                            <template v-if="!editingRate">
                                <span class="font-semibold text-slate-900 tabular-nums">Bs. {{ Number(bcvRate).toFixed(2) }}</span>
                                <button
                                    id="edit-bcv-rate-btn"
                                    type="button"
                                    title="Actualizar tasa BCV"
                                    class="rounded-md p-1 text-slate-400 transition-colors hover:bg-slate-100 hover:text-blue-700"
                                    @click="startEditRate"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                    </svg>
                                </button>
                            </template>
                            <form v-else class="flex items-center gap-1.5" @submit.prevent="submitRate">
                                <input
                                    id="bcv-rate-input"
                                    ref="rateInput"
                                    v-model="rateForm.rate"
                                    type="number"
                                    step="0.01"
                                    min="0.01"
                                    class="w-24 rounded-md border-slate-300 px-2 py-1 text-right text-sm tabular-nums focus:border-blue-600 focus:ring-blue-600"
                                    @keyup.escape="editingRate = false"
                                />
                                <button type="submit" :disabled="rateForm.processing" class="rounded-md bg-blue-700 px-2 py-1 text-xs font-medium text-white hover:bg-blue-800 disabled:opacity-50">
                                    Guardar
                                </button>
                                <button type="button" class="rounded-md px-1.5 py-1 text-xs text-slate-500 hover:bg-slate-100" @click="editingRate = false">
                                    ✕
                                </button>
                            </form>
                        </div>

                        <button
                            id="export-btn"
                            type="button"
                            class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-medium text-slate-700 shadow-sm transition-all hover:bg-slate-50 hover:text-slate-900 active:scale-[0.98]"
                            @click="exportCsv"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Exportar
                        </button>
                        <Link
                            id="new-product-btn"
                            :href="route('products.create')"
                            class="inline-flex items-center gap-2 rounded-xl bg-blue-700 px-4 py-2.5 text-sm font-medium text-white shadow-lg shadow-blue-700/25 transition-all hover:bg-blue-800 active:scale-[0.98]"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Nuevo producto
                        </Link>
                    </div>
                </div>
            </header>

            <div class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
                <!-- ── Tarjetas de resumen ─────────────────────────── -->
                <SummaryCards :metrics="metrics" :bcv-rate="bcvRate" />

                <div class="grid grid-cols-1 gap-6 xl:grid-cols-[minmax(0,1fr)_20rem]">
                    <!-- ── Listado de productos ────────────────────── -->
                    <section aria-label="Listado de productos" class="min-w-0 space-y-4">
                        <!-- Búsqueda + categoría -->
                        <div class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row">
                            <div class="relative flex-1">
                                <svg class="pointer-events-none absolute left-3.5 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                <input
                                    id="product-search"
                                    v-model="search"
                                    type="search"
                                    placeholder="Buscar por nombre, SKU o proveedor..."
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 pl-10 pr-3 text-sm placeholder-slate-400 transition focus:border-blue-600 focus:bg-white focus:ring-blue-600"
                                />
                            </div>
                            <select
                                id="category-filter"
                                v-model="categoryId"
                                class="rounded-xl border-slate-200 bg-slate-50 py-2.5 pl-3 pr-9 text-sm text-slate-700 focus:border-blue-600 focus:ring-blue-600 sm:w-56"
                            >
                                <option value="">Todas las categorías</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">
                                    {{ category.name }}
                                </option>
                            </select>
                        </div>

                        <!-- Pestañas de estado -->
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex flex-wrap gap-2" role="tablist" aria-label="Filtrar por estado">
                                <button
                                    v-for="tab in statusTabs"
                                    :id="`status-tab-${tab.value}`"
                                    :key="tab.value"
                                    type="button"
                                    role="tab"
                                    :aria-selected="statusFilter === tab.value"
                                    :class="[
                                        'inline-flex items-center gap-1.5 rounded-full px-4 py-1.5 text-sm font-medium transition-all duration-150',
                                        statusFilter === tab.value
                                            ? 'bg-blue-700 text-white shadow-md shadow-blue-700/25'
                                            : 'bg-slate-200/70 text-slate-600 hover:bg-slate-200 hover:text-slate-900',
                                    ]"
                                    @click="statusFilter = tab.value"
                                >
                                    {{ tab.label }}
                                    <span
                                        :class="[
                                            'rounded-full px-1.5 text-[11px] tabular-nums',
                                            statusFilter === tab.value ? 'bg-white/20' : 'bg-white text-slate-500',
                                        ]"
                                    >{{ countByStatus[tab.value] }}</span>
                                </button>
                            </div>
                            <p class="text-sm text-slate-500">
                                {{ formatNumber(filteredProducts.length) }} de {{ formatNumber(products.length) }} productos
                            </p>
                        </div>

                        <ProductTable :products="filteredProducts" @delete="productToDelete = $event" />
                    </section>

                    <!-- ── Alertas ─────────────────────────────────── -->
                    <StockAlerts :alerts="alerts" class="xl:sticky xl:top-6 xl:self-start" />
                </div>
            </div>
        </div>

        <ConfirmModal
            :show="!!productToDelete"
            title="¿Eliminar producto?"
            :message="productToDelete ? `Se eliminará «${productToDelete.name}» del inventario.` : ''"
            :processing="deleting"
            @confirm="confirmDelete"
            @cancel="productToDelete = null"
        />
    </AuthenticatedLayout>
</template>
