<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/Ui/PageHeader.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    description: '',
    is_active: true,
});

const submit = () => {
    form.post(route('categories.store'));
};
</script>

<template>
    <Head title="Nueva Categoría — Inventario" />
    <AuthenticatedLayout>
        <div class="p-6 max-w-lg mx-auto">
            <PageHeader
                title="Nueva Categoría"
                subtitle="Agrega una nueva categoría al inventario"
                :back-href="route('categories.index')"
                back-label="Volver a Categorías"
            />

            <form @submit.prevent="submit" class="rounded-2xl bg-gray-800 border border-gray-700 p-6 space-y-5">

                <!-- Nombre -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">
                        Nombre <span class="text-red-400">*</span>
                    </label>
                    <input
                        v-model="form.name"
                        type="text"
                        placeholder="Ej: Electrónica, Ferretería, Alimentos..."
                        :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                            form.errors.name ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']"
                    />
                    <p v-if="form.errors.name" class="text-red-400 text-xs mt-1.5">{{ form.errors.name }}</p>
                </div>

                <!-- Descripción -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Descripción</label>
                    <textarea
                        v-model="form.description"
                        rows="3"
                        placeholder="Descripción opcional de la categoría..."
                        :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors resize-none',
                            form.errors.description ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']"
                    />
                    <p v-if="form.errors.description" class="text-red-400 text-xs mt-1.5">{{ form.errors.description }}</p>
                </div>

                <!-- Estado -->
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-700/50 border border-gray-600">
                    <div>
                        <p class="text-sm font-medium text-white">Categoría activa</p>
                        <p class="text-xs text-gray-400 mt-0.5">Las categorías inactivas no aparecerán en formularios</p>
                    </div>
                    <button
                        type="button"
                        @click="form.is_active = !form.is_active"
                        :class="['relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none',
                            form.is_active ? 'bg-indigo-600' : 'bg-gray-600']"
                    >
                        <span :class="['pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200',
                            form.is_active ? 'translate-x-5' : 'translate-x-0']" />
                    </button>
                </div>

                <!-- Acciones -->
                <div class="flex gap-3 pt-1">
                    <a
                        :href="route('categories.index')"
                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 bg-gray-700 hover:bg-gray-600 border border-gray-600 transition-colors text-center"
                    >
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 transition-colors disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                    >
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        {{ form.processing ? 'Guardando...' : 'Crear Categoría' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
