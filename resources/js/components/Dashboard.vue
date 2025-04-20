<template>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-white">Dashboard</h1>
            <div class="flex space-x-3">
                <button class="btn-primary flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Startup
                </button>
                <button class="btn-secondary flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                    </svg>
                    Export Data
                </button>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <div class="card bg-gradient-to-br from-blue-600 to-blue-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-500 bg-opacity-30">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-medium text-white">Total Startups</h3>
                        <div class="flex items-baseline flex-wrap">
                            <p class="text-2xl font-semibold text-white">{{ stats.totalStartups }}</p>
                            <p class="ml-2 text-sm text-blue-200 whitespace-nowrap">+{{ stats.startupGrowth }}% from last month</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-gradient-to-br from-green-600 to-green-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-500 bg-opacity-30">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-medium text-white">Total Funding</h3>
                        <div class="flex items-baseline flex-wrap">
                            <p class="text-2xl font-semibold text-white">${{ formatNumber(stats.totalFunding) }}</p>
                            <p class="ml-2 text-sm text-green-200 whitespace-nowrap">+{{ stats.fundingGrowth }}% from last month</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-gradient-to-br from-purple-600 to-purple-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-500 bg-opacity-30">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-medium text-white">Active Investors</h3>
                        <div class="flex items-baseline flex-wrap">
                            <p class="text-2xl font-semibold text-white">{{ stats.activeInvestors }}</p>
                            <p class="ml-2 text-sm text-purple-200 whitespace-nowrap">+{{ stats.investorGrowth }}% from last month</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-gradient-to-br from-yellow-600 to-yellow-800">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-500 bg-opacity-30">
                        <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5">
                        <h3 class="text-lg font-medium text-white">Success Rate</h3>
                        <div class="flex items-baseline flex-wrap">
                            <p class="text-2xl font-semibold text-white">{{ stats.successRate }}%</p>
                            <p class="ml-2 text-sm text-yellow-200 whitespace-nowrap">+{{ stats.successGrowth }}% from last month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Funding Trends Chart -->
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-white">Funding Trends</h3>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs rounded-full bg-gray-700 text-white">Monthly</button>
                        <button class="px-3 py-1 text-xs rounded-full bg-primary-600 text-white">Quarterly</button>
                        <button class="px-3 py-1 text-xs rounded-full bg-gray-700 text-white">Yearly</button>
                    </div>
                </div>
                <div class="h-80">
                    <canvas ref="fundingChart"></canvas>
                </div>
            </div>

            <!-- Industry Distribution Chart -->
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-white">Industry Distribution</h3>
                    <button class="text-gray-400 hover:text-white">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
                <div class="h-80">
                    <canvas ref="industryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Recent Activity and Top Startups -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Recent Activity -->
            <div class="card">
                <h3 class="text-lg font-medium text-white mb-4">Recent Activity</h3>
                <div class="flow-root">
                    <ul class="-mb-8">
                        <li v-for="(activity, index) in recentActivity" :key="index">
                            <div class="relative pb-8">
                                <span v-if="index !== recentActivity.length - 1" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-700" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span :class="getActivityIconClass(activity.type)" class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-gray-800">
                                            <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path v-if="activity.type === 'funding'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                <path v-else-if="activity.type === 'launch'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                <path v-else-if="activity.type === 'partnership'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                        <div>
                                            <p class="text-sm text-white">{{ activity.description }}</p>
                                        </div>
                                        <div class="text-right text-sm whitespace-nowrap text-gray-400">
                                            <time :datetime="activity.date">{{ formatDate(activity.date) }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Top Startups -->
            <div class="card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-white">Top Startups</h3>
                    <a href="/startups" class="text-sm text-primary-400 hover:text-primary-300">View all</a>
                </div>
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Startup</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Industry</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Funding</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Growth</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            <tr v-for="startup in topStartups" :key="startup.id" class="hover:bg-gray-800">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" :src="startup.logo" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-white">{{ startup.name }}</div>
                                            <div class="text-sm text-gray-400">{{ startup.location }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">{{ startup.industry }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-white">${{ formatNumber(startup.funding) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getGrowthBadgeClass(startup.growth)" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full">
                                        {{ startup.growth > 0 ? '+' : '' }}{{ startup.growth }}%
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, onMounted } from 'vue';
import { Chart, registerables } from 'chart.js';
import { Line, Doughnut } from 'vue-chartjs';

Chart.register(...registerables);

export default {
    name: 'Dashboard',
    components: {
        Line,
        Doughnut
    },
    setup() {
        const fundingChart = ref(null);
        const industryChart = ref(null);

        // Sample data
        const stats = ref({
            totalStartups: 248,
            startupGrowth: 12,
            totalFunding: 1250000000,
            fundingGrowth: 8,
            activeInvestors: 56,
            investorGrowth: 5,
            successRate: 78,
            successGrowth: 3
        });

        const recentActivity = ref([
            {
                type: 'funding',
                description: 'TechStart Inc. raised $10M in Series A funding',
                date: '2024-03-15T10:00:00Z'
            },
            {
                type: 'launch',
                description: 'HealthTech Solutions launched their new AI platform',
                date: '2024-03-14T15:30:00Z'
            },
            {
                type: 'partnership',
                description: 'FinTech Innovations partnered with Global Bank',
                date: '2024-03-13T09:15:00Z'
            },
            {
                type: 'funding',
                description: 'EcoGreen raised $5M in seed funding',
                date: '2024-03-12T14:45:00Z'
            },
            {
                type: 'other',
                description: 'AI Solutions expanded to European market',
                date: '2024-03-11T11:20:00Z'
            }
        ]);

        const topStartups = ref([
            {
                id: 1,
                name: 'TechStart Inc.',
                logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                location: 'San Francisco, CA',
                industry: 'AI',
                funding: 25000000,
                growth: 25
            },
            {
                id: 2,
                name: 'HealthTech Solutions',
                logo: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                location: 'Boston, MA',
                industry: 'Healthcare',
                funding: 18000000,
                growth: 18
            },
            {
                id: 3,
                name: 'FinTech Innovations',
                logo: 'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                location: 'New York, NY',
                industry: 'FinTech',
                funding: 15000000,
                growth: 15
            },
            {
                id: 4,
                name: 'EcoGreen',
                logo: 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                location: 'Austin, TX',
                industry: 'CleanTech',
                funding: 12000000,
                growth: 12
            },
            {
                id: 5,
                name: 'AI Solutions',
                logo: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                location: 'Seattle, WA',
                industry: 'AI',
                funding: 10000000,
                growth: -5
            }
        ]);

        // Initialize charts
        onMounted(() => {
            // Funding Trends Chart
            const fundingCtx = fundingChart.value.getContext('2d');
            new Chart(fundingCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [
                        {
                            label: 'Funding Amount ($M)',
                            data: [65, 59, 80, 81, 56, 55, 40, 65, 59, 80, 81, 56],
                            fill: false,
                            borderColor: '#3B82F6',
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)'
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)'
                            }
                        }
                    }
                }
            });

            // Industry Distribution Chart
            const industryCtx = industryChart.value.getContext('2d');
            new Chart(industryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['AI', 'FinTech', 'Healthcare', 'CleanTech', 'E-commerce', 'Other'],
                    datasets: [
                        {
                            data: [30, 25, 15, 10, 12, 8],
                            backgroundColor: [
                                '#3B82F6',
                                '#10B981',
                                '#F59E0B',
                                '#EF4444',
                                '#8B5CF6',
                                '#6B7280'
                            ]
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: 'rgba(255, 255, 255, 0.7)'
                            }
                        }
                    }
                }
            });
        });

        // Helper functions
        const formatNumber = (num) => {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        };

        const formatDate = (dateString) => {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        };

        const getActivityIconClass = (type) => {
            const classes = {
                'funding': 'bg-green-500',
                'launch': 'bg-blue-500',
                'partnership': 'bg-purple-500',
                'other': 'bg-gray-500'
            };
            return classes[type] || 'bg-gray-500';
        };

        const getGrowthBadgeClass = (growth) => {
            if (growth > 0) {
                return 'bg-green-100 text-green-800';
            } else if (growth < 0) {
                return 'bg-red-100 text-red-800';
            } else {
                return 'bg-gray-100 text-gray-800';
            }
        };

        return {
            fundingChart,
            industryChart,
            stats,
            recentActivity,
            topStartups,
            formatNumber,
            formatDate,
            getActivityIconClass,
            getGrowthBadgeClass
        };
    }
};
</script> 