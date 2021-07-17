<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


// 后台
$router->group([
    'prefix' => 'admin',
    'middleware' => ['center_menu_auth', 'admin_request_log', 'access_control_allow_origin']
], function () use ($router) {
    // 账户
    $router->group(['prefix' => 'bd_account'], function () use ($router) {
        $router->post('create', 'Admin\BdAccountController@create');
        $router->post('update', 'Admin\BdAccountController@update');
        $router->post('select', 'Admin\BdAccountController@select');
        $router->post('get', 'Admin\BdAccountController@get');
        $router->post('read', 'Admin\BdAccountController@read');
        $router->post('enable', 'Admin\BdAccountController@enable');
        $router->post('disable', 'Admin\BdAccountController@disable');
        $router->post('delete', 'Admin\BdAccountController@delete');
        $router->post('batch_enable', 'Admin\BdAccountController@batchEnable');
        $router->post('batch_disable', 'Admin\BdAccountController@batchDisable');
        $router->post('sync', 'Admin\BdAccountController@sync');
    });
});
