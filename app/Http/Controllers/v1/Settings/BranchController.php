<?php

namespace App\Http\Controllers\v1\Settings;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Check if branch with the same name and vendor client already exists
            $existingBranch = Branch::where('branch_name', $request->branchName)
                ->where('vendor_client_id', $request->client)
                ->first();

            if ($existingBranch) {
                return ApiResponse::success([], "Branch name with the same vendor client already exists", 500);
            }

            // Create new branch including the 5 new fields
            $branch = Branch::create([
                'business_entity_id' => $request->businessEntity,
                'vendor_client_id'   => $request->client,
                'branch_name'        => $request->branchName,
                'mobile1'            => $request->mobile1,
                'mobile2'            => $request->mobile2,
                'email1'             => $request->email1,
                'email2'             => $request->email2,
                'service_address'    => $request->serviceAddress,
            ]);

            return ApiResponse::success($branch, "Successfully Added New Branch", 201);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    // public function show()
    // {
    //     try {
    //         $show = DB::select("SELECT
    //                 c.company_name, 
    //                 ucm.client_name,
    //                 GROUP_CONCAT(b.branch_name ORDER BY b.branch_name SEPARATOR '||') AS branch_names
    //             FROM helpdesk.branches b
    //             INNER JOIN helpdesk.companies c ON b.business_entity_id = c.id
    //             INNER JOIN helpdesk.user_client_mappings ucm ON b.vendor_client_id = ucm.client_id
    //             GROUP BY c.company_name, ucm.client_name");
    //         return ApiResponse::success($show, "Successfully Fetch Branch", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    public function show()
    {
        try {
            DB::statement("SET SESSION group_concat_max_len = 10000");

            $show = DB::select("SELECT
                    b.id,
                    c.company_name, 
                    ucm.client_name,
                    GROUP_CONCAT(b.branch_name ORDER BY b.branch_name SEPARATOR '||') AS branch_names,
                    b.mobile1, b.mobile2, b.email1, b.email2, b.service_address
                FROM helpdesk.branches b
                INNER JOIN helpdesk.companies c ON b.business_entity_id = c.id
                INNER JOIN helpdesk.user_client_mappings ucm ON b.vendor_client_id = ucm.client_id
                GROUP BY c.company_name, ucm.client_name,b.id");

            return ApiResponse::success($show, "Successfully Fetch Branch", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    // Fetch branch info for editing
    public function edit($id)
    {
        try {
            $branch = Branch::findOrFail($id);
            return ApiResponse::success($branch, "Branch data fetched successfully", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    // Update branch info
    public function update(Request $request, $id)
    {
        try {
            $branch = Branch::findOrFail($id);

            // Check if branch with the same name and vendor client already exists (excluding current branch)
            $existingBranch = Branch::where('branch_name', $request->branchName)
                ->where('vendor_client_id', $request->client)
                ->where('id', '!=', $id)
                ->first();

            if ($existingBranch) {
                return ApiResponse::success([], "Branch name with the same vendor client already exists", 500);
            }

            // Update branch fields
            $branch->update([
                'business_entity_id' => $request->businessEntity,
                'vendor_client_id'   => $request->client,
                'branch_name'        => $request->branchName,
                'mobile1'            => $request->mobile1,
                'mobile2'            => $request->mobile2,
                'email1'             => $request->email1,
                'email2'             => $request->email2,
                'service_address'    => $request->serviceAddress,
            ]);

            return ApiResponse::success($branch, "Branch updated successfully", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }



    public function getBranch($id)
    {
        try {
            $show = Branch::where('vendor_client_id', $id)->get();
            return ApiResponse::success($show, "Successfully Fetch Branch", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
}
