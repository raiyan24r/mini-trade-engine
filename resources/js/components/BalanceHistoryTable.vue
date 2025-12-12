<template>
    <BaseCard>
        <CardHeader label="Balance History" title="Transaction Ledger" />

        <div v-if="loading" class="mt-4 text-center text-slate-400">
            Loading balance history...
        </div>

        <div
            v-else-if="!history.length"
            class="mt-4 rounded-lg bg-slate-900 px-4 py-6 text-center text-slate-400"
        >
            No transaction history yet
        </div>

        <div
            v-else
            class="mt-4 overflow-hidden rounded-xl ring-1 ring-slate-800"
        >
            <table class="min-w-full divide-y divide-slate-800 text-sm">
                <thead class="bg-slate-900 text-slate-300">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold">Date</th>
                        <th class="px-4 py-3 text-left font-semibold">
                            Action
                        </th>
                        <th class="px-4 py-3 text-left font-semibold">
                            Description
                        </th>
                        <th class="px-4 py-3 text-right font-semibold">
                            Credit
                        </th>
                        <th class="px-4 py-3 text-right font-semibold">
                            Debit
                        </th>
                        <th class="px-4 py-3 text-right font-semibold">
                            Balance
                        </th>
                    </tr>
                </thead>
                <tbody
                    class="divide-y divide-slate-800 bg-slate-900/60 text-slate-200"
                >
                    <tr v-for="item in paginatedHistory" :key="item.id">
                        <td class="px-4 py-3 text-slate-400">
                            {{ formatDate(item.date) }}
                        </td>
                        <td class="px-4 py-3">
                            <span
                                :class="getActionBadgeClass(item.action)"
                                class="rounded-full px-2.5 py-1 text-xs font-semibold"
                            >
                                {{ formatAction(item.action) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-slate-300">
                            {{ item.description }}
                        </td>
                        <td
                            class="px-4 py-3 text-right font-semibold text-emerald-400"
                        >
                            {{
                                item.credit > 0
                                    ? `+$${item.credit.toFixed(2)}`
                                    : '—'
                            }}
                        </td>
                        <td
                            class="px-4 py-3 text-right font-semibold text-rose-400"
                        >
                            {{
                                item.debit > 0
                                    ? `-$${item.debit.toFixed(2)}`
                                    : '—'
                            }}
                        </td>
                        <td
                            class="px-4 py-3 text-right font-semibold text-white"
                        >
                            ${{ item.balance.toFixed(2) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div
            v-if="history.length > itemsPerPage"
            class="mt-4 flex items-center justify-between"
        >
            <div class="text-sm text-slate-400">
                Showing {{ (currentPage - 1) * itemsPerPage + 1 }} to
                {{ Math.min(currentPage * itemsPerPage, history.length) }} of
                {{ history.length }} transactions
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
import { computed, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import BaseCard from './BaseCard.vue';
import CardHeader from './CardHeader.vue';

const router = useRouter();
const history = ref([]);
const loading = ref(false);
const currentPage = ref(1);
const itemsPerPage = 10;

const totalPages = computed(() => {
    return Math.ceil(history.value.length / itemsPerPage);
});

const paginatedHistory = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage;
    const end = start + itemsPerPage;
    return history.value.slice(start, end);
});

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleString();
};

const formatAction = (action) => {
    const actionMap = {
        order_reserved: 'Reserved',
        order_refund: 'Refund',
        order_cancelled: 'Cancelled',
        trade_completed: 'Trade',
    };
    return actionMap[action] || action;
};

const getActionBadgeClass = (action) => {
    const classMap = {
        order_reserved:
            'bg-amber-500/20 text-amber-200 ring-1 ring-amber-500/40',
        order_refund: 'bg-blue-500/20 text-blue-200 ring-1 ring-blue-500/40',
        order_cancelled:
            'bg-slate-500/20 text-slate-200 ring-1 ring-slate-500/40',
        trade_completed:
            'bg-emerald-500/20 text-emerald-200 ring-1 ring-emerald-500/40',
    };
    return (
        classMap[action] ||
        'bg-slate-500/20 text-slate-200 ring-1 ring-slate-500/40'
    );
};

const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
    }
};

const fetchHistory = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/balance-history');
        history.value = response.data.data || [];
    } catch (error) {
        console.error('Failed to fetch balance history:', error);
        if (error.response?.status === 401) {
            router.push('/login');
        }
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchHistory();
});
</script>
