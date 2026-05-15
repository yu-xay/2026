<script setup lang="ts">
import { uploadFile } from "@/utils/request";
import { ref, watch } from "vue";
import type { UploadProps,ImageInstance } from "element-plus";

const props = defineProps({
    limit: {
        type: Number,
        default: 999
    },
    tip: { type: String, default: '建议尺寸 200x200' },
});
const emit = defineEmits(["set"]);

interface ImageItem {
    id: number;
    url: string;
}

const model = defineModel<ImageItem[]>({ default: () => [] });

const fileList = ref<{ id: number; url: string }[]>([]);

const handleRemove = (index: number) => {
    model.value.splice(index, 1);
    if(props.limit == 1){
        emit("set", model.value[0] ? model.value[0].id : 0);
    } else {
        emit("set", model.value.map(_ => _.id));
    }
};

const handleSuccess: UploadProps['onSuccess'] = (res) => {
    if (res?.id && res?.url) {
        model.value.push({ id: res.id, url: res.url });
        if(props.limit == 1){
            emit("set", model.value[0] ? model.value[0].id : 0);
        } else {
            emit("set", model.value.map(_ => _.id));
        }
    }
};
const imageRefs = ref<ImageInstance[]>([])
const handleClick = (index: number) => {
    if (imageRefs.value[index]) {
        imageRefs.value[index].showPreview()
    }
}
</script>

<template>
    <div class="flex flex-wrap gap-3">
        <div v-for="(item, index) in model" :key="index"
             class="w-20 h-20 rounded-lg group border border-gray-200 overflow-hidden relative shadow-sm">
            <el-image ref="imageRefs" :preview-src-list="model.map(v => v.url)" :initial-index="index"
                      :preview-teleported="true" :src="item.url" class="w-full h-full object-cover"/>
            <div class="absolute bg-black/0 group-hover:bg-black/60 inset-0 flex items-center justify-center transition-all">
                <div class="flex gap-3 text-white opacity-0 group-hover:opacity-100">
                    <el-icon class="cursor-pointer" @click="handleClick(index)"><ZoomIn /></el-icon>
                    <el-icon class="cursor-pointer" @click="handleRemove(index)"><Delete /></el-icon>
                </div>
            </div>
        </div>

        <el-upload
            v-if="model.length < limit"
            action="#"
            multiple
            :limit="limit"
            :show-file-list="false"
            :http-request="uploadFile"
            :on-success="handleSuccess"
        >
            <div class="w-20 h-20 relative group border border-dashed border-gray-300 rounded-lg bg-gray:50 flex items-center justify-center hover:border-blue-500 hover:bg-blue-50/50 transition-all overflow-hidden">
                <div class="flex text-gray-400 group-hover:opacity-0 transition-opacity">
                    <el-icon><Plus /></el-icon>
                </div>

                <div class="absolute flex opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="text-[11px] text-blue-600 font-medium text-center leading-relaxed">
                        {{ tip }}
                    </span>
                </div>
            </div>
        </el-upload>
    </div>
</template>
