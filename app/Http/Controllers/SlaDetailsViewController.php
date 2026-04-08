<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use Illuminate\Support\Facades\DB;

class SlaDetailsViewController extends Controller
{
    // public function getTicketSlaReport(string $ticketNumber)
    // {
    //     // ===============================
    //     // QUERY 1: SLA Summary
    //     // ===============================
    //     $slaSummary = DB::selectOne('
    //         SELECT 
    //             sh.ticket_number,
    //             sh.sla_success_count,
    //             sh.sla_failed_count,
    //             sh.sla_started_count,
    //             frs.fr_success_count,
    //             frs.fr_failed_count,
    //             frs.fr_started_count,
    //             sh.teams_traversed_count,
    //             sh.teams_traversed,
    //             sh.first_event_at,
    //             sh.last_event_at
    //         FROM (
    //             SELECT 
    //                 sh.ticket_number,
    //                 COUNT(CASE WHEN sh.sla_status = 1 THEN 1 END) AS sla_success_count,
    //                 COUNT(CASE WHEN sh.sla_status = 0 THEN 1 END) AS sla_failed_count,
    //                 COUNT(CASE WHEN sh.sla_status = 2 THEN 1 END) AS sla_started_count,
    //                 COUNT(DISTINCT sc.team_id)                     AS teams_traversed_count,
    //                 GROUP_CONCAT(DISTINCT t.team_name ORDER BY t.team_name SEPARATOR ", ") AS teams_traversed,
    //                 MIN(sh.created_at) AS first_event_at,
    //                 MAX(sh.created_at) AS last_event_at
    //             FROM srv_time_subcat_sla_histories sh
    //             JOIN sla_subcat_configs sc ON sh.sla_subcat_config_id = sc.id
    //             JOIN teams t ON sc.team_id = t.id
    //             WHERE sh.ticket_number = :ticketNumber1
    //             GROUP BY sh.ticket_number
    //         ) sh
    //         JOIN (
    //             SELECT 
    //                 frs.ticket_number,
    //                 COUNT(CASE WHEN frs.sla_status = 1 THEN 1 END) AS fr_success_count,
    //                 COUNT(CASE WHEN frs.sla_status = 0 THEN 1 END) AS fr_failed_count,
    //                 COUNT(CASE WHEN frs.sla_status = 2 THEN 1 END) AS fr_started_count
    //             FROM first_res_sla_histories frs
    //             JOIN first_res_configs frc ON frs.first_res_config_id = frc.id
    //             WHERE frs.ticket_number = :ticketNumber2
    //             GROUP BY frs.ticket_number
    //         ) frs ON sh.ticket_number = frs.ticket_number
    //     ', [
    //         'ticketNumber1' => $ticketNumber,
    //         'ticketNumber2' => $ticketNumber,
    //     ]);

    //     // ===============================
    //     // QUERY 2: Ticket Age & Subcategory
    //     // ===============================
    //     $ticketMeta = DB::selectOne('
    //         SELECT 
    //             th.ticket_number,
    //             TIME_FORMAT(
    //                 SEC_TO_TIME(
    //                     TIMESTAMPDIFF(SECOND, MIN(th.created_at), MAX(th.updated_at))
    //                 ),
    //                 "%H:%i"
    //             ) AS ticket_age,
    //             sc.sub_category_in_english
    //         FROM ticket_histories th
    //         JOIN sub_categories sc ON th.subcat_id = sc.id
    //         WHERE th.ticket_number = :ticketNumber
    //         GROUP BY th.ticket_number, sc.sub_category_in_english
    //     ', [
    //         'ticketNumber' => $ticketNumber,
    //     ]);

    //     // ===============================
    //     // MERGE & RETURN
    //     // ===============================
    //     if (!$slaSummary && !$ticketMeta) {
    //         return ApiResponse::error('No data found for ticket ' . $ticketNumber, 'Not Found', 404);
    //     }

