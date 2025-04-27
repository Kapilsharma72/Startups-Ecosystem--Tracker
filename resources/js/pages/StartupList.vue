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

        <!-- Loading State -->
        <div v-if="isLoading" class="card flex items-center justify-center p-12">
            <div class="text-center">
                <svg class="animate-spin h-10 w-10 text-blue-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-4 text-lg text-gray-400">Loading startups...</p>
            </div>
        </div>

        <!-- Map View (if enabled) -->
        <div v-else-if="showMap" class="card h-96 mb-6">
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
        <div v-if="!isLoading" class="card">
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
                            <option v-for="industry in uniqueIndustries" :key="industry" :value="industry">
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
                            <option v-for="stage in uniqueStages" :key="stage" :value="stage">
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
        <div v-if="!isLoading && viewMode === 'cards'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="startup in paginatedStartups" :key="startup.id" class="card hover:shadow-lg transition-shadow duration-300">
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
        <div v-else-if="!isLoading" class="card">
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
                        <tr v-for="startup in paginatedStartups" :key="startup.id" class="hover:bg-gray-700 transition-colors duration-150">
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
        <div v-if="!isLoading" class="flex items-center justify-between">
            <div class="text-sm text-gray-400">
                Showing {{ paginatedStartups.length }} of {{ filteredStartups.length }} startups
            </div>
            <div class="flex space-x-2">
                <button 
                    class="btn-secondary" 
                    :disabled="currentPage === 1"
                    @click="currentPage--"
                    :class="{'opacity-50': currentPage === 1}"
                >
                    Previous
                </button>
                <button 
                    class="btn-secondary" 
                    :disabled="currentPage === totalPages || totalPages === 0"
                    @click="currentPage++"
                    :class="{'opacity-50': currentPage === totalPages || totalPages === 0}"
                >
                    Next
                </button>
            </div>
        </div>
        
        <!-- No results message -->
        <div v-if="!isLoading && filteredStartups.length === 0" class="card p-8 text-center">
            <svg class="h-16 w-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-4 text-xl text-gray-400">No startups found matching your criteria</p>
            <button @click="clearFilters" class="mt-4 btn-secondary">Clear filters</button>
        </div>

        <!-- Edit Startup Modal -->
        <div v-if="showEditModal" class="fixed inset-0 overflow-y-auto z-50">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full max-h-[90vh] overflow-y-auto">
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-primary-600 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-white">
                                    Edit Startup
                                </h3>
                                <div class="mt-4">
                                    <form @submit.prevent="updateStartup" class="space-y-4">
                                        <!-- Basic Information -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="edit-name" class="block text-sm font-medium text-gray-300">Name*</label>
                                                <input type="text" id="edit-name" v-model="editForm.name" class="mt-1 input-field w-full" required />
                                            </div>
                                            <div>
                                                <label for="edit-website" class="block text-sm font-medium text-gray-300">Website</label>
                                                <input type="url" id="edit-website" v-model="editForm.website" class="mt-1 input-field w-full" placeholder="https://..." />
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="edit-location" class="block text-sm font-medium text-gray-300">Location*</label>
                                                <input type="text" id="edit-location" v-model="editForm.location" class="mt-1 input-field w-full" required />
                                            </div>
                                            <div>
                                                <label for="edit-logo" class="block text-sm font-medium text-gray-300">Logo URL</label>
                                                <input type="url" id="edit-logo" v-model="editForm.logo" class="mt-1 input-field w-full" placeholder="https://..." />
                                            </div>
                                        </div>

                                        <!-- Industry & Stage -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="edit-industry" class="block text-sm font-medium text-gray-300">Industry*</label>
                                                <select id="edit-industry" v-model="editForm.industry" class="mt-1 input-field w-full" required>
                                                    <option v-for="industry in uniqueIndustries" :key="industry" :value="industry">{{ industry }}</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="edit-stage" class="block text-sm font-medium text-gray-300">Stage*</label>
                                                <select id="edit-stage" v-model="editForm.stage" class="mt-1 input-field w-full" required>
                                                    <option v-for="stage in uniqueStages" :key="stage" :value="stage">{{ stage }}</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Funding Information -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="edit-funding" class="block text-sm font-medium text-gray-300">Funding Amount (in millions)*</label>
                                                <input type="number" id="edit-funding" v-model="editForm.funding" step="0.01" min="0" class="mt-1 input-field w-full" required />
                                            </div>
                                            <div>
                                                <label for="edit-funding-round" class="block text-sm font-medium text-gray-300">Funding Round*</label>
                                                <select id="edit-funding-round" v-model="editForm.fundingRound" class="mt-1 input-field w-full" required>
                                                    <option value="Seed">Seed</option>
                                                    <option value="Angel">Angel</option>
                                                    <option value="Series A">Series A</option>
                                                    <option value="Series B">Series B</option>
                                                    <option value="Series C">Series C</option>
                                                    <option value="IPO">IPO</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label for="edit-description" class="block text-sm font-medium text-gray-300">Description</label>
                                            <textarea id="edit-description" v-model="editForm.description" rows="3" class="mt-1 input-field w-full"></textarea>
                                        </div>

                                        <!-- Technologies -->
                                        <div>
                                            <label for="edit-technologies" class="block text-sm font-medium text-gray-300">Technologies</label>
                                            <input 
                                                type="text" 
                                                id="edit-technologies"
                                                v-model="editForm.technologies" 
                                                placeholder="Comma separated" 
                                                class="mt-1 input-field w-full"
                                            />
                                        </div>

                                        <!-- Employee Numbers -->
                                        <div>
                                            <label for="edit-emp-numbers" class="block text-sm font-medium text-gray-300">Employee Numbers</label>
                                            <input type="number" id="edit-emp-numbers" v-model="editForm.emp_numbers" class="mt-1 input-field w-full" />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            @click="updateStartup" 
                            type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Update Startup
                        </button>
                        <button 
                            @click="showEditModal = false" 
                            type="button" 
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-600 shadow-sm px-4 py-2 bg-gray-800 text-base font-medium text-gray-300 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

