<?php

namespace App\Console\Commands;

use App\Common\Console\BaseCommand;
use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Services\ChannelAdgroupService;

class SyncChannelAdgroupCommand extends BaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'sync_channel_adgroup  {--date=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '同步渠道推广单元关联';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(){
        parent::__construct();
    }


    /**
     * @throws CustomException
     */
    public function handle(){
        $param = $this->option();

        $lockKey = 'sync_channel_adgroup_'. $param['date'];

        $option = ['log' => true];
        $this->lockRun(
            [$this, 'exec'],
            $lockKey,
            43200,
            $option,
            $param
        );
    }

    /**
     * @param $param
     * @return bool
     * @throws CustomException
     * 执行
     */
    public function exec($param){
        // 获取日期范围
        $dateRange = Functions::getDateRange($param['date']);
        $dateList = Functions::getDateListByRange($dateRange);

        $channelCreativeService = new ChannelAdgroupService();
        foreach($dateList as $date){
            $channelCreativeService->sync([
                'date' => $date,
            ]);
        }

        return true;
    }
}
