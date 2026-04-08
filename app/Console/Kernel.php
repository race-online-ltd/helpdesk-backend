<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {


        // $schedule->call(function () {
        //     $controller = new \App\Http\Controllers\v1\Corn\SLAJobController();
        //     $controller->isViolatedTeamFirstResponseTime();
        // })->everyTwoMinutes();

        // $schedule->call(function () {
        //     $controller = new \App\Http\Controllers\v1\Corn\SLAJobController();
        //     $controller->escalatedTeamFirstResponseTime();
        // })->everyTwoMinutes();

        // $schedule->call(function () {
        //     $controller = new \App\Http\Controllers\v1\Corn\SLAJobController();
        //     $controller->isViolatedTeamServiceTime();
        // })->everyTwoMinutes();

        // $schedule->call(function () {
        //     $controller = new \App\Http\Controllers\v1\Corn\SLAJobController();
        //     $controller->escalatedTeamServiceTime();
        // })->everyTwoMinutes();

        // $schedule->call(function () {
        //     $controller = new \App\Http\Controllers\v1\UtilityController();
        //     $controller->fetchAndInsert();
        // })->dailyAt('02:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
