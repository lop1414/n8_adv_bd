<?php

namespace App\Services\BaiDu\Report;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Services\BaiDu\BaiDuService;
use Illuminate\Support\Facades\DB;

class BaiDuReportService extends BaiDuService
{
    /**
     * @var string
     * 模型类
     */
    public $modelClass;

    /**
     * @var
     * 统计时间单位
     */
    public $unitOfTime;


    /**
     * BaiDuReportService constructor.
     * @param array $manageAccount
     */
    public function __construct($manageAccount = []){
        parent::__construct($manageAccount);
    }


    /**
     * @param array $option
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($option = []){
        ini_set('memory_limit', '2048M');

        $t = microtime(1);

        $accountIds = [];
        // 账户id过滤
        if(!empty($option['account_ids'])){
            $accountIds = $option['account_ids'];
        }

        // 并发分片大小
        if(!empty($option['multi_chunk_size'])){
            $multiChunkSize = min(intval($option['multi_chunk_size']), 8);
            $this->sdk->setMultiChunkSize($multiChunkSize);
        }

        // 在跑账户
        if(!empty($option['running'])){
            $runningAccountIds = $this->getRunningAccountIds();
            if(!empty($accountIds)){
                $accountIds = array_intersect($accountIds, $runningAccountIds);
            }else{
                $accountIds = $runningAccountIds;
            }
        }

        $dateRange = Functions::getDateRange($option['date']);
        $dateList = Functions::getDateListByRange($dateRange);

        // 删除
        if(!empty($option['delete'])){
            $between = [
                $dateRange[0] .' 00:00:00',
                $dateRange[1] .' 23:59:59',
            ];

            $model = new $this->modelClass();

            $builder = $model->whereBetween('stat_datetime', $between);

            if(!empty($accountIds)){
                $builder->whereIn('account_id', $accountIds);
            }

            $builder->delete();
        }

        if(!empty($option['run_by_account_cost'])){
            // 处理广告账户id
            $accountIds = $this->runByAccountCost($accountIds);
        }

        // 获取子账户组
        $accountGroup = $this->getSubAccountGroup($accountIds);


        foreach($dateList as $date){
            $param = [
                'start_date'  => $date,
                'end_date'     => $date
            ];

            if(!empty($this->unitOfTime)){
                $param['unit_of_time'] = $this->unitOfTime;
            }


            foreach($accountGroup as $groups){
                $this->setSdk($groups['name'],$groups['password'],$groups['token']);

                $items = $this->multiGetPageList($groups['list'], $param);

                Functions::consoleDump('count:'. count($items));

                $cost = 0;

                // 保存
                $data = [];
                foreach($items as $item) {
                    $cost += $item['cost'];

                    if(!$this->itemValid($item)){
                        continue;
                    }

                    $item['cost'] *= 100000;
                    $item['stat_datetime'] = date('Y-m-d H:i:s',strtotime($item['date']));
                    $item['extends'] = json_encode($item);

                    $data[] = $item;
                }

                // 批量保存
                $this->batchSave($this->modelClass,$data,20,false);

                Functions::consoleDump('cost:'. $cost);
            }
        }

        $t = microtime(1) - $t;
        Functions::consoleDump($t);

        return true;
    }




    public function sdkMultiGetList($accountNames,$param,$page,$pageSize){
        throw new CustomException([
            'code' => 'PLEASE_WRITE_SDK_MULTI_GET_LIST_CODE',
            'message' => '请书写sdk批量获取列表代码',
        ]);
    }


    /**
     * @param $item
     * @return bool
     * 校验
     */
    protected function itemValid($item){

        if(
            empty($item['cost']) &&
            empty($item['impression']) &&
            empty($item['click']) &&
            empty($item['ocpctargettrans'])
        ){
            return false;
        }

        return true;
    }


    /**
     * @param $accountIds
     * @return mixed
     * 按账户消耗执行
     */
    protected function runByAccountCost($accountIds){
        return $accountIds;
    }



    /**
     * @param string $date
     * @return mixed
     * @throws CustomException
     * 按日期获取账户报表
     */
    public function getAccountReportByDate($date = 'today'){
        $date = Functions::getDate($date);
        Functions::dateCheck($date);

        $model = new $this->modelClass();
        $report = $model->whereBetween('stat_datetime', ["{$date} 00:00:00", "{$date} 23:59:59"])
            ->groupBy('account_id')
            ->orderBy('cost', 'DESC')
            ->select(DB::raw("account_id, SUM(cost) cost"))
            ->get();

        return $report;
    }
}