export default {
    name: 'StartupList',
    setup() {
        const viewMode = ref('cards');
        const showMap = ref(false);
        const currentPage = ref(1);
        const itemsPerPage = ref(9);
        const isLoading = ref(true);
        const startups = ref([]);
        const filters = ref({
            search: '',
            industry: '',
            stage: ''
        });
        const sortBy = ref('name');
        const sortDesc = ref(false);
        const showEditModal = ref(false);
        const editForm = ref({
            id: null,
            name: '',
            website: '',
            location: '',
            logo: '',
            industry: '',
            stage: '',
            funding: null,
            fundingRound: '',
            description: '',
            technologies: '',
            emp_numbers: null
        });

        // Fetch startups list data from API
        const fetchStartups = async () => {
            try {
                isLoading.value = true;
                const baseUrl = window.location.origin;
                const response = await axios.get(`${baseUrl}/api/startup-list-data`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    withCredentials: true
                });
                
                startups.value = response.data;
                isLoading.value = false;
            } catch (error) {
                console.error('Error fetching startups:', error);
                alert('Failed to load startups data');
                isLoading.value = false;
            }
        };
        
        // Extract unique industries from the startups list
        const uniqueIndustries = computed(() => {
            const industries = new Set(startups.value.map(startup => startup.industry));
            return Array.from(industries).sort();
        });
        
        // Extract unique stages from the startups list
        const uniqueStages = computed(() => {
            const stages = new Set(startups.value.map(startup => startup.stage));
            return Array.from(stages).sort();
        });

        // Apply filters and sorting to the startups list
        const filteredStartups = computed(() => {
            return startups.value
                .filter(startup => {
                    const matchesSearch = !filters.value.search || 
                        startup.name.toLowerCase().includes(filters.value.search.toLowerCase()) ||
                        startup.location.toLowerCase().includes(filters.value.search.toLowerCase());
                    const matchesIndustry = !filters.value.industry || startup.industry === filters.value.industry;
                    const matchesStage = !filters.value.stage || startup.stage === filters.value.stage;
                    return matchesSearch && matchesIndustry && matchesStage;
                })
                .sort((a, b) => {
                    let comparison = 0;
                    if (a[sortBy.value] > b[sortBy.value]) comparison = 1;
                    if (a[sortBy.value] < b[sortBy.value]) comparison = -1;
                    return sortDesc.value ? comparison * -1 : comparison;
                });
        });

        // Calculate total pages for pagination
        const totalPages = computed(() => {
            return Math.max(1, Math.ceil(filteredStartups.value.length / itemsPerPage.value));
        });
        
        // Get current page of startups
        const paginatedStartups = computed(() => {
            const start = (currentPage.value - 1) * itemsPerPage.value;
            const end = start + itemsPerPage.value;
            return filteredStartups.value.slice(start, end);
        });

        // Initialize and set up auto-refresh
        onMounted(() => {
            fetchStartups();
            
            // Refresh data every minute (60000 ms)
            const refreshInterval = setInterval(() => {
                fetchStartups();
            }, 60000);
            
            // Clean up interval on component unmount
            return () => {
                clearInterval(refreshInterval);
            };
        });

        // Toggle between card and table views
        const toggleViewMode = () => {
            viewMode.value = viewMode.value === 'cards' ? 'table' : 'cards';
        };
        
        // Toggle map view
        const toggleMapView = () => {
            showMap.value = !showMap.value;
        };
        
        // Handle sorting
        const sort = (column) => {
            if (sortBy.value === column) {
                sortDesc.value = !sortDesc.value;
            } else {
                sortBy.value = column;
                sortDesc.value = false;
            }
        };
        
        // Get CSS class for stage badge
        const getStageBadgeClass = (stage) => {
            const classes = {
                'Seed': 'badge badge-warning',
                'Series A': 'badge badge-primary',
                'Series B': 'badge badge-success',
                'Series C': 'badge badge-success',
                'Growth': 'badge badge-success',
                'IPO': 'badge badge-info'
            };
            return classes[stage] || 'badge badge-secondary';
        };
        
        // Clear all filters
        const clearFilters = () => {
            filters.value = {
                search: '',
                industry: '',
                stage: ''
            };
            sortBy.value = 'name';
            sortDesc.value = false;
            currentPage.value = 1;
        };
        
        // View startup details
        const viewStartup = (startup) => {
            // Implement view startup logic
            console.log('View startup:', startup);
            
            // In a real application, you might navigate to a details page
            // router.push(`/startups/${startup.id}`);
        };
        
        // Edit startup
        const editStartup = (startup) => {
            showEditModal.value = true;
            // Clone the startup object to avoid direct mutation
            editForm.value = {
                id: startup.id,
                name: startup.name,
                website: startup.website || '',
                location: startup.location,
                logo: startup.logo || '',
                industry: startup.industry,
                stage: startup.stage,
                funding: startup.funding,
                fundingRound: startup.fundingRound,
                description: startup.description || '',
                technologies: startup.technologies || '',
                emp_numbers: startup.employees || null
            };
        };
        
        // Update startup
        const updateStartup = async () => {
            try {
                // Show loading state
                isLoading.value = true;
                
                // Prepare the data for API
                const formData = {
                    name: editForm.value.name,
                    logo: editForm.value.logo,
                    location: editForm.value.location,
                    industry: editForm.value.industry,
                    stage: editForm.value.stage,
                    funding: parseFloat(editForm.value.funding),
                    funding_round: editForm.value.fundingRound,
                    description: editForm.value.description,
                    website: editForm.value.website,
                    emp_numbers: editForm.value.emp_numbers ? parseInt(editForm.value.emp_numbers) : null,
                    // Ensure technologies is sent as a string
                    technologies: Array.isArray(editForm.value.technologies) 
                        ? editForm.value.technologies.join(', ') 
                        : (editForm.value.technologies || '')
                };
                
                // Include CSRF token in headers
                const headers = {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                };
                
                // Get the base URL of the application
                const baseUrl = window.location.origin;
                
                // Make the API request to update the startup
                const response = await axios.put(`${baseUrl}/api/startups/${editForm.value.id}`, formData, {
                    headers,
                    withCredentials: true
                });
                
                // Close the modal and show success message
                showEditModal.value = false;
                alert(response.data.message || 'Startup updated successfully');
                
                // Refresh the startups list
                await fetchStartups();
                
            } catch (error) {
                console.error('Error updating startup:', error);
                let errorMessage = 'Failed to update startup';
                
                if (error.response && error.response.data && error.response.data.errors) {
                    const errors = error.response.data.errors;
                    errorMessage = Object.values(errors).flat().join(', ');
                } else if (error.response && error.response.data && error.response.data.message) {
                    errorMessage = error.response.data.message;
                }
                
                alert('Error: ' + errorMessage);
            } finally {
                // End loading state
                isLoading.value = false;
            }
        };

        // Add new startup
        const addStartup = () => {
            // Navigate to dashboard to use the existing add startup modal
            window.location.href = '/dashboard';
        };

        return {
            viewMode,
            showMap,
            currentPage,
            itemsPerPage,
            isLoading,
            startups,
            filters,
            sortBy,
            sortDesc,
            uniqueIndustries,
            uniqueStages,
            filteredStartups,
            totalPages,
            paginatedStartups,
            toggleViewMode,
            toggleMapView,
            sort,
            getStageBadgeClass,
            clearFilters,
            viewStartup,
            editStartup,
            addStartup,
            showEditModal,
            editForm,
            updateStartup
        };
    }
};
</script>