import {defineStore} from 'pinia'

export const useSettingStore = defineStore('setting', {
    persist: true,
    state: () => ({
        isCollapse: false,
    }),
    actions: {

    }
})