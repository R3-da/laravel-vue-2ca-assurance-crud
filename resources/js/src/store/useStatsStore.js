import { defineStore } from 'pinia';

export const useStatsStore = defineStore('stats', {
    state: () => ({
        stats: {
            totalClaims: 0,
            approvedClaims: 0,
            pendingClaims: 0,
            rejectedClaims: 0,
            openClaims: 0
        }
    }),

    actions: {
        async fetchStats(params) {
            try {
                const response = await fetch(`/api/stats?start_date=${params.start_date}&end_date=${params.end_date}`);
                const data = await response.json();
                this.stats = data;
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        }
    }
});