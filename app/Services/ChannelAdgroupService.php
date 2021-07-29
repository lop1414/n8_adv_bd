<?php

namespace App\Services;

use App\Common\Enums\AdvAliasEnum;
use App\Common\Enums\PlatformEnum;
use App\Common\Helpers\Advs;
use App\Common\Helpers\Functions;
use App\Common\Services\BaseService;
use App\Common\Services\SystemApi\UnionApiService;
use App\Common\Tools\CustomException;
use App\Models\BaiDuCreativeModel;
use App\Models\ChannelAdgroupLogModel;
use App\Models\ChannelAdgroupModel;
use Illuminate\Support\Facades\DB;

class ChannelAdgroupService extends BaseService
{
    /**
     * @param $data
     * @return bool
     * @throws CustomException
     * 批量更新
     */
    public function batchUpdate($data){
        $this->validRule($data, [
            'channel_id' => 'required|integer',
            'adgroup_feed_ids' => 'required|array',
            'channel' => 'required',
            'platform' => 'required'
        ]);

        Functions::hasEnum(PlatformEnum::class, $data['platform']);

        DB::beginTransaction();

        try{
            foreach($data['adgroup_feed_ids'] as $adgroupFeedId){
                $this->update([
                    'adgroup_feed_id' => $adgroupFeedId,
                    'channel_id' => $data['channel_id'],
                    'platform' => $data['platform'],
                    'extends' => [
                        'channel' => $data['channel'],
                    ],
                ]);
            }
        }catch(CustomException $e){
            DB::rollBack();
            throw $e;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return true;
    }

    /**
     * @param $data
     * @return bool
     * 更新
     */
    public function update($data){
        $channelAdgroupModel = new ChannelAdgroupModel();
        $channelAdgroup = $channelAdgroupModel->where('adgroup_feed_id', $data['adgroup_feed_id'])
            ->where('platform', $data['platform'])
            ->first();

        $flag = $this->buildFlag($channelAdgroup);
        if(empty($channelAdgroup)){
            $channelAdgroup = new ChannelAdgroupModel();
        }

        $channelAdgroup->adgroup_feed_id = $data['adgroup_feed_id'];
        $channelAdgroup->channel_id = $data['channel_id'];
        $channelAdgroup->platform = $data['platform'];
        $channelAdgroup->extends = $data['extends'];
        $ret = $channelAdgroup->save();
        if($ret && !empty($channelAdgroup->adgroup_feed_id) && $flag != $this->buildFlag($channelAdgroup)){
            $this->createChannelAdLog([
                'channel_adgroup_feed_id' => $channelAdgroup->id,
                'adgroup_feed_id' => $data['adgroup_feed_id'],
                'channel_id' => $data['channel_id'],
                'platform'   => $data['platform'],
                'extends'    => $data['extends']
            ]);
        }

        return $ret;
    }

    /**
     * @param $channelAdgroup
     * @return string
     * 构建标识
     */
    protected function buildFlag($channelAdgroup){
        $adminId = !empty($channelAdgroup->extends->channel->admin_id) ? $channelAdgroup->extends->channel->admin_id : 0;
        if(empty($channelAdgroup)){
            $flag = '';
        }else{
            $flag = implode("_", [
                $channelAdgroup->adgroup_feed_id,
                $channelAdgroup->channel_id,
                $channelAdgroup->platform,
                $adminId
            ]);
        }
        return $flag;
    }

    /**
     * @param $data
     * @return bool
     * 创建渠道-计划日志
     */
    protected function createChannelAdLog($data){
        $channelFeedCreativeLogModel = new ChannelAdgroupLogModel();
        $channelFeedCreativeLogModel->channel_adgroup_feed_id = $data['channel_adgroup_feed_id'];
        $channelFeedCreativeLogModel->adgroup_feed_id = $data['adgroup_feed_id'];
        $channelFeedCreativeLogModel->channel_id = $data['channel_id'];
        $channelFeedCreativeLogModel->platform = $data['platform'];
        $channelFeedCreativeLogModel->extends = $data['extends'];
        return $channelFeedCreativeLogModel->save();
    }

//    /**
//     * @param $param
//     * @return array
//     * @throws CustomException
//     * 列表
//     */
//    public function select($param){
//        $this->validRule($param, [
//            'start_datetime' => 'required',
//            'end_datetime' => 'required',
//        ]);
//        Functions::timeCheck($param['start_datetime']);
//        Functions::timeCheck($param['end_datetime']);
//        $channelAdModel = new ChannelAdModel();
//        $channelAds = $channelAdModel->whereBetween('updated_at', [$param['start_datetime'], $param['end_datetime']])->get();
//
//        $distinct = $data = [];
//        foreach($channelAds as $channelAd){
//            if(empty($distinct[$channelAd['channel_id']])){
//                // 计划
//                $oceanAd = OceanAdModel::find($channelAd['ad_id']);
//                if(empty($oceanAd)){
//                    continue;
//                }
//
//                // 账户
//                $oceanAccount = (new OceanAccountModel())->where('account_id', $oceanAd['account_id'])->first();
//                if(empty($oceanAccount)){
//                    continue;
//                }
//
//                $data[] = [
//                    'channel_id' => $channelAd['channel_id'],
//                    'ad_id' => $channelAd['ad_id'],
//                    'ad_name' => $oceanAd['name'],
//                    'account_id' => $oceanAd['account_id'],
//                    'account_name' => $oceanAccount['name'],
//                    'admin_id' => $oceanAccount['admin_id'],
//                ];
//                $distinct[$channelAd['channel_id']] = 1;
//            }
//        }
//
//        return $data;
//    }
//
//    /**
//     * @param $data
//     * @return array
//     * @throws CustomException
//     * 详情
//     */
//    public function read($data){
//        $this->validRule($data, [
//            'channel_id' => 'required|integer'
//        ]);
//
//        $channelAdModel = new ChannelAdModel();
//        $adIds = $channelAdModel->where('channel_id', $data['channel_id'])->pluck('ad_id')->toArray();
//
//        $builder = new OceanAdModel();
//        $builder = $builder->whereIn('id', $adIds);
//
//        // 过滤
//        if(!empty($data['filtering'])){
//            $builder = $builder->filtering($data['filtering']);
//        }
//
//        $ads = $builder->get();
//
//        foreach($ads as $k => $v){
//            unset($ads[$k]['extends']);
//        }
//
//        foreach($ads as $ad){
//            if(!empty($ad->ocean_ad_extends)){
//                $ad->convert_callback_strategy = ConvertCallbackStrategyModel::find($ad->ocean_ad_extends->convert_callback_strategy_id);
//            }else{
//                $ad->convert_callback_strategy = null;
//            }
//        }
//
//        return [
//            'channel_id' => $data['channel_id'],
//            'list' => $ads
//        ];
//    }


    /**
     * @param $param
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($param){
        $date = $param['date'];

        $startTime = date('Y-m-d H:i:s', strtotime('-2 hours', strtotime($date)));
        $endTime = "{$date} 23:59:59";

        $lastMaxId = 0;
        do{

            $baiDuFeedCreatives = (new BaiDuCreativeModel())
                ->where('id','>',$lastMaxId)
                ->whereBetween('addtime', [$startTime, $endTime])
                ->skip(0)
                ->take(1000)
                ->orderBy('id')
                ->get();

            $keyword = 'sign='. Advs::getAdvClickSign(AdvAliasEnum::BAI_DU);

            foreach($baiDuFeedCreatives as $baiDuFeedCreative){
                $lastMaxId = $baiDuFeedCreative['id'];

                $material = json_decode($baiDuFeedCreative->extends->material,true);
                $monitorUrl = $material['monitorUrl'] ?? '';
                if(empty($monitorUrl)) continue;
                if(strpos($monitorUrl, $keyword) === false) continue;

                $ret = parse_url($monitorUrl);
                parse_str($ret['query'], $param);

                $unionApiService = new UnionApiService();


                if(!empty($param['android_channel_id'])){
                    $channel = $unionApiService->apiReadChannel(['id' => $param['android_channel_id']]);
                    $channelExtends = $channel['channel_extends'] ?? [];
                    $channel['admin_id'] = $channelExtends['admin_id'] ?? 0;
                    unset($channel['extends']);
                    unset($channel['channel_extends']);

                    $this->update([
                        'adgroup_feed_id' => $baiDuFeedCreative->adgroup_feed_id,
                        'channel_id' => $param['android_channel_id'],
                        'platform' => PlatformEnum::DEFAULT,
                        'extends' => [
                            'monitor_url' => $monitorUrl,
                            'channel' => $channel,
                        ],
                    ]);
                }


            }
        }while(!$baiDuFeedCreatives->isEmpty());

        return true;
    }
}
