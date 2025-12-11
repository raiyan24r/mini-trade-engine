<template>
    <div>
        <p class="mb-2 text-xs tracking-wide uppercase" :class="labelColor">
            {{ label }}
        </p>
        <div class="space-y-2">
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
    </div>
</template>

<script setup>
const props = defineProps({
    label: String,
    items: Array,
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
