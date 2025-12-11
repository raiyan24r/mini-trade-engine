<template>
  <div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Counter Example</h2>
    
    <div class="space-y-4">
      <div class="text-6xl font-bold text-blue-600 text-center">
        {{ count }}
      </div>
      
      <div class="flex gap-4 justify-center">
        <button
          @click="decrement"
          class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-6 rounded"
        >
          Decrement
        </button>
        
        <button
          @click="reset"
          class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded"
        >
          Reset
        </button>
        
        <button
          @click="increment"
          class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded"
        >
          Increment
        </button>
      </div>
      
      <p class="text-center text-gray-600 mt-4">
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
