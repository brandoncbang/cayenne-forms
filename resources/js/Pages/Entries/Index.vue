<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed, nextTick, ref } from 'vue';
import { displayDate, displayNumber, getFormEmbedCode } from '@/helpers.js';
import CopyButton from '@/Components/Dashboard/CopyButton.vue';
import { TabGroup, TabList, Tab, TabPanels, TabPanel } from '@headlessui/vue';
import { ArchiveBoxIcon, InboxArrowDownIcon, PencilIcon, TrashIcon } from '@heroicons/vue/20/solid/index.js';
import { router, Link } from '@inertiajs/vue3';
import SimplePagination from '@/Components/Dashboard/SimplePagination.vue';
import ThinArrowDownIcon from '@/Components/Dashboard/Icons/ThinArrowDownIcon.vue';
import ThinTrashIcon from '@/Components/Dashboard/Icons/ThinTrashIcon.vue';
import ThinArchiveBoxIcon from '@/Components/Dashboard/Icons/ThinArchiveBoxIcon.vue';
import Entry from '@/Pages/Entries/Partials/Entry.vue';

const props = defineProps({
    form: Object,
    entries: Object,
});

const entryPanelShown = ref(false);

const tabs = ref(null);
const panels = ref(null);

const folders = computed(() => {
    return [
        {
            name: 'Inbox',
            href: route('forms.entries.index', { form: props.form }),
            icon: InboxArrowDownIcon,
            graphic: ThinArrowDownIcon,
            current: route().current('forms.entries.index', { form: props.form, filter: null }),
        },
        {
            name: 'Archive',
            href: route('forms.entries.index', { form: props.form, filter: 'archived' }),
            icon: ArchiveBoxIcon,
            graphic: ThinArchiveBoxIcon,
            current: route().current('forms.entries.index', { form: props.form, filter: 'archived' }),
        },
        {
            name: 'Trash',
            href: route('forms.entries.index', { form: props.form, filter: 'trashed' }),
            icon: TrashIcon,
            graphic: ThinTrashIcon,
            current: route().current('forms.entries.index', { form: props.form, filter: 'trashed' }),
        },
    ];
});
const currentFolder = computed(() => {
    return folders.value.find(tab => tab.current);
});

const showEntryPanel = () => {
    entryPanelShown.value = true;
};

const hideEntryPanel = () => {
    entryPanelShown.value = false;
};
</script>

<template>
    <AuthenticatedLayout :title="form.name">
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
                        <option v-for="folder in folders" :key="folder.name" :selected="folder.current" :value="folder.href">
                            {{ folder.name }}
                        </option>
                    </select>
                </div>
                <div class="hidden md:block">
                    <nav class="isolate flex divide-x divide-gray-200 rounded-lg shadow" aria-label="Tabs">
                        <Link
                            v-for="(tab, tabIdx) in folders"
                            :key="tab.name"
                            :href="tab.href"
                            :class="[tab.current ? 'text-gray-900' : 'text-gray-500 hover:text-gray-700', tabIdx === 0 ? 'rounded-l-lg' : '', tabIdx === folders.length - 1 ? 'rounded-r-lg' : '', 'group relative inline-flex justify-center items-center min-w-0 flex-1 overflow-hidden bg-white py-3 px-4 text-center text-sm font-medium hover:bg-gray-50 focus:z-10']"
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
                                :class="[tab.current ? 'bg-indigo-500' : 'bg-transparent', 'absolute inset-x-0 bottom-0 h-0.5']"
                            />
                        </Link>
                    </nav>
                </div>
            </div>

            <SimplePagination v-if="entries.data.length > 0" :paginator="entries" />
        </div>
        <div
            class="mt-4 bg-white shadow-sm ring-1 ring-gray-900/5 sm:mt-2 sm:rounded-xl md:h-[60vh]"
            ref="entryContainer"
            tabindex="-1"
        >
            <TabGroup
                as="div"
                v-if="entries.data.length > 0"
                class="h-full md:flex md:items-stretch md:divide-x md:divide-gray-200"
                @change="showEntryPanel"
                manual
                vertical
            >
                <TabList
                    class="relative overflow-hidden overflow-y-auto divide-y divide-gray-100 sm:flex-shrink-0 sm:rounded-xl md:w-96 md:rounded-r-none"
                    :class="{ 'hidden md:block': entryPanelShown }"
                    ref="tabs"
                >
                    <Tab
                        as="template"
                        v-slot="{ selected }"
                        v-for="entry in entries.data"
                        :key="entry.uuid"
                    >
                        <button
                            class="block w-full px-4 py-5 text-left sm:px-6 sm:first:rounded-t-xl sm:last:rounded-b-xl md:first:rounded-tr-none md:last:rounded-br-none focus:ring-2 focus:ring-inset focus:ring-indigo-500 focus:outline-none"
                            :class="{ 'bg-indigo-100': selected }"
                            @click="showEntryPanel"
                            @keydown.enter="showEntryPanel"
                            @keydown.space="showEntryPanel"
                        >
                            <span class="flex items-baseline justify-between gap-x-4">
                                <span class="text-sm font-semibold leading-6 text-gray-900">
                                    <span class="sr-only">Show entry, </span>{{ entry.title }}
                                </span>
                                <span class="flex-none text-xs text-gray-600">
                                    <time :datetime="entry.created_at">
                                        {{ displayDate(entry.created_at) }}
                                    </time>
                                </span>
                            </span>
                                <span
                                    v-if="entry.excerpt"
                                    class="mt-1 line-clamp-2 text-sm leading-6 text-gray-600"
                                >
                                {{ entry.excerpt }}
                            </span>
                        </button>
                    </Tab>
                </TabList>
                <TabPanels
                    class="flex-1 overflow-hidden sm:rounded-xl md:rounded-l-none"
                    :class="{ 'hidden md:block': !entryPanelShown }"
                    ref="panels"
                >
                    <TabPanel
                        as="template"
                        v-for="entry in entries.data"
                        :key="entry.uuid"
                    >
                        <Entry
                            :entry="entry"
                            :honeypot_field="form.honeypot_field"
                            class="max-w-full md:h-full md:overflow-y-auto"
                            @close="hideEntryPanel"
                        />
                    </TabPanel>
                </TabPanels>
            </TabGroup>
            <!-- No entries -->
            <div v-else class="flex items-center h-full overflow-hidden px-4 py-5 sm:px-6 sm:rounded-xl">
                <div class="mx-auto max-w-lg">
                    <div class="text-center">
                        <svg
                            class="mx-auto h-12 w-12 -mt-1.5 text-gray-400"
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
                            <span v-if="currentFolder?.name === 'Inbox'">
                                Add this form's
                                <Link :href="route('forms.edit', { form })" class="font-medium underline text-gray-900">embed code</Link>
                                to your website's HTML, and any new form entries you receive will show up here.
                            </span>
                            <span v-else>
                                Any form entries you {{ currentFolder?.name.toLocaleLowerCase() }} will show up here.
                            </span>
                        </p>
                    </div>
                    <CopyButton
                        v-if="currentFolder?.name === 'Inbox'"
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
