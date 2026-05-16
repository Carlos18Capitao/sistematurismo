<script setup>
import { useForm, router } from '@inertiajs/vue3';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineOptions({ layout: GuestLayout });
defineProps({ status: String });

const form = useForm({});
function resend() { form.post(route('verification.send')); }
function logout() { router.post(route('logout')); }
</script>

<template>
    <Head title="Verificar email" />
    <div class="text-center mb-6">
        <div class="w-14 h-14 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-envelope-open-text text-orange-500 text-xl"></i>
        </div>
        <h2 class="text-2xl font-bold text-gray-900">Verificar email</h2>
        <p class="text-sm text-gray-500 mt-1">Obrigado por se registar! Verifique o seu email clicando no link que acabámos de enviar.</p>
    </div>
    <div v-if="status === 'verification-link-sent'" class="mb-4 text-sm font-medium text-green-600 bg-green-50 p-3 rounded-xl text-center">
        <i class="fas fa-check-circle mr-1"></i>Um novo link de verificação foi enviado para o seu email.
    </div>
    <div class="flex flex-col gap-3">
        <button @click="resend" :disabled="form.processing"
                class="w-full bg-orange-500 text-white py-2.5 rounded-xl font-semibold hover:bg-orange-600 transition disabled:opacity-60">
            <i class="fas fa-redo mr-2"></i>Reenviar email de verificação
        </button>
        <button @click="logout" class="w-full text-center text-sm text-gray-500 hover:text-orange-500 font-medium transition">
            <i class="fas fa-sign-out-alt mr-1"></i>Sair
        </button>
    </div>
</template>
