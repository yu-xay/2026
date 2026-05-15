import {defineStore} from 'pinia'
import {useRoute} from "vue-router";
import {CoreApi} from '@/api/index';

export const useConfigStore = defineStore('config', {
    persist: import.meta.env.MODE === 'production',
    state: () => ({
        view: 'view',
        create: 'create',
        delete: 'delete',
        update: 'update',
        _loading: false,
        ///////
        isAdmin: false,
        menus: [],
        label: {} as {[key:string]: string},
        permissions: [] as string[],
    }),
    actions: {
        hasPermission(type: string | string[], routeName: string = '') {
            if (this.isAdmin) {
                return true;
            }
            if (!routeName) {
                const route = useRoute() as { name: string };
                routeName = route.name;
            }
            const per = type instanceof Array ? type : [type];
            return per.some(_ => this.permissions.includes(routeName + '.' + _) || this.permissions.includes(routeName + '.*'));
        },
        async loadData() {
            const result = await CoreApi.getConfig();
            if (result.code == 0) {
                const {isAdmin, menus, permissions, label} = result.data;
                this.$patch({isAdmin, menus, permissions, label, _loading: true})
            }
        },
    }
})