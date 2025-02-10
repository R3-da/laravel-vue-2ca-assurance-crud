import { defineStore } from 'pinia';

export const useStatsStore = defineStore('stats', {
    state: () => ({
        stats: {
            totalClaims: 0,
            approvedClaims: 0,
            pendingClaims: 0,
            rejectedClaims: 0,
            monthlyStats: []
        }
    }),
    actions: {
        async fetchStats() {
            try {
                const response = await fetch('/api/stats');
                const data = await response.json();
                this.stats = data;
            } catch (error) {
                console.error('Error fetching stats:', error);
            }
        }
    }
});
