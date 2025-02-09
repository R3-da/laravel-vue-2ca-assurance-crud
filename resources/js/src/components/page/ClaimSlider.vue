<script setup>
import { computed, ref, watch } from 'vue';
import Slider from '../ui/Slider.vue';
import FormInput from '../ui/FormInput.vue';
import FormLabelError from '../ui/FormLabelError.vue';
import VSelect from 'vue-select';  // Import vue-select
import Button from '../ui/Button.vue';
import AuthorizationFallback from '../../components/page/AuthorizationFallback.vue';

import useUserStore from '../../store/useUserStore';
import useClaimStore from '../../store/useClaimStore';
import useValidation from '../../composables/useValidation';
import useHttpRequest from '../../composables/useHttpRequest';
import useModalToast from '../../composables/useModalToast';

import * as yup from 'yup';

const props = defineProps({
    show: {
        type: Boolean,
        default: () => false,
    },
    claim: {
        type: [Object, null],
        default: () => null,
    },
});
const emit = defineEmits(['hide']);

const userStore = useUserStore();
const claimStore = useClaimStore();

const {
    store: createClaim,
    saving,
    update: updateClaim,
    updating,
} = useHttpRequest('/claims');
const { runYupValidation } = useValidation();
const { showToast } = useModalToast();

const requiredPermissions = computed(() => {
    if (!props.claim?.id) return ['claims-all', 'claims-create'];
    else return ['claims-all', 'claims-edit'];
});

const title = computed(() =>
    props.claim ? `Update claim "${props.claim?.subject}"` : 'Add new claim',
);

// Predefined category options
const categoryOptions = [
    { label: 'Reimbursement', value: 'reimbursement' },
    { label: 'Contract Issue', value: 'contract_issue' },
    { label: 'Billing Error', value: 'billing_error' },
    { label: 'Others', value: 'others' },
];

const initialFormData = () => {
    return {
        subject: null,
        detailed_description: null,
        category: 'others', // Default category to 'Others'
        status: 'Open', // Default status to 'Open'
    };
};

const formData = ref(initialFormData());
const formErrors = ref({});

watch(
    () => props.show,
    () => {
        if (props.show) {
            if (props.claim?.id) {
                formData.value = Object.entries(initialFormData()).reduce(
                    (r, [key, val]) => {
                        if (props.claim[key])
                            return { ...r, [key]: props.claim[key] };
                        return { ...r, [key]: val };
                    },
                    {},
                );
            } else {
                formData.value = initialFormData();
                formErrors.value = {};
            }
        }
    },
);

const schema = yup.object().shape({
    subject: yup.string().nullable().required(),
    detailed_description: yup.string().nullable().required(),
    category: yup.string().oneOf(categoryOptions.map((c) => c.value)).required(),
    status: yup.string().default('Open'),
});

const onSubmit = async () => {
    if (saving.value || updating.value) return;

    const data = { ...formData.value };

    const { validated, errors } = await runYupValidation(schema, data);
    if (!validated) {
        formErrors.value = errors;
        return;
    }
    formErrors.value = {};

    const response = props.claim?.id
        ? await updateClaim(props.claim?.id, data)
        : await createClaim(data);

    if (response?.id) {
        showToast(
            `Claim ${props.claim?.id ? 'updated' : 'created'} successfully`,
        );
        claimStore.loadClaims();
        userStore.loadUsers();
        emit('hide');
    }
};
</script>

<template>
    <Slider
        :show="show"
        :title="title"
        @hide="emit('hide')"
    >
        <AuthorizationFallback :permissions="requiredPermissions">
            <div class="mt-4 space-y-4">
                <FormInput
                    v-model="formData.subject"
                    :focus="show"
                    label="Subject"
                    :error="formErrors?.subject"
                    required
                />

                <FormInput
                    v-model="formData.detailed_description"
                    label="Detailed Description"
                    :error="formErrors?.detailed_description"
                    required
                />

                <!-- Category Dropdown using VSelect -->
                <FormLabelError label="Category">
                    <VSelect
                        v-model="formData.category"
                        :options="categoryOptions"
                        label="label"
                        :reduce="option => option.value"
                        :clearable="false"
                    />
                </FormLabelError>

                <Button
                    :title="claim?.id ? 'Save' : 'Create'"
                    :loading-title="claim?.id ? 'Saving...' : 'Creating...'"
                    class="!w-full"
                    :loading="saving || updating"
                    @click="onSubmit"
                />
            </div>
        </AuthorizationFallback>
    </Slider>
</template>