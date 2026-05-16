<script setup>
import { useForm } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineOptions({ layout: GuestLayout });

const form = useForm({ password: '' });
function submit() { form.post(route('password.confirm'), { onFinish: () => form.reset() }); }
</script>

<template>
    <Head title="Confirmar palavra-passe" />
    <div class="text-center mb-6">
        <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-shield-alt text-orange-500 text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Confirmar palavra-passe</h2>
        <p class="text-sm text-gray-500 mt-1">Esta é uma área segura. Por favor confirme a sua palavra-passe antes de continuar.</p>
    </div>
    <form @submit.prevent="submit" class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Palavra-passe</label>
            <input v-model="form.password" type="password" required autocomplete="current-password" placeholder="••••••••"
                   class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
            <p v-if="form.errors.password" class="mt-1 text-xs text-red-500">{{ form.errors.password }}</p>
        </div>
        <button type="submit" :disabled="form.processing"
                class="w-full bg-orange-500 text-white py-2.5 rounded-xl font-semibold hover:bg-orange-600 transition disabled:opacity-60">
            <i class="fas fa-check-circle mr-2"></i>Confirmar
        </button>
    </form>
</template>
