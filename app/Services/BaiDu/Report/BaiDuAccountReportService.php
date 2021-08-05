<?php

namespace App\Services\BaiDu\Report;

use App\Common\Tools\CustomException;
use App\Models\BaiDu\Report\BaiDuAccountReportModel;
use App\Models\BaiDu\Report\BaiDuCreativeReportModel;

class BaiDuAccountReportService extends BaiDuReportService
{

    /**
     * @var
     * 统计时间单位
     */
    public $unitOfTime = 7;

    /**
     * BaiDuCreativeReportService constructor.
     * @param array $manageAccount
     */
    public function __construct($manageAccount = []){
        parent::__construct($manageAccount);

        $this->modelClass = BaiDuAccountReportModel::class;
    }


    /**
     * @param $accountNames
     * @param $param
     * @param $page
     * @param $pageSize
     * @return mixed|void
     * sdk批量获取列表
     */
    public function sdkMultiGetList($accountNames,$param,$page,$pageSize){
        return $this->sdk->multiGetAccountReportFeed($accountNames, $param,$page,$pageSize);
    }


}
