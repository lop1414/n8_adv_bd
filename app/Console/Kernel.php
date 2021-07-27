<?php

namespace App\Console;

use App\Common\Console\Queue\QueueClickCommand;
use App\Console\Commands\BaiDu\BaiDuSyncCommand;
use App\Console\Commands\SyncChannelCreativeCommand;
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
        SyncChannelCreativeCommand::class,

        // 队列
        QueueClickCommand::class,
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

        // 同步渠道-创意
        $schedule->command('sync_channel_creative --date=today')->cron('*/2 * * * *');
    }
}
