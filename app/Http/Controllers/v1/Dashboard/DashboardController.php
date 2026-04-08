<?php

namespace App\Http\Controllers\v1\Dashboard;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{

    // public function summary(Request $request)
    // {
    //     try {

    //         // return $request->all();
    //         $companyId = $request->businessEntity;
    //         $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //         $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

    //         $userType = $request->userType;

    //         $userID = $request->userId;

    //         if ($userType == 'Agent') {
    //             if (!empty($companyId) && empty($fromDate) && empty($toDate)) {

    //                 // return $companyId;
    //                 // return 'i am here';

    //                 $results = DB::select("SELECT 
    //                     COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS closed_tickets,
    //                     COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets,
    //                     COALESCE(srv_ticket_counts.over_due, 0) AS over_due,
    //                     COALESCE(fr_ticket_counts.over_due_fr, 0) AS over_due_fr
    //                     FROM helpdesk.companies c
    //                     LEFT JOIN helpdesk.tickets th2 ON c.id = th2.business_entity_id
    //                     LEFT JOIN (SELECT th.business_entity_id, COUNT(DISTINCT tsh.ticket_number) AS over_due
    //                         FROM helpdesk.ticket_srv_time_team_histories tsh
    //                         JOIN helpdesk.ticket_histories th ON th.ticket_number = tsh.ticket_number
    //                         WHERE tsh.srv_time_status = 2 
    //                         GROUP BY th.business_entity_id
    //                     ) AS srv_ticket_counts 
    //                         ON c.id = srv_ticket_counts.business_entity_id
    //                         LEFT JOIN (SELECT th.business_entity_id, 
    //                                 COUNT(DISTINCT fth.ticket_number) AS over_due_fr
    //                                 FROM  helpdesk.ticket_fr_time_team_histories fth
    //                                 JOIN helpdesk.ticket_histories th ON th.ticket_number = fth.ticket_number
    //                                 WHERE fth.fr_response_status = 2 
    //                                 GROUP BY th.business_entity_id) AS fr_ticket_counts 
    //                                 ON c.id = fr_ticket_counts.business_entity_id
    //                     WHERE c.id = $companyId
    //                     GROUP BY c.company_name");

    //                 // return $results;

    //                 return ApiResponse::success($results, "Success", 200);
    //             } elseif (!empty($companyId) && !empty($fromDate) && !empty($toDate)) {
    //                 $results = DB::select("SELECT 
    //                     COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS closed_tickets,
    //                     COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets,
    //                     COALESCE(srv_ticket_counts.over_due, 0) AS over_due,
    //                     COALESCE(fr_ticket_counts.over_due_fr, 0) AS over_due_fr
    //                     FROM helpdesk.companies c
    //                     LEFT JOIN helpdesk.tickets th2 ON c.id = th2.business_entity_id
    //                     AND DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                     LEFT JOIN (SELECT th.business_entity_id, 
    //                         COUNT(DISTINCT tsh.ticket_number) AS over_due
    //                     FROM helpdesk.ticket_srv_time_team_histories tsh
    //                     JOIN helpdesk.ticket_histories th ON th.ticket_number = tsh.ticket_number
    //                     WHERE tsh.srv_time_status = 2 
    //                     AND DATE(tsh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                     GROUP BY th.business_entity_id ) AS srv_ticket_counts 
    //                     ON c.id = srv_ticket_counts.business_entity_id
    //                     LEFT JOIN (SELECT th.business_entity_id, 
    //                             COUNT(DISTINCT fth.ticket_number) AS over_due_fr
    //                         FROM helpdesk.ticket_fr_time_team_histories fth
    //                         JOIN helpdesk.ticket_histories th ON th.ticket_number = fth.ticket_number
    //                         WHERE fth.fr_response_id = 2 
    //                         AND DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                         GROUP BY th.business_entity_id) AS fr_ticket_counts 
    //                         ON c.id = fr_ticket_counts.business_entity_id
    //                     WHERE c.id = $companyId
    //                         AND DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                     GROUP BY c.company_name");

    //                 return ApiResponse::success($results, "Success", 200);
    //             }
    //         } elseif ($userType == 'Client') {
    //             if (!empty($companyId) && empty($fromDate) && empty($toDate)) {

    //                 // return 'hi here';
    //                 $results = DB::select("SELECT 
    //                     COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS closed_tickets,
    //                     COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets
                        
    //                     FROM helpdesk.companies c
    //                     LEFT JOIN (
    //                             SELECT 
    //                                 id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
    //                                 'Ticket' AS source_type
    //                             FROM helpdesk.tickets

    //                             UNION ALL

    //                             SELECT 
    //                                 id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
    //                                 'Self-Ticket' AS source_type
    //                             FROM helpdesk.self_tickets
    //                         ) th2 ON c.id = th2.business_entity_id
    //                     WHERE c.id = $companyId AND th2.client_id_helpdesk = '$userID'
    //                     GROUP BY c.company_name");

    //                 return ApiResponse::success($results, "Success", 200);
    //             } elseif (!empty($companyId) && !empty($fromDate) && !empty($toDate)) {
    //                 $results = DB::select("SELECT 
    //                     COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS closed_tickets,
    //                     COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets
    //                     FROM helpdesk.companies c
    //                     LEFT JOIN (
    //                             SELECT 
    //                                 id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
    //                                 'Ticket' AS source_type
    //                             FROM helpdesk.tickets

    //                             UNION ALL

    //                             SELECT 
    //                                 id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
    //                                 'Self-Ticket' AS source_type
    //                             FROM helpdesk.self_tickets
    //                         ) th2 ON c.id = th2.business_entity_id
    //                     WHERE c.id = $companyId AND th2.client_id_helpdesk = '$userID'
    //                     AND DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                     GROUP BY c.company_name");

    //                 return ApiResponse::success($results, "Success", 200);
    //             }
    //         } elseif ($userType == 'Customer') {
    //             if (!empty($companyId) && empty($fromDate) && empty($toDate)) {

    //                 $results = DB::select("SELECT 
    //                     COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS closed_tickets,
    //                     COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets
    //                     FROM helpdesk.companies c
    //                     -- LEFT JOIN helpdesk.tickets th2 ON c.id = th2.business_entity_id
    //                     LEFT JOIN 
    //                     (SELECT * FROM helpdesk.tickets
    //                         UNION ALL
    //                         SELECT * FROM helpdesk.self_tickets) th2 ON c.id = th2.business_entity_id
    //                     WHERE  th2.user_id = '$userID'
    //                     GROUP BY c.company_name");

    //                 return ApiResponse::success($results, "Success", 200);
    //             } elseif (!empty($companyId) && !empty($fromDate) && !empty($toDate)) {
    //                 $results = DB::select("SELECT 
    //                     COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS closed_tickets,
    //                     COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets,
    //                     COALESCE(srv_ticket_counts.over_due, 0) AS over_due,
    //                     COALESCE(fr_ticket_counts.over_due_fr, 0) AS over_due_fr
    //                     FROM helpdesk.companies c
    //                     -- JOIN helpdesk.tickets th2 ON c.id = th2.business_entity_id
    //                     LEFT JOIN 
    //                     (SELECT * FROM helpdesk.tickets
    //                         UNION ALL
    //                         SELECT * FROM helpdesk.self_tickets) th2 ON c.id = th2.business_entity_id
    //                     WHERE th2.user_id = '$userID'
    //                         AND DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                     GROUP BY c.company_name");

    //                 return ApiResponse::success($results, "Success", 200);
    //             }
    //         }
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Failed", 500);
    //     }
    // }


  // c1

    public function summary(Request $request)
    {
        try {
            $companyId = $request->businessEntity;
            $userType  = $request->userType;
            $userID    = $request->userId;

            $fromDate = !empty($request->fromDate)
                ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
            $toDate   = !empty($request->toDate)
                ? (new DateTime($request->toDate))->format('Y-m-d')   : "";

            $isDateRange = !empty($fromDate) && !empty($toDate);
            $dateFilter  = $isDateRange
                ? "DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'"
                : null;

            // ── open + close helpdesk tickets (status_updated_by) ────────────────
            $allTickets = "(
                SELECT ticket_number, user_id, client_id_helpdesk, business_entity_id,
                    team_id, cat_id, subcat_id, status_id, created_at, updated_at,
                    status_updated_by
                FROM helpdesk.open_tickets
                UNION ALL
                SELECT ticket_number, user_id, client_id_helpdesk, business_entity_id,
                    team_id, cat_id, subcat_id, status_id, created_at, updated_at,
                    status_updated_by
                FROM helpdesk.close_tickets
            )";

            // ── open + close + self_tickets (self_tickets uses status_update_by) ─
            // Aliased to a common column name; updated_at NULL for self_tickets
            $allTicketsWithSelf = "(
                SELECT ticket_number, user_id, client_id_helpdesk, business_entity_id,
                    team_id, cat_id, subcat_id, status_id, created_at, updated_at,
                    status_updated_by
                FROM helpdesk.open_tickets
                UNION ALL
                SELECT ticket_number, user_id, client_id_helpdesk, business_entity_id,
                    team_id, cat_id, subcat_id, status_id, created_at, updated_at,
                    status_updated_by
                FROM helpdesk.close_tickets
                UNION ALL
                SELECT ticket_number, user_id, client_id_helpdesk, business_entity_id,
                    team_id, cat_id, subcat_id, status_id, created_at,
                    NULL AS updated_at,
                    status_update_by AS status_updated_by
                FROM helpdesk.self_tickets
            )";

