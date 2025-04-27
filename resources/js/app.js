import './bootstrap';
import { createApp } from 'vue';
import Chart from 'chart.js/auto';
import axios from 'axios';
import router from './router';
import App from './App.vue';

// Configure axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

// Create Vue app
const app = createApp(App);

// Ensure router is used for navigation
app.use(router);

// Mount app
app.mount('#app');

// Make Chart.js available globally
window.Chart = Chart;
