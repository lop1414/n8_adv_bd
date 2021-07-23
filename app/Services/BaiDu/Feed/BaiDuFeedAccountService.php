<?php

namespace App\Services\BaiDu\Feed;

use App\Models\BaiDuFeedAccountModel;


class BaiDuFeedAccountService extends BaiDuFeedService
{


    public function syncItem($subAccount){
        $accountNames = [];
        foreach ($subAccount as $account){
            $this->setAccountMap($account);
            $accountNames[] = $account['name'];
        }
        $saveData = [];
        $list = $this->sdk->multiGetAccountFeed($accountNames);
        foreach ($list as $item){

            foreach ($item['data']['data'] as $data){
                $saveData[] = [
                    'id'             => $data['userId'],
                    'balance'        => $data['balance'],
                    'budget'         => $data['budget'],
                    'balance_package'=> $data['balancePackage'],
                    'user_stat'      => $data['userStat'],
                    'ua_status'      => $data['uaStatus'],
                    'valid_flows'    => json_encode($data['validFlows']),
                    'created_at'     => date('Y-m-d H:i:s'),
                    'updated_at'     => date('Y-m-d H:i:s')
                ];
            }
        }

        if(empty($saveData)) return;
        $this->batchSave(BaiDuFeedAccountModel::class,$saveData);
    }

}