            // ── SRV overdue sub-query builder ─────────────────────────────────────
            // sla_subcat_configs + srv_time_subcat_sla_histories (sla_status = 0 = violated)
            // business_entity_id resolved via joining open+close tickets
            $srvOverdueSub = function($dateWhere) use ($allTickets) {
                $extra = $dateWhere ? "AND $dateWhere" : "";
                return "(
                    SELECT t.business_entity_id,
                        COUNT(DISTINCT stsh.ticket_number) AS over_due
                    FROM helpdesk.sla_subcat_configs tsh
                    JOIN helpdesk.srv_time_subcat_sla_histories stsh
                        ON stsh.sla_subcat_config_id = tsh.id
                    JOIN $allTickets t ON t.ticket_number = stsh.ticket_number
                    WHERE stsh.sla_status = 0
                    $extra
                    GROUP BY t.business_entity_id
                ) AS srv_ticket_counts";
            };

            // ── FR overdue sub-query builder ──────────────────────────────────────
            // first_res_configs + first_res_sla_histories (sla_status = 0 = violated)
            $frOverdueSub = function($dateWhere) use ($allTickets) {
                $extra = $dateWhere ? "AND $dateWhere" : "";
                return "(
                    SELECT t.business_entity_id,
                        COUNT(DISTINCT frh.ticket_number) AS over_due_fr
                    FROM helpdesk.first_res_configs frc
                    JOIN helpdesk.first_res_sla_histories frh
                        ON frh.first_res_config_id = frc.id
                    JOIN $allTickets t ON t.ticket_number = frh.ticket_number
                    WHERE frh.sla_status = 0
                    $extra
                    GROUP BY t.business_entity_id
                ) AS fr_ticket_counts";
            };

            // ════════════════════════════════════════════════════════════════════
            // Branch: Agent
            // ════════════════════════════════════════════════════════════════════
            if ($userType == 'Agent' && !empty($companyId)) {

                $ticketDateJoin = $isDateRange
                    ? "AND DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'"
                    : "";

                $slaDateFilter  = $isDateRange
                    ? "DATE(stsh.created_at) BETWEEN '$fromDate' AND '$toDate'"
                    : null;

                $frDateFilter   = $isDateRange
                    ? "DATE(frh.created_at) BETWEEN '$fromDate' AND '$toDate'"
                    : null;

                $results = DB::select("
                    SELECT
                        COUNT(DISTINCT CASE WHEN th2.status_id = 6  THEN th2.ticket_number END) AS closed_tickets,
                        COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets,
                        COALESCE(srv_ticket_counts.over_due,    0) AS over_due,
                        COALESCE(fr_ticket_counts.over_due_fr,  0) AS over_due_fr
                    FROM helpdesk.companies c
                    LEFT JOIN $allTickets th2
                        ON c.id = th2.business_entity_id $ticketDateJoin
                    LEFT JOIN {$srvOverdueSub($slaDateFilter)}
                        ON c.id = srv_ticket_counts.business_entity_id
                    LEFT JOIN {$frOverdueSub($frDateFilter)}
                        ON c.id = fr_ticket_counts.business_entity_id
                    WHERE c.id = $companyId
                    GROUP BY c.company_name
                ");

                return ApiResponse::success($results, "Success", 200);

            // ════════════════════════════════════════════════════════════════════
            // Branch: Client  (open + close + self_tickets, filtered by client_id)
            // ════════════════════════════════════════════════════════════════════
            } elseif ($userType == 'Client' && !empty($companyId)) {

                $dateWhere = $isDateRange
                    ? "AND DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'"
                    : "";

                $results = DB::select("
                    SELECT
                        COUNT(DISTINCT CASE WHEN th2.status_id = 6  THEN th2.ticket_number END) AS closed_tickets,
                        COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets
                    FROM helpdesk.companies c
                    LEFT JOIN $allTicketsWithSelf th2
                        ON c.id = th2.business_entity_id
                    WHERE c.id = $companyId
                    AND th2.client_id_helpdesk = '$userID'
                    $dateWhere
                    GROUP BY c.company_name
                ");

                return ApiResponse::success($results, "Success", 200);

            // ════════════════════════════════════════════════════════════════════
            // Branch: Customer  (open + close + self_tickets, filtered by user_id)
            // ════════════════════════════════════════════════════════════════════
            } elseif ($userType == 'Customer' && !empty($companyId)) {

                $dateWhere = $isDateRange
                    ? "AND DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'"
                    : "";

                // Customer with date range also shows SLA overdue (from original)
                if ($isDateRange) {
                    $slaDateFilter = "DATE(stsh.created_at) BETWEEN '$fromDate' AND '$toDate'";
                    $frDateFilter  = "DATE(frh.created_at) BETWEEN '$fromDate' AND '$toDate'";

                    $results = DB::select("
                        SELECT
                            COUNT(DISTINCT CASE WHEN th2.status_id = 6  THEN th2.ticket_number END) AS closed_tickets,
                            COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets,
                            COALESCE(srv_ticket_counts.over_due,   0) AS over_due,
                            COALESCE(fr_ticket_counts.over_due_fr, 0) AS over_due_fr
                        FROM helpdesk.companies c
                        LEFT JOIN $allTicketsWithSelf th2
                            ON c.id = th2.business_entity_id
                        LEFT JOIN {$srvOverdueSub($slaDateFilter)}
                            ON c.id = srv_ticket_counts.business_entity_id
                        LEFT JOIN {$frOverdueSub($frDateFilter)}
                            ON c.id = fr_ticket_counts.business_entity_id
                        WHERE th2.user_id = '$userID'
                        $dateWhere
                        GROUP BY c.company_name
                    ");
                } else {
                    $results = DB::select("
                        SELECT
                            COUNT(DISTINCT CASE WHEN th2.status_id = 6  THEN th2.ticket_number END) AS closed_tickets,
                            COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets
                        FROM helpdesk.companies c
                        LEFT JOIN $allTicketsWithSelf th2
                            ON c.id = th2.business_entity_id
                        WHERE th2.user_id = '$userID'
                        GROUP BY c.company_name
                    ");
                }

                return ApiResponse::success($results, "Success", 200);
            }

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }



    // public function dashboardLast30Days(Request $request)
    // {
    //     try {

    //         $companyId = $request->businessEntity;

    //         $userType = $request->userType;

    //         $userID = $request->userId;

    //         if ($userType == 'Agent') {

    //             $ticketCounts = DB::select("SELECT 
    //                     COUNT(DISTINCT CASE WHEN t.status_id = 6 THEN t.ticket_number END) AS close_tickets,
    //                     COUNT(DISTINCT CASE WHEN t.status_id != 6 THEN t.ticket_number END) AS open_tickets,
    //                     COUNT(DISTINCT CASE WHEN t.status_id IN (1,2,3,4,5,6) THEN t.ticket_number END) AS create_tickets
    //                 FROM helpdesk.tickets t WHERE t.business_entity_id = '$companyId' 
    //                 AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");

    //             $sumClosedTickets = $ticketCounts[0]->close_tickets ?? 0;
    //             $sumOpenTickets = $ticketCounts[0]->open_tickets ?? 0;
    //             $sumCreateTickets = $ticketCounts[0]->create_tickets ?? 0;

    //             // Step: Fetch SLA Success Count
    //             $slaSuccessQuery = DB::select("SELECT COUNT(DISTINCT tsh.ticket_number) AS sla_success
    //                     FROM helpdesk.ticket_srv_time_team_histories tsh
    //                     JOIN helpdesk.tickets t ON t.ticket_number = tsh.ticket_number
    //                     JOIN helpdesk.companies c ON c.id = t.business_entity_id AND c.id = '$companyId'
    //                     WHERE tsh.srv_time_status = 1 AND tsh.created_at BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
    //                 ");
    //             $srvViolatedSLA = DB::select("SELECT COUNT(DISTINCT tsh.ticket_number) AS srv_violated_sla
    //                 FROM helpdesk.ticket_srv_time_team_histories tsh
    //                 JOIN helpdesk.tickets t ON t.ticket_number = tsh.ticket_number
    //                 JOIN helpdesk.companies c ON c.id = t.business_entity_id AND c.id = '$companyId'
    //                 WHERE tsh.srv_time_status = 2 AND tsh.created_at BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
    //             ");

    //             $frViolatedSLA = DB::select("SELECT COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
    //             FROM helpdesk.ticket_fr_time_team_histories tfh
    //             JOIN helpdesk.tickets t ON t.ticket_number = tfh.ticket_number
    //             JOIN helpdesk.companies c ON c.id = t.business_entity_id AND c.id = '$companyId'
    //             WHERE tfh.fr_response_status = 2 AND tfh.created_at BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
    //             ");
    //             $slaSuccessCount = $slaSuccessQuery[0]->sla_success ?? 0;

    //             $finalOutputSum = [
    //                 'ticket_closed_by_team' => $sumClosedTickets,
    //                 'ticket_open_by_team' => $sumOpenTickets,
    //                 'ticket_create_by_team' => $sumCreateTickets,
    //                 'sla_success' => $slaSuccessCount,
    //                 'fr_violated_sla' => $frViolatedSLA[0]->fr_violated_sla ?? 0,
    //                 'srv_violated_sla' => $srvViolatedSLA[0]->srv_violated_sla ?? 0,
    //             ];

    //             $opencloseByLast30Days = DB::select("WITH RECURSIVE date_series AS (
    //                     SELECT CURDATE() - INTERVAL 30 DAY AS report_date
    //                     UNION ALL
    //                     SELECT report_date + INTERVAL 1 DAY
    //                     FROM date_series
    //                     WHERE report_date < CURDATE()
    //                 )
    //                 SELECT 
    //                     DATE_FORMAT(ds.report_date, '%d-%b-%y') AS report_date,
    //                     COUNT(DISTINCT CASE 
    //                         WHEN th2.status_id = 6 THEN th2.ticket_number 
    //                     END) AS closed_tickets,
    //                     COUNT(DISTINCT CASE 
    //                         WHEN th2.status_id != 6 THEN th2.ticket_number 
    //                     END) AS open_tickets,
	// 					COUNT(DISTINCT CASE 
    //                         WHEN th2.status_id IN (1,2,3,4,5,6) THEN th2.ticket_number 
    //                     END) AS create_tickets
    //                 FROM date_series ds
    //                 LEFT JOIN helpdesk.tickets th2 
    //                     ON DATE(th2.created_at) = ds.report_date
    //                     AND th2.business_entity_id = '$companyId'
    //                 GROUP BY ds.report_date
    //                 ORDER BY ds.report_date
    //             ");

    //             $response = [
    //                 'summary' => $finalOutputSum,
    //                 'opencloseByLast30Days' => $opencloseByLast30Days
    //                 // 'details' => $finalOutput,
    //             ];

    //             return ApiResponse::success($response, "Success", 200);
    //         } elseif ($userType == 'Client') {
    //             $ticketCounts = DB::select("SELECT 
    //                 COUNT(DISTINCT CASE WHEN t.status_id = 6 THEN t.ticket_number END) AS close_tickets,
    //                 COUNT(DISTINCT CASE WHEN t.status_id != 6 THEN t.ticket_number END) AS open_tickets,
    //                 COUNT(DISTINCT CASE WHEN t.status_id IN (1,2,3,4,5,6) THEN t.ticket_number END) AS create_tickets
    //             FROM (
    //                             SELECT 
    //                                 id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
    //                                 'Ticket' AS source_type
    //                             FROM helpdesk.tickets

    //                             UNION ALL

    //                             SELECT 
    //                                 id, ticket_number, user_id, client_id_helpdesk, business_entity_id, team_id, sid, cat_id, subcat_id, status_id, created_at, updated_at, status_update_by,
    //                                 'Self-Ticket' AS source_type
    //                             FROM helpdesk.self_tickets
    //                         ) t WHERE t.business_entity_id = '$companyId' AND t.client_id_helpdesk = '$userID'
    //             AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");

    //             $sumClosedTickets = $ticketCounts[0]->close_tickets ?? 0;
    //             $sumOpenTickets = $ticketCounts[0]->open_tickets ?? 0;
    //             $sumCreateTickets = $ticketCounts[0]->create_tickets ?? 0;

    //             $finalOutputSum = [
    //                 'ticket_closed_by_team' => $sumClosedTickets,
    //                 'ticket_open_by_team' => $sumOpenTickets,
    //                 'ticket_create_by_team' => $sumCreateTickets,
    //                 // 'sla_success' => $slaSuccessCount,
    //             ];

    //             $opencloseByLast30Days = DB::select("WITH RECURSIVE date_series AS (
    //                     SELECT CURDATE() - INTERVAL 30 DAY AS report_date
    //                     UNION ALL
    //                     SELECT report_date + INTERVAL 1 DAY
    //                     FROM date_series
    //                     WHERE report_date < CURDATE()
    //                 )
    //                 SELECT 
    //                     DATE_FORMAT(ds.report_date, '%d-%b-%y') AS report_date,
    //                     COUNT(DISTINCT CASE 
    //                         WHEN th2.status_id = 6 THEN th2.ticket_number 
    //                     END) AS closed_tickets,
    //                     COUNT(DISTINCT CASE 
    //                         WHEN th2.status_id != 6 THEN th2.ticket_number 
    //                     END) AS open_tickets,
    //                     COUNT(DISTINCT CASE 
    //                         WHEN th2.status_id IN (1,2,3,4,5,6) THEN th2.ticket_number 
    //                     END) AS create_tickets
    //                 FROM date_series ds
    //                 JOIN helpdesk.tickets th2 
    //                     ON DATE(th2.created_at) = ds.report_date
    //                     AND th2.business_entity_id = '$companyId' AND th2.client_id_helpdesk = '$userID'
    //                 GROUP BY ds.report_date
    //                 ORDER BY ds.report_date
    //             ");

    //             $response = [
    //                 'summary' => $finalOutputSum,
    //                 'opencloseByLast30Days' => $opencloseByLast30Days
    //                 // 'details' => $finalOutput,
    //             ];

    //             return ApiResponse::success($response, "Success", 200);
    //         } elseif ($userType == 'Customer') {
    //             $ticketCounts = DB::select("SELECT 
    //                 COUNT(DISTINCT CASE WHEN t.status_id = 6 THEN t.ticket_number END) AS close_tickets,
    //                 COUNT(DISTINCT CASE WHEN t.status_id != 6 THEN t.ticket_number END) AS open_tickets,
    //                 COUNT(DISTINCT CASE WHEN t.status_id IN (1,2,3,4,5,6) THEN t.ticket_number END) AS create_tickets
    //             FROM helpdesk.tickets t WHERE  t.user_id = '$userID'
    //             AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");

    //             $sumClosedTickets = $ticketCounts[0]->close_tickets ?? 0;
    //             $sumOpenTickets = $ticketCounts[0]->open_tickets ?? 0;
    //             $sumCreateTickets = $ticketCounts[0]->create_tickets ?? 0;

    //             // Step: Fetch SLA Success Count
    //             // $slaSuccessQuery = DB::select("SELECT COUNT(DISTINCT tsh.ticket_number) AS sla_success
    //             //     FROM helpdesk.ticket_srv_time_team_histories tsh
    //             //     JOIN helpdesk.tickets t ON t.ticket_number = tsh.ticket_number
    //             //     JOIN helpdesk.companies c ON c.id = t.business_entity_id AND c.id = '$companyId'
    //             //     WHERE tsh.srv_time_status = 1 AND tsh.created_at BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE()
    //             // ");
    //             // $slaSuccessCount = $slaSuccessQuery[0]->sla_success ?? 0;

    //             $finalOutputSum = [
    //                 'ticket_closed_by_team' => $sumClosedTickets,
    //                 'ticket_open_by_team' => $sumOpenTickets,
    //                 'ticket_create_by_team' => $sumCreateTickets,
    //                 // 'sla_success' => $slaSuccessCount,
    //             ];

    //             $opencloseByLast30Days = DB::select("WITH RECURSIVE date_series AS (
    //                     SELECT CURDATE() - INTERVAL 30 DAY AS report_date
    //                     UNION ALL
    //                     SELECT report_date + INTERVAL 1 DAY
    //                     FROM date_series
    //                     WHERE report_date < CURDATE()
    //                 )
    //                 SELECT 
    //                     DATE_FORMAT(ds.report_date, '%d-%b-%y') AS report_date,
    //                     COUNT(DISTINCT CASE 
    //                         WHEN th2.status_id = 6 THEN th2.ticket_number 
    //                     END) AS closed_tickets,
    //                     COUNT(DISTINCT CASE 
    //                         WHEN th2.status_id != 6 THEN th2.ticket_number 
    //                     END) AS open_tickets,
    //                     COUNT(DISTINCT CASE 
    //                         WHEN th2.status_id IN (1,2,3,4,5,6) THEN th2.ticket_number 
    //                     END) AS create_tickets
    //                 FROM date_series ds
    //                 JOIN helpdesk.tickets th2 
    //                     ON DATE(th2.created_at) = ds.report_date
    //                     AND th2.user_id = '$userID'
    //                 GROUP BY ds.report_date
    //                 ORDER BY ds.report_date
    //             ");

    //             $response = [
    //                 'summary' => $finalOutputSum,
    //                 'opencloseByLast30Days' => $opencloseByLast30Days
    //                 // 'details' => $finalOutput,
    //             ];

    //             return ApiResponse::success($response, "Success", 200);
    //         }
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Failed", 500);
    //     }
    // }

  //c2
    public function dashboardLast30Days(Request $request)
    {
        try {
            $companyId = $request->businessEntity;
            $userType  = $request->userType;
            $userID    = $request->userId;

            // ── open_tickets + close_tickets: status_updated_by, no sid, has updated_at ──
            $allTickets = "(
                SELECT ticket_number, user_id, client_id_helpdesk, business_entity_id,
                    team_id, cat_id, subcat_id, status_id, created_at, updated_at,
                    status_updated_by
                FROM helpdesk.open_tickets
                UNION ALL
                SELECT ticket_number, user_id, client_id_helpdesk, business_entity_id,
                    team_id, cat_id, subcat_id, status_id, created_at, updated_at,
                    status_updated_by
                FROM helpdesk.close_tickets
            ) t";

            // ── date_series CTE ───────────────────────────────────────────────────
            $dateSeries = "WITH RECURSIVE date_series AS (
                SELECT CURDATE() - INTERVAL 30 DAY AS report_date
                UNION ALL
                SELECT report_date + INTERVAL 1 DAY
                FROM date_series
                WHERE report_date < CURDATE()
            )";

            // ════════════════════════════════════════════════════════════════════
            // Branch: Agent
            // ════════════════════════════════════════════════════════════════════
            if ($userType == 'Agent') {

                $ticketCounts = DB::select("
                    SELECT
                        COUNT(DISTINCT CASE WHEN t.status_id = 6  THEN t.ticket_number END) AS close_tickets,
                        COUNT(DISTINCT CASE WHEN t.status_id != 6 THEN t.ticket_number END) AS open_tickets,
                        COUNT(DISTINCT t.ticket_number)                                      AS create_tickets
                    FROM $allTickets
                    WHERE t.business_entity_id = '$companyId'
                    AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                ");

                // SLA success: sla_subcat_configs + srv_time_subcat_sla_histories (sla_status = 1)
                $slaSuccessQuery = DB::select("
                    SELECT COUNT(DISTINCT stsh.ticket_number) AS sla_success
                    FROM helpdesk.sla_subcat_configs tsh
                    JOIN helpdesk.srv_time_subcat_sla_histories stsh ON stsh.sla_subcat_config_id = tsh.id
                    JOIN $allTickets ON t.ticket_number = stsh.ticket_number
                    JOIN helpdesk.companies c ON c.id = t.business_entity_id AND c.id = '$companyId'
                    WHERE stsh.sla_status = 1
                    AND stsh.created_at BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
                ");

                // SRV violated: sla_status = 0
                $srvViolatedSLA = DB::select("
                    SELECT COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
                    FROM helpdesk.sla_subcat_configs tsh
                    JOIN helpdesk.srv_time_subcat_sla_histories stsh ON stsh.sla_subcat_config_id = tsh.id
                    JOIN $allTickets ON t.ticket_number = stsh.ticket_number
                    JOIN helpdesk.companies c ON c.id = t.business_entity_id AND c.id = '$companyId'
                    WHERE stsh.sla_status = 0
                    AND stsh.created_at BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
                ");

                // FR violated: first_res_configs + first_res_sla_histories (sla_status = 0)
                $frViolatedSLA = DB::select("
                    SELECT COUNT(DISTINCT frh.ticket_number) AS fr_violated_sla
                    FROM helpdesk.first_res_configs frc
                    JOIN helpdesk.first_res_sla_histories frh ON frh.first_res_config_id = frc.id
                    JOIN $allTickets ON t.ticket_number = frh.ticket_number
                    JOIN helpdesk.companies c ON c.id = t.business_entity_id AND c.id = '$companyId'
                    WHERE frh.sla_status = 0
                    AND frh.created_at BETWEEN NOW() - INTERVAL 30 DAY AND NOW()
                ");

                $finalOutputSum = [
                    'ticket_closed_by_team' => $ticketCounts[0]->close_tickets       ?? 0,
                    'ticket_open_by_team'   => $ticketCounts[0]->open_tickets         ?? 0,
                    'ticket_create_by_team' => $ticketCounts[0]->create_tickets       ?? 0,
                    'sla_success'           => $slaSuccessQuery[0]->sla_success       ?? 0,
                    'fr_violated_sla'       => $frViolatedSLA[0]->fr_violated_sla     ?? 0,
                    'srv_violated_sla'      => $srvViolatedSLA[0]->srv_violated_sla   ?? 0,
                ];

                $opencloseByLast30Days = DB::select("
                    $dateSeries
                    SELECT
                        DATE_FORMAT(ds.report_date, '%d-%b-%y') AS report_date,
                        COUNT(DISTINCT CASE WHEN t.status_id = 6  THEN t.ticket_number END) AS closed_tickets,
                        COUNT(DISTINCT CASE WHEN t.status_id != 6 THEN t.ticket_number END) AS open_tickets,
                        COUNT(DISTINCT t.ticket_number)                                      AS create_tickets
                    FROM date_series ds
                    LEFT JOIN $allTickets
                        ON DATE(t.created_at) = ds.report_date
                        AND t.business_entity_id = '$companyId'
                    GROUP BY ds.report_date
                    ORDER BY ds.report_date
                ");

                return ApiResponse::success([
                    'summary'               => $finalOutputSum,
                    'opencloseByLast30Days' => $opencloseByLast30Days,
                ], "Success", 200);

            // ════════════════════════════════════════════════════════════════════
            // Branch: Client  (open + close helpdesk tickets + self_tickets)
            // self_tickets has: status_update_by (no 'd'), sid, NO updated_at
            // Aliased to match open/close_tickets column names in the UNION
            // ════════════════════════════════════════════════════════════════════
            } elseif ($userType == 'Client') {

                $clientAllTickets = "(
                    SELECT ticket_number, user_id, client_id_helpdesk, business_entity_id,
                        team_id, cat_id, subcat_id, status_id, created_at,
                        status_updated_by, 'Ticket' AS source_type
                    FROM helpdesk.open_tickets

                    UNION ALL

                    SELECT ticket_number, user_id, client_id_helpdesk, business_entity_id,
                        team_id, cat_id, subcat_id, status_id, created_at,
                        status_updated_by, 'Ticket' AS source_type
                    FROM helpdesk.close_tickets

                    UNION ALL

                    -- self_tickets: alias status_update_by → status_updated_by to unify column names
                    SELECT ticket_number, user_id, client_id_helpdesk, business_entity_id,
                        team_id, cat_id, subcat_id, status_id, created_at,
                        status_update_by AS status_updated_by, 'Self-Ticket' AS source_type
                    FROM helpdesk.self_tickets
                ) t";

                $ticketCounts = DB::select("
                    SELECT
                        COUNT(DISTINCT CASE WHEN t.status_id = 6  THEN t.ticket_number END) AS close_tickets,
                        COUNT(DISTINCT CASE WHEN t.status_id != 6 THEN t.ticket_number END) AS open_tickets,
                        COUNT(DISTINCT t.ticket_number)                                      AS create_tickets
                    FROM $clientAllTickets
                    WHERE t.business_entity_id = '$companyId'
                    AND t.client_id_helpdesk  = '$userID'
                    AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                ");

                $finalOutputSum = [
                    'ticket_closed_by_team' => $ticketCounts[0]->close_tickets ?? 0,
                    'ticket_open_by_team'   => $ticketCounts[0]->open_tickets   ?? 0,
                    'ticket_create_by_team' => $ticketCounts[0]->create_tickets ?? 0,
                ];

                // Per-day chart uses helpdesk tickets only (open + close), no self_tickets
                $opencloseByLast30Days = DB::select("
                    $dateSeries
                    SELECT
                        DATE_FORMAT(ds.report_date, '%d-%b-%y') AS report_date,
                        COUNT(DISTINCT CASE WHEN t.status_id = 6  THEN t.ticket_number END) AS closed_tickets,
                        COUNT(DISTINCT CASE WHEN t.status_id != 6 THEN t.ticket_number END) AS open_tickets,
                        COUNT(DISTINCT t.ticket_number)                                      AS create_tickets
                    FROM date_series ds
                    LEFT JOIN $allTickets
                        ON DATE(t.created_at) = ds.report_date
                        AND t.business_entity_id = '$companyId'
                        AND t.client_id_helpdesk  = '$userID'
                    GROUP BY ds.report_date
                    ORDER BY ds.report_date
                ");

                return ApiResponse::success([
                    'summary'               => $finalOutputSum,
                    'opencloseByLast30Days' => $opencloseByLast30Days,
                ], "Success", 200);

            // ════════════════════════════════════════════════════════════════════
            // Branch: Customer
            // ════════════════════════════════════════════════════════════════════
            } elseif ($userType == 'Customer') {

                $ticketCounts = DB::select("
                    SELECT
                        COUNT(DISTINCT CASE WHEN t.status_id = 6  THEN t.ticket_number END) AS close_tickets,
                        COUNT(DISTINCT CASE WHEN t.status_id != 6 THEN t.ticket_number END) AS open_tickets,
                        COUNT(DISTINCT t.ticket_number)                                      AS create_tickets
                    FROM $allTickets
                    WHERE t.user_id = '$userID'
                    AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                ");

                $finalOutputSum = [
                    'ticket_closed_by_team' => $ticketCounts[0]->close_tickets ?? 0,
                    'ticket_open_by_team'   => $ticketCounts[0]->open_tickets   ?? 0,
                    'ticket_create_by_team' => $ticketCounts[0]->create_tickets ?? 0,
                ];

                $opencloseByLast30Days = DB::select("
                    $dateSeries
                    SELECT
                        DATE_FORMAT(ds.report_date, '%d-%b-%y') AS report_date,
                        COUNT(DISTINCT CASE WHEN t.status_id = 6  THEN t.ticket_number END) AS closed_tickets,
                        COUNT(DISTINCT CASE WHEN t.status_id != 6 THEN t.ticket_number END) AS open_tickets,
                        COUNT(DISTINCT t.ticket_number)                                      AS create_tickets
                    FROM date_series ds
                    LEFT JOIN $allTickets
                        ON DATE(t.created_at) = ds.report_date
                        AND t.user_id = '$userID'
                    GROUP BY ds.report_date
                    ORDER BY ds.report_date
                ");

                return ApiResponse::success([
                    'summary'               => $finalOutputSum,
                    'opencloseByLast30Days' => $opencloseByLast30Days,
                ], "Success", 200);
            }

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }

    // public function departmentReportForDashboard(Request $request)
    // {

    //     try {

    //         // $cacheKey = 'department_report_dashboard';

    //         // $cachedData = Cache::get($cacheKey);

    //         // if ($cachedData) {
    //         //     Log::info("Cache hit for key: {$cacheKey}");
    //         //     return ApiResponse::success($cachedData, "Success", 200);
    //         // }


    //         $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //         $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

    //         // if ((empty($fromDate)) && (empty($toDate))) {
    //         //     $cacheKey = 'department_report_dashboard';

    //         //     $cachedData = Cache::get($cacheKey);

    //         //     if ($cachedData) {
    //         //         Log::info("Cache hit for key: {$cacheKey}");
    //         //         return ApiResponse::success($cachedData, "Success", 200);
    //         //     }
    //         // }

    //         if ((empty($fromDate)) && (empty($toDate))) {

    //             $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth
    //             WHERE fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");

    //             $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth
    //             WHERE sth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
    //         } else {

    //             $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth WHERE DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate' ");

    //             $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth WHERE DATE(sth.created_at) BETWEEN '$fromDate' AND '$toDate'");
    //         }

    //         $ListOfTicket = array_map(fn($item) => $item->ticket_number, $fr_teamTicket);

    //         $ListOfTimeDifferences = [];

    //         // Step 2: Compute time differences for each team and ticket
    //         foreach ($ListOfTicket as $ticket_number) {
    //             $fr_teamID = DB::select("SELECT DISTINCT(fth.team_id), t.team_name, d.department_name 
    //                 FROM helpdesk.ticket_fr_time_team_histories fth 
    //                 JOIN helpdesk.teams t ON fth.team_id = t.id
    //                 JOIN helpdesk.departments d ON d.id = t.department_id
    //                 WHERE fth.ticket_number = ?
    //             ", [$ticket_number]);

    //             // dd($fr_teamID);

    //             foreach ($fr_teamID as $team) {
    //                 $team_id = $team->team_id;
    //                 // $team_name = $team->team_name;
    //                 $department_name = $team->department_name;

    //                 $fr_statusRecords = DB::select("SELECT fth.fr_response_status, fth.created_at 
    //                     FROM helpdesk.ticket_fr_time_team_histories fth
    //                     WHERE fth.ticket_number = ? AND fth.team_id = ? 
    //                     AND fth.fr_response_status IN (0, 1, 2) 
    //                     ORDER BY fth.created_at ASC
    //                 ", [$ticket_number, $team_id]);

    //                 $timestamps = [
    //                     'status_0' => null,
    //                     'status_1_or_2' => null
    //                 ];

    //                 foreach ($fr_statusRecords as $record) {
    //                     if ($record->fr_response_status == 0) {
    //                         $timestamps['status_0'] = $record->created_at;
    //                     } elseif (in_array($record->fr_response_status, [1, 2])) {
    //                         $timestamps['status_1_or_2'] = $record->created_at;
    //                     }
    //                 }

    //                 if ($timestamps['status_0'] && $timestamps['status_1_or_2']) {
    //                     $startTime = new DateTime($timestamps['status_0']);
    //                     $endTime = new DateTime($timestamps['status_1_or_2']);
    //                     $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

    //                     $ListOfTimeDifferences[] = [
    //                         // 'team_id' => $team_id,
    //                         // 'team_name' => $team_name,
    //                         'department_name' => $department_name,
    //                         'time_difference_in_seconds' => $timeDifferenceInSeconds
    //                     ];
    //                 }
    //             }

    //             // dd($ListOfTimeDifferences);
    //         }

    //         // Step 3: Summing time and calculating the average per team
    //         $timeSumsByTeam = [];
    //         foreach ($ListOfTimeDifferences as $entry) {
    //             // $team_id = $entry['team_id'];
    //             // $team_name = $entry['team_name'];
    //             $department_name = $entry['department_name'];
    //             $time_difference = $entry['time_difference_in_seconds'];

    //             if (!isset($timeSumsByTeam[$department_name])) {
    //                 $timeSumsByTeam[$department_name] = [
    //                     // 'team_name' => $team_name,
    //                     'department_name' => $department_name,
    //                     'total_time_in_seconds' => 0,
    //                     'count' => 0
    //                 ];
    //             }

    //             $timeSumsByTeam[$department_name]['total_time_in_seconds'] += $time_difference;
    //             $timeSumsByTeam[$department_name]['count']++;
    //         }

    //         // Step 4: Calculate average times
    //         $averagesByTeam = [];
    //         foreach ($timeSumsByTeam as $department_name => $data) {
    //             $totalTime = $data['total_time_in_seconds'];
    //             $count = $data['count'];
    //             $averageTime = $totalTime / $count;

    //             $averagesByTeam[$department_name] = [
    //                 // 'team_id' => $team_id,
    //                 // 'team_name' => $data['team_name'],
    //                 'department_name' => $data['department_name'],
    //                 // 'total_time' => gmdate("H:i:s", $totalTime),
    //                 // 'average_time' => gmdate("H:i:s", $averageTime)

    //                 'total_time' => formatTime($totalTime),
    //                 'average_time' => formatTime($averageTime)
    //             ];
    //         }
    //         $ListOfSrvTicket = array_map(fn($item) => $item->ticket_number, $srv_teamTicket);

    //         $ListOfSrvTimeDifferences = [];

    //         // Step 6: Compute time differences for each team and SRV ticket
    //         foreach ($ListOfSrvTicket as $ticket_number) {
    //             $srv_teamID = DB::select("SELECT DISTINCT(sth.team_id), t.team_name, d.department_name
    //                 FROM helpdesk.ticket_srv_time_team_histories sth 
    //                 JOIN helpdesk.teams t ON sth.team_id = t.id
    //                 JOIN helpdesk.departments d ON d.id = t.department_id
    //                 WHERE sth.ticket_number = ?
    //             ", [$ticket_number]);

    //             foreach ($srv_teamID as $team) {
    //                 $team_id = $team->team_id;
    //                 // $team_name = $team->team_name;
    //                 $department_name = $team->department_name;

    //                 $srv_statusRecords = DB::select("SELECT sth.srv_time_status, sth.created_at 
    //                     FROM helpdesk.ticket_srv_time_team_histories sth
    //                     WHERE sth.ticket_number = ? AND sth.team_id = ? 
    //                     AND sth.srv_time_status IN (0, 1, 2) 
    //                     ORDER BY sth.created_at ASC
    //                 ", [$ticket_number, $team_id]);

    //                 $srvTimestamps = [
    //                     'status_0' => null,
    //                     'status_1_or_2' => null
    //                 ];

    //                 foreach ($srv_statusRecords as $record) {
    //                     if ($record->srv_time_status == 0) {
    //                         $srvTimestamps['status_0'] = $record->created_at;
    //                     } elseif (in_array($record->srv_time_status, [1, 2])) {
    //                         $srvTimestamps['status_1_or_2'] = $record->created_at;
    //                     }
    //                 }

    //                 if ($srvTimestamps['status_0'] && $srvTimestamps['status_1_or_2']) {
    //                     $startTime = new DateTime($srvTimestamps['status_0']);
    //                     $endTime = new DateTime($srvTimestamps['status_1_or_2']);
    //                     $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

    //                     $ListOfSrvTimeDifferences[] = [
    //                         // 'team_id' => $team_id,
    //                         // 'team_name' => $team_name,
    //                         'department_name' => $department_name,
    //                         'time_difference_in_seconds' => $timeDifferenceInSeconds
    //                     ];
    //                 }
    //             }
    //         }

    //         // Step 7: Summing time and calculating the average per SRV team
    //         $timeSumsByTeamSrv = [];
    //         foreach ($ListOfSrvTimeDifferences as $entry) {
    //             // $team_id = $entry['team_id'];
    //             // $team_name = $entry['team_name'];
    //             $department_name = $entry['department_name'];
    //             $time_difference = $entry['time_difference_in_seconds'];

    //             if (!isset($timeSumsByTeamSrv[$department_name])) {
    //                 $timeSumsByTeamSrv[$department_name] = [
    //                     // 'team_name' => $team_name,
    //                     'department_name' => $department_name,
    //                     'total_time_in_seconds' => 0,
    //                     'count' => 0
    //                 ];
    //             }

    //             $timeSumsByTeamSrv[$department_name]['total_time_in_seconds'] += $time_difference;
    //             $timeSumsByTeamSrv[$department_name]['count']++;
    //         }

    //         // Step 8: Calculate average times for SRV teams
    //         $averagesBySrvTeam = [];
    //         foreach ($timeSumsByTeamSrv as $department_name => $data) {
    //             $totalTime = $data['total_time_in_seconds'];
    //             $count = $data['count'];
    //             $averageSrvTime = $totalTime / $count;

    //             $averagesBySrvTeam[$department_name] = [
    //                 // 'team_id' => $team_id,
    //                 // 'team_name' => $data['team_name'],
    //                 'department_name' => $data['department_name'],
    //                 // 'total_time' => gmdate("H:i:s", $totalTime),
    //                 // 'average_srv_time' => gmdate("H:i:s", $averageSrvTime)

    //                 'total_time' => formatTime($totalTime),
    //                 'average_srv_time' => formatTime($averageSrvTime)
    //             ];
    //         }

    //         if ((empty($fromDate)) && (empty($toDate))) {
    //             $overDueCounts = DB::select("SELECT d.department_name as department_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
    //                         FROM helpdesk.ticket_srv_time_team_histories tsh
    //                         JOIN helpdesk.teams t ON t.id = tsh.team_id
    //                         JOIN helpdesk.departments d ON d.id = t.department_id
    //                         WHERE tsh.srv_time_status = 2
    //                         GROUP BY d.department_name
    //                 ");
    //         } else {
    //             $overDueCounts = DB::select("SELECT d.department_name as department_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
    //                         FROM helpdesk.ticket_srv_time_team_histories tsh
    //                         JOIN helpdesk.teams t ON t.id = tsh.team_id
    //                         JOIN helpdesk.departments d ON d.id = t.department_id
    //                         WHERE tsh.srv_time_status = 2 AND DATE(tsh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                         GROUP BY d.department_name
    //             ");
    //         }

    //         // Map the over_due counts by team_id for easy access
    //         $overDueMap = [];
    //         foreach ($overDueCounts as $overDueData) {
    //             $overDueMap[$overDueData->department_name] = $overDueData->over_due;
    //         }

    //         // Step 13: Merge the overdue counts into the final averages data
    //         foreach ($averagesByTeam as $department_name => $teamData) {
    //             if (isset($overDueMap[$department_name])) {
    //                 $averagesByTeam[$department_name]['over_due'] = $overDueMap[$department_name];
    //             } else {
    //                 $averagesByTeam[$department_name]['over_due'] = 0; // Default to 0 if no overdue count exists
    //             }
    //         }

    //         // Step 14: Final output
    //         $finalOutput = array_values($averagesByTeam);

    //         if ((empty($fromDate)) && (empty($toDate))) {
    //             $overDueCountsFr = DB::select("SELECT d.department_name as department_name, COUNT(DISTINCT fth.ticket_number) AS over_due_fr
    //                         FROM helpdesk.ticket_fr_time_team_histories fth
    //                         JOIN helpdesk.teams t ON t.id = fth.team_id
    //                         JOIN helpdesk.departments d ON d.id = t.department_id
    //                         WHERE fth.fr_response_status = 2
    //                         GROUP BY d.department_name
    //                 ");
    //         } else {
    //             $overDueCountsFr = DB::select("SELECT d.department_name as department_name, COUNT(DISTINCT fth.ticket_number) AS over_due_fr
    //                         FROM helpdesk.ticket_fr_time_team_histories fth
    //                         JOIN helpdesk.teams t ON t.id = fth.team_id
    //                         JOIN helpdesk.departments d ON d.id = t.department_id
    //                         WHERE fth.fr_response_status = 2 AND DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                         GROUP BY d.department_name
    //             ");
    //         }

    //         // Map the over_due_fr counts by team_id for easy access
    //         $overDueMapFr = [];
    //         foreach ($overDueCountsFr as $overDueData) {
    //             $overDueMapFr[$overDueData->department_name] = $overDueData->over_due_fr;
    //         }

    //         // Step 13: Merge the overdue counts into the final averages data
    //         foreach ($averagesByTeam as $department_name => $teamData) {
    //             if (isset($overDueMapFr[$department_name])) {
    //                 $averagesByTeam[$department_name]['over_due_fr'] = $overDueMapFr[$department_name];
    //             } else {
    //                 $averagesByTeam[$department_name]['over_due_fr'] = 0; // Default to 0 if no overdue count exists
    //             }
    //         }
    //         $finalOutput = array_values($averagesByTeam);

    //         // Get current date and 30 days ago date
    //         $currentDate = date('Y-m-d');
    //         $lastMonthDate = date('Y-m-d', strtotime('-30 days'));

    //         // Step 14: Add date range to each team
    //         foreach ($averagesByTeam as $department_name => &$teamData) { // Use reference to modify array
    //             $teamData['current_date'] = $currentDate;
    //             $teamData['last_month_date'] = $lastMonthDate;
    //         }

    //         // Step 15: Final output
    //         $finalOutput = array_values($averagesByTeam);


    //         if ((empty($fromDate)) && (empty($toDate))) {

    //             // $cacheKeyTicketsBydept = 'ticket_count_by_dept';

    //             // $ticketCounts = Cache::get($cacheKeyTicketsBydept);

    //             // $cacheKeyTicketsBydept = 'ticket_count_by_dept';

    //             // // Log cache retrieval attempt
    //             // Log::info("Attempting to retrieve cache for key: {$cacheKeyTicketsBydept}");

    //             // $ticketCounts = Cache::get($cacheKeyTicketsBydept);

    //             // if ($ticketCounts) {
    //             //     Log::info("Cache hit for key: {$cacheKeyTicketsBydept}");
    //             //     return response()->json($ticketCounts);
    //             // }
    //             // Log::info("Cache miss for key: {$cacheKeyTicketsBydept}. Fetching from database...");

    //             $ticketCounts = DB::select("SELECT c1.id AS department_id,c1.department_name AS department_name,total_created,total_resolved,
    //                 ticket_closed_by_team,ticket_open_by_team
    //                 FROM helpdesk.departments c1
    //                 LEFT JOIN (SELECT 
    //                         te.department_id,
    //                         COUNT(DISTINCT t.ticket_number) AS total_created,
    //                         COUNT(DISTINCT CASE WHEN t.status_id = 2 THEN t.ticket_number END) AS total_resolved
    //                     FROM helpdesk.tickets t
    //                     LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                     LEFT JOIN helpdesk.teams te ON utm.team_id = te.id
    //                     GROUP BY te.department_id
    //                 ) AS ticket_stats ON c1.id = ticket_stats.department_id
    //                 LEFT JOIN (SELECT 
    //                         te.department_id,
    //                         COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
    //                         COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team
    //                     FROM helpdesk.tickets th2
    //                     LEFT JOIN helpdesk.teams te ON th2.team_id = te.id
    //                     WHERE th2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                     GROUP BY te.department_id
    //                 ) AS ticket_statuses ON c1.id = ticket_statuses.department_id
    //             ORDER BY c1.department_name");

    //             // Cache::put($cacheKeyTicketsBydept, $ticketCounts, now()->addMinutes(60));

    //             // Log::info("Stored new data in cache with key: {$cacheKeyTicketsBydept}");

    //         } else {

    //             // $cacheKeyTicketsBydept = 'ticket_count_by_dept';

    //             // $ticketCounts = Cache::get($cacheKeyTicketsBydept);

    //             $ticketCounts = DB::select("SELECT c1.id AS department_id,c1.department_name AS department_name,total_created,total_resolved,
    //                 ticket_closed_by_team,ticket_open_by_team
    //                 FROM helpdesk.departments c1
    //                 LEFT JOIN (SELECT 
    //                         te.department_id,
    //                         COUNT(DISTINCT t.ticket_number) AS total_created,
    //                         COUNT(DISTINCT CASE WHEN t.status_id = 2 THEN t.ticket_number END) AS total_resolved
    //                     FROM helpdesk.tickets t
    //                     LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                     LEFT JOIN helpdesk.teams te ON utm.team_id = te.id
    //                     GROUP BY te.department_id
    //                 ) AS ticket_stats ON c1.id = ticket_stats.department_id
    //                 LEFT JOIN (SELECT 
    //                         te.department_id,
    //                         COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
    //                         COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team
    //                     FROM helpdesk.tickets th2
    //                     LEFT JOIN helpdesk.teams te ON th2.team_id = te.id
    //                     WHERE DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                     GROUP BY te.department_id
    //                 ) AS ticket_statuses ON c1.id = ticket_statuses.department_id
    //             ORDER BY c1.department_name");

    //             // Cache::put($cacheKeyTicketsBydept, $ticketCounts, now()->addMinutes(60));
    //         }

    //         foreach ($ticketCounts as $ticketData) {
    //             $department_name = $ticketData->department_name;

    //             // Initialize department entry in $averagesByTeam if not already set
    //             if (!isset($averagesByTeam[$department_name])) {
    //                 $averagesByTeam[$department_name] = [
    //                     'department_name' => $department_name,
    //                     'total_time' => '00:00:00', // Default value
    //                     'average_time' => '0d 0h 0m', // Default value
    //                     'over_due' => 0, // Default value
    //                     'over_due_fr' => 0, // Default value
    //                     'current_date' => date('Y-m-d'), // Current date
    //                     'last_month_date' => date('Y-m-d', strtotime('-1 month')), // Last month's date
    //                     'average_srv_time' => '0d 0h 0m', // Default value
    //                     'total_created' => 0,
    //                     'total_resolved' => 0,
    //                     'ticket_closed_by_team' => 0, // Default value
    //                     'ticket_open_by_team' => 0 // Default value
    //                 ];
    //             }

    //             // Update ticket counts for the department

    //             $averagesByTeam[$department_name]['total_created'] = $ticketData->total_created ?? 0;
    //             $averagesByTeam[$department_name]['total_resolved'] = $ticketData->total_resolved ?? 0;
    //             $averagesByTeam[$department_name]['ticket_closed_by_team'] = $ticketData->ticket_closed_by_team ?? 0;
    //             $averagesByTeam[$department_name]['ticket_open_by_team'] = $ticketData->ticket_open_by_team ?? 0;

    //             // Update average_srv_time if it exists in $averagesBySrvTeam
    //             if (isset($averagesBySrvTeam[$department_name]['average_srv_time'])) {
    //                 $averagesByTeam[$department_name]['average_srv_time'] = $averagesBySrvTeam[$department_name]['average_srv_time'];
    //             }
    //         }
    //         $filteredAverages = array_filter($averagesByTeam, function ($teamData) {
    //             return $teamData['ticket_closed_by_team'] > 0 || $teamData['ticket_open_by_team'] > 0 || $teamData['total_created'] > 0 || $teamData['total_resolved'] > 0;
    //         });

    //         // $finalOutput = array_values($averagesByTeam);
    //         $finalOutput = array_values($filteredAverages);

    //         // if ((empty($fromDate)) && (empty($toDate))) {

    //         //     Cache::put($cacheKey, $finalOutput, now()->addMinutes(60));
    //         //     Log::info("Cache miss for key: {$cacheKey}. Stored new data in cache.");
    //         // }

    //         // Log the cache storage for debugging


    //         return ApiResponse::success($finalOutput, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Failed", 500);
    //     }
    // }

    

    public function departmentReportForDashboard(Request $request)
    {
        try {
            $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
            $toDate   = !empty($request->toDate)   ? (new DateTime($request->toDate))->format('Y-m-d')   : "";

            $isDefaultRange = empty($fromDate) && empty($toDate);

            // ─── Date conditions with correct aliases ─────────────────────────────
            $s2DateCond = $isDefaultRange
                ? "s2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(s2.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $fthDateCond = $isDefaultRange
                ? "fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $tshDateCond = $isDefaultRange
                ? "tsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(tsh.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $at1DateCond = $isDefaultRange
                ? "at1.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(at1.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $at2DateCond = $isDefaultRange
                ? "at2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(at2.created_at) BETWEEN '$fromDate' AND '$toDate'";

            // ─── FR SLA averages ──────────────────────────────────────────────────
            $frAverages = DB::select("
                SELECT
                    d.department_name,
                    SUM(ABS(TIMESTAMPDIFF(SECOND, s2.created_at, s1.created_at))) AS total_time_in_seconds,
                    COUNT(*) AS ticket_count
                FROM helpdesk.first_res_sla_histories s2
                JOIN helpdesk.first_res_sla_histories s1
                    ON  s1.ticket_number       = s2.ticket_number
                    AND s1.first_res_config_id = s2.first_res_config_id
                    AND s1.sla_status          IN (0, 1)
                JOIN helpdesk.first_res_configs frc ON s2.first_res_config_id = frc.id
                JOIN helpdesk.teams t ON t.id = frc.team_id
                JOIN helpdesk.departments d ON d.id = t.department_id
                WHERE s2.sla_status = 2
                AND $s2DateCond
                GROUP BY d.department_name
            ");

            $averagesByTeam = [];
            foreach ($frAverages as $row) {
                $avg = $row->total_time_in_seconds / $row->ticket_count;
                $averagesByTeam[$row->department_name] = [
                    'department_name'  => $row->department_name,
                    'total_time'       => formatTime($row->total_time_in_seconds),
                    'average_time'     => formatTime($avg),
                    'over_due'         => 0,
                    'over_due_fr'      => 0,
                    'average_srv_time' => '0h 0m 0d',
                ];
            }

            // ─── SRV SLA averages ─────────────────────────────────────────────────
            $srvAverages = DB::select("
                SELECT
                    d.department_name,
                    SUM(ABS(TIMESTAMPDIFF(SECOND, s2.created_at, s1.created_at))) AS total_time_in_seconds,
                    COUNT(*) AS ticket_count
                FROM helpdesk.srv_time_subcat_sla_histories s2
                JOIN helpdesk.srv_time_subcat_sla_histories s1
                    ON  s1.ticket_number        = s2.ticket_number
                    AND s1.sla_subcat_config_id = s2.sla_subcat_config_id
                    AND s1.sla_status           IN (0, 1)
                JOIN helpdesk.sla_subcat_configs ssc ON s2.sla_subcat_config_id = ssc.id
                JOIN helpdesk.teams t ON t.id = ssc.team_id
                JOIN helpdesk.departments d ON d.id = t.department_id
                WHERE s2.sla_status = 2
                AND $s2DateCond
                GROUP BY d.department_name
            ");

            $averagesBySrvTeam = [];
            foreach ($srvAverages as $row) {
                $avg = $row->total_time_in_seconds / $row->ticket_count;
                $averagesBySrvTeam[$row->department_name] = [
                    'total_time'       => formatTime($row->total_time_in_seconds),
                    'average_srv_time' => formatTime($avg),
                ];
            }

            // ─── Overdue SRV ──────────────────────────────────────────────────────
            $overDueCounts = DB::select("
                SELECT d.department_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
                FROM helpdesk.srv_time_subcat_sla_histories tsh
                JOIN helpdesk.sla_subcat_configs ssc ON tsh.sla_subcat_config_id = ssc.id
                JOIN helpdesk.teams t ON t.id = ssc.team_id
                JOIN helpdesk.departments d ON d.id = t.department_id
                LEFT JOIN helpdesk.open_tickets ot  ON ot.ticket_number = tsh.ticket_number
                LEFT JOIN helpdesk.close_tickets ct ON ct.ticket_number = tsh.ticket_number
                WHERE tsh.sla_status = 0
                AND $tshDateCond
                AND (ot.ticket_number IS NOT NULL OR ct.ticket_number IS NOT NULL)
                GROUP BY d.department_name
            ");
            $overDueMap = array_column($overDueCounts, 'over_due', 'department_name');

            // ─── Overdue FR ───────────────────────────────────────────────────────
            $overDueCountsFr = DB::select("
                SELECT d.department_name, COUNT(DISTINCT fth.ticket_number) AS over_due_fr
                FROM helpdesk.first_res_sla_histories fth
                JOIN helpdesk.first_res_configs frc ON fth.first_res_config_id = frc.id
                JOIN helpdesk.teams t ON t.id = frc.team_id
                JOIN helpdesk.departments d ON d.id = t.department_id
                LEFT JOIN helpdesk.open_tickets ot  ON ot.ticket_number = fth.ticket_number
                LEFT JOIN helpdesk.close_tickets ct ON ct.ticket_number = fth.ticket_number
                WHERE fth.sla_status = 0
                AND $fthDateCond
                AND (ot.ticket_number IS NOT NULL OR ct.ticket_number IS NOT NULL)
                GROUP BY d.department_name
            ");
            $overDueFrMap = array_column($overDueCountsFr, 'over_due_fr', 'department_name');

            // ─── Ticket counts ────────────────────────────────────────────────────
            $ticketCounts = DB::select("
                WITH AllTickets AS (
                    SELECT * FROM helpdesk.open_tickets
                    UNION ALL
                    SELECT * FROM helpdesk.close_tickets
                )
                SELECT
                    c1.id              AS department_id,
                    c1.department_name AS department_name,
                    COALESCE(ticket_stats.total_created, 0)                        AS total_created,
                    COALESCE(ticket_stats.total_resolved, 0)                       AS total_resolved,
                    COALESCE(ticket_statuses.ticket_closed_by_team, 0)             AS ticket_closed_by_team,
                    COALESCE(ticket_statuses.ticket_open_by_team, 0)               AS ticket_open_by_team
                FROM helpdesk.departments c1
                -- Total Created & Resolved
                LEFT JOIN (
                    SELECT
                        te.department_id,
                        COUNT(DISTINCT at1.ticket_number)                                       AS total_created,
                        COUNT(DISTINCT CASE WHEN at1.status_id = 2 THEN at1.ticket_number END) AS total_resolved
                    FROM AllTickets at1
                    LEFT JOIN helpdesk.user_team_mappings utm ON at1.user_id = utm.user_id
                    LEFT JOIN helpdesk.teams te ON utm.team_id = te.id
                    WHERE $at1DateCond
                    GROUP BY te.department_id
                ) AS ticket_stats ON c1.id = ticket_stats.department_id
                -- Open & Closed counts
                LEFT JOIN (
                    SELECT
                        te.department_id,
                        COUNT(DISTINCT CASE WHEN at2.status_id = 6  THEN at2.ticket_number END) AS ticket_closed_by_team,
                        COUNT(DISTINCT CASE WHEN at2.status_id != 6 THEN at2.ticket_number END) AS ticket_open_by_team
                    FROM AllTickets at2
                    LEFT JOIN helpdesk.teams te ON at2.team_id = te.id
                    WHERE $at2DateCond
                    GROUP BY te.department_id
                ) AS ticket_statuses ON c1.id = ticket_statuses.department_id
                ORDER BY c1.department_name
            ");

            // ─── Merge everything ─────────────────────────────────────────────────
            $currentDate   = date('Y-m-d');
            $lastMonthDate = date('Y-m-d', strtotime('-30 days'));

            foreach ($ticketCounts as $ticketData) {
                $department_name = $ticketData->department_name;

                if (!isset($averagesByTeam[$department_name])) {
                    $averagesByTeam[$department_name] = [
                        'department_name'       => $department_name,
                        'total_time'            => '00:00:00',
                        'average_time'          => '0d 0h 0m',
                        'over_due'              => 0,
                        'over_due_fr'           => 0,
                        'current_date'          => $currentDate,
                        'last_month_date'       => $lastMonthDate,
                        'average_srv_time'      => '0d 0h 0m',
                        'total_created'         => 0,
                        'total_resolved'        => 0,
                        'ticket_closed_by_team' => 0,
                        'ticket_open_by_team'   => 0,
                    ];
                }

                $averagesByTeam[$department_name] = array_merge($averagesByTeam[$department_name], [
                    'department_name'       => $ticketData->department_name,
                    'total_created'         => $ticketData->total_created         ?? 0,
                    'total_resolved'        => $ticketData->total_resolved        ?? 0,
                    'ticket_closed_by_team' => $ticketData->ticket_closed_by_team ?? 0,
                    'ticket_open_by_team'   => $ticketData->ticket_open_by_team   ?? 0,
                    'current_date'          => $currentDate,
                    'last_month_date'       => $lastMonthDate,
                    'over_due'              => $overDueMap[$department_name]   ?? 0,
                    'over_due_fr'           => $overDueFrMap[$department_name] ?? 0,
                    'average_srv_time'      => $averagesBySrvTeam[$department_name]['average_srv_time'] ?? '0d 0h 0m',
                ]);
            }

            // ─── Filter & return ──────────────────────────────────────────────────
            $finalOutput = array_values(array_filter($averagesByTeam, fn($d) =>
                $d['ticket_closed_by_team'] > 0 ||
                $d['ticket_open_by_team']   > 0 ||
                $d['total_created']         > 0 ||
                $d['total_resolved']        > 0
            ));

            return ApiResponse::success($finalOutput, "Success", 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }

    // public function divisionReportForDashboard(Request $request)
    // {
    //     try {
    //         $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //         $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";


    //         if ((empty($fromDate)) && (empty($toDate))) {

    //             $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth
    //             WHERE fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");

    //             $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth
    //             WHERE sth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
    //         } else {

    //             $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth WHERE DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'");

    //             $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth WHERE DATE(sth.created_at) BETWEEN '$fromDate' AND '$toDate'");
    //         }

    //         $ListOfTicket = array_map(fn($item) => $item->ticket_number, $fr_teamTicket);

    //         $ListOfTimeDifferences = [];

    //         // Step 2: Compute time differences for each team and ticket
    //         foreach ($ListOfTicket as $ticket_number) {
    //             $fr_teamID = DB::select("SELECT DISTINCT(fth.team_id), t.team_name, d.division_name 
    //                 FROM helpdesk.ticket_fr_time_team_histories fth 
    //                 JOIN helpdesk.teams t ON fth.team_id = t.id
    //                 JOIN helpdesk.divisions d ON d.id = t.division_id
    //                 WHERE fth.ticket_number = ?
    //             ", [$ticket_number]);

    //             // dd($fr_teamID);

    //             foreach ($fr_teamID as $team) {
    //                 $team_id = $team->team_id;
    //                 // $team_name = $team->team_name;
    //                 $division_name = $team->division_name;

    //                 $fr_statusRecords = DB::select("SELECT fth.fr_response_status, fth.created_at 
    //                     FROM helpdesk.ticket_fr_time_team_histories fth
    //                     WHERE fth.ticket_number = ? AND fth.team_id = ? 
    //                     AND fth.fr_response_status IN (0, 1, 2) 
    //                     ORDER BY fth.created_at ASC
    //                 ", [$ticket_number, $team_id]);

    //                 $timestamps = [
    //                     'status_0' => null,
    //                     'status_1_or_2' => null
    //                 ];

    //                 foreach ($fr_statusRecords as $record) {
    //                     if ($record->fr_response_status == 0) {
    //                         $timestamps['status_0'] = $record->created_at;
    //                     } elseif (in_array($record->fr_response_status, [1, 2])) {
    //                         $timestamps['status_1_or_2'] = $record->created_at;
    //                     }
    //                 }

    //                 if ($timestamps['status_0'] && $timestamps['status_1_or_2']) {
    //                     $startTime = new DateTime($timestamps['status_0']);
    //                     $endTime = new DateTime($timestamps['status_1_or_2']);
    //                     $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

    //                     $ListOfTimeDifferences[] = [
    //                         // 'team_id' => $team_id,
    //                         // 'team_name' => $team_name,
    //                         'division_name' => $division_name,
    //                         'time_difference_in_seconds' => $timeDifferenceInSeconds
    //                     ];
    //                 }
    //             }

    //             // dd($ListOfTimeDifferences);
    //         }

    //         // Step 3: Summing time and calculating the average per team
    //         $timeSumsByTeam = [];
    //         foreach ($ListOfTimeDifferences as $entry) {
    //             // $team_id = $entry['team_id'];
    //             // $team_name = $entry['team_name'];
    //             $division_name = $entry['division_name'];
    //             $time_difference = $entry['time_difference_in_seconds'];

    //             if (!isset($timeSumsByTeam[$division_name])) {
    //                 $timeSumsByTeam[$division_name] = [
    //                     // 'team_name' => $team_name,
    //                     'division_name' => $division_name,
    //                     'total_time_in_seconds' => 0,
    //                     'count' => 0
    //                 ];
    //             }

    //             $timeSumsByTeam[$division_name]['total_time_in_seconds'] += $time_difference;
    //             $timeSumsByTeam[$division_name]['count']++;
    //         }



    //         $averagesByTeam = [];
    //         foreach ($timeSumsByTeam as $division_name => $data) {
    //             $totalTime = $data['total_time_in_seconds'];
    //             $count = $data['count'];
    //             $averageTime = $totalTime / $count;

    //             $averagesByTeam[$division_name] = [
    //                 // 'team_id' => $team_id,
    //                 // 'team_name' => $data['team_name'],
    //                 // 'division_name' => $data['division_name'],
    //                 'total_time' => formatTime($totalTime),
    //                 'average_time' => formatTime($averageTime)
    //             ];
    //         }


    //         // Step 5: Fetch distinct SRV ticket numbers
    //         // $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth");

    //         $ListOfSrvTicket = array_map(fn($item) => $item->ticket_number, $srv_teamTicket);

    //         $ListOfSrvTimeDifferences = [];

    //         // Step 6: Compute time differences for each team and SRV ticket
    //         foreach ($ListOfSrvTicket as $ticket_number) {
    //             $srv_teamID = DB::select("SELECT DISTINCT(sth.team_id), t.team_name, d.division_name
    //                 FROM helpdesk.ticket_srv_time_team_histories sth 
    //                 JOIN helpdesk.teams t ON sth.team_id = t.id
    //                 JOIN helpdesk.divisions d ON d.id = t.division_id
    //                 WHERE sth.ticket_number = ?
    //             ", [$ticket_number]);

    //             foreach ($srv_teamID as $team) {
    //                 $team_id = $team->team_id;
    //                 // $team_name = $team->team_name;
    //                 $division_name = $team->division_name;

    //                 $srv_statusRecords = DB::select("SELECT sth.srv_time_status, sth.created_at 
    //                     FROM helpdesk.ticket_srv_time_team_histories sth
    //                     WHERE sth.ticket_number = ? AND sth.team_id = ? 
    //                     AND sth.srv_time_status IN (0, 1, 2) 
    //                     ORDER BY sth.created_at ASC
    //                 ", [$ticket_number, $team_id]);

    //                 $srvTimestamps = [
    //                     'status_0' => null,
    //                     'status_1_or_2' => null
    //                 ];

    //                 foreach ($srv_statusRecords as $record) {
    //                     if ($record->srv_time_status == 0) {
    //                         $srvTimestamps['status_0'] = $record->created_at;
    //                     } elseif (in_array($record->srv_time_status, [1, 2])) {
    //                         $srvTimestamps['status_1_or_2'] = $record->created_at;
    //                     }
    //                 }

    //                 if ($srvTimestamps['status_0'] && $srvTimestamps['status_1_or_2']) {
    //                     $startTime = new DateTime($srvTimestamps['status_0']);
    //                     $endTime = new DateTime($srvTimestamps['status_1_or_2']);
    //                     $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

    //                     $ListOfSrvTimeDifferences[] = [
    //                         // 'team_id' => $team_id,
    //                         // 'team_name' => $team_name,
    //                         'division_name' => $division_name,
    //                         'time_difference_in_seconds' => $timeDifferenceInSeconds
    //                     ];
    //                 }
    //             }
    //         }

    //         // Step 7: Summing time and calculating the average per SRV team
    //         $timeSumsByTeamSrv = [];
    //         foreach ($ListOfSrvTimeDifferences as $entry) {
    //             // $team_id = $entry['team_id'];
    //             // $team_name = $entry['team_name'];
    //             $division_name = $entry['division_name'];
    //             $time_difference = $entry['time_difference_in_seconds'];

    //             if (!isset($timeSumsByTeamSrv[$division_name])) {
    //                 $timeSumsByTeamSrv[$division_name] = [
    //                     // 'team_name' => $team_name,
    //                     'division_name' => $division_name,
    //                     'total_time_in_seconds' => 0,
    //                     'count' => 0
    //                 ];
    //             }

    //             $timeSumsByTeamSrv[$division_name]['total_time_in_seconds'] += $time_difference;
    //             $timeSumsByTeamSrv[$division_name]['count']++;
    //         }

    //         // Step 8: Calculate average times for SRV teams
    //         $averagesBySrvTeam = [];
    //         foreach ($timeSumsByTeamSrv as $division_name => $data) {
    //             $totalTime = $data['total_time_in_seconds'];
    //             $count = $data['count'];
    //             $averageSrvTime = $totalTime / $count;

    //             $averagesBySrvTeam[$division_name] = [
    //                 // 'team_id' => $team_id,
    //                 // 'team_name' => $data['team_name'],
    //                 // 'division_name' => $data['division_name'],
    //                 'total_time' => formatTime($totalTime),
    //                 'average_srv_time' => formatTime($averageSrvTime)
    //             ];
    //         }



    //         if ((empty($fromDate)) && (empty($toDate))) {
    //             $overDueCounts = DB::select("SELECT d.division_name as division_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
    //                         FROM helpdesk.ticket_srv_time_team_histories tsh
    //                         JOIN helpdesk.teams t ON t.id = tsh.team_id
    //                         JOIN helpdesk.divisions d ON d.id = t.division_id
    //                         WHERE tsh.srv_time_status = 2 AND tsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                         GROUP BY d.division_name
    //                 ");
    //         } else {
    //             $overDueCounts = DB::select("SELECT d.division_name as division_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
    //                         FROM helpdesk.ticket_srv_time_team_histories tsh
    //                         JOIN helpdesk.teams t ON t.id = tsh.team_id
    //                                     JOIN helpdesk.divisions d ON d.id = t.division_id
    //                         WHERE tsh.srv_time_status = 2 AND DATE(tsh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                         GROUP BY d.division_name
    //             ");
    //         }

    //         // Map the over_due counts by team_id for easy access
    //         $overDueMap = [];
    //         foreach ($overDueCounts as $overDueData) {
    //             $overDueMap[$overDueData->division_name] = $overDueData->over_due;
    //         }

    //         // Step 13: Merge the overdue counts into the final averages data
    //         foreach ($averagesByTeam as $division_name => $teamData) {
    //             if (isset($overDueMap[$division_name])) {
    //                 $averagesByTeam[$division_name]['over_due'] = $overDueMap[$division_name];
    //             } else {
    //                 $averagesByTeam[$division_name]['over_due'] = 0; // Default to 0 if no overdue count exists
    //             }
    //         }

    //         // Step 14: Final output
    //         $finalOutput = array_values($averagesByTeam);



    //         if ((empty($fromDate)) && (empty($toDate))) {
    //             $overDueCountsFr = DB::select("SELECT d.division_name as division_name, COUNT(DISTINCT fth.ticket_number) AS over_due_fr
    //                             FROM helpdesk.ticket_fr_time_team_histories fth
    //                             JOIN helpdesk.teams t ON t.id = fth.team_id
    //                             JOIN helpdesk.divisions d ON d.id = t.division_id
    //                             WHERE fth.fr_response_status = 2 AND fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY d.division_name
    //                     ");
    //         } else {
    //             $overDueCountsFr = DB::select("SELECT d.division_name as division_name, COUNT(DISTINCT fth.ticket_number) AS over_due_fr
    //                             FROM helpdesk.ticket_fr_time_team_histories fth
    //                             JOIN helpdesk.teams t ON t.id = fth.team_id
    //                             JOIN helpdesk.divisions d ON d.id = t.division_id
    //                             WHERE fth.fr_response_status = 2 AND DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate' 
    //                             GROUP BY d.division_name
    //                 ");
    //         }

    //         // Map the over_due counts by team_id for easy access
    //         $overDueMap = [];
    //         foreach ($overDueCountsFr as $overDueData) {
    //             $overDueMap[$overDueData->division_name] = $overDueData->over_due_fr;
    //         }

    //         // Step 13: Merge the overdue counts into the final averages data
    //         foreach ($averagesByTeam as $division_name => $teamData) {
    //             if (isset($overDueMap[$division_name])) {
    //                 $averagesByTeam[$division_name]['over_due_fr'] = $overDueMap[$division_name];
    //             } else {
    //                 $averagesByTeam[$division_name]['over_due_fr'] = 0; // Default to 0 if no overdue count exists
    //             }
    //         }

    //         // Step 14: Final output
    //         $finalOutput = array_values($averagesByTeam);



    //         if ((empty($fromDate)) && (empty($toDate))) {




    //             $ticketCounts = DB::select("SELECT c1.id AS division_id, c1.division_name AS division_name, total_created,
    //                                 total_resolved,ticket_closed_by_team,ticket_open_by_team
    //                             FROM helpdesk.divisions c1
    //                             LEFT JOIN (SELECT 
    //                                     te.division_id,
    //                                     COUNT(DISTINCT t.ticket_number) AS total_created,
    //                                     COUNT(DISTINCT CASE WHEN t.status_id = 2 THEN t.ticket_number END) AS total_resolved
    //                                 FROM helpdesk.tickets t
    //                                 LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                                 LEFT JOIN helpdesk.teams te ON utm.team_id = te.id
    //                                 GROUP BY te.division_id
    //                             ) AS ticket_stats ON c1.id = ticket_stats.division_id
    //                             LEFT JOIN (SELECT 
    //                                     te.division_id,
    //                                     COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
    //                                     COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team
    //                                 FROM helpdesk.tickets th2
    //                                 LEFT JOIN helpdesk.teams te ON th2.team_id = te.id
    //                                 WHERE th2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                                 GROUP BY te.division_id
    //                             ) AS ticket_statuses ON c1.id = ticket_statuses.division_id
    //                             ORDER BY c1.division_name");



    //         } else {



    //             $ticketCounts = DB::select("SELECT c1.id AS division_id, c1.division_name AS division_name, total_created,
    //                                                     total_resolved,ticket_closed_by_team,ticket_open_by_team
    //                                                 FROM helpdesk.divisions c1
    //                                                 LEFT JOIN (SELECT 
    //                                                         te.division_id,
    //                                                         COUNT(DISTINCT t.ticket_number) AS total_created,
    //                                                         COUNT(DISTINCT CASE WHEN t.status_id = 2 THEN t.ticket_number END) AS total_resolved
    //                                                     FROM helpdesk.tickets t
    //                                                     LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                                                     LEFT JOIN helpdesk.teams te ON utm.team_id = te.id
    //                                                     GROUP BY te.division_id
    //                                                 ) AS ticket_stats ON c1.id = ticket_stats.division_id
    //                                                 LEFT JOIN (SELECT 
    //                                                         te.division_id,
    //                                                         COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
    //                                                         COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team
    //                                                     FROM helpdesk.tickets th2
    //                                                     LEFT JOIN helpdesk.teams te ON th2.team_id = te.id
    //                                                     WHERE DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                                                     GROUP BY te.division_id
    //                                                 ) AS ticket_statuses ON c1.id = ticket_statuses.division_id
    //                                                 ORDER BY c1.division_name");
    //         }



    //         foreach ($ticketCounts as $ticketData) {
    //             $division_name = $ticketData->division_name;

    //             // Initialize department entry in $averagesByTeam if not already set
    //             if (!isset($averagesByTeam[$division_name])) {
    //                 $averagesByTeam[$division_name] = [
    //                     'division_name' => $division_name,
    //                     'total_time' => '00:00:00', // Default value
    //                     'average_time' => '0d 0h 0m', // Default value
    //                     'over_due' => 0, // Default value
    //                     'over_due_fr' => 0, // Default value
    //                     'current_date' => date('Y-m-d'), // Current date
    //                     'last_month_date' => date('Y-m-d', strtotime('-1 month')), // Last month's date
    //                     'average_srv_time' => '0d 0h 0m', // Default value
    //                     'total_created' => 0,
    //                     'total_resolved' => 0,
    //                     'ticket_closed_by_team' => 0, // Default value
    //                     'ticket_open_by_team' => 0 // Default value
    //                 ];
    //             }

    //             // Update ticket counts for the department
    //             $averagesByTeam[$division_name]['division_name'] = $ticketData->division_name ?? null;

    //             $averagesByTeam[$division_name]['total_created'] = $ticketData->total_created ?? 0;
    //             $averagesByTeam[$division_name]['total_resolved'] = $ticketData->total_resolved ?? 0;
    //             $averagesByTeam[$division_name]['ticket_closed_by_team'] = $ticketData->ticket_closed_by_team ?? 0;
    //             $averagesByTeam[$division_name]['ticket_open_by_team'] = $ticketData->ticket_open_by_team ?? 0;

    //             // Update average_srv_time if it exists in $averagesBySrvTeam
    //             if (isset($averagesBySrvTeam[$division_name]['average_srv_time'])) {
    //                 $averagesByTeam[$division_name]['average_srv_time'] = $averagesBySrvTeam[$division_name]['average_srv_time'];
    //             }
    //         }

    //         // Filter out departments where both ticket counts are 0
    //         $filteredAverages = array_filter($averagesByTeam, function ($teamData) {
    //             return $teamData['ticket_closed_by_team'] > 0 || $teamData['ticket_open_by_team'] > 0 || $teamData['total_created'] > 0 || $teamData['total_resolved'] > 0;
    //         });

    //         // $finalOutput = array_values($averagesByTeam);
    //         $finalOutput = array_values($filteredAverages);



    //         return ApiResponse::success($finalOutput, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Failed", 500);
    //     }
    // }

    public function divisionReportForDashboard(Request $request)
    {
        try {
            $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
            $toDate   = !empty($request->toDate)   ? (new DateTime($request->toDate))->format('Y-m-d')   : "";

            $isDefaultRange = empty($fromDate) && empty($toDate);

            // ─── Date conditions with correct aliases ─────────────────────────────
            $s2DateCond = $isDefaultRange
                ? "s2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(s2.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $fthDateCond = $isDefaultRange
                ? "fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $tshDateCond = $isDefaultRange
                ? "tsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(tsh.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $at1DateCond = $isDefaultRange
                ? "at1.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(at1.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $at2DateCond = $isDefaultRange
                ? "at2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(at2.created_at) BETWEEN '$fromDate' AND '$toDate'";

            // ─── FR SLA averages ──────────────────────────────────────────────────
            $frAverages = DB::select("
                SELECT
                    d.division_name,
                    SUM(ABS(TIMESTAMPDIFF(SECOND, s2.created_at, s1.created_at))) AS total_time_in_seconds,
                    COUNT(*) AS ticket_count
                FROM helpdesk.first_res_sla_histories s2
                JOIN helpdesk.first_res_sla_histories s1
                    ON  s1.ticket_number       = s2.ticket_number
                    AND s1.first_res_config_id = s2.first_res_config_id
                    AND s1.sla_status          IN (0, 1)
                JOIN helpdesk.first_res_configs frc ON s2.first_res_config_id = frc.id
                JOIN helpdesk.teams t ON t.id = frc.team_id
                JOIN helpdesk.divisions d ON d.id = t.division_id
                WHERE s2.sla_status = 2
                AND $s2DateCond
                GROUP BY d.division_name
            ");

            $averagesByTeam = [];
            foreach ($frAverages as $row) {
                $avg = $row->total_time_in_seconds / $row->ticket_count;
                $averagesByTeam[$row->division_name] = [
                    'division_name'    => $row->division_name,
                    'total_time'       => formatTime($row->total_time_in_seconds),
                    'average_time'     => formatTime($avg),
                    'over_due'         => 0,
                    'over_due_fr'      => 0,
                    'average_srv_time' => '0h 0m 0d',
                ];
            }

            // ─── SRV SLA averages ─────────────────────────────────────────────────
            $srvAverages = DB::select("
                SELECT
                    d.division_name,
                    SUM(ABS(TIMESTAMPDIFF(SECOND, s2.created_at, s1.created_at))) AS total_time_in_seconds,
                    COUNT(*) AS ticket_count
                FROM helpdesk.srv_time_subcat_sla_histories s2
                JOIN helpdesk.srv_time_subcat_sla_histories s1
                    ON  s1.ticket_number        = s2.ticket_number
                    AND s1.sla_subcat_config_id = s2.sla_subcat_config_id
                    AND s1.sla_status           IN (0, 1)
                JOIN helpdesk.sla_subcat_configs ssc ON s2.sla_subcat_config_id = ssc.id
                JOIN helpdesk.teams t ON t.id = ssc.team_id
                JOIN helpdesk.divisions d ON d.id = t.division_id
                WHERE s2.sla_status = 2
                AND $s2DateCond
                GROUP BY d.division_name
            ");

            $averagesBySrvTeam = [];
            foreach ($srvAverages as $row) {
                $avg = $row->total_time_in_seconds / $row->ticket_count;
                $averagesBySrvTeam[$row->division_name] = [
                    'total_time'       => formatTime($row->total_time_in_seconds),
                    'average_srv_time' => formatTime($avg),
                ];
            }

            // ─── Overdue SRV ──────────────────────────────────────────────────────
            $overDueCounts = DB::select("
                SELECT d.division_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
                FROM helpdesk.srv_time_subcat_sla_histories tsh
                JOIN helpdesk.sla_subcat_configs ssc ON tsh.sla_subcat_config_id = ssc.id
                JOIN helpdesk.teams t ON t.id = ssc.team_id
                JOIN helpdesk.divisions d ON d.id = t.division_id
                LEFT JOIN helpdesk.open_tickets ot  ON ot.ticket_number = tsh.ticket_number
                LEFT JOIN helpdesk.close_tickets ct ON ct.ticket_number = tsh.ticket_number
                WHERE tsh.sla_status = 0
                AND $tshDateCond
                AND (ot.ticket_number IS NOT NULL OR ct.ticket_number IS NOT NULL)
                GROUP BY d.division_name
            ");
            $overDueMap = array_column($overDueCounts, 'over_due', 'division_name');

            // ─── Overdue FR ───────────────────────────────────────────────────────
            $overDueCountsFr = DB::select("
                SELECT d.division_name, COUNT(DISTINCT fth.ticket_number) AS over_due_fr
                FROM helpdesk.first_res_sla_histories fth
                JOIN helpdesk.first_res_configs frc ON fth.first_res_config_id = frc.id
                JOIN helpdesk.teams t ON t.id = frc.team_id
                JOIN helpdesk.divisions d ON d.id = t.division_id
                LEFT JOIN helpdesk.open_tickets ot  ON ot.ticket_number = fth.ticket_number
                LEFT JOIN helpdesk.close_tickets ct ON ct.ticket_number = fth.ticket_number
                WHERE fth.sla_status = 0
                AND $fthDateCond
                AND (ot.ticket_number IS NOT NULL OR ct.ticket_number IS NOT NULL)
                GROUP BY d.division_name
            ");
            $overDueFrMap = array_column($overDueCountsFr, 'over_due_fr', 'division_name');

            // ─── Ticket counts ────────────────────────────────────────────────────
            $ticketCounts = DB::select("
                WITH AllTickets AS (
                    SELECT * FROM helpdesk.open_tickets
                    UNION ALL
                    SELECT * FROM helpdesk.close_tickets
                )
                SELECT
                    c1.id            AS division_id,
                    c1.division_name AS division_name,
                    COALESCE(ticket_stats.total_created, 0)                        AS total_created,
                    COALESCE(ticket_stats.total_resolved, 0)                       AS total_resolved,
                    COALESCE(ticket_statuses.ticket_closed_by_team, 0)             AS ticket_closed_by_team,
                    COALESCE(ticket_statuses.ticket_open_by_team, 0)               AS ticket_open_by_team
                FROM helpdesk.divisions c1
                -- Total Created & Resolved
                LEFT JOIN (
                    SELECT
                        te.division_id,
                        COUNT(DISTINCT at1.ticket_number)                                       AS total_created,
                        COUNT(DISTINCT CASE WHEN at1.status_id = 2 THEN at1.ticket_number END) AS total_resolved
                    FROM AllTickets at1
                    LEFT JOIN helpdesk.user_team_mappings utm ON at1.user_id = utm.user_id
                    LEFT JOIN helpdesk.teams te ON utm.team_id = te.id
                    WHERE $at1DateCond
                    GROUP BY te.division_id
                ) AS ticket_stats ON c1.id = ticket_stats.division_id
                -- Open & Closed counts
                LEFT JOIN (
                    SELECT
                        te.division_id,
                        COUNT(DISTINCT CASE WHEN at2.status_id = 6  THEN at2.ticket_number END) AS ticket_closed_by_team,
                        COUNT(DISTINCT CASE WHEN at2.status_id != 6 THEN at2.ticket_number END) AS ticket_open_by_team
                    FROM AllTickets at2
                    LEFT JOIN helpdesk.teams te ON at2.team_id = te.id
                    WHERE $at2DateCond
                    GROUP BY te.division_id
                ) AS ticket_statuses ON c1.id = ticket_statuses.division_id
                ORDER BY c1.division_name
            ");

            // ─── Merge everything ─────────────────────────────────────────────────
            foreach ($ticketCounts as $ticketData) {
                $division_name = $ticketData->division_name;

                if (!isset($averagesByTeam[$division_name])) {
                    $averagesByTeam[$division_name] = [
                        'division_name'         => $division_name,
                        'total_time'            => '00:00:00',
                        'average_time'          => '0d 0h 0m',
                        'over_due'              => 0,
                        'over_due_fr'           => 0,
                        'current_date'          => date('Y-m-d'),
                        'last_month_date'       => date('Y-m-d', strtotime('-1 month')),
                        'average_srv_time'      => '0d 0h 0m',
                        'total_created'         => 0,
                        'total_resolved'        => 0,
                        'ticket_closed_by_team' => 0,
                        'ticket_open_by_team'   => 0,
                    ];
                }

                $averagesByTeam[$division_name] = array_merge($averagesByTeam[$division_name], [
                    'division_name'         => $ticketData->division_name,
                    'total_created'         => $ticketData->total_created         ?? 0,
                    'total_resolved'        => $ticketData->total_resolved        ?? 0,
                    'ticket_closed_by_team' => $ticketData->ticket_closed_by_team ?? 0,
                    'ticket_open_by_team'   => $ticketData->ticket_open_by_team   ?? 0,
                    'over_due'              => $overDueMap[$division_name]        ?? 0,
                    'over_due_fr'           => $overDueFrMap[$division_name]      ?? 0,
                    'average_srv_time'      => $averagesBySrvTeam[$division_name]['average_srv_time'] ?? '0d 0h 0m',
                ]);
            }

            // ─── Filter & return ──────────────────────────────────────────────────
            $finalOutput = array_values(array_filter($averagesByTeam, fn($d) =>
                $d['ticket_closed_by_team'] > 0 ||
                $d['ticket_open_by_team']   > 0 ||
                $d['total_created']         > 0 ||
                $d['total_resolved']        > 0
            ));

            return ApiResponse::success($finalOutput, "Success", 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }


    // public function teamReportForDashboard(Request $request)
    // {
    //     try {

    //         $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //         $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

    //         // if ((empty($fromDate)) && (empty($toDate))) {
    //         //     $cacheKey = 'team_report_dashboard';

    //         //     $cachedData = Cache::get($cacheKey);

    //         //     if ($cachedData) {
    //         //         Log::info("Cache hit for key: {$cacheKey}");
    //         //         return ApiResponse::success($cachedData, "Success", 200);
    //         //     }
    //         // }


    //         if ((empty($fromDate)) && (empty($toDate))) {

    //             $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth
    //             WHERE fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");

    //             $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth
    //             WHERE sth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
    //         } else {

    //             $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth WHERE DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'");

    //             $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth WHERE DATE(sth.created_at) BETWEEN '$fromDate' AND '$toDate'");
    //         }
    //         $ListOfTicket = array_map(fn($item) => $item->ticket_number, $fr_teamTicket);

    //         $ListOfTimeDifferences = [];

    //         // Step 2: Compute time differences for each team and FR ticket
    //         foreach ($ListOfTicket as $ticket_number) {
    //             $fr_teamID = DB::select("SELECT DISTINCT(fth.team_id), t.team_name
    //                     FROM helpdesk.ticket_fr_time_team_histories fth 
    //                     JOIN helpdesk.teams t ON fth.team_id = t.id
    //                     -- JOIN helpdesk.divisions d ON d.id = t.division_id
    //                     WHERE fth.ticket_number = ?
    //                 ", [$ticket_number]);

    //             foreach ($fr_teamID as $team) {
    //                 $team_id = $team->team_id;
    //                 $team_name = $team->team_name;
    //                 // $division_name = $team->division_name;

    //                 $fr_statusRecords = DB::select("SELECT fth.fr_response_status, fth.created_at 
    //                         FROM helpdesk.ticket_fr_time_team_histories fth
    //                         WHERE fth.ticket_number = ? AND fth.team_id = ? 
    //                         AND fth.fr_response_status IN (0, 1, 2) 
    //                         ORDER BY fth.created_at ASC
    //                     ", [$ticket_number, $team_id]);

    //                 $timestamps = [
    //                     'status_0' => null,
    //                     'status_1_or_2' => null
    //                 ];

    //                 foreach ($fr_statusRecords as $record) {
    //                     if ($record->fr_response_status == 0) {
    //                         $timestamps['status_0'] = $record->created_at;
    //                     } elseif (in_array($record->fr_response_status, [1, 2])) {
    //                         $timestamps['status_1_or_2'] = $record->created_at;
    //                     }
    //                 }

    //                 if ($timestamps['status_0'] && $timestamps['status_1_or_2']) {
    //                     $startTime = new DateTime($timestamps['status_0']);
    //                     $endTime = new DateTime($timestamps['status_1_or_2']);
    //                     $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

    //                     $ListOfTimeDifferences[] = [
    //                         'team_id' => $team_id,
    //                         'team_name' => $team_name,
    //                         // 'division_name' => $division_name,
    //                         'time_difference_in_seconds' => $timeDifferenceInSeconds
    //                     ];
    //                 }
    //             }
    //         }

    //         // Step 3: Summing time and calculating the average per FR team
    //         $timeSumsByTeam = [];
    //         foreach ($ListOfTimeDifferences as $entry) {
    //             $team_id = $entry['team_id'];
    //             $team_name = $entry['team_name'];
    //             // $division_name = $entry['division_name'];
    //             $time_difference = $entry['time_difference_in_seconds'];

    //             if (!isset($timeSumsByTeam[$team_id])) {
    //                 $timeSumsByTeam[$team_id] = [
    //                     'team_name' => $team_name,
    //                     // 'division_name' => $division_name,
    //                     'total_time_in_seconds' => 0,
    //                     'count' => 0
    //                 ];
    //             }

    //             $timeSumsByTeam[$team_id]['total_time_in_seconds'] += $time_difference;
    //             $timeSumsByTeam[$team_id]['count']++;
    //         }

    //         $averagesByTeam = [];
    //         foreach ($timeSumsByTeam as $team_id => $data) {
    //             $totalTime = $data['total_time_in_seconds'];
    //             $count = $data['count'];
    //             $averageTime = $totalTime / $count;

    //             $averagesByTeam[$team_id] = [
    //                 'team_id' => $team_id,
    //                 // 'team_name' => $data['team_name'],
    //                 // 'division_name' => $data['division_name'],
    //                 'total_time' => formatTime($totalTime),
    //                 'average_time' => formatTime($averageTime)
    //             ];
    //         }
    //         $ListOfSrvTicket = array_map(fn($item) => $item->ticket_number, $srv_teamTicket);

    //         $ListOfSrvTimeDifferences = [];

    //         // Step 6: Compute time differences for each team and SRV ticket
    //         foreach ($ListOfSrvTicket as $ticket_number) {
    //             $srv_teamID = DB::select("SELECT DISTINCT(sth.team_id), t.team_name 
    //                     FROM helpdesk.ticket_srv_time_team_histories sth 
    //                     JOIN helpdesk.teams t ON sth.team_id = t.id
    //                     -- JOIN helpdesk.divisions d ON d.id = t.division_id
    //                     WHERE sth.ticket_number = ?
    //                 ", [$ticket_number]);

    //             foreach ($srv_teamID as $team) {
    //                 $team_id = $team->team_id;
    //                 $team_name = $team->team_name;
    //                 // $division_name = $team->division_name;

    //                 $srv_statusRecords = DB::select("SELECT sth.srv_time_status, sth.created_at 
    //                         FROM helpdesk.ticket_srv_time_team_histories sth
    //                         WHERE sth.ticket_number = ? AND sth.team_id = ? 
    //                         AND sth.srv_time_status IN (0, 1, 2) 
    //                         ORDER BY sth.created_at ASC
    //                     ", [$ticket_number, $team_id]);

    //                 $srvTimestamps = [
    //                     'status_0' => null,
    //                     'status_1_or_2' => null
    //                 ];

    //                 foreach ($srv_statusRecords as $record) {
    //                     if ($record->srv_time_status == 0) {
    //                         $srvTimestamps['status_0'] = $record->created_at;
    //                     } elseif (in_array($record->srv_time_status, [1, 2])) {
    //                         $srvTimestamps['status_1_or_2'] = $record->created_at;
    //                     }
    //                 }

    //                 if ($srvTimestamps['status_0'] && $srvTimestamps['status_1_or_2']) {
    //                     $startTime = new DateTime($srvTimestamps['status_0']);
    //                     $endTime = new DateTime($srvTimestamps['status_1_or_2']);
    //                     $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

    //                     $ListOfSrvTimeDifferences[] = [
    //                         'team_id' => $team_id,
    //                         'team_name' => $team_name,
    //                         // 'division_name' => $division_name,
    //                         'time_difference_in_seconds' => $timeDifferenceInSeconds
    //                     ];
    //                 }
    //             }
    //         }

    //         // Step 7: Summing time and calculating the average per SRV team
    //         $timeSumsByTeamSrv = [];
    //         foreach ($ListOfSrvTimeDifferences as $entry) {
    //             $team_id = $entry['team_id'];
    //             $team_name = $entry['team_name'];
    //             // $division_name = $entry['division_name'];
    //             $time_difference = $entry['time_difference_in_seconds'];

    //             if (!isset($timeSumsByTeamSrv[$team_id])) {
    //                 $timeSumsByTeamSrv[$team_id] = [
    //                     'team_name' => $team_name,
    //                     // 'division_name' => $division_name,
    //                     'total_time_in_seconds' => 0,
    //                     'count' => 0
    //                 ];
    //             }

    //             $timeSumsByTeamSrv[$team_id]['total_time_in_seconds'] += $time_difference;
    //             $timeSumsByTeamSrv[$team_id]['count']++;
    //         }

    //         $averagesBySrvTeam = [];
    //         foreach ($timeSumsByTeamSrv as $team_id => $data) {
    //             $totalTime = $data['total_time_in_seconds'];
    //             $count = $data['count'];
    //             $averageSrvTime = $totalTime / $count;

    //             $averagesBySrvTeam[$team_id] = [
    //                 'team_id' => $team_id,
    //                 // 'team_name' => $data['team_name'],
    //                 // 'division_name' => $data['division_name'],
    //                 'total_time' => formatTime($totalTime),
    //                 'average_srv_time' => formatTime($averageSrvTime)
    //             ];
    //         }




    //         // Step 12: Fetch overdue counts for each team
    //         if ((empty($fromDate)) && (empty($toDate))) {
    //             $overDueCounts = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
    //                     FROM helpdesk.ticket_srv_time_team_histories tsh
    //                     JOIN helpdesk.teams t ON t.id = tsh.team_id
    //                     JOIN helpdesk.tickets te ON te.ticket_number = tsh.ticket_number
    //                     WHERE tsh.srv_time_status = 2
    //                     AND tsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                     GROUP BY t.id, t.team_name
    //                 ");
    //         } else {
    //             $overDueCounts = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
    //                 FROM helpdesk.ticket_srv_time_team_histories tsh
    //                 JOIN helpdesk.teams t ON t.id = tsh.team_id
    //                 JOIN helpdesk.tickets te ON te.ticket_number = tsh.ticket_number
    //                 WHERE tsh.srv_time_status = 2 AND DATE(tsh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                 GROUP BY t.id, t.team_name
    //             ");
    //         }

    //         // Map the over_due counts by team_id for easy access
    //         $overDueMap = [];
    //         foreach ($overDueCounts as $overDueData) {
    //             $overDueMap[$overDueData->team_id] = $overDueData->over_due;
    //         }

    //         // Step 13: Merge the overdue counts into the final averages data
    //         foreach ($averagesByTeam as $team_id => $teamData) {
    //             if (isset($overDueMap[$team_id])) {
    //                 $averagesByTeam[$team_id]['over_due'] = $overDueMap[$team_id];
    //             } else {
    //                 $averagesByTeam[$team_id]['over_due'] = 0; // Default to 0 if no overdue count exists
    //             }
    //         }

    //         // Step 14: Final output
    //         $finalOutput = array_values($averagesByTeam);


    //         if ((empty($fromDate)) && (empty($toDate))) {
    //             $overDueCountsFr = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT tfh.ticket_number) AS over_due_fr
    //                     FROM helpdesk.ticket_fr_time_team_histories tfh
    //                     JOIN helpdesk.teams t ON t.id = tfh.team_id
    //                     JOIN helpdesk.tickets te ON te.ticket_number = tfh.ticket_number
    //                     WHERE tfh.fr_response_status = 2
    //                     AND tfh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                     GROUP BY t.id, t.team_name
    //                 ");
    //         } else {

    //             $overDueCountsFr = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT tfh.ticket_number) AS over_due_fr
    //                                         FROM helpdesk.ticket_fr_time_team_histories tfh
    //                                         JOIN helpdesk.teams t ON t.id = tfh.team_id
    //                                         JOIN helpdesk.tickets te ON te.ticket_number = tfh.ticket_number
    //                                         WHERE tfh.fr_response_status = 2
    //                                         AND DATE(tfh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                                         GROUP BY t.id, t.team_name
    //                 ");
    //         }

    //         // Map the over_due_fr counts by team_id for easy access
    //         $overDueMap = [];
    //         foreach ($overDueCountsFr as $overDueData) {
    //             $overDueMap[$overDueData->team_id] = $overDueData->over_due_fr;
    //         }

    //         // Step 13: Merge the overdue counts into the final averages data
    //         foreach ($averagesByTeam as $team_id => $teamData) {
    //             if (isset($overDueMap[$team_id])) {
    //                 $averagesByTeam[$team_id]['over_due_fr'] = $overDueMap[$team_id];
    //             } else {
    //                 $averagesByTeam[$team_id]['over_due_fr'] = 0; // Default to 0 if no overdue count exists
    //             }
    //         }

    //         // Step 14: Final output
    //         $finalOutput = array_values($averagesByTeam);

    //         // ticket open & close count start

    //         if ((empty($fromDate)) && (empty($toDate))) {

    //             // $cacheKeyTicketsByteam = 'ticket_count_by_team';

    //             // $ticketCounts = Cache::get($cacheKeyTicketsByteam);


    //             $ticketCounts = DB::select("WITH TicketHistoryWithLags AS (
    //                                         SELECT id, ticket_number, team_id,
    //                                             LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
    //                                         FROM helpdesk.ticket_histories
    //                                     ),
    //                                     EscalationCheck AS (
    //                                         SELECT *, LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
    //                                         FROM TicketHistoryWithLags
    //                                     )
    //                                     SELECT 
    //                                         te.id AS team_id,
    //                                         te.team_name AS team_name,
    //                                         COALESCE(ticket_stats.total_created, 0) AS total_created,
    //                                         COALESCE(ticket_statuses.total_resolved, 0) AS total_resolved,
    //                                         COALESCE(ticket_statuses.ticket_closed_by_team, 0) AS ticket_closed_by_team,
    //                                         COALESCE(ticket_statuses.ticket_open_by_team, 0) AS ticket_open_by_team,
    //                                         COALESCE(fq.ticket_count_work, 0) AS total_work_ticket_count,
    //                                         COALESCE(sq.ticket_count, 0) AS ticket_count_tickets,
    //                                         COALESCE(uq.ticket_count_tickets_updated_by_other_team, 0) AS ticket_forwarded,
    //                                         COALESCE(avg_ticket_age.avg_open_ticket_age, '0d 0h 0m') AS avg_open_ticket_age,
    //                                         COALESCE(escalated_in.total_escalated_in, 0) AS escalated_in
    //                                     FROM helpdesk.teams te
    //                                     -- Total Created Tickets
    //                                     LEFT JOIN (
    //                                         SELECT utm.team_id, COUNT(DISTINCT t.ticket_number) AS total_created
    //                                         FROM helpdesk.tickets t
    //                                         LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                                         WHERE t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                                         GROUP BY utm.team_id
    //                                     ) AS ticket_stats ON te.id = ticket_stats.team_id
    //                                     -- Ticket Status Counts
    //                                     LEFT JOIN (
    //                                         SELECT 
    //                                             th2.team_id,
    //                                             COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
    //                                             COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                                             COUNT(DISTINCT CASE WHEN th2.status_id = 2 THEN th2.ticket_number END) AS total_resolved
    //                                         FROM helpdesk.tickets th2
    //                                         WHERE th2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                                         GROUP BY th2.team_id
    //                                     ) AS ticket_statuses ON te.id = ticket_statuses.team_id
    //                                     -- Total Worked Tickets
    //                                     LEFT JOIN (
    //                                         SELECT th.team_id, COUNT(DISTINCT th.ticket_number) AS ticket_count_work
    //                                         FROM helpdesk.ticket_histories th
    //                                         WHERE th.created_at IN (
    //                                             SELECT MIN(th_inner.created_at) 
    //                                             FROM helpdesk.ticket_histories th_inner 
    //                                             WHERE th_inner.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                                             GROUP BY th_inner.ticket_number
    //                                         )
    //                                         GROUP BY th.team_id
    //                                     ) AS fq ON te.id = fq.team_id
    //                                     -- Total Tickets Count
    //                                     LEFT JOIN (
    //                                         SELECT th.team_id, COUNT(DISTINCT th.ticket_number) AS ticket_count
    //                                         FROM helpdesk.tickets th
    //                                         WHERE th.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                                         GROUP BY th.team_id
    //                                     ) AS sq ON te.id = sq.team_id
    //                                     -- Tickets Worked by a Team but Updated by Another Team
    //                                     LEFT JOIN (
    //                                         SELECT team_id,
    //                                             SUM(CASE WHEN lead_team_id IS NOT NULL AND lead_team_id <> team_id THEN 1 ELSE 0 END) AS ticket_count_tickets_updated_by_other_team
    //                                         FROM EscalationCheck
    //                                         WHERE prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL
    //                                         GROUP BY team_id
    //                                     ) AS uq ON te.id = uq.team_id
    //                                     -- Average Open Ticket Age
    //                                     LEFT JOIN (
    //                                         SELECT th2.team_id, 
    //                                         COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                                         CASE 
    //                                             WHEN COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) = 0 
    //                                                 THEN '0 day 0 hour 0 min'
    //                                             ELSE CONCAT(
    //                                                 FLOOR(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) / 86400), 'd ',
    //                                                 FLOOR(MOD(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END), 86400) / 3600), 'h ',
    //                                                 FLOOR(MOD(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END), 3600) / 60), 'm'
    //                                             )
    //                                         END AS avg_open_ticket_age
    //                                         FROM helpdesk.tickets th2
    //                                         WHERE th2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                                         AND th2.status_id != 6
    //                                         GROUP BY th2.team_id
    //                                     ) AS avg_ticket_age ON te.id = avg_ticket_age.team_id
    //                                     -- Escalated Tickets (Tickets worked on by the team but escalated by others)
    //                                     LEFT JOIN (
    //                                         SELECT team_id,
    //                                             SUM(CASE WHEN prev_team_id IS NOT NULL AND prev_team_id <> team_id THEN 1 ELSE 0 END) AS total_escalated_in
    //                                         FROM EscalationCheck
    //                                         WHERE prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL
    //                                         GROUP BY team_id
    //                                     ) AS escalated_in ON te.id = escalated_in.team_id

    //                                     ORDER BY te.team_name");

    //             // Cache::put($cacheKeyTicketsByteam, $ticketCounts, now()->addMinutes(60));
    //         } else {


    //             $ticketCounts = DB::select("WITH TicketHistoryWithLags AS (
    //                                         SELECT id, ticket_number, team_id,
    //                                             LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
    //                                         FROM helpdesk.ticket_histories
    //                                     ),
    //                                     EscalationCheck AS (
    //                                         SELECT *, LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
    //                                         FROM TicketHistoryWithLags
    //                                     )
    //                                     SELECT 
    //                                         te.id AS team_id,
    //                                         te.team_name AS team_name,
    //                                         COALESCE(ticket_stats.total_created, 0) AS total_created,
    //                                         COALESCE(ticket_statuses.total_resolved, 0) AS total_resolved,
    //                                         COALESCE(ticket_statuses.ticket_closed_by_team, 0) AS ticket_closed_by_team,
    //                                         COALESCE(ticket_statuses.ticket_open_by_team, 0) AS ticket_open_by_team,
    //                                         COALESCE(fq.ticket_count_work, 0) AS total_work_ticket_count,
    //                                         COALESCE(sq.ticket_count, 0) AS ticket_count_tickets,
    //                                         COALESCE(uq.ticket_count_tickets_updated_by_other_team, 0) AS ticket_forwarded,
    //                                         COALESCE(avg_ticket_age.avg_open_ticket_age, '0d 0h 0m') AS avg_open_ticket_age,
    //                                         COALESCE(escalated_in.total_escalated_in, 0) AS escalated_in
    //                                     FROM helpdesk.teams te
    //                                     -- Total Created Tickets
    //                                     LEFT JOIN (
    //                                         SELECT utm.team_id, COUNT(DISTINCT t.ticket_number) AS total_created
    //                                         FROM helpdesk.tickets t
    //                                         LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                                         WHERE DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                                         GROUP BY utm.team_id
    //                                     ) AS ticket_stats ON te.id = ticket_stats.team_id
    //                                     -- Ticket Status Counts
    //                                     LEFT JOIN (
    //                                         SELECT 
    //                                             th2.team_id,
    //                                             COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
    //                                             COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                                             COUNT(DISTINCT CASE WHEN th2.status_id = 2 THEN th2.ticket_number END) AS total_resolved
    //                                         FROM helpdesk.tickets th2
    //                                         WHERE th2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                                         GROUP BY th2.team_id
    //                                     ) AS ticket_statuses ON te.id = ticket_statuses.team_id
    //                                     -- Total Worked Tickets
    //                                     LEFT JOIN (
    //                                         SELECT th.team_id, COUNT(DISTINCT th.ticket_number) AS ticket_count_work
    //                                         FROM helpdesk.ticket_histories th
    //                                         WHERE th.created_at IN (
    //                                             SELECT MIN(th_inner.created_at) 
    //                                             FROM helpdesk.ticket_histories th_inner 
    //                                             WHERE DATE(th_inner.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                                             GROUP BY th_inner.ticket_number
    //                                         )
    //                                         GROUP BY th.team_id
    //                                     ) AS fq ON te.id = fq.team_id
    //                                     -- Total Tickets Count
    //                                     LEFT JOIN (
    //                                         SELECT th.team_id, COUNT(DISTINCT th.ticket_number) AS ticket_count
    //                                         FROM helpdesk.tickets th
    //                                         WHERE DATE(th.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                                         GROUP BY th.team_id
    //                                     ) AS sq ON te.id = sq.team_id
    //                                     -- Tickets Worked by a Team but Updated by Another Team
    //                                     LEFT JOIN (
    //                                         SELECT team_id,
    //                                             SUM(CASE WHEN lead_team_id IS NOT NULL AND lead_team_id <> team_id THEN 1 ELSE 0 END) AS ticket_count_tickets_updated_by_other_team
    //                                         FROM EscalationCheck
    //                                         WHERE prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL
    //                                         GROUP BY team_id
    //                                     ) AS uq ON te.id = uq.team_id
    //                                     -- Average Open Ticket Age
    //                                     LEFT JOIN (
    //                                         SELECT th2.team_id, 
    //                                         COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                                         CASE 
    //                                             WHEN COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) = 0 
    //                                                 THEN '0 day 0 hour 0 min'
    //                                             ELSE CONCAT(
    //                                                 FLOOR(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) / 86400), 'd ',
    //                                                 FLOOR(MOD(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END), 86400) / 3600), 'h ',
    //                                                 FLOOR(MOD(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END), 3600) / 60), 'm'
    //                                             )
    //                                         END AS avg_open_ticket_age
    //                                         FROM helpdesk.tickets th2
    //                                         WHERE DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                                         AND th2.status_id != 6
    //                                         GROUP BY th2.team_id
    //                                     ) AS avg_ticket_age ON te.id = avg_ticket_age.team_id
    //                                     -- Escalated Tickets (Tickets worked on by the team but escalated by others)
    //                                     LEFT JOIN (
    //                                         SELECT team_id,
    //                                             SUM(CASE WHEN prev_team_id IS NOT NULL AND prev_team_id <> team_id THEN 1 ELSE 0 END) AS total_escalated_in
    //                                         FROM EscalationCheck
    //                                         WHERE prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL
    //                                         GROUP BY team_id
    //                                     ) AS escalated_in ON te.id = escalated_in.team_id

    //                                     ORDER BY te.team_name");
    //         }

    //         // ticket open & close count end

    //         foreach ($ticketCounts as $ticketData) {
    //             $team_id = $ticketData->team_id;

    //             // Initialize department entry in $averagesByTeam if not already set
    //             if (!isset($averagesByTeam[$team_id])) {
    //                 $averagesByTeam[$team_id] = [
    //                     'team_name' => $team_id,
    //                     'total_time' => '00:00:00',
    //                     'average_time' => '0h 0m 0d',
    //                     'over_due' => 0,
    //                     'over_due_fr' => 0,
    //                     'current_date' => date('Y-m-d'),
    //                     'last_month_date' => date('Y-m-d', strtotime('-1 month')),
    //                     'average_srv_time' => '0h 0m 0d',
    //                     'total_created' => 0,
    //                     'total_resolved' => 0,
    //                     'ticket_closed_by_team' => 0,
    //                     'ticket_open_by_team' => 0,
    //                     'ticket_forwarded' => 0,
    //                     'avg_open_ticket_age' => 0,
    //                     'escalated_in' => 0,
    //                 ];
    //             }

    //             // Update ticket counts for the team
    //             $averagesByTeam[$team_id]['team_name'] = $ticketData->team_name ?? 0;
    //             $averagesByTeam[$team_id]['total_created'] = $ticketData->total_created ?? 0;
    //             $averagesByTeam[$team_id]['total_resolved'] = $ticketData->total_resolved ?? 0;
    //             $averagesByTeam[$team_id]['ticket_closed_by_team'] = $ticketData->ticket_closed_by_team ?? 0;
    //             $averagesByTeam[$team_id]['ticket_open_by_team'] = $ticketData->ticket_open_by_team ?? 0;
    //             $averagesByTeam[$team_id]['ticket_forwarded'] = $ticketData->ticket_forwarded ?? 0;
    //             $averagesByTeam[$team_id]['avg_open_ticket_age'] = $ticketData->avg_open_ticket_age ?? 0;
    //             $averagesByTeam[$team_id]['escalated_in'] = $ticketData->escalated_in ?? 0;

    //             // Update average_srv_time if it exists in $averagesBySrvTeam
    //             if (isset($averagesBySrvTeam[$team_id]['average_srv_time'])) {
    //                 $averagesByTeam[$team_id]['average_srv_time'] = $averagesBySrvTeam[$team_id]['average_srv_time'];
    //             }
    //         }

    //         // Filter out departments where both ticket counts are 0
    //         $filteredAverages = array_filter($averagesByTeam, function ($teamData) {
    //             return $teamData['ticket_closed_by_team'] > 0 || $teamData['ticket_open_by_team'] > 0 || $teamData['total_created'] > 0 || $teamData['total_resolved'] > 0;
    //         });

    //         // $finalOutput = array_values($averagesByTeam);
    //         $finalOutput = array_values($filteredAverages);

    //         // if ((empty($fromDate)) && (empty($toDate))) {

    //         //     Cache::put($cacheKey, $finalOutput, now()->addMinutes(60));
    //         //     Log::info("Cache miss for key: {$cacheKey}. Stored new data in cache.");
    //         // }


    //         return ApiResponse::success($finalOutput, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Failed", 500);
    //     }
    // }



    // new latest
    // public function teamReportForDashboard(Request $request)
    // {
    //     try {

    //         $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //         $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

    //         // if ((empty($fromDate)) && (empty($toDate))) {
    //         //     $cacheKey = 'team_report_dashboard';

    //         //     $cachedData = Cache::get($cacheKey);

    //         //     if ($cachedData) {
    //         //         Log::info("Cache hit for key: {$cacheKey}");
    //         //         return ApiResponse::success($cachedData, "Success", 200);
    //         //     }
    //         // }


    //         if ((empty($fromDate)) && (empty($toDate))) {

    //             $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.first_res_sla_histories fth
    //             WHERE fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");

    //             $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.srv_time_subcat_sla_histories sth
    //             WHERE sth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
    //         } else {

    //             $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.first_res_sla_histories fth WHERE DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'");

    //             $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.srv_time_subcat_sla_histories sth WHERE DATE(sth.created_at) BETWEEN '$fromDate' AND '$toDate'");
    //         }


    //         // $isDefaultRange = empty($fromDate) && empty($toDate);

    //         // $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.first_res_sla_histories fth 
    //         //     WHERE " . ($isDefaultRange ? "fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)" : "DATE(fth.created_at) BETWEEN ? AND ?"),
    //         //     $isDefaultRange ? [] : [$fromDate, $toDate]
    //         // );

    //         // $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.srv_time_subcat_sla_histories sth 
    //         //     WHERE " . ($isDefaultRange ? "sth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)" : "DATE(sth.created_at) BETWEEN ? AND ?"),
    //         //     $isDefaultRange ? [] : [$fromDate, $toDate]
    //         // );

    //         $ListOfTicket = array_map(fn($item) => $item->ticket_number, $fr_teamTicket);

    //         $ListOfTimeDifferences = [];

    //         // Step 2: Compute time differences for each team and FR ticket
    //         foreach ($ListOfTicket as $ticket_number) {
    //             $fr_teamID = DB::select("SELECT DISTINCT(frc.team_id), t.team_name
    //                     FROM helpdesk.first_res_sla_histories fth 
    //                     JOIN helpdesk.first_res_configs frc ON fth.first_res_config_id = frc.id
    //                     JOIN helpdesk.teams t ON frc.team_id = t.id
    //                     -- JOIN helpdesk.divisions d ON d.id = t.division_id
    //                     WHERE fth.ticket_number = ?
    //                 ", [$ticket_number]);

    //             foreach ($fr_teamID as $team) {
    //                 $team_id = $team->team_id;
    //                 $team_name = $team->team_name;
    //                 // $division_name = $team->division_name;

    //                 $fr_statusRecords = DB::select("SELECT fth.sla_status, fth.created_at 
    //                         FROM helpdesk.first_res_sla_histories fth
    //                         JOIN helpdesk.first_res_configs frc ON fth.first_res_config_id = frc.id
    //                         WHERE fth.ticket_number = ? AND frc.team_id = ? 
    //                         AND fth.sla_status IN (0, 1, 2) 
    //                         ORDER BY fth.created_at ASC
    //                     ", [$ticket_number, $team_id]);

    //                 $timestamps = [
    //                     'status_2' => null,
    //                     'status_1_or_0' => null
    //                 ];

    //                 foreach ($fr_statusRecords as $record) {
    //                     if ($record->sla_status == 2) {
    //                         $timestamps['status_2'] = $record->created_at;
    //                     } elseif (in_array($record->sla_status, [1, 0])) {
    //                         $timestamps['status_1_or_0'] = $record->created_at;
    //                     }
    //                 }

    //                 if ($timestamps['status_2'] && $timestamps['status_1_or_0']) {
    //                     $startTime = new DateTime($timestamps['status_2']);
    //                     $endTime = new DateTime($timestamps['status_1_or_0']);
    //                     $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

    //                     $ListOfTimeDifferences[] = [
    //                         'team_id' => $team_id,
    //                         'team_name' => $team_name,
    //                         // 'division_name' => $division_name,
    //                         'time_difference_in_seconds' => $timeDifferenceInSeconds
    //                     ];
    //                 }
    //             }
    //         }

    //         // Step 3: Summing time and calculating the average per FR team
    //         $timeSumsByTeam = [];
    //         foreach ($ListOfTimeDifferences as $entry) {
    //             $team_id = $entry['team_id'];
    //             $team_name = $entry['team_name'];
    //             // $division_name = $entry['division_name'];
    //             $time_difference = $entry['time_difference_in_seconds'];

    //             if (!isset($timeSumsByTeam[$team_id])) {
    //                 $timeSumsByTeam[$team_id] = [
    //                     'team_name' => $team_name,
    //                     // 'division_name' => $division_name,
    //                     'total_time_in_seconds' => 0,
    //                     'count' => 0
    //                 ];
    //             }

    //             $timeSumsByTeam[$team_id]['total_time_in_seconds'] += $time_difference;
    //             $timeSumsByTeam[$team_id]['count']++;
    //         }

    //         $averagesByTeam = [];
    //         foreach ($timeSumsByTeam as $team_id => $data) {
    //             $totalTime = $data['total_time_in_seconds'];
    //             $count = $data['count'];
    //             $averageTime = $totalTime / $count;

    //             $averagesByTeam[$team_id] = [
    //                 'team_id' => $team_id,
    //                 // 'team_name' => $data['team_name'],
    //                 // 'division_name' => $data['division_name'],
    //                 'total_time' => formatTime($totalTime),
    //                 'average_time' => formatTime($averageTime)
    //             ];
    //         }
    //         $ListOfSrvTicket = array_map(fn($item) => $item->ticket_number, $srv_teamTicket);

    //         $ListOfSrvTimeDifferences = [];

    //         // Step 6: Compute time differences for each team and SRV ticket
    //         foreach ($ListOfSrvTicket as $ticket_number) {
    //             $srv_teamID = DB::select("SELECT DISTINCT(ssc.team_id), t.team_name 
    //                     FROM helpdesk.srv_time_subcat_sla_histories sth 
    //                     JOIN helpdesk.sla_subcat_configs ssc ON sth.sla_subcat_config_id = ssc.id
    //                     JOIN helpdesk.teams t ON ssc.team_id = t.id
    //                     -- JOIN helpdesk.divisions d ON d.id = t.division_id
    //                     WHERE sth.ticket_number = ?
    //                 ", [$ticket_number]);

    //             foreach ($srv_teamID as $team) {
    //                 $team_id = $team->team_id;
    //                 $team_name = $team->team_name;
    //                 // $division_name = $team->division_name;

    //                 $srv_statusRecords = DB::select("SELECT sth.sla_status, sth.created_at 
    //                         FROM helpdesk.srv_time_subcat_sla_histories sth
    //                         JOIN helpdesk.sla_subcat_configs ssc ON sth.sla_subcat_config_id = ssc.id
    //                         WHERE sth.ticket_number = ? AND ssc.team_id = ? 
    //                         AND sth.sla_status IN (0, 1, 2) 
    //                         ORDER BY sth.created_at ASC
    //                     ", [$ticket_number, $team_id]);

    //                 $srvTimestamps = [
    //                     'status_2' => null,
    //                     'status_1_or_0' => null
    //                 ];

    //                 foreach ($srv_statusRecords as $record) {
    //                     if ($record->sla_status == 2) {
    //                         $srvTimestamps['status_2'] = $record->created_at;
    //                     } elseif (in_array($record->sla_status, [1, 0])) {
    //                         $srvTimestamps['status_1_or_0'] = $record->created_at;
    //                     }
    //                 }

    //                 if ($srvTimestamps['status_2'] && $srvTimestamps['status_1_or_0']) {
    //                     $startTime = new DateTime($srvTimestamps['status_2']);
    //                     $endTime = new DateTime($srvTimestamps['status_1_or_0']);
    //                     $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

    //                     $ListOfSrvTimeDifferences[] = [
    //                         'team_id' => $team_id,
    //                         'team_name' => $team_name,
    //                         // 'division_name' => $division_name,
    //                         'time_difference_in_seconds' => $timeDifferenceInSeconds
    //                     ];
    //                 }
    //             }
    //         }

    //         // Step 7: Summing time and calculating the average per SRV team
    //         $timeSumsByTeamSrv = [];
    //         foreach ($ListOfSrvTimeDifferences as $entry) {
    //             $team_id = $entry['team_id'];
    //             $team_name = $entry['team_name'];
    //             // $division_name = $entry['division_name'];
    //             $time_difference = $entry['time_difference_in_seconds'];

    //             if (!isset($timeSumsByTeamSrv[$team_id])) {
    //                 $timeSumsByTeamSrv[$team_id] = [
    //                     'team_name' => $team_name,
    //                     // 'division_name' => $division_name,
    //                     'total_time_in_seconds' => 0,
    //                     'count' => 0
    //                 ];
    //             }

    //             $timeSumsByTeamSrv[$team_id]['total_time_in_seconds'] += $time_difference;
    //             $timeSumsByTeamSrv[$team_id]['count']++;
    //         }

    //         $averagesBySrvTeam = [];
    //         foreach ($timeSumsByTeamSrv as $team_id => $data) {
    //             $totalTime = $data['total_time_in_seconds'];
    //             $count = $data['count'];
    //             $averageSrvTime = $totalTime / $count;

    //             $averagesBySrvTeam[$team_id] = [
    //                 'team_id' => $team_id,
    //                 // 'team_name' => $data['team_name'],
    //                 // 'division_name' => $data['division_name'],
    //                 'total_time' => formatTime($totalTime),
    //                 'average_srv_time' => formatTime($averageSrvTime)
    //             ];
    //         }




    //         // Step 12: Fetch overdue counts for each team
    //         if ((empty($fromDate)) && (empty($toDate))) {
    //             $overDueCounts = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
    //                 FROM helpdesk.srv_time_subcat_sla_histories tsh
    //                 JOIN helpdesk.sla_subcat_configs ssc ON tsh.sla_subcat_config_id = ssc.id
    //                 JOIN helpdesk.teams t ON t.id = ssc.team_id
    //                 LEFT JOIN helpdesk.open_tickets ot ON ot.ticket_number = tsh.ticket_number
    //                 LEFT JOIN helpdesk.close_tickets ct ON ct.ticket_number = tsh.ticket_number
    //                 WHERE tsh.sla_status = 0
    //                 AND tsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                 AND (ot.ticket_number IS NOT NULL OR ct.ticket_number IS NOT NULL)
    //                 GROUP BY t.id, t.team_name
    //             ");
    //         } else {
    //             $overDueCounts = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
    //                 FROM helpdesk.srv_time_subcat_sla_histories tsh
    //                 JOIN helpdesk.sla_subcat_configs ssc ON tsh.sla_subcat_config_id = ssc.id
    //                 JOIN helpdesk.teams t ON t.id = ssc.team_id
    //                 LEFT JOIN helpdesk.open_tickets ot ON ot.ticket_number = tsh.ticket_number
    //                 LEFT JOIN helpdesk.close_tickets ct ON ct.ticket_number = tsh.ticket_number
    //                 WHERE tsh.sla_status = 0 AND DATE(tsh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                 AND (ot.ticket_number IS NOT NULL OR ct.ticket_number IS NOT NULL)
    //                 GROUP BY t.id, t.team_name
    //             ");
    //         }

    //         // Map the over_due counts by team_id for easy access
    //         $overDueMap = [];
    //         foreach ($overDueCounts as $overDueData) {
    //             $overDueMap[$overDueData->team_id] = $overDueData->over_due;
    //         }

    //         // Step 13: Merge the overdue counts into the final averages data
    //         foreach ($averagesByTeam as $team_id => $teamData) {
    //             if (isset($overDueMap[$team_id])) {
    //                 $averagesByTeam[$team_id]['over_due'] = $overDueMap[$team_id];
    //             } else {
    //                 $averagesByTeam[$team_id]['over_due'] = 0; // Default to 0 if no overdue count exists
    //             }
    //         }

    //         // Step 14: Final output
    //         $finalOutput = array_values($averagesByTeam);


    //         if ((empty($fromDate)) && (empty($toDate))) {
    //             $overDueCountsFr = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT tfh.ticket_number) AS over_due_fr
    //                     FROM helpdesk.first_res_sla_histories tfh
    //                     JOIN helpdesk.first_res_configs frc ON tfh.first_res_config_id = frc.id
    //                     JOIN helpdesk.teams t ON t.id = frc.team_id
    //                     LEFT JOIN helpdesk.open_tickets ot ON ot.ticket_number = tfh.ticket_number
    //                     LEFT JOIN helpdesk.close_tickets ct ON ct.ticket_number = tfh.ticket_number
    //                     WHERE tfh.sla_status = 0
    //                     AND tfh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                     AND (ot.ticket_number IS NOT NULL OR ct.ticket_number IS NOT NULL)
    //                     GROUP BY t.id, t.team_name
    //                 ");
    //         } else {

    //             $overDueCountsFr = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT tfh.ticket_number) AS over_due_fr
    //                                         FROM helpdesk.first_res_sla_histories tfh
    //                                         JOIN helpdesk.first_res_configs frc ON tfh.first_res_config_id = frc.id
    //                                         JOIN helpdesk.teams t ON t.id = frc.team_id
    //                                         LEFT JOIN helpdesk.open_tickets ot ON ot.ticket_number = tfh.ticket_number
    //                                         LEFT JOIN helpdesk.close_tickets ct ON ct.ticket_number = tfh.ticket_number
    //                                         WHERE tfh.sla_status = 0
    //                                         AND DATE(tfh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                                         AND (ot.ticket_number IS NOT NULL OR ct.ticket_number IS NOT NULL)
    //                                         GROUP BY t.id, t.team_name
    //                 ");
    //         }

    //         // Map the over_due_fr counts by team_id for easy access
    //         $overDueMap = [];
    //         foreach ($overDueCountsFr as $overDueData) {
    //             $overDueMap[$overDueData->team_id] = $overDueData->over_due_fr;
    //         }

    //         // Step 13: Merge the overdue counts into the final averages data
    //         foreach ($averagesByTeam as $team_id => $teamData) {
    //             if (isset($overDueMap[$team_id])) {
    //                 $averagesByTeam[$team_id]['over_due_fr'] = $overDueMap[$team_id];
    //             } else {
    //                 $averagesByTeam[$team_id]['over_due_fr'] = 0; // Default to 0 if no overdue count exists
    //             }
    //         }

    //         // Step 14: Final output
    //         $finalOutput = array_values($averagesByTeam);

    //         // ticket open & close count start

    //         if ((empty($fromDate)) && (empty($toDate))) {

    //             // $cacheKeyTicketsByteam = 'ticket_count_by_team';

    //             // $ticketCounts = Cache::get($cacheKeyTicketsByteam);


    //             $ticketCounts = DB::select("WITH AllTickets AS (
    //                             SELECT * FROM helpdesk.open_tickets
    //                             UNION ALL
    //                             SELECT * FROM helpdesk.close_tickets
    //                         ),
    //                         TicketHistoryWithLags AS (
    //                             SELECT id, ticket_number, team_id,
    //                                 LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
    //                             FROM helpdesk.ticket_histories
    //                         ),
    //                         EscalationCheck AS (
    //                             SELECT *, LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
    //                             FROM TicketHistoryWithLags
    //                         )
    //                         SELECT 
    //                             te.id AS team_id,
    //                             te.team_name AS team_name,
    //                             COALESCE(ticket_stats.total_created, 0) AS total_created,
    //                             COALESCE(ticket_statuses.total_resolved, 0) AS total_resolved,
    //                             COALESCE(ticket_statuses.ticket_closed_by_team, 0) AS ticket_closed_by_team,
    //                             COALESCE(ticket_statuses.ticket_open_by_team, 0) AS ticket_open_by_team,
    //                             COALESCE(fq.ticket_count_work, 0) AS total_work_ticket_count,
    //                             COALESCE(sq.ticket_count, 0) AS ticket_count_tickets,
    //                             COALESCE(uq.ticket_count_tickets_updated_by_other_team, 0) AS ticket_forwarded,
    //                             COALESCE(avg_ticket_age.avg_open_ticket_age, '0d 0h 0m') AS avg_open_ticket_age,
    //                             COALESCE(escalated_in.total_escalated_in, 0) AS escalated_in
    //                         FROM helpdesk.teams te
    //                         -- Total Created Tickets
    //                         LEFT JOIN (
    //                             SELECT utm.team_id, COUNT(DISTINCT t.ticket_number) AS total_created
    //                             FROM AllTickets t
    //                             LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                             WHERE t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY utm.team_id
    //                         ) AS ticket_stats ON te.id = ticket_stats.team_id
    //                         -- Ticket Status Counts
    //                         LEFT JOIN (
    //                             SELECT 
    //                                 th2.team_id,
    //                                 COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
    //                                 COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                                 COUNT(DISTINCT CASE WHEN th2.status_id = 2 THEN th2.ticket_number END) AS total_resolved
    //                             FROM AllTickets th2
    //                             WHERE th2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY th2.team_id
    //                         ) AS ticket_statuses ON te.id = ticket_statuses.team_id
    //                         -- Total Worked Tickets
    //                         LEFT JOIN (
    //                             SELECT th.team_id, COUNT(DISTINCT th.ticket_number) AS ticket_count_work
    //                             FROM helpdesk.ticket_histories th
    //                             WHERE th.created_at IN (
    //                                 SELECT MIN(th_inner.created_at) 
    //                                 FROM helpdesk.ticket_histories th_inner 
    //                                 WHERE th_inner.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                                 GROUP BY th_inner.ticket_number
    //                             )
    //                             GROUP BY th.team_id
    //                         ) AS fq ON te.id = fq.team_id
    //                         -- Total Tickets Count
    //                         LEFT JOIN (
    //                             SELECT th.team_id, COUNT(DISTINCT th.ticket_number) AS ticket_count
    //                             FROM AllTickets th
    //                             WHERE th.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY th.team_id
    //                         ) AS sq ON te.id = sq.team_id
    //                         -- Tickets Worked by a Team but Updated by Another Team
    //                         LEFT JOIN (
    //                             SELECT team_id,
    //                                 SUM(CASE WHEN lead_team_id IS NOT NULL AND lead_team_id <> team_id THEN 1 ELSE 0 END) AS ticket_count_tickets_updated_by_other_team
    //                             FROM EscalationCheck
    //                             WHERE prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL
    //                             GROUP BY team_id
    //                         ) AS uq ON te.id = uq.team_id
    //                         -- Average Open Ticket Age
    //                         LEFT JOIN (
    //                             SELECT th2.team_id, 
    //                             COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                             CASE 
    //                                 WHEN COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) = 0 
    //                                     THEN '0 day 0 hour 0 min'
    //                                 ELSE CONCAT(
    //                                     FLOOR(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) / 86400), 'd ',
    //                                     FLOOR(MOD(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END), 86400) / 3600), 'h ',
    //                                     FLOOR(MOD(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END), 3600) / 60), 'm'
    //                                 )
    //                             END AS avg_open_ticket_age
    //                             FROM AllTickets th2
    //                             WHERE th2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             AND th2.status_id != 6
    //                             GROUP BY th2.team_id
    //                         ) AS avg_ticket_age ON te.id = avg_ticket_age.team_id
    //                         -- Escalated Tickets
    //                         LEFT JOIN (
    //                             SELECT team_id,
    //                                 SUM(CASE WHEN prev_team_id IS NOT NULL AND prev_team_id <> team_id THEN 1 ELSE 0 END) AS total_escalated_in
    //                             FROM EscalationCheck
    //                             WHERE prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL
    //                             GROUP BY team_id
    //                         ) AS escalated_in ON te.id = escalated_in.team_id

    //                         ORDER BY te.team_name");

    //             // Cache::put($cacheKeyTicketsByteam, $ticketCounts, now()->addMinutes(60));
    //         } else {


    //             $ticketCounts = DB::select("WITH AllTickets AS (
    //                             SELECT * FROM helpdesk.open_tickets
    //                             UNION ALL
    //                             SELECT * FROM helpdesk.close_tickets
    //                         ),
    //                         TicketHistoryWithLags AS (
    //                             SELECT id, ticket_number, team_id,
    //                                 LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
    //                             FROM helpdesk.ticket_histories
    //                         ),
    //                         EscalationCheck AS (
    //                             SELECT *, LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
    //                             FROM TicketHistoryWithLags
    //                         )
    //                         SELECT 
    //                             te.id AS team_id,
    //                             te.team_name AS team_name,
    //                             COALESCE(ticket_stats.total_created, 0) AS total_created,
    //                             COALESCE(ticket_statuses.total_resolved, 0) AS total_resolved,
    //                             COALESCE(ticket_statuses.ticket_closed_by_team, 0) AS ticket_closed_by_team,
    //                             COALESCE(ticket_statuses.ticket_open_by_team, 0) AS ticket_open_by_team,
    //                             COALESCE(fq.ticket_count_work, 0) AS total_work_ticket_count,
    //                             COALESCE(sq.ticket_count, 0) AS ticket_count_tickets,
    //                             COALESCE(uq.ticket_count_tickets_updated_by_other_team, 0) AS ticket_forwarded,
    //                             COALESCE(avg_ticket_age.avg_open_ticket_age, '0d 0h 0m') AS avg_open_ticket_age,
    //                             COALESCE(escalated_in.total_escalated_in, 0) AS escalated_in
    //                         FROM helpdesk.teams te
    //                         -- Total Created Tickets
    //                         LEFT JOIN (
    //                             SELECT utm.team_id, COUNT(DISTINCT t.ticket_number) AS total_created
    //                             FROM AllTickets t
    //                             LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                             WHERE DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY utm.team_id
    //                         ) AS ticket_stats ON te.id = ticket_stats.team_id
    //                         -- Ticket Status Counts
    //                         LEFT JOIN (
    //                             SELECT 
    //                                 th2.team_id,
    //                                 COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
    //                                 COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                                 COUNT(DISTINCT CASE WHEN th2.status_id = 2 THEN th2.ticket_number END) AS total_resolved
    //                             FROM AllTickets th2
    //                             WHERE DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY th2.team_id
    //                         ) AS ticket_statuses ON te.id = ticket_statuses.team_id
    //                         -- Total Worked Tickets
    //                         LEFT JOIN (
    //                             SELECT th.team_id, COUNT(DISTINCT th.ticket_number) AS ticket_count_work
    //                             FROM helpdesk.ticket_histories th
    //                             WHERE th.created_at IN (
    //                                 SELECT MIN(th_inner.created_at) 
    //                                 FROM helpdesk.ticket_histories th_inner 
    //                                 WHERE DATE(th_inner.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                                 GROUP BY th_inner.ticket_number
    //                             )
    //                             GROUP BY th.team_id
    //                         ) AS fq ON te.id = fq.team_id
    //                         -- Total Tickets Count
    //                         LEFT JOIN (
    //                             SELECT th.team_id, COUNT(DISTINCT th.ticket_number) AS ticket_count
    //                             FROM AllTickets th
    //                             WHERE DATE(th.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY th.team_id
    //                         ) AS sq ON te.id = sq.team_id
    //                         -- Tickets Worked by a Team but Updated by Another Team
    //                         LEFT JOIN (
    //                             SELECT team_id,
    //                                 SUM(CASE WHEN lead_team_id IS NOT NULL AND lead_team_id <> team_id THEN 1 ELSE 0 END) AS ticket_count_tickets_updated_by_other_team
    //                             FROM EscalationCheck
    //                             WHERE prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL
    //                             GROUP BY team_id
    //                         ) AS uq ON te.id = uq.team_id
    //                         -- Average Open Ticket Age
    //                         LEFT JOIN (
    //                             SELECT th2.team_id, 
    //                             COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                             CASE 
    //                                 WHEN COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) = 0 
    //                                     THEN '0 day 0 hour 0 min'
    //                                 ELSE CONCAT(
    //                                     FLOOR(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) / 86400), 'd ',
    //                                     FLOOR(MOD(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END), 86400) / 3600), 'h ',
    //                                     FLOOR(MOD(SUM(TIMESTAMPDIFF(SECOND, th2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END), 3600) / 60), 'm'
    //                                 )
    //                             END AS avg_open_ticket_age
    //                             FROM AllTickets th2
    //                             WHERE DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             AND th2.status_id != 6
    //                             GROUP BY th2.team_id
    //                         ) AS avg_ticket_age ON te.id = avg_ticket_age.team_id
    //                         -- Escalated Tickets
    //                         LEFT JOIN (
    //                             SELECT team_id,
    //                                 SUM(CASE WHEN prev_team_id IS NOT NULL AND prev_team_id <> team_id THEN 1 ELSE 0 END) AS total_escalated_in
    //                             FROM EscalationCheck
    //                             WHERE prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL
    //                             GROUP BY team_id
    //                         ) AS escalated_in ON te.id = escalated_in.team_id

    //                         ORDER BY te.team_name");
    //         }

    //         // ticket open & close count end

    //         foreach ($ticketCounts as $ticketData) {
    //             $team_id = $ticketData->team_id;

    //             // Initialize department entry in $averagesByTeam if not already set
    //             if (!isset($averagesByTeam[$team_id])) {
    //                 $averagesByTeam[$team_id] = [
    //                     'team_name' => $team_id,
    //                     'total_time' => '00:00:00',
    //                     'average_time' => '0h 0m 0d',
    //                     'over_due' => 0,
    //                     'over_due_fr' => 0,
    //                     'current_date' => date('Y-m-d'),
    //                     'last_month_date' => date('Y-m-d', strtotime('-1 month')),
    //                     'average_srv_time' => '0h 0m 0d',
    //                     'total_created' => 0,
    //                     'total_resolved' => 0,
    //                     'ticket_closed_by_team' => 0,
    //                     'ticket_open_by_team' => 0,
    //                     'ticket_forwarded' => 0,
    //                     'avg_open_ticket_age' => 0,
    //                     'escalated_in' => 0,
    //                 ];
    //             }

    //             // Update ticket counts for the team
    //             $averagesByTeam[$team_id]['team_name'] = $ticketData->team_name ?? 0;
    //             $averagesByTeam[$team_id]['total_created'] = $ticketData->total_created ?? 0;
    //             $averagesByTeam[$team_id]['total_resolved'] = $ticketData->total_resolved ?? 0;
    //             $averagesByTeam[$team_id]['ticket_closed_by_team'] = $ticketData->ticket_closed_by_team ?? 0;
    //             $averagesByTeam[$team_id]['ticket_open_by_team'] = $ticketData->ticket_open_by_team ?? 0;
    //             $averagesByTeam[$team_id]['ticket_forwarded'] = $ticketData->ticket_forwarded ?? 0;
    //             $averagesByTeam[$team_id]['avg_open_ticket_age'] = $ticketData->avg_open_ticket_age ?? 0;
    //             $averagesByTeam[$team_id]['escalated_in'] = $ticketData->escalated_in ?? 0;

    //             // Update average_srv_time if it exists in $averagesBySrvTeam
    //             if (isset($averagesBySrvTeam[$team_id]['average_srv_time'])) {
    //                 $averagesByTeam[$team_id]['average_srv_time'] = $averagesBySrvTeam[$team_id]['average_srv_time'];
    //             }
    //         }

    //         // Filter out departments where both ticket counts are 0
    //         $filteredAverages = array_filter($averagesByTeam, function ($teamData) {
    //             return $teamData['ticket_closed_by_team'] > 0 || $teamData['ticket_open_by_team'] > 0 || $teamData['total_created'] > 0 || $teamData['total_resolved'] > 0;
    //         });

    //         // $finalOutput = array_values($averagesByTeam);
    //         $finalOutput = array_values($filteredAverages);

    //         // if ((empty($fromDate)) && (empty($toDate))) {

    //         //     Cache::put($cacheKey, $finalOutput, now()->addMinutes(60));
    //         //     Log::info("Cache miss for key: {$cacheKey}. Stored new data in cache.");
    //         // }


    //         return ApiResponse::success($finalOutput, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Failed", 500);
    //     }
    // }

    public function teamReportForDashboard(Request $request)
    {
        try {
            $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
            $toDate   = !empty($request->toDate)   ? (new DateTime($request->toDate))->format('Y-m-d')   : "";

            $isDefaultRange = empty($fromDate) && empty($toDate);

            // Date conditions with proper aliases
            $s2DateCond = $isDefaultRange
                ? "s2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(s2.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $tshDateCond = $isDefaultRange
                ? "tsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(tsh.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $tfhDateCond = $isDefaultRange
                ? "tfh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(tfh.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $at1DateCond = $isDefaultRange
                ? "at1.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(at1.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $at2DateCond = $isDefaultRange
                ? "at2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(at2.created_at) BETWEEN '$fromDate' AND '$toDate'";

            $innerDateCond = $isDefaultRange
                ? "th_inner.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"
                : "DATE(th_inner.created_at) BETWEEN '$fromDate' AND '$toDate'";

            // ─── FR SLA averages ──────────────────────────────────────────────────
            $frAverages = DB::select("
                SELECT 
                    frc.team_id,
                    t.team_name,
                    SUM(ABS(TIMESTAMPDIFF(SECOND, s2.created_at, s1.created_at))) AS total_time_in_seconds,
                    COUNT(*) AS ticket_count
                FROM helpdesk.first_res_sla_histories s2
                JOIN helpdesk.first_res_sla_histories s1
                    ON  s1.ticket_number       = s2.ticket_number
                    AND s1.first_res_config_id = s2.first_res_config_id
                    AND s1.sla_status          IN (0, 1)
                JOIN helpdesk.first_res_configs frc ON s2.first_res_config_id = frc.id
                JOIN helpdesk.teams t ON t.id = frc.team_id
                WHERE s2.sla_status = 2
                AND $s2DateCond
                GROUP BY frc.team_id, t.team_name
            ");

            $averagesByTeam = [];
            foreach ($frAverages as $row) {
                $avg = $row->total_time_in_seconds / $row->ticket_count;
                $averagesByTeam[$row->team_id] = [
                    'team_id'          => $row->team_id,
                    'team_name'        => $row->team_name,
                    'total_time'       => formatTime($row->total_time_in_seconds),
                    'average_time'     => formatTime($avg),
                    'over_due'         => 0,
                    'over_due_fr'      => 0,
                    'average_srv_time' => '0h 0m 0d',
                ];
            }

            // ─── SRV SLA averages ─────────────────────────────────────────────────
            $srvAverages = DB::select("
                SELECT 
                    ssc.team_id,
                    t.team_name,
                    SUM(ABS(TIMESTAMPDIFF(SECOND, s2.created_at, s1.created_at))) AS total_time_in_seconds,
                    COUNT(*) AS ticket_count
                FROM helpdesk.srv_time_subcat_sla_histories s2
                JOIN helpdesk.srv_time_subcat_sla_histories s1
                    ON  s1.ticket_number        = s2.ticket_number
                    AND s1.sla_subcat_config_id = s2.sla_subcat_config_id
                    AND s1.sla_status           IN (0, 1)
                JOIN helpdesk.sla_subcat_configs ssc ON s2.sla_subcat_config_id = ssc.id
                JOIN helpdesk.teams t ON t.id = ssc.team_id
                WHERE s2.sla_status = 2
                AND $s2DateCond
                GROUP BY ssc.team_id, t.team_name
            ");

            $averagesBySrvTeam = [];
            foreach ($srvAverages as $row) {
                $avg = $row->total_time_in_seconds / $row->ticket_count;
                $averagesBySrvTeam[$row->team_id] = [
                    'team_id'          => $row->team_id,
                    'total_time'       => formatTime($row->total_time_in_seconds),
                    'average_srv_time' => formatTime($avg),
                ];
            }

            // ─── Overdue SRV ──────────────────────────────────────────────────────
            $overDueCounts = DB::select("
                SELECT t.id AS team_id, COUNT(DISTINCT tsh.ticket_number) AS over_due
                FROM helpdesk.srv_time_subcat_sla_histories tsh
                JOIN helpdesk.sla_subcat_configs ssc ON tsh.sla_subcat_config_id = ssc.id
                JOIN helpdesk.teams t ON t.id = ssc.team_id
                LEFT JOIN helpdesk.open_tickets ot  ON ot.ticket_number = tsh.ticket_number
                LEFT JOIN helpdesk.close_tickets ct ON ct.ticket_number = tsh.ticket_number
                WHERE tsh.sla_status = 0
                AND $tshDateCond
                AND (ot.ticket_number IS NOT NULL OR ct.ticket_number IS NOT NULL)
                GROUP BY t.id
            ");
            $overDueMap = array_column($overDueCounts, 'over_due', 'team_id');

            // ─── Overdue FR ───────────────────────────────────────────────────────
            $overDueCountsFr = DB::select("
                SELECT t.id AS team_id, COUNT(DISTINCT tfh.ticket_number) AS over_due_fr
                FROM helpdesk.first_res_sla_histories tfh
                JOIN helpdesk.first_res_configs frc ON tfh.first_res_config_id = frc.id
                JOIN helpdesk.teams t ON t.id = frc.team_id
                LEFT JOIN helpdesk.open_tickets ot  ON ot.ticket_number = tfh.ticket_number
                LEFT JOIN helpdesk.close_tickets ct ON ct.ticket_number = tfh.ticket_number
                WHERE tfh.sla_status = 0
                AND $tfhDateCond
                AND (ot.ticket_number IS NOT NULL OR ct.ticket_number IS NOT NULL)
                GROUP BY t.id
            ");
            $overDueFrMap = array_column($overDueCountsFr, 'over_due_fr', 'team_id');

            // ─── Ticket counts ────────────────────────────────────────────────────
            $ticketCounts = DB::select("
                WITH AllTickets AS (
                    SELECT * FROM helpdesk.open_tickets
                    UNION ALL
                    SELECT * FROM helpdesk.close_tickets
                ),
                TicketHistoryWithLags AS (
                    SELECT id, ticket_number, team_id,
                        LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
                    FROM helpdesk.ticket_histories
                ),
                EscalationCheck AS (
                    SELECT *, LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
                    FROM TicketHistoryWithLags
                )
                SELECT 
                    te.id AS team_id,
                    te.team_name,
                    COALESCE(ticket_stats.total_created, 0)                            AS total_created,
                    COALESCE(ticket_statuses.total_resolved, 0)                        AS total_resolved,
                    COALESCE(ticket_statuses.ticket_closed_by_team, 0)                 AS ticket_closed_by_team,
                    COALESCE(ticket_statuses.ticket_open_by_team, 0)                   AS ticket_open_by_team,
                    COALESCE(fq.ticket_count_work, 0)                                  AS total_work_ticket_count,
                    COALESCE(sq.ticket_count, 0)                                       AS ticket_count_tickets,
                    COALESCE(uq.ticket_count_tickets_updated_by_other_team, 0)         AS ticket_forwarded,
                    COALESCE(avg_ticket_age.avg_open_ticket_age, '0d 0h 0m')           AS avg_open_ticket_age,
                    COALESCE(escalated_in.total_escalated_in, 0)                       AS escalated_in
                FROM helpdesk.teams te
                -- Total Created Tickets
                LEFT JOIN (
                    SELECT utm.team_id, COUNT(DISTINCT at1.ticket_number) AS total_created
                    FROM AllTickets at1
                    LEFT JOIN helpdesk.user_team_mappings utm ON at1.user_id = utm.user_id
                    WHERE $at1DateCond
                    GROUP BY utm.team_id
                ) AS ticket_stats ON te.id = ticket_stats.team_id
                -- Ticket Status Counts
                LEFT JOIN (
                    SELECT 
                        at2.team_id,
                        COUNT(DISTINCT CASE WHEN at2.status_id = 6  THEN at2.ticket_number END) AS ticket_closed_by_team,
                        COUNT(DISTINCT CASE WHEN at2.status_id != 6 THEN at2.ticket_number END) AS ticket_open_by_team,
                        COUNT(DISTINCT CASE WHEN at2.status_id = 2  THEN at2.ticket_number END) AS total_resolved
                    FROM AllTickets at2
                    WHERE $at2DateCond
                    GROUP BY at2.team_id
                ) AS ticket_statuses ON te.id = ticket_statuses.team_id
                -- Total Worked Tickets
                LEFT JOIN (
                    SELECT th.team_id, COUNT(DISTINCT th.ticket_number) AS ticket_count_work
                    FROM helpdesk.ticket_histories th
                    WHERE th.created_at IN (
                        SELECT MIN(th_inner.created_at)
                        FROM helpdesk.ticket_histories th_inner
                        WHERE $innerDateCond
                        GROUP BY th_inner.ticket_number
                    )
                    GROUP BY th.team_id
                ) AS fq ON te.id = fq.team_id
                -- Total Tickets Count
                LEFT JOIN (
                    SELECT at1.team_id, COUNT(DISTINCT at1.ticket_number) AS ticket_count
                    FROM AllTickets at1
                    WHERE $at1DateCond
                    GROUP BY at1.team_id
                ) AS sq ON te.id = sq.team_id
                -- Tickets Forwarded
                LEFT JOIN (
                    SELECT team_id,
                        SUM(CASE WHEN lead_team_id IS NOT NULL AND lead_team_id <> team_id THEN 1 ELSE 0 END) AS ticket_count_tickets_updated_by_other_team
                    FROM EscalationCheck
                    WHERE prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL
                    GROUP BY team_id
                ) AS uq ON te.id = uq.team_id
                -- Average Open Ticket Age
                LEFT JOIN (
                    SELECT at2.team_id,
                        CASE 
                            WHEN COUNT(DISTINCT CASE WHEN at2.status_id != 6 THEN at2.ticket_number END) = 0 
                                THEN '0 day 0 hour 0 min'
                            ELSE CONCAT(
                                FLOOR(SUM(TIMESTAMPDIFF(SECOND, at2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN at2.status_id != 6 THEN at2.ticket_number END) / 86400), 'd ',
                                FLOOR(MOD(SUM(TIMESTAMPDIFF(SECOND, at2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN at2.status_id != 6 THEN at2.ticket_number END), 86400) / 3600), 'h ',
                                FLOOR(MOD(SUM(TIMESTAMPDIFF(SECOND, at2.created_at, NOW())) / COUNT(DISTINCT CASE WHEN at2.status_id != 6 THEN at2.ticket_number END), 3600) / 60), 'm'
                            )
                        END AS avg_open_ticket_age
                    FROM AllTickets at2
                    WHERE $at2DateCond
                    AND at2.status_id != 6
                    GROUP BY at2.team_id
                ) AS avg_ticket_age ON te.id = avg_ticket_age.team_id
                -- Escalated In
                LEFT JOIN (
                    SELECT team_id,
                        SUM(CASE WHEN prev_team_id IS NOT NULL AND prev_team_id <> team_id THEN 1 ELSE 0 END) AS total_escalated_in
                    FROM EscalationCheck
                    WHERE prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL
                    GROUP BY team_id
                ) AS escalated_in ON te.id = escalated_in.team_id
                ORDER BY te.team_name
            ");

            // ─── Merge everything ─────────────────────────────────────────────────
            foreach ($ticketCounts as $ticketData) {
                $team_id = $ticketData->team_id;

                if (!isset($averagesByTeam[$team_id])) {
                    $averagesByTeam[$team_id] = [
                        'team_id'          => $team_id,
                        'team_name'        => $ticketData->team_name,
                        'total_time'       => '00:00:00',
                        'average_time'     => '0h 0m 0d',
                        'average_srv_time' => '0h 0m 0d',
                        'over_due'         => 0,
                        'over_due_fr'      => 0,
                    ];
                }

                $averagesByTeam[$team_id] = array_merge($averagesByTeam[$team_id], [
                    'team_name'             => $ticketData->team_name,
                    'total_created'         => $ticketData->total_created,
                    'total_resolved'        => $ticketData->total_resolved,
                    'ticket_closed_by_team' => $ticketData->ticket_closed_by_team,
                    'ticket_open_by_team'   => $ticketData->ticket_open_by_team,
                    'ticket_forwarded'      => $ticketData->ticket_forwarded,
                    'avg_open_ticket_age'   => $ticketData->avg_open_ticket_age,
                    'escalated_in'          => $ticketData->escalated_in,
                    'over_due'              => $overDueMap[$team_id]   ?? 0,
                    'over_due_fr'           => $overDueFrMap[$team_id] ?? 0,
                    'average_srv_time'      => $averagesBySrvTeam[$team_id]['average_srv_time'] ?? '0h 0m 0d',
                ]);
            }

            // ─── Filter & return ──────────────────────────────────────────────────
            $finalOutput = array_values(array_filter($averagesByTeam, fn($t) =>
                $t['ticket_closed_by_team'] > 0 ||
                $t['ticket_open_by_team']   > 0 ||
                $t['total_created']         > 0 ||
                $t['total_resolved']        > 0
            ));

            return ApiResponse::success($finalOutput, "Success", 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }



    // public function teamTicketDetailsGraph(Request $request)
    // {

    //     $teamId = $request->team;
    //     $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //     $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

    //     if (!empty($teamId) && empty($fromDate) && empty($toDate)) {

    //         $ticketCounts = DB::select("
    //                         WITH first_query AS (
    //                             SELECT DATE(MIN(th.created_at)) AS date, COUNT(DISTINCT th.ticket_number) AS ticket_count_work
    //                             FROM helpdesk.ticket_histories th
    //                             WHERE th.team_id = '$teamId'
    //                             AND th.created_at IN (
    //                                 SELECT MIN(th_inner.created_at) 
    //                                 FROM helpdesk.ticket_histories th_inner 
    //                                 WHERE th_inner.team_id = '$teamId'
    //                                 AND th_inner.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                                 GROUP BY th_inner.ticket_number
    //                             )
    //                             GROUP BY DATE(th.created_at)
    //                         ),

    //                         second_query AS (
    //                             SELECT DATE(th.created_at) AS date, COUNT(DISTINCT ticket_number) AS ticket_count
    //                             FROM helpdesk.open_tickets th
    //                             WHERE th.team_id = '$teamId'
    //                             AND th.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(th.created_at)
    //                             UNION ALL
    //                             SELECT DATE(th.created_at) AS date, COUNT(DISTINCT ticket_number) AS ticket_count
    //                             FROM helpdesk.close_tickets th
    //                             WHERE th.team_id = '$teamId'
    //                             AND th.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(th.created_at)
    //                         ),

    //                         third_query AS (
    //                             SELECT DATE(t.created_at) AS date, COUNT(DISTINCT t.ticket_number) AS total_created
    //                             FROM helpdesk.open_tickets t
    //                             LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                             WHERE utm.team_id = '$teamId'
    //                             AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(t.created_at)
    //                             UNION ALL
    //                             SELECT DATE(t.created_at) AS date, COUNT(DISTINCT t.ticket_number) AS total_created
    //                             FROM helpdesk.close_tickets t
    //                             LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                             WHERE utm.team_id = '$teamId'
    //                             AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(t.created_at)
    //                         ),

    //                         fourth_query AS (
    //                             SELECT DATE(created_at) AS date, 
    //                                 COUNT(*) AS open_count,
    //                                 0 AS close_count
    //                             FROM helpdesk.open_tickets 
    //                             WHERE team_id = '$teamId'
    //                             AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(created_at)
                                
    //                             UNION ALL
                                
    //                             SELECT DATE(created_at) AS date, 
    //                                 0 AS open_count,
    //                                 COUNT(*) AS close_count
    //                             FROM helpdesk.close_tickets 
    //                             WHERE team_id = '$teamId'
    //                             AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(created_at)
    //                         ),

    //                         fifth_query AS (
    //                             SELECT DATE(tfh.created_at) AS date, COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
    //                             FROM helpdesk.first_res_configs frc
    //                             JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
    //                             LEFT JOIN helpdesk.open_tickets t ON t.ticket_number = tfh.ticket_number
    //                             WHERE tfh.sla_status = 0
    //                             AND frc.team_id = '$teamId'
    //                             AND tfh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(tfh.created_at)
                                
    //                             UNION ALL
                                
    //                             SELECT DATE(tfh.created_at) AS date, COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
    //                             FROM helpdesk.first_res_configs frc
    //                             JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
    //                             LEFT JOIN helpdesk.close_tickets t ON t.ticket_number = tfh.ticket_number
    //                             WHERE tfh.sla_status = 0
    //                             AND frc.team_id = '$teamId'
    //                             AND tfh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(tfh.created_at)
    //                         ),

    //                         sixth_query AS (
    //                             SELECT DATE(stsh.created_at) AS date, COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
    //                             FROM helpdesk.sla_subcat_configs tsh
    //                             JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
    //                             LEFT JOIN helpdesk.open_tickets t ON t.ticket_number = stsh.ticket_number
    //                             WHERE stsh.sla_status = 0
    //                             AND tsh.team_id = '$teamId'
    //                             AND stsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(stsh.created_at)
                                
    //                             UNION ALL
                                
    //                             SELECT DATE(stsh.created_at) AS date, COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
    //                             FROM helpdesk.sla_subcat_configs tsh
    //                             JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
    //                             LEFT JOIN helpdesk.close_tickets t ON t.ticket_number = stsh.ticket_number
    //                             WHERE stsh.sla_status = 0
    //                             AND tsh.team_id = '$teamId'
    //                             AND stsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(stsh.created_at)
    //                         ),

    //                         seventh_query AS (
    //                             SELECT DATE(th.created_at) AS date, COUNT(DISTINCT th.ticket_number) AS ticket_count_work_own_created
    //                             FROM helpdesk.ticket_histories th
    //                             JOIN helpdesk.user_team_mappings utm ON th.user_id = utm.user_id
    //                             WHERE th.team_id = '$teamId'
    //                             AND th.created_at IN (
    //                                 SELECT MIN(th_inner.created_at) 
    //                                 FROM helpdesk.ticket_histories th_inner 
    //                                 WHERE th_inner.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                                 GROUP BY th_inner.ticket_number
    //                             )
    //                             AND th.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(th.created_at)
    //                         ),

    //                         eighth_query AS (
    //                             WITH TicketHistoryWithLags AS (
    //                                 SELECT id, ticket_number, team_id, created_at,
    //                                     LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
    //                                 FROM ticket_histories
    //                             ),
    //                             EscalationCheck AS (
    //                                 SELECT id, ticket_number, team_id, created_at, prev_team_id,
    //                                     LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
    //                                 FROM TicketHistoryWithLags
    //                             )
    //                             SELECT 
    //                                 DATE(created_at) AS date,
    //                                 team_id,
    //                                 SUM(CASE WHEN lead_team_id IS NOT NULL AND lead_team_id <> team_id THEN 1 ELSE 0 END) AS ticket_count_external_work
    //                             FROM EscalationCheck
    //                             WHERE (prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL)
    //                             AND DATE(created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    //                             AND team_id = '$teamId'
    //                             GROUP BY DATE(created_at)
    //                         ),

    //                         ninth_query AS (
    //                             WITH TicketHistoryWithLags AS (
    //                                 SELECT DISTINCT ticket_number, team_id, created_at,
    //                                     LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS prev_team_id
    //                                 FROM helpdesk.ticket_histories
    //                             ),
    //                             EscalationCheck AS (
    //                                 SELECT DISTINCT ticket_number, team_id, created_at, prev_team_id,
    //                                     LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS lead_team_id
    //                                 FROM TicketHistoryWithLags
    //                             ),
    //                             EscalatedTickets AS (
    //                                 SELECT DISTINCT ticket_number
    //                                 FROM EscalationCheck
    //                                 WHERE prev_team_id IS NOT NULL 
    //                                 AND prev_team_id <> team_id
    //                                 AND created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    //                                 AND team_id = '$teamId'
    //                                 UNION
    //                                 SELECT DISTINCT ticket_number
    //                                 FROM helpdesk.ticket_histories t
    //                                 WHERE t.user_id != t.status_updated_by
    //                                 AND t.team_id = '$teamId'
    //                                 AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
    //                             )
    //                             SELECT 
    //                                 DATE(t.created_at) AS date,
    //                                 t.team_id,
    //                                 COUNT(DISTINCT t.ticket_number) AS escalated_in
    //                             FROM (
    //                                 SELECT created_at, team_id, ticket_number FROM helpdesk.open_tickets
    //                                 UNION ALL
    //                                 SELECT created_at, team_id, ticket_number FROM helpdesk.close_tickets
    //                             ) t
    //                             LEFT JOIN EscalatedTickets esc ON t.ticket_number = esc.ticket_number
    //                             WHERE esc.ticket_number IS NOT NULL
    //                             GROUP BY DATE(t.created_at), t.team_id
    //                             ORDER BY DATE(t.created_at) DESC
    //                         ),

    //                         combined_fourth AS (
    //                             SELECT 
    //                                 date,
    //                                 SUM(open_count) AS open_count,
    //                                 SUM(close_count) AS close_count
    //                             FROM fourth_query
    //                             GROUP BY date
    //                         )

    //                         SELECT 
    //                             fq.date,
    //                             COALESCE(fq.ticket_count_work, 0) AS total_work_ticket_count,
    //                             COALESCE(sq.ticket_count, 0) AS ticket_count_tickets,
    //                             COALESCE(tq.total_created, 0) AS total_created_tickets,
    //                             COALESCE(cf.open_count, 0) AS open_ticket,
    //                             COALESCE(cf.close_count, 0) AS close_ticket,
    //                             COALESCE(fiq.fr_violated_sla, 0) AS fr_violated_sla,
    //                             COALESCE(siq.srv_violated_sla, 0) AS srv_violated_sla,
    //                             COALESCE(svq.ticket_count_work_own_created, 0) AS ticket_count_work_own_created,
    //                             COALESCE(eq.ticket_count_external_work, 0) AS ticket_forwarded,
    //                             COALESCE(nq.escalated_in, 0) AS escalated_in
    //                         FROM first_query fq
    //                         LEFT JOIN second_query sq ON fq.date = sq.date
    //                         LEFT JOIN third_query tq ON fq.date = tq.date
    //                         LEFT JOIN combined_fourth cf ON fq.date = cf.date
    //                         LEFT JOIN fifth_query fiq ON fq.date = fiq.date
    //                         LEFT JOIN sixth_query siq ON fq.date = siq.date
    //                         LEFT JOIN seventh_query svq ON fq.date = svq.date
    //                         LEFT JOIN eighth_query eq ON fq.date = eq.date
    //                         LEFT JOIN ninth_query nq ON fq.date = nq.date
    //                         ORDER BY fq.date
    //                     ");


    //         $ticketCountsTotal = DB::select("
    //                     WITH first_query AS (
    //                         SELECT th.team_id, COUNT(DISTINCT ticket_number) AS ticket_count_work
    //                         FROM helpdesk.ticket_histories th
    //                         WHERE th.team_id = '$teamId'
    //                         AND th.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                     ),
                        
    //                     second_query AS (
    //                         SELECT SUM(ticket_count) AS ticket_count
    //                         FROM (
    //                             SELECT COUNT(DISTINCT ticket_number) AS ticket_count
    //                             FROM helpdesk.open_tickets th
    //                             WHERE th.team_id = '$teamId'
    //                             AND th.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                                
    //                             UNION ALL
                                
    //                             SELECT COUNT(DISTINCT ticket_number) AS ticket_count
    //                             FROM helpdesk.close_tickets th
    //                             WHERE th.team_id = '$teamId'
    //                             AND th.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                         ) combined_tickets
    //                     ),
                        
    //                     third_query AS (
    //                         SELECT SUM(total_created) AS total_created
    //                         FROM (
    //                             SELECT COUNT(DISTINCT t.ticket_number) AS total_created
    //                             FROM helpdesk.open_tickets t
    //                             LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                             WHERE utm.team_id = '$teamId'
    //                             AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                                
    //                             UNION ALL
                                
    //                             SELECT COUNT(DISTINCT t.ticket_number) AS total_created
    //                             FROM helpdesk.close_tickets t
    //                             LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                             WHERE utm.team_id = '$teamId'
    //                             AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                         ) combined_created
    //                     ),
                        
    //                     fourth_query AS (
    //                         SELECT 
    //                             SUM(open_count) AS open_count,
    //                             SUM(close_count) AS close_count
    //                         FROM (
    //                             SELECT 
    //                                 COUNT(*) AS open_count,
    //                                 0 AS close_count
    //                             FROM helpdesk.open_tickets 
    //                             WHERE team_id = '$teamId'
    //                             AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                                
    //                             UNION ALL
                                
    //                             SELECT 
    //                                 0 AS open_count,
    //                                 COUNT(*) AS close_count
    //                             FROM helpdesk.close_tickets 
    //                             WHERE team_id = '$teamId'
    //                             AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                         ) combined_counts
    //                     ),

                        
    //                     fifth_query AS (
    //                         SELECT SUM(fr_violated_sla) AS fr_violated_sla
    //                         FROM (
    //                             SELECT COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
    //                             FROM helpdesk.first_res_configs frc
    //                             JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
    //                             LEFT JOIN helpdesk.open_tickets t ON t.ticket_number = tfh.ticket_number
    //                             WHERE tfh.sla_status = 0
    //                             AND frc.team_id = '$teamId'
    //                             AND tfh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                                
    //                             UNION ALL
                                
    //                             SELECT COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
    //                             FROM helpdesk.first_res_configs frc
    //                             JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
    //                             LEFT JOIN helpdesk.close_tickets t ON t.ticket_number = tfh.ticket_number
    //                             WHERE tfh.sla_status = 0
    //                             AND frc.team_id = '$teamId'
    //                             AND tfh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                         ) combined_fr
    //                     ),
                        
    //                     sixth_query AS (
    //                         SELECT SUM(srv_violated_sla) AS srv_violated_sla
    //                         FROM (
    //                             SELECT COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
    //                             FROM helpdesk.sla_subcat_configs tsh
    //                             JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
    //                             LEFT JOIN helpdesk.open_tickets t ON t.ticket_number = stsh.ticket_number
    //                             WHERE stsh.sla_status = 0
    //                             AND tsh.team_id = '$teamId'
    //                             AND tsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                                
    //                             UNION ALL
                                
    //                             SELECT COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
    //                             FROM helpdesk.sla_subcat_configs tsh
    //                             JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
    //                             LEFT JOIN helpdesk.close_tickets t ON t.ticket_number = stsh.ticket_number
    //                             WHERE stsh.sla_status = 0
    //                             AND tsh.team_id = '$teamId'
    //                             AND tsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                         ) combined_srv
    //                     ),
                        
    //                     seventh_query AS (
    //                         SELECT COUNT(DISTINCT th.ticket_number) AS ticket_count_work_own_created
    //                         FROM helpdesk.ticket_histories th
    //                         JOIN helpdesk.user_team_mappings utm ON th.user_id = utm.user_id
    //                         WHERE th.team_id = '$teamId'
    //                         AND th.created_at IN (
    //                             SELECT MIN(th_inner.created_at) 
    //                             FROM helpdesk.ticket_histories th_inner 
    //                             WHERE th_inner.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY th_inner.ticket_number
    //                         )
    //                         AND th.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                     ),
                        
    //                     eighth_query AS (
    //                         WITH TicketHistoryWithLags AS (
    //                             SELECT id, ticket_number, team_id, created_at,
    //                                 LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
    //                             FROM ticket_histories
    //                         ),
    //                         EscalationCheck AS (
    //                             SELECT id, ticket_number, team_id, created_at, prev_team_id,
    //                                 LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
    //                             FROM TicketHistoryWithLags
    //                         )
    //                         SELECT team_id,
    //                             SUM(CASE WHEN lead_team_id IS NOT NULL AND lead_team_id <> team_id THEN 1 ELSE 0 END) AS ticket_count_external_work
    //                         FROM EscalationCheck
    //                         WHERE (prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL)
    //                         AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                         AND team_id = '$teamId'
    //                     ),
                        
    //                     ninth_query AS (
    //                         WITH TicketHistoryWithLags AS (
    //                             SELECT DISTINCT ticket_number, team_id, created_at,
    //                                 LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS prev_team_id
    //                             FROM helpdesk.ticket_histories
    //                         ),
    //                         EscalationCheck AS (
    //                             SELECT DISTINCT ticket_number, team_id, created_at, prev_team_id,
    //                                 LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS lead_team_id
    //                             FROM TicketHistoryWithLags
    //                         ),
    //                         EscalatedTickets AS (
    //                             SELECT DISTINCT ticket_number
    //                             FROM EscalationCheck
    //                             WHERE prev_team_id IS NOT NULL 
    //                             AND prev_team_id <> team_id
    //                             AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             AND team_id = '$teamId'
                                
    //                             UNION
                                
    //                             SELECT DISTINCT ticket_number
    //                             FROM helpdesk.ticket_histories t
    //                             WHERE t.user_id != t.status_updated_by
    //                             AND t.team_id = '$teamId'
    //                             AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                         )
    //                         SELECT COUNT(DISTINCT t.ticket_number) AS escalated_in
    //                         FROM (
    //                             SELECT ticket_number FROM helpdesk.open_tickets
    //                             UNION ALL
    //                             SELECT ticket_number FROM helpdesk.close_tickets
    //                         ) t
    //                         LEFT JOIN EscalatedTickets esc ON t.ticket_number = esc.ticket_number
    //                         WHERE esc.ticket_number IS NOT NULL
    //                     )
                        
    //                     SELECT 
    //                         fq.team_id,
    //                         COALESCE(fq.ticket_count_work, 0) AS total_work_ticket_count,
    //                         COALESCE(sq.ticket_count, 0) AS ticket_count_tickets,
    //                         COALESCE(tq.total_created, 0) AS total_created_tickets,
    //                         COALESCE(frq.open_count, 0) AS open_ticket,
    //                         COALESCE(frq.close_count, 0) AS close_ticket,
    //                         COALESCE(fiq.fr_violated_sla, 0) AS fr_violated_sla,
    //                         COALESCE(siq.srv_violated_sla, 0) AS srv_violated_sla,
    //                         COALESCE(svq.ticket_count_work_own_created, 0) AS ticket_count_work_own_created,
    //                         COALESCE(eq.ticket_count_external_work, 0) AS ticket_forwarded,
    //                         COALESCE(nq.escalated_in, 0) AS escalated_in
    //                     FROM first_query fq
    //                     CROSS JOIN second_query sq
    //                     CROSS JOIN third_query tq
    //                     CROSS JOIN fourth_query frq
    //                     CROSS JOIN fifth_query fiq
    //                     CROSS JOIN sixth_query siq
    //                     CROSS JOIN seventh_query svq
    //                     CROSS JOIN eighth_query eq
    //                     CROSS JOIN ninth_query nq
    //                 ");
    //         $response = [
    //             'ticketCounts' => $ticketCounts,
    //             'ticketCountsTotal' => $ticketCountsTotal
    //         ];
    //         return ApiResponse::success($response, "Success", 200);
    //     } else if (!empty($teamId) && !empty($fromDate) && !empty($toDate)) {
            


    //         $ticketCounts = DB::select("
    //                         WITH first_query AS (
    //                             SELECT DATE(MIN(th.created_at)) AS date, COUNT(DISTINCT th.ticket_number) AS ticket_count_work
    //                             FROM helpdesk.ticket_histories th
    //                             WHERE th.team_id = '$teamId'
    //                             AND th.created_at IN (
    //                                 SELECT MIN(th_inner.created_at) 
    //                                 FROM helpdesk.ticket_histories th_inner 
    //                                 WHERE th_inner.team_id = '$teamId'
    //                                 AND DATE(th_inner.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                                 GROUP BY th_inner.ticket_number
    //                             )
    //                             GROUP BY DATE(th.created_at)
    //                         ),

    //                         second_query AS (
    //                             SELECT DATE(th.created_at) AS date, COUNT(DISTINCT ticket_number) AS ticket_count
    //                             FROM helpdesk.open_tickets th
    //                             WHERE th.team_id = '$teamId'
    //                             AND th.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             GROUP BY DATE(th.created_at)
    //                             UNION ALL
    //                             SELECT DATE(th.created_at) AS date, COUNT(DISTINCT ticket_number) AS ticket_count
    //                             FROM helpdesk.close_tickets th
    //                             WHERE th.team_id = '$teamId'
    //                             AND DATE(th.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY DATE(th.created_at)
    //                         ),

    //                         third_query AS (
    //                             SELECT DATE(t.created_at) AS date, COUNT(DISTINCT t.ticket_number) AS total_created
    //                             FROM helpdesk.open_tickets t
    //                             LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                             WHERE utm.team_id = '$teamId'
    //                             AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY DATE(t.created_at)
    //                             UNION ALL
    //                             SELECT DATE(t.created_at) AS date, COUNT(DISTINCT t.ticket_number) AS total_created
    //                             FROM helpdesk.close_tickets t
    //                             LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                             WHERE utm.team_id = '$teamId'
    //                             AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY DATE(t.created_at)
    //                         ),

    //                         fourth_query AS (
    //                             SELECT DATE(created_at) AS date, 
    //                                 COUNT(*) AS open_count,
    //                                 0 AS close_count
    //                             FROM helpdesk.open_tickets 
    //                             WHERE team_id = '$teamId'
    //                             AND DATE(created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY DATE(created_at)
                                
    //                             UNION ALL
                                
    //                             SELECT DATE(created_at) AS date, 
    //                                 0 AS open_count,
    //                                 COUNT(*) AS close_count
    //                             FROM helpdesk.close_tickets 
    //                             WHERE team_id = '$teamId'
    //                             AND DATE(created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY DATE(created_at)
    //                         ),

    //                         fifth_query AS (
    //                             SELECT DATE(tfh.created_at) AS date, COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
    //                             FROM helpdesk.first_res_configs frc
    //                             JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
    //                             LEFT JOIN helpdesk.open_tickets t ON t.ticket_number = tfh.ticket_number
    //                             WHERE tfh.sla_status = 0
    //                             AND frc.team_id = '$teamId'
    //                             AND DATE(tfh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY DATE(tfh.created_at)
                                
    //                             UNION ALL
                                
    //                             SELECT DATE(tfh.created_at) AS date, COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
    //                             FROM helpdesk.first_res_configs frc
    //                             JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
    //                             LEFT JOIN helpdesk.close_tickets t ON t.ticket_number = tfh.ticket_number
    //                             WHERE tfh.sla_status = 0
    //                             AND frc.team_id = '$teamId'
    //                             AND DATE(tfh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY DATE(tfh.created_at)
    //                         ),

    //                         sixth_query AS (
    //                             SELECT DATE(stsh.created_at) AS date, COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
    //                             FROM helpdesk.sla_subcat_configs tsh
    //                             JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
    //                             LEFT JOIN helpdesk.open_tickets t ON t.ticket_number = stsh.ticket_number
    //                             WHERE stsh.sla_status = 0
    //                             AND tsh.team_id = '$teamId'
    //                             AND DATE(stsh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY DATE(stsh.created_at)
                                
    //                             UNION ALL
                                
    //                             SELECT DATE(stsh.created_at) AS date, COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
    //                             FROM helpdesk.sla_subcat_configs tsh
    //                             JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
    //                             LEFT JOIN helpdesk.close_tickets t ON t.ticket_number = stsh.ticket_number
    //                             WHERE stsh.sla_status = 0
    //                             AND tsh.team_id = '$teamId'
    //                             AND DATE(stsh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY DATE(stsh.created_at)
    //                         ),

    //                         seventh_query AS (
    //                             SELECT DATE(th.created_at) AS date, COUNT(DISTINCT th.ticket_number) AS ticket_count_work_own_created
    //                             FROM helpdesk.ticket_histories th
    //                             JOIN helpdesk.user_team_mappings utm ON th.user_id = utm.user_id
    //                             WHERE th.team_id = '$teamId'
    //                             AND th.created_at IN (
    //                                 SELECT MIN(th_inner.created_at) 
    //                                 FROM helpdesk.ticket_histories th_inner 
    //                                 WHERE th_inner.created_at >= '$fromDate' AND th_inner.created_at <= '$toDate'
    //                                 GROUP BY th_inner.ticket_number
    //                             )
    //                             AND DATE(th.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY DATE(th.created_at)
    //                         ),

    //                         eighth_query AS (
    //                             WITH TicketHistoryWithLags AS (
    //                                 SELECT id, ticket_number, team_id, created_at,
    //                                     LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
    //                                 FROM ticket_histories
    //                             ),
    //                             EscalationCheck AS (
    //                                 SELECT id, ticket_number, team_id, created_at, prev_team_id,
    //                                     LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
    //                                 FROM TicketHistoryWithLags
    //                             )
    //                             SELECT 
    //                                 DATE(created_at) AS date,
    //                                 team_id,
    //                                 SUM(CASE WHEN lead_team_id IS NOT NULL AND lead_team_id <> team_id THEN 1 ELSE 0 END) AS ticket_count_external_work
    //                             FROM EscalationCheck
    //                             WHERE (prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL)
    //                             AND DATE(created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             AND team_id = '$teamId'
    //                             GROUP BY DATE(created_at)
    //                         ),

    //                         ninth_query AS (
    //                             WITH TicketHistoryWithLags AS (
    //                                 SELECT DISTINCT ticket_number, team_id, created_at,
    //                                     LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS prev_team_id
    //                                 FROM helpdesk.ticket_histories
    //                             ),
    //                             EscalationCheck AS (
    //                                 SELECT DISTINCT ticket_number, team_id, created_at, prev_team_id,
    //                                     LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS lead_team_id
    //                                 FROM TicketHistoryWithLags
    //                             ),
    //                             EscalatedTickets AS (
    //                                 SELECT DISTINCT ticket_number
    //                                 FROM EscalationCheck
    //                                 WHERE prev_team_id IS NOT NULL 
    //                                 AND prev_team_id <> team_id
    //                                 AND created_at BETWEEN '$fromDate' AND '$toDate'
    //                                 AND team_id = '$teamId'
    //                                 UNION
    //                                 SELECT DISTINCT ticket_number
    //                                 FROM helpdesk.ticket_histories t
    //                                 WHERE t.user_id != t.status_updated_by
    //                                 AND t.team_id = '$teamId'
    //                                 AND t.created_at BETWEEN '$fromDate' AND '$toDate'
    //                             )
    //                             SELECT 
    //                                 DATE(t.created_at) AS date,
    //                                 t.team_id,
    //                                 COUNT(DISTINCT t.ticket_number) AS escalated_in
    //                             FROM (
    //                                 SELECT created_at, team_id, ticket_number FROM helpdesk.open_tickets
    //                                 UNION ALL
    //                                 SELECT created_at, team_id, ticket_number FROM helpdesk.close_tickets
    //                             ) t
    //                             LEFT JOIN EscalatedTickets esc ON t.ticket_number = esc.ticket_number
    //                             WHERE esc.ticket_number IS NOT NULL
    //                             GROUP BY DATE(t.created_at), t.team_id
    //                             ORDER BY DATE(t.created_at) DESC
    //                         ),

    //                         combined_fourth AS (
    //                             SELECT 
    //                                 date,
    //                                 SUM(open_count) AS open_count,
    //                                 SUM(close_count) AS close_count
    //                             FROM fourth_query
    //                             GROUP BY date
    //                         )

    //                         SELECT 
    //                             fq.date,
    //                             COALESCE(fq.ticket_count_work, 0) AS total_work_ticket_count,
    //                             COALESCE(sq.ticket_count, 0) AS ticket_count_tickets,
    //                             COALESCE(tq.total_created, 0) AS total_created_tickets,
    //                             COALESCE(cf.open_count, 0) AS open_ticket,
    //                             COALESCE(cf.close_count, 0) AS close_ticket,
    //                             COALESCE(fiq.fr_violated_sla, 0) AS fr_violated_sla,
    //                             COALESCE(siq.srv_violated_sla, 0) AS srv_violated_sla,
    //                             COALESCE(svq.ticket_count_work_own_created, 0) AS ticket_count_work_own_created,
    //                             COALESCE(eq.ticket_count_external_work, 0) AS ticket_forwarded,
    //                             COALESCE(nq.escalated_in, 0) AS escalated_in
    //                         FROM first_query fq
    //                         LEFT JOIN second_query sq ON fq.date = sq.date
    //                         LEFT JOIN third_query tq ON fq.date = tq.date
    //                         LEFT JOIN combined_fourth cf ON fq.date = cf.date
    //                         LEFT JOIN fifth_query fiq ON fq.date = fiq.date
    //                         LEFT JOIN sixth_query siq ON fq.date = siq.date
    //                         LEFT JOIN seventh_query svq ON fq.date = svq.date
    //                         LEFT JOIN eighth_query eq ON fq.date = eq.date
    //                         LEFT JOIN ninth_query nq ON fq.date = nq.date
    //                         ORDER BY fq.date
    //                     ");

           

    //         $ticketCountsTotal = DB::select("
    //                     WITH first_query AS (
    //                         SELECT th.team_id, COUNT(DISTINCT ticket_number) AS ticket_count_work
    //                         FROM helpdesk.ticket_histories th
    //                         WHERE th.team_id = '$teamId'
    //                         AND th.created_at BETWEEN '$fromDate' AND '$toDate'
    //                     ),
                        
    //                     second_query AS (
    //                         SELECT SUM(ticket_count) AS ticket_count
    //                         FROM (
    //                             SELECT COUNT(DISTINCT ticket_number) AS ticket_count
    //                             FROM helpdesk.open_tickets th
    //                             WHERE th.team_id = '$teamId'
    //                             AND th.created_at BETWEEN '$fromDate' AND '$toDate'
                                
    //                             UNION ALL
                                
    //                             SELECT COUNT(DISTINCT ticket_number) AS ticket_count
    //                             FROM helpdesk.close_tickets th
    //                             WHERE th.team_id = '$teamId'
    //                             AND th.created_at BETWEEN '$fromDate' AND '$toDate'
    //                         ) combined_tickets
    //                     ),
                        
    //                     third_query AS (
    //                         SELECT SUM(total_created) AS total_created
    //                         FROM (
    //                             SELECT COUNT(DISTINCT t.ticket_number) AS total_created
    //                             FROM helpdesk.open_tickets t
    //                             LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                             WHERE utm.team_id = '$teamId'
    //                             AND t.created_at BETWEEN '$fromDate' AND '$toDate'
                                
    //                             UNION ALL
                                
    //                             SELECT COUNT(DISTINCT t.ticket_number) AS total_created
    //                             FROM helpdesk.close_tickets t
    //                             LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                             WHERE utm.team_id = '$teamId'
    //                             AND t.created_at BETWEEN '$fromDate' AND '$toDate'
    //                         ) combined_created
    //                     ),
                        
    //                     fourth_query AS (
    //                         SELECT 
    //                             SUM(open_count) AS open_count,
    //                             SUM(close_count) AS close_count
    //                         FROM (
    //                             SELECT 
    //                                 COUNT(*) AS open_count,
    //                                 0 AS close_count
    //                             FROM helpdesk.open_tickets 
    //                             WHERE team_id = '$teamId'
    //                             AND created_at BETWEEN '$fromDate' AND '$toDate'
                                
    //                             UNION ALL
                                
    //                             SELECT 
    //                                 0 AS open_count,
    //                                 COUNT(*) AS close_count
    //                             FROM helpdesk.close_tickets 
    //                             WHERE team_id = '$teamId'
    //                             AND created_at BETWEEN '$fromDate' AND '$toDate'
    //                         ) combined_counts
    //                     ),

                        
    //                     fifth_query AS (
    //                         SELECT SUM(fr_violated_sla) AS fr_violated_sla
    //                         FROM (
    //                             SELECT COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
    //                             FROM helpdesk.first_res_configs frc
    //                             JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
    //                             LEFT JOIN helpdesk.open_tickets t ON t.ticket_number = tfh.ticket_number
    //                             WHERE tfh.sla_status = 0
    //                             AND frc.team_id = '$teamId'
    //                             AND tfh.created_at BETWEEN '$fromDate' AND '$toDate'
                                
    //                             UNION ALL
                                
    //                             SELECT COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
    //                             FROM helpdesk.first_res_configs frc
    //                             JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
    //                             LEFT JOIN helpdesk.close_tickets t ON t.ticket_number = tfh.ticket_number
    //                             WHERE tfh.sla_status = 0
    //                             AND frc.team_id = '$teamId'
    //                             AND tfh.created_at BETWEEN '$fromDate' AND '$toDate'
    //                         ) combined_fr
    //                     ),
                        
    //                     sixth_query AS (
    //                         SELECT SUM(srv_violated_sla) AS srv_violated_sla
    //                         FROM (
    //                             SELECT COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
    //                             FROM helpdesk.sla_subcat_configs tsh
    //                             JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
    //                             LEFT JOIN helpdesk.open_tickets t ON t.ticket_number = stsh.ticket_number
    //                             WHERE stsh.sla_status = 0
    //                             AND tsh.team_id = '$teamId'
    //                             AND tsh.created_at BETWEEN '$fromDate' AND '$toDate'
                                
    //                             UNION ALL
                                
    //                             SELECT COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
    //                             FROM helpdesk.sla_subcat_configs tsh
    //                             JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
    //                             LEFT JOIN helpdesk.close_tickets t ON t.ticket_number = stsh.ticket_number
    //                             WHERE stsh.sla_status = 0
    //                             AND tsh.team_id = '$teamId'
    //                             AND tsh.created_at BETWEEN '$fromDate' AND '$toDate'
    //                         ) combined_srv
    //                     ),
                        
    //                     seventh_query AS (
    //                         SELECT COUNT(DISTINCT th.ticket_number) AS ticket_count_work_own_created
    //                         FROM helpdesk.ticket_histories th
    //                         JOIN helpdesk.user_team_mappings utm ON th.user_id = utm.user_id
    //                         WHERE th.team_id = '$teamId'
    //                         AND th.created_at IN (
    //                             SELECT MIN(th_inner.created_at) 
    //                             FROM helpdesk.ticket_histories th_inner 
    //                             WHERE th_inner.created_at BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY th_inner.ticket_number
    //                         )
    //                         AND th.created_at BETWEEN '$fromDate' AND '$toDate'
    //                     ),
                        
    //                     eighth_query AS (
    //                         WITH TicketHistoryWithLags AS (
    //                             SELECT id, ticket_number, team_id, created_at,
    //                                 LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
    //                             FROM ticket_histories
    //                         ),
    //                         EscalationCheck AS (
    //                             SELECT id, ticket_number, team_id, created_at, prev_team_id,
    //                                 LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
    //                             FROM TicketHistoryWithLags
    //                         )
    //                         SELECT team_id,
    //                             SUM(CASE WHEN lead_team_id IS NOT NULL AND lead_team_id <> team_id THEN 1 ELSE 0 END) AS ticket_count_external_work
    //                         FROM EscalationCheck
    //                         WHERE (prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL)
    //                         AND created_at BETWEEN '$fromDate' AND '$toDate'
    //                         AND team_id = '$teamId'
    //                     ),
                        
    //                     ninth_query AS (
    //                         WITH TicketHistoryWithLags AS (
    //                             SELECT DISTINCT ticket_number, team_id, created_at,
    //                                 LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS prev_team_id
    //                             FROM helpdesk.ticket_histories
    //                         ),
    //                         EscalationCheck AS (
    //                             SELECT DISTINCT ticket_number, team_id, created_at, prev_team_id,
    //                                 LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS lead_team_id
    //                             FROM TicketHistoryWithLags
    //                         ),
    //                         EscalatedTickets AS (
    //                             SELECT DISTINCT ticket_number
    //                             FROM EscalationCheck
    //                             WHERE prev_team_id IS NOT NULL 
    //                             AND prev_team_id <> team_id
    //                             AND created_at BETWEEN '$fromDate' AND '$toDate'
    //                             AND team_id = '$teamId'
                                
    //                             UNION
                                
    //                             SELECT DISTINCT ticket_number
    //                             FROM helpdesk.ticket_histories t
    //                             WHERE t.user_id != t.status_updated_by
    //                             AND t.team_id = '$teamId'
    //                             AND t.created_at BETWEEN '$fromDate' AND '$toDate'
    //                         )
    //                         SELECT COUNT(DISTINCT t.ticket_number) AS escalated_in
    //                         FROM (
    //                             SELECT ticket_number FROM helpdesk.open_tickets
    //                             UNION ALL
    //                             SELECT ticket_number FROM helpdesk.close_tickets
    //                         ) t
    //                         LEFT JOIN EscalatedTickets esc ON t.ticket_number = esc.ticket_number
    //                         WHERE esc.ticket_number IS NOT NULL
    //                     )
                        
    //                     SELECT 
    //                         fq.team_id,
    //                         COALESCE(fq.ticket_count_work, 0) AS total_work_ticket_count,
    //                         COALESCE(sq.ticket_count, 0) AS ticket_count_tickets,
    //                         COALESCE(tq.total_created, 0) AS total_created_tickets,
    //                         COALESCE(frq.open_count, 0) AS open_ticket,
    //                         COALESCE(frq.close_count, 0) AS close_ticket,
    //                         COALESCE(fiq.fr_violated_sla, 0) AS fr_violated_sla,
    //                         COALESCE(siq.srv_violated_sla, 0) AS srv_violated_sla,
    //                         COALESCE(svq.ticket_count_work_own_created, 0) AS ticket_count_work_own_created,
    //                         COALESCE(eq.ticket_count_external_work, 0) AS ticket_forwarded,
    //                         COALESCE(nq.escalated_in, 0) AS escalated_in
    //                     FROM first_query fq
    //                     CROSS JOIN second_query sq
    //                     CROSS JOIN third_query tq
    //                     CROSS JOIN fourth_query frq
    //                     CROSS JOIN fifth_query fiq
    //                     CROSS JOIN sixth_query siq
    //                     CROSS JOIN seventh_query svq
    //                     CROSS JOIN eighth_query eq
    //                     CROSS JOIN ninth_query nq
    //                 ");

    //         $response = [
    //             'ticketCounts' => $ticketCounts,
    //             'ticketCountsTotal' => $ticketCountsTotal
    //         ];
    //         return ApiResponse::success($response, "Success", 200);
    //     }
    // }



    public function teamTicketDetailsGraph(Request $request)
    {
        $teamId   = $request->team;
        $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
        $toDate   = !empty($request->toDate)   ? (new DateTime($request->toDate))->format('Y-m-d')   : "";

        if (empty($teamId)) {
            return ApiResponse::error("Team ID is required", 400);
        }

        $isDateRange = !empty($fromDate) && !empty($toDate);

        // ── Date filter snippets, each with an explicit table alias ──────────────
        // th  = ticket_histories main alias
        $dfTH = $isDateRange
            ? "DATE(th.created_at) BETWEEN '$fromDate' AND '$toDate'"
            : "th.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";

        // t = open_tickets / close_tickets joined with utm
        $dfT = $isDateRange
            ? "DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'"
            : "t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";

        // ot = open_tickets standalone
        $dfOT = $isDateRange
            ? "DATE(ot.created_at) BETWEEN '$fromDate' AND '$toDate'"
            : "ot.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";

        // ct = close_tickets standalone
        $dfCT = $isDateRange
            ? "DATE(ct.created_at) BETWEEN '$fromDate' AND '$toDate'"
            : "ct.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";

        // tfh = first_res_sla_histories
        $dfTFH = $isDateRange
            ? "DATE(tfh.created_at) BETWEEN '$fromDate' AND '$toDate'"
            : "tfh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";

        // stsh = srv_time_subcat_sla_histories
        $dfSTSH = $isDateRange
            ? "DATE(stsh.created_at) BETWEEN '$fromDate' AND '$toDate'"
            : "stsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";

        // ec = EscalationCheck CTE rows
        $dfEC = $isDateRange
            ? "DATE(ec.created_at) BETWEEN '$fromDate' AND '$toDate'"
            : "DATE(ec.created_at) >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";

        // t alias inside ninth_query ticket_histories part
        $dfTHT = $isDateRange
            ? "DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'"
            : "t.created_at >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)";

        // th_inner = inner subquery alias in Q1 / Q7
        $dfInner = $isDateRange
            ? "DATE(th_inner.created_at) BETWEEN '$fromDate' AND '$toDate'"
            : "th_inner.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";

        // ── Per-date query ────────────────────────────────────────────────────────
        $ticketCounts = DB::select("
            WITH

            first_query AS (
                SELECT
                    DATE(MIN(th.created_at)) AS date,
                    COUNT(DISTINCT th.ticket_number) AS ticket_count_work
                FROM helpdesk.ticket_histories th
                WHERE th.team_id = '$teamId'
                AND th.created_at IN (
                    SELECT MIN(th_inner.created_at)
                    FROM helpdesk.ticket_histories th_inner
                    WHERE th_inner.team_id = '$teamId'
                        AND $dfInner
                    GROUP BY th_inner.ticket_number
                )
                GROUP BY DATE(th.created_at)
            ),

            second_query AS (
                SELECT date, SUM(ticket_count) AS ticket_count
                FROM (
                    SELECT DATE(ot.created_at) AS date, COUNT(DISTINCT ot.ticket_number) AS ticket_count
                    FROM helpdesk.open_tickets ot
                    WHERE ot.team_id = '$teamId' AND $dfOT
                    GROUP BY DATE(ot.created_at)

                    UNION ALL

                    SELECT DATE(ct.created_at) AS date, COUNT(DISTINCT ct.ticket_number) AS ticket_count
                    FROM helpdesk.close_tickets ct
                    WHERE ct.team_id = '$teamId' AND $dfCT
                    GROUP BY DATE(ct.created_at)
                ) combined
                GROUP BY date
            ),

            third_query AS (
                SELECT date, SUM(total_created) AS total_created
                FROM (
                    SELECT DATE(t.created_at) AS date, COUNT(DISTINCT t.ticket_number) AS total_created
                    FROM helpdesk.open_tickets t
                    LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
                    WHERE utm.team_id = '$teamId' AND $dfT
                    GROUP BY DATE(t.created_at)

                    UNION ALL

                    SELECT DATE(t.created_at) AS date, COUNT(DISTINCT t.ticket_number) AS total_created
                    FROM helpdesk.close_tickets t
                    LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
                    WHERE utm.team_id = '$teamId' AND $dfT
                    GROUP BY DATE(t.created_at)
                ) combined
                GROUP BY date
            ),

            fourth_query AS (
                SELECT date, SUM(open_count) AS open_count, SUM(close_count) AS close_count
                FROM (
                    SELECT DATE(ot.created_at) AS date, COUNT(*) AS open_count, 0 AS close_count
                    FROM helpdesk.open_tickets ot
                    WHERE ot.team_id = '$teamId' AND $dfOT
                    GROUP BY DATE(ot.created_at)

                    UNION ALL

                    SELECT DATE(ct.created_at) AS date, 0 AS open_count, COUNT(*) AS close_count
                    FROM helpdesk.close_tickets ct
                    WHERE ct.team_id = '$teamId' AND $dfCT
                    GROUP BY DATE(ct.created_at)
                ) combined
                GROUP BY date
            ),

            fifth_query AS (
                SELECT date, SUM(fr_violated_sla) AS fr_violated_sla
                FROM (
                    SELECT DATE(tfh.created_at) AS date, COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
                    FROM helpdesk.first_res_configs frc
                    JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
                    JOIN helpdesk.open_tickets ot ON ot.ticket_number = tfh.ticket_number
                    WHERE tfh.sla_status = 0 AND frc.team_id = '$teamId' AND $dfTFH
                    GROUP BY DATE(tfh.created_at)

                    UNION ALL

                    SELECT DATE(tfh.created_at) AS date, COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
                    FROM helpdesk.first_res_configs frc
                    JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
                    JOIN helpdesk.close_tickets ct ON ct.ticket_number = tfh.ticket_number
                    WHERE tfh.sla_status = 0 AND frc.team_id = '$teamId' AND $dfTFH
                    GROUP BY DATE(tfh.created_at)
                ) combined
                GROUP BY date
            ),

            sixth_query AS (
                SELECT date, SUM(srv_violated_sla) AS srv_violated_sla
                FROM (
                    SELECT DATE(stsh.created_at) AS date, COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
                    FROM helpdesk.sla_subcat_configs tsh
                    JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
                    JOIN helpdesk.open_tickets ot ON ot.ticket_number = stsh.ticket_number
                    WHERE stsh.sla_status = 0 AND tsh.team_id = '$teamId' AND $dfSTSH
                    GROUP BY DATE(stsh.created_at)

                    UNION ALL

                    SELECT DATE(stsh.created_at) AS date, COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
                    FROM helpdesk.sla_subcat_configs tsh
                    JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
                    JOIN helpdesk.close_tickets ct ON ct.ticket_number = stsh.ticket_number
                    WHERE stsh.sla_status = 0 AND tsh.team_id = '$teamId' AND $dfSTSH
                    GROUP BY DATE(stsh.created_at)
                ) combined
                GROUP BY date
            ),

            seventh_query AS (
                SELECT DATE(th.created_at) AS date, COUNT(DISTINCT th.ticket_number) AS ticket_count_work_own_created
                FROM helpdesk.ticket_histories th
                JOIN helpdesk.user_team_mappings utm ON th.user_id = utm.user_id AND utm.team_id = '$teamId'
                WHERE th.team_id = '$teamId'
                AND th.created_at IN (
                    SELECT MIN(th_inner.created_at)
                    FROM helpdesk.ticket_histories th_inner
                    WHERE $dfInner
                    GROUP BY th_inner.ticket_number
                )
                AND $dfTH
                GROUP BY DATE(th.created_at)
            ),

            eighth_query AS (
                WITH TicketHistoryWithLags AS (
                    SELECT id, ticket_number, team_id, created_at,
                        LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
                    FROM helpdesk.ticket_histories
                ),
                EscalationCheck AS (
                    SELECT id, ticket_number, team_id, created_at, prev_team_id,
                        LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
                    FROM TicketHistoryWithLags
                )
                SELECT
                    DATE(ec.created_at) AS date,
                    SUM(CASE WHEN ec.lead_team_id IS NOT NULL AND ec.lead_team_id <> ec.team_id THEN 1 ELSE 0 END) AS ticket_count_external_work
                FROM EscalationCheck ec
                WHERE (ec.prev_team_id IS NOT NULL OR ec.lead_team_id IS NOT NULL)
                AND $dfEC
                AND ec.team_id = '$teamId'
                GROUP BY DATE(ec.created_at)
            ),

            ninth_query AS (
                WITH TicketHistoryWithLags AS (
                    SELECT DISTINCT ticket_number, team_id, created_at,
                        LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS prev_team_id
                    FROM helpdesk.ticket_histories
                ),
                EscalationCheck AS (
                    SELECT DISTINCT ticket_number, team_id, created_at, prev_team_id,
                        LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS lead_team_id
                    FROM TicketHistoryWithLags
                ),
                EscalatedTickets AS (
                    SELECT DISTINCT ec.ticket_number
                    FROM EscalationCheck ec
                    WHERE ec.prev_team_id IS NOT NULL
                    AND ec.prev_team_id <> ec.team_id
                    AND $dfEC
                    AND ec.team_id = '$teamId'

                    UNION

                    SELECT DISTINCT t.ticket_number
                    FROM helpdesk.ticket_histories t
                    WHERE t.user_id != t.status_updated_by
                    AND t.team_id = '$teamId'
                    AND $dfTHT
                )
                SELECT
                    DATE(all_t.created_at) AS date,
                    COUNT(DISTINCT all_t.ticket_number) AS escalated_in
                FROM (
                    SELECT created_at, ticket_number FROM helpdesk.open_tickets
                    UNION ALL
                    SELECT created_at, ticket_number FROM helpdesk.close_tickets
                ) all_t
                INNER JOIN EscalatedTickets esc ON all_t.ticket_number = esc.ticket_number
                GROUP BY DATE(all_t.created_at)
            )

            SELECT
                fq.date,
                COALESCE(fq.ticket_count_work,               0) AS total_work_ticket_count,
                COALESCE(sq.ticket_count,                    0) AS ticket_count_tickets,
                COALESCE(tq.total_created,                   0) AS total_created_tickets,
                COALESCE(fo.open_count,                      0) AS open_ticket,
                COALESCE(fo.close_count,                     0) AS close_ticket,
                COALESCE(fiq.fr_violated_sla,                0) AS fr_violated_sla,
                COALESCE(siq.srv_violated_sla,               0) AS srv_violated_sla,
                COALESCE(svq.ticket_count_work_own_created,  0) AS ticket_count_work_own_created,
                COALESCE(eq.ticket_count_external_work,      0) AS ticket_forwarded,
                COALESCE(nq.escalated_in,                    0) AS escalated_in
            FROM first_query   fq
            LEFT JOIN second_query  sq  ON fq.date = sq.date
            LEFT JOIN third_query   tq  ON fq.date = tq.date
            LEFT JOIN fourth_query  fo  ON fq.date = fo.date
            LEFT JOIN fifth_query   fiq ON fq.date = fiq.date
            LEFT JOIN sixth_query   siq ON fq.date = siq.date
            LEFT JOIN seventh_query svq ON fq.date = svq.date
            LEFT JOIN eighth_query  eq  ON fq.date = eq.date
            LEFT JOIN ninth_query   nq  ON fq.date = nq.date
            ORDER BY fq.date
        ");

        // ── Totals query ──────────────────────────────────────────────────────────
        $ticketCountsTotal = DB::select("
            WITH

            first_query AS (
                SELECT th.team_id, COUNT(DISTINCT th.ticket_number) AS ticket_count_work
                FROM helpdesk.ticket_histories th
                WHERE th.team_id = '$teamId' AND $dfTH
            ),

            second_query AS (
                SELECT SUM(ticket_count) AS ticket_count
                FROM (
                    SELECT COUNT(DISTINCT ot.ticket_number) AS ticket_count
                    FROM helpdesk.open_tickets ot
                    WHERE ot.team_id = '$teamId' AND $dfOT

                    UNION ALL

                    SELECT COUNT(DISTINCT ct.ticket_number) AS ticket_count
                    FROM helpdesk.close_tickets ct
                    WHERE ct.team_id = '$teamId' AND $dfCT
                ) combined
            ),

            third_query AS (
                SELECT SUM(total_created) AS total_created
                FROM (
                    SELECT COUNT(DISTINCT t.ticket_number) AS total_created
                    FROM helpdesk.open_tickets t
                    LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
                    WHERE utm.team_id = '$teamId' AND $dfT

                    UNION ALL

                    SELECT COUNT(DISTINCT t.ticket_number) AS total_created
                    FROM helpdesk.close_tickets t
                    LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
                    WHERE utm.team_id = '$teamId' AND $dfT
                ) combined
            ),

            fourth_query AS (
                SELECT SUM(open_count) AS open_count, SUM(close_count) AS close_count
                FROM (
                    SELECT COUNT(*) AS open_count, 0 AS close_count
                    FROM helpdesk.open_tickets ot
                    WHERE ot.team_id = '$teamId' AND $dfOT

                    UNION ALL

                    SELECT 0 AS open_count, COUNT(*) AS close_count
                    FROM helpdesk.close_tickets ct
                    WHERE ct.team_id = '$teamId' AND $dfCT
                ) combined
            ),

            fifth_query AS (
                SELECT SUM(fr_violated_sla) AS fr_violated_sla
                FROM (
                    SELECT COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
                    FROM helpdesk.first_res_configs frc
                    JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
                    JOIN helpdesk.open_tickets ot ON ot.ticket_number = tfh.ticket_number
                    WHERE tfh.sla_status = 0 AND frc.team_id = '$teamId' AND $dfTFH

                    UNION ALL

                    SELECT COUNT(DISTINCT tfh.ticket_number) AS fr_violated_sla
                    FROM helpdesk.first_res_configs frc
                    JOIN helpdesk.first_res_sla_histories tfh ON tfh.first_res_config_id = frc.id
                    JOIN helpdesk.close_tickets ct ON ct.ticket_number = tfh.ticket_number
                    WHERE tfh.sla_status = 0 AND frc.team_id = '$teamId' AND $dfTFH
                ) combined
            ),

            sixth_query AS (
                SELECT SUM(srv_violated_sla) AS srv_violated_sla
                FROM (
                    SELECT COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
                    FROM helpdesk.sla_subcat_configs tsh
                    JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
                    JOIN helpdesk.open_tickets ot ON ot.ticket_number = stsh.ticket_number
                    WHERE stsh.sla_status = 0 AND tsh.team_id = '$teamId' AND $dfSTSH

                    UNION ALL

                    SELECT COUNT(DISTINCT stsh.ticket_number) AS srv_violated_sla
                    FROM helpdesk.sla_subcat_configs tsh
                    JOIN helpdesk.srv_time_subcat_sla_histories stsh ON tsh.id = stsh.sla_subcat_config_id
                    JOIN helpdesk.close_tickets ct ON ct.ticket_number = stsh.ticket_number
                    WHERE stsh.sla_status = 0 AND tsh.team_id = '$teamId' AND $dfSTSH
                ) combined
            ),

            seventh_query AS (
                SELECT COUNT(DISTINCT th.ticket_number) AS ticket_count_work_own_created
                FROM helpdesk.ticket_histories th
                JOIN helpdesk.user_team_mappings utm ON th.user_id = utm.user_id AND utm.team_id = '$teamId'
                WHERE th.team_id = '$teamId'
                AND th.created_at IN (
                    SELECT MIN(th_inner.created_at)
                    FROM helpdesk.ticket_histories th_inner
                    WHERE $dfInner
                    GROUP BY th_inner.ticket_number
                )
                AND $dfTH
            ),

            eighth_query AS (
                WITH TicketHistoryWithLags AS (
                    SELECT id, ticket_number, team_id, created_at,
                        LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
                    FROM helpdesk.ticket_histories
                ),
                EscalationCheck AS (
                    SELECT id, ticket_number, team_id, created_at, prev_team_id,
                        LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
                    FROM TicketHistoryWithLags
                )
                SELECT ec.team_id,
                    SUM(CASE WHEN ec.lead_team_id IS NOT NULL AND ec.lead_team_id <> ec.team_id THEN 1 ELSE 0 END) AS ticket_count_external_work
                FROM EscalationCheck ec
                WHERE (ec.prev_team_id IS NOT NULL OR ec.lead_team_id IS NOT NULL)
                AND $dfEC
                AND ec.team_id = '$teamId'
            ),

            ninth_query AS (
                WITH TicketHistoryWithLags AS (
                    SELECT DISTINCT ticket_number, team_id, created_at,
                        LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS prev_team_id
                    FROM helpdesk.ticket_histories
                ),
                EscalationCheck AS (
                    SELECT DISTINCT ticket_number, team_id, created_at, prev_team_id,
                        LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS lead_team_id
                    FROM TicketHistoryWithLags
                ),
                EscalatedTickets AS (
                    SELECT DISTINCT ec.ticket_number
                    FROM EscalationCheck ec
                    WHERE ec.prev_team_id IS NOT NULL
                    AND ec.prev_team_id <> ec.team_id
                    AND $dfEC
                    AND ec.team_id = '$teamId'

                    UNION

                    SELECT DISTINCT t.ticket_number
                    FROM helpdesk.ticket_histories t
                    WHERE t.user_id != t.status_updated_by
                    AND t.team_id = '$teamId'
                    AND $dfTHT
                )
                SELECT COUNT(DISTINCT all_t.ticket_number) AS escalated_in
                FROM (
                    SELECT ticket_number FROM helpdesk.open_tickets
                    UNION ALL
                    SELECT ticket_number FROM helpdesk.close_tickets
                ) all_t
                INNER JOIN EscalatedTickets esc ON all_t.ticket_number = esc.ticket_number
            )

            SELECT
                fq.team_id,
                COALESCE(fq.ticket_count_work,               0) AS total_work_ticket_count,
                COALESCE(sq.ticket_count,                    0) AS ticket_count_tickets,
                COALESCE(tq.total_created,                   0) AS total_created_tickets,
                COALESCE(fo.open_count,                      0) AS open_ticket,
                COALESCE(fo.close_count,                     0) AS close_ticket,
                COALESCE(fiq.fr_violated_sla,                0) AS fr_violated_sla,
                COALESCE(siq.srv_violated_sla,               0) AS srv_violated_sla,
                COALESCE(svq.ticket_count_work_own_created,  0) AS ticket_count_work_own_created,
                COALESCE(eq.ticket_count_external_work,      0) AS ticket_forwarded,
                COALESCE(nq.escalated_in,                    0) AS escalated_in
            FROM first_query   fq
            CROSS JOIN second_query  sq
            CROSS JOIN third_query   tq
            CROSS JOIN fourth_query  fo
            CROSS JOIN fifth_query   fiq
            CROSS JOIN sixth_query   siq
            CROSS JOIN seventh_query svq
            CROSS JOIN eighth_query  eq
            CROSS JOIN ninth_query   nq
        ");

        return ApiResponse::success(
            ['ticketCounts' => $ticketCounts, 'ticketCountsTotal' => $ticketCountsTotal],
            "Success",
            200
        );
    }

    public function getOpenTicketCountByBusinessEntity()
    {
        try {
            $openCount = DB::select("SELECT 
                    c.company_name,
                   CASE 
                    WHEN t.business_entity_id = 4 THEN 'Dhaka Colo'
                    WHEN t.business_entity_id = 5 THEN 'Race'
                    WHEN t.business_entity_id = 6 THEN 'Earth'
                    WHEN t.business_entity_id = 7 THEN 'Network & Backbone'
                    WHEN t.business_entity_id = 8 THEN 'Orbit Partner'
                    WHEN t.business_entity_id = 9 THEN 'Orbit OWN'
                    ELSE c.company_name
                END AS customized_company_name,
                    SUM(CASE WHEN t.id THEN 1 ELSE 0 END) AS open_ount
                FROM helpdesk.open_tickets t
                INNER JOIN companies c ON t.business_entity_id = c.id
                GROUP BY t.business_entity_id");
            return ApiResponse::success($openCount, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }

    // public function getTicketSummaryByBusinessEntity(Request $request)
    // {
    //     try {
    //         $businessEntity = $request->businessEntity;
    //         $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //         $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";


    //         if (!empty($businessEntity) && empty($fromDate) && empty($toDate)) {

    //             $count = DB::select("SELECT 
    //             DATE(t.created_at) AS ticket_created_date, 
    //             SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count,
    //             COUNT(t.id) AS created_count
    //             FROM helpdesk.tickets t
    //             LEFT JOIN helpdesk.users u ON u.id = t.user_id
    //             INNER JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //             WHERE t.created_at >= CURDATE() - INTERVAL 30 DAY
    //             AND t.business_entity_id = '$businessEntity'
    //             GROUP BY ticket_created_date
    //             ORDER BY ticket_created_date");

    //             $totalCount = DB::select("SELECT 
    //         SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count,
    //         COUNT(t.id) AS created_count
    //         FROM helpdesk.tickets t
    //         LEFT JOIN helpdesk.users u ON u.id = t.user_id
    //         INNER JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //         WHERE t.created_at >= CURDATE() - INTERVAL 30 DAY
    //         AND t.business_entity_id = '$businessEntity'
    //         ");

    //             $mergedResults = [
    //                 'dayWise' => $count,
    //                 'total_count' => $totalCount
    //             ];
    //         } else if (!empty($businessEntity) && !empty($fromDate) && !empty($toDate)) {

    //             $count = DB::select("SELECT 
    //             DATE(t.created_at) AS ticket_created_date, 
    //             SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count,
    //             COUNT(t.id) AS created_count
    //             FROM helpdesk.tickets t
    //             LEFT JOIN helpdesk.users u ON u.id = t.user_id
    //             INNER JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //             WHERE DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //         AND t.business_entity_id = '$businessEntity'
    //             GROUP BY ticket_created_date
    //             ORDER BY ticket_created_date");

    //             $totalCount = DB::select("SELECT 
    //             SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count,
    //             COUNT(t.id) AS created_count
    //             FROM helpdesk.tickets t
    //             LEFT JOIN helpdesk.users u ON u.id = t.user_id
    //             INNER JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //             WHERE DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //             AND t.business_entity_id = '$businessEntity'
                
    //             ");

    //             $mergedResults = [
    //                 'dayWise' => $count,
    //                 'total_count' => $totalCount
    //             ];
    //         } else {

    //             $count = DB::select("SELECT 
    //                     DATE(t.created_at) AS ticket_created_date, 
    //                     SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count,
    //                     COUNT(t.id) AS created_count
    //                     FROM helpdesk.tickets t
    //                     LEFT JOIN helpdesk.users u ON u.id = t.user_id
    //                     INNER JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                     WHERE 
    //                         t.created_at >= CURDATE() - INTERVAL 30 DAY
    //                     GROUP BY ticket_created_date
    //                     ORDER BY ticket_created_date");

    //             $totalCount = DB::select("SELECT 
    //                             SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count,
    //                             COUNT(t.id) AS created_count
    //                             FROM helpdesk.tickets t
    //                             LEFT JOIN helpdesk.users u ON u.id = t.user_id
    //                             INNER JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                             WHERE t.created_at >= CURDATE() - INTERVAL 30 DAY");

    //             $mergedResults = [
    //                 'dayWise' => $count,
    //                 'total_count' => $totalCount
    //             ];
    //         }

    //         return ApiResponse::success($mergedResults, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Failed", 500);
    //     }
    // }

    public function getTicketSummaryByBusinessEntity(Request $request)
    {
        try {
            $businessEntity = $request->businessEntity;
            $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
            $toDate   = !empty($request->toDate)   ? (new DateTime($request->toDate))->format('Y-m-d')   : "";

            // ── Dynamic WHERE conditions ─────────────────────────────────
            $dateConditionOpen  = (!empty($fromDate) && !empty($toDate))
                ? "DATE(ot.created_at) BETWEEN '$fromDate' AND '$toDate'"
                : "ot.created_at >= CURDATE() - INTERVAL 30 DAY";

            $dateConditionClose = (!empty($fromDate) && !empty($toDate))
                ? "DATE(ct.created_at) BETWEEN '$fromDate' AND '$toDate'"
                : "ct.created_at >= CURDATE() - INTERVAL 30 DAY";

            $beWhereOpen  = !empty($businessEntity) ? "AND ot.business_entity_id = '$businessEntity'" : "";
            $beWhereClose = !empty($businessEntity) ? "AND ct.business_entity_id = '$businessEntity'" : "";

            // ── Day-wise query ───────────────────────────────────────────
            $count = DB::select("
                SELECT
                    ticket_created_date,
                    SUM(created_count) AS created_count,
                    SUM(close_count)   AS close_count
                FROM (
                    SELECT
                        DATE(ot.created_at) AS ticket_created_date,
                        COUNT(ot.id)        AS created_count,
                        0                   AS close_count
                    FROM helpdesk.open_tickets ot
                    LEFT JOIN helpdesk.companies c ON c.id = ot.business_entity_id
                    LEFT JOIN helpdesk.users u      ON u.id = ot.user_id
                    WHERE $dateConditionOpen
                    $beWhereOpen
                    GROUP BY DATE(ot.created_at)

                    UNION ALL

                    SELECT
                        DATE(ct.created_at) AS ticket_created_date,
                        0                   AS created_count,
                        COUNT(ct.id)        AS close_count
                    FROM helpdesk.close_tickets ct
                    LEFT JOIN helpdesk.companies c ON c.id = ct.business_entity_id
                    LEFT JOIN helpdesk.users u      ON u.id = ct.user_id
                    WHERE $dateConditionClose
                    $beWhereClose
                    GROUP BY DATE(ct.created_at)
                ) combined
                GROUP BY ticket_created_date
                ORDER BY ticket_created_date
            ");

            // ── Total summary query ──────────────────────────────────────
            $totalCount = DB::select("
                SELECT
                    (
                        SELECT COUNT(ot.id)
                        FROM helpdesk.open_tickets ot
                        WHERE $dateConditionOpen
                        $beWhereOpen
                    ) AS created_count,
                    (
                        SELECT COUNT(ct.id)
                        FROM helpdesk.close_tickets ct
                        WHERE $dateConditionClose
                        $beWhereClose
                    ) AS close_count
            ");

            return ApiResponse::success([
                'dayWise'     => $count,
                'total_count' => $totalCount,
            ], "Success", 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }

    // public function getTeamTicketDetails(Request $request)
    // {
    //     try {

    //         $businessId = $request->businessId;
    //         $teamId = $request->teamId;
    //         $isOpen = $request->isOpen;
    //         $isClose = $request->isClose;
    //         $isCreated = $request->isCreated;
    //         $isEscaletedOut = $request->isEscaletedOut;
    //         $isEscaletedIn = $request->isEscaletedIn;
    //         $isFrViolated = $request->isFrViolated;
    //         $isSrViolated = $request->isSrViolated;

    //         $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //         $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";
    //         // return [gettype($isOpen), gettype($fromDate), $toDate];


    //         if ($isOpen === true && !empty($teamId)) {

    //             // AND fth.team_id = '$teamIdsPermited'
    //             // AND tst.team_id = '$teamIdsPermited'

    //             $Details = DB::select("SELECT distinct
    //                         t.id,t.ticket_number,t.user_id,ucm.client_name, 
    //                         t.business_entity_id,c.company_name,t.team_id,t.sid,teams.team_name,t.cat_id,categories.category_in_english,
    //                         t.subcat_id,sub_categories.sub_category_in_english,t.status_id,statuses.status_name,t.created_at,t.updated_at,
    //                         CONCAT(
    //                             TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
    //                             TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
    //                             TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
    //                         ) AS ticket_age,

    //                         u.username AS created_by
    //                         ,u2.username AS status_update_by
    //                         ,latest_comments.comments AS last_comment

    //                         FROM helpdesk.tickets t
    //                         LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                         LEFT JOIN users u ON t.user_id = u.id
    //                         LEFT JOIN users u2 ON t.status_update_by = u2.id
    //                         LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
    //                         LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
    //                         LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
    //                         LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
    //                         AND ucm.business_entity_id = c.id
    //                         LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                           
    //                         LEFT JOIN (SELECT ticket_number, comments
	// 								FROM (SELECT tc.ticket_number, tc.comments, 
	// 										ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
	// 											FROM helpdesk.ticket_comments tc) AS ranked_comments
	// 												WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
    //                         where t.team_id IN ($teamId)
    //                         AND t.status_id != 6
    //                         -- AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                          AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                          -- AND DATE(t.created_at) BETWEEN '2025-01-21' AND '2025-02-20'
    //                         order by t.ticket_number desc");
    //         } else if ($isClose === true && !empty($teamId)) {

    //             $Details = DB::select("SELECT distinct
    //                         t.id,t.ticket_number,t.user_id,ucm.client_name, 
    //                         t.business_entity_id,c.company_name,t.team_id,t.sid,teams.team_name,t.cat_id,categories.category_in_english,
    //                         t.subcat_id,sub_categories.sub_category_in_english,t.status_id,statuses.status_name,t.created_at,t.updated_at,
    //                         CONCAT(
    //                             TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
    //                             TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
    //                             TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
    //                         ) AS ticket_age,

    //                         u.username AS created_by
    //                         ,u2.username AS status_update_by
    //                         ,latest_comments.comments AS last_comment

    //                         FROM helpdesk.tickets t
    //                         LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                         LEFT JOIN users u ON t.user_id = u.id
    //                         LEFT JOIN users u2 ON t.status_update_by = u2.id
    //                         LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
    //                         LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
    //                         LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
    //                         LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
    //                         AND ucm.business_entity_id = c.id
    //                         LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                           
    //                         LEFT JOIN (SELECT ticket_number, comments
	// 								FROM (SELECT tc.ticket_number, tc.comments, 
	// 										ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
	// 											FROM helpdesk.ticket_comments tc) AS ranked_comments
	// 												WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
    //                         where t.team_id IN ($teamId)
    //                         AND t.status_id = 6
    //                        -- AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                          AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                         order by t.ticket_number desc");
    //         } else if ($isCreated === true && !empty($teamId)) {
    //             $Details = DB::select("SELECT distinct
    //                         t.id,t.ticket_number,t.user_id,ucm.client_name, 
    //                         t.business_entity_id,c.company_name,t.team_id,t.sid,teams.team_name,t.cat_id,categories.category_in_english,
    //                         t.subcat_id,sub_categories.sub_category_in_english,t.status_id,statuses.status_name,t.created_at,t.updated_at,
    //                         CASE 
    //                                     WHEN t.status_id != 6 THEN 
    //                                         CONCAT(
    //                                             TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
    //                                             TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
    //                                             TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
    //                                         )
    //                                     WHEN t.status_id = 6 THEN 
    //                                         CONCAT(
    //                                             TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
    //                                             TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
    //                                             TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
    //                                         )
    //                                 END AS ticket_age,

    //                         u.username AS created_by
    //                         ,u2.username AS status_update_by
    //                         ,latest_comments.comments AS last_comment

    //                         FROM helpdesk.tickets t
    //                         LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                         LEFT JOIN users u ON t.user_id = u.id
    //                         LEFT JOIN users u2 ON t.status_update_by = u2.id
    //                         LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
    //                         LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
    //                         LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
    //                         LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                            
    //                         LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
    //                        LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
                           
    //                         LEFT JOIN (SELECT ticket_number, comments
	// 								FROM (SELECT tc.ticket_number, tc.comments, 
	// 										ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
	// 											FROM helpdesk.ticket_comments tc) AS ranked_comments
	// 												WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
    //                         where utm.team_id IN ($teamId)
    //                         AND ucm.business_entity_id = c.id
                           
    //                         AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                         order by t.ticket_number desc");
    //         } else if ($isEscaletedOut === true && !empty($teamId)) {

    //             $Details = DB::select("WITH TicketHistoryWithLags AS (
    //                     SELECT id, ticket_number, team_id, created_at,
    //                         LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
    //                     FROM helpdesk.ticket_histories
    //                 ),
    //                 EscalationCheck AS (
    //                     SELECT id, ticket_number, team_id, created_at, prev_team_id,
    //                         LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
    //                     FROM TicketHistoryWithLags
    //                 )
    //                 SELECT DISTINCT 
    //                     t.id, t.ticket_number, t.user_id, ucm.client_name, t.business_entity_id, c.company_name, t.team_id, t.sid, 
    //                     teams.team_name, t.cat_id, categories.category_in_english, t.subcat_id, 
    //                     sub_categories.sub_category_in_english, t.status_id, statuses.status_name, t.created_at, 
    //                     t.updated_at,
    //                     CASE 
    //                         WHEN t.status_id != 6 THEN 
    //                             CONCAT(
    //                                 TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
    //                                 TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
    //                                 TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
    //                             )
    //                         WHEN t.status_id = 6 THEN 
    //                             CONCAT(
    //                                 TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
    //                                 TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
    //                                 TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
    //                             )
    //                     END AS ticket_age,

    //                     u.username AS created_by,
    //                     u2.username AS status_update_by,
    //                     latest_comments.comments AS last_comment

    //                 FROM helpdesk.tickets t
    //                 LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                 LEFT JOIN users u ON t.user_id = u.id
    //                 LEFT JOIN users u2 ON t.status_update_by = u2.id
    //                 LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
    //                 LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
    //                 LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
    //                 LEFT JOIN helpdesk.user_client_mappings ucm 
    //                     ON t.client_id_helpdesk = ucm.user_id AND ucm.business_entity_id = c.id
    //                 LEFT JOIN helpdesk.ticket_histories th ON t.ticket_number = th.ticket_number
    //                 LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
    //                 LEFT JOIN helpdesk.ticket_fr_time_team_histories fth 
    //                     ON t.ticket_number = fth.ticket_number AND t.team_id = fth.team_id 
    //                     AND fth.fr_response_status != 0
    //                 LEFT JOIN helpdesk.ticket_srv_time_team_histories tst 
    //                     ON t.ticket_number = tst.ticket_number AND t.team_id = tst.team_id 
    //                     AND tst.srv_time_status != 0
    //                 LEFT JOIN (
    //                     SELECT ticket_number, comments
    //                     FROM (
    //                         SELECT tc.ticket_number, tc.comments, 
    //                             ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
    //                         FROM helpdesk.ticket_comments tc
    //                     ) AS ranked_comments
    //                     WHERE rn = 1
    //                 ) AS latest_comments ON t.ticket_number = latest_comments.ticket_number

    //                 JOIN (
    //                     SELECT ticket_number, team_id,
    //                         SUM(CASE WHEN lead_team_id IS NOT NULL AND lead_team_id <> team_id THEN 1 ELSE 0 END) AS total_escalated_out
    //                     FROM EscalationCheck
    //                     WHERE (prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL)
    //                 --     AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                         AND DATE(created_at) BETWEEN '$fromDate' AND '$toDate'
    //                     AND team_id = '$teamId'
    //                     GROUP BY ticket_number, team_id
    //                     HAVING total_escalated_out > 0
    //                 ) AS escalated_tickets ON t.ticket_number = escalated_tickets.ticket_number

    //                 ORDER BY t.ticket_number DESC");
    //         } else if ($isEscaletedIn === true && !empty($teamId)) {
    //             // return 'hi';
    //             $Details = DB::select("WITH TicketHistoryWithLags AS (
    //                     SELECT DISTINCT ticket_number, team_id, created_at,
    //                         LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS prev_team_id
    //                     FROM helpdesk.ticket_histories
    //                 ),
    //                 EscalationCheck AS (
    //                     SELECT DISTINCT ticket_number, team_id, created_at, prev_team_id,
    //                         LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS lead_team_id
    //                     FROM TicketHistoryWithLags
    //                 ),
    //                 EscalatedTickets AS (
    //                     -- Tickets escalated by team change
    //                     SELECT DISTINCT ticket_number
    //                     FROM EscalationCheck
    //                     WHERE prev_team_id IS NOT NULL 
    //                     AND prev_team_id <> team_id
    //                 --       AND created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             AND DATE(created_at)  BETWEEN '$fromDate' AND '$toDate'
    //                     AND team_id = '$teamId'
    //                     UNION
    //                     -- Tickets escalated by user change
    //                     SELECT DISTINCT ticket_number
    //                     FROM helpdesk.ticket_histories t
    //                     WHERE t.user_id != t.status_update_by
    //                     AND t.team_id = '$teamId'
    //                 --       AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                             AND DATE(created_at)  BETWEEN '$fromDate' AND '$toDate'
    //                 )
    //                 SELECT DISTINCT t.id, t.ticket_number, t.user_id, ucm.client_name,
    //                     t.business_entity_id, c.company_name, t.team_id, t.sid, teams.team_name,
    //                     t.cat_id, categories.category_in_english, t.subcat_id, sub_categories.sub_category_in_english, t.status_id,
    //                     statuses.status_name, t.created_at, t.updated_at,
    //                     CASE 
    //                         WHEN t.status_id != 6 THEN 
    //                             CONCAT(
    //                                 TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
    //                                 TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
    //                                 TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
    //                             )
    //                         WHEN t.status_id = 6 THEN 
    //                             CONCAT(
    //                                 TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
    //                                 TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
    //                                 TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
    //                             )
    //                     END AS ticket_age,
    //                     u.username AS created_by, u2.username AS status_update_by,
    //                     latest_comments.comments AS last_comment
    //                 FROM helpdesk.tickets t
    //                 LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                 LEFT JOIN users u ON t.user_id = u.id
    //                 LEFT JOIN users u2 ON t.status_update_by = u2.id
    //                 LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
    //                 LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
    //                 LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
    //                 LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id AND ucm.business_entity_id = c.id
    //                 LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
    //                 LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number AND t.team_id = fth.team_id AND fth.fr_response_status != 0
    //                 LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number AND t.team_id = tst.team_id AND tst.srv_time_status != 0
    //                 LEFT JOIN helpdesk.ticket_histories th ON t.ticket_number = th.ticket_number
    //                 LEFT JOIN (
    //                     SELECT ticket_number, comments
    //                     FROM (
    //                         SELECT tc.ticket_number, tc.comments, 
    //                             ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
    //                         FROM helpdesk.ticket_comments tc
    //                     ) AS ranked_comments
    //                     WHERE rn = 1
    //                 ) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
    //                 LEFT JOIN EscalatedTickets esc ON t.ticket_number = esc.ticket_number
    //                 WHERE esc.ticket_number IS NOT NULL
    //                 ORDER BY t.ticket_number DESC");
    //         } else if ($isFrViolated === true && !empty($teamId)) {

    //             $Details = DB::select("SELECT DISTINCT
    //                     t.id, t.ticket_number, t.user_id, ucm.client_name,
    //                     fth.fr_response_status_name,
    //                     t.business_entity_id, c.company_name, t.team_id, t.sid,
    //                     teams.team_name, t.cat_id, categories.category_in_english,
    //                     t.subcat_id, sub_categories.sub_category_in_english,
    //                     t.status_id, statuses.status_name, t.updated_at, t.created_at,
    //                     CASE 
    //                                     WHEN t.status_id != 6 THEN 
    //                                         CONCAT(
    //                                             TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
    //                                             TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
    //                                             TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
    //                                         )
    //                                     WHEN t.status_id = 6 THEN 
    //                                         CONCAT(
    //                                             TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
    //                                             TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
    //                                             TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
    //                                         )
    //                                 END AS ticket_age,
    //                     u.username AS created_by,
    //                     u2.username AS status_update_by,
    //                     latest_comments.comments AS last_comment
    //                 FROM helpdesk.tickets t
    //                 LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                 LEFT JOIN users u ON t.user_id = u.id
    //                 LEFT JOIN users u2 ON t.status_update_by = u2.id
    //                 LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
    //                 LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
    //                 LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
    //                 LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
    //                 LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
    //                 JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number
    //                 LEFT JOIN helpdesk.ticket_histories th ON t.ticket_number = th.ticket_number
    //                 LEFT JOIN (
    //                     SELECT ticket_number, comments
    //                     FROM (
    //                         SELECT tc.ticket_number, tc.comments, 
    //                             ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
    //                         FROM helpdesk.ticket_comments tc
    //                     ) AS ranked_comments
    //                     WHERE rn = 1
    //                 ) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
    //                 WHERE fth.team_id = '$teamId'
    //                 -- AND fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                  AND DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                 AND th.team_id = fth.team_id 
    //                 AND fth.fr_response_status_name = 'violated'
    //                 ORDER BY t.ticket_number DESC
    //                 ");
    //         } else if ($isSrViolated === true && !empty($teamId)) {
    //             $Details = DB::select("SELECT DISTINCT
    //                 t.id, t.ticket_number, t.user_id, ucm.client_name,
    //                 fth.srv_time_status_name,
    //                 t.business_entity_id, c.company_name, t.team_id, t.sid,
    //                 teams.team_name, t.cat_id, categories.category_in_english,
    //                 t.subcat_id, sub_categories.sub_category_in_english,
    //                 t.status_id, statuses.status_name, t.updated_at, t.created_at,
    //                 CASE 
    //                                     WHEN t.status_id != 6 THEN 
    //                                         CONCAT(
    //                                             TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
    //                                             TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
    //                                             TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
    //                                         )
    //                                     WHEN t.status_id = 6 THEN 
    //                                         CONCAT(
    //                                             TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
    //                                             TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
    //                                             TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
    //                                         )
    //                                 END AS ticket_age,
    //                 u.username AS created_by,
    //                 u2.username AS status_update_by,
    //                 latest_comments.comments AS last_comment
    //                 FROM helpdesk.tickets t
    //                 LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                 LEFT JOIN users u ON t.user_id = u.id
    //                 LEFT JOIN users u2 ON t.status_update_by = u2.id
    //                 LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
    //                 LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
    //                 LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
    //                 LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
    //                 LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
    //                 JOIN helpdesk.ticket_srv_time_team_histories fth ON t.ticket_number = fth.ticket_number
    //                 LEFT JOIN helpdesk.ticket_histories th ON t.ticket_number = th.ticket_number
    //                 LEFT JOIN (
    //                     SELECT ticket_number, comments
    //                     FROM (
    //                         SELECT tc.ticket_number, tc.comments, 
    //                             ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
    //                         FROM helpdesk.ticket_comments tc
    //                     ) AS ranked_comments
    //                     WHERE rn = 1
    //                 ) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
    //                 WHERE fth.team_id = '$teamId'
    //                 -- AND fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                   AND DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                 AND th.team_id = fth.team_id 
    //                 AND fth.srv_time_status_name = 'violated'
    //                 ORDER BY t.ticket_number DESC");
    //         }

    //         return ApiResponse::success($Details, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Failed", 500);
    //     }
    // }


    public function getTeamTicketDetails(Request $request)
    {
        try {
            $teamId         = $request->teamId;
            $isOpen         = $request->isOpen         === true;
            $isClose        = $request->isClose        === true;
            $isCreated      = $request->isCreated      === true;
            $isEscaletedOut = $request->isEscaletedOut === true;
            $isEscaletedIn  = $request->isEscaletedIn  === true;
            $isFrViolated   = $request->isFrViolated   === true;
            $isSrViolated   = $request->isSrViolated   === true;

            $fromDate = !empty($request->fromDate)
                ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
            $toDate   = !empty($request->toDate)
                ? (new DateTime($request->toDate))->format('Y-m-d')   : "";

            if (empty($teamId)) {
                return ApiResponse::error("teamId is required", "Failed", 400);
            }

            // ── Date range snippet (applied on t.created_at) ─────────────────────
            $dateRange = "DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'";

            // ── ticket_age: open tickets use NOW(), closed use updated_at ─────────
            $ticketAgeOpen = "CONCAT(
                TIMESTAMPDIFF(DAY,    t.created_at, NOW()), 'd ',
                TIMESTAMPDIFF(HOUR,   t.created_at, NOW()) % 24, 'h ',
                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
            ) AS ticket_age";

            $ticketAgeClose = "CONCAT(
                TIMESTAMPDIFF(DAY,    t.created_at, t.updated_at), 'd ',
                TIMESTAMPDIFF(HOUR,   t.created_at, t.updated_at) % 24, 'h ',
                TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
            ) AS ticket_age";

            // For UNION branches (mix of open+closed) use status_id guard
            $ticketAgeMixed = "CASE
                WHEN t.status_id != 6 THEN CONCAT(
                    TIMESTAMPDIFF(DAY,    t.created_at, NOW()), 'd ',
                    TIMESTAMPDIFF(HOUR,   t.created_at, NOW()) % 24, 'h ',
                    TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                )
                ELSE CONCAT(
                    TIMESTAMPDIFF(DAY,    t.created_at, t.updated_at), 'd ',
                    TIMESTAMPDIFF(HOUR,   t.created_at, t.updated_at) % 24, 'h ',
                    TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
                )
            END AS ticket_age";

            // ── Latest comment sub-query ─────────────────────────────────────────
            $latestComment = "(
                SELECT ticket_number, comments
                FROM (
                    SELECT tc.ticket_number, tc.comments,
                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                    FROM helpdesk.ticket_comments tc
                ) ranked_comments
                WHERE rn = 1
            ) AS latest_comments";

            // ── Common JOINs applied after any ticket source alias `t` ───────────
            // ticket_orbits joined for sid_uid
            // users joined for username (created_by / status_updated_by)
            // NOTE: user_profiles holds fullname but NOT username — we use users.username
            $commonJoins = "LEFT JOIN helpdesk.companies c                   ON t.business_entity_id = c.id
                LEFT JOIN helpdesk.users u                           ON t.user_id = u.id
                LEFT JOIN helpdesk.users u2                          ON t.status_updated_by = u2.id
                LEFT JOIN helpdesk.users u3                          ON t.assigned_agent_id = u3.id
                LEFT JOIN helpdesk.user_profiles up                  ON t.user_id = up.user_id
                LEFT JOIN helpdesk.teams teams                       ON t.team_id = teams.id
                LEFT JOIN helpdesk.statuses statuses                 ON t.status_id = statuses.id
                LEFT JOIN helpdesk.categories                        ON t.cat_id = categories.id
                LEFT JOIN helpdesk.user_client_mappings ucm          ON t.client_id_helpdesk = ucm.user_id
                    AND ucm.business_entity_id = c.id
                LEFT JOIN helpdesk.sub_categories sub_categories     ON t.subcat_id = sub_categories.id
                LEFT JOIN helpdesk.ticket_orbits tor                 ON tor.ticket_number = t.ticket_number
                LEFT JOIN $latestComment                             ON latest_comments.ticket_number = t.ticket_number";

            // ── Shared SELECT columns (used by mixed/escalation/sla branches) ────
            // uses tor.sid_uid instead of t.sid
            // uses u.username for created_by/status_update_by
            // uses up.fullname for full name if needed
            $baseSelectMixed = "SELECT DISTINCT
                t.ticket_number, t.user_id,
                ucm.client_name,
                t.business_entity_id, c.company_name,
                t.team_id, tor.sid_uid,u3.username AS assigned_agent_username,
                teams.team_name, t.cat_id, categories.category_in_english,
                t.subcat_id, sub_categories.sub_category_in_english,
                t.status_id, statuses.status_name,
                t.created_at, t.updated_at,
                $ticketAgeMixed,
                u.username  AS created_by,
                u2.username AS status_updated_by,
                up.fullname AS created_by_fullname,
                latest_comments.comments AS last_comment";

            // ── UNION ALL of open + close tickets aliased as `t` ─────────────────
            // Only columns that exist in both tables are selected
            $allTickets = "(
                SELECT ticket_number, user_id, status_updated_by, assigned_agent_id,
                    business_entity_id, client_id_helpdesk, client_id_vendor,
                    source_id, cat_id, subcat_id, status_id, team_id,
                    priority_name, note, mobile_no, created_at, updated_at
                FROM helpdesk.open_tickets

                UNION ALL

                SELECT ticket_number, user_id, status_updated_by, assigned_agent_id,
                    business_entity_id, client_id_helpdesk, client_id_vendor,
                    source_id, cat_id, subcat_id, status_id, team_id,
                    priority_name, note, mobile_no, created_at, updated_at
                FROM helpdesk.close_tickets
            ) t";

            // ════════════════════════════════════════════════════════════════════
            // Branch: Open tickets  (open_tickets only, age = NOW())
            // ════════════════════════════════════════════════════════════════════
            if ($isOpen) {
                $sql = "SELECT DISTINCT
                    t.ticket_number, t.user_id,
                    ucm.client_name,
                    t.business_entity_id, c.company_name,
                    t.team_id, tor.sid_uid, u3.username AS assigned_agent_username,
                    teams.team_name, t.cat_id, categories.category_in_english,
                    t.subcat_id, sub_categories.sub_category_in_english,
                    t.status_id, statuses.status_name,
                    t.created_at, t.updated_at,
                    $ticketAgeOpen,
                    u.username  AS created_by,
                    u2.username AS status_updated_by,
                    up.fullname AS created_by_fullname,
                    latest_comments.comments AS last_comment
                FROM helpdesk.open_tickets t
                $commonJoins
                WHERE t.team_id IN ($teamId)
                AND $dateRange
                ORDER BY t.ticket_number DESC";

            // ════════════════════════════════════════════════════════════════════
            // Branch: Closed tickets  (close_tickets only, age = updated_at)
            // ════════════════════════════════════════════════════════════════════
            } elseif ($isClose) {
                $sql = "SELECT DISTINCT
                    t.ticket_number, t.user_id,
                    ucm.client_name,
                    t.business_entity_id, c.company_name,
                    t.team_id, tor.sid_uid, u3.username AS assigned_agent_username,
                    teams.team_name, t.cat_id, categories.category_in_english,
                    t.subcat_id, sub_categories.sub_category_in_english,
                    t.status_id, statuses.status_name,
                    t.created_at, t.updated_at,
                    $ticketAgeClose,
                    u.username  AS created_by,
                    u2.username AS status_updated_by,
                    up.fullname AS created_by_fullname,
                    latest_comments.comments AS last_comment
                FROM helpdesk.close_tickets t
                $commonJoins
                WHERE t.team_id IN ($teamId)
                AND $dateRange
                ORDER BY t.ticket_number DESC";

            // ════════════════════════════════════════════════════════════════════
            // Branch: Created by team members  (open + closed via utm)
            // ════════════════════════════════════════════════════════════════════
            } elseif ($isCreated) {
                $sql = "$baseSelectMixed
                FROM $allTickets
                $commonJoins
                LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
                WHERE utm.team_id IN ($teamId)
                AND ucm.business_entity_id = c.id
                AND $dateRange
                ORDER BY t.ticket_number DESC";

            // ════════════════════════════════════════════════════════════════════
            // Branch: Escalated OUT
            // ════════════════════════════════════════════════════════════════════
            } elseif ($isEscaletedOut) {
                $sql = "WITH TicketHistoryWithLags AS (
                    SELECT id, ticket_number, team_id, created_at,
                        LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
                    FROM helpdesk.ticket_histories
                ),
                EscalationCheck AS (
                    SELECT id, ticket_number, team_id, created_at, prev_team_id,
                        LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS lead_team_id
                    FROM TicketHistoryWithLags
                ),
                EscalatedOut AS (
                    SELECT ticket_number
                    FROM EscalationCheck
                    WHERE (prev_team_id IS NOT NULL OR lead_team_id IS NOT NULL)
                    AND DATE(created_at) BETWEEN '$fromDate' AND '$toDate'
                    AND team_id = '$teamId'
                    GROUP BY ticket_number
                    HAVING SUM(CASE WHEN lead_team_id IS NOT NULL AND lead_team_id <> team_id THEN 1 ELSE 0 END) > 0
                )
                $baseSelectMixed
                FROM $allTickets
                $commonJoins
                JOIN EscalatedOut eo ON t.ticket_number = eo.ticket_number
                ORDER BY t.ticket_number DESC";

            // ════════════════════════════════════════════════════════════════════
            // Branch: Escalated IN
            // ════════════════════════════════════════════════════════════════════
            } elseif ($isEscaletedIn) {
                $sql = "WITH TicketHistoryWithLags AS (
                    SELECT DISTINCT ticket_number, team_id, created_at,
                        LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS prev_team_id
                    FROM helpdesk.ticket_histories
                ),
                EscalationCheck AS (
                    SELECT DISTINCT ticket_number, team_id, created_at, prev_team_id,
                        LEAD(team_id) OVER (PARTITION BY ticket_number ORDER BY ticket_number) AS lead_team_id
                    FROM TicketHistoryWithLags
                ),
                EscalatedTickets AS (
                    SELECT DISTINCT ticket_number
                    FROM EscalationCheck
                    WHERE prev_team_id IS NOT NULL
                    AND prev_team_id <> team_id
                    AND DATE(created_at) BETWEEN '$fromDate' AND '$toDate'
                    AND team_id = '$teamId'

                    UNION

                    SELECT DISTINCT th.ticket_number
                    FROM helpdesk.ticket_histories th
                    WHERE th.user_id != th.status_updated_by
                    AND th.team_id = '$teamId'
                    AND DATE(th.created_at) BETWEEN '$fromDate' AND '$toDate'
                )
                $baseSelectMixed
                FROM $allTickets
                $commonJoins
                JOIN EscalatedTickets esc ON t.ticket_number = esc.ticket_number
                ORDER BY t.ticket_number DESC";

            // ════════════════════════════════════════════════════════════════════
            // Branch: FR (First Response) SLA Violated
            //   first_res_configs → first_res_sla_histories (sla_status = 0)
            // ════════════════════════════════════════════════════════════════════
            } elseif ($isFrViolated) {
                $sql = "SELECT DISTINCT
                    t.ticket_number, t.user_id,
                    ucm.client_name,
                    frh.sla_status AS fr_sla_status,
                    t.business_entity_id, c.company_name,
                    t.team_id, tor.sid_uid,u3.username AS assigned_agent_username,
                    teams.team_name, t.cat_id, categories.category_in_english,
                    t.subcat_id, sub_categories.sub_category_in_english,
                    t.status_id, statuses.status_name,
                    t.updated_at, t.created_at,
                    $ticketAgeMixed,
                    u.username  AS created_by,
                    u2.username AS status_updated_by,
                    up.fullname AS created_by_fullname,
                    latest_comments.comments AS last_comment
                FROM $allTickets
                $commonJoins
                JOIN helpdesk.first_res_configs frc
                    ON frc.team_id = '$teamId'
                JOIN helpdesk.first_res_sla_histories frh
                    ON frh.first_res_config_id = frc.id
                AND frh.ticket_number = t.ticket_number
                AND frh.sla_status = 0
                AND DATE(frh.created_at) BETWEEN '$fromDate' AND '$toDate'
                ORDER BY t.ticket_number DESC";

            // ════════════════════════════════════════════════════════════════════
            // Branch: SR (Service Resolution) SLA Violated
            //   sla_subcat_configs → srv_time_subcat_sla_histories (sla_status = 0)
            // ════════════════════════════════════════════════════════════════════
            } elseif ($isSrViolated) {
                $sql = "SELECT DISTINCT
                    t.ticket_number, t.user_id,
                    ucm.client_name,
                    stsh.sla_status AS srv_sla_status,
                    t.business_entity_id, c.company_name,
                    t.team_id, tor.sid_uid,u3.username AS assigned_agent_username,
                    teams.team_name, t.cat_id, categories.category_in_english,
                    t.subcat_id, sub_categories.sub_category_in_english,
                    t.status_id, statuses.status_name,
                    t.updated_at, t.created_at,
                    $ticketAgeMixed,
                    u.username  AS created_by,
                    u2.username AS status_updated_by,
                    up.fullname AS created_by_fullname,
                    latest_comments.comments AS last_comment
                FROM $allTickets
                $commonJoins
                JOIN helpdesk.sla_subcat_configs tsh
                    ON tsh.team_id = '$teamId'
                JOIN helpdesk.srv_time_subcat_sla_histories stsh
                    ON stsh.sla_subcat_config_id = tsh.id
                AND stsh.ticket_number = t.ticket_number
                AND stsh.sla_status = 0
                AND DATE(stsh.created_at) BETWEEN '$fromDate' AND '$toDate'
                ORDER BY t.ticket_number DESC";

            } else {
                return ApiResponse::error("No valid filter flag provided.", "Failed", 400);
            }

            $details = DB::select($sql);
            return ApiResponse::success($details, "Success", 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }


    // public function subcategoryVsCreated(Request $request)
    // {
    //     try {

    //         $businessId = $request->businessId;
    //         $departmentId = $request->departmentId;
    //         $divisionId = $request->divisionId;
    //         $teamId = $request->teamId;

    //         $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //         $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

    //         if (!empty($teamId) && empty($fromDate) && empty($toDate)) {
    //             $ticket = DB::select("SELECT 
    //                         t.team_id,
    //                         teams.team_name,
    //                         t.subcat_id, 
    //                         sub.sub_category_in_english, 
    //                         COUNT(*) AS ticket_count
    //                     FROM helpdesk.tickets t
    //                     INNER JOIN helpdesk.sub_categories sub ON t.subcat_id = sub.id
    //                     INNER JOIN helpdesk.teams ON t.team_id = teams.id
    //                     WHERE t.team_id = '$teamId'
    //                     AND DATE(t.created_at) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                     GROUP BY t.team_id,teams.team_name,t.subcat_id, sub.sub_category_in_english
    //                     ORDER BY ticket_count DESC
    //                     ");
    //         } else if (!empty($businessId) && empty($fromDate) && empty($toDate)) {
    //             $ticket = DB::select("SELECT 
    //                     t.business_entity_id,
    //                     c.company_name,
    //                     t.subcat_id, 
    //                     sub.sub_category_in_english, 
    //                     COUNT(*) AS ticket_count
    //                 FROM helpdesk.tickets t
    //                 INNER JOIN helpdesk.sub_categories sub ON t.subcat_id = sub.id
    //                 INNER JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                 WHERE t.business_entity_id = '$businessId'
    //                 AND DATE(t.created_at) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                 GROUP BY t.business_entity_id,
    //                     c.company_name,t.subcat_id, sub.sub_category_in_english
    //                 ORDER BY ticket_count DESC");
    //         } else if (!empty($businessId) && empty($teamId) && empty($departmentId) && empty($divisionId) && !empty($fromDate) && !empty($toDate)) {
    //             $ticket = DB::select("SELECT 
    //                     t.business_entity_id,
    //                     c.company_name,
    //                     t.subcat_id, 
    //                     sub.sub_category_in_english, 
    //                     COUNT(*) AS ticket_count
    //                 FROM helpdesk.tickets t
    //                 INNER JOIN helpdesk.sub_categories sub ON t.subcat_id = sub.id
    //                 INNER JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                 WHERE t.business_entity_id = '$businessId'
    //                 AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                 GROUP BY t.business_entity_id,
    //                     c.company_name,t.subcat_id, sub.sub_category_in_english
    //                 ORDER BY ticket_count DESC
    //                 ");
    //         } else if (empty($businessId) && !empty($teamId) && empty($departmentId) && empty($divisionId) && !empty($fromDate) && !empty($toDate)) {
    //             $ticket = DB::select("SELECT 
    //             t.team_id,
    //             teams.team_name,
    //             t.subcat_id, 
    //             sub.sub_category_in_english, 
    //             COUNT(*) AS ticket_count
    //             FROM helpdesk.tickets t
    //             INNER JOIN helpdesk.sub_categories sub ON t.subcat_id = sub.id
    //             INNER JOIN helpdesk.teams ON t.team_id = teams.id
    //             WHERE t.team_id = '$teamId'
    //             AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //             GROUP BY t.team_id,teams.team_name,t.subcat_id, sub.sub_category_in_english
    //             ORDER BY ticket_count DESC
    //             ");
    //         } else if (!empty($departmentId) && empty($fromDate) && empty($toDate)) {
    //             $ticket = DB::select("SELECT 
    //                     d.id,
    //                     d.department_name,
    //                     t.subcat_id, 
    //                     sub.sub_category_in_english, 
    //                     COUNT(*) AS ticket_count
    //                 FROM helpdesk.tickets t
    //                 INNER JOIN helpdesk.sub_categories sub ON t.subcat_id = sub.id
    //                  INNER JOIN helpdesk.teams ON t.team_id = teams.id 
    //                 INNER JOIN helpdesk.departments d ON teams.department_id = d.id
    //                 WHERE d.id = '$departmentId'
    //                 AND DATE(t.created_at) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                 GROUP BY d.id,
    //                     d.department_name,t.subcat_id, sub.sub_category_in_english
    //                 ORDER BY ticket_count DESC
    //                     ");
    //         } else if (!empty($divisionId) && empty($fromDate) && empty($toDate)) {
    //             $ticket = DB::select("SELECT 
    //                     d.id,
    //                     d.division_name,
    //                     t.subcat_id, 
    //                     sub.sub_category_in_english, 
    //                     COUNT(*) AS ticket_count
    //                 FROM helpdesk.tickets t
    //                 INNER JOIN helpdesk.sub_categories sub ON t.subcat_id = sub.id
    //                  INNER JOIN helpdesk.teams ON t.team_id = teams.id 
    //                 INNER JOIN helpdesk.divisions d ON teams.division_id = d.id
    //                 WHERE d.id = '$divisionId'
    //                 AND DATE(t.created_at) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                 GROUP BY d.id,
    //                     d.division_name,t.subcat_id, sub.sub_category_in_english
    //                 ORDER BY ticket_count DESC
    //                     ");
    //         } else if (empty($businessId) && empty($teamId) && !empty($departmentId) && empty($divisionId) && !empty($fromDate) && !empty($toDate)) {
    //             $ticket = DB::select("SELECT 
    //                     d.id,
    //                     d.department_name,
    //                     t.subcat_id, 
    //                     sub.sub_category_in_english, 
    //                     COUNT(*) AS ticket_count
    //                 FROM helpdesk.tickets t
    //                 INNER JOIN helpdesk.sub_categories sub ON t.subcat_id = sub.id
    //                  INNER JOIN helpdesk.teams ON t.team_id = teams.id 
    //                 INNER JOIN helpdesk.departments d ON teams.department_id = d.id
    //                 WHERE d.id = '$departmentId'
    //                AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                 GROUP BY d.id,
    //                     d.department_name,t.subcat_id, sub.sub_category_in_english
    //                 ORDER BY ticket_count DESC
    //                     ");
    //         } else if (empty($businessId) && empty($teamId) && empty($departmentId) && !empty($divisionId) && !empty($fromDate) && !empty($toDate)) {
    //             $ticket = DB::select("SELECT 
    //                     d.id,
    //                     d.division_name,
    //                     t.subcat_id, 
    //                     sub.sub_category_in_english, 
    //                     COUNT(*) AS ticket_count
    //                 FROM helpdesk.tickets t
    //                 INNER JOIN helpdesk.sub_categories sub ON t.subcat_id = sub.id
    //                  INNER JOIN helpdesk.teams ON t.team_id = teams.id 
    //                 INNER JOIN helpdesk.divisions d ON teams.division_id = d.id
    //                 WHERE d.id = '$divisionId'
    //                 AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                 GROUP BY d.id,
    //                     d.division_name,t.subcat_id, sub.sub_category_in_english
    //                 ORDER BY ticket_count DESC
    //                     ");
    //         } else {
    //             return $ticket = [];
    //         }

    //         return ApiResponse::success($ticket, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Failed", 500);
    //     }
    // }


    public function subcategoryVsCreated(Request $request)
    {
        try {
            $businessId   = $request->businessId;
            $departmentId = $request->departmentId;
            $divisionId   = $request->divisionId;
            $teamId       = $request->teamId;

            $fromDate = !empty($request->fromDate)
                ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
            $toDate   = !empty($request->toDate)
                ? (new DateTime($request->toDate))->format('Y-m-d')   : "";

            // ── Ticket source: UNION ALL of open + closed ────────────────────────
            $allTickets = "(
                SELECT ticket_number, user_id, business_entity_id, client_id_helpdesk,
                    cat_id, subcat_id, status_id, team_id, created_at, updated_at
                FROM helpdesk.open_tickets
                UNION ALL
                SELECT ticket_number, user_id, business_entity_id, client_id_helpdesk,
                    cat_id, subcat_id, status_id, team_id, created_at, updated_at
                FROM helpdesk.close_tickets
            ) t";

            // ── Date filter ──────────────────────────────────────────────────────
            $isDateRange = !empty($fromDate) && !empty($toDate);
            $dateFilter  = $isDateRange
                ? "DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'"
                : "DATE(t.created_at) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";

            // ── Determine filter type ────────────────────────────────────────────
            $hasTeam       = !empty($teamId);
            $hasBusiness   = !empty($businessId);
            $hasDepartment = !empty($departmentId);
            $hasDivision   = !empty($divisionId);

            // ════════════════════════════════════════════════════════════════════
            // Branch: Team filter
            // ════════════════════════════════════════════════════════════════════
            if ($hasTeam) {
                $sql = "SELECT
                    t.team_id,
                    teams.team_name,
                    t.subcat_id,
                    sub.sub_category_in_english,
                    COUNT(*) AS ticket_count
                FROM $allTickets
                INNER JOIN helpdesk.sub_categories sub  ON t.subcat_id = sub.id
                INNER JOIN helpdesk.teams teams          ON t.team_id = teams.id
                WHERE t.team_id = '$teamId'
                AND $dateFilter
                GROUP BY t.team_id, teams.team_name, t.subcat_id, sub.sub_category_in_english
                ORDER BY ticket_count DESC";

            // ════════════════════════════════════════════════════════════════════
            // Branch: Business filter
            // ════════════════════════════════════════════════════════════════════
            } elseif ($hasBusiness) {
                $sql = "SELECT
                    t.business_entity_id,
                    c.company_name,
                    t.subcat_id,
                    sub.sub_category_in_english,
                    COUNT(*) AS ticket_count
                FROM $allTickets
                INNER JOIN helpdesk.sub_categories sub ON t.subcat_id = sub.id
                INNER JOIN helpdesk.companies c         ON t.business_entity_id = c.id
                WHERE t.business_entity_id = '$businessId'
                AND $dateFilter
                GROUP BY t.business_entity_id, c.company_name, t.subcat_id, sub.sub_category_in_english
                ORDER BY ticket_count DESC";

            // ════════════════════════════════════════════════════════════════════
            // Branch: Department filter
            // ════════════════════════════════════════════════════════════════════
            } elseif ($hasDepartment) {
                $sql = "SELECT
                    d.id,
                    d.department_name,
                    t.subcat_id,
                    sub.sub_category_in_english,
                    COUNT(*) AS ticket_count
                FROM $allTickets
                INNER JOIN helpdesk.sub_categories sub  ON t.subcat_id = sub.id
                INNER JOIN helpdesk.teams teams          ON t.team_id = teams.id
                INNER JOIN helpdesk.departments d        ON teams.department_id = d.id
                WHERE d.id = '$departmentId'
                AND $dateFilter
                GROUP BY d.id, d.department_name, t.subcat_id, sub.sub_category_in_english
                ORDER BY ticket_count DESC";

            // ════════════════════════════════════════════════════════════════════
            // Branch: Division filter
            // ════════════════════════════════════════════════════════════════════
            } elseif ($hasDivision) {
                $sql = "SELECT
                    d.id,
                    d.division_name,
                    t.subcat_id,
                    sub.sub_category_in_english,
                    COUNT(*) AS ticket_count
                FROM $allTickets
                INNER JOIN helpdesk.sub_categories sub ON t.subcat_id = sub.id
                INNER JOIN helpdesk.teams teams         ON t.team_id = teams.id
                INNER JOIN helpdesk.divisions d         ON teams.division_id = d.id
                WHERE d.id = '$divisionId'
                AND $dateFilter
                GROUP BY d.id, d.division_name, t.subcat_id, sub.sub_category_in_english
                ORDER BY ticket_count DESC";

            } else {
                return ApiResponse::success([], "Success", 200);
            }

            $ticket = DB::select($sql);
            return ApiResponse::success($ticket, "Success", 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }


    // public function getTicketCountByClientCustomer(Request $request)
    // {
    //     // return $request->all();
    //     $businessId = $request->businessEntity;
    //     $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //     $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

    //     if (!empty($businessId) && empty($fromDate) && empty($toDate)) {

    //         $Details = DB::select("SELECT DATE(t.created_at) AS ticket_created_date,c.company_name, 
    //                         SUM(CASE WHEN u.user_type = 'Agent' THEN 1 ELSE 0 END) AS Agent,
    //                             SUM(CASE WHEN u.user_type = 'Client' THEN 1 ELSE 0 END) AS Client,
    //                             SUM(CASE WHEN u.user_type = 'Customer' THEN 1 ELSE 0 END) AS Customer
    //                         FROM helpdesk.tickets t 
    //                         JOIN helpdesk.users u ON t.user_id = u.id
    //                         JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                         WHERE DATE(t.created_at) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                         AND t.business_entity_id = '$businessId'
    //                         GROUP BY ticket_created_date
    //                         ORDER BY ticket_created_date");
    //         $count = DB::select("SELECT 
    //                          SUM(CASE WHEN u.user_type = 'Agent' THEN 1 ELSE 0 END) AS agent_tickets,
    //                          SUM(CASE WHEN u.user_type = 'Client' THEN 1 ELSE 0 END) AS client_tickets,
    //                          SUM(CASE WHEN u.user_type = 'Customer' THEN 1 ELSE 0 END) AS customer_tickets
    //                      FROM helpdesk.tickets t 
    //                      JOIN helpdesk.users u ON t.user_id = u.id
    //                      JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                      WHERE DATE(t.created_at) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
    //                         AND t.business_entity_id = '$businessId' ");
    //     } else if (!empty($businessId) && !empty($fromDate) && !empty($toDate)) {
    //         // return [$businessId, $fromDate, $toDate];
    //         $Details = DB::select("SELECT DATE(t.created_at) AS ticket_created_date,c.company_name, 
    //                         SUM(CASE WHEN u.user_type = 'Agent' THEN 1 ELSE 0 END) AS Agent,
    //                             SUM(CASE WHEN u.user_type = 'Client' THEN 1 ELSE 0 END) AS Client,
    //                             SUM(CASE WHEN u.user_type = 'Customer' THEN 1 ELSE 0 END) AS Customer
    //                         FROM helpdesk.tickets t 
    //                         JOIN helpdesk.users u ON t.user_id = u.id
    //                         JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                         WHERE DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                         AND t.business_entity_id = '$businessId'
    //                         GROUP BY ticket_created_date
    //                         ORDER BY ticket_created_date");

    //         $count = DB::select("SELECT 
    //                         SUM(CASE WHEN u.user_type = 'Agent' THEN 1 ELSE 0 END) AS agent_tickets,
    //                         SUM(CASE WHEN u.user_type = 'Client' THEN 1 ELSE 0 END) AS client_tickets,
    //                         SUM(CASE WHEN u.user_type = 'Customer' THEN 1 ELSE 0 END) AS customer_tickets
    //                     FROM helpdesk.tickets t 
    //                     JOIN helpdesk.users u ON t.user_id = u.id
    //                     JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                     WHERE DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                     AND t.business_entity_id = '$businessId' ");
    //     }

    //     $mergeData = [
    //         'dayWise' => $Details,
    //         'count' => $count
    //     ];
    //     return ApiResponse::success($mergeData, "Success", 200);
    // }

    public function getTicketCountByClientCustomer(Request $request)
    {
        $businessId = $request->businessEntity;
        $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
        $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

        if (empty($businessId)) {
            return ApiResponse::error("Business Entity is required", 400);
        }

        // Date condition
        if (!empty($fromDate) && !empty($toDate)) {
            $dateCondition = "DATE(t.created_at) BETWEEN ? AND ?";
            $bindings = [$fromDate, $toDate, $businessId];
        } else {
            $dateCondition = "DATE(t.created_at) >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
            $bindings = [$businessId];
        }

        // =============================
        // Day Wise Data
        // =============================
        $Details = DB::select("
            SELECT DATE(t.created_at) AS ticket_created_date,
                SUM(CASE WHEN up.user_type = 'Agent' THEN 1 ELSE 0 END) AS Agent,
                SUM(CASE WHEN up.user_type = 'Client' THEN 1 ELSE 0 END) AS Client,
                SUM(CASE WHEN up.user_type = 'Customer' THEN 1 ELSE 0 END) AS Customer
            FROM (
                SELECT created_at, user_id, business_entity_id FROM open_tickets
                UNION ALL
                SELECT created_at, user_id, business_entity_id FROM close_tickets
            ) t
            JOIN users u ON t.user_id = u.id
            JOIN user_profiles up ON u.id = up.user_id
            WHERE $dateCondition
            AND t.business_entity_id = ?
            GROUP BY DATE(t.created_at)
            ORDER BY DATE(t.created_at)
        ", $bindings);

        // =============================
        // Total Count
        // =============================
        $count = DB::select("
            SELECT 
                SUM(CASE WHEN up.user_type = 'Agent' THEN 1 ELSE 0 END) AS agent_tickets,
                SUM(CASE WHEN up.user_type = 'Client' THEN 1 ELSE 0 END) AS client_tickets,
                SUM(CASE WHEN up.user_type = 'Customer' THEN 1 ELSE 0 END) AS customer_tickets
            FROM (
                SELECT created_at, user_id, business_entity_id FROM open_tickets
                UNION ALL
                SELECT created_at, user_id, business_entity_id FROM close_tickets
            ) t
            JOIN users u ON t.user_id = u.id
            JOIN user_profiles up ON u.id = up.user_id
            WHERE $dateCondition
            AND t.business_entity_id = ?
        ", $bindings);

        return ApiResponse::success([
            'dayWise' => $Details,
            'count' => $count
        ], "Success", 200);
    }
    // public function getTicketCountAndAvgTimeByTeam()
    // {
    //     $Details = DB::select("SELECT 
    //                     t.team_id,
    //                     teams.team_name,
    //                     count(t.team_id) as 'total_open',
    //                     COUNT(CASE WHEN t.business_entity_id = 8 THEN 1 END) AS 'orbit_partner_open',
    //                     CONCAT(
    //                         FLOOR(AVG(CASE WHEN t.business_entity_id = 8 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 8 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400) / 3600), 'h ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 8 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600) / 60), 'm'
    //                     ) AS avg_time_orbit_partner,
    //                     COUNT(CASE WHEN t.business_entity_id = 5 THEN 1 END) AS 'race_online_open',
    //                     CONCAT(
    //                         FLOOR(AVG(CASE WHEN t.business_entity_id = 5 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 5 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400) / 3600), 'h ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 5 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600) / 60), 'm'
    //                     ) AS avg_time_race_online,
    //                     COUNT(CASE WHEN t.business_entity_id = 6 THEN 1 END) AS 'earth_open',
    //                     CONCAT(
    //                         FLOOR(AVG(CASE WHEN t.business_entity_id = 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400) / 3600), 'h ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600) / 60), 'm'
    //                     ) AS avg_time_earth,
    //                     COUNT(CASE WHEN t.business_entity_id = 4 THEN 1 END) AS 'dhakacolo_open',
    //                     CONCAT(
    //                         FLOOR(AVG(CASE WHEN t.business_entity_id = 4 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 4 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400) / 3600), 'h ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 4 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600) / 60), 'm'
    //                     ) AS avg_time_dhaka_colo,
    //                     COUNT(CASE WHEN t.business_entity_id = 7 THEN 1 END) AS 'network_backbone_open',
    //                     CONCAT(
    //                         FLOOR(AVG(CASE WHEN t.business_entity_id = 7 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 7 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400) / 3600), 'h ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 7 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600) / 60), 'm'
    //                     ) AS avg_time_network_backbone,
                    
    //                     COUNT(CASE WHEN t.business_entity_id = 9 THEN 1 END) AS 'orbit_own_open',
    //                     CONCAT(
    //                         FLOOR(AVG(CASE WHEN t.business_entity_id = 9 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 9 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400) / 3600), 'h ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 9 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600) / 60), 'm'
    //                     ) AS avg_time_orbit_own,
    //                     COUNT(CASE WHEN t.business_entity_id = 10 THEN 1 END) AS 'internal_operation_open',
    //                     CONCAT(
    //                         FLOOR(AVG(CASE WHEN t.business_entity_id = 10 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 10 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400) / 3600), 'h ',
    //                         FLOOR((AVG(CASE WHEN t.business_entity_id = 10 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600) / 60), 'm'
    //                     ) AS avg_time_internal_operation
                        
    //                 FROM helpdesk.tickets t 
    //                 INNER JOIN helpdesk.companies c ON c.id = t.business_entity_id
    //                 INNER JOIN helpdesk.teams  ON teams.id = t.team_id
    //                 WHERE t.status_id != 6
    //                 GROUP BY t.team_id
    //                 ORDER BY total_open DESC");


    //     return ApiResponse::success($Details, "Success", 200);
    // }

    // last 2
    public function getTicketCountAndAvgTimeByTeam()
    {

        $Details = DB::select("
            SELECT
                t.team_id,
                teams.team_name,
                COUNT(t.team_id) AS total_open,

                -- ── Orbit Partner (business_entity_id = 8) ──────────────────────
                COUNT(CASE WHEN t.business_entity_id = 8 THEN 1 END) AS orbit_partner_open,
                CONCAT(
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 8 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 8 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 8 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600 / 60), 'm'
                ) AS avg_time_orbit_partner,

                -- ── Race Partner (business_entity_id = 11) ──────────────────────
                COUNT(CASE WHEN t.business_entity_id = 11 THEN 1 END) AS race_partner_open,
                CONCAT(
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 11 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 11 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 11 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600 / 60), 'm'
                ) AS avg_time_race_partner,

                -- ── Race Online (business_entity_id = 5) ────────────────────────
                COUNT(CASE WHEN t.business_entity_id = 5 THEN 1 END) AS race_online_open,
                CONCAT(
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 5 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 5 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 5 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600 / 60), 'm'
                ) AS avg_time_race_online,

                -- ── Earth (business_entity_id = 6) ──────────────────────────────
                COUNT(CASE WHEN t.business_entity_id = 6 THEN 1 END) AS earth_open,
                CONCAT(
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600 / 60), 'm'
                ) AS avg_time_earth,

                -- ── Dhaka Colo (business_entity_id = 4) ─────────────────────────
                COUNT(CASE WHEN t.business_entity_id = 4 THEN 1 END) AS dhakacolo_open,
                CONCAT(
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 4 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 4 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 4 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600 / 60), 'm'
                ) AS avg_time_dhaka_colo,

                -- ── Network Backbone (business_entity_id = 7) ───────────────────
                COUNT(CASE WHEN t.business_entity_id = 7 THEN 1 END) AS network_backbone_open,
                CONCAT(
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 7 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 7 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 7 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600 / 60), 'm'
                ) AS avg_time_network_backbone,

                -- ── Orbit Own (business_entity_id = 9) ──────────────────────────
                COUNT(CASE WHEN t.business_entity_id = 9 THEN 1 END) AS orbit_own_open,
                CONCAT(
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 9 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 9 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 9 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600 / 60), 'm'
                ) AS avg_time_orbit_own,

                -- ── Internal Operation (business_entity_id = 10) ────────────────
                COUNT(CASE WHEN t.business_entity_id = 10 THEN 1 END) AS internal_operation_open,
                CONCAT(
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 10 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 10 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN t.business_entity_id = 10 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE 0 END) % 3600 / 60), 'm'
                ) AS avg_time_internal_operation

            FROM helpdesk.open_tickets t
            INNER JOIN helpdesk.companies c ON c.id = t.business_entity_id
            INNER JOIN helpdesk.teams       ON teams.id = t.team_id
            GROUP BY t.team_id, teams.team_name
            ORDER BY total_open DESC
        ");

        return ApiResponse::success($Details, "Success", 200);
    }

    // public function getTeamVsBusinessEntityTicketCountDetails(Request $request)
    // {
    //     $teamId = $request->teamId;
    //     if (!empty($teamId)) {
    //         $Details = DB::select("SELECT distinct
    //                         t.id,t.ticket_number,t.user_id,ucm.client_name, fth.fr_response_status_name, tst.srv_time_status_name, 
    //                         t.business_entity_id,c.company_name,t.team_id,t.sid,teams.team_name,t.cat_id,categories.category_in_english,
    //                         t.subcat_id,sub_categories.sub_category_in_english,t.status_id,statuses.status_name,t.created_at,t.updated_at,
    //                         CASE 
    //                                     WHEN t.status_id = 1 THEN 
    //                                         CONCAT(
    //                                             TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
    //                                             TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
    //                                             TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
    //                                         )
                                        
    //                                 END AS ticket_age,

    //                         CASE 
    //                                     WHEN fth.fr_response_status = 2 AND fth.team_id = '$teamId'
    //                                     THEN CONCAT(
    //                                         TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
    //                                         TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
    //                                         TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
    //                                     )
    //                                     ELSE ''
    //                                 END AS fr_due_time,

    //                         CASE 
    //                             WHEN tst.srv_time_status = 2 AND tst.team_id = '$teamId'
    //                             THEN CONCAT(
    //                                 TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
    //                                 TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
    //                                 TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
    //                             )
    //                             ELSE ''
    //                         END AS srv_due_time
    //                         ,u.username AS created_by
    //                         ,u2.username AS status_update_by
    //                         ,latest_comments.comments AS last_comment

    //                         FROM helpdesk.tickets t
    //                         LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                         LEFT JOIN users u ON t.user_id = u.id
    //                         LEFT JOIN users u2 ON t.status_update_by = u2.id
    //                         LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
    //                         LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
    //                         LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                           
    //                         LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
    //                         AND ucm.business_entity_id = c.id
    //                         LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
    //                         LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
    //                         AND t.team_id = fth.team_id AND fth.fr_response_status != 0 AND fth.team_id = '$teamId'
    //                         LEFT JOIN helpdesk.ticket_histories th ON t.ticket_number = th.ticket_number
                            
    //                         LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
    //                         AND t.team_id = tst.team_id AND tst.srv_time_status != 0 AND tst.team_id = '$teamId'
    //                         LEFT JOIN (SELECT ticket_number, comments
	// 								FROM (SELECT tc.ticket_number, tc.comments, 
	// 										ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
	// 											FROM helpdesk.ticket_comments tc) AS ranked_comments
	// 												WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
    //                         where t.team_id = '$teamId'
    //                         AND t.status_id = 1
                           
    //                         order by t.ticket_number desc");

    //         return ApiResponse::success($Details, "Success", 200);
    //     }
    // }




    public function getTeamVsBusinessEntityTicketCountDetails(Request $request)
    {
        $teamId = $request->teamId;

        if (empty($teamId)) {
            return ApiResponse::error("teamId is required", "Failed", 400);
        }

        // ── Only open tickets needed (status_id = 1 = new/open, never in close_tickets) ──
        // fr/srv SLA: ticket_fr_time_team_histories  → first_res_configs + first_res_sla_histories
        //             ticket_srv_time_team_histories  → sla_subcat_configs + srv_time_subcat_sla_histories
        // users    → users (username) + user_profiles (fullname)
        // t.sid    → tor.sid_uid from ticket_orbits
        // t.status_update_by → t.status_updated_by

        $Details = DB::select("
            SELECT DISTINCT
                t.ticket_number,
                t.user_id,
                ucm.client_name,
                frh.sla_status          AS fr_sla_status,
                stsh.sla_status         AS srv_sla_status,
                t.business_entity_id,
                c.company_name,
                t.team_id,
                u3.username AS assigned_agent_username,
                tor.sid_uid,
                teams.team_name,
                t.cat_id,               categories.category_in_english,
                t.subcat_id,            sub_categories.sub_category_in_english,
                t.status_id,            statuses.status_name,
                t.created_at,           t.updated_at,

                -- ── Ticket age (status_id = 1 means always open, use NOW()) ──────
                CONCAT(
                    TIMESTAMPDIFF(DAY,    t.created_at, NOW()), 'd ',
                    TIMESTAMPDIFF(HOUR,   t.created_at, NOW()) % 24, 'h ',
                    TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                ) AS ticket_age,

                -- ── FR due time: show remaining time if SLA pending (sla_status = 2) ─
                CASE
                    WHEN frh.sla_status = 2
                    THEN CONCAT(
                        TIMESTAMPDIFF(DAY,    frh.created_at, NOW()), 'd ',
                        TIMESTAMPDIFF(HOUR,   frh.created_at, NOW()) % 24, 'h ',
                        TIMESTAMPDIFF(MINUTE, frh.created_at, NOW()) % 60, 'm '
                    )
                    ELSE ''
                END AS fr_due_time,

                -- ── SRV due time: show remaining time if SLA pending (sla_status = 2) ─
                CASE
                    WHEN stsh.sla_status = 2
                    THEN CONCAT(
                        TIMESTAMPDIFF(DAY,    stsh.created_at, NOW()), 'd ',
                        TIMESTAMPDIFF(HOUR,   stsh.created_at, NOW()) % 24, 'h ',
                        TIMESTAMPDIFF(MINUTE, stsh.created_at, NOW()) % 60, 'm '
                    )
                    ELSE ''
                END AS srv_due_time,

                u.username  AS created_by,
                u2.username AS status_updated_by,
                up.fullname AS created_by_fullname,
                latest_comments.comments AS last_comment

            FROM helpdesk.open_tickets t

            LEFT JOIN helpdesk.companies c
                ON t.business_entity_id = c.id
            LEFT JOIN helpdesk.users u
                ON t.user_id = u.id
            LEFT JOIN helpdesk.users u2
                ON t.status_updated_by = u2.id
            LEFT JOIN helpdesk.users u3
                ON t.assigned_agent_id = u3.id
            LEFT JOIN helpdesk.user_profiles up
                ON t.user_id = up.user_id
            LEFT JOIN helpdesk.teams teams
                ON t.team_id = teams.id
            LEFT JOIN helpdesk.statuses statuses
                ON t.status_id = statuses.id
            LEFT JOIN helpdesk.categories
                ON t.cat_id = categories.id
            LEFT JOIN helpdesk.user_client_mappings ucm
                ON t.client_id_helpdesk = ucm.user_id
                AND ucm.business_entity_id = c.id
            LEFT JOIN helpdesk.sub_categories sub_categories
                ON t.subcat_id = sub_categories.id
            LEFT JOIN helpdesk.ticket_orbits tor
                ON tor.ticket_number = t.ticket_number

            -- ── FR SLA: first_res_configs → first_res_sla_histories ─────────────
            LEFT JOIN helpdesk.first_res_configs frc
                ON frc.team_id = '$teamId'
            LEFT JOIN helpdesk.first_res_sla_histories frh
                ON frh.first_res_config_id = frc.id
                AND frh.ticket_number = t.ticket_number
                AND frh.sla_status != 0

            -- ── SRV SLA: sla_subcat_configs → srv_time_subcat_sla_histories ──────
            LEFT JOIN helpdesk.sla_subcat_configs tsh
                ON tsh.team_id = '$teamId'
            LEFT JOIN helpdesk.srv_time_subcat_sla_histories stsh
                ON stsh.sla_subcat_config_id = tsh.id
                AND stsh.ticket_number = t.ticket_number
                AND stsh.sla_status != 0

            -- ── Latest comment per ticket ────────────────────────────────────────
            LEFT JOIN (
                SELECT ticket_number, comments
                FROM (
                    SELECT tc.ticket_number, tc.comments,
                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                    FROM helpdesk.ticket_comments tc
                ) ranked_comments
                WHERE rn = 1
            ) AS latest_comments ON latest_comments.ticket_number = t.ticket_number

            WHERE t.team_id = '$teamId'
            AND t.status_id = 1

            ORDER BY t.ticket_number DESC
        ");

        return ApiResponse::success($Details, "Success", 200);
    }

    // public function getTeamVsBusinessEntityTicketCountDetailsByBusinessEntityId(Request $request)
    // {
    //     $businessEntityId = $request->businessEntityId;

    //     $teamId = $request->teamId;
    //     if (!empty($teamId) && !empty($businessEntityId)) {
    //         $Details = DB::select("SELECT distinct
    //                         t.id,t.ticket_number,t.user_id,ucm.client_name, fth.fr_response_status_name, tst.srv_time_status_name, 
    //                         t.business_entity_id,c.company_name,t.team_id,t.sid,teams.team_name,t.cat_id,categories.category_in_english,
    //                         t.subcat_id,sub_categories.sub_category_in_english,t.status_id,statuses.status_name,t.created_at,t.updated_at,
    //                         CASE 
    //                                     WHEN t.status_id = 1 THEN 
    //                                         CONCAT(
    //                                             TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
    //                                             TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
    //                                             TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
    //                                         )
                                        
    //                                 END AS ticket_age,

    //                         CASE 
    //                                     WHEN fth.fr_response_status = 2 AND fth.team_id = '$teamId'
    //                                     THEN CONCAT(
    //                                         TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
    //                                         TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
    //                                         TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
    //                                     )
    //                                     ELSE ''
    //                                 END AS fr_due_time,

    //                         CASE 
    //                             WHEN tst.srv_time_status = 2 AND tst.team_id = '$teamId'
    //                             THEN CONCAT(
    //                                 TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
    //                                 TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
    //                                 TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
    //                             )
    //                             ELSE ''
    //                         END AS srv_due_time
    //                         ,u.username AS created_by
    //                         ,u2.username AS status_update_by
    //                         ,latest_comments.comments AS last_comment

    //                         FROM helpdesk.tickets t
    //                         LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                         LEFT JOIN users u ON t.user_id = u.id
    //                         LEFT JOIN users u2 ON t.status_update_by = u2.id
    //                         LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
    //                         LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
    //                         LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                           
    //                         LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
    //                         AND ucm.business_entity_id = c.id
    //                         LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
    //                         LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
    //                         AND t.team_id = fth.team_id AND fth.fr_response_status != 0 AND fth.team_id = '$teamId'
    //                         LEFT JOIN helpdesk.ticket_histories th ON t.ticket_number = th.ticket_number
                            
    //                         LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
    //                         AND t.team_id = tst.team_id AND tst.srv_time_status != 0 AND tst.team_id = '$teamId'
    //                         LEFT JOIN (SELECT ticket_number, comments
	// 								FROM (SELECT tc.ticket_number, tc.comments, 
	// 										ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
	// 											FROM helpdesk.ticket_comments tc) AS ranked_comments
	// 												WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
    //                         where t.team_id = '$teamId'
    //                         AND t.business_entity_id = '$businessEntityId'
    //                         AND t.status_id = 1
                           
    //                         order by t.ticket_number desc");

    //         return ApiResponse::success($Details, "Success", 200);
    //     }
    // }


    

    public function getTeamVsBusinessEntityTicketCountDetailsByBusinessEntityId(Request $request)
    {
        $teamId           = $request->teamId;
        $businessEntityId = $request->businessEntityId;

        if (empty($teamId) || empty($businessEntityId)) {
            return ApiResponse::error("teamId and businessEntityId are required", "Failed", 400);
        }

        $Details = DB::select("
            SELECT DISTINCT
                t.ticket_number,
                t.user_id,
                ucm.client_name,
                frh.sla_status          AS fr_sla_status,
                stsh.sla_status         AS srv_sla_status,
                t.business_entity_id,
                c.company_name,
                t.team_id,
                tor.sid_uid,
                u3.username AS assigned_agent_username,
                teams.team_name,
                t.cat_id,               categories.category_in_english,
                t.subcat_id,            sub_categories.sub_category_in_english,
                t.status_id,            statuses.status_name,
                t.created_at,           t.updated_at,

                -- ── Ticket age (status_id = 1, always open → use NOW()) ──────────
                CONCAT(
                    TIMESTAMPDIFF(DAY,    t.created_at, NOW()), 'd ',
                    TIMESTAMPDIFF(HOUR,   t.created_at, NOW()) % 24, 'h ',
                    TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                ) AS ticket_age,

                -- ── FR due time (sla_status = 2 = pending/breaching) ─────────────
                CASE
                    WHEN frh.sla_status = 2
                    THEN CONCAT(
                        TIMESTAMPDIFF(DAY,    frh.created_at, NOW()), 'd ',
                        TIMESTAMPDIFF(HOUR,   frh.created_at, NOW()) % 24, 'h ',
                        TIMESTAMPDIFF(MINUTE, frh.created_at, NOW()) % 60, 'm '
                    )
                    ELSE ''
                END AS fr_due_time,

                -- ── SRV due time (sla_status = 2 = pending/breaching) ────────────
                CASE
                    WHEN stsh.sla_status = 2
                    THEN CONCAT(
                        TIMESTAMPDIFF(DAY,    stsh.created_at, NOW()), 'd ',
                        TIMESTAMPDIFF(HOUR,   stsh.created_at, NOW()) % 24, 'h ',
                        TIMESTAMPDIFF(MINUTE, stsh.created_at, NOW()) % 60, 'm '
                    )
                    ELSE ''
                END AS srv_due_time,

                u.username  AS created_by,
                u2.username AS status_updated_by,
                up.fullname AS created_by_fullname,
                latest_comments.comments AS last_comment

            FROM helpdesk.open_tickets t

            LEFT JOIN helpdesk.companies c
                ON t.business_entity_id = c.id
            LEFT JOIN helpdesk.users u
                ON t.user_id = u.id
            LEFT JOIN helpdesk.users u2
                ON t.status_updated_by = u2.id
            LEFT JOIN helpdesk.users u3
                ON t.assigned_agent_id = u3.id
            LEFT JOIN helpdesk.user_profiles up
                ON t.user_id = up.user_id
            LEFT JOIN helpdesk.teams teams
                ON t.team_id = teams.id
            LEFT JOIN helpdesk.statuses statuses
                ON t.status_id = statuses.id
            LEFT JOIN helpdesk.categories
                ON t.cat_id = categories.id
            LEFT JOIN helpdesk.user_client_mappings ucm
                ON t.client_id_helpdesk = ucm.user_id
                AND ucm.business_entity_id = c.id
            LEFT JOIN helpdesk.sub_categories sub_categories
                ON t.subcat_id = sub_categories.id
            LEFT JOIN helpdesk.ticket_orbits tor
                ON tor.ticket_number = t.ticket_number

            -- ── FR SLA: first_res_configs → first_res_sla_histories ─────────────
            LEFT JOIN helpdesk.first_res_configs frc
                ON frc.team_id = '$teamId'
            LEFT JOIN helpdesk.first_res_sla_histories frh
                ON frh.first_res_config_id = frc.id
                AND frh.ticket_number = t.ticket_number
                AND frh.sla_status != 0

            -- ── SRV SLA: sla_subcat_configs → srv_time_subcat_sla_histories ──────
            LEFT JOIN helpdesk.sla_subcat_configs tsh
                ON tsh.team_id = '$teamId'
            LEFT JOIN helpdesk.srv_time_subcat_sla_histories stsh
                ON stsh.sla_subcat_config_id = tsh.id
                AND stsh.ticket_number = t.ticket_number
                AND stsh.sla_status != 0

            -- ── Latest comment per ticket ────────────────────────────────────────
            LEFT JOIN (
                SELECT ticket_number, comments
                FROM (
                    SELECT tc.ticket_number, tc.comments,
                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                    FROM helpdesk.ticket_comments tc
                ) ranked_comments
                WHERE rn = 1
            ) AS latest_comments ON latest_comments.ticket_number = t.ticket_number

            WHERE t.team_id           = '$teamId'
            AND t.business_entity_id = '$businessEntityId'
            AND t.status_id          = 1

            ORDER BY t.ticket_number DESC
        ");

        return ApiResponse::success($Details, "Success", 200);
    }
    // public function getTeamVsOwnBusinessEntityTicketCountDetails(Request $request)
    // {
    //     $teamId = $request->teamId;
    //     if (!empty($teamId)) {
    //         $Details = DB::select("SELECT distinct
    //                         t.id,t.ticket_number,t.user_id,ucm.client_name, fth.fr_response_status_name, tst.srv_time_status_name, 
    //                         t.business_entity_id,c.company_name,t.team_id,t.sid,teams.team_name,t.cat_id,categories.category_in_english,
    //                         t.subcat_id,sub_categories.sub_category_in_english,t.status_id,statuses.status_name,t.created_at,t.updated_at,
    //                         CASE 
    //                                     WHEN t.status_id = 1 THEN 
    //                                         CONCAT(
    //                                             TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
    //                                             TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
    //                                             TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
    //                                         )
                                        
    //                                 END AS ticket_age,

    //                         CASE 
    //                                     WHEN fth.fr_response_status = 2 AND fth.team_id = '$teamId'
    //                                     THEN CONCAT(
    //                                         TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
    //                                         TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
    //                                         TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
    //                                     )
    //                                     ELSE ''
    //                                 END AS fr_due_time,

    //                         CASE 
    //                             WHEN tst.srv_time_status = 2 AND tst.team_id = '$teamId'
    //                             THEN CONCAT(
    //                                 TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
    //                                 TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
    //                                 TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
    //                             )
    //                             ELSE ''
    //                         END AS srv_due_time
    //                         ,u.username AS created_by
    //                         ,u2.username AS status_update_by
    //                         ,latest_comments.comments AS last_comment

    //                         FROM helpdesk.tickets t
    //                         LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
    //                         LEFT JOIN users u ON t.user_id = u.id
    //                         LEFT JOIN users u2 ON t.status_update_by = u2.id
    //                         LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
    //                         LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
    //                         LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                           
    //                         LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
    //                         AND ucm.business_entity_id = c.id
    //                         LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
    //                         LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
    //                         AND t.team_id = fth.team_id AND fth.fr_response_status != 0 AND fth.team_id = '$teamId'
    //                         LEFT JOIN helpdesk.ticket_histories th ON t.ticket_number = th.ticket_number
                            
    //                         LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
    //                         AND t.team_id = tst.team_id AND tst.srv_time_status != 0 AND tst.team_id = '$teamId'
    //                         LEFT JOIN (SELECT ticket_number, comments
	// 								FROM (SELECT tc.ticket_number, tc.comments, 
	// 										ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
	// 											FROM helpdesk.ticket_comments tc) AS ranked_comments
	// 												WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
    //                         where t.team_id = '$teamId'
    //                         AND t.status_id = 1
    //                         AND ucm.business_entity_id = 9
                           
    //                         order by t.ticket_number desc");

    //         return ApiResponse::success($Details, "Success", 200);
    //     }
    // }

   

    public function getTeamVsOwnBusinessEntityTicketCountDetails(Request $request)
    {
        $teamId = $request->teamId;

        if (empty($teamId)) {
            return ApiResponse::error("teamId is required", "Failed", 400);
        }

        $Details = DB::select("
            SELECT DISTINCT
                t.ticket_number,
                t.user_id,
                ucm.client_name,
                frh.sla_status          AS fr_sla_status,
                stsh.sla_status         AS srv_sla_status,
                t.business_entity_id,
                c.company_name,
                t.team_id,
                u3.username AS assigned_agent_username,
                tor.sid_uid,
                teams.team_name,
                t.cat_id,               categories.category_in_english,
                t.subcat_id,            sub_categories.sub_category_in_english,
                t.status_id,            statuses.status_name,
                t.created_at,           t.updated_at,

                -- ── Ticket age (status_id = 1, always open → use NOW()) ──────────
                CONCAT(
                    TIMESTAMPDIFF(DAY,    t.created_at, NOW()), 'd ',
                    TIMESTAMPDIFF(HOUR,   t.created_at, NOW()) % 24, 'h ',
                    TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                ) AS ticket_age,

                -- ── FR due time (sla_status = 2 means pending/breaching) ─────────
                CASE
                    WHEN frh.sla_status = 2
                    THEN CONCAT(
                        TIMESTAMPDIFF(DAY,    frh.created_at, NOW()), 'd ',
                        TIMESTAMPDIFF(HOUR,   frh.created_at, NOW()) % 24, 'h ',
                        TIMESTAMPDIFF(MINUTE, frh.created_at, NOW()) % 60, 'm '
                    )
                    ELSE ''
                END AS fr_due_time,

                -- ── SRV due time (sla_status = 2 means pending/breaching) ────────
                CASE
                    WHEN stsh.sla_status = 2
                    THEN CONCAT(
                        TIMESTAMPDIFF(DAY,    stsh.created_at, NOW()), 'd ',
                        TIMESTAMPDIFF(HOUR,   stsh.created_at, NOW()) % 24, 'h ',
                        TIMESTAMPDIFF(MINUTE, stsh.created_at, NOW()) % 60, 'm '
                    )
                    ELSE ''
                END AS srv_due_time,

                u.username  AS created_by,
                u2.username AS status_updated_by,
                up.fullname AS created_by_fullname,
                latest_comments.comments AS last_comment

            FROM helpdesk.open_tickets t

            LEFT JOIN helpdesk.companies c
                ON t.business_entity_id = c.id
            LEFT JOIN helpdesk.users u
                ON t.user_id = u.id
            LEFT JOIN helpdesk.users u2
                ON t.status_updated_by = u2.id
            LEFT JOIN helpdesk.users u3
                ON t.assigned_agent_id = u3.id
            LEFT JOIN helpdesk.user_profiles up
                ON t.user_id = up.user_id
            LEFT JOIN helpdesk.teams teams
                ON t.team_id = teams.id
            LEFT JOIN helpdesk.statuses statuses
                ON t.status_id = statuses.id
            LEFT JOIN helpdesk.categories
                ON t.cat_id = categories.id
            LEFT JOIN helpdesk.user_client_mappings ucm
                ON t.client_id_helpdesk = ucm.user_id
                AND ucm.business_entity_id = c.id
            LEFT JOIN helpdesk.sub_categories sub_categories
                ON t.subcat_id = sub_categories.id
            LEFT JOIN helpdesk.ticket_orbits tor
                ON tor.ticket_number = t.ticket_number

            -- ── FR SLA: first_res_configs → first_res_sla_histories ─────────────
            LEFT JOIN helpdesk.first_res_configs frc
                ON frc.team_id = '$teamId'
            LEFT JOIN helpdesk.first_res_sla_histories frh
                ON frh.first_res_config_id = frc.id
                AND frh.ticket_number = t.ticket_number
                AND frh.sla_status != 0

            -- ── SRV SLA: sla_subcat_configs → srv_time_subcat_sla_histories ──────
            LEFT JOIN helpdesk.sla_subcat_configs tsh
                ON tsh.team_id = '$teamId'
            LEFT JOIN helpdesk.srv_time_subcat_sla_histories stsh
                ON stsh.sla_subcat_config_id = tsh.id
                AND stsh.ticket_number = t.ticket_number
                AND stsh.sla_status != 0

            -- ── Latest comment per ticket ────────────────────────────────────────
            LEFT JOIN (
                SELECT ticket_number, comments
                FROM (
                    SELECT tc.ticket_number, tc.comments,
                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                    FROM helpdesk.ticket_comments tc
                ) ranked_comments
                WHERE rn = 1
            ) AS latest_comments ON latest_comments.ticket_number = t.ticket_number

            WHERE t.team_id = '$teamId'
            AND t.status_id = 1
            AND ucm.business_entity_id = 9

            ORDER BY t.ticket_number DESC
        ");

        return ApiResponse::success($Details, "Success", 200);
    }
    public function getTeamWiseSLAstatistics()
    {
        $Details = DB::select("SELECT 
        t.team_name,
        COALESCE(fr_success.fr_response_success, 0) AS fr_response_success,
        COALESCE(fr_violated.fr_response_violated, 0) AS fr_response_violated,
        COALESCE(srv_violated.srv_time_violated, 0) AS srv_time_violated
        FROM helpdesk.teams t
        LEFT JOIN (
            SELECT frc.team_id, COUNT(DISTINCT frh.ticket_number) AS fr_response_success
            FROM helpdesk.first_res_configs frc
            JOIN helpdesk.first_res_sla_histories frh ON frc.id = frh.first_res_config_id
            WHERE frh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
            AND frh.sla_status = 1
            GROUP BY frc.team_id
        ) fr_success ON fr_success.team_id = t.id
        LEFT JOIN (
            SELECT frc.team_id, COUNT(DISTINCT frh.ticket_number) AS fr_response_violated
            FROM helpdesk.first_res_configs frc
            JOIN helpdesk.first_res_sla_histories frh ON frc.id = frh.first_res_config_id
            WHERE frh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
            AND frh.sla_status = 0
            GROUP BY frc.team_id
        ) fr_violated ON fr_violated.team_id = t.id
        LEFT JOIN (
            SELECT ssc.team_id, COUNT(DISTINCT stsh.ticket_number) AS srv_time_violated
            FROM helpdesk.sla_subcat_configs ssc
            JOIN helpdesk.srv_time_subcat_sla_histories stsh ON ssc.id = stsh.sla_subcat_config_id
            WHERE stsh.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
            AND stsh.sla_status = 2
            GROUP BY ssc.team_id
        ) srv_violated ON srv_violated.team_id = t.id
        WHERE 
            fr_success.fr_response_success IS NOT NULL OR
            fr_violated.fr_response_violated IS NOT NULL OR
            srv_violated.srv_time_violated IS NOT NULL
        ORDER BY t.team_name");
        return ApiResponse::success($Details, "Success", 200);
    }


    // public function getTicketCountAndAvgTimeByTeamOwnEntity()
    // {
    //     $Details_Own = DB::select("SELECT 
    //                                 te.team_name,te.id,
                                    
    //                                 -- Coxs Bazar
    //                                 SUM(CASE WHEN ucm.client_name = 'Coxs Bazar' THEN 1 ELSE 0 END) AS Coxs_Bazar_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Coxs Bazar' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Coxs Bazar' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Coxs Bazar' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Coxs_Bazar,
                                    
    //                                 -- Chittagong Zonal Office
    //                                 SUM(CASE WHEN ucm.client_name = 'Chittagong Zonal Office' THEN 1 ELSE 0 END) AS Chittagong_Zonal_Office_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Chittagong Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Chittagong Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Chittagong Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Chittagong_Zonal_Office,

    //                                 -- Uttara Area Office
    //                                 SUM(CASE WHEN ucm.client_name = 'Uttara Area Office' THEN 1 ELSE 0 END) AS Uttara_Area_Office_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Uttara Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Uttara Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Uttara Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Uttara_Area_Office,

    //                                 -- Bashundhara Area Office
    //                                 SUM(CASE WHEN ucm.client_name = 'Bashundhara Area Office' THEN 1 ELSE 0 END) AS Bashundhara_Area_Office_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Bashundhara Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Bashundhara Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Bashundhara Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Bashundhara_Area_Office,

    //                                 -- Rajshahi Zonal Office
    //                                 SUM(CASE WHEN ucm.client_name = 'Rajshahi Zonal Office' THEN 1 ELSE 0 END) AS Rajshahi_Zonal_Office_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Rajshahi Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Rajshahi Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Rajshahi Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Rajshahi_Zonal_Office,

    //                                 -- Khulna Zonal Office
    //                                 SUM(CASE WHEN ucm.client_name = 'Khulna Zonal Office' THEN 1 ELSE 0 END) AS Khulna_Zonal_Office_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Khulna Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Khulna Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Khulna Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Khulna_Zonal_Office,

    //                                 -- Sylhet Zonal Office
    //                                 SUM(CASE WHEN ucm.client_name = 'Sylhet Zonal Office' THEN 1 ELSE 0 END) AS Sylhet_Zonal_Office_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Sylhet Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Sylhet Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Sylhet Zonal Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Sylhet_Zonal_Office,

    //                                 -- Dhanmondi Area Office
    //                                 SUM(CASE WHEN ucm.client_name = 'Dhanmondi Area Office' THEN 1 ELSE 0 END) AS Dhanmondi_Area_Office_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Dhanmondi Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Dhanmondi Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Dhanmondi Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Dhanmondi_Area_Office,

    //                                 -- Banani Area Office
    //                                 SUM(CASE WHEN ucm.client_name = 'Banani Area Office' THEN 1 ELSE 0 END) AS Banani_Area_Office_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Banani Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Banani Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Banani Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Banani_Area_Office,

    //                                 -- Race Online Limited
    //                                 SUM(CASE WHEN ucm.client_name = 'Race Online Limited' THEN 1 ELSE 0 END) AS Race_Online_Limited_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Race Online Limited' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Race Online Limited' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Race Online Limited' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Race_Online_Limited,

    //                                 -- Motijheel Area Office
    //                                 SUM(CASE WHEN ucm.client_name = 'Motijheel Area Office' THEN 1 ELSE 0 END) AS Motijheel_Area_Office_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Motijheel Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Motijheel Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Motijheel Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Motijheel_Area_Office,

    //                                 -- Niketon Area Office
    //                                 SUM(CASE WHEN ucm.client_name = 'Niketon Area Office' THEN 1 ELSE 0 END) AS Niketon_Area_Office_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Niketon Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Niketon Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Niketon Area Office' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Niketon_Area_Office,

    //                                 -- Corporate User
    //                                 SUM(CASE WHEN ucm.client_name = 'Corporate User' THEN 1 ELSE 0 END) AS Corporate_User_Total,
    //                                 CONCAT(
    //                                     FLOOR(AVG(CASE WHEN ucm.client_name = 'Corporate User' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Corporate User' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400) / 3600), 'h ',
    //                                     FLOOR((AVG(CASE WHEN ucm.client_name = 'Corporate User' AND t.status_id != 6 THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600) / 60), 'm'
    //                                 ) AS Avg_time_Corporate_User,

    //                                 -- Total open tickets
    //                                 count(t.team_id) as 'total_open'

    //                             FROM 
    //                                 helpdesk.user_client_mappings ucm 
    //                                 INNER JOIN helpdesk.tickets t ON ucm.client_id = t.client_id_vendor
    //                                 LEFT JOIN helpdesk.teams te ON t.team_id = te.id

    //                             WHERE ucm.business_entity_id = 9
    //                             AND t.status_id != 6

    //                             GROUP BY te.team_name,te.id
    //                             ORDER BY total_open desc");


    //     return ApiResponse::success($Details_Own, "Success", 200);
    // }

// last
    public function getTicketCountAndAvgTimeByTeamOwnEntity()
    {
    

        $Details_Own = DB::select("
            SELECT
                te.team_name,
                te.id,

                -- ── Coxs Bazar ───────────────────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Coxs Bazar' THEN 1 ELSE 0 END) AS Coxs_Bazar_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Coxs Bazar' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Coxs Bazar' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Coxs Bazar' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Coxs_Bazar,

                -- ── Chittagong Zonal Office ──────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Chittagong Zonal Office' THEN 1 ELSE 0 END) AS Chittagong_Zonal_Office_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Chittagong Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Chittagong Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Chittagong Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Chittagong_Zonal_Office,

                -- ── Uttara Area Office ───────────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Uttara Area Office' THEN 1 ELSE 0 END) AS Uttara_Area_Office_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Uttara Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Uttara Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Uttara Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Uttara_Area_Office,

                -- ── Bashundhara Area Office ──────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Bashundhara Area Office' THEN 1 ELSE 0 END) AS Bashundhara_Area_Office_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Bashundhara Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Bashundhara Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Bashundhara Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Bashundhara_Area_Office,

                -- ── Rajshahi Zonal Office ────────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Rajshahi Zonal Office' THEN 1 ELSE 0 END) AS Rajshahi_Zonal_Office_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Rajshahi Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Rajshahi Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Rajshahi Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Rajshahi_Zonal_Office,

                -- ── Khulna Zonal Office ──────────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Khulna Zonal Office' THEN 1 ELSE 0 END) AS Khulna_Zonal_Office_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Khulna Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Khulna Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Khulna Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Khulna_Zonal_Office,

                -- ── Sylhet Zonal Office ──────────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Sylhet Zonal Office' THEN 1 ELSE 0 END) AS Sylhet_Zonal_Office_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Sylhet Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Sylhet Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Sylhet Zonal Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Sylhet_Zonal_Office,

                -- ── Dhanmondi Area Office ────────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Dhanmondi Area Office' THEN 1 ELSE 0 END) AS Dhanmondi_Area_Office_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Dhanmondi Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Dhanmondi Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Dhanmondi Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Dhanmondi_Area_Office,

                -- ── Banani Area Office ───────────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Banani Area Office' THEN 1 ELSE 0 END) AS Banani_Area_Office_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Banani Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Banani Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Banani Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Banani_Area_Office,

                -- ── Race Online Limited ──────────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Race Online Limited' THEN 1 ELSE 0 END) AS Race_Online_Limited_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Race Online Limited' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Race Online Limited' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Race Online Limited' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Race_Online_Limited,

                -- ── Motijheel Area Office ────────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Motijheel Area Office' THEN 1 ELSE 0 END) AS Motijheel_Area_Office_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Motijheel Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Motijheel Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Motijheel Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Motijheel_Area_Office,

                -- ── Niketon Area Office ──────────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Niketon Area Office' THEN 1 ELSE 0 END) AS Niketon_Area_Office_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Niketon Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Niketon Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Niketon Area Office' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Niketon_Area_Office,

                -- ── Corporate User ───────────────────────────────────────────────
                SUM(CASE WHEN ucm.client_name = 'Corporate User' THEN 1 ELSE 0 END) AS Corporate_User_Total,
                CONCAT(
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Corporate User' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) / 86400), 'd ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Corporate User' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 86400 / 3600), 'h ',
                    FLOOR(AVG(CASE WHEN ucm.client_name = 'Corporate User' THEN TIMESTAMPDIFF(SECOND, t.created_at, NOW()) ELSE NULL END) % 3600 / 60), 'm'
                ) AS Avg_time_Corporate_User,

                -- ── Total open tickets ───────────────────────────────────────────
                COUNT(t.team_id) AS total_open

            FROM helpdesk.user_client_mappings ucm
            INNER JOIN helpdesk.open_tickets t  ON ucm.client_id = t.client_id_vendor
            LEFT JOIN  helpdesk.teams te        ON t.team_id = te.id

            WHERE ucm.business_entity_id = 9

            GROUP BY te.team_name, te.id
            ORDER BY total_open DESC
        ");

        return ApiResponse::success($Details_Own, "Success", 200);
    }


    public function getTicketCountByOwnEntity()
    {
        $Open_ticket_Own = DB::select("SELECT ucm.client_name, ucm.client_id,
                                SUM(CASE WHEN t.id THEN 1 ELSE 0 END) AS Open_Count
                                FROM helpdesk.user_client_mappings ucm 
                                LEFT JOIN helpdesk.open_tickets t ON ucm.client_id = t.client_id_vendor
                                WHERE ucm.business_entity_id = 9
                                GROUP BY ucm.client_name, ucm.client_id
                                ORDER BY ucm.client_name");


        return ApiResponse::success($Open_ticket_Own, "Success", 200);
    }
}
