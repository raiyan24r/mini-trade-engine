<template>
    <div
        class="flex min-h-screen items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 p-4"
    >
        <div class="w-full max-w-md rounded-lg bg-white p-8 shadow-xl">
            <h1 class="mb-2 text-3xl font-bold text-gray-900">
                Mini Trade Engine
            </h1>
            <p class="mb-8 text-gray-600">Create your account</p>

            <!-- Error message -->
            <div
                v-if="error"
                class="mb-4 rounded-lg border border-red-200 bg-red-50 p-4 text-red-700"
            >
                {{ error }}
            </div>

            <!-- Success message -->
            <div
                v-if="successMessage"
                class="mb-4 rounded-lg border border-green-200 bg-green-50 p-4 text-green-700"
            >
                {{ successMessage }}
            </div>

            <!-- Register form -->
            <form class="space-y-6" @submit.prevent="handleRegister">
                <!-- Name field -->
                <div>
                    <label
                        for="name"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Full Name
                    </label>
                    <input
                        id="name"
                        v-model="form.name"
                        type="text"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="John Doe"
                    />
                </div>

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
                        v-model="form.email"
                        type="email"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="you@example.com"
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
                        v-model="form.password"
                        type="password"
                        required
                        minlength="8"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Minimum 8 characters"
                    />
                    <p class="mt-1 text-xs text-gray-500">
                        Must be at least 8 characters
                    </p>
                </div>

                <!-- Confirm Password field -->
                <div>
                    <label
                        for="password_confirmation"
                        class="mb-2 block text-sm font-medium text-gray-700"
                    >
                        Confirm Password
                    </label>
                    <input
                        id="password_confirmation"
                        v-model="form.passwordConfirmation"
                        type="password"
                        required
                        minlength="8"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        placeholder="Confirm your password"
                    />
                    <p
                        v-if="passwordMismatch"
                        class="mt-1 text-xs text-red-600"
                    >
                        Passwords do not match
                    </p>
                </div>

                <!-- Submit button -->
                <button
                    type="submit"
                    :disabled="isLoading || passwordMismatch"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white transition duration-200 hover:bg-blue-700 disabled:bg-blue-400"
                >
                    {{ isLoading ? 'Creating account...' : 'Create Account' }}
                </button>
            </form>

            <!-- Login link -->
            <div class="mt-6 text-center">
                <p class="text-gray-600">
                    Already have an account?
                    <RouterLink
                        to="/login"
                        class="font-semibold text-blue-600 hover:text-blue-700"
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
