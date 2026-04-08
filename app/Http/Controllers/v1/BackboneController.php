<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\BackboneElement;
use App\Models\BackboneElementList;
use Illuminate\Http\Request;

class BackboneController extends Controller
{
    public function getAllBackboneElements()
    {
        try {
            $backboneElements = BackboneElement::all(['id', 'name']);

            return ApiResponse::success($backboneElements, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getBackboneElementListsByElementId($id)
    {
        try {
            if (in_array($id, [16, 17, 18])) {
                $lists = BackboneElementList::whereIn('backbone_element_id', [11, 12, 13, 14, 15])
                    ->get(['id', 'name']);
            } else {
                $lists = BackboneElementList::where('backbone_element_id', $id)
                    ->get(['id', 'name']);
            }
        
            return ApiResponse::success($lists, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
        
    }
}
