<template>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold text-white">Startups</h1>
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
                <button @click="toggleMapView" class="btn-secondary flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    {{ showMap ? 'Hide Map' : 'Show Map' }}
                </button>
                <button @click="addStartup" class="btn-primary flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Startup
                </button>
            </div>
        </div>

        <!-- Map View (if enabled) -->
        <div v-if="showMap" class="card h-96 mb-6">
            <div class="h-full w-full bg-gray-800 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <svg class="h-16 w-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <p class="mt-2 text-gray-400">Map view coming soon</p>
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
                            placeholder="Search startups..."
                        />
                    </div>
                </div>
                <div class="flex-1">
                    <label for="industry" class="block text-sm font-medium text-white">Industry</label>
                    <div class="mt-1">
                        <select
                            id="industry"
                            v-model="filters.industry"
                            class="input-field w-full"
                        >
                            <option value="">All Industries</option>
                            <option v-for="industry in industries" :key="industry" :value="industry">
                                {{ industry }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="flex-1">
                    <label for="stage" class="block text-sm font-medium text-white">Stage</label>
                    <div class="mt-1">
                        <select
                            id="stage"
                            v-model="filters.stage"
                            class="input-field w-full"
                        >
                            <option value="">All Stages</option>
                            <option v-for="stage in stages" :key="stage" :value="stage">
                                {{ stage }}
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
                            <option value="funding">Funding</option>
                            <option value="founded">Founded Date</option>
                            <option value="employees">Employees</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card View -->
        <div v-if="viewMode === 'cards'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="startup in filteredStartups" :key="startup.id" class="card hover:shadow-lg transition-shadow duration-300">
                <div class="flex flex-col h-full">
                    <div class="flex-shrink-0">
                        <div class="h-40 w-full bg-gradient-to-r from-primary-600 to-primary-800 rounded-t-lg flex items-center justify-center">
                            <img :src="startup.logo" :alt="startup.name" class="h-24 w-24 rounded-full border-4 border-white">
                        </div>
                    </div>
                    <div class="flex-1 p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-white">{{ startup.name }}</h3>
                            <span :class="getStageBadgeClass(startup.stage)" class="badge">{{ startup.stage }}</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-400">{{ startup.location }}</p>
                        <div class="mt-2">
                            <span class="badge badge-secondary">{{ startup.industry }}</span>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400">Funding</p>
                                <p class="text-sm font-medium text-white">${{ startup.funding }}M</p>
                                <p class="text-xs text-gray-400">{{ startup.fundingRound }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Founded</p>
                                <p class="text-sm font-medium text-white">{{ startup.founded }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Employees</p>
                                <p class="text-sm font-medium text-white">{{ startup.employees }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Growth</p>
                                <p class="text-sm font-medium" :class="startup.growth >= 0 ? 'text-green-500' : 'text-red-500'">
                                    {{ startup.growth >= 0 ? '+' : '' }}{{ startup.growth }}%
                                </p>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button @click="viewStartup(startup)" class="btn-secondary flex-1">
                                View Details
                            </button>
                            <button @click="editStartup(startup)" class="btn-primary flex-1">
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
                            <th @click="sort('industry')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Industry
                                    <svg v-if="sortBy === 'industry'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th @click="sort('stage')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Stage
                                    <svg v-if="sortBy === 'stage'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th @click="sort('funding')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Funding
                                    <svg v-if="sortBy === 'funding'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th @click="sort('employees')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Employees
                                    <svg v-if="sortBy === 'employees'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="startup in filteredStartups" :key="startup.id" class="hover:bg-gray-700 transition-colors duration-150">
                            <td>
                                <div class="flex items-center">
                                    <img :src="startup.logo" :alt="startup.name" class="h-8 w-8 rounded-full">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-white">{{ startup.name }}</div>
                                        <div class="text-sm text-gray-400">{{ startup.location }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-primary">{{ startup.industry }}</span>
                            </td>
                            <td>
                                <span :class="getStageBadgeClass(startup.stage)">{{ startup.stage }}</span>
                            </td>
                            <td>
                                <div class="text-sm text-white">${{ startup.funding }}M</div>
                                <div class="text-sm text-gray-400">{{ startup.fundingRound }}</div>
                            </td>
                            <td>
                                <div class="text-sm text-white">{{ startup.employees }}</div>
                                <div class="text-sm" :class="startup.growth >= 0 ? 'text-green-500' : 'text-red-500'">
                                    {{ startup.growth >= 0 ? '+' : '' }}{{ startup.growth }}%
                                </div>
                            </td>
                            <td>
                                <div class="flex space-x-2">
                                    <button @click="viewStartup(startup)" class="btn-secondary">
                                        View
                                    </button>
                                    <button @click="editStartup(startup)" class="btn-primary">
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
                Showing {{ filteredStartups.length }} of {{ startups.length }} startups
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
    name: 'StartupList',
    data() {
        return {
            viewMode: 'cards',
            showMap: false,
            currentPage: 1,
            itemsPerPage: 9,
            filters: {
                search: '',
                industry: '',
                stage: ''
            },
            sortBy: 'name',
            sortDesc: false,
            industries: ['Technology', 'Healthcare', 'FinTech', 'E-commerce', 'CleanTech', 'AI/ML', 'EdTech', 'Biotech'],
            stages: ['Seed', 'Series A', 'Series B', 'Series C', 'Growth', 'IPO'],
            startups: [
                {
                    id: 1,
                    name: 'TechStart Inc.',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'San Francisco, CA',
                    industry: 'Technology',
                    stage: 'Series A',
                    funding: 10,
                    fundingRound: 'Series A',
                    founded: '2020',
                    employees: 45,
                    growth: 25
                },
                {
                    id: 2,
                    name: 'HealthTech Solutions',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'Boston, MA',
                    industry: 'Healthcare',
                    stage: 'Series B',
                    funding: 25,
                    fundingRound: 'Series B',
                    founded: '2018',
                    employees: 120,
                    growth: 15
                },
                {
                    id: 3,
                    name: 'FinTech Pro',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'New York, NY',
                    industry: 'FinTech',
                    stage: 'Seed',
                    funding: 2,
                    fundingRound: 'Seed',
                    founded: '2022',
                    employees: 12,
                    growth: 8
                },
                {
                    id: 4,
                    name: 'EcoClean Energy',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'Austin, TX',
                    industry: 'CleanTech',
                    stage: 'Series A',
                    funding: 8,
                    fundingRound: 'Series A',
                    founded: '2021',
                    employees: 35,
                    growth: 18
                },
                {
                    id: 5,
                    name: 'AI Analytics',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'Seattle, WA',
                    industry: 'AI/ML',
                    stage: 'Series C',
                    funding: 50,
                    fundingRound: 'Series C',
                    founded: '2017',
                    employees: 250,
                    growth: 30
                },
                {
                    id: 6,
                    name: 'ShopSmart',
                    logo: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-1.2.1&ixid=eyJhcHBfaWQiOjEyMDd9&auto=format&fit=facearea&facepad=2&w=256&h=256&q=80',
                    location: 'Chicago, IL',
                    industry: 'E-commerce',
                    stage: 'Series B',
                    funding: 15,
                    fundingRound: 'Series B',
                    founded: '2019',
                    employees: 85,
                    growth: 22
                }
            ]
        }
    },
    computed: {
        filteredStartups() {
            return this.startups
                .filter(startup => {
                    const matchesSearch = startup.name.toLowerCase().includes(this.filters.search.toLowerCase()) ||
                        startup.location.toLowerCase().includes(this.filters.search.toLowerCase());
                    const matchesIndustry = !this.filters.industry || startup.industry === this.filters.industry;
                    const matchesStage = !this.filters.stage || startup.stage === this.filters.stage;
                    return matchesSearch && matchesIndustry && matchesStage;
                })
                .sort((a, b) => {
                    let comparison = 0;
                    if (a[this.sortBy] > b[this.sortBy]) comparison = 1;
                    if (a[this.sortBy] < b[this.sortBy]) comparison = -1;
                    return this.sortDesc ? comparison * -1 : comparison;
                });
        },
        totalPages() {
            return Math.ceil(this.filteredStartups.length / this.itemsPerPage);
        },
        paginatedStartups() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredStartups.slice(start, end);
        }
    },
    methods: {
        toggleViewMode() {
            this.viewMode = this.viewMode === 'cards' ? 'table' : 'cards';
        },
        toggleMapView() {
            this.showMap = !this.showMap;
        },
        sort(column) {
            if (this.sortBy === column) {
                this.sortDesc = !this.sortDesc;
            } else {
                this.sortBy = column;
                this.sortDesc = false;
            }
        },
        getStageBadgeClass(stage) {
            const classes = {
                'Seed': 'badge badge-warning',
                'Series A': 'badge badge-primary',
                'Series B': 'badge badge-success',
                'Series C': 'badge badge-success',
                'Growth': 'badge badge-success',
                'IPO': 'badge badge-info'
            };
            return classes[stage] || 'badge badge-secondary';
        },
        viewStartup(startup) {
            // Implement view startup logic
            console.log('View startup:', startup);
        },
        editStartup(startup) {
            // Implement edit startup logic
            console.log('Edit startup:', startup);
        },
        addStartup() {
            // Implement add startup logic
            console.log('Add new startup');
        }
    }
}
</script> 