<template>
    <el-card body-class="bg-[#f3f3f3] border-0 dark:bg-black" body-style="padding: 10px 0 0;" header-class="border-0!"
             shadow="never"
             style="border:0">
        <template #header>
            <div class="flex-nowrap flex justify-between">
                <el-breadcrumb class="flex items-center" separator="/">
                    <el-breadcrumb-item :to="{ name: 'order' }">订单列表</el-breadcrumb-item>
                    <el-breadcrumb-item>订单详情</el-breadcrumb-item>
                </el-breadcrumb>
                <el-button @click="router.back()">返回</el-button>
            </div>
        </template>

        <div v-loading="loading" class="p-3 bg-white dark:bg-black">
            <el-row :gutter="20">
                <!-- 订单基本信息 -->
                <el-col :span="16">
                    <el-descriptions :column="2" border title="基本信息">
                        <el-descriptions-item label="订单号">{{ detail.order_sn }}</el-descriptions-item>
                        <el-descriptions-item label="订单状态">
                            <el-tag v-if="detail.status == 0" type="warning">待支付</el-tag>
                            <el-tag v-else-if="detail.status == 1" type="success">已支付</el-tag>
                            <el-tag v-else-if="detail.status == 2" type="primary">已发货</el-tag>
                            <el-tag v-else-if="detail.status == 3" type="info">已完成</el-tag>
                            <el-tag v-else-if="detail.status == 4" type="danger">已取消</el-tag>
                        </el-descriptions-item>
                        <el-descriptions-item label="买家昵称">{{ detail.address_snapshot?.nickname }}</el-descriptions-item>
                        <el-descriptions-item label="买家手机">{{ detail.address_snapshot?.phone || '未绑定' }}</el-descriptions-item>
                        <el-descriptions-item label="下单时间">{{ dateFormatter(null, null, detail.created_at) }}</el-descriptions-item>
                        <el-descriptions-item label="支付时间">{{ detail.pay_time || '未支付' }}</el-descriptions-item>
                        <el-descriptions-item label="订单总额"><span class="text-red-500 font-bold">￥{{ detail.total_amount }}</span></el-descriptions-item>
                        <el-descriptions-item label="买家备注">{{ detail.remark || '无' }}</el-descriptions-item>
                    </el-descriptions>

                    <div class="mt-5">
                        <h3 class="text-lg font-bold mb-3">商品清单</h3>
                        <el-table :data="detail.items" border>
                            <el-table-column label="商品图片" width="100">
                                <template #default="scope">
                                    <el-image :src="scope.row.pic" class="w-16 h-16" fit="cover" />
                                </template>
                            </el-table-column>
                            <el-table-column label="商品名称" prop="goods_name" />
                            <el-table-column label="规格" prop="goods_sku_name" width="150" />
                            <el-table-column label="单价" width="120">
                                <template #default="scope">￥{{ scope.row.price }}</template>
                            </el-table-column>
                            <el-table-column label="数量" prop="num" width="100" />
                            <el-table-column label="小计" width="120">
                                <template #default="scope">
                                    <span class="text-red-500">￥{{ (scope.row.price * scope.row.num).toFixed(2) }}</span>
                                </template>
                            </el-table-column>
                        </el-table>
                    </div>
                </el-col>

                <!-- 收货信息与物流 -->
                <el-col :span="8">
                    <el-descriptions :column="1" border title="收货信息">
                        <el-descriptions-item label="收货人">{{ detail.address_snapshot?.name }}</el-descriptions-item>
                        <el-descriptions-item label="联系电话">{{ detail.address_snapshot?.phone }}</el-descriptions-item>
                        <el-descriptions-item label="收货地址">{{ detail.address_snapshot?.address }}</el-descriptions-item>
                    </el-descriptions>

                    <el-descriptions v-if="detail.status >= 2" :column="1" border class="mt-5" title="物流信息">
                        <el-descriptions-item label="快递公司">{{ detail.express_company }}</el-descriptions-item>
                        <el-descriptions-item label="快递单号">{{ detail.express_no }}</el-descriptions-item>
                        <el-descriptions-item label="发货时间">{{ detail.delivery_time }}</el-descriptions-item>
                    </el-descriptions>

                    <div class="mt-5 flex flex-col gap-3">
                        <el-button v-if="detail.status == 1" type="success" @click="handleDelivery">立即发货</el-button>
                        <el-button v-if="detail.status == 0" type="danger" @click="handleCancel">取消订单</el-button>
                    </div>
                </el-col>
            </el-row>
        </div>
    </el-card>
</template>

<script lang="ts" setup>
import {onMounted, ref} from 'vue';
import {useRoute, useRouter} from "vue-router";
import {dateFormatter, message} from "@/utils/utils";
import * as OrderApi from '@/api/order';

const route = useRoute();
const router = useRouter();
const loading = ref(false);
const detail = ref<any>({});

const id = Number(route.params.id);

async function getDetail() {
    loading.value = true;
    try {
        const result = await OrderApi.showOrder(id);
        detail.value = result.data;
    } finally {
        loading.value = false;
    }
}

onMounted(() => {
    getDetail();
});

function handleDelivery() {
    // 逻辑同列表页，可复用弹窗组件
    message({code: 0, message: '请在列表页点击发货按钮进行操作'});
}

async function handleCancel() {
    const result = await OrderApi.postOrderCancel(id);
    message(result, function () {
        getDetail();
    });
}
</script>
