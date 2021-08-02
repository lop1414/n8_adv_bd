<?php

namespace App\Services\BaiDu;

use App\Common\Enums\StatusEnum;
use App\Models\BaiDu\BaiDuAccountModel;
use App\Models\BaiDu\BaiDuFeedAccountModel;


class BaiDuAccountService extends BaiDuService
{


    public function syncSubAccount(){
        $list = $this->sdk->getSubAccount();
        foreach ($list as $item){
            $info = (new BaiDuAccountModel())->where('account_id',$item['userid'])->first();
            if(empty($info)){
                $info = new BaiDuAccountModel();
                $info->status = StatusEnum::ENABLE;
                $info->token = '';
                $info->ocpc_token = '';
                $info->password = '';
            }
            $info->account_id = $item['userid'];
            $info->name = $item['username'];
            $info->parent_id = $this->manageAccount['id'];
            $info->admin_id = 0;
            $info->extends = [
                'remark' => $item['remark']
            ];
            $info->save();
        }
    }


    /**
     * @param $subAccount
     * 同步信息流账户
     */
    public function syncItem($subAccount){
        $accountNames = [];
        foreach ($subAccount as $account){
            $this->setAccountMap($account);
            $accountNames[] = $account['name'];
        }
        $saveData = [];
        $list = $this->sdk->multiGetAccountFeed($accountNames);
        foreach ($list as $item){

            foreach ($item['data']['body']['data'] as $data){
                $saveData[] = [
                    'account_id'     => $data['userId'],
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
