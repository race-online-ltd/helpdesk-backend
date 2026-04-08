<?php

use App\Events\CommentEvent;
use App\Events\CommentNotification;
use App\Http\Controllers\v1\Corn\SLAJobController;
use App\Http\Controllers\v1\Settings\UserLoginController;
use App\Http\Controllers\v1\Ticket\TicketController;
use App\Http\Controllers\v1\Settings\EmailController;
use App\Http\Controllers\v1\Report\ReportsController;
use App\Models\Ticket;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/', function () {
    return view('welcome');
});


Route::get('frsla', [SLAJobController::class, 'firstResponseSlaCheck']);
Route::get('clientsrvsla', [SLAJobController::class, 'serviceTimeClientSlaCheck']);
Route::get('subcatsrvsla', [SLAJobController::class, 'serviceTimeSubCategorySlaCheck']);



Route::get('dhakacolo/customers', [UserLoginController::class, 'dhakaColoCustomers']);

Route::get('earth/customers', [UserLoginController::class, 'earthCustomers']);
Route::get('race/customers', [UserLoginController::class, 'raceCustomers']);



Route::get('/token', [UserLoginController::class, 'test']);
Route::get('/apitest', [UserLoginController::class, 'testApi']);


Route::get('/getreport', [ReportsController::class, 'reportsOutput']);
Route::get('/getreport2', [ReportsController::class, 'reportsOutput2']);
Route::get('/getdashboardmiddle', [ReportsController::class, 'statisticsWithGraph']);
Route::get('/ticketstatistics', [ReportsController::class, 'statistics']);

Route::get('/summarytest', [ReportsController::class, 'summaryTest']);

Route::get('/ticketstatdashboard', [ReportsController::class, 'statisticsWithGraph']);

Route::get('/getreportfinal', [ReportsController::class, 'getReportsOutput']);

Route::get('/dashboardteamreport', [ReportsController::class, 'teamReportForDashboard']);

Route::get('/dashboarddivisionreport', [ReportsController::class, 'divisionReportForDashboard']);

Route::get('/dashboarddepartmentreport', [ReportsController::class, 'departmentReportForDashboard']);

Route::get('/tcketcycle', [ReportsController::class, 'ticketLifeCycle']);

// Route::get('/send-email', [EmailController::class, 'sendWelcomeEmail']);

Route::get('/send-email', [EmailController::class, 'sendEmailNotification']);



Route::get('clientfrsla', [SLAJobController::class, 'isViolatedClientFirstResponseTime']);
Route::get('clientfresc', [SLAJobController::class, 'escalatedClientFirstResponseTime']);
Route::get('clientsrvtimesla', [SLAJobController::class, 'isViolatedClientServiceTime']);
Route::get('clientsrvtimeesc', [SLAJobController::class, 'escalatedClientServiceTime']);

Route::get('teamfrsla', [SLAJobController::class, 'isViolatedTeamFirstResponseTime'])->name('teamfrsla');
Route::get('teamfresc', [SLAJobController::class, 'escalatedTeamFirstResponseTime'])->name('teamfresc');
Route::get('teamsrvtimesla', [SLAJobController::class, 'isViolatedTeamServiceTime'])->name('teamsrvtimesla');
Route::get('teamsrvtimeesc', [SLAJobController::class, 'escalatedTeamServiceTime'])->name('teamsrvtimeesc');

Route::get('s', [TicketController::class, 'statusChanged']);
Route::get('a', [TicketController::class, 'assignTeam']);
Route::get('c', [TicketController::class, 'comment']);

Route::get('/ticket-details', [TicketController::class, 'getTicketDetails']);

Route::get('test', [UserLoginController::class, 'oracleTest']);

Route::get('active', [UserLoginController::class, 'active']);

Route::get('/test-event', function () {
    event(new \App\Events\CommentEvent(['message' => 'Hello from Laravel!']));
    return 'Event triggered!';
});
Route::get('test', [SLAJobController::class, 'serviceTimeSubCategorySlaCheck']);

Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return "Cache Cleared";
});
