import {onMounted, ref} from 'vue';
import {message} from "@/utils/utils";
import {useRouter} from "vue-router";

export function useList<T>(apiFn: (params: any) => Promise<any>) {
    const list = ref<T[]>([]);
    const loading = ref(false);

    //分页
    const page = ref(1);
    const pagination = ref({
        total: 0,
    })
    const keyword = ref('');

    const index = async () => {
        loading.value = true;

        const res = await apiFn({
            page: page.value,
            keyword: keyword.value || null
        });
        loading.value = false;
        if (res.code) {
            return message(res);
        }
        list.value = res.data;
        pagination.value = res.paginate;
    };

    const search = async () => {
        page.value = 1;
        await index();
    };
    const pageChange = async (num: number) => {
        page.value = num;
        await index();
    }

    onMounted(async () => {
        await index();
    })

    return {
        list,
        index,
        loading,
        page,
        pagination,
        pageChange,
        keyword,
        search,
    };
}

export function useCrud(options: { formRef: any; doSave: any; beforeSubmit: any; }) {
    const { formRef, doSave, beforeSubmit } = options;
    const router = useRouter();

    const handleSubmit = () => {
        if (!formRef.value) return;

        formRef.value.validate(async (valid: any) => {
            if (!valid) return;

            // 1. 如果有预处理逻辑则执行（如 toRaw）
            const finalData = beforeSubmit ? beforeSubmit() : {};

            try {
                // 2. 执行传入的 API 函数
                const result = await doSave(finalData);

                // 3. 统一反馈逻辑
                message(result, () => {
                    setTimeout(() => {
                        router.go(-1);
                    }, 500);
                });
            } catch (error) {
                console.error("提交异常:", error);
            }
        });
    };

    return { handleSubmit };
}