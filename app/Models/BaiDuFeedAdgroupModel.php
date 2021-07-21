<?php

namespace App\Models;

use App\Common\Models\BaseModel;

class BaiDuFeedAdgroupModel extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'baidu_feed_adgroups';


    /**
     * @var array
     * 批量更新忽略字段
     */
    protected $updateIgnoreFields = [
        'created_at'
    ];



    protected $fillable = [
        'account_id',
        'campaign_feed_id',
        'adgroup_feed_name',
        'pause',
        'status',
        'bid',
        'bidtype',
        'atp_feed_id',
        'ocpc_trans_from',
        'ocpc_bid',
        'ocpc_trans_type',
        'ocpc_pay_mode',
        'extends'
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
