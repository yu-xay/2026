<?php

namespace App\Providers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Laravel\Octane\Events\WorkerStarting;
use Illuminate\Support\Facades\Event;
class NacosServiceProvider  extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen(WorkerStarting::class, function () {
            // 获取本机内网 IP (Linux 环境常用方式)
            $localIp = gethostbyname(gethostname());

            Http::asForm()->post('http://nacos:8848/nacos/v1/ns/instance', [
                'serviceName' => 'laravel-octane-service',
                'ip'          => $localIp,
                'port'        => 8000, // Octane 的运行端口
                'healthy'     => 'true',
                'weight'      => 1.0,
                'ephemeral'   => 'true', // 关键：设置为临时实例，进程挂了 Nacos 自动剔除
            ]);
        });
    }
}