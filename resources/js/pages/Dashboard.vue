<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Navigation bar -->
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <h1 class="text-2xl font-bold text-gray-900">Mini Trade Engine</h1>
        <div class="flex items-center gap-4">
          <span class="text-gray-700">{{ user?.name || 'User' }}</span>
          <button
            :disabled="isLoading"
            class="bg-red-600 hover:bg-red-700 disabled:bg-red-400 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
            @click="handleLogout"
          >
            {{ isLoading ? 'Logging out...' : 'Logout' }}
          </button>
        </div>
      </div>
    </nav>

    <!-- Main content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Welcome card -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome, {{ user?.name }}!</h2>
        <p class="text-gray-600">You are successfully logged in to the Mini Trade Engine.</p>
      </div>

      <!-- User info card -->
      <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Your Account</h3>
        <div class="space-y-2">
          <p class="text-gray-700"><strong>Email:</strong> {{ user?.email }}</p>
          <p class="text-gray-700"><strong>Member since:</strong> {{ formatDate(user?.created_at) }}</p>
        </div>
      </div>

      <!-- Dashboard cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-lg shadow p-6">
          <h4 class="text-lg font-semibold text-gray-900 mb-2">Total Trades</h4>
          <p class="text-4xl font-bold text-blue-600">0</p>
          <p class="text-gray-600 text-sm mt-2">Trades executed</p>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-lg shadow p-6">
          <h4 class="text-lg font-semibold text-gray-900 mb-2">Active Portfolio</h4>
          <p class="text-4xl font-bold text-green-600">$0.00</p>
          <p class="text-gray-600 text-sm mt-2">Current balance</p>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-lg shadow p-6">
          <h4 class="text-lg font-semibold text-gray-900 mb-2">ROI</h4>
          <p class="text-4xl font-bold text-purple-600">0%</p>
          <p class="text-gray-600 text-sm mt-2">Return on investment</p>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';

const router = useRouter();
const { user, isLoading, logout, fetchUser } = useAuth();

onMounted(() => {
  // Fetch user data when component mounts
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

<style scoped>
</style>
