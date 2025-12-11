<template>
    <div class="flex min-h-screen items-center justify-center bg-slate-950 p-4">
        <div
            class="w-full max-w-md rounded-2xl bg-slate-900/70 p-8 shadow-lg ring-1 ring-slate-800"
        >
            <div class="mb-8">
                <p class="text-sm tracking-[0.25em] text-slate-400 uppercase">
                    Create account
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

            <!-- Success message -->
            <div
                v-if="successMessage"
                class="mb-4 rounded-xl border border-emerald-500/40 bg-emerald-600/10 p-4 text-emerald-200"
            >
                {{ successMessage }}
            </div>

            <!-- Register form -->
            <form class="space-y-5" @submit.prevent="handleRegister">
                <!-- Name field -->
                <div>
                    <label
                        for="name"
                        class="mb-2 block text-sm font-medium text-slate-300"
                    >
                        Full Name
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        class="w-full rounded-xl border border-slate-800 bg-slate-900 px-4 py-3 text-white placeholder-slate-500 shadow-inner transition focus:border-indigo-500 focus:outline-none"
                        placeholder="John Doe"
                    />
                </div>

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
                        v-model="form.email"
                        type="email"
                        required
                        class="w-full rounded-xl border border-slate-800 bg-slate-900 px-4 py-3 text-white placeholder-slate-500 shadow-inner transition focus:border-indigo-500 focus:outline-none"
                        placeholder="you@example.com"
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
                        v-model="form.password"
                        type="password"
                        required
                        minlength="8"
                        class="w-full rounded-xl border border-slate-800 bg-slate-900 px-4 py-3 text-white placeholder-slate-500 shadow-inner transition focus:border-indigo-500 focus:outline-none"
                        placeholder="Minimum 8 characters"
                    />
                    <p class="mt-1 text-xs text-slate-400">
                        Must be at least 8 characters
                    </p>
                </div>

                <!-- Confirm Password field -->
                <div>
                    <label
                        for="password_confirmation"
                        class="mb-2 block text-sm font-medium text-slate-300"
                    >
                        Confirm Password
                    </label>
                    <input
                        id="password_confirmation"
                        v-model="form.passwordConfirmation"
                        type="password"
                        required
                        minlength="8"
                        class="w-full rounded-xl border border-slate-800 bg-slate-900 px-4 py-3 text-white placeholder-slate-500 shadow-inner transition focus:border-indigo-500 focus:outline-none"
                        placeholder="Confirm your password"
                    />
                    <p
                        v-if="passwordMismatch"
                        class="mt-1 text-xs text-rose-400"
                    >
                        Passwords do not match
                    </p>
                </div>

                <!-- Submit button -->
                <button
                    type="submit"
                    :disabled="isLoading || passwordMismatch"
                    class="w-full rounded-xl bg-indigo-600 px-4 py-3 font-semibold text-white transition duration-200 hover:bg-indigo-700 disabled:bg-indigo-500"
                >
                    {{ isLoading ? 'Creating account...' : 'Create Account' }}
                </button>
            </form>

            <!-- Login link -->
            <div class="mt-6 text-center">
                <p class="text-slate-400">
                    Already have an account?
                    <RouterLink
                        to="/login"
                        class="font-semibold text-indigo-400 transition hover:text-indigo-300"
                    >
                        Sign in
                    </RouterLink>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useRouter } from 'vue-router';
import { useAuth } from '../composables/useAuth';

const router = useRouter();
const { register, isLoading, error: authError } = useAuth();

const form = ref({
    name: '',
    email: '',
    password: '',
    passwordConfirmation: '',
});

const error = ref('');
const successMessage = ref('');

const passwordMismatch = computed(
    () =>
        form.value.password &&
        form.value.passwordConfirmation &&
        form.value.password !== form.value.passwordConfirmation,
);

const handleRegister = async () => {
    error.value = '';
    successMessage.value = '';

    // Validation
    if (!form.value.name.trim()) {
        error.value = 'Name is required';
        return;
    }

    if (form.value.password.length < 8) {
        error.value = 'Password must be at least 8 characters';
        return;
    }

    if (form.value.password !== form.value.passwordConfirmation) {
        error.value = 'Passwords do not match';
        return;
    }

    try {
        await register(
            form.value.name,
            form.value.email,
            form.value.password,
            form.value.passwordConfirmation,
        );

        successMessage.value =
            'Account created successfully! Redirecting to dashboard...';

        setTimeout(() => {
            router.push('/dashboard');
        }, 1500);
    } catch (err) {
        console.log('Registration failed:', err);
        error.value =
            authError.value || 'Registration failed. Please try again.';
    }
};
</script>

<style scoped>
input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
