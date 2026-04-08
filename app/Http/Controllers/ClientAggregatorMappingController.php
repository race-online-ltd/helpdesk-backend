<?php

namespace App\Http\Controllers;

use App\Models\ClientAggregatorMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientAggregatorMappingController extends Controller
{
    
    public function store(Request $request)
    {
        $request->validate([
            'business_entity_id' => 'required',
            'client_id'          => 'required',
            'aggregator_id'      => 'required|exists:aggregators,id',
        ]);

        $mapping = ClientAggregatorMapping::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Client Aggregator Mapping created successfully',
            'data' => $mapping
        ]);
    }

    
    public function index()
    {
        $data = ClientAggregatorMapping::with('aggregator')
            ->latest()
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }

    
    // public function edit($id)
    // {
    //     $mapping = ClientAggregatorMapping::with('aggregator')
    //         ->findOrFail($id);

    //     return response()->json([
    //         'status' => true,
    //         'data' => $mapping
    //     ]);
    // }


    public function edit($id)
    {
        $mapping = ClientAggregatorMapping::findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $mapping
        ]);
    }

    
    public function update(Request $request, $id)
    {
        $request->validate([
            'business_entity_id' => 'required',
            'client_id'          => 'required',
            'aggregator_id'      => 'required|exists:aggregators,id',
        ]);

        $mapping = ClientAggregatorMapping::findOrFail($id);
        $mapping->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Client Aggregator Mapping updated successfully',
            'data' => $mapping
        ]);
    }

   
    public function destroy($id)
    {
        ClientAggregatorMapping::findOrFail($id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Client Aggregator Mapping deleted successfully'
        ]);
    }


    public function fetchAggregatorClientMapping()
    {
        $data = DB::table('client_aggregator_mappings as m')
            ->join('companies as c', 'm.business_entity_id', '=', 'c.id')
            ->join('user_client_mappings as u', 'm.client_id', '=', 'u.client_id')
            ->join('aggregators as a', 'm.aggregator_id', '=', 'a.id')
            ->select(
                'm.id',
                'c.company_name',
                'u.client_name',
                'a.name as aggregator_name',
                'm.business_entity_id',
                'm.client_id',
                'm.aggregator_id',
                'm.created_at'
            )
            ->orderBy('m.created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $data
        ]);
    }


    public function getAggregatorsByClient($clientId)
    {
        $data = DB::select(
            "
            SELECT 
                m.aggregator_id,
                a.name
            FROM client_aggregator_mappings m
            JOIN aggregators a 
                ON m.aggregator_id = a.id
            WHERE m.client_id = ?
            ",
            [$clientId]
        );

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}
