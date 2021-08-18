<?php

namespace App\Http\Controllers;

use App\Common\Controllers\Front\FrontController;


use App\Models\BaiDu\BaiDuAccountModel;
use App\Models\BaiDu\BaiDuImageModel;
use App\Services\BaiDu\BaiDuImageService;
use Illuminate\Http\Request;

class TestController extends FrontController
{
    /**
     * constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }



    public function test(Request $request){
        $key = $request->input('key');
        if($key != 'aut'){
            return $this->forbidden();
        }

        $manageAccount = (new BaiDuAccountModel())->find('33901781');
        $service = new BaiDuImageService($manageAccount);
        $file = storage_path() ."/app/415.jpg";

        $info = $service->upload(33900411,$file);
        dd($info);
    }




}
