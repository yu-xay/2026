import {createRouter, createWebHistory} from 'vue-router'
import {useConfigStore} from "@/stores/config";
import {useRouterStore} from "@/stores/router";

const routes = [
    {
        path: '/',
        name: 'home',
        component: () => import('@/pages/home.vue'),
        redirect: '/center',
        children: [],
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('@/pages/login.vue')
    },
    {
        path: '/:pathMatch(.*)*',
        component: () => import('@/pages/404.vue')
    }
]
const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL), routes,
})

router.beforeEach(async (to, from) => {
    if (to.name === 'login') {
        return true;
    }
    //加载配置
    const configStore = useConfigStore();
    if (!configStore._loading) {
        await configStore.loadData();
    }
    //配置动态路由
    const routerStore = useRouterStore();
    if (!routerStore.loading) {
        routerStore.loadRoutes()
        return {...to, replace: true}
    }
});

export default router
