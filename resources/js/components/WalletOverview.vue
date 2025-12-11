<template>
    <BaseCard>
        <CardHeader
            label="Wallet Overview"
            title="USD & Assets"
            badge="/api/profile"
        />

        <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
            <AssetItem
                v-for="asset in assets"
                :key="asset.label"
                :label="asset.label"
                :amount="asset.amount"
            />
        </div>
    </BaseCard>
</template>

<script setup>
import BaseCard from './BaseCard.vue';
import CardHeader from './CardHeader.vue';
import AssetItem from './AssetItem.vue';

defineProps({
    assets: {
        type: Array,
        default: () => [
            { label: 'USD', amount: '$12,500.00' },
            { label: 'BTC', amount: '0.5400' },
            { label: 'ETH', amount: '3.2000' },
            { label: 'USDT', amount: '5,000.00' },
            { label: 'SOL', amount: '28.4' },
            { label: 'XRP', amount: '320.0' },
        ],
    },
});
</script>
