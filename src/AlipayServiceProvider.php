<?php

namespace Crisen\LaravelAlipay;

use Illuminate\Support\ServiceProvider;


class AlipayServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/alipay.php' => config_path('alipay.php'),
        ]);
    }

    public function register()
    {

        $this->app->singleton('Alipay', function ($app) {
            return new AlipayFactory();
        });
    }
}
