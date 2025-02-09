<template>
    <div>
        <h1>Claims</h1>
        <div v-if="isClient">
            <h2>Create a Claim</h2>
            <form @submit.prevent="createClaim">
                <input v-model="newClaim.description" placeholder="Description" required />
                <button type="submit">Submit</button>
            </form>
        </div>
        <div v-if="isBroker">
            <h2>Update Claim Status</h2>
            <ul>
                <li v-for="claim in claims" :key="claim.id">
                    <span>{{ claim.description }}</span>
                    <select v-model="claim.status" @change="updateClaimStatus(claim)">
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import useHttpRequest from '../composables/useHttpRequest';
import useUserStore from '../store/useUserStore';

const userStore = useUserStore();
const { index: fetchClaims, store: storeClaim, update: updateClaim } = useHttpRequest('/claims');

const claims = ref([]);
const newClaim = ref({ description: '', status: 'pending' });

const isClient = userStore.user?.role === 'client';
const isBroker = userStore.user?.role === 'broker';

const loadClaims = async () => {
    claims.value = await fetchClaims();
};

const createClaim = async () => {
    await storeClaim(newClaim.value);
    newClaim.value.description = '';
    await loadClaims();
};

const updateClaimStatus = async (claim) => {
    await updateClaim(claim.id, { status: claim.status });
    await loadClaims();
};

onMounted(loadClaims);
</script>