<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class RolesProvider extends ServiceProvider
{
    public function boot(): void
    {
        Gate::before(function (User $user) {
            return $user->hasRole('Super Admin') ? true : null;
        });
        Gate::define('is-admin', function (User $user) {
            return $user->hasRole('Super Admin');
        });
    }
}
