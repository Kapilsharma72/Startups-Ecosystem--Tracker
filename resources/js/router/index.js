import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../pages/Dashboard.vue';
import StartupList from '../pages/StartupList.vue';
import InvestorList from '../pages/InvestorList.vue';
import NewsFeed from '../pages/NewsFeed.vue';

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/startups',
        name: 'startups',
        component: StartupList,
        meta: { requiresAuth: true }
    },
    {
        path: '/investors',
        name: 'investors',
        component: InvestorList,
        meta: { requiresAuth: true }
    },
    {
        path: '/news',
        name: 'news',
        component: NewsFeed,
        meta: { requiresAuth: true }
    },
    {
        path: '/dashboard',
        redirect: '/'
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

// Navigation guard to check authentication
router.beforeEach((to, from, next) => {
    // Check if the route requires authentication
    if (to.matched.some(record => record.meta.requiresAuth)) {
        // Check if the user is authenticated using the global variable set in app.blade.php
        if (window.isLoggedIn !== true) {
            // Redirect to login if not authenticated
            window.location.href = '/login';
            return;
        }
    }
    // Continue navigation
    next();
})

export default router