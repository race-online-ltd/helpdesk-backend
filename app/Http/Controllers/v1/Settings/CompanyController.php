<?php

namespace App\Http\Controllers\v1\Settings;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function index(Request $request)
    {


        try {

            $userType = $request->userType;

            $userID = $request->userId;

            if ($userType == 'Agent') {

                $resources = DB::select("SELECT c.id,c.company_name FROM helpdesk.companies c");
                return response()->json(['status' => true, 'message' => 'success', 'result' => $resources], 200);
            } elseif ($userType == 'Client') {

                $resources = DB::select("SELECT c.id,c.company_name FROM helpdesk.companies c , helpdesk.user_entity_mappings uem
                WHERE c.id = uem.business_entity_id AND uem.user_id = '$userID'");
                return response()->json(['status' => true, 'message' => 'success', 'result' => $resources], 200);
            } elseif ($userType == 'Customer') {

                $resources = DB::select("SELECT c.id,c.company_name FROM helpdesk.companies c , helpdesk.user_entity_mappings uem
                WHERE c.id = uem.business_entity_id AND uem.user_id = '$userID'");
                return response()->json(['status' => true, 'message' => 'success', 'result' => $resources], 200);
            } else {

                try {
                    $resources = DB::select("SELECT companies.*,teams.team_name
                    FROM helpdesk.companies
                    LEFT JOIN helpdesk.teams ON companies.team_id = teams.id");
                    return response()->json([
                        'status' => true,
                        'message' => 'success',
                        'result' => $resources
                    ], 200);
                } catch (\Exception $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Something went wrong!',
                        'result' => $e->getMessage()
                    ], 500);
                }
            }
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Something went wrong!', 'result' => $e->getMessage()], 500);
        }
    }

    public function ClientEntityShow(Request $request)
    {

        try {
            $userID = $request->userId;

            $resources = DB::select("SELECT c.id,c.company_name FROM helpdesk.companies c , helpdesk.user_client_mappings ucm
            WHERE c.id = ucm.business_entity_id AND ucm.user_id = '$userID'");

            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $existingCompany = Company::where('company_name', $request->companyName)->first();

            if ($existingCompany) {
                return response()->json([
                    'status' => false,
                    'message' => 'Company already exists!',
                    'result' => []
                ], 409);
            }

            $company = Company::create([
                'team_id' => $request->teams,
                'company_name' => $request->companyName,
                'prefix' => $request->companyPrefix,
                'url' => $request->websiteUrl,
                'phone' => $request->phoneNo,
                'address' => $request->address,
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Successfully Inserted',
                'result' => $company
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'result' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $resource = Company::findOrFail($id);
            return response()->json([
                'status' => true,
                'message' => 'success',
                'result' => $resource
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'result' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {


        DB::beginTransaction();
        try {
            $resource = Company::findOrFail($id);
            $resource->update([
                'team_id' => $request->input('teams'),
                'company_name' => $request->input('companyName'),
                'prefix' => $request->input('companyPrefix'),
                'url' => $request->input('websiteUrl'),
                'phone' => $request->input('phoneNo'),
                'address' => $request->input('address'),
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Successfully Updated',
                'result' => $resource
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'result' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $resource = Company::findOrFail($id);
        $resource->delete();
        return response()->json(null, 204);
    }
}
