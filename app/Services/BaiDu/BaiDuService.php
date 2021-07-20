<?php

namespace App\Services\BaiDu;

use App\Common\Enums\StatusEnum;
use App\Common\Services\BaseService;
use App\Models\BaiDuAccountModel;


class BaiDuService extends BaseService
{




    public function getAccount($accountIds = [], $status = StatusEnum::ENABLE){

        $parentAccountList = (new BaiDuAccountModel())
            ->where('parent_id',0)
            ->get();

        $list = [];

        if(!$parentAccountList->isEmpty()){
            $list = $parentAccountList->toArray();
        }

        foreach ($list as &$parentAccount){
            $subAccountList = (new BaiDuAccountModel())
                ->where('parent_id',$parentAccount['id'])
                ->where('status',$status)
                ->when($accountIds && !in_array($parentAccount['account_id'],$accountIds),function ($query) use ($accountIds){
                    return $query->whereIn('account_id',$accountIds);
                })
                ->get();
            if(!$subAccountList->isEmpty()){
                $parentAccount['sub_account'] = $subAccountList->toArray();
            }
        }
        return $list;
    }



    /**
     * @param $modelClass
     * @param $data
     * @param int $number
     * @return bool
     * 批量保存
     */
    public function batchSave($modelClass,$data,$number = 20){
        $model = new $modelClass();
        $model->chunkInsertOrUpdate($data, $number, $model->getTable(), $model->getTableColumnsWithPrimaryKey());
        return true;
    }
}
