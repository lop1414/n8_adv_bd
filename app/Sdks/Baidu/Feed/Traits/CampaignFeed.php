<?php

namespace App\Sdks\BaiDu\Feed\Traits;


trait CampaignFeed
{



    /**
     * @return mixed
     * 查询信息流账户信息
     */
    public function getCampaignFeed(){
        $url = $this->getUrl('json/feed/v1/CampaignFeedService/getCampaignFeed');
        $para = [
            'campaignFeedFields' => [
                "campaignFeedId","campaignFeedName","subject","appinfo","budget","starttime","endtime",
                "bgtctltype","pause","status","bstype","addtime","shadow","schedule",
            ]
        ];

        return $this->authRequest($url, $para, 'POST');
    }

}
