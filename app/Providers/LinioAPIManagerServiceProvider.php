<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Components\LinioAPI\LinioAPIManager;


class LinioAPIManagerServiceProvider extends ServiceProvider
{
    protected $defer = true;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Components\LinioAPI\Contracts\LinioAPIManagerContract', function(){
            return new LinioAPIManager(config('linioAPI.credentials'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Components\LinioAPI\Contracts\LinioAPIManagerContract'];
    }

}
