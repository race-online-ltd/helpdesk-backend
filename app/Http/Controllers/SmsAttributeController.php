<?php

namespace App\Http\Controllers;

use App\Models\SmsAttribute;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class SmsAttributeController extends Controller
{
    /**
     * GET /api/sms-attributes
     * Returns all active attributes ordered by sort_order
     */
    public function index()
    {
        try {
            $attributes = SmsAttribute::active()->ordered()->get();
            return ApiResponse::success($attributes, "SMS attributes fetched successfully", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    /**
     * POST /api/sms-attributes
     */
    public function store(Request $request)
    {
        $request->validate([
            'label'      => 'required|string|max:255',
            'value'      => 'required|string|unique:sms_attributes,value',
            'status'     => 'in:Active,Inactive',
            'sort_order' => 'integer',
        ]);

        try {
            $attribute = SmsAttribute::create([
                'label'      => $request->label,
                'value'      => $request->value,
                'status'     => $request->status     ?? 'Active',
                'sort_order' => $request->sort_order ?? 0,
            ]);
            return ApiResponse::success($attribute, "Successfully Inserted", 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    /**
     * PUT /api/sms-attributes/{id}
     */
    public function update(Request $request, $id)
    {
        $attribute = SmsAttribute::find($id);
        if (!$attribute) {
            return ApiResponse::error("SMS attribute not found.", "Not Found", 404);
        }

        $request->validate([
            'label'      => 'string|max:255',
            'value'      => 'string|unique:sms_attributes,value,' . $id,
            'status'     => 'in:Active,Inactive',
            'sort_order' => 'integer',
        ]);

        try {
            $attribute->update([
                'label'      => $request->label      ?? $attribute->label,
                'value'      => $request->value      ?? $attribute->value,
                'status'     => $request->status     ?? $attribute->status,
                'sort_order' => $request->sort_order ?? $attribute->sort_order,
            ]);
            return ApiResponse::success($attribute->fresh(), "Successfully Updated", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    /**
     * DELETE /api/sms-attributes/{id}
     */
    public function destroy($id)
    {
        $attribute = SmsAttribute::find($id);
        if (!$attribute) {
            return ApiResponse::error("SMS attribute not found.", "Not Found", 404);
        }

        try {
            $attribute->delete();
            return ApiResponse::success(null, "Successfully Deleted", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    
}