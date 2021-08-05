<?php

namespace App\Models\BaiDu\Report;


class BaiDuAccountReportModel extends BaiDuReportModel
{
    /**
     * 关联到模型的数据表
     *
     * @var string
     */
    protected $table = 'baidu_account_reports';

    /**
     * 关联到模型数据表的主键
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
