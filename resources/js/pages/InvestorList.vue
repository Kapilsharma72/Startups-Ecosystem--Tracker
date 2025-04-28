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
                <button @click="showAddModal = true" class="btn-primary flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Investor
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
                <p class="mt-4 text-lg text-gray-400">Loading investors...</p>
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
                            <option v-for="type in uniqueTypes" :key="type" :value="type">
                                {{ type }}
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
                            <option value="total_investment">Investment</option>
                            <option value="portfolio_size">Portfolio Size</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card View -->
        <div v-if="!isLoading && viewMode === 'cards'" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="investor in paginatedInvestors" :key="investor.id" class="card hover:shadow-lg transition-shadow duration-300">
                <div class="flex flex-col h-full">
                    <div class="flex-shrink-0">
                        <div class="h-40 w-full bg-gradient-to-r from-purple-600 to-purple-800 rounded-t-lg flex items-center justify-center">
                            <img :src="investor.logo" :alt="investor.name" class="h-24 w-24 rounded-full border-4 border-white">
                        </div>
                    </div>
                    <div class="flex-1 p-4">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-white">{{ investor.name }}</h3>
                            <span :class="getTypeBadgeClass(investor.type)" class="badge">{{ investor.type }}</span>
                        </div>
                        <p class="mt-1 text-sm text-gray-400">{{ investor.location }}</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span v-for="(area, index) in getArrayFromString(investor.focus_areas)" 
                                  :key="index" 
                                  class="badge badge-secondary">{{ area }}</span>
                        </div>
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-400">Total Investment</p>
                                <p class="text-sm font-medium text-white">${{ investor.total_investment }}M</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Portfolio Size</p>
                                <p class="text-sm font-medium text-white">{{ investor.portfolio_size }} Companies</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Avg Check</p>
                                <p class="text-sm font-medium text-white">${{ investor.avg_check || '0' }}M</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Founded</p>
                                <p class="text-sm font-medium text-white">{{ investor.founded || 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-400">Growth</p>
                                <p class="text-sm font-medium" :class="investor.growth >= 0 ? 'text-green-500' : 'text-red-500'">
                                    {{ investor.growth >= 0 ? '+' : '' }}{{ investor.growth }}%
                                </p>
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
                            <th @click="sort('type')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Type
                                    <svg v-if="sortBy === 'type'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th>Focus Areas</th>
                            <th @click="sort('founded')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Founded
                                    <svg v-if="sortBy === 'founded'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th @click="sort('total_investment')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Investment
                                    <svg v-if="sortBy === 'total_investment'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th @click="sort('avg_check')" class="cursor-pointer">
                                <div class="flex items-center">
                                    Avg Check
                                    <svg v-if="sortBy === 'avg_check'" :class="{'rotate-180': sortDesc}" class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </div>
                            </th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="investor in paginatedInvestors" :key="investor.id" class="hover:bg-gray-700 transition-colors duration-150">
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
                                <span :class="getTypeBadgeClass(investor.type)">{{ investor.type }}</span>
                            </td>
                            <td>
                                <div class="flex flex-wrap gap-1">
                                    <span v-for="(area, index) in getArrayFromString(investor.focus_areas).slice(0, 2)" 
                                        :key="index" 
                                        class="badge badge-secondary">{{ area }}</span>
                                    <span v-if="getArrayFromString(investor.focus_areas).length > 2" 
                                        class="badge badge-secondary">+{{ getArrayFromString(investor.focus_areas).length - 2 }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-sm text-white">{{ investor.founded || 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="text-sm text-white">${{ investor.total_investment }}M</div>
                            </td>
                            <td>
                                <div class="text-sm text-white">${{ investor.avg_check || '0' }}M</div>
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
        <div v-if="!isLoading" class="flex items-center justify-between">
            <div class="text-sm text-gray-400">
                Showing {{ paginatedInvestors.length }} of {{ filteredInvestors.length }} investors
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
        <div v-if="!isLoading && filteredInvestors.length === 0" class="card p-8 text-center">
            <svg class="h-16 w-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="mt-4 text-xl text-gray-400">No investors found matching your criteria</p>
            <button @click="clearFilters" class="mt-4 btn-secondary">Clear filters</button>
        </div>

        <!-- Add/Edit Investor Modal -->
        <div v-if="showAddModal || showEditModal" class="fixed inset-0 overflow-y-auto z-50">
            <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                    <div class="absolute inset-0 bg-gray-900 opacity-75"></div>
                </div>
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div class="inline-block align-bottom bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-xl sm:w-full max-h-[90vh] overflow-y-auto">
                    <div class="bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-purple-600 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          v-if="showAddModal" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                          v-else d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-white">
                                    {{ showAddModal ? 'Add New Investor' : 'Edit Investor' }}
                                </h3>
                                <div class="mt-4">
                                    <form @submit.prevent="showAddModal ? submitInvestor() : updateInvestor()" class="space-y-4">
                                        <!-- Basic Information -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="name" class="block text-sm font-medium text-gray-300">Name*</label>
                                                <input type="text" id="name" v-model="investorForm.name" class="mt-1 input-field w-full" required />
                                            </div>
                                            <div>
                                                <label for="website" class="block text-sm font-medium text-gray-300">Website</label>
                                                <input type="url" id="website" v-model="investorForm.website" class="mt-1 input-field w-full" placeholder="https://..." />
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="location" class="block text-sm font-medium text-gray-300">Location*</label>
                                                <input type="text" id="location" v-model="investorForm.location" class="mt-1 input-field w-full" required />
                                            </div>
                                            <div>
                                                <label for="logo" class="block text-sm font-medium text-gray-300">Logo URL</label>
                                                <input type="url" id="logo" v-model="investorForm.logo" class="mt-1 input-field w-full" placeholder="https://..." />
                                            </div>
                                        </div>

                                        <!-- Type & Focus Areas -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="type" class="block text-sm font-medium text-gray-300">Investor Type*</label>
                                                <select id="type" v-model="investorForm.type" class="mt-1 input-field w-full" required>
                                                    <option value="">Select Type</option>
                                                    <option value="Venture Capital">Venture Capital</option>
                                                    <option value="Angel Investor">Angel Investor</option>
                                                    <option value="Private Equity">Private Equity</option>
                                                    <option value="Corporate VC">Corporate VC</option>
                                                    <option value="Accelerator">Accelerator</option>
                                                    <option value="Other">Other</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label for="focus_areas" class="block text-sm font-medium text-gray-300">Focus Areas*</label>
                                                <input 
                                                    type="text" 
                                                    id="focus_areas" 
                                                    v-model="investorForm.focus_areas" 
                                                    class="mt-1 input-field w-full" 
                                                    placeholder="e.g. FinTech, AI, Healthcare (comma-separated)"
                                                    required
                                                />
                                            </div>
                                        </div>

                                        <!-- Investment & Portfolio Size -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="total_investment" class="block text-sm font-medium text-gray-300">Total Investment (in millions)*</label>
                                                <input type="number" id="total_investment" v-model="investorForm.total_investment" step="0.01" min="0" class="mt-1 input-field w-full" required />
                                            </div>
                                            <div>
                                                <label for="portfolio_size" class="block text-sm font-medium text-gray-300">Portfolio Size*</label>
                                                <input type="number" id="portfolio_size" v-model="investorForm.portfolio_size" min="0" class="mt-1 input-field w-full" required />
                                            </div>
                                        </div>

                                        <!-- Average Check & Founded Year -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="avg_check" class="block text-sm font-medium text-gray-300">Average Check Size (in millions)</label>
                                                <input type="number" id="avg_check" v-model="investorForm.avg_check" step="0.01" min="0" class="mt-1 input-field w-full" />
                                            </div>
                                            <div>
                                                <label for="founded" class="block text-sm font-medium text-gray-300">Founded Year</label>
                                                <input 
                                                    type="number" 
                                                    id="founded" 
                                                    v-model="investorForm.founded" 
                                                    min="1900" 
                                                    :max="new Date().getFullYear()" 
                                                    class="mt-1 input-field w-full" 
                                                    placeholder="e.g. 2010"
                                                />
                                            </div>
                                        </div>

                                        <!-- Description -->
                                        <div>
                                            <label for="description" class="block text-sm font-medium text-gray-300">Description</label>
                                            <textarea id="description" v-model="investorForm.description" rows="3" class="mt-1 input-field w-full"></textarea>
                                        </div>

                                        <!-- Team Members -->
                                        <div>
                                            <label for="team_members" class="block text-sm font-medium text-gray-300">Team Members</label>
                                            <input 
                                                type="text" 
                                                id="team_members" 
                                                v-model="investorForm.team_members" 
                                                class="mt-1 input-field w-full" 
                                                placeholder="e.g. John Doe, Jane Smith (comma-separated)"
                                            />
                                        </div>

                                        <!-- Investment Criteria -->
                                        <div>
                                            <label for="investment_criteria" class="block text-sm font-medium text-gray-300">Investment Criteria</label>
                                            <input 
                                                type="text" 
                                                id="investment_criteria" 
                                                v-model="investorForm.investment_criteria" 
                                                class="mt-1 input-field w-full" 
                                                placeholder="e.g. Seed stage, B2B, Recurring revenue (comma-separated)"
                                            />
                                        </div>

                                        <!-- Portfolio Companies -->
                                        <div>
                                            <label for="portfolio_companies" class="block text-sm font-medium text-gray-300">Portfolio Companies</label>
                                            <input 
                                                type="text" 
                                                id="portfolio_companies" 
                                                v-model="investorForm.portfolio_companies" 
                                                class="mt-1 input-field w-full" 
                                                placeholder="e.g. Company A, Company B (comma-separated)"
                                            />
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button 
                            @click="showAddModal ? submitInvestor() : updateInvestor()"
                            type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-purple-600 text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            {{ showAddModal ? 'Save Investor' : 'Update Investor' }}
                        </button>
                        <button 
                            @click="closeModal" 
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
    name: 'InvestorList',
    setup() {
        const viewMode = ref('cards');
        const currentPage = ref(1);
        const itemsPerPage = ref(9);
        const isLoading = ref(true);
        const investors = ref([]);
        const filters = ref({
            search: '',
            type: ''
        });
        const sortBy = ref('name');
        const sortDesc = ref(false);
        const showAddModal = ref(false);
        const showEditModal = ref(false);
        const investorForm = ref({
            id: null,
            name: '',
            website: '',
            location: '',
            logo: '',
            type: '',
            focus_areas: '',
            portfolio_size: null,
            total_investment: null,
            avg_check: null,
            founded: null,
            description: '',
            team_members: '',
            investment_criteria: '',
            portfolio_companies: ''
        });

        // Fetch investors list data from API
        const fetchInvestors = async () => {
            try {
                isLoading.value = true;
                const baseUrl = window.location.origin;
                const response = await axios.get(`${baseUrl}/api/investor-list-data`, {
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    withCredentials: true
                });
                
                investors.value = response.data;
                isLoading.value = false;
            } catch (error) {
                console.error('Error fetching investors:', error);
                alert('Failed to load investors data');
                isLoading.value = false;
            }
        };
        
        // Extract unique investor types from the investors list
        const uniqueTypes = computed(() => {
            const types = new Set(investors.value.map(investor => investor.type));
            return Array.from(types).sort();
        });

        // Apply filters and sorting to the investors list
        const filteredInvestors = computed(() => {
            return investors.value
                .filter(investor => {
                    const matchesSearch = !filters.value.search || 
                        investor.name.toLowerCase().includes(filters.value.search.toLowerCase()) ||
                        investor.location.toLowerCase().includes(filters.value.search.toLowerCase());
                    const matchesType = !filters.value.type || investor.type === filters.value.type;
                    return matchesSearch && matchesType;
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
            return Math.max(1, Math.ceil(filteredInvestors.value.length / itemsPerPage.value));
        });
        
        // Get current page of investors
        const paginatedInvestors = computed(() => {
            const start = (currentPage.value - 1) * itemsPerPage.value;
            const end = start + itemsPerPage.value;
            return filteredInvestors.value.slice(start, end);
        });

        // Convert comma-separated string to array
        const getArrayFromString = (stringValue) => {
            if (!stringValue) return [];
            if (Array.isArray(stringValue)) return stringValue;
            return stringValue.split(',').map(item => item.trim()).filter(Boolean);
        };

        // Initialize and set up auto-refresh
        onMounted(() => {
            fetchInvestors();
            
            // Refresh data every minute (60000 ms)
            const refreshInterval = setInterval(() => {
                fetchInvestors();
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
        
        // Handle sorting
        const sort = (column) => {
            if (sortBy.value === column) {
                sortDesc.value = !sortDesc.value;
            } else {
                sortBy.value = column;
                sortDesc.value = false;
            }
        };
        
        // Get CSS class for investor type badge
        const getTypeBadgeClass = (type) => {
            const classes = {
                'Venture Capital': 'badge badge-primary',
                'Angel Investor': 'badge badge-success',
                'Private Equity': 'badge badge-warning',
                'Corporate VC': 'badge badge-info',
                'Accelerator': 'badge badge-secondary'
            };
            return classes[type] || 'badge badge-secondary';
        };
        
        // Clear all filters
        const clearFilters = () => {
            filters.value = {
                search: '',
                type: ''
            };
            sortBy.value = 'name';
            sortDesc.value = false;
            currentPage.value = 1;
        };
        
        // View investor details
        const viewInvestor = (investor) => {
            // Implement view investor logic
            console.log('View investor:', investor);
            // In a real app, this might navigate to a details page
        };
        
        // Edit an investor
        const editInvestor = (investor) => {
            showEditModal.value = true;
            // Clone the investor object to avoid direct mutation
            investorForm.value = {
                id: investor.id,
                name: investor.name,
                website: investor.website || '',
                location: investor.location,
                logo: investor.logo || '',
                type: investor.type,
                focus_areas: Array.isArray(investor.focus_areas) ? investor.focus_areas.join(', ') : investor.focus_areas || '',
                portfolio_size: investor.portfolio_size,
                total_investment: investor.total_investment,
                description: investor.description || '',
                team_members: Array.isArray(investor.team_members) ? investor.team_members.join(', ') : investor.team_members || '',
                investment_criteria: Array.isArray(investor.investment_criteria) ? investor.investment_criteria.join(', ') : investor.investment_criteria || '',
                portfolio_companies: Array.isArray(investor.portfolio_companies) ? investor.portfolio_companies.join(', ') : investor.portfolio_companies || ''
            };
        };
        
        // Close the modal
        const closeModal = () => {
            showAddModal.value = false;
            showEditModal.value = false;
            resetForm();
        };
        
        // Reset the form
        const resetForm = () => {
            investorForm.value = {
                id: null,
                name: '',
                website: '',
                location: '',
                logo: '',
                type: '',
                focus_areas: '',
                portfolio_size: null,
                total_investment: null,
                avg_check: null,
                founded: null,
                description: '',
                team_members: '',
                investment_criteria: '',
                portfolio_companies: ''
            };
        };

        // Submit a new investor
        const submitInvestor = async () => {
            try {
                isLoading.value = true;
                
                const formData = {
                    name: investorForm.value.name,
                    logo: investorForm.value.logo,
                    location: investorForm.value.location,
                    type: investorForm.value.type,
                    focus_areas: investorForm.value.focus_areas,
                    portfolio_size: parseInt(investorForm.value.portfolio_size) || 0,
                    total_investment: parseFloat(investorForm.value.total_investment) || 0,
                    avg_check: parseFloat(investorForm.value.avg_check) || null,
                    founded: parseInt(investorForm.value.founded) || null,
                    description: investorForm.value.description,
                    website: investorForm.value.website,
                    team_members: investorForm.value.team_members,
                    investment_criteria: investorForm.value.investment_criteria,
                    portfolio_companies: investorForm.value.portfolio_companies
                };
                
                // Include CSRF token in headers
                const headers = {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                };
                
                const baseUrl = window.location.origin;
                const response = await axios.post(`${baseUrl}/api/investors`, formData, {
                    headers,
                    withCredentials: true
                });
                
                closeModal();
                alert(response.data.message || 'Investor created successfully');
                await fetchInvestors();
                
            } catch (error) {
                console.error('Error creating investor:', error);
                let errorMessage = 'Failed to create investor';
                
                if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    errorMessage = Object.values(errors).flat().join(', ');
                } else if (error.response?.data?.message) {
                    errorMessage = error.response.data.message;
                }
                
                alert('Error: ' + errorMessage);
            } finally {
                isLoading.value = false;
            }
        };
        
        // Update an existing investor
        const updateInvestor = async () => {
            try {
                isLoading.value = true;
                
                const formData = {
                    name: investorForm.value.name,
                    logo: investorForm.value.logo,
                    location: investorForm.value.location,
                    type: investorForm.value.type,
                    focus_areas: investorForm.value.focus_areas,
                    portfolio_size: parseInt(investorForm.value.portfolio_size) || 0,
                    total_investment: parseFloat(investorForm.value.total_investment) || 0,
                    avg_check: parseFloat(investorForm.value.avg_check) || null,
                    founded: parseInt(investorForm.value.founded) || null,
                    description: investorForm.value.description,
                    website: investorForm.value.website,
                    team_members: investorForm.value.team_members,
                    investment_criteria: investorForm.value.investment_criteria,
                    portfolio_companies: investorForm.value.portfolio_companies
                };
                
                // Include CSRF token in headers
                const headers = {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                };
                
                const baseUrl = window.location.origin;
                const response = await axios.put(`${baseUrl}/api/investors/${investorForm.value.id}`, formData, {
                    headers,
                    withCredentials: true
                });
                
                closeModal();
                alert(response.data.message || 'Investor updated successfully');
                await fetchInvestors();
                
            } catch (error) {
                console.error('Error updating investor:', error);
                let errorMessage = 'Failed to update investor';
                
                if (error.response?.data?.errors) {
                    const errors = error.response.data.errors;
                    errorMessage = Object.values(errors).flat().join(', ');
                } else if (error.response?.data?.message) {
                    errorMessage = error.response.data.message;
                }
                
                alert('Error: ' + errorMessage);
            } finally {
                isLoading.value = false;
            }
        };

        return {
            viewMode,
            currentPage,
            itemsPerPage,
            isLoading,
            investors,
            filters,
            sortBy,
            sortDesc,
            showAddModal,
            showEditModal,
            investorForm,
            uniqueTypes,
            filteredInvestors,
            totalPages,
            paginatedInvestors,
            getArrayFromString,
            toggleViewMode,
            sort,
            getTypeBadgeClass,
            clearFilters,
            viewInvestor,
            editInvestor,
            closeModal,
            submitInvestor,
            updateInvestor
        };
    }
};
</script>