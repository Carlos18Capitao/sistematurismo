<script setup>
import { ref } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

defineOptions({ layout: AdminLayout });
const props = defineProps({ categories: Array, difficulties: Array, provinces: Array });

const highlights = ref([]);
const includes = ref([]);
const excludes = ref([]);

const form = useForm({
    name: '', short_description: '', description: '',
    city: '', province: '', location: '',
    price: '', duration_days: '', max_participants: '', available_spots: '',
    category: '', difficulty: '',
    available_from: '', available_until: '',
    cover_image: null,
    highlights: [], includes: [], excludes: [],
    is_active: true, is_featured: false,
});

function syncArrays() {
    form.highlights = highlights.value.filter(Boolean);
    form.includes = includes.value.filter(Boolean);
    form.excludes = excludes.value.filter(Boolean);
}

function handleFile(e) { form.cover_image = e.target.files[0] ?? null; }

function submit() {
    syncArrays();
    form.post(route('admin.tours.store'));
}
</script>

<template>
    <Head title="Novo Tour" />

    <div class="mb-4">
        <Link :href="route('admin.tours.index')" class="text-sm text-gray-500 hover:text-gray-700 transition">
            <i class="fas fa-arrow-left mr-1"></i>Voltar aos Tours
        </Link>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <div v-if="Object.keys(form.errors).length" class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 text-sm mb-6">
            <p class="font-semibold mb-2"><i class="fas fa-exclamation-circle mr-2"></i>Erros de validação:</p>
            <ul class="list-disc list-inside space-y-1">
                <li v-for="(error, field) in form.errors" :key="field">{{ error }}</li>
            </ul>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Nome do Tour *</label>
                    <input v-model="form.name" type="text" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição Curta * <span class="text-gray-400 font-normal">(máx 500 caracteres)</span></label>
                    <textarea v-model="form.short_description" rows="2" required
                              class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição Completa *</label>
                    <textarea v-model="form.description" rows="6" required
                              class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500 resize-none"></textarea>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Cidade *</label>
                    <input v-model="form.city" type="text" required placeholder="Ex: Luanda"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Província *</label>
                    <select v-model="form.province" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option value="">Selecionar...</option>
                        <option v-for="p in provinces" :key="p" :value="p">{{ p }}</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Localização / Ponto de encontro</label>
                    <input v-model="form.location" type="text" placeholder="Ex: Museu Nacional de Angola, Luanda"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Preço por pessoa (AOA) *</label>
                    <input v-model="form.price" type="number" min="0" step="0.01" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Duração (dias) *</label>
                    <input v-model="form.duration_days" type="number" min="1" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Máx. Participantes *</label>
                    <input v-model="form.max_participants" type="number" min="1" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Vagas Disponíveis *</label>
                    <input v-model="form.available_spots" type="number" min="0" required
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Categoria *</label>
                    <select v-model="form.category" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option v-for="cat in categories" :key="cat" :value="cat" class="capitalize">{{ cat.charAt(0).toUpperCase() + cat.slice(1) }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Dificuldade *</label>
                    <select v-model="form.difficulty" required class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <option v-for="diff in difficulties" :key="diff" :value="diff" class="capitalize">{{ diff.charAt(0).toUpperCase() + diff.slice(1) }}</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Disponível de</label>
                    <input v-model="form.available_from" type="date"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Disponível até</label>
                    <input v-model="form.available_until" type="date"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Imagem de Capa</label>
                    <input type="file" accept="image/*" @change="handleFile"
                           class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <p class="text-xs text-gray-400 mt-1">JPEG, PNG ou WebP. Máx 2MB.</p>
                </div>

                <!-- Destaques -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Destaques</label>
                    <div class="space-y-2">
                        <div v-for="(_, i) in highlights" :key="i" class="flex gap-2">
                            <input v-model="highlights[i]" type="text"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <button type="button" @click="highlights.splice(i, 1)" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-times"></i></button>
                        </div>
                        <button type="button" @click="highlights.push('')" class="text-orange-500 text-sm hover:text-orange-600 font-semibold">
                            <i class="fas fa-plus mr-1"></i>Adicionar destaque
                        </button>
                    </div>
                </div>

                <!-- Inclui -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">O que está incluído</label>
                    <div class="space-y-2">
                        <div v-for="(_, i) in includes" :key="i" class="flex gap-2">
                            <input v-model="includes[i]" type="text"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <button type="button" @click="includes.splice(i, 1)" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-times"></i></button>
                        </div>
                        <button type="button" @click="includes.push('')" class="text-green-600 text-sm hover:text-green-700 font-semibold">
                            <i class="fas fa-plus mr-1"></i>Adicionar item
                        </button>
                    </div>
                </div>

                <!-- Não inclui -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-1">O que não está incluído</label>
                    <div class="space-y-2">
                        <div v-for="(_, i) in excludes" :key="i" class="flex gap-2">
                            <input v-model="excludes[i]" type="text"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <button type="button" @click="excludes.splice(i, 1)" class="text-red-400 hover:text-red-600 px-2"><i class="fas fa-times"></i></button>
                        </div>
                        <button type="button" @click="excludes.push('')" class="text-red-500 text-sm hover:text-red-600 font-semibold">
                            <i class="fas fa-plus mr-1"></i>Adicionar item
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input v-model="form.is_active" type="checkbox" class="w-4 h-4 text-orange-500 rounded focus:ring-orange-500">
                        <span class="text-sm font-semibold text-gray-700">Tour ativo</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input v-model="form.is_featured" type="checkbox" class="w-4 h-4 text-orange-500 rounded focus:ring-orange-500">
                        <span class="text-sm font-semibold text-gray-700">Em destaque</span>
                    </label>
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" :disabled="form.processing"
                        class="bg-orange-500 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-orange-600 transition text-sm disabled:opacity-60">
                    <i class="fas fa-save mr-2"></i>Criar Tour
                </button>
                <Link :href="route('admin.tours.index')"
                      class="px-6 py-2.5 rounded-xl font-semibold text-sm text-gray-600 border border-gray-200 hover:bg-gray-50 transition">
                    Cancelar
                </Link>
            </div>
        </form>
    </div>
</template>
