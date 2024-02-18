<script setup>
import { defineProps,computed } from 'vue';

const props = defineProps({
    class: {
        type: String,
    },
});

const contentClasses = computed(() => {
    return [
        'text-left', // Default class
        'text-white',
        'dark:text-gray-300',
        props.class ? props.class : '' // User-given class (if provided)
    ];
});

</script>

<template>
    <tr :class="contentClasses.join(' ')">
            <slot></slot>
    </tr>
</template>
