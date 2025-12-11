<template>
    <div
        class="rounded-2xl bg-slate-900/70 p-6 shadow-lg ring-1 ring-slate-800"
    >
        <div class="flex items-center justify-between">
            <p class="text-sm tracking-wide text-slate-400 uppercase">
                {{ label }}
            </p>
            <span
                :class="badgeColor"
                class="rounded-full px-3 py-1 text-xs font-semibold"
            >
                {{ tag }}
            </span>
        </div>
        <p class="mt-3 text-3xl font-semibold text-white">
            {{ value }}
        </p>
        <p class="text-sm text-slate-400">{{ subtext }}</p>
    </div>
</template>

<script setup>
defineProps({
    label: String,
    value: String,
    subtext: String,
    tag: String,
    badgeColor: String,
});
</script>
