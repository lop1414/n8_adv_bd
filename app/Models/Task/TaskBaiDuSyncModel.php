<?php

namespace App\Models\Task;


class TaskBaiDuSyncModel extends TaskBaiDuModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'task_baidu_syncs';

    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
