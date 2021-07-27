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
    $router->group(['prefix' => 'baidu_account'], function () use ($router) {
        $router->post('create', 'Admin\BaiDuAccountController@create');
        $router->post('update', 'Admin\BaiDuAccountController@update');
        $router->post('select', 'Admin\BaiDuAccountController@select');
        $router->post('get', 'Admin\BaiDuAccountController@get');
        $router->post('read', 'Admin\BaiDuAccountController@read');
        $router->post('enable', 'Admin\BaiDuAccountController@enable');
        $router->post('disable', 'Admin\BaiDuAccountController@disable');
        $router->post('delete', 'Admin\BaiDuAccountController@delete');
        $router->post('batch_enable', 'Admin\BaiDuAccountController@batchEnable');
        $router->post('batch_disable', 'Admin\BaiDuAccountController@batchDisable');
        $router->post('sync', 'Admin\BaiDuAccountController@sync');
    });
});

// 前台接口

$router->group([
    'prefix' => 'front',
    'middleware' => ['api_sign_valid', 'access_control_allow_origin']
], function () use ($router) {
    $router->group(['middleware' => ['access_control_allow_origin']], function () use ($router) {
        // 点击
        $router->get('front/click', 'Front\AdvClickController@index');
    });
});

