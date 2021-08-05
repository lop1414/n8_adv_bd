<?php

namespace App\Enums\BaiDu;

class BaiDuSubjectTypeEnum
{
    const WEB = 1;
    const APP_IOS = 2;
    const APP_ANDROID = 3;


    /**
     * @var string
     * 名称
     */
    static public $name = '巨量推广类型';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        ['id' => self::WEB, 'name' => '网站链接'],
        ['id' => self::APP_ANDROID, 'name' => '应用下载-安卓'],
        ['id' => self::APP_IOS, 'name' => '应用下载-IOS'],
    ];
}
