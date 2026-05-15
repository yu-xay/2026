<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Sentry\Laravel\Integration;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

// 拉取 Nacos 配置并加载到环境变量
$dataId = '9';
$group = 'DEFAULT_GROUP';
$namespaceId = 'public'; // 【必须加】

$nacosServer = 'http://nacos:8848';   // 根据你的实际地址修改

$url = $nacosServer . '/nacos/v3/client/cs/config?' . http_build_query([
        'dataId'      => $dataId,
        'groupName'       => $group,
        'namespaceId' => $namespaceId,
    ]);
$configContent = file_get_contents($url);


if ($configContent) {
    // 解析 Properties 格式（每行 key=value）
    $configContent = json_decode($configContent, true);
    $configContent = $configContent['data']['content'];

    $lines = explode("\n", trim($configContent));
    foreach ($lines as $line) {
        if (trim($line) && strpos($line, '=') !== false && substr($line, 0, 1) !== '#') {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            putenv("$key=$value");  // 设置环境变量
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }
} else {
    // 获取失败时 fallback 到本地 .env 或报错
    echo "Nacos 配置获取失败，使用本地 .env";
}

//header('Access-Control-Allow-Origin: http://localhost:777');
//header('Access-Control-Allow-Credentials: true');
//header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With, X-XSRF-TOKEN');
//header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            \Illuminate\Support\Facades\Route::prefix('plugin')
                ->name('plugin.');
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('universal', []);
        $middleware->statefulApi();
        $middleware->alias([
            'permission' => \App\Http\Middleware\PermissionMiddleware::class,
//            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
//            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(function ($request, $e) {
            if ($request->is('api/*')) {
                return true;
            }
            return $request->expectsJson();
        });

        $exceptions->report(function (\RuntimeException $throwable): void {
        });
        $exceptions->context(fn () => [
            'foo' => 'bar',
        ]);
    })->create();
