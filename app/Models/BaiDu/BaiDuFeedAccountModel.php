<?php

namespace App\Models\BaiDu;


class BaiDuFeedAccountModel extends BaiDuModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'baidu_feed_accounts';



    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'account_id';



    /**
     * @var bool
     * 是否自增
     */
    public $incrementing = false;


    /**
     * @var array
     * 批量更新忽略字段
     */
    protected $updateIgnoreFields = [
        'created_at'
    ];



    protected $fillable = [
        'balance',
        'budget',
        'balance_package',
        'user_stat',
        'ua_status',
        'valid_flows',
    ];


    /**
     * 属性访问器
     * @param $value
     * @return mixed
     */
    public function getValidFlowsAttribute($value){
        return json_decode($value);
    }

    /**
     * 属性修饰器
     * @param $value
     */
    public function setValidFlowsAttribute($value){
        $this->attributes['valid_flows'] = json_encode($value);
    }

}
