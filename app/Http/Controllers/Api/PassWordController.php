<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Admin\PassWordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;
use Spatie\RouteAttributes\Attributes\Any;
use Spatie\RouteAttributes\Attributes\Domain;
use Spatie\RouteAttributes\Attributes\Get;
use Spatie\RouteAttributes\Attributes\Middleware;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;

class PassWordController extends R
{
    #[Any('password/login')]
    public function login(PassWordRequest $request): JsonResponse
    {

        $form = $request->validated();
        $loginData = $request->only('email', 'password');
        if (Auth::attempt($loginData)) {
//            $request->session()->regenerate();
            $user = Auth::user();
            $token = $user->createToken('api');
            return $this->success([
                'token' => $token->plainTextToken,
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
        $request->user()->currentAccessToken()->delete();
        return $this->success([
            'msg' => '退出成功',
        ]);
    }
}
