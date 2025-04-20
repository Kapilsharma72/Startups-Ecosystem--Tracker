import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../components/Dashboard.vue'
import StartupList from '../components/StartupList.vue'
import InvestorList from '../components/InvestorList.vue'
import NewsFeed from '../components/NewsFeed.vue'

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