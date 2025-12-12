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

        <div v-else-if="items.length">
            <div
                class="flex items-center justify-between rounded-lg pb-1 text-sm"
            >
                <span>Price</span>
                <span :class="textColor">Amount</span>
            </div>
            <div class="space-y-2">
                <div
                    v-for="item in items"
                    :key="item.price"
                    class="flex items-center justify-between rounded-lg px-3 text-sm"
                    :class="[
                        bgColor,
                        item.user_id === myUserId
                            ? 'py-3! ring-1 ring-indigo-500/70'
                            : 'py-2 ring-1 ring-slate-800',
                    ]"
                >
                    <span class="flex items-center gap-2">
                        <span>{{ item.price }}</span>
                        <span
                            v-if="item.user_id === myUserId"
                            class="inline-flex items-center rounded-full bg-indigo-600/20 px-2 py-0.5 text-[10px] font-semibold text-indigo-200 ring-1 ring-indigo-500/40"
                        >
                            Mine
                        </span>
                    </span>
                    <span :class="textColor">{{ item.amount }}</span>
                </div>
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
import { computed } from 'vue';
import { useAuth } from '../composables/useAuth';

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

const { user } = useAuth();
const myUserId = computed(() => user.value?.id ?? null);
</script>
