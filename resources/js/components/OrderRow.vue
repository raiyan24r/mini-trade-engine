<template>
    <tr>
        <td class="px-4 py-3 text-slate-400">#{{ order.id }}</td>
        <td class="px-4 py-3 font-semibold text-white">
            {{ order.symbol }}
        </td>
        <td class="px-4 py-3">
            <span
                :class="[
                    'rounded-full px-2.5 py-1 text-xs font-semibold',
                    order.side === 'Buy'
                        ? 'bg-emerald-600/20 text-emerald-200 ring-1 ring-emerald-500/40'
                        : 'bg-rose-600/20 text-rose-200 ring-1 ring-rose-500/40',
                ]"
            >
                {{ order.side }}
            </span>
        </td>
        <td class="px-4 py-3 text-slate-200">{{ order.price }}</td>
        <td class="px-4 py-3 text-slate-200">{{ order.amount }}</td>
        <td class="relative px-4 py-3">
            <div
                v-if="order.status === 'Cancelled' && order.cancelled_at"
                class="group inline-block"
            >
                <span
                    :class="statusBadge(order.status)"
                    class="cursor-help rounded-full px-2.5 py-1 text-xs font-semibold"
                >
                    {{ order.status }}
                </span>
                <div
                    class="invisible absolute bottom-full left-1/2 z-10 mb-2 -translate-x-1/2 rounded bg-slate-800 px-2 py-1 text-xs whitespace-nowrap text-slate-100 ring-1 ring-slate-700 group-hover:visible"
                >
                    Cancelled at: {{ order.cancelled_at }}
                </div>
            </div>
            <span
                v-else
                :class="statusBadge(order.status)"
                class="rounded-full px-2.5 py-1 text-xs font-semibold"
            >
                {{ order.status }}
            </span>
        </td>
        <td class="px-4 py-3 text-slate-400">{{ order.date }}</td>
        <td class="px-4 py-3">
            <button
                v-if="order.status === 'Open'"
                class="rounded-lg bg-rose-600/20 px-3 py-1 text-xs font-semibold text-rose-200 ring-1 ring-rose-500/40 transition-colors hover:bg-rose-600/30"
                @click="$emit('cancel', order.id)"
            >
                Cancel
            </button>
            <span v-else class="text-slate-500">—</span>
        </td>
    </tr>
</template>

<script setup>
defineProps({
    order: {
        type: Object,
        default: () => ({}),
    },
});

const statusBadge = (status) => {
    if (status === 'Open') {
        return 'bg-amber-500/20 text-amber-200 ring-1 ring-amber-500/40';
    }
    if (status === 'Filled') {
        return 'bg-emerald-500/20 text-emerald-200 ring-1 ring-emerald-500/40';
    }
    return 'bg-slate-500/20 text-slate-200 ring-1 ring-slate-500/40';
};
</script>
