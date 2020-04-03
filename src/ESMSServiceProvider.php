<?php

namespace Jacksonit\ESMS;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

/**
 * ServiceProvider
 *
 * The service provider for the modules. After being registered
 * it will make sure that each of the modules are properly loaded
 * i.e. with their routes, views etc.
 *
 * @author Cao Son <son.caoxuan92@gmail.com>
 * @package Jacksonit\ESMS
 */
class ESMSServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__ . '/config/esms.php' => config_path('esms.php'),
        ]);
    }

    public function register()
    {
        $this->app->booting(function () {
            $loader = AliasLoader::getInstance();
            $loader->alias('ESMSCharge', 'Jacksonit\ESMS\Facades\ESMSCharge');
        });

        $this->app->bind('ESMSCharge', SMSCharge::class);
    }
}