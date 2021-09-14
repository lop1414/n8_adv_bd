<?php

namespace App\Enums;

class FixStatusEnum
{
    const WAITING = 'WAITING';
    const SUCCESS = 'SUCCESS';
    const FAIL = 'FAIL';

    /**
     * @var string
     * 名称
     */
    static public $name = '修正状态';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        ['id' => self::WAITING, 'name' => '等待'],
        ['id' => self::SUCCESS, 'name' => '成功'],
        ['id' => self::FAIL, 'name' => '失败'],
    ];
}
