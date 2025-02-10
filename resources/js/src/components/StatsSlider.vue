<script setup>
import { ref, onMounted } from 'vue';
import { useStatsStore } from '../store/useStatsStore';
import useHttpRequest from '../composables/useHttpRequest';

const statsStore = useStatsStore();
const { index: fetchStats } = useHttpRequest('/stats');

// Get first and last day of current month
const currentDate = new Date();
const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
const lastDay = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0);

const dateRange = ref({
    start: firstDay.toISOString().split('T')[0],
    end: lastDay.toISOString().split('T')[0]
});

const updateStats = async () => {
    const params = {
        start_date: dateRange.value.start,
        end_date: dateRange.value.end
    };
    const data = await fetchStats(params);
    if (data) {
        statsStore.$patch({ stats: data });
    }
};

onMounted(async () => {
    await updateStats();
});
</script>

<template>
    <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-bold">Statistics Filter</h2>
            <div class="flex gap-4">
                <input 
                    type="date" 
                    v-model="dateRange.start"
                    class="rounded border p-2 dark:bg-gray-700"
                    @change="updateStats"
                >
                <input 
                    type="date" 
                    v-model="dateRange.end"
                    class="rounded border p-2 dark:bg-gray-700"
                    @change="updateStats"
                >
            </div>
        </div>
    </div>
</template>
