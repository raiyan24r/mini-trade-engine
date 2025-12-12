<template>
    <BaseCard>
        <CardHeader label="Orders" title="Past & Open" />

        <!-- Filters -->
        <div class="mt-4 flex flex-wrap gap-3">
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-400"
                    >Symbol</label
                >
                <select
                    v-model="filters.symbol"
                    class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-200 focus:border-slate-500 focus:outline-none"
                >
                    <option value="">All Symbols</option>
                    <option
                        v-for="symbol in uniqueSymbols"
                        :key="symbol"
                        :value="symbol"
                    >
                        {{ symbol }}
                    </option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-400"
                    >Side</label
                >
                <select
                    v-model="filters.side"
                    class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-200 focus:border-slate-500 focus:outline-none"
                >
                    <option value="">All Sides</option>
                    <option value="Buy">Buy</option>
                    <option value="Sell">Sell</option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold text-slate-400"
                    >Status</label
                >
                <select
                    v-model="filters.status"
                    class="rounded-lg border border-slate-700 bg-slate-800 px-3 py-2 text-sm text-slate-200 focus:border-slate-500 focus:outline-none"
                >
                    <option value="">All Statuses</option>
                    <option value="Open">Open</option>
                    <option value="Filled">Filled</option>
                    <option value="Cancelled">Cancelled</option>
                </select>
            </div>
            <div class="flex items-end">
                <button
                    class="rounded-lg bg-slate-700/50 px-3 py-2 text-sm font-semibold text-slate-300 transition-colors hover:bg-slate-700"
                    @click="resetFilters"
                >
                    Reset
                </button>
            </div>
        </div>

        <div v-if="loading" class="mt-4 text-center text-slate-400">
            Loading orders...
        </div>

        <div
            v-else-if="!orders.length"
            class="mt-4 rounded-lg bg-slate-900 px-4 py-6 text-center text-slate-400"
        >
            No orders yet
        </div>

        <div
            v-else-if="!filteredOrders.length"
            class="mt-4 rounded-lg bg-slate-900 px-4 py-6 text-center text-slate-400"
        >
            No orders match the selected filters
        </div>

        <div
            v-else
            class="mt-4 overflow-hidden rounded-xl ring-1 ring-slate-800"
        >
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-900 text-slate-300">
                    <tr>
                        <th
                            class="cursor-pointer px-4 py-3 text-left font-semibold hover:text-slate-100"
                            @click="toggleSort('id')"
                        >
                            ID
                            <span v-if="sortBy === 'id'" class="ml-1">{{
                                sortOrder === 'asc' ? '↑' : '↓'
                            }}</span>
                        </th>
                        <th
                            class="cursor-pointer px-4 py-3 text-left font-semibold hover:text-slate-100"
                            @click="toggleSort('symbol')"
                        >
                            Symbol
                            <span v-if="sortBy === 'symbol'" class="ml-1">{{
                                sortOrder === 'asc' ? '↑' : '↓'
                            }}</span>
                        </th>
                        <th
                            class="cursor-pointer px-4 py-3 text-left font-semibold hover:text-slate-100"
                            @click="toggleSort('side')"
                        >
                            Side
                            <span v-if="sortBy === 'side'" class="ml-1">{{
                                sortOrder === 'asc' ? '↑' : '↓'
                            }}</span>
                        </th>
                        <th
                            class="cursor-pointer px-4 py-3 text-left font-semibold hover:text-slate-100"
                            @click="toggleSort('price')"
                        >
                            Price
                            <span v-if="sortBy === 'price'" class="ml-1">{{
                                sortOrder === 'asc' ? '↑' : '↓'
                            }}</span>
                        </th>
                        <th
                            class="cursor-pointer px-4 py-3 text-left font-semibold hover:text-slate-100"
                            @click="toggleSort('amount')"
                        >
                            Amount
                            <span v-if="sortBy === 'amount'" class="ml-1">{{
                                sortOrder === 'asc' ? '↑' : '↓'
                            }}</span>
                        </th>
                        <th
                            class="cursor-pointer px-4 py-3 text-left font-semibold hover:text-slate-100"
                            @click="toggleSort('status')"
                        >
                            Status
                            <span v-if="sortBy === 'status'" class="ml-1">{{
                                sortOrder === 'asc' ? '↑' : '↓'
                            }}</span>
                        </th>
                        <th
                            class="cursor-pointer px-4 py-3 text-left font-semibold hover:text-slate-100"
                            @click="toggleSort('date')"
                        >
                            Date
                            <span v-if="sortBy === 'date'" class="ml-1">{{
                                sortOrder === 'asc' ? '↑' : '↓'
                            }}</span>
                        </th>
                        <th class="px-4 py-3 text-left font-semibold">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-slate-800 bg-slate-900/60 text-slate-200"
                >
                    <OrderRow
                        v-for="order in paginatedOrders"
                        :key="order.id"
                        :order="order"
                        @cancel="handleCancelOrder"
                    />
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="filteredOrders.length > itemsPerPage"
            class="mt-4 flex items-center justify-between"
        >
            <div class="text-sm text-slate-400">
                Showing {{ (currentPage - 1) * itemsPerPage + 1 }} to
                {{
                    Math.min(currentPage * itemsPerPage, filteredOrders.length)
                }}
                of {{ filteredOrders.length }} orders
            </div>
            <div class="flex gap-2">
                <button
                    :disabled="currentPage === 1"
                    class="rounded-lg bg-slate-800 px-3 py-2 text-sm font-semibold text-slate-300 transition-colors hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-50"
                    @click="goToPage(currentPage - 1)"
                >
                    Previous
                </button>
                <button
                    v-for="page in totalPages"
                    :key="page"
                    :class="[
                        'rounded-lg px-3 py-2 text-sm font-semibold transition-colors',
                        currentPage === page
                            ? 'bg-indigo-600 text-white'
                            : 'bg-slate-800 text-slate-300 hover:bg-slate-700',
                    ]"
                    @click="goToPage(page)"
                >
                    {{ page }}
                </button>
                <button
                    :disabled="currentPage === totalPages"
                    class="rounded-lg bg-slate-800 px-3 py-2 text-sm font-semibold text-slate-300 transition-colors hover:bg-slate-700 disabled:cursor-not-allowed disabled:opacity-50"
                    @click="goToPage(currentPage + 1)"
                >
                    Next
                </button>
            </div>
        </div>
    </BaseCard>
