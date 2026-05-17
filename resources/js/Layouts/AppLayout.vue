<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import FlashMessage from '@/Components/FlashMessage.vue';

const page = usePage();
const mobileOpen = ref(false);
const userDropOpen = ref(false);

const user = page.props.auth.user;

function logout() {
    router.post(route('logout'));
}

function closeDropdown(e) {
    if (!e.target.closest('#user-dropdown')) {
        userDropOpen.value = false;
    }
}

onMounted(() => document.addEventListener('click', closeDropdown));
onUnmounted(() => document.removeEventListener('click', closeDropdown));
</script>

<template>
    <div class="min-h-full bg-gray-50 text-gray-900 font-sans antialiased">
        <!-- Navbar -->
        <nav class="bg-white shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <Link :href="route('home')" class="flex items-center gap-2">
                        <img :src="'/images/Sistur-logo.png'" alt="Sistur" class="h-10">
                    </Link>

                    <!-- Desktop nav -->
                    <div class="hidden md:flex items-center gap-6">
                        <Link :href="route('home')" class="text-gray-600 hover:text-orange-500 transition font-medium">Início</Link>
                        <Link :href="route('tours.index')" class="text-gray-600 hover:text-orange-500 transition font-medium">Tours</Link>

                        <template v-if="user">
                            <Link :href="route('bookings.index')" class="text-gray-600 hover:text-orange-500 transition font-medium">Minhas Reservas</Link>
                            <Link v-if="user.role === 'admin'" :href="route('admin.dashboard')" class="text-gray-600 hover:text-orange-500 transition font-medium">
                                <i class="fas fa-shield-alt mr-1"></i>Admin
                            </Link>

                            <!-- User dropdown -->
                            <div id="user-dropdown" class="relative">
                                <button @click="userDropOpen = !userDropOpen" class="flex items-center gap-2 text-gray-600 hover:text-orange-500 transition">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-semibold text-sm">
                                        {{ user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </button>
                                <div v-show="userDropOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900">{{ user.name }}</p>
                                        <p class="text-xs text-gray-500">{{ user.email }}</p>
                                    </div>
                                    <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <i class="fas fa-user-circle mr-2 text-gray-400"></i>Perfil
                                    </Link>
                                    <button @click="logout" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Sair
                                    </button>
                                </div>
                            </div>
                        </template>

                        <template v-else>
                            <Link :href="route('login')" class="text-gray-600 hover:text-orange-500 transition font-medium">Entrar</Link>
                            <Link :href="route('register')" class="bg-orange-500 text-white px-4 py-2 rounded-lg hover:bg-orange-600 transition font-medium">Registar</Link>
                        </template>
                    </div>

                    <button @click="mobileOpen = !mobileOpen" class="md:hidden text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile menu -->
            <div v-show="mobileOpen" class="md:hidden bg-white border-t border-gray-100 px-4 py-3 space-y-2">
                <Link :href="route('home')" class="block py-2 text-gray-700 font-medium">Início</Link>
                <Link :href="route('tours.index')" class="block py-2 text-gray-700 font-medium">Tours</Link>
                <template v-if="user">
                    <Link :href="route('bookings.index')" class="block py-2 text-gray-700 font-medium">Minhas Reservas</Link>
                    <Link v-if="user.role === 'admin'" :href="route('admin.dashboard')" class="block py-2 text-gray-700 font-medium">Admin</Link>
                    <button @click="logout" class="w-full bg-red-50 text-red-600 py-2 rounded-lg font-medium mt-2">Sair</button>
                </template>
                <template v-else>
                    <Link :href="route('login')" class="block py-2 text-gray-700 font-medium">Entrar</Link>
                    <Link :href="route('register')" class="block bg-orange-500 text-white text-center py-2 rounded-lg font-medium">Registar</Link>
                </template>
            </div>
        </nav>

        <FlashMessage />

        <main>
            <slot />
        </main>

        <!-- Footer -->
        <footer class="bg-gray-900 text-gray-300 mt-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div class="md:col-span-2">
                        <div class="flex items-center gap-2 mb-4">
                            <img :src="'/images/Sistur-logo.png'" alt="Sistur" class="h-[60px]">
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed max-w-xs">Descubra a beleza de Angola através de experiências turísticas únicas e inesquecíveis.</p>
                        <div class="flex gap-3 mt-4">
                            <a href="#" class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-orange-500 transition"><i class="fab fa-facebook-f text-xs"></i></a>
                            <a href="#" class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-orange-500 transition"><i class="fab fa-instagram text-xs"></i></a>
                            <a href="#" class="w-8 h-8 bg-gray-700 rounded-full flex items-center justify-center hover:bg-orange-500 transition"><i class="fab fa-whatsapp text-xs"></i></a>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-3">Explorar</h4>
                        <ul class="space-y-2 text-sm">
                            <li><Link :href="route('tours.index')" class="hover:text-orange-400 transition">Todos os Tours</Link></li>
                            <li v-for="city in ['Luanda', 'Benguela', 'Huíla']" :key="city">
                                <Link :href="route('tours.index', { city })" class="hover:text-orange-400 transition">Tours em {{ city }}</Link>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="text-white font-semibold mb-3">Contacto</h4>
                        <ul class="space-y-2 text-sm">
                            <li class="flex items-center gap-2"><i class="fas fa-envelope text-orange-400 w-4"></i>geral@sistur.ao</li>
                            <li class="flex items-center gap-2"><i class="fas fa-phone text-orange-400 w-4"></i>+244 923 000 000</li>
                            <li class="flex items-center gap-2"><i class="fas fa-map-marker-alt text-orange-400 w-4"></i>Luanda, Angola</li>
                        </ul>
                    </div>
                </div>
                <div class="border-t border-gray-700 mt-8 pt-6 flex flex-col md:flex-row items-center justify-between gap-2 text-sm text-gray-500">
                    <p>&copy; {{ new Date().getFullYear() }} Sistur Angola. Todos os direitos reservados.</p>
                    <p>Desenvolvido em Angola 🇦🇴</p>
                </div>
            </div>
        </footer>
    </div>
</template>