    //     $result = [
    //         'ticket_number'         => $ticketNumber,
    //         'sub_category'          => $ticketMeta->sub_category_in_english ?? null,
    //         'ticket_age'            => $ticketMeta->ticket_age ?? null,
    //         'sla_success_count'     => $slaSummary->sla_success_count ?? 0,
    //         'sla_failed_count'      => $slaSummary->sla_failed_count ?? 0,
    //         'sla_started_count'     => $slaSummary->sla_started_count ?? 0,
    //         'fr_success_count'      => $slaSummary->fr_success_count ?? 0,
    //         'fr_failed_count'       => $slaSummary->fr_failed_count ?? 0,
    //         'fr_started_count'      => $slaSummary->fr_started_count ?? 0,
    //         'teams_traversed_count' => $slaSummary->teams_traversed_count ?? 0,
    //         'teams_traversed'       => $slaSummary->teams_traversed ?? null,
    //         'first_event_at'        => $slaSummary->first_event_at ?? null,
    //         'last_event_at'         => $slaSummary->last_event_at ?? null,
    //     ];

    //     return ApiResponse::success($result, 'Ticket SLA Report', 200);
    // }

    // public function getTicketSlaReport(string $ticketNumber)
    // {
    //     // ===============================
    //     // QUERY 1: SLA Summary
    //     // ===============================
    //     $slaSummary = DB::selectOne('
    //         SELECT 
    //             sh.ticket_number,
    //             sh.sla_success_count,
    //             sh.sla_failed_count,
    //             sh.sla_started_count,
    //             frs.fr_success_count,
    //             frs.fr_failed_count,
    //             frs.fr_started_count,
    //             sh.teams_traversed_count,
    //             sh.teams_traversed,
    //             sh.first_event_at,
    //             sh.last_event_at
    //         FROM (
    //             SELECT 
    //                 sh.ticket_number,
    //                 COUNT(CASE WHEN sh.sla_status = 1 THEN 1 END) AS sla_success_count,
    //                 COUNT(CASE WHEN sh.sla_status = 0 THEN 1 END) AS sla_failed_count,
    //                 COUNT(CASE WHEN sh.sla_status = 2 THEN 1 END) AS sla_started_count,
    //                 COUNT(DISTINCT sc.team_id)                     AS teams_traversed_count,
    //                 GROUP_CONCAT(DISTINCT t.team_name ORDER BY t.team_name SEPARATOR ", ") AS teams_traversed,
    //                 MIN(sh.created_at) AS first_event_at,
    //                 MAX(sh.created_at) AS last_event_at
    //             FROM srv_time_subcat_sla_histories sh
    //             JOIN sla_subcat_configs sc ON sh.sla_subcat_config_id = sc.id
    //             JOIN teams t ON sc.team_id = t.id
    //             WHERE sh.ticket_number = :ticketNumber1
    //             GROUP BY sh.ticket_number
    //         ) sh
    //         JOIN (
    //             SELECT 
    //                 frs.ticket_number,
    //                 COUNT(CASE WHEN frs.sla_status = 1 THEN 1 END) AS fr_success_count,
    //                 COUNT(CASE WHEN frs.sla_status = 0 THEN 1 END) AS fr_failed_count,
    //                 COUNT(CASE WHEN frs.sla_status = 2 THEN 1 END) AS fr_started_count
    //             FROM first_res_sla_histories frs
    //             JOIN first_res_configs frc ON frs.first_res_config_id = frc.id
    //             WHERE frs.ticket_number = :ticketNumber2
    //             GROUP BY frs.ticket_number
    //         ) frs ON sh.ticket_number = frs.ticket_number
    //     ', [
    //         'ticketNumber1' => $ticketNumber,
    //         'ticketNumber2' => $ticketNumber,
    //     ]);

    //     // ===============================
    //     // QUERY 2: Ticket Age & Subcategory
    //     // ===============================
    //     $ticketMeta = DB::selectOne('
    //         SELECT 
    //             th.ticket_number,
    //             TIME_FORMAT(
    //                 SEC_TO_TIME(
    //                     TIMESTAMPDIFF(SECOND, MIN(th.created_at), MAX(th.updated_at))
    //                 ),
    //                 "%H:%i"
    //             ) AS ticket_age,
    //             sc.sub_category_in_english
    //         FROM ticket_histories th
    //         JOIN sub_categories sc ON th.subcat_id = sc.id
    //         WHERE th.ticket_number = :ticketNumber
    //         GROUP BY th.ticket_number, sc.sub_category_in_english
    //     ', [
    //         'ticketNumber' => $ticketNumber,
    //     ]);

