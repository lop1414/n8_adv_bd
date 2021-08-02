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
        $router->post('create', 'Admin\BaiDu\BaiDuAccountController@create');
        $router->post('update', 'Admin\BaiDu\BaiDuAccountController@update');
        $router->post('select', 'Admin\BaiDu\BaiDuAccountController@select');
        $router->post('get', 'Admin\BaiDu\BaiDuAccountController@get');
        $router->post('read', 'Admin\BaiDu\BaiDuAccountController@read');
        $router->post('enable', 'Admin\BaiDu\BaiDuAccountController@enable');
        $router->post('disable', 'Admin\BaiDu\BaiDuAccountController@disable');
        $router->post('delete', 'Admin\BaiDu\BaiDuAccountController@delete');
        $router->post('batch_enable', 'Admin\BaiDu\BaiDuAccountController@batchEnable');
        $router->post('batch_disable', 'Admin\BaiDu\BaiDuAccountController@batchDisable');
        $router->post('sync', 'Admin\BaiDu\BaiDuAccountController@syncAccount');
    });

    //百度
    $router->group(['prefix' => 'baidu'], function () use ($router) {
        // 推广计划
        $router->group(['prefix' => 'campaign'], function () use ($router) {
            $router->post('select', 'Admin\BaiDu\BaiDuCampaignController@select');
            $router->post('get', 'Admin\BaiDu\BaiDuCampaignController@get');
            $router->post('read', 'Admin\BaiDu\BaiDuCampaignController@read');
        });
        // 推广单元
        $router->group(['prefix' => 'adgroup'], function () use ($router) {
            $router->post('select', 'Admin\BaiDu\BaiDuAdgroupController@select');
            $router->post('get', 'Admin\BaiDu\BaiDuAdgroupController@get');
            $router->post('read', 'Admin\BaiDu\BaiDuAdgroupController@read');
        });
    });


    // 任务
    $router->group(['prefix' => 'task'], function () use ($router) {
        $router->post('select', '\\App\Common\Controllers\Admin\TaskController@select');
        $router->post('open', '\\App\Common\Controllers\Admin\TaskController@open');
        $router->post('close', '\\App\Common\Controllers\Admin\TaskController@close');
    });

    // 子任务
    $router->group(['prefix' => 'sub_task'], function () use ($router) {

        // 百度同步
        $router->group(['prefix' => 'baidu_sync'], function () use ($router) {
            $router->post('select', 'Admin\SubTask\TaskBaiDuSyncController@select');
            $router->post('read', 'Admin\SubTask\TaskBaiDuSyncController@read');
        });
    });
});

// 前台接口

$router->group([
    'prefix' => 'front',
    'middleware' => ['api_sign_valid', 'access_control_allow_origin']
], function () use ($router) {
    // 转化
    $router->group(['prefix' => 'convert'], function () use ($router) {
        $router->post('match', '\\App\Common\Controllers\Front\ConvertController@match');
    });

    // 转化回传
    $router->group(['prefix' => 'convert_callback'], function () use ($router) {
        $router->post('get', '\\App\Common\Controllers\Front\ConvertCallbackController@get');
    });

    // 渠道-推广单元
    $router->group(['prefix' => 'channel_adgroup'], function () use ($router) {
        $router->post('select', 'Front\ChannelAdgroupController@select');
        $router->post('batch_update', 'Front\ChannelAdgroupController@batchUpdate');
    });
});


$router->group(['middleware' => ['access_control_allow_origin']], function () use ($router) {
    // 点击
    $router->get('front/click', 'Front\AdvClickController@index');
});



// 测试
$router->post('front/baidu/test', 'Front\BaiDu\IndexController@test');

