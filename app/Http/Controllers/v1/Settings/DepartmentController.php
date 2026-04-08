<?php

namespace App\Http\Controllers\v1\Settings;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentController extends Controller
{
    public function index()
    {
        try {
            $resources = Department::all();
            return response()->json([
                'status'=>true,
                'message' => 'success',
                'result'=>$resources
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'status'=>false,
                'message' => 'Something went wrong!',
                'result' => $e->getMessage()
            ], 500);
        }

    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $existingCompany = Department::where('department_name', $request->departmentName)->first();

            if ($existingCompany) {
                return response()->json([
                    'status'=>false,
                    'message' => 'Department already exists!',
                    'result'=>[]
                ], 409);
            }

            $department = Department::create([
                'department_name'=>$request->departmentName,
            ]);

            DB::commit();
            return response()->json([
                'status'=>true,
                'message' => 'Successfully Inserted',
                'result'=>$department
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'=>false,
                'message' => 'Something went wrong!',
                'result' => $e->getMessage()
            ], 500);
        }

    }

    public function show($id)
    {
        try {
            $resource = Department::findOrFail($id);
            return response()->json([
                'status'=>true,
                'message' => 'success',
                'result'=>$resource
            ], 200);
        }catch (\Exception $e) {
            return response()->json([
                'status'=>false,
                'message' => 'Something went wrong!',
                'result' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $resource = Department::findOrFail($id);
            $resource->update([
                'department_name' => $request->input('departmentName'),
            ]);
            DB::commit();
            return response()->json([
                'status'=>true,
                'message' => 'Successfully Updated',
                'result'=>$resource
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'=>false,
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
