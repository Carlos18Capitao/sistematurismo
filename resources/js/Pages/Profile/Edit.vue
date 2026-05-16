<script setup>
import { useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineOptions({ layout: AppLayout });
const props = defineProps({ user: Object });

const profileForm = useForm({ name: props.user.name, email: props.user.email });
const passwordForm = useForm({ current_password: '', password: '', password_confirmation: '' });

function updateProfile() {
    profileForm.patch(route('profile.update'));
}

function updatePassword() {
    passwordForm.put(route('password.update'), { onFinish: () => passwordForm.reset() });
}

function deleteAccount() {
    if (confirm('Tem a certeza que quer eliminar a sua conta? Esta acção é irreversível.')) {
        router.delete(route('profile.destroy'));
    }
}
</script>

<template>
    <Head title="Perfil" />
    <div class="py-12">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Profile Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Informações do Perfil</h2>
                <form @submit.prevent="updateProfile" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                        <input v-model="profileForm.name" type="text" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p v-if="profileForm.errors.name" class="mt-1 text-xs text-red-500">{{ profileForm.errors.name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input v-model="profileForm.email" type="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p v-if="profileForm.errors.email" class="mt-1 text-xs text-red-500">{{ profileForm.errors.email }}</p>
                    </div>
                    <button type="submit" :disabled="profileForm.processing"
                            class="bg-orange-500 text-white px-5 py-2 rounded-xl font-semibold hover:bg-orange-600 transition text-sm disabled:opacity-60">
                        Guardar
                    </button>
                </form>
            </div>

            <!-- Password -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Alterar Palavra-passe</h2>
                <form @submit.prevent="updatePassword" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Palavra-passe actual</label>
                        <input v-model="passwordForm.current_password" type="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p v-if="passwordForm.errors.current_password" class="mt-1 text-xs text-red-500">{{ passwordForm.errors.current_password }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nova palavra-passe</label>
                        <input v-model="passwordForm.password" type="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                        <p v-if="passwordForm.errors.password" class="mt-1 text-xs text-red-500">{{ passwordForm.errors.password }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirmar palavra-passe</label>
                        <input v-model="passwordForm.password_confirmation" type="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-orange-500">
                    </div>
                    <button type="submit" :disabled="passwordForm.processing"
                            class="bg-orange-500 text-white px-5 py-2 rounded-xl font-semibold hover:bg-orange-600 transition text-sm disabled:opacity-60">
                        Actualizar
                    </button>
                </form>
            </div>

            <!-- Delete Account -->
            <div class="bg-white rounded-2xl shadow-sm border border-red-100 p-6">
                <h2 class="text-lg font-bold text-red-700 mb-2">Eliminar Conta</h2>
                <p class="text-sm text-gray-500 mb-4">Ao eliminar a sua conta, todos os dados serão permanentemente removidos.</p>
                <button @click="deleteAccount" class="bg-red-600 text-white px-5 py-2 rounded-xl font-semibold hover:bg-red-700 transition text-sm">
                    Eliminar Conta
                </button>
            </div>
        </div>
    </div>
</template>
