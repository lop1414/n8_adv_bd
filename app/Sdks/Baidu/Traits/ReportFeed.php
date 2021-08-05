<?php

namespace App\Sdks\BaiDu\Traits;


trait ReportFeed
{

    /**
     * @param $accountNames
     * @param $param
     * @param $page
     * @param $pageSize
     * @return array
     * 并发获取信息流报表
     */
    public function multiGetReportFeed($accountNames,$param,$page,$pageSize){
        $url = $this->getUrl('json/feed/v1/ReportFeedService/getRealTimeFeedData');

        $params = [];
        foreach ($accountNames as $accountName){
            $params[] = [
                'body' => [
                    'realTimeRequestType' => [
                        "performanceData"=> $param['performance_data'],
                        "startDate"      => $param['start_date'],
                        "endDate"        => $param['end_date'],
                        "levelOfDetails" => $param['level_of_details'],
                        "reportType"     => $param['report_type'],
                        "statRange"      => $param['stat_range'],
                        "unitOfTime"     => $param['unit_of_time'] ?? 5,
                        "number"         => $pageSize,
                        "pageIndex"      => $page
                    ]
                ],
                'header' =>  [
                    'target' => $accountName
                ]
            ];
        }

        $list = $this->multiGet($url,$params);

        // 数据过滤
        $req = [];
        foreach ($list as $item){
            $accountName = $item['req']['param']['header']['target'] ?? $item['req']['param']['header']['username'];

            foreach ($item['data']['body']['data'] as $data){
                $tmp = [
                    'account_name'=> $accountName,
                    'date'        => $data['date'],
                    'account_id'  => $data['relateIdsList'][0],
                    'totalRowNumber'  => $data['totalRowNumber']
                ];

                foreach ($param['performance_data'] as $k => $v){
                    $tmp[$v] = $data['kpis'][$k];
                }
                if(!empty($data['relateIdsList'][1])) $tmp['campaign_id'] = $data['relateIdsList'][1];
                if(!empty($data['relateIdsList'][2])) $tmp['adgroup_id'] = $data['relateIdsList'][2];
                if(!empty($data['relateIdsList'][3])) $tmp['creative_id'] = $data['relateIdsList'][3];

                $req[] = $tmp;
            }
        }
        return $req;
    }



    /**
     * @param $accountNames
     * @param $param
     * @param $page
     * @param int $pageSize
     * @return array
     * 并发获取信息流账户报表
     */
    public function multiGetAccountReportFeed($accountNames,$param,$page,$pageSize = 200){
        $param['level_of_details'] = 2;
        $param['report_type'] = 700;
        $param['stat_range'] = 2;
        $param['performance_data'] = ['cost','click','impression'];
        return $this->multiGetReportFeed($accountNames,$param,$page,$pageSize);
    }


    /**
     * @param $accountNames
     * @param $param
     * @param $page
     * @param int $pageSize
     * @return mixed
     * 并发获取信息流创意报表
     */
    public function multiGetCreativeReportFeed($accountNames,$param,$page,$pageSize = 200){
        $param['level_of_details'] = 7;
        $param['report_type'] = 703;
        $param['stat_range'] = 7;
        $param['performance_data'] = ['cost','click','impression','ocpctargettrans'];
        return $this->multiGetReportFeed($accountNames,$param,$page,$pageSize);
    }




}
