<?php

namespace App\Services\BaiDu;

use App\Common\Enums\StatusEnum;
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


    public function getAccount($accountIds = [], $status = StatusEnum::ENABLE){

        $parentAccountList = (new BaiDuAccountModel())
            ->where('parent_id',0)
            ->get();

        $list = [];

        if(!$parentAccountList->isEmpty()){
            $list = $parentAccountList->toArray();
        }

        foreach ($list as &$parentAccount){
            $subAccountList = (new BaiDuAccountModel())
                ->where('parent_id',$parentAccount['id'])
                ->where('status',$status)
                ->when($accountIds && !in_array($parentAccount['account_id'],$accountIds),function ($query) use ($accountIds){
                    return $query->whereIn('account_id',$accountIds);
                })
                ->get();
            if(!$subAccountList->isEmpty()){
                $parentAccount['sub_account'] = $subAccountList->toArray();
            }
        }
        return $list;
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
