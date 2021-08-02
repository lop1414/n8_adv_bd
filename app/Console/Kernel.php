<?php

namespace App\Console;

use App\Common\Console\ConvertCallbackCommand;
use App\Common\Console\Queue\QueueClickCommand;
use App\Console\Commands\BaiDu\BaiDuSyncCommand;
use App\Console\Commands\SyncChannelAdgroupCommand;
use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        BaiDuSyncCommand::class,
        SyncChannelAdgroupCommand::class,

        // 队列
        QueueClickCommand::class,

        // 转化回传
        ConvertCallbackCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // 队列
        $schedule->command('queue:click')->cron('* * * * *');

        // 同步渠道-推广单元
        $schedule->command('sync_channel_adgroup --date=today')->cron('*/2 * * * *');


        // 转化上报
        $schedule->command('convert_callback')->cron('* * * * *');

        // 百度同步

        // 推广计划
        $schedule->command(' artisan baidu:sync --type=campaign')->cron('*/15 * * * *');
        // 推广单元
        $schedule->command(' artisan baidu:sync --type=adgroup')->cron('*/15 * * * *');
        // 创意
        $schedule->command(' artisan baidu:sync --type=creative')->cron('*/15 * * * *');
    }
}
