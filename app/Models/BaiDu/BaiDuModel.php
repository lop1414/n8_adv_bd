<?php

namespace App\Models\BaiDu;

use App\Common\Helpers\Functions;
use App\Common\Models\BaseModel;

class BaiDuModel extends BaseModel
{
    /**
     * @param $query
     * 数据授权
     */
    public function scopeWithPermission($query){
        $adminUserInfo = Functions::getGlobalData('admin_user_info');
        $table = $this->getTable();
        if(!$adminUserInfo['is_admin']){
            $query->whereRaw("
                    {$table}.account_id IN (
                        SELECT account_id FROM baidu_accounts
                            WHERE admin_id = {$adminUserInfo['admin_user']['id']}
                    )
                ");
        }
    }

}
