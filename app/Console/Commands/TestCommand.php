<?php

namespace App\Console\Commands;

use App\Common\Console\BaseCommand;
use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Services\BaiDu\Report\BaiDuCreativeReportService;
use App\Services\BaiDu\Report\BaiDuReportService;
use App\Services\ChannelAdgroupService;

class TestCommand extends BaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'test  {--date=}';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '测试';

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
        (new BaiDuCreativeReportService())->sync($param);
    }

}
