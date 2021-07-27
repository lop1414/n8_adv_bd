<?php

namespace App\Services;

use App\Common\Enums\AdvAliasEnum;
use App\Common\Enums\PlatformEnum;
use App\Common\Helpers\Advs;
use App\Common\Helpers\Functions;
use App\Common\Services\BaseService;
use App\Common\Services\SystemApi\UnionApiService;
use App\Common\Tools\CustomException;
use App\Models\BaiDuFeedCreativeModel;
use App\Models\ChannelFeedCreativeLogModel;
use App\Models\ChannelFeedCreativeModel;
use Illuminate\Support\Facades\DB;

class ChannelFeedCreativeService extends BaseService
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
            'creative_feed_ids' => 'required|array',
            'channel' => 'required',
            'platform' => 'required'
        ]);

        Functions::hasEnum(PlatformEnum::class, $data['platform']);

        DB::beginTransaction();

        try{
            foreach($data['creative_feed_ids'] as $creativeFeedId){
                $this->update([
                    'creative_feed_id' => $creativeFeedId,
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
        $channelCreativeModel = new ChannelFeedCreativeModel();
        $channelCreative = $channelCreativeModel->where('feed_creative_id', $data['feed_creative_id'])
            ->where('platform', $data['platform'])
            ->first();

        $flag = $this->buildFlag($channelCreative);
        if(empty($channelCreative)){
            $channelCreative = new ChannelFeedCreativeModel();
        }

        $channelCreative->feed_creative_id = $data['feed_creative_id'];
        $channelCreative->channel_id = $data['channel_id'];
        $channelCreative->platform = $data['platform'];
        $channelCreative->extends = $data['extends'];
        $ret = $channelCreative->save();
        if($ret && !empty($channelCreative->feed_creative_id) && $flag != $this->buildFlag($channelCreative)){
            $this->createChannelAdLog([
                'channel_feed_creative_id' => $channelCreative->id,
                'feed_creative_id' => $data['feed_creative_id'],
                'channel_id' => $data['channel_id'],
                'platform'   => $data['platform'],
                'extends'    => $data['extends']
            ]);
        }

        return $ret;
    }

    /**
     * @param $channelCreative
     * @return string
     * 构建标识
     */
    protected function buildFlag($channelCreative){
        $adminId = !empty($channelCreative->extends->channel->admin_id) ? $channelCreative->extends->channel->admin_id : 0;
        if(empty($channelCreative)){
            $flag = '';
        }else{
            $flag = implode("_", [
                $channelCreative->feed_creative_id,
                $channelCreative->channel_id,
                $channelCreative->platform,
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
        $channelFeedCreativeLogModel = new ChannelFeedCreativeLogModel();
        $channelFeedCreativeLogModel->channel_feed_creative_id = $data['channel_feed_creative_id'];
        $channelFeedCreativeLogModel->feed_creative_id = $data['feed_creative_id'];
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

            $baiDuFeedCreatives = (new BaiDuFeedCreativeModel())
                ->where('id','>',$lastMaxId)
//                ->whereBetween('addtime', [$startTime, $endTime])
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


                if(!empty($param['channel_id'])){
                    $channel = $unionApiService->apiReadChannel(['id' => $param['channel_id']]);
                    $channelExtends = $channel['channel_extends'] ?? [];
                    $channel['admin_id'] = $channelExtends['admin_id'] ?? 0;
                    unset($channel['extends']);
                    unset($channel['channel_extends']);

                    $this->update([
                        'feed_creative_id' => $baiDuFeedCreative->id,
                        'channel_id' => $param['channel_id'],
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
