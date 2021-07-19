<?php

namespace App\Models;

use App\Common\Models\BaseModel;

class BaiDuAccountModel extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'baidu_accounts';





    protected $fillable = [
        'account_id',
        'name',
        'token',
        'ocpc_token',
        'rebate',
        'password',
        'parent_id',
        'status',
        'admin_id',
        'extends',
    ];


    /**
     * 属性访问器
     * @param $value
     * @return mixed
     */
    public function getExtendsAttribute($value){
        return json_decode($value);
    }

    /**
     * 属性修饰器
     * @param $value
     */
    public function setExtendsAttribute($value){
        $this->attributes['extends'] = json_encode($value);
    }

}
