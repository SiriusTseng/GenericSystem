<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
//    'namespace'     => config('admin.route.namespace'),
    'namespace'  => 'App\Admin\Controllers',
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');


    Route::prefix('user')->group(function () {
        Route::redirect('/', 'user/lists');
        Route::resource('lists', 'User\UserController');
        Route::resource('roles', 'User\RoleController');
        Route::resource('permissions', 'User\PermissionController');
    });
});
