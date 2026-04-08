<?php

namespace App\Http\Controllers\v1\Corn;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Logic\TicketInfo;
use App\Models\EscalateFrResClient;
use App\Models\EscalateFrResSubcategory;
use App\Models\EscalateSrvTimeClient;
use App\Models\EscalateSrvTimeSubcategory;
use App\Models\SlaClient;
use App\Models\SlaSubcategory;
use App\Models\Ticket;
use App\Models\TicketFrTimeClient;
use App\Models\TicketFrTimeClientHistory;
use App\Models\TicketFrTimeEscClient;
use App\Models\TicketFrTimeEscClientHistory;
use App\Models\TicketFrTimeEscTeam;
use App\Models\TicketFrTimeEscTeamHistory;
use App\Models\TicketFrTimeTeam;
use App\Models\TicketFrTimeTeamHistory;
use App\Models\TicketSrvTimeClient;
use App\Models\TicketSrvTimeClientHistory;
use App\Models\TicketSrvTimeEscClient;
use App\Models\TicketSrvTimeEscClientHistory;
use App\Models\TicketSrvTimeEscTeam;
use App\Models\TicketSrvTimeEscTeamHistory;
use App\Models\TicketSrvTimeTeam;
use App\Models\TicketSrvTimeTeamHistory;

