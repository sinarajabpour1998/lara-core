<?php
use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'Sinarajabpour1998\LaraCore\Http\Controllers',
    'prefix' => 'panel',
    'middleware' => ['web', 'auth', 'verified', Config::get('lara-core.permissions.main')]
], function () {
    Route::namespace('Authorization')->group(function () {

        Route::resource('permissions', 'PermissionsController')
            ->except(['show'])->middleware([Config::get('lara-core.permissions.permissions')]);

        Route::resource('roles', 'RolesController')
            ->except(['show'])->middleware([Config::get('lara-core.permissions.roles')]);

        Route::resource('roles-assignment', 'RolesAssignmentController')
            ->only(['index', 'edit', 'update'])->middleware([Config::get('lara-core.permissions.users')]);

    });
    Route::namespace('User')->group(function (){
        Route::resource('users', 'UserController')
            ->except(['index', 'show']);
        Route::get('/users/reset/password/{user}', 'UserController@userResetPasswordForm')->name('user.reset_password');
        Route::post('/users/reset/password/{user}', 'UserController@userResetPassword'); //
    });
});
