<template>
    <el-card body-class="bg-[#f3f3f3] border-0 dark:bg-black" body-style="padding: 10px 0 0;" header-class="border-0!"
             shadow="never"
             style="border:0">
        <template #header>
            <div class="flex-nowrap flex justify-between">
                <el-breadcrumb class="flex items-center" separator="/">
                    <el-breadcrumb-item>
                        <span>角色</span>
                    </el-breadcrumb-item>
                </el-breadcrumb>
                <el-button type="primary" @click="jump()" v-if="configStore.hasPermission([configStore.create])">
                    {{ configStore.label[configStore.create] }}角色
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
            <el-table :data="list" border v-loading="loading">
                <el-table-column label="Id" prop="id" width="120"/>
                <el-table-column label="角色名称" prop="name"/>
                <el-table-column label="关联用户数" prop="users_count"/>
                <el-table-column label="添加时间" prop="created_at" width="180" :formatter="dateFormatter"/>
                <el-table-column fixed="right" label="操作" width="120">
                    <template #default="scope">
                        <template v-if="scope.row.id == 1">
                            -
                        </template>
                        <template v-else>
                            <el-button link type="primary" v-if="configStore.hasPermission([configStore.update])"
                                       @click.prevent="jump(scope.row.id)">
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
</template>

<script lang="ts" setup>
import {UserApi} from '@/api/index';
import {useRouter} from "vue-router";
import {dateFormatter, message} from "@/utils/utils";
import {useConfigStore} from "@/stores/config";
import {useList} from '@/hooks/UseRequest'
//显示
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
} = useList(UserApi.roleList)

const router = useRouter()
function jump(id?: number) {
    router.push({name: 'role-edit', params: {id}});
}

async function destroy(id: number) {
    const result = await UserApi.roleDelete(id);
    message(result, function () {
        index();
    });
}
</script>