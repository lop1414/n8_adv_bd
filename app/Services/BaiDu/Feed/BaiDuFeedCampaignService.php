<?php

namespace App\Services\BaiDu\Feed;

use App\Models\BaiDuFeedCampaignModel;


class BaiDuFeedCampaignService extends BaiDuFeedService
{


    public function syncItem($subAccount){
        $accountNames = [];
        foreach ($subAccount as $account){
            $this->setAccountMap($account);
            $accountNames[] = $account['name'];
        }

        $list = $this->sdk->multiGetCampaignFeed($accountNames);
        $saveData = [];
        foreach ($list as $item){

            $accountName = isset($item['req']['param']['header']['target'])
                ? $item['req']['param']['header']['target']
                : $item['req']['param']['header']['username'];

            $account = $this->getAccountByName($accountName);

            foreach ($item['data']['data'] as $campaign){
                $saveData[] = [
                    'id'                => $campaign['campaignFeedId'],
                    'account_id'        => $account['account_id'],
                    'campaign_feed_name'=> $campaign['campaignFeedName'],
                    'subject'           => $campaign['subject'],
                    'budget'            => $campaign['budget'] * 100,
                    'pause'             => $campaign['pause'],
                    'status'            => $campaign['status'],
                    'starttime'         => date('Y-m-d H:i:s',strtotime($campaign['starttime'])),
                    'endtime'           => isset($campaign['endtime']) ?  date('Y-m-d H:i:s',strtotime($campaign['endtime'])): null,
                    'addtime'           => date('Y-m-d H:i:s',strtotime($campaign['addtime'])),
                    'extends'           => json_encode($campaign),
                    'created_at'        => date('Y-m-d H:i:s'),
                    'updated_at'        => date('Y-m-d H:i:s'),
                ];
            }

        }

        if(empty($saveData)) return;
        $this->batchSave(BaiDuFeedCampaignModel::class,$saveData);
    }


}
