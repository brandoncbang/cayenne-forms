<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { InformationCircleIcon } from '@heroicons/vue/20/solid';
import { Link, useForm } from '@inertiajs/vue3';
import TextField from '@/Components/Dashboard/TextField.vue';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout title="Sign in to your account">
        <div v-if="status" class="rounded-md bg-blue-50 p-4 mb-10">
            <div class="flex">
                <div class="flex-shrink-0">
                    <InformationCircleIcon class="h-5 w-5 text-blue-400" aria-hidden="true" />
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm text-blue-700">{{ status }}</p>
                </div>
            </div>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
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

            <TextField
                id="password"
                v-model="form.password"
                type="password"
                label="Password"
                :error="form.errors.password"
                autocomplete="current-password"
                required
            />

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input
                        v-model="form.remember"
                        id="remember-me"
                        name="remember-me"
                        type="checkbox"
                        class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-600"
                    />
                    <label for="remember-me" class="ml-3 block text-sm leading-6 text-gray-900">
                        Remember me
                    </label>
                </div>

                <div class="text-sm leading-6">
                    <Link :href="route('password.request')" class="font-semibold text-indigo-600 hover:text-indigo-500">
                        Forgot password?
                    </Link>
                </div>
            </div>

            <div>
                <button
                    type="submit"
                    class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Sign in
                </button>
            </div>
        </form>
    </GuestLayout>
</template>
