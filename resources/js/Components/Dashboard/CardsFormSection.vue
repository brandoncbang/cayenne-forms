<script setup>
import {useSlots} from "vue";

const props = defineProps({
    title: String,
});

const slots = useSlots();
</script>

<template>
    <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
        <div class="px-4 sm:px-0">
            <h2 class="text-base font-semibold leading-7 text-gray-900">{{ title }}</h2>
            <div v-if="slots.description" class="mt-1 text-sm leading-6 text-gray-600">
                <slot name="description" />
            </div>
        </div>

        <div class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:col-span-2">
            <div class="px-4 py-6 sm:p-8">
                <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                    <slot />
                </div>
            </div>
            <div class="flex items-center justify-end gap-x-6 border-t border-gray-900/10 px-4 py-4 sm:px-8">
                <slot name="actions" />
            </div>
        </div>
    </div>
</template>
