<template>
    <div class="mb-4 flex items-center justify-between">
        <div>
            <p class="text-sm tracking-wide text-slate-400 uppercase">
                {{ label }}
            </p>
            <h2 :class="[titleClass]">
                {{ title }}
            </h2>
        </div>
        <span
            class="rounded-full bg-slate-800 px-3 py-1 text-xs text-slate-300"
        >
            {{ badge }}
        </span>
    </div>
</template>

<script setup>
defineProps({
    label: String,
    title: String,
    badge: String,
    titleClass: {
        type: String,
        default: 'text-xl font-semibold text-white',
    },
});
</script>
