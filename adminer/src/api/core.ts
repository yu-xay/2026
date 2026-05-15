import {request} from "@/utils/request";

export function getConfig() {
    return request({
        url: "config",
    });
}

export function clear() {
    return request({
        url: "clear",
        method: "post",
    });
}

export function getRoles() {
    return request({
        url: "roles",
    });
}

export function getPermissions() {
    return request({
        url: "permissions",
    });
}

export function getCats() {
    return request({
        url: "cats",
    });
}
