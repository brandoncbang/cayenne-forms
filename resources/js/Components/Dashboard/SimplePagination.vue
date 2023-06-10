<script setup>
import { Link } from "@inertiajs/vue3";
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/20/solid/index.js';

defineProps({
    paginator: Object,
});
</script>

<template>
    <nav class="flex items-center justify-between" aria-label="Pagination">
        <div class="hidden sm:block sm:mr-8">
            <p class="text-sm text-gray-700">
                Showing
                {{ ' ' }}
                <span class="font-medium">{{ paginator.from }}</span>
                {{ ' ' }}
                to
                {{ ' ' }}
                <span class="font-medium">{{ paginator.to }}</span>
                {{ ' ' }}
                of
                {{ ' ' }}
                <span class="font-medium">{{ paginator.total }}</span>
                {{ ' ' }}
                results
            </p>
        </div>

        <div class="isolate inline-flex -space-x-px rounded-md bg-white shadow-sm" aria-label="Pagination">
            <Link
                :href="paginator.prev_page_url"
                class="relative inline-flex items-center rounded-l-md px-3 py-3 ring-1 ring-inset ring-gray-300 sm:px-2 sm:py-2 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                :class="{ 'text-gray-900': paginator.prev_page_url, 'text-gray-400': !paginator.prev_page_url }"
                preserve-state
            >
                <span class="sr-only">Previous</span>
                <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
            </Link>
            <Link
                :href="paginator.next_page_url"
                class="relative inline-flex items-center rounded-r-md px-3 py-3 ring-1 ring-inset ring-gray-300 sm:px-2 sm:py-2 hover:bg-gray-50 focus:z-20 focus:outline-offset-0"
                :class="{ 'text-gray-900': paginator.next_page_url, 'text-gray-400': !paginator.next_page_url }"
                preserve-state
            >
                <span class="sr-only">Next</span>
                <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
            </Link>
        </div>
    </nav>
</template>
