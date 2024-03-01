<script setup>
import Table from '@/Components/Table.vue';
import TableBody from '@/Components/TableBody.vue';
import TableCell from '@/Components/TableCell.vue';
import TableHeader from '@/Components/TableHeader.vue';
import TableHead from '@/Components/TableHead.vue';
import TableRow from '@/Components/TableRow.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head , Link} from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import Pagination from '@/Components/Pagination.vue';
import EyeIcon from '@/Icons/EyeIcon.vue';
import CopyIcon from '@/Icons/CopyIcon.vue';
const props = defineProps({
  shortUrls: {
    type: Object,
    required: true
  }
});

const copyUrlToClipboard = async (url) => {
  try {
    await navigator.clipboard.writeText(url);
    console.log('URL copied:', url);
  } catch (error) {
    console.error('Failed to copy URL:', error);
  }
};
</script>

<template>
    <Head title="Short URls" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow-sm sm:rounded-lg">
                    <NavLink class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mb-4 float-right" :href="route('short-urls.create')">Add Url</NavLink>
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
                                <TableCell class="flex">
                                    <a :href="shortUrl.short_url" target="_blank" class="mr-2">{{ shortUrl.short_url }}</a>
                                    <a href="javascript:void(0)" class="mr-2">
                                        <CopyIcon @click="copyUrlToClipboard(shortUrl.short_url)" ></CopyIcon>
                                    </a>
                                </TableCell>
                                <TableCell>
                                    <a :href="shortUrl.redirect_url" target="_blank">{{ shortUrl.redirect_url }} </a>
                                </TableCell>
                                <TableCell>
                                    {{ shortUrl.clicks }}
                                </TableCell>
                                <TableCell>
                                    <a :href="route('short-urls.show', {short_url: shortUrl.code})" target="_blank"><EyeIcon></EyeIcon></a>
                                </TableCell>
                            </TableRow>
                            <TableRow class="bg-white dark:bg-gray-600 text-center" v-else>
                                <TableCell colspan="5">No URLs Found</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                    <Pagination :pagination="shortUrls.meta"></Pagination>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
