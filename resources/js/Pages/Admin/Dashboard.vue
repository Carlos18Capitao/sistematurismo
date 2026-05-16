<script setup>
import { Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });
defineProps({ stats: Object, recentBookings: Array });

const cards = [
    { icon: 'fa-map-marked-alt', color: 'bg-orange-100 text-orange-600', label: 'Tours', key: 'total_tours' },
    { icon: 'fa-calendar-check', color: 'bg-blue-100 text-blue-600', label: 'Reservas', key: 'total_bookings' },
    { icon: 'fa-users', color: 'bg-green-100 text-green-600', label: 'Utilizadores', key: 'total_users' },
    { icon: 'fa-star', color: 'bg-yellow-100 text-yellow-600', label: 'Avaliações pendentes', key: 'pending_reviews' },
    { icon: 'fa-money-bill-wave', color: 'bg-purple-100 text-purple-600', label: 'Receita (AOA)', key: 'revenue' },
];

const statusColors = { pendente: 'bg-yellow-100 text-yellow-700', confirmado: 'bg-green-100 text-green-700', cancelado: 'bg-red-100 text-red-600', concluido: 'bg-blue-100 text-blue-700' };
</script>

<template>
    <Head title="Admin — Dashboard" />

    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div v-for="card in cards" :key="card.key" class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center mb-3" :class="card.color">
                <i :class="`fas ${card.icon}`"></i>
            </div>
            <p class="text-2xl font-bold text-gray-900">
                {{ card.key === 'revenue' ? Number(stats[card.key]).toLocaleString('pt-AO', { minimumFractionDigits: 2 }) : stats[card.key] }}
            </p>
            <p class="text-sm text-gray-500 mt-0.5">{{ card.label }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Bookings -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-900">Reservas Recentes</h2>
                <Link :href="route('admin.bookings.index')" class="text-sm text-orange-500 hover:text-orange-600 font-semibold">Ver todas</Link>
            </div>
            <div class="space-y-3">
                <div v-if="!recentBookings.length" class="text-gray-400 text-sm">Nenhuma reserva ainda.</div>
                <div v-for="booking in recentBookings" :key="booking.id"
                     class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="font-semibold text-sm text-gray-900">{{ booking.tour.name }}</p>
                        <p class="text-xs text-gray-400">{{ booking.reference }} · {{ booking.user.name }}</p>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold capitalize" :class="statusColors[booking.status]">{{ booking.status }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-900 mb-4">Acções Rápidas</h2>
            <div class="grid grid-cols-2 gap-3">
                <Link :href="route('admin.tours.create')" class="flex items-center gap-3 p-4 bg-orange-50 rounded-xl hover:bg-orange-100 transition">
                    <i class="fas fa-plus-circle text-orange-500 text-xl"></i>
                    <span class="text-sm font-semibold text-gray-900">Novo Tour</span>
                </Link>
                <Link :href="route('admin.bookings.index', { status: 'pendente' })" class="flex items-center gap-3 p-4 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition">
                    <i class="fas fa-clock text-yellow-500 text-xl"></i>
                    <span class="text-sm font-semibold text-gray-900">Pendentes</span>
                </Link>
                <Link :href="route('admin.reviews.index')" class="flex items-center gap-3 p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition">
                    <i class="fas fa-star text-blue-500 text-xl"></i>
                    <span class="text-sm font-semibold text-gray-900">Avaliações</span>
                </Link>
                <Link :href="route('tours.index')" class="flex items-center gap-3 p-4 bg-green-50 rounded-xl hover:bg-green-100 transition">
                    <i class="fas fa-external-link-alt text-green-500 text-xl"></i>
                    <span class="text-sm font-semibold text-gray-900">Ver Site</span>
                </Link>
            </div>
        </div>
    </div>
</template>
