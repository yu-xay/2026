<template>
    <el-card body-class="bg-[#f3f3f3] border-0 dark:bg-black" body-style="padding: 10px 0 0;" header-class="border-0!"
             shadow="never" style="border:0">
        <template #header>
            <div class="flex-nowrap flex justify-between">
                <el-breadcrumb class="flex items-center" separator="/">
                    <el-breadcrumb-item>
                        <span style="color: #409EFF;cursor: pointer" @click="router.push({name: 'user'})">
                            商品列表
                        </span>
                    </el-breadcrumb-item>
                    <el-breadcrumb-item v-if="goodsId">
                        {{ configStore.label[configStore.update] }}商品
                    </el-breadcrumb-item>
                    <el-breadcrumb-item v-else>{{ configStore.label[configStore.create] }}商品</el-breadcrumb-item>
                </el-breadcrumb>
            </div>
        </template>
        <el-card v-loading="formLoading" shadow="never" @submit.prevent>
            <el-form ref="editFormRef" :model="editForm" :rules="rules" label-width="auto">
                <el-form-item label="标题" prop="title">
                    <el-input v-model="editForm.title" class="w-120!" placeholder="请输入标题"></el-input>
                </el-form-item>
                <el-form-item label="副标题" prop="sub_title">
                    <el-input v-model="editForm.sub_title" class="w-120!" placeholder="请输入副标题"></el-input>
                </el-form-item>
                <el-form-item label="视频轮播图" prop="attachment_id">
                    <Upload v-model="editForm.pic" @set="_ => editForm.attachment_id = _"></Upload>
                </el-form-item>
                <!-- 规格 -->
                <el-form-item label="规格组">
                    <div class="w-[62vw] border border-[#EBEEF5]">
                        <div v-for="(group, gIndex) in specGroups" :key="gIndex"
                             class="flex flex-col p-2">
                            <div class="flex items-center bg-[#f8f8f8] dark:bg-[#121212] h-11 px-4">
                                <div class="mr-2">规格名:</div>
                                <el-input v-model="group.name" class="w-40!"/>
                                <el-image :src="GrImg" class="size-5 cursor-pointer ml-auto"
                                          @click="groupDelete(gIndex)"></el-image>
                            </div>
                            <div class="flex mt-2 px-4 flex-nowra">
                                <div class="shrink-0 grow-0">规格值：</div>
                                <div class="flex flex-wrap">
                                    <VueDraggable v-model="group.children" class="flex flex-wrap" item-key="index"
                                                  @update="generatedSkus">
                                        <view v-for="(val, vIndex) in group.children" :key="vIndex"
                                              class="relative mr-4 group mb-4">
                                            <el-input v-model="val.name"
                                                      class="w-[150px]!"></el-input>
                                            <img
                                                :src="DelImg"
                                                alt=""
                                                class="absolute w-4 h-4 -top-2 -right-2 z-99 cursor-pointer hidden group-hover:block group-focus-within:block"
                                                @click="valueDelete(gIndex,vIndex)">
                                        </view>
                                    </VueDraggable>

                                    <el-button class="h-8! mb-4" link type="primary"
                                               @click="valueAdd(gIndex)">
                                        添加规格值
                                    </el-button>
                                </div>
                            </div>
                        </div>
                        <el-button type="primary" class="ml-2 mb-2 mt-2" @click="groupAdd">添加规格</el-button>
                    </div>
                </el-form-item>
                <el-form-item v-if="skus.length > 0" label=" ">
                    <el-table :data="skus" border class="max-w-full w-8/10!">
                        <el-table-column type="selection"/>
                        <el-table-column v-for="(item,index) in specGroups" :label="item.name">
                            <template #default="{row}">
                                {{ attrName(item, row) }}
                            </template>
                        </el-table-column>
                        <el-table-column label="封面图片" width="120">
                            <template #default="{ row}">
                                <Upload v-model="row.images" :limit="1"/>
                            </template>
                        </el-table-column>
                        <el-table-column label="价格" width="200">
                            <template #default="{ row }">
                                <el-input v-model.number="row.price" placeholder="价格" type="number"/>
                            </template>
                        </el-table-column>
                        <el-table-column label="库存" width="200">
                            <template #default="{ row }">
                                <el-input v-model.number="row.stock" placeholder="库存" type="number"/>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-form-item>
                <!-- 规格 -->
                <el-form-item class="w-120!" label="上架" prop="status">
                    <el-switch v-model="editForm.status" :active-value="1" :inactive-value="0"></el-switch>
                </el-form-item>
                <el-form-item class="w-120!" label="分类" prop="category_id">
                    <el-select v-model="editForm.category_id" :disabled="false" placeholder="请选择分类">
                        <el-option
                            v-for="item in cats"
                            :key="item.id"
                            :label="item.name"
                            :value="item.id"
                        />
                    </el-select>
                </el-form-item>
                <el-form-item class="w-120!" label="排序" prop="sort">
                    <el-input v-model="editForm.sort" placeholder="请输入排序" type="number"></el-input>
                </el-form-item>
                <el-form-item class="w-240!" label="图文详情" prop="description">
                    <RichTextEditor v-model="editForm.description"/>
                </el-form-item>
            </el-form>
            <FooterButton @confirm="handleSubmit"></FooterButton>
        </el-card>
    </el-card>
