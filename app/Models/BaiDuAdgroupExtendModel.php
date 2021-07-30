<?php

namespace App\Models;

use App\Common\Models\BaseModel;

class BaiDuAdgroupExtendModel extends BaseModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'baidu_adgroup_extends';


    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'adgroup_feed_id';

    /**
     * @var string
     * 主键数据类型
     */
    public $keyType = 'string';

    /**
     * @var bool
     * 是否自增
     */
    public $incrementing = false;


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 关联策略模型 一对一
     */
    public function convert_callback_strategy(){
        return $this->belongsTo('App\Common\Models\ConvertCallbackStrategyModel', 'convert_callback_strategy_id', 'id');
    }
}
