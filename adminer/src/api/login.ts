import {request} from "@/utils/request";

export function login(data: object) {
    return request({
        url: "/password/login",
        method: 'post',
        data: data
    });
}

export function show() {
    return request({
        url: "/password/show",
    })
}
export function csrf() {
    return request({
        url: "/password/csrf-cookie",
    })
}
export function logout() {
    return request({
        url: "/password/logout",
    })
}
export function captcha() {
    return request({
        url: "/password/captcha",
    })
}