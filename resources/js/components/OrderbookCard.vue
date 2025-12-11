<template>
    <BaseCard>
        <CardHeader label="Orderbook" :title="`${symbol}/USD`" badge="Depth" />

        <div class="mt-4 space-y-3">
            <OrderbookSection label="Asks" :items="orderbook.asks" type="ask" />

            <OrderbookSection label="Bids" :items="orderbook.bids" type="bid" />
        </div>
    </BaseCard>
</template>

<script setup>
import BaseCard from './BaseCard.vue';
import CardHeader from './CardHeader.vue';
import OrderbookSection from './OrderbookSection.vue';

defineProps({
    symbol: {
        type: String,
        default: 'BTC',
    },
    orderbook: {
        type: Object,
        default: () => ({
            asks: [
                { price: '$42,150', amount: '0.40' },
                { price: '$42,140', amount: '0.25' },
                { price: '$42,130', amount: '0.18' },
            ],
            bids: [
                { price: '$42,120', amount: '0.32' },
                { price: '$42,110', amount: '0.28' },
                { price: '$42,100', amount: '0.35' },
            ],
        }),
    },
});
</script>
