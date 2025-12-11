<template>
    <tr>
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
        <td class="px-4 py-3">
            <span
                :class="statusBadge(order.status)"
                class="rounded-full px-2.5 py-1 text-xs font-semibold"
            >
                {{ order.status }}
            </span>
        </td>
        <td class="px-4 py-3 text-slate-400">{{ order.date }}</td>
    </tr>
</template>

<script setup>
defineProps({
    order: Object,
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
