import {request} from "@/utils/request";
export function tenantStore(params: object) {
    return request({
        url: "tenant",
        method: "POST",
        data: params
    });
}

export function tenantUpdate(id: string, params: object) {
    return request({
        url: "tenant/" + id,
        method: "put",
        data: params
    });
}

export function tenantShow(id: number) {
    return request({
        url: "tenant/" + id,
    })
}

export function tenantList(params: object) {
    return request({
        url: "tenant",
        data: params
    })
}

export function tenantDelete(id: string) {
    return request({
        url: "tenant/" + id,
        method: "delete"
    })
}
