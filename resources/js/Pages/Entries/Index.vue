<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, ref } from 'vue';
import { displayDate, displayDateTime, displayNumber, getFormEmbedCode } from '@/helpers.js';
import CopyButton from '@/Components/Dashboard/CopyButton.vue';
import { ArchiveBoxIcon, InboxArrowDownIcon, PencilIcon, TrashIcon } from '@heroicons/vue/20/solid/index.js';
import { InformationCircleIcon } from '@heroicons/vue/24/outline/index.js';
import { router, Link } from '@inertiajs/vue3';
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue';
import SimplePagination from '@/Components/Dashboard/SimplePagination.vue';
import ThinArrowDownIcon from '@/Components/Dashboard/Icons/ThinArrowDownIcon.vue';
import ThinTrashIcon from '@/Components/Dashboard/Icons/ThinTrashIcon.vue';
import ThinArchiveBoxIcon from '@/Components/Dashboard/Icons/ThinArchiveBoxIcon.vue';
import Entry from '@/Pages/Entries/Partials/Entry.vue';

const props = defineProps({
    form: Object,
    entries: Object,
});

const loadingSelectedEntry = ref(false);
const selectedEntry = ref(null);

const tabs = computed(() => {
    return [
        {
            name: 'Inbox',
            href: route('forms.entries.index', { form: props.form }),
            icon: InboxArrowDownIcon,
            current: route().current('forms.entries.index', { form: props.form, filter: null }),
        },
        {
            name: 'Archive',
            href: route('forms.entries.index', { form: props.form, filter: 'archived' }),
            icon: ArchiveBoxIcon,
            current: route().current('forms.entries.index', { form: props.form, filter: 'archived' }),
        },
        {
            name: 'Trash',
            href: route('forms.entries.index', { form: props.form, filter: 'trashed' }),
            icon: TrashIcon,
            current: route().current('forms.entries.index', { form: props.form, filter: 'trashed' }),
        },
    ];
});
const currentTab = computed(() => {
    return tabs.value.find(tab => tab.current);
})

const selectEntry = (entry) => {
    if (entry.uuid === selectedEntry.value?.uuid) {
        return;
    }

    loadingSelectedEntry.value = true;

    axios
        .get(route('entries.show', { entry }))
        .then(response => {
            selectedEntry.value = response.data.entry;
            // TODO: Focus inside entry container
        })
        .catch(_error => {
            router.reload();
            selectedEntry.value = null;
            // TODO: Focus outside entry container
        })
        .finally(() => {
            loadingSelectedEntry.value = false;
        });
};

const deselectEntry = () => {
    selectedEntry.value = null;
    // TODO: Focus outside entry container
};
</script>

<template>
    <AuthenticatedLayout :title="`Entries for &ldquo;${form.name}&rdquo;`">
        <template #actions>
            <Link
                :href="route('forms.edit', { form: props.form })"
                class="ml-auto flex items-center gap-x-1 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
                <PencilIcon class="-ml-0.5 mr-1.5 h-5 w-5" aria-hidden="true" />
                Edit
            </Link>
        </template>

        <div class="flex justify-between items-end px-4 sm:px-0">
            <div class="w-48 md:w-96">
                <div class="md:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <select
                        id="tabs"
                        name="tabs"
                        class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        @change="router.visit($event.target.value)"
                    >
                        <option v-for="tab in tabs" :key="tab.name" :selected="tab.current" :value="tab.href">
                            {{ tab.name }}
                        </option>
                    </select>
                </div>
                <div class="hidden md:block">
                    <nav class="isolate flex divide-x divide-gray-200 rounded-lg shadow" aria-label="Tabs">
                        <Link
                            v-for="(tab, tabIdx) in tabs"
                            :key="tab.name"
                            :href="tab.href"
                            :class="[tab.current ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700', tabIdx === 0 ? 'rounded-l-lg' : '', tabIdx === tabs.length - 1 ? 'rounded-r-lg' : '', 'group relative inline-flex justify-center items-center min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-center text-sm font-medium hover:bg-gray-50 focus:z-10']"
                            :aria-current="tab.current ? 'page' : undefined"
                        >
                            <component
                                :is="tab.icon"
                                :class="[tab.current ? 'text-indigo-500' : 'text-gray-400 group-hover:text-gray-500', 'flex-shrink-0 -ml-0.5 mr-2 h-5 w-5']"
                                aria-hidden="true"
                            />
                            <span>{{ tab.name }}</span>
                            <span
                                aria-hidden="true"
                                :class="[tab.current ? 'bg-indigo-500' : 'bg-transparent', 'absolute inset-x-0 top-0 h-0.5']"
                            />
                        </Link>
                    </nav>
                </div>
            </div>

            <SimplePagination v-if="entries.total > 0" :paginator="entries" />
        </div>
        <div class="overflow-hidden mt-4 bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl md:h-3/4">
            <!-- Entries -->
            <div v-if="entries.total > 0" class="h-full md:flex md:items-stretch md:divide-x md:divide-gray-200">
                <!-- Entry selection list -->
                <ul
                    class="overflow-y-auto divide-y divide-gray-100 md:flex-shrink-0 md:w-1/3"
                    :class="{ 'hidden md:block': selectedEntry }"
                >
                    <li v-for="entry in entries.data" key="entry.uuid">
                        <button
                            class="block w-full px-4 py-5 text-left sm:px-6"
                            :class="{ 'bg-indigo-100': entry.uuid === selectedEntry?.uuid }"
                            type="button"
                            @click="selectEntry(entry)"
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
                    <!-- Show selected entry -->
                    <Entry
                        v-if="selectedEntry"
                        :entry="selectedEntry"
                        class="max-w-full md:h-full md:overflow-y-auto"
                        @close="deselectEntry"
                    />
                    <!-- No entry selected -->
                    <div v-else class="hidden md:flex md:flex-col md:justify-center md:h-full md:px-6 md:py-5">
                        <div class="text-center">
                            <div class="inline-block mx-auto text-gray-400">
                                <ThinArrowDownIcon v-if="currentTab.name === 'Inbox'" />
                                <ThinArchiveBoxIcon v-if="currentTab.name === 'Archive'" />
                                <ThinTrashIcon v-if="currentTab.name === 'Trash'" />
                            </div>
                            <h3 class="mt-2 text-base font-semibold text-gray-900">
                                {{ tabs.find(tab => tab.current)?.name }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-500">
                                You have {{ displayNumber(entries.total, 999_999) }}
                                {{ entries.total === 1 ? 'entry' : 'entries' }} here.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- No entries -->
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
                            <span v-if="currentTab?.name === 'Inbox'">
                                Add this form's
                                <Link :href="route('forms.edit', { form })" class="font-medium underline text-gray-900">embed code</Link>
                                to your website's HTML, and any new form entries you receive will show up here.
                            </span>
                            <span v-else>
                                Any form entries you {{ currentTab?.name.toLocaleLowerCase() }} will show up here.
                            </span>
                        </p>
                    </div>
                    <CopyButton
                        v-if="currentTab?.name === 'Inbox'"
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
