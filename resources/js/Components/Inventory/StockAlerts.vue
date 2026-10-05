<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    alerts: { type: Array, default: () => [] },
});
</script>

<template>
    <aside
        id="stock-alerts"
        aria-labelledby="stock-alerts-title"
        class="flex flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm"
    >
        <header class="flex items-center justify-between px-5 pb-3 pt-5">
            <h2 id="stock-alerts-title" class="text-base font-semibold text-slate-900">Alertas de inventario</h2>
            <span class="inline-flex min-w-[1.75rem] items-center justify-center rounded-full bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600 tabular-nums">
                {{ alerts.length }}
            </span>
        </header>

        <!-- Sin alertas -->
        <div v-if="alerts.length === 0" class="flex flex-col items-center justify-center px-5 pb-8 pt-4 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-full bg-emerald-50 text-emerald-600">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <p class="text-sm font-medium text-slate-700">¡Todo en orden!</p>
            <p class="mt-1 text-xs text-slate-500">No hay productos con stock bajo ni agotados.</p>
        </div>

        <!-- Lista de alertas -->
        <ul v-else class="max-h-[34rem] space-y-2.5 overflow-y-auto px-5 pb-5">
            <li v-for="item in alerts" :key="item.id">
                <Link
                    :href="route('products.show', item.id)"
                    :class="[
                        'flex items-start gap-3 rounded-xl border p-3 transition-colors duration-150',
                        item.status === 'out_of_stock'
                            ? 'border-rose-200 bg-rose-50/70 hover:bg-rose-50'
                            : 'border-amber-200 bg-amber-50/70 hover:bg-amber-50',
                    ]"
                >
                    <svg
                        :class="['mt-0.5 h-5 w-5 flex-shrink-0', item.status === 'out_of_stock' ? 'text-rose-600' : 'text-amber-600']"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true"
                    >
                        <path
                            v-if="item.status === 'out_of_stock'"
                            stroke-linecap="round" stroke-linejoin="round"
                            d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                        <path
                            v-else
                            stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"
                        />
                    </svg>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-start justify-between gap-2">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ item.name }}</p>
                            <span class="flex-shrink-0 font-mono text-[11px] text-slate-500">{{ item.sku }}</span>
                        </div>
                        <p class="mt-0.5 text-xs text-slate-600">
                            <template v-if="item.status === 'out_of_stock'">Sin stock</template>
                            <template v-else>Quedan {{ item.current_stock }} uds.</template>
                            · mínimo requerido {{ item.min_stock }}
                        </p>
                    </div>
                </Link>
            </li>
        </ul>
    </aside>
</template>
