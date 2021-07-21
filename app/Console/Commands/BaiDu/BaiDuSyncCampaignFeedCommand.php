<?php

namespace App\Console\Commands\BaiDu;

use App\Common\Console\BaseCommand;
use App\Services\BaiDu\BaiDuAccountService;
use App\Services\BaiDu\BaiDuCampaignService;

class BaiDuSyncCampaignFeedCommand extends BaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'baidu:sync_campaign_feed {--account_ids=} {--status=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '同步百度信息流计划';

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

        // 账户
        if(!empty($param['account_ids'])){
            $param['account_ids'] = explode(",", $param['account_ids']);
        }


        $service = new BaiDuCampaignService();
        $option = ['log' => true];
        $this->lockRun(
            [$service, 'syncFeed'],
            'baidu|sync_feed_campaign',
            3600,
            $option,
            $param
        );
    }
}
