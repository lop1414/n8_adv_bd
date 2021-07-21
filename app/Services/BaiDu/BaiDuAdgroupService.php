<?php

namespace App\Services\BaiDu;

use App\Common\Enums\StatusEnum;
use App\Common\Helpers\Functions;
use App\Models\BaiDuFeedAdgroupModel;
use App\Models\BaiDuFeedCampaignModel;
use App\Sdks\BaiDu\Feed\BaiDuFeed;


class BaiDuAdgroupService extends BaiDuService
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

            foreach ($parentAccount['sub_account'] as  $account) {
                // 获取计划ID
                $campaignIds = [];

                $campaigns = (new BaiDuFeedCampaignModel())
                    ->where('account_id',$account['account_id'])
                    ->get();

                foreach ($campaigns as $campaign){
                    $campaignIds[] = $campaign['id'];
                }

                if(empty($campaignIds)) continue;

                // 同步
                $baiduSdk->setTargetAccountName($account['name']);
                $data = $baiduSdk->getAdGroupFeed($campaignIds);
                $saveData = [];
                foreach ($data as $adgroup){

                    $saveData[] = [
                        'id'                => $adgroup['adgroupFeedId'],
                        'account_id'        => $account['account_id'],
                        'campaign_feed_id'  => $adgroup['campaignFeedId'],
                        'adgroup_feed_name' => $adgroup['adgroupFeedName'],
                        'pause'             => $adgroup['pause'],
                        'status'            => $adgroup['status'],
                        'bid'               => $adgroup['bid'],
                        'bidtype'           => $adgroup['bidtype'],
                        'atp_feed_id'       => $adgroup['atpFeedId'],
                        'extends'           => json_encode($adgroup),
                        'created_at'        => date('Y-m-d H:i:s'),
                        'updated_at'        => date('Y-m-d H:i:s'),
                    ];
                }
                $this->batchSave(BaiDuFeedAdgroupModel::class,$saveData);
            }
        }
    }




}
