<?php

namespace App\Http\Controllers\v1\Settings;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponse;
use App\Models\Company;
use App\Models\TeamSupervisor;
use App\Models\FirstResConfig;
use App\Models\TeamConfig;
use Carbon\Carbon;

class TeamController extends Controller
{
//     public function index()
//     {
//         try {

//             $resources = DB::select("SELECT 
//     t.id,
//     t.team_name,
//     t.group_email,
//     ts.agent_id,
//     dept.department_name,
//     d.division_name,
//     t.created_at,
//     ts.created_at AS supervisor_created_at
// FROM 
//     helpdesk.teams t
// LEFT JOIN 
//     helpdesk.team_supervisors ts ON t.id = ts.team_id
// LEFT JOIN 
//     helpdesk.departments dept ON t.department_id = dept.id
// LEFT JOIN 
//     helpdesk.divisions d ON t.division_id = d.id;
// ");


//             return ApiResponse::success($resources, "Success", 200);
//         } catch (\Exception $e) {
//             return ApiResponse::error($e->getMessage(), "Error", 500);
//         }
//     }


// public function index()
// {
//     try {

//         // return 'hi';

//         $resources = DB::table('teams as t')
//             ->leftJoin('team_supervisors as ts', 't.id', '=', 'ts.team_id')
//             ->leftJoin('users as u', 'u.id', '=', 'ts.agent_id')
//             ->leftJoin('departments as dept', 't.department_id', '=', 'dept.id')
//             ->leftJoin('divisions as d', 't.division_id', '=', 'd.id')
//             ->leftJoin('first_res_configs as frc', 'frc.team_id', '=', 't.id')
//             ->select(
//                 't.id',
//                 't.team_name',
//                 't.group_email',
//                 't.additional_email',
//                 't.department_id',
//                  'dept.department_name',
//                 't.division_id',
//                 'd.division_name',
//                 't.idle_start_hr',
//                 't.idle_start_min',
//                 't.idle_end_hr',
//                 't.idle_end_min',
//                 't.idle_start_end_diff_min',
//                 't.created_at',

//                 // 🔥 comma separated supervisors
//                 DB::raw("GROUP_CONCAT(DISTINCT u.fullname ORDER BY u.fullname SEPARATOR ', ') as supervisors"),

//                 // first response config
//                 'frc.duration_min as first_response_duration',
//                 'frc.first_response_status',
//                 'frc.escalation_status'
//             )
//             ->groupBy(
//                 't.id',
//                 't.team_name',
//                 't.group_email',
//                 'dept.department_name',
//                 'd.division_name',
//                 't.created_at',
//                 'frc.duration_min',
//                 'frc.first_response_status',
//                 'frc.escalation_status'
//             )
//             ->orderBy('t.id', 'desc')
//             ->get();

//         return ApiResponse::success($resources, 'Success', 200);

//     } catch (\Throwable $e) {
//         return ApiResponse::error($e->getMessage(), 'Error', 500);
//     }
// }


    public function index()
    {
        try {

            $resources = DB::table('teams as t')
                ->leftJoin('team_supervisors as ts', 't.id', '=', 'ts.team_id')
                ->leftJoin('users as u', 'u.id', '=', 'ts.agent_id')
                ->leftJoin('user_profiles as up', 'up.user_id', '=', 'u.id')
                ->leftJoin('departments as dept', 't.department_id', '=', 'dept.id')
                ->leftJoin('divisions as d', 't.division_id', '=', 'd.id')
                ->leftJoin('first_res_configs as frc', 'frc.team_id', '=', 't.id')
                ->select(
                    't.id',
                    't.team_name',
                    't.group_email',
                    't.additional_email',
                    't.department_id',
                    'dept.department_name',
                    't.division_id',
                    'd.division_name',
                    't.idle_start_hr',
                    't.idle_start_min',
                    't.idle_end_hr',
                    't.idle_end_min',
                    't.idle_start_end_diff_min',
                    't.created_at',

                    
                    DB::raw(
                        "GROUP_CONCAT(DISTINCT up.fullname ORDER BY up.fullname SEPARATOR ', ') as supervisors"
                    ),

                    
                    'frc.duration_min as first_response_duration',
                    'frc.first_response_status',
                    'frc.escalation_status'
                )
                ->groupBy(
                    't.id',
                    't.team_name',
                    't.group_email',
                    't.additional_email',
                    't.department_id',
                    'dept.department_name',
                    't.division_id',
                    'd.division_name',
                    't.idle_start_hr',
                    't.idle_start_min',
                    't.idle_end_hr',
                    't.idle_end_min',
                    't.idle_start_end_diff_min',
                    't.created_at',
                    'frc.duration_min',
                    'frc.first_response_status',
                    'frc.escalation_status'
                )
                ->orderBy('t.id', 'desc')
                ->get();

            return ApiResponse::success($resources, 'Success', 200);

        } catch (\Throwable $e) {
            return ApiResponse::error($e->getMessage(), 'Error', 500);
        }
    }


