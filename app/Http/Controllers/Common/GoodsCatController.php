<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests\Admin\GoodsCatRequest;
use App\Models\GoodsCat;
use Illuminate\Http\JsonResponse;
use Spatie\RouteAttributes\Attributes\ApiResource;
use Spatie\RouteAttributes\Attributes\Middleware;

#[ApiResource(resource: 'goods-cat', except: 'show')]
#[Middleware(['permission:goods-cat'])]
class GoodsCatController extends R
{
    public function index(GoodsCatRequest $request): JsonResponse
    {
        $goodsCatModel = GoodsCat::withCount('goods')->keyword('name')->page();
        $response = $this->toArray($goodsCatModel);

        return $this->success($response['data'], $response['paginate']);
    }

    public function store(GoodsCatRequest $request): JsonResponse
    {
        $form = $request->validated();
        GoodsCat::create([
            'name' => $form['name'],
            'sort' => $form['sort'],
        ]);

        return $this->success([
            'message' => self::MESSAGE['STORE']
        ]);
    }

    public function update(GoodsCatRequest $request, GoodsCat $goodsCat): JsonResponse
    {
        $form = $request->validated();
        $goodsCat->name = $form['name'];
        $goodsCat->sort = $form['sort'];
        $goodsCat->save();

        return $this->success([
            'message' => self::MESSAGE['UPDATE']
        ]);
    }

    public function destroy(GoodsCatRequest $request, GoodsCat $goodsCat): JsonResponse
    {
        $goodsCat->delete();

        return $this->success([
            'message' => self::MESSAGE['DESTROY']
        ]);
    }
}
