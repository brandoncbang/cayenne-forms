<script>
export default {
    inheritAttrs: false,
};
</script>

<script setup>
import { useAttrs } from "vue";
import { ExclamationCircleIcon } from '@heroicons/vue/20/solid';

const props = defineProps({
    id: String,
    type: {
        type: String,
        default: 'text',
    },
    label: String,
    description: String,
    error: String,
    modelValue: String,
});

defineEmits(['update:modelValue']);

const attrs = useAttrs();
</script>

<template>
    <div class="col-span-full" :class="attrs.class">
        <label :for="id" class="block text-sm font-medium leading-6 text-gray-900">{{ label }}</label>
        <div class="mt-2" :class="{ 'relative rounded-md shadow-sm': error }">
            <input
                :id="id"
                :type="type"
                :name="id"
                v-bind="{ ...attrs, class: null }"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                :class="{ 'pr-10 text-red-900 ring-red-300 placeholder:text-red-300 focus:ring-red-500': error }"
                :aria-describedby="`${id}-description`"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            />
            <div v-if="error" class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                <ExclamationCircleIcon class="h-5 w-5 text-red-500" aria-hidden="true" />
            </div>
        </div>
        <div v-if="error || description" :id="`${id}-info`">
            <p v-if="error" class="mt-2 text-sm text-red-600">{{ error }}</p>
            <p v-if="description" class="mt-2 text-sm text-gray-500">{{ description }}</p>
        </div>
    </div>
</template>
