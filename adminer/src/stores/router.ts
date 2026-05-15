import {defineStore} from 'pinia'
import router from '@/router'
import {ref} from 'vue'
import {useConfigStore} from '@/stores/config';

export const useRouterStore = defineStore('router', () => {
    const loading = ref(false);

    function loadRoutes() {
        const configStore = useConfigStore();

        interface Role {
            name: string;
            page: string;
            key: string;
            children: {
                page: string,
                key: string
            }[];
        }

        const menus = configStore.menus as Role[];
        menus.forEach(menu => {
            if (menu.children && menu.children.length) {
                const prefix = menu.key.slice(1);
                menu.children.forEach(m => {
                    router.addRoute('home', {
                        path: m.page || m.key,
                        name: m.key,
                        component: () => import(`@/pages/${prefix}/${m.key}.vue`),
                    })
                })
            } else {
                router.addRoute('home', {
                    path: menu.page || menu.key,
                    name: menu.key,
                    component: () => import(`@/pages/${menu.key}.vue`),
                })
            }
        })
        loading.value = true;
    }

    return {loading, loadRoutes}
})

