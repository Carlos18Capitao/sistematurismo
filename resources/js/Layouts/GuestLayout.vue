<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 bg-gradient-to-br from-orange-50 via-white to-orange-50 font-sans text-gray-900 antialiased">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

        <div class="mb-6">
            <Link href="/" class="flex flex-col items-center gap-3">
                <img :src="'/images/Sistur-logo.png'" alt="Sistur" class="h-16">
            </Link>
        </div>

        <div class="w-full sm:max-w-md bg-white rounded-2xl shadow-xl border border-gray-100 px-8 py-8">
            <slot />
        </div>

        <p class="mt-6 text-sm text-gray-400">&copy; {{ new Date().getFullYear() }} Sistur Angola</p>
    </div>
</template>
