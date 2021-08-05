<?php

namespace App\Http\Controllers\Admin;

use App\Common\Controllers\Admin\AdminController;
use App\Common\Models\ClickModel;
use App\Common\Tools\CustomException;
use App\Enums\BaiDu\BaiDuSubjectTypeEnum;
use App\Services\AdvConvertCallbackService;
use Illuminate\Http\Request;

class ClickController extends AdminController
{
    /**
     * @var string
     * 默认排序字段
     */
    protected $defaultOrderBy = 'click_at';

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->model = new ClickModel();

        parent::__construct();
    }

    /**
     * 列表预处理
     */
    public function selectPrepare(){
        $this->curdService->selectQueryBefore(function(){
            $this->curdService->customBuilder(function($builder){
                // 24小时内
                $datetime = date('Y-m-d H:i:s', strtotime("-24 hours"));
                $builder->where('click_at', '>', $datetime);
            });
        });
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CustomException
     * 回传
     */
    public function callback(Request $request){

        $this->validRule($request->post(), [
            'event_type' => 'required',
            'subject_type' => 'required',
        ]);

        $eventType = $request->post('event_type');
        $subjectType = $request->post('subject_type');

        $advConvertCallbackService = new AdvConvertCallbackService();
        $eventTypeMap = $advConvertCallbackService->getEventTypeMap();
        $eventTypes = array_values($eventTypeMap);
        if(!in_array($eventType, $eventTypes)){
            throw new CustomException([
                'code' => 'UNKNOWN_EVENT_TYPE',
                'message' => '非合法回传类型',
            ]);
        }

        if($subjectType == BaiDuSubjectTypeEnum::WEB){
            $this->validRule($request->post(), [
                'link' => 'required',
                'account_id' => 'required'
            ]);
            $link = trim($request->post('link'));
            $click = new ClickModel();
            $click->link = $link;
            $click->account_id = $request->post('account_id');
        }else{

            throw new CustomException([
                'code' => 'NOT_SUPPORTED',
                'message' => '不支持该类型回传',
            ]);

        }

        $ret = $advConvertCallbackService->runCallback($click, $eventType);

        return $this->ret($ret);
    }
}
