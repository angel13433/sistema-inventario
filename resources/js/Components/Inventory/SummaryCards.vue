<script setup>
import { computed } from 'vue';
import { formatNumber, formatUsd, formatVes } from '@/utils/format';

const props = defineProps({
    metrics: { type: Object, required: true },
    bcvRate: { type: Number, required: true },
});

const cards = computed(() => [
    {
        id: 'total-products',
        title: 'Total de productos',
        value: formatNumber(props.metrics.total_products),
        hint: `${formatNumber(props.metrics.total_units)} unidades en total`,
        iconBg: 'bg-blue-50 text-blue-700 ring-blue-100',
        icon: 'M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9',
    },
    {
        id: 'low-stock',
        title: 'Stock bajo',
        value: formatNumber(props.metrics.low_stock_count),
        hint: 'Requieren reposición pronto',
        iconBg: 'bg-amber-50 text-amber-600 ring-amber-100',
        icon: 'M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z',
    },
    {
        id: 'out-of-stock',
        title: 'Agotados',
        value: formatNumber(props.metrics.out_of_stock_count),
        hint: 'Sin unidades disponibles',
        iconBg: 'bg-rose-50 text-rose-600 ring-rose-100',
        icon: 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    },
    {
        id: 'inventory-value',
        title: 'Valor del inventario',
        value: formatUsd(props.metrics.inventory_value_usd),
        hint: `≈ ${formatVes(props.metrics.inventory_value_usd * props.bcvRate)} · Precio × unidades`,
        iconBg: 'bg-emerald-50 text-emerald-600 ring-emerald-100',
        icon: 'M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3',
    },
]);
</script>

<template>
    <section aria-label="Resumen del inventario" class="grid grid-cols-1 gap-4 sm:grid-cols-2 2xl:grid-cols-4">
        <article
            v-for="(card, index) in cards"
            :id="`summary-${card.id}`"
            :key="card.id"
            class="group relative overflow-hidden rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-all duration-200 hover:-translate-y-0.5 hover:shadow-md animate-fade-up"
            :style="{ animationDelay: `${index * 60}ms` }"
        >
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <p class="text-sm font-medium text-slate-600">{{ card.title }}</p>
                    <p class="mt-3 truncate text-3xl font-semibold tracking-tight text-slate-900 tabular-nums">
                        {{ card.value }}
                    </p>
                </div>
                <div :class="['flex h-11 w-11 flex-shrink-0 items-center justify-center rounded-xl ring-1 transition-transform duration-200 group-hover:scale-105', card.iconBg]">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="card.icon" />
                    </svg>
                </div>
            </div>
            <p class="mt-3 truncate text-xs text-slate-500">{{ card.hint }}</p>
        </article>
    </section>
</template>
