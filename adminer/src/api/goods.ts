import {request} from "@/utils/request";

//商品分类
export function getGoodsCatList(params: object) {
    return request({
        url: 'goods-cat',
        data: params
    });
}

export function postGoodsCat(params: object) {
    return request({
        url: 'goods-cat',
        method: "POST",
        data: params
    });
}

export function putGoodsCat(id: number, params: object) {
    return request({
        url: "goods-cat/" + id,
        method: "put",
        data: params
    });
}

export function deleteGoodsCat(id: number) {
    return request({
        url: "goods-cat/" + id,
        method: "delete"
    })
}

//商品
export function getGoodsList(params: object) {
    return request({
        url: 'goods',
        data: params
    });
}

export function showGoods(id: number) {
    return request({
        url: "goods/" + id,
    })
}

export function postGoods(params: object) {
    return request({
        url: 'goods',
        method: "POST",
        data: params
    });
}

export function putGoods(id: number, params: object) {
    return request({
        url: "goods/" + id,
        method: "put",
        data: params
    });
}

export function deleteGoods(id: number) {
    return request({
        url: "goods/" + id,
        method: "delete"
    })
}

