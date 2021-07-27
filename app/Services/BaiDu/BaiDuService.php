<?php

namespace App\Services\BaiDu;

use App\Common\Enums\StatusEnum;
use App\Common\Helpers\Functions;
use App\Common\Services\BaseService;
use App\Enums\RemarkStatusEnum;
use App\Models\BaiDuAccountModel;
use App\Models\BaiDuAdgroupModel;
use App\Models\BaiDuCampaignModel;
use App\Models\BaiDuCreativeModel;
use App\Sdks\BaiDu\BaiDu;


class BaiDuService extends BaseService
{

    /**
     * @var BaiDu
     * 句柄
     */
    public $sdk;

    /**
     * @var
     * 管家账户信息
     */
    protected $manageAccount;


    /**
     * @var
     * 账户ID映射
     */
    protected $accountMap;




    /**
     * BaiDuService constructor.
     * @param array $manageAccount
     */
    public function __construct($manageAccount = []){
        parent::__construct();

        if(!empty($manageAccount)) $this->setManageAccount($manageAccount);

    }



    public function setSdk($accountName,$password,$token){
        $this->sdk = new BaiDu($accountName,$password,$token);
        return $this;
    }



    public function setManageAccount($info){
        $this->manageAccount = $info;
        $this->setSdk($this->manageAccount['name'],$this->manageAccount['password'],$this->manageAccount['token']);
        return $this;
    }


    /**
     * @param $name
     * @return mixed
     * 通过账户名称
     */
    public function getAccountByName($name){
        if(!isset($this->accountIdMap[$name])){
            $info = (new BaiDuAccountModel())->where('name',$name)->first();
            $this->setAccountMap($info);
        }
        return $this->accountMap[$name];
    }


    public function setAccountMap($info){
        $this->accountMap[$info['name']] = $info;
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




    public function sync($option){
        $accountIds = [];
        // 账户id过滤
        if(!empty($option['account_ids'])){
            $accountIds = $option['account_ids'];
        }

        // 状态
        if(!empty($option['status'])){
            $status = strtoupper($option['status']);
            Functions::hasEnum(StatusEnum::class, $status);
        }else{
            $status = StatusEnum::ENABLE;
        }


        $parentAccountList = (new BaiDuAccountModel())->where('parent_id',0)->get();

        $list = [];

        if(!$parentAccountList->isEmpty()){
            $list = $parentAccountList->toArray();
        }

        foreach ($list as $parentAccount){

            $query = (new BaiDuAccountModel())
                ->where('parent_id',$parentAccount['id'])
                ->where('status',$status)
                ->when($accountIds && !in_array($parentAccount['account_id'],$accountIds),function ($query) use ($accountIds){
                    return $query->whereIn('account_id',$accountIds);
                })
                ->orderBy('account_id');

            $page = 1;
            do{
                // 并发最多20个账户
                $subAccountList = (new BaiDuAccountModel())->scopeListPage($query,$page, 20);

                $this->setSdk($parentAccount['name'],$parentAccount['password'],$parentAccount['token']);

                // 并发分片大小
                if(!empty($option['multi_chunk_size'])){
                    $multiChunkSize = min(intval($option['multi_chunk_size']), 8);
                    $this->sdk->setMultiChunkSize($multiChunkSize);
                }

                $this->syncItem($subAccountList['list']);
                $page += 1;
            }while($subAccountList['page_info']['page'] < $subAccountList['page_info']['total_page']);
        }



    }



    public function syncItem($accountNames){}



    /**
     * @param $item
     * 计划不存在 异常处理 - 更新备注状态
     */
    public function handleCampaignFeedIdNotExists($item){
        if($this->sdk->hasCampaignFeedIdNotExists($item['data'])){

            // 计划被删除 更新状态
            foreach($item['data']['header']['failures'] as $failure){
                if(!$this->sdk->isCampaignFeedIdNotExistsByCode($failure['code'])){
                    continue;
                }

                $campaign = (new BaiDuCampaignModel())
                    ->where('id',$failure['id'])
                    ->first();
                if(empty($campaign)) continue;

                $campaign->remark_status =  RemarkStatusEnum::DELETE;
                $campaign->save();

                $adgroups = (new BaiDuAdgroupModel())
                    ->where('campaign_feed_id',$failure['id'])
                    ->get();

                foreach ($adgroups as $adgroup){

                    $adgroup->remark_status =  RemarkStatusEnum::DELETE;
                    $adgroup->save();

                    (new BaiDuCreativeModel())
                        ->where('adgroup_feed_id',$adgroup['id'])
                        ->update(['remark_status' => RemarkStatusEnum::DELETE]);
                }
            }
        }
    }
}
