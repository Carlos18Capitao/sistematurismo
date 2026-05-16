<script setup>
import { ref, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import TourCard from '@/Components/TourCard.vue';
import Pagination from '@/Components/Pagination.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
    tours: Object,
    cities: Array,
    categories: Array,
    filters: Object,
});

const form = ref({
    search: props.filters?.search ?? '',
    city: props.filters?.city ?? '',
    category: props.filters?.category ?? '',
    min_price: props.filters?.min_price ?? '',
    max_price: props.filters?.max_price ?? '',
    sort: props.filters?.sort ?? 'created_at',
});

const hasFilters = computed(() => Object.values(form.value).some(v => v !== '' && v !== 'created_at'));

function applyFilters() {
    const params = Object.fromEntries(Object.entries(form.value).filter(([, v]) => v !== ''));
    router.get(route('tours.index'), params, { preserveState: true, replace: true });
}

function clearFilters() {
    router.get(route('tours.index'));
}
</script>

<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Tours em Angola</h1>
                <p class="text-gray-500 mt-1">{{ tours.total }} tour(s) encontrado(s)</p>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Filters -->
            <aside class="lg:w-64 flex-shrink-0">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 space-y-5 sticky top-24">
                    <h3 class="font-bold text-gray-900 text-lg">Filtros</h3>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pesquisa</label>
                        <input v-model="form.search" type="text" placeholder="Nome ou descrição..."
                               class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"
                               @keyup.enter="applyFilters">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cidade</label>
                        <select v-model="form.city" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="">Todas</option>
                            <option v-for="city in cities" :key="city" :value="city">{{ city }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Categoria</label>
                        <select v-model="form.category" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="">Todas</option>
                            <option v-for="cat in categories" :key="cat" :value="cat" class="capitalize">{{ cat }}</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preço (AOA)</label>
                        <div class="flex gap-2">
                            <input v-model="form.min_price" type="number" placeholder="Mín"
                                   class="w-1/2 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <input v-model="form.max_price" type="number" placeholder="Máx"
                                   class="w-1/2 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ordenar por</label>
                        <select v-model="form.sort" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="created_at">Mais recentes</option>
                            <option value="price">Preço</option>
                            <option value="rating_average">Avaliação</option>
                        </select>
                    </div>

                    <button @click="applyFilters" class="w-full bg-orange-500 text-white py-2.5 rounded-xl font-semibold hover:bg-orange-600 transition text-sm">
                        Aplicar Filtros
                    </button>

                    <button v-if="hasFilters" @click="clearFilters" class="block w-full text-center text-sm text-gray-500 hover:text-gray-700 transition">
                        <i class="fas fa-times mr-1"></i>Limpar filtros
                    </button>
                </div>
            </aside>

            <!-- Tours Grid -->
            <div class="flex-1">
                <div v-if="tours.data.length === 0" class="bg-white rounded-2xl border border-gray-100 p-16 text-center">
                    <i class="fas fa-search text-5xl text-gray-200 mb-4"></i>
                    <h3 class="text-xl font-bold text-gray-700 mb-2">Sem resultados</h3>
                    <p class="text-gray-400">Nenhum tour encontrado com os filtros selecionados.</p>
                    <button @click="clearFilters" class="mt-4 inline-block text-orange-500 hover:text-orange-600 font-semibold">Ver todos</button>
                </div>
                <template v-else>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <TourCard v-for="tour in tours.data" :key="tour.id" :tour="tour" />
                    </div>
                    <Pagination :links="tours.links" />
                </template>
            </div>
        </div>
    </div>
</template>
