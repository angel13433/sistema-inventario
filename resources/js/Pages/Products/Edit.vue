<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/Ui/PageHeader.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    product:    { type: Object, required: true },
    categories: { type: Array, default: () => [] },
    suppliers:  { type: Array, default: () => [] },
});

const form = useForm({
    barcode:     props.product.barcode ?? '',
    sku:         props.product.sku,
    name:        props.product.name,
    description: props.product.description ?? '',
    price_usd:   props.product.price_usd,
    min_stock:   props.product.min_stock,
    category_id: props.product.category_id,
    supplier_id: props.product.supplier_id ?? '',
    is_active:   props.product.is_active,
});

const submit = () => form.put(route('products.update', props.product.id));
</script>

<template>
    <Head :title="`Editar — ${product.name}`" />
    <AuthenticatedLayout>
        <div class="p-6 max-w-3xl mx-auto">
            <PageHeader
                :title="`Editar: ${product.name}`"
                subtitle="Modifica los datos del producto"
                :back-href="route('products.show', product.id)"
            />

            <!-- Nota sobre stock -->
            <div class="mb-5 flex items-start gap-3 p-4 rounded-xl bg-amber-500/10 border border-amber-500/20">
                <svg class="w-5 h-5 text-amber-400 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                </svg>
                <div>
                    <p class="text-sm font-medium text-amber-300">El stock actual no se edita aquí</p>
                    <p class="text-xs text-amber-400/70 mt-0.5">Para ajustar el stock usa la sección de <a :href="route('inventory-movements.index')" class="underline hover:text-amber-300">Movimientos de Inventario</a>.</p>
                </div>
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                    <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-bold">1</span>
                        Identificación
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">SKU <span class="text-red-400">*</span></label>
                            <input v-model="form.sku" type="text"
                                :class="['font-mono w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.errors.sku ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                            <p v-if="form.errors.sku" class="text-red-400 text-xs mt-1.5">{{ form.errors.sku }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Código de Barras</label>
                            <input v-model="form.barcode" type="text"
                                :class="['font-mono w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.errors.barcode ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                            <p v-if="form.errors.barcode" class="text-red-400 text-xs mt-1.5">{{ form.errors.barcode }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Nombre <span class="text-red-400">*</span></label>
                            <input v-model="form.name" type="text"
                                :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.errors.name ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                            <p v-if="form.errors.name" class="text-red-400 text-xs mt-1.5">{{ form.errors.name }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Descripción</label>
                            <textarea v-model="form.description" rows="2"
                                class="w-full px-4 py-2.5 rounded-xl bg-gray-700 border border-gray-600 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none" />
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                    <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-bold">2</span>
                        Clasificación y Precio
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Categoría <span class="text-red-400">*</span></label>
                            <select v-model="form.category_id"
                                :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.errors.category_id ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']">
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Proveedor</label>
                            <select v-model="form.supplier_id"
                                class="w-full px-4 py-2.5 rounded-xl bg-gray-700 border border-gray-600 text-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">Sin proveedor</option>
                                <option v-for="sup in suppliers" :key="sup.id" :value="sup.id">{{ sup.name }}</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Precio (USD) <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">$</span>
                                <input v-model="form.price_usd" type="number" step="0.01" min="0"
                                    :class="['w-full pl-8 pr-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors tabular-nums',
                                        form.errors.price_usd ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Stock Mínimo</label>
                            <input v-model="form.min_stock" type="number" min="0"
                                class="w-full px-4 py-2.5 rounded-xl bg-gray-700 border border-gray-600 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors tabular-nums" />
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                    <div class="flex items-center justify-between flex-1 p-4 rounded-xl bg-gray-800 border border-gray-700">
                        <div>
                            <p class="text-sm font-medium text-white">Producto activo</p>
                            <p class="text-xs text-gray-400 mt-0.5">Visible y disponible en el sistema</p>
                        </div>
                        <button type="button" @click="form.is_active = !form.is_active"
                            :class="['relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200', form.is_active ? 'bg-indigo-600' : 'bg-gray-600']">
                            <span :class="['pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200', form.is_active ? 'translate-x-5' : 'translate-x-0']" />
                        </button>
                    </div>
                    <div class="flex gap-3">
                        <a :href="route('products.show', product.id)" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-300 bg-gray-700 hover:bg-gray-600 border border-gray-600 transition-colors">Cancelar</a>
                        <button type="submit" :disabled="form.processing || !form.isDirty"
                            class="px-6 py-2.5 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 transition-colors disabled:opacity-50 flex items-center gap-2">
                            <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
