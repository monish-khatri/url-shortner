<script setup>
import { Inertia } from "@inertiajs/inertia";

const props = defineProps({
    bordered: {
        type: Boolean,
        default: true,
    },
    pagination: {
        type: Object,
        required: true
    },
    offset: {
        type: Number,
        default: 5
    },
    tab: {
        default: 5
    },
});
const fetchResults = (page) => {
  if (! page.active) {
    let url =page.url
    if (props.tab != null){
        url = page.url + '&type=' + props.tab
    }
    Inertia.visit(url, {
        method: 'GET',
        preserveScroll: true,
        replace: true,
      })
  }
}
</script>
<template>
<div class="flex justify-between mt-2 text-center text-sm-start">
  <div class="py-4 px-4 col-sm">
    <div class="text-muted">
      <span class="text-sm text-gray-700 dark:text-gray-400">
        Showing <span class="font-semibold text-gray-900 dark:text-white">{{ props.pagination.from }}</span> to <span class="font-semibold text-gray-900 dark:text-white">{{ props.pagination.to }}</span> of <span class="font-semibold text-gray-900 dark:text-white">{{ props.pagination.total }}</span> Entries
      </span>
    </div>
  </div>
  <div class="px-4 py-4 col-sm-auto">
    <ul class="flex items-center -space-x-px h-8 text-sm">
      <template v-for="(page,index) in props.pagination.links" :key="index">
        <li :class="{ 'active' : page.active }">
          <a
            v-if="page.url"
            @click="fetchResults(page)"
            href="javascript:void(0)"
            :class="{
                'flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !page.active,
                'z-10 flex items-center justify-center px-3 h-8 leading-tight text-blue-600 border border-blue-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 dark:border-gray-700 dark:bg-gray-700 dark:text-white': !page.active
            }"
            class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">{{ page.label }}</a>
        </li>
      </template>
    </ul>
  </div>
</div>
</template>
