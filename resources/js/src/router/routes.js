export default [
    {
        path: '/',
        name: 'login',
        component: () => import('../pages/Login.vue'),
        meta: {
            layout: 'full',
            permissions: [],
        },
    },
    {
        path: '/stats',
        name: 'stats',
        component: () => import('../pages/Stats.vue'),
        meta: { requiresAuth: true }
    },

    {
        path: '/claims',
        name: 'claims',
        component: () => import('../pages/Claims.vue'),
        meta: {
            layout: 'dashboard',
            permissions: ['claims-all', 'claims-view'],
        },
    },

    {
        path: '/users',
        name: 'users',
        component: () => import('../pages/Users.vue'),
        meta: {
            layout: 'dashboard',
            permissions: ['users-all', 'users-view'],
        },
    },

    {
        path: '/roles',
        name: 'roles',
        component: () => import('../pages/Roles.vue'),
        meta: {
            layout: 'dashboard',
            permissions: ['roles-all', 'roles-view'],
        },
    },

    {
        path: '/permissions',
        name: 'permissions',
        component: () => import('../pages/Permissions.vue'),
        meta: {
            layout: 'dashboard',
            permissions: ['permissions-all', 'permissions-view'],
        },
    },
];
