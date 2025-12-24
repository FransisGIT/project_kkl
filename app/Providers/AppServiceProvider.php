<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Share roles list globally for topbar role switcher
        try {
            View::share('roles', Role::all());
        } catch (\Throwable $e) {
            // ignore if roles table not available yet (migrations)
        }
    }
}
