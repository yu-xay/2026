<template>
    <div v-if="editor" class="min-w-2xl border border-gray-300 rounded-lg overflow-hidden">
        <div class="menu-bar bg-gray-100 border-b border-gray-300 p-2 flex flex-wrap gap-1">
            <button
                :class="{ 'bg-blue-500 text-white': editor.isActive('bold') }"
                :disabled="!editor.can().chain().focus().toggleBold().run()"
                class="px-3 py-1 rounded hover:bg-gray-300 disabled:opacity-50"
                title="粗体" type="button"
                @click="editor.chain().focus().toggleBold().run()"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M6 4h8a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6zM6 12h9a4 4 0 0 1 4 4 4 4 0 0 1-4 4H6z"/>
                </svg>
            </button>
            <!-- 新增图片上传按钮 -->
            <button
                class="px-3 py-1 rounded hover:bg-gray-300"
                title="上传图片" type="button"
                @click="triggerImageUpload"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="www.w3.org">
                    <path
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                        stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="2"></path>
                </svg>
            </button>
            <!-- 隐藏的文件输入框 -->
            <input
                ref="fileInput"
                accept="image/*"
                class="hidden"
                type="file"
                @change="onFileSelected"
            />

            <button
                :disabled="!editor.can().chain().focus().undo().run()"
                class="px-3 py-1 rounded hover:bg-gray-300 disabled:opacity-50"
                title="撤销" type="button"
                @click="editor.chain().focus().undo().run()"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12.5 8c-2.65 0-5.05.99-6.9 2.6L2 7v9h9l-3.62-3.62c1.39-1.16 3.16-1.88 5.12-1.88 3.54 0 6.55 2.31 7.6 5.5l2.37-.78C21.08 11.03 17.15 8 12.5 8z"/>
                </svg>
            </button>

            <button
                :disabled="!editor.can().chain().focus().redo().run()"
                class="px-3 py-1 rounded hover:bg-gray-300 disabled:opacity-50"
                title="重做" type="button"
                @click="editor.chain().focus().redo().run()"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12.5 8c-2.65 0-5.05.99-6.9 2.6L2 7v9h9l-3.62-3.62c1.39-1.16 3.16-1.88 5.12-1.88 3.54 0 6.55 2.31 7.6 5.5l2.37-.78C21.08 11.03 17.15 8 12.5 8z"
                        transform="scale(-1,1) translate(-24,0)"/>
                </svg>
            </button>
        </div>
        <editor-content :editor="editor" class="p-4 min-h-64"/>
    </div>
</template>
<script lang="ts" setup>
import {useEditor, EditorContent} from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Image from '@tiptap/extension-image'
import {onBeforeUnmount, ref, watch} from 'vue'
import {uploadFile} from "@/utils/request";

const model = defineModel();

const editor = useEditor({
    content: model.value || '',
    extensions: [
        StarterKit,
        Image.configure({
            inline: true,
            allowBase64: true,
        }),
    ],
    editorProps: {
        attributes: {
            class: 'focus:outline-none',
        },
    },
    onUpdate: () => {
        model.value = editor.value?.getHTML();
    },
})

// 【核心修复】监听外部传入的值，确保第一次异步加载的数据能进入编辑器
watch(() => model.value, (newValue) => {
    if (!editor.value) return;

    // 获取编辑器当前 HTML
    const isSame = editor.value.getHTML() === newValue;

    // 只有当外部传入的值与内部不一致时才更新
    // 主要是为了解决初始化时 content 为空的问题
    if (!isSame) {
        editor.value.commands.setContent(newValue || '');
    }
}, {immediate: true});
// 触发点击文件选择器
const fileInput = ref<HTMLInputElement | null>(null);

const triggerImageUpload = () => {
    fileInput.value?.click();
}

const onFileSelected = async (event: Event) => {
    const input = event.target as HTMLInputElement;
    const file = input.files?.[0];

    if (file) {
        await uploadFile({
            file: file,
            onSuccess: function (url: { url: string }) {
                editor.value?.chain().focus().setImage({src: url.url}).run();
            },
            onError: function () {

            },
        })
    }
    input.value = '';
}

onBeforeUnmount(() => {
    editor.value?.destroy()
})
</script>

