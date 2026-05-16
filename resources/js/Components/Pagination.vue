<script setup>
import { Link } from '@inertiajs/vue3';
defineProps({ links: { type: Array, required: true } });
</script>

<template>
    <div v-if="links.length > 3" class="flex items-center justify-center gap-1 mt-8">
        <template v-for="link in links" :key="link.label">
            <Link v-if="link.url" :href="link.url" preserve-scroll
                  class="px-3 py-2 rounded-lg text-sm transition"
                  :class="link.active ? 'bg-orange-500 text-white font-semibold' : 'text-gray-600 hover:bg-gray-100'"
                  v-html="link.label" />
            <span v-else class="px-3 py-2 text-sm text-gray-400" v-html="link.label" />
        </template>
    </div>
</template>
