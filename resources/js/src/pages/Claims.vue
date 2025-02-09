<script setup>
import { ref, onMounted } from 'vue';
import Table from '../components/table/Table.vue';
import THead from '../components/table/THead.vue';
import TBody from '../components/table/TBody.vue';
import Tr from '../components/table/Tr.vue';
import Th from '../components/table/Th.vue';
import Td from '../components/table/Td.vue';
import CreateButton from '../components/ui/CreateButton.vue';
import EditButton from '../components/ui/EditButton.vue';
import useHttpRequest from '../composables/useHttpRequest';
import useUserStore from '../store/useUserStore';
import useSlider from '../composables/useSlider';
import useModalToast from '../composables/useModalToast';
import AuthorizationFallback from '../components/page/AuthorizationFallback.vue';


const userStore = useUserStore();
const { index: fetchClaims, store: storeClaim, update: updateClaim } = useHttpRequest('/claims');

const claims = ref([]);
const newClaim = ref({ name: '', description: '', status: 'pending', user_id: userStore.user?.id });

const isClient = userStore.user?.role === 'client';
const isBroker = userStore.user?.role === 'broker';

const { slider, sliderData, showSlider, hideSlider } = useSlider('claim-crud');
const { showToast } = useModalToast();

const loadClaims = async () => {
    claims.value = await fetchClaims();
};

const createClaim = async () => {
    await storeClaim(newClaim.value);
    showToast('Claim created successfully');
    newClaim.value = { name: '', description: '', status: 'pending', user_id: userStore.user?.id };
    await loadClaims();
};

const updateClaimStatus = async (claim) => {
    await updateClaim(claim.id, { status: claim.status });
    showToast('Claim status updated successfully');
    await loadClaims();
};

onMounted(loadClaims);
</script>

<template>
    <AuthorizationFallback :permissions="['claims-all', 'claims-view']">
        <div class="w-full space-y-4 py-6">
            <div class="flex-between">
                <h2 class="text-active font-bold text-2xl">Claims</h2>
                <CreateButton v-if="isClient" @click="showSlider(true)" />
            </div>

            <div class="w-full">
                <Table>
                    <THead>
                        <Tr>
                            <Th>Name</Th>
                            <Th>Description</Th>
                            <Th>Status</Th>
                            <Th v-if="isBroker">Actions</Th>
                        </Tr>
                    </THead>

                    <TBody>
                        <Tr v-for="claim in claims" :key="claim.id">
                            <Td>{{ claim.name }}</Td>
                            <Td>{{ claim.description }}</Td>
                            <Td>
                                <div :class="claim.status === 'approved' ? 'text-emerald-500 dark:text-emerald-200' : 
                                        claim.status === 'rejected' ? 'text-red-500 dark:text-red-200' : 
                                        'text-yellow-500 dark:text-yellow-200'">
                                    {{ claim.status }}
                                </div>
                            </Td>
                            <Td v-if="isBroker" class="align-middle">
                                <div class="flex flex-col gap-2">
                                    <EditButton @click="showSlider(true, claim)" />
                                </div>
                            </Td>
                        </Tr>
                    </TBody>
                </Table>
            </div>
        </div>
    </AuthorizationFallback>
</template>
