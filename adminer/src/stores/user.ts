import {defineStore} from 'pinia'
import {ref} from 'vue'


export const useUserStore = defineStore('user', () => {
    const userInfo = ref<{
        name: string;
        email: string;
        avatar: string;
        id: number;
    }>({name: '', email: '', avatar: '', id: 0});

    function $reset() {
        userInfo.value = {name: '', email: '', avatar: '', id: 0};
    }

    return { userInfo, $reset}
},{persist: true})