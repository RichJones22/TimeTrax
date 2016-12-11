<?php

namespace Premise\Utilities;

use Illuminate\Support\ServiceProvider;

class PremiseUtilitiesProvider extends ServiceProvider
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
        $this->app->make(PremiseUtilities::class);
    }
}
