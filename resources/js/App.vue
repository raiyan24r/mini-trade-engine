<template>
    <RouterView />
</template>

<script setup>
import { RouterView } from 'vue-router';
import { onMounted } from 'vue';
import { useAuth } from './composables/useAuth';

const { fetchUser, token } = useAuth();

onMounted(async () => {
    // Populate user on app start if token exists
    if (token.value) {
        await fetchUser();
    }
});
</script>

<style>
* {
    box-sizing: border-box;
}

body {
    font-family:
        -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu,
        Cantarell, sans-serif;
}
</style>
