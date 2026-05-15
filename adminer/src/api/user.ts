import {request} from "@/utils/request";

export function roleStore(params: object) {
    return request({
        url: "role",
        method: "POST",
        data: params
    });
}

export function roleUpdate(id: number, params: object) {
    return request({
        url: "role/" + id,
        method: "put",
        data: params
    });
}

export function roleShow(id: number) {
    return request({
        url: "role/" + id,
    })
}

export function roleList(params: object) {
    return request({
        url: "role",
        data: params
    })
}

export function roleDelete(id: number) {
    return request({
        url: "role/" + id,
        method: "delete"
    })
}

//用户
export function userStore(params: object) {
    return request({
        url: "user",
        method: "POST",
        data: params
    });
}

export function userUpdate(id: number, params: object) {
    return request({
        url: "user/" + id,
        method: "put",
        data: params
    });
}

export function userShow(id: number) {
    return request({
        url: "user/" + id,
    })
}

export function userList(params: object) {
    return request({
        url: "user",
        data: params
    })
}

export function userDelete(id: number) {
    return request({
        url: "user/" + id,
        method: "delete"
    })
}

