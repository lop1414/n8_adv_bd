<?php

namespace App\Enums;

use App\Common\Enums\SystemAliasEnum;
use App\Models\Task\TaskBaiDuSyncModel;

class TaskTypeEnum
{
    const BAIDU_SYNC = 'BAIDU_SYNC';


    /**
     * @var string
     * 名称
     */
    static public $name = '任务类型';

    /**
     * @var array
     * 列表
     */
    static public $list = [
        [
            'id' => self::BAIDU_SYNC,
            'name' => '百度同步',
            'sub_model_class' => TaskBaiDuSyncModel::class,
            'system_alias' => SystemAliasEnum::ADV_BD,
        ]
    ];
}
