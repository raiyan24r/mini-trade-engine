<template>
    <div>
        <p class="mb-2 text-xs tracking-wide uppercase" :class="labelColor">
            {{ label }}
        </p>
        <div
            v-if="loading"
            class="rounded-lg bg-slate-900 px-3 py-4 text-center text-sm text-slate-400 ring-1 ring-slate-800"
        >
            Loading...
        </div>
        <div v-else-if="items.length" class="space-y-2">
            <div
                v-for="item in items"
                :key="item.price"
                class="flex items-center justify-between rounded-lg px-3 py-2 text-sm"
                :class="bgColor"
            >
                <span>{{ item.price }}</span>
                <span :class="textColor">{{ item.amount }}</span>
            </div>
        </div>
        <div
            v-else
            class="rounded-lg bg-slate-900 px-3 py-4 text-center text-sm text-slate-400 ring-1 ring-slate-800"
        >
            No {{ label.toLowerCase() }} data
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    label: {
        type: String,
        default: '',
    },
    items: {
        type: Array,
        default: () => [],
    },
    loading: {
        type: Boolean,
        default: false,
    },
    type: {
        type: String,
        enum: ['ask', 'bid'],
        default: 'ask',
    },
});

const colorMap = {
    ask: {
        bg: 'bg-rose-500/10 text-rose-100',
        label: 'text-rose-300',
        text: 'text-rose-200',
    },
    bid: {
        bg: 'bg-emerald-500/10 text-emerald-100',
        label: 'text-emerald-300',
        text: 'text-emerald-200',
    },
};

const bgColor = colorMap[props.type].bg;
const labelColor = colorMap[props.type].label;
const textColor = colorMap[props.type].text;
</script>
