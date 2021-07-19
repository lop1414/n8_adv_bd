<?php

namespace App\Sdks\BaiDu\Feed\Traits;


trait AccountFeed
{



    /**
     * @return mixed
     * 查询信息流账户信息
     */
    public function getAccountFeed(){
        $url = $this->getUrl('json/feed/v1/AccountFeedService/getAccountFeed');
        $para = [
            'accountFeedFields' => ["userId","balance","budget","balancePackage","userStat","uaStatus","validFlows"]
        ];

        return $this->authRequest($url, $para, 'POST');
    }

}