</template>

<script lang="ts" setup>

import {type FormInstance, type FormRules} from "element-plus";
import RichTextEditor from '@/components/RichTextEditor.vue'
import {CoreApi, GoodsApi} from '@/api/index';
import {onMounted, reactive, ref, toRaw} from "vue";
import FooterButton from "@/components/FooterButton.vue";
import Upload from "@/components/Upload.vue";
import {useRoute, useRouter} from "vue-router";
import {useConfigStore} from "@/stores/config";
import DelImg from '@/assets/image/goods-edit-del.png';
import GrImg from '@/assets/image/g-r.png';
import {type GroupType, type SkuItem, Attr} from "@/hooks/Attr";
import {useCrud} from "@/hooks/UseRequest";
import {VueDraggable} from 'vue-draggable-plus'

const configStore = useConfigStore();
const route = useRoute();
const router = useRouter();
const goodsId = Number(route.params.id);
const {
    specGroups,
    skus,
    generatedSkus,
    groupAdd,
    groupDelete,
    valueAdd,
    valueDelete,
    attrName,
} = Attr();

const formLoading = ref(true);

interface ImageItem {
    id: number,
    url: string
}

interface FormType {
    title: string,
    sub_title: string,
    pic: ImageItem[],
    status: number,
    category_id: number | null
    sort: number,
    attachment_id: number | null,
    description: string,
    spec_groups: GroupType[],
    skus: SkuItem[],
}

const editForm = ref<FormType>({
    title: '',
    sub_title: '',
    pic: [],
    status: 0,
    category_id: null,
    sort: 0,
    attachment_id: null,
    description: '',
    spec_groups: [],
    skus: [],
})

const rules = reactive<FormRules<FormType>>({
    title: [
        {required: true, message: '标题不能为空', trigger: 'blur'},
    ],
    category_id: [
        {required: true, message: '权限不能为空', trigger: 'blur'},
    ],
    sort: [
        {required: true, message: '排序不能为空', trigger: 'blur'},
    ],
    attachment_id: [
        {required: true, message: '轮播图不能为空', trigger: 'blur'},
    ],
})

onMounted(() => {
    getCats();
    if (goodsId) {
        show();
    } else {
        formLoading.value = false;
    }
})

async function show() {
    try {
        const {code, data} = await GoodsApi.showGoods(goodsId)
        if (code === 0) {
            editForm.value = data;
            specGroups.value = editForm.value.spec_groups;
            skus.value = editForm.value.skus;
        }
    } finally {
        formLoading.value = false;
    }
}

interface RolesType {
    id: number;
    name: string;
}

const cats = ref<RolesType[]>([]);

async function getCats() {
    const result = await CoreApi.getCats()
    cats.value = result.data;
}

const editFormRef = ref<FormInstance>()

const {handleSubmit} = useCrud({
    formRef: editFormRef,
    doSave: (data: object) => goodsId ? GoodsApi.putGoods(goodsId, data) : GoodsApi.postGoods(data),
    beforeSubmit: () => {
        return {
            ...editForm.value,
            skus: toRaw(skus.value),
            spec_groups: toRaw(specGroups.value)
        };
    }
});
</script>