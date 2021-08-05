<?php

namespace App\Services\BaiDu\Report;

use App\Common\Tools\CustomException;
use App\Models\BaiDu\Report\BaiDuCreativeReportModel;

class BaiDuCreativeReportService extends BaiDuReportService
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

        $this->modelClass = BaiDuCreativeReportModel::class;
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
        return $this->sdk->multiGetCreativeReportFeed($accountNames, $param,$page,$pageSize);
    }



    /**
     * @param $accountIds
     * @return array|mixed
     * @throws CustomException
     * 按账户消耗执行
     */
    protected function runByAccountCost($accountIds){

        $baiduAccountReportService = new BaiDuAccountReportService();
        $accountReportMap = $baiduAccountReportService->getAccountReportByDate()->pluck('cost', 'account_id');

        $creativeReportMap = $this->getAccountReportByDate()->pluck('cost', 'account_id');

        $creativeAccountIds = ['xx'];
        foreach($accountReportMap as $accountId => $cost){
            if(isset($creativeReportMap[$accountId]) && bcsub($creativeReportMap[$accountId] * 100, $cost * 100) >= 0){
                continue;
            }
            $creativeAccountIds[] = $accountId;
        }

        return $creativeAccountIds;
    }
}
