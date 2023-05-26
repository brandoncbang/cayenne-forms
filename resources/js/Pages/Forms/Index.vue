<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Link } from '@inertiajs/vue3';
import { EllipsisVerticalIcon, PlusIcon, PlusSmallIcon } from '@heroicons/vue/20/solid';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Dashboard/Pagination.vue';

const props = defineProps({
    'forms': Object,
});

const maxUnreadEntriesCountDisplayed = 999_999;
</script>

<template>
    <AuthenticatedLayout title="Forms">
        <template #actions>
            <Link
                :href="route('forms.create')"
                class="ml-auto flex items-center gap-x-1 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            >
                <PlusSmallIcon class="-ml-1.5 h-5 w-5" aria-hidden="true" />
                New form
            </Link>
        </template>

        <div
            v-if="forms.data.length > 0"
            class="bg-white shadow-sm ring-1 ring-gray-900/5 sm:rounded-xl"
        >
            <ul
                role="list"
                class="divide-y divide-gray-100"
            >
                <li
                    v-for="form in forms.data"
                    :key="form.uuid"
                    class="flex items-center justify-between gap-x-6 px-4 py-5 sm:px-6"
                >
                    <div class="min-w-0">
                        <div class="flex items-start gap-x-3">
                            <p class="text-sm font-semibold leading-6 text-gray-900">
                                {{ form.name }}
                            </p>
                            <p
                                v-if="form.unread_entries_count > 0"
                                class="inline-flex items-center gap-x-1.5 rounded-full bg-blue-100 px-2 py-1 text-xs font-medium text-blue-700"
                            >
                                <svg class="h-1.5 w-1.5 fill-blue-500" viewBox="0 0 6 6" aria-hidden="true">
                                    <circle cx="3" cy="3" r="3" />
                                </svg>
                                {{
                                    form.unread_entries_count <= maxUnreadEntriesCountDisplayed
                                        ? form.unread_entries_count.toLocaleString()
                                        : `${maxUnreadEntriesCountDisplayed.toLocaleString()}+`
                                }} Unread
                            </p>
                        </div>
                    </div>
                    <div class="flex flex-none items-center gap-x-4">
                        <Link
                            :href="route('forms.entries.index', { form })"
                            class="hidden rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:block"
                        >
                            View<span class="sr-only">, {{ form.name }}'s</span> entries
                        </Link>
                        <Menu as="div" class="relative flex-none">
                            <MenuButton class="-m-2.5 block p-2.5 text-gray-500 hover:text-gray-900">
                                <span class="sr-only">Open options</span>
                                <EllipsisVerticalIcon class="h-5 w-5" aria-hidden="true" />
                            </MenuButton>
                            <transition
                                enter-active-class="transition ease-out duration-100"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2 w-32 origin-top-right rounded-md bg-white py-2 shadow-lg ring-1 ring-gray-900/5 focus:outline-none"
                                >
                                    <MenuItem v-slot="{ active }" class="sm:hidden">
                                        <Link
                                            :href="route('forms.entries.index', { form })"
                                            :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"
                                        >
                                            View<span class="sr-only">, {{ form.name }}'s</span> entries
                                        </Link>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            :href="route('forms.edit', { form })"
                                            :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"
                                        >
                                            Edit<span class="sr-only">, {{ form.name }}</span>
                                        </Link>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            href="#"
                                            :class="[active ? 'bg-gray-50' : '', 'block px-3 py-1 text-sm leading-6 text-gray-900']"
                                        >
                                            Delete<span class="sr-only">, {{ form.name }}</span>
                                        </Link>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
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
