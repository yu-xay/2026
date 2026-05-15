<template>
    <div class="fixed z-99 bottom-0 right-0 p-4 bg-white dark:bg-black shadow-md flex justify-center transition-all duration-100 ease-in-out"
         :style="{width: `calc(100vw - ${settingStore.isCollapse ? 64 : 300}px - var(--el-main-padding))`}">
        <el-button @click="handleCancel">
            取消
        </el-button>
        <el-button type="primary"   @click="handleConfirm">
            保存
        </el-button>
    </div>
    <div class="w-1 h-20">

    </div>
</template>

<script lang="ts" setup>
import {useRouter} from 'vue-router';
import {useSettingStore} from "@/stores/setting";

const router = useRouter();
const settingStore = useSettingStore();
const emit = defineEmits(['cancel', 'confirm']);

const handleCancel = () => {
    emit('cancel');
    router.go(-1);
};

const handleConfirm = () => {
    emit('confirm');
};
</script>
