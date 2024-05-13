<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Gate::define('update-post', function ($user, $post) {
        //     return $user->id == $post->user_id;
        // });
        Gate::define('update-view-user-admin', function ($user, $userParams, $permissionName) {
            return ($user->hasRole('Admin') || !$userParams->hasRole('Admin')) && auth()->user()->hasPermissionTo($permissionName);
        });
        Gate::define('is-admin', function ($user) {
            return $user->hasRole('Admin');
        });
    }
}
