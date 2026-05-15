<template>
    <el-card body-class="bg-[#f3f3f3] border-0 dark:bg-black" body-style="padding: 10px 0 0;" header-class="border-0!"
             shadow="never"
             style="border:0">
        <template #header>
            <div class="flex-nowrap flex justify-between">
                <el-breadcrumb class="flex items-center" separator="/">
                    <el-breadcrumb-item>
                        <span>多租户</span>
                    </el-breadcrumb-item>
                </el-breadcrumb>
                <el-button v-if="configStore.hasPermission([configStore.create])" type="primary" @click="add">
                    {{ configStore.label[configStore.create] }}多租户
                </el-button>
            </div>
        </template>
        <div class="p-3 bg-white dark:bg-black">
            <!--工具条 过滤表单和新增按钮-->
            <el-col>
                <el-input
                    v-model="keyword"
                    class="w-2xs! mb-4"
                    clearable
                    placeholder="请输入ID或者名称搜索"
                    @clear="search"
                    @keyup.enter="search"
                >
                    <template #append>
                        <el-button icon="Search" @click="search"/>
                    </template>
                </el-input>
            </el-col>
            <el-table v-loading="loading" :data="list" border>
                <el-table-column label="标识" prop="id" width="120"/>
                <el-table-column label="租户名称" prop="name"/>
                <el-table-column label="域名" prop="domain">
                    <template #default="scope">
                        <el-link :href="scope.row.domain" target="_blank" type="primary" underline="never">
                            {{ scope.row.domain }}
                        </el-link>
                    </template>
                </el-table-column>
                <el-table-column label="数据库名" prop="tenancy_db_name"/>
                <el-table-column :formatter="dateFormatter" label="添加时间" prop="created_at" width="180"/>
                <el-table-column fixed="right" label="操作" width="120">
                    <template #default="scope">
                        <el-button v-if="configStore.hasPermission([configStore.update])" link type="primary"
                                   @click.prevent="edit(scope.row)">
                            {{ configStore.label[configStore.update] }}
                        </el-button>
                        <el-popconfirm v-if="configStore.hasPermission(configStore.delete)"
                                       :title="`确定${configStore.label[configStore.delete]}吗？`"
                                       @confirm="destroy(scope.row.id)">
                            <template #reference>
                                <el-button link type="danger">
                                    {{ configStore.label[configStore.delete] }}
                                </el-button>
                            </template>
                        </el-popconfirm>
                    </template>
                </el-table-column>
            </el-table>
            <div class="mt-3 flex justify-end">
                <el-pagination v-model:current-page="page"
                               :hide-on-single-page="true"
                               :total="pagination.total"
                               background
                               layout="prev, pager, next"
                               @change="pageChange"
                />
            </div>
        </div>
    </el-card>
    <el-dialog v-model="dialogVisible"
               :title="`租户${configStore.label[mType === 'add' ? configStore.create: configStore.update]}`"
               center width="500">
        <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="auto" @submit.prevent>
            <el-form-item label="标识" prop="id">
                <el-input v-model="ruleForm.id" :disabled="mType === 'edit'" placeholder="请输入标识"/>
            </el-form-item>
            <el-form-item label="租户名称" prop="name">
                <el-input v-model="ruleForm.name" placeholder="请输入租户名称"/>
            </el-form-item>
        </el-form>
        <template #footer>
            <div class="flex-row-reverse flex">
                <el-button :loading="btnLoading" type="primary" @click="confirm">
                    确定
                </el-button>
                <el-button class="mr-5" @click="dialogVisible = false">取消</el-button>
            </div>
        </template>
    </el-dialog>
</template>

<script lang="ts" setup>
import {reactive, ref, toRaw} from "vue";
import {dateFormatter, message} from "@/utils/utils";
import {useConfigStore} from "@/stores/config";
import {TenantApi} from '@/api/index';
import type {FormInstance, FormRules} from "element-plus";
import {useList} from "@/hooks/UseRequest";

const configStore = useConfigStore();

const {
    list,
    index,
    loading,
    page,
    pagination,
    pageChange,
    keyword,
    search,
} = useList(TenantApi.tenantList)

interface RuleForm {
    name: string
    id: string
}

const ruleForm = ref<RuleForm>({
    id: '',
    name: '',
})

const rules = reactive<FormRules<RuleForm>>({
    id: [
        {required: true, message: '标识不能为空', trigger: 'blur'},
    ],
    name: [
        {required: true, message: '名称不能为空', trigger: 'blur'},
    ]
})

const dialogVisible = ref(false);
const btnLoading = ref(false);

const mType = ref('');

function add() {
    mType.value = 'add';
    ruleForm.value = {
        id: '',
        name: '',
    }
    dialogVisible.value = true;
}

function edit(column: RuleForm) {
    mType.value = 'edit';
    ruleForm.value = structuredClone(toRaw(column));
    dialogVisible.value = true;
}

const ruleFormRef = ref<FormInstance>()

function confirm() {
    if (!ruleFormRef.value) return
    ruleFormRef.value.validate(async (valid, fields) => {
        if (valid) {
            mType.value === 'edit' ? await update(ruleForm.value.id) : await store();
        }
    });
}

async function store() {
    btnLoading.value = true;
    const result = await TenantApi.tenantStore(ruleForm.value);
    btnLoading.value = false;
    message(result, function () {
        index();
        dialogVisible.value = false;
    });
}

async function update(id: string) {
    btnLoading.value = true;
    const result = await TenantApi.tenantUpdate(id, ruleForm.value);
    btnLoading.value = false;
    message(result, function () {
        index();
        dialogVisible.value = false;
    });
}

async function destroy(id: string) {
    const result = await TenantApi.tenantDelete(id);
    message(result, function () {
        index();
    });
}
</script>