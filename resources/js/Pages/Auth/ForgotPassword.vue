<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { CheckCircleIcon } from '@heroicons/vue/20/solid/index.js';
import TextField from '@/Components/Dashboard/TextField.vue';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout title="Forgot password">
        <div v-if="status" class="rounded-md bg-green-50 p-4 mb-10">
            <div class="flex">
                <div class="flex-shrink-0">
                    <CheckCircleIcon class="h-5 w-5 text-green-400" aria-hidden="true" />
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm text-green-700">{{ status }}</p>
                </div>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <p class="text-sm leading-6 text-gray-600">
                Forgot your password? No problem. Just let us know your email address and we will email you a password reset
                link that will allow you to choose a new one.
            </p>

            <TextField
                id="email"
                v-model="form.email"
                type="email"
                label="Email address"
                :error="form.errors.email"
                autocomplete="username"
                autofocus
                required
            />

            <div>
                <button
                    type="submit"
                    class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Email password reset link
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