    public function all()
    {
        try {
            $resources = Team::orderBy('team_name', 'asc')->get();
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getTeamBySubcategory($id)
    {
        try {
            $resources = DB::select("SELECT st.team_id, t.team_name 
            FROM helpdesk.sub_category_teams st , helpdesk.teams t
            where st.team_id = t.id
            and st.sub_category_id = '$id' ");
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

public function storeOrUpdateTeamConfiguration(Request $request)
{
    try {

        $validated = $request->validate([
            'teamIds' => 'required',
            'ticketSlaHold' => 'nullable|array',
            'ticketReopen' => 'nullable|array',
            'ticketMerge' => 'nullable|array',
            'ticketEscalate' => 'nullable|array',
            'additionalEmailcc' => 'nullable|array',
        ]);

        $teamId = (int) $validated['teamIds'];

        $normalize = function ($value) {
            if (!is_array($value) || empty($value)) {
                return [];
            }
            return in_array('all', $value, true) ? ['all'] : array_values($value);
        };

        $config = TeamConfig::updateOrCreate(
            ['team_id' => $teamId],
            [
                'sla_hold_agents'   => $normalize($validated['ticketSlaHold'] ?? []),
                'reopen_agents'     => $normalize($validated['ticketReopen'] ?? []),
                'merge_agents'      => $normalize($validated['ticketMerge'] ?? []),
                'escalate_agents'   => $normalize($validated['ticketEscalate'] ?? []),
                'additional_emails' => $validated['additionalEmailcc'] ?? [],
            ]
        );

        return ApiResponse::success($config, 'Success', 200);

    } catch (\Throwable $e) {
        return ApiResponse::error($e->getMessage(), 'Error', 500);
    }
}



 public function getTeamConfig($id)
    {
        try {
            $team = TeamConfig::findOrFail($id);
            
            return ApiResponse::success($team, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
   

    public function store(Request $request)
{
    DB::beginTransaction();

    try {



        /* =========================
         | 2️⃣ Idle time calculation (midnight safe)
         ========================= */
        $start = Carbon::createFromTime(
            $request->idleStartHours,
            $request->idleStartMinutes
        );

        $end = Carbon::createFromTime(
            $request->idleEndHours,
            $request->idleEndMinutes
        );

        if ($end->lt($start)) {
            $end->addDay(); // midnight cross
        }

        $idleDiffMin = $end->diffInMinutes($start);

        /* =========================
         | 3️⃣ Insert Team
         ========================= */
        $team = Team::create([
            'team_name'                  => $request->teamName,
            'group_email'                => $request->groupEmail,
            'additional_email'           => $request->additionalEmailcc,
            'department_id'              => $request->departmentId,
            'division_id'                => $request->divisionId,

            'idle_start_hr'              => $request->idleStartHours,
            'idle_start_min'             => $request->idleStartMinutes,
            'idle_end_hr'                => $request->idleEndHours,
            'idle_end_min'               => $request->idleEndMinutes,
            'idle_start_end_diff_min'    => $idleDiffMin,
        ]);

        /* =========================
         | 4️⃣ Insert First Response Config (team-wise)
         ========================= */
        $firstResConfig = FirstResConfig::create([
            'team_id'               => $team->id,
            'duration_min'          => $request->first_response_duration,
            'first_response_status' => $request->first_res_status,
            'escalation_status'     => $request->escalation_status,
        ]);

        /* =========================
         | 5️⃣ Insert Supervisors (bulk, scalable)
         ========================= */
        if (!empty($request->supervisorName)) {
            $rows = collect($request->supervisorName)->map(function ($agentId) use ($team) {
                return [
                    'agent_id'   => $agentId,
                    'team_id'    => $team->id,
                    'created_at'=> now(),
                    'updated_at'=> now(),
                ];
            })->toArray();

            TeamSupervisor::insert($rows);
        }

        DB::commit();

        return ApiResponse::success([
            'team' => $team,
            'first_res_config' => $firstResConfig,
        ], 'Successfully inserted', 201);

    } catch (\Throwable $e) {
        DB::rollBack();

        return ApiResponse::error(
            $e->getMessage(),
            'Insert failed',
            500
        );
    }
}
    public function show($id)
    {
        try {
            $team = Team::findOrFail($id);
            // $teamSupervisor = TeamSupervisor::where('team_id', $id)->get();
            $teamSupervisor =  DB::select("SELECT 
    ts.agent_id,
    up.fullname,
    u.username
FROM helpdesk.team_supervisors ts
JOIN helpdesk.users u 
    ON ts.agent_id = u.id
JOIN user_profiles up 
    ON up.user_id = u.id
WHERE ts.team_id = '$id' ");

                /* =========================
         | 3️⃣ First Response Config (team wise)
         ========================= */
        $firstResConfig = FirstResConfig::where('team_id', $id)
            ->select(
                'id',
                'duration_min',
                'first_response_status',
                'escalation_status'
            )
            ->first();

            $mergedData = [
                'team' => $team,
                'team_supervisor' => $teamSupervisor,
                'first_response_config' => $firstResConfig,
            ];
            return ApiResponse::success($mergedData, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    // public function update(Request $request, $id)
    // {

    //     DB::beginTransaction();
    //     try {
    //         $idleStartHours = $request->idleStartHours;
    //         $idleStartMinutes = $request->idleStartMinutes;
    //         $idleStartHoursMinutes = "{$idleStartHours}h {$idleStartMinutes}m";

    //         $idleEndHours = $request->idleEndHours;
    //         $idleEndMinutes = $request->idleEndMinutes;
    //         $idleEndHoursMinutes = "{$idleEndHours}h {$idleEndMinutes}m";


    //         // $diff = end - start = (end hr - start hr)*60 + (end min - start min)

    //         $start = Carbon::createFromTime($idleStartHours, $idleStartMinutes); // 1 hour 20 minutes
    //         $end = Carbon::createFromTime($idleEndHours, $idleEndMinutes); // 4 hours 20 minutes
    //         if ($end->lt($start)) {
    //             $end->addDay();
    //         }
    //         $diffInMinutes = $end->diffInMinutes($start);

    //         $team = Team::findOrFail($id);
    //         $team->update([
    //             'team_name' => $request->teamName,
    //             'group_email' => $request->groupEmail,
    //             'department_id' => $request->departmentId,
    //             'division_id' => $request->divisionId,
    //             'idle_start_hr' => $idleStartHours,
    //             'idle_start_min' => $idleStartMinutes,
    //             'idle_start_time_str' => $idleStartHoursMinutes,
    //             'idle_end_hr' => $idleEndHours,
    //             'idle_end_min' => $idleEndMinutes,
    //             'idle_end_time_str' => $idleEndHoursMinutes,
    //             'idle_start_end_diff_min' => $diffInMinutes,
    //         ]);

    //         TeamSupervisor::where('team_id', $team->id)->delete();

    //         $supervisors = $request->supervisorName;
    //         foreach ($supervisors as $supervisorName) {
    //             TeamSupervisor::updateOrCreate(
    //                 ['team_id' => $team->id, 'agent_id' => $supervisorName],
    //                 ['updated_at' => now()]
    //             );
    //         }

    //         DB::commit();
    //         return ApiResponse::success($team, "Successfully Updated", 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }
public function update(Request $request, $id)
{
    DB::beginTransaction();

    try {

        $team = Team::findOrFail($id);

        /* =========================
         | 3️⃣ Idle time calculation (midnight safe)
         ========================= */
        $start = Carbon::createFromTime(
            $request->idleStartHours,
            $request->idleStartMinutes
        );

        $end = Carbon::createFromTime(
            $request->idleEndHours,
            $request->idleEndMinutes
        );

        if ($end->lt($start)) {
            $end->addDay();
        }

        $idleDiffMin = $end->diffInMinutes($start);

        /* =========================
         | 4️⃣ Update Team
         ========================= */
        $team->update([
            'team_name'               => $request->teamName,
            'group_email'             => $request->groupEmail,
            'additional_email'        => $request->additionalEmailcc,
            'department_id'           => $request->departmentId,
            'division_id'             => $request->divisionId,

            'idle_start_hr'           => $request->idleStartHours,
            'idle_start_min'          => $request->idleStartMinutes,
            'idle_end_hr'             => $request->idleEndHours,
            'idle_end_min'            => $request->idleEndMinutes,
            'idle_start_end_diff_min' => $idleDiffMin,
        ]);

        /* =========================
         | 5️⃣ Update First Response Config
         ========================= */
        FirstResConfig::updateOrCreate(
            ['team_id' => $team->id],
            [
                'duration_min'          => $request->first_response_duration,
                'first_response_status' => $request->first_res_status,
                'escalation_status'     => $request->escalation_status,
            ]
        );

        /* =========================
         | 6️⃣ Update Supervisors (sync)
         ========================= */
        if ($request->has('supervisorName')) {
            // remove old
            TeamSupervisor::where('team_id', $team->id)->delete();

            // insert new
            if (!empty($request->supervisorName)) {
                $rows = collect($request->supervisorName)->map(function ($agentId) use ($team) {
                    return [
                        'agent_id'   => $agentId,
                        'team_id'    => $team->id,
                        'created_at'=> now(),
                        'updated_at'=> now(),
                    ];
                })->toArray();

                TeamSupervisor::insert($rows);
            }
        }

        DB::commit();

        return ApiResponse::success([
            'team' => $team->fresh(),
        ], 'Successfully updated', 200);

    } catch (\Throwable $e) {
        DB::rollBack();

        return ApiResponse::error(
            $e->getMessage(),
            'Update failed',
            500
        );
    }
}

    public function getTeamByDefaultEntity($id)
    {
        try {
            $team = Company::findOrFail($id);

            return ApiResponse::success($team, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $resources = Team::findOrFail($id);
            $resources->delete();
            DB::commit();
            return ApiResponse::success(null, "Successfully Deleted", 204);
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::error($th->getMessage(), "Error", 500);
        }
    }
}
