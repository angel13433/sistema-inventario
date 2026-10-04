<script setup>
defineProps({
    title: { type: String, required: true },
    value: { type: [String, Number], required: true },
    subtitle: { type: String, default: null },
    color: { type: String, default: 'indigo' }, // indigo | emerald | amber | red | blue
    trend: { type: Number, default: null },
});

const colorMap = {
    indigo: {
        bg: 'bg-indigo-500/10',
        icon: 'text-indigo-400',
        border: 'border-indigo-500/20',
        dot: 'bg-indigo-500',
    },
    emerald: {
        bg: 'bg-emerald-500/10',
        icon: 'text-emerald-400',
        border: 'border-emerald-500/20',
        dot: 'bg-emerald-500',
    },
    amber: {
        bg: 'bg-amber-500/10',
        icon: 'text-amber-400',
        border: 'border-amber-500/20',
        dot: 'bg-amber-500',
    },
    red: {
        bg: 'bg-red-500/10',
        icon: 'text-red-400',
        border: 'border-red-500/20',
        dot: 'bg-red-500',
    },
    blue: {
        bg: 'bg-blue-500/10',
        icon: 'text-blue-400',
        border: 'border-blue-500/20',
        dot: 'bg-blue-500',
    },
};
</script>

<template>
    <div :class="['relative overflow-hidden rounded-2xl border bg-gray-800 p-5 transition-all duration-200 hover:bg-gray-750 hover:shadow-lg', colorMap[color]?.border ?? 'border-gray-700']">
        <!-- Decorative glow -->
        <div :class="['absolute -top-6 -right-6 w-24 h-24 rounded-full blur-2xl opacity-20', colorMap[color]?.dot ? `bg-${color === 'indigo' ? 'indigo' : color === 'emerald' ? 'emerald' : color === 'amber' ? 'amber' : color === 'red' ? 'red' : 'blue'}-500` : '']" />

        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-400 mb-1">{{ title }}</p>
                <p class="text-2xl font-bold text-white tabular-nums">{{ value }}</p>
                <p v-if="subtitle" class="text-xs text-gray-500 mt-1">{{ subtitle }}</p>
                <div v-if="trend !== null" class="flex items-center gap-1 mt-2">
                    <svg v-if="trend >= 0" class="w-3 h-3 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                    </svg>
                    <svg v-else class="w-3 h-3 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6L9 12.75l4.306-4.307a11.95 11.95 0 015.814 5.519l2.74 1.22m0 0l-5.94 2.28m5.94-2.28l-2.28-5.941" />
                    </svg>
                    <span :class="['text-xs font-medium', trend >= 0 ? 'text-emerald-400' : 'text-red-400']">{{ Math.abs(trend) }}%</span>
                </div>
            </div>
            <div :class="['flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center', colorMap[color]?.bg ?? 'bg-gray-700']">
                <svg :class="['w-6 h-6', colorMap[color]?.icon ?? 'text-gray-400']" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                    <slot name="icon" />
                </svg>
            </div>
        </div>
    </div>
</template>
