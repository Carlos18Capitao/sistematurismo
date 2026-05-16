<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineOptions({ layout: GuestLayout });
defineProps({ status: String });

const form = useForm({ email: '' });
function submit() { form.post(route('password.email')); }
</script>

<template>
    <Head title="Recuperar palavra-passe" />
    <div class="text-center mb-6">
        <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-lock text-orange-500 text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Esqueceu a palavra-passe?</h2>
        <p class="text-sm text-gray-500 mt-1">Indique o seu email e enviaremos um link para redefinir a sua palavra-passe.</p>
    </div>
    <div v-if="status" class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-xl text-center">{{ status }}</div>
    <form @submit.prevent="submit" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="form.email" type="email" required autofocus placeholder="seu@email.com"
                   class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">{{ form.errors.email }}</p>
        </div>
        <button type="submit" :disabled="form.processing"
                class="w-full bg-orange-500 text-white py-2.5 rounded-xl font-semibold hover:bg-orange-600 transition disabled:opacity-60">
            <i class="fas fa-paper-plane mr-2"></i>Enviar link de recuperação
        </button>
        <p class="text-center text-sm">
            <Link :href="route('login')" class="text-orange-500 hover:text-orange-600 font-semibold">
                <i class="fas fa-arrow-left mr-1"></i>Voltar ao login
            </Link>
        </p>
    </form>
</template>
