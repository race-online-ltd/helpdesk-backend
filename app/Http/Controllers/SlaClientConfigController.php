<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use App\Models\SlaClientConfig;
use App\Helpers\ApiResponse;


use Illuminate\Http\Request;

class SlaClientConfigController extends Controller
{
    public function index()
    {

        $configs = DB::select('SELECT sc.id, sc.business_entity_id,sc.client_id,m.client_name AS sub_category_in_english,sc.resolution_min,sc.sla_status
                                , sc.escalation_status, "N/A" AS team_id,c.company_name, "Client" AS type, "--" AS team_name
                                FROM sla_client_configs sc
                                JOIN companies c ON sc.business_entity_id = c.id
                                JOIN user_client_mappings m ON sc.client_id = m.client_id');

                
                return ApiResponse::success($configs, "Success", 200);
    }

    
    public function store(Request $request)
    {

        try{

        
        $validated = $request->validate([
            'business_entity_id' => 'required',
            'client_id'          => 'required',
            'client_vendor_id'   => 'nullable',
            'resolution_min'     => 'required',
            'sla_status'         => 'required',
            'escalation_status'  => 'required',
        ]);

        
        $existing = SlaClientConfig::where('business_entity_id', $validated['business_entity_id'])
            ->where('client_id', $validated['client_id'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'SLA Client Config already exists',
                'id' => $existing->id
            ], 409);
        }

        
        $config = SlaClientConfig::create($validated);


        return ApiResponse::success($config, 'SLA Client Config created successfully', 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error', 500);
        }
    }

    
    public function show($id)
    {

        $config = DB::select('SELECT sc.id, sc.business_entity_id,sc.client_id,sc.client_vendor_id,m.client_name,sc.resolution_min,sc.sla_status, sc.escalation_status
                                ,c.company_name, "Client" AS type
                                FROM sla_client_configs sc
                                JOIN companies c ON sc.business_entity_id = c.id
                                JOIN user_client_mappings m ON sc.client_id = m.client_id
                                WHERE sc.id = ?', [$id]);

        if (!$config) {
            return response()->json(['message' => 'Not Found'], 404);
        }
        
        return ApiResponse::success($config, "Success", 200);
    }

    
    public function update(Request $request, $id)
    {
        $config = SlaClientConfig::findOrFail($id);

        $validated = $request->validate([
            'business_entity_id' => 'required',
            'client_id'          => 'required',
            'client_vendor_id'   => 'nullable',
            'resolution_min'     => 'required',
            'sla_status'         => 'required',
            'escalation_status'  => 'required',
        ]);

        $config->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'SLA Client Config updated successfully',
            'data' => $config
        ]);
    }

    
    public function destroy($id)
    {
        $config = SlaClientConfig::findOrFail($id);
        $config->delete();

        return response()->json([
            'success' => true,
            'message' => 'SLA Client Config deleted successfully'
        ]);
    }
}
