import './bootstrap';
import { createApp } from 'vue';
import Chart from 'chart.js/auto';
import axios from 'axios';
import router from './router';
import App from './App.vue';

// Import components
import Dashboard from './components/Dashboard.vue';
import StartupList from './components/StartupList.vue';
import InvestorList from './components/InvestorList.vue';
import NewsFeed from './components/NewsFeed.vue';

// Configure axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

// Create Vue app
const app = createApp(App);

// Register global components
app.component('dashboard', Dashboard);
app.component('startup-list', StartupList);
app.component('investor-list', InvestorList);
app.component('news-feed', NewsFeed);

// Use router
app.use(router);

// Mount app
app.mount('#app');

// Make Chart.js available globally
window.Chart = Chart;
