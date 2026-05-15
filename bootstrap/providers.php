<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    App\Providers\HorizonServiceProvider::class,
    App\Providers\NacosServiceProvider::class,
    App\Providers\RolesProvider::class,
    App\Providers\TelescopeServiceProvider::class,
    App\Providers\TenancyServiceProvider::class,
    App\Providers\Yu::class,
    Mews\Captcha\CaptchaServiceProvider::class,
];