    //     // ===============================
    //     // QUERY 3: Step-by-Step SLA Details
    //     // ===============================
    //     $slaDetails = DB::select('
    //         SELECT 
    //             sla.ticket_number,
    //             sla.team_name,
    //             sla.step_number,
    //             sla.in_time,
    //             sla.out_time,
    //             sla.resolution_min,
    //             TIMESTAMPDIFF(MINUTE, sla.in_time, sla.out_time) AS duration_min,
    //             sla.sla_result,
    //             fr.duration_min         AS fr_duration_min,
    //             fr.allowed_duration_min AS fr_allowed_duration_min,
    //             fr.fr_result
    //         FROM (
    //             SELECT 
    //                 sh.ticket_number,
    //                 t.team_name,
    //                 sc.resolution_min,
    //                 sh.created_at AS in_time,
    //                 LEAD(sh.created_at) OVER (PARTITION BY sh.ticket_number ORDER BY sh.created_at) AS out_time,
    //                 CASE 
    //                     WHEN LEAD(sh.sla_status) OVER (PARTITION BY sh.ticket_number ORDER BY sh.created_at) = 1 THEN "Success"
    //                     WHEN LEAD(sh.sla_status) OVER (PARTITION BY sh.ticket_number ORDER BY sh.created_at) = 0 THEN "Failed"
    //                 END AS sla_result,
    //                 ROW_NUMBER() OVER (ORDER BY sh.created_at) AS step_number,
    //                 sh.sla_status
    //             FROM srv_time_subcat_sla_histories sh
    //             JOIN sla_subcat_configs sc ON sh.sla_subcat_config_id = sc.id
    //             JOIN teams t ON sc.team_id = t.id
    //             WHERE sh.ticket_number = :ticketNumber1
    //         ) sla
    //         LEFT JOIN (
    //             SELECT 
    //                 frs.ticket_number,
    //                 t.team_name,
    //                 frc.duration_min AS allowed_duration_min,
    //                 frs.created_at AS in_time,
    //                 LEAD(frs.created_at) OVER (PARTITION BY frs.ticket_number ORDER BY frs.created_at) AS out_time,
    //                 TIMESTAMPDIFF(MINUTE, 
    //                     frs.created_at, 
    //                     LEAD(frs.created_at) OVER (PARTITION BY frs.ticket_number ORDER BY frs.created_at)
    //                 ) AS duration_min,
    //                 CASE 
    //                     WHEN LEAD(frs.sla_status) OVER (PARTITION BY frs.ticket_number ORDER BY frs.created_at) = 1 THEN "Success"
    //                     WHEN LEAD(frs.sla_status) OVER (PARTITION BY frs.ticket_number ORDER BY frs.created_at) = 0 THEN "Failed"
    //                 END AS fr_result,
    //                 ROW_NUMBER() OVER (ORDER BY frs.created_at) AS step_number,
    //                 frs.sla_status
    //             FROM first_res_sla_histories frs
    //             JOIN first_res_configs frc ON frs.first_res_config_id = frc.id
    //             JOIN teams t ON frc.team_id = t.id
    //             WHERE frs.ticket_number = :ticketNumber2
    //         ) fr ON sla.step_number = fr.step_number
    //             AND fr.sla_status = 2
    //             AND fr.fr_result IS NOT NULL
    //         WHERE sla.sla_status = 2
    //         AND sla.sla_result IS NOT NULL
    //         ORDER BY sla.in_time
    //     ', [
    //         'ticketNumber1' => $ticketNumber,
    //         'ticketNumber2' => $ticketNumber,
    //     ]);

    //     // ===============================
    //     // MERGE & RETURN
    //     // ===============================
    //     if (!$slaSummary && !$ticketMeta) {
    //         return ApiResponse::error('No data found for ticket ' . $ticketNumber, 'Not Found', 404);
    //     }

    //     $result = [
    //         'ticket_number'         => $ticketNumber,
    //         'sub_category'          => $ticketMeta->sub_category_in_english ?? null,
    //         'ticket_age'            => $ticketMeta->ticket_age ?? null,
    //         'sla_success_count'     => $slaSummary->sla_success_count ?? 0,
    //         'sla_failed_count'      => $slaSummary->sla_failed_count ?? 0,
    //         'sla_started_count'     => $slaSummary->sla_started_count ?? 0,
    //         'fr_success_count'      => $slaSummary->fr_success_count ?? 0,
    //         'fr_failed_count'       => $slaSummary->fr_failed_count ?? 0,
    //         'fr_started_count'      => $slaSummary->fr_started_count ?? 0,
    //         'teams_traversed_count' => $slaSummary->teams_traversed_count ?? 0,
    //         'teams_traversed'       => $slaSummary->teams_traversed ?? null,
    //         'first_event_at'        => $slaSummary->first_event_at ?? null,
    //         'last_event_at'         => $slaSummary->last_event_at ?? null,
    //         // ✅ Step-by-step details added here
    //         'details'               => $slaDetails,
    //     ];

