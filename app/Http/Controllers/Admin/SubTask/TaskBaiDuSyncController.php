<?php

namespace App\Http\Controllers\Admin\SubTask;

use App\Models\Task\TaskBaiDuSyncModel;

class TaskBaiDuSyncController extends SubTaskBaiDuController
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->model = new TaskBaiDuSyncModel();

        parent::__construct();
    }
}
