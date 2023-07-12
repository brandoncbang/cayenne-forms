<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import CardsForm from '@/Components/Dashboard/CardsForm.vue';
import CardsFormSection from '@/Components/Dashboard/CardsFormSection.vue';
import PrimaryButton from '@/Components/Dashboard/PrimaryButton.vue';
import TextField from '@/Components/Dashboard/TextField.vue';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <CardsForm @submit.prevent="updatePassword">
        <CardsFormSection title="Update Password">
            <template #description>
                <p>
                    Ensure your account is using a long, random password to stay secure.
                </p>
            </template>

            <TextField
                ref="currentPasswordInput"
                v-model="form.current_password"
                id="current_password"
                type="password"
                label="Current password"
                :error="form.errors.current_password"
                autocomplete="current-password"
            />

            <TextField
                ref="passwordInput"
                v-model="form.password"
                id="password"
                type="password"
                label="New password"
                :error="form.errors.password"
                autocomplete="new-password"
            />

            <TextField
                v-model="form.password_confirmation"
                id="password_confirmation"
                type="password"
                label="Confirm password"
                :error="form.errors.password_confirmation"
                autocomplete="new-password"
            />

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
