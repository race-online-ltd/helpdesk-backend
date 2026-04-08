<?php

namespace App\Http\Controllers\v1\Ticket;

use App\Events\CommentEvent;
use App\Events\CommentNotification;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Controllers\v1\Settings\EmailController;
use App\Logic\Client;
use App\Models\Notification;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Logic\ClientCreate;
use App\Logic\TicketInfo;
use App\Models\Company;
use App\Models\EscalateFrResClient;
use App\Models\MergeTicket;
use App\Models\SelfTicket;
use App\Models\SelfTicketAttachment;
use App\Models\SelfTicketOrbit;
use App\Models\SlaSubcategory;
use App\Models\Team;
use App\Models\TicketAttachment;
use App\Models\TicketComment;
use App\Models\TicketCommentAttachment;
use App\Models\TicketFrTimeClient;
use App\Models\TicketFrTimeClientHistory;
use App\Models\TicketFrTimeEscClient;
use App\Models\TicketFrTimeEscClentHistory;
use App\Models\TicketFrTimeEscClientHistory;
use App\Models\TicketFrTimeEscTeam;
use App\Models\TicketFrTimeEscTeamHistory;
use App\Models\TicketFrTimeTeam;
use App\Models\TicketFrTimeTeamHistory;
use App\Models\TicketHistory;
use App\Models\TicketSrvTimeClient;
use App\Models\TicketSrvTimeClientHistory;
use App\Models\TicketSrvTimeEscClient;
use App\Models\TicketSrvTimeEscClientHistory;
use App\Models\TicketSrvTimeEscTeam;
use App\Models\TicketSrvTimeEscTeamHistory;
use App\Models\TicketSrvTimeTeam;
use App\Models\TicketSrvTimeTeamHistory;
use App\Models\BusinessEntityWiseClient;
use App\Models\TicketAssignTeamLog;
use App\Http\Controllers\SendSmsController;
use App\Models\Branch;
use App\Models\CloseTicket;
use App\Models\UserClientMapping;
use App\Models\UserTeamMapping;
use App\Models\TeamMappingForPartner;
use App\Models\TicketTrackingToken;

use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\Models\OpenTicket;
use App\Models\FirstResConfig;
use App\Models\FirstResSla;
use App\Models\FirstResSlaHistory;
use App\Models\SlaClientConfig;
use App\Models\SrvTimeClientSla;
use App\Models\SrvTimeClientSlaHistory;
use App\Models\SlaSubcatConfig;
use App\Models\SrvTimeSubcatSla;
use App\Models\SrvTimeSubcatSlaHistory;
use App\Models\TicketAssignAgentLog;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Status;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;


class TicketController extends Controller
{

