<?php

namespace App\Services\BaiDu\Feed;

use App\Common\Enums\StatusEnum;
use App\Common\Helpers\Functions;
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


        $list = $this->getAccount($accountIds,$status);

        foreach ($list as $parentAccount){
            $this->setSdk($parentAccount['name'],$parentAccount['password'],$parentAccount['token']);

            // 并发分片大小
            if(!empty($option['multi_chunk_size'])){
                $multiChunkSize = min(intval($option['multi_chunk_size']), 8);
                $this->sdk->setMultiChunkSize($multiChunkSize);
            }

            $this->syncItem($parentAccount['sub_account']);
        }
    }



    public function syncItem($accountNames){}


}
