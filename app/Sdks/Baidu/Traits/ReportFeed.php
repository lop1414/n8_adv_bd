<?php

namespace App\Sdks\BaiDu\Traits;


trait ReportFeed
{



    /**
     * @param $accountNames
     * @param $param
     * @param $page
     * @param int $pageSize
     * @return mixed
     * 并发获取信息流创意报表
     */
    public function multiGetCreativeReportFeed($accountNames,$param,$page,$pageSize = 200){
        $url = $this->getUrl('json/feed/v1/ReportFeedService/getRealTimeFeedData');

        $params = [];
        foreach ($accountNames as $accountName){
            $params[] = [
                'body' => [
                    'realTimeRequestType' => [
                        "performanceData"=> ['cost','click','impression','ocpctargettrans'],
                        "startDate"      => $param['start_date'],
                        "endDate"        => $param['end_date'],
                        "levelOfDetails" => 7,
                        "reportType"     => 703,
                        "statRange"      => 7,
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
                $req[] = [
                    'account_name'=> $accountName,
                    'date'        => $data['date'],
                    'account_id'  => $data['relateIdsList'][0],
                    'campaign_id' => $data['relateIdsList'][1],
                    'adgroup_id'  => $data['relateIdsList'][2],
                    'creative_id' => $data['relateIdsList'][3],
                    'cost'        => $data['kpis'][0],
                    'click'       => $data['kpis'][1],
                    'impression'      => $data['kpis'][2],
                    'ocpctargettrans' => $data['kpis'][3],
                    'totalRowNumber'  => $data['totalRowNumber']
                ];
            }
        }
        return $req;
    }

}
