<?php

namespace App\Services\BaiDu;

use App\Common\Services\BaseService;
use App\Models\BaiDuAccountModel;
use App\Sdks\BaiDu\BaiDu;


class BaiDuService extends BaseService
{

    /**
     * @var BaiDu
     * 句柄
     */
    public $sdk;

    /**
     * @var
     * 管家账户信息
     */
    protected $manageAccount;


    /**
     * @var
     * 账户ID映射
     */
    protected $accountMap;




    /**
     * BaiDuService constructor.
     * @param array $manageAccount
     */
    public function __construct($manageAccount = []){
        parent::__construct();

        if(!empty($manageAccount)) $this->setManageAccount($manageAccount);

    }



    public function setSdk($accountName,$password,$token){}



    public function setManageAccount($info){
        $this->manageAccount = $info;
        $this->setSdk($this->manageAccount['name'],$this->manageAccount['password'],$this->manageAccount['token']);
        return $this;
    }


    /**
     * @param $name
     * @return mixed
     * 通过账户名称
     */
    public function getAccountByName($name){
        if(!isset($this->accountIdMap[$name])){
            $info = (new BaiDuAccountModel())->where('name',$name)->first();
            $this->setAccountMap($info);
        }
        return $this->accountMap[$name];
    }


    public function setAccountMap($info){
        $this->accountMap[$info['name']] = $info;
    }



    /**
     * @param $modelClass
     * @param $data
     * @param int $number
     * @return bool
     * 批量保存
     */
    public function batchSave($modelClass,$data,$number = 20){
        $model = new $modelClass();
        $model->chunkInsertOrUpdate($data, $number, $model->getTable(), $model->getTableColumnsWithPrimaryKey());
        return true;
    }
}
