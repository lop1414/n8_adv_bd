<?php

namespace App\Console\Commands\BaiDu;

use App\Common\Console\BaseCommand;
use App\Common\Tools\CustomException;
use App\Services\BaiDu\BaiDuCreativeService;
use App\Services\BaiDu\BaiDuAccountService;
use App\Services\BaiDu\BaiDuAdgroupService;
use App\Services\BaiDu\BaiDuCampaignService;

class BaiDuSyncCommand extends BaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'baidu:sync {--type=} {--account_ids=} {--status=} {--multi_chunk_size=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '同步百度信息';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * @throws \App\Common\Tools\CustomException
     * 处理
     */
    public function handle(){
        $param = $this->option();

        if(empty($param['type'])){
            throw new CustomException([
                'code' => 'NO_TYPE_PARAM',
                'message' => 'type 必传',
            ]);
        }

        // 账户
        if(!empty($param['account_ids'])){
            $param['account_ids'] = explode(",", $param['account_ids']);
        }

        $service = $this->getServices($param['type']);

        $option = ['log' => true];
        $this->lockRun(
            [$service, 'sync'],
            'baidu|sync|'.$param['type'],
            3600 * 3,
            $option,
            $param
        );
    }



    public function getServices($type){
        switch ($type){
            case 'account_feed':
                echo "同步信息流账户\n";
                $service = new BaiDuAccountService();
                break;
            case 'campaign':
                echo "同步信息流计划\n";
                $service = new BaiDuCampaignService();
                break;
            case 'adgroup':
                echo "同步信息流推广单元\n";
                $service = new BaiDuAdgroupService();
                break;
            case 'creative':
                echo "同步信息流创意\n";
                $service = new BaiDuCreativeService();
                break;
            default:
                throw new CustomException([
                    'code' => 'TYPE_PARAM_INVALID',
                    'message' => 'type 无效',
                ]);
        }
       return $service;
    }
}
