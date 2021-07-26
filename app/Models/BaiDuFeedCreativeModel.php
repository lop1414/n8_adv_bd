<?php

namespace App\Models;

use App\Common\Models\BaseModel;

class BaiDuFeedCreativeModel extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'baidu_feed_creatives';


    /**
     * @var array
     * 批量更新忽略字段
     */
    protected $updateIgnoreFields = [
        'created_at'
    ];



    protected $fillable = [
        'account_id',
        'adgroup_feed_id',
        'creative_feed_name',
        'materialstyle',
        'pause',
        'status',
        'idea_type',
        'show_mt',
        'addtime',
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
