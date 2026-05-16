<script setup>
import { ref, computed } from 'vue';
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({ tour: Object });
const page = usePage();
const user = page.props.auth.user;

const rating = ref(0);
const participants = ref(1);
const maxSpots = computed(() => Math.min(props.tour.available_spots, 20));
const total = computed(() => (participants.value * props.tour.price).toLocaleString('pt-AO', { minimumFractionDigits: 2 }) + ' AOA');

const bookingForm = useForm({
    tour_id: props.tour.id,
    tour_date: '',
    participants: 1,
    special_requests: '',
    contact_email: user?.email ?? '',
    contact_phone: user?.phone ?? '',
});

const reviewForm = useForm({
    tour_id: props.tour.id,
    rating: 0,
    title: '',
    body: '',
});

function submitBooking() {
    bookingForm.participants = participants.value;
    bookingForm.post(route('bookings.store'));
}

function submitReview() {
    reviewForm.rating = rating.value;
    reviewForm.post(route('reviews.store'));
}

const statusColors = { pendente: 'bg-yellow-100 text-yellow-700', confirmado: 'bg-green-100 text-green-700', cancelado: 'bg-red-100 text-red-600', concluido: 'bg-blue-100 text-blue-700' };
</script>

<template>
    <Head :title="tour.name" />
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
            <Link :href="route('home')" class="hover:text-orange-500 transition">Início</Link>
            <i class="fas fa-chevron-right text-xs"></i>
            <Link :href="route('tours.index')" class="hover:text-orange-500 transition">Tours</Link>
            <i class="fas fa-chevron-right text-xs"></i>
            <span class="text-gray-700 font-medium">{{ tour.name }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Cover -->
                <div class="rounded-2xl overflow-hidden h-72 md:h-96 bg-gray-200">
                    <img v-if="tour.cover_image" :src="`/storage/${tour.cover_image}`" :alt="tour.name" class="w-full h-full object-cover">
                    <div v-else class="w-full h-full bg-gradient-to-br from-orange-400 to-orange-600 flex items-center justify-center">
                        <i class="fas fa-map-marked-alt text-white text-8xl opacity-30"></i>
                    </div>
                </div>

                <!-- Title -->
                <div>
                    <div class="flex items-center gap-3 mb-3 flex-wrap">
                        <span class="bg-orange-100 text-orange-600 text-sm font-semibold px-3 py-1 rounded-full capitalize">{{ tour.category }}</span>
                        <span class="bg-gray-100 text-gray-600 text-sm px-3 py-1 rounded-full capitalize">{{ tour.difficulty }}</span>
                        <span v-if="tour.is_featured" class="bg-yellow-100 text-yellow-600 text-sm px-3 py-1 rounded-full">
                            <i class="fas fa-star mr-1"></i>Destaque
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-3">{{ tour.name }}</h1>
                    <div class="flex flex-wrap items-center gap-5 text-gray-500 text-sm">
                        <span class="flex items-center gap-1.5"><i class="fas fa-map-marker-alt text-orange-400"></i>{{ tour.city }}, {{ tour.province }}</span>
                        <span class="flex items-center gap-1.5"><i class="fas fa-clock text-orange-400"></i>{{ tour.duration_days }} dia(s)</span>
                        <span class="flex items-center gap-1.5"><i class="fas fa-users text-orange-400"></i>Máx. {{ tour.max_participants }} pessoas</span>
                        <span v-if="tour.reviews_count > 0" class="flex items-center gap-1.5">
                            <i class="fas fa-star text-amber-400"></i>{{ Number(tour.rating_average).toFixed(1) }} ({{ tour.reviews_count }} avaliações)
                        </span>
                    </div>
                </div>

                <!-- Description -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <h2 class="text-xl font-bold mb-4 text-gray-900">Sobre este tour</h2>
                    <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ tour.description }}</p>
                </div>

                <!-- Highlights -->
                <div v-if="tour.highlights?.length" class="bg-white rounded-2xl p-6 border border-gray-100">
                    <h2 class="text-xl font-bold mb-4 text-gray-900">Destaques</h2>
                    <ul class="space-y-2">
                        <li v-for="hl in tour.highlights" :key="hl" class="flex items-start gap-2 text-gray-600">
                            <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>{{ hl }}
                        </li>
                    </ul>
                </div>

                <!-- Includes / Excludes -->
                <div v-if="tour.includes?.length || tour.excludes?.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-if="tour.includes?.length" class="bg-green-50 rounded-2xl p-5 border border-green-100">
                        <h3 class="font-bold text-green-800 mb-3"><i class="fas fa-plus-circle mr-2"></i>Incluído</h3>
                        <ul class="space-y-1.5">
                            <li v-for="item in tour.includes" :key="item" class="text-sm text-green-700 flex items-start gap-2">
                                <i class="fas fa-check mt-0.5 flex-shrink-0"></i>{{ item }}
                            </li>
                        </ul>
                    </div>
                    <div v-if="tour.excludes?.length" class="bg-red-50 rounded-2xl p-5 border border-red-100">
                        <h3 class="font-bold text-red-800 mb-3"><i class="fas fa-minus-circle mr-2"></i>Não incluído</h3>
                        <ul class="space-y-1.5">
                            <li v-for="item in tour.excludes" :key="item" class="text-sm text-red-700 flex items-start gap-2">
                                <i class="fas fa-times mt-0.5 flex-shrink-0"></i>{{ item }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Reviews -->
                <div class="bg-white rounded-2xl p-6 border border-gray-100">
                    <h2 class="text-xl font-bold mb-6 text-gray-900">
                        Avaliações <span v-if="tour.reviews_count > 0" class="text-gray-400 font-normal text-base">({{ tour.reviews_count }})</span>
                    </h2>

                    <div v-if="tour.reviews?.length">
                        <div v-for="review in tour.reviews" :key="review.id" class="border-b border-gray-100 pb-5 mb-5 last:border-0 last:mb-0 last:pb-0">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-orange-100 rounded-full flex items-center justify-center text-orange-600 font-bold text-sm">
                                        {{ review.user.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 text-sm">{{ review.user.name }}</p>
                                        <p class="text-gray-400 text-xs">{{ new Date(review.created_at).toLocaleDateString('pt-PT') }}</p>
                                    </div>
                                </div>
                                <div class="flex gap-0.5">
                                    <i v-for="i in 5" :key="i" class="fas fa-star text-xs" :class="i <= review.rating ? 'text-amber-400' : 'text-gray-200'"></i>
                                </div>
                            </div>
                            <p class="font-semibold text-gray-800 text-sm mb-1">{{ review.title }}</p>
                            <p class="text-gray-600 text-sm">{{ review.body }}</p>
                        </div>
                    </div>
                    <p v-else class="text-gray-400 text-sm">Ainda não há avaliações para este tour.</p>

                    <!-- Review Form -->
                    <div v-if="user" class="mt-6 pt-6 border-t border-gray-100">
                        <h3 class="font-bold text-gray-900 mb-4">Deixar uma avaliação</h3>
                        <div v-if="reviewForm.errors && Object.keys(reviewForm.errors).length" class="bg-red-50 text-red-600 rounded-xl p-3 mb-4 text-sm">
                            <p v-for="(error, key) in reviewForm.errors" :key="key">{{ error }}</p>
                        </div>
                        <form @submit.prevent="submitReview" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Classificação</label>
                                <div class="flex gap-2">
                                    <button v-for="i in 5" :key="i" type="button" @click="rating = i; reviewForm.rating = i">
                                        <i class="fas fa-star text-2xl transition" :class="rating >= i ? 'text-amber-400' : 'text-gray-200'"></i>
                                    </button>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Título</label>
                                <input v-model="reviewForm.title" type="text" placeholder="Resumo da sua experiência..."
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Comentário</label>
                                <textarea v-model="reviewForm.body" rows="4" placeholder="Conte a sua experiência..."
                                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none"></textarea>
                            </div>
                            <button type="submit" :disabled="reviewForm.processing"
                                    class="bg-orange-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-orange-600 transition text-sm disabled:opacity-60">
                                Enviar Avaliação
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Booking Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sticky top-24">
                    <div class="mb-5">
                        <span class="text-sm text-gray-500">Preço por pessoa</span>
                        <p class="text-3xl font-bold text-gray-900 mt-1">
                            {{ Number(tour.price).toLocaleString('pt-AO', { minimumFractionDigits: 2 }) }}
                            <span class="text-base text-gray-500 font-normal">AOA</span>
                        </p>
                    </div>

                    <div class="space-y-2 mb-5 text-sm">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Duração</span><span class="font-semibold">{{ tour.duration_days }} dia(s)</span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Vagas disponíveis</span>
                            <span class="font-semibold" :class="tour.available_spots > 0 ? 'text-green-600' : 'text-red-500'">
                                {{ tour.available_spots > 0 ? tour.available_spots + ' vagas' : 'Esgotado' }}
                            </span>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-500">Dificuldade</span><span class="font-semibold capitalize">{{ tour.difficulty }}</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-500">Localização</span><span class="font-semibold">{{ tour.city }}</span>
                        </div>
                    </div>

                    <template v-if="tour.available_spots > 0">
                        <form v-if="user" @submit.prevent="submitBooking" class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Data do tour</label>
                                <input v-model="bookingForm.tour_date" type="date" :min="new Date().toISOString().split('T')[0]"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Participantes</label>
                                <input v-model="participants" type="number" min="1" :max="maxSpots"
                                       class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Pedidos especiais</label>
                                <textarea v-model="bookingForm.special_requests" rows="2" placeholder="Ex: dieta vegetariana..."
                                          class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none"></textarea>
                            </div>
                            <div class="bg-orange-50 rounded-xl p-3">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Total</span>
                                    <span class="font-bold text-orange-600">{{ total }}</span>
                                </div>
                            </div>
                            <button type="submit" :disabled="bookingForm.processing"
                                    class="w-full bg-orange-500 text-white py-3 rounded-xl font-bold hover:bg-orange-600 transition disabled:opacity-60">
                                <i class="fas fa-calendar-check mr-2"></i>Reservar Agora
                            </button>
                        </form>
                        <template v-else>
                            <Link :href="route('login')" class="block w-full bg-orange-500 text-white py-3 rounded-xl font-bold hover:bg-orange-600 transition text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>Entrar para Reservar
                            </Link>
                            <p class="mt-3 text-center text-sm text-gray-500">
                                Não tem conta? <Link :href="route('register')" class="text-orange-500 hover:underline font-semibold">Registar</Link>
                            </p>
                        </template>
                    </template>
                    <div v-else class="bg-gray-100 rounded-xl p-4 text-center text-gray-500 text-sm">
                        <i class="fas fa-ban text-2xl mb-2 block opacity-40"></i>
                        Tour esgotado.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
