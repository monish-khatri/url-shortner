<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import Checkbox from '@/Components/Checkbox.vue';

defineProps({
    message: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    redirect_url: '',
    single_use: false,
    track_visits: false,
    is_active: true,
    activated_at: '',
    deactivated_at: '',
});

const submit = () => {
    form.post(route('short-urls.store'), {
        onSuccess: () => form.reset(),
    });
};

const validateDate = () => {
    const now = new Date().toISOString().split('T')[0];

    form.errors.activated_at = '';
    form.errors.deactivated_at = '';
    // Validate activated_at
    if (form.activated_at && form.activated_at < now) {
        form.errors.activated_at = 'The Activated date must be a date after or equal to Current date.';
    }

    // Validate deactivated_at
    if (form.deactivated_at) {
        if (form.deactivated_at < now) {
            form.errors.deactivated_at = 'The Expires date must be a date after or equal to Current date.';
        } else if (form.deactivated_at < form.activated_at) {
            form.errors.deactivated_at = 'The Expires date must be a date after or equal to Activated date.';
        }
    }
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Create" />
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div v-if="message" :class="{ 'text-green-600': status, 'text-red-600': !status }" class="mb-4 font-medium text-sm">
                    {{ message }}
                </div>
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <section>
                        <header>
                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Short URL</h2>

                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Configration Short URLs.
                            </p>
                        </header>
                        <div class="w-full flex">
                            <form @submit.prevent="submit" class="w-full mt-6 space-y-6">
                                <div class="w-full">
                                    <InputLabel for="redirect_url" value="URL" />

                                    <TextInput
                                        id="redirect_url"
                                        type="url"
                                        class="mt-1 block w-full"
                                        v-model="form.redirect_url"
                                        required
                                    />

                                    <InputError class="mt-2" :message="form.errors.redirect_url" />
                                </div>
                                <div class="w-full flex">
                                    <div class="mr-2 w-1/2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Activated At:</label>
                                        <TextInput
                                            type="date"
                                            id="activated_at"
                                            class="mt-1 block w-full"
                                            v-model="form.activated_at"
                                            @input="validateDate"
                                        />
                                        <InputError class="mt-2" :message="form.errors.activated_at" />
                                    </div>
                                    <div class="w-1/2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Expires At:</label>
                                        <TextInput
                                            type="date"
                                            id="deactivated_at"
                                            class="mt-1 block w-full"
                                            v-model="form.deactivated_at"
                                            @input="validateDate"
                                        />
                                        <InputError class="mt-2" :message="form.errors.deactivated_at" />
                                    </div>
                                </div>
                                <div class="mt-4 w-1/">
                                    <label class="flex items-center">
                                        <Checkbox name="is_active" v-model:checked="form.is_active" />
                                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Active?</span>
                                        <InputError class="mt-2" :message="form.errors.is_active" />
                                    </label>
                                </div>
                                <div class="mt-4 w-1/4">
                                    <label class="flex items-center">
                                        <Checkbox name="single_use" v-model:checked="form.single_use" />
                                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Single Use?</span>
                                        <InputError class="mt-2" :message="form.errors.single_use" />
                                    </label>
                                </div>
                                <div class="mt-4 w-1/4">
                                    <label class="flex items-center">
                                        <Checkbox name="track_visits" v-model:checked="form.track_visits" />
                                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Track Visits?</span>
                                        <InputError class="mt-2" :message="form.errors.track_visits" />
                                    </label>
                                </div>

                                <div class="flex items-center gap-4">
                                    <PrimaryButton :disabled="form.processing">Create</PrimaryButton>

                                    <Transition
                                        enter-active-class="transition ease-in-out"
                                        enter-from-class="opacity-0"
                                        leave-active-class="transition ease-in-out"
                                        leave-to-class="opacity-0"
                                    >
                                        <p v-if="form.recentlySuccessful" class="text-sm text-gray-600 dark:text-gray-400">Saved.</p>
                                    </Transition>
                                </div>
                            </form>
                        </div>

                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
