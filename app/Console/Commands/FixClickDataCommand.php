<?php

namespace App\Console\Commands;

use App\Common\Console\BaseCommand;
use App\Common\Tools\CustomException;
use App\Services\FixClickDataService;


class FixClickDataCommand extends BaseCommand
{
    /**
     * 命令行执行命令
     * @var string
     */
    protected $signature = 'fix_click_data';

    /**
     * 命令描述
     *
     * @var string
     */
    protected $description = '修正点击数据';

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

        $lockKey = 'fix_click_data';

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
     * 执行
     */
    public function exec($param){

        (new FixClickDataService())->index();
        return true;
    }
}
