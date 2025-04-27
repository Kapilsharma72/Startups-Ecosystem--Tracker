<template>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold text-white">Investors</h1>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <button @click="toggleViewMode" class="btn-secondary flex items-center">
                    <svg v-if="viewMode === 'cards'" class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <svg v-else class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    {{ viewMode === 'cards' ? 'Table View' : 'Card View' }}
                </button>
                <button @click="toggleNetworkView" class="btn-secondary flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    {{ showNetwork ? 'Hide Network' : 'Show Network' }}
                </button>
                <button @click="addInvestor" class="btn-primary flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Investor
                </button>
            </div>
        </div>

        <!-- Network View (if enabled) -->
        <div v-if="showNetwork" class="card h-96 mb-6">
            <div class="h-full w-full bg-gray-800 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <svg class="h-16 w-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <p class="mt-2 text-gray-400">Investor network visualization coming soon</p>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="card">
            <div class="flex flex-col space-y-4 sm:flex-row sm:space-x-4 sm:space-y-0">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-white">Search</label>
                    <div class="mt-1 relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            type="text"
                            id="search"
                            v-model="filters.search"
                            class="input-field w-full pl-10"
                            placeholder="Search investors..."
                        />
                    </div>
                </div>
                <div class="flex-1">
                    <label for="type" class="block text-sm font-medium text-white">Type</label>
                    <div class="mt-1">
                        <select
                            id="type"
                            v-model="filters.type"
                            class="input-field w-full"
                        >
                            <option value="">All Types</option>
                            <option v-for="type in investorTypes" :key="type" :value="type">
                                {{ type }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex-1">
                    <label for="focus" class="block text-sm font-medium text-white">Focus</label>
                    <div class="mt-1">
                        <select
                            id="focus"
                            v-model="filters.focus"
                            class="input-field w-full"
                        >
                            <option value="">All Focus Areas</option>
                            <option v-for="focus in focusAreas" :key="focus" :value="focus">
                                {{ focus }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex-1">
                    <label for="sort" class="block text-sm font-medium text-white">Sort By</label>
                    <div class="mt-1">
                        <select
                            id="sort"
                            v-model="sortBy"
                            class="input-field w-full"
                        >
                            <option value="name">Name</option>
                            <option value="portfolioSize">Portfolio Size</option>
                            <option value="totalInvestment">Total Investment</option>
                            <option value="founded">Founded Date</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card View -->
        <div v-if="viewMode === 'cards'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="investor in filteredInvestors" :key="investor.id" class="card hover:shadow-lg transition-shadow duration-300">
                <div class="flex flex-col h-full">
                    <div class="flex-shrink-0">
                        <div class="h-40 w-full bg-gradient-to-r from-primary-600 to-primary-800 rounded-t-lg flex items-center justify-center">
                            <img :src="investor.logo" :alt="investor.name" class="h-24 w-24 rounded-full border-4 border-white">
                        </div>
                    </div>
                    <div class="flex-1 p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-white">{{ investor.name }}</h3>
                            <span :class="getTypeBadgeClass(investor.type)" class="badge">{{ investor.type }}</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-400">{{ investor.location }}</p>
                        <div class="mt-2 flex flex-wrap gap-1">
                            <span v-for="focus in investor.focus" :key="focus" class="badge badge-secondary">
                                {{ focus }}
                            </span>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400">Portfolio</p>
                                <p class="text-sm font-medium text-white">{{ investor.portfolioSize }} companies</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Investment</p>
                                <p class="text-sm font-medium text-white">${{ investor.totalInvestment }}M</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Founded</p>
                                <p class="text-sm font-medium text-white">{{ investor.founded }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Avg. Check</p>
                                <p class="text-sm font-medium text-white">${{ investor.avgCheck }}M</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="text-xs text-gray-400">Recent Investments</p>
                            <div class="mt-1 flex -space-x-2">
                                <img v-for="(startup, index) in investor.recentInvestments.slice(0, 3)" :key="index" 
                                     :src="startup.logo" :alt="startup.name" 
                                     class="h-6 w-6 rounded-full border-2 border-gray-800">
                                <div v-if="investor.recentInvestments.length > 3" 
                                     class="h-6 w-6 rounded-full border-2 border-gray-800 bg-gray-700 flex items-center justify-center">
                                    <span class="text-xs text-white">+{{ investor.recentInvestments.length - 3 }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button @click="viewInvestor(investor)" class="btn-secondary flex-1">
                                View Details
                            </button>
                            <button @click="editInvestor(investor)" class="btn-primary flex-1">
                                Edit
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table View -->
        <div v-else class="card">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th @click="sort('name')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Name
                                    <svg v-if="sortBy === 'name'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th @click="sort('type')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Type
                                    <svg v-if="sortBy === 'type'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th @click="sort('focus')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Focus
                                    <svg v-if="sortBy === 'focus'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th @click="sort('portfolioSize')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Portfolio Size
                                    <svg v-if="sortBy === 'portfolioSize'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th @click="sort('totalInvestment')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Total Investment
                                    <svg v-if="sortBy === 'totalInvestment'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="investor in filteredInvestors" :key="investor.id" class="hover:bg-gray-700 transition-colors duration-150">
                            <td>
                                <div class="flex items-center">
                                    <img :src="investor.logo" :alt="investor.name" class="h-8 w-8 rounded-full">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ investor.name }}</div>
                                        <div class="text-sm text-gray-400">{{ investor.location }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span :class="getTypeBadgeClass(investor.type)" class="badge">{{ investor.type }}</span>
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <span v-for="focus in investor.focus" :key="focus" class="badge badge-secondary">
                                        {{ focus }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm text-white">{{ investor.portfolioSize }} companies</div>
                                <div class="text-sm text-gray-400">${{ investor.avgCheck }}M avg. check</div>
                            </td>
                            <td>
                                <div class="text-sm text-white">${{ investor.totalInvestment }}M</div>
                                <div class="text-sm text-gray-400">{{ investor.founded }}</div>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button @click="viewInvestor(investor)" class="btn-secondary">
                                        View
                                    </button>
                                    <button @click="editInvestor(investor)" class="btn-primary">
                                        Edit
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-400">
                Showing {{ filteredInvestors.length }} of {{ investors.length }} investors
            </div>
            <div class="flex space-x-2">
                <button class="btn-secondary" :disabled="currentPage === 1">
                    Previous
                </button>
                <button class="btn-secondary" :disabled="currentPage === totalPages">
                    Next
                </button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'InvestorList',
    data() {
        return {
            viewMode: 'cards',
            showNetwork: false,
            currentPage: 1,
            itemsPerPage: 9,
            filters: {
                search: '',
                type: '',
                focus: ''
            },
            sortBy: 'name',
            sortDesc: false,
            investorTypes: ['Venture Capital', 'Angel Investor', 'Private Equity', 'Corporate VC', 'Accelerator', 'Family Office'],
            focusAreas: ['Technology', 'Healthcare', 'FinTech', 'E-commerce', 'CleanTech', 'AI/ML', 'EdTech', 'Biotech'],
            investors: [
                {
                    id: 1,
                    name: 'Growth Capital Ventures',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'San Francisco, CA',
                    type: 'Venture Capital',
                    focus: ['Technology', 'AI/ML'],
                    portfolioSize: 25,
                    totalInvestment: 150,
                    founded: '2015',
                    avgCheck: 5,
                    recentInvestments: [
                        { name: 'TechStart Inc.', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' },
                        { name: 'AI Analytics', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' },
                        { name: 'ShopSmart', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' },
                        { name: 'EcoClean Energy', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }
                    ]
                },
                {
                    id: 2,
                    name: 'HealthTech Investors',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'Boston, MA',
                    type: 'Private Equity',
                    focus: ['Healthcare', 'Biotech'],
                    portfolioSize: 15,
                    totalInvestment: 200,
                    founded: '2010',
                    avgCheck: 8,
                    recentInvestments: [
                        { name: 'HealthTech Solutions', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' },
                        { name: 'BioTech Innovations', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' },
                        { name: 'MedTech Solutions', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }
                    ]
                },
                {
                    id: 3,
                    name: 'FinTech Angels',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'New York, NY',
                    type: 'Angel Investor',
                    focus: ['FinTech'],
                    portfolioSize: 8,
                    totalInvestment: 20,
                    founded: '2018',
                    avgCheck: 1.5,
                    recentInvestments: [
                        { name: 'FinTech Pro', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' },
                        { name: 'PayTech', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }
                    ]
                },
                {
                    id: 4,
                    name: 'EcoVentures',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'Austin, TX',
                    type: 'Venture Capital',
                    focus: ['CleanTech', 'Sustainability'],
                    portfolioSize: 12,
                    totalInvestment: 80,
                    founded: '2017',
                    avgCheck: 4,
                    recentInvestments: [
                        { name: 'EcoClean Energy', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' },
                        { name: 'GreenTech Solutions', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }
                    ]
                },
                {
                    id: 5,
                    name: 'Tech Accelerator Fund',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'Seattle, WA',
                    type: 'Accelerator',
                    focus: ['Technology', 'SaaS'],
                    portfolioSize: 30,
                    totalInvestment: 40,
                    founded: '2019',
                    avgCheck: 0.5,
                    recentInvestments: [
                        { name: 'SaaS Solutions', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' },
                        { name: 'CloudTech', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' },
                        { name: 'DevOps Pro', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }
                    ]
                },
                {
                    id: 6,
                    name: 'Global Family Office',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'London, UK',
                    type: 'Family Office',
                    focus: ['Technology', 'Healthcare', 'Real Estate'],
                    portfolioSize: 20,
                    totalInvestment: 300,
                    founded: '2005',
                    avgCheck: 10,
                    recentInvestments: [
                        { name: 'TechStart Inc.', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' },
                        { name: 'HealthTech Solutions', logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80' }
                    ]
                }
            ]
        }
    },
    computed: {
        filteredInvestors() {
            return this.investors
                .filter(investor => {
                    const matchesSearch = investor.name.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                        investor.location.toLowerCase().includes(this.filters.search.toLowerCase());
                    const matchesType = !this.filters.type || investor.type === this.filters.type;
                    const matchesFocus = !this.filters.focus || investor.focus.includes(this.filters.focus);
                    return matchesSearch && matchesType && matchesFocus;
                })
                .sort((a, b) => {
                    let comparison = 0;
                    if (a[this.sortBy] > b[this.sortBy]) comparison = 1;
                    if (a[this.sortBy] < b[this.sortBy]) comparison = -1;
                    return this.sortDesc ? comparison * -1 : comparison;
                });
        },
        totalPages() {
            return Math.ceil(this.filteredInvestors.length / this.itemsPerPage);
        },
        paginatedInvestors() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredInvestors.slice(start, end);
        }
    },
    methods: {
        toggleViewMode() {
            this.viewMode = this.viewMode === 'cards' ? 'table' : 'cards';
        },
        toggleNetworkView() {
            this.showNetwork = !this.showNetwork;
        },
        sort(column) {
            if (this.sortBy === column) {
                this.sortDesc = !this.sortDesc;
            } else {
                this.sortBy = column;
                this.sortDesc = false;
            }
        },
        getTypeBadgeClass(type) {
            const classes = {
                'Venture Capital': 'badge badge-primary',
                'Angel Investor': 'badge badge-warning',
                'Private Equity': 'badge badge-success',
                'Corporate VC': 'badge badge-info',
                'Accelerator': 'badge badge-secondary',
                'Family Office': 'badge badge-purple-500'
            };
            return classes[type] || 'badge badge-secondary';
        },
        viewInvestor(investor) {
            // Implement view investor logic
            console.log('View investor:', investor);
        },
        editInvestor(investor) {
            // Implement edit investor logic
            console.log('Edit investor:', investor);
        },
        addInvestor() {
            // Implement add investor logic
            console.log('Add new investor');
        }
    }
}
</script> 