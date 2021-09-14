<?php

namespace App\Console;

use App\Common\Console\ConvertCallbackCommand;
use App\Common\Console\Queue\QueueClickCommand;
use App\Console\Commands\BaiDu\BaiDuSyncCommand;
use App\Console\Commands\BaiDu\Report\BaiDuSyncAccountReportCommand;
use App\Console\Commands\BaiDu\Report\BaiDuSyncCreativeReportCommand;
use App\Console\Commands\Queue\QueuePageClickCommand;
use App\Console\Commands\SyncChannelAdgroupCommand;
use App\Console\Commands\TestCommand;
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
        TestCommand::class,
        BaiDuSyncCommand::class,
        SyncChannelAdgroupCommand::class,

        // 队列
        QueueClickCommand::class,
        QueuePageClickCommand::class,

        // 转化回传
        ConvertCallbackCommand::class,

        // 报表
        BaiDuSyncCreativeReportCommand::class,
        BaiDuSyncAccountReportCommand::class,
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
        $schedule->command('queue:page_click')->cron('* * * * *');

        // 同步渠道-推广单元
        $schedule->command('sync_channel_adgroup --date=today')->cron('*/2 * * * *');


        // 转化上报
        $schedule->command('convert_callback')->cron('* * * * *');

        // 百度同步任务
        $schedule->command('baidu:sync --type=campaign')->cron('*/20 * * * *');
        $schedule->command('baidu:sync --type=adgroup')->cron('*/20 * * * *');
        $schedule->command('baidu:sync --type=creative')->cron('*/20 * * * *');

        // 百度报表同步
        $schedule->command('baidu:sync_account_report --date=today --running=1')->cron('*/5 * * * *');
        $schedule->command('baidu:sync_account_report --date=yesterday --key_suffix=yesterday')->cron('25-30 9 * * *');

        // 百度创意报表同步
        $schedule->command('baidu:sync_creative_report --date=today --running=1 --run_by_account_cost=1')->cron('*/5 * * * *');
        $schedule->command('baidu:sync_creative_report --date=yesterday --key_suffix=yesterday')->cron('25-30 9 * * *');

    }
}
