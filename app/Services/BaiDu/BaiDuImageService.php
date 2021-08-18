<?php

namespace App\Services\BaiDu;



use App\Models\BaiDu\BaiDuAccountModel;
use App\Models\BaiDu\BaiDuImageModel;

class BaiDuImageService extends BaiDuService
{

    protected $baiduAccountService;


    /**
     * BaiDuService constructor.
     * @param array $manageAccount
     */
    public function __construct($manageAccount = []){
        parent::__construct($manageAccount);

        $this->baiduAccountService = new BaiDuAccountService();
    }



    public function upload($accountId,$file){
        $account = $this->baiduAccountService->read($accountId);
        $this->sdk->setTargetAccountName($account->name);
        $data = $this->sdk->uploadImage($file);
        dd($data);
    }


    public function read($accountName,$id){
       $this->sdk->setTargetAccountName($accountName);
       $data = $this->sdk->readImage($id);
       if(empty($data[0]['listData'])){
            return [];
       }

       return $data[0]['listData'][0];
    }


    public function updateInfo($accountId,$id){
        $account = $this->baiduAccountService->read($accountId);
        $info = $this->read($account->name,$id);
        if(empty($info)) return ;

        $image = (new BaiDuImageModel())->find($id);
        if(empty($image)){
            $image = new BaiDuImageModel();
            $image->id = $info['imageId'];
        }
        $image->name = $info['imageName'];
        $image->size = $info['size'];
        $image->width = $info['width'];
        $image->height = $info['height'];
        $image->source_type = $info['sourceType'];
        $image->is_collect = $info['isCollect'];
        $image->url = $info['url'];
        $image->format = $info['format'];
        $image->signature = $info['signature'];
        $image->create_time = date('Y-m-d H:i:s',strtotime($info['addTime']));
        $image->update_time = date('Y-m-d H:i:s',strtotime($info['modTime']));
        $image->save();
        return $image;
    }
}
