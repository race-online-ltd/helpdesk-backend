<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;

class SendCustomerSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */

    protected $data;
    public function __construct($data) { $this->data = $data; }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $controller = app(\App\Http\Controllers\SendSmsController::class);
        $request = new Request($this->data);
        $controller->checkAndSendSMSForPartner($request);
    }
}
