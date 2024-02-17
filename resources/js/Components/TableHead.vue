<script setup>
import { defineProps,computed } from 'vue';

const props = defineProps({
    class: {
        type: String,
    },
});

const contentClasses = computed(() => {
    return [
        'p-2',
        props.class
    ];
});
</script>

<template>
    <th :class="contentClasses.join(' ')">
            <slot></slot>
    </th>
</template>
