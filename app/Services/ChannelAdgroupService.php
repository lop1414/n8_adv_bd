<?php

namespace App\Services;

use App\Common\Enums\AdvAliasEnum;
use App\Common\Enums\PlatformEnum;
use App\Common\Helpers\Advs;
use App\Common\Helpers\Functions;
use App\Common\Services\BaseService;
use App\Common\Services\SystemApi\UnionApiService;
use App\Common\Tools\CustomException;
use App\Models\BaiDu\BaiDuAccountModel;
use App\Models\BaiDu\BaiDuAdgroupModel;
use App\Models\BaiDu\BaiDuCreativeModel;
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
            'adgroup_ids' => 'required|array',
            'channel' => 'required',
            'platform' => 'required'
        ]);

        Functions::hasEnum(PlatformEnum::class, $data['platform']);

        DB::beginTransaction();

        try{
            foreach($data['adgroup_ids'] as $adgroupId){
                $this->update([
                    'adgroup_id' => $adgroupId,
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
        $channelAdgroup = $channelAdgroupModel->where('adgroup_id', $data['adgroup_id'])
            ->where('platform', $data['platform'])
            ->first();

        $flag = $this->buildFlag($channelAdgroup);
        if(empty($channelAdgroup)){
            $channelAdgroup = new ChannelAdgroupModel();
        }

        $channelAdgroup->adgroup_id = $data['adgroup_id'];
        $channelAdgroup->channel_id = $data['channel_id'];
        $channelAdgroup->platform = $data['platform'];
        $channelAdgroup->extends = $data['extends'];
        $ret = $channelAdgroup->save();
        if($ret && !empty($channelAdgroup->adgroup_id) && $flag != $this->buildFlag($channelAdgroup)){
            $this->createChannelAdLog([
                'channel_adgroup_id' => $channelAdgroup->id,
                'adgroup_id' => $data['adgroup_id'],
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
                $channelAdgroup->adgroup_id,
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
        $channelFeedCreativeLogModel->channel_adgroup_id = $data['channel_adgroup_id'];
        $channelFeedCreativeLogModel->adgroup_id = $data['adgroup_id'];
        $channelFeedCreativeLogModel->channel_id = $data['channel_id'];
        $channelFeedCreativeLogModel->platform = $data['platform'];
        $channelFeedCreativeLogModel->extends = $data['extends'];
        return $channelFeedCreativeLogModel->save();
    }

    /**
     * @param $param
     * @return array
     * @throws CustomException
     * 列表
     */
    public function select($param){
        $this->validRule($param, [
            'start_datetime' => 'required',
            'end_datetime' => 'required',
        ]);
        Functions::timeCheck($param['start_datetime']);
        Functions::timeCheck($param['end_datetime']);
        $channelAdgroupModel = new ChannelAdgroupModel();
        $channelAdgroups = $channelAdgroupModel->whereBetween('updated_at', [$param['start_datetime'], $param['end_datetime']])->get();

        $distinct = $data = [];
        foreach($channelAdgroups as $channelAdgroup){
            if(empty($distinct[$channelAdgroup['channel_id']])){
                // 推广单元
                $baiduAdgroup = BaiDuAdgroupModel::find($channelAdgroup['adgroup_id']);
                if(empty($baiduAdgroup)){
                    continue;
                }

                // 账户
                $baiduAccount = (new BaiDuAccountModel())->where('account_id', $baiduAdgroup['account_id'])->first();
                if(empty($baiduAccount)){
                    continue;
                }

                $data[] = [
                    'channel_id' => $channelAdgroup['channel_id'],
                    'ad_id' => $channelAdgroup['ad_id'],
                    'ad_name' => $baiduAdgroup['name'],
                    'account_id' => $baiduAdgroup['account_id'],
                    'account_name' => $baiduAccount['name'],
                    'admin_id' => $baiduAccount['admin_id'],
                ];
                $distinct[$channelAdgroup['channel_id']] = 1;
            }
        }

        return $data;
    }




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
                ->whereBetween('updated_at', [$startTime, $endTime])
                ->skip(0)
                ->take(1000)
                ->orderBy('id')
                ->get();

            $keyword = 'sign='. Advs::getAdvClickSign(AdvAliasEnum::BD);

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
                        'adgroup_id' => $baiDuFeedCreative->adgroup_id,
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
