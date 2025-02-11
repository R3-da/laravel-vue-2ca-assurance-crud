<script setup>
import { ref } from 'vue';
import FormInput from '../components/ui/FormInput.vue';
import Button from '../components/ui/Button.vue';

import useHttpRequest from '../composables/useHttpRequest';
import useValidation from '../composables/useValidation';
import useAppRouter from '../composables/useAppRouter';
import useUserStore from '../store/useUserStore';

const { store: login, saving: loggingIn } = useHttpRequest('/login');
const { runYupValidation } = useValidation();
const { pushToRoute } = useAppRouter();
const userStore = useUserStore();

import { string, object } from 'yup';

const formData = ref({
    email: null,
    password: null,
});
const formErrors = ref({});

const schema = object().shape({
    email: string().email().nullable().required(),
    password: string().nullable().required(),
});

const onSignIn = async () => {
    if (loggingIn.value) return;

    const { validated, data, errors } = await runYupValidation(
        schema,
        formData.value,
    );

    if (!validated) {
        formErrors.value = errors;
        return;
    }

    formErrors.value = {};
    const user = await login(data);

    if (user?.id) {
        userStore.setUser(user);
        await pushToRoute({ name: 'claims' });
    }
};
</script>

<template>
    <section class="">
        <div
            class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0"
        >
            <div
                class="w-full bg-white rounded-lg dark:border md:mt-0 sm:max-w-2xl xl:p-0 dark:bg-gray-800 dark:border-gray-700 shadow-google"
            >
                <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                    <h1
                        class="text-xl font-bold leading-tight tracking-tight text-active md:text-2xl"
                    >
                        Sign in to your account
                    </h1>

                    <!-- Horizontal layout for each credentials block -->
                    <div class="flex flex-row justify-center gap-4 md:gap-6 w-full">
                        <!-- Admin credentials -->
                        <div class="flex flex-col max-w-[300px]">
                            <div class="font-bold underline">admin credentials</div>
                            <div class="dark:text-[#aaa]">
                                "admin@admin.com"
                            </div>
                            <div class="dark:text-[#aaa]">
                                "password"
                            </div>
                        </div>

                        <!-- Broker credentials -->
                        <div class="flex flex-col max-w-[300px]">
                            <div class="font-bold underline">broker credentials</div>
                            <div class="dark:text-[#aaa]">
                                "broker@broker.com"
                            </div>
                            <div class="dark:text-[#aaa]">
                                "password"
                            </div>
                        </div>

                        <!-- Client credentials -->
                        <div class="flex flex-col max-w-[300px]">
                            <div class="font-bold underline">client credentials</div>
                            <div class="dark:text-[#aaa]">
                                "client@client.com"
                            </div>
                            <div class="dark:text-[#aaa]">
                                "password"
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4 md:space-y-6">
                        <div>
                            <FormInput
                                v-model="formData.email"
                                label="Email"
                                :error="formErrors?.email"
                            />
                        </div>

                        <div>
                            <FormInput
                                v-model="formData.password"
                                label="Password"
                                type="password"
                                :error="formErrors?.password"
                            />
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input
                                        id="remember"
                                        aria-describedby="remember"
                                        type="checkbox"
                                        class="w-4 h-4 border border-gray-300 rounded bg-gray-50 dark:border-gray-600 dark:accent-active text-white"
                                        required=""
                                    />
                                </div>
                                <div class="ml-3 text-sm">
                                    <label
                                        for="remember"
                                        class="text-gray-500 dark:text-gray-300"
                                        >Remember me</label
                                    >
                                </div>
                            </div>
                        </div>

                        <Button
                            title="Sign in"
                            class="!w-full"
                            loading-title="Signing in..."
                            :loading="loggingIn"
                            @click="onSignIn"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
