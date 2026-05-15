<?php

namespace App\Providers;

use App\Events\PodcastProcessed;
use App\Http\Controllers\TestController;
use App\Http\Middleware\PluginMiddleware;
use App\Listeners\SendPodcastNotification;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Access\Response;
use Laravel\Octane\Facades\Octane;

class Yu extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        app()->singleton(PluginMiddleware::class);
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

        Event::listen(
            PodcastProcessed::class,
            \App\Listeners\L1::class
        );
        //避免懒加载
        Model::shouldBeStrict(!$this->app->isProduction());

        //分页
        Builder::macro('page', function (int $perPage = 15, $page = null) {
            return $this->paginate($perPage, ['*'], 'page', $page);
        });

        Builder::macro('keyword', function (string|array $columnName, ?string $keyword = null) {
            $keyword = $keyword ?? request()->input('keyword');
            $keyword = trim($keyword ?? '');

            if ($keyword === '') {
                return $this;
            }

            return $this->where(function ($query) use ($columnName, $keyword) {
                if (is_array($columnName)) {
                    foreach ($columnName as $index => $column) {
                        if ($index === 0) {
                            $query->where($column, 'like', "%{$keyword}%");
                        } else {
                            $query->orWhere($column, 'like', "%{$keyword}%");
                        }
                    }
                } else {
                    $query->where($columnName, 'like', "%{$keyword}%");
                }
            });
        });
    }
}
