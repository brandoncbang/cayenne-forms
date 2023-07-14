<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Link } from '@inertiajs/vue3';
import { EllipsisVerticalIcon, PlusIcon, PlusSmallIcon } from '@heroicons/vue/20/solid';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Dashboard/Pagination.vue';
import { displayDateTime, displayNumber } from '@/helpers.js';

const props = defineProps({
    'forms': Object,
});
</script>

<template>
    <AuthenticatedLayout title="Forms">
        <template #actions>
            <Link
                v-if="forms.data.length > 0"
                :href="route('forms.create')"
                class="ml-auto flex items-center gap-x-1 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
                <PlusSmallIcon class="-ml-1.5 h-5 w-5" aria-hidden="true" />
                New form
            </Link>
        </template>

        <div
            v-if="forms.data.length > 0"
            class="overflow-hidden bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl"
        >
            <ul role="list" class="divide-y divide-gray-100">
                <li
                    v-for="form in forms.data"
                    :key="form.uuid"
                    class="relative flex justify-between gap-x-6 px-4 py-5 hover:bg-gray-50 sm:px-6 lg:px-8"
                >
                    <div class="min-w-0">
                        <p class="text-sm font-semibold leading-6 text-gray-900">
                            <a :href="route('forms.entries.index', { form })">
                                <span class="absolute inset-x-0 -top-px bottom-0"></span>
                                {{ form.name }}
                            </a>
                        </p>
                        <p class="mt-1 flex text-xs leading-5 text-gray-500">
                            <span class="relative truncate hover:underline">
                                {{ route('forms.entries.store', { form }) }}
                            </span>
                        </p>
                    </div>
                    <div class="flex items-center gap-x-4">
                        <div v-if="form.entries_count > 0" class="hidden sm:flex sm:flex-col sm:items-end">
                            <p class="text-sm leading-6 text-gray-900">
                                {{ displayNumber(form.entries_count) }} {{ form.entries_count > 1 ? 'Entries' : 'Entry' }}
                            </p>
                            <p class="mt-1 text-xs leading-5 text-gray-500">
                                Last entry at
                                <time datetime="2023-01-23T13:23Z">
                                    {{ displayDateTime(form.latest_entry.created_at) }}
                                </time>
                            </p>
                        </div>
                        <svg class="h-5 w-5 flex-none text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </li>
            </ul>

            <Pagination :paginator="forms" class="px-4 py-5 border-t border-gray-200 sm:px-6" />
        </div>
        <div v-else class="px-4 py-5 text-center sm:px-6">
            <svg
                class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                aria-hidden="true"
            >
                <path
                    vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"
                />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900">No forms</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new form.</p>
            <div class="mt-6">
                <Link
                    :href="route('forms.create')"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    <PlusIcon class="-ml-0.5 mr-1.5 h-5 w-5" aria-hidden="true" />
                    New Form
                </Link>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
