<?php

namespace app\Providers;

use Illuminate\Support\ServiceProvider;
use App;
use App\Helpers\appGlobals;

class AppGlobalsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        /* @noinspection PhpUndefinedClassInspection */
        App::singleton('appglobals', function () {
            return new appGlobals();
        });
    }
}
