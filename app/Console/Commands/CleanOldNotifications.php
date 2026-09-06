<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;
use Carbon\Carbon;

class CleanOldNotifications extends Command
{
    protected $signature = 'notifications:clean';

    protected $description = 'Delete old read notifications';


    public function handle()
    {
        $deleted = Notification::where(
            'is_read',
            1
        )
        ->where(
            'created_at',
            '<',
            Carbon::now()->subDays(30)
        )
        ->delete();


        $this->info(
            $deleted .
            ' old notifications deleted.'
        );


        return Command::SUCCESS;
    }
}