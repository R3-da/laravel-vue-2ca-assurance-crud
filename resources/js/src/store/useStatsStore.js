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

    const loadStats = async () => {
        const res = await getStats();
        stats.value = res;
    };

    return {
        stats,
        loadStats,
        statsLoading,
        statsFirstTimeLoading,
    };
});

export default useStatsStore;