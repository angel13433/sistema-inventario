<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/Ui/PageHeader.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    name:           '',
    rif:            '',
    phone:          '',
    email:          '',
    address:        '',
    contact_person: '',
    is_active:      true,
});

const submit = () => form.post(route('suppliers.store'));

// Formateo automático del RIF
const formatRif = (e) => {
    let v = e.target.value.toUpperCase().replace(/[^VEJPG0-9-]/g, '');
    form.rif = v;
};
</script>

<template>
    <Head title="Nuevo Proveedor — Inventario" />
    <AuthenticatedLayout>
        <div class="p-6 max-w-2xl mx-auto">
            <PageHeader
                title="Nuevo Proveedor"
                subtitle="Registra un nuevo proveedor en el sistema"
                :back-href="route('suppliers.index')"
            />

            <form @submit.prevent="submit" class="rounded-2xl bg-gray-800 border border-gray-700 p-6 space-y-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nombre -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Razón Social / Nombre <span class="text-red-400">*</span></label>
                        <input v-model="form.name" type="text" placeholder="Ej: Distribuidora El Sol C.A."
                            :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                form.errors.name ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                        <p v-if="form.errors.name" class="text-red-400 text-xs mt-1.5">{{ form.errors.name }}</p>
                    </div>

                    <!-- RIF -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">RIF <span class="text-red-400">*</span></label>
                        <input v-model="form.rif" @input="formatRif" type="text" placeholder="J-12345678-9"
                            class="font-mono w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                            :class="form.errors.rif ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500'" />
                        <p v-if="form.errors.rif" class="text-red-400 text-xs mt-1.5">{{ form.errors.rif }}</p>
                        <p class="text-gray-500 text-xs mt-1">Formato: J-12345678-9 (V, E, J, P o G)</p>
                    </div>

                    <!-- Teléfono -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Teléfono</label>
                        <input v-model="form.phone" type="tel" placeholder="0212-1234567"
                            :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                form.errors.phone ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                        <p v-if="form.errors.phone" class="text-red-400 text-xs mt-1.5">{{ form.errors.phone }}</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Correo Electrónico</label>
                        <input v-model="form.email" type="email" placeholder="contacto@proveedor.com"
                            :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                form.errors.email ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                        <p v-if="form.errors.email" class="text-red-400 text-xs mt-1.5">{{ form.errors.email }}</p>
                    </div>

                    <!-- Persona de contacto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Persona de Contacto</label>
                        <input v-model="form.contact_person" type="text" placeholder="Nombre del encargado"
                            :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                form.errors.contact_person ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                        <p v-if="form.errors.contact_person" class="text-red-400 text-xs mt-1.5">{{ form.errors.contact_person }}</p>
                    </div>

                    <!-- Dirección -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Dirección</label>
                        <textarea v-model="form.address" rows="2" placeholder="Dirección fiscal del proveedor..."
                            :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors resize-none',
                                form.errors.address ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                        <p v-if="form.errors.address" class="text-red-400 text-xs mt-1.5">{{ form.errors.address }}</p>
                    </div>
                </div>

                <!-- Estado -->
                <div class="flex items-center justify-between p-4 rounded-xl bg-gray-700/50 border border-gray-600">
                    <div>
                        <p class="text-sm font-medium text-white">Proveedor activo</p>
                        <p class="text-xs text-gray-400 mt-0.5">Los proveedores inactivos no aparecerán al asignar productos</p>
                    </div>
                    <button type="button" @click="form.is_active = !form.is_active"
                        :class="['relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200', form.is_active ? 'bg-indigo-600' : 'bg-gray-600']">
                        <span :class="['pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200', form.is_active ? 'translate-x-5' : 'translate-x-0']" />
                    </button>
                </div>

                <!-- Acciones -->
                <div class="flex gap-3 pt-1">
                    <a :href="route('suppliers.index')" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 bg-gray-700 hover:bg-gray-600 border border-gray-600 transition-colors text-center">Cancelar</a>
                    <button type="submit" :disabled="form.processing"
                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 transition-colors disabled:opacity-50 flex items-center justify-center gap-2">
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        {{ form.processing ? 'Guardando...' : 'Crear Proveedor' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