use App\Models\OpenTicket;
use App\Models\FirstResSla;
use App\Models\SrvTimeClientSla;
use App\Models\SrvTimeSubcatSla;
use App\Http\Controllers\v1\Settings\EmailController;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SLAJobController extends Controller
{
    public function isViolatedClientFirstResponseTime()
    {


        $slaClientTickets = TicketFrTimeClient::all();
        foreach ($slaClientTickets as $ticket) {

            $current_time = Carbon::now();
            $ticket_created_at = $ticket->created_at;
            $ticketAge = $current_time->diffInMinutes($ticket_created_at);

            if ($ticketAge > $ticket->fr_response_time) {

                //send notification
                $ticket->update([
                    'fr_response_status_id' => 2,
                    'fr_response_status_name' => 'violated',
                ]);

                TicketFrTimeClientHistory::create([
                    'ticket_number' => $ticket->ticket_number,
                    'client_id' => $ticket->client_id,
                    'subcat_id' => $ticket->subcat_id,
                    'fr_response_id' => $ticket->fr_response_id,
                    'fr_response_time' => $ticket->fr_response_time,
                    'fr_response_status_id' => $ticket->fr_response_status_id,
                    'fr_response_status_name' => $ticket->fr_response_status_name,
                ]);



                $checkEscalateStatus = SlaClient::firstWhere([
                    'client_id' => $ticket->client_id,
                    'subcat_id' => $ticket->subcat_id
                ]);

                $escalateData = EscalateFrResClient::firstWhere([
                    'client_id' => $ticket->client_id,
                    'subcat_id' => $ticket->subcat_id
                ]);

                $tickInfo = Ticket::where('ticket_number', $ticket->ticket_number)->first();

                    if ($tickInfo) {
                        // $ticketNoForEmail = [
                        //     'ticket_number' => $tickInfo->ticket_number,
                        //     'subcat_id' => $tickInfo->subcat_id,
                        //     'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                        // ];


                        $ticketNoForEmail = [
                            'ticket_number' => $ticket->ticket_number,
                            'user_id' => $ticket->user_id,
                            'cat_id' => $ticket->cat_id,
                            'subcat_id' => $ticket->subcat_id,
                            'client_id_helpdesk' => $ticket->client_id_helpdesk,
                            'business_entity_id' => $ticket->business_entity_id,
                            'created_at' => $ticket->created_at,
                            'ticketAge' => $ticketAge,
                        ];

                        // $teamId = $tickInfo['team_id'];
                        $teamId = $tickInfo->team_id;

                        $recipient = DB::table('teams')->where('id', $teamId)->value('group_email');


                        $emailTemplate = DB::table('email_templates')
                            ->where('id', 4)
                            ->where('status', 'Active')
                            ->first(['subject', 'content']);


                        $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                        $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);

                        if ($emailResult->status() !== 200) {
                            return ApiResponse::error('Email sending failed', "Error", 500);
                        }

                        if ($teamId) {
                            if ($checkEscalateStatus && $checkEscalateStatus->esc_status == 1) {


                            
                                TicketFrTimeEscClient::create([
                                    'ticket_number' => $ticket->ticket_number,
                                    'client_id' => $ticket->client_id,
                                    'subcat_id' => $ticket->subcat_id,
                                    'team_id' => $teamId,
                                    'escalate_id' => $escalateData->id,
                                    'escalate_level' => $escalateData->level_id,
                                    'escalate_fr_response_time' => $escalateData->notification_min,
                                    'notification_status' => $escalateData->send_email_status,
                                    'status' => 0,
                                    'status_name' => 'started',
                                ]);

                                TicketFrTimeEscClientHistory::create([
                                    'ticket_number' => $ticket->ticket_number,
                                    'client_id' => $ticket->client_id,
                                    'subcat_id' => $ticket->subcat_id,
                                    'team_id' => $teamId,
                                    'escalate_id' => $escalateData->id,
                                    'escalate_level' => $escalateData->level_id,
                                    'escalate_fr_response_time' => $escalateData->notification_min,
                                    'notification_status' => $escalateData->send_email_status,
                                    'status' => 0,
                                    'status_name' => 'started',
                                ]);
                            }
                        }

                    }

                

                $ticket->delete();
            }
        }
    }

    public function escalatedClientFirstResponseTime()
    {

        $slaClientEscTickets = TicketFrTimeEscClient::all();

        foreach ($slaClientEscTickets as $slaClientEscTicket) {

            $tickInfo = Ticket::where('ticket_number', $slaClientEscTicket->ticket_number)->first();


            

            $countEscLavel = EscalateFrResClient::where('client_id', $slaClientEscTicket->client_id)
                ->where('subcat_id', $slaClientEscTicket->subcat_id)
                ->count('level_id');

            for ($m = 1; $m <= $countEscLavel; $m++) {

                $slaEscClientLevelId = TicketFrTimeEscClient::firstWhere([
                    'client_id' => $slaClientEscTicket->client_id,
                    'subcat_id' => $slaClientEscTicket->subcat_id,
                ]);
                $finalEscLevel = $slaEscClientLevelId->escalate_level;
                $finalSubcatId = $slaEscClientLevelId->subcat_id;


                $current_time = Carbon::now();
                $ticket_created_at = $tickInfo->created_at;
                $ticketAge = $current_time->diffInMinutes($ticket_created_at);


                if ($ticketAge > $slaClientEscTicket->escalate_fr_response_time) {
                    //send notification
                    if ($finalEscLevel < 2) {


                        $ticketNoForEmail = [
                            'ticket_number' => $slaEscClientLevelId->ticket_number,
                            'level_id' => $slaEscClientLevelId->level_id,
                            'subcat_id' => $slaEscClientLevelId->subcat_id,
                            'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                            'business_entity_id' => $tickInfo->business_entity_id,
                        ];

                        $agentId = DB::table('escalate_fr_re_agent_clients')
                                        ->where('level_id', $finalEscLevel)
                                        ->where('subcat_id', $finalSubcatId)
                                        ->value('agent_id');
            
                        // $teamId = $tickInfo['team_id'];
                        $teamId = $slaEscClientLevelId->team_id;
            
                        $recipient = DB::table('escalate_fr_re_agent_clients as ec')
                                        ->join('users as u', 'ec.agent_id', '=', 'u.id')
                                        ->where('ec.level_id', $finalEscLevel)
                                        ->where('ec.subcat_id', $finalSubcatId)
                                        ->where('ec.agent_id', $agentId)
                                        ->value('u.email_primary');
            
                        $emailTemplate = DB::table('email_templates')
                            ->where('id', 6)
                            ->where('status', 'Active')
                            ->first(['subject', 'content']);
            
            
                        $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                        $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);
            
                        if ($emailResult->status() !== 200) {
                            return ApiResponse::error('Email sending failed', "Error", 500);
                        }

                        $slaClientEscTicket->update([
                            'status' => 2,
                            'status_name' => 'failed',
                        ]);

                        TicketFrTimeEscClientHistory::create($slaClientEscTicket->toArray());

                        $slaClientEscTicket->update([
                            'escalate_level' => $finalEscLevel + 1,
                        ]);
                    } else {

                        if ($finalEscLevel === $m) {

                            $escalateDataLevel = EscalateFrResClient::firstWhere([
                                'client_id' => $slaClientEscTicket->client_id,
                                'subcat_id' => $slaClientEscTicket->subcat_id,
                                'level_id' => $finalEscLevel
                            ]);

                            $existingTicket = TicketFrTimeEscClient::where([
                                'client_id' => $escalateDataLevel->client_id,
                                'subcat_id' => $escalateDataLevel->subcat_id,
                                'escalate_level' => $finalEscLevel,
                                'status' => 0,
                            ])->first();


                            if (!$existingTicket) {
                                $slaClientEscTicketSecond = TicketFrTimeEscClient::create([
                                    'ticket_number' => $slaClientEscTicket->ticket_number,
                                    'client_id' => $slaClientEscTicket->client_id,
                                    'subcat_id' => $slaClientEscTicket->subcat_id,
                                    'team_id' => $slaClientEscTicket->team_id,
                                    'escalate_id' => $escalateDataLevel->id,
                                    'escalate_level' => $finalEscLevel,
                                    'escalate_fr_response_time' => $escalateDataLevel->notification_min,
                                    'notification_status' => $escalateDataLevel->send_email_status,
                                    'status' => 0,
                                    'status_name' => 'started',
                                ]);

                                TicketFrTimeEscClientHistory::create($slaClientEscTicketSecond->toArray());
                            } else {

                                $slaClientEscTicketSecond = TicketFrTimeEscClient::where('ticket_number', $slaClientEscTicket->ticket_number)
                                    ->first();


                                if ($slaClientEscTicketSecond) {

                                    if ($ticketAge > $slaClientEscTicketSecond->escalate_fr_response_time) {

                                        $ticketNoForEmail = [
                                            'ticket_number' => $slaClientEscTicketSecond->ticket_number,
                                            'level_id' => $slaClientEscTicketSecond->level_id,
                                            'subcat_id' => $slaClientEscTicketSecond->subcat_id,
                                            'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                                        ];
                
                                        $agentId = DB::table('escalate_fr_re_agent_clients')
                                                        ->where('level_id', $finalEscLevel)
                                                        ->where('subcat_id', $finalSubcatId)
                                                        ->value('agent_id');
                            
                                        // $teamId = $tickInfo['team_id'];
                                        $teamId = $slaEscClientLevelId->team_id;
                            
                                        $recipient = DB::table('escalate_fr_re_agent_clients as ec')
                                                        ->join('users as u', 'ec.agent_id', '=', 'u.id')
                                                        ->where('ec.level_id', $finalEscLevel)
                                                        ->where('ec.subcat_id', $finalSubcatId)
                                                        ->where('ec.agent_id', $agentId)
                                                        ->value('u.email_primary');
                            
                                        $emailTemplate = DB::table('email_templates')
                                            ->where('id', 6)
                                            ->where('status', 'Active')
                                            ->first(['subject', 'content']);
                            
                            
                                        $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                                        $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);
                            
                                        if ($emailResult->status() !== 200) {
                                            return ApiResponse::error('Email sending failed', "Error", 500);
                                        }

                                        $slaClientEscTicketSecond->update([
                                            'status' => 2,
                                            'status_name' => 'failed',
                                        ]);

                                        TicketFrTimeEscClientHistory::create($slaClientEscTicketSecond->toArray());

                                        $slaClientEscTicketSecond->update([
                                            'escalate_level' => $finalEscLevel + 1,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        TicketFrTimeEscClient::where('status', 2)->where('status_name', 'failed')->delete();
    }

    public function isViolatedClientServiceTime()
    {
        $slaClientTickets = TicketSrvTimeClient::all();
        foreach ($slaClientTickets as $ticket) {
            $current_time = Carbon::now();
            $ticket_created_at = $ticket->created_at;
            $ticketAge = $current_time->diffInMinutes($ticket_created_at);
            if ($ticketAge > $ticket->srv_time_duration) {

                $ticket->update([
                    'srv_time_status' => 2,
                    'srv_time_status_name' => 'violated',
                ]);
                //srv_time_id stored sla id
                TicketSrvTimeClientHistory::create([
                    'ticket_number' => $ticket->ticket_number,
                    'client_id' => $ticket->client_id,
                    'subcat_id' => $ticket->subcat_id,
                    'srv_time_id' => $ticket->srv_time_id,
                    'srv_time_duration' => $ticket->srv_time_duration,
                    'srv_time_status' => $ticket->srv_time_status,
                    'srv_time_status_name' => $ticket->srv_time_status_name,
                ]);


                $checkEscalateStatus = SlaClient::firstWhere([
                    'client_id' => $ticket->client_id,
                    'subcat_id' => $ticket->subcat_id
                ]);

                $escalateData = EscalateSrvTimeClient::firstWhere([
                    'client_id' => $ticket->client_id,
                    'subcat_id' => $ticket->subcat_id
                ]);

                $tickInfo = Ticket::where('ticket_number', $ticket->ticket_number)->first();

                if ($tickInfo) {

                    $ticketNoForEmail = [
                        'ticket_number' => $tickInfo->ticket_number,
                        'subcat_id' => $tickInfo->subcat_id,
                        'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                        'business_entity_id' => $tickInfo->business_entity_id,
                    ];

                    // $teamId = $tickInfo['team_id'];
                    $teamId = $tickInfo->team_id;

                    $recipient = DB::table('teams')->where('id', $teamId)->value('group_email');


                    $emailTemplate = DB::table('email_templates')
                        ->where('id', 5)
                        ->where('status', 'Active')
                        ->first(['subject', 'content']);


                    $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                    $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);

                    if ($emailResult->status() !== 200) {
                        return ApiResponse::error('Email sending failed', "Error", 500);
                    }

                    if ($teamId) {

                        if ($checkEscalateStatus && $checkEscalateStatus->esc_status == 1) {

                            TicketSrvTimeEscClient::create([
                                'ticket_number' => $ticket->ticket_number,
                                'client_id' => $ticket->client_id,
                                'subcat_id' => $ticket->subcat_id,
                                'team_id' => $teamId,
                                'escalate_id' => $escalateData->id,
                                'escalate_level' => $escalateData->level_id,
                                'escalate_srv_response_time' => $escalateData->notification_min,
                                'notification_status' => $escalateData->send_email_status,
                                'status' => 0,
                                'status_name' => 'started',
                            ]);


                            TicketSrvTimeEscClientHistory::create([
                                'ticket_number' => $ticket->ticket_number,
                                'client_id' => $ticket->client_id,
                                'subcat_id' => $ticket->subcat_id,
                                'team_id' => $teamId,
                                'escalate_id' => $escalateData->id,
                                'escalate_level' => $escalateData->level_id,
                                'escalate_srv_response_time' => $escalateData->notification_min,
                                'notification_status' => $escalateData->send_email_status,
                                'status' => 0,
                                'status_name' => 'started',
                            ]);
                        }
                    }

                }


                $ticket->delete();
            }
        }
    }


    public function escalatedClientServiceTime()
    {

        $slaClientSrvEscTickets = TicketSrvTimeEscClient::all();

        foreach ($slaClientSrvEscTickets as $slaClientSrvEscTicket) {

            $tickInfo = Ticket::where('ticket_number', $slaClientSrvEscTicket->ticket_number)->first();

            $countEscLavel = EscalateSrvTimeClient::where('client_id', $slaClientSrvEscTicket->client_id)
                ->where('subcat_id', $slaClientSrvEscTicket->subcat_id)
                ->count('level_id');

            // return $countEscLavel;

            for ($m = 1; $m <= $countEscLavel; $m++) {

                $slaEscClientLevelId = TicketSrvTimeEscClient::firstWhere([
                    'client_id' => $slaClientSrvEscTicket->client_id,
                    'subcat_id' => $slaClientSrvEscTicket->subcat_id,
                ]);
                $finalEscLevel = $slaEscClientLevelId->escalate_level;
                $finalSubcatId = $slaEscClientLevelId->subcat_id;

                // return $finalEscLevel;

                $current_time = Carbon::now();
                $ticket_created_at = $tickInfo->created_at;
                $ticketAge = $current_time->diffInMinutes($ticket_created_at);

                


                if ($ticketAge > $slaClientSrvEscTicket->escalate_srv_response_time) {

                    
                    //send notification
                    if ($finalEscLevel < 2) {


                        $ticketNoForEmail = [
                            'ticket_number' => $slaEscClientLevelId->ticket_number,
                            'level_id' => $slaEscClientLevelId->level_id,
                            'subcat_id' => $slaEscClientLevelId->subcat_id,
                            'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                        ];
        
                        $agentId = DB::table('escalate_srv_time_agent_clients')
                                        ->where('level_id', $finalEscLevel)
                                        ->where('subcat_id', $finalSubcatId)
                                        ->value('agent_id');
            
                        // $teamId = $tickInfo['team_id'];
                        $teamId = $slaEscClientLevelId->team_id;
            
                        $recipient = DB::table('escalate_srv_time_agent_clients as ec')
                                        ->join('users as u', 'ec.agent_id', '=', 'u.id')
                                        ->where('ec.level_id', $finalEscLevel)
                                        ->where('ec.subcat_id', $finalSubcatId)
                                        ->where('ec.agent_id', $agentId)
                                        ->value('u.email_primary');
            
                        $emailTemplate = DB::table('email_templates')
                            ->where('id', 7)
                            ->where('status', 'Active')
                            ->first(['subject', 'content']);
            
            
                        $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                        $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);
            
                        if ($emailResult->status() !== 200) {
                            return ApiResponse::error('Email sending failed', "Error", 500);
                        }

                        $slaClientSrvEscTicket->update([
                            'status' => 2,
                            'status_name' => 'failed',
                        ]);

                        TicketSrvTimeEscClientHistory::create($slaClientSrvEscTicket->toArray());

                        $slaClientSrvEscTicket->update([
                            'escalate_level' => $finalEscLevel + 1,
                        ]);
                    } else {

                        if ($finalEscLevel === $m) {

                            $escalateDataLevel = EscalateSrvTimeClient::firstWhere([
                                'client_id' => $slaClientSrvEscTicket->client_id,
                                'subcat_id' => $slaClientSrvEscTicket->subcat_id,
                                'level_id' => $finalEscLevel
                            ]);

                            $existingTicket = TicketSrvTimeEscClient::where([
                                'client_id' => $escalateDataLevel->client_id,
                                'subcat_id' => $escalateDataLevel->subcat_id,
                                'escalate_level' => $finalEscLevel,
                                'status' => 0,
                            ])->first();


                            if (!$existingTicket) {
                                $slaClientEscTicketSecond = TicketSrvTimeEscClient::create([
                                    'ticket_number' => $slaClientSrvEscTicket->ticket_number,
                                    'client_id' => $slaClientSrvEscTicket->client_id,
                                    'subcat_id' => $slaClientSrvEscTicket->subcat_id,
                                    'team_id' => $tickInfo->team_id,
                                    'escalate_id' => $escalateDataLevel->id,
                                    'escalate_level' => $finalEscLevel,
                                    'escalate_srv_response_time' => $escalateDataLevel->notification_min,
                                    'notification_status' => $escalateDataLevel->send_email_status,
                                    'status' => 0,
                                    'status_name' => 'started',
                                ]);

                                TicketSrvTimeEscClientHistory::create($slaClientEscTicketSecond->toArray());
                            } else {
                                $slaClientEscTicketSecond =  TicketSrvTimeEscClient::where('ticket_number', $slaClientSrvEscTicket->ticket_number)
                                    ->first();

                                // return $slaClientEscTicketSecond;

                                if ($ticketAge > $slaClientEscTicketSecond->escalate_srv_response_time) {

                                    $ticketNoForEmail = [
                                        'ticket_number' => $slaClientEscTicketSecond->ticket_number,
                                        'level_id' => $slaClientEscTicketSecond->level_id,
                                        'subcat_id' => $slaClientEscTicketSecond->subcat_id,
                                        'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                                    ];
                    
                                    $agentId = DB::table('escalate_srv_time_agent_clients')
                                                    ->where('level_id', $finalEscLevel)
                                                    ->where('subcat_id', $finalSubcatId)
                                                    ->value('agent_id');
                        
                                    // $teamId = $tickInfo['team_id'];
                                    $teamId = $slaEscClientLevelId->team_id;
                        
                                    $recipient = DB::table('escalate_srv_time_agent_clients as ec')
                                                    ->join('users as u', 'ec.agent_id', '=', 'u.id')
                                                    ->where('ec.level_id', $finalEscLevel)
                                                    ->where('ec.subcat_id', $finalSubcatId)
                                                    ->where('ec.agent_id', $agentId)
                                                    ->value('u.email_primary');
                        
                                    $emailTemplate = DB::table('email_templates')
                                        ->where('id', 7)
                                        ->where('status', 'Active')
                                        ->first(['subject', 'content']);
                        
                        
                                    $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                                    $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);
                        
                                    if ($emailResult->status() !== 200) {
                                        return ApiResponse::error('Email sending failed', "Error", 500);
                                    }

                                    $slaClientEscTicketSecond->update([
                                        'status' => 2,
                                        'status_name' => 'failed',
                                    ]);

                                    TicketSrvTimeEscClientHistory::create($slaClientEscTicketSecond->toArray());

                                    $slaClientEscTicketSecond->update([
                                        'escalate_level' => $finalEscLevel + 1,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }
        }
        TicketSrvTimeEscClient::where('status', 2)->where('status_name', 'failed')->delete();
    }



    // --------------

    
    public function isViolatedTeamFirstResponseTime()
    {

        // return 'hi';
        $slaTeamTickets = TicketFrTimeTeam::all();
        // $slaTeamTickets = TicketFrTimeTeam::where('fr_response_status', '!=', 1)->get();

        // return $slaTeamTickets;

        foreach ($slaTeamTickets as $ticket) {
            $current_time = Carbon::now();
            $ticket_created_at = $ticket->created_at;
            $ticketAge = $current_time->diffInMinutes($ticket_created_at);

            // return $ticket;

            // $ticket_updated_at = $ticket->updated_at;
            // $ticketAgeTeam = $current_time->diffInMinutes($ticket_updated_at);


            if ($ticketAge > $ticket->fr_response_time) {

                // return 'hi all 1';
                // return $ticket; 

             DB::transaction(function () use ($ticket, $ticketAge) {

                    // return 'hi all 1';

                //send notification

                $ticket->update([
                    'fr_response_status' => 2,
                    'fr_response_status_name' => 'violated',
                ]);

                TicketFrTimeTeamHistory::create($ticket->toArray());

                $checkEscalateStatus = SlaSubcategory::firstWhere([
                    'team_id' => $ticket->team_id,
                    'subcat_id' => $ticket->subcat_id,
                    'business_entity_id' => $ticket->business_entity_id,
                ]);

                $escalateData = EscalateFrResSubcategory::firstWhere([
                    'team_id' => $ticket->team_id,
                    'subcat_id' => $ticket->subcat_id,
                    'business_entity_id' => $ticket->business_entity_id,
                ]);

                // return $escalateData;

                $tickInfo = Ticket::where('ticket_number', $ticket->ticket_number)->first();

                if($tickInfo){

                    // return 'here i am ';


                    $ticketNoForEmail = [
                        'ticket_number' => $tickInfo->ticket_number,
                        'user_id' => $tickInfo->user_id,
                        'cat_id' => $tickInfo->cat_id,
                        'subcat_id' => $tickInfo->subcat_id,
                        'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                        'business_entity_id' => $tickInfo->business_entity_id,
                        // 'sub_category_in_english' => $tickInfo->sub_category_in_english,
                        'created_at' => $tickInfo->created_at,
                        'ticketAge' => $ticketAge,
                    ];

                    // return $ticketNoForEmail;
    
                    // // $teamId = $tickInfo['team_id'];
                    $teamId = $tickInfo->team_id;
    
                    $recipient = DB::table('teams')->where('id', $teamId)->value('group_email');
    
    
                    $emailTemplate = DB::table('email_templates')
                        ->where('id', 4)
                        ->where('status', 'Active')
                        ->first(['subject', 'content']);
    
    
                    $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                    // return [$ticketNoForEmail, $teamId, $emailTemplate, $recipient];
                    $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);

                    // return $emailResult;
    
                    if ($emailResult->status() !== 200) {
                        return ApiResponse::error('Email sending failed', "Error", 500);
                    }

                    if($teamId){
                        if ($checkEscalateStatus && $checkEscalateStatus->esc_status == 1) {

                            // return [$escalateData];
    
                            $escateCreate = TicketFrTimeEscTeam::create([
                                'ticket_number' => $ticket->ticket_number,
                                'subcat_id' => $ticket->subcat_id,
                                'team_id' => $teamId,
                                'escalate_id' => $escalateData->id,
                                'escalate_level' => $escalateData->level_id,
                                'escalate_fr_response_time' => $escalateData->notification_min,
                                'notification_status' => $escalateData->send_email_status,
                                'status' => 0,
                                'status_name' => 'started',
                            ]);
        
                            TicketFrTimeEscTeamHistory::create($escateCreate->toArray());
                        }

                    }

                }

                $ticket->delete();

             });
            }

            
        }
    }

    // public function escalatedTeamFirstResponseTime()
    // {

    //     $slaTeamEscTickets = TicketFrTimeEscTeam::all();

    //     foreach ($slaTeamEscTickets as $slaTeamEscTicket) {

    //         $tickInfo = Ticket::where('ticket_number', $slaTeamEscTicket->ticket_number)->first();

    //         $countEscLavel = EscalateFrResSubcategory::where('team_id', $slaTeamEscTicket->team_id)
    //             ->where('subcat_id', $slaTeamEscTicket->subcat_id)
    //             ->count('level_id');

    //         for ($m = 1; $m <= $countEscLavel; $m++) {

    //             $slaEscTeamLevelId = TicketFrTimeEscTeam::firstWhere([
    //                 'team_id' => $slaTeamEscTicket->team_id,
    //                 'subcat_id' => $slaTeamEscTicket->subcat_id,
    //             ]);
    //             $finalEscLevel = $slaEscTeamLevelId->escalate_level;
    //             $finalSubcatId = $slaEscTeamLevelId->subcat_id;


    //             $current_time = Carbon::now();
    //             $ticket_created_at = $tickInfo->created_at;
    //             $ticketAge = $current_time->diffInMinutes($ticket_created_at);
    //             // $ticket_updated_at = $tickInfo->updated_at;
    //             // $ticketAgeTeam = $current_time->diffInMinutes($ticket_updated_at);


    //             if ($ticketAge > $slaTeamEscTicket->escalate_fr_response_time) {

                    
                    
    //                 //send notification
    //                 if ($finalEscLevel < 2) {

    //                     // $ticketNoForEmail = [
    //                     //     'ticket_number' => $slaEscTeamLevelId->ticket_number,
    //                     //     'level_id' => $slaEscTeamLevelId->level_id,
    //                     //     'subcat_id' => $slaEscTeamLevelId->subcat_id,
    //                     //     'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
    //                     // ];

    //                     $ticketNoForEmail = [
    //                         'ticket_number' => $slaEscTeamLevelId->ticket_number,
    //                         'level_id' => $slaEscTeamLevelId->level_id,
    //                         'user_id' => $slaEscTeamLevelId->user_id,
    //                         'cat_id' => $tickInfo->cat_id,
    //                         'subcat_id' => $slaEscTeamLevelId->subcat_id,
    //                         'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
    //                         'business_entity_id' => $tickInfo->business_entity_id,
    //                         'created_at' => $tickInfo->created_at,
    //                         'ticketAge' => $ticketAge,
    //                     ];
    
    //                     $agentId = DB::table('escalate_fr_res_agent_subcategories')
    //                                     ->where('level_id', $finalEscLevel)
    //                                     ->where('subcat_id', $finalSubcatId)
    //                                     ->value('agent_id');
            
    //                     // $teamId = $tickInfo['team_id'];
    //                     $teamId = $slaEscTeamLevelId->team_id;
            
    //                     $recipient = DB::table('escalate_fr_res_agent_subcategories as ec')
    //                                     ->join('users as u', 'ec.agent_id', '=', 'u.id')
    //                                     ->where('ec.level_id', $finalEscLevel)
    //                                     ->where('ec.subcat_id', $finalSubcatId)
    //                                     ->where('ec.agent_id', $agentId)
    //                                     ->value('u.email_primary');
            

                                        

    //                     $emailTemplate = DB::table('email_templates')
    //                         ->where('id', 6)
    //                         ->where('status', 'Active')
    //                         ->first(['subject', 'content']);
            
            
    //                     $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
    //                     $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);
            
    //                     if ($emailResult->status() !== 200) {
    //                         return ApiResponse::error('Email sending failed', "Error", 500);
    //                     }

    //                     $slaTeamEscTicket->update([
    //                         'status' => 2,
    //                         'status_name' => 'failed',
    //                     ]);

    //                     TicketFrTimeEscTeamHistory::create($slaTeamEscTicket->toArray());

    //                     $slaTeamEscTicket->update([
    //                         'escalate_level' => $finalEscLevel + 1,
    //                     ]);
    //                 } else {

    //                     if ($finalEscLevel === $m) {

    //                             $escalateDataLevel = EscalateFrResSubcategory::firstWhere([
    //                                 'team_id' => $slaTeamEscTicket->team_id,
    //                                 'subcat_id' => $slaTeamEscTicket->subcat_id,
    //                                 'level_id' => $finalEscLevel
    //                             ]);

    //                             $existingTicket = TicketFrTimeEscTeam::where([
    //                                 'team_id' => $escalateDataLevel->team_id,
    //                                 'subcat_id' => $escalateDataLevel->subcat_id,
    //                                 'escalate_level' => $finalEscLevel,
    //                                 'status' => 0,
    //                             ])->first();


    //                             if (!$existingTicket) {
    //                                 $slaClientEscTicketSecond = TicketFrTimeEscTeam::create([
    //                                     'ticket_number' => $slaTeamEscTicket->ticket_number,
    //                                     'subcat_id' => $slaTeamEscTicket->subcat_id,
    //                                     'team_id' => $slaTeamEscTicket->team_id,
    //                                     'escalate_id' => $escalateDataLevel->id,
    //                                     'escalate_level' => $finalEscLevel,
    //                                     'escalate_fr_response_time' => $escalateDataLevel->notification_min,
    //                                     'notification_status' => $escalateDataLevel->send_email_status,
    //                                     'status' => 0,
    //                                     'status_name' => 'started',
    //                                 ]);

    //                                 TicketFrTimeEscTeamHistory::create($slaClientEscTicketSecond->toArray());
    //                             } else {

    //                                 $slaClientEscTicketSecond = TicketFrTimeEscTeam::where('ticket_number', $slaTeamEscTicket->ticket_number)
    //                                     ->first();


    //                             if ($slaClientEscTicketSecond) {
    //                                 if ($ticketAge > $slaClientEscTicketSecond->escalate_fr_response_time) {

    //                                     // $ticketNoForEmail = [
    //                                     //     'ticket_number' => $slaClientEscTicketSecond->ticket_number,
    //                                     //     'level_id' => $slaClientEscTicketSecond->level_id,
    //                                     //     'subcat_id' => $slaClientEscTicketSecond->subcat_id,
    //                                     //     'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
    //                                     // ];


    //                                     $ticketNoForEmail = [
    //                                         'ticket_number' => $slaClientEscTicketSecond->ticket_number,
    //                                         'level_id' => $slaClientEscTicketSecond->level_id,
    //                                         'user_id' => $slaClientEscTicketSecond->user_id,
    //                                         'cat_id' => $tickInfo->cat_id,
    //                                         'subcat_id' => $slaClientEscTicketSecond->subcat_id,
    //                                         'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
    //                                         'business_entity_id' => $tickInfo->business_entity_id,
    //                                         'created_at' => $tickInfo->created_at,
    //                                         'ticketAge' => $ticketAge,
    //                                     ];
                    
    //                                     $agentId = DB::table('escalate_fr_res_agent_subcategories')
    //                                                     ->where('level_id', $finalEscLevel)
    //                                                     ->where('subcat_id', $finalSubcatId)
    //                                                     ->value('agent_id');
                            
    //                                     // $teamId = $tickInfo['team_id'];
    //                                     $teamId = $slaClientEscTicketSecond->team_id;
                            
    //                                     $recipient = DB::table('escalate_fr_res_agent_subcategories as ec')
    //                                                     ->join('users as u', 'ec.agent_id', '=', 'u.id')
    //                                                     ->where('ec.level_id', $finalEscLevel)
    //                                                     ->where('ec.subcat_id', $finalSubcatId)
    //                                                     ->where('ec.agent_id', $agentId)
    //                                                     ->value('u.email_primary');
                            
    //                                     $emailTemplate = DB::table('email_templates')
    //                                         ->where('id', 6)
    //                                         ->where('status', 'Active')
    //                                         ->first(['subject', 'content']);
                            
                            
    //                                     $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
    //                                     $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);
                            
    //                                     if ($emailResult->status() !== 200) {
    //                                         return ApiResponse::error('Email sending failed', "Error", 500);
    //                                     }

    //                                     $slaClientEscTicketSecond->update([
    //                                         'status' => 2,
    //                                         'status_name' => 'failed',
    //                                     ]);

    //                                     TicketFrTimeEscTeamHistory::create($slaClientEscTicketSecond->toArray());

    //                                     $slaClientEscTicketSecond->update([
    //                                         'escalate_level' => $finalEscLevel + 1,
    //                                     ]);
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     TicketFrTimeEscTeam::where('status', 2)->where('status_name', 'failed')->delete();
    // }

    public function escalatedTeamFirstResponseTime()
    {
        $slaTeamEscTickets = TicketFrTimeEscTeam::all();

        foreach ($slaTeamEscTickets as $slaTeamEscTicket) {
            DB::beginTransaction(); // Start transaction

            try {
                $tickInfo = Ticket::where('ticket_number', $slaTeamEscTicket->ticket_number)->first();

                $countEscLavel = EscalateFrResSubcategory::where('team_id', $slaTeamEscTicket->team_id)
                    ->where('subcat_id', $slaTeamEscTicket->subcat_id)
                    ->count('level_id');

                for ($m = 1; $m <= $countEscLavel; $m++) {
                    $slaEscTeamLevelId = TicketFrTimeEscTeam::firstWhere([
                        'team_id' => $slaTeamEscTicket->team_id,
                        'subcat_id' => $slaTeamEscTicket->subcat_id,
                    ]);

                    $finalEscLevel = $slaEscTeamLevelId->escalate_level;
                    $finalSubcatId = $slaEscTeamLevelId->subcat_id;

                    $current_time = Carbon::now();
                    $ticket_created_at = $tickInfo->created_at;
                    $ticketAge = $current_time->diffInMinutes($ticket_created_at);

                    if ($ticketAge > $slaTeamEscTicket->escalate_fr_response_time) {
                        if ($finalEscLevel < 2) {
                            $ticketNoForEmail = [
                                'ticket_number' => $slaEscTeamLevelId->ticket_number,
                                'level_id' => $slaEscTeamLevelId->level_id,
                                'user_id' => $slaEscTeamLevelId->user_id,
                                'cat_id' => $tickInfo->cat_id,
                                'subcat_id' => $slaEscTeamLevelId->subcat_id,
                                'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                                'business_entity_id' => $tickInfo->business_entity_id,
                                'created_at' => $tickInfo->created_at,
                                'ticketAge' => $ticketAge,
                            ];

                            $agentId = DB::table('escalate_fr_res_agent_subcategories')
                                ->where('level_id', $finalEscLevel)
                                ->where('subcat_id', $finalSubcatId)
                                ->value('agent_id');

                            $teamId = $slaEscTeamLevelId->team_id;

                            $recipient = DB::table('escalate_fr_res_agent_subcategories as ec')
                                ->join('users as u', 'ec.agent_id', '=', 'u.id')
                                ->where('ec.level_id', $finalEscLevel)
                                ->where('ec.subcat_id', $finalSubcatId)
                                ->where('ec.agent_id', $agentId)
                                ->value('u.email_primary');

                            $emailTemplate = DB::table('email_templates')
                                ->where('id', 6)
                                ->where('status', 'Active')
                                ->first(['subject', 'content']);

                            $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                            $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);

                            if ($emailResult->status() !== 200) {
                                throw new \Exception('Email sending failed');
                            }

                            $slaTeamEscTicket->update([
                                'status' => 2,
                                'status_name' => 'failed',
                            ]);

                            TicketFrTimeEscTeamHistory::create($slaTeamEscTicket->toArray());

                            $slaTeamEscTicket->update([
                                'escalate_level' => $finalEscLevel + 1,
                            ]);
                        } else {
                            if ($finalEscLevel === $m) {
                                $escalateDataLevel = EscalateFrResSubcategory::firstWhere([
                                    'team_id' => $slaTeamEscTicket->team_id,
                                    'subcat_id' => $slaTeamEscTicket->subcat_id,
                                    'level_id' => $finalEscLevel,
                                ]);

                                $existingTicket = TicketFrTimeEscTeam::where([
                                    'team_id' => $escalateDataLevel->team_id,
                                    'subcat_id' => $escalateDataLevel->subcat_id,
                                    'escalate_level' => $finalEscLevel,
                                    'status' => 0,
                                ])->first();

                                if (!$existingTicket) {
                                    $slaClientEscTicketSecond = TicketFrTimeEscTeam::create([
                                        'ticket_number' => $slaTeamEscTicket->ticket_number,
                                        'subcat_id' => $slaTeamEscTicket->subcat_id,
                                        'team_id' => $slaTeamEscTicket->team_id,
                                        'escalate_id' => $escalateDataLevel->id,
                                        'escalate_level' => $finalEscLevel,
                                        'escalate_fr_response_time' => $escalateDataLevel->notification_min,
                                        'notification_status' => $escalateDataLevel->send_email_status,
                                        'status' => 0,
                                        'status_name' => 'started',
                                    ]);

                                    TicketFrTimeEscTeamHistory::create($slaClientEscTicketSecond->toArray());
                                } else {
                                    $slaClientEscTicketSecond = TicketFrTimeEscTeam::where('ticket_number', $slaTeamEscTicket->ticket_number)->first();

                                    if ($slaClientEscTicketSecond && $ticketAge > $slaClientEscTicketSecond->escalate_fr_response_time) {
                                        $ticketNoForEmail = [
                                            'ticket_number' => $slaClientEscTicketSecond->ticket_number,
                                            'level_id' => $slaClientEscTicketSecond->level_id,
                                            'user_id' => $slaClientEscTicketSecond->user_id,
                                            'cat_id' => $tickInfo->cat_id,
                                            'subcat_id' => $slaClientEscTicketSecond->subcat_id,
                                            'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                                            'business_entity_id' => $tickInfo->business_entity_id,
                                            'created_at' => $tickInfo->created_at,
                                            'ticketAge' => $ticketAge,
                                        ];

                                        $agentId = DB::table('escalate_fr_res_agent_subcategories')
                                            ->where('level_id', $finalEscLevel)
                                            ->where('subcat_id', $finalSubcatId)
                                            ->value('agent_id');

                                        $teamId = $slaClientEscTicketSecond->team_id;

                                        $recipient = DB::table('escalate_fr_res_agent_subcategories as ec')
                                            ->join('users as u', 'ec.agent_id', '=', 'u.id')
                                            ->where('ec.level_id', $finalEscLevel)
                                            ->where('ec.subcat_id', $finalSubcatId)
                                            ->where('ec.agent_id', $agentId)
                                            ->value('u.email_primary');

                                        $emailTemplate = DB::table('email_templates')
                                            ->where('id', 6)
                                            ->where('status', 'Active')
                                            ->first(['subject', 'content']);

                                        $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                                        $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);

                                        if ($emailResult->status() !== 200) {
                                            throw new \Exception('Email sending failed');
                                        }

                                        $slaClientEscTicketSecond->update([
                                            'status' => 2,
                                            'status_name' => 'failed',
                                        ]);

                                        TicketFrTimeEscTeamHistory::create($slaClientEscTicketSecond->toArray());

                                        $slaClientEscTicketSecond->update([
                                            'escalate_level' => $finalEscLevel + 1,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }

                DB::commit(); // Commit transaction
            } catch (\Exception $e) {
                DB::rollBack(); // Rollback transaction on failure
                throw $e;
            }
        }

        TicketFrTimeEscTeam::where('status', 2)->where('status_name', 'failed')->delete();
    }


    // public function isViolatedTeamServiceTime()
    // {
    //     $slaTeamSrvTimeTickets = TicketSrvTimeTeam::all();
    //     foreach ($slaTeamSrvTimeTickets as $ticket) {
    //         $current_time = Carbon::now();
    //         $ticket_created_at = $ticket->created_at;
    //         $ticketAge = $current_time->diffInMinutes($ticket_created_at);
    //         // $ticket_updated_at = $ticket->updated_at;
    //         // $ticketAgeTeam = $current_time->diffInMinutes($ticket_updated_at);

    //         if ($ticketAge > $ticket->srv_time_duration) {

    //             $ticket->update([
    //                 'srv_time_status' => 2,
    //                 'srv_time_status_name' => 'violated',
    //             ]);
    //             //srv_time_id stored sla id

    //             TicketSrvTimeTeamHistory::create($ticket->toArray());

    //             $checkEscalateStatus = SlaSubcategory::firstWhere([
    //                 'team_id' => $ticket->team_id,
    //                 'subcat_id' => $ticket->subcat_id
    //             ]);

    //             $escalateData = EscalateSrvTimeSubcategory::firstWhere([
    //                 'team_id' => $ticket->team_id,
    //                 'subcat_id' => $ticket->subcat_id
    //             ]);

    //             $tickInfo = Ticket::where('ticket_number', $ticket->ticket_number)->first();

    //             if($tickInfo){
    //                 // $ticketNoForEmail = [
    //                 //     'ticket_number' => $tickInfo->ticket_number,
    //                 //     'subcat_id' => $tickInfo->subcat_id,
    //                 //     'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
    //                 // ];


    //                 $ticketNoForEmail = [
    //                     'ticket_number' => $tickInfo->ticket_number,
    //                     // 'level_id' => $tickInfo->level_id,
    //                     'user_id' => $tickInfo->user_id,
    //                     'cat_id' => $tickInfo->cat_id,
    //                     'subcat_id' => $tickInfo->subcat_id,
    //                     'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
    //                     'business_entity_id' => $tickInfo->business_entity_id,
    //                     'created_at' => $tickInfo->created_at,
    //                     'ticketAge' => $ticketAge,
    //                 ];
    
    //                 // $teamId = $tickInfo['team_id'];
    //                 $teamId = $tickInfo->team_id;
    
    //                 $recipient = DB::table('teams')->where('id', $teamId)->value('group_email');
    
    
    //                 $emailTemplate = DB::table('email_templates')
    //                     ->where('id', 5)
    //                     ->where('status', 'Active')
    //                     ->first(['subject', 'content']);
    
    
    //                 $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
    //                 $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);
    
    //                 if ($emailResult->status() !== 200) {
    //                     return ApiResponse::error('Email sending failed', "Error", 500);
    //                 }

    //                 if($teamId){
    //                     if ($checkEscalateStatus && $checkEscalateStatus->esc_status == 1) {
    
    //                         $escateDataCreate = TicketSrvTimeEscTeam::create([
    //                             'ticket_number' => $ticket->ticket_number,
    //                             'subcat_id' => $ticket->subcat_id,
    //                             'team_id' => $tickInfo->team_id,
    //                             'escalate_id' => $escalateData->id,
    //                             'escalate_level' => $escalateData->level_id,
    //                             'escalate_srv_response_time' => $escalateData->notification_min,
    //                             'notification_status' => $escalateData->send_email_status,
    //                             'status' => 0,
    //                             'status_name' => 'started',
    //                         ]);
        
    //                         TicketSrvTimeEscTeamHistory::create($escateDataCreate->toArray());
    //                     }

    //                 }

    //             }

                


    //             $ticket->delete();
    //         }
    //     }
    // }


    public function isViolatedTeamServiceTime()
    {
        $slaTeamSrvTimeTickets = TicketSrvTimeTeam::all();
        foreach ($slaTeamSrvTimeTickets as $ticket) {
            $current_time = Carbon::now();
            $ticket_created_at = $ticket->created_at;
            $ticketAge = $current_time->diffInMinutes($ticket_created_at);
            // $ticket_updated_at = $ticket->updated_at;
            // $ticketAgeTeam = $current_time->diffInMinutes($ticket_updated_at);

            if ($ticketAge > $ticket->srv_time_duration) {

             DB::transaction(function () use ($ticket, $ticketAge) {

                $ticket->update([
                    'srv_time_status' => 2,
                    'srv_time_status_name' => 'violated',
                ]);
                //srv_time_id stored sla id

                TicketSrvTimeTeamHistory::create($ticket->toArray());

                $checkEscalateStatus = SlaSubcategory::firstWhere([
                    'team_id' => $ticket->team_id,
                    'subcat_id' => $ticket->subcat_id,
                    'business_entity_id' => $ticket->business_entity_id,
                ]);

                $escalateData = EscalateSrvTimeSubcategory::firstWhere([
                    'team_id' => $ticket->team_id,
                    'subcat_id' => $ticket->subcat_id,
                    'business_entity_id' => $ticket->business_entity_id,
                ]);

                $tickInfo = Ticket::where('ticket_number', $ticket->ticket_number)->first();

                if($tickInfo){
                    // $ticketNoForEmail = [
                    //     'ticket_number' => $tickInfo->ticket_number,
                    //     'subcat_id' => $tickInfo->subcat_id,
                    //     'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                    // ];


                    $ticketNoForEmail = [
                        'ticket_number' => $tickInfo->ticket_number,
                        // 'level_id' => $tickInfo->level_id,
                        'user_id' => $tickInfo->user_id,
                        'cat_id' => $tickInfo->cat_id,
                        'subcat_id' => $tickInfo->subcat_id,
                        'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                        'business_entity_id' => $tickInfo->business_entity_id,
                        'created_at' => $tickInfo->created_at,
                        'ticketAge' => $ticketAge,
                    ];


                   
    
                    // $teamId = $tickInfo['team_id'];
                    $teamId = $tickInfo->team_id;
    
                    $recipient = DB::table('teams')->where('id', $teamId)->value('group_email');
    
    
                    $emailTemplate = DB::table('email_templates')
                        ->where('id', 5)
                        ->where('status', 'Active')
                        ->first(['subject', 'content']);
    
    
                    $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                    $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);
                    // return $emailResult;
    
                    if ($emailResult->status() !== 200) {
                        return ApiResponse::error('Email sending failed', "Error", 500);
                    }

                    if($teamId){
                        if ($checkEscalateStatus && $checkEscalateStatus->esc_status == 1) {
    
                            $escateDataCreate = TicketSrvTimeEscTeam::create([
                                'ticket_number' => $ticket->ticket_number,
                                'subcat_id' => $ticket->subcat_id,
                                'team_id' => $tickInfo->team_id,
                                'escalate_id' => $escalateData->id,
                                'escalate_level' => $escalateData->level_id,
                                'escalate_srv_response_time' => $escalateData->notification_min,
                                'notification_status' => $escalateData->send_email_status,
                                'status' => 0,
                                'status_name' => 'started',
                            ]);
        
                            TicketSrvTimeEscTeamHistory::create($escateDataCreate->toArray());
                        }

                    }

                }

                    $ticket->delete();
             });
            }
        }
    }


    public function escalatedTeamServiceTime()
    {

        $slaTeamSrvEscTickets = TicketSrvTimeEscTeam::all();
        
        foreach ($slaTeamSrvEscTickets as $slaTeamSrvEscTicket) {
            DB::beginTransaction();

            try {
            $tickInfo = Ticket::where('ticket_number', $slaTeamSrvEscTicket->ticket_number)->first();



            $countEscLavel = EscalateSrvTimeSubcategory::where('team_id', $slaTeamSrvEscTicket->team_id)
                ->where('subcat_id', $slaTeamSrvEscTicket->subcat_id)
                ->count('level_id');

            for ($m = 1; $m <= $countEscLavel; $m++) {

                $slaEscTeamLevelId = TicketSrvTimeEscTeam::firstWhere([
                    'team_id' => $slaTeamSrvEscTicket->team_id,
                    'subcat_id' => $slaTeamSrvEscTicket->subcat_id,
                ]);
                $finalEscLevel = $slaEscTeamLevelId->escalate_level;
                $finalSubcatId = $slaEscTeamLevelId->subcat_id;

                $current_time = Carbon::now();
                $ticket_created_at = $tickInfo->created_at;
                $ticketAge = $current_time->diffInMinutes($ticket_created_at);
                $ticket_updated_at = $slaTeamSrvEscTicket->updated_at;
                $ticketAgeTeam = $current_time->diffInMinutes($ticket_updated_at);


                

                // return $finalEscLevel;

                $current_time = Carbon::now();
                $ticket_created_at = $tickInfo->created_at;
                $ticketAge = $current_time->diffInMinutes($ticket_created_at);



                if ($ticketAge > $slaTeamSrvEscTicket->escalate_srv_response_time) {

                    
                    //send notification

                    if ($finalEscLevel < 2) {

                        // $ticketNoForEmail = [
                        //     'ticket_number' => $slaEscTeamLevelId->ticket_number,
                        //     'level_id' => $slaEscTeamLevelId->level_id,
                        //     'subcat_id' => $slaEscTeamLevelId->subcat_id,
                        //     'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                        // ];

                        $ticketNoForEmail = [
                            'ticket_number' => $slaEscTeamLevelId->ticket_number,
                            'level_id' => $slaEscTeamLevelId->level_id,
                            'user_id' => $slaEscTeamLevelId->user_id,
                            'cat_id' => $tickInfo->cat_id,
                            'subcat_id' => $slaEscTeamLevelId->subcat_id,
                            'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                            'business_entity_id' => $tickInfo->business_entity_id,
                            'created_at' => $tickInfo->created_at,
                            'ticketAge' => $ticketAge,
                        ];
        
                        $agentId = DB::table('escalate_srv_time_agent_subcategories')
                                        ->where('level_id', $finalEscLevel)
                                        ->where('subcat_id', $finalSubcatId)
                                        ->value('agent_id');
            
                        // $teamId = $tickInfo['team_id'];
                        $teamId = $slaEscTeamLevelId->team_id;
            
                        $recipient = DB::table('escalate_srv_time_agent_subcategories as es')
                                        ->join('users as u', 'es.agent_id', '=', 'u.id')
                                        ->where('es.level_id', $finalEscLevel)
                                        ->where('es.subcat_id', $finalSubcatId)
                                        ->where('es.agent_id', $agentId)
                                        ->value('u.email_primary');

                                        // dd($recipient );
            
                        $emailTemplate = DB::table('email_templates')
                            ->where('id', 7)
                            ->where('status', 'Active')
                            ->first(['subject', 'content']);
            
            
                        $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                        $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);
            
                        if ($emailResult->status() !== 200) {
                            return ApiResponse::error('Email sending failed', "Error", 500);
                        }

                        $slaTeamSrvEscTicket->update([
                            'status' => 2,
                            'status_name' => 'failed',
                        ]);

                        TicketSrvTimeEscTeamHistory::create($slaTeamSrvEscTicket->toArray());

                        $slaTeamSrvEscTicket->update([
                            'escalate_level' => $finalEscLevel + 1,
                        ]);
                    } else {

                        if ($finalEscLevel === $m) {

                            $escalateDataLevel = EscalateSrvTimeSubcategory::firstWhere([
                                'team_id' => $slaTeamSrvEscTicket->team_id,
                                'subcat_id' => $slaTeamSrvEscTicket->subcat_id,
                                'level_id' => $finalEscLevel
                            ]);

                            $existingTicket = TicketSrvTimeEscTeam::where([
                                'team_id' => $escalateDataLevel->team_id,
                                'subcat_id' => $escalateDataLevel->subcat_id,
                                'escalate_level' => $finalEscLevel,
                                'status' => 0,
                            ])->first();


                            if (!$existingTicket) {
                                $slaClientEscTicketSecond = TicketSrvTimeEscTeam::create([
                                    'ticket_number' => $slaTeamSrvEscTicket->ticket_number,
                                    'subcat_id' => $slaTeamSrvEscTicket->subcat_id,
                                    'team_id' => $tickInfo->team_id,
                                    'escalate_id' => $escalateDataLevel->id,
                                    'escalate_level' => $finalEscLevel,
                                    'escalate_srv_response_time' => $escalateDataLevel->notification_min,
                                    'notification_status' => $escalateDataLevel->send_email_status,
                                    'status' => 0,
                                    'status_name' => 'started',
                                ]);

                                TicketSrvTimeEscTeamHistory::create($slaClientEscTicketSecond->toArray());
                            } else {
                                $slaClientEscTicketSecond =  TicketSrvTimeEscTeam::where('ticket_number', $slaTeamSrvEscTicket->ticket_number)
                                    ->first();


                                if ($slaClientEscTicketSecond) {
                                    
                                
                                    if ($ticketAge > $slaClientEscTicketSecond->escalate_srv_response_time) {

                                        // $ticketNoForEmail = [
                                        //     'ticket_number' => $slaClientEscTicketSecond->ticket_number,
                                        //     'level_id' => $slaClientEscTicketSecond->level_id,
                                        //     'subcat_id' => $slaClientEscTicketSecond->subcat_id,
                                        //     'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                                        // ];


                                        $ticketNoForEmail = [
                                            'ticket_number' => $slaClientEscTicketSecond->ticket_number,
                                            'level_id' => $slaClientEscTicketSecond->level_id,
                                            'user_id' => $slaClientEscTicketSecond->user_id,
                                            'cat_id' => $tickInfo->cat_id,
                                            'subcat_id' => $slaClientEscTicketSecond->subcat_id,
                                            'client_id_helpdesk' => $tickInfo->client_id_helpdesk,
                                            'business_entity_id' => $tickInfo->business_entity_id,
                                            'created_at' => $tickInfo->created_at,
                                            'ticketAge' => $ticketAge,
                                        ];
                        
                                        $agentId = DB::table('escalate_srv_time_agent_subcategories')
                                                        ->where('level_id', $finalEscLevel)
                                                        ->where('subcat_id', $finalSubcatId)
                                                        ->value('agent_id');
                            
                                        // $teamId = $tickInfo['team_id'];
                                        $teamId = $slaClientEscTicketSecond->team_id;
                            
                                        $recipient = DB::table('escalate_srv_time_agent_subcategories as ec')
                                                        ->join('users as u', 'ec.agent_id', '=', 'u.id')
                                                        ->where('ec.level_id', $finalEscLevel)
                                                        ->where('ec.subcat_id', $finalSubcatId)
                                                        ->where('ec.agent_id', $agentId)
                                                        ->value('u.email_primary');
                            
                                        $emailTemplate = DB::table('email_templates')
                                            ->where('id', 7)
                                            ->where('status', 'Active')
                                            ->first(['subject', 'content']);
                            
                            
                                        $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                                        $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);
                            
                                        if ($emailResult->status() !== 200) {
                                            return ApiResponse::error('Email sending failed', "Error", 500);
                                        }

                                        $slaClientEscTicketSecond->update([
                                            'status' => 2,
                                            'status_name' => 'failed',
                                        ]);

                                        TicketSrvTimeEscTeamHistory::create($slaClientEscTicketSecond->toArray());

                                        $slaClientEscTicketSecond->update([
                                            'escalate_level' => $finalEscLevel + 1,
                                        ]);
                                    }
                                }
                            }
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback transaction on failure
            throw $e;
        }
        
        }
        TicketSrvTimeEscTeam::where('status', 2)->where('status_name', 'failed')->delete();
    }

    // reformed code start

    public function escalatedTeamServiceTime1()
    {
        $slaTickets = TicketSrvTimeEscTeam::all();

        foreach ($slaTickets as $slaTicket) {
            $ticketInfo = Ticket::where('ticket_number', $slaTicket->ticket_number)->first();

            if (!$ticketInfo) {
                continue; // Skip if ticket info is not found
            }

            $escalationLevels = EscalateSrvTimeSubcategory::where('team_id', $slaTicket->team_id)
                ->where('subcat_id', $slaTicket->subcat_id)
                ->count('level_id');

            for ($level = 1; $level <= $escalationLevels; $level++) {
                $currentTime = Carbon::now();
                $ticketAge = $currentTime->diffInMinutes($ticketInfo->created_at);
                $ticketAgeSinceUpdate = $currentTime->diffInMinutes($slaTicket->updated_at);

                if ($ticketAge > $slaTicket->escalate_srv_response_time) {
                    if ($slaTicket->escalate_level < 2) {
                        $this->sendEscalationEmail($slaTicket, $ticketInfo, $ticketAge);
                        $this->updateTicketEscalation($slaTicket, $slaTicket->escalate_level + 1);
                    } else {
                        $this->handleFinalEscalation($slaTicket, $ticketInfo, $level, $ticketAge);
                    }
                }
            }
        }

        // Clean up failed tickets
        TicketSrvTimeEscTeam::where('status', 2)->where('status_name', 'failed')->delete();
    }

    /**
     * Send escalation email to the responsible agent.
     */
    private function sendEscalationEmail($slaTicket, $ticketInfo, $ticketAge)
    {
        $finalEscLevel = $slaTicket->escalate_level;
        $finalSubcatId = $slaTicket->subcat_id;

        $ticketData = [
            'ticket_number' => $slaTicket->ticket_number,
            'level_id' => $slaTicket->level_id,
            'user_id' => $slaTicket->user_id,
            'cat_id' => $ticketInfo->cat_id,
            'subcat_id' => $finalSubcatId,
            'client_id_helpdesk' => $ticketInfo->client_id_helpdesk,
            'business_entity_id' => $ticketInfo->business_entity_id,
            'created_at' => $ticketInfo->created_at,
            'ticketAge' => $ticketAge,
        ];

        $agentId = DB::table('escalate_srv_time_agent_subcategories')
            ->where('level_id', $finalEscLevel)
            ->where('subcat_id', $finalSubcatId)
            ->value('agent_id');

        $recipientEmail = DB::table('escalate_srv_time_agent_subcategories as es')
            ->join('users as u', 'es.agent_id', '=', 'u.id')
            ->where('es.level_id', $finalEscLevel)
            ->where('es.subcat_id', $finalSubcatId)
            ->where('es.agent_id', $agentId)
            ->value('u.email_primary');

        $emailTemplate = DB::table('email_templates')
            ->where('id', 7)
            ->where('status', 'Active')
            ->first(['subject', 'content']);

        $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
        $emailResult = $emailController->sendEmailNotification($ticketData, $slaTicket->team_id, $emailTemplate, $recipientEmail);

        if ($emailResult->status() !== 200) {
            throw new \Exception('Email sending failed');
        }
    }

    /**
     * Update escalation level and log the ticket history.
     */
    private function updateTicketEscalation($slaTicket, $newLevel)
    {
        $slaTicket->update([
            'status' => 2,
            'status_name' => 'failed',
        ]);

        TicketSrvTimeEscTeamHistory::create($slaTicket->toArray());

        $slaTicket->update([
            'escalate_level' => $newLevel,
        ]);
    }

    /**
     * Handle final escalation logic when the maximum level is reached.
     */
    private function handleFinalEscalation($slaTicket, $ticketInfo, $currentLevel, $ticketAge)
    {
        $escalationData = EscalateSrvTimeSubcategory::firstWhere([
            'team_id' => $slaTicket->team_id,
            'subcat_id' => $slaTicket->subcat_id,
            'level_id' => $slaTicket->escalate_level,
        ]);

        if (!$escalationData) {
            return; // Skip if escalation data is not found
        }

        $existingTicket = TicketSrvTimeEscTeam::where([
            'team_id' => $escalationData->team_id,
            'subcat_id' => $escalationData->subcat_id,
            'escalate_level' => $slaTicket->escalate_level,
            'status' => 0,
        ])->first();

        if (!$existingTicket) {
            $newTicket = TicketSrvTimeEscTeam::create([
                'ticket_number' => $slaTicket->ticket_number,
                'subcat_id' => $slaTicket->subcat_id,
                'team_id' => $ticketInfo->team_id,
                'escalate_id' => $escalationData->id,
                'escalate_level' => $slaTicket->escalate_level,
                'escalate_srv_response_time' => $escalationData->notification_min,
                'notification_status' => $escalationData->send_email_status,
                'status' => 0,
                'status_name' => 'started',
            ]);

            TicketSrvTimeEscTeamHistory::create($newTicket->toArray());
        } elseif ($ticketAge > $existingTicket->escalate_srv_response_time) {
            $this->sendEscalationEmail($existingTicket, $ticketInfo, $ticketAge);
            $this->updateTicketEscalation($existingTicket, $slaTicket->escalate_level + 1);
        }
    }

    public function firstResponseSlaCheck()
    {
        try{
        FirstResSla::chunk(100, function ($slas) {
                foreach ($slas as $sla) {
                    TicketInfo::processFirstResponseSla(
                        $sla->ticket_number,
                        $sla->created_at
                    );
                }
            });
        }catch(\Exception $e){
        ApiResponse::error('First Response SLA Check Failed', 'Error', 500);
        }
        
    }

    public function serviceTimeClientSlaCheck()
    {
        try{
        SrvTimeClientSla::chunk(100, function ($slas) {
                foreach ($slas as $sla) {
                    TicketInfo::serviceTimeSlaClient(
                        $sla->ticket_number,
                        $sla->created_at
                    );
                }
            });
        }catch(\Exception $e){
        ApiResponse::error('First Response SLA Check Failed', 'Error', 500);
        }
    }

    public function serviceTimeSubCategorySlaCheck()
    {
        try{
        SrvTimeSubcatSla::chunk(100, function ($slas) {
                foreach ($slas as $sla) {
                    TicketInfo::processJobServiceTimeSlaSubcategory(
                        $sla->ticket_number,
                        $sla->created_at
                    );
                }
            });
        }catch(\Exception $e){
        ApiResponse::error('First Response SLA Check Failed', 'Error', 500);
        }
    }

}
