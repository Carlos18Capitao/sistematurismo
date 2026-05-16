<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });
defineProps({ bookings: Array });

const statusColors = {
    pendente: 'bg-yellow-100 text-yellow-700', confirmado: 'bg-green-100 text-green-700',
    cancelado: 'bg-red-100 text-red-600', concluido: 'bg-blue-100 text-blue-700',
};

function cancel(reference) {
    if (confirm('Confirma o cancelamento?')) {
        router.post(route('bookings.cancel', reference));
    }
}
</script>

<template>
    <Head title="Minhas Reservas" />
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Minhas Reservas</h1>

        <div v-if="!bookings.length" class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
            <i class="fas fa-calendar-times text-5xl text-gray-200 mb-4"></i>
            <h3 class="text-xl font-bold text-gray-700 mb-2">Sem reservas</h3>
            <p class="text-gray-400 mb-6">Ainda não fez nenhuma reserva.</p>
            <Link :href="route('tours.index')" class="bg-orange-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-orange-600 transition">
                Explorar Tours
            </Link>
        </div>

        <div v-else class="space-y-4">
            <div v-for="booking in bookings" :key="booking.id"
                 class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 flex flex-col md:flex-row gap-5">
                <div class="md:w-28 h-24 rounded-xl bg-gray-100 overflow-hidden flex-shrink-0">
                    <img v-if="booking.tour.cover_image" :src="`/storage/${booking.tour.cover_image}`" :alt="booking.tour.name" class="w-full h-full object-cover">
                    <div v-else class="w-full h-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-white text-2xl opacity-40"></i>
                    </div>
                </div>

                <div class="flex-1">
                    <div class="flex items-start justify-between flex-wrap gap-2 mb-2">
                        <div>
                            <p class="font-mono text-xs text-gray-400 mb-1">{{ booking.reference }}</p>
                            <h3 class="font-bold text-gray-900">{{ booking.tour.name }}</h3>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full capitalize" :class="statusColors[booking.status]">
                            {{ booking.status }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-4 text-sm text-gray-500 mb-3">
                        <span><i class="fas fa-calendar mr-1 text-orange-400"></i>{{ new Date(booking.tour_date).toLocaleDateString('pt-PT') }}</span>
                        <span><i class="fas fa-users mr-1 text-orange-400"></i>{{ booking.participants }} pessoa(s)</span>
                        <span><i class="fas fa-money-bill mr-1 text-orange-400"></i>{{ Number(booking.total_price).toLocaleString('pt-AO', { minimumFractionDigits: 2 }) }} AOA</span>
                    </div>

                    <div class="flex gap-3">
                        <Link :href="route('bookings.show', booking.reference)" class="text-sm text-orange-500 hover:text-orange-600 font-semibold transition">Ver detalhes</Link>
                        <button v-if="booking.status === 'pendente'" @click="cancel(booking.reference)" class="text-sm text-red-500 hover:text-red-600 font-semibold transition">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
