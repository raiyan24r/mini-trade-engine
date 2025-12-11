<template>
    <div
        class="flex min-h-screen items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4"
    >
        <div class="w-full max-w-md rounded-lg bg-white p-8 shadow-xl">
            <h1 class="mb-2 text-3xl font-bold text-gray-900">
                Mini Trade Engine
            </h1>
            <p class="mb-8 text-gray-600">Sign in to your account</p>

            <!-- Error message -->
            <div
                v-if="error"
                class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700"
            >
                {{ error }}
            </div>

            <!-- Login form -->
            <form class="space-y-6" @submit.prevent="handleLogin">
                <!-- Email field -->
                <div>
                    <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Email
                    </label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Enter your email"
                    />
                </div>

                <!-- Password field -->
                <div>
                    <label
                        for="password"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Password
                    </label>
                    <input
                        id="password"
                        v-model="password"
                        type="password"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Enter your password"
                    />
                </div>

                <!-- Submit button -->
                <button
                    type="submit"
                    :disabled="isLoading"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white transition duration-200 hover:bg-blue-700 disabled:bg-blue-400"
                >
                    {{ isLoading ? 'Signing in...' : 'Sign In' }}
                </button>
            </form>

            <!-- Demo credentials hint -->
            <div class="mt-6 rounded-lg border border-blue-200 bg-blue-50 p-4">
                <p class="text-sm text-blue-900">
                    <strong>Demo account:</strong><br />
                    Email: test@example.com<br />
                    Password: password
                </p>
            </div>

            <!-- Register link -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Don't have an account?
                    <RouterLink
                        to="/register"
                        class="font-semibold text-blue-600 hover:text-blue-700"
                    >
                        Sign up
                    </RouterLink>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';

const router = useRouter();
const { login, isLoading, error } = useAuth();

const email = ref('');
const password = ref('');

const handleLogin = async () => {
    try {
        await login(email.value, password.value);
        // Redirect to dashboard on successful login
        router.push('/dashboard');
    } catch (err) {
        console.error('Login failed:', err);
    }
};
</script>

<style scoped>
input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
