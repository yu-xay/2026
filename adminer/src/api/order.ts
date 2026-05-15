import {request} from "@/utils/request";

// 订单列表
export function getOrderList(params: object) {
    return request({
        url: 'order',
        data: params
    });
}

// 订单详情
export function showOrder(id: number) {
    return request({
        url: "order/" + id,
    })
}

// 发货
export function postOrderDelivery(id: number, params: object) {
    return request({
        url: 'order/delivery/' + id,
        method: "POST",
        data: params
    });
}

// 取消订单
export function postOrderCancel(id: number) {
    return request({
        url: 'order/cancel/' + id,
        method: "POST"
    });
}

// 删除订单
export function deleteOrder(id: number) {
    return request({
        url: "order/" + id,
        method: "delete"
    })
}
