<?php

return [

    // set custom permission names for menus and routes
    // all permissions (except for menu) must be start with [permission:] keyword
    'permissions' => [
        'main' => 'permission:lara-core',
        'users' => 'permission:lara-core-users',
        'roles' => 'permission:lara-core-roles',
        'permissions' => 'permission:lara-core-permissions',
        'menu' => [
            'main' => 'lara-core',
            'users' => 'lara-core-users',
            'roles' => 'lara-core-roles',
            'permissions' => 'lara-core-permissions'
        ]
    ],

    'language' => 'fa',
    'site_key' => env('GOOGLE_RECAPTCHA_SITE_KEY'),
    'secret_key' => env('GOOGLE_RECAPTCHA_SECRET_KEY'),
    'captcha_message' => 'شما به عنوان ربات تشخیص داده شده‌اید.'

];
