<script setup>
import Table from '../components/table/Table.vue';
import THead from '../components/table/THead.vue';
import TBody from '../components/table/TBody.vue';
import Tr from '../components/table/Tr.vue';
import Th from '../components/table/Th.vue';
import Td from '../components/table/Td.vue';
import CreateButton from '../components/ui/CreateButton.vue';
import EditButton from '../components/ui/EditButton.vue';
import DeleteButton from '../components/ui/DeleteButton.vue';
import AuthorizationFallback from '../components/page/AuthorizationFallback.vue';
import ClaimSlider from '../components/page/ClaimSlider.vue';

import useUserStore from '../store/useUserStore';
import useClaimStore from '../store/useClaimStore';
import usePermissionStore from '../store/usePermissionStore';
import useSlider from '../composables/useSlider';
import useModalToast from '../composables/useModalToast';
import useHttpRequest from '../composables/useHttpRequest';

const userStore = useUserStore();
const claimStore = useClaimStore();
const permissionStore = usePermissionStore();

if (!permissionStore.permissions.length)
    await permissionStore.loadPermissions();
if (!claimStore.claims?.length) await claimStore.loadClaims();

const { slider, sliderData, showSlider, hideSlider } = useSlider('claim-crud');
const { showConfirmModal, showToast } = useModalToast();
const { destroy: deleteClaim, deleting } = useHttpRequest('/claims');

const onDelete = (claim) => {
    if (deleting.value) return;

    showConfirmModal(null, async (confirmed) => {
        if (!confirmed) return;

        const isDeleted = await deleteClaim(claim?.id);
        if (isDeleted) {
            showToast(`Claim "${claim?.name}" deleted successfully...`);
            claimStore.loadClaims();
            userStore.loadUsers();
        }
    });
};
</script>

<template>
    <AuthorizationFallback :permissions="['claims-all', 'claims-view']">
        <div class="w-full space-y-4 py-6">
            <div class="flex-between">
                <h2 class="text-active font-bold text-2xl">Claims</h2>

                <CreateButton @click="showSlider(true)" />
            </div>

            <div class="w-full">
                <Table>
                    <THead>
                        <Tr>
                            <Th> Id </Th>
                            <Th> Claim </Th>
                            <Th> Description </Th>
                            <Th> Action </Th>
                        </Tr>
                    </THead>

                    <TBody>
                        <Tr
                            v-for="claim in claimStore.claims"
                            :key="claim.id"
                        >
                            <Td>{{ claim?.id }}</Td>
                            <Td>
                                <div
                                    class="text-emerald-500 dark:text-emerald-200"
                                >
                                    {{ claim?.name }}
                                </div>
                            </Td>

                            <Td>
                                {{ claim?.description }}
                            </Td>
                            <Td class="align-middle">
                                <div class="flex flex-col gap-2">
                                    <EditButton
                                        @click="showSlider(true, claim)"
                                    />
                                    <DeleteButton @click="onDelete(claim)" />
                                </div>
                            </Td>
                        </Tr>
                    </TBody>
                </Table>
            </div>
        </div>

        <ClaimSlider
            :show="slider"
            :claim="sliderData"
            @hide="hideSlider"
        />
    </AuthorizationFallback>
</template>