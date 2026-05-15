import 'element-plus/dist/index.css'
import 'element-plus/theme-chalk/dark/css-vars.css'
import "@/assets/css/tailwindcss.css";
// 核心
import {createApp} from 'vue'
import App from './App.vue'
const app = createApp(App)

// Element-ui
import * as ElementPlusIconsVue from '@element-plus/icons-vue'
for (const [key, component] of Object.entries(ElementPlusIconsVue)) {
    app.component(key, component)
}
import ElementPlus from 'element-plus'
app.use(ElementPlus)

//Pinia
import {createPinia} from 'pinia'
const pinia = createPinia()
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
pinia.use(piniaPluginPersistedstate)
app.use(pinia)

//Router
import router from './router/index.js'
app.use(router)

//其他



import dayjs from 'dayjs'
import utc from 'dayjs/plugin/utc'     // 处理 UTC
import timezone from 'dayjs/plugin/timezone' // 时区支持（可选）
dayjs.extend(utc)
dayjs.extend(timezone)

app.config.globalProperties.$dateFormatter = (row: any, column: any, value: string, options = {}) :string => {
    if (!value) return '-'
    return dayjs.utc(value).local().format('YYYY-MM-DD HH:mm:ss')
}


app.mount('#app')
