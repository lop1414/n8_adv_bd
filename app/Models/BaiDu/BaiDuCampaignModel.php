<?php

namespace App\Models\BaiDu;


class BaiDuCampaignModel extends BaiDuModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'baidu_campaigns';


    /**
     * @var array
     * 批量更新忽略字段
     */
    protected $updateIgnoreFields = [
        'created_at'
    ];



    protected $fillable = [
        'account_id',
        'campaign_feed_name',
        'subject',
        'budget',
        'pause',
        'status',
        'starttime',
        'endtime',
        'addtime',
        'extends',
        'remark_status'
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
