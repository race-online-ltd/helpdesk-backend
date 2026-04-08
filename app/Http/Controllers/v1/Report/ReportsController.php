<?php

namespace App\Http\Controllers\v1\Report;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    public function reportsFilterValue()
    {
        try {
            $cpmpanies = DB::select("SELECT c.id, c.company_name FROM companies c");
            return ApiResponse::success($cpmpanies, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function reportsOutput()
    {
        try {
            // Step 1: Fetch distinct ticket numbers
            $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth");

            $ListOfTicket = array_map(fn($item) => $item->ticket_number, $fr_teamTicket);

            $ListOfTimeDifferences = [];

            // Step 2: Compute time differences for each team and ticket
            foreach ($ListOfTicket as $ticket_number) {
                $fr_teamID = DB::select("SELECT DISTINCT(fth.team_id), t.team_name 
                    FROM helpdesk.ticket_fr_time_team_histories fth 
                    JOIN helpdesk.teams t ON fth.team_id = t.id
                    WHERE fth.ticket_number = ?
                ", [$ticket_number]);

                foreach ($fr_teamID as $team) {
                    $team_id = $team->team_id;
                    $team_name = $team->team_name;

                    $fr_statusRecords = DB::select("SELECT fth.fr_response_status, fth.created_at 
                        FROM helpdesk.ticket_fr_time_team_histories fth
                        WHERE fth.ticket_number = ? AND fth.team_id = ? 
                        AND fth.fr_response_status IN (0, 1, 2) 
                        ORDER BY fth.created_at ASC
                    ", [$ticket_number, $team_id]);

                    $timestamps = [
                        'status_0' => null,
                        'status_1_or_2' => null
                    ];

                    foreach ($fr_statusRecords as $record) {
                        if ($record->fr_response_status == 0) {
                            $timestamps['status_0'] = $record->created_at;
                        } elseif (in_array($record->fr_response_status, [1, 2])) {
                            $timestamps['status_1_or_2'] = $record->created_at;
                        }
                    }

                    // Calculate the time difference if both timestamps are available
                    if ($timestamps['status_0'] && $timestamps['status_1_or_2']) {
                        $startTime = new DateTime($timestamps['status_0']);
                        $endTime = new DateTime($timestamps['status_1_or_2']);
                        $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

                        $ListOfTimeDifferences[] = [
                            'team_id' => $team_id,
                            'team_name' => $team_name,
                            'time_difference_in_seconds' => $timeDifferenceInSeconds
                        ];
                    }
                }
            }

            // Step 3: Summing time and calculating the average per team
            $timeSumsByTeam = [];
            foreach ($ListOfTimeDifferences as $entry) {
                $team_id = $entry['team_id'];
                $team_name = $entry['team_name'];
                $time_difference = $entry['time_difference_in_seconds'];

                if (!isset($timeSumsByTeam[$team_id])) {
                    $timeSumsByTeam[$team_id] = [
                        'team_name' => $team_name,
                        'total_time_in_seconds' => 0,
                        'count' => 0
                    ];
                }

                $timeSumsByTeam[$team_id]['total_time_in_seconds'] += $time_difference;
                $timeSumsByTeam[$team_id]['count']++;
            }

            // Step 4: Calculate average times
            $averagesByTeam = [];
            foreach ($timeSumsByTeam as $team_id => $data) {
                $totalTime = $data['total_time_in_seconds'];
                $count = $data['count'];
                $averageTime = $totalTime / $count;

                $averagesByTeam[$team_id] = [
                    'team_id' => $team_id,
                    'team_name' => $data['team_name'],
                    'total_time' => gmdate("H:i:s", $totalTime), // Convert seconds to H:i:s format
                    'average_time' => gmdate("H:i:s", $averageTime)
                ];
            }

            // Step 5: Add closed and open tickets data
            $ticketCounts = DB::select("SELECT t.id AS team_id, t.team_name, 
                    COUNT(DISTINCT CASE WHEN th1.status_id = 6 THEN th1.ticket_number END) AS ticket_closed_by_team,
                    COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team
                FROM helpdesk.teams t
                LEFT JOIN helpdesk.ticket_histories th1 ON th1.team_id = t.id
                LEFT JOIN helpdesk.tickets th2 ON th2.team_id = t.id
                GROUP BY t.id, t.team_name
                ORDER BY t.team_name
            ");

            // Combine averages and ticket counts
            foreach ($ticketCounts as $ticketData) {
                $team_id = $ticketData->team_id;

                $averagesByTeam[$team_id]['ticket_closed_by_team'] = $ticketData->ticket_closed_by_team ?? 0;
                $averagesByTeam[$team_id]['ticket_open_by_team'] = $ticketData->ticket_open_by_team ?? 0;
            }

            // Step 6: Convert associative array to indexed array for response
            $finalOutput = array_values($averagesByTeam);

            return ApiResponse::success($finalOutput, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function reportsOutput2()
    {
        try {
            // Step 1: Fetch distinct ticket numbers
            $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth");

            $ListOfTicket = array_map(fn($item) => $item->ticket_number, $fr_teamTicket);

            $ListOfTimeDifferences = [];

            // Step 2: Compute time differences for each team and ticket
            foreach ($ListOfTicket as $ticket_number) {
                $fr_teamID = DB::select("SELECT DISTINCT(fth.team_id), t.team_name, d.division_name 
                    FROM helpdesk.ticket_fr_time_team_histories fth 
                    JOIN helpdesk.teams t ON fth.team_id = t.id
                    JOIN helpdesk.divisions d ON d.id = t.division_id
                    WHERE fth.ticket_number = ?
                ", [$ticket_number]);

                foreach ($fr_teamID as $team) {
                    $team_id = $team->team_id;
                    $team_name = $team->team_name;
                    $division_name = $team->division_name;

                    $fr_statusRecords = DB::select("SELECT fth.fr_response_status, fth.created_at 
                        FROM helpdesk.ticket_fr_time_team_histories fth
                        WHERE fth.ticket_number = ? AND fth.team_id = ? 
                        AND fth.fr_response_status IN (0, 1, 2) 
                        ORDER BY fth.created_at ASC
                    ", [$ticket_number, $team_id]);

                    $timestamps = [
                        'status_0' => null,
                        'status_1_or_2' => null
                    ];

                    foreach ($fr_statusRecords as $record) {
                        if ($record->fr_response_status == 0) {
                            $timestamps['status_0'] = $record->created_at;
                        } elseif (in_array($record->fr_response_status, [1, 2])) {
                            $timestamps['status_1_or_2'] = $record->created_at;
                        }
                    }

                    if ($timestamps['status_0'] && $timestamps['status_1_or_2']) {
                        $startTime = new DateTime($timestamps['status_0']);
                        $endTime = new DateTime($timestamps['status_1_or_2']);
                        $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

                        $ListOfTimeDifferences[] = [
                            'team_id' => $team_id,
                            'team_name' => $team_name,
                            'division_name' => $division_name,
                            'time_difference_in_seconds' => $timeDifferenceInSeconds
                        ];
                    }
                }
            }

            // Step 3: Summing time and calculating the average per team
            $timeSumsByTeam = [];
            foreach ($ListOfTimeDifferences as $entry) {
                $team_id = $entry['team_id'];
                $team_name = $entry['team_name'];
                $division_name = $entry['division_name'];
                $time_difference = $entry['time_difference_in_seconds'];

                if (!isset($timeSumsByTeam[$team_id])) {
                    $timeSumsByTeam[$team_id] = [
                        'team_name' => $team_name,
                        'division_name' => $division_name,
                        'total_time_in_seconds' => 0,
                        'count' => 0
                    ];
                }

                $timeSumsByTeam[$team_id]['total_time_in_seconds'] += $time_difference;
                $timeSumsByTeam[$team_id]['count']++;
            }

            // Step 4: Calculate average times
            $averagesByTeam = [];
            foreach ($timeSumsByTeam as $team_id => $data) {
                $totalTime = $data['total_time_in_seconds'];
                $count = $data['count'];
                $averageTime = $totalTime / $count;

                $averagesByTeam[$team_id] = [
                    'team_id' => $team_id,
                    'team_name' => $data['team_name'],
                    'division_name' => $data['division_name'],
                    'total_time' => gmdate("H:i:s", $totalTime),
                    'average_time' => gmdate("H:i:s", $averageTime)
                ];
            }

            // Step 5: Add closed, open tickets, and company data
            $ticketCounts = DB::select("SELECT  
                COALESCE(c1.company_name, c2.company_name) AS company_name,
                t.id AS team_id,
                t.team_name,
                COUNT(DISTINCT CASE WHEN th1.status_id = 6 THEN th1.ticket_number END) AS ticket_closed_by_team,
                COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team
                FROM helpdesk.teams t
                LEFT JOIN helpdesk.ticket_histories th1 
                    ON th1.team_id = t.id
                LEFT JOIN helpdesk.companies c1 
                    ON c1.id = th1.business_entity_id
                LEFT JOIN helpdesk.tickets th2 
                    ON th2.team_id = t.id
                LEFT JOIN helpdesk.companies c2 
                    ON c2.id = th2.business_entity_id
                GROUP BY t.id, t.team_name, COALESCE(c1.company_name, c2.company_name)
                ORDER BY t.team_name
            ");

            foreach ($ticketCounts as $ticketData) {
                $team_id = $ticketData->team_id;

                $averagesByTeam[$team_id]['company_name'] = $ticketData->company_name ?? null;
                $averagesByTeam[$team_id]['ticket_closed_by_team'] = $ticketData->ticket_closed_by_team ?? 0;
                $averagesByTeam[$team_id]['ticket_open_by_team'] = $ticketData->ticket_open_by_team ?? 0;
            }

            // Step 6: Convert associative array to indexed array for response
            $finalOutput = array_values($averagesByTeam);

            return ApiResponse::success($finalOutput, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }


    public function statistics()
    {
        try {
            // Step 1: Fetch distinct FR ticket numbers
            $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth");

            $ListOfTicket = array_map(fn($item) => $item->ticket_number, $fr_teamTicket);

            $ListOfTimeDifferences = [];

            // Step 2: Compute time differences for each team and FR ticket
            foreach ($ListOfTicket as $ticket_number) {
                $fr_teamID = DB::select("SELECT DISTINCT(fth.team_id), t.team_name, d.division_name 
                    FROM helpdesk.ticket_fr_time_team_histories fth 
                    JOIN helpdesk.teams t ON fth.team_id = t.id
                    JOIN helpdesk.divisions d ON d.id = t.division_id
                    WHERE fth.ticket_number = ?
                ", [$ticket_number]);

                foreach ($fr_teamID as $team) {
                    $team_id = $team->team_id;
                    $team_name = $team->team_name;
                    $division_name = $team->division_name;

                    $fr_statusRecords = DB::select("SELECT fth.fr_response_status, fth.created_at 
                        FROM helpdesk.ticket_fr_time_team_histories fth
                        WHERE fth.ticket_number = ? AND fth.team_id = ? 
                        AND fth.fr_response_status IN (0, 1, 2) 
                        ORDER BY fth.created_at ASC
                    ", [$ticket_number, $team_id]);

                    $timestamps = [
                        'status_0' => null,
                        'status_1_or_2' => null
                    ];

                    foreach ($fr_statusRecords as $record) {
                        if ($record->fr_response_status == 0) {
                            $timestamps['status_0'] = $record->created_at;
                        } elseif (in_array($record->fr_response_status, [1, 2])) {
                            $timestamps['status_1_or_2'] = $record->created_at;
                        }
                    }

                    if ($timestamps['status_0'] && $timestamps['status_1_or_2']) {
                        $startTime = new DateTime($timestamps['status_0']);
                        $endTime = new DateTime($timestamps['status_1_or_2']);
                        $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

                        $ListOfTimeDifferences[] = [
                            'team_id' => $team_id,
                            'team_name' => $team_name,
                            'division_name' => $division_name,
                            'time_difference_in_seconds' => $timeDifferenceInSeconds
                        ];
                    }
                }
            }

            // Step 3: Summing time and calculating the average per FR team
            $timeSumsByTeam = [];
            foreach ($ListOfTimeDifferences as $entry) {
                $team_id = $entry['team_id'];
                $team_name = $entry['team_name'];
                $division_name = $entry['division_name'];
                $time_difference = $entry['time_difference_in_seconds'];

                if (!isset($timeSumsByTeam[$team_id])) {
                    $timeSumsByTeam[$team_id] = [
                        'team_name' => $team_name,
                        'division_name' => $division_name,
                        'total_time_in_seconds' => 0,
                        'count' => 0
                    ];
                }

                $timeSumsByTeam[$team_id]['total_time_in_seconds'] += $time_difference;
                $timeSumsByTeam[$team_id]['count']++;
            }

            // Step 4: Calculate average times for FR teams
            $averagesByTeam = [];
            foreach ($timeSumsByTeam as $team_id => $data) {
                $totalTime = $data['total_time_in_seconds'];
                $count = $data['count'];
                $averageTime = $totalTime / $count;

                $averagesByTeam[$team_id] = [
                    'team_id' => $team_id,
                    'team_name' => $data['team_name'],
                    'division_name' => $data['division_name'],
                    'total_time' => gmdate("H:i:s", $totalTime),
                    'average_time' => gmdate("H:i:s", $averageTime)
                ];
            }

            // Step 5: Fetch distinct SRV ticket numbers
            $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth");

            $ListOfSrvTicket = array_map(fn($item) => $item->ticket_number, $srv_teamTicket);

            $ListOfSrvTimeDifferences = [];

            // Step 6: Compute time differences for each team and SRV ticket
            foreach ($ListOfSrvTicket as $ticket_number) {
                $srv_teamID = DB::select("SELECT DISTINCT(sth.team_id), t.team_name, d.division_name 
                    FROM helpdesk.ticket_srv_time_team_histories sth 
                    JOIN helpdesk.teams t ON sth.team_id = t.id
                    JOIN helpdesk.divisions d ON d.id = t.division_id
                    WHERE sth.ticket_number = ?
                ", [$ticket_number]);

                foreach ($srv_teamID as $team) {
                    $team_id = $team->team_id;
                    $team_name = $team->team_name;
                    $division_name = $team->division_name;

                    $srv_statusRecords = DB::select("SELECT sth.srv_time_status, sth.created_at 
                        FROM helpdesk.ticket_srv_time_team_histories sth
                        WHERE sth.ticket_number = ? AND sth.team_id = ? 
                        AND sth.srv_time_status IN (0, 1, 2) 
                        ORDER BY sth.created_at ASC
                    ", [$ticket_number, $team_id]);

                    $srvTimestamps = [
                        'status_0' => null,
                        'status_1_or_2' => null
                    ];

                    foreach ($srv_statusRecords as $record) {
                        if ($record->srv_time_status == 0) {
                            $srvTimestamps['status_0'] = $record->created_at;
                        } elseif (in_array($record->srv_time_status, [1, 2])) {
                            $srvTimestamps['status_1_or_2'] = $record->created_at;
                        }
                    }

                    if ($srvTimestamps['status_0'] && $srvTimestamps['status_1_or_2']) {
                        $startTime = new DateTime($srvTimestamps['status_0']);
                        $endTime = new DateTime($srvTimestamps['status_1_or_2']);
                        $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

                        $ListOfSrvTimeDifferences[] = [
                            'team_id' => $team_id,
                            'team_name' => $team_name,
                            'division_name' => $division_name,
                            'time_difference_in_seconds' => $timeDifferenceInSeconds
                        ];
                    }
                }
            }

            // Step 7: Summing time and calculating the average per SRV team
            $timeSumsByTeamSrv = [];
            foreach ($ListOfSrvTimeDifferences as $entry) {
                $team_id = $entry['team_id'];
                $team_name = $entry['team_name'];
                $division_name = $entry['division_name'];
                $time_difference = $entry['time_difference_in_seconds'];

                if (!isset($timeSumsByTeamSrv[$team_id])) {
                    $timeSumsByTeamSrv[$team_id] = [
                        'team_name' => $team_name,
                        'division_name' => $division_name,
                        'total_time_in_seconds' => 0,
                        'count' => 0
                    ];
                }

                $timeSumsByTeamSrv[$team_id]['total_time_in_seconds'] += $time_difference;
                $timeSumsByTeamSrv[$team_id]['count']++;
            }

            // Step 8: Calculate average times for SRV teams
            $averagesBySrvTeam = [];
            foreach ($timeSumsByTeamSrv as $team_id => $data) {
                $totalTime = $data['total_time_in_seconds'];
                $count = $data['count'];
                $averageSrvTime = $totalTime / $count;

                $averagesBySrvTeam[$team_id] = [
                    'team_id' => $team_id,
                    'team_name' => $data['team_name'],
                    'division_name' => $data['division_name'],
                    'total_time' => gmdate("H:i:s", $totalTime),
                    'average_srv_time' => gmdate("H:i:s", $averageSrvTime)
                ];
            }

            // Step 9: Add closed, open tickets, and company data
            $ticketCounts = DB::select("SELECT  
                COALESCE(c1.company_name, c2.company_name) AS company_name,
                t.id AS team_id,
                t.team_name,
                COUNT(DISTINCT CASE WHEN th1.status_id = 6 THEN th1.ticket_number END) AS ticket_closed_by_team,
                COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team
                FROM helpdesk.teams t
                LEFT JOIN helpdesk.ticket_histories th1 ON th1.team_id = t.id
                LEFT JOIN helpdesk.companies c1 ON c1.id = th1.business_entity_id
                LEFT JOIN helpdesk.tickets th2 ON th2.team_id = t.id
                LEFT JOIN helpdesk.companies c2 ON c2.id = th2.business_entity_id
                GROUP BY t.id, t.team_name, COALESCE(c1.company_name, c2.company_name)
                ORDER BY t.team_name
                
            ");

            // $mergedData = array_merge($averagesByTeam, $averagesBySrvTeam);

            // Step 10: Merge the averages and ticket counts into the final response
            foreach ($averagesByTeam as $team_id => $teamData) {
                if (isset($averagesBySrvTeam[$team_id])) {
                    $averagesByTeam[$team_id]['average_srv_time'] = $averagesBySrvTeam[$team_id]['average_srv_time'];
                }

                // Add ticket closed, open counts, and company name
                foreach ($ticketCounts as $ticketData) {
                    if ($ticketData->team_id == $team_id) {
                        $averagesByTeam[$team_id]['company_name'] = $ticketData->company_name ?? null;
                        $averagesByTeam[$team_id]['ticket_closed_by_team'] = $ticketData->ticket_closed_by_team ?? 0;
                        $averagesByTeam[$team_id]['ticket_open_by_team'] = $ticketData->ticket_open_by_team ?? 0;
                    }
                }
            }

            // Step 11: Final output
            $finalOutput = array_values($averagesByTeam);
            return ApiResponse::success($finalOutput, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }

    // public function statisticsWithGraph(Request $request)
    // {
    //     try {

    //         // return $request->all();
    //         $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //         $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

    //         // $fromDate = $request->fromDate
    //         //     ? Carbon::parse($request->fromDate)->startOfDay()->toDateTimeString()  // Converts to 'Y-m-d 00:00:00'
    //         //     : null;

    //         // $toDate = $request->toDate
    //         //     ? Carbon::parse($request->toDate)->endOfDay()->toDateTimeString()  // Converts to 'Y-m-d 23:59:59'
    //         //     : null;
    //         // Step 1: Fetch distinct FR ticket numbers

    //         if ((empty($fromDate)) && (empty($toDate))) {

    //             $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth");

    //             $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth");
    //         } else {
    //             $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth
    //             WHERE DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'");

    //             $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth WHERE DATE(sth.created_at) BETWEEN '$fromDate' AND '$toDate'");
    //         }

    //         $ListOfTicket = array_map(fn($item) => $item->ticket_number, $fr_teamTicket);

    //         // dd($ListOfTicket);

    //         $ListOfTimeDifferences = [];

    //         // Step 2: Compute time differences for each team and FR ticket
    //         foreach ($ListOfTicket as $ticket_number) {
    //             $fr_teamID = DB::select("SELECT DISTINCT(fth.team_id), t.team_name, d.division_name 
    //                 FROM helpdesk.ticket_fr_time_team_histories fth 
    //                 JOIN helpdesk.teams t ON fth.team_id = t.id
    //                 JOIN helpdesk.divisions d ON d.id = t.division_id
    //                 WHERE fth.ticket_number = ?
    //             ", [$ticket_number]);

    //             foreach ($fr_teamID as $team) {
    //                 $team_id = $team->team_id;
    //                 $team_name = $team->team_name;
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
    //                         'team_id' => $team_id,
    //                         'team_name' => $team_name,
    //                         'division_name' => $division_name,
    //                         'time_difference_in_seconds' => $timeDifferenceInSeconds
    //                     ];
    //                 }
    //             }
    //         }

    //         // return $ticket_number;

    //         // Step 3: Summing time and calculating the average per FR team
    //         $timeSumsByTeam = [];
    //         foreach ($ListOfTimeDifferences as $entry) {
    //             $team_id = $entry['team_id'];
    //             $team_name = $entry['team_name'];
    //             $division_name = $entry['division_name'];
    //             $time_difference = $entry['time_difference_in_seconds'];

    //             if (!isset($timeSumsByTeam[$team_id])) {
    //                 $timeSumsByTeam[$team_id] = [
    //                     'team_name' => $team_name,
    //                     'division_name' => $division_name,
    //                     'total_time_in_seconds' => 0,
    //                     'count' => 0
    //                 ];
    //             }

    //             $timeSumsByTeam[$team_id]['total_time_in_seconds'] += $time_difference;
    //             $timeSumsByTeam[$team_id]['count']++;
    //         }

    //         // Step 4: Calculate average times for FR teams
    //         $averagesByTeam = [];
    //         foreach ($timeSumsByTeam as $team_id => $data) {
    //             $totalTime = $data['total_time_in_seconds'];
    //             $count = $data['count'];
    //             $averageTime = $totalTime / $count;

    //             $averagesByTeam[$team_id] = [
    //                 'team_id' => $team_id,
    //                 'team_name' => $data['team_name'],
    //                 'division_name' => $data['division_name'],
    //                 'total_time' => gmdate("H:i:s", $totalTime),
    //                 'average_time' => gmdate("H:i:s", $averageTime)
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
    //                 $team_name = $team->team_name;
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
    //                         'team_id' => $team_id,
    //                         'team_name' => $team_name,
    //                         'division_name' => $division_name,
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
    //             $division_name = $entry['division_name'];
    //             $time_difference = $entry['time_difference_in_seconds'];

    //             if (!isset($timeSumsByTeamSrv[$team_id])) {
    //                 $timeSumsByTeamSrv[$team_id] = [
    //                     'team_name' => $team_name,
    //                     'division_name' => $division_name,
    //                     'total_time_in_seconds' => 0,
    //                     'count' => 0
    //                 ];
    //             }

    //             $timeSumsByTeamSrv[$team_id]['total_time_in_seconds'] += $time_difference;
    //             $timeSumsByTeamSrv[$team_id]['count']++;

    //             // return $timeSumsByTeamSrv;
    //         }

    //         // return $timeSumsByTeamSrv;

    //         // Step 8: Calculate average times for SRV teams
    //         $averagesBySrvTeam = [];
    //         foreach ($timeSumsByTeamSrv as $team_id => $data) {
    //             $totalTime = $data['total_time_in_seconds'];
    //             $count = $data['count'];
    //             // return [$count,$totalTime];
    //             $averageSrvTime = $totalTime / $count;

    //             // return [$averageSrvTime,$team_id];

    //             $averagesBySrvTeam[$team_id] = [
    //                 'team_id' => $team_id,
    //                 'team_name' => $data['team_name'],
    //                 'division_name' => $data['division_name'],
    //                 'total_time' => gmdate("H:i:s", $totalTime),
    //                 'average_srv_time' => gmdate("H:i:s", $averageSrvTime)
    //             ];
    //         }

    //         // return $averageSrvTime;

    //         // Step 9: Add closed, open tickets, and company data
    //         if ((empty($fromDate)) && (empty($toDate))) {

    //             // return 'hi';

    //             $ticketCounts = DB::select("SELECT  
    //                             COALESCE(c1.company_name, c2.company_name) AS company_name,
    //                             t.id AS team_id,
    //                             t.team_name,
    //                             COUNT(DISTINCT CASE WHEN th1.status_id = 6 THEN th1.ticket_number END) AS ticket_closed_by_team,
    //                             COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                                             COUNT(DISTINCT tsh.ticket_number) AS srv_violated,
    //                                             COUNT(DISTINCT fth.ticket_number) AS fr_violated
    //                             FROM helpdesk.teams t
    //                             LEFT JOIN helpdesk.ticket_histories th1 ON th1.team_id = t.id
    //                             LEFT JOIN helpdesk.companies c1 ON c1.id = th1.business_entity_id
    //                             LEFT JOIN helpdesk.tickets th2 ON th2.team_id = t.id
    //                             LEFT JOIN helpdesk.companies c2 ON c2.id = th2.business_entity_id
    //                                             LEFT JOIN helpdesk.ticket_srv_time_team_histories tsh ON th1.ticket_number = tsh.ticket_number
    //                                             AND tsh.srv_time_status = 2
    //                                             LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON th1.ticket_number = fth.ticket_number
    //                                             AND fth.fr_response_id = 2
    //                             GROUP BY t.id, t.team_name, COALESCE(c1.company_name, c2.company_name)
    //                             ORDER BY t.team_name

    //                         ");
    //         } else {

    //             // return 'hi2';

    //             $ticketCounts = DB::select("SELECT  
    //                             COALESCE(c1.company_name, c2.company_name) AS company_name,
    //                             t.id AS team_id,
    //                             t.team_name,
    //                             COUNT(DISTINCT CASE WHEN th1.status_id = 6 THEN th1.ticket_number END) AS ticket_closed_by_team,
    //                             COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                                             COUNT(DISTINCT tsh.ticket_number) AS srv_violated,
    //                                             COUNT(DISTINCT fth.ticket_number) AS fr_violated
    //                             FROM helpdesk.teams t
    //                             LEFT JOIN helpdesk.ticket_histories th1 ON th1.team_id = t.id
    //                             -- AND DATE(th1.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             LEFT JOIN helpdesk.companies c1 ON c1.id = th1.business_entity_id
    //                             LEFT JOIN helpdesk.tickets th2 ON th2.team_id = t.id
    //                             LEFT JOIN helpdesk.companies c2 ON c2.id = th2.business_entity_id
    //                                             LEFT JOIN helpdesk.ticket_srv_time_team_histories tsh ON th1.ticket_number = tsh.ticket_number
    //                                             AND tsh.srv_time_status = 2
    //                                             LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON th1.ticket_number = fth.ticket_number
    //                                             AND fth.fr_response_id = 2
    //                                             WHERE DATE(th1.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY t.id, t.team_name, COALESCE(c1.company_name, c2.company_name)
    //                             ORDER BY t.team_name

    //                         ");

    //             // return $ticketCounts;
    //         }

    //         // $mergedData = array_merge($averagesByTeam, $averagesBySrvTeam);

    //         // Step 10: Merge the averages and ticket counts into the final response
    //         foreach ($averagesByTeam as $team_id => $teamData) {
    //             if (isset($averagesBySrvTeam[$team_id])) {
    //                 $averagesByTeam[$team_id]['average_srv_time'] = $averagesBySrvTeam[$team_id]['average_srv_time'];

    //                 // return 'here';
    //             }

    //             // Add ticket closed, open counts, and company name
    //             foreach ($ticketCounts as $ticketData) {
    //                 if ($ticketData->team_id == $team_id) {
    //                     $averagesByTeam[$team_id]['company_name'] = $ticketData->company_name ?? null;
    //                     $averagesByTeam[$team_id]['ticket_closed_by_team'] = $ticketData->ticket_closed_by_team ?? 0;
    //                     $averagesByTeam[$team_id]['ticket_open_by_team'] = $ticketData->ticket_open_by_team ?? 0;
    //                     $averagesByTeam[$team_id]['fr_violated'] = $ticketData->fr_violated ?? 0;
    //                     $averagesByTeam[$team_id]['srv_violated'] = $ticketData->srv_violated ?? 0;
    //                 }
    //             }
    //         }

    //         // Step 11: Final output
    //         $finalOutput = array_values($averagesByTeam);

    //         // return $finalOutput;

    //         $sumClosedTickets = 0;
    //         $sumOpenTickets = 0;

    //         $totalTimes = [];
    //         $averageTimes = [];
    //         $averageSrvTimes = [];

    //         foreach ($finalOutput as $item) {
    //             // Sum ticket counts
    //             $sumClosedTickets += $item['ticket_closed_by_team'];
    //             $sumOpenTickets += $item['ticket_open_by_team'];

    //             // Convert time strings to seconds
    //             $totalTimes[] = strtotime($item['total_time']) - strtotime('TODAY');
    //             $averageTimes[] = strtotime($item['average_time']) - strtotime('TODAY');
    //             $averageSrvTimes[] = strtotime($item['average_srv_time']) - strtotime('TODAY');

    //         }

    //         // return $finalOutput;

    //         // Sum times
    //         $sumTotalTimeInSeconds = array_sum($totalTimes);
    //         $sumAverageTimeInSeconds = array_sum($averageTimes);
    //         $sumAverageSrvTimeInSeconds = array_sum($averageSrvTimes);

    //         // return [$sumTotalTimeInSeconds,$sumAverageTimeInSeconds,$sumAverageSrvTimeInSeconds];

    //         // return $finalOutput;
    //         // Calculate averages (divide total by the number of entries)
    //         // $overallAverageTotalTime = $sumTotalTimeInSeconds / count($finalOutput);
    //         // $overallAverageSrvTime = $sumAverageSrvTimeInSeconds / count($finalOutput);

    //         if (count($finalOutput) > 0) {
    //             $overallAverageTotalTime = $sumTotalTimeInSeconds / count($finalOutput);
    //             $overallAverageSrvTime = $sumAverageSrvTimeInSeconds / count($finalOutput);
    //         } else {
    //             $overallAverageTotalTime = 0; // Or handle as needed
    //             $overallAverageSrvTime = 0;  // Or handle as needed
    //         }

    //         // Format times back to "H:i:s"
    //         $formattedTotalTime = gmdate("H:i:s", $sumTotalTimeInSeconds);
    //         $formattedAverageTime = gmdate("H:i:s", $sumAverageTimeInSeconds);
    //         $formattedAverageSrvTime = gmdate("H:i:s", $overallAverageSrvTime);

    //         // Add sums and averages to the response
    //         $finalOutputSum = [
    //             'total_time' => $formattedTotalTime,
    //             'average_time' => $formattedAverageTime,
    //             'average_srv_time' => $formattedAverageSrvTime,
    //             'ticket_closed_by_team' => $sumClosedTickets,
    //             'ticket_open_by_team' => $sumOpenTickets,
    //         ];


    //         if ((empty($fromDate)) && (empty($toDate))) {
    //             $sla = DB::select("SELECT  
    //                     COUNT(DISTINCT CASE WHEN tsh.srv_time_status = 2 THEN tsh.ticket_number END) AS over_due,
    //                     COUNT(DISTINCT CASE WHEN tsh.srv_time_status = 1 THEN tsh.ticket_number END) AS sla_success
    //                 FROM 
    //                     helpdesk.ticket_srv_time_team_histories tsh
    //                 ");
    //         } else {
    //             $sla = DB::select("SELECT  
    //             COUNT(DISTINCT CASE WHEN tsh.srv_time_status = 2 THEN tsh.ticket_number END) AS over_due,
    //             COUNT(DISTINCT CASE WHEN tsh.srv_time_status = 1 THEN tsh.ticket_number END) AS sla_success
    //             FROM 
    //                 helpdesk.ticket_srv_time_team_histories tsh WHERE DATE(tsh.created_at) BETWEEN '$fromDate' AND '$toDate'
    //             ");
    //         }

    //         $sla = $sla[0];

    //         $slaFinal = [
    //             'over_due' => $sla->over_due,
    //             'sla_success' => $sla->sla_success,
    //         ];

    //         // $slaFr = DB::select("SELECT  
    //         //         COUNT(DISTINCT CASE WHEN fth.fr_response_status = 2 THEN fth.ticket_number END) AS over_due_fr,
    //         //         COUNT(DISTINCT CASE WHEN fth.fr_response_status = 1 THEN fth.ticket_number END) AS sla_success_fr
    //         //     FROM 
    //         //         helpdesk.ticket_fr_time_team_histories fth
    //         //     ");


    //         if ((empty($fromDate)) && (empty($toDate))) {
    //             $slaFr = DB::select("SELECT  
    //                             COUNT(DISTINCT CASE WHEN fth.fr_response_status = 2 THEN fth.ticket_number END) AS over_due_fr,
    //                             COUNT(DISTINCT CASE WHEN fth.fr_response_status = 1 THEN fth.ticket_number END) AS sla_success_fr
    //                         FROM 
    //                             helpdesk.ticket_fr_time_team_histories fth
    //                 ");
    //         } else {
    //             $slaFr = DB::select("SELECT  
    //                             COUNT(DISTINCT CASE WHEN fth.fr_response_status = 2 THEN fth.ticket_number END) AS over_due_fr,
    //                             COUNT(DISTINCT CASE WHEN fth.fr_response_status = 1 THEN fth.ticket_number END) AS sla_success_fr
    //                         FROM 
    //                             helpdesk.ticket_fr_time_team_histories fth WHERE DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'
    //             ");
    //         }

    //         $slaFr = $slaFr[0];

    //         $slaFrFinal = [
    //             'over_due_fr' => $slaFr->over_due_fr,
    //             'sla_success_fr' => $slaFr->sla_success_fr,
    //         ];


    //         if ((empty($fromDate)) && (empty($toDate))) {

    //             $ticketCountsNew = DB::select("SELECT  
    //                         COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
    //                         COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                         COUNT(DISTINCT tsh.ticket_number) AS srv_violated,
    //                         COUNT(DISTINCT fth.ticket_number) AS fr_violated
    //                         FROM helpdesk.tickets th2 
    //                         LEFT JOIN helpdesk.ticket_srv_time_team_histories tsh ON th2.ticket_number = tsh.ticket_number
    //                         AND tsh.srv_time_status = 2
    //                         LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON th2.ticket_number = fth.ticket_number
    //                         AND fth.fr_response_status = 2

    //                         ");
    //         } else {

    //             // return 'hi2';

    //                     $ticketCountsNew = DB::select("SELECT  
    //                             COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
    //                             COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
    //                             COUNT(DISTINCT tsh.ticket_number) AS srv_violated,
    //                             COUNT(DISTINCT fth.ticket_number) AS fr_violated
    //                             FROM helpdesk.tickets th2 
    //                             LEFT JOIN helpdesk.ticket_srv_time_team_histories tsh ON th2.ticket_number = tsh.ticket_number
    //                             AND tsh.srv_time_status = 2
    //                             LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON th2.ticket_number = fth.ticket_number
    //                             AND fth.fr_response_status = 2
    //                             WHERE DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'

    //                         ");

    //             // return $ticketCounts;
    //         }

    //         $ticketCountsNew = $ticketCountsNew[0];

    //         // Add sums and averages to the response
    //         $finalOutputSumNew = [
    //             'ticket_closed_by_team' => $ticketCountsNew->ticket_closed_by_team,
    //             'ticket_open_by_team' => $ticketCountsNew->ticket_open_by_team,
    //         ];

    //         $formattedOutputSum = [

    //             // ['label' => 'AVG First Response Time', 'value' => $finalOutputSum['average_time']],
    //             // ['label' => 'AVG Service Time', 'value' => $finalOutputSum['average_srv_time']],

    //             ['label' => 'Open', 'value' => $finalOutputSumNew['ticket_open_by_team']],
    //             ['label' => 'Closed', 'value' => $finalOutputSumNew['ticket_closed_by_team']],
    //             ['label' => 'First response Violated', 'value' => $slaFrFinal['over_due_fr']],
    //             ['label' => 'Service time Violated', 'value' => $slaFinal['over_due']],
    //             // ['label' => 'Fr Sla Success', 'value' => $slaFrFinal['sla_success_fr']],
    //             // ['label' => 'Sla Success', 'value' => $slaFinal['sla_success']],

    //         ];


    //         if ((empty($fromDate)) && (empty($toDate))) {

    //             $ticketCountsNewSeparate = DB::select("SELECT c.company_name company_name1,d.division_name division_name1, te.team_name team_name1,
    //                             SUM(CASE WHEN t.status_id != 6 THEN 1 ELSE 0 END) AS open_count,
    //                             SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count
    //                             FROM helpdesk.tickets t, helpdesk.teams te, helpdesk.divisions d, helpdesk.companies c
    //                             WHERE t.team_id = te.id
    //                             AND te.division_id = d.id 
    // 							AND t.business_entity_id = c.id
    //                             GROUP BY c.company_name,d.division_name, te.team_name");
    //         } else {

    //                     $ticketCountsNewSeparate = DB::select("SELECT c.company_name company_name1,d.division_name division_name1, te.team_name team_name1,
    //                             SUM(CASE WHEN t.status_id != 6 THEN 1 ELSE 0 END) AS open_count,
    //                             SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count
    //                             FROM helpdesk.tickets t, helpdesk.teams te, helpdesk.divisions d, helpdesk.companies c
    //                             WHERE t.team_id = te.id
    //                             AND te.division_id = d.id 
    // 							AND t.business_entity_id = c.id
    //                             AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
    //                             GROUP BY c.company_name,d.division_name, te.team_name");

    //         }



    //         $response = [
    //             'summary' => $formattedOutputSum,
    //             'details' => $finalOutput,
    //             'teamTicket' => $ticketCountsNewSeparate,
    //         ];

    //         return ApiResponse::success($response, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Failed", 500);
    //     }
    // }

    public function statisticsWithGraph(Request $request)
    {
        try {

            // return $request->all();
            $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
            $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";

            if ((empty($fromDate)) && (empty($toDate))) {

                $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth
                WHERE fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");

                $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth
                WHERE sth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)");
            } else {

                $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth WHERE DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'");

                $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth WHERE DATE(sth.created_at) BETWEEN '$fromDate' AND '$toDate'");
            }


            // Step 1: Fetch distinct FR ticket numbers
            // $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth");

            $ListOfTicket = array_map(fn($item) => $item->ticket_number, $fr_teamTicket);

            $ListOfTimeDifferences = [];

            // Step 2: Compute time differences for each team and FR ticket
            foreach ($ListOfTicket as $ticket_number) {
                $fr_teamID = DB::select("SELECT DISTINCT(fth.team_id), t.team_name
                        FROM helpdesk.ticket_fr_time_team_histories fth 
                        JOIN helpdesk.teams t ON fth.team_id = t.id
                        -- JOIN helpdesk.divisions d ON d.id = t.division_id
                        WHERE fth.ticket_number = ?
                    ", [$ticket_number]);

                foreach ($fr_teamID as $team) {
                    $team_id = $team->team_id;
                    $team_name = $team->team_name;
                    // $division_name = $team->division_name;

                    $fr_statusRecords = DB::select("SELECT fth.fr_response_status, fth.created_at 
                            FROM helpdesk.ticket_fr_time_team_histories fth
                            WHERE fth.ticket_number = ? AND fth.team_id = ? 
                            AND fth.fr_response_status IN (0, 1, 2) 
                            ORDER BY fth.created_at ASC
                        ", [$ticket_number, $team_id]);

                    $timestamps = [
                        'status_0' => null,
                        'status_1_or_2' => null
                    ];

                    foreach ($fr_statusRecords as $record) {
                        if ($record->fr_response_status == 0) {
                            $timestamps['status_0'] = $record->created_at;
                        } elseif (in_array($record->fr_response_status, [1, 2])) {
                            $timestamps['status_1_or_2'] = $record->created_at;
                        }
                    }

                    if ($timestamps['status_0'] && $timestamps['status_1_or_2']) {
                        $startTime = new DateTime($timestamps['status_0']);
                        $endTime = new DateTime($timestamps['status_1_or_2']);
                        $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

                        $ListOfTimeDifferences[] = [
                            'team_id' => $team_id,
                            'team_name' => $team_name,
                            // 'division_name' => $division_name,
                            'time_difference_in_seconds' => $timeDifferenceInSeconds
                        ];
                    }
                }
            }

            // Step 3: Summing time and calculating the average per FR team
            $timeSumsByTeam = [];
            foreach ($ListOfTimeDifferences as $entry) {
                $team_id = $entry['team_id'];
                $team_name = $entry['team_name'];
                // $division_name = $entry['division_name'];
                $time_difference = $entry['time_difference_in_seconds'];

                if (!isset($timeSumsByTeam[$team_id])) {
                    $timeSumsByTeam[$team_id] = [
                        'team_name' => $team_name,
                        // 'division_name' => $division_name,
                        'total_time_in_seconds' => 0,
                        'count' => 0
                    ];
                }

                $timeSumsByTeam[$team_id]['total_time_in_seconds'] += $time_difference;
                $timeSumsByTeam[$team_id]['count']++;
            }

            // Step 4: Calculate average times for FR teams
            $averagesByTeam = [];
            foreach ($timeSumsByTeam as $team_id => $data) {
                $totalTime = $data['total_time_in_seconds'];
                $count = $data['count'];
                $averageTime = $totalTime / $count;

                $averagesByTeam[$team_id] = [
                    'team_id' => $team_id,
                    // 'team_name' => $data['team_name'],
                    // 'division_name' => $data['division_name'],
                    'total_time' => gmdate("H:i:s", $totalTime),
                    'average_time' => gmdate("H:i:s", $averageTime)
                ];
            }

            // Step 5: Fetch distinct SRV ticket numbers
            // $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth");

            $ListOfSrvTicket = array_map(fn($item) => $item->ticket_number, $srv_teamTicket);

            $ListOfSrvTimeDifferences = [];

            // Step 6: Compute time differences for each team and SRV ticket
            foreach ($ListOfSrvTicket as $ticket_number) {
                $srv_teamID = DB::select("SELECT DISTINCT(sth.team_id), t.team_name 
                        FROM helpdesk.ticket_srv_time_team_histories sth 
                        JOIN helpdesk.teams t ON sth.team_id = t.id
                        -- JOIN helpdesk.divisions d ON d.id = t.division_id
                        WHERE sth.ticket_number = ?
                    ", [$ticket_number]);

                foreach ($srv_teamID as $team) {
                    $team_id = $team->team_id;
                    $team_name = $team->team_name;
                    // $division_name = $team->division_name;

                    $srv_statusRecords = DB::select("SELECT sth.srv_time_status, sth.created_at 
                            FROM helpdesk.ticket_srv_time_team_histories sth
                            WHERE sth.ticket_number = ? AND sth.team_id = ? 
                            AND sth.srv_time_status IN (0, 1, 2) 
                            ORDER BY sth.created_at ASC
                        ", [$ticket_number, $team_id]);

                    $srvTimestamps = [
                        'status_0' => null,
                        'status_1_or_2' => null
                    ];

                    foreach ($srv_statusRecords as $record) {
                        if ($record->srv_time_status == 0) {
                            $srvTimestamps['status_0'] = $record->created_at;
                        } elseif (in_array($record->srv_time_status, [1, 2])) {
                            $srvTimestamps['status_1_or_2'] = $record->created_at;
                        }
                    }

                    if ($srvTimestamps['status_0'] && $srvTimestamps['status_1_or_2']) {
                        $startTime = new DateTime($srvTimestamps['status_0']);
                        $endTime = new DateTime($srvTimestamps['status_1_or_2']);
                        $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

                        $ListOfSrvTimeDifferences[] = [
                            'team_id' => $team_id,
                            'team_name' => $team_name,
                            // 'division_name' => $division_name,
                            'time_difference_in_seconds' => $timeDifferenceInSeconds
                        ];
                    }
                }
            }

            // Step 7: Summing time and calculating the average per SRV team
            $timeSumsByTeamSrv = [];
            foreach ($ListOfSrvTimeDifferences as $entry) {
                $team_id = $entry['team_id'];
                $team_name = $entry['team_name'];
                // $division_name = $entry['division_name'];
                $time_difference = $entry['time_difference_in_seconds'];

                if (!isset($timeSumsByTeamSrv[$team_id])) {
                    $timeSumsByTeamSrv[$team_id] = [
                        'team_name' => $team_name,
                        // 'division_name' => $division_name,
                        'total_time_in_seconds' => 0,
                        'count' => 0
                    ];
                }

                $timeSumsByTeamSrv[$team_id]['total_time_in_seconds'] += $time_difference;
                $timeSumsByTeamSrv[$team_id]['count']++;
            }

            // Step 8: Calculate average times for SRV teams
            $averagesBySrvTeam = [];
            foreach ($timeSumsByTeamSrv as $team_id => $data) {
                $totalTime = $data['total_time_in_seconds'];
                $count = $data['count'];
                $averageSrvTime = $totalTime / $count;

                $averagesBySrvTeam[$team_id] = [
                    'team_id' => $team_id,
                    // 'team_name' => $data['team_name'],
                    // 'division_name' => $data['division_name'],
                    'total_time' => gmdate("H:i:s", $totalTime),
                    'average_srv_time' => gmdate("H:i:s", $averageSrvTime)
                ];
            }


            // Step 12: Fetch overdue counts for each team
            if ((empty($fromDate)) && (empty($toDate))) {
                $overDueCounts = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
                        FROM helpdesk.ticket_srv_time_team_histories tsh
                        JOIN helpdesk.teams t ON t.id = tsh.team_id
                        WHERE tsh.srv_time_status = 2
                        GROUP BY t.id, t.team_name
                    ");
            } else {
                $overDueCounts = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT tsh.ticket_number) AS over_due
                    FROM helpdesk.ticket_srv_time_team_histories tsh
                    JOIN helpdesk.teams t ON t.id = tsh.team_id
                    WHERE tsh.srv_time_status = 2 AND DATE(tsh.created_at) BETWEEN '$fromDate' AND '$toDate'
                    GROUP BY t.id, t.team_name
                ");
            }

            // Map the over_due counts by team_id for easy access
            $overDueMap = [];
            foreach ($overDueCounts as $overDueData) {
                $overDueMap[$overDueData->team_id] = $overDueData->over_due;
            }

            // Step 13: Merge the overdue counts into the final averages data
            foreach ($averagesByTeam as $team_id => $teamData) {
                if (isset($overDueMap[$team_id])) {
                    $averagesByTeam[$team_id]['over_due'] = $overDueMap[$team_id];
                } else {
                    $averagesByTeam[$team_id]['over_due'] = 0; // Default to 0 if no overdue count exists
                }
            }

            // Step 14: Final output
            $finalOutput = array_values($averagesByTeam);


            // Step 12: Fetch overduefr counts for each team
            // $overDueCountsFr = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT fth.ticket_number) AS over_due_fr
            //         FROM helpdesk.ticket_fr_time_team_histories fth
            //         JOIN helpdesk.teams t ON t.id = fth.team_id
            //         WHERE fth.fr_response_status = 2
            //         GROUP BY t.id, t.team_name
            //     ");

            if ((empty($fromDate)) && (empty($toDate))) {
                $overDueCountsFr = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT fth.ticket_number) AS over_due_fr
                    FROM helpdesk.ticket_fr_time_team_histories fth
                    JOIN helpdesk.teams t ON t.id = fth.team_id
                    WHERE fth.fr_response_status = 2 AND fth.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)
                    GROUP BY t.id, t.team_name
                    ");
            } else {
                $overDueCountsFr = DB::select("SELECT t.id AS team_id, t.team_name, COUNT(DISTINCT fth.ticket_number) AS over_due_fr
                    FROM helpdesk.ticket_fr_time_team_histories fth
                    JOIN helpdesk.teams t ON t.id = fth.team_id
                    WHERE fth.fr_response_status = 2 AND DATE(fth.created_at) BETWEEN '$fromDate'	 AND '$toDate' 
                    GROUP BY t.id, t.team_name
                ");
            }

            // Map the over_due_fr counts by team_id for easy access
            $overDueMap = [];
            foreach ($overDueCountsFr as $overDueData) {
                $overDueMap[$overDueData->team_id] = $overDueData->over_due_fr;
            }

            // Step 13: Merge the overdue counts into the final averages data
            foreach ($averagesByTeam as $team_id => $teamData) {
                if (isset($overDueMap[$team_id])) {
                    $averagesByTeam[$team_id]['over_due_fr'] = $overDueMap[$team_id];
                } else {
                    $averagesByTeam[$team_id]['over_due_fr'] = 0; // Default to 0 if no overdue count exists
                }
            }

            // Step 14: Final output
            $finalOutput = array_values($averagesByTeam);


            if ((empty($fromDate)) && (empty($toDate))) {

                $ticketCounts = DB::select("SELECT d.division_name,t.id AS team_id,t.team_name AS team_name,
                                    COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
                                    COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team
                                FROM helpdesk.teams t
                                LEFT JOIN helpdesk.tickets th2 
                                ON th2.team_id = t.id AND th2.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)

                                LEFT JOIN helpdesk.divisions d ON t.division_id = d.id
                                GROUP BY d.division_name, t.id, t.team_name
                                HAVING 
                                    COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) > 0
                                    OR 
                                    COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) > 0
                                ORDER BY t.team_name");
            } else {

                $ticketCounts = DB::select("SELECT d.division_name,t.id AS team_id,t.team_name AS team_name,
                        COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
                        COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team
                        FROM helpdesk.teams t
                        LEFT JOIN helpdesk.tickets th2 ON th2.team_id = t.id AND DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'
                                
                                LEFT JOIN helpdesk.divisions d ON t.division_id = d.id
                            GROUP BY d.division_name,t.id, t.team_name
                            
                            HAVING 
                        COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) > 0
                        OR 
                        COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) > 0
                        ORDER BY t.team_name
                                ");
            }



            $finalOutput = array_values($averagesByTeam);


            foreach ($ticketCounts as $ticketData) {
                $team_id = $ticketData->team_id;

                // Initialize department entry in $averagesByTeam if not already set
                if (!isset($averagesByTeam[$team_id])) {
                    $averagesByTeam[$team_id] = [
                        'team_name' => $team_id,
                        'total_time' => '00:00:00',
                        'average_time' => '00:00:00',
                        'over_due' => 0,
                        'over_due_fr' => 0,
                        'current_date' => date('Y-m-d'),
                        'last_month_date' => date('Y-m-d', strtotime('-1 month')),
                        'average_srv_time' => '00:00:00',
                        // 'ticket_closed_by_team' => 0,
                        // 'ticket_open_by_team' => 0,
                        'ticket_closed_by_team' => $ticketData->ticket_closed_by_team,
                        'ticket_open_by_team' => $ticketData->ticket_open_by_team,
                        // 'company_name' => $ticketData->company_name,
                        'division_name' => $ticketData->division_name,
                    ];
                }

                // Update ticket counts for the department
                $averagesByTeam[$team_id]['team_name'] = $ticketData->team_name ?? 0;
                $averagesByTeam[$team_id]['ticket_closed_by_team'] = $ticketData->ticket_closed_by_team ?? 0;
                $averagesByTeam[$team_id]['ticket_open_by_team'] = $ticketData->ticket_open_by_team ?? 0;
                $averagesByTeam[$team_id]['division_name'] = $ticketData->division_name ?? 0;

                // Update average_srv_time if it exists in $averagesBySrvTeam
                if (isset($averagesBySrvTeam[$team_id]['average_srv_time'])) {
                    $averagesByTeam[$team_id]['average_srv_time'] = $averagesBySrvTeam[$team_id]['average_srv_time'];
                }
            }


            // return 'here';



            // Filter out departments where both ticket counts are 0
            $filteredAverages = array_filter($averagesByTeam, function ($teamData) {
                return $teamData['ticket_closed_by_team'] > 0 || $teamData['ticket_open_by_team'] > 0;
            });

            // $finalOutput = array_values($averagesByTeam);
            $finalOutput = array_values($filteredAverages);

            // Add sums and averages to the response
            // $finalOutputSum = [
            //     'total_time' => $formattedTotalTime,
            //     'average_time' => $formattedAverageTime,
            //     'average_srv_time' => $formattedAverageSrvTime,
            //     'ticket_closed_by_team' => $sumClosedTickets,
            //     'ticket_open_by_team' => $sumOpenTickets,
            // ];


            if ((empty($fromDate)) && (empty($toDate))) {
                $sla = DB::select("SELECT  
                        COUNT(DISTINCT CASE WHEN tsh.srv_time_status = 2 THEN tsh.ticket_number END) AS over_due,
                        COUNT(DISTINCT CASE WHEN tsh.srv_time_status = 1 THEN tsh.ticket_number END) AS sla_success
                    FROM 
                        helpdesk.ticket_srv_time_team_histories tsh
                    ");
            } else {
                $sla = DB::select("SELECT  
                COUNT(DISTINCT CASE WHEN tsh.srv_time_status = 2 THEN tsh.ticket_number END) AS over_due,
                COUNT(DISTINCT CASE WHEN tsh.srv_time_status = 1 THEN tsh.ticket_number END) AS sla_success
                FROM 
                    helpdesk.ticket_srv_time_team_histories tsh WHERE DATE(tsh.created_at) BETWEEN '$fromDate' AND '$toDate'
                ");
            }

            $sla = $sla[0];

            $slaFinal = [
                'over_due' => $sla->over_due,
                'sla_success' => $sla->sla_success,
            ];

            if ((empty($fromDate)) && (empty($toDate))) {
                $slaFr = DB::select("SELECT  
                                COUNT(DISTINCT CASE WHEN fth.fr_response_status = 2 THEN fth.ticket_number END) AS over_due_fr,
                                COUNT(DISTINCT CASE WHEN fth.fr_response_status = 1 THEN fth.ticket_number END) AS sla_success_fr
                            FROM 
                                helpdesk.ticket_fr_time_team_histories fth
                    ");
            } else {
                $slaFr = DB::select("SELECT  
                                COUNT(DISTINCT CASE WHEN fth.fr_response_status = 2 THEN fth.ticket_number END) AS over_due_fr,
                                COUNT(DISTINCT CASE WHEN fth.fr_response_status = 1 THEN fth.ticket_number END) AS sla_success_fr
                            FROM 
                                helpdesk.ticket_fr_time_team_histories fth WHERE DATE(fth.created_at) BETWEEN '$fromDate' AND '$toDate'
                ");
            }

            $slaFr = $slaFr[0];

            $slaFrFinal = [
                'over_due_fr' => $slaFr->over_due_fr,
                'sla_success_fr' => $slaFr->sla_success_fr,
            ];


            if ((empty($fromDate)) && (empty($toDate))) {

                $ticketCountsNew = DB::select("SELECT  
                            COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
                            COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
                            COUNT(DISTINCT tsh.ticket_number) AS srv_violated,
                            COUNT(DISTINCT fth.ticket_number) AS fr_violated
                            FROM helpdesk.tickets th2 
                            LEFT JOIN helpdesk.ticket_srv_time_team_histories tsh ON th2.ticket_number = tsh.ticket_number
                            AND tsh.srv_time_status = 2
                            LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON th2.ticket_number = fth.ticket_number
                            AND fth.fr_response_status = 2
                                
                            ");
            } else {

                // return 'hi2';

                $ticketCountsNew = DB::select("SELECT  
                                COUNT(DISTINCT CASE WHEN th2.status_id = 6 THEN th2.ticket_number END) AS ticket_closed_by_team,
                                COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team,
                                COUNT(DISTINCT tsh.ticket_number) AS srv_violated,
                                COUNT(DISTINCT fth.ticket_number) AS fr_violated
                                FROM helpdesk.tickets th2 
                                LEFT JOIN helpdesk.ticket_srv_time_team_histories tsh ON th2.ticket_number = tsh.ticket_number
                                AND tsh.srv_time_status = 2
                                LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON th2.ticket_number = fth.ticket_number
                                AND fth.fr_response_status = 2
                                WHERE DATE(th2.created_at) BETWEEN '$fromDate' AND '$toDate'
                                
                            ");

                // return $ticketCounts;
            }

            $ticketCountsNew = $ticketCountsNew[0];

            // Add sums and averages to the response
            $finalOutputSumNew = [
                'ticket_closed_by_team' => $ticketCountsNew->ticket_closed_by_team,
                'ticket_open_by_team' => $ticketCountsNew->ticket_open_by_team,
            ];

            $formattedOutputSum = [

                // ['label' => 'AVG First Response Time', 'value' => $finalOutputSum['average_time']],
                // ['label' => 'AVG Service Time', 'value' => $finalOutputSum['average_srv_time']],

                ['label' => 'Open', 'value' => $finalOutputSumNew['ticket_open_by_team']],
                ['label' => 'Closed', 'value' => $finalOutputSumNew['ticket_closed_by_team']],
                ['label' => 'First response Violated', 'value' => $slaFrFinal['over_due_fr']],
                ['label' => 'Service time Violated', 'value' => $slaFinal['over_due']],
                // ['label' => 'Fr Sla Success', 'value' => $slaFrFinal['sla_success_fr']],
                // ['label' => 'Sla Success', 'value' => $slaFinal['sla_success']],

            ];


            if ((empty($fromDate)) && (empty($toDate))) {

                $ticketCountsNewSeparate = DB::select("SELECT c.company_name company_name1,d.division_name division_name1, te.team_name team_name1,
                                SUM(CASE WHEN t.status_id != 6 THEN 1 ELSE 0 END) AS open_count,
                                SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count
                                FROM helpdesk.tickets t, helpdesk.teams te, helpdesk.divisions d, helpdesk.companies c
                                WHERE t.team_id = te.id
                                AND te.division_id = d.id 
								AND t.business_entity_id = c.id
                                GROUP BY c.company_name,d.division_name, te.team_name");
            } else {

                $ticketCountsNewSeparate = DB::select("SELECT c.company_name company_name1,d.division_name division_name1, te.team_name team_name1,
                                SUM(CASE WHEN t.status_id != 6 THEN 1 ELSE 0 END) AS open_count,
                                SUM(CASE WHEN t.status_id = 6 THEN 1 ELSE 0 END) AS close_count
                                FROM helpdesk.tickets t, helpdesk.teams te, helpdesk.divisions d, helpdesk.companies c
                                WHERE t.team_id = te.id
                                AND te.division_id = d.id 
								AND t.business_entity_id = c.id
                                AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                GROUP BY c.company_name,d.division_name, te.team_name");
            }



            $response = [
                'summary' => $formattedOutputSum,
                'details' => $finalOutput,
                'teamTicket' => $ticketCountsNewSeparate,
            ];

            return ApiResponse::success($response, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }




    public function summaryOld()
    {
        try {

            $companyId = null;

            if (is_null($companyId)) {

                $results = DB::select("SELECT 
                        closed_and_open_tickets.closed_tickets,
                        closed_and_open_tickets.open_tickets,
                        srv_ticket_counts.over_due
                    FROM 
                        (SELECT 
                            COUNT(DISTINCT CASE WHEN th1.status_id = 6 THEN th1.ticket_number END) AS closed_tickets,
                            COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets
                        FROM helpdesk.ticket_histories th1, helpdesk.tickets th2) AS closed_and_open_tickets
                    CROSS JOIN
                        (SELECT 
                            COUNT(DISTINCT tsh.ticket_number) AS over_due
                        FROM helpdesk.ticket_srv_time_team_histories tsh 
                        WHERE tsh.srv_time_status = 2) AS srv_ticket_counts
                ");
                return ApiResponse::success($results, "Success - All Companies", 200);
            } else {

                $results = DB::select("SELECT 
                        COUNT(DISTINCT CASE WHEN th1.status_id = 6 THEN th1.ticket_number END) AS closed_tickets,
                        COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets,
                        COALESCE(srv_ticket_counts.over_due, 0) AS over_due
                    FROM 
                        helpdesk.companies c
                    LEFT JOIN helpdesk.ticket_histories th1 
                        ON c.id = th1.business_entity_id
                    LEFT JOIN helpdesk.tickets th2 
                        ON c.id = th2.business_entity_id
                    LEFT JOIN (
                        SELECT 
                            th.business_entity_id, 
                            COUNT(DISTINCT tsh.ticket_number) AS over_due
                        FROM helpdesk.ticket_srv_time_team_histories tsh
                        JOIN helpdesk.ticket_histories th 
                            ON th.ticket_number = tsh.ticket_number
                        WHERE tsh.srv_time_status = 2
                        GROUP BY th.business_entity_id
                    ) AS srv_ticket_counts 
                        ON c.id = srv_ticket_counts.business_entity_id
                    WHERE (? IS NULL OR c.id = ?)
                    GROUP BY 
                        c.company_name
                ", [$companyId, $companyId]);
                return ApiResponse::success($results, "Success - Specific Company", 200);
            }
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }


    public function getReportsOutput()
    {
        try {
            // Call the stored procedure
            $results = DB::select("CALL GetTicketReports()");

            // Return the success response with the results
            return ApiResponse::success($results, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }



    public function summaryTest()
    {
        try {

            $companyId = 2;

            if (is_null($companyId)) {

                $results = DB::select("SELECT 
                        closed_and_open_tickets.closed_tickets,
                        closed_and_open_tickets.open_tickets,
                        srv_ticket_counts.over_due
                    FROM 
                        (SELECT 
                            COUNT(DISTINCT CASE WHEN th1.status_id = 6 THEN th1.ticket_number END) AS closed_tickets,
                            COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets
                        FROM helpdesk.ticket_histories th1, helpdesk.tickets th2) AS closed_and_open_tickets
                    CROSS JOIN
                        (SELECT 
                            COUNT(DISTINCT tsh.ticket_number) AS over_due
                        FROM helpdesk.ticket_srv_time_team_histories tsh 
                        WHERE tsh.srv_time_status = 2) AS srv_ticket_counts
                ");
                return ApiResponse::success($results, "Success - All Companies", 200);
            } else {

                $results = DB::select("SELECT 
                        COUNT(DISTINCT CASE WHEN th1.status_id = 6 THEN th1.ticket_number END) AS closed_tickets,
                        COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS open_tickets,
                        COALESCE(srv_ticket_counts.over_due, 0) AS over_due
                    FROM 
                        helpdesk.companies c
                    LEFT JOIN helpdesk.ticket_histories th1 
                        ON c.id = th1.business_entity_id
                    LEFT JOIN helpdesk.tickets th2 
                        ON c.id = th2.business_entity_id
                    LEFT JOIN (
                        SELECT 
                            th.business_entity_id, 
                            COUNT(DISTINCT tsh.ticket_number) AS over_due
                        FROM helpdesk.ticket_srv_time_team_histories tsh
                        JOIN helpdesk.ticket_histories th 
                            ON th.ticket_number = tsh.ticket_number
                        WHERE tsh.srv_time_status = 2
                        GROUP BY th.business_entity_id
                    ) AS srv_ticket_counts 
                        ON c.id = srv_ticket_counts.business_entity_id
                    WHERE (? IS NULL OR c.id = ?)
                    GROUP BY 
                        c.company_name
                ", [$companyId, $companyId]);
                return ApiResponse::success($results, "Success - Specific Company", 200);
            }
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }

    public function statisticsWithGraphTest()
    {
        try {
            // Step 1: Fetch distinct FR ticket numbers
            $fr_teamTicket = DB::select("SELECT DISTINCT(fth.ticket_number) ticket_number FROM helpdesk.ticket_fr_time_team_histories fth");

            $ListOfTicket = array_map(fn($item) => $item->ticket_number, $fr_teamTicket);

            $ListOfTimeDifferences = [];

            // Step 2: Compute time differences for each team and FR ticket
            foreach ($ListOfTicket as $ticket_number) {
                $fr_teamID = DB::select("SELECT DISTINCT(fth.team_id), t.team_name, d.division_name 
                    FROM helpdesk.ticket_fr_time_team_histories fth 
                    JOIN helpdesk.teams t ON fth.team_id = t.id
                    JOIN helpdesk.divisions d ON d.id = t.division_id
                    WHERE fth.ticket_number = ?
                ", [$ticket_number]);

                foreach ($fr_teamID as $team) {
                    $team_id = $team->team_id;
                    $team_name = $team->team_name;
                    $division_name = $team->division_name;

                    $fr_statusRecords = DB::select("SELECT fth.fr_response_status, fth.created_at 
                        FROM helpdesk.ticket_fr_time_team_histories fth
                        WHERE fth.ticket_number = ? AND fth.team_id = ? 
                        AND fth.fr_response_status IN (0, 1, 2) 
                        ORDER BY fth.created_at ASC
                    ", [$ticket_number, $team_id]);

                    $timestamps = [
                        'status_0' => null,
                        'status_1_or_2' => null
                    ];

                    foreach ($fr_statusRecords as $record) {
                        if ($record->fr_response_status == 0) {
                            $timestamps['status_0'] = $record->created_at;
                        } elseif (in_array($record->fr_response_status, [1, 2])) {
                            $timestamps['status_1_or_2'] = $record->created_at;
                        }
                    }

                    if ($timestamps['status_0'] && $timestamps['status_1_or_2']) {
                        $startTime = new DateTime($timestamps['status_0']);
                        $endTime = new DateTime($timestamps['status_1_or_2']);
                        $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

                        $ListOfTimeDifferences[] = [
                            'team_id' => $team_id,
                            'team_name' => $team_name,
                            'division_name' => $division_name,
                            'time_difference_in_seconds' => $timeDifferenceInSeconds
                        ];
                    }
                }
            }

            // Step 3: Summing time and calculating the average per FR team
            $timeSumsByTeam = [];
            foreach ($ListOfTimeDifferences as $entry) {
                $team_id = $entry['team_id'];
                $team_name = $entry['team_name'];
                $division_name = $entry['division_name'];
                $time_difference = $entry['time_difference_in_seconds'];

                if (!isset($timeSumsByTeam[$team_id])) {
                    $timeSumsByTeam[$team_id] = [
                        'team_name' => $team_name,
                        'division_name' => $division_name,
                        'total_time_in_seconds' => 0,
                        'count' => 0
                    ];
                }

                $timeSumsByTeam[$team_id]['total_time_in_seconds'] += $time_difference;
                $timeSumsByTeam[$team_id]['count']++;
            }

            // Step 4: Calculate average times for FR teams
            $averagesByTeam = [];
            foreach ($timeSumsByTeam as $team_id => $data) {
                $totalTime = $data['total_time_in_seconds'];
                $count = $data['count'];
                $averageTime = $totalTime / $count;

                $averagesByTeam[$team_id] = [
                    'team_id' => $team_id,
                    'team_name' => $data['team_name'],
                    'division_name' => $data['division_name'],
                    'total_time' => gmdate("H:i:s", $totalTime),
                    'average_time' => gmdate("H:i:s", $averageTime)
                ];
            }

            // Step 5: Fetch distinct SRV ticket numbers
            $srv_teamTicket = DB::select("SELECT DISTINCT(sth.ticket_number) ticket_number FROM helpdesk.ticket_srv_time_team_histories sth");

            $ListOfSrvTicket = array_map(fn($item) => $item->ticket_number, $srv_teamTicket);

            $ListOfSrvTimeDifferences = [];

            // Step 6: Compute time differences for each team and SRV ticket
            foreach ($ListOfSrvTicket as $ticket_number) {
                $srv_teamID = DB::select("SELECT DISTINCT(sth.team_id), t.team_name, d.division_name 
                    FROM helpdesk.ticket_srv_time_team_histories sth 
                    JOIN helpdesk.teams t ON sth.team_id = t.id
                    JOIN helpdesk.divisions d ON d.id = t.division_id
                    WHERE sth.ticket_number = ?
                ", [$ticket_number]);

                foreach ($srv_teamID as $team) {
                    $team_id = $team->team_id;
                    $team_name = $team->team_name;
                    $division_name = $team->division_name;

                    $srv_statusRecords = DB::select("SELECT sth.srv_time_status, sth.created_at 
                        FROM helpdesk.ticket_srv_time_team_histories sth
                        WHERE sth.ticket_number = ? AND sth.team_id = ? 
                        AND sth.srv_time_status IN (0, 1, 2) 
                        ORDER BY sth.created_at ASC
                    ", [$ticket_number, $team_id]);

                    $srvTimestamps = [
                        'status_0' => null,
                        'status_1_or_2' => null
                    ];

                    foreach ($srv_statusRecords as $record) {
                        if ($record->srv_time_status == 0) {
                            $srvTimestamps['status_0'] = $record->created_at;
                        } elseif (in_array($record->srv_time_status, [1, 2])) {
                            $srvTimestamps['status_1_or_2'] = $record->created_at;
                        }
                    }

                    if ($srvTimestamps['status_0'] && $srvTimestamps['status_1_or_2']) {
                        $startTime = new DateTime($srvTimestamps['status_0']);
                        $endTime = new DateTime($srvTimestamps['status_1_or_2']);
                        $timeDifferenceInSeconds = abs($startTime->getTimestamp() - $endTime->getTimestamp());

                        $ListOfSrvTimeDifferences[] = [
                            'team_id' => $team_id,
                            'team_name' => $team_name,
                            'division_name' => $division_name,
                            'time_difference_in_seconds' => $timeDifferenceInSeconds
                        ];
                    }
                }
            }

            // Step 7: Summing time and calculating the average per SRV team
            $timeSumsByTeamSrv = [];
            foreach ($ListOfSrvTimeDifferences as $entry) {
                $team_id = $entry['team_id'];
                $team_name = $entry['team_name'];
                $division_name = $entry['division_name'];
                $time_difference = $entry['time_difference_in_seconds'];

                if (!isset($timeSumsByTeamSrv[$team_id])) {
                    $timeSumsByTeamSrv[$team_id] = [
                        'team_name' => $team_name,
                        'division_name' => $division_name,
                        'total_time_in_seconds' => 0,
                        'count' => 0
                    ];
                }

                $timeSumsByTeamSrv[$team_id]['total_time_in_seconds'] += $time_difference;
                $timeSumsByTeamSrv[$team_id]['count']++;
            }

            // Step 8: Calculate average times for SRV teams
            $averagesBySrvTeam = [];
            foreach ($timeSumsByTeamSrv as $team_id => $data) {
                $totalTime = $data['total_time_in_seconds'];
                $count = $data['count'];
                $averageSrvTime = $totalTime / $count;

                $averagesBySrvTeam[$team_id] = [
                    'team_id' => $team_id,
                    'team_name' => $data['team_name'],
                    'division_name' => $data['division_name'],
                    'total_time' => gmdate("H:i:s", $totalTime),
                    'average_srv_time' => gmdate("H:i:s", $averageSrvTime)
                ];
            }

            // Step 9: Add closed, open tickets, and company data
            $ticketCounts = DB::select("SELECT  
                COALESCE(c1.company_name, c2.company_name) AS company_name,
                t.id AS team_id,
                t.team_name,
                COUNT(DISTINCT CASE WHEN th1.status_id = 6 THEN th1.ticket_number END) AS ticket_closed_by_team,
                COUNT(DISTINCT CASE WHEN th2.status_id != 6 THEN th2.ticket_number END) AS ticket_open_by_team
                FROM helpdesk.teams t
                LEFT JOIN helpdesk.ticket_histories th1 ON th1.team_id = t.id
                LEFT JOIN helpdesk.companies c1 ON c1.id = th1.business_entity_id
                LEFT JOIN helpdesk.tickets th2 ON th2.team_id = t.id
                LEFT JOIN helpdesk.companies c2 ON c2.id = th2.business_entity_id
                GROUP BY t.id, t.team_name, COALESCE(c1.company_name, c2.company_name)
                ORDER BY t.team_name
                
            ");

            // $mergedData = array_merge($averagesByTeam, $averagesBySrvTeam);

            // Step 10: Merge the averages and ticket counts into the final response
            foreach ($averagesByTeam as $team_id => $teamData) {
                if (isset($averagesBySrvTeam[$team_id])) {
                    $averagesByTeam[$team_id]['average_srv_time'] = $averagesBySrvTeam[$team_id]['average_srv_time'];
                }

                // Add ticket closed, open counts, and company name
                foreach ($ticketCounts as $ticketData) {
                    if ($ticketData->team_id == $team_id) {
                        $averagesByTeam[$team_id]['company_name'] = $ticketData->company_name ?? null;
                        $averagesByTeam[$team_id]['ticket_closed_by_team'] = $ticketData->ticket_closed_by_team ?? 0;
                        $averagesByTeam[$team_id]['ticket_open_by_team'] = $ticketData->ticket_open_by_team ?? 0;
                    }
                }
            }

            // Step 11: Final output
            $finalOutput = array_values($averagesByTeam);

            $sumClosedTickets = 0;
            $sumOpenTickets = 0;

            $totalTimes = [];
            $averageTimes = [];
            $averageSrvTimes = [];

            foreach ($finalOutput as $item) {
                // Sum ticket counts
                $sumClosedTickets += $item['ticket_closed_by_team'];
                $sumOpenTickets += $item['ticket_open_by_team'];

                // Convert time strings to seconds
                $totalTimes[] = strtotime($item['total_time']) - strtotime('TODAY');
                $averageTimes[] = strtotime($item['average_time']) - strtotime('TODAY');
                $averageSrvTimes[] = strtotime($item['average_srv_time']) - strtotime('TODAY');
            }

            // Sum times
            $sumTotalTimeInSeconds = array_sum($totalTimes);
            $sumAverageTimeInSeconds = array_sum($averageTimes);
            $sumAverageSrvTimeInSeconds = array_sum($averageSrvTimes);

            // Calculate averages (divide total by the number of entries)
            $overallAverageTotalTime = $sumTotalTimeInSeconds / count($finalOutput);
            $overallAverageSrvTime = $sumAverageSrvTimeInSeconds / count($finalOutput);

            // Format times back to "H:i:s"
            $formattedTotalTime = gmdate("H:i:s", $sumTotalTimeInSeconds);
            $formattedAverageTime = gmdate("H:i:s", $sumAverageTimeInSeconds);
            $formattedAverageSrvTime = gmdate("H:i:s", $overallAverageSrvTime);

            // Add sums and averages to the response
            $finalOutputSum = [
                'total_time' => $formattedTotalTime,
                'average_time' => $formattedAverageTime,
                'average_srv_time' => $formattedAverageSrvTime,
                'ticket_closed_by_team' => $sumClosedTickets,
                'ticket_open_by_team' => $sumOpenTickets,
            ];

            $sla = DB::select("SELECT  
                    COUNT(DISTINCT CASE WHEN tsh.srv_time_status = 2 THEN tsh.ticket_number END) AS over_due,
                    COUNT(DISTINCT CASE WHEN tsh.srv_time_status = 1 THEN tsh.ticket_number END) AS sla_success
                FROM 
                    helpdesk.ticket_srv_time_team_histories tsh
                ");

            $formattedOutputSum = [
                ['label' => 'Sla Success', 'value' => $sla['sla_success']],
                ['label' => 'Over Due', 'value' => $sla['over_due']],
                ['label' => 'AVG First Response Time', 'value' => $finalOutputSum['average_time']],
                ['label' => 'AVG Service Time', 'value' => $finalOutputSum['average_srv_time']],
                ['label' => 'Closed', 'value' => $finalOutputSum['ticket_closed_by_team']],
                ['label' => 'Open', 'value' => $finalOutputSum['ticket_open_by_team']],
            ];

            $response = [
                'summary' => $formattedOutputSum,
                'details' => $finalOutput,
            ];

            return ApiResponse::success($response, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Failed", 500);
        }
    }


    public function ticketLifeCycle(Request $request)
    {
        try {
            // Get filter parameters from the request
            $ticketNumber = $request->input('ticket_number');
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');
            $companyId = $request->input('company_id');

            // Build the base query
            // if (!empty($startDate) && !empty($endDate)) {
            $query = "SELECT 
                    th.ticket_number, c.company_name, divs.division_name, departs.department_name, t.team_name, sc.source_name, 
                    ucm.client_name, cat.category_in_english, scat.sub_category_in_english, st.status_name, p.priority_name, 
                    th.attached_filename, th.ref_ticket_no, tsh.srv_time_status_name,cc.agent_email, th.note, th.created_at, th.updated_at
                FROM helpdesk.ticket_histories th
                JOIN helpdesk.companies c ON th.business_entity_id = c.id
                JOIN helpdesk.teams t ON th.team_id = t.id
                JOIN helpdesk.user_client_mappings ucm ON th.client_id_vendor = ucm.client_id
                JOIN helpdesk.categories cat ON th.cat_id = cat.id
                JOIN helpdesk.sub_categories scat ON th.subcat_id = scat.id
                LEFT JOIN helpdesk.sources sc ON th.source_id = sc.id
                LEFT JOIN helpdesk.divisions divs ON t.division_id = divs.id
                LEFT JOIN helpdesk.departments departs ON t.department_id = departs.id
                LEFT JOIN helpdesk.statuses st ON st.id = th.status_id
                LEFT JOIN helpdesk.priorities p ON th.priority_name = p.id
                LEFT JOIN helpdesk.ticket_srv_time_team_histories tsh ON th.ticket_number = tsh.ticket_number
                LEFT JOIN helpdesk.ticket_ccs cc ON th.ticket_number = cc.ticket_number
                WHERE 1 = 1
            ";

            // Add conditional filters
            if ($ticketNumber) {
                $query .= " AND th.ticket_number = :ticket_number";
            }
            if ($startDate && $endDate) {
                $query .= " AND th.created_at BETWEEN :start_date AND :end_date";
            }
            if ($companyId) {
                $query .= " AND c.id = :company_id";
            }

            $query .= " ORDER BY th.ticket_number";

            // Execute the query with parameters
            $parameters = [];
            if ($ticketNumber) $parameters['ticket_number'] = $ticketNumber;
            if ($startDate && $endDate) {
                $parameters['start_date'] = $startDate;
                $parameters['end_date'] = $endDate;
            }
            if ($companyId) $parameters['company_id'] = $companyId;

            $ticketLifeCycle = DB::select($query, $parameters);


            return ApiResponse::success($ticketLifeCycle, "Success", 200);
            // }
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    


    // public function getTicketDetails(Request $request)
    // {
    //     $startDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //     $endDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";
    //     $businessEntityId = $request->businessEntity;
    //     $clientIdHelpdesk = $request->localClients;
    //     $teamID = $request->allTeams;

    //     // return $teamID;

    //     $query = DB::table('helpdesk.ticket_histories as th')
    //         ->distinct()
    //         ->select('th.ticket_number');

    //     if ($startDate && $endDate) {
    //         $query->whereBetween('th.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
    //     }

    //     if ($businessEntityId) {
    //         $query->where('th.business_entity_id', $businessEntityId);
    //     }

    //     if ($clientIdHelpdesk) {
    //         $query->where('th.client_id_helpdesk', $clientIdHelpdesk);
    //     }

    //     if ($teamID) {
    //         $query->where('th.team_id', $teamID);
    //     }

    //     $ticketNumbers = $query->pluck('ticket_number');
    //     $ticketDetails = [];



    //     foreach ($ticketNumbers as $ticketNumber) {
    //         $ticketQuery = DB::table(DB::raw("(SELECT th.ticket_number,c.company_name,departs.department_name, t.team_name,
    //         ucm.client_name,cat.category_in_english,scat.sub_category_in_english,st.status_name,
    //         GROUP_CONCAT(DISTINCT u.username) AS created_by,GROUP_CONCAT(DISTINCT u2.username) AS status_update_by,
    //         GROUP_CONCAT(DISTINCT tc.comments) AS comments,GROUP_CONCAT(DISTINCT t1.team_name) AS agent_team_names,
    //         th.updated_at,th.created_at,ss.srv_time_str,tsh.srv_time_status_name
    //         FROM helpdesk.ticket_histories AS th
    //         JOIN users AS u ON th.user_id = u.id
    //         JOIN helpdesk.companies AS c ON th.business_entity_id = c.id
    //         JOIN helpdesk.teams AS t ON th.team_id = t.id
    //         JOIN helpdesk.user_client_mappings AS ucm ON th.client_id_helpdesk = ucm.user_id
    //         JOIN helpdesk.categories AS cat ON th.cat_id = cat.id
    //         JOIN helpdesk.sub_categories AS scat ON th.subcat_id = scat.id
    //         LEFT JOIN helpdesk.departments AS departs ON t.department_id = departs.id
    //         LEFT JOIN helpdesk.statuses AS st ON st.id = th.status_id
    //         LEFT JOIN users AS u2 ON th.status_update_by = u2.id
    //         LEFT JOIN user_team_mappings AS utm ON th.status_update_by = utm.user_id
    //         LEFT JOIN helpdesk.teams AS t1 ON utm.team_id = t1.id
    //         LEFT JOIN helpdesk.ticket_comments AS tc ON th.ticket_number = tc.ticket_number 
    //             AND tc.user_id = th.status_update_by 
    //             AND th.updated_at = tc.updated_at
    //         LEFT JOIN helpdesk.sla_subcategories AS ss ON th.subcat_id = ss.subcat_id AND th.team_id = ss.team_id
    //         LEFT JOIN helpdesk.ticket_srv_time_team_histories AS tsh ON th.ticket_number = tsh.ticket_number 
    //             AND th.team_id = tsh.team_id 
    //             AND th.subcat_id = tsh.subcat_id 
    //             AND tsh.srv_time_status != 0
    //         WHERE th.ticket_number = '$ticketNumber'
    //         GROUP BY th.ticket_number, c.company_name, departs.department_name, t.team_name, 
    //             ucm.client_name, cat.category_in_english, scat.sub_category_in_english, st.status_name, 
    //             th.updated_at, th.created_at, ss.srv_time_str, tsh.srv_time_status_name

    //         UNION ALL

    //         SELECT tc.ticket_number,NULL AS company_name, NULL AS department_name,NULL AS team_name,NULL AS client_name,
    //             NULL AS category_in_english,NULL AS sub_category_in_english,NULL AS status_name,NULL AS created_by,
    //             GROUP_CONCAT(DISTINCT u.username) AS status_update_by,GROUP_CONCAT(DISTINCT tc.comments) AS comments,
    //             GROUP_CONCAT(DISTINCT t1.team_name) AS agent_team_names,tc.created_at AS updated_at,
    //             NULL AS created_at,NULL AS srv_time_str,NULL AS srv_time_status_name
    //         FROM helpdesk.ticket_comments AS tc
    //         JOIN users AS u ON tc.user_id = u.id
    //         LEFT JOIN helpdesk.teams AS t1 ON tc.team_id = t1.id
    //         LEFT JOIN helpdesk.user_client_mappings AS ucm ON tc.user_id = ucm.user_id
    //         WHERE tc.ticket_number = '$ticketNumber'
    //             AND NOT EXISTS (
    //                 SELECT 1 
    //                 FROM helpdesk.ticket_histories AS th 
    //                 WHERE th.ticket_number = tc.ticket_number 
    //                 AND th.updated_at = tc.created_at
    //             )
    //         GROUP BY tc.ticket_number, tc.created_at) AS combined_query"))
    //             ->orderBy('updated_at', 'asc')
    //             ->get();

    //         $formattedDetails = $this->formatTicketDetails($ticketQuery);
    //         $ticketDetails[] = $formattedDetails;
    //     }

    //     return response()->json($ticketDetails);
    // }

    public function getTicketDetails(Request $request)
    {
        $startDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
        $endDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";
        $businessEntityId = $request->businessEntity;
        $clientIdHelpdesk = $request->localClients;
        $teamID = $request->allTeams;

        // return $teamID;

        $query = DB::table('helpdesk.ticket_histories as th')
            ->distinct()
            ->select('th.ticket_number');

        if ($startDate && $endDate) {
            $query->whereBetween('th.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        }

        if ($businessEntityId) {
            $query->where('th.business_entity_id', $businessEntityId);
        }

        if ($clientIdHelpdesk) {
            $query->where('th.client_id_helpdesk', $clientIdHelpdesk);
        }

        if ($teamID) {
            $query->where('th.team_id', $teamID);
        }

        $ticketNumbers = $query->pluck('ticket_number');
        $ticketDetails = [];



        foreach ($ticketNumbers as $ticketNumber) {
            $ticketQuery = DB::table(DB::raw("(SELECT th.ticket_number,c.company_name,departs.department_name, t.team_name,
            ucm.client_name,cat.category_in_english,scat.sub_category_in_english,st.status_name,
            GROUP_CONCAT(DISTINCT u.fullname) AS created_by,GROUP_CONCAT(DISTINCT u2.fullname) AS status_update_by,GROUP_CONCAT(DISTINCT u3.fullname) AS assigned_agent_name,
            GROUP_CONCAT(DISTINCT tc.comments) AS comments,GROUP_CONCAT(DISTINCT t1.team_name) AS agent_team_names,
            th.updated_at,th.created_at,ss.resolution_min,tsh.sla_status
            FROM helpdesk.ticket_histories AS th
            JOIN user_profiles AS u ON th.user_id = u.user_id
            JOIN helpdesk.companies AS c ON th.business_entity_id = c.id
            JOIN helpdesk.teams AS t ON th.team_id = t.id
            JOIN helpdesk.user_client_mappings AS ucm ON th.client_id_helpdesk = ucm.user_id
            JOIN helpdesk.categories AS cat ON th.cat_id = cat.id
            JOIN helpdesk.sub_categories AS scat ON th.subcat_id = scat.id
            LEFT JOIN helpdesk.departments AS departs ON t.department_id = departs.id
            LEFT JOIN helpdesk.statuses AS st ON st.id = th.status_id
            LEFT JOIN user_profiles AS u2 ON th.status_updated_by = u2.user_id
            LEFT JOIN user_profiles AS u3 ON th.assigned_agent_id = u3.user_id
            LEFT JOIN user_team_mappings AS utm ON th.status_updated_by = utm.user_id
            LEFT JOIN helpdesk.teams AS t1 ON utm.team_id = t1.id
            LEFT JOIN helpdesk.ticket_comments AS tc ON th.ticket_number = tc.ticket_number 
                AND tc.user_id = th.status_updated_by 
                AND th.updated_at = tc.updated_at
            LEFT JOIN helpdesk.sla_subcat_configs AS ss ON th.subcat_id = ss.subcategory_id AND th.team_id = ss.team_id AND th.business_entity_id = ss.business_entity_id
            LEFT JOIN helpdesk.srv_time_subcat_sla_histories AS tsh ON th.ticket_number = tsh.ticket_number AND ss.id = tsh.sla_subcat_config_id
                
                AND tsh.sla_status != 0
            WHERE th.ticket_number = '$ticketNumber'
            GROUP BY th.ticket_number, c.company_name, departs.department_name, t.team_name, 
                ucm.client_name, cat.category_in_english, scat.sub_category_in_english, st.status_name, 
                th.updated_at, th.created_at, ss.resolution_min, tsh.sla_status

            UNION ALL

            SELECT tc.ticket_number,NULL AS company_name, NULL AS department_name,NULL AS team_name,NULL AS client_name,
                NULL AS category_in_english,NULL AS sub_category_in_english,NULL AS status_name,NULL AS created_by,
                GROUP_CONCAT(DISTINCT u.fullname) AS status_update_by,NULL AS assigned_agent_name,GROUP_CONCAT(DISTINCT tc.comments) AS comments,
                GROUP_CONCAT(DISTINCT t1.team_name) AS agent_team_names,tc.created_at AS updated_at,
                NULL AS created_at,NULL AS resolution_min,NULL AS sla_status
            FROM helpdesk.ticket_comments AS tc
            JOIN user_profiles AS u ON tc.user_id = u.user_id
            LEFT JOIN helpdesk.teams AS t1 ON tc.team_id = t1.id
            LEFT JOIN helpdesk.user_client_mappings AS ucm ON tc.user_id = ucm.user_id
            WHERE tc.ticket_number = '$ticketNumber'
                AND NOT EXISTS (
                    SELECT 1 
                    FROM helpdesk.ticket_histories AS th 
                    WHERE th.ticket_number = tc.ticket_number 
                    AND th.updated_at = tc.created_at
                )
            GROUP BY tc.ticket_number, tc.created_at) AS combined_query"))
                ->orderBy('updated_at', 'asc')
                ->get();

            $formattedDetails = $this->formatTicketDetails($ticketQuery);
            $ticketDetails[] = $formattedDetails;
        }

        return response()->json($ticketDetails);
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
                $timeDifference = sprintf("%02d h:%02d m:%02d s", $hours, $minutes, $seconds);
            }

            $ticket->time_difference = $timeDifference;
            $previousUpdatedAt = $ticket->updated_at;

            if ($levelCounter == 1) {
                $formatted = [
                    'ticket_number' => $ticket->ticket_number,
                    'company_name' => $ticket->company_name,
                    'client_name' => $ticket->client_name,
                    'category_subcategory' => $ticket->category_in_english . ' [' . $ticket->sub_category_in_english . ']',
                    'created_at' => $ticket->created_at,
                    'comments' => $ticket->comments,
                    // 'srv_time_status_name' => $ticket->srv_time_status_name,
                    'levels' => []
                ];
            }


            $formatted['levels'][] = [
                'ticket_created_by' => $ticket->created_by,
                'assigned_to' => $ticket->team_name,
                'department' => $ticket->department_name,
                'ticket_status' => $ticket->status_name,
                'agent' => $ticket->status_update_by,
                'assigned_agent' => $ticket->assigned_agent_name,
                // 'agent_team' => $ticket->agent_team_names,
                'agent_team' => ' ' . str_replace(',', ', ', $ticket->agent_team_names) . ' ',
                'comment' => $ticket->comments,
                'updated_at' => $ticket->updated_at,
                'ticket_age' => $ticket->time_difference,
                'sla' => $ticket->resolution_min,
                'sla_status' => $ticket->sla_status
            ];

            $levelCounter++;
        }

        return $formatted;
    }




    // public function getLocalClientsByBusinessEntityId($id)
    // {
    //     try {
    //         $localClients = DB::select("SELECT u.id, u.fullname 
    //             FROM helpdesk.users u
    //             INNER JOIN helpdesk.user_entity_mappings uem 
    //                 ON u.id = uem.user_id
    //             WHERE u.user_type = 'Client'
    //             AND uem.business_entity_id = '$id' ");
    //         return ApiResponse::success($localClients, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }




    public function getLocalClientsByBusinessEntityId($id)
    {
        try {
            $localClients = DB::select("SELECT up.user_id, up.fullname 
                FROM user_profiles up
                INNER JOIN helpdesk.user_entity_mappings uem ON up.user_id = uem.user_id
                WHERE up.user_type = 'Client'
                AND uem.business_entity_id = '$id' ");
            return ApiResponse::success($localClients, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }






   


    public function getNewTicketReports(Request $request)
{
    try {

        $startDate = !empty($request->fromDate)
            ? (new DateTime($request->fromDate))->format('Y-m-d')
            : null;

        $endDate = !empty($request->toDate)
            ? (new DateTime($request->toDate))->format('Y-m-d')
            : null;

        $businessEntityId = $request->businessEntity ?? null;
        $catId            = $request->allCategory ?? null;
        $sub_catId        = $request->allSubcat ?? null;
        $status           = strtolower($request->status ?? '');
        $dateFilterType   = $request->dateFilterType ?? 'created_at';


        /* ---------------- STATUS VALIDATION ---------------- */

        if (!in_array($status, ['open', 'closed'])) {
            return ApiResponse::error(
                "Status must be open or closed",
                "Error",
                400
            );
        }

        $tableName = $status === 'open'
            ? 'helpdesk.open_tickets'
            : 'helpdesk.close_tickets';


        /* ---------------- DATE VALIDATION ---------------- */

        if (($businessEntityId || $catId || $sub_catId) && (!$startDate || !$endDate)) {
            return ApiResponse::error(
                "Start Date and End Date are required when filtering by Business Entity, Category or Sub-category.",
                "Error",
                400
            );
        }


        /* ---------------- CONDITIONS ---------------- */

        $conditions = [];

        if ($startDate && $endDate) {
            $column = $dateFilterType === 'updated_at'
                ? 't.updated_at'
                : 't.created_at';

            // index friendly filtering
            $conditions[] = "$column BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'";
        }

        if ($businessEntityId) {
            $conditions[] = "co.id = '{$businessEntityId}'";
        }

        if ($catId) {
            $conditions[] = "c.id = '{$catId}'";
        }

        if ($sub_catId) {
            $conditions[] = "sc.id = '{$sub_catId}'";
        }


        /* ---------------- MAIN QUERY ---------------- */

        $ticketQuery = "
            SELECT DISTINCT
                t.ticket_number AS Ticket_Number,
                co.company_name AS Business_Entity,
                te.team_name AS Team,
                ucm.client_name AS Client_Name,
                t.id AS ID,
                c.category_in_english AS Category,
                sc.sub_category_in_english AS Sub_Category,

                '{$status}' AS Status,

                CASE
                    WHEN '{$status}' = 'open' THEN
                        CONCAT(
                            TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ',
                            TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ',
                            TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm'
                        )
                    ELSE
                        CONCAT(
                            TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ',
                            TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ',
                            TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm'
                        )
                END AS Age,

                b.branch_name AS Branch_Name,
                bael.name AS Element_Name,
                bel.name AS Element_List,
                bela.name AS Element_List_A,
                belb.name AS Element_List_B,

                u.username AS Created_by,
                te2.team_name AS Created_Team,
                t.created_at AS Created_Time,

                CASE WHEN '{$status}' = 'closed' THEN u2.username END AS Closed_by,
                CASE WHEN '{$status}' = 'closed' THEN te.team_name END AS Closed_Team,
                CASE WHEN '{$status}' = 'closed' THEN t.updated_at END AS Closed_Time,

                latest_comments.comments AS Last_Comment,
                latest_comments.username AS Last_Commented_by,
                latest_comments.team_name AS Last_Commented_Team,
                latest_comments.created_at AS Last_Comment_Time,

                latest_rca.comments AS RCA_Comment,
                latest_rca.username AS RCA_by,
                latest_rca.team_name AS RCA_Team,
                latest_rca.created_at AS RCA_Time,

                t.mobile_no AS Complaint_Number,
                COALESCE(tesc.team_escalate_count, 0) AS Escalation_Count

            FROM $tableName t

            JOIN helpdesk.teams te ON t.team_id = te.id
            JOIN helpdesk.categories c ON t.cat_id = c.id
            JOIN helpdesk.sub_categories sc ON t.subcat_id = sc.id
            JOIN helpdesk.companies co ON t.business_entity_id = co.id

            LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
            LEFT JOIN helpdesk.teams te2 ON utm.team_id = te2.id
            LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
            LEFT JOIN helpdesk.ticket_branches tbs ON tbs.ticket_number = t.ticket_number
            LEFT JOIN helpdesk.branches b ON tbs.branch_id = b.id

            LEFT JOIN helpdesk.ticket_backbones tb ON t.ticket_number = tb.ticket_number
            LEFT JOIN helpdesk.backbone_elements bael ON tb.backbone_element_id = bael.id
            LEFT JOIN helpdesk.backbone_element_lists bel ON tb.backbone_element_list_id = bel.id
            LEFT JOIN helpdesk.backbone_element_lists bela ON tb.backbone_element_list_id_a_end = bela.id
            LEFT JOIN helpdesk.backbone_element_lists belb ON tb.backbone_element_list_id_b_end = belb.id

            LEFT JOIN users u ON t.user_id = u.id
            LEFT JOIN users u2 ON t.status_updated_by = u2.id

            LEFT JOIN (
                SELECT ticket_number, comments, username, team_name, created_at
                FROM (
                    SELECT tc.ticket_number, tc.comments, u.username, te.team_name, tc.created_at,
                           ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) rn
                    FROM helpdesk.ticket_comments tc
                    JOIN helpdesk.users u ON tc.user_id = u.id
                    JOIN helpdesk.teams te ON tc.team_id = te.id
                ) ranked
                WHERE rn = 1
            ) latest_comments ON t.ticket_number = latest_comments.ticket_number

            LEFT JOIN (
                SELECT ticket_number, comments, username, team_name, created_at
                FROM (
                    SELECT tc.ticket_number, tc.comments, u.username, te.team_name, tc.created_at,
                           ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) rn
                    FROM helpdesk.ticket_comments tc
                    JOIN helpdesk.users u ON tc.user_id = u.id
                    JOIN helpdesk.teams te ON tc.team_id = te.id
                    WHERE tc.is_rca = 1
                ) ranked
                WHERE rn = 1
            ) latest_rca ON t.ticket_number = latest_rca.ticket_number

            LEFT JOIN (
                SELECT ticket_number, COUNT(*) AS team_escalate_count
                FROM (
                    SELECT ticket_number, team_id,
                           LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) prev_team_id
                    FROM helpdesk.ticket_histories
                ) x
                WHERE team_id <> prev_team_id
                GROUP BY ticket_number
            ) tesc ON t.ticket_number = tesc.ticket_number
        ";


        if (!empty($conditions)) {
            $ticketQuery .= " WHERE " . implode(" AND ", $conditions);
        } else {
            $ticketQuery .= " WHERE t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        }


        $results = DB::select($ticketQuery);

        return ApiResponse::success($results, "Success", 200);

    } catch (\Exception $e) {
        return ApiResponse::error($e->getMessage(), "Error", 500);
    }
}
   

    // public function getAgentPerformance(Request $request)
    // {
    //     try {
    //         $startDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : null;
    //         $endDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : null;
    //         $teamId = !empty($request->allTeam) ? $request->allTeam : null;
    //         $agentId = !empty($request->allAgent) ? $request->allAgent : null;

    //         if (($teamId || $agentId) && (!$startDate || !$endDate)) {
    //             return ApiResponse::error("Start Date and End Date are required when filtering by Team.", "Error", 400);
    //         }

    //         /* ---------------- CREATED (OPEN + CLOSED) ---------------- */

    //     $createdSubQuery = "
    //         SELECT user_id, ticket_number, created_at
    //         FROM helpdesk.open_tickets
    //         UNION ALL
    //         SELECT user_id, ticket_number, created_at
    //         FROM helpdesk.close_tickets
    //     ";

    //         $ticketQuery = "SELECT 
    //             u.id, 
    //             up.fullname AS Agent, 
    //             te.team_name AS Team,
    //             COALESCE(NULLIF(COUNT(DISTINCT t.ticket_number), 0), '--') AS Created, 
    //             COALESCE(NULLIF(ct.closed_tickets, 0), '--') AS Closed, 
    //             COALESCE(NULLIF(tc_count.ticket_comments, 0), '--') AS Commented, 
    //             COALESCE(NULLIF(te_count.ticket_escalate_count, 0), '--') AS Escalated,
    //             COALESCE(NULLIF(
    //                 COALESCE(NULLIF(COUNT(DISTINCT t.ticket_number), 0), 0) + 
    //                 COALESCE(NULLIF(ct.closed_tickets, 0), 0) + 
    //                 COALESCE(NULLIF(tc_count.ticket_comments, 0), 0) + 
    //                 COALESCE(NULLIF(te_count.ticket_escalate_count, 0), 0),
    //             0), '--') AS Total
    //         FROM helpdesk.users u
    //         INNER JOIN helpdesk.user_profiles up ON up.user_id = u.id
    //         LEFT JOIN helpdesk.user_team_mappings utm ON u.id = utm.user_id
    //         LEFT JOIN helpdesk.teams te ON utm.team_id = te.id
    //          LEFT JOIN (
    //             $createdSubQuery
    //         ) c ON u.id = c.user_id ."($startDate && $endDate ? " AND DATE(t.created_at) BETWEEN '$startDate' AND '$endDate'" : "") . "
    //         LEFT JOIN (
    //             SELECT status_update_by, COUNT(*) AS closed_tickets
    //             FROM helpdesk.close_tickets " .
    //             ($startDate && $endDate ? " AND DATE(created_at) BETWEEN '$startDate' AND '$endDate'" : "") . "
    //             GROUP BY status_update_by
    //         ) AS ct ON u.id = ct.status_update_by
    //         LEFT JOIN (
    //             SELECT user_id, COUNT(ticket_number) AS ticket_comments
    //             FROM helpdesk.ticket_comments" .
    //             ($startDate && $endDate ? " WHERE DATE(created_at) BETWEEN '$startDate' AND '$endDate'" : "") . "
    //             GROUP BY user_id
    //         ) AS tc_count ON u.id = tc_count.user_id
    //         LEFT JOIN (
    //             SELECT status_update_by, COUNT(*) AS ticket_escalate_count
    //             FROM (
    //                 SELECT status_update_by, team_id,
    //                     LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
    //                 FROM helpdesk.ticket_histories" .
    //             ($startDate && $endDate ? " WHERE DATE(updated_at) BETWEEN '$startDate' AND '$endDate'" : "") . "
    //             ) AS subquery
    //             WHERE team_id <> prev_team_id
    //               AND status_update_by IS NOT NULL
    //             GROUP BY status_update_by
    //         ) AS te_count ON u.id = te_count.status_update_by
    //         WHERE up.user_type = 'Agent'";

    //         // Apply team filter here in WHERE clause
    //         if ($teamId) {
    //             $ticketQuery .= " AND te.id = '$teamId'";
    //         }

    //         // Apply agent filter
    //         if ($agentId) {
    //             $ticketQuery .= " AND u.id = '$agentId'";
    //         }

    //         // Default last 1 month if no date/team/agent filter
    //         if (!$startDate || !$endDate) {
    //             $ticketQuery .= " AND t.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
    //         }

    //         $ticketQuery .= " GROUP BY 
    //             u.id, up.fullname, te.team_name, ct.closed_tickets, te_count.ticket_escalate_count, tc_count.ticket_comments
    //         ORDER BY te.team_name";

    //         $results = DB::select($ticketQuery);

    //         return ApiResponse::success($results, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }

    public function getAgentPerformance(Request $request)
{
    try {
        $startDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : null;
        $endDate   = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : null;
        $teamId    = !empty($request->allTeam) ? $request->allTeam : null;
        $agentId   = !empty($request->allAgent) ? $request->allAgent : null;

        if (($teamId || $agentId) && (!$startDate || !$endDate)) {
            return ApiResponse::error("Start Date and End Date are required when filtering by Team.", "Error", 400);
        }

        $createdSubQuery = "
            SELECT user_id, ticket_number, created_at
            FROM helpdesk.open_tickets
            UNION ALL
            SELECT user_id, ticket_number, created_at
            FROM helpdesk.close_tickets
        ";

        $ticketQuery = "SELECT 
                u.id, 
                up.fullname AS Agent, 
                te.team_name AS Team,
                COALESCE(NULLIF(COUNT(DISTINCT c.ticket_number), 0), '--') AS Created, 
                COALESCE(NULLIF(ct.closed_tickets, 0), '--') AS Closed, 
                COALESCE(NULLIF(tc_count.ticket_comments, 0), '--') AS Commented, 
                COALESCE(NULLIF(te_count.ticket_escalate_count, 0), '--') AS Escalated,
                COALESCE(NULLIF(
                    COALESCE(COUNT(DISTINCT c.ticket_number), 0) + 
                    COALESCE(ct.closed_tickets, 0) + 
                    COALESCE(tc_count.ticket_comments, 0) + 
                    COALESCE(te_count.ticket_escalate_count, 0),
                0), '--') AS Total
            FROM helpdesk.users u
            INNER JOIN helpdesk.user_profiles up ON up.user_id = u.id
            LEFT JOIN helpdesk.user_team_mappings utm ON u.id = utm.user_id
            LEFT JOIN helpdesk.teams te ON utm.team_id = te.id
            LEFT JOIN (
                $createdSubQuery
            ) c ON u.id = c.user_id";

        if ($startDate && $endDate) {
            $ticketQuery .= " AND c.created_at BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'";
        }

        $ticketQuery .= "
            LEFT JOIN (
                SELECT status_updated_by, COUNT(*) AS closed_tickets
                FROM helpdesk.close_tickets";

        if ($startDate && $endDate) {
            $ticketQuery .= " WHERE created_at BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'";
        }

        $ticketQuery .= "
                GROUP BY status_updated_by
            ) AS ct ON u.id = ct.status_updated_by
            LEFT JOIN (
                SELECT user_id, COUNT(ticket_number) AS ticket_comments
                FROM helpdesk.ticket_comments";

        if ($startDate && $endDate) {
            $ticketQuery .= " WHERE created_at BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'";
        }

        $ticketQuery .= "
                GROUP BY user_id
            ) AS tc_count ON u.id = tc_count.user_id
            LEFT JOIN (
                SELECT status_updated_by, COUNT(*) AS ticket_escalate_count
                FROM (
                    SELECT status_updated_by, team_id,
                        LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
                    FROM helpdesk.ticket_histories";

        if ($startDate && $endDate) {
            $ticketQuery .= " WHERE updated_at BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'";
        }

        $ticketQuery .= "
                ) AS subquery
                WHERE team_id <> prev_team_id
                  AND status_updated_by IS NOT NULL
                GROUP BY status_updated_by
            ) AS te_count ON u.id = te_count.status_updated_by
            WHERE up.user_type = 'Agent'";

        if ($teamId) {
            $ticketQuery .= " AND te.id = '{$teamId}'";
        }

        if ($agentId) {
            $ticketQuery .= " AND u.id = '{$agentId}'";
        }

        if (!$startDate || !$endDate) {
            $ticketQuery .= " AND c.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)";
        }

        $ticketQuery .= "
            GROUP BY 
                u.id, up.fullname, te.team_name,
                ct.closed_tickets,
                te_count.ticket_escalate_count,
                tc_count.ticket_comments
            ORDER BY te.team_name";

        $results = DB::select($ticketQuery);

        return ApiResponse::success($results, "Success", 200);

    } catch (\Exception $e) {
        return ApiResponse::error($e->getMessage(), "Error", 500);
    }
}



    public function getAgentPerformanceDetails(Request $request)
    {
        $userId = $request->user_id;
        $isCreated = $request->isCreated;
        $isClose = $request->isClose;
        $isCommented = $request->isCommented;
        $isEscaleted = $request->isEscaleted;
        $isTotal = $request->isTotal;

        $fromDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
        $toDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";



        if ($isCreated === true && !empty($userId)) {
            $Details = DB::select("SELECT distinct t.id,t.ticket_number,t.user_id,ucm.client_name, fth.fr_response_status_name
                , tst.srv_time_status_name, t.business_entity_id,c.company_name,t.team_id,t.sid,teams.team_name,t.cat_id,categories.category_in_english,
                t.subcat_id,sub_categories.sub_category_in_english,t.status_id,statuses.status_name,t.created_at,t.updated_at,
                CASE 
                        WHEN t.status_id = 1 THEN 
                                CONCAT(
                                        TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                        TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                        TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
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
               
                LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                AND ucm.business_entity_id = c.id
                LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                LEFT JOIN helpdesk.ticket_histories th ON t.ticket_number = th.ticket_number
                
                LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                AND t.team_id = tst.team_id AND tst.srv_time_status != 0 
                LEFT JOIN (SELECT ticket_number, comments
                        FROM (SELECT tc.ticket_number, tc.comments, 
                                ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                    FROM helpdesk.ticket_comments tc) AS ranked_comments
                                        WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
                where t.user_id = '$userId'
                AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
               
                order by t.ticket_number desc");
        } else if ($isClose === true && !empty($userId)) {

            $Details = DB::select("SELECT distinct t.id,t.ticket_number,t.user_id,ucm.client_name, fth.fr_response_status_name, tst.    srv_time_status_name, 
                    t.business_entity_id,c.company_name,t.team_id,t.sid,teams.team_name,t.cat_id,categories.category_in_english,
                    t.subcat_id,sub_categories.sub_category_in_english,t.status_id,statuses.status_name,t.created_at,t.updated_at,
                            CASE 
                                    WHEN t.status_id = 1 THEN 
                                            CONCAT(
                                                    TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                    TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                    TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
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
                           
                            LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
                            AND ucm.business_entity_id = c.id
                            LEFT JOIN helpdesk.sub_categories sub_categories ON t.subcat_id = sub_categories.id
                            LEFT JOIN helpdesk.ticket_fr_time_team_histories fth ON t.ticket_number = fth.ticket_number 
                            AND t.team_id = fth.team_id AND fth.fr_response_status != 0
                            LEFT JOIN helpdesk.ticket_histories th ON t.ticket_number = th.ticket_number
                            
                            LEFT JOIN helpdesk.ticket_srv_time_team_histories tst ON t.ticket_number = tst.ticket_number 
                            AND t.team_id = tst.team_id AND tst.srv_time_status != 0 
                            LEFT JOIN (SELECT ticket_number, comments
									FROM (SELECT tc.ticket_number, tc.comments, 
											ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
												FROM helpdesk.ticket_comments tc) AS ranked_comments
													WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
                            where t.status_update_by = '$userId'
                            AND t.status_id = 6
                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                           
                            order by t.ticket_number desc");
        } else if ($isCommented === true && !empty($userId)) {
            $Details = DB::select("SELECT DISTINCT t.id, t.ticket_number, t.user_id, ucm.client_name, fth.fr_response_status_name, tst.srv_time_status_name, t.business_entity_id, c.company_name, t.team_id, t.sid, teams.team_name, t.cat_id, categories.category_in_english, t.subcat_id, sub_categories.sub_category_in_english, t.status_id, statuses.status_name, t.created_at,t.updated_at,
                            CASE 
                                WHEN t.status_id = 1 THEN 
                                    CONCAT(
                                        TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                        TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                        TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                    )
                            END AS ticket_age,

                            CASE 
                                WHEN fth.fr_response_status = 2 THEN 
                                    CONCAT(
                                        TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                        TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                        TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                    )
                                ELSE ''
                            END AS fr_due_time,
                            CASE 
                                WHEN tst.srv_time_status = 2 THEN 
                                    CONCAT(
                                        TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                        TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                        TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                    )
                                ELSE ''
                            END AS srv_due_time,

                            u.username AS created_by,
                            u2.username AS status_update_by,
                            tc.comments AS comment_by_user,
                            tc.created_at AS comment_date

                        FROM helpdesk.tickets t
                        LEFT JOIN helpdesk.companies c ON t.business_entity_id = c.id
                        LEFT JOIN users u ON t.user_id = u.id
                        LEFT JOIN users u2 ON t.status_update_by = u2.id
                        LEFT JOIN helpdesk.teams teams ON t.team_id = teams.id
                        LEFT JOIN helpdesk.statuses statuses ON t.status_id = statuses.id
                        LEFT JOIN helpdesk.categories ON t.cat_id = categories.id
                        LEFT JOIN helpdesk.user_client_mappings ucm 
                            ON t.client_id_helpdesk = ucm.user_id AND ucm.business_entity_id = c.id
                        LEFT JOIN helpdesk.sub_categories sub_categories 
                            ON t.subcat_id = sub_categories.id
                        LEFT JOIN helpdesk.ticket_fr_time_team_histories fth 
                            ON t.ticket_number = fth.ticket_number 
                            AND t.team_id = fth.team_id 
                            AND fth.fr_response_status != 0
                        LEFT JOIN helpdesk.ticket_histories th 
                            ON t.ticket_number = th.ticket_number
                        LEFT JOIN helpdesk.ticket_srv_time_team_histories tst 
                            ON t.ticket_number = tst.ticket_number 
                            AND t.team_id = tst.team_id 
                            AND tst.srv_time_status != 0 
                        JOIN helpdesk.ticket_comments tc 
                            ON t.ticket_number = tc.ticket_number
                            AND tc.user_id = '$userId' 
                            AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                        ORDER BY t.ticket_number DESC, tc.created_at DESC");
        } else if ($isEscaleted === true && !empty($userId)) {

            $Details = DB::select("WITH EscalatedTickets AS (
                                    SELECT DISTINCT ticket_number
                                    FROM (
                                        SELECT ticket_number, status_update_by, team_id,
                                            LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
                                        FROM helpdesk.ticket_histories
                                    ) AS subquery
                                    WHERE team_id <> prev_team_id
                                    AND status_update_by = '$userId'
                                )

                                SELECT DISTINCT t.id, t.ticket_number, t.user_id, ucm.client_name, 
                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                    t.business_entity_id, c.company_name, t.team_id, t.sid, 
                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                    t.subcat_id, sub_categories.sub_category_in_english, 
                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                    CASE 
                                        WHEN t.status_id = 1 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                    END AS ticket_age,

                                    CASE 
                                        WHEN fth.fr_response_status = 2 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                            )
                                        ELSE ''
                                    END AS fr_due_time,

                                    CASE 
                                        WHEN tst.srv_time_status = 2 THEN 
                                            CONCAT(
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
                                LEFT JOIN helpdesk.ticket_fr_time_team_histories fth 
                                    ON t.ticket_number = fth.ticket_number 
                                    AND t.team_id = fth.team_id 
                                    AND fth.fr_response_status != 0
                                LEFT JOIN helpdesk.ticket_histories th 
                                    ON t.ticket_number = th.ticket_number
                                LEFT JOIN helpdesk.ticket_srv_time_team_histories tst 
                                    ON t.ticket_number = tst.ticket_number 
                                    AND t.team_id = tst.team_id 
                                    AND tst.srv_time_status != 0 

                                LEFT JOIN (SELECT ticket_number, comments
                                                                    FROM (SELECT tc.ticket_number, tc.comments, 
                                                                            ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                                                                FROM helpdesk.ticket_comments tc) AS ranked_comments
                                                                                    WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number

                                WHERE t.ticket_number IN (SELECT ticket_number FROM EscalatedTickets)
                                AND DATE(t.created_at) BETWEEN '$fromDate' AND '$toDate'
                                ORDER BY t.ticket_number DESC");
        } else if ($isTotal === true && !empty($userId)) {

            $Details = DB::select("WITH EscalatedTickets AS (
                                    SELECT DISTINCT ticket_number
                                    FROM (
                                        SELECT ticket_number, status_update_by, team_id,
                                            LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
                                        FROM helpdesk.ticket_histories
                                    ) AS subquery
                                    WHERE team_id <> prev_team_id
                                    AND status_update_by = '$userId'
                                )

                                SELECT DISTINCT t.id, t.ticket_number, t.user_id, ucm.client_name, 
                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                    t.business_entity_id, c.company_name, t.team_id, t.sid, 
                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                    t.subcat_id, sub_categories.sub_category_in_english, 
                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                    CASE 
                                        WHEN t.status_id = 1 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                    END AS ticket_age,

                                    CASE 
                                        WHEN fth.fr_response_status = 2 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                            )
                                        ELSE ''
                                    END AS fr_due_time,

                                    CASE 
                                        WHEN tst.srv_time_status = 2 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                            )
                                        ELSE ''
                                    END AS srv_due_time,

                                    u.username AS created_by,
                                    u2.username AS status_update_by,
                                    latest_comments.comments AS last_comment,
                                    NULL AS comment_by_user,
                                    NULL AS comment_date

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
                                LEFT JOIN helpdesk.ticket_fr_time_team_histories fth 
                                    ON t.ticket_number = fth.ticket_number 
                                    AND t.team_id = fth.team_id 
                                    AND fth.fr_response_status != 0
                                LEFT JOIN helpdesk.ticket_histories th 
                                    ON t.ticket_number = th.ticket_number
                                LEFT JOIN helpdesk.ticket_srv_time_team_histories tst 
                                    ON t.ticket_number = tst.ticket_number 
                                    AND t.team_id = tst.team_id 
                                    AND tst.srv_time_status != 0 
                                LEFT JOIN (SELECT ticket_number, comments
                                    FROM (SELECT tc.ticket_number, tc.comments, 
                                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                            FROM helpdesk.ticket_comments tc) AS ranked_comments
                                                WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
                                WHERE t.user_id = '$userId'

                                UNION ALL

                                SELECT DISTINCT t.id, t.ticket_number, t.user_id, ucm.client_name, 
                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                    t.business_entity_id, c.company_name, t.team_id, t.sid, 
                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                    t.subcat_id, sub_categories.sub_category_in_english, 
                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                    CASE 
                                        WHEN t.status_id = 1 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                    END AS ticket_age,

                                    CASE 
                                        WHEN fth.fr_response_status = 2 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                            )
                                        ELSE ''
                                    END AS fr_due_time,

                                    CASE 
                                        WHEN tst.srv_time_status = 2 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                            )
                                        ELSE ''
                                    END AS srv_due_time,

                                    u.username AS created_by,
                                    u2.username AS status_update_by,
                                    latest_comments.comments AS last_comment,
                                    NULL AS comment_by_user,
                                    NULL AS comment_date

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
                                LEFT JOIN helpdesk.ticket_fr_time_team_histories fth 
                                    ON t.ticket_number = fth.ticket_number 
                                    AND t.team_id = fth.team_id 
                                    AND fth.fr_response_status != 0
                                LEFT JOIN helpdesk.ticket_histories th 
                                    ON t.ticket_number = th.ticket_number
                                LEFT JOIN helpdesk.ticket_srv_time_team_histories tst 
                                    ON t.ticket_number = tst.ticket_number 
                                    AND t.team_id = tst.team_id 
                                    AND tst.srv_time_status != 0 
                                LEFT JOIN (SELECT ticket_number, comments
                                    FROM (SELECT tc.ticket_number, tc.comments, 
                                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                            FROM helpdesk.ticket_comments tc) AS ranked_comments
                                                WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
                                WHERE t.status_update_by = '$userId'
                                AND t.status_id = 6

                                UNION ALL

                                SELECT DISTINCT t.id, t.ticket_number, t.user_id, ucm.client_name, 
                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                    t.business_entity_id, c.company_name, t.team_id, t.sid, 
                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                    t.subcat_id, sub_categories.sub_category_in_english, 
                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                    CASE 
                                        WHEN t.status_id = 1 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                    END AS ticket_age,

                                    CASE 
                                        WHEN fth.fr_response_status = 2 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                            )
                                        ELSE ''
                                    END AS fr_due_time,

                                    CASE 
                                        WHEN tst.srv_time_status = 2 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                            )
                                        ELSE ''
                                    END AS srv_due_time,

                                    u.username AS created_by,
                                    u2.username AS status_update_by,
                                    NULL AS last_comment,
                                    tc.comments AS comment_by_user,
                                    tc.created_at AS comment_date

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
                                LEFT JOIN helpdesk.ticket_fr_time_team_histories fth 
                                    ON t.ticket_number = fth.ticket_number 
                                    AND t.team_id = fth.team_id 
                                    AND fth.fr_response_status != 0
                                LEFT JOIN helpdesk.ticket_histories th 
                                    ON t.ticket_number = th.ticket_number
                                LEFT JOIN helpdesk.ticket_srv_time_team_histories tst 
                                    ON t.ticket_number = tst.ticket_number 
                                    AND t.team_id = tst.team_id 
                                    AND tst.srv_time_status != 0 
                                JOIN helpdesk.ticket_comments tc 
                                    ON t.ticket_number = tc.ticket_number
                                    AND tc.user_id = '$userId' 

                                UNION ALL

                                SELECT DISTINCT t.id, t.ticket_number, t.user_id, ucm.client_name, 
                                    fth.fr_response_status_name, tst.srv_time_status_name, 
                                    t.business_entity_id, c.company_name, t.team_id, t.sid, 
                                    teams.team_name, t.cat_id, categories.category_in_english, 
                                    t.subcat_id, sub_categories.sub_category_in_english, 
                                    t.status_id, statuses.status_name, t.created_at, t.updated_at,

                                    CASE 
                                        WHEN t.status_id = 1 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
                                                TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
                                                TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
                                            )
                                    END AS ticket_age,

                                    CASE 
                                        WHEN fth.fr_response_status = 2 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, fth.created_at, NOW()), 'd ',
                                                TIMESTAMPDIFF(HOUR, fth.created_at, NOW()) % 24, 'h ',
                                                TIMESTAMPDIFF(MINUTE, fth.created_at, NOW()) % 60, 'm '
                                            )
                                        ELSE ''
                                    END AS fr_due_time,

                                    CASE 
                                        WHEN tst.srv_time_status = 2 THEN 
                                            CONCAT(
                                                TIMESTAMPDIFF(DAY, tst.created_at, NOW()), 'd ',
                                                TIMESTAMPDIFF(HOUR, tst.created_at, NOW()) % 24, 'h ',
                                                TIMESTAMPDIFF(MINUTE, tst.created_at, NOW()) % 60, 'm '
                                            )
                                        ELSE ''
                                    END AS srv_due_time,

                                    u.username AS created_by,
                                    u2.username AS status_update_by,
                                    latest_comments.comments AS last_comment,
                                    NULL AS comment_by_user,
                                    NULL AS comment_date

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
                                LEFT JOIN helpdesk.ticket_fr_time_team_histories fth 
                                    ON t.ticket_number = fth.ticket_number 
                                    AND t.team_id = fth.team_id 
                                    AND fth.fr_response_status != 0
                                LEFT JOIN helpdesk.ticket_histories th 
                                    ON t.ticket_number = th.ticket_number
                                LEFT JOIN helpdesk.ticket_srv_time_team_histories tst 
                                    ON t.ticket_number = tst.ticket_number 
                                    AND t.team_id = tst.team_id 
                                    AND tst.srv_time_status != 0 
                                LEFT JOIN (SELECT ticket_number, comments
                                    FROM (SELECT tc.ticket_number, tc.comments, 
                                        ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
                                            FROM helpdesk.ticket_comments tc) AS ranked_comments
                                                WHERE rn = 1) AS latest_comments ON t.ticket_number = latest_comments.ticket_number
                                WHERE t.ticket_number IN (SELECT ticket_number FROM EscalatedTickets)

                                ORDER BY ticket_number DESC");
        }


        return ApiResponse::success($Details, "Success", 200);
    }






    // private function formatTicketDetails($query)
    // {
    //     $formatted = [];
    //     $previousUpdatedAt = null;
    //     $levelCounter = 1;

    //     foreach ($query as $index => $ticket) {
    //         $timeDifference = '';
    //         if ($previousUpdatedAt) {
    //             $diffInSeconds = strtotime($ticket->updated_at) - strtotime($previousUpdatedAt);
    //             $hours = floor($diffInSeconds / 3600);
    //             $minutes = floor(($diffInSeconds % 3600) / 60);
    //             $seconds = $diffInSeconds % 60;
    //             $timeDifference = sprintf("%02d h:%02d m:%02d s", $hours, $minutes, $seconds);
    //         }

    //         $ticket->time_difference = $timeDifference;
    //         $previousUpdatedAt = $ticket->updated_at;

    //         if ($levelCounter == 1) {
    //             $formatted[$ticket->ticket_number] = [
    //                 'ticket_number' => $ticket->ticket_number,
    //                 'company_name' => $ticket->company_name,
    //                 'client_name' => $ticket->client_name,
    //                 'category_subcategory' => $ticket->category_in_english . ' [' . $ticket->sub_category_in_english . ']',
    //                 'created_at' => $ticket->created_at,
    //                 'comments' => $ticket->comments,
    //                 // 'srv_time_status_name' => $ticket->srv_time_status_name,
    //             ];
    //         }

    //         $formatted[$ticket->ticket_number]['Level' . $levelCounter . '_details'] =
    //             'Created by ' . $ticket->created_by .
    //             ', Assigned to ' . $ticket->team_name .
    //             ', Department ' . $ticket->department_name .
    //             ', Status ' . $ticket->status_name .
    //             ', Updated By ' . $ticket->status_update_by .
    //             ', Agent Team Names: ' . $ticket->agent_team_names .
    //             ', Comments: ' . $ticket->comments .
    //             ', Updated_at ' . $ticket->updated_at .
    //             ', Time_difference ' . $ticket->time_difference .
    //             ', srv_time_status_name ' . $ticket->srv_time_status_name .
    //             ', srv_time_str ' . $ticket->srv_time_str;

    //         $levelCounter++;
    //     }

    //     return $formatted;
    // }

    // public function getTicketDetails(Request $request)
    // {
    //     $startDate = $request->query('startDate');
    //     $endDate = $request->query('endDate');

    //     // return [$startDate,$endDate];

    //     $query = DB::table('helpdesk.ticket_histories as th')
    //         ->distinct()
    //         ->select('th.ticket_number');


    //     if ($startDate && $endDate) {
    //         $query->whereBetween('th.updated_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
    //     }

    //     $ticketNumbers = $query->pluck('ticket_number');

    //     $ticketDetails = [];

    //     foreach ($ticketNumbers as $ticketNumber) {

    //         $ticketQuery = DB::table('helpdesk.ticket_histories as th')
    //             ->join('users as u', 'th.user_id', '=', 'u.id')
    //             ->join('helpdesk.companies as c', 'th.business_entity_id', '=', 'c.id')
    //             ->join('helpdesk.teams as t', 'th.team_id', '=', 't.id')
    //             ->join('helpdesk.user_client_mappings as ucm', 'th.client_id_vendor', '=', 'ucm.client_id')
    //             ->join('helpdesk.categories as cat', 'th.cat_id', '=', 'cat.id')
    //             ->join('helpdesk.sub_categories as scat', 'th.subcat_id', '=', 'scat.id')
    //             ->leftJoin('helpdesk.departments as departs', 't.department_id', '=', 'departs.id')
    //             ->leftJoin('helpdesk.statuses as st', 'st.id', '=', 'th.status_id')
    //             ->leftJoin('users as u2', 'th.status_update_by', '=', 'u2.id')
    //             ->leftJoin('user_team_mappings as utm', 'th.status_update_by', '=', 'utm.user_id')
    //             ->leftJoin('helpdesk.teams as t1', 'utm.team_id', '=', 't1.id')
    //             ->where('th.ticket_number', $ticketNumber)
    //             ->select('th.ticket_number','c.company_name','departs.department_name','t.team_name','ucm.client_name','cat.category_in_english',
    //                 'scat.sub_category_in_english','st.status_name','u.username as created_by','u2.username as status_update_by',
    //                 DB::raw('GROUP_CONCAT(DISTINCT t1.team_name) as agent_team_names'),
    //                 'th.updated_at','th.created_at'
    //             )
    //             ->groupBy('th.ticket_number', 'c.company_name', 'departs.department_name', 't.team_name', 'ucm.client_name', 
    //                 'cat.category_in_english','scat.sub_category_in_english', 'st.status_name', 'u.username', 'u2.username', 
    //                 'th.updated_at', 'th.created_at'
    //             )
    //             ->orderBy('th.ticket_number', 'asc')
    //             ->orderBy('th.updated_at', 'asc')
    //             ->get();

    //         // Step 4: Format the result
    //         $formattedDetails = $this->formatTicketDetails($ticketQuery);

    //         // Step 5: Store the formatted details in an array for each ticket
    //         $ticketDetails[$ticketNumber] = $formattedDetails;
    //     }

    //     return response()->json($ticketDetails);
    // }


    // private function formatTicketDetails($query)
    // {
    //     $formatted = [];
    //     $previousUpdatedAt = null;
    //     $levelCounter = 1;

    //     foreach ($query as $index => $ticket) {
    //         $timeDifference = '';
    //         if ($previousUpdatedAt) {

    //             $diffInSeconds = strtotime($ticket->updated_at) - strtotime($previousUpdatedAt);
    //             $hours = floor($diffInSeconds / 3600);
    //             $minutes = floor(($diffInSeconds % 3600) / 60);
    //             $seconds = $diffInSeconds % 60;


    //             $timeDifference = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    //         }


    //         $ticket->time_difference = $timeDifference;


    //         $previousUpdatedAt = $ticket->updated_at;

    //         // Format the ticket details for level 1
    //         if ($levelCounter == 1) {
    //             $formatted[$ticket->ticket_number] = [
    //                 'ticket_number' => $ticket->ticket_number,
    //                 'company_name' => $ticket->company_name,
    //                 'client_name' => $ticket->client_name,
    //                 'category_subcategory' => $ticket->category_in_english . ' [' . $ticket->sub_category_in_english . ']',
    //                 'created_at' => $ticket->updated_at,
    //             ];
    //         }

    //         // Add level-specific details
    //         $formatted[$ticket->ticket_number]['Level' . $levelCounter . '_details'] = 'Created by ' . $ticket->created_by . 
    //             ', Assigned to ' . $ticket->team_name . ', Department ' . $ticket->department_name . 
    //             ', Status ' . $ticket->status_name . ', Updated By ' . $ticket->status_update_by . 
    //             ', Agent Team Names: ' . $ticket->agent_team_names . ', Updated_at ' . $ticket->updated_at . 
    //             ', Time_difference ' . $ticket->time_difference;

    //         // Increment level counter for the next entry
    //         $levelCounter++;
    //     }

    //     return $formatted;
    // }



    // public function ticketHistory()
    // {
    //     try {
    //         $ticketLifeCycle = DB::select("SELECT 
    //                 th.ticket_number,c.company_name, divs.division_name, departs.department_name, t.team_name, sc.source_name, 
    //                 ucm.client_name,cat.category_in_english, scat.sub_category_in_english, st.status_name, p.priority_name, 
    //                     th.attached_filename, th.ref_ticket_no, tsh.srv_time_status_name,th.created_at, th.updated_at
    //                     FROM helpdesk.ticket_histories th
    //             JOIN helpdesk.companies c ON th.business_entity_id = c.id
    //             JOIN helpdesk.teams t ON th.team_id = t.id
    //             JOIN helpdesk.user_client_mappings ucm ON th.client_id_vendor = ucm.client_id
    //             JOIN helpdesk.categories cat ON th.cat_id = cat.id
    //             LEFT JOIN helpdesk.sources sc ON th.source_id = sc.id
    //             LEFT JOIN helpdesk.divisions divs ON t.division_id = divs.id
    //             LEFT JOIN helpdesk.departments departs ON t.department_id = departs.id
    //             LEFT JOIN helpdesk.statuses st ON st.id = th.status_id
    //             LEFT JOIN helpdesk.sub_categories scat ON cat.id = scat.category_id
    //             LEFT JOIN helpdesk.priorities p ON th.priority_name = p.id
    //             LEFT JOIN helpdesk.ticket_srv_time_team_histories tsh ON th.ticket_number = tsh.ticket_number
    //             WHERE th.ticket_number = 1 AND th.created_at BETWEEN '2024-11-20' AND '2024-11-22'
    //             AND c.id = 1
    //             ORDER BY th.ticket_number");
    //         return ApiResponse::success($ticketLifeCycle, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    // public function getNewTicketReports(Request $request)
    // {

    //     try {

    //     $startDate = !empty($request->fromDate) ? (new DateTime($request->fromDate))->format('Y-m-d') : "";
    //     $endDate = !empty($request->toDate) ? (new DateTime($request->toDate))->format('Y-m-d') : "";
    //     $businessEntityId = $request->businessEntity;


    //         $ticketQuery = DB::table(DB::raw("SELECT DISTINCT t.ticket_number, co.company_name,ucm.client_name,c.category_in_english,sc.sub_category_in_english,
    //                         CASE 
    //                             WHEN t.status_id = 6 THEN 'Close'
    //                             ELSE 'Open'
    //                         END AS ticket_status,
    //                             CASE 
    //                         WHEN t.status_id != 6 THEN 
    //                                 CONCAT(
    //                                         TIMESTAMPDIFF(DAY, t.created_at, NOW()), 'd ', 
    //                                         TIMESTAMPDIFF(HOUR, t.created_at, NOW()) % 24, 'h ', 
    //                                         TIMESTAMPDIFF(MINUTE, t.created_at, NOW()) % 60, 'm '
    //                                 )
    //                         WHEN t.status_id = 6 THEN 
    //                                 CONCAT(
    //                                         TIMESTAMPDIFF(DAY, t.created_at, t.updated_at), 'd ', 
    //                                         TIMESTAMPDIFF(HOUR, t.created_at, t.updated_at) % 24, 'h ', 
    //                                         TIMESTAMPDIFF(MINUTE, t.created_at, t.updated_at) % 60, 'm '
    //                                 )
    //                     END AS ticket_age
    //                             ,b.branch_name, bela.name AS Element_List_A, belb.name AS Element_List_B
    //                             ,u.username AS created_by, te2.team_name AS open_by_team, t.created_at AS ticket_create_time,
    //                             u2.username AS status_update_by, te.team_name AS updated_by_team, t.updated_at AS ticket_update_time,
    //                             latest_comments.comments AS last_comment
    //                             ,latest_comments.username AS last_comment_username
    //                             ,latest_comments.team_name AS last_comment_team_name
    //                             ,latest_comments.created_at AS last_comment_created_at
    //                             ,tco.comments AS RCA_Comments
    //                             ,uc.username AS RCA_UserName
    //                             ,tec.team_name AS RCA_team_name
    //                             ,tec.created_at AS RCA_create_time
    //                             , COALESCE(tesc.team_escalate_count, 0) AS team_escalate_count

    //                     FROM helpdesk.tickets t
    //                         JOIN helpdesk.teams te ON t.team_id = te.id
    //                         JOIN helpdesk.categories c ON t.cat_id = c.id
    //                         JOIN helpdesk.sub_categories sc ON t.subcat_id = sc.id
    //                         JOIN helpdesk.companies co ON t.business_entity_id = co.id
    //                     LEFT JOIN helpdesk.user_team_mappings utm ON t.user_id = utm.user_id
    //                         LEFT JOIN helpdesk.teams te2 ON utm.team_id = te2.id
    //                         LEFT JOIN helpdesk.user_client_mappings ucm ON t.client_id_helpdesk = ucm.user_id
    //                         LEFT JOIN helpdesk.branches b ON t.branch_id = b.vendor_client_id
    //                         LEFT JOIN helpdesk.ticket_backbones tb ON t.ticket_number = tb.ticket_number
    //                         LEFT JOIN helpdesk.backbone_element_lists bela ON tb.backbone_element_list_id_a_end = bela.id
    //                         LEFT JOIN helpdesk.backbone_element_lists belb ON tb.backbone_element_list_id_b_end = belb.id
    //                         LEFT JOIN users u ON t.user_id = u.id
    //                     LEFT JOIN users u2 ON t.status_update_by = u2.id

    //                         LEFT JOIN (
    //                                 SELECT ticket_number, comments,username,team_name,created_at
    //                                 FROM (
    //                                     SELECT tc.ticket_number, tc.comments, u.username, te.team_name,tc.created_at,
    //                                                 ROW_NUMBER() OVER (PARTITION BY tc.ticket_number ORDER BY tc.created_at DESC) AS rn
    //                                         FROM helpdesk.ticket_comments tc
    //                                         JOIN helpdesk.users u ON tc.user_id = u.id
    //                                         JOIN helpdesk.teams te ON tc.team_id = te.id
    //                                 ) AS ranked_comments
    //                                 WHERE rn = 1
    //                         ) AS latest_comments 
    //                         ON t.ticket_number = latest_comments.ticket_number
    //                         LEFT JOIN ticket_comments tco ON t.ticket_number = tco.ticket_number
    //                         AND tco.is_rca = 1
    //                         LEFT JOIN helpdesk.users uc ON tco.user_id = uc.id
    //                         LEFT JOIN helpdesk.teams tec ON tco.team_id = tec.id
    //                         LEFT JOIN (
    //                         SELECT ticket_number, COUNT(*) AS team_escalate_count
    //                         FROM (
    //                             SELECT ticket_number, team_id,
    //                                 LAG(team_id) OVER (PARTITION BY ticket_number ORDER BY id) AS prev_team_id
    //                             FROM helpdesk.ticket_histories
    //                         ) AS subquery
    //                         WHERE team_id <> prev_team_id
    //                         GROUP BY ticket_number
    //                     ) AS tesc ON t.ticket_number = tesc.ticket_number
    //                     WHERE f.created_at >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH)"));

    //                 return ApiResponse::success($ticketQuery, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }
}
