<?php

namespace App\Http\Controllers\Common;

use App\Enums\PageEnum;
use App\Models\GoodsCat;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Post;

class CoreController extends R
{
    #[Post('clear')]
    public function clear(): JsonResponse
    {
        Artisan::call('optimize:clear');
        return $this->success([
            'message' => '所有缓存已清除'
        ]);
    }

    #[Get('permissions')]
    public function permissions(): JsonResponse
    {
        $permissions = Permission::all();
        $temp = [];
        foreach ($permissions as $permission) {
            list($page, $b) = explode('.', $permission->name);
            $temp[$page]['name'] = PageEnum::from($page)->label();
            $temp[$page]['children'][] = [
                'id' => $permission->id,
                'name' => $permission->name,
                'value' => $b
            ];
        }
        return $this->success(array_values($temp));
    }

    #[Get('roles')]
    public function roles(): JsonResponse
    {
        return $this->success(Role::all());
    }

    #[Get('cats')]
    public function cats(): JsonResponse
    {
        return $this->success(GoodsCat::all());
    }
}