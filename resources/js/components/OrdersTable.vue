<template>
    <BaseCard>
        <CardHeader label="Orders" title="Past & Open" badge="History" />

        <div class="mt-4 overflow-hidden rounded-xl ring-1 ring-slate-800">
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-900 text-slate-300">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">
                            Symbol
                        </th>
                        <th class="px-4 py-3 text-left font-semibold">Side</th>
                        <th class="px-4 py-3 text-left font-semibold">Price</th>
                        <th class="px-4 py-3 text-left font-semibold">
                            Amount
                        </th>
                        <th class="px-4 py-3 text-left font-semibold">
                            Status
                        </th>
                        <th class="px-4 py-3 text-left font-semibold">Date</th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-slate-800 bg-slate-900/60 text-slate-200"
                >
                    <OrderRow
                        v-for="order in orders"
                        :key="order.id"
                        :order="order"
                    />
                </tbody>
            </table>
        </div>
    </BaseCard>
</template>

<script setup>
import BaseCard from './BaseCard.vue';
import CardHeader from './CardHeader.vue';
import OrderRow from './OrderRow.vue';

defineProps({
    orders: {
        type: Array,
        default: () => [
            {
                id: 1,
                symbol: 'BTC',
                side: 'Buy',
                price: '$42,120',
                amount: '0.25',
                status: 'Open',
                date: '2025-12-11',
            },
            {
                id: 2,
                symbol: 'ETH',
                side: 'Sell',
                price: '$2,320',
                amount: '1.20',
                status: 'Filled',
                date: '2025-12-10',
            },
            {
                id: 3,
                symbol: 'BTC',
                side: 'Sell',
                price: '$41,800',
                amount: '0.10',
                status: 'Cancelled',
                date: '2025-12-08',
            },
        ],
    },
});
</script>
