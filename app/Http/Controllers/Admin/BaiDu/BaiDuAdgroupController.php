<?php
namespace App\Http\Controllers\Admin\BaiDu;


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

    }


    /**
     * 列表预处理
     */
    public function getPrepare(){
        parent::getPrepare();

    }

}
