<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;

class R extends Controller
{
    const MESSAGE = [
        'STORE' => '添加成功',
        'UPDATE' => '更新成功',
        'DESTROY' => '删除成功',
    ];
    const CODE_SUCCESS = 0;
    const CODE_ERROR = 1;


    protected function success($data = [], $paginate = null, $arr = []): JsonResponse
    {
        $result = array_merge($arr, [
            'code' => self::CODE_SUCCESS,
            'data' => $data,
        ]);
        if (isset($result['data']['message'])) {
            $result['message'] = $data['message'];
            unset($result['data']['message']);
        }
        if ($paginate) {
            $result['paginate'] = $paginate;
        }
        return response()->json($result);
    }

    protected function error(array $data = []): JsonResponse
    {
        $result = [
            'code' => self::CODE_ERROR,
            'data' => $data,
        ];
        if (isset($result['data']['message'])) {
            $result['message'] = $data['message'];
            unset($result['data']['message']);
        }
        if (empty($result['data'])) {
            unset($result['data']);
        }
        return response()->json($result);
    }

    protected function toArray(LengthAwarePaginator $model): array
    {
        return [
            'data' => $model->items(),
            'paginate' => [
                'total' => $model->total(),
                'current_page' => $model->currentPage(),
            ]
        ];
    }
}