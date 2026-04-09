<?php

namespace App\Logic;

use App\Helpers\ApiResponse;
use App\Http\Controllers\v1\Settings\EmailController;
use App\Mail\EmailNotification;
use App\Models\SelfTicket;
use App\Models\OpenTicket;
use App\Models\SlaClient;
use App\Models\SlaSubcategory;
use App\Models\Team;
use App\Models\Ticket;
use App\Models\TicketCommentAttachment;
use App\Models\TicketAttachment;
use App\Models\TicketBackbone;
use App\Models\TicketCc;
use App\Models\CloseTicket;
use App\Models\TicketFrTimeClient;
use App\Models\TicketFrTimeClientHistory;
use App\Models\TicketFrTimeEscClient;
use App\Models\TicketFrTimeEscClientHistory;
use App\Models\TicketFrTimeTeam;
use App\Models\TicketFrTimeTeamHistory;
use App\Models\TicketHistory;
use App\Models\TicketOrbit;
use App\Models\TicketSrvTimeClient;
use App\Models\TicketSrvTimeClientHistory;
use App\Models\TicketSrvTimeEscClient;
use App\Models\TicketSrvTimeTeam;
use App\Models\TicketSrvTimeTeamHistory;
use App\Http\Controllers\SendSmsController;
use App\Models\User;
use App\Models\UserClientMapping;
use App\Models\UserEntityMapping;
use App\Models\TicketAggregator;
use App\Models\TicketTrackingToken;
use App\Models\TicketAssignAgentLog;
use App\Models\TicketAssignTeamLog;
use App\Models\TicketBranch;
use App\Models\FirstResConfig;
use App\Models\FirstResSla;
use App\Models\FirstResSlaHistory;
use App\Models\SlaClientConfig;
use App\Models\SlaSubcatConfig;
use App\Models\SrvTimeClientSla;
use App\Models\SrvTimeClientSlaHistory;
use App\Models\SrvTimeSubcatSla;
use App\Models\SrvTimeSubcatSlaHistory;
use App\Models\TicketDivisionDistrict;
use App\Models\TicketPriority;
use App\Models\TicketStatus;
use App\Models\Company;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use App\Jobs\SendCustomerSmsJob;
class TicketInfo
{
    public $clientId;

    // Constructor to set clientId
    public function __construct($clientId)
    {
        $this->clientId = $clientId;
    }

    public static function getTicketAge($ticketNumber)
    {
        $ticket = Ticket::where('ticket_number', $ticketNumber)->first();
        if (!$ticket) {
            return null;
        }
        $current_time = Carbon::now();
        $ticket_created_at = $ticket->created_at;
        $ticketAge = $current_time->diffInMinutes($ticket_created_at);
        return $ticketAge;
    }

    public static function ticketNumberGenarateForSelfTicket()
    {

        $yearSuffix = date('y');
        $latestCode = SelfTicket::where('ticket_number', 'LIKE', $yearSuffix . '%')
            ->orderBy('ticket_number', 'desc')
            ->first();
        if ($latestCode) {

            $currentNumber = (int)substr($latestCode->ticket_number, 2);
            $nextNumber = $currentNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $newCode = $yearSuffix . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);

        return $newCode;
    }




    public static function ticketNumberGenarate()
    {
        $year = date('y');

        $latestOpenTicket = OpenTicket::where('ticket_number', 'LIKE', $year.'%')
            ->orderBy('ticket_number', 'desc')
            ->value('ticket_number');

        $latestCloseTicket = CloseTicket::where('ticket_number', 'LIKE', $year.'%')
            ->orderBy('ticket_number', 'desc')
            ->value('ticket_number');

        $latestSelfTicket = SelfTicket::where('ticket_number', 'LIKE', $year.'%')
            ->orderBy('ticket_number', 'desc')
            ->value('ticket_number');

        $lastNumber = max(
            (int)substr($latestOpenTicket ?? $year.'000000', 2),
            (int)substr($latestCloseTicket ?? $year.'000000', 2),
            (int)substr($latestSelfTicket ?? $year.'000000', 2)
        );

        return $year . str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
    }



    public function isClientSLA($clientId, $subcat_id)
    {
        return SlaClient::where('client_id', $clientId)->where('subcat_id', $subcat_id)->first();
    }

    public function isSubcategorySLA($teamId, $subcat_id)
    {
        // return [$teamId, $subcat_id];
        return SlaSubcategory::where('team_id', $teamId)->where('subcat_id', $subcat_id)->first();
    }

    public static function isSubcategorySLA2($teamId, $subcat_id)
    {
        return SlaSubcategory::where('team_id', $teamId)->where('subcat_id', $subcat_id)->first();
    }

    public static function getIdleTime($teamId, $responseTime)
    {


        $idleTime = Team::select('id', 'idle_start_hr', 'idle_start_min', 'idle_end_hr', 'idle_end_min', 'idle_start_end_diff_min')
            ->find($teamId);
        if ($idleTime) {
            $start = "{$idleTime->idle_start_hr}:{$idleTime->idle_start_min}:00";
            $end = "{$idleTime->idle_end_hr}:{$idleTime->idle_end_min}:00";
            $currentTime = new DateTime();
            $startTime = new DateTime($start);
            $endTime = new DateTime($end);

            $currentTimeStr = $currentTime->format("H:i:s");
            $startTimeStr = $startTime->format("H:i:s");
            $endTimeStr = $endTime->format("H:i:s");

            if ($currentTimeStr < $startTimeStr) {
                $interval = $startTime->diff($currentTime);
                $diffStartMinusCurrentTimeInMinutes = ($interval->h * 60) + $interval->i;
                if ($diffStartMinusCurrentTimeInMinutes < $responseTime) {
                    return $responseTime += $idleTime->idle_start_end_diff_min;
                }
            } elseif ($currentTimeStr >= $startTimeStr && $currentTimeStr < $endTimeStr) {
                $interval = $endTime->diff($currentTime);
                $differenceInMinutes = ($interval->h * 60) + $interval->i;
                return $responseTime += $differenceInMinutes;
            }

            return $responseTime;
        }
    }



