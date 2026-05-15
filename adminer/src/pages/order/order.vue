<template>
    <el-card body-class="bg-[#f3f3f3] border-0 dark:bg-black" body-style="padding: 10px 0 0;" header-class="border-0!"
             shadow="never"
             style="border:0">
        <template #header>
            <div class="flex-nowrap flex justify-between">
                <el-breadcrumb class="flex items-center" separator="/">
                    <el-breadcrumb-item>
                        <span>订单列表</span>
                    </el-breadcrumb-item>
                </el-breadcrumb>
            </div>
        </template>
        <div class="p-3 bg-white dark:bg-black">
            <!--工具条 过滤表单-->
            <el-col>
                <el-form :inline="true" class="demo-form-inline">
                    <el-form-item label="订单号">
                        <el-input v-model="keyword" clearable placeholder="请输入订单号搜索" @clear="search" @keyup.enter="search" />
                    </el-form-item>
                    <el-form-item label="订单状态">
                        <el-select v-model="status" placeholder="请选择状态" clearable @change="search" style="width: 150px">
                            <el-option label="全部" value="" />
                            <el-option label="待支付" value="0" />
                            <el-option label="已支付" value="1" />
                            <el-option label="已发货" value="2" />
                            <el-option label="已完成" value="3" />
                            <el-option label="已取消" value="4" />
                        </el-select>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" icon="Search" @click="search">查询</el-button>
                    </el-form-item>
                </el-form>
            </el-col>

            <el-table v-loading="loading" :data="list" border>
                <el-table-column label="订单号" prop="order_sn" width="200" />
                <el-table-column label="买家" prop="address_snapshot.name" />
                <el-table-column label="订单金额" prop="total_amount" >
                    <template #default="scope">
                        <span class="text-red-500">￥{{ scope.row.total_amount }}</span>
                    </template>
                </el-table-column>
                <el-table-column label="状态" prop="status">
                    <template #default="scope">
                        <el-tag v-if="scope.row.status == 0" type="warning">待支付</el-tag>
                        <el-tag v-else-if="scope.row.status == 1" type="success">已支付</el-tag>
                        <el-tag v-else-if="scope.row.status == 2" type="primary">已发货</el-tag>
                        <el-tag v-else-if="scope.row.status == 3" type="info">已完成</el-tag>
                        <el-tag v-else-if="scope.row.status == 4" type="danger">已取消</el-tag>
                    </template>
                </el-table-column>
                <el-table-column label="下单时间" prop="created_at" :formatter="dateFormatter"/>
                <el-table-column fixed="right" label="操作" >
                    <template #default="scope">
                        <el-button link type="primary" @click="jump(scope.row.id)">详情</el-button>
                        <el-button v-if="scope.row.status == 1" link type="success" @click="handleDelivery(scope.row)">发货</el-button>
                        <el-popconfirm v-if="configStore.hasPermission(configStore.delete)"
                                       :title="`确定删除该订单吗？`"
                                       @confirm="destroy(scope.row.id)">
                            <template #reference>
                                <el-button link type="danger">删除</el-button>
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

        <!-- 发货弹窗 -->
        <el-dialog v-model="deliveryVisible" title="订单发货" width="400px">
            <el-form :model="deliveryForm" label-width="80px">
                <el-form-item label="快递公司">
                    <el-input v-model="deliveryForm.express_company" placeholder="请输入快递公司" />
                </el-form-item>
                <el-form-item label="快递单号">
                    <el-input v-model="deliveryForm.express_no" placeholder="请输入快递单号" />
                </el-form-item>
            </el-form>
            <template #footer>
                <el-button @click="deliveryVisible = false">取消</el-button>
                <el-button type="primary" @click="submitDelivery">确定</el-button>
            </template>
        </el-dialog>
    </el-card>
</template>

<script lang="ts" setup>
import {ref} from 'vue';
import {useRouter} from "vue-router";
import {dateFormatter, message} from "@/utils/utils";
import {useConfigStore} from "@/stores/config";
import {useList} from "@/hooks/UseRequest";
import * as OrderApi from '@/api/order';

const configStore = useConfigStore();
const router = useRouter();
const status = ref('');

const {
    list,
    index,
    loading,
    page,
    pagination,
    pageChange,
    keyword,
    search,
} = useList((params) => OrderApi.getOrderList({...params, status: status.value}))

function jump(id: number) {
    router.push({name: 'order-detail', params: {id}});
}

async function destroy(id: number) {
    const result = await OrderApi.deleteOrder(id);
    message(result, function () {
        index();
    });
}

// 发货逻辑
const deliveryVisible = ref(false);
const currentOrder = ref<any>(null);
const deliveryForm = ref({
    express_company: '',
    express_no: ''
});

function handleDelivery(row: any) {
    currentOrder.value = row;
    deliveryVisible.value = true;
}

async function submitDelivery() {
    if (!deliveryForm.value.express_no) {
        return message({code: 1, message: '请输入快递单号'});
    }
    const result = await OrderApi.postOrderDelivery(currentOrder.value.id, deliveryForm.value);
    message(result, function () {
        deliveryVisible.value = false;
        index();
    });
}
</script>
