<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Helpers\ApiResponse;

class SlaSubcatConfigController extends Controller
{
    
    // public function index()
    // {
    //     $configs = DB::select('SELECT * FROM sla_subcat_configs');
    //     return response()->json($configs);
    // }


    public function index()
    {
        $configs = DB::select('SELECT sc.id, sc.business_entity_id, sc.team_id, sc.subcategory_id,sc.resolution_min,sc.sla_status, sc.escalation_status
                            ,c.company_name, t.team_name, s.sub_category_in_english, "Sub-Category" AS type
                            FROM sla_subcat_configs sc
                            JOIN companies c ON sc.business_entity_id = c.id
                            JOIN teams t ON sc.team_id = t.id
                            JOIN sub_categories s ON sc.subcategory_id = s.id');

                // return response()->json($configs);
                return ApiResponse::success($configs, "Success", 200);
    }


    // public function index()
    // {
    //     $configs = DB::select("
    //         SELECT sc.id,sc.business_entity_id,sc.team_id,sc.subcategory_id,sc.resolution_min,sc.sla_status,sc.escalation_status,c.company_name,
    //             t.team_name,s.sub_category_in_english,'Sub_Cat' AS type
    //         FROM sla_subcat_configs sc
    //         JOIN companies c ON sc.business_entity_id = c.id
    //         JOIN teams t ON sc.team_id = t.id
    //         JOIN sub_categories s ON sc.subcategory_id = s.id

    //         UNION ALL

    //         SELECT sc.id,sc.business_entity_id,'N/A' AS team_id,sc.client_id AS subcategory_id,sc.resolution_min,sc.sla_status,sc.escalation_status,
    //             c.company_name,'N/A' AS team_name,m.client_name AS sub_category_in_english,'Client' AS type
    //         FROM sla_client_configs sc
    //         JOIN companies c ON sc.business_entity_id = c.id
    //         JOIN user_client_mappings m ON sc.client_id = m.client_id
    //     ");

    //     return ApiResponse::success($configs, "Success", 200);
    // }

    
    public function show($id)
    {
        $config = DB::select('SELECT sc.id, sc.business_entity_id, sc.team_id, sc.subcategory_id,sc.resolution_min,sc.sla_status, sc.escalation_status
                            ,c.company_name, t.team_name, s.sub_category_in_english
                            FROM sla_subcat_configs sc
                            JOIN companies c ON sc.business_entity_id = c.id
                            JOIN teams t ON sc.team_id = t.id
                            JOIN sub_categories s ON sc.subcategory_id = s.id
                            WHERE sc.id = ?', [$id]);
        if (!$config) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        // return response()->json($config[0]);
        return ApiResponse::success($config, "Success", 200);
    }

    
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'business_entity_id' => 'required|integer',
    //         'team_id' => 'required|integer',
    //         'subcategory_id' => 'required|integer',
    //         'resolution_min' => 'required|integer',
    //         'sla_status' => 'required|string',
    //         'escalation_status' => 'required|string',
    //     ]);

    //     $id = DB::table('sla_subcat_configs')->insertGetId([
    //         'business_entity_id' => $request->business_entity_id,
    //         'team_id' => $request->team_id,
    //         'subcategory_id' => $request->subcategory_id,
    //         'resolution_min' => $request->resolution_min,
    //         'sla_status' => $request->sla_status,
    //         'escalation_status' => $request->escalation_status,
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);

    //     return response()->json(['message' => 'Created', 'id' => $id], 201);
    // }


    public function store(Request $request)
    {

        try{

        $request->validate([
            'business_entity_id' => 'required|integer',
            'team_id' => 'required|integer',
            'subcategory_id' => 'required|integer',
            'resolution_min' => 'required|integer',
            'sla_status' => 'required|string',
            'escalation_status' => 'required|string',
        ]);

        
        $existingRecord = DB::table('sla_subcat_configs')
            ->where('business_entity_id', $request->business_entity_id)
            ->where('team_id', $request->team_id)
            ->where('subcategory_id', $request->subcategory_id)
            ->first();

        if ($existingRecord) {
            return response()->json([
                'message' => 'Record already exists',
                'id' => $existingRecord->id
            ], 409);
        }

        $id = DB::table('sla_subcat_configs')->insertGetId([
            'business_entity_id' => $request->business_entity_id,
            'team_id' => $request->team_id,
            'subcategory_id' => $request->subcategory_id,
            'resolution_min' => $request->resolution_min,
            'sla_status' => $request->sla_status,
            'escalation_status' => $request->escalation_status,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // return response()->json(['message' => 'Created', 'id' => $id], 201);

        return ApiResponse::success($id, 'SLA Sub-Category Config created successfully', 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error', 500);
        }
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'business_entity_id' => 'sometimes|integer',
            'team_id' => 'sometimes|integer',
            'subcategory_id' => 'sometimes|integer',
            'resolution_min' => 'sometimes|integer',
            'sla_status' => 'sometimes|string',
            'escalation_status' => 'sometimes|string',
        ]);

        $updated = DB::update(
            'UPDATE sla_subcat_configs SET business_entity_id = ?, team_id = ?, subcategory_id = ?, resolution_min = ?, sla_status = ?, escalation_status = ?, updated_at = ? WHERE id = ?',
            [
                $request->business_entity_id,
                $request->team_id,
                $request->subcategory_id,
                $request->resolution_min,
                $request->sla_status,
                $request->escalation_status,
                now(),
                $id
            ]
        );

        if (!$updated) {
            return response()->json(['message' => 'Not Found or Nothing to Update'], 404);
        }

        // return response()->json(['message' => 'Updated']);
        return ApiResponse::success($updated, "SLA updated successfully", 200);
    }

    
    public function destroy($id)
    {
        $deleted = DB::delete('DELETE FROM sla_subcat_configs WHERE id = ?', [$id]);
        if (!$deleted) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        return response()->json(['message' => 'Deleted']);
    }
}
