<?php
namespace App\Http\Controllers\Admin\BaiDu;


use App\Common\Models\ConvertCallbackStrategyModel;
use App\Models\BaiDu\BaiDuAdgroupModel;

class BaiDuAdgroupController extends BaiDuController
{

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->model = new BaiDuAdgroupModel();

        parent::__construct();
    }



    /**
     * 分页列表预处理
     */
    public function selectPrepare(){

        parent::selectPrepare();
        $this->curdService->selectQueryBefore(function(){
            $this->curdService->customBuilder(function($builder){
                // 筛选渠道
                $channelId = $this->curdService->requestData['channel_id'] ?? '';
                if($channelId){
                    $builder->whereRaw("id IN (
                        SELECT adgroup_feed_id FROM channel_adgroups
                            WHERE channel_id = {$channelId}
                    )");
                }
            });
        });
        $this->curdService->selectQueryAfter(function(){

            foreach ($this->curdService->responseData['list'] as $item){
                if(!empty($item->baidu_adgroup_extends)){
                    $item->convert_callback_strategy = ConvertCallbackStrategyModel::find($item->baidu_adgroup_extends->convert_callback_strategy_id);
                }else{
                    $item->convert_callback_strategy = null;
                }
                $item->channel_adgroup;
                $item->baidu_campaign;
                $item->baidu_account;
                $item->admin_name = $this->adminMap[$item->baidu_account->admin_id]['name'];
            }
        });

    }


    /**
     * 列表预处理
     */
    public function getPrepare(){
        parent::getPrepare();

    }

}
