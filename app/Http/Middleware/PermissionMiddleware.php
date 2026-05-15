<?php

namespace App\Http\Middleware;
use App\Enums\RolesEnum;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $page)
    {
        app('config')->set('auth.defaults.guard', 'web');
        $route = Route::current();
        $controller = $route->getActionName();
        list($controllerName, $method) = explode('@', $controller);
        $map = [
            'index' => RolesEnum::VIEW,
            'show' => RolesEnum::VIEW,
            'store' => RolesEnum::CREATE,
            'update' => RolesEnum::UPDATE,
            'destroy' => RolesEnum::DELETE,
        ];

        if(!isset($map[$method])) {
            return $next($request);
        }
        $permission = $page .'.'. $map[$method]->value;
        $permissionMiddleware = App::make('Spatie\Permission\Middleware\PermissionMiddleware');
        Log::info($permission);

        return $permissionMiddleware->handle($request, $next,$permission);
    }
}