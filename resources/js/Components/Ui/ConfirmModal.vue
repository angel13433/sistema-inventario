<script setup>
const props = defineProps({
    show: { type: Boolean, default: false },
    title: { type: String, default: '¿Eliminar registro?' },
    message: { type: String, default: 'Esta acción no se puede deshacer. El registro será eliminado permanentemente.' },
    confirmText: { type: String, default: 'Sí, eliminar' },
    cancelText: { type: String, default: 'Cancelar' },
    processing: { type: Boolean, default: false },
});

const emit = defineEmits(['confirm', 'cancel']);
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 z-50 flex items-center justify-center p-4"
                @click.self="emit('cancel')"
            >
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" />

                <!-- Modal -->
                <Transition
                    enter-active-class="transition ease-out duration-200"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition ease-in duration-150"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        v-if="show"
                        class="relative w-full max-w-md rounded-2xl bg-gray-800 border border-gray-700 shadow-2xl p-6"
                    >
                        <!-- Icon -->
                        <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-red-500/10 border border-red-500/20 mx-auto mb-4">
                            <svg class="w-6 h-6 text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>

                        <h3 class="text-lg font-bold text-white text-center mb-2">{{ title }}</h3>
                        <p class="text-sm text-gray-400 text-center mb-6">{{ message }}</p>

                        <div class="flex gap-3">
                            <button
                                @click="emit('cancel')"
                                :disabled="processing"
                                class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 bg-gray-700 hover:bg-gray-600 border border-gray-600 transition-colors disabled:opacity-50"
                            >
                                {{ cancelText }}
                            </button>
                            <button
                                @click="emit('confirm')"
                                :disabled="processing"
                                class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-red-600 hover:bg-red-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                            >
                                <svg v-if="processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                </svg>
                                {{ processing ? 'Eliminando...' : confirmText }}
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
