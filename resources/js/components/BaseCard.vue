<template>
    <div
        :class="['rounded-2xl shadow-lg ring-1', bgClass, 'p-6 ring-slate-800']"
    >
        <slot />
    </div>
</template>

<script setup>
defineProps({
    bgClass: {
        type: String,
        default: 'bg-slate-900/80',
    },
});
</script>
