import { ref } from 'vue';
import { defineStore } from 'pinia';
import useHttpRequest from '../composables/useHttpRequest';

const useStatsStore = defineStore('stats', () => {
    const {
        index: getStats,
        loading: statsLoading,
        initialLoading: statsFirstTimeLoading,
    } = useHttpRequest('/stats');

    const stats = ref({
        totalClaims: 0,
        approvedClaims: 0,
        pendingClaims: 0,
        rejectedClaims: 0,
        openClaims: 0,
    });

    // Accept `params` (start_date, end_date) when loading stats
    const loadStats = async (params = {}) => {
        console.log("Fetching stats with params:", params); // Debug log
        const res = await getStats(params);
        if (res) {
            stats.value = res;
        }
    };

    return {
        stats,
        loadStats, // Now accepts query parameters
        statsLoading,
        statsFirstTimeLoading,
    };
});

export default useStatsStore;