    //     return ApiResponse::success($result, 'Ticket SLA Report', 200);
    // }

    public function getTicketSlaReport(string $ticketNumber)
    {
        // ===============================
        // QUERY 1: SLA Summary
        // ===============================
        $slaSummary = DB::selectOne('
            SELECT 
                sh.ticket_number,
                sh.sla_success_count,
                sh.sla_failed_count,
                sh.sla_started_count,
                frs.fr_success_count,
                frs.fr_failed_count,
                frs.fr_started_count,
                sh.teams_traversed_count,
                sh.teams_traversed,
                sh.first_event_at,
                sh.last_event_at
            FROM (
                SELECT 
                    sh.ticket_number,
                    COUNT(CASE WHEN sh.sla_status = 1 THEN 1 END) AS sla_success_count,
                    COUNT(CASE WHEN sh.sla_status = 0 THEN 1 END) AS sla_failed_count,
                    COUNT(CASE WHEN sh.sla_status = 2 THEN 1 END) AS sla_started_count,
                    COUNT(DISTINCT sc.team_id)                     AS teams_traversed_count,
                    GROUP_CONCAT(DISTINCT t.team_name ORDER BY t.team_name SEPARATOR ", ") AS teams_traversed,
                    MIN(sh.created_at) AS first_event_at,
                    MAX(sh.created_at) AS last_event_at
                FROM srv_time_subcat_sla_histories sh
                JOIN sla_subcat_configs sc ON sh.sla_subcat_config_id = sc.id
                JOIN teams t ON sc.team_id = t.id
                WHERE sh.ticket_number = :ticketNumber1
                GROUP BY sh.ticket_number
            ) sh
            JOIN (
                SELECT 
                    frs.ticket_number,
                    COUNT(CASE WHEN frs.sla_status = 1 THEN 1 END) AS fr_success_count,
                    COUNT(CASE WHEN frs.sla_status = 0 THEN 1 END) AS fr_failed_count,
                    COUNT(CASE WHEN frs.sla_status = 2 THEN 1 END) AS fr_started_count
                FROM first_res_sla_histories frs
                JOIN first_res_configs frc ON frs.first_res_config_id = frc.id
                WHERE frs.ticket_number = :ticketNumber2
                GROUP BY frs.ticket_number
            ) frs ON sh.ticket_number = frs.ticket_number
        ', [
            'ticketNumber1' => $ticketNumber,
            'ticketNumber2' => $ticketNumber,
        ]);

