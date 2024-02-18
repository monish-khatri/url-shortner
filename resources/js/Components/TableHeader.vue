<script setup>
import { defineProps,computed } from 'vue';

const props = defineProps({
    class: {
        type: String,
    },
});

const contentClasses = computed(() => {
    return [
        'text-left',
        'bg-gray-200',
        'dark:bg-gray-700',
        props.class
    ];
});
</script>

<template>
    <thead :class="contentClasses.join(' ')">
        <slot></slot>
    </thead>
</template>
