<script setup>
import { computed, ref, watchEffect } from "vue";

defineProps({
    value: String,
    copiedMessage: {
        type: String,
        default: 'Copied!',
    },
});

const copyCount = ref(0);

const copied = computed(() => {
    return copyCount.value > 0;
});

watchEffect((onCleanup) => {
    if (copyCount.value > 0) {
        onCleanup(() => clearTimeout(timeout));
        let timeout = setTimeout(() => copyCount.value = 0, 1000);
    }
});

const copy = (text) => {
    window.navigator.clipboard.writeText(text).then(() => {
        copyCount.value += 1;
    });
};
</script>

<template>
    <button type="button" @click="copy(value)">
        <span v-show="!copied" :aria-hidden="copied">
            <slot />
        </span>
        <span v-show="copied" :aria-hidden="!copied">
            {{ copiedMessage }}
        </span>
    </button>
</template>
