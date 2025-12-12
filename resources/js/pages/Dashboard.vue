<template>
    <div class="min-h-screen bg-slate-950 text-slate-100">
        <ToastContainer />
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
                    <LimitOrderForm @order-placed="handleOrderPlaced" />
                </section>

                <!-- Orderbook -->
                <section>
                    <OrderbookCard
                        ref="orderbookRef"
                        :symbols="['BTC', 'ETH']"
                    />
                </section>
            </div>

            <!-- Orders overview -->
            <section class="mt-8 space-y-6">
                <OrdersTable ref="ordersTableRef" />
                <BalanceHistoryTable />
            </section>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';
import { usePusher } from '../composables/usePusher';
import { useToast } from '../composables/useToast';
import LimitOrderForm from '../components/LimitOrderForm.vue';
import OrderbookCard from '../components/OrderbookCard.vue';
import OrdersTable from '../components/OrdersTable.vue';
import BalanceHistoryTable from '../components/BalanceHistoryTable.vue';
import DashboardHeader from '../components/DashboardHeader.vue';
import ToastContainer from '../components/ToastContainer.vue';

const router = useRouter();
const { user, isLoading, logout, fetchUser, token } = useAuth();
const { initializePusher, listenToOrderMatched, unsubscribe } = usePusher();
const toast = useToast();

const profileData = ref(null);
const orderbookRef = ref(null);
const ordersTableRef = ref(null);

const handleOrderMatched = async (data) => {
    const isBuyer = user.value?.id === data.buyer_id;
    const side = isBuyer ? 'Buy' : 'Sell';

    toast.success(
        `Trade complete! ${side} ${data.amount} ${data.symbol} at $${parseFloat(data.price).toFixed(2)}`,
    );

    await fetchProfile();
    await fetchUser();
};

const handleOrderPlaced = (orderData) => {
    if (orderbookRef.value) {
        orderbookRef.value.refreshOrderbook();
    }
    if (ordersTableRef.value) {
        ordersTableRef.value.refreshOrders();
    }
    toast.info(
        `Order placed! ${orderData.side.toUpperCase()} ${orderData.symbol}`,
    );
};

const colorMap = {
    USD: 'bg-indigo-600/20 text-indigo-200 ring-1 ring-indigo-500/40',
    BTC: 'bg-amber-600/20 text-amber-200 ring-1 ring-amber-500/40',
    ETH: 'bg-emerald-600/20 text-emerald-200 ring-1 ring-emerald-500/40',
};

const balances = computed(() => {
    if (!profileData.value) {
        return [];
    }

    const cards = [];

    // Add USD balance first
    if (user.value?.balance !== undefined) {
        cards.push({
            label: 'USD Balance',
            value: `${parseFloat(user.value.balance).toFixed(2)} USD`,
            subtext: 'Available to trade',
            tag: 'USD',
            badgeColor:
                'bg-indigo-600/20 text-indigo-200 ring-1 ring-indigo-500/40',
        });
    }

    if (profileData.value.asset_balances) {
        profileData.value.asset_balances.forEach((asset) => {
            cards.push({
                label: `${asset.symbol} Balance`,
                value: `${asset.amount} ${asset.symbol}`,
                subtext: `Total ${asset.symbol}`,
                tag: asset.symbol,
                badgeColor:
                    colorMap[asset.symbol] ||
                    'bg-slate-600/20 text-slate-200 ring-1 ring-slate-500/40',
            });
        });
    }

    return cards;
});

const fetchProfile = async () => {
    if (!token.value) {
        router.push('/login');
        return;
    }

    try {
        const response = await axios.get('/api/profile');
        profileData.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch profile:', error);
        // Redirect to login if unauthorized or token missing
        if (error.response?.status === 401) {
            router.push('/login');
        } else {
            toast.error('Failed to load profile. Please try again.');
        }
    }
};

onMounted(async () => {
    await fetchUser();

    if (!user.value) {
        router.push('/login');
        return;
    }

    await fetchProfile();

    if (!profileData.value) {
        router.push('/login');
        return;
    }

    // Initialize Pusher connection
    initializePusher(user.value.id);

    // Listen for order matched events
    listenToOrderMatched(handleOrderMatched);
});

onBeforeUnmount(() => {
    unsubscribe(handleOrderMatched);
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
