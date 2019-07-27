<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EventModelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \App\Event::observe(\App\Observers\EventObserver::class);
    }
}
