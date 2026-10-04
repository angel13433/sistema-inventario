<script setup>
import { ref, computed, watch } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page = usePage();
const user = computed(() => page.props.auth.user);
const flash = computed(() => page.props.flash ?? {});

const sidebarOpen = ref(false);
const flashVisible = ref(false);
const flashMessage = ref('');
const flashType = ref('success');

watch(flash, (val) => {
    if (val.success) {
        flashMessage.value = val.success;
        flashType.value = 'success';
        flashVisible.value = true;
        setTimeout(() => { flashVisible.value = false; }, 4000);
    } else if (val.error) {
        flashMessage.value = val.error;
        flashType.value = 'error';
        flashVisible.value = true;
        setTimeout(() => { flashVisible.value = false; }, 5000);
    }
}, { immediate: true, deep: true });

const userInitials = computed(() => {
    if (!user.value?.name) return '?';
    return user.value.name.split(' ').map(n => n[0]).slice(0, 2).join('').toUpperCase();
});

const navigation = [
    {
        name: 'Dashboard',
        href: () => route('dashboard'),
        routePattern: 'dashboard',
        icon: `<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />`,
    },
    {
        name: 'Productos',
        href: () => route('products.index'),
        routePattern: 'products.*',
        icon: `<path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />`,
    },
    {
        name: 'Categorías',
        href: () => route('categories.index'),
        routePattern: 'categories.*',
        icon: `<path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" /><path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />`,
    },
    {
        name: 'Proveedores',
        href: () => route('suppliers.index'),
        routePattern: 'suppliers.*',
        icon: `<path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />`,
    },
    {
        name: 'Movimientos',
        href: () => route('inventory-movements.index'),
        routePattern: 'inventory-movements.*',
        icon: `<path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5L7.5 3m0 0L12 7.5M7.5 3v13.5m13.5 0L16.5 21m0 0L12 16.5m4.5 4.5V7.5" />`,
    },
];

const isActive = (pattern) => {
    try { return route().current(pattern); } catch { return false; }
};

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div class="flex h-screen overflow-hidden bg-gray-900">

        <!-- Mobile overlay -->
        <div
            v-if="sidebarOpen"
            class="fixed inset-0 z-20 bg-black/60 backdrop-blur-sm lg:hidden"
            @click="sidebarOpen = false"
        />

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-30 w-64 flex flex-col',
                'bg-slate-900 border-r border-slate-700/50',
                'transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:z-auto',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full'
            ]"
        >
            <!-- Logo -->
            <div class="flex items-center gap-3 h-16 px-5 border-b border-slate-700/50 flex-shrink-0">
                <div class="flex items-center justify-center w-9 h-9 rounded-xl bg-indigo-600 shadow-lg shadow-indigo-600/30">
                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-sm leading-tight">Inventario</p>
                    <p class="text-slate-400 text-xs">Sistema de Control</p>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
                <template v-for="item in navigation" :key="item.name">
                    <Link
                        :href="item.href()"
                        @click="sidebarOpen = false"
                        :class="[
                            'group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150',
                            isActive(item.routePattern)
                                ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30'
                                : 'text-slate-400 hover:text-white hover:bg-slate-800'
                        ]"
                    >
                        <svg
                            class="w-5 h-5 flex-shrink-0 transition-colors"
                            :class="isActive(item.routePattern) ? 'text-indigo-400' : 'text-slate-500 group-hover:text-slate-300'"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor"
                            v-html="item.icon"
                        />
                        <span>{{ item.name }}</span>
                        <!-- Active indicator -->
                        <span
                            v-if="isActive(item.routePattern)"
                            class="ml-auto w-1.5 h-1.5 rounded-full bg-indigo-400"
                        />
                    </Link>
                </template>
            </nav>

            <!-- User section -->
            <div class="border-t border-slate-700/50 p-3 flex-shrink-0">
                <div class="flex items-center gap-3 px-2 py-2">
                    <div class="flex-shrink-0 w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow">
                        <span class="text-white text-xs font-bold">{{ userInitials }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ user?.name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ user?.email }}</p>
                    </div>
                    <button
                        @click="logout"
                        title="Cerrar sesión"
                        class="flex-shrink-0 p-1.5 rounded-lg text-slate-500 hover:text-red-400 hover:bg-red-400/10 transition-colors"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <!-- Top bar (mobile) -->
            <header class="lg:hidden flex items-center gap-3 h-14 px-4 border-b border-gray-700/50 bg-slate-900 flex-shrink-0">
                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <span class="text-white font-semibold text-sm">Inventario</span>
            </header>

            <!-- Flash notification -->
            <Transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0 translate-y-[-8px]"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-200"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-[-8px]"
            >
                <div
                    v-if="flashVisible"
                    :class="[
                        'fixed top-4 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-xl shadow-2xl border max-w-sm',
                        flashType === 'success'
                            ? 'bg-emerald-950 border-emerald-700/50 text-emerald-300'
                            : 'bg-red-950 border-red-700/50 text-red-300'
                    ]"
                >
                    <svg v-if="flashType === 'success'" class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <svg v-else class="w-5 h-5 text-red-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <p class="text-sm font-medium">{{ flashMessage }}</p>
                    <button @click="flashVisible = false" class="ml-auto text-current opacity-60 hover:opacity-100 transition-opacity">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </Transition>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto">
                <slot />
            </main>
        </div>
    </div>
</template>
