<?php

namespace App\Enums\BaiDu;

class BaiDuAdgroupStatusEnum
{
    const ADGROUP_STATUS_OK = 0;
    const ADGROUP_STATUS_PAUSE_TIME = 1;
    const ADGROUP_STATUS_PAUSE = 2;
    const ADGROUP_STATUS_INSUFFICIENT_ADGROUP_BUDGET = 3;
    const ADGROUP_STATUS_ACCOUNT_NOT_ACTIVATED = 4;
    const ADGROUP_STATUS_INSUFFICIENT_ACCOUNT_BUDGET = 11;
    const ADGROUP_STATUS_INSUFFICIENT_ACCOUNT_BALANCE = 20;
    const ADGROUP_STATUS_FORBIDDEN = 23;
    const ADGROUP_STATUS_APP_OFFLINE = 24;
    const ADGROUP_STATUS_AUDIT = 25;


    /**
     * @var string
     * 名称
     */
    static public $name = '百度推广单元投放状态';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        ['id' => self::ADGROUP_STATUS_OK, 'name' => '有效'],
        ['id' => self::ADGROUP_STATUS_PAUSE_TIME, 'name' => '处于暂停时段'],
        ['id' => self::ADGROUP_STATUS_PAUSE, 'name' => '暂停推广'],
        ['id' => self::ADGROUP_STATUS_INSUFFICIENT_ADGROUP_BUDGET, 'name' => '推广计划预算不足'],
        ['id' => self::ADGROUP_STATUS_ACCOUNT_NOT_ACTIVATED, 'name' => '账户待激活'],
        ['id' => self::ADGROUP_STATUS_INSUFFICIENT_ACCOUNT_BUDGET, 'name' => '账户预算不足'],
        ['id' => self::ADGROUP_STATUS_INSUFFICIENT_ACCOUNT_BALANCE, 'name' => '账户余额为零'],
        ['id' => self::ADGROUP_STATUS_FORBIDDEN, 'name' => '被禁推'],
        ['id' => self::ADGROUP_STATUS_APP_OFFLINE, 'name' => 'app已下线'],
        ['id' => self::ADGROUP_STATUS_AUDIT, 'name' => '应用审核中'],

    ];
}
