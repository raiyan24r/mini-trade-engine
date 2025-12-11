<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Navigation bar -->
        <nav class="bg-white shadow">
            <div
                class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
            >
                <h1 class="text-2xl font-bold text-gray-900">
                    Mini Trade Engine
                </h1>
                <div class="flex items-center gap-4">
                    <span class="text-gray-700">{{
                        user?.name || 'User'
                    }}</span>
                    <button
                        :disabled="isLoading"
                        class="rounded-lg bg-red-600 px-4 py-2 font-semibold text-white transition duration-200 hover:bg-red-700 disabled:bg-red-400"
                        @click="handleLogout"
                    >
                        {{ isLoading ? 'Logging out...' : 'Logout' }}
                    </button>
                </div>
            </div>
        </nav>

        <!-- Main content -->
        <main class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
            <!-- Welcome card -->
            <div class="mb-6 rounded-lg bg-white p-6 shadow">
                <h2 class="mb-2 text-3xl font-bold text-gray-900">
                    Welcome, {{ user?.name }}!
                </h2>
                <p class="text-gray-600">
                    You are successfully logged in to the Mini Trade Engine.
                </p>
            </div>

            <!-- User info card -->
            <div class="mb-6 rounded-lg bg-white p-6 shadow">
                <h3 class="mb-4 text-xl font-bold text-gray-900">
                    Your Account
                </h3>
                <div class="space-y-2">
                    <p class="text-gray-700">
                        <strong>Email:</strong> {{ user?.email }}
                    </p>
                    <p class="text-gray-700">
                        <strong>Member since:</strong>
                        {{ formatDate(user?.created_at) }}
                    </p>
                </div>
            </div>

            <!-- Dashboard cards -->
            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                <!-- Card 1 -->
                <div class="rounded-lg bg-white p-6 shadow">
                    <h4 class="mb-2 text-lg font-semibold text-gray-900">
                        Total Trades
                    </h4>
                    <p class="text-4xl font-bold text-blue-600">0</p>
                    <p class="mt-2 text-sm text-gray-600">Trades executed</p>
                </div>

                <!-- Card 2 -->
                <div class="rounded-lg bg-white p-6 shadow">
                    <h4 class="mb-2 text-lg font-semibold text-gray-900">
                        Active Portfolio
                    </h4>
                    <p class="text-4xl font-bold text-green-600">$0.00</p>
                    <p class="mt-2 text-sm text-gray-600">Current balance</p>
                </div>

                <!-- Card 3 -->
                <div class="rounded-lg bg-white p-6 shadow">
                    <h4 class="mb-2 text-lg font-semibold text-gray-900">
                        ROI
                    </h4>
                    <p class="text-4xl font-bold text-purple-600">0%</p>
                    <p class="mt-2 text-sm text-gray-600">
                        Return on investment
                    </p>
                </div>
            </div>
        </main>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';

const router = useRouter();
const { user, isLoading, logout, fetchUser } = useAuth();

onMounted(() => {
    fetchUser();
});

const handleLogout = async () => {
    await logout();
    router.push('/login');
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};
</script>

<style scoped></style>
