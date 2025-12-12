<template>
    <BaseCard>
        <CardHeader label="Orderbook" :title="`${selectedSymbol}/USD`" />

        <div class="mt-3 flex flex-wrap gap-2">
            <button
                v-for="option in symbols"
                :key="option"
                type="button"
                class="rounded-full px-3 py-1 text-xs font-semibold ring-1 transition"
                :class="
                    option === selectedSymbol
                        ? 'bg-indigo-600/30 text-indigo-200 ring-indigo-500/60'
                        : 'bg-slate-800 text-slate-200 ring-slate-700 hover:bg-slate-700'
                "
                @click="selectSymbol(option)"
            >
                {{ option }}
            </button>
        </div>

        <div class="mt-4 space-y-3">
            <OrderbookSection
                label="SELL (Asks)"
                :items="orderbook.asks"
                :loading="loading"
                type="ask"
            />

            <OrderbookSection
                label="BUY (Bids)"
                :items="orderbook.bids"
                :loading="loading"
                type="bid"
            />
        </div>
    </BaseCard>
</template>

<script setup>
import axios from 'axios';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';
import { usePusher } from '../composables/usePusher';
import BaseCard from './BaseCard.vue';
import CardHeader from './CardHeader.vue';
import OrderbookSection from './OrderbookSection.vue';

const props = defineProps({
    symbols: {
        type: Array,
        default: () => ['BTC', 'ETH', 'XRP'],
    },
    initialSymbol: {
        type: String,
        default: 'BTC',
    },
});

const router = useRouter();
const { token } = useAuth();
const { listenToOrderMatched, unsubscribe } = usePusher();

const selectedSymbol = ref(props.initialSymbol || props.symbols[0] || 'BTC');
const orderbook = ref({ asks: [], bids: [] });
const loading = ref(false);

const fetchOrderbook = async (symbol) => {
    if (!token.value) {
        router.push('/login');
        return;
    }

    loading.value = true;
    try {
        const response = await axios.get('/api/orders', {
            params: { symbol },
        });

        orderbook.value = response.data.data;
    } catch (error) {
        console.error('Failed to fetch orderbook:', error);
        if (error.response?.status === 401) {
            router.push('/login');
        }
    } finally {
        loading.value = false;
    }
};

const selectSymbol = async (symbol) => {
    if (symbol === selectedSymbol.value) return;
    selectedSymbol.value = symbol;
};

watch(selectedSymbol, async (symbol) => {
    await fetchOrderbook(symbol);
});

onMounted(async () => {
    await fetchOrderbook(selectedSymbol.value);

    const handleOrderMatched = async (data) => {
        await fetchOrderbook(selectedSymbol.value);
    };

    listenToOrderMatched(handleOrderMatched);

    onBeforeUnmount(() => {
        unsubscribe(handleOrderMatched);
    });
});
</script>
