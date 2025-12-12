<template>
    <div class="flex min-h-screen items-center justify-center bg-slate-950 p-4">
        <div
            class="w-full max-w-md rounded-2xl bg-slate-900/70 p-8 shadow-lg ring-1 ring-slate-800"
        >
            <div class="mb-8">
                <p class="text-sm tracking-[0.25em] text-slate-400 uppercase">
                    Welcome back
                </p>
                <h1 class="text-3xl font-semibold text-white">
                    Mini Trade Engine
                </h1>
            </div>

            <!-- Error message -->
            <div
                v-if="error"
                class="mb-4 rounded-xl border border-rose-500/40 bg-rose-600/10 p-4 text-rose-200"
            >
                {{ error }}
            </div>

            <!-- Login form -->
            <form class="space-y-6" @submit.prevent="handleLogin">
                <!-- Email field -->
                <div>
                    <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-slate-300"
                    >
                        Email
                    </label>
                    <input
                        id="email"
                        v-model="email"
                        type="email"
                        required
                        class="w-full rounded-xl border border-slate-800 bg-slate-900 px-4 py-3 text-white placeholder-slate-500 shadow-inner transition focus:border-indigo-500 focus:outline-none"
                        placeholder="Enter your email"
                    />
                </div>

                <!-- Password field -->
                <div>
                    <label
                        for="password"
                        class="mb-2 block text-sm font-medium text-slate-300"
                    >
                        Password
                    </label>
                    <input
                        id="password"
                        v-model="password"
                        type="password"
                        required
                        class="w-full rounded-xl border border-slate-800 bg-slate-900 px-4 py-3 text-white placeholder-slate-500 shadow-inner transition focus:border-indigo-500 focus:outline-none"
                        placeholder="Enter your password"
                    />
                </div>

                <!-- Submit button -->
                <button
                    type="submit"
                    :disabled="isLoading"
                    class="w-full rounded-xl bg-indigo-600 px-4 py-3 font-semibold text-white transition duration-200 hover:bg-indigo-700 disabled:bg-indigo-500"
                >
                    {{ isLoading ? 'Signing in...' : 'Sign In' }}
                </button>
            </form>

            <!-- Register link -->
            <div class="mt-6 text-center">
                <p class="text-slate-400">
                    Don't have an account?
                    <RouterLink
                        to="/register"
                        class="font-semibold text-indigo-400 transition hover:text-indigo-300"
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
