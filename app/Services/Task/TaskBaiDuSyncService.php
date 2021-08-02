<?php

namespace App\Services\Task;

use App\Common\Enums\ExecStatusEnum;
use App\Enums\BaiDu\BaiDuSyncTypeEnum;
use App\Enums\TaskTypeEnum;
use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Services\BaiDu\BaiDuAdgroupService;
use App\Services\BaiDu\BaiDuCampaignService;
use App\Services\BaiDu\BaiDuCreativeService;

class TaskBaiDuSyncService extends TaskBaiDuService
{
    /**
     * @var
     * 同步类型
     */
    public $syncType;

    /**
     * TaskOceanSyncService constructor.
     * @param $syncType
     * @throws CustomException
     */
    public function __construct($syncType)
    {
        parent::__construct(TaskTypeEnum::BAIDU_SYNC);

        // 同步类型
        Functions::hasEnum(BaiDuSyncTypeEnum::class, $syncType);
        $this->syncType = $syncType;
    }

    /**
     * @param $taskId
     * @param $data
     * @return bool
     * @throws CustomException
     * 创建
     */
    public function createSub($taskId, $data){
        // 验证
        $this->validRule($data, [
            'account_id' => 'required',
        ]);

        // 校验
        Functions::hasEnum(BaiDuSyncTypeEnum::class, $this->syncType);

        $subModel = new $this->subModelClass();
        $subModel->task_id = $taskId;
        $subModel->account_id = $data['account_id'];
        $subModel->sync_type = $this->syncType;
        $subModel->exec_status = ExecStatusEnum::WAITING;
        $subModel->admin_id = $data['admin_id'] ?? 0;
        $subModel->extends = $data['extends'] ?? [];

        return $subModel->save();
    }

    /**
     * @param $taskId
     * @return mixed
     * 获取待执行子任务
     */
    public function getWaitingSubTasks($taskId){
        $subModel = new $this->subModelClass();

        $builder = $subModel->where('task_id', $taskId)
            ->where('sync_type', $this->syncType)
            ->where('exec_status', ExecStatusEnum::WAITING);


        return $builder->orderBy('id', 'asc')->get();
    }

    /**
     * @param $subTask
     * @return bool|void
     * @throws CustomException
     * 执行单个子任务
     */
    public function runSub($subTask){
        if($this->syncType == BaiDuSyncTypeEnum::CAMPAIGN){
            $this->syncCampaign($subTask);
        }elseif($this->syncType == BaiDuSyncTypeEnum::ADGROUP){
            $this->syncAdgroup($subTask);
        }elseif($this->syncType == BaiDuSyncTypeEnum::CREATIVE){
            $this->syncCreative($subTask);
        }else{
            throw new CustomException([
                'code' => 'NOT_HANDLE_FOR_SYNC_TYPE',
                'message' => '该同步类型无对应处理',
            ]);
        }

        return true;
    }


    /**
     * @param $subTask
     * @return bool
     * 同步推广计划
     */
    private function syncCampaign($subTask){
        $baiduCampaignService = new BaiDuCampaignService();
        $option = [
            'account_ids' => [$subTask->account_id],
        ];
        $baiduCampaignService->sync($option);
        return true;
    }


    /**
     * @param $subTask
     * @return bool
     * 同步推广单元
     */
    private function syncAdgroup($subTask){
        $oceanAdService = new BaiDuAdgroupService();
        $option = [
            'account_ids' => [$subTask->account_id],
        ];

        $oceanAdService->sync($option);
        return true;
    }


    /**
     * @param $subTask
     * @return bool
     * 同步广告创意
     */
    private function syncCreative($subTask){
        $oceanCreativeService = new BaiDuCreativeService();
        $option = [
            'account_ids' => [$subTask->account_id],
        ];

        $oceanCreativeService->sync($option);
        return true;
    }

}
