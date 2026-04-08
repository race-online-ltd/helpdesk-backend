<?php

namespace App\Http\Controllers\v1\Settings;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\BackboneElement;
use App\Models\BackboneElementList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NetworkBackboneController extends Controller
{
    public function storeElement(Request $request)
    {
        try {
            $newElement = BackboneElement::create([
                'name' => $request->elementName
            ]);
            return ApiResponse::success($newElement, "Successfully Added New Element", 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getNetworkBackboneElements()
    {
        try {
            $elements = DB::select("SELECT 
    be.id, 
    be.name AS element_name, 
    GROUP_CONCAT(bel.name ORDER BY bel.id SEPARATOR ' || ') AS list_names,
    GROUP_CONCAT(CONCAT(bel.id, ':', bel.name) ORDER BY bel.id SEPARATOR ' || ') AS list_details
FROM helpdesk.backbone_elements be
LEFT JOIN helpdesk.backbone_element_lists bel 
    ON be.id = bel.backbone_element_id
GROUP BY be.id, be.name");
            return ApiResponse::success($elements, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
    public function showElements()
    {
        try {
            $elements = BackboneElement::all();
            return ApiResponse::success($elements, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function storeElementList(Request $request)
    {
        try {
            $newElementList = BackboneElementList::create([
                'backbone_element_id' => $request->elementName,
                'name' => $request->elementList,
            ]);
            return ApiResponse::success($newElementList, "Successfully Added New Element List", 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
}
