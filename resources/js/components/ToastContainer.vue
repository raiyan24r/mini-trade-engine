<template>
    <div
        class="pointer-events-none fixed top-4 right-4 z-50 flex flex-col gap-3"
    >
        <TransitionGroup name="toast">
            <div
                v-for="toast in toasts"
                :key="toast.id"
                :class="[
                    'pointer-events-auto flex items-center gap-3 rounded-xl px-4 py-3 shadow-lg ring-1 backdrop-blur-sm',
                    getToastClasses(toast.type),
                ]"
            >
                <div :class="getIconClasses(toast.type)">
                    <svg
                        v-if="toast.type === 'success'"
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>
                    <svg
                        v-else-if="toast.type === 'error'"
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                    <svg
                        v-else-if="toast.type === 'warning'"
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                        />
                    </svg>
                    <svg
                        v-else
                        class="h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>
                <p class="text-sm font-medium">{{ toast.message }}</p>
                <button
                    class="ml-auto rounded-lg p-1 transition-colors hover:bg-white/10"
                    @click="removeToast(toast.id)"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </div>
        </TransitionGroup>
    </div>
</template>

<script setup>
import { useToast } from '../composables/useToast';

const { toasts, removeToast } = useToast();

const getToastClasses = (type) => {
    const classes = {
        success:
            'bg-emerald-600/20 text-emerald-200 ring-emerald-500/40 backdrop-blur-sm',
        error: 'bg-rose-600/20 text-rose-200 ring-rose-500/40 backdrop-blur-sm',
        warning:
            'bg-amber-600/20 text-amber-200 ring-amber-500/40 backdrop-blur-sm',
        info: 'bg-indigo-600/20 text-indigo-200 ring-indigo-500/40 backdrop-blur-sm',
    };
    return classes[type] || classes.info;
};

const getIconClasses = (type) => {
    const classes = {
        success: 'text-emerald-400',
        error: 'text-rose-400',
        warning: 'text-amber-400',
        info: 'text-indigo-400',
    };
    return classes[type] || classes.info;
};
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
    transition: all 0.3s ease;
}

.toast-enter-from {
    opacity: 0;
    transform: translateX(100%);
}

.toast-leave-to {
    opacity: 0;
    transform: translateX(100%) scale(0.8);
}
</style>