</template>

<script setup>
import axios from 'axios';
import { computed, onMounted, onBeforeUnmount, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from '../composables/useToast';
import BaseCard from './BaseCard.vue';
import CardHeader from './CardHeader.vue';
import OrderRow from './OrderRow.vue';
import { usePusher } from '../composables/usePusher.js';

const router = useRouter();
const toast = useToast();
const orders = ref([]);
const loading = ref(false);
const sortBy = ref('date');
const sortOrder = ref('desc');
const currentPage = ref(1);
const itemsPerPage = 10;

const filters = ref({
    symbol: '',
    side: '',
    status: '',
});

const statusMap = {
    1: 'Open',
    2: 'Filled',
    3: 'Cancelled',
};

const sideMap = {
    buy: 'Buy',
    sell: 'Sell',
};

const uniqueSymbols = computed(() => {
    const symbols = new Set(orders.value.map((order) => order.symbol));
    return Array.from(symbols).sort();
});

const normalizedOrder = (order) => {
    return {
        id: order.id || Math.random(),
        symbol: order.symbol,
        side: sideMap[order.side] || order.side,
        price: `$${parseFloat(order.price).toFixed(2)}`,
        priceNumeric: parseFloat(order.price),
        amount: parseFloat(order.amount).toFixed(8),
        amountNumeric: parseFloat(order.amount),
        status: statusMap[order.status] || 'Unknown',
        date: new Date(order.date).toISOString().split('T')[0],
        dateRaw: new Date(order.date),
        cancelled_at: order.cancelled_at
            ? new Date(order.cancelled_at).toLocaleString()
            : null,
    };
};

const filteredOrders = computed(() => {
    let filtered = orders.value.map(normalizedOrder);

    // Apply filters
    if (filters.value.symbol) {
        filtered = filtered.filter(
            (order) => order.symbol === filters.value.symbol,
        );
    }
    if (filters.value.side) {
        filtered = filtered.filter(
            (order) => order.side === filters.value.side,
        );
    }
    if (filters.value.status) {
        filtered = filtered.filter(
            (order) => order.status === filters.value.status,
        );
    }

    // Apply sorting
    filtered.sort((a, b) => {
        let aVal = a[sortBy.value];
        let bVal = b[sortBy.value];

        // Handle numeric comparisons
        if (sortBy.value === 'price') {
            aVal = a.priceNumeric;
            bVal = b.priceNumeric;
        } else if (sortBy.value === 'amount') {
            aVal = a.amountNumeric;
            bVal = b.amountNumeric;
        } else if (sortBy.value === 'date') {
            aVal = a.dateRaw;
            bVal = b.dateRaw;
        }

        if (aVal < bVal) return sortOrder.value === 'asc' ? -1 : 1;
        if (aVal > bVal) return sortOrder.value === 'asc' ? 1 : -1;
        return 0;
    });

    return filtered;
});

const totalPages = computed(() => {
    return Math.ceil(filteredOrders.value.length / itemsPerPage);
});

const paginatedOrders = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return filteredOrders.value.slice(start, end);
});

const toggleSort = (column) => {
    if (sortBy.value === column) {
        sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
    } else {
        sortBy.value = column;
        sortOrder.value = 'asc';
    }
};

const resetFilters = () => {
    filters.value = {
        symbol: '',
        side: '',
        status: '',
    };
    currentPage.value = 1;
};

const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

const fetchOrders = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/my-orders');
        orders.value = response.data.data || [];
    } catch (error) {
        console.error('Failed to fetch orders:', error);
        if (error.response?.status === 401) {
            router.push('/login');
        } else {
            toast.error('Failed to load orders. Please try again.');
        }
    } finally {
        loading.value = false;
    }
};

const refreshOrders = async () => {
    await fetchOrders();
};

defineExpose({
    refreshOrders,
});

const handleCancelOrder = async (orderId) => {
    try {
        await axios.post(`/api/orders/${orderId}/cancel`);
        await fetchOrders();
        toast.success('Order cancelled successfully.');
        // eslint-disable-next-line no-undef
        window.dispatchEvent(new Event('orderCancelled'));
    } catch (error) {
        console.error('Failed to cancel order:', error);
        toast.error(error.response?.data?.message || 'Failed to cancel order.');
    }
};

onMounted(() => {
    fetchOrders();
});

const { listenToOrderMatched, unsubscribe } = usePusher();

const handleOrderMatched = async (data) => {
    await fetchOrders();
};

listenToOrderMatched(handleOrderMatched);

onBeforeUnmount(() => {
    unsubscribe(handleOrderMatched);
});
</script>
