<template>
    <el-card body-class="bg-[#f3f3f3] border-0 dark:bg-black" body-style="padding: 10px 0 0;" header-class="border-0!"
             shadow="never" style="border:0">
        <template #header>
            <div class="flex-nowrap flex justify-between">
                <el-breadcrumb class="flex items-center" separator="/">
                    <el-breadcrumb-item>
                        <span style="color: #409EFF;cursor: pointer" @click="router.push({name: 'user'})">
                            用户
                        </span>
                    </el-breadcrumb-item>
                    <el-breadcrumb-item v-if="userId">
                        {{configStore.label[configStore.update]}}用户
                    </el-breadcrumb-item>
                    <el-breadcrumb-item v-else>{{ configStore.label[configStore.create] }}用户</el-breadcrumb-item>
                </el-breadcrumb>
            </div>
        </template>
        <el-card v-loading="formLoading" shadow="never">
            <el-form ref="editFormRef" :model="editForm" class="w-120!" :rules="rules" label-width="auto"
                     @submit.prevent>
                <el-form-item label="账号" prop="email">
                    <el-input v-model="editForm.email" placeholder="请输入账号"></el-input>
                </el-form-item>
                <el-form-item label="昵称" prop="name">
                    <el-input v-model="editForm.name" placeholder="请输入昵称"></el-input>
                </el-form-item>
                <el-form-item label="密码" prop="password">
                    <el-input v-model="editForm.password" placeholder="请输入密码"></el-input>
                </el-form-item>
                <el-form-item label="用户头像" prop="attachment_id">
                    <Upload v-model="imageList" :limit="1" @set="_ => editForm.attachment_id = _"></Upload>
                </el-form-item>
                <el-form-item label="权限" prop="role_id">
                    <el-select v-model="editForm.role_id" placeholder="请选择" :disabled="false">
                        <el-option
                            v-for="item in roles"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        />
                    </el-select>
                </el-form-item>
            </el-form>
            <FooterButton @confirm="confirm"></FooterButton>
        </el-card>
    </el-card>
</template>

<script lang="ts" setup>
import {type FormInstance, type FormRules} from "element-plus";
import {CoreApi, UserApi} from '@/api/index';
import {onMounted, reactive, ref} from "vue";
import FooterButton from "@/components/FooterButton.vue";
import Upload from "@/components/Upload.vue";
import {useRoute, useRouter} from "vue-router";
import {message} from '@/utils/utils';
import {useConfigStore} from "@/stores/config";

const configStore = useConfigStore();
const route = useRoute();
const userId = route.params.id as any as number;

const formLoading = ref(true);
const imageList = ref<{
    id: number,
    url: string
}[]>([]);

interface FormType {
    name: string,
    password?: string,
    role_id: number | null,
    attachment_id: number | null,
    email: string,
    avatar: string
}

const editForm = ref<FormType>({
    name: '',
    password: '',
    role_id: null,
    attachment_id: null,
    email: '',
    avatar: '',
})

const rules = reactive<FormRules<FormType>>({
    email: [
        {required: true, message: '账号不能为空', trigger: 'blur'},
    ],
    name: [
        {required: true, message: '用户名称不能为空', trigger: 'blur'},
    ],
    attachment_id: [
        {required: true, message: '头像不能为空', trigger: 'blur'},
    ],
    role_id: [
        {required: true, message: '权限不能为空', trigger: 'blur'},
    ]
})

onMounted(() => {
    getRoles();
    if (userId) {
        show();
    }
    formLoading.value = false;
})

interface RolesType {
    id: number;
    name: string;
}

const roles = ref<RolesType[]>([]);

async function getRoles() {
    const result = await CoreApi.getRoles()
    roles.value = result.data;
}

async function show() {
    const result = await UserApi.userShow(userId)
    if (result.code === 0) {
        editForm.value = result.data;
        if (result.data.attachment_id) {
            imageList.value = [{
                id: result.data.attachment_id,
                url: result.data.avatar,
            }]
        }
    }
}

const editFormRef = ref<FormInstance>()
function confirm() {
    if (!editFormRef.value) return;
    editFormRef.value.validate((valid, fields) => {
        if (valid) {
            userId ? update() : store();
        }
    });
}

const router = useRouter()
async function store() {
    const result = await UserApi.userStore(editForm.value);
    message(result, function () {
        setTimeout(() => {
            router.go(-1);
        }, 500)
    });
}

async function update() {
    const result = await UserApi.userUpdate(userId, editForm.value);
    message(result, function () {
        setTimeout(() => {
            router.go(-1);
        }, 500)
    });
}
</script>