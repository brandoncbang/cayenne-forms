<script setup>
import { displayDateTime, displayObjectKey, entryFieldIsEmail } from '@/helpers.js';
import { Popover, PopoverButton, PopoverPanel } from '@headlessui/vue';
import { ArchiveBoxIcon, ArrowLeftIcon, TrashIcon, XCircleIcon } from '@heroicons/vue/20/solid/index.js';
import { InformationCircleIcon } from '@heroicons/vue/24/outline/index.js';
import { router } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    entry: Object,
    honeypot_field: {
        type: String,
        default: null,
    },
});

const emit = defineEmits(['close']);

const entryData = computed(() => {
    return Object.fromEntries(
        Object.entries(props.entry.data)
            .filter(([key, _]) => key !== props.honeypot_field),
    );
});

const entryIsArchived = computed(() => {
    return props.entry.archived_at !== null;
});
const entryIsTrashed = computed(() => {
    return props.entry.deleted_at !== null;
});

const updateEntry = (data) => {
    emit('close')

    router.patch(route('entries.update', { entry: props.entry }), data, { preserveState: true });
};

const archive = () => {
    if (!confirm('Are you sure you want to archive this entry?')) {
        return;
    }

    updateEntry({
        archived_at: (new Date()).toISOString(),
    });
};

const unarchive = () => {
    updateEntry({
        archived_at: null
    });
};

const trash = () => {
    if (!confirm('Are you sure you want to trash this entry?')) {
        return;
    }

    updateEntry({
        deleted_at: (new Date()).toISOString(),
    });
};

const untrash = () => {
    updateEntry({
        deleted_at: null,
    });
};

const destroy = () => {
    if (!confirm('Are you sure you want to permanently delete this entry?')) {
        return;
    }

    emit('close');

    router.delete(route('entries.destroy', { entry: props.entry }), { preserveState: true });
}
</script>

<template>
    <article>
        <div class="px-4 py-6 lg:flex lg:flex-row-reverse lg:items-start lg:justify-between lg:space-x-reverse-5 md:px-6">
            <div class="flex flex-col justify-stretch space-y-4 space-y sm:flex-row sm:space-x-3 sm:space-y-0 md:space-x-reverse lg:justify-end lg:mt-0 lg:space-x-3">
                <button
                    type="button"
                    class="inline-flex items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 md:hidden hover:bg-gray-50"
                    @click="$emit('close')"
                >
                    <ArrowLeftIcon class="-ml-0.5 mr-1.5 h-5 w-5" aria-hidden="true" />
                    Back
                </button>

                <button
                    class="inline-flex items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                    type="button"
                    @click="entryIsArchived ? unarchive() : archive()"
                >
                    <ArchiveBoxIcon class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                    {{ entryIsArchived ? 'Unarchive' : 'Archive' }}
                </button>

                <button
                    class="inline-flex items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                    type="button"
                    @click="entryIsTrashed ? untrash() : trash()"
                >
                    <TrashIcon class="-ml-0.5 mr-1.5 h-5 w-5 text-gray-400" aria-hidden="true" />
                    {{ entryIsTrashed ? 'Untrash' : 'Trash' }}
                </button>

                <button
                    v-if="entryIsTrashed"
                    class="inline-flex items-center justify-center rounded-md bg-rose-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600"
                    type="button"
                    @click="destroy"
                >
                    <XCircleIcon class="-ml-0.5 mr-1.5 h-5 w-5" aria-hidden="true" />
                    Delete
                </button>
            </div>

            <div class="mt-6 lg:mt-0">
                <h2 class="text-base font-semibold leading-7 text-gray-900">
                    {{ entry.title }}
                </h2>
                <div class="flex items-center mt-1 max-w-2xl">
                    <time
                        :datetime="entry.created_at" class="text-sm font-medium leading-6 text-gray-500"
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
                            <!-- TODO: Fix when desktop layout is narrow. -->
                            <PopoverPanel class="absolute left-0 ml-4 z-10 mt-2 px-4 py-3 w-56 origin-top rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 md:origin-top-left md:ml-0">
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
        </div>
        <div class="border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div
                    v-for="(value, key) in entryData"
                    class="px-4 py-6 even:bg-gray-50 sm:grid sm:grid-cols-4 sm:gap-4 sm:px-6"
                >
                    <dt class="text-sm font-medium leading-6 text-gray-900">
                        {{ displayObjectKey(key) }}
                    </dt>
                    <dd class="mt-1 text-sm leading-6 whitespace-pre-wrap text-gray-700 sm:col-span-3 sm:mt-0">
                        <a
                            v-if="entryFieldIsEmail(key, value)"
                            :href="`mailto:${value}`"
                            class="inline-flex items-center font-medium text-indigo-600 hover:text-indigo-500"
                        >
                            {{ value }}
                        </a>
                        <span v-else>
                            {{ value }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </article>
</template>
