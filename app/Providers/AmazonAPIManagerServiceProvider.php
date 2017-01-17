<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Components\AmazonAPI\AmazonAPIManager;


class AmazonAPIManagerServiceProvider extends ServiceProvider
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
        $this->app->bind('App\Components\AmazonAPI\Contracts\AmazonAPIManagerContract', function(){
            return new AmazonAPIManager(config('amazonAPI.credentials'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['App\Components\AmazonAPI\Contracts\AmazonAPIManagerContract'];
    }

}
