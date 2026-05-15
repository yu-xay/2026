<template>
    <el-card body-class="bg-[#f3f3f3] border-0 dark:bg-black" body-style="padding: 10px 0 0;" header-class="border-0!"
             shadow="never"
             style="border:0">
        <template #header>
            <div class="flex-nowrap flex justify-between">
                <el-breadcrumb class="flex items-center" separator="/">
                    <el-breadcrumb-item>
                        <span>商品列表</span>
                    </el-breadcrumb-item>
                </el-breadcrumb>
                <el-button v-if="configStore.hasPermission([configStore.create])" type="primary" @click="jump()">
                    {{ configStore.label[configStore.create] }}商品
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
                <el-table-column label="Id" prop="id" width="120"/>
                <el-table-column label="标题" prop="title"/>
                <el-table-column label="分类" prop="cat.name"/>
                <el-table-column label="状态" prop="status">
                    <template #default="scope">
                        <el-tag v-if="scope.row.status == 1">上架</el-tag>
                        <el-tag v-if="scope.row.status == 0">下架</el-tag>
                    </template>
                </el-table-column>

                <el-table-column :formatter="dateFormatter" label="添加时间" prop="created_at" width="180"/>
                <el-table-column fixed="right" label="操作" width="120">
                    <template #default="scope">
                        <el-button v-if="configStore.hasPermission([configStore.update])" link type="primary"
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
import {GoodsApi} from '@/api/index';
import {useRouter} from "vue-router";
import {dateFormatter, message} from "@/utils/utils";
import {useConfigStore} from "@/stores/config";
import {useList} from "@/hooks/UseRequest";

// (function() {
//     function debuggerTrap() {
//         const t = function() {
//             // 這種寫法可以繞過許多簡單的去混淆工具
//             (function() {
//                 return false;
//             }['constructor']('debugger')['call']());
//         };
//         try {
//             t();
//         } catch (e) {
//             // 如果環境嘗試攔截，則進入死循環
//             setTimeout(debuggerTrap, 100);
//         }
//     }
//     // 設定每 2 秒執行一次防護檢查
//     setInterval(debuggerTrap, 2000);
// })();
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
} = useList(GoodsApi.getGoodsList)

const router = useRouter()

function jump(id?: number) {
    router.push({name: 'goods-edit', params: {id}});
}

async function destroy(id: number) {
    const result = await GoodsApi.deleteGoods(id);
    message(result, function () {
        index();
    });
}
</script>