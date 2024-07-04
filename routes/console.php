<?php

use App\Console\Commands\DeleteInactiveUser;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
// Artisan::command('inspire', function () {
//     $this->comment(Inspiring::quote());
// })->purpose('Display an inspiring quote')->hourly();


use Illuminate\Support\Facades\Schedule;
 
Schedule::call(function () {
    Log::info("Working");
    DB::table('users')->whereActive(false)->delete();
})->dailyAt('22:10');
