<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import CardsForm from "@/Components/Dashboard/CardsForm.vue";
import CardsFormSection from "@/Components/Dashboard/CardsFormSection.vue";
import TextField from "@/Components/Dashboard/TextField.vue";
import CheckboxField from "@/Components/Dashboard/CheckboxField.vue";
import PrimaryButton from "@/Components/Dashboard/PrimaryButton.vue";

const form = useForm({
    name: '',
    sends_notifications: true,
    honeypot_field: '',
});

const submit = () => {
    form.post(route('forms.store'));
};
</script>

<template>
    <AuthenticatedLayout title="Create a Form">
        <CardsForm @submit.prevent="submit">
            <CardsFormSection title="Form Overview">
                <template #description>
                    <p>
                        Creating a form will give you an endpoint that lets you receive and view form entries from your
                        website.
                    </p>
                </template>

                <TextField
                    v-model="form.name"
                    id="name"
                    label="Name"
                    :error="form.errors.name"
                    autocomplete="off"
                    required
                />

                <CheckboxField
                    v-model="form.sends_notifications"
                    id="sends_notifications"
                    label="Send Notifications"
                    description="Get notified via email when a new form entry is submitted."
                    :error="form.errors.sends_notifications"
                    required
                />

                <template #actions>
                    <Link :href="route('forms.index')" class="text-sm font-semibold leading-6 text-gray-900">
                        Cancel
                    </Link>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Save
                    </PrimaryButton>
                </template>
            </CardsFormSection>

            <CardsFormSection title="Advanced" class="pt-10">
                <template #description>
                    <p>Set up more advanced form settings, such as spam management.</p>
                </template>

                <TextField
                    v-model="form.honeypot_field"
                    id="honeypot_field"
                    label="Honeypot Field (optional)"
                    description="A form entry with this field filled out will be automatically marked as spam."
                    :error="form.errors.honeypot_field"
                    autocomplete="off"
                />

                <template #actions>
                    <Link :href="route('forms.index')" class="text-sm font-semibold leading-6 text-gray-900">
                        Cancel
                    </Link>
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Save
                    </PrimaryButton>
                </template>
            </CardsFormSection>
        </CardsForm>
    </AuthenticatedLayout>
</template>
