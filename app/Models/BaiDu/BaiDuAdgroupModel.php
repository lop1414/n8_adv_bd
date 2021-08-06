<?php

namespace App\Models\BaiDu;


class BaiDuAdgroupModel extends BaiDuModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'baidu_adgroups';


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
        'extends',
        'remark_status'
    ];

    /**
     * 属性访问器
     * @param $value
     * @return mixed
     */
    public function getBidAttribute($value){
        return $value / 100;
    }

    /**
     * 属性修饰器
     * @param $value
     */
    public function setBidAttribute($value){
        $this->attributes['bid'] = $value * 100;
    }


    /**
     * 属性访问器
     * @param $value
     * @return mixed
     */
    public function getOcpcBidAttribute($value){
        return $value / 100;
    }

    /**
     * 属性修饰器
     * @param $value
     */
    public function setOcpcBidAttribute($value){
        $this->attributes['ocpc_bid'] = $value * 100;
    }


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

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 关联推广计划
     */
    public function baidu_campaign(){
        return $this->hasOne('App\Models\BaiDu\BaiDuCampaignModel', 'id', 'campaign_feed_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 关联推广单元扩展模型 一对一
     */
    public function baidu_adgroup_extends(){
        return $this->hasOne('App\Models\BaiDu\BaiDuAdgroupExtendModel', 'adgroup_feed_id', 'id');
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * 关联渠道模型 一对一
     */
    public function channel_adgroup(){
        return $this->hasOne('App\Models\ChannelAdgroupModel', 'adgroup_feed_id', 'id');
    }


}
