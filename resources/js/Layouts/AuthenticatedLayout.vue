<script setup>
import { Disclosure, DisclosureButton, DisclosurePanel, Menu, MenuButton, MenuItem, MenuItems } from '@headlessui/vue';
import { Bars3Icon, XMarkIcon } from '@heroicons/vue/24/outline';
import { Head, Link } from '@inertiajs/vue3';
import InitialsAvatar from '@/Components/Dashboard/InitialsAvatar.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { useSlots } from 'vue';

defineProps({
    title: String,
});

const slots = useSlots();
</script>

<template>
    <Head :title="title" />

    <div class="flex flex-col items-stretch">
        <Disclosure as="nav" class="flex-shrink-0 bg-white shadow-sm" v-slot="{ open }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <div class="flex flex-shrink-0 items-center">
                            <ApplicationLogo class="h-8 w-8" aria-hidden="true" />
                            <span class="sr-only">{{ $page.props.app.name }}</span>
                        </div>
                        <div class="hidden sm:-my-px sm:ml-6 sm:flex sm:space-x-8">
                            <Link
                                v-for="item in $page.props.navigation" :key="item.name" :href="item.href"
                                :class="[item.current ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700', 'inline-flex items-center border-b-2 px-1 pt-1 text-sm font-medium']"
                                :aria-current="item.current ? 'page' : undefined"
                            >
                                {{ item.name }}
                            </Link>
                        </div>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center">
                        <!-- Profile dropdown -->
                        <Menu as="div" class="relative ml-3">
                            <div>
                                <MenuButton
                                    class="flex rounded-full bg-white text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                >
                                    <span class="sr-only">Open user menu</span>
                                    <InitialsAvatar :user="$page.props.auth.user" />
                                </MenuButton>
                            </div>
                            <transition
                                enter-active-class="transition ease-out duration-200"
                                enter-from-class="transform opacity-0 scale-95"
                                enter-to-class="transform opacity-100 scale-100"
                                leave-active-class="transition ease-in duration-75"
                                leave-from-class="transform opacity-100 scale-100"
                                leave-to-class="transform opacity-0 scale-95"
                            >
                                <MenuItems
                                    class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                >
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            :href="route('profile.edit')"
                                            :class="[route().current('profile.edit') ? 'bg-gray-100' : '', 'block px-4 py-2 text-sm text-gray-700']"
                                        >
                                            Profile
                                        </Link>
                                    </MenuItem>
                                    <MenuItem v-slot="{ active }">
                                        <Link
                                            :href="route('logout')" as="button" method="post"
                                            class="block w-full px-4 py-2 text-sm text-left text-gray-700"
                                        >
                                            Log Out
                                        </Link>
                                    </MenuItem>
                                </MenuItems>
                            </transition>
                        </Menu>
                    </div>
                    <div class="-mr-2 flex items-center sm:hidden">
                        <!-- Mobile menu button -->
                        <DisclosureButton
                            class="inline-flex items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            <span class="sr-only">Open main menu</span>
                            <Bars3Icon v-if="!open" class="block h-6 w-6" aria-hidden="true" />
                            <XMarkIcon v-else class="block h-6 w-6" aria-hidden="true" />
                        </DisclosureButton>
                    </div>
                </div>
            </div>

            <DisclosurePanel class="sm:hidden">
                <div class="space-y-1 pb-3 pt-2">
                    <Link
                        v-for="item in $page.props.navigation" :key="item.name" :href="item.href"
                        :class="[item.current ? 'border-indigo-500 bg-indigo-50 text-indigo-700' : 'border-transparent text-gray-600 hover:border-gray-300 hover:bg-gray-50 hover:text-gray-800', 'block border-l-4 py-2 pl-3 pr-4 text-base font-medium']"
                        :aria-current="item.current ? 'page' : undefined"
                    >
                        {{ item.name }}
                    </Link>
                </div>
                <div class="border-t border-gray-200 pb-3 pt-4">
                    <div class="flex items-center px-4">
                        <div class="flex-shrink-0">
                            <InitialsAvatar :user="$page.props.auth.user" />
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-gray-800">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <Link
                            :href="route('profile.edit')"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800"
                        >
                            Profile
                        </Link>
                        <Link
                            :href="route('logout')" as="button" method="post"
                            class="block px-4 py-2 text-base font-medium text-gray-500 hover:bg-gray-100 hover:text-gray-800"
                        >
                            Log Out
                        </Link>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>

        <div class="flex-1 flex flex-col items-stretch min-h-0 py-10">
            <header class="flex-shrink-0">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
                    <div class="min-w-0 flex-1">
                        <h1 class="text-3xl font-bold leading-tight tracking-tight text-gray-900 sm:truncate">
                            {{ title }}
                        </h1>
                    </div>
                    <div v-if="slots.actions" class="mt-4 flex md:ml-4 md:mt-0">
                        <slot name="actions" />
                    </div>
                </div>
            </header>
            <main class="flex-1">
                <div class="min-h-full mx-auto max-w-7xl py-8 sm:px-6 lg:px-8">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
