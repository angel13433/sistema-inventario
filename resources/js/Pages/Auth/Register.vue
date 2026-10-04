<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    name:                  '',
    email:                 '',
    password:              '',
    password_confirmation: '',
});

const showPassword    = ref(false);
const showConfirm     = ref(false);

const strengthLevel = (pwd) => {
    if (!pwd) return 0;
    let score = 0;
    if (pwd.length >= 8)            score++;
    if (/[A-Z]/.test(pwd))          score++;
    if (/[0-9]/.test(pwd))          score++;
    if (/[^A-Za-z0-9]/.test(pwd))  score++;
    return score;
};

const strengthLabel = (level) => {
    return ['', 'Débil', 'Regular', 'Buena', 'Fuerte'][level] ?? '';
};

const strengthColor = (level) => {
    return [
        '',
        'bg-red-500',
        'bg-amber-500',
        'bg-yellow-400',
        'bg-emerald-500',
    ][level] ?? '';
};

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Crear Cuenta — Inventario" />

    <div class="min-h-screen bg-gray-900 flex items-center justify-center p-4">

        <!-- Fondo decorativo -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-96 h-96 rounded-full bg-violet-600/10 blur-3xl" />
            <div class="absolute -bottom-40 -left-40 w-96 h-96 rounded-full bg-indigo-600/10 blur-3xl" />
        </div>

        <div class="relative w-full max-w-md">

            <!-- Logo y marca -->
            <div class="flex flex-col items-center mb-8">
                <div class="flex items-center justify-center w-14 h-14 rounded-2xl bg-indigo-600 shadow-2xl shadow-indigo-600/40 mb-4">
                    <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-white">Crear Cuenta</h1>
                <p class="text-gray-400 text-sm mt-1">Registra un nuevo usuario en el sistema</p>
            </div>

            <!-- Tarjeta del formulario -->
            <div class="rounded-2xl bg-gray-800 border border-gray-700 shadow-2xl p-8">

                <form @submit.prevent="submit" class="space-y-4">

                    <!-- Nombre completo -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Nombre Completo <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                            <input
                                id="name"
                                v-model="form.name"
                                type="text"
                                placeholder="Nombre y Apellido"
                                required
                                autofocus
                                autocomplete="name"
                                :class="['w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.errors.name ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']"
                            />
                        </div>
                        <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Correo electrónico -->
                    <div>
                        <label for="reg-email" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Correo Electrónico <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            <input
                                id="reg-email"
                                v-model="form.email"
                                type="email"
                                placeholder="correo@empresa.com.ve"
                                required
                                autocomplete="username"
                                :class="['w-full pl-10 pr-4 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.errors.email ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']"
                            />
                        </div>
                        <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="reg-password" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Contraseña <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            <input
                                id="reg-password"
                                v-model="form.password"
                                :type="showPassword ? 'text' : 'password'"
                                placeholder="Mínimo 8 caracteres"
                                required
                                autocomplete="new-password"
                                :class="['w-full pl-10 pr-11 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.errors.password ? 'border-red-500' : 'border-gray-600 focus:border-indigo-500']"
                            />
                            <button type="button" @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-200 transition-colors">
                                <svg v-if="!showPassword" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>

                        <!-- Indicador de fortaleza -->
                        <div v-if="form.password" class="mt-2">
                            <div class="flex gap-1 mb-1">
                                <div v-for="i in 4" :key="i"
                                    :class="['h-1 flex-1 rounded-full transition-all duration-300',
                                        i <= strengthLevel(form.password) ? strengthColor(strengthLevel(form.password)) : 'bg-gray-700']"
                                />
                            </div>
                            <p class="text-xs" :class="{
                                'text-red-400':    strengthLevel(form.password) === 1,
                                'text-amber-400':  strengthLevel(form.password) === 2,
                                'text-yellow-400': strengthLevel(form.password) === 3,
                                'text-emerald-400':strengthLevel(form.password) === 4,
                            }">
                                Contraseña {{ strengthLabel(strengthLevel(form.password)) }}
                            </p>
                        </div>

                        <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Confirmar contraseña -->
                    <div>
                        <label for="password-confirm" class="block text-sm font-medium text-gray-300 mb-1.5">
                            Confirmar Contraseña <span class="text-red-400">*</span>
                        </label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                            <input
                                id="password-confirm"
                                v-model="form.password_confirmation"
                                :type="showConfirm ? 'text' : 'password'"
                                placeholder="Repite la contraseña"
                                required
                                autocomplete="new-password"
                                :class="['w-full pl-10 pr-11 py-2.5 rounded-xl bg-gray-700 border text-white placeholder-gray-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors',
                                    form.password_confirmation && form.password !== form.password_confirmation
                                        ? 'border-amber-500'
                                        : form.errors.password_confirmation
                                            ? 'border-red-500'
                                            : 'border-gray-600 focus:border-indigo-500']"
                            />
                            <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-200 transition-colors">
                                <svg v-if="!showConfirm" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        <!-- Validación en tiempo real de coincidencia -->
                        <p v-if="form.password_confirmation && form.password !== form.password_confirmation"
                            class="mt-1.5 text-xs text-amber-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            Las contraseñas no coinciden
                        </p>
                        <p v-else-if="form.password_confirmation && form.password === form.password_confirmation"
                            class="mt-1.5 text-xs text-emerald-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Las contraseñas coinciden
                        </p>
                        <p v-if="form.errors.password_confirmation" class="mt-1.5 text-xs text-red-400 flex items-center gap-1">
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                            {{ form.errors.password_confirmation }}
                        </p>
                    </div>

                    <!-- Botón de registro -->
                    <button
                        id="register-submit"
                        type="submit"
                        :disabled="form.processing"
                        class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-semibold transition-all duration-150 shadow-lg shadow-indigo-600/20 disabled:opacity-50 disabled:cursor-not-allowed mt-2"
                    >
                        <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <svg v-else class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                        </svg>
                        {{ form.processing ? 'Creando cuenta...' : 'Crear Cuenta' }}
                    </button>
                </form>
            </div>

            <!-- Enlace a login -->
            <p class="text-center text-sm text-gray-500 mt-6">
                ¿Ya tienes cuenta?
                <Link :href="route('login')" class="text-indigo-400 hover:text-indigo-300 font-medium transition-colors ml-1">
                    Iniciar sesión
                </Link>
            </p>

            <!-- Footer -->
            <p class="text-center text-xs text-gray-600 mt-4">
                Sistema de Control de Inventario &mdash; Venezuela
            </p>
        </div>
    </div>
</template>
