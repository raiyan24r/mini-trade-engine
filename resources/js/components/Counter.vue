<template>
    <div class="rounded-lg bg-white p-6 shadow">
        <h2 class="mb-4 text-2xl font-bold text-gray-900">Counter Example</h2>

        <div class="space-y-4">
            <div class="text-center text-6xl font-bold text-blue-600">
                {{ count }}
            </div>

            <div class="flex justify-center gap-4">
                <button
                    class="rounded bg-red-500 px-6 py-2 font-bold text-white hover:bg-red-600"
                    @click="decrement"
                >
                    Decrement
                </button>

                <button
                    class="rounded bg-gray-500 px-6 py-2 font-bold text-white hover:bg-gray-600"
                    @click="reset"
                >
                    Reset
                </button>

                <button
                    class="rounded bg-green-500 px-6 py-2 font-bold text-white hover:bg-green-600"
                    @click="increment"
                >
                    Increment
                </button>
            </div>

            <p class="mt-4 text-center text-gray-600">
                Double count: {{ doubleCount }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';

// Reactive state
const count = ref(0);

// Computed property
const doubleCount = computed(() => count.value * 2);

// Methods
const increment = () => {
    count.value++;
};

const decrement = () => {
    count.value--;
};

const reset = () => {
    count.value = 0;
};
</script>

<style scoped>
button {
    transition: all 0.3s ease;
}

button:active {
    transform: scale(0.95);
}
</style>
