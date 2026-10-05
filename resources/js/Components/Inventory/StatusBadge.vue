<script setup>
import { computed } from 'vue';

const props = defineProps({
    status: { type: String, required: true }, // in_stock | low_stock | out_of_stock
});

const config = {
    in_stock:     { label: 'En stock',   classes: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20', dot: 'bg-emerald-500' },
    low_stock:    { label: 'Stock bajo', classes: 'bg-amber-50 text-amber-700 ring-amber-600/20',       dot: 'bg-amber-500' },
    out_of_stock: { label: 'Agotado',    classes: 'bg-rose-50 text-rose-700 ring-rose-600/20',          dot: 'bg-rose-500' },
};

const current = computed(() => config[props.status] ?? config.in_stock);
</script>

<template>
    <span
        :class="['inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium ring-1 ring-inset whitespace-nowrap', current.classes]"
    >
        <span :class="['h-1.5 w-1.5 rounded-full', current.dot]" />
        {{ current.label }}
    </span>
</template>
