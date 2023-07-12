<script setup>
import { Link, useForm, usePage } from '@inertiajs/vue3';
import CardsForm from '@/Components/Dashboard/CardsForm.vue';
import CardsFormSection from '@/Components/Dashboard/CardsFormSection.vue';
import PrimaryButton from '@/Components/Dashboard/PrimaryButton.vue';
import TextField from '@/Components/Dashboard/TextField.vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <CardsForm @submit.prevent="form.patch(route('profile.update'))">
        <CardsFormSection title="Profile Information">
            <template #description>
                <p>
                    Update your account's profile information and email address.
                </p>
            </template>

            <TextField
                v-model="form.name"
                id="name"
                label="Name"
                :error="form.errors.name"
                autocomplete="name"
                required
            />

            <TextField
                v-model="form.email"
                id="email"
                type="email"
                label="Email address"
                :error="form.errors.email"
                autocomplete="name"
                required
            />

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800 dark:text-gray-200">
                    Your email address is unverified.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600 dark:text-green-400"
                >
                    A new verification link has been sent to your email address.
                </div>
            </div>

            <template #actions>
                <Transition enter-from-class="opacity-0" leave-to-class="opacity-0" class="transition ease-in-out">
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                </Transition>

                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Save
                </PrimaryButton>
            </template>
        </CardsFormSection>
    </CardsForm>
</template>
