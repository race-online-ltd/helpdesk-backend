<?php

namespace App\Http\Controllers\v1;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Priority;
use App\Models\Source;
use App\Models\Status;
use App\Models\User;
use App\Models\UserDumpMqToLocal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class UtilityController extends Controller
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
    public function getSource()
    {
        try {
            $resources = Source::all();
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
    public function getCcEmail()
    {
        try {
            $resources = DB::select("SELECT u.id,up.email_primary FROM users u
INNER JOIN user_profiles up ON up.user_id = u.id");
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getStatus()
    {
        try {
            $resources =
                Status::select('id', 'status_name')->get();
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getPriority()
    {
        try {
            $resources = Priority::select('id', 'priority_name')->get();
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

    public function resetPassword(Request $request)
    {

        try {
            $user = User::findOrFail($request->userId);
            $user->password = bcrypt($request->password);
            $user->save();

            return ApiResponse::success(null, "Password successfully reset", 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::error($th->getMessage(), "Error", 500);
        }
    }

    public function clientResetPassword(Request $request)
    {

        try {
            $user = User::findOrFail($request->userId);
            $user->password = bcrypt($request->password);
            $user->save();

            return ApiResponse::success(null, "Password successfully reset", 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return ApiResponse::error($th->getMessage(), "Error", 500);
        }
    }

    public function getSID()
    {
        try {

            // return 'test';
            // $resources = UserDumpMqToLocal::all();
            // $resources = UserDumpMqToLocal::pluck('SID');
            // $resources = UserDumpMqToLocal::pluck('SID')->toArray();
            // $resources = UserDumpMqToLocal::limit(10)->pluck('SID')->toArray();
            // $resources = UserDumpMqToLocal::paginate(100);
            // return response()->json($resources);

            $resources = [];

            // Process records in chunks of 1000
            UserDumpMqToLocal::chunk(100, function ($records) use (&$resources) {
                foreach ($records as $record) {
                    // Add the SID of each record to the $sids array
                    $resources[] = $record->SID;
                }
            });



            // $resources = DB::select("SELECT sid FROM helpdesk.user_dump_mq_to_locals limit 10");
            // return $resources;
            return ApiResponse::success($resources, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function fetchAndInsert()
    {
        $response = Http::get('https://webapp.race.net.bd/api/webappsiddump');

        if ($response->failed()) {
            return response()->json(['error' => 'Failed to fetch data from API'], 500);
        }

        $data = $response->json();

        $this->insertUserDumpData($data);

        return response()->json(['message' => 'Data processed successfully']);
    }

    private function insertUserDumpData($data)
    {
        foreach ($data as $row) {
            $exists = UserDumpMqToLocal::where('sid', $row['sid'])->exists();

            if (!$exists) {
                UserDumpMqToLocal::create([
                    'sid'         => $row['sid'],
                    'pppoe_name'  => $row['pppoe_name'] ?? null,
                    'entity_name' => $row['entity_name'],
                    'entity_id'   => $row['entity_id'],
                    'entity_code' => $row['entity_code'],
                    'entity_type' => $row['entity_type'],
                    'full_name'   => $row['full_name'],
                    'email'       => $row['email'] ?? null,
                    'phone'       => $row['phone'],
                    'password'    => $row['password'] ?? null,
                ]);
            }
        }
    }


    // public function getSID()
    // {
    //     try {
    //         // Create a generator function to yield SIDs
    //         $sids = function () {
    //             UserDumpMqToLocal::chunk(1000, function ($records) {
    //                 foreach ($records as $record) {
    //                     yield $record->SID;
    //                 }
    //             });
    //         };

    //         // Return the generator as a JSON response
    //         return ApiResponse::success(iterator_to_array($sids()), "Success", 200);
    //     } catch (\Exception $e) {
    //         // Handle any exceptions
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    public function sendSmsTest()
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->post('https://api.mimsms.com/api/SmsSending/SMS', [
            "UserName" => "api-orbitbd@race.net.bd", // এখানে তোমার MiM অ্যাকাউন্টের ইউজারনেম
            "Apikey" => "U96YG4DDUDTCH44", // এখানে তোমার API Key বসাও
            "MobileNumber" => "8801322811594", // যাকে SMS পাঠাতে চাও তার নাম্বার
            // "CampaignId" => null,
            "SenderName" => "8809643901301", // তোমার Sender ID
            "TransactionType" => "T", // Promotional হলে 'P', Transactional হলে 'T'
            "Message" => "This is a test SMS from Laravel with static data
            . hey baby"
        ]);

        // Response দেখানোর জন্য
        return response()->json([
            'status' => $response->status(),
            'body' => $response->json(),
        ]);
    }
}
