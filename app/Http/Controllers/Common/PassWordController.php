<?php

namespace App\Http\Controllers\Common;

use App\Http\Requests\Admin\PassWordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Spatie\RouteAttributes\Attributes\Domain;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

#[Middleware('api')]
#[Domain('localhost')]
#[Prefix('admin/password')]
class PassWordController extends R
{
    #[Get('csrf-cookie')]
    public function cookie(Request $request, CsrfCookieController $csrfCookieController): JsonResponse|Response
    {
        return $csrfCookieController->show($request);
    }

    #[Get('captcha')]
    public function captcha(): JsonResponse
    {
        return $this->success([
            'url' => captcha_src()
        ]);
    }

    #[Post('login')]
    public function login(PassWordRequest $request): JsonResponse
    {
        $form = $request->validated();
        $loginData = $request->only('email', 'password');
        if (Auth::attempt($loginData)) {
            $request->session()->regenerate();
            $user = Auth::user();
            return $this->success([
                'avatar' => $user->avatar?->url,
                'name' => $user->name,
                'email' => $user->email,
                'id' => $user->id,
            ]);
        } else {
            return $this->error(['message' => '账号或密码错误']);
        }
    }

    #[Get('logout', middleware: 'auth:sanctum')]
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        abort(401, '已退出登录');
    }
}
