import './bootstrap';
import { createApp } from 'vue';
import DashboardCharts from './components/DashboardCharts.vue';

const app = createApp({});

app.component('dashboard-charts', DashboardCharts);

app.mount('#app');
