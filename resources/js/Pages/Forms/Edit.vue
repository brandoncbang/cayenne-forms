<script setup>
import CheckboxField from '@/Components/Dashboard/CheckboxField.vue';
import TextField from '@/Components/Dashboard/TextField.vue';
import CardsFormSection from '@/Components/Dashboard/CardsFormSection.vue';
import PrimaryButton from '@/Components/Dashboard/PrimaryButton.vue';
import CardsForm from '@/Components/Dashboard/CardsForm.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';
import CopyButton from '@/Components/Dashboard/CopyButton.vue';
import { getFormEmbedCode } from '@/helpers.js';

const props = defineProps({
    form: Object,
});

const form = useForm({
    name: props.form.name,
    success_url: props.form.success_url,
    sends_notifications: props.form.sends_notifications,
    honeypot_field: props.form.honeypot_field,
});

const submit = () => {
    form.patch(route('forms.update', { form: props.form }));
};

const confirmDeletion = () => {
    return confirm('Are you sure you want to permanently delete this form and all its entries?');
}
</script>

<template>
    <AuthenticatedLayout :title="`Edit &ldquo;${props.form.name}&rdquo;`">
        <template #actions>
            <Link
                :href="route('forms.entries.index', { form: props.form })"
                class="flex items-center justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
            >
                View entries
            </Link>
        </template>

        <CardsForm @submit.prevent="submit">
            <CardsFormSection title="Form Endpoint">
                <template #description>
                    <p>
                        To start receiving form entries, add the form's embed code to your website's HTML.
                    </p>
                </template>

                <div class="col-span-full">
                    <div class="flex justify-between items-end">
                        <p class="text-sm font-medium leading-6 text-gray-900">Embed Code</p>
                        <CopyButton
                            type="button"
                            class="rounded bg-white px-2 py-1 text-xs font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50"
                            :value="getFormEmbedCode(props.form)"
                        >
                            Copy<span class="sr-only">, form embed code</span>
                        </CopyButton>
                    </div>
                    <div class="mt-2">
                        <pre
                            class="overflow-x-auto rounded-md border-0 px-4 py-3.5 text-sm leading-6 font-mono text-gray-800 bg-gray-100"
                        ><code>{{ getFormEmbedCode(props.form) }}</code></pre>
                    </div>
                </div>
            </CardsFormSection>

            <CardsFormSection title="Form Overview" class="pt-10">
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

                <TextField
                    v-model="form.success_url"
                    id="success_url"
                    label="Success URL (optional)"
                    description="Users will be redirected to this URL after their form entry is received. A default page will be shown if this is left blank."
                    :error="form.errors.success_url"
                    placeholder="https://example.com/success.html"
                    autocomplete="off"
                />

                <CheckboxField
                    v-model="form.sends_notifications"
                    id="sends_notifications"
                    label="Send Notifications"
                    description="Get notified via email when a new form entry is submitted."
                    :error="form.errors.sends_notifications"
                />

                <template #actions>
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
                    <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Save
                    </PrimaryButton>
                </template>
            </CardsFormSection>

            <div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-10 md:grid-cols-3">
                <div class="px-4 sm:px-0">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Delete Form</h2>
                    <div class="mt-1 text-sm leading-6 text-gray-600">
                        <p>
                            This will permanently delete this form and all of its entries.
                            <strong class="text-gray-900">Proceed with caution!</strong>
                        </p>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <Link
                        as="button"
                        :href="route('forms.destroy', { form: props.form })"
                        method="DELETE"
                        class="inline-flex items-center justify-center rounded-md bg-rose-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-rose-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-rose-600"
                        type="button"
                        :onBefore="confirmDeletion"
                    >
                        Delete this form
                    </Link>
                </div>
            </div>
        </CardsForm>
    </AuthenticatedLayout>
</template>
