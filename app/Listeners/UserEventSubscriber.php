<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Events\Dispatcher;

class UserEventSubscriber
{
    /**
     * 处理用户登录事件。
     */
    public function handleUserLogin(Login $event): void {}

    /**
     * 处理用户注销事件。
     */
    public function handleUserLogout(Logout $event): void {}

    /**
     * 为订阅者注册监听器。
     */
    public function subscribe(Dispatcher $events): void
    {
        $events->listen(
            Login::class,
            [UserEventSubscriber::class, 'handleUserLogin']
        );

        $events->listen(
            Logout::class,
            [UserEventSubscriber::class, 'handleUserLogout']
        );
    }
}