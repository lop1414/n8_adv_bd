<?php

namespace App\Services\BaiDu\Feed;

use App\Models\BaiDuFeedCampaignModel;
use App\Models\BaiDuFeedCreativeModel;


class BaiDuFeedCreativeService extends BaiDuFeedService
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
        $list = $this->sdk->multiGetCreativeFeed($params);
        foreach ($list as $item){

            $accountName = isset($item['req']['param']['header']['target'])
                ? $item['req']['param']['header']['target']
                : $item['req']['param']['header']['username'];

            $account = $this->getAccountByName($accountName);
            foreach ($item['data']['body']['data'] as $creative){
                $saveData[] = [
                    'id'                => $creative['creativeFeedId'],
                    'account_id'        => $account['account_id'],
                    'adgroup_feed_id'   => $creative['adgroupFeedId'],
                    'creative_feed_name'=> $creative['creativeFeedName'],
                    'materialstyle'     => $creative['materialstyle'],
                    'pause'             => $creative['pause'],
                    'status'            => $creative['status'],
                    'idea_type'         => $creative['ideaType'],
                    'show_mt'           => $creative['showMt'] ?? 0,
                    'addtime'           => date('Y-m-d H:i:s',strtotime($creative['addtime'])),
                    'extends'           => json_encode($creative),
                    'remark_status'     => '',
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ];
            }
        }
        if(empty($saveData)) return;
        $this->batchSave(BaiDuFeedCreativeModel::class,$saveData);
    }
}
