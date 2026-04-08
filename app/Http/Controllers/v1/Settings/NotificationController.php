<?php

namespace App\Http\Controllers\v1\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Notification;
use App\Helpers\ApiResponse;

class NotificationController extends Controller
{
    /**
     * @OA\Tag(
     *     name="Notification",
     *     description="Notification related endpoints"
     * )
     */


    /**
     * @OA\Get(
     *     path="/api/v1/settings/email/notification/show",
     *     tags={"Notification"},
     *     summary="Get all notification",
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
            $resources = DB::table('notifications as n')
            ->join('email_templates as e', 'n.email_template_id', '=', 'e.id')
            ->select('n.id', 'n.notification_name', 'e.template_name', 'n.client', 'n.status', 'n.created_at')
            ->get();
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500); 
        }
    }

    /**
     * @OA\Post(
     *     path="/api/v1/settings/email/notification/store",
     *     tags={"Notification"},
     *     summary="Create a new notification",
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
            $exist = Notification::where('notification_name', $request->notificationName)->first();

            if ($exist) {
                return ApiResponse::success($exist, "Notification already exists!", 409);
            }

            $resources = Notification::create([
                'notification_name' => $request->notificationName,
                'email_template_id' => $request->emailTemplate,
                'client' => $request->client,
                'status' => $request->status,
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
     *     path="/api/v1/settings/email/notification/show/{id}",
     *     tags={"Notification"},
     *     summary="Get an notification by ID",
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
            $resources = Notification::findOrFail($id);
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500); 
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/settings/email/notification/update/{id}",
     *     tags={"Notification"},
     *     summary="Update an notification",
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
            $resources = Notification::findOrFail($id);
            $resources->update([
                'notification_name' => $request->notificationName,
                'email_template_id' => $request->emailTemplate,
                'client' => $request->client,
                'status' => $request->status,   
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
     *     path="/api/v1/settings/email/notification/destroy/{id}",
     *     tags={"Notification"},
     *     summary="Delete an notification",
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
            $resources = Notification::findOrFail($id);
            $resources->delete();
            DB::commit();
            return ApiResponse::success(null, "Successfully Deleted", 204);
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::error($th->getMessage(), "Error", 500); 
        }
    }
}
