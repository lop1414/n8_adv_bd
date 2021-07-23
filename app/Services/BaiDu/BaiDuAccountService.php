<?php

namespace App\Services\BaiDu;

use App\Common\Enums\StatusEnum;
use App\Models\BaiDuAccountModel;
use App\Sdks\BaiDu\Feed\BaiDuFeed;


class BaiDuAccountService extends BaiDuService
{




    public function setSdk($accountName,$password,$token){
        $this->sdk = new BaiDuFeed($accountName,$password,$token);
        return $this;
    }


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
}
