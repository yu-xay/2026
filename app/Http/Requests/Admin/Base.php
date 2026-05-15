<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class Base extends FormRequest
{
    protected $stopOnFirstFailure = true;


    public function authorize(): bool
    {
        return auth()->check();
    }

//    public function all($keys = null)
//    {
//        $data = parent::all($keys);
//        dd($data);
//        // 'id' 是你在路由文件中定义的占位符名称，例如 Route::put('/goods/edit/{id}', ...)
//        $data['id'] = $this->route('id');
//        return $data;
//    }


    public function rules(): array
    {
        return match ($this->route()?->getActionMethod()) {
            'index' => $this->indexRules(),
            'store' => $this->storeRules(),
            'show' => $this->showRules(),
            'update' => $this->updateRules(),
            'destroy' => $this->destroyRules(),
            default => [],
        };
    }

    protected function indexRules(): array
    {
        return [
            'page' => 'required|integer|min:1',
            'per_page' => 'integer|between:1,100',
            'keyword' => 'nullable|string|max:50',
            'keywords' => 'nullable|array',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'order_by' => 'nullable|string|alpha_dash',
            'sort' => 'nullable|in:asc,desc,ASC,DESC',
        ];
    }

    protected function storeRules(): array
    {
        return [];
    }

    protected function showRules(): array
    {
        return [];
    }

    protected function updateRules(): array
    {
        return $this->storeRules();
    }

    protected function destroyRules(): array
    {
        return [];
    }


    public function attributes(): array
    {
        return [
            'page' => '当前页码',
            'per_page' => '每页数量',
            'keyword' => '关键词',
            'keywords' => '关键词',
            'start_date' => '开始时间',
            'end_date' => '结束时间',
            'order_by' => '排序',
            'sort' => '顺序'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'code' => 422,
            'message' => $validator->errors()->first(), // 只返回第一条错误信息
        ], 422));
    }
}