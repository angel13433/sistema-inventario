<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/Ui/PageHeader.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    categories: { type: Array, default: () => [] },
    suppliers:  { type: Array, default: () => [] },
});

const form = useForm({
    barcode:       '',
    sku:           '',
    name:          '',
    description:   '',
    price_usd:     '',
    min_stock:     0,
    current_stock: 0,
    category_id:   '',
    supplier_id:   '',
    is_active:     true,
});

const submit = () => form.post(route('products.store'));
</script>

<template>
    <Head title="Nuevo Producto — Inventario" />
    <AuthenticatedLayout>
        <div class="p-6 max-w-3xl mx-auto">
            <PageHeader
                title="Nuevo Producto"
                subtitle="Agrega un producto al catálogo de inventario"
                :back-href="route('products.index')"
            />

            <form @submit.prevent="submit" class="space-y-5">

                <!-- Identificación -->
                <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                    <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-bold">1</span>
                        Identificación del Producto
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">SKU <span class="text-red-400">*</span></label>
                            <input v-model="form.sku" type="text" placeholder="Ej: ELEC-001"
                                :class="['font-mono w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.errors.sku ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                            <p v-if="form.errors.sku" class="text-red-400 text-xs mt-1.5">{{ form.errors.sku }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Código de Barras</label>
                            <input v-model="form.barcode" type="text" placeholder="EAN-13 / UPC-A"
                                :class="['font-mono w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.errors.barcode ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                            <p v-if="form.errors.barcode" class="text-red-400 text-xs mt-1.5">{{ form.errors.barcode }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Nombre del Producto <span class="text-red-400">*</span></label>
                            <input v-model="form.name" type="text" placeholder="Nombre descriptivo del producto"
                                :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.errors.name ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                            <p v-if="form.errors.name" class="text-red-400 text-xs mt-1.5">{{ form.errors.name }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Descripción</label>
                            <textarea v-model="form.description" rows="2" placeholder="Descripción opcional del producto..."
                                class="w-full px-4 py-2.5 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none" />
                        </div>
                    </div>
                </div>

                <!-- Clasificación -->
                <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                    <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-bold">2</span>
                        Clasificación
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Categoría <span class="text-red-400">*</span></label>
                            <select v-model="form.category_id"
                                :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.errors.category_id ? 'border-red-500 text-white' : 'border-gray-600 focus:border-indigo-500 text-gray-300']">
                                <option value="" disabled>Seleccionar categoría...</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                            <p v-if="form.errors.category_id" class="text-red-400 text-xs mt-1.5">{{ form.errors.category_id }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Proveedor</label>
                            <select v-model="form.supplier_id"
                                class="w-full px-4 py-2.5 rounded-xl bg-gray-700 border border-gray-600 text-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors">
                                <option value="">Sin proveedor</option>
                                <option v-for="sup in suppliers" :key="sup.id" :value="sup.id">{{ sup.name }} ({{ sup.rif }})</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Precio y Stock -->
                <div class="rounded-2xl bg-gray-800 border border-gray-700 p-6">
                    <h3 class="text-sm font-semibold text-white mb-4 flex items-center gap-2">
                        <span class="w-5 h-5 rounded-full bg-indigo-600 text-white text-xs flex items-center justify-center font-bold">3</span>
                        Precio y Stock
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Precio (USD) <span class="text-red-400">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-medium">$</span>
                                <input v-model="form.price_usd" type="number" step="0.01" min="0" placeholder="0.00"
                                    :class="['w-full pl-8 pr-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors tabular-nums',
                                        form.errors.price_usd ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                            </div>
                            <p v-if="form.errors.price_usd" class="text-red-400 text-xs mt-1.5">{{ form.errors.price_usd }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Stock Inicial</label>
                            <input v-model="form.current_stock" type="number" min="0"
                                :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors tabular-nums',
                                    form.errors.current_stock ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                            <p class="text-gray-500 text-xs mt-1">Unidades al momento del registro</p>
                            <p v-if="form.errors.current_stock" class="text-red-400 text-xs mt-1">{{ form.errors.current_stock }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-1.5">Stock Mínimo</label>
                            <input v-model="form.min_stock" type="number" min="0"
                                class="w-full px-4 py-2.5 rounded-xl bg-gray-700 border border-gray-600 text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors tabular-nums" />
                            <p class="text-gray-500 text-xs mt-1">Nivel de alerta de reposición</p>
                        </div>
                    </div>
                </div>

                <!-- Estado + Acciones -->
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
                        <a :href="route('products.index')" class="px-5 py-2.5 rounded-xl text-sm font-medium text-gray-300 bg-gray-700 hover:bg-gray-600 border border-gray-600 transition-colors">Cancelar</a>
                        <button type="submit" :disabled="form.processing"
                            class="px-6 py-2.5 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 transition-colors disabled:opacity-50 flex items-center gap-2">
                            <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            {{ form.processing ? 'Creando...' : 'Crear Producto' }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
