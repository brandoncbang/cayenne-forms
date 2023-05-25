<script setup>
import {computed} from "vue";

const props = defineProps({
    user: Object,
});

const initials = computed(() => {
    const names = props.user.name.split(' ').filter(name => name !== '');

    if (names.length > 1) {
        return (names[0][0] + names[names.length - 1][0]).toUpperCase();
    }

    return names[0].slice(0, 2).toUpperCase();
});
</script>

<template>
    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-gray-500">
        <span class="font-medium leading-none text-white">{{ initials }}</span>
    </span>
</template>
