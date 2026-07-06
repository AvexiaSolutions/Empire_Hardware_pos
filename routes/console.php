<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Setting;
use Illuminate\Support\Facades\Schema;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

try {
    if (Schema::hasTable('settings')) {
        $reportTime = Setting::get('report_time') ?: '18:59';
        
        Schedule::command('app:check-low-stock')->dailyAt($reportTime);
        Schedule::command('app:send-daily-report')->dailyAt($reportTime);
        Schedule::command('app:database-backup')->dailyAt($reportTime);
    }
} catch (\Exception $e) {
    // Ignore db connection issues during setup
}
