<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    paginator: Object,
});
</script>

<template>
    <div v-if="paginator.total > paginator.per_page" class="flex items-center justify-between">
        <div class="flex flex-1 justify-between lg:hidden">
            <Link
                :href="paginator.last_page_url"
                class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Previous
            </Link>
            <Link
                :href="paginator.prev_page_url"
                class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50"
            >
                Next
            </Link>
        </div>
        <div class="hidden lg:flex lg:flex-1 lg:items-center lg:justify-between">
            <div>
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
            <div>
                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <template v-for="(link, i) in paginator.links">
                        <Link
                            v-if="link.url"
                            :key="link.label"
                            :href="link.url"
                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold ring-inset first:rounded-l-md last:rounded-r-md focus:z-20"
                            :class="{
                                'z-10 bg-indigo-600 text-white focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600': link.active,
                                'text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:outline-offset-0': !link.active,
                            }"
                            v-html="link.label"
                        ></Link>
                        <span
                            v-if="!link.url"
                            :key="link.label"
                            class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 first:rounded-l-md last:rounded-r-md focus:outline-offset-0"
                            v-html="link.label"
                        ></span>
                    </template>
                </nav>
            </div>
        </div>
    </div>
</template>
