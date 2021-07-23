<?php

namespace App\Sdks\BaiDu\Feed\Traits;


trait CreativeFeed
{


    /**
     * @param array $campaignFeedIds
     * @return mixed
     * 查询原生创意信息
     */
    public function getCreativeFeed(array $campaignFeedIds){
        $url = $this->getUrl('json/feed/v1/CreativeFeedService/getCreativeFeed');
        $para = [
            'creativeFeedFields' => [
                "creativeFeedId", "adgroupFeedId", "materialstyle", "creativeFeedName",
                "pause", "material", "status", "refusereason", "expmask", "changeorder",
                "commentnum", "readnum", "playnum", "ideaType", "showMt", "addtime",
                "progFlag", "approvemsgnew", "auditTimeModel", "naUrlGenerationType"
            ],
            'ids'    => $campaignFeedIds,
            'idType' => 1
        ];

        return $this->authRequest($url, $para, 'POST');
    }

}
