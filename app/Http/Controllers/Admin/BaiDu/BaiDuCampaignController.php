<?php
namespace App\Http\Controllers\Admin\BaiDu;


use App\Models\BaiDu\BaiDuCampaignModel;

class BaiDuCampaignController extends BaiDuController
{

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->model = new BaiDuCampaignModel();

        parent::__construct();
    }



    /**
     * 分页列表预处理
     */
    public function selectPrepare(){
        parent::selectPrepare();
        $this->curdService->selectQueryAfter(function(){
            foreach ($this->curdService->responseData['list'] as $item){
                $item->baidu_account;
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
