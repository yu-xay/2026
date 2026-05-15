<template>
    <el-container class="h-screen flex flex-col dark:black">
        <el-header class="shadow-sm flex items-center px-6">
            <div class="text-xl font-semibold text-gray-800 cursor-pointer" @click="settingStore.isCollapse=!settingStore.isCollapse">
                  名称
            </div>
            <el-button class="ml-auto text-gray-700 mr-3" @click="handlerClear" link>清除缓存</el-button>
            <el-button class="mr-3" @click="handleToggle">
                <el-icon v-if="isDark">
                    <Sunny/>
                </el-icon>
                <el-icon v-else>
                    <Moon/>
                </el-icon>
            </el-button>
            <!-- 账户信息与下拉菜单 -->
            <el-dropdown trigger="click">
                <div class="flex items-center cursor-pointer space-x-3">
                    <el-avatar
                        :size="40"
                        :src="userInfo?.avatar || '/path/to/default-avatar.png'"
                    />
                    <span class="text-gray-700 font-medium">{{ userInfo.name }}</span>
                    <el-icon class="text-gray-500">
                        <arrow-down/>
                    </el-icon>
                </div>
                <template #dropdown>
                    <el-dropdown-menu class="w-48">
                        <el-dropdown-item class="flex items-center" @click="handleCenter">
                            <el-icon>
                                <user/>
                            </el-icon>
                            <span>个人中心</span>
                        </el-dropdown-item>
                        <el-dropdown-item class="flex text-red-600!" divided @click="handleLogout">
                            <el-icon>
                                <switch-button/>
                            </el-icon>
                            <span>退出登录</span>
                        </el-dropdown-item>
                    </el-dropdown-menu>
                </template>
            </el-dropdown>
        </el-header>
        <el-container class="flex-1 overflow-hidden">
            <el-aside :width="settingStore.isCollapse ? 'auto' : '300px'" class=" bg-[#465269] dark:bg-gray-900! overflow-y-auto">
                <el-menu
                    background-color="transparent"
                    class="border-r-0!"
                    unique-opened
                    :collapse="settingStore.isCollapse"
                    :default-active="active"
                    text-color="#ffffff"
                    @select="handleJump"
                >
                    <template v-for="(item,index) in menus.filter(_ => _.is_menu)" :key="index">
                        <el-sub-menu v-if="item.children" :index="item.key">
                            <template #title>
                                <el-icon>
                                    <component :is="item.icon"/>
                                </el-icon>
                                <span>{{ item.name }}</span>
                            </template>
                            <el-menu-item v-for="(item2,index2) in item.children.filter(_ => _.is_menu)" :key="index2"
                                          :index="item2.key"
                                          class="hover:bg-gray-800 pl-12 py-3">
                                <span>{{ item2.name }}</span>
                            </el-menu-item>
                        </el-sub-menu>
                        <el-menu-item v-else :index="item.key" class="hover:bg-gray-800 px-4 py-3">
                            <el-icon>
                                <component :is="item.icon"/>
                            </el-icon>
                            <span>{{ item.name }}</span>
                        </el-menu-item>
                    </template>
                </el-menu>
            </el-aside>
            <!-- Main 内容区 -->
            <el-main class="overflow-y-auto bg-[#f3f3f3] dark:bg-black">
                <router-view/>
            </el-main>
        </el-container>
    </el-container>
</template>

<script lang="ts" setup>
import {useUserStore} from "@/stores/user";
import {useConfigStore} from '@/stores/config';
import {useRouter, useRoute} from 'vue-router'
import {onMounted, ref} from "vue";
import {LoginApi,CoreApi} from '@/api/index';
import {useBattery, useDark, useToggle} from "@vueuse/core";
import {ElMessageBox} from 'element-plus';
import {message} from "@/utils/utils";
import {useSettingStore} from "@/stores/setting";


const router = useRouter()
function handleJump(name: string) {
    router.push({name});
}
function handleCenter() {
    router.push({name: 'center'})
}

const settingStore = useSettingStore();

//暗黑模式
const isDark = useDark({
    selector: 'html',
    attribute: 'class',
    valueDark: 'dark',
    valueLight: '',
    storageKey: 'theme-mode',
})

const toggleDark = useToggle(isDark)
const handleToggle = (event: MouseEvent) => {
    if (!document.startViewTransition) {
        toggleDark()
        return
    }
    const x = event.clientX
    const y = event.clientY
    const endRadius = Math.hypot(
        Math.max(x, innerWidth - x),
        Math.max(y, innerHeight - y)
    )

    const transition = document.startViewTransition(() => {
        toggleDark()
    })

    transition.ready.then(() => {
        const root = document.documentElement.animate(
            {
                clipPath: [
                    `circle(0 at ${x}px ${y}px)`,
                    `circle(${endRadius}px at ${x}px ${y}px)`
                ]
            },
            {
                duration: 600,
                easing: 'ease-in-out',
                pseudoElement: '::view-transition-new(root)'
            }
        )
    })
}

//菜单
interface MenuItem {
    name: string;
    icon?: string;
    key: string;
    is_menu: boolean;
    children?: MenuItem[] | undefined;
}

const menus = ref<MenuItem[]>([]);
const configStore = useConfigStore();

onMounted(() => {
    menus.value = configStore.menus;
})

const route = useRoute()
const active = route.name;

//退出
const userStore = useUserStore();
const {userInfo} = userStore;
const handleLogout = () => {
    ElMessageBox.confirm('确定要退出登录吗？', '提示', {
        type: 'warning',
        cancelButtonText: '取消',
        confirmButtonText: '确认',
    }).then(() => {
        userStore.$reset();
        LoginApi.logout();
        router.push({name: 'login'})
    })
}

const handlerClear = async () => {
    const result = await CoreApi.clear();
    configStore.$reset();
    message(result);
}
</script>