<?php

namespace Sinarajabpour1998\LaraCore;

use Sinarajabpour1998\LaraCore\View\Components\AclMenu;
use Illuminate\Support\ServiceProvider;

class LaraCoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }


    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/views','LaraCore');
        $this->mergeConfigFrom(__DIR__ . '/config/lara-core.php', 'lara-core');
        $this->publishes([
            __DIR__.'/config/lara-core.php' =>config_path('lara-core.php'),
            __DIR__.'/views/' => resource_path('views/vendor/LaraCore'),
        ], 'lara-core');
        $this->loadViewComponentsAs('', [
            AclMenu::class
        ]);
    }
}