    public static function getTicketAgeWithIdleTime($teamId, $ticketNumber)
    {
        // Get team idle time configuration
        $idleTime = Team::select(
            'id',
            'idle_start_hr',
            'idle_start_min',
            'idle_end_hr',
            'idle_end_min',
            'idle_start_end_diff_min'
        )->find($teamId);

        if (!$idleTime) {
            // If no team found, return simple age calculation
            $srvTimeSubcatSla = SrvTimeSubcatSla::where('ticket_number', $ticketNumber)->first();
            $currentTime = new DateTime();
            $ticketCreateDateTime = new DateTime($srvTimeSubcatSla->created_at);
            return self::formatTicketAge($currentTime->getTimestamp() - $ticketCreateDateTime->getTimestamp());
        }

        // Get SrvTimeSubcatSla record for the ticket
        $srvTimeSubcatSla = SrvTimeSubcatSla::where('ticket_number', $ticketNumber)->first();

        // Create DateTime objects
        $currentTime = new DateTime();
        $ticketCreateDateTime = new DateTime($srvTimeSubcatSla->created_at);

        // Build idle time strings
        $idleStartTime = new DateTime("{$idleTime->idle_start_hr}:{$idleTime->idle_start_min}:00");
        $idleEndTime = new DateTime("{$idleTime->idle_end_hr}:{$idleTime->idle_end_min}:00");

        // Format times for comparison
        $currentTimeStr = $currentTime->format("H:i:s");
        $ticketCreateTimeStr = $ticketCreateDateTime->format("H:i:s");
        $idleStartTimeStr = $idleStartTime->format("H:i:s");
        $idleEndTimeStr = $idleEndTime->format("H:i:s");

        // Calculate age in seconds
        $ageInSeconds = 0;

        // Condition 1: current_time > idle_start_time AND current_time < idle_end_time
        // AND ticket_create_time < idle_start_time
        if ($currentTimeStr > $idleStartTimeStr && $currentTimeStr < $idleEndTimeStr
            && $ticketCreateTimeStr < $idleStartTimeStr) {

            $totalDiff = $currentTime->getTimestamp() - $ticketCreateDateTime->getTimestamp();
            $idleDiff = $currentTime->getTimestamp() - $idleStartTime->getTimestamp();

            $ageInSeconds = $totalDiff - $idleDiff;
        }

        // Condition 2: current_time > idle_start_time AND current_time > idle_end_time
        // AND ticket_create_time < idle_start_time
        elseif ($currentTimeStr > $idleStartTimeStr && $currentTimeStr > $idleEndTimeStr
                && $ticketCreateTimeStr < $idleStartTimeStr) {

            $totalDiff = $currentTime->getTimestamp() - $ticketCreateDateTime->getTimestamp();
            $idleStartDiff = $idleStartTime->getTimestamp() - $idleEndTime->getTimestamp();
            $idleEndDiff = $currentTime->getTimestamp() - $idleEndTime->getTimestamp();

            $ageInSeconds = $totalDiff - abs($idleStartDiff) + $idleEndDiff;

            // return [$ticketCreateDateTime,$ageInSeconds,$totalDiff,$idleStartDiff,$idleEndDiff,$currentTimeStr,$ticketCreateTimeStr,$idleStartTimeStr,$idleEndTimeStr];
        }

        // Condition 3: current_time > idle_start_time AND current_time > idle_end_time
        // AND ticket_create_time > idle_start_time AND ticket_create_time < idle_end_time
        elseif ($currentTimeStr > $idleStartTimeStr && $currentTimeStr > $idleEndTimeStr
                && $ticketCreateTimeStr > $idleStartTimeStr && $ticketCreateTimeStr < $idleEndTimeStr) {

            $idleEndDiff = $currentTime->getTimestamp() - $idleEndTime->getTimestamp();

            $ageInSeconds = $idleEndDiff;
        }

        // Condition 4: current_time > idle_start_time AND current_time > idle_end_time
        // AND ticket_create_time > idle_start_time AND ticket_create_time > idle_end_time
        elseif ($currentTimeStr > $idleStartTimeStr && $currentTimeStr > $idleEndTimeStr
                && $ticketCreateTimeStr > $idleStartTimeStr && $ticketCreateTimeStr > $idleEndTimeStr) {

            $ageInSeconds = $currentTime->getTimestamp() - $ticketCreateDateTime->getTimestamp();
        }

        // Default: No idle time conditions met
        else {
            $ageInSeconds = $currentTime->getTimestamp() - $ticketCreateDateTime->getTimestamp();
        }

        // Return formatted age in minutes
        return self::formatTicketAge($ageInSeconds);
    }

    /**
     * Format ticket age in minutes
     */
    private static function formatTicketAge($seconds)
    {
        $minutes = intval($seconds / 60);
        return $minutes;
    }




    public static function getEffectiveSlaMinutes($teamId, $ticketNumber)
    {
        // 1️⃣ Get ticket creation time
        $ticket = SrvTimeSubcatSla::where('ticket_number', $ticketNumber)->first();
        if (!$ticket) return 0;

        $ticketCreate = new DateTime($ticket->created_at);
        $now = new DateTime();

        // 2️⃣ Get team idle configuration
        $team = Team::find($teamId);
        if (!$team) {
            // No idle configured, return simple difference
            return intval(($now->getTimestamp() - $ticketCreate->getTimestamp()) / 60);
        }

        // 3️⃣ Build idle interval(s)
        // For simplicity assuming 1 idle window per team.
        // You can extend to multiple idle windows by looping over array
        $idleStart = (clone $ticketCreate)->setTime($team->idle_start_hr, $team->idle_start_min, 0);
        $idleEnd = (clone $ticketCreate)->setTime($team->idle_end_hr, $team->idle_end_min, 0);

        // Handle case where idleEnd < idleStart (overnight)
        if ($idleEnd < $idleStart) {
            $idleEnd->modify('+1 day');
        }

        // 4️⃣ Calculate effective SLA
        $totalSeconds = $now->getTimestamp() - $ticketCreate->getTimestamp();

        // Calculate overlap between ticket timeline and idle window
        $idleOverlap = 0;
        $ticketStart = $ticketCreate->getTimestamp();
        $ticketEnd = $now->getTimestamp();
        $idleStartTs = $idleStart->getTimestamp();
        $idleEndTs = $idleEnd->getTimestamp();

        // Overlap calculation
        $overlapStart = max($ticketStart, $idleStartTs);
        $overlapEnd = min($ticketEnd, $idleEndTs);

        if ($overlapEnd > $overlapStart) {
            $idleOverlap = $overlapEnd - $overlapStart;
        }

        // 5️⃣ Effective SLA = total - idle overlap
        $effectiveSeconds = max($totalSeconds - $idleOverlap, 0);

        // 6️⃣ Convert to minutes
        $effectiveMinutes = intval($effectiveSeconds / 60);

        return $effectiveMinutes;
    }




    // public static function createTicket($ticketData, $ccEmail, $attachments, $backboneData, $orbitData,$branchId,$aggregatorId)
    // {

    //     if (!isset($ticketData['client_id_vendor'])) {
    //         return ApiResponse::error('Client ID is required', "Error", 400);
    //     }

    //     $clientId = $ticketData['client_id_vendor'];
    //     $teamId = $ticketData['team_id'];
    //     $subCatId = $ticketData['subcat_id'];

    //     // Check if the client has an SLA
    //     $checkClientSla = (new self($clientId))->isClientSLA($clientId, $subCatId);
    //     $checkSubcategorySla = (new self($teamId))->isSubcategorySLA($teamId, $subCatId);


    //     DB::beginTransaction();
    //     try {
    //         $ticketNo = OpenTicket::create($ticketData);
    //         TicketHistory::create($ticketData);

    //        if($branchId){
    //             TicketBranch::create([
    //                 'ticket_number' => $ticketData['ticket_number'],
    //                 'branch_id' => $branchId,
    //             ])
    //         }

    //         if($aggregatorId){
    //             TicketAggregator::create([
    //                 'ticket_number' => $ticketData['ticket_number'],
    //                 'aggregator_id' => $aggregatorId,
    //             ])
    //         };
    //         TicketAssignAgentLog::create([
    //             'ticket_number' => $ticketData['ticket_number'],
    //             'assigned_in' => $ticketData['user_id'],
    //             'assigned_out' => null,
    //         ]);

    //         TicketAssignTeamLog::create([
    //             'ticket_number' => $ticketData['ticket_number'],
    //             'assigned_in' => $teamId,
    //             'assigned_out' => null,
    //         ]);


    //         if ($ticketData['business_entity_id'] == 8 || $ticketData['business_entity_id'] == 9) {
    //             TicketOrbit::create($orbitData);
    //         }


    //         if ($ticketData['business_entity_id'] == 7) {
    //             TicketBackbone::create([
    //                 'ticket_number' => $ticketData['ticket_number'],
    //                 'backbone_element_id' => $backboneData['backbone_element_id'],
    //                 'backbone_element_list_id' => $backboneData['elementList'],
    //                 'backbone_element_list_id_a_end' => $backboneData['elementListA'],
    //                 'backbone_element_list_id_b_end' => $backboneData['elementListB'],
    //             ]);
    //         }

    //         $agentEmailList = User::whereIn('id', $ccEmail)->get();

