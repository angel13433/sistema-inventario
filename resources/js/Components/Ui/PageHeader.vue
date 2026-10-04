<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: { type: String, required: true },
    subtitle: { type: String, default: null },
    backHref: { type: String, default: null },
    backLabel: { type: String, default: 'Volver' },
});
</script>

<template>
    <div class="flex items-start justify-between gap-4 mb-6">
        <div class="flex items-center gap-3">
            <Link
                v-if="backHref"
                :href="backHref"
                class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-gray-700 transition-colors flex-shrink-0"
                :title="backLabel"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
            </Link>
            <div>
                <h1 class="text-xl font-bold text-white">{{ title }}</h1>
                <p v-if="subtitle" class="text-sm text-gray-400 mt-0.5">{{ subtitle }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <slot name="actions" />
        </div>
    </div>
</template>
