import {ElMessage} from "element-plus";
import dayjs from 'dayjs'
import {toRaw} from 'vue';
import utc from 'dayjs/plugin/utc'     // 处理 UTC
import timezone from 'dayjs/plugin/timezone' // 时区支持（可选）
dayjs.extend(utc)
dayjs.extend(timezone)

export function dateFormatter(row: any, column: any, value: string, options = {}) {
    if (!value) return '-'
    return dayjs.utc(value).local().format('YYYY-MM-DD HH:mm:ss')
}


export function message(result: { code: number; message: any; }, callback?: () => void) {
    if (result.code === 0) {
        ElMessage({
            message: result.message,
            type: 'success',
            plain: true,
        })
        if (typeof callback === 'function') {
            callback();
        }
    } else {
        ElMessage({
            message: result.message,
            type: 'error',
            plain: true,
        })
    }
}