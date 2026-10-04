<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/Ui/PageHeader.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    supplier: { type: Object, required: true },
});

const form = useForm({
    name:           props.supplier.name,
    rif:            props.supplier.rif,
    phone:          props.supplier.phone ?? '',
    email:          props.supplier.email ?? '',
    address:        props.supplier.address ?? '',
    contact_person: props.supplier.contact_person ?? '',
    is_active:      props.supplier.is_active,
});

const submit = () => form.put(route('suppliers.update', props.supplier.id));
</script>

<template>
    <Head :title="`Editar Proveedor — ${supplier.name}`" />
    <AuthenticatedLayout>
        <div class="p-6 max-w-2xl mx-auto">
            <PageHeader
                :title="`Editar: ${supplier.name}`"
                subtitle="Modifica los datos del proveedor"
                :back-href="route('suppliers.index')"
            />

            <form @submit.prevent="submit" class="rounded-2xl bg-gray-800 border border-gray-700 p-6 space-y-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Razón Social / Nombre <span class="text-red-400">*</span></label>
                        <input v-model="form.name" type="text"
                            :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                form.errors.name ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                        <p v-if="form.errors.name" class="text-red-400 text-xs mt-1.5">{{ form.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">RIF <span class="text-red-400">*</span></label>
                        <input v-model="form.rif" type="text" class="font-mono w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors"
                            :class="form.errors.rif ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500'" />
                        <p v-if="form.errors.rif" class="text-red-400 text-xs mt-1.5">{{ form.errors.rif }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Teléfono</label>
                        <input v-model="form.phone" type="tel"
                            :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                form.errors.phone ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                        <p v-if="form.errors.phone" class="text-red-400 text-xs mt-1.5">{{ form.errors.phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Correo Electrónico</label>
                        <input v-model="form.email" type="email"
                            :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                form.errors.email ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                        <p v-if="form.errors.email" class="text-red-400 text-xs mt-1.5">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Persona de Contacto</label>
                        <input v-model="form.contact_person" type="text"
                            :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                form.errors.contact_person ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-300 mb-1.5">Dirección</label>
                        <textarea v-model="form.address" rows="2"
                            :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors resize-none',
                                form.errors.address ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                    </div>
                </div>

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

                <div class="flex gap-3 pt-1">
                    <a :href="route('suppliers.index')" class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 bg-gray-700 hover:bg-gray-600 border border-gray-600 transition-colors text-center">Cancelar</a>
                    <button type="submit" :disabled="form.processing || !form.isDirty"
                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 transition-colors disabled:opacity-50 flex items-center justify-center gap-2">
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
