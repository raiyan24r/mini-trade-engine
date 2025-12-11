<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl p-8 w-full max-w-md">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Mini Trade Engine</h1>
      <p class="text-gray-600 mb-8">Create your account</p>

      <!-- Error message -->
      <div v-if="error" class="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-lg">
        {{ error }}
      </div>

      <!-- Success message -->
      <div v-if="successMessage" class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
        {{ successMessage }}
      </div>

      <!-- Register form -->
      <form class="space-y-6" @submit.prevent="handleRegister">
        <!-- Name field -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Full Name
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="John Doe"
          />
        </div>

        <!-- Email field -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
            Email
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="you@example.com"
          />
        </div>

        <!-- Password field -->
        <div>
          <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
            Password
          </label>
          <input
            id="password"
            v-model="form.password"
            type="password"
            required
            minlength="8"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Minimum 8 characters"
          />
          <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters</p>
        </div>

        <!-- Confirm Password field -->
        <div>
          <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
            Confirm Password
          </label>
          <input
            id="password_confirmation"
            v-model="form.passwordConfirmation"
            type="password"
            required
            minlength="8"
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Confirm your password"
          />
          <p v-if="passwordMismatch" class="text-xs text-red-600 mt-1">
            Passwords do not match
          </p>
        </div>

        <!-- Submit button -->
        <button
          type="submit"
          :disabled="isLoading || passwordMismatch"
          class="w-full bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-semibold py-2 px-4 rounded-lg transition duration-200"
        >
          {{ isLoading ? 'Creating account...' : 'Create Account' }}
        </button>
      </form>

      <!-- Login link -->
      <div class="mt-6 text-center">
        <p class="text-gray-600">
          Already have an account?
          <RouterLink to="/login" class="text-blue-600 hover:text-blue-700 font-semibold">
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
  () => form.value.password && form.value.passwordConfirmation && form.value.password !== form.value.passwordConfirmation
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
      form.value.passwordConfirmation
    );

    successMessage.value = 'Account created successfully! Redirecting to dashboard...';

    // Redirect to dashboard after successful registration
    setTimeout(() => {
      router.push('/dashboard');
    }, 1500);
  } catch (err) {
    error.value = authError.value || 'Registration failed. Please try again.';
  }
};
</script>

<style scoped>
input:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}
</style>
