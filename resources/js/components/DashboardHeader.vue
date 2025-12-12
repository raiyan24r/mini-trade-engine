<template>
    <header
        class="mb-8 flex flex-col items-center justify-between gap-4 rounded-2xl bg-linear-to-r from-slate-900 to-slate-800 px-6 py-6 shadow-lg sm:flex-row"
    >
        <div>
            <p class="text-sm tracking-[0.25em] text-slate-400 uppercase">
                Welcome back
            </p>
            <h1 class="text-3xl font-semibold text-white">
                {{ title }}
            </h1>
            <p class="text-sm text-slate-400">
                {{ subtitle }}
            </p>
        </div>
        <div class="flex items-center gap-4">
            <div
                class="rounded-xl bg-slate-800 px-4 py-2 text-xl text-slate-200"
            >
                {{ userName }}
            </div>
            <button
                :disabled="isLoading"
                class="rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700 disabled:bg-red-400"
                @click="handleLogout"
            >
                {{ isLoading ? 'Logging out…' : 'Logout' }}
            </button>
        </div>
    </header>
</template>

<script setup>
defineProps({
    title: {
        type: String,
        default: 'Mini Trade Engine',
    },
    subtitle: {
        type: String,
        default: 'Signed in user',
    },
    userName: {
        type: String,
        default: 'User',
    },
    isLoading: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['logout']);

const handleLogout = () => {
    emit('logout');
};
</script>
