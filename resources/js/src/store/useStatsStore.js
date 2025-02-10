import { ref } from 'vue';
import { defineStore } from 'pinia';
import useHttpRequest from '../composables/useHttpRequest';

const useStatsStore = defineStore('stats', () => {
    const {
        index: fetchStatsRequest,
        loading: statsLoading,
        initialLoading: statsFirstTimeLoading,
    } = useHttpRequest('/api/stats');

    const stats = ref({
        totalClaims: 0,
        approvedClaims: 0,
        pendingClaims: 0,
        rejectedClaims: 0,
        openClaims: 0,
    });

    const loadStats = async (params) => {
        const response = await fetchStatsRequest(params);
        stats.value = response;
    };

    return {
        stats,
        loadStats,
        statsLoading,
        statsFirstTimeLoading,
    };
});

export default useStatsStore;