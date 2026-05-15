<template>
    <el-card body-class="bg-[#f3f3f3] border-0 dark:bg-black" body-style="padding: 10px 0 0;" header-class="border-0!"
             shadow="never" style="border:0">
        <template #header>
            <div class="flex-nowrap flex justify-between">
                <el-breadcrumb class="flex items-center" separator="/">
                    <el-breadcrumb-item>
                        <span style="color: #409EFF;cursor: pointer" @click="router.push({name: 'role'})">
                            角色
                        </span>
                    </el-breadcrumb-item>
                    <el-breadcrumb-item v-if="roleId">
                        {{ configStore.label[configStore.update] }}角色
                    </el-breadcrumb-item>
                    <el-breadcrumb-item v-else>{{ configStore.label[configStore.create] }}角色</el-breadcrumb-item>
                </el-breadcrumb>
            </div>
        </template>
        <el-card v-loading="formLoading" shadow="never">
            <el-form ref="editFormRef" :model="editForm" :rules="rules" label-width="auto" @submit.prevent>
                <el-form-item label="角色名称" prop="name">
                    <el-input v-model="editForm.name" class="w-120!" placeholder="请添加角色名称"></el-input>
                </el-form-item>
                <el-form-item label="权限" prop="permissions">
                    <div>
                        <div class="mb-2">
                            <el-checkbox v-for="(label,value) of configStore.label" :key="value" :label="'一键' + label"
                                         :value="value"
                                         border
                                         @change="add($event, value)">
                            </el-checkbox>
                        </div>
                        <div v-for="permission in permissions" class="flex mb-2">
                            <div class="mr-3 w-50">{{ permission.name }}</div>
                            <el-checkbox-group v-model="editForm.permissions" size="large">
                                <el-checkbox-button v-for="core in permission.children" :key="core.id" :value="core.id">
                                    {{ configStore.label[core.value] }}
                                </el-checkbox-button>
                            </el-checkbox-group>
                        </div>
                    </div>
                </el-form-item>
            </el-form>
            <FooterButton @confirm="confirm"></FooterButton>
        </el-card>
    </el-card>
</template>

<script lang="ts" setup>
import {CoreApi, UserApi} from '@/api/index';
import {type FormInstance, type FormRules} from "element-plus";
import {onMounted, reactive, ref} from "vue";
import FooterButton from "@/components/FooterButton.vue";
import {useRoute, useRouter} from "vue-router";
import {message} from '@/utils/utils';
import {useConfigStore} from "@/stores/config";

const configStore = useConfigStore();

const route = useRoute();
const roleId = route.params.id as any as number;

const formLoading = ref(true);

interface FormType {
    name: string,
    permissions: number[],
}

const editForm = ref<FormType>({
    name: '',
    permissions: [],
})

const rules = reactive<FormRules<FormType>>({
    name: [
        {required: true, message: '角色名称不能为空', trigger: 'blur'},
    ]
})

interface permissions {
    id: number;
    value: string;
}

function add(event: boolean, value: string | number) {
    const s = new Set(editForm.value.permissions);
    permissions.value.forEach(t => t.children.forEach(_ => {
        if (_.value === value) {
            event ? s.add(_.id) : s.delete(_.id);
        }
    }))
    editForm.value.permissions = Array.from(s);
}

onMounted(() => {
    getPermission();
    if (roleId) {
        show()
    }
    formLoading.value = false;
})

interface Role {
    name: string;
    children: permissions[];
}

const permissions = ref<Role[]>([]);

async function getPermission() {
    const result = await CoreApi.getPermissions();
    permissions.value = result.data;
}

async function show() {
    const result = await UserApi.roleShow(roleId);
    if (result.code === 0) {
        editForm.value = result.data;
    }
}

const editFormRef = ref<FormInstance>()

function confirm() {
    if (!editFormRef.value) return;
    editFormRef.value.validate((valid, fields) => {
        if (valid) {
            roleId ? update() : store();
        }
    });
}

const router = useRouter()

async function store() {
    const result = await UserApi.roleStore(editForm.value);
    message(result, function () {
        setTimeout(() => {
            router.go(-1);
        }, 500)
    });
}

async function update() {
    const result = await UserApi.roleUpdate(roleId, editForm.value);
    message(result, function () {
        setTimeout(() => {
            router.go(-1);
        }, 500)
    });
}
</script>