    //         if ($ccEmail) {
    //             foreach ($agentEmailList as $email) {
    //                 TicketCc::create([
    //                     'ticket_number' => $ticketData['ticket_number'],
    //                     'agent_id' => $ticketData['user_id'],
    //                     'agent_email' => $email->email_primary
    //                 ]);
    //             }
    //         }

    //         // for multiple attachtment
    //         if (!empty($attachments)) {

    //             foreach ($attachments as $file) {
    //                 $fileExtension = $file->getClientOriginalExtension();
    //                 $orginalName = $file->getClientOriginalName();
    //                 $customizeName = time() . '_' . uniqid() . '.' . $fileExtension;
    //                 $size = $file->getSize();
    //                 $mimeType = $file->getMimeType();
    //                 $filePath = $file->storeAs('ticket_attachments', $customizeName, 'public');
    //                 $attached[] = 'storage/' . $filePath;

    //                 TicketAttachment::create([
    //                     'ticket_number' => $ticketData['ticket_number'],
    //                     'name' => $orginalName,
    //                     'customize_name' => $customizeName,
    //                     'size' => $size,
    //                     'url' => 'storage/' . $filePath,
    //                     'mime_type' => $mimeType,
    //                     'storage_type' => 'local',
    //                 ]);
    //             }
    //         }

    //         $ticketNoForEmail = [
    //             'ticket_number' => $ticketNo->ticket_number,
    //             'user_id' => $ticketNo->user_id,
    //             'cat_id' => $ticketNo->cat_id,
    //             'subcat_id' => $ticketNo->subcat_id,
    //             'client_id_helpdesk' => $ticketNo->client_id_helpdesk,
    //             'business_entity_id' => $ticketNo->business_entity_id,
    //             'created_at' => $ticketNo->created_at,
    //             'ticketAge' => '',
    //         ];



    //         $recipientEmail = DB::table('teams')->where('id', $teamId)->value('group_email');


    //         $ccEmailString = implode("','", $ccEmail);
    //         // $agentsEmail = DB::select("SELECT u.email_primary FROM helpdesk.users u WHERE u.id IN ('$ccEmailString')");
    //          $agentsEmail = DB::select("SELECT up.email_primary FROM helpdesk.users u
    //         INNER JOIN user_profiles up ON up.user_id = u.id WHERE u.id IN ('$ccEmailString')");

    //         $agentEmails = array_map(function ($agent) {
    //             return $agent->email_primary;
    //         }, $agentsEmail);

    //         $recipient = implode(',', $agentEmails) . ',' . $recipientEmail;

    //         // for cc email end

    //         $emailTemplate = DB::table('email_templates')
    //             ->where('id', 1)
    //             ->where('status', 'Active')
    //             ->first(['subject', 'content']);


    //         // Client Sla
    //         if ($checkClientSla) {
    //             TicketFrTimeClient::create([
    //                 'ticket_number' => $ticketData['ticket_number'],
    //                 'client_id' => $checkClientSla->client_id,
    //                 'subcat_id' => $checkClientSla->subcat_id,
    //                 'fr_response_id' => $checkClientSla->sla_id,
    //                 'fr_response_time' => $checkClientSla->fr_res_time_min,
    //                 'fr_response_status_id' => 0,
    //                 'fr_response_status_name' => 'started',
    //             ]);
    //             TicketFrTimeClientHistory::create([
    //                 'ticket_number' => $ticketData['ticket_number'],
    //                 'client_id' => $checkClientSla->client_id,
    //                 'subcat_id' => $checkClientSla->subcat_id,
    //                 'fr_response_id' => $checkClientSla->sla_id,
    //                 'fr_response_time' => $checkClientSla->fr_res_time_min,
    //                 'fr_response_status_id' => 0,
    //                 'fr_response_status_name' => 'started',
    //             ]);

    //             TicketSrvTimeClient::create([
    //                 'ticket_number' => $ticketData['ticket_number'],
    //                 'client_id' => $checkClientSla->client_id,
    //                 'subcat_id' => $checkClientSla->subcat_id,
    //                 'srv_time_id' => $checkClientSla->sla_id,
    //                 'srv_time_duration' => $checkClientSla->srv_time_min,
    //                 'srv_time_status' => 0,
    //                 'srv_time_status_name' => 'started',
    //             ]);
    //             TicketSrvTimeClientHistory::create([
    //                 'ticket_number' => $ticketData['ticket_number'],
    //                 'client_id' => $checkClientSla->client_id,
    //                 'subcat_id' => $checkClientSla->subcat_id,
    //                 'srv_time_id' => $checkClientSla->sla_id,
    //                 'srv_time_duration' => $checkClientSla->srv_time_min,
    //                 'srv_time_status' => 0,
    //                 'srv_time_status_name' => 'started',
    //             ]);
    //         } elseif ($checkSubcategorySla) {
    //             // SubCategory Sla
    //             $firstResponseIdleTime = self::getIdleTime($teamId, $checkSubcategorySla->fr_res_time_min);
    //             $serviceResponseIdleTime = self::getIdleTime($teamId, $checkSubcategorySla->srv_time_min);
    //             // return [$firstResponseIdleTime, $serviceResponseIdleTime];
    //             TicketFrTimeTeam::create([
    //                 'ticket_number' => $ticketData['ticket_number'],
    //                 'team_id' => $checkSubcategorySla->team_id,
    //                 'subcat_id' => $checkSubcategorySla->subcat_id,
    //                 'fr_response_id' => $checkSubcategorySla->sla_id,
    //                 'fr_response_time' => $firstResponseIdleTime,
    //                 'fr_response_status' => 0,
    //                 'fr_response_status_name' => 'started',
    //             ]);
    //             TicketFrTimeTeamHistory::create([
    //                 'ticket_number' => $ticketData['ticket_number'],
    //                 'team_id' => $checkSubcategorySla->team_id,
    //                 'subcat_id' => $checkSubcategorySla->subcat_id,
    //                 'fr_response_id' => $checkSubcategorySla->sla_id,
    //                 'fr_response_time' => $firstResponseIdleTime,
    //                 'fr_response_status' => 0,
    //                 'fr_response_status_name' => 'started',
    //             ]);

    //             TicketSrvTimeTeam::create([
    //                 'ticket_number' => $ticketData['ticket_number'],
    //                 'team_id' => $checkSubcategorySla->team_id,
    //                 'subcat_id' => $checkSubcategorySla->subcat_id,
    //                 'srv_time_id' => $checkSubcategorySla->sla_id,
    //                 'srv_time_duration' => $serviceResponseIdleTime,
    //                 'srv_time_status' => 0,
    //                 'srv_time_status_name' => 'started',
    //             ]);
    //             TicketSrvTimeTeamHistory::create([
    //                 'ticket_number' => $ticketData['ticket_number'],
    //                 'team_id' => $checkSubcategorySla->team_id,
    //                 'subcat_id' => $checkSubcategorySla->subcat_id,
    //                 'srv_time_id' => $checkSubcategorySla->sla_id,
    //                 'srv_time_duration' => $serviceResponseIdleTime,
    //                 'srv_time_status' => 0,
    //                 'srv_time_status_name' => 'started',
    //             ]);
    //         }


    //         DB::commit();



    //         $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
    //         $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);

    //         if ($emailResult->status() !== 200) {
    //             return ApiResponse::error('Email sending failed', "Error", 500);
    //         }

    //         // if (!empty($sidNumber)) {
    //         //     $smsController = new SendSmsController();
    //         //     $smsController->checkAndSendSMS(request());
    //         // }

    //         return ApiResponse::success($ticketNo, "Successfully Created", 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }




