<?php

namespace App\Sdks\BaiDu\Traits;


trait CampaignFeed
{


    /**
     * @param $accountNames
     * @return mixed
     * 并发获取信息流计划信息
     */
    public function multiGetCampaignFeed($accountNames){
        $url = $this->getUrl('json/feed/v1/CampaignFeedService/getCampaignFeed');

        $params = [];
        foreach ($accountNames as $accountName){
            $params[] = [
                'body' => [
                    'campaignFeedFields' => [
                        "campaignFeedId","campaignFeedName","subject","appinfo","budget","starttime","endtime",
                        "bgtctltype","pause","status","bstype","addtime","shadow","schedule",
                    ]
                ],
                'header' =>  [
                    'target' => $accountName
                ]
            ];
        }

        return $this->multiGet($url,$params);
    }


}
