<script setup>
import { Link, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });
defineProps({ reviews: Object, status: String });

function approve(id) {
    router.post(route('admin.reviews.approve', id));
}

function destroy(id) {
    if (confirm('Eliminar esta avaliação?')) {
        router.delete(route('admin.reviews.destroy', id));
    }
}

function formatDate(str) {
    const d = new Date(str);
    return d.toLocaleDateString('pt-PT', { day: '2-digit', month: '2-digit', year: 'numeric', hour: '2-digit', minute: '2-digit' });
}
</script>

<template>
    <Head title="Avaliações" />

    <div class="flex items-center gap-3 mb-6">
        <Link v-for="tab in [{ val: 'pendente', label: 'Pendentes' }, { val: 'aprovado', label: 'Aprovadas' }]"
              :key="tab.val"
              :href="route('admin.reviews.index', { status: tab.val })"
              :class="status === tab.val ? 'bg-orange-500 text-white' : 'bg-white text-gray-600 border border-gray-200 hover:border-orange-300'"
              class="px-3 py-1.5 rounded-full text-sm font-semibold transition">
            {{ tab.label }}
        </Link>
    </div>

    <div class="space-y-4">
        <div v-if="!reviews.data.length" class="text-center py-12 text-gray-400">
            <i class="fas fa-star text-4xl mb-3 opacity-20"></i>
            <p>Nenhuma avaliação {{ status === 'pendente' ? 'pendente' : 'aprovada' }} de momento.</p>
        </div>

        <div v-for="review in reviews.data" :key="review.id"
             class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-start justify-between gap-4">
                <div class="flex items-start gap-3 flex-1">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-sm flex-shrink-0">
                        {{ review.user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <div class="flex items-center gap-2 flex-wrap mb-1">
                            <p class="font-semibold text-gray-900 text-sm">{{ review.user.name }}</p>
                            <span class="text-gray-400 text-xs">→</span>
                            <p class="text-orange-600 text-sm font-medium">{{ review.tour.name }}</p>
                        </div>
                        <div class="flex gap-0.5 mb-2">
                            <i v-for="i in 5" :key="i"
                               class="fas fa-star text-xs"
                               :class="i <= review.rating ? 'text-amber-400' : 'text-gray-200'"></i>
                        </div>
                        <p class="font-semibold text-gray-900 text-sm mb-1">{{ review.title }}</p>
                        <p class="text-gray-600 text-sm">{{ review.body }}</p>
                        <p class="text-gray-400 text-xs mt-2">{{ formatDate(review.created_at) }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <button v-if="!review.is_approved"
                            @click="approve(review.id)"
                            class="bg-green-500 text-white px-3 py-1.5 rounded-xl text-xs font-semibold hover:bg-green-600 transition">
                        <i class="fas fa-check mr-1"></i>Aprovar
                    </button>
                    <button @click="destroy(review.id)"
                            class="bg-red-50 text-red-500 px-3 py-1.5 rounded-xl text-xs font-semibold hover:bg-red-100 transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>

        <div v-if="reviews.links && reviews.last_page > 1" class="mt-4">
            <div class="flex gap-1 flex-wrap">
                <Link v-for="link in reviews.links" :key="link.label"
                      :href="link.url || '#'"
                      :class="[link.active ? 'bg-orange-500 text-white' : 'text-gray-600 hover:bg-gray-100', !link.url ? 'opacity-40 pointer-events-none' : '']"
                      class="px-3 py-1 rounded-lg text-sm font-medium transition"
                      v-html="link.label" preserve-scroll />
            </div>
        </div>
    </div>
</template>
