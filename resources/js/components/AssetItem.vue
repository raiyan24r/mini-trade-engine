<template>
    <div
        class="rounded-xl bg-slate-900 px-4 py-4 text-sm text-slate-200 ring-1 ring-slate-800"
    >
        <div class="flex items-center justify-between">
            <span class="text-xs tracking-wide text-slate-500 uppercase">
                {{ label }}
            </span>
            <span class="text-slate-300">{{ amount }}</span>
        </div>
        <p class="text-xs text-slate-500">{{ subtext }}</p>
    </div>
</template>

<script setup>
defineProps({
    label: {
        type: String,
        default: '',
    },
    amount: {
        type: String,
        default: '',
    },
    subtext: {
        type: String,
        default: 'Holdings',
    },
});
</script>
