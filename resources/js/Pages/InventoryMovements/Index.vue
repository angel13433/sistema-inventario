<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PageHeader from '@/Components/Ui/PageHeader.vue';
import Badge from '@/Components/Ui/Badge.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    movements:     { type: Object, required: true },
    products:      { type: Array, default: () => [] },
    movementTypes: { type: Array, default: () => [] },
    filters:       { type: Object, default: () => ({}) },
});

// Filtros
const filterProduct  = ref(props.filters.product_id ?? '');
const filterType     = ref(props.filters.type ?? '');
const filterDateFrom = ref(props.filters.date_from ?? '');
const filterDateTo   = ref(props.filters.date_to ?? '');

let filterTimer = null;
watch([filterProduct, filterType, filterDateFrom, filterDateTo], () => {
    clearTimeout(filterTimer);
    filterTimer = setTimeout(() => {
        router.get(route('inventory-movements.index'), {
            product_id: filterProduct.value || undefined,
            type:       filterType.value || undefined,
            date_from:  filterDateFrom.value || undefined,
            date_to:    filterDateTo.value || undefined,
        }, { preserveState: true, replace: true });
    }, 300);
});

// Modal de nuevo movimiento
const showModal = ref(false);

const form = useForm({
    product_id: '',
    type:       '',
    quantity:   1,
    reason:     '',
    notes:      '',
});

const submit = () => {
    form.post(route('inventory-movements.store'), {
        onSuccess: () => {
            showModal.value = false;
            form.reset();
        },
    });
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
    form.clearErrors();
};

// Helpers
const typeVariantMap = {
    entry:      { variant: 'success', label: 'Entrada',  sign: '+', signClass: 'text-emerald-400' },
    exit:       { variant: 'danger',  label: 'Salida',   sign: '-', signClass: 'text-red-400' },
    adjustment: { variant: 'warning', label: 'Ajuste',   sign: '±', signClass: 'text-amber-400' },
};

