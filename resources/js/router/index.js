import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../pages/Dashboard.vue';
import StartupList from '../pages/StartupList.vue';
import InvestorList from '../pages/InvestorList.vue';
import NewsFeed from '../pages/NewsFeed.vue';

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard
    },
    {
        path: '/startups',
        name: 'startups',
        component: StartupList
    },
    {
        path: '/investors',
        name: 'investors',
        component: InvestorList
    },
    {
        path: '/news',
        name: 'news',
        component: NewsFeed
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

export default router