    public function index()
    {
        try {
            $resources = Notification::select('id', 'priority_name')->get();
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function isTicketReal($id)
    {
        try {
            $exists = Ticket::where('ticket_number', $id)->exists();
            return ApiResponse::success(['exists' => $exists], "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    public function ticketDetails($id)
    {

        try {
          $tableName = '';
          if (OpenTicket::where('ticket_number', $id)->exists()) {
            $tableName = 'open_tickets';
          } else if (CloseTicket::where('ticket_number', $id)->exists()) {
            $tableName = 'close_tickets';
          }
            $ticket = DB::select("CALL get_ticket(:table_name,:id)", ['table_name' => $tableName, 'id' => $id]);
            $teamId = $ticket[0]->team_id;
            $ticketNumber = $ticket[0]->ticket_number;

            $firstResponeSla = FirstResConfig::where('team_id', $teamId)->first();
            $serviceTimeClientSlaHistory = SrvTimeClientSlaHistory::where('ticket_number', $ticketNumber)->first();
            $serviceTimeSubcatSlaHistory = SrvTimeSubcatSlaHistory::where('ticket_number', $ticketNumber)->first();

            $slaConfig = null;

            if ($serviceTimeClientSlaHistory) {
                $slaConfig = SlaClientConfig::find($serviceTimeClientSlaHistory->sla_client_config_id);
            } elseif ($serviceTimeSubcatSlaHistory) {
                $slaConfig = SlaSubcatConfig::find($serviceTimeSubcatSlaHistory->sla_subcat_config_id);
            }


            $sla = [
                'first_response' => $firstResponeSla,
                'service_time' => $slaConfig,
            ];


        
            
            
            $forwardedTeamsInfo = DB::select("CALL forwared_teams(:id)", ['id' => $id]);


            $recentlyOpenTickets = (!empty($ticket) && isset($ticket[0]->client_id_helpdesk))
                ? DB::select("CALL recently_open_tickets(:id)", ['id' => $ticket[0]->client_id_helpdesk])
                : [];
            $recentlyClosedTickets = (!empty($ticket) && isset($ticket[0]->client_id_helpdesk))
                ? DB::select("CALL recently_closed_tickets(:id)", ['id' => $ticket[0]->client_id_helpdesk])
                : [];

            $networkBackbone = DB::select("SELECT t.ticket_number, be.name AS backbone_name, bel_main.name AS backbone_element_name
                            , bel_a.name AS backbone_element_name_a, bel_b.name AS backbone_element_name_b
                            FROM helpdesk.{$tableName} t
                            LEFT JOIN helpdesk.ticket_backbones b ON t.ticket_number = b.ticket_number
                            LEFT JOIN helpdesk.user_client_mappings ucm ON ucm.user_id = b.backbone_element_id
                            AND ucm.business_entity_id = 7
                            LEFT JOIN helpdesk.backbone_elements be ON ucm.client_id = be.id
                            LEFT JOIN helpdesk.backbone_element_lists bel_main ON b.backbone_element_list_id = bel_main.id
                            LEFT JOIN helpdesk.backbone_element_lists bel_a ON b.backbone_element_list_id_a_end = bel_a.id
                            LEFT JOIN helpdesk.backbone_element_lists bel_b ON b.backbone_element_list_id_b_end = bel_b.id
                            WHERE t.business_entity_id = 7 AND b.ticket_number = $id
                            ORDER BY t.ticket_number");

            return ApiResponse::success([
                'ticket' => $ticket,
                'slaInfo' => $sla,
                'forwarded_teams' => $forwardedTeamsInfo,
                'recently_open_ticket' => $recentlyOpenTickets,
                'recently_closed_ticket' => $recentlyClosedTickets,
                'networkBackbone' => $networkBackbone
            ], "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getTicketByStatusAndDefaultEntity_old(Request $request)
    {
        try {
            $statusName = ucfirst($request->status);
            $businessEntity = $request->businessEntity;
            $businessEntity1 = $request->businessEntity1;
            $team = $request->team;
            $team1 = $request->team1;
            $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
            $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

            $response = ($request->slaMissed === "Response") ? 2 : null;
            $resolved = ($request->slaMissed === "Resolved") ? 2 : null;

            $userType = $request->userType;

            $userID = $request->userId;

            $ticketNumber = $request->ticketNumber;

            $orbitValue = $request->orbit;

            $orbitUsers = ($request->orbit === "SID") ? 2 : null;
            $orbitPartners = ($request->orbit === "ENTITY") ? 2 : null;

            // return $ticketNumber;


            $teamIds = DB::select("SELECT GROUP_CONCAT(utm.team_id) AS team_ids
                                    FROM user_team_mappings utm
                                    WHERE utm.user_id = ?
                                    ", [$userID]);


            $teamIdsPermited = $teamIds[0]->team_ids;

            if ($userType == 'Agent') {
                // return 'i am  Agent';

                if (!empty($businessEntity) && !empty($statusName) && empty($team) && empty($team1) && empty($fromDate) && empty($toDate) && $response === null && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 1 All';

                    $resources = DB::select("WITH Latest_FTH AS (
                                                SELECT fth.*
                                                FROM helpdesk.ticket_fr_time_team_histories fth
                                                WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                    SELECT ticket_number, team_id, MAX(created_at)
                                                    FROM helpdesk.ticket_fr_time_team_histories
                                                    WHERE fr_response_status != 0
                                                    GROUP BY ticket_number, team_id
                                                )
                                            ),
                                            Latest_TST AS (
                                                SELECT tst.*
                                                FROM helpdesk.ticket_srv_time_team_histories tst
                                                WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                    SELECT ticket_number, team_id, MAX(created_at)
                                                    FROM helpdesk.ticket_srv_time_team_histories
                                                    WHERE srv_time_status != 0
                                                    GROUP BY ticket_number, team_id
                                                )
                                            )
                                            SELECT DISTINCT
                                                t.id,t.ticket_number, CASE
                                                WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                THEN CONCAT(' partner')
                                                ELSE null
                                            END AS ticket_partner, t.user_id, ucm.client_name, 
                                                fth.fr_response_status_name, tst.srv_time_status_name, 
                                                t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                teams.team_name, t.cat_id, categories.category_in_english, 
                                                t.subcat_id, sub_categories.sub_category_in_english,
                                                t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                CASE 
                                                    WHEN '$statusName' = 'Open' THEN 
                                                        CONCAT(
                                                            TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                            TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                            TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                        )
                                                    WHEN '$statusName' = 'Closed' THEN 
                                                        CONCAT(
                                                            TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                            TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                            TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                        )
                                                END AS ticket_age,

                                                CASE 
                                                    WHEN fth.fr_response_status = 2 
                                                    THEN CONCAT(
                                                        TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                        TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                        TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                    )
                                                    ELSE ''
                                                END AS fr_due_time,

                                                CASE 
                                                    WHEN tst.srv_time_status = 2
                                                    THEN CONCAT(
                                                        TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                        TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                        TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                    )
                                                    ELSE ''
                                                END AS srv_due_time,

                                                u.username AS created_by,
                                                u2.username AS status_update_by,
                                                latest_comments.comments AS last_comment

                                            FROM helpdesk.tickets t
                                            LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                            LEFT JOIN users u ON t.user_id = u.id
                                            LEFT JOIN users u2 ON t.status_update_by = u2.id
                                            LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                            LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                            LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                            LEFT JOIN helpdesk.user_client_mappings ucm 
                                                ON t.client_id_helpdesk = ucm.user_id
                                                AND ucm.business_entity_id = c.id
                                            LEFT JOIN helpdesk.sub_categories sub_categories 
                                                ON t.subcat_id = sub_categories.id

                                            LEFT JOIN Latest_FTH fth 
                                                ON t.ticket_number = fth.ticket_number 
                                                AND t.team_id = fth.team_id 

                                            LEFT JOIN Latest_TST tst 
                                                ON t.ticket_number = tst.ticket_number 
                                                AND t.team_id = tst.team_id 

                                            LEFT JOIN helpdesk.ticket_histories th 
                                                ON t.ticket_number = th.ticket_number

                                            LEFT JOIN (
                                                SELECT ticket_number, comments
                                                FROM (
                                                    SELECT tc.ticket_number, tc.comments, 
                                                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                    FROM helpdesk.ticket_comments tc
                                                ) AS ranked_comments
                                                WHERE rn = 1
                                            ) AS latest_comments 
                                            ON t.ticket_number = latest_comments.ticket_number

                                            WHERE t.business_entity_id = '$businessEntity'
                                            AND (
                                        ('$statusName' = 'Open' 
                                            AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                        )
                                        OR ('$statusName' = 'Closed' 
                                            AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                            AND t.created_at >= NOW() - INTERVAL 1 DAY  -- only last 24 hours
                                        )
                                    )

                                            ORDER BY 
                        CASE
                            WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                            THEN CONCAT(t.ticket_number, ' (partner)')
                            ELSE t.ticket_number
                        END DESC");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && !empty($team) && empty($team1) && empty($fromDate) && empty($toDate) && $response === null && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 2';

                    $resources = DB::select("WITH Latest_FTH AS (
                                                SELECT fth.*
                                                FROM helpdesk.ticket_fr_time_team_histories fth
                                                WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                    SELECT ticket_number, team_id, MAX(created_at)
                                                    FROM helpdesk.ticket_fr_time_team_histories
                                                    WHERE fr_response_status != 0 AND team_id = '$teamIdsPermited'
                                                    GROUP BY ticket_number, team_id
                                                )
                                            ),
                                            Latest_TST AS (
                                                SELECT tst.*
                                                FROM helpdesk.ticket_srv_time_team_histories tst
                                                WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                    SELECT ticket_number, team_id, MAX(created_at)
                                                    FROM helpdesk.ticket_srv_time_team_histories
                                                    WHERE srv_time_status != 0 AND team_id = '$teamIdsPermited'
                                                    GROUP BY ticket_number, team_id
                                                )
                                            )
                                            SELECT DISTINCT
                                                t.id, t.ticket_number, CASE
                                                WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                THEN CONCAT(' partner')
                                                ELSE null
                                            END AS ticket_partner, t.user_id, ucm.client_name, 
                                                fth.fr_response_status_name, tst.srv_time_status_name, 
                                                t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                teams.team_name, t.cat_id, categories.category_in_english, 
                                                t.subcat_id, sub_categories.sub_category_in_english,
                                                t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                CASE 
                                                    WHEN '$statusName' = 'Open' THEN 
                                                        CONCAT(
                                                            TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                            TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                            TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                        )
                                                    WHEN '$statusName' = 'Closed' THEN 
                                                        CONCAT(
                                                            TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                            TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                            TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                        )
                                                END AS ticket_age,

                                                CASE 
                                                    WHEN fth.fr_response_status = 2 
                                                    THEN CONCAT(
                                                        TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                        TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                        TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                    )
                                                    ELSE ''
                                                END AS fr_due_time,

                                                CASE 
                                                    WHEN tst.srv_time_status = 2
                                                    THEN CONCAT(
                                                        TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                        TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                        TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                    )
                                                    ELSE ''
                                                END AS srv_due_time,

                                                u.username AS created_by,
                                                u2.username AS status_update_by,
                                                latest_comments.comments AS last_comment

                                            FROM helpdesk.tickets t
                                            LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                            LEFT JOIN users u ON t.user_id = u.id
                                            LEFT JOIN users u2 ON t.status_update_by = u2.id
                                            LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                            LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                            LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                            LEFT JOIN helpdesk.user_client_mappings ucm 
                                                ON t.client_id_helpdesk = ucm.user_id
                                                AND ucm.business_entity_id = c.id
                                            LEFT JOIN helpdesk.sub_categories sub_categories 
                                                ON t.subcat_id = sub_categories.id

                                            LEFT JOIN Latest_FTH fth 
                                                ON t.ticket_number = fth.ticket_number 
                                                AND t.team_id = fth.team_id 

                                            LEFT JOIN Latest_TST tst 
                                                ON t.ticket_number = tst.ticket_number 
                                                AND t.team_id = tst.team_id 

                                            LEFT JOIN helpdesk.ticket_histories th 
                                                ON t.ticket_number = th.ticket_number

                                            LEFT JOIN (
                                                SELECT ticket_number, comments
                                                FROM (
                                                    SELECT tc.ticket_number, tc.comments, 
                                                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                    FROM helpdesk.ticket_comments tc
                                                ) AS ranked_comments
                                                WHERE rn = 1
                                            ) AS latest_comments 
                                            ON t.ticket_number = latest_comments.ticket_number

                                            WHERE t.team_id IN ($teamIdsPermited)
                                            AND (
                                ('$statusName' = 'Open' 
                                    AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                )
                                OR ('$statusName' = 'Closed' 
                                    AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                    AND t.created_at >= NOW() - INTERVAL 1 DAY  -- only last 24 hours
                                )
                            )

                                            ORDER BY 
                                                    CASE
                                                        WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                        THEN CONCAT(t.ticket_number, ' (partner)')
                                                        ELSE t.ticket_number
                                                    END DESC");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (!empty($businessEntity) && !empty($statusName) && !empty($fromDate) && !empty($toDate) && empty($team) && empty($team1) && $response === null && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 3';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                SELECT fth.*
                                                FROM helpdesk.ticket_fr_time_team_histories fth
                                                WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                    SELECT ticket_number, team_id, MAX(created_at)
                                                    FROM helpdesk.ticket_fr_time_team_histories
                                                    WHERE fr_response_status != 0
                                                    GROUP BY ticket_number, team_id
                                                )
                                            ),
                                            Latest_TST AS (
                                                SELECT tst.*
                                                FROM helpdesk.ticket_srv_time_team_histories tst
                                                WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                    SELECT ticket_number, team_id, MAX(created_at)
                                                    FROM helpdesk.ticket_srv_time_team_histories
                                                    WHERE srv_time_status != 0
                                                    GROUP BY ticket_number, team_id
                                                )
                                            )
                                            SELECT DISTINCT
                                                t.id, t.ticket_number, CASE
                                                WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                THEN CONCAT(' partner')
                                                ELSE null
                                            END AS ticket_partner, t.user_id, ucm.client_name, 
                                                fth.fr_response_status_name, tst.srv_time_status_name, 
                                                t.business_entity_id, c.company_name, t.team_id, t.sid, teams.team_name,
                                                t.cat_id, categories.category_in_english, t.subcat_id, sub_categories.sub_category_in_english,
                                                t.status_id, statuses.status_name, t.updated_at, t.created_at,

                                                -- Ticket Age Calculation
                                                CASE 
                                                    WHEN '$statusName' = 'Open' THEN 
                                                        CONCAT(
                                                            TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                            TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                            TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                        )
                                                    WHEN '$statusName' = 'Closed' THEN 
                                                        CONCAT(
                                                            TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                            TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                            TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                        )
                                                END AS ticket_age,

                                                -- First Response Due Time
                                                CASE 
                                                    WHEN fth.fr_response_status = 2 
                                                    THEN CONCAT(
                                                        TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                        TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                        TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                    )
                                                    ELSE ''
                                                END AS fr_due_time,

                                                -- Service Response Due Time
                                                CASE 
                                                    WHEN tst.srv_time_status = 2
                                                    THEN CONCAT(
                                                        TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                        TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                        TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                    )
                                                    ELSE ''
                                                END AS srv_due_time,

                                                u.username AS created_by,
                                                u2.username AS status_update_by,
                                                latest_comments.comments AS last_comment

                                            FROM helpdesk.tickets t
                                            LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                            LEFT JOIN users u ON t.user_id = u.id
                                            LEFT JOIN users u2 ON t.status_update_by = u2.id
                                            LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                            LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                            LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                            LEFT JOIN helpdesk.user_client_mappings ucm 
                                                ON t.client_id_helpdesk = ucm.user_id
                                                AND ucm.business_entity_id = c.id
                                            LEFT JOIN helpdesk.sub_categories sub_categories 
                                                ON t.subcat_id = sub_categories.id

                                            -- Join with latest FTH & TST records only
                                            LEFT JOIN Latest_FTH fth 
                                                ON t.ticket_number = fth.ticket_number 
                                                AND t.team_id = fth.team_id 

                                            LEFT JOIN Latest_TST tst 
                                                ON t.ticket_number = tst.ticket_number 
                                                AND t.team_id = tst.team_id 

                                            -- Get latest comment per ticket
                                            LEFT JOIN (
                                                SELECT ticket_number, comments
                                                FROM (
                                                    SELECT tc.ticket_number, tc.comments, 
                                                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                    FROM helpdesk.ticket_comments tc
                                                ) AS ranked_comments
                                                WHERE rn = 1
                                            ) AS latest_comments 
                                            ON t.ticket_number = latest_comments.ticket_number

                                            WHERE t.business_entity_id = '$businessEntity'
                                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                            AND (
                                                '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                            )
                                            ORDER BY CASE
                                        WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                        THEN CONCAT(t.ticket_number, ' partner')
                                        ELSE t.ticket_number
                                    END  DESC");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (!empty($businessEntity) && !empty($statusName) && empty($team) && empty($team1) && empty($fromDate) && empty($toDate)  && !empty($response) && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 4';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                SELECT fth.*
                                                FROM helpdesk.ticket_fr_time_team_histories fth
                                                WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                    SELECT ticket_number, team_id, MAX(created_at)
                                                    FROM helpdesk.ticket_fr_time_team_histories
                                                    WHERE fr_response_status != 0
                                                    GROUP BY ticket_number, team_id
                                                )
                                            ),
                                            Latest_TST AS (
                                                SELECT tst.*
                                                FROM helpdesk.ticket_srv_time_team_histories tst
                                                WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                    SELECT ticket_number, team_id, MAX(created_at)
                                                    FROM helpdesk.ticket_srv_time_team_histories
                                                    WHERE srv_time_status != 0
                                                    GROUP BY ticket_number, team_id
                                                )
                                            )
                                            SELECT DISTINCT
                                                t.id, t.ticket_number, CASE
                                                WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                THEN CONCAT(' partner')
                                                ELSE null
                                            END AS ticket_partner, t.user_id, ucm.client_name, 
                                                fth.fr_response_status_name, tst.srv_time_status_name, 
                                                t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                teams.team_name, t.cat_id, categories.category_in_english, 
                                                t.subcat_id, sub_categories.sub_category_in_english,
                                                t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                CASE 
                                                    WHEN '$statusName' = 'Open' THEN 
                                                        CONCAT(
                                                            TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                            TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                            TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                        )
                                                    WHEN '$statusName' = 'Closed' THEN 
                                                        CONCAT(
                                                            TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                            TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                            TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                        )
                                                END AS ticket_age,

                                                CASE 
                                                    WHEN fth.fr_response_status = 2 
                                                    THEN CONCAT(
                                                        TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                        TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                        TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                    )
                                                    ELSE ''
                                                END AS fr_due_time,

                                                CASE 
                                                    WHEN tst.srv_time_status = 2
                                                    THEN CONCAT(
                                                        TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                        TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                        TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                    )
                                                    ELSE ''
                                                END AS srv_due_time,

                                                u.username AS created_by,
                                                u2.username AS status_update_by,
                                                latest_comments.comments AS last_comment

                                            FROM helpdesk.tickets t
                                            LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                            LEFT JOIN users u ON t.user_id = u.id
                                            LEFT JOIN users u2 ON t.status_update_by = u2.id
                                            LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                            LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                            LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                            LEFT JOIN helpdesk.user_client_mappings ucm 
                                                ON t.client_id_helpdesk = ucm.user_id
                                                AND ucm.business_entity_id = c.id
                                            LEFT JOIN helpdesk.sub_categories sub_categories 
                                                ON t.subcat_id = sub_categories.id

                                            JOIN Latest_FTH fth 
                                                ON t.ticket_number = fth.ticket_number 
                                                AND t.team_id = fth.team_id 
											AND fth.fr_response_status = '$response'

                                            LEFT JOIN Latest_TST tst 
                                                ON t.ticket_number = tst.ticket_number 
                                                AND t.team_id = tst.team_id 

                                            LEFT JOIN helpdesk.ticket_histories th 
                                                ON t.ticket_number = th.ticket_number

                                            LEFT JOIN (
                                                SELECT ticket_number, comments
                                                FROM (
                                                    SELECT tc.ticket_number, tc.comments, 
                                                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                    FROM helpdesk.ticket_comments tc
                                                ) AS ranked_comments
                                                WHERE rn = 1
                                            ) AS latest_comments 
                                            ON t.ticket_number = latest_comments.ticket_number

                                            WHERE t.business_entity_id = '$businessEntity'
                                            AND(
                                                '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                            )

                                            ORDER BY CASE
                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                    THEN CONCAT(t.ticket_number, ' partner')
                    ELSE t.ticket_number
                    END DESC");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (!empty($businessEntity) && !empty($statusName) && empty($team) && empty($team1) && !empty($fromDate) && !empty($toDate) && !empty($response) && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 5';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 
                                                    AND fth.fr_response_status = '$response'

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.business_entity_id = '$businessEntity'
                                                AND(
                                                '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )
                                                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'

                                                ORDER BY CASE
                            WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                            THEN CONCAT(t.ticket_number, ' partner')
                            ELSE t.ticket_number
                        END DESC");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (!empty($businessEntity) && !empty($statusName) && !empty($team1) && empty($team) && !empty($fromDate) && !empty($toDate)  && !empty($response) && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 6';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 
                                                                                                    AND fth.fr_response_status = '$response'

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.business_entity_id = '$businessEntity'
                                                AND(
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )
                                                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                                            AND t.team_id IN ($team1)

                                                ORDER BY CASE
                        WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                        THEN CONCAT(t.ticket_number, ' partner')
                        ELSE t.ticket_number
                    END DESC");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (!empty($businessEntity) && !empty($statusName) && empty($team) && empty($team1) && empty($fromDate) && empty($toDate)  && $response === null && !empty($resolved) && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 7';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 
                                                                                                    AND tst.srv_time_status = '$resolved'

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.business_entity_id = '$businessEntity'
                                                AND(
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )

                                                ORDER BY CASE
                            WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                            THEN CONCAT(t.ticket_number, ' partner')
                            ELSE t.ticket_number
                        END AS DESC");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (!empty($businessEntity) && !empty($statusName) && empty($team) && empty($team1) && !empty($fromDate) && !empty($toDate) && $response === null && !empty($resolved) && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 8';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 
                                                                                                    AND tst.srv_time_status = '$resolved'

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.business_entity_id = '$businessEntity'
                                                AND(
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )
                                                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (!empty($businessEntity) && !empty($statusName) && !empty($team1) && !empty($fromDate) && !empty($toDate) && $response === null && !empty($resolved) && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {
                    // return 'I am 9';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 
                                                    AND tst.srv_time_status = '$resolved'

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.business_entity_id = '$businessEntity'
                                                AND(
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )
                                                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                                            AND t.team_id IN ($team1)

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && !empty($team) && empty($team1) && !empty($fromDate) && !empty($toDate) && $response === null && !empty($resolved) && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {
                    // return 'I am 9';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                        t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 
                                                                                                    AND tst.srv_time_status = '$resolved'

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE ('$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )
                                                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                                            AND t.team_id IN ($team1)

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && !empty($team1) && empty($team) && !empty($fromDate) && !empty($toDate)  && !empty($response) && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 6';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 
                                                                                                    AND fth.fr_response_status = '$response'

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.team_id IN ($team1)
                                                AND(
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )
                                                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                                            

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (!empty($businessEntity) && !empty($statusName) && !empty($team1) && empty($team) && empty($fromDate) && empty($toDate) && $response === null && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 2';

                    // return [$businessEntity,$teamIdsPermited];
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.business_entity_id = '$businessEntity'
                                                                                            AND t.team_id IN ($team1)
                                                AND(
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && !empty($fromDate) && !empty($toDate) && !empty($team) && empty($team1) && $response === null && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 3';
                    $resources = DB::select("SELECT distinct
                                t.id,t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner,t.user_id,ucm.client_name,fth.fr_response_status_name, tst.srv_time_status_name, 
                                t.business_entity_id,c.company_name,t.team_id,t.sid,teams.team_name,
                                t.cat_id,categories.category_in_english,t.subcat_id,sub_categories.sub_category_in_english,t.status_id,
                                statuses.status_name, t.updated_at,t.created_at,
                                CASE 
                                            WHEN '$statusName' = 'Open' THEN 
                                                CONCAT(
                                                    TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                    TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                    TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                )
                                            WHEN '$statusName' = 'Closed' THEN 
                                                CONCAT(
                                                    TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                    TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                    TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                )
                                        END AS ticket_age,

                                CASE 
                                            WHEN fth.fr_response_status = 2
                                            THEN CONCAT(
                                                TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                            )
                                            ELSE ''
                                        END AS fr_due_time,

                                CASE 
                                    WHEN tst.srv_time_status = 2
                                    THEN CONCAT(
                                        TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                        TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                        TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                    )
                                    ELSE ''
                                END AS srv_due_time
                                ,u.username AS created_by
                                ,u2.username AS status_update_by
                                ,latest_comments.comments AS last_comment

                                FROM helpdesk.tickets t
                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                LEFT JOIN users u ON t.user_id = u.id
                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                -- LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_vendor = ucm.client_id
                                LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                                AND ucm.business_entity_id = c.id
                                LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                                LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                                AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                                
                                LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                                AND t.team_id = tst.team_id AND tst.srv_time_status != 0
                                LEFT JOIN (SELECT ticket_number, comments
                                        FROM (SELECT tc.ticket_number, tc.comments, 
                                                ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                    FROM helpdesk.ticket_comments tc) AS ranked_comments
                                                        WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
                                where t.team_id IN ($team)
                                AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                AND (
                                '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                    OR '$statusName'= 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                )order by CASE
                                WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                THEN CONCAT(t.ticket_number, ' partner')
                                ELSE t.ticket_number
                            END desc");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && !empty($team1) && empty($team) && empty($fromDate) && empty($toDate) && $response === null && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 2 here';
                    // $resources = DB::select("SELECT distinct
                    //         t.id,t.ticket_number,t.user_id,ucm.client_name, fth.fr_response_status_name, tst.srv_time_status_name, 
                    //         t.business_entity_id,c.company_name,t.team_id,t.sid,teams.team_name,t.cat_id,categories.category_in_english,
                    //         t.subcat_id,sub_categories.sub_category_in_english,t.status_id,statuses.status_name,t.created_at,t.updated_at,
                    //         CASE 
                    //                     WHEN '$statusName' = 'Open' THEN 
                    //                         CONCAT(
                    //                             TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                    //                             TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                    //                             TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                    //                         )
                    //                     WHEN '$statusName' = 'Closed' THEN 
                    //                         CONCAT(
                    //                             TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                    //                             TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                    //                             TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                    //                         )
                    //                 END AS ticket_age,

                    //         CASE 
                    //                     WHEN fth.fr_response_status = 2
                    //                     THEN CONCAT(
                    //                         TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                    //                         TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                    //                         TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                    //                     )
                    //                     ELSE ''
                    //                 END AS fr_due_time,

                    //         CASE 
                    //             WHEN tst.srv_time_status = 2
                    //             THEN CONCAT(
                    //                 TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                    //                 TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                    //                 TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                    //             )
                    //             ELSE ''
                    //         END AS srv_due_time
                    //         ,u.username AS created_by
                    //         ,u2.username AS status_update_by
                    //         ,latest_comments.comments AS last_comment

                    //         FROM helpdesk.tickets t
                    //         LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                    //         LEFT JOIN users u ON t.user_id = u.id
                    //         LEFT JOIN users u2 ON t.status_update_by = u2.id
                    //         LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                    //         LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                    //         LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                    //         -- LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_vendor = ucm.client_id
                    //         LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                    //         AND ucm.business_entity_id = c.id
                    //         LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                    //         LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                    //         AND t.team_id = fth.team_id AND fth.fr_response_status != 0

                    //         LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                    //         AND t.team_id = tst.team_id AND tst.srv_time_status != 0
                    //         LEFT JOIN (SELECT ticket_number, comments
                    // 				FROM (SELECT tc.ticket_number, tc.comments, 
                    // 						ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                    // 							FROM helpdesk.ticket_comments tc) AS ranked_comments
                    // 								WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
                    //         where t.team_id IN ($team1)
                    //         AND (
                    //         '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                    //             OR '$statusName'= 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                    //         )
                    //         order by t.ticket_number desc
                    //         ");


                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.team_id IN ($team1)
                                                AND(
                                                '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                            )

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");


                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && !empty($fromDate) && !empty($toDate) && !empty($team1) && empty($team) && $response === null && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 3';
                    $resources = DB::select("SELECT distinct
                                t.id,t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner,t.user_id,ucm.client_name,fth.fr_response_status_name, tst.srv_time_status_name, 
                                t.business_entity_id,c.company_name,t.team_id,t.sid,teams.team_name,
                                t.cat_id,categories.category_in_english,t.subcat_id,sub_categories.sub_category_in_english,t.status_id,
                                statuses.status_name, t.updated_at,t.created_at,
                                CASE 
                                            WHEN '$statusName' = 'Open' THEN 
                                                CONCAT(
                                                    TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                    TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                    TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                )
                                            WHEN '$statusName' = 'Closed' THEN 
                                                CONCAT(
                                                    TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                    TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                    TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                )
                                        END AS ticket_age,

                                CASE 
                                            WHEN fth.fr_response_status = 2
                                            THEN CONCAT(
                                                TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                            )
                                            ELSE ''
                                        END AS fr_due_time,

                                CASE 
                                    WHEN tst.srv_time_status = 2
                                    THEN CONCAT(
                                        TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                        TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                        TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                    )
                                    ELSE ''
                                END AS srv_due_time
                                ,u.username AS created_by
                                ,u2.username AS status_update_by
                                ,latest_comments.comments AS last_comment

                                FROM helpdesk.tickets t
                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                LEFT JOIN users u ON t.user_id = u.id
                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                -- LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_vendor = ucm.client_id
                                LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                                AND ucm.business_entity_id = c.id
                                LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                                LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                                AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                                
                                LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                                AND t.team_id = tst.team_id AND tst.srv_time_status != 0
                                LEFT JOIN (SELECT ticket_number, comments
                                        FROM (SELECT tc.ticket_number, tc.comments, 
                                                ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                    FROM helpdesk.ticket_comments tc) AS ranked_comments
                                                        WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
                                where t.team_id IN ($team1)
                                AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                AND (
                                '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                    OR '$statusName'= 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                )order by CASE
                                WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                THEN CONCAT(t.ticket_number, ' partner')
                                ELSE t.ticket_number
                            END desc");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && !empty($team1) && empty($team) && empty($fromDate) && empty($toDate)  && !empty($response) && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 6';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 
                                                                                                    AND fth.fr_response_status = '$response'

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.team_id IN ($team1)
                                                AND(
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )
                                                            -- AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                                            

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && !empty($team1) && empty($team) && empty($fromDate) && empty($toDate) && $response === null && !empty($resolved) && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {
                    // return 'I am 9';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 
                                                                                                    AND tst.srv_time_status = '$resolved'

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE (
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )
                                                            AND t.team_id IN ($team1)

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && empty($team1) && empty($team) && empty($fromDate) && empty($toDate)  && !empty($response) && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 6';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 
                                                                                                    AND fth.fr_response_status = '$response'

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE (
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && empty($team1) && empty($fromDate) && empty($toDate) && $response === null && !empty($resolved) && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {
                    // return 'I am 9';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 
                                                                                                    AND tst.srv_time_status = '$resolved'

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE (
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && empty($team1) && empty($team) && !empty($fromDate) && !empty($toDate)  && !empty($response) && $resolved === null && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {

                    // return 'I am 6';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 
                                                                                                    AND fth.fr_response_status = '$response'

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE ('$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )
                                                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && empty($team1) && empty($team) && !empty($fromDate) && !empty($toDate) && $response === null && !empty($resolved) && empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {
                    // return 'I am 9';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 
                                                                                                    AND tst.srv_time_status = '$resolved'

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE (
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )
                                                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && empty($team1) && empty($team) && empty($fromDate) && empty($toDate) && $response === null && $resolved === null && !empty($ticketNumber) && $orbitUsers === null && $orbitPartners === null) {
                    // return 'I am 9';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.ticket_number = '$ticketNumber'
                                                AND(
                                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )

                                                ORDER BY CASE
                                WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                THEN CONCAT(t.ticket_number, ' partner')
                                ELSE t.ticket_number
                            END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && empty($team1) && empty($team) && empty($fromDate) && empty($toDate) && $response === null && $resolved === null && empty($ticketNumber) && !empty($orbitUsers) && $orbitPartners === null) {
                    // return 'I am 9';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.business_entity_id = 8
                                                AND t.sid IS NOT NULL
                                                AND(
                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                    OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && empty($team1) && empty($team) && !empty($fromDate) && !empty($toDate) && $response === null && $resolved === null && empty($ticketNumber) && !empty($orbitUsers) && $orbitPartners === null) {
                    // return 'I am 9';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.business_entity_id = 8
                                                AND t.sid IS NOT NULL
                                AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                                AND(
                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                    OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                                ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && empty($team1) && empty($team) && empty($fromDate) && empty($toDate) && $response === null && $resolved === null && empty($ticketNumber) && $orbitUsers === null && !empty($orbitPartners)) {
                    // return 'I am 9';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                    SELECT fth.*
                                                    FROM helpdesk.ticket_fr_time_team_histories fth
                                                    WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_fr_time_team_histories
                                                        WHERE fr_response_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                ),
                                                Latest_TST AS (
                                                    SELECT tst.*
                                                    FROM helpdesk.ticket_srv_time_team_histories tst
                                                    WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                        SELECT ticket_number, team_id, MAX(created_at)
                                                        FROM helpdesk.ticket_srv_time_team_histories
                                                        WHERE srv_time_status != 0
                                                        GROUP BY ticket_number, team_id
                                                    )
                                                )
                                                SELECT DISTINCT
                                                    t.id, t.ticket_number, CASE
                                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                    THEN CONCAT(' partner')
                                                    ELSE null
                                                END AS ticket_partner, t.user_id, ucm.client_name, 
                                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                                    t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                                    t.subcat_id, sub_categories.sub_category_in_english,
                                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                    CASE 
                                                        WHEN '$statusName' = 'Open' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                            )
                                                        WHEN '$statusName' = 'Closed' THEN 
                                                            CONCAT(
                                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                            )
                                                    END AS ticket_age,

                                                    CASE 
                                                        WHEN fth.fr_response_status = 2 
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS fr_due_time,

                                                    CASE 
                                                        WHEN tst.srv_time_status = 2
                                                        THEN CONCAT(
                                                            TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                            TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                            TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                        )
                                                        ELSE ''
                                                    END AS srv_due_time,

                                                    u.username AS created_by,
                                                    u2.username AS status_update_by,
                                                    latest_comments.comments AS last_comment

                                                FROM helpdesk.tickets t
                                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                                LEFT JOIN users u ON t.user_id = u.id
                                                LEFT JOIN users u2 ON t.status_update_by = u2.id
                                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                                LEFT JOIN helpdesk.user_client_mappings ucm 
                                                    ON t.client_id_helpdesk = ucm.user_id
                                                    AND ucm.business_entity_id = c.id
                                                LEFT JOIN helpdesk.sub_categories sub_categories 
                                                    ON t.subcat_id = sub_categories.id

                                                LEFT JOIN Latest_FTH fth 
                                                    ON t.ticket_number = fth.ticket_number 
                                                    AND t.team_id = fth.team_id 

                                                LEFT JOIN Latest_TST tst 
                                                    ON t.ticket_number = tst.ticket_number 
                                                    AND t.team_id = tst.team_id 

                                                LEFT JOIN helpdesk.ticket_histories th 
                                                    ON t.ticket_number = th.ticket_number

                                                LEFT JOIN (
                                                    SELECT ticket_number, comments
                                                    FROM (
                                                        SELECT tc.ticket_number, tc.comments, 
                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                        FROM helpdesk.ticket_comments tc
                                                    ) AS ranked_comments
                                                    WHERE rn = 1
                                                ) AS latest_comments 
                                                ON t.ticket_number = latest_comments.ticket_number

                                                WHERE t.business_entity_id = 8
                                AND t.sid IS NULL
                                                AND(
                                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                    OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                                )

                                                ORDER BY CASE
                                    WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                    THEN CONCAT(t.ticket_number, ' partner')
                                    ELSE t.ticket_number
                                END DESC
                            ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity) && !empty($statusName) && empty($team1) && empty($team) && !empty($fromDate) && !empty($toDate) && $response === null && $resolved === null && empty($ticketNumber) && $orbitUsers === null && !empty($orbitPartners)) {
                    // return 'I am 9';
                    $resources = DB::select("WITH Latest_FTH AS (
                                                SELECT fth.*
                                                FROM helpdesk.ticket_fr_time_team_histories fth
                                                WHERE (fth.ticket_number, fth.team_id, fth.created_at) IN (
                                                    SELECT ticket_number, team_id, MAX(created_at)
                                                    FROM helpdesk.ticket_fr_time_team_histories
                                                    WHERE fr_response_status != 0
                                                    GROUP BY ticket_number, team_id
                                                )
                                            ),
                                            Latest_TST AS (
                                                SELECT tst.*
                                                FROM helpdesk.ticket_srv_time_team_histories tst
                                                WHERE (tst.ticket_number, tst.team_id, tst.created_at) IN (
                                                    SELECT ticket_number, team_id, MAX(created_at)
                                                    FROM helpdesk.ticket_srv_time_team_histories
                                                    WHERE srv_time_status != 0
                                                    GROUP BY ticket_number, team_id
                                                )
                                            )
                                            SELECT DISTINCT
                                                t.id, t.ticket_number, CASE
                                                WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                                THEN CONCAT(' partner')
                                                ELSE null
                                            END AS ticket_partner, t.user_id, ucm.client_name, 
                                                fth.fr_response_status_name, tst.srv_time_status_name, 
                                                t.business_entity_id, c.company_name, t.team_id, t.sid,
                                                teams.team_name, t.cat_id, categories.category_in_english, 
                                                t.subcat_id, sub_categories.sub_category_in_english,
                                                t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                                CASE 
                                                    WHEN '$statusName' = 'Open' THEN 
                                                        CONCAT(
                                                            TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                            TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                            TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                                        )
                                                    WHEN '$statusName' = 'Closed' THEN 
                                                        CONCAT(
                                                            TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                            TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                            TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                                        )
                                                END AS ticket_age,

                                                CASE 
                                                    WHEN fth.fr_response_status = 2 
                                                    THEN CONCAT(
                                                        TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                        TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                        TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                                    )
                                                    ELSE ''
                                                END AS fr_due_time,

                                                CASE 
                                                    WHEN tst.srv_time_status = 2
                                                    THEN CONCAT(
                                                        TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                        TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                        TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                                    )
                                                    ELSE ''
                                                END AS srv_due_time,

                                                u.username AS created_by,
                                                u2.username AS status_update_by,
                                                latest_comments.comments AS last_comment

                                            FROM helpdesk.tickets t
                                            LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                            LEFT JOIN users u ON t.user_id = u.id
                                            LEFT JOIN users u2 ON t.status_update_by = u2.id
                                            LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                            LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                            LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                            LEFT JOIN helpdesk.user_client_mappings ucm 
                                                ON t.client_id_helpdesk = ucm.user_id
                                                AND ucm.business_entity_id = c.id
                                            LEFT JOIN helpdesk.sub_categories sub_categories 
                                                ON t.subcat_id = sub_categories.id

                                            LEFT JOIN Latest_FTH fth 
                                                ON t.ticket_number = fth.ticket_number 
                                                AND t.team_id = fth.team_id 

                                            LEFT JOIN Latest_TST tst 
                                                ON t.ticket_number = tst.ticket_number 
                                                AND t.team_id = tst.team_id 

                                            LEFT JOIN helpdesk.ticket_histories th 
                                                ON t.ticket_number = th.ticket_number

                                            LEFT JOIN (
                                                SELECT ticket_number, comments
                                                FROM (
                                                    SELECT tc.ticket_number, tc.comments, 
                                                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                    FROM helpdesk.ticket_comments tc
                                                ) AS ranked_comments
                                                WHERE rn = 1
                                            ) AS latest_comments 
                                            ON t.ticket_number = latest_comments.ticket_number

                                            WHERE t.business_entity_id = 8
                            AND t.sid IS NULL
                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                            AND(
                                                '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                                OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                                            )

                                            ORDER BY CASE
                                WHEN u.username LIKE 'OP%' OR u.username LIKE 'OM%' OR u.username LIKE 'RP%'
                                THEN CONCAT(t.ticket_number, ' partner')
                                ELSE t.ticket_number
                            END DESC");
                    return ApiResponse::success($resources, "Success", 200);
                }
            } elseif ($userType == 'Client') {
                // return 'i am  client';

                if (!empty($businessEntity1) && $businessEntity === null && !empty($statusName) && $team1 === null && empty($fromDate) && empty($toDate) && $response === null && $resolved === null &&  empty($ticketNumber)) {

                    // return 'I am 1';


                    $resources = DB::select("SELECT DISTINCT t.id,t.ticket_number,t.user_id,ucm.client_name,t.business_entity_id,c.company_name,t.  team_id,t.sid,t.cat_id,categories.category_in_english,t.subcat_id,sub_categories.sub_category_in_english,t.status_id,
                            statuses.status_name,t.created_at,t.updated_at,
                            CASE 
                                        WHEN '$statusName' = 'Open' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                        WHEN '$statusName' = 'Closed' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                            )
                                    END AS ticket_age
                            ,u.username AS created_by
                            ,u2.username AS status_update_by
                            ,t.source_type

                        FROM (
                                SELECT 
                                    id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
                                    'Ticket' AS source_type
                                FROM helpdesk.tickets

                                UNION ALL

                                SELECT 
                                    id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
                                    'Self-Ticket' AS source_type
                                FROM helpdesk.self_tickets
                            ) t

                        LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                        LEFT JOIN helpdesk.users u ON t.user_id = u.id
                        LEFT JOIN users u2 ON t.status_update_by = u2.id
                        LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                        LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                        LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                        -- LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_vendor = ucm.client_id
                        LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                        AND ucm.business_entity_id = c.id
                        LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                        LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                            AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                        LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                            AND t.team_id = tst.team_id AND tst.srv_time_status != 0

                        WHERE t.business_entity_id = '$businessEntity1'
                        -- AND t.client_id_helpdesk = '$userID'
                        -- AND t.user_id = '$userID'
                        AND (t.user_id = $userID OR t.client_id_helpdesk = $userID)
                        AND (
                            '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                        )
                        ORDER BY t.id,t.ticket_number DESC");

                    return ApiResponse::success($resources, "Success", 200);
                } elseif (!empty($businessEntity1) && empty($businessEntity) && !empty($statusName) && !empty($fromDate) && !empty($toDate) && $team1 === null && $response === null && $resolved === null &&  empty($ticketNumber)) {

                    // return 'I am 3';
                    $resources = DB::select("SELECT DISTINCT t.id,t.ticket_number,t.user_id,ucm.client_name,t.business_entity_id,c.company_name,t.team_id,t.sid,t.cat_id,categories.category_in_english,t.subcat_id,sub_categories.sub_category_in_english,t.status_id,
                            statuses.status_name,t.created_at,t.updated_at,
                            CASE 
                                        WHEN '$statusName' = 'Open' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                        WHEN '$statusName' = 'Closed' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                            )
                                    END AS ticket_age
                            ,u.username AS created_by
                            ,u2.username AS status_update_by
                            ,t.source_type

                        FROM (
                            SELECT 
                                id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
                                'Ticket' AS source_type
                            FROM helpdesk.tickets

                            UNION ALL

                            SELECT 
                                id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
                                'Self-Ticket' AS source_type
                            FROM helpdesk.self_tickets
                        ) t

                        LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                        LEFT JOIN helpdesk.users u ON t.user_id = u.id
                        LEFT JOIN users u2 ON t.status_update_by = u2.id
                        LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                        LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                        LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                        -- LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_vendor = ucm.client_id
                        LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                            AND ucm.business_entity_id = c.id
                        LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                        LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                            AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                        LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                            AND t.team_id = tst.team_id AND tst.srv_time_status != 0

                        WHERE t.business_entity_id = '$businessEntity1'
                        AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                        -- AND t.user_id = '$userID'
                        AND (t.user_id = $userID OR t.client_id_helpdesk = $userID)
                        AND (
                            '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                        )
                        ORDER BY t.id,t.ticket_number DESC");

                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity1) && empty($businessEntity) && !empty($statusName) && empty($fromDate) && empty($toDate) && $team1 === null && $response === null && $resolved === null &&  !empty($ticketNumber)) {

                    // return 'I am 3';
                    $resources = DB::select("SELECT DISTINCT t.id,t.ticket_number,t.user_id,ucm.client_name,t.business_entity_id,c.company_name,t.team_id,t.sid,t.cat_id,categories.category_in_english,t.subcat_id,sub_categories.sub_category_in_english,t.status_id,
                            statuses.status_name,t.created_at,t.updated_at,
                            CASE 
                                        WHEN '$statusName' = 'Open' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                        WHEN '$statusName' = 'Closed' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                            )
                                    END AS ticket_age
                            ,u.username AS created_by
                            ,u2.username AS status_update_by
                            ,t.source_type

                        FROM (
                            SELECT 
                                id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
                                'Ticket' AS source_type
                            FROM helpdesk.tickets

                            UNION ALL

                            SELECT 
                                id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
                                'Self-Ticket' AS source_type
                            FROM helpdesk.self_tickets
                        ) t

                        LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                        LEFT JOIN helpdesk.users u ON t.user_id = u.id
                        LEFT JOIN users u2 ON t.status_update_by = u2.id
                        LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                        LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                        LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                        -- LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_vendor = ucm.client_id
                        LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                            AND ucm.business_entity_id = c.id
                        LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                        LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                            AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                        LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                            AND t.team_id = tst.team_id AND tst.srv_time_status != 0

                        WHERE (t.user_id = $userID OR t.client_id_helpdesk = $userID)
                        AND (
                            '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                            OR '$statusName' = 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                        )
                        AND t.ticket_number = '$ticketNumber'
                        ORDER BY t.id,t.ticket_number DESC");

                    return ApiResponse::success($resources, "Success", 200);
                }
            } elseif ($userType == 'Customer') {
                // return 'i am  customer';

                if (!empty($businessEntity1) && $businessEntity === null && !empty($statusName) && $team1 === null && empty($fromDate) && empty($toDate) && $response === null && $resolved === null &&  empty($ticketNumber)) {

                    // return 'I am 1';

                    // return $userID;

                    $resources = DB::select("SELECT distinct
                                    t.id,t.ticket_number,t.user_id,  t.business_entity_id,c.company_name,t.team_id,t.sid,
                                    t.cat_id,categories.category_in_english,t.subcat_id,sub_categories.sub_category_in_english,
                                    t.status_id,statuses.status_name,t.created_at, t.updated_at,
                                    CASE 
                                        WHEN '$statusName' = 'Open' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                        WHEN '$statusName' = 'Closed' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                            )
                                    END AS ticket_age
                                    ,u.username AS created_by
                                    ,u2.username AS status_update_by
                                    -- ,t.source_type

                            -- FROM helpdesk.tickets t

                            -- FROM (
                            --     SELECT *, 'tickets' AS source_type FROM helpdesk.tickets
                            --     UNION ALL
                            --     SELECT *, 'selfTicket' AS source_type FROM helpdesk.self_tickets
                            -- ) t

                            FROM (
                            SELECT * FROM helpdesk.tickets
                            UNION ALL
                            SELECT * FROM helpdesk.self_tickets) t
                            LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                            LEFT JOIN helpdesk.users u ON t.user_id = u.id
                            LEFT JOIN users u2 ON t.status_update_by = u2.id
                                    LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                    LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                    
                                    LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                    -- LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_vendor = ucm.client_id
                                    LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                                    AND ucm.business_entity_id = c.id
                                    LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id

                                    LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                                    AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                                    
                                    LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                                    AND t.team_id = tst.team_id AND tst.srv_time_status != 0

                                    -- where t.business_entity_id = '$businessEntity1'
                                    -- AND t.client_id_helpdesk = '$userID'
                                    WHERE t.user_id = '$userID'
                                    AND t.business_entity_id = '$businessEntity1'
                                    AND (
                                    '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                    OR '$statusName'= 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                            ) order by t.ticket_number desc");

                    return ApiResponse::success($resources, "Success", 200);
                } elseif (!empty($businessEntity1) && $businessEntity === null && !empty($statusName) && !empty($fromDate) && !empty($toDate) && $team1 === null && $response === null && $resolved === null &&  empty($ticketNumber)) {

                    // return 'I am 3';
                    $resources = DB::select("SELECT distinct
                            t.id,t.ticket_number,t.user_id, t.business_entity_id,c.company_name,t.team_id,t.sid,
                                    t.cat_id,categories.category_in_english,t.subcat_id,sub_categories.sub_category_in_english,
                                    t.status_id,statuses.status_name,t.created_at, t.updated_at,
                                    CASE 
                                        WHEN '$statusName' = 'Open' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                        WHEN '$statusName' = 'Closed' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                            )
                                    END AS ticket_age
                                    ,u.username AS created_by
                                    ,u2.username AS status_update_by

                            -- FROM helpdesk.tickets t

                            FROM (
                            SELECT * FROM helpdesk.tickets
                            UNION ALL
                            SELECT * FROM helpdesk.self_tickets) t
                            LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                            LEFT JOIN helpdesk.users u ON t.user_id = u.id
                            LEFT JOIN users u2 ON t.status_update_by = u2.id
                            LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                            LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                            LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                            -- LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_vendor = ucm.client_id
                            LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                            AND ucm.business_entity_id = c.id
                            LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                            LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                            AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                            
                            LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                            AND t.team_id = tst.team_id AND tst.srv_time_status != 0
                            where t.business_entity_id = '$businessEntity1'
                            -- AND t.client_id_helpdesk = '$userID'
                            AND t.user_id = '$userID'
                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                            AND (
                            '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                OR '$statusName'= 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                            )
                            order by t.ticket_number desc
                            ");
                    return ApiResponse::success($resources, "Success", 200);
                } elseif (empty($businessEntity1) && $businessEntity === null && !empty($statusName) && empty($fromDate) && empty($toDate) && $team1 === null && $response === null && $resolved === null && !empty($ticketNumber)) {

                    // return 'I am 3';
                    $resources = DB::select("SELECT distinct
                            t.id,t.ticket_number,t.user_id, t.business_entity_id,c.company_name,t.team_id,t.sid,
                                    t.cat_id,categories.category_in_english,t.subcat_id,sub_categories.sub_category_in_english,
                                    t.status_id,statuses.status_name,t.created_at, t.updated_at,
                                    CASE 
                                        WHEN '$statusName' = 'Open' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                        WHEN '$statusName' = 'Closed' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                            )
                                    END AS ticket_age
                                    ,u.username AS created_by
                                    ,u2.username AS status_update_by

                            -- FROM helpdesk.tickets t

                            FROM (
                            SELECT * FROM helpdesk.tickets
                            UNION ALL
                            SELECT * FROM helpdesk.self_tickets) t
                            LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                            LEFT JOIN helpdesk.users u ON t.user_id = u.id
                            LEFT JOIN users u2 ON t.status_update_by = u2.id
                            LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                            LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                            LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                            -- LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_vendor = ucm.client_id
                            LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                            AND ucm.business_entity_id = c.id
                            LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                            LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                            AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                            
                            LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                            AND t.team_id = tst.team_id AND tst.srv_time_status != 0
                            AND t.user_id = '$userID'
                            AND (
                            '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                            OR '$statusName'= 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                            )
                            AND t.ticket_number = '$ticketNumber'
                            order by t.ticket_number desc
                            ");
                    return ApiResponse::success($resources, "Success", 200);
                }
            }
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function ticketCount(Request $request)
    {


        try {
            $team = $request->team;
            $teamIds = implode(',', $team);
            $userType = $request->userType;
            $userID = $request->userId;
            $businessEntity1 = $request->businessEntity1;
            $businessEntity = $request->businessEntity;

            if ($userType == 'Agent') {
                $resources = DB::select("SELECT 
                                                SUM(CASE WHEN t.status_id != 6 THEN 1 ELSE 0 END) AS open_count,
                                                SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count
                                                FROM helpdesk.tickets t WHERE t.team_id IN ($teamIds)");
                $responseObject = (object) [
                    'open_count' => $resources[0]->open_count ?? 0,
                    'close_count' => $resources[0]->close_count ?? 0,
                ];
                return ApiResponse::success($responseObject, "Success", 200);
            } elseif ($userType == 'Client') {

                $resources = DB::select("SELECT 
                    SUM(CASE WHEN t.status_id != 6 THEN 1 ELSE 0 END) AS open_count,
                    SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count
                FROM (
                    SELECT status_id 
                    FROM helpdesk.tickets 
                    WHERE (user_id = $userID OR client_id_helpdesk = $userID)
                    -- AND business_entity_id = '$businessEntity1'
                    AND (business_entity_id = '$businessEntity1' OR business_entity_id = '$businessEntity')
                    UNION ALL
                    SELECT status_id 
                    FROM helpdesk.self_tickets 
                    WHERE (user_id = $userID OR client_id_helpdesk = $userID)
                    -- AND business_entity_id = '$businessEntity1'
                    AND (business_entity_id = '$businessEntity1' OR business_entity_id = '$businessEntity')
                    ) t");
                $responseObject = (object) [
                    'open_count' => $resources[0]->open_count ?? 0,
                    'close_count' => $resources[0]->close_count ?? 0,
                ];
                return ApiResponse::success($responseObject, "Success", 200);
            } elseif ($userType == 'Customer') {
                $resources = DB::select("SELECT 
                                                SUM(CASE WHEN t.status_id != 6 THEN 1 ELSE 0 END) AS open_count,
                                                SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count
                                                FROM helpdesk.tickets t WHERE user_id IN ($userID)");
                $responseObject = (object) [
                    'open_count' => $resources[0]->open_count ?? 0,
                    'close_count' => $resources[0]->close_count ?? 0,
                ];
                return ApiResponse::success($responseObject, "Success", 200);
            }
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getTeamBySubcategoryId($id)
    {

        try {
            $resources = DB::select("SELECT sct.team_id, t.team_name
            FROM helpdesk.sub_category_teams sct
            JOIN  helpdesk.teams t ON sct.team_id = t.id
            where sct. sub_category_id = '$id'");
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function store(Request $request)
    {

        $recentClientId = Client::createClient($request->clientInfo);

        $ticketNumber  = TicketInfo::ticketNumberGenarate();

        $ccEmail = [];
        if (array_key_exists('ccEmail', $request->ticketInfo)) {
            $ccEmail = $request->ticketInfo['ccEmail'];
        }

        $attachments = [];
        if ($request->hasFile('ticketInfo.attachment')) {
            $attachments = $request->file('ticketInfo.attachment');
        }
        
        $branchId = $request->ticketInfo['branch'] ?? null;;
        $aggregatorId = $request->ticketInfo['aggregatorId'] ?? null;;

        $division = $request->clientInfo['division'] ?? null;;
        $district = $request->clientInfo['district'] ?? null;;

        $ticketData = [
            'ticket_number' => $ticketNumber,
            'platform_id'=>1,
            'user_id' => $request->loginUserId,
            'business_entity_id' => $request->ticketInfo['businessEntity'],
            'client_id_helpdesk' => $recentClientId,
            'client_id_vendor' => $request->ticketInfo['client'],
            'source_id' => $request->ticketInfo['source'],
            'cat_id' => $request->ticketInfo['category'],
            'subcat_id' => $request->ticketInfo['subCategory'],
            'priority_name' => $request->ticketInfo['priority'],
            'status_id' => $request->ticketInfo['status'],
            'team_id' => $request->ticketInfo['teamId'],
            'assigned_agent_id'=> $request->ticketInfo['assingedAgentId'],
            'mobile_no' => $request->ticketInfo['mobileNumber'] ?? null,
            'note' => $request->ticketInfo['descriptions'],
            'status_updated_by' => $request->loginUserId,
        ];

        if ($ticketData['business_entity_id'] == 8 || $ticketData['business_entity_id'] == 9) {
            $orbitData = [
                'ticket_number' => $ticketNumber,
                'client_type' => $request->clientInfo['clientType'],
                'client_id_helpdesk' => $recentClientId,
                'client_id_vendor' => $request->ticketInfo['client'],
                'billing_source' => $request->clientInfo['billingSource'],
                'sid_uid' => $request->ticketInfo['sid'],
                'fullname' => $request->clientInfo['fullName'],
                'phone' => $request->clientInfo['primaryEmail'],
            ];
        } else {
            $orbitData = null;
        }


        if ($ticketData['business_entity_id'] == 7) {
            $backboneData = [
                'backbone_element_id' => $request->ticketInfo['client'],
                'elementList' => $request->ticketInfo['elementList'],
                'elementListA' => $request->ticketInfo['elementListA'],
                'elementListB' => $request->ticketInfo['elementListB'],
            ];
        } else {
            $backboneData = null;
        }

        return TicketInfo::createTicket($ticketData, $ccEmail, $attachments, $backboneData, $orbitData, $branchId, $aggregatorId,$division,$district);
    }

    public function storeSelfTicket(Request $request)
    {
        try {
            // DB::transaction();
            DB::transaction(function () use ($request) {
                $recentClientId = Client::createChildClient($request->clientInfo);
                $defaultTeamId = Company::where('id', $request->businessEntity)
                    ->value('team_id');


                if (!$defaultTeamId) {
                    return ApiResponse::error("Error", "Default team not found. Please set default team for business entity", 500);
                }
                $ticketNumber  = TicketInfo::ticketNumberGenarate();

                $attachments = [];
                if ($request->hasFile('attachment')) {
                    $attachments = $request->file('attachment');
                }

                $ccEmail = $request->ccEmail;

                $ticketData = [
                    'ticket_number' => $ticketNumber,
                    'user_id' => $request->user_id,
                    'business_entity_id' => $request->businessEntity,
                    'client_id_helpdesk' =>  $recentClientId,
                    'client_id_vendor' => $request->client,
                    'sid' => $request->sid,
                    'source_id' => $request->source,
                    'cat_id' => $request->category,
                    'subcat_id' => $request->subCategory,
                    'priority_name' => $request->priority,
                    'status_id' => $request->status,
                    'team_id' => $request->teamId ?? $defaultTeamId,
                    'ref_ticket_no' => $request->refTicket,
                    // 'attached_filename' => $attached,
                    'note' => $request->descriptions,
                    // 'cc_email' => $ccEmail,
                ];

                SelfTicket::create($ticketData);

                // for multiple attachtment
                if (!empty($attachments)) {
                    foreach ($attachments as $file) {
                        $fileExtension = $file->getClientOriginalExtension();
                        $orginalName = $file->getClientOriginalName();
                        $customizeName = time() . '_' . uniqid() . '.' . $fileExtension;
                        $size = $file->getSize();
                        $mimeType = $file->getMimeType();
                        $filePath = $file->storeAs('ticket_attachments', $customizeName, 'public');
                        $attached[] = 'storage/' . $filePath;

                        SelfTicketAttachment::create([
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

                if ($ticketData['business_entity_id'] == 8 || $ticketData['business_entity_id'] == 9) {
                    $orbitData = [
                        'ticket_number' => $ticketNumber,
                        'client_type' => $request->clientInfo['clientType'],
                        'client_id_helpdesk' =>  $recentClientId,
                        'client_id_vendor' => $request->client,
                        'billing_source' => $request->clientInfo['billingSource'],
                        'sid_uid' => $request->sid,
                        'fullname' => $request->clientInfo['fullName'],
                        'phone' => $request->clientInfo['primaryEmail'],
                    ];
                    SelfTicketOrbit::create($orbitData);
                } else {
                    $orbitData = null;
                    SelfTicketOrbit::create($orbitData);
                }

                DB::commit();
            });
            return ApiResponse::success([], "Successfully Created", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function selfTicketShow(Request $request)
    {
        try {
            $statusName = ucfirst($request->status);
            $businessEntity = $request->businessEntity;
            // $team = $request->team;
            $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
            $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

            // $response = ($request->slaMissed === "Response") ? 2 : null;
            // $resolved = ($request->slaMissed === "Resolved") ? 2 : null;

            // $userType = $request->userType;

            $userID = $request->userId;


            if (!empty($businessEntity) && !empty($statusName)) {

                // return 'I am 1';

                $resources = DB::select("SELECT distinct
                                t.id,t.ticket_number, ucm.client_name, t.business_entity_id,c.company_name,t.team_id,
                                t.cat_id,categories.category_in_english,t.subcat_id,sub_categories.sub_category_in_english,
                                t.status_id,statuses.status_name, t.updated_at,
                                CASE 
                                        WHEN '$statusName' = 'Open' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                        WHEN '$statusName' = 'Closed' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                            )
                                    END AS ticket_age
                                ,u.username AS created_by
                                ,'Self-Ticket' AS source_type
                                FROM helpdesk.self_tickets t
                                LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                                LEFT JOIN helpdesk.users u ON t.user_id = u.id
                                LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                                LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                                LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                                -- LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_vendor = ucm.client_id
                                LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                                LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                                LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                                AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                                LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                                AND t.team_id = tst.team_id AND tst.srv_time_status != 0

                                where t.business_entity_id = '$businessEntity'
                                AND t.client_id_helpdesk = '$userID'
                                AND (
                                '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                                OR '$statusName'= 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                        )
            order by t.ticket_number desc");
                return ApiResponse::success($resources, "Success", 200);
            } elseif (!empty($businessEntity) && !empty($statusName) && !empty($fromDate) && !empty($toDate)) {

                // return 'I am 3';
                $resources = DB::select("SELECT distinct
                        t.id,t.ticket_number, ucm.client_name, t.business_entity_id,c.company_name,t.team_id,
                                t.cat_id,categories.category_in_english,t.subcat_id,sub_categories.sub_category_in_english,
                                t.status_id,statuses.status_name, t.updated_at,
                                CASE 
                                        WHEN '$statusName' = 'Open' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                        WHEN '$statusName' = 'Closed' THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                                            )
                                    END AS ticket_age
                                ,u.username AS created_by
                                ,'Self-Ticket' AS source_type

                        FROM helpdesk.self_tickets t
                        LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                        LEFT JOIN helpdesk.users u ON t.user_id = u.id
                        LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                        LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                        LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                        -- LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_vendor = ucm.client_id
                        LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                        LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                        LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                        AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                        
                        LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                        AND t.team_id = tst.team_id AND tst.srv_time_status != 0
                        where t.business_entity_id = '$businessEntity'
                        AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                        AND t.client_id_helpdesk = '$userID'
                        AND (
                        '$statusName' = 'Open' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name != 'Closed')
                            OR '$statusName'= 'Closed' AND t.status_id IN (SELECT id FROM helpdesk.statuses WHERE status_name = 'Closed')
                        )
                        order by t.ticket_number desc
                        ");
                return ApiResponse::success($resources, "Success", 200);
            }
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function insertSelfTicketToTicket(Request $request)
    {
        $ticketNumber = $request->input('ticket_number');
        $ccEmail = [];

        if (!$ticketNumber) {
            return ApiResponse::error('Ticket number is required', "Error", 500);
        }

        $selfTicket = SelfTicket::where('ticket_number', $ticketNumber)->first();
        $attachments = SelfTicketAttachment::where('ticket_number', $ticketNumber)->get();
        $orbit = SelfTicketOrbit::where('ticket_number', $ticketNumber)->first();

        if (!$selfTicket) {
            return ApiResponse::error('Ticket not found', "Error", 500);
        }


        $ticketData = $selfTicket->toArray();
        $orbitData = $orbit->toArray();
        // $orbitData = $orbit;

        // Reset ticket number assign again
        unset($ticketData['ticket_number']);
        unset($orbitData['ticket_number']);
        $generatedTicketNumber = TicketInfo::ticketNumberGenarate();
        $ticketData['ticket_number'] = $generatedTicketNumber;
        $orbitData['ticket_number'] = $generatedTicketNumber;

        $attachmentsData = $attachments->map(function ($attachment) use ($generatedTicketNumber) {
            return [
                'ticket_number'  => $generatedTicketNumber,
                'name'           => $attachment->name,
                'customize_name' => $attachment->customize_name,
                'size'           => $attachment->size,
                'url'            => $attachment->url,
                'mime_type'      => $attachment->mime_type,
                'storage_type'   => $attachment->storage_type,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        })->toArray();

        // $newTicket = TicketInfo::createTicket($ticketData, $ccEmail, null, null, $orbitData);
        $newTicket = TicketInfo::createTicket($ticketData, $ccEmail, null, null, $orbitData, null, null, null, null);
        TicketAttachment::insert($attachmentsData);
        if ($newTicket) {
            $selfTicket->delete();
            $orbit->delete();
            SelfTicketAttachment::where('ticket_number', $ticketNumber)->delete();

            return ApiResponse::success($newTicket, "Ticket Forwarded successfully", 201);
        }
        return ApiResponse::error([], "Error", 500);
    }

    

    // public function forwardToHQ(Request $request)
    // {
    //     $recentClientId = Client::createChildClient($request->clientInfo);
        
    //     $teamMapping = TeamMappingForPartner::where('company_id', $request->businessEntity)
    //         ->where('subcategory_id', $request->subCategory)
    //         ->where('is_active', true)
    //         ->first();
        
    //     if ($teamMapping) {
    //         $teamId = $teamMapping->team_id;
    //     } else {
    //         $teamId = $map[$request->subCategoryName] ?? null;
    //         if (!$teamId) {
    //             return ApiResponse::error(
    //                 'No team mapping or fallback team found.',
    //                 'Error',
    //                 422
    //             );
    //         }
    //     }
        
    //     $ticketNumber = TicketInfo::ticketNumberGenarate();
    //     $attachments = $request->file('attachment', []);
    //     $ccEmail = $request->ccEmail;
        
    //     $ticketData = [
    //         'ticket_number'      => $ticketNumber,
    //         'is_parent'          => 0,
    //         'platform_id'        => $request->platform_id ?? 1,
    //         'user_id'            => $request->user_id,
    //         'status_updated_by'  => $request->user_id,
    //         'business_entity_id' => $request->businessEntity,
    //         'client_id_helpdesk' => $recentClientId,
    //         'client_id_vendor'   => $request->client,
    //         'source_id'          => $request->source,
    //         'cat_id'             => $request->category,
    //         'subcat_id'          => $request->subCategory,
    //         'priority_name'      => $request->priority,
    //         'status_id'          => $request->status,
    //         'team_id'            => $teamId, 
    //         'note'               => $request->descriptions,
    //         'mobile_no'          => $request->mobileNumber,
    //     ];
        
    //     $orbitData = in_array($ticketData['business_entity_id'], [8, 9])
    //         ? [
    //             'ticket_number'      => $ticketNumber,
    //             'client_type'        => $request->clientInfo['clientType'],
    //             'client_id_helpdesk' => $recentClientId,
    //             'client_id_vendor'   => $request->client,
    //             'billing_source'     => $request->clientInfo['billingSource'],
    //             'sid_uid'            => $request->sid,
    //             'fullname'           => $request->clientInfo['fullName'],
    //             'phone'              => $request->clientInfo['primaryEmail'],
    //         ]
    //         : null;
        
    //     // ✅ INSERT INTO ticket_assign_team_logs
    //     try {
    //         TicketAssignTeamLog::create([
    //             'ticket_number' => $ticketNumber,
    //             'assigned_in'   => $teamId,
    //             'assigned_out'  => null, // Will be null when ticket is first assigned
    //         ]);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error(
    //             'Error creating ticket assignment log: ' . $e->getMessage(),
    //             'Error',
    //             500
    //         );
    //     }
        
    //     // 7️⃣ Create ticket
    //     return TicketInfo::ticketCreatedByClient(
    //         $ticketData,
    //         $ccEmail,
    //         $attachments,
    //         $orbitData
    //     );
    // }


    public function forwardToHQ(Request $request)
    {
        $recentClientId = Client::createChildClient($request->clientInfo);
        
        $teamMapping = TeamMappingForPartner::where('company_id', $request->businessEntity)
            ->where('subcategory_id', $request->subCategory)
            ->where('is_active', true)
            ->first();
        
        if ($teamMapping) {
            $teamId = $teamMapping->team_id;
        } else {
            $teamId = $map[$request->subCategoryName] ?? null;
            if (!$teamId) {
                return ApiResponse::error(
                    'No team mapping or fallback team found.',
                    'Error',
                    422
                );
            }
        }
        
        $ticketNumber = TicketInfo::ticketNumberGenarate();
        $attachments = $request->file('attachment', []);
        $ccEmail = $request->ccEmail;
        
        $ticketData = [
            'ticket_number'      => $ticketNumber,
            'is_parent'          => 0,
            'platform_id'        => $request->platform_id ?? 1,
            'user_id'            => $request->user_id,
            'status_updated_by'  => $request->user_id,
            'business_entity_id' => $request->businessEntity,
            'client_id_helpdesk' => $recentClientId,
            'client_id_vendor'   => $request->client,
            'source_id'          => $request->source,
            'cat_id'             => $request->category,
            'subcat_id'          => $request->subCategory,
            'priority_name'      => $request->priority,
            'status_id'          => $request->status,
            'team_id'            => $teamId, 
            'note'               => $request->descriptions,
            'mobile_no'          => $request->mobileNumber,
        ];
        
        // Add aggregator_id if business entity is 8 or 9
        if (in_array($ticketData['business_entity_id'], [8, 9]) && $request->has('aggregatorId')) {
            $ticketData['aggregator_id'] = $request->aggregatorId;
        }
        
        $orbitData = in_array($ticketData['business_entity_id'], [8, 9])
            ? [
                'ticket_number'      => $ticketNumber,
                'client_type'        => $request->clientInfo['clientType'],
                'client_id_helpdesk' => $recentClientId,
                'client_id_vendor'   => $request->client,
                'billing_source'     => $request->clientInfo['billingSource'],
                'sid_uid'            => $request->sid,
                'fullname'           => $request->clientInfo['fullName'],
                'phone'              => $request->clientInfo['primaryEmail'],
                'aggregator_id'      => $request->aggregatorId ?? null,
            ]
            : null;
        
        // ✅ INSERT INTO ticket_assign_team_logs
        try {
            TicketAssignTeamLog::create([
                'ticket_number' => $ticketNumber,
                'assigned_in'   => $teamId,
                'assigned_out'  => null,
            ]);
        } catch (\Exception $e) {
            return ApiResponse::error(
                'Error creating ticket assignment log: ' . $e->getMessage(),
                'Error',
                500
            );
        }
        
        // ✅ FIRST RESPONSE SLA LOGIC
        try {
            $firstResConfig = FirstResConfig::where('team_id', $teamId)->first();
            
            if ($firstResConfig) {
                FirstResSla::create([
                    'ticket_number'       => $ticketNumber,
                    'first_res_config_id' => $firstResConfig->id,
                    'sla_status'          => 2,
                ]);
                
                FirstResSlaHistory::create([
                    'ticket_number'       => $ticketNumber,
                    'first_res_config_id' => $firstResConfig->id,
                    'sla_status'          => 2,
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error creating First Response SLA: ' . $e->getMessage());
        }
        
        // ✅ SERVICE TIME SLA LOGIC
        try {
            // Check if client-specific SLA config exists
            $slaClientConfig = SlaClientConfig::where('client_id', $recentClientId)
                ->first();
            
            if ($slaClientConfig) {
                // Client-specific SLA exists
                SrvTimeClientSla::create([
                    'ticket_number'        => $ticketNumber,
                    'sla_client_config_id' => $slaClientConfig->id,
                    'sla_status'           => 2,
                ]);
                
                SrvTimeClientSlaHistory::create([
                    'ticket_number'        => $ticketNumber,
                    'sla_client_config_id' => $slaClientConfig->id,
                    'sla_status'           => 2,
                    // 'created_at'           => now(),
                ]);
            } else {
                // Check for subcategory-specific SLA config
                $slaSubcatConfig = SlaSubcatConfig::where('business_entity_id', $request->businessEntity)
                    ->where('team_id', $teamId)
                    ->where('subcategory_id', $request->subCategory)
                    ->first();
                
                if ($slaSubcatConfig) {
                    SrvTimeSubcatSla::create([
                        'ticket_number'        => $ticketNumber,
                        'sla_subcat_config_id' => $slaSubcatConfig->id,
                        'sla_status'           => 2,
                    ]);
                    
                    SrvTimeSubcatSlaHistory::create([
                        'ticket_number'        => $ticketNumber,
                        'sla_subcat_config_id' => $slaSubcatConfig->id,
                        'sla_status'           => 2,
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error creating Service Time SLA: ' . $e->getMessage());
        }
        
        // 7️⃣ Create ticket
        return TicketInfo::ticketCreatedByClient(
            $ticketData,
            $ccEmail,
            $attachments,
            $orbitData
        );
    }

    public function show($id)
    {
        try {
            $resources = Notification::findOrFail($id);
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }



    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $resources = Notification::findOrFail($id);
            $resources->update([
                'notification_name' => $request->notificationName,
                'email_template_id' => $request->emailTemplate,
                'client' => $request->client,
                'status' => $request->status,
            ]);
            DB::commit();
            return ApiResponse::success($resources, "Successfully Updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    public function assignTeamAndStore(Request $request)
    {

        DB::beginTransaction();

        try {
          // ===============================
        // 1. INPUTS
        // ===============================
        $ticketNumber = $request->ticketNumber;
        $userId       = $request->userId;
        $teamId       = $request->teamId;
        $agentId      = (int) $request->agentId;
        $commentText  = $request->comment;
        $isInternal   = $request->isInternal;
        $attachments = $request->file('attachedFile', []);

        
        // ===============================
        // 2. LOAD DATA
        // ===============================
        $ticket = OpenTicket::where('ticket_number', $ticketNumber)->first();
        $outTeamId = $ticket->team_id;
        $outAgentId = $ticket->assigned_agent_id;

        TicketInfo::firstResponseSla($ticketNumber, $ticket->updated_at,$teamId);

        $clientSla = SrvTimeClientSla::where('ticket_number', $ticketNumber)->first();
        if($clientSla){
          TicketInfo::serviceTimeSlaClient($ticketNumber, $ticket->updated_at);
        }else{
          TicketInfo::serviceTimeSlaSubcategory($ticketNumber, $ticket->updated_at, $teamId);
        }

        // ===============================
        // 5. UPDATE TICKET
        // ===============================
        $ticket->update([
            'team_id'            => $teamId,
            'assigned_agent_id'  => $agentId,
        ]);

        // ===============================
        // 6. CREATE COMMENT
        // ===============================
        $comment = TicketComment::create([
            'ticket_number' => $ticketNumber,
            'user_id'       => $userId,
            'team_id'       => $outTeamId,
            'comments'      => $commentText,
            'attached_file' => null,
            'is_internal'   => $isInternal,
        ]);

        // ===============================
        // 7. ATTACHMENTS
        // ===============================
        // foreach ($attachments as $file) {
            TicketInfo::storeCommentAttachment($attachments, $comment, $ticketNumber);
        // }

        // ===============================
        // 8. TICKET HISTORY
        // ===============================
        TicketHistory::create($ticket->toArray());

        // ===============================
        // 9. Assign In / Out
        
        TicketAssignTeamLog::create([
            'ticket_number' => $ticketNumber,
            'assigned_in'   => $teamId,
            'assigned_out'  => $outTeamId,
        ]);
        
        if($agentId){
          TicketAssignAgentLog::create([
            'ticket_number' => $ticketNumber,
            'assigned_in'   => $agentId,
            'assigned_out'  => $outAgentId,
        ]);
        }
        


            DB::commit();
            return ApiResponse::success($ticket, "Successfully Assgined", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function statusChanged_old($status, $ticketNo, $userId)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            $ticketNumber = $ticketNo;
            $ticketStatus = $status;
            $closedBy = $userId;

            // $sidNumber = $sid;

            // Check in SelfTicket
            $ticket = SelfTicket::where('ticket_number', $ticketNumber)->first();

            if ($ticket) {
                // Update SelfTicket if exists
                $ticket->update([
                    'status_update_by' => $closedBy,
                    'status_id' => $ticketStatus,
                ]);
            } else {
                // Check in Ticket
                $ticket = Ticket::where('ticket_number', $ticketNumber)->first();

                if ($ticket) {
                    $slaClientTickets = TicketFrTimeClient::where('ticket_number', $ticketNumber)->first();
                    $slaEscClientTickets = TicketFrTimeEscClient::where('ticket_number', $ticketNumber)->first();
                    $slaEscTeamTickets = TicketFrTimeEscTeam::where('ticket_number', $ticketNumber)->first();

                    $teamId = $ticket['team_id'];

                    $current_time = Carbon::now();
                    $ticket_created_at = $ticket->updated_at;
                    $ticketAge = $current_time->diffInMinutes($ticket_created_at);

                    $ticketNoForEmail = [
                        'ticket_number' => $ticket->ticket_number,
                        'user_id' => $ticket->user_id,
                        'cat_id' => $ticket->cat_id,
                        'subcat_id' => $ticket->subcat_id,
                        'client_id_helpdesk' => $ticket->client_id_helpdesk,
                        'business_entity_id' => $ticket->business_entity_id,
                        'sub_category_in_english' => $ticket->sub_category_in_english,
                        'created_at' => $ticket->created_at,
                        'ticketAge' => $ticketAge,
                        'status_update_by' => $closedBy,
                    ];

                    $ticket->update([
                        'status_update_by' => $closedBy,
                        'status_id' => $ticketStatus,
                    ]);
                    TicketHistory::create($ticket->toArray());

                    // Client and Team both First Response Handling
                    if ($slaClientTickets && $ticketAge <= $slaClientTickets->fr_response_time && $ticket->status_id == 6) {
                        $slaClientTickets->update([
                            'fr_response_status_id' => 1,
                            'fr_response_status_name' => 'success',
                        ]);
                        TicketFrTimeClientHistory::create($slaClientTickets->toArray());
                        $slaClientTickets->delete();
                    }

                    $slaSubcategoryTickets = TicketFrTimeTeam::where('ticket_number', $ticketNumber)->first();
                    if ($slaSubcategoryTickets && $ticketAge <= $slaSubcategoryTickets->fr_response_time && $ticket->status_id == 6) {
                        if ($slaSubcategoryTickets->fr_response_status != 1) {
                            $slaSubcategoryTickets->update([
                                'fr_response_status' => 1,
                                'fr_response_status_name' => 'success',
                            ]);
                            TicketFrTimeTeamHistory::create($slaSubcategoryTickets->toArray());
                        }
                        $slaSubcategoryTickets->delete();
                    }

                    // Client and Team both First Response Escalate Handling
                    if ($slaEscClientTickets && $ticketAge <= $slaEscClientTickets->escalate_fr_response_time && $ticket->status_id == 6) {
                        $slaEscClientTickets->update([
                            'status' => 1,
                            'status_name' => 'success',
                        ]);
                        TicketFrTimeEscClientHistory::create($slaEscClientTickets->toArray());
                        $slaEscClientTickets->delete();
                    }

                    if ($slaEscTeamTickets && $ticketAge <= $slaEscTeamTickets->escalate_fr_response_time && $ticket->status_id == 6) {
                        $slaEscTeamTickets->update([
                            'status' => 1,
                            'status_name' => 'success',
                        ]);
                        TicketFrTimeEscTeamHistory::create($slaEscTeamTickets->toArray());
                        $slaEscTeamTickets->delete();
                    }

                    // Client and Team Service Time Handling
                    $slaServiceTimeClientTickets = TicketSrvTimeClient::where('ticket_number', $ticketNumber)->first();
                    if ($slaServiceTimeClientTickets && $ticketAge <= $slaServiceTimeClientTickets->srv_time_duration && $ticket->status_id == 6) {
                        $slaServiceTimeClientTickets->update([
                            'srv_time_status' => 1,
                            'srv_time_status_name' => 'success',
                        ]);
                        TicketSrvTimeClientHistory::create($slaServiceTimeClientTickets->toArray());
                        $slaServiceTimeClientTickets->delete();
                    }

                    $slaSubcategorySrvTickets = TicketSrvTimeTeam::where('ticket_number', $ticketNumber)->first();
                    if ($slaSubcategorySrvTickets && $ticketAge <= $slaSubcategorySrvTickets->srv_time_duration && $ticket->status_id == 6) {
                        $slaSubcategorySrvTickets->update([
                            'srv_time_status' => 1,
                            'srv_time_status_name' => 'success',
                        ]);
                        TicketSrvTimeTeamHistory::create($slaSubcategorySrvTickets->toArray());
                        $slaSubcategorySrvTickets->delete();
                    }

                    // Service Time Escalate Handling
                    $slaEscSrvClientTickets = TicketSrvTimeEscClient::where('ticket_number', $ticketNumber)->first();
                    $slaEscSrvTeamTickets = TicketSrvTimeEscTeam::where('ticket_number', $ticketNumber)->first();

                    if ($slaEscSrvClientTickets && $ticketAge <= $slaEscSrvClientTickets->escalate_srv_response_time && $ticket->status_id == 6) {
                        $slaEscSrvClientTickets->update([
                            'status' => 1,
                            'status_name' => 'success',
                        ]);
                        TicketSrvTimeEscClientHistory::create($slaEscSrvClientTickets->toArray());
                        $slaEscSrvClientTickets->delete();
                    }

                    if ($slaEscSrvTeamTickets && $ticketAge <= $slaEscSrvTeamTickets->escalate_srv_response_time && $ticket->status_id == 6) {
                        $slaEscSrvTeamTickets->update([
                            'status' => 1,
                            'status_name' => 'success',
                        ]);
                        TicketSrvTimeEscTeamHistory::create($slaEscSrvTeamTickets->toArray());
                        $slaEscSrvTeamTickets->delete();
                    }

                    // Send Email Notification
                    $recipient = DB::table('teams')->where('id', $teamId)->value('group_email');
                    if ($recipient) {
                        $emailTemplate = DB::table('email_templates')
                            ->where('id', 2)
                            ->where('status', 'Active')
                            ->first(['subject', 'content']);

                        $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                        $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);

                        if ($emailResult->status() !== 200) {
                            DB::rollBack();
                            return ApiResponse::error('Email sending failed', "Error", 500);
                        }
                    }

                    // if ($ticket->status_id == 6) {
                    //     if (!empty($sidNumber)) {
                    //         $smsController = new \App\Http\Controllers\SendSmsController();
                    //         $smsController->checkAndSendSMS(request());
                    //     }
                    // }

                }
            }

            DB::commit(); // Commit the transaction if everything is successful
            return ApiResponse::success($ticket, "Successfully Changed Status", 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


   

    public function statusChanged_forcloseEmail($status, $ticketNo, $userId)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            $ticketNumber = $ticketNo;
            $ticketStatus = $status;
            $closedBy = $userId;

            // Check in SelfTicket
            $ticket = SelfTicket::where('ticket_number', $ticketNumber)->first();

            if ($ticket) {
                // Update SelfTicket if exists
                $ticket->update([
                    'status_update_by' => $closedBy,
                    'status_id' => $ticketStatus,
                ]);
            } else {
                // Check in OpenTicket
                $ticket = OpenTicket::where('ticket_number', $ticketNumber)->first();

                if ($ticket) {
                    $teamId = $ticket['team_id'];

                    $current_time = Carbon::now();
                    $ticket_created_at = $ticket->updated_at;
                    $ticketAge = $current_time->diffInMinutes($ticket_created_at);

                    $ticketNoForEmail = [
                        'ticket_number' => $ticket->ticket_number,
                        'user_id' => $ticket->user_id,
                        'cat_id' => $ticket->cat_id,
                        'subcat_id' => $ticket->subcat_id,
                        'client_id_helpdesk' => $ticket->client_id_helpdesk,
                        'business_entity_id' => $ticket->business_entity_id,
                        'sub_category_in_english' => $ticket->sub_category_in_english,
                        'created_at' => $ticket->created_at,
                        'ticketAge' => $ticketAge,
                        'status_update_by' => $closedBy,
                    ];

                    // If status is On Hold (4), delete SLA records
                    if ($ticketStatus == 4) {
                        // Delete First Response SLA
                        FirstResSla::where('ticket_number', $ticketNumber)->delete();
                        
                        // Delete Service Time Client SLA (if exists)
                        SrvTimeClientSla::where('ticket_number', $ticketNumber)->delete();
                        
                        // Delete Service Time Subcategory SLA
                        SrvTimeSubcatSla::where('ticket_number', $ticketNumber)->delete();

                        // Update status in OpenTicket
                        $ticket->update([
                            'status_update_by' => $closedBy,
                            'status_id' => $ticketStatus,
                        ]);

                        // Create history for status change
                        TicketHistory::create($ticket->toArray());
                    }
                    // If status is Reopening from Hold (1), recreate SLA records
                    elseif ($ticketStatus == 1 && $ticket->status_id == 4) {
                        // Recreate First Response SLA using your existing function
                        TicketInfo::recreateFirstResponseSlaOnReopen($ticketNumber);
                        
                        // Recreate Service Time SLA using your existing function
                        TicketInfo::recreateServiceTimeSlaOnReopen($ticketNumber);

                        // Update status in OpenTicket
                        $ticket->update([
                            'status_update_by' => $closedBy,
                            'status_id' => $ticketStatus,
                        ]);

                        // Create history for status change
                        TicketHistory::create($ticket->toArray());
                    }
                    // If status is closed (6), process SLA and migrate to CloseTicket
                    elseif ($ticketStatus == 6) {
                        TicketInfo::processFirstResponseSla($ticketNumber, $ticket->updated_at);
                        
                        $clientSla = SrvTimeClientSla::where('ticket_number', $ticketNumber)->first();
                        if($clientSla) {
                            TicketInfo::serviceTimeSlaClient($ticketNumber, $ticket->updated_at);
                        } else {
                            TicketInfo::processServiceTimeSlaSubcategory($ticketNumber, $ticket->updated_at);
                        }

                        // Update status and created by in the OpenTicket before migrating
                        $ticket->update([
                            'status_update_by' => $closedBy,
                            'status_id' => $ticketStatus,
                        ]);

                        // Get the updated ticket data
                        $ticketData = $ticket->toArray();

                        // Create ticket history first
                        TicketHistory::create($ticketData);

                        // Create in CloseTicket table
                        CloseTicket::create($ticketData);

                        // Delete from OpenTicket table
                        $ticket->delete();
                    } 
                    // For any other status, just update the OpenTicket
                    else {
                        // Update the OpenTicket with new status
                        $ticket->update([
                            'status_update_by' => $closedBy,
                            'status_id' => $ticketStatus,
                        ]);

                        // Create history for status change
                        TicketHistory::create($ticket->toArray());
                    }

                    // Send Email Notification (optional - uncomment if needed)
                    $recipient = DB::table('teams')->where('id', $teamId)->value('group_email');
                    if ($recipient) {
                        $emailTemplate = DB::table('email_templates')
                            ->where('id', 2)
                            ->where('status', 'Active')
                            ->first(['subject', 'content']);

                        if ($emailTemplate) {
                            $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                            $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);

                            if ($emailResult->status() !== 200) {
                                DB::rollBack();
                                return ApiResponse::error('Email sending failed', "Error", 500);
                            }
                        }
                    }
                }
            }

            DB::commit(); // Commit the transaction if everything is successful
            return ApiResponse::success($ticket, "Successfully Changed Status", 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function statusChanged($status, $ticketNo, $userId)
    {
        DB::beginTransaction();

        try {
            $ticketNumber = $ticketNo;
            $ticketStatus = $status;
            $closedBy     = $userId;

            // Check in SelfTicket
            $ticket = SelfTicket::where('ticket_number', $ticketNumber)->first();

            if ($ticket) {
                $ticket->update([
                    'status_update_by' => $closedBy,
                    'status_id'        => $ticketStatus,
                ]);
            } else {
                // Check in OpenTicket
                $ticket = OpenTicket::where('ticket_number', $ticketNumber)->first();

                if ($ticket) {
                    $teamId = $ticket['team_id'];

                    $current_time       = Carbon::now();
                    $ticket_created_at  = $ticket->updated_at;
                    $ticketAge          = $current_time->diffInMinutes($ticket_created_at);

                    $ticketNoForEmail = [
                        'ticket_number'          => $ticket->ticket_number,
                        'user_id'                => $ticket->user_id,
                        'cat_id'                 => $ticket->cat_id,
                        'subcat_id'              => $ticket->subcat_id,
                        'client_id_helpdesk'     => $ticket->client_id_helpdesk,
                        'business_entity_id'     => $ticket->business_entity_id,
                        'sub_category_in_english'=> $ticket->sub_category_in_english,
                        'created_at'             => $ticket->created_at,
                        'ticketAge'              => $ticketAge,
                        'status_update_by'       => $closedBy,
                    ];

                    // If status is On Hold (4)
                    if ($ticketStatus == 4) {
                        FirstResSla::where('ticket_number', $ticketNumber)->delete();
                        SrvTimeClientSla::where('ticket_number', $ticketNumber)->delete();
                        SrvTimeSubcatSla::where('ticket_number', $ticketNumber)->delete();

                        $ticket->update([
                            'status_update_by' => $closedBy,
                            'status_id'        => $ticketStatus,
                        ]);

                        TicketHistory::create($ticket->toArray());
                    }
                    // If status is Reopening from Hold (1)
                    elseif ($ticketStatus == 1 && $ticket->status_id == 4) {
                        TicketInfo::recreateFirstResponseSlaOnReopen($ticketNumber);
                        TicketInfo::recreateServiceTimeSlaOnReopen($ticketNumber);

                        $ticket->update([
                            'status_update_by' => $closedBy,
                            'status_id'        => $ticketStatus,
                        ]);

                        TicketHistory::create($ticket->toArray());
                    }
                    // If status is closed (6)
                    elseif ($ticketStatus == 6) {
                        TicketInfo::processFirstResponseSla($ticketNumber, $ticket->updated_at);

                        $clientSla = SrvTimeClientSla::where('ticket_number', $ticketNumber)->first();
                        if ($clientSla) {
                            TicketInfo::serviceTimeSlaClient($ticketNumber, $ticket->updated_at);
                        } else {
                            TicketInfo::processServiceTimeSlaSubcategory($ticketNumber, $ticket->updated_at);
                        }

                        $ticket->update([
                            'status_update_by' => $closedBy,
                            'status_id'        => $ticketStatus,
                        ]);

                        $ticketData = $ticket->toArray();
                        TicketHistory::create($ticketData);
                        CloseTicket::create($ticketData);
                        $ticket->delete();
                    }
                    // Any other status
                    else {
                        $ticket->update([
                            'status_update_by' => $closedBy,
                            'status_id'        => $ticketStatus,
                        ]);

                        TicketHistory::create($ticket->toArray());
                    }

                    // ✅ Replace old email block — now uses sendTicketEmailForClose
                    //    which handles template matching, notify_client, fallback logic
                    //    ccEmail is null here since statusChanged has no cc param
                    self::sendTicketEmailForClose($ticket, $teamId, []);
                }
            }

            DB::commit();
            return ApiResponse::success($ticket, "Successfully Changed Status", 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    private static function sendTicketEmailForClose($ticket, $teamId, $ccEmail)
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
            ->where('et.event_id', 2)
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
                ->where('et.event_id', 2)
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
                ->where('id', 2)
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



    public function ticketReopened($status, $ticketNo, $userId)
    {
        DB::beginTransaction(); // Start the transaction

        try {
            $ticketNumber = $ticketNo;
            $ticketStatus = $status; // Status 1 for reopened
            $reopenedBy = $userId;

            // Check in CloseTicket table
            $ticket = CloseTicket::where('ticket_number', $ticketNumber)->first();

            if ($ticket) {
                // If ticket exists in CloseTicket, migrate back to OpenTicket
                if ($ticketStatus == 1) { // Status 1 = Open/Reopened
                    
                    // Prepare ticket data for reopening
                    $ticketData = $ticket->toArray();
                    
                    // Update the status and reopened by info
                    $ticketData['status_id'] = $ticketStatus;
                    $ticketData['status_update_by'] = $reopenedBy;
                    $ticketData['updated_at'] = now();

                    // Create ticket history for reopening
                    TicketHistory::create($ticketData);

                    // Create in OpenTicket table
                    OpenTicket::create($ticketData);

                    // Update status in CloseTicket before deleting (optional - for audit trail)
                    $ticket->update([
                        'status_update_by' => $reopenedBy,
                        'status_id' => $ticketStatus,
                        'updated_at' => now(),
                    ]);

                    // Delete from CloseTicket table
                    $ticket->delete();

                    // Prepare data for email notification
                    $teamId = $ticket->team_id;
                    $ticketNoForEmail = [
                        'ticket_number' => $ticket->ticket_number,
                        'user_id' => $ticket->user_id,
                        'cat_id' => $ticket->cat_id,
                        'subcat_id' => $ticket->subcat_id,
                        'client_id_helpdesk' => $ticket->client_id_helpdesk,
                        'business_entity_id' => $ticket->business_entity_id,
                        'sub_category_in_english' => $ticket->sub_category_in_english,
                        'created_at' => $ticket->created_at,
                        'reopened_at' => now(),
                        'status_update_by' => $reopenedBy,
                    ];

                    // Send Email Notification (optional - uncomment if needed)
                    // $recipient = DB::table('teams')->where('id', $teamId)->value('group_email');
                    // if ($recipient) {
                    //     $emailTemplate = DB::table('email_templates')
                    //         ->where('id', 3) // Use different template ID for reopening email
                    //         ->where('status', 'Active')
                    //         ->first(['subject', 'content']);

                    //     if ($emailTemplate) {
                    //         $emailController = new \App\Http\Controllers\v1\Settings\EmailController();
                    //         $emailResult = $emailController->sendEmailNotification($ticketNoForEmail, $teamId, $emailTemplate, $recipient);

                    //         if ($emailResult->status() !== 200) {
                    //             DB::rollBack();
                    //             return ApiResponse::error('Email sending failed', "Error", 500);
                    //         }
                    //     }
                    // }

                } else {
                    // If status is not 1 (reopening), just update the CloseTicket
                    $ticket->update([
                        'status_update_by' => $reopenedBy,
                        'status_id' => $ticketStatus,
                        'updated_at' => now(),
                    ]);

                    // Create history for status change
                    TicketHistory::create($ticket->toArray());
                }

            } else {
                // If ticket not found in CloseTicket, check in OpenTicket
                $ticket = OpenTicket::where('ticket_number', $ticketNumber)->first();

                if ($ticket) {
                    // If already in OpenTicket, just update status
                    $ticket->update([
                        'status_update_by' => $reopenedBy,
                        'status_id' => $ticketStatus,
                        'updated_at' => now(),
                    ]);

                    // Create history for status change
                    TicketHistory::create($ticket->toArray());

                } else {
                    // Ticket not found anywhere
                    DB::rollBack();
                    return ApiResponse::error('Ticket not found', "Error", 404);
                }
            }

            DB::commit(); // Commit the transaction if everything is successful
            return ApiResponse::success($ticket, "Successfully Reopened Ticket", 200);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    // public function commentStore(Request $request)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $ticketNumber = $request->ticketNumber;
    //         $userId = $request->userId;
    //         $teamId = $request->teamId;
    //         $isRCA = $request->isRCA;
    //         $commentText = $request->comment;
    //         $isInternal = $request->isInternal;
    //         $attachments = $request->file('attachedFile', []);

    //         $comment = TicketComment::create([
    //             'ticket_number' => $ticketNumber,
    //             'user_id' => $userId,
    //             'team_id' => $teamId,
    //             'comments' => $commentText,
    //             'attached_file' => null,
    //             'is_internal' => $isInternal,
    //             'is_rca' => $isRCA
    //         ]);

    //         // foreach ($attachments as $file) {
    //             TicketInfo::storeCommentAttachment($attachments, $comment, $ticketNumber);
    //         // }
            
    //         $ticket = OpenTicket::where('ticket_number', $ticketNumber)->first();
    //         if (!$ticket) {
    //             return ApiResponse::error("Ticket not found", "Error", 404);
    //         }

    //         TicketInfo::processFirstResponseSla($ticketNumber,$ticket->updated_at);

    //         // event(new CommentEvent($comment));
    //         DB::commit();

    //         return ApiResponse::success($comment, "Successfully Commented", 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }

    public function commentStore(Request $request)
    {
        try {
            DB::beginTransaction();

            $ticketNumber = $request->ticketNumber;
            $userId       = $request->userId;
            $teamId       = $request->teamId;
            $isRCA        = $request->isRCA;
            $commentText  = $request->comment;
            $isInternal   = $request->isInternal;
            $attachments  = $request->file('attachedFile', []);

            $comment = TicketComment::create([
                'ticket_number' => $ticketNumber,
                'user_id'       => $userId,
                'team_id'       => $teamId,
                'comments'      => $commentText,
                'attached_file' => null,
                'is_internal'   => $isInternal,
                'is_rca'        => $isRCA,
            ]);

            TicketInfo::storeCommentAttachment($attachments, $comment, $ticketNumber);

            $ticket = OpenTicket::where('ticket_number', $ticketNumber)->first();
            if (!$ticket) {
                return ApiResponse::error("Ticket not found", "Error", 404);
            }

            TicketInfo::commentFirstResponseSla($ticketNumber, $ticket->updated_at);
           

            // ✅ Call sendTicketEmailForComment, passing isInternal from request
            self::sendTicketEmailForComment($ticket, $teamId, [], (int) $isInternal);

            DB::commit();
            return ApiResponse::success($comment, "Successfully Commented", 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    // ✅ Added $isInternal parameter
    private static function sendTicketEmailForComment($ticket, $teamId, $ccEmail, int $isInternal = 0)
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

        // ✅ Step 1A: Try to find template matched by BOTH business_entity_id AND user_id
        $matchedTemplate = DB::table('email_templates as et')
            ->join('event_tags as ta', 'et.event_id', '=', 'ta.id')
            ->join('user_client_mappings as ucm', 'ucm.client_id', '=', 'et.client_id')
            ->where('et.status', 'active')
            ->where('et.event_id', 3)
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

        // ✅ Step 1B: Fallback — match by business_entity_id ONLY, skip notify_client
        $isBusinessEntityOnlyMatch = false;
        if (!$matchedTemplate) {
            Log::info('TicketEmail - no user-specific template found, falling back to business_entity_id only match.');

            $matchedTemplate = DB::table('email_templates as et')
                ->join('event_tags as ta', 'et.event_id', '=', 'ta.id')
                ->join('user_client_mappings as ucm', 'ucm.client_id', '=', 'et.client_id')
                ->where('et.status', 'active')
                ->where('et.event_id', 3)
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
                $isBusinessEntityOnlyMatch = true;
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

        // ✅ Step 4: Check is_internal to decide whether to include client email
        //
        //   is_internal = 0 → public comment → fetch & add client email (up.email_primary)
        //   is_internal = 1 → internal comment → skip client email entirely
        //
        if ($isInternal === 0) {
            Log::info('TicketEmail - comment is public (is_internal=0), checking client email.');

            // Fetch client email from user_client_mappings → user_profiles
            $clientEmailRecord = DB::table('user_client_mappings as ucm')
                ->join('user_profiles as up', 'ucm.user_id', '=', 'up.user_id')
                ->where('up.user_id', $ticket->client_id_helpdesk)
                ->first(['ucm.business_entity_id', 'ucm.user_id', 'ucm.client_id', 'up.email_primary']);

            if ($clientEmailRecord && filter_var($clientEmailRecord->email_primary, FILTER_VALIDATE_EMAIL)) {
                $agentEmails[] = $clientEmailRecord->email_primary;
                Log::info('TicketEmail - client email added: ' . $clientEmailRecord->email_primary);
            } else {
                Log::info('TicketEmail - no valid client email found for user_id: ' . $ticket->client_id_helpdesk);
            }
        } else {
            // ✅ is_internal = 1 — internal comment, do NOT add client email
            Log::info('TicketEmail - comment is internal (is_internal=1), skipping client email.');

            // Still apply notify_client logic from template matching (existing behaviour)
            if ($matchedTemplate && !$isBusinessEntityOnlyMatch && (int) $matchedTemplate->notify_client === 1) {
                $clientEmailRecord = DB::table('user_client_mappings as ucm')
                    ->join('user_profiles as up', 'ucm.user_id', '=', 'up.user_id')
                    ->where('up.user_id', $ticket->client_id_helpdesk)
                    ->first(['ucm.business_entity_id', 'ucm.user_id', 'ucm.client_id', 'up.email_primary']);

                if ($clientEmailRecord && filter_var($clientEmailRecord->email_primary, FILTER_VALIDATE_EMAIL)) {
                    $agentEmails[] = $clientEmailRecord->email_primary;
                    Log::info('TicketEmail - notify_client=1 (internal), adding client email: ' . $clientEmailRecord->email_primary);
                }
            } elseif ($isBusinessEntityOnlyMatch) {
                Log::info('TicketEmail - business_entity_id only match, notify_client check skipped.');
            } else {
                Log::info('TicketEmail - notify_client=0 or no matched template, skipping client email.');
            }
        }

        // ✅ Step 5: Merge all recipients
        $allRecipients = array_filter(array_merge(array_values($agentEmails), [$recipientEmail]));

        Log::info('TicketEmail - resolved recipients for ticket ' . $ticketNoForEmail['ticket_number'] . ': ' . implode(',', $allRecipients));

        $recipient = implode(',', $allRecipients);

        // ✅ Step 6: Use matched template, fallback to id=3
        if ($matchedTemplate && !empty($matchedTemplate->subject) && !empty($matchedTemplate->content)) {
            $emailTemplate = $matchedTemplate;
            Log::info('TicketEmail - using matched template: ' . $matchedTemplate->template_name);
        } else {
            Log::info('TicketEmail - no matched template found, falling back to template id=3');
            $emailTemplate = DB::table('email_templates')
                ->where('id', 3)
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

    public function getCommentsByTicketNumber($id)
    {
        try {


            $comments = DB::select("SELECT 
            tc.id,
            tc.ticket_number,
            tc.user_id,
            tc.team_id,
            tc.comments,
            tc.attached_file,
            tc.is_internal,
            tc.is_rca,
            tc.created_at,
            tc.updated_at,
            CASE
                WHEN TIMESTAMPDIFF(SECOND, tc.created_at, NOW()) < 60 THEN 'Just Now' 
                WHEN TIMESTAMPDIFF(MINUTE, tc.created_at, NOW()) < 60 THEN 
                    CONCAT(TIMESTAMPDIFF(MINUTE, tc.created_at, NOW()), ' minute', 
                        IF(TIMESTAMPDIFF(MINUTE, tc.created_at, NOW()) > 1, 's', ''), ' ago')  
                WHEN TIMESTAMPDIFF(HOUR, tc.created_at, NOW()) < 24 THEN 
                    CONCAT(TIMESTAMPDIFF(HOUR, tc.created_at, NOW()), ' hour', 
                        IF(TIMESTAMPDIFF(HOUR, tc.created_at, NOW()) > 1, 's', ''), ' ago') 
                WHEN TIMESTAMPDIFF(DAY, tc.created_at, NOW()) < 365 THEN
                    CONCAT(TIMESTAMPDIFF(DAY, tc.created_at, NOW()), ' day', 
                        IF(TIMESTAMPDIFF(DAY, tc.created_at, NOW()) > 1, 's', ''), ' ago') 
                ELSE 
                    CONCAT(TIMESTAMPDIFF(YEAR, tc.created_at, NOW()), ' year', 
                        IF(TIMESTAMPDIFF(YEAR, tc.created_at, NOW()) > 1, 's', ''), ' ago') 
            END AS comment_age,
            u.fullname,
            GROUP_CONCAT(DISTINCT tca.url) AS attachment_urls,
            GROUP_CONCAT(DISTINCT t.team_name) AS team_names
        FROM 
            helpdesk.ticket_comments tc 
        JOIN 
            helpdesk.user_profiles u ON tc.user_id = u.user_id
        LEFT JOIN 
            helpdesk.ticket_comment_attachments tca ON tc.id = tca.comment_id
        LEFT JOIN 
            helpdesk.user_team_mappings utm ON utm.user_id = tc.user_id
        LEFT JOIN 
            helpdesk.teams t ON utm.team_id = t.id
        WHERE 
            tc.ticket_number = '$id'
        GROUP BY 
            tc.id, u.fullname, 
            tc.ticket_number,
            tc.user_id,
            tc.team_id,
            tc.comments,
            tc.attached_file,
            tc.is_internal,
            tc.is_rca,
            tc.created_at,
            tc.updated_at
        ORDER BY 
            tc.created_at DESC
        ");

            return ApiResponse::success($comments, "success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function commentByUserTeam(Request $request)
    {
        try {
            $userType = $request->userType;
            $userId = $request->userId;
            $userTeam = $request->userTeam;

            if ($userType === "Client" || $userType === "Customer") {
                $comments = DB::select("SELECT c.ticket_number, c.comments,c.is_internal, c.created_at 'comment_created', t.created_at 'ticket_created', cat.category_in_english
                ,t.user_id, u.fullname,u.user_type, ucm.client_id, ucm.client_name,
                t.cat_id, cat.category_in_english,cat.category_in_bangla,
                t.subcat_id,subcat.sub_category_in_english,subcat.sub_category_in_bangla,
                t.team_id,teams.team_name,
                CASE 
                WHEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) < 60 THEN 
        'Just now'
        WHEN TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) < 60 THEN 
            CONCAT(TIMESTAMPDIFF(MINUTE, t.created_at, NOW()), ' minutes ago')
        WHEN TIMESTAMPDIFF(HOUR, t.created_at, NOW()) < 24 THEN 
            CONCAT(TIMESTAMPDIFF(HOUR, t.created_at, NOW()), ' hours ago')
        ELSE 
            CONCAT(TIMESTAMPDIFF(DAY, t.created_at, NOW()), ' days ago')
        END AS ticket_age,
        CASE 
        WHEN TIMESTAMPDIFF(SECOND, c.created_at, NOW()) < 60 THEN 
        'Just now'
        WHEN TIMESTAMPDIFF(MINUTE, c.created_at, NOW()) < 60 THEN 
            CONCAT(TIMESTAMPDIFF(MINUTE, c.created_at, NOW()), ' minutes ago')
        WHEN TIMESTAMPDIFF(HOUR, c.created_at, NOW()) < 24 THEN 
            CONCAT(TIMESTAMPDIFF(HOUR, c.created_at, NOW()), ' hours ago')
        ELSE 
            CONCAT(TIMESTAMPDIFF(DAY, c.created_at, NOW()), ' days ago')
        END AS comment_age
                FROM helpdesk.ticket_comments c
                JOIN helpdesk.tickets t ON t.ticket_number = c.ticket_number
                JOIN helpdesk.user_profiles u ON u.user_id = t.user_id
                JOIN helpdesk.user_client_mappings ucm ON ucm.client_id = t.client_id_vendor
                JOIN helpdesk.categories cat ON cat.id = t.cat_id
                JOIN helpdesk.sub_categories subcat ON subcat.id = t.subcat_id
                JOIN helpdesk.teams ON teams.id = t.team_id
                where t.user_id = '$userId'
                and c.is_internal = 0
                order by c.created_at desc
                limit 0,1");

                return ApiResponse::success($comments, "success", 200);
            } else {

                $comments = DB::select("SELECT c.ticket_number, c.comments,c.is_internal, c.created_at 'comment_created', t.created_at 'ticket_created', cat.category_in_english
                ,t.user_id, u.fullname,u.user_type, ucm.client_id, ucm.client_name,
                t.cat_id, cat.category_in_english,cat.category_in_bangla,
                t.subcat_id,subcat.sub_category_in_english,subcat.sub_category_in_bangla,
                t.team_id,teams.team_name,
                CASE 
                WHEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) < 60 THEN 
                            'Just now'
                            WHEN TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) < 60 THEN 
                                CONCAT(TIMESTAMPDIFF(MINUTE, t.created_at, NOW()), ' minutes ago')
                            WHEN TIMESTAMPDIFF(HOUR, t.created_at, NOW()) < 24 THEN 
                                CONCAT(TIMESTAMPDIFF(HOUR, t.created_at, NOW()), ' hours ago')
                            ELSE 
                                CONCAT(TIMESTAMPDIFF(DAY, t.created_at, NOW()), ' days ago')
                            END AS ticket_age,
                            CASE 
                            WHEN TIMESTAMPDIFF(SECOND, c.created_at, NOW()) < 60 THEN 
                            'Just now'
                            WHEN TIMESTAMPDIFF(MINUTE, c.created_at, NOW()) < 60 THEN 
                                CONCAT(TIMESTAMPDIFF(MINUTE, c.created_at, NOW()), ' minutes ago')
                            WHEN TIMESTAMPDIFF(HOUR, c.created_at, NOW()) < 24 THEN 
                                CONCAT(TIMESTAMPDIFF(HOUR, c.created_at, NOW()), ' hours ago')
                            ELSE 
                                CONCAT(TIMESTAMPDIFF(DAY, c.created_at, NOW()), ' days ago')
                            END AS comment_age
                                    FROM helpdesk.ticket_comments c
                                    JOIN helpdesk.tickets t ON t.ticket_number = c.ticket_number
                                    JOIN helpdesk.user_profiles u ON u.user_id = t.user_id
                                    JOIN helpdesk.user_client_mappings ucm ON ucm.client_id = t.client_id_vendor
                                    JOIN helpdesk.categories cat ON cat.id = t.cat_id
                                    JOIN helpdesk.sub_categories subcat ON subcat.id = t.subcat_id
                                    JOIN helpdesk.teams ON teams.id = t.team_id
                                    where t.team_id = '$userTeam'
                                    order by c.created_at desc
                                    limit 0,1");
                return ApiResponse::success($comments, "success", 200);
            }
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function violatedFirstResponseTime($id)
    {

        try {

            $violated = DB::select("SELECT frth.ticket_number, frth.team_id,frth.created_at as violate_age ,t.created_at as ticket_age,
            NULL AS client_id,
            t.user_id, u.fullname,u.user_type,
            t.client_id_vendor,ucm.client_name,
            t.cat_id,cat.category_in_bangla,cat.category_in_english,
            t.subcat_id, subcat.sub_category_in_bangla,subcat.sub_category_in_english,
            t.status_id,
            s.status_name

            FROM helpdesk.ticket_fr_time_team_histories frth
            JOIN helpdesk.tickets t ON t.ticket_number = frth.ticket_number
            JOIN helpdesk.users u ON u.id = t.user_id
            JOIN helpdesk.user_client_mappings ucm ON ucm.client_id = t.client_id_vendor
            JOIN helpdesk.categories cat ON cat.id = t.cat_id
            JOIN helpdesk.sub_categories subcat ON subcat.id = t.subcat_id
            JOIN helpdesk.statuses s ON s.id = t.status_id
            and frth.fr_response_status_name = 'violated'
            and s.status_name = 'Open'
            and frth.team_id = '$id'

            union

            SELECT frth.ticket_number, frth.client_id, frth.created_at as violate_age ,t.created_at as ticket_age,
            t.team_id,
            t.user_id, u.fullname,u.user_type,
            t.client_id_vendor,ucm.client_name,
            t.cat_id,cat.category_in_bangla,cat.category_in_english,
            t.subcat_id, subcat.sub_category_in_bangla,subcat.sub_category_in_english,
            t.status_id,
            s.status_name

            FROM helpdesk.ticket_fr_time_client_histories frth
            JOIN helpdesk.tickets t ON t.ticket_number = frth.ticket_number
            JOIN helpdesk.user_profiles u ON u.id = t.user_id
            JOIN helpdesk.user_client_mappings ucm ON ucm.client_id = t.client_id_vendor
            JOIN helpdesk.categories cat ON cat.id = t.cat_id
            JOIN helpdesk.sub_categories subcat ON subcat.id = t.subcat_id
            JOIN helpdesk.statuses s ON s.id = t.status_id

            where frth.fr_response_status_name = 'violated'
            and s.status_name = 'Open'
            and t.team_id = '$id'
            ");


            return ApiResponse::success($violated, "success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function violatedServiceResponseTime($id)
    {


        try {

            $violated = DB::select("SELECT frth.ticket_number, frth.team_id,frth.created_at as violate_age ,t.created_at as ticket_age,
            t.user_id, u.fullname,u.user_type,
            t.client_id_vendor,ucm.client_name,
            t.cat_id,cat.category_in_bangla,cat.category_in_english,
            t.subcat_id, subcat.sub_category_in_bangla,subcat.sub_category_in_english,
            t.status_id,
            s.status_name
            FROM helpdesk.ticket_srv_time_team_histories frth
            JOIN helpdesk.tickets t ON t.ticket_number = frth.ticket_number
            JOIN helpdesk.user_profiles u ON u.id = t.user_id
            JOIN helpdesk.user_client_mappings ucm ON ucm.client_id = t.client_id_vendor
            JOIN helpdesk.categories cat ON cat.id = t.cat_id
            JOIN helpdesk.sub_categories subcat ON subcat.id = t.subcat_id
            JOIN helpdesk.statuses s ON s.id = t.status_id
            where frth.team_id = 1
            and frth.srv_time_status_name = 'violated'
            and s.status_name = 'Open'

            union

            SELECT frth.ticket_number, frth.client_id,frth.created_at as violate_age ,t.created_at as ticket_age,
            t.user_id, u.fullname,u.user_type,
            t.client_id_vendor,ucm.client_name,
            t.cat_id,cat.category_in_bangla,cat.category_in_english,
            t.subcat_id, subcat.sub_category_in_bangla,subcat.sub_category_in_english,
            t.status_id,
            s.status_name
            FROM helpdesk.ticket_srv_time_client_histories frth
            JOIN helpdesk.tickets t ON t.ticket_number = frth.ticket_number
            JOIN helpdesk.user_profiles u ON u.id = t.user_id
            JOIN helpdesk.user_client_mappings ucm ON ucm.client_id = t.client_id_vendor
            JOIN helpdesk.categories cat ON cat.id = t.cat_id
            JOIN helpdesk.sub_categories subcat ON subcat.id = t.subcat_id
            JOIN helpdesk.statuses s ON s.id = t.status_id
            where frth.client_id = 1
            and frth.srv_time_status_name = 'violated'
            and s.status_name = 'Open'
            ");


            return ApiResponse::success($violated, "success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $resources = Notification::findOrFail($id);
            $resources->delete();
            DB::commit();
            return ApiResponse::success(null, "Successfully Deleted", 204);
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::error($th->getMessage(), "Error", 500);
        }
    }




    

    public function getTicketDetailsByTicket($id)
    {
        $ticketNumbers = $id;
        $ticketQuery = DB::table(DB::raw("( SELECT th.ticket_number,c.company_name,departs.department_name, t.team_name,
            ucm.client_name,cat.category_in_english,scat.sub_category_in_english,st.status_name,
            GROUP_CONCAT(DISTINCT u.username) AS created_by,GROUP_CONCAT(DISTINCT u2.username) AS status_updated_by,
            GROUP_CONCAT(DISTINCT tc.comments) AS comments,GROUP_CONCAT(DISTINCT t1.team_name) AS agent_team_names,
            th.updated_at,th.created_at,ssc.resolution_min AS srv_time_str, ssh.sla_status AS srv_time_status_name
            FROM helpdesk.ticket_histories AS th
            JOIN users AS u ON th.user_id = u.id
            JOIN helpdesk.companies AS c ON th.business_entity_id = c.id
            JOIN helpdesk.teams AS t ON th.team_id = t.id
            JOIN helpdesk.user_client_mappings AS ucm ON th.client_id_helpdesk = ucm.user_id
            JOIN helpdesk.categories AS cat ON th.cat_id = cat.id
            JOIN helpdesk.sub_categories AS scat ON th.subcat_id = scat.id
            LEFT JOIN helpdesk.departments AS departs ON t.department_id = departs.id
            LEFT JOIN helpdesk.statuses AS st ON st.id = th.status_id
            LEFT JOIN users AS u2 ON th.status_updated_by = u2.id
            LEFT JOIN user_team_mappings AS utm ON th.status_updated_by = utm.user_id
            LEFT JOIN helpdesk.teams AS t1 ON utm.team_id = t1.id
            LEFT JOIN helpdesk.ticket_comments AS tc ON th.ticket_number = tc.ticket_number 
                AND tc.user_id = th.status_updated_by 
                AND th.updated_at = tc.updated_at
						
						LEFT JOIN helpdesk.sla_subcat_configs AS ssc
                ON ssc.business_entity_id = th.business_entity_id
                AND ssc.team_id = th.team_id
                AND ssc.subcategory_id = th.subcat_id

            LEFT JOIN helpdesk.srv_time_subcat_sla_histories AS ssh
                ON ssh.ticket_number = th.ticket_number
                AND ssh.sla_subcat_config_id = ssc.id

            WHERE th.ticket_number = '$ticketNumbers'
            GROUP BY th.ticket_number, c.company_name, departs.department_name, t.team_name, 
                ucm.client_name, cat.category_in_english, scat.sub_category_in_english, st.status_name, 
                th.updated_at, th.created_at,srv_time_str, srv_time_status_name

            UNION ALL

            SELECT tc.ticket_number,NULL AS company_name, NULL AS department_name,NULL AS team_name,NULL AS client_name,
                NULL AS category_in_english,NULL AS sub_category_in_english,NULL AS status_name,NULL AS created_by,
                GROUP_CONCAT(DISTINCT u.username) AS status_updated_by,GROUP_CONCAT(DISTINCT tc.comments) AS comments,
                GROUP_CONCAT(DISTINCT t1.team_name) AS agent_team_names,tc.created_at AS updated_at,
                NULL AS created_at,NULL AS srv_time_str,NULL AS srv_time_status_name
            FROM helpdesk.ticket_comments AS tc
            JOIN users AS u ON tc.user_id = u.id
            LEFT JOIN helpdesk.teams AS t1 ON tc.team_id = t1.id
            LEFT JOIN helpdesk.user_client_mappings AS ucm ON tc.user_id = ucm.user_id
            WHERE tc.ticket_number = '$ticketNumbers'
                AND NOT EXISTS (SELECT 1 
                    FROM helpdesk.ticket_histories AS th 
                    WHERE th.ticket_number = tc.ticket_number 
                    AND th.updated_at = tc.created_at
                )
            GROUP BY tc.ticket_number, tc.created_at) AS combined_query"))
            ->orderBy('updated_at', 'asc')
            ->get();


        // return $ticketQuery;

        $formattedDetails = $this->formatTicketDetails($ticketQuery);
        $ticketDetails[$ticketNumbers] = $formattedDetails;


        return response()->json($ticketDetails);
        // return response()->json($formattedDetails);
    }


    private function formatTicketDetails($query)
    {
        $formatted = [];
        $previousUpdatedAt = null;
        $levelCounter = 1;

        foreach ($query as $index => $ticket) {
            $timeDifference = '';
            if ($previousUpdatedAt) {
                $diffInSeconds = strtotime($ticket->updated_at) - strtotime($previousUpdatedAt);
                $hours = floor($diffInSeconds / 3600);
                $minutes = floor(($diffInSeconds % 3600) / 60);
                $seconds = $diffInSeconds % 60;
                $timeDifference = sprintf("%dh %dm %ds", $hours, $minutes, $seconds);
            }

            $ticket->time_difference = $timeDifference;
            $previousUpdatedAt = $ticket->updated_at;

            // Level 1 Details
            if ($levelCounter == 1) {
                $formatted[$ticket->ticket_number] = [
                    'ticket_number' => $ticket->ticket_number,
                    'company_name' => $ticket->company_name,
                    'client_name' => $ticket->client_name,
                    'category_subcategory' => $ticket->category_in_english . ' [' . $ticket->sub_category_in_english . ']',
                    'created_at' => $ticket->created_at,
                    'comments' => $ticket->comments,
                    // 'srv_time_status_name' => $ticket->srv_time_status_name,
                ];
            }


            // $formatted[$ticket->ticket_number]['Level' . $levelCounter . '_details'] =
            //     'ticket_created_by' . $ticket->created_by .
            //     ', assigned_to' . $ticket->team_name .
            //     ', Department' . $ticket->department_name .
            //     ', ticket_status' . $ticket->status_name .
            //     ', agent' . $ticket->status_update_by .
            //     ', agent_team' . $ticket->agent_team_names .
            //     ', comment' . $ticket->comments .
            //     ', updated_at' . $ticket->updated_at .
            //     ', ticket_age' . $ticket->time_difference .
            //     ', srv_time_min ' . $ticket->srv_time_min.
            //     ', sla_status' . $ticket->srv_time_status_name;  

            // $levelCounter++;

            $formatted['Level_' . $levelCounter] = [
                'ticket_created_by' => $ticket->created_by,
                'assigned_to' => $ticket->team_name,
                'department' => $ticket->department_name,
                'ticket_status' => $ticket->status_name,
                'agent' => $ticket->status_updated_by,
                // 'agent_team' => $ticket->agent_team_names,
                'agent_team' => ' ' . str_replace(',', ', ', $ticket->agent_team_names) . ' ',
                'comment' => $ticket->comments,
                'updated_at' => $ticket->updated_at,
                'ticket_age' => $ticket->time_difference,
                'sla' => $ticket->srv_time_str,
                'sla_status' => $ticket->srv_time_status_name,
            ];

            $levelCounter++;
        }

        return $formatted;
    }



    public function getClientId(Request $request)
    {
        try {
            $clientData = $request->only(['businessEntity', 'vendorId']);

            $clientId = Client::getClientIdGeneratedBySystem($clientData);

            if ($clientId) {
                $results = DB::select('CALL get_recent_open_tickets_for_client(?)', [$clientId]);
                return ApiResponse::success($results, "Successfully retrieved client data", 200);
            }

            return ApiResponse::success([], "No client data found", 200);
        } catch (\Throwable $th) {

            return ApiResponse::error($th->getMessage(), "Error", 500);
        }
    }

    public function getOpenTicketForSID(Request $request)
    {
        try {


            if ($request->sid) {
                $results = DB::select("SELECT t.id, t.ticket_number, t.cat_id, c.category_in_english, t.subcat_id, sc.sub_category_in_english
                ,t.created_at 
                FROM helpdesk.tickets t
                JOIN helpdesk.categories c ON t.cat_id = c.id
                JOIN helpdesk.sub_categories sc ON t.subcat_id = sc.id
                where t.sid = '$request->sid'
                and t.status_id !=6
                ORDER BY t.created_at desc
                LIMIT 5");
                return ApiResponse::success($results, "Successfully retrieved client data", 200);
            }

            return ApiResponse::success([], "No client data found", 200);
        } catch (\Throwable $th) {

            return ApiResponse::error($th->getMessage(), "Error", 500);
        }
    }

    public function mergeTicketShowList(Request $request)
    {
        try {
            $team = $request->team;

            // $resources = DB::select("SELECT distinct t.ticket_number FROM helpdesk.tickets t WHERE t.team_id = '$team' AND i.status_id != 6");

            $resources = Ticket::where('team_id', $team)
                ->where('status_id', '!=', 6)
                ->distinct()
                ->pluck('ticket_number');

            return ApiResponse::success($resources, "success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }



    public function mergrTicketStore(Request $request)
    {
        $ticketNumbers = $request->input('ticket_numbers'); // List of tickets
        $parentTicket = $request->input('parent_ticket'); // Parent ticket

        if (empty($ticketNumbers) || !is_array($ticketNumbers) || empty($parentTicket)) {
            return response()->json(['error' => 'Invalid ticket data'], 400);
        }

        // Store the parent ticket first (if not already in the array)
        if (!in_array($parentTicket, $ticketNumbers)) {
            $ticketNumbers[] = $parentTicket;
        }

        foreach ($ticketNumbers as $ticket) {
            MergeTicket::create([
                'ticket_number' => $ticket,
                'parent_ticket' => $ticket == $parentTicket ? null : $parentTicket, // Assign parent
            ]);
        }

        return response()->json(['message' => 'Tickets stored successfully']);
    }






    public function getRecentlyOpenAndClosedTicketForSID(Request $request)
    {
        try {


            if ($request->sid) {
                $openResults = DB::select("SELECT t.id, t.ticket_number, t.cat_id, c.category_in_english, t.subcat_id, sc.sub_category_in_english
                ,t.created_at 
                FROM helpdesk.open_tickets t
                JOIN helpdesk.categories c ON t.cat_id = c.id
                JOIN helpdesk.sub_categories sc ON t.subcat_id = sc.id
                where t.sid = '$request->sid'
                and t.status_id = 1
                ORDER BY t.created_at desc
                LIMIT 5");
                $closedResults = DB::select("SELECT t.id, t.ticket_number, t.cat_id, c.category_in_english, t.subcat_id, sc.sub_category_in_english
                ,t.created_at 
                FROM helpdesk.close_tickets t
                JOIN helpdesk.categories c ON t.cat_id = c.id
                JOIN helpdesk.sub_categories sc ON t.subcat_id = sc.id
                where t.sid = '$request->sid'
                and t.status_id = 6
                ORDER BY t.created_at desc
                LIMIT 5");
                $results = [
                    'recently_closed_ticket' => $closedResults,
                    'recently_open_ticket' => $openResults
                ];
                return ApiResponse::success($results, "Successfully retrieved client data", 200);
            }

            return ApiResponse::success([], "No client data found", 200);
        } catch (\Throwable $th) {

            return ApiResponse::error($th->getMessage(), "Error", 500);
        }
    }

    public function getBranchDetailsById($id)
    {
        try {
            // $resources = DB::select("SELECT * FROM helpdesk.branches WHERE id = $id");
            $resources = Branch::find($id);
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    // public function getClientList($businessEntityId)
    // {
    //   try{
    //     // return $businessEntityId;
    //     $clients = BusinessEntityWiseClient::where('business_entity_id', $businessEntityId)->get();
    //     return ApiResponse::success($clients, "Successfully Added New Branch", 200);
    //   }catch(Exception $e){
    //     return ApiResponse::error($e->getMessage(), 500);
    //   }
    // }

    public function getClientList(Request $request, $businessEntityId)
{
    try {
        $search = $request->query('search');

        $clients = BusinessEntityWiseClient::where('business_entity_id', $businessEntityId)
    ->when($search, function ($query, $search) {
        $query->whereRaw('LOWER(client_name) LIKE ?', ['%' . strtolower($search) . '%']);
    })
    ->limit(20)
    ->get();

        return ApiResponse::success($clients, "Client list fetched", 200);
    } catch (Exception $e) {
        return ApiResponse::error($e->getMessage(), 500);
    }
}




    // new function for ticket status with default entity start







    public function getTicketByStatusAndDefaultEntity(Request $request)
    {
        try {
            // Extract and validate request parameters
            $params = $this->extractRequestParameters($request);

            // Get user's team IDs if agent
            $teamIdsPermited = $this->getUserTeamIds($params['userID'], $params['userType']);

            // Determine if open or closed tickets
            $ticketTable = $params['statusName'] === 'Closed' ? 'close_tickets' : 'open_tickets';
            $statusIds = $params['statusName'] === 'Closed' ? [6] : [1, 2, 4];

            // Build query based on user type and parameters
            $queryData = $this->buildQuery($params, $teamIdsPermited, $ticketTable, $statusIds);

            if ($queryData['should_query']) {
                $resources = DB::select($queryData['sql'], $queryData['bindings']);
                return ApiResponse::success($resources, "Success", 200);
            }

            return ApiResponse::success([], "No matching criteria found", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    private function extractRequestParameters(Request $request)
    {
        $statusName = ucfirst($request->status);
        $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
        $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

        // ✅ FIX: Handle 'response' and 'resolved' separately
        $response = null;
        $resolved = null;
        
        if ($request->slaMissed === 'response') {
            $response = 0; // First Response SLA failed
        } elseif ($request->slaMissed === 'resolved') {
            $resolved = 0; // Resolution SLA failed
        }

        return [
            'statusName' => $statusName,
            'businessEntity' => $request->businessEntity ?? null,
            'businessEntity1' => $request->businessEntity1 ?? null,
            'team' => $request->team ?? null,
            'team1' => $request->team1 ?? null,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'response' => $response,        // ✅ NOW: 0 for First Response OR null
            'resolved' => $resolved,        // ✅ NOW: 0 for Resolved OR null
            'userType' => $request->userType,
            'userID' => $request->userId,
            'ticketNumber' => $request->ticketNumber ?? null,
            'aggregatorId' => $request->aggregatorId ?? null,
            'agent' => $request->agent ?? null,
            'aggregator' => $request->aggregator ?? null,
            'branch' => $request->branch ?? null,
            'division' => $request->division ?? null,
            'district' => $request->district ?? null,
            'platform' => $request->platform ?? null,
            'orbit' => $request->orbit ?? null,
            'client' => $request->client ?? null,
        ];
    }

    private function getUserTeamIds($userID, $userType)
    {
        if ($userType == 'Agent') {
            $teamIds = DB::select("
                SELECT GROUP_CONCAT(utm.team_id) AS team_ids
                FROM user_team_mappings utm
                INNER JOIN user_profiles up ON utm.user_id = up.user_id
                WHERE utm.user_id = ?
            ", [$userID]);
            return $teamIds[0]->team_ids ?? '';
        }
        return '';
    }

    private function buildQuery($params, $teamIdsPermited, $ticketTable, $statusIds)
    {
        $userType = $params['userType'];

        if ($userType == 'Agent') {
            return $this->buildAgentQuery($params, $teamIdsPermited, $ticketTable, $statusIds);
        } elseif ($userType == 'Client') {
            return $this->buildClientQuery($params, $ticketTable, $statusIds);
        }

        return ['should_query' => false];
    }

    private function buildAgentQuery($params, $teamIdsPermited, $ticketTable, $statusIds)
    {
        $conditions = $this->buildAgentConditions($params, $teamIdsPermited, $ticketTable, $statusIds);

        if (!$conditions['should_query']) {
            return ['should_query' => false];
        }

        $statusIdList = implode(',', $statusIds);
        $sql = $this->getBaseAgentSQL($ticketTable, $statusIdList);
        $sql .= $conditions['where_clause'];
        $sql .= " ORDER BY t.ticket_number DESC";

        // Use parameterized query bindings to prevent SQL injection
        return [
            'should_query' => true,
            'sql' => $sql,
            'bindings' => $conditions['bindings'] ?? []
        ];
    }


    private function buildAgentConditions($params, $teamIdsPermited, $ticketTable, $statusIds)
    {
        $p = $params;
        $bindings = [];

        // ✅ FIX: Handle teamIdsPermited as either string or array
        $teamIdList = '';
        if (!empty($teamIdsPermited)) {
            if (is_string($teamIdsPermited)) {
                $teamIdList = $teamIdsPermited;
            } elseif (is_array($teamIdsPermited)) {
                $teamIdList = implode(',', $teamIdsPermited);
            }
        }

        $conditions = [
            // Pattern 1: Business entity + status only
            [
                'check' => !empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.business_entity_id = ?",
                'bindings' => [$p['businessEntity']]
            ],

            // Pattern 2: Status + team filter
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    !empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.team_id = ?",
                'bindings' => [(int)$p['team']]
            ],

            // Pattern 2B: Status + team1 filter
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && !empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.team_id = ?",
                'bindings' => [(int)$p['team1']]
            ],

            // Pattern 2C: Status + date range (no businessEntity, no team)
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && !empty($p['fromDate']) &&
                    !empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE DATE(t.created_at) BETWEEN ? AND ?",
                'bindings' => [$p['fromDate'], $p['toDate']]
            ],

            // Pattern 2D: Status + team + date range
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    !empty($p['team']) && empty($p['team1']) && !empty($p['fromDate']) &&
                    !empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.team_id = ? AND DATE(t.created_at) BETWEEN ? AND ?",
                'bindings' => [(int)$p['team'], $p['fromDate'], $p['toDate']]
            ],

            // Pattern 2E: Status + team1 + date range
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && !empty($p['team1']) && !empty($p['fromDate']) &&
                    !empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => !empty($teamIdList)
                    ? "WHERE DATE(t.created_at) BETWEEN ? AND ? AND t.team_id IN ({$teamIdList})"
                    : "WHERE t.team_id = ? AND DATE(t.created_at) BETWEEN ? AND ?",
                'bindings' => !empty($teamIdList)
                    ? [$p['fromDate'], $p['toDate']]
                    : [(int)$p['team1'], $p['fromDate'], $p['toDate']]
            ],

            // Pattern 3: Business entity + status + date range
            [
                'check' => !empty($p['businessEntity']) && !empty($p['statusName']) &&
                    !empty($p['fromDate']) && !empty($p['toDate']) && empty($p['team']) &&
                    empty($p['team1']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.business_entity_id = ? AND DATE(t.created_at) BETWEEN ? AND ?",
                'bindings' => [$p['businessEntity'], $p['fromDate'], $p['toDate']]
            ],

            // Pattern 4: Business entity + status + response SLA missed
            [
                'check' => !empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] !== null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.business_entity_id = ? AND frsh.sla_status = ?",
                'bindings' => [$p['businessEntity'], (int)$p['response']]
            ],

            // Pattern 5: Business entity + status + date range + response SLA missed
            [
                'check' => !empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && !empty($p['fromDate']) &&
                    !empty($p['toDate']) && $p['response'] !== null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.business_entity_id = ? AND DATE(t.created_at) BETWEEN ? AND ? AND frsh.sla_status = ?",
                'bindings' => [$p['businessEntity'], $p['fromDate'], $p['toDate'], (int)$p['response']]
            ],

            // Pattern 6: Business entity + status + team1 + date range + response SLA missed
            [
                'check' => !empty($p['businessEntity']) && !empty($p['statusName']) &&
                    !empty($p['team1']) && empty($p['team']) && !empty($p['fromDate']) &&
                    !empty($p['toDate']) && $p['response'] !== null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => !empty($teamIdList) 
                    ? "WHERE t.business_entity_id = ? AND DATE(t.created_at) BETWEEN ? AND ? AND t.team_id IN ({$teamIdList}) AND frsh.sla_status = ?"
                    : "WHERE t.business_entity_id = ? AND DATE(t.created_at) BETWEEN ? AND ? AND t.team_id = ? AND frsh.sla_status = ?",
                'bindings' => !empty($teamIdList)
                    ? [$p['businessEntity'], $p['fromDate'], $p['toDate'], (int)$p['response']]
                    : [$p['businessEntity'], $p['fromDate'], $p['toDate'], (int)$p['team1'], (int)$p['response']]
            ],

            // Pattern 7: Business entity + status + resolved SLA missed
            [
                'check' => !empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] !== null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.business_entity_id = ? AND sth.sla_status = ?",
                'bindings' => [$p['businessEntity'], (int)$p['resolved']]
            ],

            // Pattern 8: Status + ticket number
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team1']) && empty($p['team']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    !empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.ticket_number = ?",
                'bindings' => [$p['ticketNumber']]
            ],

            // Pattern 9: Status + aggregator
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team1']) && empty($p['team']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && !empty($p['aggregatorId']),
                'where' => "WHERE ta.aggregator_id = ?",
                'bindings' => [$p['aggregatorId']]
            ],

            // Pattern 10: Business entity + status + aggregator
            [
                'check' => !empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && !empty($p['aggregatorId']),
                'where' => "WHERE t.business_entity_id = ? AND ta.aggregator_id = ?",
                'bindings' => [$p['businessEntity'], $p['aggregatorId']]
            ],

            // Pattern 12: Status + agent filter
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']) && !empty($p['agent']),
                'where' => "WHERE t.assigned_agent_id = ?",
                'bindings' => [(int)$p['agent']]
            ],

            // Pattern 13: Status + aggregator (new field)
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']) && !empty($p['aggregator']),
                'where' => "WHERE a.id = ?",
                'bindings' => [(int)$p['aggregator']]
            ],

            // Pattern 14: Status + platform filter
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']) && !empty($p['platform']),
                'where' => "WHERE t.platform_id = ?",
                'bindings' => [(int)$p['platform']]
            ],

            // Pattern 15: Status + division filter
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']) && !empty($p['division']),
                'where' => "WHERE tdd.division = ?",
                'bindings' => [$p['division']]
            ],

            // Pattern 16: Status + district filter
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']) && !empty($p['district']),
                'where' => "WHERE tdd.district = ?",
                'bindings' => [$p['district']]
            ],

            // Pattern 17: Status + division + district filter
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']) && !empty($p['division']) && !empty($p['district']),
                'where' => "WHERE tdd.division = ? AND tdd.district = ?",
                'bindings' => [$p['division'], $p['district']]
            ],

            // // ✅ Pattern 18: Status + orbit filter (FIXED)
            // [
            //     'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
            //         empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
            //         empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
            //         empty($p['ticketNumber']) && empty($p['aggregatorId']) && !empty($p['orbit']),
            //     'where' => $p['orbit'] === 'SID' 
            //         ? "WHERE t.business_entity_id = 8 AND t.sid IS NOT NULL"
            //         : ($p['orbit'] === 'ENTITY' ? "WHERE t.business_entity_id = 8 AND t.sid IS NULL" : "WHERE 1=1"),
            //     'bindings' => []
            // ],


            // ✅ Pattern 18: Status + orbit filter
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']) && !empty($p['orbit']),
                'where' => $p['orbit'] === 'SID' 
                    ? "WHERE t.business_entity_id = 8 AND tor.sid_uid IS NOT NULL"
                    : ($p['orbit'] === 'ENTITY' ? "WHERE t.business_entity_id = 8 AND tor.sid_uid IS NULL" : "WHERE 1=1"),
                'bindings' => []
            ],

            // ✅ NEW PATTERNS - SLA FILTERS WITHOUT BUSINESS ENTITY

            // Pattern 18A: Status + response SLA missed (NO business entity required)
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] !== null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE frsh.sla_status = ?",
                'bindings' => [(int)$p['response']]
            ],

            // Pattern 18B: Status + resolved SLA missed (NO business entity required)
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] !== null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE sth.sla_status = ?",
                'bindings' => [(int)$p['resolved']]
            ],

            // Pattern 18C: Status + response SLA + date range (NO business entity)
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && !empty($p['fromDate']) &&
                    !empty($p['toDate']) && $p['response'] !== null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE DATE(t.created_at) BETWEEN ? AND ? AND frsh.sla_status = ?",
                'bindings' => [$p['fromDate'], $p['toDate'], (int)$p['response']]
            ],

            // Pattern 18D: Status + resolved SLA + date range (NO business entity)
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && empty($p['team1']) && !empty($p['fromDate']) &&
                    !empty($p['toDate']) && $p['response'] === null && $p['resolved'] !== null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE DATE(t.created_at) BETWEEN ? AND ? AND sth.sla_status = ?",
                'bindings' => [$p['fromDate'], $p['toDate'], (int)$p['resolved']]
            ],

            // Pattern 18E: Status + team + response SLA (NO business entity)
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    !empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] !== null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.team_id = ? AND frsh.sla_status = ?",
                'bindings' => [(int)$p['team'], (int)$p['response']]
            ],

            // Pattern 18F: Status + team + resolved SLA (NO business entity)
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    !empty($p['team']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] !== null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.team_id = ? AND sth.sla_status = ?",
                'bindings' => [(int)$p['team'], (int)$p['resolved']]
            ],

            // Pattern 18G: Status + team1 + response SLA (NO business entity)
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && !empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] !== null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.team_id = ? AND frsh.sla_status = ?",
                'bindings' => [(int)$p['team1'], (int)$p['response']]
            ],

            // Pattern 18H: Status + team1 + resolved SLA (NO business entity)
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team']) && !empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] !== null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.team_id = ? AND sth.sla_status = ?",
                'bindings' => [(int)$p['team1'], (int)$p['resolved']]
            ],

            // Pattern 19: Status only (no filters)
            [
                'check' => empty($p['businessEntity']) && !empty($p['statusName']) &&
                    empty($p['team1']) && empty($p['team']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']) && empty($p['agent']) &&
                    empty($p['aggregator']) && empty($p['platform']) && empty($p['division']) &&
                    empty($p['district']) && empty($p['orbit']),
                'where' => !empty($teamIdList) ? "WHERE t.team_id IN ({$teamIdList})" : "",
                'bindings' => []
            ],

            // Pattern 20: Status + client filter
            [
                'check' => !empty($p['client']) && !empty($p['statusName']),
                'where' => "WHERE t.client_id_helpdesk = ?",
                'bindings' => [(int)$p['client']]
            ],
            
            // Pattern 20B: Status + businessEntity + client filter
            [
                'check' => !empty($p['businessEntity']) && !empty($p['client']) && !empty($p['statusName']) &&
                    empty($p['fromDate']) && empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']),
                'where' => "WHERE t.business_entity_id = ? AND t.client_id_helpdesk = ?",
                'bindings' => [$p['businessEntity'], (int)$p['client']]
            ],
            
            // Pattern 20C: Status + businessEntity + client + date range
            [
                'check' => !empty($p['businessEntity']) && !empty($p['client']) && !empty($p['statusName']) &&
                    !empty($p['fromDate']) && !empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']),
                'where' => "WHERE t.business_entity_id = ? AND t.client_id_helpdesk = ? AND DATE(t.created_at) BETWEEN ? AND ?",
                'bindings' => [$p['businessEntity'], (int)$p['client'], $p['fromDate'], $p['toDate']]
            ],
        ];

        foreach ($conditions as $index => $condition) {
            if ($condition['check']) {
                $whereClause = $condition['where'];
                $bindings = $condition['bindings'] ?? [];

                return [
                    'should_query' => true,
                    'where_clause' => !empty($whereClause) ? $whereClause : "WHERE 1=1",
                    'bindings' => $bindings,
                    'join_type' => $condition['join_type'] ?? null
                ];
            }
        }

        return ['should_query' => false];
    }


    private function buildClientQuery($params, $ticketTable, $statusIds)
    {
        $p = $params;

        $conditions = [
            // Pattern 1: Business entity + status only
            [
                'check' => !empty($p['businessEntity1']) && empty($p['businessEntity']) &&
                    !empty($p['statusName']) && empty($p['team1']) && empty($p['fromDate']) &&
                    empty($p['toDate']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.business_entity_id = '{$p['businessEntity1']}'
                    AND (t.user_id = {$p['userID']} OR t.client_id_helpdesk = {$p['userID']})"
            ],

            // Pattern 2: Business entity + status + date range
            [
                'check' => !empty($p['businessEntity1']) && empty($p['businessEntity']) &&
                    !empty($p['statusName']) && !empty($p['fromDate']) && !empty($p['toDate']) &&
                    empty($p['team1']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.business_entity_id = '{$p['businessEntity1']}'
                    AND (t.user_id = {$p['userID']} OR t.client_id_helpdesk = {$p['userID']})
                    AND DATE(t.created_at) BETWEEN '{$p['fromDate']}' AND '{$p['toDate']}'"
            ],

            // Pattern 3: Ticket number search
            [
                'check' => empty($p['businessEntity1']) && empty($p['businessEntity']) &&
                    !empty($p['statusName']) && empty($p['fromDate']) && empty($p['toDate']) &&
                    empty($p['team1']) && $p['response'] === null && $p['resolved'] === null &&
                    !empty($p['ticketNumber']) && empty($p['aggregatorId']),
                'where' => "WHERE t.ticket_number = '{$p['ticketNumber']}'
                    AND (t.user_id = {$p['userID']} OR t.client_id_helpdesk = {$p['userID']})"
            ],

            // Pattern 4: Aggregator search
            [
                'check' => empty($p['businessEntity1']) && empty($p['businessEntity']) &&
                    !empty($p['statusName']) && empty($p['fromDate']) && empty($p['toDate']) &&
                    empty($p['team1']) && $p['response'] === null && $p['resolved'] === null &&
                    empty($p['ticketNumber']) && !empty($p['aggregatorId']),
                'where' => "WHERE ta.aggregator_id = '{$p['aggregatorId']}'
                    AND (t.user_id = {$p['userID']} OR t.client_id_helpdesk = {$p['userID']})"
            ]
        ];

        foreach ($conditions as $condition) {
            if ($condition['check']) {
                $statusIdList = implode(',', $statusIds);
                $sql = $this->getBaseClientSQL($ticketTable, $statusIdList);
                $sql .= $condition['where'];
                $sql .= " ORDER BY t.ticket_number DESC";

                return [
                    'should_query' => true,
                    'sql' => $sql,
                    'bindings' => []
                ];
            }
        }

        return ['should_query' => false];
    }



    private function getBaseAgentSQL($ticketTable, $statusIdList)
    {
        return "SELECT 
            t.id, 
            t.ticket_number,
            CASE
                WHEN up.user_type = 'Agent' THEN CONCAT(t.ticket_number, ' - Agent')
                WHEN up.user_type = 'Client' AND t.business_entity_id != 8 THEN CONCAT(t.ticket_number, ' Client')
                WHEN up.user_type = 'Client' AND t.business_entity_id = 8 THEN CONCAT(t.ticket_number, ' Partner')
                ELSE t.ticket_number
            END AS ticket_display,
            CASE
                WHEN mt.child_exists = 1 THEN CONCAT(t.ticket_number, ' (Parent)')
                WHEN mt.parent_ticket_number IS NOT NULL THEN CONCAT(t.ticket_number, ' (Child of ', mt.parent_ticket_number, ')')
                ELSE t.ticket_number
            END AS ticket_number_with_relationship,
            CASE
                WHEN up.user_type = 'Agent' THEN t.assigned_agent_id
                WHEN up.user_type = 'Client' THEN t.client_id_helpdesk
                ELSE NULL
            END AS user_identifier,
            up.user_type,
            t.user_id, 
            up.fullname,
            up.email_primary,
            CASE 
                WHEN frsh.sla_status = 2 THEN 'Started'
                WHEN frsh.sla_status = 1 THEN 'Success'
                WHEN frsh.sla_status = 0 THEN 'Failed'
                ELSE NULL
            END AS fr_response_status,
            CASE 
                WHEN sth.sla_status = 2 THEN 'Started'
                WHEN sth.sla_status = 1 THEN 'Success'
                WHEN sth.sla_status = 0 THEN 'Failed'
                ELSE NULL
            END AS srv_response_status,
            CASE 
                WHEN stsh.sla_status = 2 THEN 'Started'
                WHEN stsh.sla_status = 1 THEN 'Success'
                WHEN stsh.sla_status = 0 THEN 'Failed'
                ELSE NULL
            END AS srv_client_response_status,
            t.business_entity_id, 
            c.company_name, 
            t.team_id, 
            teams.team_name, 
            t.cat_id, 
            categories.category_in_english, 
            t.subcat_id, 
            sub_categories.sub_category_in_english,
            t.status_id, 
            statuses.status_name, 
            t.created_at, 
            t.updated_at,
            CONCAT(
                TIMESTAMPDIFF(DAY, t.created_at, IF('{$ticketTable}' = 'close_tickets', t.updated_at, NOW())), 'd ', 
                TIMESTAMPDIFF(HOUR, t.created_at, IF('{$ticketTable}' = 'close_tickets', t.updated_at, NOW())) % 24, 'h ', 
                TIMESTAMPDIFF(MINUTE, t.created_at, IF('{$ticketTable}' = 'close_tickets', t.updated_at, NOW())) % 60, 'm '
            ) AS ticket_age,
            u.username AS created_by,
            u2.username AS status_updated_by,
            a.name AS aggregator_name, 
            p.platform_name,
            t.client_id_helpdesk,
            raised_for_profile.fullname AS raised_for,
            lc.comments AS last_comment,
            tor.sid_uid,
            tdd.division,
            tdd.district,
            tdd.thana,
            u3.username AS assigned_agent,
            COALESCE(fr_success.success_count, 0) AS fr_sla_success_count,
            COALESCE(fr_failed.failed_count, 0) AS fr_sla_failed_count,
            COALESCE(srv_success.success_count, 0) AS srv_sla_success_count,
            COALESCE(srv_failed.failed_count, 0) AS srv_sla_failed_count,
            COALESCE(stsh_success.success_count, 0) AS srv_client_sla_success_count,
            COALESCE(stsh_failed.failed_count, 0) AS srv_client_sla_failed_count,
            CASE 
                WHEN mt.child_exists = 1 THEN 'Parent'
                WHEN mt.parent_ticket_number IS NOT NULL THEN 'Child'
                ELSE NULL
            END AS ticket_relationship,
            mt.parent_ticket_number,
            mt.child_exists

        FROM {$ticketTable} t
        LEFT JOIN companies c ON t.business_entity_id = c.id
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN user_profiles up ON u.id = up.user_id
        LEFT JOIN users u2 ON t.status_updated_by = u2.id
        LEFT JOIN users u3 ON t.assigned_agent_id = u3.id
        LEFT JOIN user_profiles raised_for_profile ON t.client_id_helpdesk = raised_for_profile.user_id
        LEFT JOIN teams teams ON t.team_id = teams.id
        LEFT JOIN statuses statuses ON t.status_id = statuses.id AND statuses.id IN ({$statusIdList})
        LEFT JOIN categories ON t.cat_id = categories.id
        LEFT JOIN sub_categories sub_categories ON t.subcat_id = sub_categories.id
        LEFT JOIN (
            SELECT 
                ticket_number,
                sla_status
            FROM first_res_sla_histories
            WHERE (ticket_number, id) IN (
                SELECT 
                    ticket_number,
                    MAX(id)
                FROM first_res_sla_histories
                GROUP BY ticket_number
            )
        ) frsh ON t.ticket_number = frsh.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                sla_status
            FROM srv_time_subcat_sla_histories
            WHERE (ticket_number, id) IN (
                SELECT 
                    ticket_number,
                    MAX(id)
                FROM srv_time_subcat_sla_histories
                GROUP BY ticket_number
            )
        ) sth ON t.ticket_number = sth.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                sla_status
            FROM srv_time_client_sla_histories
            WHERE (ticket_number, id) IN (
                SELECT 
                    ticket_number,
                    MAX(id)
                FROM srv_time_client_sla_histories
                GROUP BY ticket_number
            )
        ) stsh ON t.ticket_number = stsh.ticket_number
        LEFT JOIN ticket_aggregators ta ON t.ticket_number = ta.ticket_number
        LEFT JOIN aggregators a ON ta.aggregator_id = a.id
        LEFT JOIN platforms p ON t.platform_id = p.id
        LEFT JOIN ticket_orbits tor ON t.ticket_number = tor.ticket_number
        LEFT JOIN ticket_division_districts tdd ON t.ticket_number = tdd.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                comments
            FROM ticket_comments
            WHERE (ticket_number, created_at) IN (
                SELECT 
                    ticket_number,
                    MAX(created_at)
                FROM ticket_comments
                GROUP BY ticket_number
            )
        ) lc ON t.ticket_number = lc.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS success_count
            FROM first_res_sla_histories
            WHERE sla_status = 1
            GROUP BY ticket_number
        ) fr_success ON t.ticket_number = fr_success.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS failed_count
            FROM first_res_sla_histories
            WHERE sla_status = 0
            GROUP BY ticket_number
        ) fr_failed ON t.ticket_number = fr_failed.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS success_count
            FROM srv_time_subcat_sla_histories
            WHERE sla_status = 1
            GROUP BY ticket_number
        ) srv_success ON t.ticket_number = srv_success.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS failed_count
            FROM srv_time_subcat_sla_histories
            WHERE sla_status = 0
            GROUP BY ticket_number
        ) srv_failed ON t.ticket_number = srv_failed.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS success_count
            FROM srv_time_client_sla_histories
            WHERE sla_status = 1
            GROUP BY ticket_number
        ) stsh_success ON t.ticket_number = stsh_success.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS failed_count
            FROM srv_time_client_sla_histories
            WHERE sla_status = 0
            GROUP BY ticket_number
        ) stsh_failed ON t.ticket_number = stsh_failed.ticket_number
        LEFT JOIN merge_tickets mt ON t.ticket_number = mt.ticket_number
        ";
    }

    private function getBaseClientSQL($ticketTable, $statusIdList)
    {
        return "SELECT DISTINCT
            t.id, 
            t.ticket_number,
            CASE
                WHEN up.user_type = 'Agent' THEN CONCAT(t.ticket_number, ' - Agent')
                WHEN up.user_type = 'Client' AND t.business_entity_id != 8 THEN CONCAT(t.ticket_number, ' Client')
                WHEN up.user_type = 'Client' AND t.business_entity_id = 8 THEN CONCAT(t.ticket_number, ' Partner')
                ELSE t.ticket_number
            END AS ticket_display,
            CASE
                WHEN mt.child_exists = 1 THEN CONCAT(t.ticket_number, ' (Parent)')
                WHEN mt.parent_ticket_number IS NOT NULL THEN CONCAT(t.ticket_number, ' (Child of ', mt.parent_ticket_number, ')')
                ELSE t.ticket_number
            END AS ticket_number_with_relationship,
            CASE
                WHEN up.user_type = 'Agent' THEN t.assigned_agent_id
                WHEN up.user_type = 'Client' THEN t.client_id_helpdesk
                ELSE NULL
            END AS user_identifier,
            up.user_type,
            t.user_id, 
            up.fullname,
            up.email_primary,
            CASE 
                WHEN frsh.sla_status = 2 THEN 'Started'
                WHEN frsh.sla_status = 1 THEN 'Success'
                WHEN frsh.sla_status = 0 THEN 'Failed'
                ELSE NULL
            END AS fr_response_status,
            CASE 
                WHEN sth.sla_status = 2 THEN 'Started'
                WHEN sth.sla_status = 1 THEN 'Success'
                WHEN sth.sla_status = 0 THEN 'Failed'
                ELSE NULL
            END AS srv_response_status,
            CASE 
                WHEN stsh.sla_status = 2 THEN 'Started'
                WHEN stsh.sla_status = 1 THEN 'Success'
                WHEN stsh.sla_status = 0 THEN 'Failed'
                ELSE NULL
            END AS srv_client_response_status,
            t.business_entity_id, 
            c.company_name, 
            t.team_id, 
            teams.team_name, 
            t.cat_id, 
            categories.category_in_english, 
            t.subcat_id, 
            sub_categories.sub_category_in_english,
            t.status_id, 
            statuses.status_name, 
            t.created_at, 
            t.updated_at,
            CONCAT(
                TIMESTAMPDIFF(DAY, t.created_at, IF('{$ticketTable}' = 'close_tickets', t.updated_at, NOW())), 'd ', 
                TIMESTAMPDIFF(HOUR, t.created_at, IF('{$ticketTable}' = 'close_tickets', t.updated_at, NOW())) % 24, 'h ', 
                TIMESTAMPDIFF(MINUTE, t.created_at, IF('{$ticketTable}' = 'close_tickets', t.updated_at, NOW())) % 60, 'm '
            ) AS ticket_age,
            u.username AS created_by,
            u2.username AS status_updated_by,
            a.name AS aggregator_name, 
            p.platform_name,
            t.client_id_helpdesk,
            raised_for_profile.fullname AS raised_for,
            lc.comments AS last_comment,
            tor.sid_uid,
            tdd.division,
            tdd.district,
            tdd.thana,
            COALESCE(fr_success.success_count, 0) AS fr_sla_success_count,
            COALESCE(fr_failed.failed_count, 0) AS fr_sla_failed_count,
            COALESCE(srv_success.success_count, 0) AS srv_sla_success_count,
            COALESCE(srv_failed.failed_count, 0) AS srv_sla_failed_count,
            COALESCE(stsh_success.success_count, 0) AS srv_client_sla_success_count,
            COALESCE(stsh_failed.failed_count, 0) AS srv_client_sla_failed_count,
            CASE 
                WHEN mt.child_exists = 1 THEN 'Parent'
                WHEN mt.parent_ticket_number IS NOT NULL THEN 'Child'
                ELSE NULL
            END AS ticket_relationship,
            mt.parent_ticket_number,
            mt.child_exists

        FROM {$ticketTable} t
        LEFT JOIN companies c ON t.business_entity_id = c.id
        LEFT JOIN users u ON t.user_id = u.id
        LEFT JOIN user_profiles up ON u.id = up.user_id
        LEFT JOIN users u2 ON t.status_updated_by = u2.id
        LEFT JOIN user_profiles raised_for_profile ON t.client_id_helpdesk = raised_for_profile.user_id
        LEFT JOIN teams teams ON t.team_id = teams.id
        LEFT JOIN statuses statuses ON t.status_id = statuses.id AND statuses.id IN ({$statusIdList})
        LEFT JOIN categories ON t.cat_id = categories.id
        LEFT JOIN sub_categories sub_categories ON t.subcat_id = sub_categories.id
        LEFT JOIN (
            SELECT 
                ticket_number,
                sla_status
            FROM first_res_sla_histories
            WHERE (ticket_number, id) IN (
                SELECT 
                    ticket_number,
                    MAX(id)
                FROM first_res_sla_histories
                GROUP BY ticket_number
            )
        ) frsh ON t.ticket_number = frsh.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                sla_status
            FROM srv_time_subcat_sla_histories
            WHERE (ticket_number, id) IN (
                SELECT 
                    ticket_number,
                    MAX(id)
                FROM srv_time_subcat_sla_histories
                GROUP BY ticket_number
            )
        ) sth ON t.ticket_number = sth.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                sla_status
            FROM srv_time_client_sla_histories
            WHERE (ticket_number, id) IN (
                SELECT 
                    ticket_number,
                    MAX(id)
                FROM srv_time_client_sla_histories
                GROUP BY ticket_number
            )
        ) stsh ON t.ticket_number = stsh.ticket_number
        LEFT JOIN ticket_aggregators ta ON t.ticket_number = ta.ticket_number
        LEFT JOIN aggregators a ON ta.aggregator_id = a.id
        LEFT JOIN platforms p ON t.platform_id = p.id
        LEFT JOIN ticket_orbits tor ON t.ticket_number = tor.ticket_number
        LEFT JOIN ticket_division_districts tdd ON t.ticket_number = tdd.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                comments
            FROM ticket_comments
            WHERE (ticket_number, created_at) IN (
                SELECT 
                    ticket_number,
                    MAX(created_at)
                FROM ticket_comments
                GROUP BY ticket_number
            )
        ) lc ON t.ticket_number = lc.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS success_count
            FROM first_res_sla_histories
            WHERE sla_status = 1
            GROUP BY ticket_number
        ) fr_success ON t.ticket_number = fr_success.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS failed_count
            FROM first_res_sla_histories
            WHERE sla_status = 0
            GROUP BY ticket_number
        ) fr_failed ON t.ticket_number = fr_failed.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS success_count
            FROM srv_time_subcat_sla_histories
            WHERE sla_status = 1
            GROUP BY ticket_number
        ) srv_success ON t.ticket_number = srv_success.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS failed_count
            FROM srv_time_subcat_sla_histories
            WHERE sla_status = 0
            GROUP BY ticket_number
        ) srv_failed ON t.ticket_number = srv_failed.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS success_count
            FROM srv_time_client_sla_histories
            WHERE sla_status = 1
            GROUP BY ticket_number
        ) stsh_success ON t.ticket_number = stsh_success.ticket_number
        LEFT JOIN (
            SELECT 
                ticket_number,
                COUNT(*) AS failed_count
            FROM srv_time_client_sla_histories
            WHERE sla_status = 0
            GROUP BY ticket_number
        ) stsh_failed ON t.ticket_number = stsh_failed.ticket_number
        LEFT JOIN merge_tickets mt ON t.ticket_number = mt.ticket_number
        ";
    }




    public function getAggregators()
    {
        $aggregators = DB::select("
            SELECT a.id, a.name AS aggregator_name 
            FROM aggregators a
        ");

        return response()->json([
            'status' => true,
            'message' => 'Aggregator list fetched successfully',
            'data' => $aggregators
        ]);
    }


    public function getBranches()
    {
        $branches = DB::select("
            SELECT b.id, b.branch_name 
            FROM branches b
        ");

        return response()->json([
            'status' => true,
            'message' => 'Branch list fetched successfully',
            'data' => $branches
        ]);
    }


    public function getDivisions()
    {
        $divisions = DB::select("
            SELECT DISTINCT tdd.division 
            FROM ticket_division_districts tdd
        ");

        return response()->json([
            'status' => true,
            'message' => 'Division list fetched successfully',
            'data' => $divisions
        ]);
    }


    public function getDistricts()
    {
        $districts = DB::select("
            SELECT DISTINCT tdd.district 
            FROM ticket_division_districts tdd
        ");

        return response()->json([
            'status' => true,
            'message' => 'District list fetched successfully',
            'data' => $districts
        ]);
    }



    // public function getAgents()
    // {
    //     $agents = DB::select("
    //         SELECT up.user_id, up.fullname FROM user_profiles up
    //         WHERE up.user_type = 'agent'
    //     ");

    //     return response()->json([
    //         'status' => true,
    //         'message' => 'Agents list fetched successfully',
    //         'data' => $agents
    //     ]);
    // }


    public function getAgents(Request $request)
    {
        $teamId = $request->query('team_id');

        if (!$teamId) {
            return response()->json([
                'status' => false,
                'message' => 'Team ID is required',
                'data' => []
            ], 400);
        }

        $agents = DB::select("
            SELECT up.user_id, up.fullname 
            FROM user_profiles up
            JOIN user_team_mappings utm 
                ON up.user_id = utm.user_id
            WHERE up.user_type = 'agent'
            AND utm.team_id = ?
        ", [$teamId]);

        return response()->json([
            'status' => true,
            'message' => 'Agents list fetched successfully',
            'data' => $agents
        ]);
    }


    public function fetchEvents()
    {
        $events = DB::select("
            SELECT et.id, et.event_name from event_tags et
        ");

        return response()->json([
            'status' => true,
            'message' => 'Events list fetched successfully',
            'data' => $events
        ]);
    }
    
    
//     public function getPublicTicket($token)
// {
//     try {
//         $secret = env('TRACK_SECRET');

//         $decoded = JWT::decode($token, new Key($secret, 'HS256'));

//         $ticket = OpenTicket::where('ticket_number', $decoded->ticketNumber)
//     ->first();

//         if (!$ticket) {
//             return response()->json(['message' => 'Ticket not found'], 404);
//         }

//         return response()->json($ticket);

//     } catch (\Exception $e) {
//         return response()->json(['message' => 'Invalid or expired link'], 403);
//     }
// }

    // public function getPublicTicket($token)
    // {
    //     try {

    //         // 1️⃣ Token দিয়ে ticket number বের করা
    //         $tracking = TicketTrackingToken::where('token', $token)->first();

    //         if (!$tracking) {
    //             return response()->json([
    //                 'message' => 'Invalid or expired link'
    //             ], 403);
    //         }

    //         $ticketNumber = $tracking->ticket_number;

    //         // 2️⃣ Basic Info (First history row)
    //         $basicInfo = DB::selectOne("
    //             SELECT 
    //                 ot.ticket_number,
    //                 ot.created_at,
    //                 ot.note,
    //                 co.company_name,
    //                 cat.category_in_english,
    //                 cat.category_in_bangla,
    //                 sub.sub_category_in_english,
    //                 sub.sub_category_in_bangla,
    //                 teams.team_name,
    //                 creator.username AS created_by,
    //                 updater.username AS updated_by,
    //                 agent_profile.fullname AS agent_name,
    //                 ucm.client_name,
    //                 plt.platform_name,
    //                 so.source_name,
    //                 st.status_name
    //             FROM ticket_histories ot
    //             INNER JOIN companies co ON co.id = ot.business_entity_id
    //             INNER JOIN users AS creator ON creator.id = ot.user_id
    //             LEFT JOIN users AS updater ON updater.id = ot.status_updated_by
    //             LEFT JOIN user_profiles AS agent_profile ON agent_profile.user_id = ot.assigned_agent_id
    //             INNER JOIN user_client_mappings ucm ON ucm.user_id = ot.client_id_helpdesk
    //             INNER JOIN platforms AS plt ON plt.id = ot.platform_id
    //             INNER JOIN categories AS cat ON cat.id = ot.cat_id
    //             INNER JOIN sub_categories AS sub ON sub.id = ot.subcat_id
    //             INNER JOIN teams AS teams ON teams.id = ot.team_id
    //             INNER JOIN statuses AS st ON st.id = ot.status_id
    //             LEFT JOIN sources AS so ON so.id = ot.source_id
    //             WHERE ot.ticket_number = ?
    //             ORDER BY ot.created_at ASC
    //             LIMIT 1
    //         ", [$ticketNumber]);

    //         if (!$basicInfo) {
    //             return response()->json([
    //                 'message' => 'Ticket not found'
    //             ], 404);
    //         }

    //         // 3️⃣ Current status (Last history row)
    //         $currentInfo = DB::selectOne("
    //             SELECT 
    //                 u.username AS updated_by,
    //                 st.status_name,
    //                 ot.updated_at
    //             FROM ticket_histories ot
    //             INNER JOIN statuses st ON st.id = ot.status_id
    //                         INNER JOIN users u ON u.id = ot.status_updated_by
    //             WHERE ot.ticket_number = ?
    //             ORDER BY ot.created_at DESC
    //             LIMIT 1
    //         ", [$ticketNumber]);

    //         // 4️⃣ Team Assignment Logs
    //         $teamLogs = TicketAssignTeamLog::where('ticket_number', $ticketNumber)
    //             ->orderBy('created_at')
    //             ->get();

    //         $teamIds = $teamLogs->pluck('assigned_in')->unique();

    //         $teams = Team::whereIn('id', $teamIds)
    //             ->pluck('team_name', 'id');

    //         // 5️⃣ Build Timeline (Period Based)
    //         $timeline = [];

    //         foreach ($teamLogs as $index => $teamLog) {

    //             $startTime = $teamLog->created_at;

    //             $endTime = isset($teamLogs[$index + 1])
    //                 ? $teamLogs[$index + 1]->created_at
    //                 : null;

    //             $query = DB::table('ticket_comments as tc')
    //                 ->join('user_profiles as up', 'up.user_id', '=', 'tc.user_id')
    //                 ->where('tc.ticket_number', $ticketNumber)
    //                 ->where('tc.is_internal', 0)
    //                 ->where('tc.team_id', $teamLog->assigned_in)
    //                 ->where('tc.created_at', '>=', $startTime);

    //             if ($endTime) {
    //                 $query->where('tc.created_at', '<', $endTime);
    //             }

    //             $teamComments = $query
    //                 ->select(
    //                     'tc.id',
    //                     'tc.team_id',
    //                     'tc.comments',
    //                     'tc.created_at',
    //                     'up.fullname as comment_by'
    //                 )
    //                 ->orderBy('tc.created_at')
    //                 ->get();

    //             $timeline[] = [
    //                 'team_name'     => $teams[$teamLog->assigned_in] ?? null,
    //                 'assigned_time' => $startTime,
    //                 'comments'      => $teamComments
    //             ];
    //         }

    //         // 6️⃣ Final Response
    //         return response()->json([
    //             'basic_info' => $basicInfo,
    //             'current_info'  => $currentInfo,
    //             'timeline'   => $timeline,
    //         ]);

    //     } catch (\Throwable $e) {

    //         return response()->json([
    //             'message' => 'Something went wrong'
    //         ], 500);
    //     }
    // }

    public function getPublicTicket($token)
{
    try {

        // ===============================
        // 1️⃣ TOKEN VALIDATION
        // ===============================
        $tracking = TicketTrackingToken::where('token', $token)->first();

        if (!$tracking) {
            return response()->json([
                'message' => 'Invalid or expired link'
            ], 403);
        }

        $ticketNumber = $tracking->ticket_number;

        // ===============================
        // 2️⃣ BASIC INFO
        // ===============================
        $basicInfo = DB::selectOne("
            SELECT 
                ot.ticket_number,
                ot.created_at,
                ot.note,
                co.company_name,
                cat.category_in_english,
                sub.sub_category_in_english,
                teams.team_name,
                creator.username AS created_by,
                agent_profile.fullname AS agent_name,
                ucm.client_name,
                st.status_name
            FROM ticket_histories ot
            INNER JOIN companies co ON co.id = ot.business_entity_id
            INNER JOIN users AS creator ON creator.id = ot.user_id
            LEFT JOIN user_profiles AS agent_profile ON agent_profile.user_id = ot.assigned_agent_id
            INNER JOIN user_client_mappings ucm ON ucm.user_id = ot.client_id_helpdesk
            INNER JOIN categories AS cat ON cat.id = ot.cat_id
            INNER JOIN sub_categories AS sub ON sub.id = ot.subcat_id
            INNER JOIN teams AS teams ON teams.id = ot.team_id
            INNER JOIN statuses AS st ON st.id = ot.status_id
            WHERE ot.ticket_number = ?
            ORDER BY ot.created_at ASC
            LIMIT 1
        ", [$ticketNumber]);

        // ===============================
        // 3️⃣ CURRENT STATUS
        // ===============================
        $currentInfo = DB::selectOne("
            SELECT 
                u.username AS updated_by,
                st.status_name,
                ot.updated_at
            FROM ticket_histories ot
            INNER JOIN statuses st ON st.id = ot.status_id
            INNER JOIN users u ON u.id = ot.status_updated_by
            WHERE ot.ticket_number = ?
            ORDER BY ot.created_at DESC
            LIMIT 1
        ", [$ticketNumber]);

        // ===============================
        // 4️⃣ TEAM LOGS
        // ===============================
        $teamLogs = TicketAssignTeamLog::where('ticket_number', $ticketNumber)
            ->orderBy('created_at')
            ->get();

        $teamIds = $teamLogs->pluck('assigned_in')->unique();
        $teams = Team::whereIn('id', $teamIds)->pluck('team_name', 'id');

        // ===============================
        // 5️⃣ AGENT LOGS (fallback use)
        // ===============================
        $agentLogs = TicketAssignAgentLog::where('ticket_number', $ticketNumber)
            ->orderBy('created_at')
            ->get();

        $agentIds = $agentLogs->pluck('assigned_in')->unique();
        $agents = DB::table('user_profiles')
            ->whereIn('user_id', $agentIds)
            ->pluck('fullname', 'user_id');

        // ===============================
        // 6️⃣ BUILD TIMELINE
        // ===============================
        $timeline = [];

        foreach ($teamLogs as $index => $teamLog) {

            $startTime = $teamLog->created_at;

            $endTime = isset($teamLogs[$index + 1])
                ? $teamLogs[$index + 1]->created_at
                : null;

            // ===============================
            // COMMENTS (PER TEAM PERIOD)
            // ===============================
            $query = DB::table('ticket_comments as tc')
                ->join('user_profiles as up', 'up.user_id', '=', 'tc.user_id')
                ->where('tc.ticket_number', $ticketNumber)
                ->where('tc.is_internal', 0)
                ->where('tc.team_id', $teamLog->assigned_in)
                ->where('tc.created_at', '>=', $startTime);

            if ($endTime) {
                $query->where('tc.created_at', '<', $endTime);
            }

            $comments = $query->select(
                    'tc.id',
                    'tc.comments',
                    'tc.created_at',
                    'up.fullname as comment_by'
                )
                ->orderBy('tc.created_at')
                ->get();

            // ===============================
            // 🔥 AGENT DETECTION (FINAL LOGIC)
            // ===============================
            $agentName = null;

            // 1️⃣ comment থেকে agent ধরো (BEST)
            if ($comments->count() > 0) {
                $agentName = $comments->last()->comment_by;
            }

            // 2️⃣ fallback → agent log
            if (!$agentName) {
                $agentLog = $agentLogs
                    ->where('created_at', '<=', $startTime)
                    ->sortByDesc('created_at')
                    ->first();

                if ($agentLog) {
                    $agentName = $agents[$agentLog->assigned_in] ?? null;
                }
            }

            // ===============================
            // PUSH TIMELINE
            // ===============================
            $timeline[] = [
                'team_id'       => $teamLog->assigned_in,
                'team_name'     => $teams[$teamLog->assigned_in] ?? null,
                'agent_name'    => $agentName,
                'assigned_time' => $startTime,
                'comments'      => $comments
            ];
        }

        // ===============================
        // FINAL RESPONSE
        // ===============================
        return response()->json([
            'basic_info'   => $basicInfo,
            'current_info' => $currentInfo,
            'timeline'     => $timeline
        ]);

    } catch (\Throwable $e) {

        return response()->json([
            'message' => 'Something went wrong',
            'error'   => $e->getMessage()
        ], 500);
    }
}
}
