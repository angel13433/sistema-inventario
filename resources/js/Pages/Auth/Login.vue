<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: { type: Boolean },
    status:           { type: String },
});

const form = useForm({
    email:    '',
    password: '',
    remember: false,
});

const showPassword = ref(false);

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Acceso — Sistema de Inventario" />

    <div class="flex min-h-screen bg-slate-50">
        
        <!-- Mitad Izquierda: Branding / Visual (Oculto en móviles) -->
        <div class="relative hidden w-1/2 flex-col justify-between overflow-hidden bg-slate-900 p-12 lg:flex">
            <!-- Fondo dinámico con gradientes y formas (Wow factor) -->
            <div class="absolute inset-0 pointer-events-none">
                <!-- Capa base oscura -->
                <div class="absolute inset-0 bg-gradient-to-br from-blue-950 via-slate-900 to-indigo-950"></div>
                
                <!-- Orbes brillantes desenfocados -->
                <div class="absolute -top-32 -left-32 h-[32rem] w-[32rem] rounded-full bg-blue-600/20 blur-[100px] animate-pulse"></div>
                <div class="absolute top-1/2 left-1/2 h-[40rem] w-[40rem] -translate-x-1/2 -translate-y-1/2 rounded-full bg-indigo-500/10 blur-[120px]"></div>
                <div class="absolute -bottom-40 -right-40 h-[36rem] w-[36rem] rounded-full bg-blue-400/20 blur-[100px]"></div>
                
                <!-- Patrón de puntos (Opcional, sutil) -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3N2Zz4=')] opacity-50"></div>
            </div>

            <!-- Logo top-left -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 text-white shadow-xl shadow-blue-500/30">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight text-white">Inventario<span class="text-blue-400">Pro</span></span>
            </div>

            <!-- Texto central -->
            <div class="relative z-10 max-w-lg animate-fade-up">
                <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl leading-tight">
                    Gestión inteligente para tu <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-300">almacén</span>
                </h1>
                <p class="mt-6 text-lg text-slate-300 leading-relaxed">
                    Controla el stock, supervisa los movimientos y mantén tus métricas actualizadas en tiempo real con una interfaz diseñada para la eficiencia.
                </p>
                
                <!-- Mini tarjeta estilo glassmorphism como detalle visual -->
                <div class="mt-10 inline-flex items-center gap-4 rounded-2xl bg-white/5 border border-white/10 p-4 backdrop-blur-md shadow-2xl">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-emerald-500/20 text-emerald-400">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-white">Sistema Activo y Seguro</p>
                        <p class="text-xs text-slate-400">Acceso exclusivo para personal autorizado</p>
                    </div>
                </div>
            </div>

            <!-- Footer left -->
            <div class="relative z-10">
                <p class="text-sm text-slate-500">
                    &copy; {{ new Date().getFullYear() }} Sistema de Inventario. Todos los derechos reservados.
                </p>
            </div>
        </div>

        <!-- Mitad Derecha: Formulario de Login -->
        <div class="flex w-full flex-col justify-center px-6 lg:w-1/2 lg:px-20 xl:px-32 relative">
            
            <!-- Logo solo para móviles -->
            <div class="absolute top-8 left-6 flex items-center gap-3 lg:hidden">
                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-600 text-white shadow-lg shadow-blue-600/30">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.429 9.75L2.25 12l4.179 2.25m0-4.5l5.571 3 5.571-3m-11.142 0L2.25 7.5 12 2.25l9.75 5.25-4.179 2.25m0 0L21.75 12l-4.179 2.25m0 0l4.179 2.25L12 21.75 2.25 16.5l4.179-2.25m11.142 0l-5.571 3-5.571-3" />
                    </svg>
                </div>
                <span class="text-lg font-bold text-slate-900">Inventario<span class="text-blue-600">Pro</span></span>
            </div>

            <div class="mx-auto w-full max-w-md">
                
                <div class="mb-10 text-center lg:text-left animate-fade-up">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-900">Bienvenido de nuevo</h2>
                    <p class="mt-2 text-sm text-slate-500">Por favor, ingresa tus credenciales de acceso.</p>
                </div>

                <!-- Status (contraseña restablecida, etc.) -->
                <div v-if="status" class="mb-6 flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800 shadow-sm animate-fade-up">
                    <svg class="mt-0.5 h-5 w-5 flex-shrink-0 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-sm font-medium">{{ status }}</p>
                </div>

                <form @submit.prevent="submit" class="space-y-6 animate-fade-up" style="animation-delay: 50ms;">
                    
                    <!-- Correo electrónico -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-700">
                            Correo Electrónico
                        </label>
                        <div class="relative mt-2">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                            </div>
                            <input
                                id="email"
                                v-model="form.email"
                                type="email"
                                placeholder="ejemplo@empresa.com"
                                required
                                autofocus
                                autocomplete="username"
                                :class="[
                                    'block w-full rounded-xl border-0 py-3 pl-11 pr-4 text-slate-900 shadow-sm ring-1 ring-inset transition-all duration-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6',
                                    form.errors.email ? 'ring-rose-500 focus:ring-rose-500 bg-rose-50/30' : 'ring-slate-300 focus:ring-blue-600 bg-white hover:ring-slate-400'
                                ]"
                            />
                        </div>
                        <p v-if="form.errors.email" class="mt-2 text-sm text-rose-600 flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="password" class="block text-sm font-medium text-slate-700">
                                Contraseña
                            </label>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="text-sm font-medium text-blue-600 hover:text-blue-500 transition-colors"
                            >
                                ¿Olvidaste tu contraseña?
                            </Link>
                        </div>
                        <div class="relative mt-2">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <input
                                id="password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="••••••••"
                                required
                                autocomplete="current-password"
                                :class="[
                                    'block w-full rounded-xl border-0 py-3 pl-11 pr-12 text-slate-900 shadow-sm ring-1 ring-inset transition-all duration-200 placeholder:text-slate-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6',
                                    form.errors.password ? 'ring-rose-500 focus:ring-rose-500 bg-rose-50/30' : 'ring-slate-300 focus:ring-blue-600 bg-white hover:ring-slate-400'
                                ]"
                            />
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                                :title="showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'"
                            >
                                <svg v-if="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <p v-if="form.errors.password" class="mt-2 text-sm text-rose-600 flex items-center gap-1.5">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Recordarme -->
                    <div class="flex items-center">
                        <button
                            type="button"
                            id="remember-toggle"
                            @click="form.remember = !form.remember"
                            :class="[
                                'relative inline-flex h-5 w-9 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2',
                                form.remember ? 'bg-blue-600' : 'bg-slate-200'
                            ]"
                            role="switch"
                            :aria-checked="form.remember"
                        >
                            <span
                                aria-hidden="true"
                                :class="[
                                    'pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out',
                                    form.remember ? 'translate-x-4' : 'translate-x-0'
                                ]"
                            />
                        </button>
                        <label @click="form.remember = !form.remember" class="ml-3 block text-sm font-medium leading-6 text-slate-700 cursor-pointer select-none">
                            Mantener sesión iniciada
                        </label>
                    </div>

                    <!-- Botón submit -->
                    <div>
                        <button
                            id="login-submit"
                            type="submit"
                            :disabled="form.processing"
                            class="group relative flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-blue-600/25 transition-all duration-200 hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 disabled:opacity-70 disabled:cursor-not-allowed active:scale-[0.98]"
                        >
                            <svg v-if="form.processing" class="h-5 w-5 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            <span v-else class="absolute inset-y-0 left-0 flex items-center pl-4 opacity-50 transition-opacity group-hover:opacity-100">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </span>
                            
                            {{ form.processing ? 'Verificando credenciales...' : 'Ingresar al sistema' }}
                            
                            <svg v-if="!form.processing" class="absolute inset-y-0 right-0 mr-4 h-5 w-5 translate-x-0 opacity-0 transition-all duration-200 group-hover:translate-x-1 group-hover:opacity-100" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Keyframes sutiles para la animación de entrada */
@keyframes fade-up {
    0% { opacity: 0; transform: translateY(10px); }
    100% { opacity: 1; transform: translateY(0); }
}
.animate-fade-up {
    animation: fade-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
}
</style>
