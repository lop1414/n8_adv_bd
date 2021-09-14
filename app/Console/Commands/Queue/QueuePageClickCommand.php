<?php

namespace App\Console\Commands\Queue;

use App\Common\Console\BaseCommand;
use App\Services\AdvClickService;
use App\Services\AdvPageClickService;

class QueuePageClickCommand extends BaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'queue:page_click';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '广告页面转发点击队列';

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
        $advClickService = new AdvPageClickService();
        $option = ['log' => true];
        $this->lockRun(
            [$advClickService, 'pull'],
            "queue_page_click",
            43200,
            $option
        );
    }
}
