<script setup>
import { inject, computed } from 'vue';
import useHttpRequest from '../composables/useHttpRequest';
import useUserStore from '../store/useUserStore';
import useRoleStore from '../store/useRoleStore';
import usePermissionStore from '../store/usePermissionStore';
import useAppRouter from '../composables/useAppRouter';

const { isDarkMode, updateDarkMode, windowWidth } = inject('theme');

const { index: logout } = useHttpRequest('/logout');
const { pushToRoute } = useAppRouter();

const userStore = useUserStore();
const roleStore = useRoleStore();
const permissionStore = usePermissionStore();

const onLogout = async () => {
    const isLoggedOut = await logout();
    if (isLoggedOut) {
        userStore.setUser(null);
        userStore.users = [];
        roleStore.roles = [];
        permissionStore.permissions = [];

        await pushToRoute({ name: 'login' });
    }
};

const hasPermission = (permissions) => {
    return (userStore.user?.permissions || []).some((p) =>
        permissions.includes(p?.name)
    );
};
</script>

<template>
    <div>
        <!-- logo -->
        <RouterLink :to="{ name: 'users' }">
            <div class="text-3xl font-nabla hidden md:block">
                <img src="@/src/assets/2ca-logo.png" alt="2CA Logo" class="w-auto h-12">
            </div>
            <div class="text-2xl font-nabla md:hidden">
                <img src="@/src/assets/2ca-logo.png" alt="2CA Logo" class="w-auto h-10">
            </div>
        </RouterLink>

        <!-- menu links -->
        <div class="flex-start gap-4 lg:gap-8">
            <div class="flex flex-col gap-0.5">
                <div class="flex-start gap-6 lg:gap-8">
                    <RouterLink v-if="hasPermission(['stats-all', 'stats-view'])" :to="{ name: 'stats' }">
                        <span class="lg:text-lg font-bold hover:text-active-hover">Stats</span>
                    </RouterLink>
                    <RouterLink v-if="hasPermission(['claims-all', 'claims-view'])" :to="{ name: 'claims' }">
                        <span class="lg:text-lg font-bold hover:text-active-hover">Claims</span>
                    </RouterLink>
                    <RouterLink v-if="hasPermission(['users-all', 'users-view'])" :to="{ name: 'users' }">
                        <span class="lg:text-lg font-bold hover:text-active-hover">Users</span>
                    </RouterLink>
                    <RouterLink v-if="hasPermission(['roles-all', 'roles-view'])" :to="{ name: 'roles' }">
                        <span class="lg:text-lg font-bold hover:text-active-hover">Roles</span>
                    </RouterLink>
                    <RouterLink v-if="hasPermission(['permissions-all', 'permissions-view'])" :to="{ name: 'permissions' }">
                        <span class="lg:text-lg font-bold hover:text-active-hover">Permissions</span>
                    </RouterLink>

                    <span class="lg:text-lg font-bold hover:text-active-hover cursor-pointer text-red-200" @click="onLogout">
                        Logout
                    </span>
                </div>

                <div v-if="userStore.user?.id" class="text-xs text-emerald-300 flex justify-end">
                    {{ `${userStore.user?.name} (${userStore.user?.email})` }}
                </div>
            </div>

            <!-- theme toggle -->
            <span v-if="isDarkMode" class="hover:text-active-hover cursor-pointer" @click="updateDarkMode(false)">
                <svg viewBox="0 0 24 24" :width="windowWidth > 992 ? 24 : 18" :height="windowWidth > 992 ? 24 : 18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                </svg>
            </span>

            <span v-else class="hover:text-active-hover cursor-pointer" @click="updateDarkMode(true)">
                <svg viewBox="0 0 24 24" :width="windowWidth > 992 ? 24 : 18" :height="windowWidth > 992 ? 24 : 18" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </span>
        </div>
    </div>
</template>