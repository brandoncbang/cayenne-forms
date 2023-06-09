<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { displayDate, displayDateTime, displayNumber, getFormEmbedCode } from '@/helpers.js';
import CopyButton from '@/Components/Dashboard/CopyButton.vue';
import { router, Link } from '@inertiajs/vue3';

const props = defineProps({
    form: Object,
    entries: Object,
});

const loadingShownEntry = ref(false);
const shownEntry = ref(null);

const showEntry = (entry) => {
    if (entry.uuid === shownEntry.value?.uuid) {
        return;
    }

    loadingShownEntry.value = true;

    axios
        .get(route('entries.show', { entry }))
        .then(response => {
            shownEntry.value = response.data.entry;
            // TODO: Focus inside entry container
        })
        .catch(error => {
            router.reload();
            shownEntry.value = null;
            // TODO: Focus outside entry container
        })
        .finally(() => {
            loadingShownEntry.value = false;
        });
};

const hideEntry = () => {
    shownEntry.value = null;
    // TODO: Focus outside entry container
};
</script>

<template>
    <AuthenticatedLayout :title="`Entries for &ldquo;${form.name}&rdquo;`">
        <div class="overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:h-3/4">
            <div v-if="entries.total > 0" class="h-full md:flex md:items-stretch md:divide-x md:divide-gray-200">
                <ul
                    class="overflow-y-auto divide-y divide-gray-100 md:flex-shrink-0 md:w-1/3"
                    :class="{ 'hidden md:block': shownEntry }"
                >
                    <li v-for="entry in entries.data" key="entry.uuid">
                        <button
                            class="block w-full px-4 py-5 text-left sm:px-6"
                            :class="{ 'bg-indigo-100': entry.uuid === shownEntry?.uuid }"
                            type="button"
                            @click="showEntry(entry)"
                        >
                            <span class="flex items-baseline justify-between gap-x-4">
                                <span class="text-sm font-semibold leading-6 text-gray-900">
                                    <span class="sr-only">Show entry, </span>{{ Object.values(entry.data)[0] }}
                                </span>
                                <span class="flex-none text-xs text-gray-600">
                                    <time :datetime="entry.created_at">
                                        {{ displayDate(entry.created_at) }}
                                    </time>
                                </span>
                            </span>
                            <span class="mt-1 line-clamp-2 text-sm leading-6 text-gray-600">
                                {{ Object.values(entry.data)[1] }}
                            </span>
                        </button>
                    </li>
                </ul>
                <div class="flex-1">
                    <article v-if="shownEntry" class="max-w-full md:h-full md:overflow-y-auto">
                        <div class="px-4 py-8 md:px-6">
                            <div class="flex items-center space-x-2">
                                <button
                                    type="button"
                                    class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 md:hidden hover:bg-gray-50"
                                    @click="hideEntry"
                                >
                                    &larr; Back
                                </button>
                            </div>
                            <h2 class="mt-6 text-base font-semibold leading-7 text-gray-900 md:mt-0">
                                {{ 'email' in shownEntry.data ? shownEntry.data.email : 'Untitled' }}
                            </h2>
                            <time
                                :datetime="shownEntry.created_at" class="mt-1 max-w-2xl text-sm leading-6 text-gray-500"
                            >
                                {{ displayDateTime(shownEntry.created_at) }}
                            </time>
                        </div>
                        <div class="border-t border-gray-100">
                            <dl class="divide-y divide-gray-100">
                                <div
                                    v-for="(value, key) in shownEntry.data"
                                    class="px-4 py-6 even:bg-gray-50 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-6"
                                >
                                    <dt class="text-sm font-medium leading-6 text-gray-900">
                                        {{ key[0].toLocaleUpperCase() + key.slice(1) }}
                                    </dt>
                                    <dd class="mt-1 text-sm leading-6 whitespace-pre-wrap text-gray-700 sm:col-span-3 sm:mt-0">
                                        {{ value }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </article>
                    <div
                        v-else
                        class="hidden md:flex md:flex-col md:justify-center md:h-full md:px-6 md:py-5"
                    >
                        <div class="text-center">
                            <svg
                                class="mx-auto h-12 w-12 text-gray-400"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1"
                                stroke="currentColor"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M9 3.75H6.912a2.25 2.25 0 00-2.15 1.588L2.35 13.177a2.25 2.25 0 00-.1.661V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 00-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 012.012 1.244l.256.512a2.25 2.25 0 002.013 1.244h3.218a2.25 2.25 0 002.013-1.244l.256-.512a2.25 2.25 0 012.013-1.244h3.859M12 3v8.25m0 0l-3-3m3 3l3-3"
                                />
                            </svg>
                            <h3 class="mt-2 text-base font-semibold text-gray-900">Inbox</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                You have {{ displayNumber(entries.total, 999_999) }} entries here.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else class="flex items-center h-full px-4 py-5 sm:px-6">
                <div class="mx-auto max-w-lg">
                    <div class="text-center">
                        <svg
                            class="mx-auto h-12 w-12 -mt-2 text-gray-400"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"
                            />
                        </svg>
                        <h2 class="mt-2 text-base font-semibold leading-6 text-gray-900">
                            Nothing here yet
                        </h2>
                        <p class="mt-1 text-sm text-gray-500">
                            You haven’t received any entries for this form yet. To start receiving entries, add this
                            form's
                            <Link :href="route('forms.edit', { form })" class="font-medium underline text-gray-900">
                                embed code
                            </Link>
                            to your website's HTML.
                        </p>
                    </div>
                    <CopyButton
                        class="block mt-6 mx-auto rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        :value="getFormEmbedCode(form)"
                    >
                        Copy embed code
                    </CopyButton>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
