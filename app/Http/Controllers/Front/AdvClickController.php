<?php

namespace App\Http\Controllers\Front;

use App\Common\Controllers\Front\ClickController;
use App\Common\Enums\AdvAliasEnum;
use App\Services\AdvPageClickService;
use Illuminate\Http\Request;

class AdvClickController extends ClickController
{
    public function __construct(){
        parent::__construct(AdvAliasEnum::BD);
    }

    /**
     * @return false|string
     * 广告商响应
     */
    protected function advResponse(){
        return json_encode([
            'code' => 0,
            'message' => 'SUCCESS'
        ]);
    }




    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Common\Tools\CustomException
     * 转发点击
     */
    public function forward(Request $request){
        $data = $request->all();

        // 校验广告商点击签名
        $this->checkAdvClickSign($data);

        if(empty($data['click_at'])){
            $data['click_at'] = TIMESTAMP .'000';
        }

        // 队列
        $advForwardClickService = new AdvPageClickService();
        $data = $advForwardClickService->dataFilter($data);
        $advForwardClickService->push($data);

        // 响应
        return $this->success();

    }
}
