<template>
    <BaseCard>
        <CardHeader
            label="Limit Order"
            title="Place a new order"
            badge="Draft"
            title-class="text-2xl font-semibold text-white"
        />

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <label class="flex flex-col gap-2 text-sm text-slate-300">
                Symbol
                <select
                    v-model="form.symbol"
                    class="w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-3 text-white shadow-inner focus:border-indigo-500 focus:outline-none"
                >
                    <option value="BTC">BTC</option>
                    <option value="ETH">ETH</option>
                </select>
            </label>

            <label class="flex flex-col gap-2 text-sm text-slate-300">
                Side
                <div class="grid grid-cols-2 gap-2">
                    <button
                        type="button"
                        :class="[
                            'rounded-xl px-3 py-3 text-sm font-semibold transition',
                            form.side === 'buy'
                                ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30'
                                : 'bg-slate-900 text-slate-200 ring-1 ring-slate-800 hover:ring-slate-700',
                        ]"
                        @click="form.side = 'buy'"
                    >
                        Buy
                    </button>
                    <button
                        type="button"
                        :class="[
                            'rounded-xl px-3 py-3 text-sm font-semibold transition',
                            form.side === 'sell'
                                ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30'
                                : 'bg-slate-900 text-slate-200 ring-1 ring-slate-800 hover:ring-slate-700',
                        ]"
                        @click="form.side = 'sell'"
                    >
                        Sell
                    </button>
                </div>
            </label>

            <label class="flex flex-col gap-2 text-sm text-slate-300">
                Price
                <input
                    v-model="form.price"
                    type="number"
                    inputmode="decimal"
                    placeholder="e.g. 42000"
                    class="w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-3 text-white shadow-inner focus:border-indigo-500 focus:outline-none"
                />
            </label>

            <label class="flex flex-col gap-2 text-sm text-slate-300">
                Amount
                <input
                    v-model="form.amount"
                    type="number"
                    inputmode="decimal"
                    placeholder="e.g. 0.5"
                    class="w-full rounded-xl border border-slate-800 bg-slate-900 px-3 py-3 text-white shadow-inner focus:border-indigo-500 focus:outline-none"
                />
            </label>
        </div>

        <div
            class="mt-6 flex items-center justify-between rounded-xl bg-slate-900 px-5 py-4 text-sm text-slate-300 ring-1 ring-slate-800"
        >
            <div>
                <p class="text-xs tracking-wide text-slate-500 uppercase">
                    Estimated total
                </p>
                <p class="text-lg font-semibold text-white">
                    {{ estimatedTotal }} {{ form.symbol }}
                </p>
            </div>
            <button
                class="rounded-xl bg-indigo-600 px-4 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:bg-indigo-700"
                @click="placeOrder"
            >
                Place Order
            </button>
        </div>
    </BaseCard>
</template>

<script setup>
import { computed, reactive } from 'vue';
import BaseCard from './BaseCard.vue';
import CardHeader from './CardHeader.vue';

const form = reactive({
    symbol: 'BTC',
    side: 'buy',
    price: '',
    amount: '',
});

const estimatedTotal = computed(() => {
    const price = parseFloat(form.price) || 0;
    const amount = parseFloat(form.amount) || 0;
    const total = price * amount;
    return total
        ? total.toLocaleString('en-US', { maximumFractionDigits: 4 })
        : '0.00';
});

const placeOrder = () => {
    // Placeholder interaction for now
    window.alert(
        `Order placed: ${form.side.toUpperCase()} ${form.amount || '0'} ${form.symbol} at ${form.price || 'market'}`,
    );
};
</script>
