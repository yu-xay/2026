<template>
    <div
        class="min-h-screen flex flex-col items-center justify-center text-center px-6 relative"
        :style="{ backgroundImage: `url('https://picsum.photos/1920/1080')` }"
    >
        <!-- 半透明遮罩，提升文字对比度 -->
        <div class="absolute inset-0 bg-black/30"></div>

        <div class="relative z-10">
            <h1 class="text-9xl font-bold text-white mb-8 tracking-wider">404</h1>
            <p class="text-3xl font-medium text-white mb-4">页面未找到</p>
            <p class="text-xl text-white/80 mb-12 max-w-md mx-auto">
                抱歉，您访问的页面不存在。
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <el-button type="primary" size="large" round @click="goHome">
                    返回首页
                </el-button>
                <el-button size="large" round plain @click="goBack">
                    返回上一页
                </el-button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

// 随机数确保开发时背景可刷新（生产环境可移除或固定 seed）
const random = ref(Math.random())

const router = useRouter()

const goHome = () => router.push('/')
const goBack = () => router.back()
</script>