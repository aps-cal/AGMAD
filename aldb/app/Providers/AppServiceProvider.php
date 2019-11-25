<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Code Added by APS - 25 November 2019
        $this->app->bind(
            \Auth0\Login\Contract\Auth0UserRepository::class,
            \Auth0\Login\Repository\Auth0UserRepository::class
        );
    }
}