const formatDate = (d) => d ? new Date(d).toLocaleString('es-VE', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '—';
</script>

<template>
    <Head title="Movimientos de Inventario — Inventario" />
    <AuthenticatedLayout>
        <div class="p-6 space-y-5">
            <PageHeader title="Movimientos de Inventario" subtitle="Historial completo de entradas, salidas y ajustes de stock">
                <template #actions>
                    <button
                        @click="showModal = true"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-medium transition-colors shadow-lg shadow-indigo-600/20"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Registrar Movimiento
                    </button>
                </template>
            </PageHeader>

            <!-- Filtros -->
            <div class="flex flex-wrap items-center gap-3">
                <select v-model="filterProduct"
                    class="px-4 py-2.5 rounded-xl bg-gray-800 border border-gray-700 text-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors min-w-48 flex-1">
                    <option value="">Todos los productos</option>
                    <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }} ({{ p.sku }})</option>
                </select>
                <select v-model="filterType"
                    class="px-4 py-2.5 rounded-xl bg-gray-800 border border-gray-700 text-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                    <option value="">Todos los tipos</option>
                    <option v-for="t in movementTypes" :key="t.value" :value="t.value">{{ t.label }}</option>
                </select>
                <input v-model="filterDateFrom" type="date"
                    class="px-4 py-2.5 rounded-xl bg-gray-800 border border-gray-700 text-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" />
                <input v-model="filterDateTo" type="date"
                    class="px-4 py-2.5 rounded-xl bg-gray-800 border border-gray-700 text-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors" />
            </div>

            <!-- Tabla -->
            <div class="rounded-2xl bg-gray-800 border border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-700">
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Tipo</th>
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Producto</th>
                                <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Cantidad</th>
                                <th class="text-center px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden md:table-cell">Stock</th>
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden sm:table-cell">Motivo</th>
                                <th class="text-left px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider hidden lg:table-cell">Usuario</th>
                                <th class="text-right px-5 py-3.5 text-xs font-semibold text-gray-400 uppercase tracking-wider">Fecha</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700/50">
                            <tr v-if="movements.data.length === 0">
                                <td colspan="7" class="px-5 py-16 text-center">
                                    <svg class="w-10 h-10 text-gray-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />
                                    </svg>
                                    <p class="text-gray-400 font-medium">Sin movimientos registrados</p>
                                </td>
                            </tr>
                            <tr v-for="m in movements.data" :key="m.id" class="hover:bg-gray-700/20 transition-colors">
                                <td class="px-5 py-3.5">
                                    <Badge
                                        :variant="typeVariantMap[m.type]?.variant ?? 'gray'"
                                        :text="typeVariantMap[m.type]?.label ?? m.type"
                                        :dot="true"
                                    />
                                </td>
                                <td class="px-5 py-3.5">
                                    <Link :href="route('products.show', m.product?.id)" class="block hover:text-indigo-400 transition-colors">
                                        <p class="font-medium text-white">{{ m.product?.name ?? '—' }}</p>
                                        <p class="text-xs text-gray-500 font-mono">{{ m.product?.sku }}</p>
                                    </Link>
                                </td>
                                <td class="px-5 py-3.5 text-center">
                                    <span :class="['text-base font-bold tabular-nums', typeVariantMap[m.type]?.signClass ?? 'text-white']">
                                        {{ typeVariantMap[m.type]?.sign }}{{ m.quantity }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-center hidden md:table-cell">
                                    <span class="text-gray-500 tabular-nums text-xs">{{ m.previous_stock }}</span>
                                    <span class="text-gray-600 mx-1.5">→</span>
                                    <span class="text-white font-semibold tabular-nums text-xs">{{ m.new_stock }}</span>
                                </td>
                                <td class="px-5 py-3.5 hidden sm:table-cell">
                                    <p class="text-gray-300 truncate max-w-[200px]">{{ m.reason }}</p>
                                    <p v-if="m.notes" class="text-gray-600 text-xs truncate max-w-[200px]">{{ m.notes }}</p>
                                </td>
                                <td class="px-5 py-3.5 hidden lg:table-cell">
                                    <p class="text-gray-400 text-xs">{{ m.user?.name ?? '—' }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-right">
                                    <p class="text-gray-500 text-xs whitespace-nowrap">{{ formatDate(m.created_at) }}</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div v-if="movements.last_page > 1" class="flex items-center justify-between px-5 py-3.5 border-t border-gray-700">
                    <p class="text-xs text-gray-400">Mostrando {{ movements.from }}–{{ movements.to }} de {{ movements.total }} movimientos</p>
                    <div class="flex gap-1">
                        <Link v-for="link in movements.links" :key="link.label" :href="link.url ?? '#'" v-html="link.label"
                            :class="['px-3 py-1.5 rounded-lg text-xs font-medium transition-colors', link.active ? 'bg-indigo-600 text-white' : link.url ? 'text-gray-400 hover:text-white hover:bg-gray-700' : 'text-gray-600 cursor-not-allowed']" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal: Registrar Movimiento -->
        <Teleport to="body">
            <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100"
                leave-active-class="transition ease-in duration-150" leave-from-class="opacity-100" leave-to-class="opacity-0">
                <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4" @click.self="closeModal">
                    <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" />
                    <Transition enter-active-class="transition ease-out duration-200" enter-from-class="opacity-0 scale-95" enter-to-class="opacity-100 scale-100">
                        <div v-if="showModal" class="relative w-full max-w-lg rounded-2xl bg-gray-800 border border-gray-700 shadow-2xl">
                            <!-- Header -->
                            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-700">
                                <h2 class="text-base font-bold text-white">Registrar Movimiento</h2>
                                <button @click="closeModal" class="p-1.5 rounded-lg text-gray-400 hover:text-white hover:bg-gray-700 transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <!-- Body -->
                            <form @submit.prevent="submit" class="p-6 space-y-4">

                                <!-- Tipo de movimiento -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Tipo de Movimiento <span class="text-red-400">*</span></label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <button
                                            v-for="t in movementTypes"
                                            :key="t.value"
                                            type="button"
                                            @click="form.type = t.value"
                                            :class="['px-3 py-2.5 rounded-xl text-sm font-medium border transition-all text-center',
                                                form.type === t.value
                                                    ? t.value === 'entry' ? 'bg-emerald-500/20 border-emerald-500/40 text-emerald-400'
                                                      : t.value === 'exit' ? 'bg-red-500/20 border-red-500/40 text-red-400'
                                                      : 'bg-amber-500/20 border-amber-500/40 text-amber-400'
                                                    : 'bg-gray-700 border-gray-600 text-gray-400 hover:text-white hover:border-gray-500']"
                                        >
                                            {{ t.label }}
                                        </button>
                                    </div>
                                    <p v-if="form.errors.type" class="text-red-400 text-xs mt-1.5">{{ form.errors.type }}</p>
                                </div>

                                <!-- Producto -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Producto <span class="text-red-400">*</span></label>
                                    <select v-model="form.product_id"
                                        :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                            form.errors.product_id ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']">
                                        <option value="" disabled>Seleccionar producto...</option>
                                        <option v-for="p in products" :key="p.id" :value="p.id">{{ p.name }} ({{ p.sku }})</option>
                                    </select>
                                    <p v-if="form.errors.product_id" class="text-red-400 text-xs mt-1.5">{{ form.errors.product_id }}</p>
                                </div>

                                <!-- Cantidad -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">
                                        Cantidad
                                        <span v-if="form.type === 'adjustment'" class="text-amber-400 text-xs font-normal ml-1">(nuevo valor absoluto de stock)</span>
                                        <span class="text-red-400">*</span>
                                    </label>
                                    <input v-model="form.quantity" type="number" min="1"
                                        :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors tabular-nums',
                                            form.errors.quantity ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                                    <p v-if="form.errors.quantity" class="text-red-400 text-xs mt-1.5">{{ form.errors.quantity }}</p>
                                </div>

                                <!-- Motivo -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Motivo <span class="text-red-400">*</span></label>
                                    <input v-model="form.reason" type="text" placeholder="Ej: Compra a proveedor, Venta, Inventario físico..."
                                        :class="['w-full px-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                            form.errors.reason ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']" />
                                    <p v-if="form.errors.reason" class="text-red-400 text-xs mt-1.5">{{ form.errors.reason }}</p>
                                </div>

                                <!-- Notas -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-1.5">Observaciones</label>
                                    <textarea v-model="form.notes" rows="2" placeholder="Notas adicionales opcionales..."
                                        class="w-full px-4 py-2.5 rounded-xl bg-gray-700 border border-gray-600 text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none" />
                                </div>

                                <!-- Alerta stock insuficiente -->
                                <div v-if="form.errors.quantity && form.type === 'exit'" class="flex items-start gap-2 p-3 rounded-xl bg-red-500/10 border border-red-500/20">
                                    <svg class="w-4 h-4 text-red-400 mt-0.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                                    </svg>
                                    <p class="text-red-400 text-xs">{{ form.errors.quantity }}</p>
                                </div>

                                <!-- Acciones -->
                                <div class="flex gap-3 pt-1">
                                    <button type="button" @click="closeModal"
                                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-gray-300 bg-gray-700 hover:bg-gray-600 border border-gray-600 transition-colors">
                                        Cancelar
                                    </button>
                                    <button type="submit" :disabled="form.processing"
                                        class="flex-1 px-4 py-2.5 rounded-xl text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-500 transition-colors disabled:opacity-50 flex items-center justify-center gap-2">
                                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                                        </svg>
                                        {{ form.processing ? 'Registrando...' : 'Registrar Movimiento' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </Transition>
                </div>
            </Transition>
        </Teleport>
    </AuthenticatedLayout>
</template>
