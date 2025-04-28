<template>
    <div class="space-y-6">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h1 class="text-2xl font-bold text-white">Startup News</h1>
            <div class="mt-4 sm:mt-0 flex space-x-3">
                <button @click="toggleViewMode" class="btn-secondary flex items-center">
                    <svg v-if="viewMode === 'grid'" class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                    </svg>
                    <svg v-else class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    {{ viewMode === 'grid' ? 'List View' : 'Grid View' }}
                </button>
                <button @click="refreshNews" class="btn-secondary flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Loading Indicator -->
        <div v-if="isLoading" class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-primary-500"></div>
        </div>

        <div v-else>
            <!-- Featured Article -->
            <div v-if="featuredArticle" class="card overflow-hidden">
                <div class="relative h-64 md:h-96">
                    <img :src="featuredArticle.image" :alt="featuredArticle.title" class="h-full w-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6">
                        <span :class="getCategoryBadgeClass(featuredArticle.category)" class="badge mb-2">
                            {{ featuredArticle.category }}
                        </span>
                        <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">
                            <a :href="featuredArticle.url" target="_blank" class="hover:text-primary-400">
                                {{ featuredArticle.title }}
                            </a>
                        </h2>
                        <p class="text-gray-300 mb-4 line-clamp-2">{{ featuredArticle.description }}</p>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-300">{{ featuredArticle.source }}</span>
                                <span class="text-sm text-gray-300">{{ formatDate(featuredArticle.published_at) }}</span>
                            </div>
                            <div class="flex space-x-2">
                                <button @click="likeArticle(featuredArticle)" class="btn-icon">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                    </svg>
                                    <span class="ml-1">{{ featuredArticle.likes_count }}</span>
                                </button>
                                <button @click="shareArticle(featuredArticle)" class="btn-icon">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                    </svg>
                                    <span class="ml-1">{{ featuredArticle.shares_count }}</span>
                                </button>
                                <button @click="viewArticle(featuredArticle)" class="btn-primary">
                                    Read More
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trending Topics -->
            <div class="card p-4">
                <h3 class="text-lg font-medium text-white mb-3">Trending Topics</h3>
                <div class="flex flex-wrap gap-2">
                    <button 
                        v-for="topic in trendingTopics" 
                        :key="topic.id" 
                        @click="filterByTopic(topic.name)"
                        class="badge badge-secondary hover:bg-primary-600 transition-colors duration-200"
                    >
                        {{ topic.name }}
                        <span class="ml-1 text-xs opacity-75">{{ topic.count }}</span>
                    </button>
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
                                placeholder="Search news..."
                            />
                        </div>
                    </div>
                    <div class="flex-1">
                        <label for="category" class="block text-sm font-medium text-white">Category</label>
                        <div class="mt-1">
                            <select
                                id="category"
                                v-model="filters.category"
                                class="input-field w-full"
                            >
                                <option value="">All Categories</option>
                                <option v-for="category in categories" :key="category" :value="category">
                                    {{ category }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label for="source" class="block text-sm font-medium text-white">Source</label>
                        <div class="mt-1">
                            <select
                                id="source"
                                v-model="filters.source"
                                class="input-field w-full"
                            >
                                <option value="">All Sources</option>
                                <option v-for="source in sources" :key="source" :value="source">
                                    {{ source }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="flex-1">
                        <label for="timeframe" class="block text-sm font-medium text-white">Timeframe</label>
                        <div class="mt-1">
                            <select
                                id="timeframe"
                                v-model="filters.timeframe"
                                class="input-field w-full"
                            >
                                <option value="all">All Time</option>
                                <option value="today">Today</option>
                                <option value="week">This Week</option>
                                <option value="month">This Month</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- News Articles Grid View -->
            <div v-if="viewMode === 'grid'" class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div v-for="article in filteredArticles" :key="article.id" class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="flex flex-col h-full">
                        <div class="flex-shrink-0">
                            <img v-if="article.image" :src="article.image" :alt="article.title" class="h-48 w-full object-cover rounded-t-lg">
                        </div>
                        <div class="flex-1 p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span :class="getCategoryBadgeClass(article.category)" class="badge">
                                    {{ article.category }}
                                </span>
                                <span class="text-sm text-gray-400">{{ formatDate(article.published_at) }}</span>
                            </div>
                            <h3 class="text-lg font-medium text-white mb-2 line-clamp-2">
                                <a :href="article.url" target="_blank" class="hover:text-primary-400">
                                    {{ article.title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-400 mb-4 line-clamp-3">{{ article.description }}</p>
                            <div class="mt-auto flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-400">{{ article.source }}</span>
                                    <div class="flex items-center space-x-2">
                                        <button @click="likeArticle(article)" class="text-gray-400 hover:text-primary-400">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                            </svg>
                                        </button>
                                        <span class="text-sm text-gray-400">{{ article.likes_count }}</span>
                                    </div>
                                </div>
                                <button @click="viewArticle(article)" class="btn-secondary">
                                    Read More
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- News Articles List View -->
            <div v-else class="space-y-4">
                <div v-for="article in filteredArticles" :key="article.id" class="card hover:shadow-lg transition-shadow duration-300">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/3 flex-shrink-0">
                            <img v-if="article.image" :src="article.image" :alt="article.title" class="h-48 md:h-full w-full object-cover rounded-t-lg md:rounded-l-lg md:rounded-t-none">
                        </div>
                        <div class="p-4 md:w-2/3">
                            <div class="flex items-center justify-between mb-2">
                                <span :class="getCategoryBadgeClass(article.category)" class="badge">
                                    {{ article.category }}
                                </span>
                                <span class="text-sm text-gray-400">{{ formatDate(article.published_at) }}</span>
                            </div>
                            <h3 class="text-lg font-medium text-white mb-2">
                                <a :href="article.url" target="_blank" class="hover:text-primary-400">
                                    {{ article.title }}
                                </a>
                            </h3>
                            <p class="text-sm text-gray-400 mb-4">{{ article.description }}</p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-400">{{ article.source }}</span>
                                    <div class="flex items-center space-x-2">
                                        <button @click="likeArticle(article)" class="text-gray-400 hover:text-primary-400">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                            </svg>
                                        </button>
                                        <span class="text-sm text-gray-400">{{ article.likes_count }}</span>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button @click="shareArticle(article)" class="text-gray-400 hover:text-primary-400">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                            </svg>
                                        </button>
                                        <span class="text-sm text-gray-400">{{ article.shares_count }}</span>
                                    </div>
                                </div>
                                <button @click="viewArticle(article)" class="btn-secondary">
                                    Read More
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-400">
                    Showing {{ filteredArticles.length }} of {{ articles.length }} articles
                </div>
                <div class="flex space-x-2">
                    <button class="btn-secondary" :disabled="currentPage === 1" @click="currentPage--">
                        Previous
                    </button>
                    <button class="btn-secondary" :disabled="currentPage === totalPages" @click="currentPage++">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'NewsFeed',
    data() {
        return {
            isLoading: false,
            viewMode: 'grid',
            filters: {
                search: '',
                category: '',
                source: '',
                timeframe: 'all'
            },
            articles: [],
            featuredArticle: null,
            trendingTopics: [],
            currentPage: 1,
            itemsPerPage: 9,
            error: null
        }
    },
    computed: {
        categories() {
            return [...new Set(this.articles.map(article => article.category))]
        },
        sources() {
            return [...new Set(this.articles.map(article => article.source))]
        },
        filteredArticles() {
            let filtered = [...this.articles]
            
            // Apply search filter
            if (this.filters.search) {
                const searchTerm = this.filters.search.toLowerCase()
                filtered = filtered.filter(article => 
                    article.title.toLowerCase().includes(searchTerm) ||
                    article.description.toLowerCase().includes(searchTerm)
                )
            }
            
            // Apply category filter
            if (this.filters.category) {
                filtered = filtered.filter(article => 
                    article.category === this.filters.category
                )
            }
            
            // Apply source filter
            if (this.filters.source) {
                filtered = filtered.filter(article => 
                    article.source === this.filters.source
                )
            }
            
            // Apply timeframe filter
            if (this.filters.timeframe !== 'all') {
                const now = new Date()
                const today = new Date(now.getFullYear(), now.getMonth(), now.getDate())
                const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000)
                const monthAgo = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate())
                
                filtered = filtered.filter(article => {
                    const articleDate = new Date(article.published_at)
                    switch (this.filters.timeframe) {
                        case 'today':
                            return articleDate >= today
                        case 'week':
                            return articleDate >= weekAgo
                        case 'month':
                            return articleDate >= monthAgo
                        default:
                            return true
                    }
                })
            }
            
            return filtered
        },
        totalPages() {
            return Math.ceil(this.filteredArticles.length / this.itemsPerPage)
        },
        paginatedArticles() {
            const start = (this.currentPage - 1) * this.itemsPerPage
            const end = start + this.itemsPerPage
            return this.filteredArticles.slice(start, end)
        }
    },
    methods: {
        async fetchNews() {
            this.isLoading = true;
            this.error = null;
            
            try {
                // Call the backend API endpoint we created
                const response = await fetch('/api/external-news');
                
                if (!response.ok) {
                    throw new Error(`Failed to fetch news: ${response.statusText}`);
                }
                
                const data = await response.json();
                
                if (data.error) {
                    throw new Error(data.error);
                }
                
                // Update our data with the API response
                this.articles = data.articles;
                this.featuredArticle = data.featuredArticle;
                this.trendingTopics = data.trendingTopics;
                
            } catch (error) {
                console.error('Error fetching news:', error);
                this.error = error.message;
                
                // If API fails, use fallback data
                this.loadFallbackData();
            } finally {
                this.isLoading = false;
            }
        },
        loadFallbackData() {
            // Fallback data in case the API call fails
            console.log('Loading fallback news data');
            this.articles = [
                {
                    id: 1,
                    title: 'AI Startup Raises $50M Series B',
                    description: 'Leading AI company secures major funding round to expand operations globally.',
                    image: 'https://images.unsplash.com/photo-1485827404703-89b55fcc595e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                    category: 'AI',
                    source: 'TechCrunch',
                    url: '#',
                    published_at: '2024-03-15T10:00:00Z',
                    likes_count: 245,
                    shares_count: 89
                },
                {
                    id: 2,
                    title: 'FinTech Revolution in Southeast Asia',
                    description: 'Southeast Asian FinTech startups see unprecedented growth in 2024.',
                    image: 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                    category: 'FinTech',
                    source: 'Bloomberg',
                    url: '#',
                    published_at: '2024-03-14T15:30:00Z',
                    likes_count: 189,
                    shares_count: 67
                },
                {
                    id: 3,
                    title: 'Blockchain Startup Partners with Major Banks',
                    description: 'Innovative blockchain solution set to transform traditional banking.',
                    image: 'https://images.unsplash.com/photo-1518546305927-5a555bb7020d?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80',
                    category: 'Blockchain',
                    source: 'Forbes',
                    url: '#',
                    published_at: '2024-03-13T09:15:00Z',
                    likes_count: 312,
                    shares_count: 124
                }
            ];
            
            this.featuredArticle = this.articles[0];
            this.trendingTopics = [
                { id: 1, name: 'AI', count: 42 },
                { id: 2, name: 'FinTech', count: 38 },
                { id: 3, name: 'SaaS', count: 35 },
                { id: 4, name: 'Blockchain', count: 28 },
                { id: 5, name: 'HealthTech', count: 25 }
            ];
        },
        toggleViewMode() {
            this.viewMode = this.viewMode === 'grid' ? 'list' : 'grid'
        },
        refreshNews() {
            this.fetchNews()
        },
        likeArticle(article) {
            article.likes_count++
        },
        shareArticle(article) {
            article.shares_count++
        },
        viewArticle(article) {
            window.open(article.url, '_blank')
        },
        filterByTopic(topic) {
            this.filters.search = topic
        },
        getCategoryBadgeClass(category) {
            const classes = {
                'AI': 'badge-primary',
                'FinTech': 'badge-success',
                'Blockchain': 'badge-warning',
                'SaaS': 'badge-info',
                'HealthTech': 'badge-danger'
            }
            return classes[category] || 'badge-secondary'
        },
        formatDate(dateString) {
            const date = new Date(dateString)
            const now = new Date()
            const diffTime = Math.abs(now - date)
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
            
            if (diffDays === 0) {
                return 'Today'
            } else if (diffDays === 1) {
                return 'Yesterday'
            } else if (diffDays < 7) {
                return `${diffDays} days ago`
            } else {
                return date.toLocaleDateString('en-US', { 
                    year: 'numeric', 
                    month: 'short', 
                    day: 'numeric' 
                })
            }
        }
    },
    mounted() {
        this.fetchNews()
    }
}
</script>

<style scoped>
.card {
    @apply bg-gray-800 rounded-lg shadow-md overflow-hidden;
}

.badge {
    @apply px-2 py-1 text-xs font-medium rounded-full;
}

.badge-primary {
    @apply bg-primary-500 text-white;
}

.badge-success {
    @apply bg-green-500 text-white;
}

.badge-warning {
    @apply bg-yellow-500 text-white;
}

.badge-info {
    @apply bg-blue-500 text-white;
}

.badge-danger {
    @apply bg-red-500 text-white;
}

.badge-secondary {
    @apply bg-gray-600 text-white;
}

.btn-primary {
    @apply px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors duration-200;
}

.btn-secondary {
    @apply px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600 transition-colors duration-200;
}

.btn-icon {
    @apply p-2 text-gray-400 hover:text-primary-400 transition-colors duration-200;
}

.input-field {
    @apply bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>