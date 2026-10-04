<script setup>
defineProps({
    variant: {
        type: String,
        default: 'gray',
        validator: (v) => ['success', 'warning', 'danger', 'info', 'gray', 'indigo'].includes(v),
    },
    text: { type: String, required: true },
    dot: { type: Boolean, default: false },
});

const variantMap = {
    success: 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
    warning: 'bg-amber-500/10 text-amber-400 border-amber-500/20',
    danger:  'bg-red-500/10 text-red-400 border-red-500/20',
    info:    'bg-blue-500/10 text-blue-400 border-blue-500/20',
    indigo:  'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
    gray:    'bg-gray-500/10 text-gray-400 border-gray-500/20',
};

const dotMap = {
    success: 'bg-emerald-400',
    warning: 'bg-amber-400',
    danger:  'bg-red-400',
    info:    'bg-blue-400',
    indigo:  'bg-indigo-400',
    gray:    'bg-gray-400',
};
</script>

<template>
    <span :class="['inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border', variantMap[variant]]">
        <span v-if="dot" :class="['w-1.5 h-1.5 rounded-full', dotMap[variant]]" />
        {{ text }}
    </span>
</template>
