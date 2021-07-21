<?php

namespace App\Sdks\BaiDu\Feed\Traits;


trait AdgroupFeed
{


    /**
     * @param array $campaignFeedIds
     * @return mixed
     * 查询原生推广单元
     */
    public function getAdGroupFeed(array $campaignFeedIds){
        $url = $this->getUrl('json/feed/v1/AdgroupFeedService/getAdgroupFeed');
        $para = [
            'adgroupFeedFields' => [
                "adgroupFeedId","campaignFeedId","adgroupFeedName","pause","status","bid","producttypes",
                "ftypes","bidtype","ocpc","atpFeedId"
            ],
            'ids'    => $campaignFeedIds,
            'idType' => 1
        ];

        return $this->authRequest($url, $para, 'POST');
    }

}
