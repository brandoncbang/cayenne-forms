<script setup>
import { XCircleIcon, XMarkIcon } from '@heroicons/vue/20/solid/index.js';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const display = ref(true);

router.on('finish', () => {
    display.value = true;
});
</script>

<template>
    <div v-if="display" class="rounded-md bg-red-50 p-4 ring-1 ring-inset ring-red-800/30">
        <div class="flex">
            <div class="flex-shrink-0">
                <XCircleIcon class="h-5 w-5 text-red-400" aria-hidden="true" />
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800">
                    <slot />
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button
                        type="button"
                        class="inline-flex rounded-md bg-red-50 p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-600 focus:ring-offset-2 focus:ring-offset-red-50"
                        @click="display = false"
                    >
                        <span class="sr-only">Dismiss</span>
                        <XMarkIcon class="h-5 w-5" aria-hidden="true" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
