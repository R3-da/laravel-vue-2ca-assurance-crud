import { ref } from 'vue';
import { defineStore } from 'pinia';
import useHttpRequest from '../composables/useHttpRequest';

const useClaimStore = defineStore('claims', () => {
    const {
        index: getClaims,
        loading: claimsLoading,
        initialLoading: claimsFirstTimeLoading,
    } = useHttpRequest('/claims');

    const claims = ref([]);
    const loadClaims = async () => {
        const res = await getClaims();
        claims.value = res;
    };

    return {
        claims,
        loadClaims,
        claimsLoading,
        claimsFirstTimeLoading,
    };
});

export default useClaimStore;