<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import SimplePagination from '@/Components/Dashboard/SimplePagination.vue';

const props = defineProps({
    form: Object,
    entries: Object,
});

const entriesContainer = ref(null);
const shownEntryContainer = ref(null);

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
        })
        .finally(() => {
            loadingShownEntry.value = false;
            shownEntryContainer.value.focus();
        });
};

const hideEntry = () => {
    shownEntry.value = null;
    entriesContainer.value.focus();
};
</script>

<template>
    <AuthenticatedLayout :title="`Entries for &ldquo;${form.name}&rdquo;`">
        <div class="overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:h-3/4">
            <div
                class="h-full md:flex md:items-stretch md:divide-x md:divide-gray-200"
                ref="entriesContainer"
                tabindex="0"
            >
                <ul
                    class="overflow-y-auto divide-y divide-gray-100 md:flex-shrink-0 md:w-1/3"
                    :class="{ 'hidden md:block': shownEntry }"
                >
                    <li v-for="entry in entries.data" key="entry.uuid">
                        <button
                            class="block w-full px-4 py-5 text-left sm:px-6"
                            :class="{ 'bg-gray-50': entry.uuid === shownEntry?.uuid }"
                            type="button"
                            @click="showEntry(entry)"
                        >
                            <span class="flex items-baseline justify-between gap-x-4">
                                <span class="text-sm font-semibold leading-6 text-gray-900">
                                    <span class="sr-only">Show entry, </span>{{ Object.values(entry.data)[0] }}
                                </span>
                                <span class="flex-none text-xs text-gray-600">
                                    <time :datetime="entry.created_at">
                                        {{ (new Date(entry.created_at)).toLocaleDateString() }}
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
                    <article
                        v-if="shownEntry"
                        class="max-w-full md:h-full md:overflow-y-auto"
                        ref="shownEntryContainer"
                        tabindex="0"
                    >
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
                                {{ (new Date(shownEntry.created_at)).toLocaleString() }}
                            </time>
                        </div>
                        <div class="border-t border-gray-200">
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
                    <div v-else class="hidden md:block md:px-6 md:py-5">
                        You have {{ entries.total }} entries!
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
