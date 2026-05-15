<?php

namespace App\Http\Controllers\Api;

use App\Enums\PageEnum;
use App\Enums\RolesEnum;
use App\Http\Controllers\Admin\R;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;


class ConfigController extends R
{
    #[Get('config',middleware: 'auth:sanctum')]
    public function __invoke(): JsonResponse
    {
//        if(isset($_SERVER['HTTP_ORIGIN'])){
//            header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
//            header('access-control-allow-credentials: true');
//        }
        return $this->success([
            'c' => 13,
            'Auth' => Auth::check(),
        ]);
    }
}