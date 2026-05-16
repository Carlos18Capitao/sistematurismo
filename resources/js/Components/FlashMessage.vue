<script setup>
import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const showSuccess = ref(false);
const showError = ref(false);

watch(() => page.props.flash?.success, val => {
    if (val) { showSuccess.value = true; setTimeout(() => showSuccess.value = false, 5000); }
}, { immediate: true });

watch(() => page.props.flash?.error, val => {
    if (val) { showError.value = true; setTimeout(() => showError.value = false, 7000); }
}, { immediate: true });
</script>

<template>
    <Transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showSuccess && page.props.flash?.success"
             class="fixed top-20 right-4 z-50 bg-green-500 text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 max-w-sm">
            <i class="fas fa-check-circle"></i>
            <span>{{ page.props.flash.success }}</span>
            <button @click="showSuccess = false" class="ml-2 opacity-70 hover:opacity-100"><i class="fas fa-times"></i></button>
        </div>
    </Transition>
    <Transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 translate-y-2" enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="showError && page.props.flash?.error"
             class="fixed top-20 right-4 z-50 bg-red-500 text-white px-5 py-3 rounded-xl shadow-lg flex items-center gap-2 max-w-sm">
            <i class="fas fa-exclamation-circle"></i>
            <span>{{ page.props.flash.error }}</span>
            <button @click="showError = false" class="ml-2 opacity-70 hover:opacity-100"><i class="fas fa-times"></i></button>
        </div>
    </Transition>
</template>
