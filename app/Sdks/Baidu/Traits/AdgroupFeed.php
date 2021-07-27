<?php

namespace App\Sdks\BaiDu\Feed\Traits;


trait AdgroupFeed
{



    /**
     * @param $params
     * @return mixed
     * 并发获取信息流原生推广单元
     */
    public function multiGetAdGroupFeed($params){
        $url = $this->getUrl('json/feed/v1/AdgroupFeedService/getAdgroupFeed');


        $reqParams = [];
        foreach ($params as $item){
            $reqParams[] = [
                'body' => [
                    'adgroupFeedFields' => [
                        "adgroupFeedId","campaignFeedId","adgroupFeedName","pause","status","bid","producttypes",
                        "ftypes","bidtype","ocpc","atpFeedId"
                    ],
                    'ids'    => $item['campaign_feed_ids'],
                    'idType' => 1
                ],
                'header' =>  [
                    'target' => $item['account_name']
                ]
            ];
        }

        return $this->multiGet($url,$reqParams);
    }

}
