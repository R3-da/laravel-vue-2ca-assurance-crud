<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import useStatsStore from '../store/useStatsStore';
import usePermissionStore from '../store/usePermissionStore';
import StatsSlider from '../components/page/StatsSlider.vue';
import Chart from 'chart.js/auto';
import AuthorizationFallback from '../components/page/AuthorizationFallback.vue';
import useSlider from '../composables/useSlider';

// Add this with other composable initializations
const { slider, sliderData, showSlider, hideSlider } = useSlider('stats-crud');
const statsStore = useStatsStore();
const permissionStore = usePermissionStore();
const chart = ref(null);
let chartInstance = null;

// Load initial data
if (!permissionStore.permissions.length) {
    await permissionStore.loadPermissions();
}
if (!statsStore.stats) {
    await statsStore.loadStats();
}

const initChart = () => {
    if (chartInstance) {
        chartInstance.destroy();
    }
    
    const ctx = document.getElementById('statsChart');
    chartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Total Claims', 'Approved', 'Pending', 'Open', 'Rejected'],
            datasets: [{
                label: 'Claims Statistics',
                data: [
                    statsStore.stats.totalClaims,
                    statsStore.stats.approvedClaims,
                    statsStore.stats.pendingClaims,
                    statsStore.stats.openClaims,
                    statsStore.stats.rejectedClaims
                ],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
};

watch(() => statsStore.stats, (newStats) => {
    if (chartInstance) {
        chartInstance.data.datasets[0].data = [
            newStats.totalClaims,
            newStats.approvedClaims,
            newStats.pendingClaims,
            newStats.openClaims,
            newStats.rejectedClaims
        ];
        chartInstance.update();
    }
}, { deep: true });

onMounted(() => {
    initChart();
});

onUnmounted(() => {
    if (chartInstance) {
        chartInstance.destroy();
    }
});
</script>

<template>
    <DashboardHeader />
    <AuthorizationFallback :permissions="['stats-all', 'stats-view']">
        <div class="w-full space-y-4 py-6">
            <div class="flex-between">
                <h2 class="text-active font-bold text-2xl">Statistics Dashboard</h2>
                <button @click="() => showSlider(true)" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <canvas id="statsChart"></canvas>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-emerald-100 dark:bg-emerald-800 p-4 rounded-lg">
                        <h3 class="font-bold">Total Claims</h3>
                        <p class="text-2xl">{{ statsStore.stats.totalClaims }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg">
                        <h3 class="font-bold">Approved</h3>
                        <p class="text-2xl">{{ statsStore.stats.approvedClaims }}</p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg">
                        <h3 class="font-bold">Pending</h3>
                        <p class="text-2xl">{{ statsStore.stats.pendingClaims }}</p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-800 p-4 rounded-lg">
                        <h3 class="font-bold">Open</h3>
                        <p class="text-2xl">{{ statsStore.stats.openClaims }}</p>
                    </div>
                    <div class="bg-red-100 dark:bg-red-800 p-4 rounded-lg">
                        <h3 class="font-bold">Rejected</h3>
                        <p class="text-2xl">{{ statsStore.stats.rejectedClaims }}</p>
                    </div>
                </div>
            </div>
            <StatsSlider
                :show="slider"
                @hide="hideSlider"
            />
        </div>
    </AuthorizationFallback>
</template>
