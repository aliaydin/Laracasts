<?php

namespace App\Providers;
use App\Services\Twitter;

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
        /*
        // Core Concepts: Service Providers
        // SocialServiceProvider altına taşındığı için buradaki kodu kapattım.
        $this->app->singleton("foo", function() {
            return "bar";
        });
        */

/*
        $this->app->bind(
            \App\Repositories\UserRepository::class,
            \App\Repositories\DbUserRepository::class
        );
*/
    }
}
