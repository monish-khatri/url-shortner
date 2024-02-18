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
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Create" />

        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Create Short URL</h2>
        </template>

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
                                <div class="mt-4 w-1/4">
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
                                    <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

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
