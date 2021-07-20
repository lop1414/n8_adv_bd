<?php

namespace App\Services\BaiDu;

use App\Common\Enums\StatusEnum;
use App\Common\Helpers\Functions;
use App\Models\BaiDuAccountModel;
use App\Models\BaiDuFeedAccountModel;
use App\Sdks\BaiDu\BaiDu;
use App\Sdks\BaiDu\Feed\BaiDuFeed;


class BaiDuAccountService extends BaiDuService
{


    public function syncSubAccount($mangeAccount){
        $bdSdk = new BaiDu($mangeAccount['name'],$mangeAccount['password'],$mangeAccount['token']);
        $list = $bdSdk->getSubAccount();
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
            $info->parent_id = $mangeAccount['id'];
            $info->admin_id = 0;
            $info->extends = [
                'remark' => $item['remark']
            ];
            $info->save();
        }
    }


    public function syncAccountFeed($option){
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
                $data = $baiduSdk->getAccountFeed();
                $data = $data[0];

                $info = (new BaiDuFeedAccountModel())->where('account_id',$data['userId'])->first();
                if(empty($info)){
                    $info = new BaiDuFeedAccountModel();
                }
                $info->account_id = $data['userId'];
                $info->balance = $data['balance'];
                $info->budget = $data['budget'];
                $info->balance_package = $data['balancePackage'];
                $info->user_stat = $data['userStat'];
                $info->ua_status = $data['uaStatus'];
                $info->valid_flows = $data['validFlows'];
                $info->save();
            }
        }
    }
}
