<?php

namespace App\Services;

use App\Common\Enums\AdvClickSourceEnum;
use App\Common\Enums\ConvertCallbackTimeEnum;
use App\Common\Enums\ConvertTypeEnum;
use App\Common\Models\ClickModel;
use App\Common\Models\ConvertCallbackModel;
use App\Common\Services\ConvertMatchService;
use App\Models\BaiDu\BaiDuAdgroupExtendModel;

class AdvConvertMatchService extends ConvertMatchService
{
    /**
     * @param $click
     * @param $convert
     * @return array|mixed|void
     * 获取转化回传规则
     */
    protected function getConvertCallbackStrategy($click, $convert){
        // 转化类型
        $convertType = $convert['convert_type'];

        // 默认策略
        $strategy = [
            ConvertTypeEnum::PAY => [
                'time_range' => ConvertCallbackTimeEnum::TODAY,
                'convert_times' => 1,
                'callback_rate' => 100,
                'min_amount' => 20
            ],
        ];

        // 配置策略
        $adgroupId = $click->adgroup_id ?? 0;
        $adgroupExtend = BaiDuAdgroupExtendModel::find($adgroupId);
        if(!empty($adgroupExtend) && !empty($adgroupExtend->convert_callback_strategy()->enable()->first())){
            $strategy = $adgroupExtend->convert_callback_strategy['extends'];
        }

        $convertStrategy = $strategy[$convertType] ?? ['time_range' => ConvertCallbackTimeEnum::NEVER];

        return $convertStrategy;
    }

    /**
     * @param $click
     * @param $convert
     * @return mixed
     * 获取转化回传列表
     */
    protected function getConvertCallbacks($click, $convert){
        $clickDatetime = date('Y-m-d H:i:s', strtotime("-15 days", strtotime($convert['convert_at'])));
        $convertDate = date('Y-m-d', strtotime($convert['convert_at']));
        $convertRange = [
            $convertDate .' 00:00:00',
            $convertDate .' 23:59:59',
        ];

        $adgroupId = $click->adgroup_id ?? 0;

        $convertCallbackModel = new ConvertCallbackModel();
        $convertCallbacks = $convertCallbackModel->whereRaw("
            click_id IN (
                SELECT id FROM clicks
                    WHERE adgroup_id = '{$adgroupId}'
                        AND click_at BETWEEN '{$clickDatetime}' AND '{$convert['convert_at']}'
            ) AND convert_at BETWEEN '{$convertRange[0]}' AND '{$convertRange[1]}'
            AND convert_type IN ('{$convert['convert_type']}')
        ")->get();

        return $convertCallbacks;
    }

    /**
     * @param $data
     * @return ClickModel|void
     * 获取匹配查询构造器
     */
    protected function getMatchByBuilder($data){
        $builder = new ClickModel();

        if($this->clickSource != AdvClickSourceEnum::N8_TRANSFER){
            $channelId = $data['n8_union_user']['channel_id'] ?? 0;
            if(!empty($channelId)){
                $builder = $builder->whereRaw("
                adgroup_id IN (
                    SELECT adgroup_id FROM channel_adgroups
                        WHERE channel_id = {$channelId}
                )
            ");
            }
        }

        return $builder;
    }



    /**
     * @param $userAgent1
     * @param $userAgent2
     * @return bool
     * 是否相同 user_agent
     */
    protected function isSameUserAgent($userAgent1, $userAgent2){

        // 平台校验
        $platformChcek = $userAgent1->platform() == $userAgent2->platform();

        // 平台版本校验
        $platformVersionCheck = $userAgent1->version($userAgent1->platform()) == $userAgent2->version($userAgent2->platform());

        return $platformChcek && $platformVersionCheck;
    }




    /**
     * @param $data
     * @param $items
     * @return |null
     * 按IP重匹配
     */
    protected function reMatchByIp($data, $items){
        if(empty($items)) return null;
        return $items->first();
    }

}
