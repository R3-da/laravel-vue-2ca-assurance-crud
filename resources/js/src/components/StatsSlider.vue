<script setup>
import { ref, onMounted } from 'vue';
import { useStatsStore } from '../store/useStatsStore';
import useHttpRequest from '../composables/useHttpRequest';

const statsStore = useStatsStore();
const { index: fetchStats } = useHttpRequest('/stats');

const dateRange = ref({
    start: new Date(new Date().setMonth(new Date().getMonth() - 1)),
    end: new Date()
});

const updateStats = async () => {
    const params = {
        start_date: dateRange.value.start.toISOString().split('T')[0],
        end_date: dateRange.value.end.toISOString().split('T')[0]
    };
    await fetchStats(params);
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
