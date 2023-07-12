<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    title: String,
});
</script>

<template>
    <Head :title="title" />

    <div class="flex min-h-full flex-1 flex-col justify-center py-12 sm:px-6 lg:px-8">
        <header class="sm:mx-auto sm:w-full sm:max-w-md">
            <ApplicationLogo class="mx-auto h-16 w-auto" :aria-label="$page.props.app.name" />
            <h2 class="mt-6 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">
                {{ title }}
            </h2>
        </header>

        <main class="mt-10 sm:mx-auto sm:w-full sm:max-w-[480px]">
            <div class="bg-white px-6 py-12 shadow sm:rounded-lg sm:px-12">
                <slot />
            </div>
        </main>

        <footer class="mt-16 text-center text-sm text-gray-500">
            <p>
                Powered by
            </p>
            <p class="mt-2">
                <a :href="$page.props.app.repoUrl" class="inline-flex items-center">
                    <ApplicationLogo class="inline h-8 w-8" aria-hidden="true" />
                    <span class="ml-2 text-2xl font-logo font-bold text-gray-900">
                        {{ $page.props.app.name }}
                    </span>
                </a>
            </p>
        </footer>
    </div>
</template>
