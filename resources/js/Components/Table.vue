 
<script setup>
import { defineProps,computed } from 'vue';

const props = defineProps({
    class: {
        type: String,
    },
});

const contentClasses = computed(() => {
    return [
        'w-full',
        'table-auto',
        'border-collapse',
        props.class
    ];
});
</script>

<template>
    <table :class="contentClasses.join(' ')">
            <slot></slot>
    </table>
</template>
