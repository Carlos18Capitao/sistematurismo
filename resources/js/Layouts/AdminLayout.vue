<script setup>
import { Link, router, usePage } from '@inertiajs/vue3';

defineProps({ title: { type: String, default: 'Dashboard' } });

const page = usePage();
const user = page.props.auth.user;
const flash = page.props.flash;

const navItems = [
    { route: 'admin.dashboard', icon: 'fa-tachometer-alt', label: 'Dashboard' },
    { route: 'admin.tours.index', icon: 'fa-map-marked-alt', label: 'Tours' },
    { route: 'admin.bookings.index', icon: 'fa-calendar-check', label: 'Reservas' },
    { route: 'admin.reviews.index', icon: 'fa-star', label: 'Avaliações' },
];

function isActive(routeName) {
    return route().current(routeName) || route().current(routeName + '*');
}

function logout() {
    router.post(route('logout'));
}
</script>

<template>
    <div class="flex h-screen overflow-hidden bg-gray-100 font-sans antialiased">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex-shrink-0 hidden md:flex flex-col">
            <div class="p-5 border-b border-gray-700">
                <Link :href="route('home')" class="flex items-center gap-2">
                    <img :src="'/images/Sistur-logo.png'" alt="Sistur" class="h-12">
                    <span class="text-gray-400 text-sm font-normal">Admin</span>
                </Link>
            </div>

            <nav class="flex-1 p-4 space-y-1">
                <Link
                    v-for="item in navItems"
                    :key="item.route"
                    :href="route(item.route)"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition"
                    :class="isActive(item.route) ? 'bg-orange-500 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white'"
                >
                    <i :class="`fas ${item.icon} w-4`"></i>
                    {{ item.label }}
                </Link>
            </nav>

            <div class="p-4 border-t border-gray-700">
                <Link :href="route('home')" class="flex items-center gap-2 text-gray-400 hover:text-white text-sm transition mb-2">
                    <i class="fas fa-external-link-alt w-4"></i>Ver site
                </Link>
                <button @click="logout" class="flex items-center gap-2 text-red-400 hover:text-red-300 text-sm transition w-full">
                    <i class="fas fa-sign-out-alt w-4"></i>Sair
                </button>
            </div>
        </aside>

        <!-- Main -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <h1 class="text-xl font-bold text-gray-900">{{ title }}</h1>
                <div class="flex items-center gap-3 text-sm text-gray-600">
                    <i class="fas fa-user-circle text-gray-400"></i>
                    {{ user?.name }}
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                <div v-if="flash?.success" class="bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 mb-6 flex items-center gap-2">
                    <i class="fas fa-check-circle"></i>{{ flash.success }}
                </div>
                <div v-if="flash?.error" class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-6 flex items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>{{ flash.error }}
                </div>
                <slot />
            </main>
        </div>
    </div>
</template>
