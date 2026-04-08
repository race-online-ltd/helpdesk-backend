<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use Illuminate\Support\Facades\Http;

class CallSLAJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'call:slajobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {



        // $teamFrResponse = Http::get(route('teamfrsla'));

        // if ($teamFrResponse->successful()) {
        //     $this->info('SLA job executed successfully.');
        // } else {
        //     $this->error('Failed to execute SLA job.');
        // }

        // $teamFrResponseEsc = Http::get(route('teamfresc'));

        // if ($teamFrResponseEsc->successful()) {
        //     $this->info('Team first response escalation SLA job executed successfully.');
        // } else {
        //     $this->error('Failed to execute Team first response escalation SLA job.');
        // }

        // $teamSrvResponse = Http::get(route('teamsrvtimesla'));

        // if ($teamSrvResponse->successful()) {
        //     $this->info('SLA Srv timejob executed successfully.');
        // } else {
        //     $this->error('Failed to execute SLA Srv Time job.');
        // }

        // $teamSrvResponseEsc = Http::get(route('teamsrvtimeesc'));

        // if ($teamSrvResponseEsc->successful()) {
        //     $this->info('Team Srv Time response escalation SLA job executed successfully.');
        // } else {
        //     $this->error('Failed to execute Team Srv Time response escalation SLA job.');
        // }

        // $this->info('Hi, the SLA jobs command ran successfully!');
    }
}
