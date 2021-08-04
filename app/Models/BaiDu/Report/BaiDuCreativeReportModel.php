<?php

namespace App\Models\BaiDu\Report;

use Illuminate\Support\Facades\DB;

class BaiDuCreativeReportModel extends BaiDuReportModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'baidu_creative_reports';

    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @param $query
     * @return mixed
     * 计算
     */
    public function scopeCompute($query){
        return $query->select(DB::raw("
                SUM(`cost`) `cost`,
                SUM(`click`) `click`,
                SUM(`impression`) `impression`,
                SUM(`ocpctargettrans`) `ocpctargettrans`,
                ROUND(SUM(`cost` / 100) / SUM(`impression`) * 1000, 2) `show_cost`,
                ROUND(SUM(`cost` / 100) / SUM(`click`), 2) `click_cost`,
                CONCAT(ROUND(SUM(`click`) / SUM(`impression`) * 100, 2), '%') `click_rate`,
                ROUND(SUM(`cost` / 100) / SUM(`ocpctargettrans`), 2) `convert_cost`,
                CONCAT(ROUND(SUM(`ocpctargettrans`) / SUM(`click`) * 100, 2), '%') `convert_rate`
            "));
    }
}
