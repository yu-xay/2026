<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-600 to-purple-700 flex items-center justify-center">
        <div class="bg-white rounded-2xl shadow-2xl p-10 w-full max-w-md">
            <h2 class="text-3xl font-bold text-center mb-8 text-gray-800">用户登录</h2>
            <el-form :model="loginForm" :rules="loginRules" ref="loginFormRef" @submit.prevent="handleLogin">
                <el-form-item prop="email" class="mb-3">
                    <el-input
                        v-model="loginForm.email"
                        placeholder="请输入用户名"
                        size="large"
                        clearable
                        :prefix-icon="ElementIcon.User"
                        class="mb-2"
                    />
                </el-form-item>
                <el-form-item prop="password">
                    <el-input
                        v-model="loginForm.password"
                        type="password"
                        clearable
                        placeholder="请输入密码"
                        size="large"
                        :prefix-icon="ElementIcon.Lock"
                        show-password
                        class="mb-2"
                    />
                </el-form-item>
                <el-form-item prop="captcha">
                    <div class="flex items-center gap-4">
                        <el-input
                            v-model="loginForm.captcha"
                            placeholder="请输入验证码"
                            size="large"
                            class="flex-1"
                        />
                        <img :src="captchaImg"
                             v-if="captchaImg"
                             class="w-32 h-10 bg-gray-100 border border-gray-300 rounded cursor-pointer  items-center  select-none"
                             @click="refreshCaptcha"
                        >
                        </img>
                    </div>
                </el-form-item>
                <el-form-item prop="remember">
                    <el-checkbox v-model="loginForm.remember" label="记住登录"/>
                </el-form-item>
                <el-button type="primary"
                           :loading="loading"
                           size="large"
                           native-type="submit"
                           class="w-full mt-2">
                    登录
                </el-button>
            </el-form>
        </div>
    </div>
</template>

<script setup lang="ts">
import {ref, reactive, onMounted} from 'vue';
import {useRouter} from 'vue-router';
import {LoginApi} from '@/api/index';
import * as ElementIcon from '@element-plus/icons-vue'
import {ElMessage} from 'element-plus';
import type {FormInstance, FormRules} from 'element-plus'
import {useUserStore} from '@/stores/user';
const userStore = useUserStore();
const loginRules = reactive<FormRules<RuleForm>>({
    email: [
        {required: true, message: '请输入用户名', trigger: 'blur'},
    ],
    password: [
        {required: true, message: '请输入密码', trigger: 'blur'},
    ]
});

//验证码
const captchaImg = ref("");

async function refreshCaptcha() {
    const result = await LoginApi.captcha();
    if (result.code === 0) {
        captchaImg.value = result.data.url;
    }
}

onMounted(() => {
    LoginApi.csrf();
    refreshCaptcha();
})

const loginFormRef = ref<FormInstance>();

interface RuleForm {
    email: string
    password: string
    captcha: string
    remember: boolean
}

const loginForm = reactive<RuleForm>({
    email: '',
    password: '',
    captcha: '',
    remember: false,
});

const loading = ref(false);

const router = useRouter();
const handleLogin = async () => {
    if (!loginFormRef.value) return;
    await loginFormRef.value.validate((valid, fields) => {
        if (valid) {
            loading.value = true;
            LoginApi.login(loginForm).then(res => {
                loading.value = false;
                if (res.code === 0) {
                    ElMessage.success('登录成功！');
                    userStore.$patch({
                        userInfo: res.data,
                    })
                    router.push('/'); // 假设您有一个名为 /home 的路由
                } else {
                    refreshCaptcha();
                    ElMessage.error(res.message);
                }
            }).catch(e => {
                loading.value = false;
                refreshCaptcha();
            })

        }
    })
};
</script>