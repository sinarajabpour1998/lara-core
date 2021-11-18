<?php

namespace Sinarajabpour1998\LaraCore;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use Sinarajabpour1998\LaraCore\View\Components\AclMenu;
use Sinarajabpour1998\LaraCore\View\Components\CoreRecaptcha;
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
            AclMenu::class,
            CoreRecaptcha::class
        ]);

        Validator::extend('core_recaptcha', function ($attribute, $value, $parameters, $validator) {
            try{
                $client = new Client(['verify' => false]);
                $response = $client->request('POST', 'https://www.google.com/recaptcha/api/siteverify',
                    [
                        'form_params' => [
                            'secret' => config('lara-core.secret_key'),
                            'response' => $value,
                            'remoteip' => request()->ip()
                        ]
                    ]);
                $response = json_decode($response->getBody());

                return $response->success;
            } catch (Exception $e) {
                return false;
            }
        }, config('lara-core.captcha_message'));
    }
}
