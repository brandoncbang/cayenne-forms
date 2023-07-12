<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { useForm } from '@inertiajs/vue3';
import TextField from '@/Components/Dashboard/TextField.vue';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout title="Reset password">
        <form @submit.prevent="submit" class="space-y-6">
            <TextField
                id="email"
                v-model="form.email"
                type="email"
                label="Email address"
                :error="form.errors.email"
                autofocus
                autocomplete="username"
                required
            />

            <TextField
                id="password"
                v-model="form.password"
                type="password"
                label="Password"
                :error="form.errors.password"
                autocomplete="new-password"
                required
            />

            <TextField
                id="password_confirmation"
                v-model="form.password_confirmation"
                type="password"
                label="Confirm password"
                :error="form.errors.password_confirmation"
                autocomplete="new-password"
                required
            />

            <div>
                <button
                    type="submit"
                    class="flex w-full justify-center rounded-md bg-indigo-600 mt-10 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Reset password
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
