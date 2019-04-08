<?php

namespace App\Providers;

use Encore\Admin\Config\Config;
use Illuminate\Database\QueryException;
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
        Schema::defaultStringLength(191);
        try {
            if (class_exists(Config::class) && Schema::hasTable('admin_config')) {
                Config::load();
            }
        } catch (QueryException $e) {
            //do nothing
        }
    }
}