    public static function ticketCreatedByClient($ticketData, $ccEmail, $attachments, $orbitData)
    {
        DB::beginTransaction();

        try {
            $ticket = OpenTicket::create($ticketData);
            TicketHistory::create($ticketData);

            if ($orbitData) {
                TicketOrbit::create($orbitData);
            }

            if ($ccEmail) {
                foreach ($ccEmail as $email) {
                    TicketCc::create([
                        'ticket_number' => $ticket->ticket_number,
                        'agent_id'      => $ticket->user_id,
                        'agent_email'   => $email,
                    ]);
                }
            }

            foreach ($attachments as $file) {
                $path = $file->store('ticket_attachments', 'public');

                TicketAttachment::create([
                    'ticket_number' => $ticket->ticket_number,
                    'name'          => $file->getClientOriginalName(),
                    'customize_name'=> basename($path),
                    'size'          => $file->getSize(),
                    'url'           => 'storage/'.$path,
                    'mime_type'     => $file->getMimeType(),
                    'storage_type'  => 'local',
                ]);
            }

            DB::commit();
            return ApiResponse::success([], "Successfully Created", 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
// superapp

 public static function ticketCreatedBySupperApp($ticketData, $attachments, $orbitData,$divisionData)
    {
        DB::beginTransaction();

        try {
            $ticket = OpenTicket::create($ticketData);
            TicketHistory::create($ticketData);

            if ($orbitData) {
                TicketOrbit::create($orbitData);
            }
            if($divisionData){
                TicketDivisionDistrict::create([
                    'ticket_number' => $ticket->ticket_number,
                    'division' => $divisionData['division'],
                    'district' => $divisionData['district'],
                    'thana' => $divisionData['thana'],

                ]);
            }

            foreach ($attachments as $file) {
                $path = $file->store('ticket_attachments', 'public');

                TicketAttachment::create([
                    'ticket_number' => $ticket->ticket_number,
                    'name'          => $file->getClientOriginalName(),
                    'customize_name'=> basename($path),
                    'size'          => $file->getSize(),
                    'url'           => 'storage/'.$path,
                    'mime_type'     => $file->getMimeType(),
                    'storage_type'  => 'local',
                ]);
            }

            // self::handleTicketTracking($ticketData['ticket_number']);

            DB::commit();
            $businessEntityName = Company::where('id', $ticket->business_entity_id)->first();

            SendCustomerSmsJob::dispatch([
            'businessEntity' => $businessEntityName->company_name,
            'nature' => $ticket->subcat_id,
            'ticket_number' => $ticket->ticket_number,
            'phone' => $ticket->mobile_no,
            ]);
            return ApiResponse::success($ticket, "Successfully Created", 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    public static function createTicket($ticketData, $ccEmail, $attachments, $backboneData, $orbitData,$branchId,$aggregatorId,$division,$district)
    {
        self::validateTicket($ticketData);

        DB::beginTransaction();
        try {
            $ticket = self::createCoreTicket($ticketData);
            self::handleRelations($ticketData, $branchId, $aggregatorId,$division,$district);
            self::handleBusinessEntity($ticketData, $orbitData, $backboneData);
            self::handleCcUsers($ticketData, $ccEmail);
            self::handleAttachments($ticketData, $attachments);
            self::sendTicketEmail($ticket, $ticketData['team_id'], $ccEmail);
            self::handleFirstResponseSla($ticketData);
            self::handleServiceTimeSla($ticketData);
            self::handleTicketTracking($ticketData['ticket_number']);


            DB::commit();

            // self::sendTicketEmail($ticket, $ticketData['team_id'], $ccEmail);

            return ApiResponse::success($ticket, "Successfully Created", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    private static function validateTicket($ticketData)
    {
        if (!isset($ticketData['client_id_vendor'])) {
            throw new \Exception('Client ID is required');
        }
    }

    private static function createCoreTicket($ticketData)
    {
        $ticket = OpenTicket::create($ticketData);
        TicketHistory::create($ticketData);

        TicketAssignAgentLog::create([
            'ticket_number' => $ticketData['ticket_number'],
            'assigned_in' => $ticketData['assigned_agent_id'],
            'assigned_out' => null,
        ]);

        TicketAssignTeamLog::create([
            'ticket_number' => $ticketData['ticket_number'],
            'assigned_in' => $ticketData['team_id'],
            'assigned_out' => null,
        ]);

        return $ticket;
    }

    private static function handleRelations($ticketData, $branchId, $aggregatorId,$division,$district)
    {
        if ($branchId) {
            TicketBranch::create([
                'ticket_number' => $ticketData['ticket_number'],
                'branch_id' => $branchId,
            ]);
        }

        if ($aggregatorId) {
            TicketAggregator::create([
                'ticket_number' => $ticketData['ticket_number'],
                'aggregator_id' => $aggregatorId,
            ]);
        }
        if($division && $district){
            TicketDivisionDistrict::create([
                'ticket_number' => $ticketData['ticket_number'],
                'division' => $division,
                'district' => $district,
            ]);
        }
    }

    private static function handleCcUsers($ticketData, $ccEmail)
    {
        if (empty($ccEmail)) return;

        // ccEmail may contain user IDs or raw email addresses. Support both.
        $ids = array_values(array_filter($ccEmail, function ($v) {
            return is_numeric($v);
        }));

        $emails = [];
        if (!empty($ids)) {
            $queried = DB::table('users as u')
                ->join('user_profiles as up', 'up.user_id', '=', 'u.id')
                ->whereIn('u.id', $ids)
                ->pluck('up.email_primary')
                ->toArray();
            $emails = array_merge($emails, $queried);
        }

        // Also include any items that are already valid email strings
        $stringEmails = array_values(array_filter($ccEmail, function ($v) {
            return filter_var($v, FILTER_VALIDATE_EMAIL);
        }));

        if (!empty($stringEmails)) {
            $emails = array_merge($emails, $stringEmails);
        }

        // Deduplicate and validate
        $emails = array_unique(array_filter(array_map('trim', $emails), function ($e) {
            return filter_var($e, FILTER_VALIDATE_EMAIL);
        }));

        foreach ($emails as $email) {
            TicketCc::create([
                'ticket_number' => $ticketData['ticket_number'],
                'agent_id' => $ticketData['user_id'],
                'agent_email' => $email
            ]);
        }
    }


    private static function handleAttachments($ticketData, $attachments)
    {
        if (empty($attachments)) return;


                    foreach ($attachments as $file) {

                        $fileExtension = $file->getClientOriginalExtension();
                        $orginalName = $file->getClientOriginalName();
                        $customizeName = time() . '_' . uniqid() . '.' . $fileExtension;
                        $size = $file->getSize();
                        $mimeType = $file->getMimeType();
                        $filePath = $file->storeAs('ticket_attachments', $customizeName, 'public');


                        TicketAttachment::create([
                            'ticket_number' => $ticketData['ticket_number'],
                            'name' => $orginalName,
                            'customize_name' => $customizeName,
                            'size' => $size,
                            'url' => 'storage/' . $filePath,
                            'mime_type' => $mimeType,
                            'storage_type' => 'local',
                        ]);
                    }

    }

    private static function handleBusinessEntity($ticketData, $orbitData, $backboneData)
    {
        if ($ticketData['business_entity_id'] == 8 || $ticketData['business_entity_id'] == 9) {
            TicketOrbit::create($orbitData);
        }

        if ($ticketData['business_entity_id'] == 7) {
            TicketBackbone::create([
                'ticket_number' => $ticketData['ticket_number'],
                'backbone_element_id' => $backboneData['backbone_element_id'],
                'backbone_element_list_id' => $backboneData['elementList'],
                'backbone_element_list_id_a_end' => $backboneData['elementListA'],
                'backbone_element_list_id_b_end' => $backboneData['elementListB'],
            ]);
        }

    }

    private static function handleTicketTracking($ticketNumber)
{
    try {

        $existing = TicketTrackingToken::where('ticket_number', $ticketNumber)->first();

        if ($existing) {
            return;
        }

        $token = Str::random(8);

        $trackingUrl = config('app.frontend_url') . 'ticket-tracker/' . $token;

        TicketTrackingToken::create([
            'ticket_number' => $ticketNumber,
            'token' => $token,
            'tracking_url' => $trackingUrl,
        ]);

    } catch (\Throwable $e) {
        Log::error('Ticket tracking generation failed', [
            'ticket_number' => $ticketNumber,
            'error' => $e->getMessage()
        ]);

    }
}


// voice

public static function ticketCreatedByVoice($ticketData,$attachments, $orbitData)
    {
        DB::beginTransaction();

        try {
            $ticket = OpenTicket::create($ticketData);
            TicketHistory::create($ticketData);

            if ($orbitData) {
                TicketOrbit::create($orbitData);
            }

            foreach ($attachments as $file) {
                $path = $file->store('ticket_audio', 'public');

                TicketAttachment::create([
                    'ticket_number' => $ticket->ticket_number,
                    'name'          => $file->getClientOriginalName(),
                    'customize_name'=> basename($path),
                    'size'          => $file->getSize(),
                    'url'           => 'storage/'.$path,
                    'mime_type'     => $file->getMimeType(),
                    'storage_type'  => 'local',
                ]);
            }

            DB::commit();
            return ApiResponse::success([], "Successfully Created", 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    // private static function sendTicketEmail($ticket, $teamId, $ccEmail)
    // {
    //     $ticketNoForEmail = [
    //         'ticket_number'      => $ticket->ticket_number,
    //         'user_id'            => $ticket->user_id,
    //         'cat_id'             => $ticket->cat_id,
    //         'subcat_id'          => $ticket->subcat_id,
    //         'client_id_helpdesk' => $ticket->client_id_helpdesk,
    //         'business_entity_id' => $ticket->business_entity_id,
    //         'created_at'         => $ticket->created_at,
    //         'ticketAge'          => '',
    //     ];

    //     $recipientEmail = DB::table('teams')->where('id', $teamId)->value('group_email');


    //     $additionalEmailsJson = DB::table('teams')->where('id', $teamId)->value('additional_email');
    //     $additionalEmails = [];
    //     if (!empty($additionalEmailsJson)) {
    //         $decoded = json_decode($additionalEmailsJson, true);
    //         if (is_array($decoded)) {
    //             $additionalEmails = $decoded;
    //         }
    //     }
    //     $agentEmails = [];
    //     if (!empty($ccEmail)) {

    //         $ids = array_values(array_filter($ccEmail, function ($v) {
    //             return is_numeric($v);
    //         }));
    //         $emails = array_values(array_filter($ccEmail, function ($v) {
    //             return filter_var($v, FILTER_VALIDATE_EMAIL);
    //         }));

    //         if (!empty($ids)) {
    //             $queried = DB::table('users as u')
    //                 ->join('user_profiles as up', 'up.user_id', '=', 'u.id')
    //                 ->whereIn('u.id', $ids)
    //                 ->pluck('up.email_primary')
    //                 ->toArray();
    //             $agentEmails = array_merge($agentEmails, $queried);
    //         }

    //         if (!empty($emails)) {
    //             $agentEmails = array_merge($agentEmails, $emails);
    //         }
    //     }


    //     if (!empty($additionalEmails)) {
    //         $agentEmails = array_merge($agentEmails, $additionalEmails);
    //     }


    //     $agentEmails = array_filter(array_map('trim', $agentEmails), function ($e) {
    //         return filter_var($e, FILTER_VALIDATE_EMAIL);
    //     });


    //     $allRecipients = array_filter(array_merge($agentEmails, [$recipientEmail]));


    //     Log::info('TicketEmail - resolved recipients for ticket '.$ticketNoForEmail['ticket_number'].': ' . implode(',', $allRecipients));

    //     $recipient = implode(',', $allRecipients);

    //     $emailTemplate = DB::table('email_templates')
    //         ->where('id', 1)
    //         ->where('status', 'Active')
    //         ->first(['subject', 'content']);

    //     $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
    //     $emailResult = $emailController->sendEmailNotification(
    //         $ticketNoForEmail, $teamId, $emailTemplate, $recipient
    //     );

    //     if ($emailResult->status() !== 200) {
    //         throw new \Exception('Email sending failed');
    //     }
    // }

    private static function sendTicketEmail($ticket, $teamId, $ccEmail)
    {
        $ticketNoForEmail = [
            'ticket_number'      => $ticket->ticket_number,
            'user_id'            => $ticket->user_id,
            'cat_id'             => $ticket->cat_id,
            'subcat_id'          => $ticket->subcat_id,
            'client_id_helpdesk' => $ticket->client_id_helpdesk,
            'business_entity_id' => $ticket->business_entity_id,
            'created_at'         => $ticket->created_at,
            'ticketAge'          => '',
        ];

        // ✅ Step 1A: Try to find template matched by BOTH business_entity_id AND user_id (client_id_helpdesk)
        $matchedTemplate = DB::table('email_templates as et')
            ->join('event_tags as ta', 'et.event_id', '=', 'ta.id')
            ->join('user_client_mappings as ucm', 'ucm.client_id', '=', 'et.client_id')
            ->where('et.status', 'active')
            ->where('et.event_id', 1)
            ->where('et.business_entity_id', $ticket->business_entity_id)
            ->where('ucm.user_id', $ticket->client_id_helpdesk)
            ->first([
                'et.event_id',
                'et.business_entity_id',
                'et.template_name',
                'et.subject',
                'et.content',
                'et.client_id',
                'et.notify_client',
                'ucm.user_id',
            ]);

        // ✅ Step 1B: If no result found for that user, fallback — match by business_entity_id ONLY
        //            In this case, notify_client check is SKIPPED entirely
        $isBusinessEntityOnlyMatch = false;
        if (!$matchedTemplate) {
            Log::info('TicketEmail - no user-specific template found, falling back to business_entity_id only match.');

            $matchedTemplate = DB::table('email_templates as et')
                ->join('event_tags as ta', 'et.event_id', '=', 'ta.id')
                ->join('user_client_mappings as ucm', 'ucm.client_id', '=', 'et.client_id')
                ->where('et.status', 'active')
                ->where('et.event_id', 1)
                ->where('et.business_entity_id', $ticket->business_entity_id)
                ->first([
                    'et.event_id',
                    'et.business_entity_id',
                    'et.template_name',
                    'et.subject',
                    'et.content',
                    'et.client_id',
                    'et.notify_client',
                    'ucm.user_id',
                ]);

            if ($matchedTemplate) {
                $isBusinessEntityOnlyMatch = true; // ✅ flag: skip notify_client check
                Log::info('TicketEmail - business_entity_id only match found: ' . $matchedTemplate->template_name);
            }
        }

        // ✅ Step 2: Resolve team recipients
        $recipientEmail       = DB::table('teams')->where('id', $teamId)->value('group_email');
        $additionalEmailsJson = DB::table('teams')->where('id', $teamId)->value('additional_email');

        $additionalEmails = [];
        if (!empty($additionalEmailsJson)) {
            $decoded = json_decode($additionalEmailsJson, true);
            if (is_array($decoded)) {
                $additionalEmails = $decoded;
            }
        }

        // ✅ Step 3: Resolve CC/agent emails
        $agentEmails = [];
        if (!empty($ccEmail)) {
            $ids = array_values(array_filter($ccEmail, function ($v) {
                return is_numeric($v);
            }));
            $emails = array_values(array_filter($ccEmail, function ($v) {
                return filter_var($v, FILTER_VALIDATE_EMAIL);
            }));

            if (!empty($ids)) {
                $queried = DB::table('users as u')
                    ->join('user_profiles as up', 'up.user_id', '=', 'u.id')
                    ->whereIn('u.id', $ids)
                    ->pluck('up.email_primary')
                    ->toArray();
                $agentEmails = array_merge($agentEmails, $queried);
            }

            if (!empty($emails)) {
                $agentEmails = array_merge($agentEmails, $emails);
            }
        }

        if (!empty($additionalEmails)) {
            $agentEmails = array_merge($agentEmails, $additionalEmails);
        }

        $agentEmails = array_filter(array_map('trim', $agentEmails), function ($e) {
            return filter_var($e, FILTER_VALIDATE_EMAIL);
        });

        // ✅ Step 4: notify_client check — ONLY when matched by both business_entity_id + user_id
        //            SKIPPED entirely when $isBusinessEntityOnlyMatch = true
        if ($matchedTemplate && !$isBusinessEntityOnlyMatch && (int) $matchedTemplate->notify_client === 1) {
            $clientEmailRecord = DB::table('user_client_mappings as ucm')
                ->join('user_profiles as up', 'ucm.user_id', '=', 'up.user_id')
                ->where('up.user_id', $ticket->client_id_helpdesk)
                ->first(['ucm.business_entity_id', 'ucm.user_id', 'ucm.client_id', 'up.email_primary']);

            if ($clientEmailRecord && filter_var($clientEmailRecord->email_primary, FILTER_VALIDATE_EMAIL)) {
                $agentEmails[] = $clientEmailRecord->email_primary;
                Log::info('TicketEmail - notify_client=1, adding client email: ' . $clientEmailRecord->email_primary);
            }
        } elseif ($isBusinessEntityOnlyMatch) {
            Log::info('TicketEmail - business_entity_id only match, notify_client check skipped.');
        } else {
            Log::info('TicketEmail - notify_client=0 or no matched template, skipping client email.');
        }

        // ✅ Step 5: Merge all recipients
        $allRecipients = array_filter(array_merge(array_values($agentEmails), [$recipientEmail]));

        Log::info('TicketEmail - resolved recipients for ticket ' . $ticketNoForEmail['ticket_number'] . ': ' . implode(',', $allRecipients));

        $recipient = implode(',', $allRecipients);

        // ✅ Step 6: Use matched template if found, otherwise fall back to template id=1
        if ($matchedTemplate && !empty($matchedTemplate->subject) && !empty($matchedTemplate->content)) {
            $emailTemplate = $matchedTemplate;
            Log::info('TicketEmail - using matched template: ' . $matchedTemplate->template_name);
        } else {
            Log::info('TicketEmail - no matched template found, falling back to template id=1');
            $emailTemplate = DB::table('email_templates')
                ->where('id', 1)
                ->where('status', 'Active')
                ->first(['subject', 'content']);
        }

        $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
        $emailResult = $emailController->sendEmailNotification(
            $ticketNoForEmail, $teamId, $emailTemplate, $recipient
        );

        if ($emailResult->status() !== 200) {
            throw new \Exception('Email sending failed');
        }
    }

    private static function handleFirstResponseSla($ticketData)
    {
        $teamId = $ticketData['team_id'];
        $confiSla = FirstResConfig::where('team_id', $teamId)->first();

        if ($confiSla) {
        FirstResSla::create([
            'ticket_number' => $ticketData['ticket_number'],
            'first_res_config_id' => $confiSla->id,
            'sla_status' => 2,
        ]);
        FirstResSlaHistory::create([
            'ticket_number' => $ticketData['ticket_number'],
            'first_res_config_id' => $confiSla->id,
            'sla_status' => 2,
        ]);
        }



        }


    private static function handleServiceTimeSla($ticketData)
    {
        $businessEntityId = $ticketData['business_entity_id'];
        $clientId = $ticketData['client_id_vendor'];
        $teamId = $ticketData['team_id'];
        $subCatId = $ticketData['subcat_id'];
        $ticketNumber = $ticketData['ticket_number'];

        // 1. Client-specific SLA
        $slaClientConfig = SlaClientConfig::where('business_entity_id', $businessEntityId)->where('client_id', $clientId)->first();

        if ($slaClientConfig) {
            self::createClientSla($ticketNumber, $slaClientConfig->id);
            return;
        }

        // 2. Subcategory-specific SLA
        $slaSubcatConfig = SlaSubcatConfig::where('business_entity_id', $businessEntityId)
            ->where('team_id', $teamId)
            ->where('subcategory_id', $subCatId)
            ->first();

        if ($slaSubcatConfig) {
            self::createSubcatSla($ticketNumber, $slaSubcatConfig->id);
        }
    }

    private static function createClientSla($ticketNumber, $slaClientConfigId)
    {
        SrvTimeClientSla::create([
            'ticket_number'        => $ticketNumber,
            'sla_client_config_id' => $slaClientConfigId,
            'sla_status'           => 2,
        ]);

        SrvTimeClientSlaHistory::create([
            'ticket_number'        => $ticketNumber,
            'sla_client_config_id' => $slaClientConfigId,
            'sla_status'           => 2,
        ]);
    }


    private static function createSubcatSla($ticketNumber, $slaSubcatConfigId)
    {
        SrvTimeSubcatSla::create([
            'ticket_number'        => $ticketNumber,
            'sla_subcat_config_id' => $slaSubcatConfigId,
            'sla_status'           => 2,
        ]);

        SrvTimeSubcatSlaHistory::create([
            'ticket_number'        => $ticketNumber,
            'sla_subcat_config_id' => $slaSubcatConfigId,
            'sla_status'           => 2,
        ]);
    }


    public static function storeCommentAttachment($attachments, $comment, $ticketNumber): void
    {
        // $customName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        // $path = $file->storeAs('comments', $customName, 'public');

        // TicketCommentAttachment::create([
        //     'comment_id'    => $comment->id,
        //     'ticket_number' => $ticketNumber,
        //     'name'          => $file->getClientOriginalName(),
        //     'customize_name'=> $customName,
        //     'size'          => $file->getSize(),
        //     'url'           => 'storage/' . $path,
        //     'mime_type'     => $file->getMimeType(),
        //     'storage_type'  => 'local',
        // ]);


        if (empty($attachments)) return;


                    foreach ($attachments as $file) {

                        $fileExtension = $file->getClientOriginalExtension();
                        $orginalName = $file->getClientOriginalName();
                        $customizeName = time() . '_' . uniqid() . '.' . $fileExtension;
                        $size = $file->getSize();
                        $mimeType = $file->getMimeType();
                        $filePath = $file->storeAs('comments', $customizeName, 'public');

                        TicketCommentAttachment::create([
                            'comment_id'    => $comment->id,
                            'ticket_number' => $ticketNumber,
                            'name'          => $orginalName,
                            'customize_name'=> $customizeName,
                            'size'          => $size,
                            'url'           => 'storage/' . $filePath,
                            'mime_type'     => $mimeType,
                            'storage_type'  => 'local',
                        ]);
                    }
    }




/// sla
// public static function processFirstResponseSla( $ticketNumber, $ticketUpdatedAt): void
// {
//             $firstResSla = FirstResSla::where('ticket_number', $ticketNumber)->first();

//             if (!$firstResSla) {
//                 return ;
//             }

//             $firstResSlaConfig = FirstResConfig::where('id', $firstResSla->first_res_config_id)
//                 ->where('first_response_status', 1)
//                 ->first();

//             if (!$firstResSlaConfig) {
//                 return ;
//             }

//             // Calculate ticket age
//             $ticketAgeMinutes = now()->diffInMinutes($ticketUpdatedAt);

//             // Determine SLA status
//             $allowedMinutes = (int) $firstResSlaConfig->duration_min;
//             $slaStatus = $ticketAgeMinutes <= $allowedMinutes ? 1 : 0;

//             // Update SLA
//             $firstResSla->update([
//                 'sla_status' => $slaStatus
//             ]);

//             // Insert history
//             FirstResSlaHistory::create([
//                 'ticket_number'       => $firstResSla->ticket_number,
//                 'first_res_config_id' => $firstResSla->first_res_config_id,
//                 'sla_status'          => $slaStatus,
//             ]);

//             // Remove active SLA record
//             $firstResSla->delete();


// }

    public static function processFirstResponseSla($ticketNumber, $ticketUpdatedAt = null): void
    {
        $firstResSla = FirstResSla::where('ticket_number', $ticketNumber)->first();

        if (!$firstResSla) {
            return;
        }

        $firstResSlaConfig = FirstResConfig::where('id', $firstResSla->first_res_config_id)
            ->where('first_response_status', 1)
            ->first();

        if (!$firstResSlaConfig) {
            return;
        }

        // Calculate ticket age
        $ticketAgeMinutes = now()->diffInMinutes($ticketUpdatedAt);

        // Determine SLA status
        $allowedMinutes = (int) $firstResSlaConfig->duration_min;
        $isBreached = $ticketAgeMinutes > $allowedMinutes;

          if (!$isBreached) {
              return;
          }

        $slaStatus = 0;

        // Update SLA
        $firstResSla->update([
            'sla_status' => $slaStatus
        ]);

        // Insert history
        FirstResSlaHistory::create([
            'ticket_number' => $firstResSla->ticket_number,
            'first_res_config_id' => $firstResSla->first_res_config_id,
            'sla_status' => $slaStatus,
        ]);

        // Remove active SLA record
        $firstResSla->delete();
    }

    public static function commentFirstResponseSla($ticketNumber, $ticketUpdatedAt = null): void
    {
        $firstResSla = FirstResSla::where('ticket_number', $ticketNumber)->first();

        if (!$firstResSla) {
            return;
        }

        $firstResSlaConfig = FirstResConfig::where('id', $firstResSla->first_res_config_id)
            ->where('first_response_status', 1)
            ->first();

        if (!$firstResSlaConfig) {
            return;
        }

        // Calculate ticket age
        $ticketAgeMinutes = now()->diffInMinutes($ticketUpdatedAt);


        // Determine SLA status
        $allowedMinutes = (int) $firstResSlaConfig->duration_min;
        $slaStatus = $ticketAgeMinutes <= $allowedMinutes ? 1 : 0;


        // Update SLA
        $firstResSla->update([
            'sla_status' => $slaStatus
        ]);

        // Insert history
        FirstResSlaHistory::create([
            'ticket_number' => $firstResSla->ticket_number,
            'first_res_config_id' => $firstResSla->first_res_config_id,
            'sla_status' => $slaStatus,
        ]);

        // Remove active SLA record
        $firstResSla->delete();
    }


    public static function firstResponseSla( $ticketNumber, $ticketUpdatedAt,$teamId): void
    {

        $firstResSla = FirstResSla::where('ticket_number', $ticketNumber)->first();

        if ($firstResSla) {
            $firstResSlaConfig = FirstResConfig::where('id', $firstResSla->first_res_config_id)
                ->where('first_response_status', 1)
                ->first();

            if ($firstResSlaConfig) {
                // Calculate ticket age
                $ticketAgeMinutes = now()->diffInMinutes($ticketUpdatedAt);

                // Determine SLA status
                $allowedMinutes = (int) $firstResSlaConfig->duration_min;
                $slaStatus = $ticketAgeMinutes <= $allowedMinutes ? 1 : 0;

                // Update SLA
                $firstResSla->update([
                    'sla_status' => $slaStatus
                ]);

                // Insert history
                FirstResSlaHistory::create([
                    'ticket_number'       => $firstResSla->ticket_number,
                    'first_res_config_id' => $firstResSla->first_res_config_id,
                    'sla_status'          => $slaStatus,
                ]);

                // Remove active SLA record
                $firstResSla->delete();
            }
        }

        // =========================================================
        // 6. START NEW SLA BASED ON TEAM
        // =========================================================
        if (!$teamId) {
            return;
        }


        // Fetch First Response Config for this team
        $newFirstResConfig = FirstResConfig::where('team_id', $teamId)
            ->where('first_response_status', 1)
            ->first();

        if (!$newFirstResConfig) {
            return;
        }

        // ===============================
        // 7. INSERT NEW ACTIVE SLA
        // ===============================
        FirstResSla::create([
            'ticket_number'       => $ticketNumber,
            'first_res_config_id' => $newFirstResConfig->id,
            'sla_status'          => 2,
        ]);

        // ===============================
        // 8. INSERT NEW SLA HISTORY
        // ===============================
        FirstResSlaHistory::create([
            'ticket_number'       => $ticketNumber,
            'first_res_config_id' => $newFirstResConfig->id,
            'sla_status'          => 2,
        ]);

    }

    public static function serviceTimeSlaClient( $ticketNumber, $ticketUpdatedAt): void
    {
        $clientSla = SrvTimeClientSla::where('ticket_number', $ticketNumber)->first();

        if (!$clientSla) {
            return;
        }

        $clientSlaConfig = SlaClientConfig::where('id', $clientSla->sla_client_config_id)
            ->where('sla_status', 1)
            ->first();

        if (!$clientSlaConfig) {
            return;
        }

        // Calculate ticket age
        $ticketAgeMinutes = now()->diffInMinutes($ticketUpdatedAt);

        // Determine SLA status
        $allowedMinutes = (int) $clientSlaConfig->resolution_min;
        $slaStatus = $ticketAgeMinutes <= $allowedMinutes ? 1 : 0;

        // Update SLA
        $clientSla->update([
            'sla_status' => $slaStatus
        ]);

        // Insert history
        SrvTimeClientSlaHistory::create([
            'ticket_number'       => $clientSla->ticket_number,
            'sla_client_config_id' => $clientSla->sla_client_config_id,
            'sla_status'          => $slaStatus,
        ]);

        // Remove active SLA record
        $clientSla->delete();

    }

    public static function serviceTimeSlaSubcategory( $ticketNumber, $ticketUpdatedAt,$teamId): void
    {
        $subCategorySla = SrvTimeSubcatSla::where('ticket_number', $ticketNumber)->first();

        if (!$subCategorySla) {
            return;
        }

        $subCategorySlaConfig = SlaSubcatConfig::where('id', $subCategorySla->sla_subcat_config_id)
            ->where('sla_status', 1)
            ->first();

        if (!$subCategorySlaConfig) {
            return;
        }

        // Calculate ticket age
        $ticketAgeMinutes = now()->diffInMinutes($ticketUpdatedAt);

        // Determine SLA status
        $allowedMinutes = (int) $subCategorySlaConfig->resolution_min;
        $slaStatus = $ticketAgeMinutes <= $allowedMinutes ? 1 : 0;

        // Update SLA
        $subCategorySla->update([
            'sla_status' => $slaStatus
        ]);

        // Insert history
        SrvTimeSubcatSlaHistory::create([
            'ticket_number'       => $subCategorySla->ticket_number,
            'sla_subcat_config_id' => $subCategorySla->sla_subcat_config_id,
            'sla_status'          => $slaStatus,
        ]);

        // Remove active SLA record
        $subCategorySla->delete();

        // =========================================================
        // 6. START NEW SLA BASED ON TEAM
        // =========================================================

        // Fetch ticket to get team_id

        if (!$teamId) {
            return;
        }

        // Fetch First Response Config for this team
        $newSubCategorySlaConfig = SlaSubcatConfig::where('team_id', $teamId)
            ->where('sla_status', 1)
            ->first();

        if (!$newSubCategorySlaConfig) {
            return;
        }

        // ===============================
        // 7. INSERT NEW ACTIVE SLA
        // ===============================
        SrvTimeSubcatSla::create([
            'ticket_number'       => $ticketNumber,
            'sla_subcat_config_id' => $newSubCategorySlaConfig->id,
            'sla_status'          => 2,
        ]);
        // ===============================
        // 8. INSERT NEW SLA HISTORY
        // ===============================
        SrvTimeSubcatSlaHistory::create([
            'ticket_number'       => $ticketNumber,
            'sla_subcat_config_id' => $newSubCategorySlaConfig->id,
            'sla_status'          => 2,
        ]);

    }

// public static function processServiceTimeSlaSubcategory( $ticketNumber, $ticketUpdatedAt): void
// {
//     $subCategorySla = SrvTimeSubcatSla::where('ticket_number', $ticketNumber)->first();

//     if (!$subCategorySla) {
//         return;
//     }

//     $subCategorySlaConfig = SlaSubcatConfig::where('id', $subCategorySla->sla_subcat_config_id)
//         ->where('sla_status', 1)
//         ->first();

//     if (!$subCategorySlaConfig) {
//         return;
//     }

//     // Calculate ticket age
//     $ticketAgeMinutes = now()->diffInMinutes($ticketUpdatedAt);

//     // Determine SLA status
//     $allowedMinutes = (int) $subCategorySlaConfig->resolution_min;
//     $slaStatus = $ticketAgeMinutes <= $allowedMinutes ? 1 : 0;

//     // Update SLA
//     $subCategorySla->update([
//         'sla_status' => $slaStatus
//     ]);

//     // Insert history
//     SrvTimeSubcatSlaHistory::create([
//         'ticket_number'       => $subCategorySla->ticket_number,
//         'sla_subcat_config_id' => $subCategorySla->sla_subcat_config_id,
//         'sla_status'          => $slaStatus,
//     ]);

//     // Remove active SLA record
//     $subCategorySla->delete();

// }



    public static function processServiceTimeSlaSubcategory($ticketNumber, $ticketUpdatedAt = null): void
    {
        $subCategorySla = SrvTimeSubcatSla::where('ticket_number', $ticketNumber)->first();

        if (!$subCategorySla) {
            return;
        }

        $subCategorySlaConfig = SlaSubcatConfig::where('id', $subCategorySla->sla_subcat_config_id)
            ->where('sla_status', 1)
            ->first();

        if (!$subCategorySlaConfig) {
            return;
        }

        // Calculate ticket age
        $ticketAgeMinutes = now()->diffInMinutes($ticketUpdatedAt);

        // Determine SLA status
        $allowedMinutes = (int) $subCategorySlaConfig->resolution_min;
        $slaStatus = $ticketAgeMinutes <= $allowedMinutes ? 1 : 0;

        // Update SLA
        $subCategorySla->update([
            'sla_status' => $slaStatus
        ]);

        // Insert history
        SrvTimeSubcatSlaHistory::create([
            'ticket_number' => $subCategorySla->ticket_number,
            'sla_subcat_config_id' => $subCategorySla->sla_subcat_config_id,
            'sla_status' => $slaStatus,
        ]);

        // Remove active SLA record
        $subCategorySla->delete();
    }



    public static function processJobServiceTimeSlaSubcategory( $ticketNumber, $ticketUpdatedAt)
    {

        $subCategorySla = SrvTimeSubcatSla::where('ticket_number', $ticketNumber)->first();

        if (!$subCategorySla) {
            return;
        }

        $subCategorySlaConfig = SlaSubcatConfig::where('id', $subCategorySla->sla_subcat_config_id)
            ->where('sla_status', 1)
            ->first();

        if (!$subCategorySlaConfig) {
            return;
        }

        // Calculate ticket age
        $ticketAgeMinutes = self::getTicketAgeWithIdleTime($subCategorySlaConfig->team_id, $ticketNumber);
        // $ticketAgeMinutes = self::getEffectiveSlaMinutes($subCategorySlaConfig->team_id, $ticketNumber);

        //  dd($ticketAgeMinutes);

        // Determine SLA status
        $allowedMinutes = (int) $subCategorySlaConfig->resolution_min;
        $slaStatus = $ticketAgeMinutes <= $allowedMinutes ? 2 : 0;
        //  dd([$ticketAgeMinutes, $allowedMinutes, $slaStatus]);
        // Update SLA
        if($slaStatus == 0){
        $subCategorySla->update([
            'sla_status' => $slaStatus
        ]);

        // Insert history
        SrvTimeSubcatSlaHistory::create([
            'ticket_number'       => $subCategorySla->ticket_number,
            'sla_subcat_config_id' => $subCategorySla->sla_subcat_config_id,
            'sla_status'          => $slaStatus,
        ]);
        // Remove active SLA record
        $subCategorySla->delete();
        }






    }




    public static function recreateFirstResponseSlaOnReopen($ticketNumber)
    {
        try {
            // Get the ticket data to find team_id
            $ticket = OpenTicket::where('ticket_number', $ticketNumber)->first();
            if (!$ticket) {
                return;
            }

            $teamId = $ticket->team_id;

            // Get First Response SLA config by team_id
            $confiSla = FirstResConfig::where('team_id', $teamId)->first();
            if ($confiSla) {
                // Create new First Response SLA record
                FirstResSla::create([
                    'ticket_number' => $ticketNumber,
                    'first_res_config_id' => $confiSla->id,
                    'sla_status' => 2,
                ]);

                // Create history record
                FirstResSlaHistory::create([
                    'ticket_number' => $ticketNumber,
                    'first_res_config_id' => $confiSla->id,
                    'sla_status' => 2,
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Failed to recreate First Response SLA for {$ticketNumber}: " . $e->getMessage());
        }
    }


    public static function recreateServiceTimeSlaOnReopen($ticketNumber)
    {
        try {
            // Get the ticket data
            $ticket = OpenTicket::where('ticket_number', $ticketNumber)->first();
            if (!$ticket) {
                return;
            }

            $businessEntityId = $ticket->business_entity_id;
            $clientId = $ticket->client_id_vendor;
            $teamId = $ticket->team_id;
            $subCatId = $ticket->subcat_id;

            // 1. Check if we had Client-specific SLA before (from history)
            $clientSlaHistory = SrvTimeClientSlaHistory::where('ticket_number', $ticketNumber)
                ->orderBy('created_at', 'desc')
                ->first();

            if ($clientSlaHistory) {
                // Get the original config
                $slaClientConfig = SlaClientConfig::find($clientSlaHistory->sla_client_config_id);
                if ($slaClientConfig) {
                    self::createClientSla($ticketNumber, $slaClientConfig->id);
                    return;
                }
            }

            // 2. If no client SLA history, check for Subcategory-specific SLA
            $slaSubcatConfig = SlaSubcatConfig::where('business_entity_id', $businessEntityId)
                ->where('team_id', $teamId)
                ->where('subcategory_id', $subCatId)
                ->first();

            if ($slaSubcatConfig) {
                self::createSubcatSla($ticketNumber, $slaSubcatConfig->id);
            }

        } catch (\Exception $e) {
            Log::error("Failed to recreate Service Time SLA for {$ticketNumber}: " . $e->getMessage());
        }
    }



}
