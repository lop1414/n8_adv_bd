<?php

namespace App\Models\BaiDu;


class BaiDuImageModel extends BaiDuModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'baidu_images';


    /**
     * 产品
     */
    public function manageAccount(){
        return $this->hasOne('App\Models\BaiDu\BaiDuAccountModel', 'account_id', 'parent_id');
    }

}
