import { createRouter, createWebHistory } from 'vue-router';
import { useAuth } from '../composables/useAuth';
import Dashboard from '../pages/Dashboard.vue';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';

const routes = [
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { requiresAuth: false },
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: { requiresAuth: false },
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: Dashboard,
        meta: { requiresAuth: true },
    },
    {
        path: '/:pathMatch(.*)*',
        redirect: '/dashboard',
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Navigation guard for authentication
router.beforeEach(async (to, from, next) => {
    const { token, user, fetchUser } = useAuth();
    const isAuthenticated = !!(
        token.value || localStorage.getItem('auth_token')
    );

    // If we have a token but no user yet, attempt to fetch
    if (isAuthenticated && !user.value) {
        try {
            await fetchUser();
        } catch (e) {
            // If fetch fails/401, fall back to unauthenticated state
        }
    }

    if (to.meta.requiresAuth && !isAuthenticated) {
        // Redirect to login if trying to access protected route without token
        next('/login');
    } else if (
        (to.path === '/login' || to.path === '/register') &&
        isAuthenticated
    ) {
        // Redirect to dashboard if already logged in and trying to access login/register
        next('/dashboard');
    } else {
        next();
    }
});

export default router;
