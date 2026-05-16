<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });
defineProps({ bookings: Object, filters: Object });

const statusTabs = [
    { val: '', label: 'Todas' },
    { val: 'pendente', label: 'Pendentes' },
    { val: 'confirmado', label: 'Confirmadas' },
    { val: 'cancelado', label: 'Canceladas' },
    { val: 'concluido', label: 'Concluídas' },
];

const statusColors = {
    pendente: 'bg-yellow-100 text-yellow-700',
    confirmado: 'bg-green-100 text-green-700',
    cancelado: 'bg-red-100 text-red-600',
    concluido: 'bg-blue-100 text-blue-700',
};

function setFilter(val) {
    router.get(route('admin.bookings.index'), { status: val }, { preserveState: true });
}

function truncate(str, n) {
    return str && str.length > n ? str.slice(0, n) + '…' : str;
}
</script>

<template>
    <Head title="Reservas" />

    <div class="flex items-center gap-3 mb-6 flex-wrap">
        <button v-for="tab in statusTabs" :key="tab.val"
                @click="setFilter(tab.val)"
                :class="(filters?.status ?? '') === tab.val ? 'bg-orange-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-orange-300'"
                class="px-3 py-1.5 rounded-full text-sm font-semibold transition">
            {{ tab.label }}
        </button>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-gray-500 font-semibold">Referência</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-semibold hidden md:table-cell">Tour</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-semibold hidden md:table-cell">Utilizador</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-semibold hidden lg:table-cell">Data</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-semibold hidden lg:table-cell">Total</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-semibold">Estado</th>
                        <th class="text-right px-5 py-3 text-gray-500 font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr v-if="!bookings.data.length">
                        <td colspan="7" class="px-5 py-10 text-center text-gray-400">Nenhuma reserva encontrada.</td>
                    </tr>
                    <tr v-for="booking in bookings.data" :key="booking.id" class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3 font-mono text-xs font-bold text-gray-700">{{ booking.reference }}</td>
                        <td class="px-5 py-3 text-gray-700 hidden md:table-cell">{{ truncate(booking.tour.name, 25) }}</td>
                        <td class="px-5 py-3 text-gray-600 hidden md:table-cell">{{ booking.user.name }}</td>
                        <td class="px-5 py-3 text-gray-600 hidden lg:table-cell">{{ booking.tour_date_formatted }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-900 hidden lg:table-cell">
                            {{ Number(booking.total_price).toLocaleString('pt-AO', { minimumFractionDigits: 2 }) }} AOA
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold capitalize"
                                  :class="statusColors[booking.status]">
                                {{ booking.status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <Link :href="route('admin.bookings.show', booking.id)"
                                  class="text-orange-500 hover:text-orange-600 text-sm font-semibold transition">
                                Ver
                            </Link>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="bookings.links && bookings.last_page > 1" class="px-5 py-4 border-t border-gray-100">
            <div class="flex gap-1 flex-wrap">
                <Link v-for="link in bookings.links" :key="link.label"
                      :href="link.url || '#'"
                      :class="[link.active ? 'bg-orange-500 text-white' : 'text-gray-600 hover:bg-gray-100', !link.url ? 'opacity-40 pointer-events-none' : '']"
                      class="px-3 py-1 rounded-lg text-sm font-medium transition"
                      v-html="link.label" preserve-scroll />
            </div>
        </div>
    </div>
</template>
