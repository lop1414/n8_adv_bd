<?php

namespace App\Services\BaiDu\Feed;

use App\Common\Enums\StatusEnum;
use App\Common\Helpers\Functions;
use App\Models\BaiDuAccountModel;
use App\Sdks\BaiDu\Feed\BaiDuFeed;
use App\Services\BaiDu\BaiDuService;


class BaiDuFeedService extends BaiDuService
{

    /**
     * @var BaiDuFeed
     * 句柄
     */
    public $sdk;




    public function setSdk($accountName,$password,$token){
        $this->sdk = new BaiDuFeed($accountName,$password,$token);
        return $this;
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


}
