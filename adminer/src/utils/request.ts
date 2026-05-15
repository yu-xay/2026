//@ts-nocheck
import axios, {
    type AxiosInstance,
    type AxiosRequestConfig, type AxiosRequestHeaders,
    type AxiosResponse
} from "axios";
import {ElMessage} from "element-plus";
import router from "@/router";
const message: (message: string) => void = function (message) {
    ElMessage({
        message,
        type: 'error',
        plain: true,
    })
}

const instance: AxiosInstance = axios.create({
    headers: {
        common: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    },
    timeout: 0,
    withCredentials: true,
    withXSRFToken: true,
});

instance.defaults.baseURL = import.meta.env.VITE_APP_API_BASE_URL;

//请求拦截器
const m = instance.interceptors.request.use(
    config => {
        console.log('请求参数', config)
        return config;
    },
    error => {
        console.error('前置请求报错', error)
    }
)
instance.interceptors.request.eject(m);
//响应拦截器
instance.interceptors.response.use(
    (response: AxiosResponse) => response,
    async (error: any) => {
        if (error.response?.status === 401) {
            await router.push({name: 'login'});
            //  return instance(error.config); // 重新发送原始请求
        }
        if (error.response) {
            message(error.message);
        } else if (error.request) {
            message('请求发起，服务器未响');
        } else {
            message('发送请求时出了点问题');
        }
        throw error;
    }
)

export async function request(config: AxiosRequestConfig) {
    const fullUrl = {
        pathname: config.url
    };
    let axiosConfig: AxiosRequestConfig = {
        url: fullUrl.pathname,
        method: config.method,
        headers: {
            ...(config.headers ?? {})
        },
        withCredentials: true,
        data: config.data,
        validateStatus: function (status: number): boolean {
            return (status >= 200 && status < 300) || status == 422;
        },
    };
    if (!axiosConfig.method || axiosConfig.method.toUpperCase() === 'GET') {
        axiosConfig.params = {...axiosConfig.params, ...config.data};
        axiosConfig.data = undefined;
    }
    const response: AxiosResponse<any, any, []> = await instance.request(
        axiosConfig
    );
    return response.data;
}

export const uploadFile = async ({file, onSuccess, onError}) => {
    const formData = new FormData();
    formData.append('file', file);
    const result = await request({
        url: "upload",
        method: 'post',
        data: formData
    });
    if (result.code === 0) {
        onSuccess(result.data);
    } else {
        ElMessage({
            message: result.message,
            type: 'error',
            plain: true,
        })
        onError(result);
    }
};
export default request;
