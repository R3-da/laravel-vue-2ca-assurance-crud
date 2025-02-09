<script setup>
import { computed, ref, watch } from 'vue';
import Slider from '../ui/Slider.vue';
import FormInput from '../ui/FormInput.vue';
import FormLabelError from '../ui/FormLabelError.vue';
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
    props.claim ? `Update claim "${props.claim?.name}"` : 'Add new claim',
);

const initialFormData = () => {
    return {
        name: null,
        description: null,
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
    name: yup.string().nullable().required(),
    description: yup.string().nullable().required(),
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
                    v-model="formData.name"
                    :focus="show"
                    label="Name"
                    :error="formErrors?.name"
                    required
                />

                <FormInput
                    v-model="formData.description"
                    label="Description"
                    :error="formErrors?.description"
                    required
                />

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