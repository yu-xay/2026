<template>
    <el-card body-class="bg-[#f3f3f3] border-0 dark:bg-black" body-style="padding: 10px 0 0;" header-class="border-0!"
             shadow="never"
             style="border:0">
        <template #header>
            <div class="flex-nowrap flex justify-between">
                <el-breadcrumb class="flex items-center" separator="/">
                    <el-breadcrumb-item>
                        <span>商品分类</span>
                    </el-breadcrumb-item>
                </el-breadcrumb>
                <el-button v-if="configStore.hasPermission([configStore.create])" type="primary" @click="add">
                    {{ configStore.label[configStore.create] }}分类
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
                    placeholder="请输入名称搜索"
                    @clear="search"
                    @keyup.enter="search"
                >
                    <template #append>
                        <el-button icon="Search" @click="search"/>
                    </template>
                </el-input>
            </el-col>
            <el-table v-loading="loading" :data="list" border>
                <el-table-column label="Id" prop="id" width="120"/>
                <el-table-column label="分类名称" prop="name"/>
                <el-table-column label="关联商品数" prop="goods_count"/>
                <el-table-column label="排序" prop="sort"/>
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
    <el-dialog v-model="dialogVisible" center title="商品分类添加" width="500">
        <el-form ref="ruleFormRef" :model="ruleForm" :rules="rules" label-width="auto" @submit.prevent>
            <el-form-item label="名称" prop="name">
                <el-input v-model="ruleForm.name" placeholder="请输入名称"></el-input>
            </el-form-item>
            <el-form-item label="排序" prop="sort">
                <el-input v-model="ruleForm.sort" placeholder="请输入排序" type="number"></el-input>
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
import {onMounted, reactive, ref, toRaw} from "vue";
import {dateFormatter, message} from "@/utils/utils";
import {useConfigStore} from "@/stores/config";
import {GoodsApi} from '@/api/index';
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
} = useList(GoodsApi.getGoodsCatList)

interface RuleForm {
    name: string
    sort: number
    id?: number
}

const ruleForm = ref<RuleForm>({
    name: '',
    sort: 0,
    id: 0,
})

const rules = reactive<FormRules<RuleForm>>({
    name: [
        {required: true, message: '名称不能为空', trigger: 'blur'},
    ],
    sort: [
        {required: true, message: '排序不能为空', trigger: 'blur'},
    ]
})
const dialogVisible = ref(false);
const btnLoading = ref(false);

function add() {
    ruleForm.value = {
        name: '',
        sort: 0,
        id: 0,
    }
    dialogVisible.value = true;
}

function edit(column: RuleForm) {
    ruleForm.value = structuredClone(toRaw(column));
    dialogVisible.value = true;
}

const ruleFormRef = ref<FormInstance>()

function confirm() {
    if (!ruleFormRef.value) return
    ruleFormRef.value.validate(async (valid, fields) => {
        if (valid) {
            ruleForm.value.id ? await update(ruleForm.value.id) : await store();
        }
    });
}

async function store() {
    btnLoading.value = true;
    const result = await GoodsApi.postGoodsCat(ruleForm.value);
    btnLoading.value = false;
    message(result, function () {
        index();
        dialogVisible.value = false;
    });
}

async function update(id: number) {
    btnLoading.value = true;
    const result = await GoodsApi.putGoodsCat(id, ruleForm.value);
    btnLoading.value = false;
    message(result, function () {
        index();
        dialogVisible.value = false;
    });
}

async function destroy(id: number) {
    const result = await GoodsApi.deleteGoodsCat(id);
    message(result, function () {
        index();
    });
}
</script>