<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

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
        //
        Schema::defaultStringLength(191);

        if (!file_exists(storage_path('framework/cache'))) {
            mkdir(storage_path('framework/cache'), 0755, true);
        }

        if (!file_exists(storage_path('framework/views'))) {
            mkdir(storage_path('framework/views'), 0755, true);
        }

        /*if (!file_exists(bootstrap_path('cache'))) {
            mkdir(bootstrap_path('cache'), 0755, true);
        }*/

    }
}
