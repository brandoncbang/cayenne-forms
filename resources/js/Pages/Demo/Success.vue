<script setup>
import { Head, Link } from '@inertiajs/vue3';

// TODO: Maybe allow switching forms?
defineProps({
    form: Object,
    entry: Object,
});
</script>

<template>
    <Head title="Success!" />

    <div class="relative bg-white">
        <div class="lg:absolute lg:inset-0 lg:left-1/2">
            <img class="h-64 w-full bg-gray-50 object-cover sm:h-80 lg:absolute lg:h-full" src="https://images.unsplash.com/photo-1559136555-9303baea8ebd?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&crop=focalpoint&fp-x=.4&w=2560&h=3413&&q=80" alt="">
        </div>
        <div class="pb-24 pt-16 sm:pb-32 sm:pt-24 lg:mx-auto lg:grid lg:max-w-7xl lg:grid-cols-2 lg:pt-32">
            <div class="px-6 lg:px-8">
                <div class="mx-auto max-w-xl lg:mx-0 lg:max-w-lg">
                    <Link :href="route('forms.index')" class="text-lg font-medium leading-8 underline text-gray-900">
                        &larr; Back to Dashboard
                    </Link>

                    <h2 class="mt-8 text-3xl font-bold tracking-tight text-gray-900">
                        Success!
                    </h2>
                    <p class="mt-2 text-lg leading-8 text-gray-600">
                        This is a custom success page, meant to let form submitters know that their entry was received.
                        You can configure the success page URL when creating or editing a form. When left blank, a
                        <a :href="route('entries.success', form)" class="font-medium underline text-gray-900">default page</a>
                        is displayed.
                    </p>
                    <p class="mt-2 text-lg leading-8 text-gray-600">
                        You can view the entry you just submitted
                        <Link :href="route('forms.entries.index', form)" class="font-medium underline text-gray-900">
                            here.
                        </Link>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
