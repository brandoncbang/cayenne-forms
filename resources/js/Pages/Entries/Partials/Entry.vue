<script setup>
import { displayDateTime } from '@/helpers.js';
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue';
import { InformationCircleIcon } from '@heroicons/vue/24/outline/index.js';

defineProps({
    entry: Object,
});

defineEmits(['close']);
</script>

<template>
    <article>
        <div class="px-4 py-8 md:px-6">
            <div class="flex items-center space-x-2">
                <button
                    type="button"
                    class="rounded-md bg-white px-2.5 py-1.5 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 md:hidden hover:bg-gray-50"
                    @click="$emit('close')"
                >
                    &larr; Back
                </button>
            </div>
            <h2 class="mt-6 text-base font-semibold leading-7 text-gray-900 md:mt-0">
                {{ 'email' in entry.data ? entry.data.email : 'Untitled' }}
            </h2>
            <div class="flex items-center mt-1 max-w-2xl">
                <time
                    :datetime="entry.created_at" class="text-sm leading-6 text-gray-500"
                >
                    {{ displayDateTime(entry.created_at) }}
                </time>
                <Popover class="sm:relative ml-2">
                    <PopoverButton class="block -mt-0.5" title="Show advanced info">
                        <span class="sr-only">Show advanced info</span>
                        <InformationCircleIcon class="h-5 w-5 text-gray-700" aria-hidden="true" />
                    </PopoverButton>
                    <transition
                        enter-active-class="transition ease-out duration-100"
                        enter-from-class="transform opacity-0 scale-95"
                        enter-to-class="transform opacity-100 scale-100"
                        leave-active-class="transition ease-in duration-75"
                        leave-from-class="transform opacity-100 scale-100"
                        leave-to-class="transform opacity-0 scale-95"
                    >
                        <PopoverPanel class="absolute left-0 ml-4 z-10 mt-2 px-4 py-3 w-56 origin-top rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 sm:origin-top-left sm:ml-0">
                            <dl class="space-y-2">
                                <div>
                                    <dt class="text-xs font-medium leading-5 text-gray-700">
                                        IP Address
                                    </dt>
                                    <dd class="mt-1 text-xs leading-5 truncate text-gray-500">
                                        {{ entry.ip_address }}
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium leading-5 text-gray-700">
                                        User Agent
                                    </dt>
                                    <dd class="mt-1 line-clamp-6 text-xs leading-5 text-gray-500">
                                        {{ entry.user_agent }}
                                    </dd>
                                </div>
                            </dl>
                        </PopoverPanel>
                    </transition>
                </Popover>
            </div>
        </div>
        <div class="border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div
                    v-for="(value, key) in entry.data"
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
</template>
