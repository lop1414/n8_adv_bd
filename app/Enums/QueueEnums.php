<?php

namespace App\Enums;

class QueueEnums
{
    const CLICK = 'click';
    const PAGE_CLICK = 'PAGE_CLICK';

    /**
     * @var string
     * 名称
     */
    static public $name = '队列枚举';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        ['id' => self::CLICK, 'name' => '点击'],
        ['id' => self::PAGE_CLICK, 'name' => '页面点击'],
    ];
}
