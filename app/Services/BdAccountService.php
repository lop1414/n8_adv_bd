<?php

namespace App\Services;

use App\Common\Enums\StatusEnum;
use App\Common\Services\BaseService;
use App\Models\BdAccountModel;
use App\Sdks\BaiDu\BaiDu;


class BdAccountService extends BaseService
{


    public function syncSubAccount($mangeAccount){
        $bdSdk = new BaiDu();
        $bdSdk->setAccountName($mangeAccount['name']);
        $bdSdk->setAccountPassword($mangeAccount['password']);
        $bdSdk->setToken($mangeAccount['token']);
        $list = $bdSdk->getSubAccount();
        foreach ($list as $item){
            $info = (new BdAccountModel())->where('account_id',$item['userid'])->first();
            if(empty($info)){
                $info = new BdAccountModel();
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
}
