<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';
import useStatsStore from '../store/useStatsStore';
import usePermissionStore from '../store/usePermissionStore';
import StatsSlider from '../components/page/StatsSlider.vue';
import Chart from 'chart.js/auto';
import AuthorizationFallback from '../components/page/AuthorizationFallback.vue';
import useSlider from '../composables/useSlider';

const { slider, sliderData, showSlider, hideSlider } = useSlider('stats-crud');
const statsStore = useStatsStore();
const permissionStore = usePermissionStore();
const chart = ref(null);
let chartInstance = null;

// Load initial data inside onMounted hook
onMounted(async () => {
    if (!statsStore.stats.length) {
        await statsStore.loadStats();
    }
    console.log(statsStore.stats);  // Debugging log to check the structure of stats
    initChart(); // Initialize chart after loading stats
});

const initChart = () => {
    if (chartInstance) {
        chartInstance.destroy();
    }

    // Updated statsData to exclude 'Total Claims'
    const statsData = [
        statsStore.stats.openClaims,           // Open
        statsStore.stats.inProgressClaims,     // In Progress
        statsStore.stats.resolvedClaims,       // Resolved
        statsStore.stats.closedClaims         // Closed
    ];

    // Labels now only correspond to the remaining statuses
    const labels = ['Open', 'In Progress', 'Resolved', 'Closed'];

    // Check if statsData has the right number of items to match the labels
    if (statsData.length === labels.length) {
        const ctx = document.getElementById('statsChart');
        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Claims Statistics',
                    data: statsData,
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.2)',  // Open: emerald color
                        'rgba(59, 130, 246, 0.2)',  // In Progress: blue color
                        'rgba(254, 211, 85, 0.2)',  // Resolved: yellow color
                        'rgba(168, 85, 247, 0.2)'   // Closed: purple color
                    ],
                    borderColor: [
                        'rgba(34, 197, 94, 1)',  // Open: emerald color
                        'rgba(59, 130, 246, 1)',  // In Progress: blue color
                        'rgba(254, 211, 85, 1)',  // Resolved: yellow color
                        'rgba(168, 85, 247, 1)'   // Closed: purple color
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
    } else {
        console.error("Data and labels length mismatch! Check your stats data.");
    }
};

watch(() => statsStore.stats, (newStats) => {
    if (chartInstance) {
        const statsData = [
            newStats.openClaims,
            newStats.inProgressClaims,
            newStats.resolvedClaims,
            newStats.closedClaims
        ];

        if (statsData.length === chartInstance.data.labels.length) {
            chartInstance.data.datasets[0].data = statsData;
            chartInstance.update();
        } else {
            console.error("Data and labels length mismatch during update!");
        }
    }
}, { deep: true });

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
                        <h3 class="font-bold">Open</h3>
                        <p class="text-2xl">{{ statsStore.stats.openClaims }}</p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg">
                        <h3 class="font-bold">In Progress</h3>
                        <p class="text-2xl">{{ statsStore.stats.inProgressClaims }}</p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg">
                        <h3 class="font-bold">Resolved</h3>
                        <p class="text-2xl">{{ statsStore.stats.resolvedClaims }}</p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-800 p-4 rounded-lg">
                        <h3 class="font-bold">Closed</h3>
                        <p class="text-2xl">{{ statsStore.stats.closedClaims }}</p>
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