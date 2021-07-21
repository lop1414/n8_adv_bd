<?php

namespace App\Services\BaiDu;

use App\Common\Enums\StatusEnum;
use App\Common\Helpers\Functions;
use App\Models\BaiDuFeedCampaignModel;
use App\Sdks\BaiDu\Feed\BaiDuFeed;


class BaiDuCampaignService extends BaiDuService
{



    public function syncFeed($option){
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
            $baiduSdk = new BaiDuFeed($parentAccount['name'],$parentAccount['password'],$parentAccount['token']);
            foreach ($parentAccount['sub_account'] as  $item) {
                $baiduSdk->setTargetAccountName($item['name']);
                $data = $baiduSdk->getCampaignFeed();
                $saveData = [];
                foreach ($data as $campaign){

                    $saveData[] = [
                        'id'                => $campaign['campaignFeedId'],
                        'account_id'        => $item['account_id'],
                        'campaign_feed_name'=> $campaign['campaignFeedName'],
                        'subject'  => $campaign['subject'],
                        'budget'  => $campaign['budget'] * 100,
                        'pause'  => $campaign['pause'],
                        'status'  => $campaign['status'],
                        'starttime'  => date('Y-m-d H:i:s',strtotime($campaign['starttime'])),
                        'endtime'  => isset($campaign['endtime']) ?  date('Y-m-d H:i:s',strtotime($campaign['endtime'])): null,
                        'addtime'  => date('Y-m-d H:i:s',strtotime($campaign['addtime'])),
                        'extends'  => json_encode($campaign),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
                $this->batchSave(BaiDuFeedCampaignModel::class,$saveData);
            }
        }
    }




}
