import Pusher from 'pusher-js';
import { ref } from 'vue';

const isConnected = ref(false);
const channel = ref(null);
let pusherInstance = null;
let isOrderMatchedBound = false;
let listeners = [];

const bindOrderMatched = () => {
    if (!channel.value || isOrderMatchedBound) return;

    channel.value.bind('order.matched', (data) => {
        listeners.forEach((cb) => cb(data));
    });

    isOrderMatchedBound = true;
};

export function usePusher() {
    const initializePusher = (userId) => {
        if (channel.value && isConnected.value) {
            // Already initialized and connected
            return;
        }

        console.log('Initializing Pusher with:', {
            key: import.meta.env.VITE_PUSHER_APP_KEY,
            cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
            userId,
        });

        if (!pusherInstance) {
            pusherInstance = new Pusher(import.meta.env.VITE_PUSHER_APP_KEY, {
                cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
                forceTLS: true,
                channelAuthorization: {
                    endpoint: '/api/broadcasting/auth',
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('auth_token')}`,
                    },
                },
            });

            pusherInstance.connection.bind('error', function (err) {
                console.error('Pusher connection error:', err);
            });

            pusherInstance.connection.bind('connected', function () {});
        }

        channel.value = pusherInstance.subscribe(`private-user.${userId}`);

        channel.value.bind('pusher:subscription_succeeded', () => {
            isConnected.value = true;
        });

        channel.value.bind('pusher:subscription_error', (error) => {
            isConnected.value = false;
        });

        bindOrderMatched();
    };

    const listenToOrderMatched = (callback) => {
        // Track listener so all components get notified
        listeners.push(callback);
        bindOrderMatched();
    };

    const unsubscribe = (callback) => {
        if (!callback) return;
        listeners = listeners.filter((cb) => cb !== callback);
        if (listeners.length === 0 && channel.value) {
            channel.value.unbind('order.matched');
            isOrderMatchedBound = false;
        }
    };

    return {
        isConnected,
        channel,
        initializePusher,
        listenToOrderMatched,
        unsubscribe,
    };
}
