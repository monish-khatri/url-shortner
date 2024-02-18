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

const props = defineProps({
  shortUrl: {
    type: Array,
    required: true
  },
  visits: {
    type: Array,
    required: true
  }
});
</script>

<template>
    <Head title="Short URls" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Short URLs Visits: <code class="text-gray-500"><a :href="shortUrl.data.short_url" target="_blank">{{ shortUrl.data.short_url }} </a></code></h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Sr. No</TableHead>
                                <TableHead>Device Type</TableHead>
                                <TableHead>IP Address</TableHead>
                                <TableHead>Operating System Info</TableHead>
                                <TableHead>Browser Info</TableHead>
                                <TableHead>Referer URL</TableHead>
                                <TableHead>Visited At</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow class="bg-white dark:bg-gray-600" v-if="visits.data.length > 0" v-for="(visit, index) in visits.data" :key="visit.id">
                                <TableCell>{{ index+1 }}</TableCell>
                                <TableCell>
                                    {{ visit.device_type }}
                                </TableCell>
                                <TableCell>
                                    {{ visit.ip_address }}
                                </TableCell>
                                <TableCell>
                                    {{ visit.operating_system }} - {{ visit.operating_system_version }}
                                </TableCell>
                                <TableCell>
                                    {{ visit.browser }} - {{ visit.browser_version }}
                                </TableCell>
                                <TableCell>
                                    {{ visit.referer_url }}
                                </TableCell>
                                <TableCell>
                                    {{ visit.visited_at }}
                                </TableCell>
                            </TableRow>
                            <TableRow class="bg-white dark:bg-gray-600" v-else>
                                <TableCell colspan="7">No Visits Found</TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
