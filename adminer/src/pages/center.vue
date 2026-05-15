<script setup lang="ts">
import {onMounted, ref} from "vue";
import {LoginApi} from "@/api/index";
import {ElMessage} from "element-plus";
import {request} from "@/utils/request";
import {useUserStore} from "@/stores/user";
import * as echarts from 'echarts';

const chartDom = ref<HTMLElement | null>(null);

onMounted(() => {
    if (chartDom.value) {
        const myChart = echarts.init(chartDom.value);
        myChart.setOption({
            title: { text: 'ECharts 示例' },
            xAxis: { data: ['A', 'B', 'C'] },
            yAxis: {},
            series: [{ type: 'bar', data: [5, 20, 36] }]
        });
    }
});


const {userInfo} = useUserStore();
async function login() {
    const result = await LoginApi.login({
        'user': 'admin',
        'password': 'admin'
    });
    ElMessage({
        message: result.data,
        type: 'error',
        plain: true,
    })
}

async function show() {
    const result = await LoginApi.show();
    ElMessage({
        message: result.data,
        type: 'error',
        plain: true,
    })
}
async function logout() {
    const result = await LoginApi.logout();
    ElMessage({
        message: result.data,
        type: 'error',
        plain: true,
    })
}
async function test() {
    const result = await LoginApi.csrf();
    ElMessage({
        message: 'setcookie',
        type: 'success',
        plain: true,
    })
}

import { useGeolocation } from '@vueuse/core'

// 直接解構獲取 座標、定位時間戳 以及 錯誤資訊
const { coords, locatedAt, error, resume, pause } = useGeolocation()
</script>

<template>
    <div>
        <h3>📍 我的當前位置</h3>

        <div v-if="error" class="error">
            定位出錯: {{ error.message }}
        </div>

        <div v-else-if="coords.latitude !== Infinity">
            <p>緯度: {{ coords.latitude }}</p>
            <p>經度: {{ coords.longitude }}</p>
            <p>精度: {{ coords.accuracy }} 米</p>

        </div>

        <div v-else>
            正在獲取定位中...
        </div>

        <!-- 手動控制 -->
        <button @click="pause">停止追蹤</button>
        <button @click="resume">恢復追蹤</button>
    </div>

    <el-button type="danger" @click="login">登录</el-button>
    <el-button type="info" @click="show">检测</el-button>
    <el-button type="info" @click="test">Test</el-button>
    <el-button type="info" @click="logout">登出</el-button>
</template>
