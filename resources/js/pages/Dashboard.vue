<template>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <div class="mx-auto max-w-6xl px-4 py-10">
            <!-- Header -->
            <DashboardHeader
                :user-name="user?.name || 'User'"
                :subtitle="user?.email || 'Signed in user'"
                :is-loading="isLoading"
                @logout="handleLogout"
            />

            <!-- Balance card -->
            <section class="mb-8">
                <div
                    class="rounded-2xl bg-slate-900/70 p-6 shadow-lg ring-1 ring-slate-800"
                >
                    <div class="grid grid-cols-3 gap-4">
                        <div
                            v-for="balance in balances"
                            :key="balance.label"
                            class="text-center"
                        >
                            <span
                                :class="balance.badgeColor"
                                class="inline-block rounded-full px-3 py-1 text-xs font-semibold"
                            >
                                {{ balance.tag }}
                            </span>
                            <p class="mt-3 text-2xl font-semibold text-white">
                                {{ balance.value }}
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Limit order form -->
                <section class="lg:col-span-2">
                    <LimitOrderForm />
                </section>

                <!-- Orderbook -->
                <section>
                    <OrderbookCard
                        :symbol="selectedSymbol"
                        :orderbook="orderbook"
                    />
                </section>
            </div>

            <!-- Orders and wallet overview -->
            <section class="mt-8 space-y-6">
                <WalletOverview :assets="wallet" />
                <OrdersTable :orders="orders" />
            </section>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';
import LimitOrderForm from '../components/LimitOrderForm.vue';
import OrderbookCard from '../components/OrderbookCard.vue';
import WalletOverview from '../components/WalletOverview.vue';
import OrdersTable from '../components/OrdersTable.vue';
import DashboardHeader from '../components/DashboardHeader.vue';

const router = useRouter();
const { user, isLoading, logout, fetchUser } = useAuth();

const selectedSymbol = ref('BTC');

const balances = [
    {
        label: 'USD Balance',
        value: '$25,430.00',
        subtext: 'Available to trade',
        tag: 'USD',
        badgeColor:
            'bg-indigo-600/20 text-indigo-200 ring-1 ring-indigo-500/40',
    },
    {
        label: 'BTC Holdings',
        value: '0.8421 BTC',
        subtext: 'Long exposure',
        tag: 'BTC',
        badgeColor: 'bg-amber-600/20 text-amber-200 ring-1 ring-amber-500/40',
    },
    {
        label: 'ETH Holdings',
        value: '6.35 ETH',
        subtext: 'Staked + spot',
        tag: 'ETH',
        badgeColor:
            'bg-emerald-600/20 text-emerald-200 ring-1 ring-emerald-500/40',
    },
];

const wallet = [
    { label: 'USD', amount: '$12,500.00' },
    { label: 'BTC', amount: '0.5400' },
    { label: 'ETH', amount: '3.2000' },
    { label: 'USDT', amount: '5,000.00' },
    { label: 'SOL', amount: '28.4' },
    { label: 'XRP', amount: '320.0' },
];

const orders = [
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
];

const orderbook = {
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
};

onMounted(() => {
    fetchUser();
});

const handleLogout = async () => {
    await logout();
    router.push('/login');
};
</script>

<style scoped>
:global(body) {
    background-color: #0f172a;
}
</style>
