<?php

namespace App\Services;

use App\Common\Models\ClickModel;
use App\Common\Services\BaseService;
use App\Enums\FixStatusEnum;
use App\Models\PageClickModel;

class FixClickDataService extends BaseService
{

    public function index(){
        $pageClickModel = new PageClickModel();
        $clickModel = new ClickModel();
        $lastId = 0;
        do{
            $list = $pageClickModel
                ->where('fix_status',FixStatusEnum::WAITING)
                ->where('id','>',$lastId)
                ->skip(0)
                ->take(1000)
                ->get();

            foreach ($list as $item){
                $lastId = $item['id'];
                $click = $clickModel->where('bd_vid',$item['bd_vid'])->first();
                if(empty($click)){
                    $diff = time() - strtotime($item->click_at);
                    if($diff >= 60*60*2){
                        // 修改状态
                        $item->fix_status = FixStatusEnum::FAIL;
                        $item->save();
                    }
                    continue;
                }

                $extends = $click->extends;
                if($click->ip != $item['ip']){
                    $extends->old_ip =  $click->ip;
                    $click->ip = $item['ip'];
                }

                if($click->ua != $item['ua']){
                    $extends->old_ua =  $click->ua;
                    $click->ua = $item['ua'];
                }

                $click->extends = $extends;
                $click->save();
                // 修改状态
                $item->fix_status = FixStatusEnum::SUCCESS;
                $item->save();
            }
        }while(!$list->isEmpty());
    }

}
