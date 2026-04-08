<?php

namespace App\Http\Controllers\v1\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponse;
use App\Helpers\Calculation;
use App\Models\EscalateFrReAgentClient;
use App\Models\EscalateFrResAgentSubcategory;
use App\Models\EscalateFrResClient;
use App\Models\EscalateFrResSubcategory;
use App\Models\EscalateSrvTimeAgentClient;
use App\Models\EscalateSrvTimeAgentSubcategory;
use App\Models\EscalateSrvTimeClient;
use App\Models\EscalateSrvTimeSubcategory;
use App\Models\ServiceLevelAgreement;
use App\Models\SlaClient;
use App\Models\SlaSubcategory;
use App\Models\SubCategoryTeam;

class ServiceLevelAgreementController extends Controller
{
    /**
     * @OA\Tag(
     *     name="Service Level Agreement",
     *     description="Service Level Agreement related endpoints"
     * )
     */


    /**
     * @OA\Get(
     *     path="/api/v1/settings/email/notification/show",
     *     tags={"Service Level Agreement"},
     *     summary="Get all notification",
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *         
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="message", type="string")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $resources = DB::select("SELECT ss.id,co.company_name,t.team_name, t.id teamid,c.category_in_english, sc.sub_category_in_english
                , ss.fr_res_time_str, ss.srv_time_str, ss.esc_status,ss.status, ss.created_at
                FROM helpdesk.categories c, helpdesk.sub_categories sc,helpdesk.sla_subcategories ss, helpdesk.teams t
                ,helpdesk.companies co
                WHERE c.id = sc.category_id AND ss.subcat_id = sc.id AND t.id = ss.team_id 
                AND co.id = sc.company_id
                ORDER BY co.company_name");

            // return 'hi';
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    public function edit($id)
    {

        try {

            $slaSubcategory = SlaSubcategory::find($id);

            if (!$slaSubcategory) {
                return ApiResponse::error("Error", "SLA Subcategory not found", 500);
            }

            return ApiResponse::success($slaSubcategory, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            
            $slaSubcategory = SlaSubcategory::find($id);

            if (!$slaSubcategory) {
                return ApiResponse::error("SLA Subcategory not found", "Error", 404);
            }

            $validatedData = $request->validate([
                'defaultFirstResponseTimeDay' => 'nullable|integer',
                'defaultFirstResponseTimeHrs' => 'nullable|integer',
                'defaultFirstResponseTimeMins' => 'nullable|integer',
                'defaultServiceTimeDay' => 'nullable|integer',
                'defaultServiceTimeHrs' => 'nullable|integer',
                'defaultServiceTimeMins' => 'nullable|integer',
                'defaultEscalate' => 'nullable|boolean',
                'defaultStatus' => 'nullable|boolean',
            ]);

            // return $validatedData;

            // Use existing values if the frontend sends empty strings
            $frDay = $validatedData['defaultFirstResponseTimeDay'] !== ""
                ? $validatedData['defaultFirstResponseTimeDay']
                : $slaSubcategory->fr_res_day;
            $frHrs = $validatedData['defaultFirstResponseTimeHrs'] !== ""
                ? $validatedData['defaultFirstResponseTimeHrs']
                : $slaSubcategory->fr_res_hr;
            $frMins = $validatedData['defaultFirstResponseTimeMins'] !== ""
                ? $validatedData['defaultFirstResponseTimeMins']
                : $slaSubcategory->fr_res_min;

            $srvDay = $validatedData['defaultServiceTimeDay'] !== ""
                ? $validatedData['defaultServiceTimeDay']
                : $slaSubcategory->srv_day;
            $srvHrs = $validatedData['defaultServiceTimeHrs'] !== ""
                ? $validatedData['defaultServiceTimeHrs']
                : $slaSubcategory->srv_hr;
            $srvMins = $validatedData['defaultServiceTimeMins'] !== ""
                ? $validatedData['defaultServiceTimeMins']
                : $slaSubcategory->srv_min;

            $escalate = isset($validatedData['defaultEscalate'])
                ? $validatedData['defaultEscalate']
                : $slaSubcategory->esc_status;

            $status = isset($validatedData['defaultStatus'])
                ? $validatedData['defaultStatus']
                : $slaSubcategory->status;

            $fristResponseTimeInTotalMinutes = Calculation::convertToMinutes($frDay, $frHrs, $frMins);
            $fristResponseTimeInString = Calculation::convertToString($frDay, $frHrs, $frMins);

            $serviceTimeInTotalMinutes = Calculation::convertToMinutes($srvDay, $srvHrs, $srvMins);
            $serviceTimeInString = Calculation::convertToString($srvDay, $srvHrs, $srvMins);

            $slaSubcategory->update([
                'fr_res_day' => $frDay,
                'fr_res_hr' => $frHrs,
                'fr_res_min' => $frMins,
                'fr_res_time_min' => $fristResponseTimeInTotalMinutes,
                'fr_res_time_str' => $fristResponseTimeInString,
                'srv_day' => $srvDay,
                'srv_hr' => $srvHrs,
                'srv_min' => $srvMins,
                'srv_time_min' => $serviceTimeInTotalMinutes,
                'srv_time_str' => $serviceTimeInString,
                'esc_status' => $escalate,
                'status' => $status,
            ]);

            return ApiResponse::success($slaSubcategory, "SLA updated successfully", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    public function showEscalation($teamId)
    {
        try {
            $fr_response = DB::select("SELECT t.team_name, es.level_id, es.notification_min, es.notification_str, et.id email_id, et.template_name
            ,es.send_email_status, GROUP_CONCAT(DISTINCT u.id) AS userid, GROUP_CONCAT(DISTINCT u.username) AS usernames,GROUP_CONCAT(DISTINCT u.email_primary) AS emails
                                    FROM helpdesk.escalate_fr_res_subcategories es
                                    JOIN teams t ON es.team_id = t.id
                                    JOIN email_templates et ON es.email_template_id = et.id
                                    JOIN escalate_fr_res_agent_subcategories efs ON efs.team_id = t.id AND efs.level_id = es.level_id
                                    JOIN users u ON u.id = efs.agent_id
                                    WHERE t.id = $teamId
                                    AND es.is_deleted = 0
                                    GROUP BY t.team_name, es.level_id, es.notification_min, es.notification_str, et.id, et.template_name,es.send_email_status");

            $srv_time = DB::select("SELECT t.team_name, ess.level_id, ess.notification_min, ess.notification_str, et.id email_id, et.template_name
            ,ess.send_email_status, GROUP_CONCAT(DISTINCT u.id) AS userid, GROUP_CONCAT(DISTINCT u.username) AS usernames,GROUP_CONCAT(DISTINCT u.email_primary) AS emails
                                    FROM helpdesk.escalate_srv_time_subcategories ess
                                    JOIN teams t ON ess.team_id = t.id
                                    JOIN email_templates et ON ess.email_template_id = et.id
                                    JOIN escalate_srv_time_agent_subcategories esa ON esa.team_id = t.id AND esa.level_id = ess.level_id
                                    JOIN users u ON u.id = esa.agent_id
                                    WHERE t.id = $teamId
                                    AND ess.is_deleted = 0
                                    GROUP BY t.team_name, ess.level_id, ess.notification_min, ess.notification_str, et.id, et.template_name,ess.send_email_status");

            $resources = [
                'first_response' => $fr_response,
                'service_time' => $srv_time
            ];

            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function editEscalation($teamId, $levelId)
    {
        try {
            $fr_response = DB::selectOne("SELECT  t.team_name, es.level_id, es.notification_min, es.notification_str, et.id email_id, et.template_name, 
                            es.send_email_status, GROUP_CONCAT(DISTINCT u.username) AS usernames, GROUP_CONCAT(DISTINCT u.id) AS userid, GROUP_CONCAT(DISTINCT u.email_primary) AS emails
                                        FROM helpdesk.escalate_fr_res_subcategories es
                                        JOIN teams t ON es.team_id = t.id
                                        JOIN email_templates et ON es.email_template_id = et.id
                                        JOIN escalate_fr_res_agent_subcategories efs ON efs.team_id = t.id AND efs.level_id = es.level_id
                                        JOIN users u ON u.id = efs.agent_id
                                        WHERE t.id = ? 
                                        AND es.level_id = ? 
                                        AND es.is_deleted = 0
                                        GROUP BY t.team_name, es.level_id, es.notification_min, es.notification_str, et.id, et.template_name, es.send_email_status", 
                                        [$teamId, $levelId]);

            $srv_time = DB::selectOne("SELECT t.team_name, ess.level_id, ess.notification_min, ess.notification_str, et.id email_id, et.template_name, 
                            ess.send_email_status, GROUP_CONCAT(DISTINCT u.id) AS userid, GROUP_CONCAT(DISTINCT u.username) AS usernames, GROUP_CONCAT(DISTINCT u.email_primary) AS emails
                                    FROM helpdesk.escalate_srv_time_subcategories ess
                                    JOIN teams t ON ess.team_id = t.id
                                    JOIN email_templates et ON ess.email_template_id = et.id
                                    JOIN escalate_srv_time_agent_subcategories esa ON esa.team_id = t.id AND esa.level_id = ess.level_id
                                    JOIN users u ON u.id = esa.agent_id
                                    WHERE t.id = ? 
                                    AND ess.level_id = ? 
                                    AND ess.is_deleted = 0
                                    GROUP BY t.team_name, ess.level_id, ess.notification_min, ess.notification_str, et.id, et.template_name, ess.send_email_status", 
                                    [$teamId, $levelId]);

            $resources = [
                'first_response' => $fr_response,
                'service_time' => $srv_time
            ];

            if (!$fr_response && !$srv_time) {
                return ApiResponse::error("Escalation not found", "Not Found", 404);
            }

            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    // public function updateEscalation(Request $request, $teamId, $levelId)
    // {
    //     try {
    //         $validated = $request->validate([
    //             'first_response' => 'nullable|array',
    //             'service_time' => 'nullable|array',
    //         ]);

    //         if (!empty($validated['first_response'])) {
    //             DB::update("UPDATE helpdesk.escalate_fr_res_subcategories 
    //                         SET notification_min = ?, 
    //                             notification_str = ?, 
    //                             email_template_id = COALESCE((SELECT id FROM email_templates WHERE template_name = ?), email_template_id),
    //                             send_email_status = ? 
    //                         WHERE team_id = ? 
    //                         AND level_id = ? 
    //                         AND is_deleted = 0", 
    //                         [
    //                             $validated['first_response']['notification_min'], 
    //                             $validated['first_response']['notification_str'], 
    //                             $validated['first_response']['template_name'], 
    //                             $validated['first_response']['send_email_status'], 
    //                             $teamId, 
    //                             $levelId
    //                         ]);
    //         }

    //         if (!empty($validated['service_time'])) {
    //             DB::update("UPDATE helpdesk.escalate_srv_time_subcategories 
    //                         SET notification_min = ?, 
    //                             notification_str = ?, 
    //                             email_template_id = COALESCE((SELECT id FROM email_templates WHERE template_name = ?), email_template_id),
    //                             send_email_status = ? 
    //                         WHERE team_id = ? 
    //                         AND level_id = ? 
    //                         AND is_deleted = 0", 
    //                         [
    //                             $validated['service_time']['notification_min'], 
    //                             $validated['service_time']['notification_str'], 
    //                             $validated['service_time']['template_name'], 
    //                             $validated['service_time']['send_email_status'], 
    //                             $teamId, 
    //                             $levelId
    //                         ]);
    //         }

    //         return ApiResponse::success(null, "Escalation updated successfully.", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    public function updateEscalation(Request $request, $teamId, $levelId)
    {
        try {
            $validated = $request->validate([
                'first_response' => 'nullable|array',
                'service_time' => 'nullable|array',
            ]);

            DB::transaction(function () use ($validated, $teamId, $levelId) {
            
                if (!empty($validated['first_response'])) {
                    DB::update("UPDATE helpdesk.escalate_fr_res_subcategories 
                    SET notification_min = (
                        SELECT DISTINCT(fr_res_time_min) 
                        FROM sla_subcategories 
                        WHERE team_id = ? 
                    ) + ?, 
                    notification_str = ?, 
                    email_template_id = COALESCE((SELECT id FROM email_templates WHERE template_name = ?), email_template_id),
                    send_email_status = ? 
                    WHERE team_id = ? 
                    AND level_id = ? 
                    AND is_deleted = 0", 
                    [
                        $teamId, 
                        $validated['first_response']['notification_min'], // Minutes to add
                        $validated['first_response']['notification_str'], 
                        $validated['first_response']['template_name'], 
                        $validated['first_response']['send_email_status'], 
                        $teamId, 
                        $levelId
                    ]);

                    
                    if (isset($validated['first_response']['usernames']) || isset($validated['first_response']['emails'])) {
                        DB::table('escalate_fr_res_agent_subcategories')
                            ->where('team_id', $teamId)
                            ->where('level_id', $levelId)
                            ->delete();

                        foreach ($validated['first_response']['usernames'] as $username) {
                            $userId = DB::table('users')->where('username', $username)->value('id');
                            if ($userId) {
                                DB::table('escalate_fr_res_agent_subcategories')->insert([
                                    'team_id' => $teamId,
                                    'level_id' => $levelId,
                                    'agent_id' => $userId,
                                ]);
                            }
                        }
                    }
                }

                if (!empty($validated['service_time'])) {
                    DB::update("UPDATE helpdesk.escalate_srv_time_subcategories 
                    SET notification_min = (
                        SELECT DISTINCT(srv_time_min) 
                        FROM sla_subcategories 
                        WHERE team_id = ? 
                    ) + ?, 
                    notification_str = ?, 
                    email_template_id = COALESCE((SELECT id FROM email_templates WHERE template_name = ?), email_template_id),
                    send_email_status = ? 
                    WHERE team_id = ? 
                    AND level_id = ? 
                    AND is_deleted = 0", 
                    [
                        $teamId, 
                        $validated['service_time']['notification_min'], // Minutes to add
                        $validated['service_time']['notification_str'], 
                        $validated['service_time']['template_name'], 
                        $validated['service_time']['send_email_status'], 
                        $teamId, 
                        $levelId
                    ]);

                    if (isset($validated['service_time']['usernames']) || isset($validated['service_time']['emails'])) {
                        DB::table('escalate_srv_time_agent_subcategories')
                            ->where('team_id', $teamId)
                            ->where('level_id', $levelId)
                            ->delete();

                        foreach ($validated['service_time']['usernames'] as $username) {
                            $userId = DB::table('users')->where('username', $username)->value('id');
                            if ($userId) {
                                DB::table('escalate_srv_time_agent_subcategories')->insert([
                                    'team_id' => $teamId,
                                    'level_id' => $levelId,
                                    'agent_id' => $userId,
                                ]);
                            }
                        }
                    }
                }
            });

            return ApiResponse::success(null, "Escalation updated successfully.", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }



    public function deleteEscalation($teamId, $levelId)
    {
        try {
            DB::update("UPDATE helpdesk.escalate_fr_res_subcategories 
                        SET is_deleted = 1 
                        WHERE team_id = ? 
                        AND level_id = ?", 
                        [
                            $teamId, 
                            $levelId
                        ]);

            DB::update("UPDATE helpdesk.escalate_srv_time_subcategories 
                        SET is_deleted = 1 
                        WHERE team_id = ? 
                        AND level_id = ?", 
                        [
                            $teamId, 
                            $levelId
                        ]);

            return ApiResponse::success(null, "Escalation deleted successfully.", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }








    public function getSubcategoryByTeam($teamId, $businessEntity)
    {
        try {
            $resources = DB::select("SELECT sct.team_id , teams.team_name, sc.id, sc.sub_category_in_english, sc.sub_category_in_bangla
            FROM helpdesk.sub_categories sc, helpdesk.sub_category_teams sct, helpdesk.teams
            where sc.id = sct.sub_category_id
            and sct.team_id = teams.id
            and sct.team_id = '$teamId' AND sc.company_id = '$businessEntity' ORDER BY sc.sub_category_in_english ASC");
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    

    public function getSubcategoryByTeamNew($teamId, $businessEntity)
    {
        try {
            $resources = DB::select("SELECT sct.team_id, t.team_name, sc.id, sc.sub_category_in_english, sc.sub_category_in_bangla
                FROM helpdesk.sub_categories sc
                JOIN helpdesk.sub_category_teams sct ON sc.id = sct.sub_category_id
                JOIN helpdesk.teams t ON sct.team_id = t.id
                LEFT JOIN helpdesk.sla_subcategories sla ON sct.team_id = sla.team_id AND sla.subcat_id = sc.id
                WHERE sct.team_id = '$teamId' AND sc.company_id = '$businessEntity' AND sla.subcat_id IS NULL
                ORDER BY sc.sub_category_in_english ASC");
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getSubcategoryByBusinessEntity($id)
    {
        try {
            $resources = DB::select("SELECT *
        FROM helpdesk.sub_categories 
        where company_id = '$id' ");
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    public function getSLAbySubcategoryId($id)
    {
        try {
            $resources = SlaClient::where('subcat_id', $id)->first();
            if (is_null($resources)) {
                $resources = SlaSubcategory::where('subcat_id', $id)->first();
                $agents = DB::select("SELECT users.id, users.fullname FROM helpdesk.user_team_mappings ,helpdesk.users 
                    where users.id = user_team_mappings.user_id
                    and user_team_mappings.team_id = $id");
            }

            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
    

    public function store(Request $request)
    {

        try {
            // return [$request->defaultStatus, gettype($request->defaultEscalate)];
            $slaName = $request->slaName;
            $businessEntity = $request->businessEntity['value'] ?? null;
            $slaType = $request->slaType['value'] ?? null;
            $slaForClient = $request->slaForClient['value'] ?? null;
            $slaForTeam = $request->slaForTeam['value'] ?? null;
            $escalationFirstResponseLevels = $request->escalationLevels;
            $escalationServiceTimeLevels = $request->escalationServiceLevels;
            $defaultStatus = (int) $request->defaultStatus;
            $defaultEscalate = (int) $request->defaultEscalate;
            $indivisualStatus = (int) $request->indivisual;
            $subcategories = $request->subcategories;

            // return $businessEntity;


            // For Team
            if ($slaType === "Sub-Category") {

                if ($indivisualStatus == 1) {

                    // return 'hi';

                    $sla = ServiceLevelAgreement::create([
                        'sla_name' => $slaName
                    ]);

                    foreach ($subcategories as $subcategory) {
                        $fristResponseTimeInTotalMinutes = Calculation::convertToMinutes($subcategory['firstResponseTimeDay'], $subcategory['firstResponseTimeHrs'], $subcategory['firstResponseTimeMins']);
                        $fristResponseTimeInString = Calculation::convertToString($subcategory['firstResponseTimeDay'], $subcategory['firstResponseTimeHrs'], $subcategory['firstResponseTimeMins']);
                        $serviceTimeInTotalMinutes = Calculation::convertToMinutes($subcategory['serviceTimeDay'], $subcategory['serviceTimeHrs'], $subcategory['serviceTimeMins']);
                        $serviceTimeInString = Calculation::convertToString($subcategory['serviceTimeDay'], $subcategory['serviceTimeHrs'], $subcategory['serviceTimeMins']);

                        // $exists = SlaSubcategory::where('team_id', $slaForTeam)
                        //     ->where('subcat_id', $subcategory['id'] ?? 0)
                        //     ->exists();

                            $exists = SlaSubcategory::where('team_id', $slaForTeam)
                                        ->where('subcat_id', $subcategory['id'] ?? 0)
                                        ->where('business_entity_id', $businessEntity)
                                        ->exists();

                            // return $exists;
                            

                        if (!$exists) {

                            if ($fristResponseTimeInTotalMinutes > 0) {

                            SlaSubcategory::create([
                                'sla_id' => $sla->id,
                                'business_entity_id' => $businessEntity ?? 0,
                                'team_id' => $slaForTeam ?? 0,
                                'subcat_id' => $subcategory['id'] ?? 0,
                                'fr_res_day' => $subcategory['firstResponseTimeDay'] ?? 0,
                                'fr_res_hr' => $subcategory['firstResponseTimeHrs'] ?? 0,
                                'fr_res_min' => $subcategory['firstResponseTimeMins'] ?? 0,

                                'fr_res_time_min' => $fristResponseTimeInTotalMinutes,
                                'fr_res_time_str' => $fristResponseTimeInString,

                                'srv_day' => $subcategory['serviceTimeDay'] ?? 0,
                                'srv_hr' => $subcategory['serviceTimeHrs'] ?? 0,
                                'srv_min' => $subcategory['serviceTimeMins'] ?? 0,

                                'srv_time_min' => $serviceTimeInTotalMinutes,
                                'srv_time_str' => $serviceTimeInString,

                                'esc_status' => $subcategory['escalateStatus'] ?? 0,
                                'status' => $subcategory['status'] ?? 0,
                            ]);
                        

                            if ($subcategory['escalateStatus']) {

                                $sumOfFirstResponseLabel = 0;
                                $sumOfServiceTimeLabel = 0;
                                foreach ($escalationFirstResponseLevels as $level) {

                                    $agentNotifyTime =  $level['timeframe']['value'];
                                    $agentNotifyTimeInTotalMinutes = Calculation::getFirstResponseTriggerTime($agentNotifyTime, $fristResponseTimeInTotalMinutes);
                                    $sumOfFirstResponseLabel += $agentNotifyTimeInTotalMinutes;
                                    EscalateFrResSubcategory::create([
                                        'business_entity_id' => $businessEntity ?? 0,
                                        'team_id' => $slaForTeam ?? 0,
                                        'subcat_id' => $subcategory['id'] ?? 0,
                                        'level_id' => $level['id'] ?? 0,
                                        'notification_min' =>  $sumOfFirstResponseLabel,
                                        'notification_str' => $agentNotifyTime ?? null,
                                        'send_email_status' => $level['sendmail'],
                                        'email_template_id' => $level['emailTemplate']['value'],
                                    ]);

                                    foreach ($level['agents'] as $agent) {
                                        EscalateFrResAgentSubcategory::create([
                                            'level_id' => $level['id'],
                                            'subcat_id' => $subcategory['id'],
                                            'team_id' => $slaForTeam,
                                            'agent_id' => $agent['value'],
                                        ]);
                                    }
                                }

                                foreach ($escalationServiceTimeLevels as $level) {

                                    $agentNotifyTime =  $level['timeframe']['value'];
                                    $agentNotifyTimeInTotalMinutes = Calculation::getFirstResponseTriggerTime($agentNotifyTime, $serviceTimeInTotalMinutes);
                                    $sumOfServiceTimeLabel += $agentNotifyTimeInTotalMinutes;
                                    EscalateSrvTimeSubcategory::create([
                                        'business_entity_id' => $businessEntity ?? 0,
                                        'team_id' => $slaForTeam ?? 0,
                                        'subcat_id' => $subcategory['id'] ?? 0,
                                        'level_id' => $level['id'] ?? 0,
                                        'notification_min' => $sumOfServiceTimeLabel,
                                        'notification_str' => $agentNotifyTime ?? null,
                                        'send_email_status' => $level['sendmail'],
                                        'email_template_id' => $level['emailTemplate']['value'],
                                    ]);


                                    foreach ($level['agents'] as $agent) {
                                        EscalateSrvTimeAgentSubcategory::create([
                                            'level_id' => $level['id'],
                                            'subcat_id' => $subcategory['id'],
                                            'team_id' => $slaForTeam,
                                            'agent_id' => $agent['value'],
                                        ]);
                                    }
                                }
                            }
                        }
                        }
                    }
                } else {


                    // return 'i am here';

                    // Default for subcategories
                    $sla = ServiceLevelAgreement::create([
                        'sla_name' => $slaName
                    ]);
                    $getSubcategories = DB::select("SELECT sct.team_id , teams.team_name, sc.id, sc.sub_category_in_english, sc.sub_category_in_bangla
                    FROM helpdesk.sub_categories sc, helpdesk.sub_category_teams sct, helpdesk.teams
                    where sc.id = sct.sub_category_id
                    and sct.team_id = teams.id
                    and sct.team_id = '$slaForTeam' ");


                    $defaultFirstResponseTimeDay = $request->defaultFirstResponseTimeDay ?? 0;
                    $defaultFirstResponseTimeHrs = $request->defaultFirstResponseTimeHrs ?? 0;
                    $defaultFirstResponseTimeMins = $request->defaultFirstResponseTimeMins ?? 0;
                    $defaultServiceTimeDay = $request->defaultServiceTimeDay ?? 0;
                    $defaultServiceTimeHrs = $request->defaultServiceTimeHrs ?? 0;
                    $defaultServiceTimeMins = $request->defaultServiceTimeMins ?? 0;



                    foreach ($getSubcategories as $subcategory) {

                        $fristResponseTimeInTotalMinutes = Calculation::convertToMinutes($defaultFirstResponseTimeDay, $defaultFirstResponseTimeHrs, $defaultFirstResponseTimeMins);
                        $fristResponseTimeInString = Calculation::convertToString($defaultFirstResponseTimeDay, $defaultFirstResponseTimeHrs, $defaultFirstResponseTimeMins);
                        $serviceTimeInTotalMinutes = Calculation::convertToMinutes($defaultServiceTimeDay, $defaultServiceTimeHrs, $defaultServiceTimeMins);
                        $serviceTimeInString = Calculation::convertToString($defaultServiceTimeDay, $defaultServiceTimeHrs, $defaultServiceTimeMins);

                        $exists = SlaSubcategory::where('team_id', $slaForTeam)
                            ->where('subcat_id', $subcategory['id'] ?? 0)
                            ->exists();

                            // return $exists;

                        if (!$exists) {

                            if ($fristResponseTimeInTotalMinutes > 0) {

                                SlaSubcategory::create([
                                    'sla_id' => $sla->id,
                                    'business_entity_id' => $businessEntity ?? 0,
                                    'team_id' => $slaForTeam ?? 0,
                                    'subcat_id' => $subcategory->id ?? 0,
                                    'fr_res_day' => $defaultFirstResponseTimeDay ?? 0,
                                    'fr_res_hr' => $defaultFirstResponseTimeHrs ?? 0,
                                    'fr_res_min' => $defaultFirstResponseTimeMins ?? 0,

                                    'fr_res_time_min' => $fristResponseTimeInTotalMinutes,
                                    'fr_res_time_str' => $fristResponseTimeInString,

                                    'srv_day' => $defaultServiceTimeDay ?? 0,
                                    'srv_hr' => $defaultServiceTimeHrs ?? 0,
                                    'srv_min' => $defaultServiceTimeMins ?? 0,

                                    'srv_time_min' => $serviceTimeInTotalMinutes,
                                    'srv_time_str' => $serviceTimeInString,
                                    'status' => $defaultStatus ?? 0,
                                    'esc_status' => $defaultEscalate ?? 0,

                                ]);

                            // }


                        if ($defaultEscalate == 1) {
                            $sumOfFirstResponseLabel = 0;
                            $sumOfServiceTimeLabel = 0;
                            foreach ($escalationFirstResponseLevels as $level) {

                                $agentNotifyTime =  $level['timeframe']['value'];
                                $agentNotifyTimeInTotalMinutes = Calculation::getFirstResponseTriggerTime($agentNotifyTime, $fristResponseTimeInTotalMinutes);
                                $sumOfFirstResponseLabel += $agentNotifyTimeInTotalMinutes;
                                EscalateFrResSubcategory::create([
                                    'business_entity_id' => $businessEntity ?? 0,
                                    'team_id' => $slaForTeam ?? 0,
                                    'subcat_id' => $subcategory->id ?? 0,
                                    'level_id' => $level['id'] ?? 0,
                                    'notification_min' => $sumOfFirstResponseLabel,
                                    'notification_str' => $agentNotifyTime ?? null,
                                    'send_email_status' => $level['sendmail'],
                                    'email_template_id' => $level['emailTemplate']['value'],
                                ]);

                                foreach ($level['agents'] as $agent) {
                                    EscalateFrResAgentSubcategory::create([
                                        'level_id' => $level['id'],
                                        'subcat_id' => $subcategory->id,
                                        'team_id' => $slaForTeam,
                                        'agent_id' => $agent['value'],
                                    ]);
                                }
                            }

                            foreach ($escalationServiceTimeLevels as $level) {

                                $agentNotifyTime =  $level['timeframe']['value'];
                                $agentNotifyTimeInTotalMinutes = Calculation::getFirstResponseTriggerTime($agentNotifyTime, $serviceTimeInTotalMinutes);
                                $sumOfServiceTimeLabel += $agentNotifyTimeInTotalMinutes;
                                EscalateSrvTimeSubcategory::create([
                                    'business_entity_id' => $businessEntity ?? 0,
                                    'team_id' => $slaForTeam ?? 0,
                                    'subcat_id' => $subcategory->id ?? 0,
                                    'level_id' => $level['id'] ?? 0,
                                    'notification_min' => $sumOfServiceTimeLabel,
                                    'notification_str' => $agentNotifyTime ?? null,
                                    'send_email_status' => $level['sendmail'],
                                    'email_template_id' => $level['emailTemplate']['value'],
                                ]);


                                foreach ($level['agents'] as $agent) {
                                    EscalateSrvTimeAgentSubcategory::create([
                                        'level_id' => $level['id'],
                                        'subcat_id' => $subcategory->id,
                                        'team_id' => $slaForTeam,
                                        'agent_id' => $agent['value'],
                                    ]);
                                }
                            }
                        }

                    }
                    }
                    }
                }
            }

            // For Client
            if ($slaType === "Client") {



                if ($indivisualStatus == 1) {

                    $sla = ServiceLevelAgreement::create([
                        'sla_name' => $slaName
                    ]);

                    foreach ($subcategories as $subcategory) {
                        $fristResponseTimeInTotalMinutes = Calculation::convertToMinutes($subcategory['firstResponseTimeDay'], $subcategory['firstResponseTimeHrs'], $subcategory['firstResponseTimeMins']);
                        $fristResponseTimeInString = Calculation::convertToString($subcategory['firstResponseTimeDay'], $subcategory['firstResponseTimeHrs'], $subcategory['firstResponseTimeMins']);
                        $serviceTimeInTotalMinutes = Calculation::convertToMinutes($subcategory['serviceTimeDay'], $subcategory['serviceTimeHrs'], $subcategory['serviceTimeMins']);
                        $serviceTimeInString = Calculation::convertToString($subcategory['serviceTimeDay'], $subcategory['serviceTimeHrs'], $subcategory['serviceTimeMins']);

                        SlaClient::create([
                            'sla_id' => $sla->id,
                            'business_entity_id' => $businessEntity ?? 0,
                            'client_id' => $slaForClient ?? 0,
                            'subcat_id' => $subcategory['id'] ?? 0,
                            'fr_res_day' => $subcategory['firstResponseTimeDay'] ?? 0,
                            'fr_res_hr' => $subcategory['firstResponseTimeHrs'] ?? 0,
                            'fr_res_min' => $subcategory['firstResponseTimeMins'] ?? 0,

                            'fr_res_time_min' => $fristResponseTimeInTotalMinutes,
                            'fr_res_time_str' => $fristResponseTimeInString,

                            'srv_day' => $subcategory['serviceTimeDay'] ?? 0,
                            'srv_hr' => $subcategory['serviceTimeHrs'] ?? 0,
                            'srv_min' => $subcategory['serviceTimeMins'] ?? 0,

                            'srv_time_min' => $serviceTimeInTotalMinutes,
                            'srv_time_str' => $serviceTimeInString,

                            'esc_status' => $subcategory['escalateStatus'],
                            'status' => $subcategory['status'],
                        ]);

                        if ($subcategory['escalateStatus']) {

                            $sumOfFirstResponseLabel = 0;
                            $sumOfServiceTimeLabel = 0;
                            foreach ($escalationFirstResponseLevels as $level) {

                                $agentNotifyTime =  $level['timeframe']['value'];
                                $agentNotifyTimeInTotalMinutes = Calculation::getFirstResponseTriggerTime($agentNotifyTime, $fristResponseTimeInTotalMinutes);
                                $sumOfFirstResponseLabel += $agentNotifyTimeInTotalMinutes;
                                EscalateFrResClient::create([
                                    'business_entity_id' => $businessEntity ?? 0,
                                    'client_id' => $slaForClient ?? 0,
                                    'subcat_id' => $subcategory['id'] ?? 0,
                                    'level_id' => $level['id'] ?? 0,
                                    'notification_min' => $sumOfFirstResponseLabel,
                                    'notification_str' => $agentNotifyTime ?? null,
                                    'send_email_status' => $level['sendmail'],
                                    'email_template_id' => $level['emailTemplate']['value'],
                                ]);

                                foreach ($level['agents'] as $agent) {
                                    EscalateFrReAgentClient::create([
                                        'level_id' => $level['id'],
                                        'subcat_id' => $subcategory['id'],
                                        'agent_id' => $agent['value'],
                                    ]);
                                }
                            }

                            foreach ($escalationServiceTimeLevels as $level) {

                                $agentNotifyTime =  $level['timeframe']['value'];
                                $agentNotifyTimeInTotalMinutes = Calculation::getFirstResponseTriggerTime($agentNotifyTime, $serviceTimeInTotalMinutes);
                                $sumOfServiceTimeLabel += $agentNotifyTimeInTotalMinutes;
                                EscalateSrvTimeClient::create([
                                    'business_entity_id' => $businessEntity ?? 0,
                                    'client_id' => $slaForClient ?? 0,
                                    'subcat_id' => $subcategory['id'] ?? 0,
                                    'level_id' => $level['id'] ?? 0,
                                    'notification_min' => $sumOfServiceTimeLabel,
                                    'notification_str' => $agentNotifyTime ?? null,
                                    'send_email_status' => $level['sendmail'],
                                    'email_template_id' => $level['emailTemplate']['value'],
                                ]);


                                foreach ($level['agents'] as $agent) {
                                    EscalateSrvTimeAgentClient::create([
                                        'level_id' => $level['id'],
                                        'subcat_id' => $subcategory['id'],
                                        'agent_id' => $agent['value'],
                                    ]);
                                }
                            }
                        }
                    }
                } else {

                    // Default subcategories Client
                    $sla = ServiceLevelAgreement::create([
                        'sla_name' => $slaName
                    ]);

                    $getSubcategories = DB::select("SELECT *
                        FROM helpdesk.sub_categories 
                        where company_id = '$businessEntity' ");

                    $defaultFirstResponseTimeDay = $request->defaultFirstResponseTimeDay ?? 0;
                    $defaultFirstResponseTimeHrs = $request->defaultFirstResponseTimeHrs ?? 0;
                    $defaultFirstResponseTimeMins = $request->defaultFirstResponseTimeMins ?? 0;
                    $defaultServiceTimeDay = $request->defaultServiceTimeDay ?? 0;
                    $defaultServiceTimeHrs = $request->defaultServiceTimeHrs ?? 0;
                    $defaultServiceTimeMins = $request->defaultServiceTimeMins ?? 0;



                    foreach ($getSubcategories as $subcategory) {

                        $fristResponseTimeInTotalMinutes = Calculation::convertToMinutes($defaultFirstResponseTimeDay, $defaultFirstResponseTimeHrs, $defaultFirstResponseTimeMins);
                        $fristResponseTimeInString = Calculation::convertToString($defaultFirstResponseTimeDay, $defaultFirstResponseTimeHrs, $defaultFirstResponseTimeMins);
                        $serviceTimeInTotalMinutes = Calculation::convertToMinutes($defaultServiceTimeDay, $defaultServiceTimeHrs, $defaultServiceTimeMins);
                        $serviceTimeInString = Calculation::convertToString($defaultServiceTimeDay, $defaultServiceTimeHrs, $defaultServiceTimeMins);


                        SlaClient::create([
                            'sla_id' => $sla->id,
                            'business_entity_id' => $businessEntity ?? 0,
                            'client_id' => $slaForClient ?? 0,
                            'subcat_id' => $subcategory->id ?? 0,
                            'fr_res_day' => $defaultFirstResponseTimeDay ?? 0,
                            'fr_res_hr' => $defaultFirstResponseTimeHrs ?? 0,
                            'fr_res_min' => $defaultFirstResponseTimeMins ?? 0,

                            'fr_res_time_min' => $fristResponseTimeInTotalMinutes,
                            'fr_res_time_str' => $fristResponseTimeInString,

                            'srv_day' => $defaultServiceTimeDay ?? 0,
                            'srv_hr' => $defaultServiceTimeHrs ?? 0,
                            'srv_min' => $defaultServiceTimeMins ?? 0,

                            'srv_time_min' => $serviceTimeInTotalMinutes,
                            'srv_time_str' => $serviceTimeInString,
                            'status' =>  $defaultStatus,
                            'esc_status' =>  $defaultEscalate,

                        ]);



                        if ($defaultEscalate) {
                            $sumOfFirstResponseLabel = 0;
                            $sumOfServiceTimeLabel = 0;

                            foreach ($escalationFirstResponseLevels as $level) {

                                $agentNotifyTime =  $level['timeframe']['value'];
                                $agentNotifyTimeInTotalMinutes = Calculation::getFirstResponseTriggerTime($agentNotifyTime, $fristResponseTimeInTotalMinutes);
                                $sumOfFirstResponseLabel += $agentNotifyTimeInTotalMinutes;
                                EscalateFrResClient::create([
                                    'business_entity_id' => $businessEntity ?? 0,
                                    'client_id' => $slaForClient ?? 0,
                                    'subcat_id' => $subcategory->id ?? 0,
                                    'level_id' => $level['id'] ?? 0,
                                    'notification_min' => $sumOfFirstResponseLabel,
                                    'notification_str' => $agentNotifyTime ?? null,
                                    'send_email_status' => (int) $level['sendmail'] ? 1 : 0,
                                    'email_template_id' => $level['emailTemplate']['value'],
                                ]);

                                foreach ($level['agents'] as $agent) {
                                    EscalateFrReAgentClient::create([
                                        'level_id' => $level['id'],
                                        'subcat_id' => $subcategory->id,
                                        'agent_id' => $agent['value'],
                                    ]);
                                }
                            }

                            foreach ($escalationServiceTimeLevels as $level) {

                                $agentNotifyTime =  $level['timeframe']['value'];
                                $agentNotifyTimeInTotalMinutes = Calculation::getFirstResponseTriggerTime($agentNotifyTime, $serviceTimeInTotalMinutes);
                                $sumOfServiceTimeLabel += $agentNotifyTimeInTotalMinutes;
                                EscalateSrvTimeClient::create([
                                    'business_entity_id' => $businessEntity ?? 0,
                                    'client_id' => $slaForClient ?? 0,
                                    'subcat_id' => $subcategory->id ?? 0,
                                    'level_id' => $level['id'] ?? 0,
                                    'notification_min' => $sumOfServiceTimeLabel,
                                    'notification_str' => $agentNotifyTime ?? null,
                                    'send_email_status' => (int) $level['sendmail'] ? 1 : 0,
                                    'email_template_id' => $level['emailTemplate']['value'],
                                ]);


                                foreach ($level['agents'] as $agent) {
                                    EscalateSrvTimeAgentClient::create([
                                        'level_id' => $level['id'],
                                        'subcat_id' => $subcategory->id,
                                        'agent_id' => $agent['value'],
                                    ]);
                                }
                            }
                        }
                    }
                }
            }





            return ApiResponse::success([], "Successfully Inserted", 201);
        } catch (\Exception $e) {
            return ApiResponse::error(__LINE__ . ':' . $e->getMessage(), "Error", 500);
        }
    }



    // new staging version

    public function getSubCategoriesByTeamOnly($team_id)
    {
        $subCategories = DB::table('sub_category_teams as s')
            ->join('sub_categories as c', 's.sub_category_id', '=', 'c.id')
            ->where('s.team_id', $team_id)
            ->select(
                's.sub_category_id',
                'c.sub_category_in_english'
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => $subCategories
        ]);
    }






    public function getSubCategoriesByTeam($team_id, $business_entity_id)
    {
        $subCategories = DB::table('sub_category_teams as s')
            ->join('sub_categories as c', 's.sub_category_id', '=', 'c.id')
            ->join(
                'entity_category_subcategory_mappings as cm',
                's.sub_category_id',
                '=',
                'cm.sub_category_id'
            )
            ->where('s.team_id', $team_id)
            ->where('cm.company_id', $business_entity_id)
            ->whereNotIn('s.sub_category_id', function ($query) use ($team_id, $business_entity_id) {
                $query->select('subcategory_id')
                    ->from('sla_subcat_configs')
                    ->where('team_id', $team_id)
                    ->where('business_entity_id', $business_entity_id);
            })
            ->select(
                's.sub_category_id',
                'c.sub_category_in_english'
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => $subCategories
        ]);
    }






    // public function getClientsByBusinessEntityAllClient($business_entity_id)
    // {
    //     $clients = DB::table('business_entity_wise_clients as b')
    //         ->where('b.business_entity_id', $business_entity_id)
    //         ->select(
    //             'b.client_id',
    //             'b.client_name'
    //         )
    //         ->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $clients
    //     ]);
    // }



    // public function getClientsByBusinessEntity($business_entity_id)
    // {
    //     $clients = DB::table('business_entity_wise_clients as b')
    //         ->where('b.business_entity_id', $business_entity_id)
    //         ->select(
    //             'b.client_id',
    //             'b.client_name'
    //         )
    //         ->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $clients
    //     ]);
    // }



    public function getClientsByBusinessEntity($business_entity_id)
    {
        $clients = DB::table('business_entity_wise_clients as b')
            ->where('b.business_entity_id', $business_entity_id)
            ->whereNotIn('b.client_id', function ($query) use ($business_entity_id) {
                $query->select('sc.client_id')
                    ->from('sla_client_configs as sc')
                    ->where('sc.business_entity_id', $business_entity_id);
            })
            ->select(
                'b.client_id',
                'b.client_name'
            )
            ->get();

        return response()->json([
            'success' => true,
            'data' => $clients
        ]);
    }

   
}
