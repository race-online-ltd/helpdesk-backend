<?php

namespace App\Http\Controllers\v1\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\EmailAttribute;
use App\Models\EmailTemplate;
use App\Helpers\ApiResponse;
use App\Models\Division;

class DivisionController extends Controller
{
    /**
     * @OA\Tag(
     *     name="Division",
     *     description="Division related endpoints"
     * )
     */


    /**
     * @OA\Get(
     *     path="/api/v1/settings/division/show",
     *     tags={"Division"},
     *     summary="Get all division",
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
            $resources = Division::all();
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/settings/division/store",
     *     tags={"Division"},
     *     summary="Create a new division",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "subject", "content", "status"},
     *             @OA\Property(property="name", type="string", example="Welcome Email"),
     *             @OA\Property(property="subject", type="string", example="Welcome to Our Service"),
     *             @OA\Property(property="content", type="string", example="Hello, welcome to our service!"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successfully Inserted",
     *        
     *     ),
     *     @OA\Response(
     *         response=409,
     *         description="Conflict",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="message", type="string")
     *         )
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
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $exist = Division::where('template_name', $request->name)->first();

            if ($exist) {
                return ApiResponse::success($exist, "Email template already exists!", 409);
            }

            $resources = Division::create([
                'name' => $request->name,
            ]);

            DB::commit();
            return ApiResponse::success($resources, "Successfully Inserted", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/settings/division/show/{id}",
     *     tags={"Division"},
     *     summary="Get an division by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *        
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="message", type="string")
     *         )
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
    public function show($id)
    {
        try {
            $resources = Division::findOrFail($id);
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/settings/division/update/{id}",
     *     tags={"Division"},
     *     summary="Update an division",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "subject", "content", "status"},
     *             @OA\Property(property="name", type="string", example="Welcome Email"),
     *             @OA\Property(property="subject", type="string", example="Welcome to Our Service"),
     *             @OA\Property(property="content", type="string", example="Hello, welcome to our service!"),
     *             @OA\Property(property="status", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successfully Updated",
     *         
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="message", type="string")
     *         )
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
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $resources = Division::findOrFail($id);
            $resources->update([
                'name' => $request->name,

            ]);
            DB::commit();
            return ApiResponse::success($resources, "Successfully Updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/settings/division/destroy/{id}",
     *     tags={"Division"},
     *     summary="Delete an division",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Successfully Deleted"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not Found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string"),
     *             @OA\Property(property="message", type="string")
     *         )
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
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $resources = Division::findOrFail($id);
            $resources->delete();
            DB::commit();
            return ApiResponse::success(null, "Successfully Deleted", 204);
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::error($th->getMessage(), "Error", 500);
        }
    }
}
