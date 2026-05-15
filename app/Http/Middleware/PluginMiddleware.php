<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PluginMiddleware
{
    private $startTime;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $id = ''): Response
    {
        $this->startTime = microtime(true); // 记录开始时间
        Log::info('1111');
        return $next($request);
    }
    /**
     * 处理响应发送给浏览器后的任务。
     */
    public function terminate(Request $request, Response $response): void
    {
        $endTime = microtime(true);
        $duration = $endTime - $this->startTime; // 这里 $this->startTime 是 null！
        Log::info("请求耗时: {$duration} 秒");
    }
}
