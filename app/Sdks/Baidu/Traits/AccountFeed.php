<?php

namespace App\Sdks\BaiDu\Traits;


trait AccountFeed
{


    /**
     * @param $accountNames
     * @return mixed
     * 并发获取信息流账户信息
     */
    public function multiGetAccountFeed($accountNames){
        $url = $this->getUrl('json/feed/v1/AccountFeedService/getAccountFeed');

        $params = [];
        foreach ($accountNames as $accountName){
            $params[] = [
                'body' => [
                    'accountFeedFields' => ["userId","balance","budget","balancePackage","userStat","uaStatus","validFlows"]
                ],
                'header' =>  [
                    'target' => $accountName
                ]
            ];
        }

        return $this->multiGet($url,$params);
    }

}
