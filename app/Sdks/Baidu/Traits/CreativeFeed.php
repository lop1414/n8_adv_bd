<?php

namespace App\Sdks\BaiDu\Traits;


trait CreativeFeed
{

    /**
     * @param $params
     * @return mixed
     * 并发获取信息流原生创意信息
     */
    public function multiGetCreativeFeed($params){
        $url = $this->getUrl('json/feed/v1/CreativeFeedService/getCreativeFeed');

        $reqParams = [];
        foreach ($params as $item){
            $reqParams[] = [
                'body' => [
                    'creativeFeedFields' => [
                        "creativeFeedId", "adgroupFeedId", "materialstyle", "creativeFeedName",
                        "pause", "material", "status", "refusereason", "expmask", "changeorder",
                        "commentnum", "readnum", "playnum", "ideaType", "showMt", "addtime",
                        "progFlag", "approvemsgnew", "auditTimeModel", "naUrlGenerationType"
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
