<script setup>
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineOptions({ layout: GuestLayout });
const props = defineProps({ token: String, email: String });

const form = useForm({ token: props.token, email: props.email, password: '', password_confirmation: '' });
function submit() { form.post(route('password.store'), { onFinish: () => form.reset('password', 'password_confirmation') }); }
</script>

<template>
    <Head title="Redefinir palavra-passe" />
    <div class="text-center mb-6">
        <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-key text-orange-500 text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Redefinir palavra-passe</h2>
        <p class="text-sm text-gray-500 mt-1">Defina a sua nova palavra-passe abaixo.</p>
    </div>
    <form @submit.prevent="submit" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="form.email" type="email" required
                   class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            <p v-if="form.errors.email" class="mt-1 text-xs text-red-500">{{ form.errors.email }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nova palavra-passe</label>
            <input v-model="form.password" type="password" required placeholder="Mínimo 8 caracteres"
                   class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            <p v-if="form.errors.password" class="mt-1 text-xs text-red-500">{{ form.errors.password }}</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar palavra-passe</label>
            <input v-model="form.password_confirmation" type="password" required placeholder="Repita a palavra-passe"
                   class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
        </div>
        <button type="submit" :disabled="form.processing"
                class="w-full bg-orange-500 text-white py-2.5 rounded-xl font-semibold hover:bg-orange-600 transition disabled:opacity-60">
            <i class="fas fa-check mr-2"></i>Redefinir palavra-passe
        </button>
    </form>
</template>
