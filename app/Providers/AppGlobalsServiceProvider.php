<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App;
use \App\Helpers\appGlobals;

class AppGlobalsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('appglobals', function()
        {
//            return new DemoClass;
            return new appGlobals;
        });
    }
}
