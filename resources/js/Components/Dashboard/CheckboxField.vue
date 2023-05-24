<script>
export default {
    inheritAttrs: false,
};
</script>

<script setup>
import { useAttrs } from "vue";

const props = defineProps({
    id: String,
    label: String,
    description: String,
    error: String,
    modelValue: Boolean,
});

defineEmits(['update:modelValue']);

const attrs = useAttrs();
</script>

<template>
    <div class="col-span-full" :class="attrs.class">
        <div class="relative flex items-start">
            <div class="flex h-6 items-center">
                <input
                    :id="id"
                    :aria-describedby="`${id}-description`"
                    :name="id"
                    v-bind="{ ...attrs, class: null }"
                    type="checkbox"
                    class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                    :checked="modelValue"
                    @input="$emit('update:modelValue', $event.target.checked)"
                />
            </div>
            <div class="ml-3 text-sm leading-6">
                <label :for="id" class="font-medium text-gray-900">
                    {{ label }}
                </label>
                <p v-if="description" :id="`${id}-description`" class="text-gray-500">
                    {{ description }}
                </p>
            </div>
        </div>
    </div>
</template>