        // ===============================
        // QUERY 2: Ticket Age & Subcategory
        // ===============================
        $ticketMeta = DB::selectOne('
            SELECT 
                th.ticket_number,
                CONCAT(
                    FLOOR(TIMESTAMPDIFF(MINUTE, MIN(th.created_at), MAX(th.updated_at)) / 1440), "d ",
                    FLOOR((TIMESTAMPDIFF(MINUTE, MIN(th.created_at), MAX(th.updated_at)) % 1440) / 60), "h ",
                    TIMESTAMPDIFF(MINUTE, MIN(th.created_at), MAX(th.updated_at)) % 60, "m"
                ) AS ticket_age,
                sc.sub_category_in_english
            FROM ticket_histories th
            JOIN sub_categories sc ON th.subcat_id = sc.id
            WHERE th.ticket_number = :ticketNumber
            GROUP BY th.ticket_number, sc.sub_category_in_english
        ', [
            'ticketNumber' => $ticketNumber,
        ]);

        // ===============================
        // QUERY 3: Step-by-Step SLA Details
        // ===============================
        $slaDetails = DB::select('
            SELECT 
                sla.ticket_number,
                sla.team_name,
                sla.step_number,
                sla.in_time,
                sla.out_time,
                sla.resolution_min,
                TIMESTAMPDIFF(MINUTE, sla.in_time, sla.out_time) AS duration_min,
                CONCAT(
                    FLOOR(TIMESTAMPDIFF(MINUTE, sla.in_time, sla.out_time) / 1440), "d ",
                    FLOOR((TIMESTAMPDIFF(MINUTE, sla.in_time, sla.out_time) % 1440) / 60), "h ",
                    TIMESTAMPDIFF(MINUTE, sla.in_time, sla.out_time) % 60, "m"
                ) AS stay_time,
                sla.sla_result,
                fr.duration_min         AS fr_duration_min,
                fr.allowed_duration_min AS fr_allowed_duration_min,
                fr.fr_result
            FROM (
                SELECT 
                    sh.ticket_number,
                    t.team_name,
                    sc.resolution_min,
                    sh.created_at AS in_time,
                    LEAD(sh.created_at) OVER (PARTITION BY sh.ticket_number ORDER BY sh.created_at) AS out_time,
                    CASE 
                        WHEN LEAD(sh.sla_status) OVER (PARTITION BY sh.ticket_number ORDER BY sh.created_at) = 1 THEN "Success"
                        WHEN LEAD(sh.sla_status) OVER (PARTITION BY sh.ticket_number ORDER BY sh.created_at) = 0 THEN "Failed"
                    END AS sla_result,
                    ROW_NUMBER() OVER (ORDER BY sh.created_at) AS step_number,
                    sh.sla_status
                FROM srv_time_subcat_sla_histories sh
                JOIN sla_subcat_configs sc ON sh.sla_subcat_config_id = sc.id
                JOIN teams t ON sc.team_id = t.id
                WHERE sh.ticket_number = :ticketNumber1
            ) sla
            LEFT JOIN (
                SELECT 
                    frs.ticket_number,
                    t.team_name,
                    frc.duration_min AS allowed_duration_min,
                    frs.created_at AS in_time,
                    LEAD(frs.created_at) OVER (PARTITION BY frs.ticket_number ORDER BY frs.created_at) AS out_time,
                    TIMESTAMPDIFF(MINUTE, 
                        frs.created_at, 
                        LEAD(frs.created_at) OVER (PARTITION BY frs.ticket_number ORDER BY frs.created_at)
                    ) AS duration_min,
                    CASE 
                        WHEN LEAD(frs.sla_status) OVER (PARTITION BY frs.ticket_number ORDER BY frs.created_at) = 1 THEN "Success"
                        WHEN LEAD(frs.sla_status) OVER (PARTITION BY frs.ticket_number ORDER BY frs.created_at) = 0 THEN "Failed"
                    END AS fr_result,
                    ROW_NUMBER() OVER (ORDER BY frs.created_at) AS step_number,
                    frs.sla_status
                FROM first_res_sla_histories frs
                JOIN first_res_configs frc ON frs.first_res_config_id = frc.id
                JOIN teams t ON frc.team_id = t.id
                WHERE frs.ticket_number = :ticketNumber2
            ) fr ON sla.step_number = fr.step_number
                AND fr.sla_status = 2
                AND fr.fr_result IS NOT NULL
            WHERE sla.sla_status = 2
            AND sla.sla_result IS NOT NULL
            ORDER BY sla.in_time
        ', [
            'ticketNumber1' => $ticketNumber,
            'ticketNumber2' => $ticketNumber,
        ]);

        // ===============================
        // MERGE & RETURN
        // ===============================
        if (!$slaSummary && !$ticketMeta) {
            return ApiResponse::error('No data found for ticket ' . $ticketNumber, 'Not Found', 404);
        }

        $result = [
            'ticket_number'         => $ticketNumber,
            'sub_category'          => $ticketMeta->sub_category_in_english ?? null,
            'ticket_age'            => $ticketMeta->ticket_age ?? null,
            'sla_success_count'     => $slaSummary->sla_success_count ?? 0,
            'sla_failed_count'      => $slaSummary->sla_failed_count ?? 0,
            'sla_started_count'     => $slaSummary->sla_started_count ?? 0,
            'fr_success_count'      => $slaSummary->fr_success_count ?? 0,
            'fr_failed_count'       => $slaSummary->fr_failed_count ?? 0,
            'fr_started_count'      => $slaSummary->fr_started_count ?? 0,
            'teams_traversed_count' => $slaSummary->teams_traversed_count ?? 0,
            'teams_traversed'       => $slaSummary->teams_traversed ?? null,
            'first_event_at'        => $slaSummary->first_event_at ?? null,
            'last_event_at'         => $slaSummary->last_event_at ?? null,
            'details'               => $slaDetails,
        ];

        return ApiResponse::success($result, 'Ticket SLA Report', 200);
    }
}
