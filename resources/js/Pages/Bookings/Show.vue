<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });
defineProps({ booking: Object });

const statusColors = { pendente: 'bg-yellow-100 text-yellow-700', confirmado: 'bg-green-100 text-green-700', cancelado: 'bg-red-100 text-red-600', concluido: 'bg-blue-100 text-blue-700' };
const paymentColors = { pendente: 'bg-gray-100 text-gray-600', pago: 'bg-green-100 text-green-700', reembolsado: 'bg-blue-100 text-blue-700' };

function cancel(reference) {
    if (confirm('Confirma o cancelamento desta reserva?')) {
        router.post(route('bookings.cancel', reference));
    }
}
</script>

<template>
    <Head :title="`Reserva ${booking.reference}`" />
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
            <Link :href="route('bookings.index')" class="hover:text-orange-500 transition">Minhas Reservas</Link>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="font-medium text-gray-700">{{ booking.reference }}</span>
        </nav>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-5 flex justify-between items-center">
                <div>
                    <p class="text-orange-100 text-sm">Referência</p>
                    <p class="text-white font-mono font-bold text-xl">{{ booking.reference }}</p>
                </div>
                <span class="text-xs font-semibold bg-white/20 text-white px-3 py-1 rounded-full capitalize">{{ booking.status }}</span>
            </div>

            <div class="p-6 space-y-6">
                <div class="flex gap-4">
                    <div class="w-24 h-20 rounded-xl overflow-hidden bg-gray-100 flex-shrink-0">
                        <img v-if="booking.tour.cover_image" :src="`/storage/${booking.tour.cover_image}`" :alt="booking.tour.name" class="w-full h-full object-cover">
                        <div v-else class="w-full h-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                            <i class="fas fa-map-marked-alt text-white text-2xl opacity-40"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="font-bold text-gray-900 text-lg">{{ booking.tour.name }}</h2>
                        <p class="text-gray-500 text-sm mt-1"><i class="fas fa-map-marker-alt text-orange-400 mr-1"></i>{{ booking.tour.city }}, {{ booking.tour.province }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <i class="fas fa-calendar text-orange-400 mb-1"></i>
                        <p class="text-xs text-gray-500">Data do Tour</p>
                        <p class="font-semibold text-gray-900 text-sm">{{ new Date(booking.tour_date).toLocaleDateString('pt-PT') }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <i class="fas fa-users text-orange-400 mb-1"></i>
                        <p class="text-xs text-gray-500">Participantes</p>
                        <p class="font-semibold text-gray-900 text-sm">{{ booking.participants }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <i class="fas fa-money-bill text-orange-400 mb-1"></i>
                        <p class="text-xs text-gray-500">Total Pago</p>
                        <p class="font-semibold text-gray-900 text-sm">{{ Number(booking.total_price).toLocaleString('pt-AO', { minimumFractionDigits: 2 }) }} AOA</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-3 text-center">
                        <span class="block text-xs px-2 py-0.5 rounded-full capitalize mb-1" :class="paymentColors[booking.payment_status]">{{ booking.payment_status }}</span>
                        <p class="text-xs text-gray-500">Pagamento</p>
                    </div>
                </div>

                <div class="border-t border-gray-100 pt-4">
                    <h3 class="font-semibold text-gray-900 mb-3 text-sm">Informações de Contacto</h3>
                    <div class="space-y-2 text-sm text-gray-600">
                        <p><i class="fas fa-envelope text-gray-400 w-5"></i>{{ booking.contact_email }}</p>
                        <p v-if="booking.contact_phone"><i class="fas fa-phone text-gray-400 w-5"></i>{{ booking.contact_phone }}</p>
                    </div>
                </div>

                <div v-if="booking.special_requests" class="border-t border-gray-100 pt-4">
                    <h3 class="font-semibold text-gray-900 mb-2 text-sm">Pedidos Especiais</h3>
                    <p class="text-sm text-gray-600">{{ booking.special_requests }}</p>
                </div>

                <div v-if="booking.status === 'cancelado' && booking.cancellation_reason" class="bg-red-50 rounded-xl p-4 text-sm text-red-700">
                    <p class="font-semibold mb-1"><i class="fas fa-ban mr-2"></i>Motivo de cancelamento</p>
                    <p>{{ booking.cancellation_reason }}</p>
                </div>

                <div class="border-t border-gray-100 pt-4 flex gap-3 flex-wrap">
                    <Link :href="route('tours.show', booking.tour.slug)" class="text-sm text-orange-500 hover:text-orange-600 font-semibold transition">
                        <i class="fas fa-arrow-left mr-1"></i>Ver Tour
                    </Link>
                    <button v-if="booking.status === 'pendente'" @click="cancel(booking.reference)" class="text-sm text-red-500 hover:text-red-600 font-semibold transition">
                        <i class="fas fa-times-circle mr-1"></i>Cancelar Reserva
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
