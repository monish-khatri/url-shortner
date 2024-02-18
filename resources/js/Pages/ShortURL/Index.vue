<script setup>
import Table from '@/Components/Table.vue';
import TableBody from '@/Components/TableBody.vue';
import TableCell from '@/Components/TableCell.vue';
import TableHeader from '@/Components/TableHeader.vue';
import TableHead from '@/Components/TableHead.vue';
import TableRow from '@/Components/TableRow.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head , Link} from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import NavLink from '@/Components/NavLink.vue';
import Pagination from '@/Components/Pagination.vue';
const props = defineProps({
  shortUrls: {
    type: Object,
    required: true
  }
});
</script>

<template>
    <Head title="Short URls" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Short URLs</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <NavLink class="mb-4 float-right" :href="route('short-urls.create')">Add Url</NavLink>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Sr. No</TableHead>
                                <TableHead>Short URL</TableHead>
                                <TableHead>Orginial URL</TableHead>
                                <TableHead>Clicks</TableHead>
                                <TableHead>Action</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow class="bg-white dark:bg-gray-600" v-if="shortUrls.data.length > 0" v-for="(shortUrl, index) in shortUrls.data" :key="shortUrl.id">
                                <TableCell>{{ index+1 }}</TableCell>
                                <TableCell>
                                    <a :href="shortUrl.short_url" target="_blank">{{ shortUrl.short_url }} </a>
                                </TableCell>
                                <TableCell>
                                    <a :href="shortUrl.redirect_url" target="_blank">{{ shortUrl.redirect_url }} </a>
                                </TableCell>
                                <TableCell>
                                    {{ shortUrl.clicks }}
                                </TableCell>
                                <TableCell>
                                    <a :href="route('short-urls.show', {short_url: shortUrl.code})" target="_blank">View</a>
                                </TableCell>
                            </TableRow>
                            <TableRow class="bg-white dark:bg-gray-600" v-else>
                                <TableCell colspan="4">No URLs Found</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <Pagination :pagination="shortUrls.meta"></Pagination>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
