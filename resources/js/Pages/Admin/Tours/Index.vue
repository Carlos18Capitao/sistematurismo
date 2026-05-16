<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });
defineProps({ tours: Object });

function deleteTour(tour) {
    if (confirm('Eliminar este tour?')) {
        router.delete(route('admin.tours.destroy', tour.id));
    }
}
</script>

<template>
    <Head title="Tours" />

    <div class="flex items-center justify-between mb-6">
        <p class="text-gray-500 text-sm">{{ tours.total }} tour(s)</p>
        <Link :href="route('admin.tours.create')"
              class="bg-orange-500 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-orange-600 transition flex items-center gap-2">
            <i class="fas fa-plus"></i>Novo Tour
        </Link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="text-left px-5 py-3 text-gray-500 font-semibold">Tour</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-semibold hidden md:table-cell">Cidade</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-semibold hidden md:table-cell">Preço</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-semibold hidden lg:table-cell">Vagas</th>
                        <th class="text-left px-5 py-3 text-gray-500 font-semibold">Estado</th>
                        <th class="text-right px-5 py-3 text-gray-500 font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    <tr v-if="!tours.data.length">
                        <td colspan="6" class="px-5 py-10 text-center text-gray-400">Nenhum tour encontrado.</td>
                    </tr>
                    <tr v-for="tour in tours.data" :key="tour.id" class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 overflow-hidden flex-shrink-0">
                                    <img v-if="tour.cover_image" :src="`/storage/${tour.cover_image}`" alt="" class="w-full h-full object-cover">
                                    <div v-else class="w-full h-full bg-orange-100 flex items-center justify-center">
                                        <i class="fas fa-map text-orange-400 text-xs"></i>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ tour.name }}</p>
                                    <p class="text-xs text-gray-400 capitalize">{{ tour.category }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3 text-gray-600 hidden md:table-cell">{{ tour.city }}</td>
                        <td class="px-5 py-3 font-semibold text-gray-900 hidden md:table-cell">
                            {{ Number(tour.price).toLocaleString('pt-AO', { minimumFractionDigits: 2 }) }} AOA
                        </td>
                        <td class="px-5 py-3 hidden lg:table-cell">
                            <span :class="tour.available_spots > 0 ? 'text-green-600' : 'text-red-500'" class="font-semibold">
                                {{ tour.available_spots }}
                            </span>
                        </td>
                        <td class="px-5 py-3">
                            <span class="text-xs px-2.5 py-1 rounded-full font-semibold"
                                  :class="tour.is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'">
                                {{ tour.is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a :href="route('tours.show', tour.slug)" target="_blank"
                                   class="text-gray-400 hover:text-gray-600 transition" title="Ver público">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <Link :href="route('admin.tours.edit', tour.id)"
                                      class="text-orange-500 hover:text-orange-600 transition" title="Editar">
                                    <i class="fas fa-edit"></i>
                                </Link>
                                <button @click="deleteTour(tour)"
                                        class="text-red-400 hover:text-red-600 transition" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="tours.links && tours.last_page > 1" class="px-5 py-4 border-t border-gray-100">
            <div class="flex gap-1 flex-wrap">
                <Link v-for="link in tours.links" :key="link.label"
                      :href="link.url || '#'"
                      :class="[link.active ? 'bg-orange-500 text-white' : 'text-gray-600 hover:bg-gray-100', !link.url ? 'opacity-40 pointer-events-none' : '']"
                      class="px-3 py-1 rounded-lg text-sm font-medium transition"
                      v-html="link.label" preserve-scroll />
            </div>
        </div>
    </div>
</template>
