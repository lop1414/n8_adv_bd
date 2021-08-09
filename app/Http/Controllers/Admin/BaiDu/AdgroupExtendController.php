<?php

namespace App\Http\Controllers\Admin\BaiDu;

use App\Common\Enums\StatusEnum;
use App\Common\Tools\CustomException;
use App\Models\BaiDu\BaiDuAdgroupExtendModel;
use App\Common\Models\ConvertCallbackStrategyModel;
use App\Models\BaiDu\BaiDuAdgroupModel;
use Illuminate\Http\Request;

class AdgroupExtendController extends BaiDuController
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->model = new BaiDuAdgroupExtendModel();

        parent::__construct();
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws CustomException
     * 批量更新
     */
    public function batchUpdate(Request $request){
        $this->validRule($request->post(), [
            'adgroup_ids' => 'required|array',
            'convert_callback_strategy_id' => 'required',
        ]);
        $adgroupIds = $request->post('adgroup_ids');
        $convertCallbackStrategyId = $request->post('convert_callback_strategy_id');

        // 回传规则是否存在
        $convertCallbackStrategyModel = new ConvertCallbackStrategyModel();
        $strategy = $convertCallbackStrategyModel->find($convertCallbackStrategyId);
        if(empty($strategy)){
            throw new CustomException([
                'code' => 'NOT_FOUND_CONCERT_CALLBACK_STRATEGY',
                'message' => '找不到对应回传策略',
            ]);
        }

        if($strategy->status != StatusEnum::ENABLE){
            throw new CustomException([
                'code' => 'CONCERT_CALLBACK_STRATEGY_IS_NOT_ENABLE',
                'message' => '该回传策略已被禁用',
            ]);
        }

        $adgroups = [];
        foreach($adgroupIds as $adgroupId){
            $adgroup = BaiDuAdgroupModel::find($adgroupId);
            if(empty($adgroup)){
                throw new CustomException([
                    'code' => 'NOT_FOUND_AD',
                    'message' => "找不到该计划{{$adgroup}}",
                ]);
            }
            $adgroups[] = $adgroup;
        }

        foreach($adgroups as $adgroup){
            $oceanAdExtend = BaiDuAdgroupExtendModel::find($adgroup->id);

            if(empty($oceanAdExtend)){
                $oceanAdExtend = new BaiDuAdgroupExtendModel();
                $oceanAdExtend->adgroup_id = $adgroup->id;
            }

            $oceanAdExtend->convert_callback_strategy_id = $convertCallbackStrategyId;
            $oceanAdExtend->save();
        }

        return $this->success();
    }
}
