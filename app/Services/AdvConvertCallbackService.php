<?php

namespace App\Services;

use App\Common\Enums\ConvertTypeEnum;
use App\Common\Tools\CustomException;
use App\Common\Services\ConvertCallbackService;
use App\Models\BaiDu\BaiDuAccountModel;
use App\Models\BaiDu\BaiDuAdgroupModel;

class AdvConvertCallbackService extends ConvertCallbackService
{
    /**
     * @param $item
     * @return bool
     * @throws CustomException
     * 回传
     */
    protected function callback($item){
        $eventTypeMap = $this->getEventTypeMap();

        if(!isset($eventTypeMap[$item->convert_type])){
            // 无映射
            throw new CustomException([
                'code' => 'UNDEFINED_EVENT_TYPE_MAP',
                'message' => '未定义的事件类型映射',
                'log' => true,
                'data' => [
                    'item' => $item,
                ],
            ]);
        }

        // 关联点击
        if(empty($item->click)){
            throw new CustomException([
                'code' => 'NOT_FOUND_CONVERT_CLICK',
                'message' => '找不到该转化对应点击',
                'log' => true,
                'data' => [
                    'item' => $item,
                ],
            ]);
        }

        $eventType = $eventTypeMap[$item->convert_type];

        //付费金额
        $payAmount = 0;
//        if(!empty($payAmount)){
//            $payAmount =  $item->extends->amount;
//        }

        $this->runCallback($item->click,$eventType,$payAmount);

        return true;
    }



    public function runCallback($click,$eventType,$payAmount){
        if(!empty($click->link)){
            return $this->linkCallback($click, $eventType, $payAmount);

        }else{
            return $this->callbackUrlCallback($click, $eventType, $payAmount);

        }

    }






    /**
     * @param $click
     * @param $eventType
     * @param int $payAmount
     * @throws CustomException
     * link 回传
     */
    public function linkCallback($click, $eventType, $payAmount = 0){


        $conversionType = [
            'logidUrl'  => $click->link,
            'newType'   => $eventType,
        ];

        // 回传金额
        if($payAmount > 0){
            $conversionType['convertValue'] = $payAmount;
        }

        // token
        $accountId = $click->account_id ?? 0;
        if(empty($accountId)){
            $adgroupId = $click->adgroup_id;
            $adgroup = (new BaiDuAdgroupModel())->find($adgroupId);
            if(empty($adgroup)){
                throw new CustomException([
                    'code' => 'NOT_ADGROUP',
                    'message' => '找不到推广单元信息',
                    'log' => true,
                    'data' => [
                        'click_id' => $click->id,
                        'adgroup_id' => $adgroupId,
                    ],
                ]);
            }
            $accountId = $adgroup->account_id;
        }

        $account = (new BaiDuAccountModel())->where('account_id',$accountId)->first();
        $ocpcToken = $account->ocpc_token;
        // 未配置token
        if(empty($ocpcToken) && $account->parent_id > 0){
            // 管家账户token
            $parentAccount = (new BaiDuAccountModel())->find($account->parent_id);
            $ocpcToken = $parentAccount->ocpc_token;
        }

        $param = [
            'token' => $ocpcToken,
            'conversionTypes' => [
                $conversionType
            ]
        ];

        $result = $this->curlPost('https://ocpc.baidu.com/ocpcapi/api/uploadConvertData',$param);
        if(empty($result) || !isset($result['header']['status']) || $result['header']['status'] != 0){
            throw new CustomException([
                'code' => 'CONVERT_CALLBACK_ERROR',
                'message' => '转化回传失败 - link回传',
                'log' => true,
                'data' => [
                    'param' => $param,
                    'result' => $result
                ],
            ]);
        }
    }



    /**
     * @param $click
     * @param $eventType
     * @param int $payAmount
     * @throws CustomException
     * 回传链接回传
     */
    public function callbackUrlCallback($click, $eventType, $payAmount = 0){
        throw new CustomException([
            'code' => 'CONVERT_CALLBACK_ERROR',
            'message' => '转化回传失败 - 回传链接回传',
            'log' => true,
            'data' => [
                'click_id' => $click->id ?? 0,
                'event_type' => $eventType,
                'pay_amount' => $payAmount
            ],
        ]);
    }



    /**
     * @return array
     * 获取事件映射
     */
    public function getEventTypeMap(){
        return [
            ConvertTypeEnum::ACTIVATION => 6,   // 下载按钮点击
            ConvertTypeEnum::REGISTER => 51,    // 有意向客户
            ConvertTypeEnum::FOLLOW => 51,      //有意向客户
            ConvertTypeEnum::ADD_DESKTOP => 51,  //有意向客户
            ConvertTypeEnum::PAY => 19,         //一句话咨询
        ];
    }


    /**
     * @param $url
     * @param array $data
     * @return bool|string
     */
    public function curlPost($url , $data = []){

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-type: application/json']);

        if(stripos($url, 'https://') === 0){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
        }

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $output = curl_exec($ch);

        curl_close($ch);

        return json_decode($output, true);

    }



    /**
     * @param $click
     * @return array|void
     */
    public function filterClickData($click){
        return [
            'id' => $click['id'],
            'campaign_id' => $click['campaign_id'],
            'ad_id' => $click['adgroup_id'],
            'creative_id' => $click['creative_id'],
            'click_at' => $click['click_at'],
        ];
    }
}
