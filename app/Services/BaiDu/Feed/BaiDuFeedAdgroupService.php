<?php

namespace App\Services\BaiDu\Feed;

use App\Models\BaiDuFeedAdgroupModel;
use App\Models\BaiDuFeedCampaignModel;


class BaiDuFeedAdgroupService extends BaiDuFeedService
{

    public function syncItem($subAccount){

        $params = [];
        foreach ($subAccount as $account){
            // 获取计划ID
            $campaignIds = [];

            $campaigns = (new BaiDuFeedCampaignModel())
                ->where('account_id',$account['account_id'])
                ->get();

            foreach ($campaigns as $campaign){
                $this->setAccountMap($account);
                $campaignIds[] = $campaign['id'];
            }

            if(empty($campaignIds)) continue;

            $params[] = [
                'campaign_feed_ids' => $campaignIds,
                'account_name'      => $account['name']
            ];
        }
        $saveData = [];
        $list = $this->sdk->multiGetAdGroupFeed($params);
        foreach ($list as $item){
            // 计划不存在处理
            $this->handleCampaignFeedIdNotExists($item);

            $accountName = isset($item['req']['param']['header']['target'])
                ? $item['req']['param']['header']['target']
                : $item['req']['param']['header']['username'];

            $account = $this->getAccountByName($accountName);

            foreach ($item['data']['body']['data'] as $adgroup){
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
                    'ocpc_trans_from'   => $adgroup['ocpc']['transFrom'] ?? 0,
                    'ocpc_bid'          => $adgroup['ocpc']['ocpcBid'] ?? 0,
                    'ocpc_trans_type'   => $adgroup['ocpc']['transType'] ?? 0,
                    'ocpc_pay_mode'     => $adgroup['ocpc']['payMode'] ?? 0,
                    'extends'           => json_encode($adgroup),
                    'remark_status'     => '',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ];
            }
        }
        if(empty($saveData)) return;
        $this->batchSave(BaiDuFeedAdgroupModel::class,$saveData);
    }





}
