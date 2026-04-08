<?php

namespace App\Http\Controllers\v1\Settings;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\CustomerParentMapping;
use App\Models\User;
use App\Models\BusinessEntityWiseClient;
use App\Models\UserClientMapping;
use App\Models\UserDumpMqToLocal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use App\Services\TokenServiceInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Jobs\CompareAndSyncClientsJob;
use App\Models\Company;
use App\Models\UserEntityMapping;
use App\Models\UserTeamMapping;
use App\Models\UserProfile;

class UserLoginController extends Controller
{
    protected $tokenServices;

    public function __construct(TokenServiceInterface $tokenServices)
    {
        $this->tokenServices = $tokenServices;
    }

    public function FetchUser()
    {
        return User::all();
    }

    //    public function Login(Request $request)
    //    {
    //        DB::beginTransaction();
    //        try {
    //
    //            $credentials = [
    //                'username' => $request->input('username'),
    //                'password' => $request->input('password'),
    //            ];
    //
    //            if (Auth::attempt($credentials)) {
    //                $user = Auth::user();
    //                if ($user->status !== 1) {
    //                    Auth::logout();
    //                    return ApiResponse::error([], 'Your account is not active.', 403);
    //                }
    //                return ApiResponse::success($user, "success", 200);
    //            } else {
    //                return ApiResponse::error([], 'Invalid credentials', 409);
    //            }
    //        } catch (\Exception $e) {
    //            DB::rollBack();
    //            return ApiResponse::error($e->getMessage(), "Error", 500);
    //        }
    //    }

    //    public function Login(Request $request)
    //    {
    //        DB::beginTransaction();
    //        try {
    //            $credentials = [
    //                'username' => $request->input('username'),
    //                'password' => $request->input('password'),
    //            ];
    //
    //            if (Auth::attempt($credentials)) {
    //                $user = Auth::user();
    //
    //                // Check if the user account is active
    //                if ($user->status !== 1) {
    //                    Auth::logout();
    //                    return ApiResponse::error([], 'Your account is not active.', 403);
    //                }
    //
    //
    //                $username = $user->username;
    //
    //                $userDetails = DB::select("
    //                SELECT DISTINCT si.name,rp.sidebar_id
    //                FROM users u
    //                JOIN role_permissions rp ON u.role_id = rp.role_id
    //                JOIN sidebar_items si ON rp.sidebar_id = si.id
    //                WHERE u.username = ?
    //                ", [$username]);
    //
    //                $sidebarNames = array_map(fn($detail) => $detail->name, $userDetails);
    //
    //                $userPermission = DB::select("
    //                SELECT DISTINCT si.name sidebar_name,rp.sidebar_id, pi.name permission_name
    //                FROM users u
    //                JOIN role_permissions rp ON u.role_id = rp.role_id
    //                JOIN sidebar_items si ON rp.sidebar_id = si.id
    //                JOIN page_items pi ON pi.sidebar_id = si.id
    //                WHERE u.username = ?
    //                ", [$username]);
    //
    //                $permissionNames = array_map(fn($detail) => $detail->permission_name, $userPermission);
    //
    //                return ApiResponse::success([
    //                    'user' => $user,
    ////                    'details' => $userDetails,
    //                    'sidebar_names' => $sidebarNames,
    //                    'permission_names' => $permissionNames
    //                ], "success", 200);
    //                // Return success response with user details
    ////                return ApiResponse::success(['user' => $user, 'details' => $userDetails], "success", 200);
    //            } else {
    //                return ApiResponse::error([], 'Invalid credentials', 409);
    //            }
    //        } catch (\Exception $e) {
    //            DB::rollBack();
    //            return ApiResponse::error($e->getMessage(), "Error", 500);
    //        }
    //    }

    public function Login(Request $request)
    {

        try {
            $credentials = [
                'username' => $request->input('username'),
                'password' => $request->input('password'),
            ];

            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                if ($user->status !== 1) {
                    Auth::logout();
                    return ApiResponse::error([], 'Your account is not active.', 403);
                }

                // ✅ Sanctum Token Generate
            $token = $user->createToken('helpdesk-api')->plainTextToken;
           
                $username = $user->username;
                $userDetails = DB::select("SELECT DISTINCT u.username, up.user_type, u.id,up.password_change, si.name AS sidebar_names, up.fullname, up.email_primary, up.role_id, up.default_entity_id, rh.name AS role_name, 
                                    rh.default_type AS default_role, rp.sidebar_id, 
                                    pi.name AS permission_name, pi.id AS page_id,
                                    utm.team_id,
                                    utm.team_id AS user_teams,
                                    teams.team_name AS user_team_names
                    FROM helpdesk.users u
                    LEFT JOIN helpdesk.user_profiles up ON u.id = up.user_id
                    LEFT JOIN helpdesk.companies c ON c.id = up.default_entity_id
                    LEFT JOIN helpdesk.role_helpdesks rh ON up.role_id = rh.id
                    LEFT JOIN helpdesk.role_permissions rp ON up.role_id = rp.role_id
                    LEFT JOIN helpdesk.sidebar_items si ON rp.sidebar_id = si.id
                    LEFT JOIN helpdesk.page_items pi ON pi.id = rp.permission_id
                    LEFT JOIN helpdesk.user_team_mappings utm ON utm.user_id = u.id
                    LEFT JOIN helpdesk.teams ON utm.team_id = teams.id
                    WHERE u.username = ?
                ", [$username]);

                if (empty($userDetails)) {
                    return ApiResponse::error([], 'User details not found', 404);
                }

                 // ✅ THIRD PARTY USER RESPONSE
            if ($user->username === 'super-app') {

                $response = [
                    'user_id' => $user->id,
                    'name'    => $user->username,
                    'fullname' => $userDetails[0]->fullname ?? null,
                    'email_primary' => $userDetails[0]->email_primary ?? null,
                ];

                return ApiResponse::success($response, "success", 200, $token);
            }
                $sidebarNames = array_values(array_unique(array_map(fn($detail) => $detail->sidebar_names, $userDetails)));
                $sidebarIDs = array_values(array_unique(array_map(fn($detail) => (int)$detail->sidebar_id, $userDetails)));
                $pagerPermissionNames = array_values(array_unique(array_map(fn($detail) => $detail->permission_name, $userDetails)));
                $pagerPermissionIDs = array_values(array_unique(array_map(fn($detail) => $detail->page_id, $userDetails)));
                $userTeamIDs = array_values(array_unique(array_map(fn($detail) => $detail->user_teams, $userDetails)));
                $userTeamNames = array_values(array_unique(array_map(fn($detail) => $detail->user_team_names, $userDetails)));

                $result = [
                    'id' => $userDetails[0]->id ?? null,
                    'username' => $userDetails[0]->username ?? null,
                    'type' => $userDetails[0]->user_type ?? null,
                    'team_id' => $userDetails[0]->team_id ?? null,
                    'user_teams' => $userTeamIDs,
                    'user_team_names' => $userTeamNames,
                    'sidebar_names' => $sidebarNames,
                    'sidebar_ids' => $sidebarIDs,
                    'fullname' => $userDetails[0]->fullname ?? null,
                    'email_primary' => $userDetails[0]->email_primary ?? null,
                    'role_id' => $userDetails[0]->role_id ?? null,
                    'default_entity_id' => $userDetails[0]->default_entity_id ?? null,
                    'role_name' => $userDetails[0]->role_name ?? null,
                    'default_role' => $userDetails[0]->default_role ?? null,
                    'permission_names' => $pagerPermissionNames,
                    'permission_ids' => $pagerPermissionIDs,
                    'password_change' => $userDetails[0]->password_change ?? null,
               
                ];

                return ApiResponse::success($result, "success", 200,$token);
            } else {

                $SID = substr($credentials['username'], 0, 3);

                $userExist = User::where('username', $credentials['username'])->first();

                if ($userExist) {
                    return ApiResponse::success('Error', 'Invalid credentials', 409);
                }


                if ($SID === "SID") {

                    $customer = UserDumpMqToLocal::where('sid', $credentials['username'])->first();

                    if (!$customer) {
                        return ApiResponse::success([], 'Invalid credentials', 409);
                    }


                    if (
                        $customer->sid !== $credentials['username'] ||
                        (!empty($customer->password) && $customer->password !== $credentials['password'])
                    ) {
                        return ApiResponse::success([], 'Invalid credentials', 409);
                    }

                    if (User::where('username', $customer->sid)->exists()) {
                        return ApiResponse::success([], 'User already exists', 409);
                    }


                    $defaultRoleId = DB::table('helpdesk.role_helpdesks')
                        ->where('default_type', 'Customer')
                        ->value('id');

                    $entityType = $customer->entity_type;
                    $defaultBusinessEntityId = in_array($entityType, ['AO', 'ZO', 'HO']) ? 9 : (in_array($entityType, ['RESELLER', 'SUBRESELLER']) ? 8 : null);


                    $insertedUser = User::firstOrCreate(
                        ['username' => $customer->sid],
                        [
                            'user_type'         => "Customer",
                            'fullname'          => $customer->full_name,
                            'email_primary'     => $customer->email,
                            'mobile_primary'    => $customer->phone,
                            'role_id'           => $defaultRoleId,
                            'default_entity_id' => $defaultBusinessEntityId,
                            'password'          => bcrypt($customer->password ?: '1234'),
                            'one_time_password' => $customer->password ?: '1234',
                            'password_change'   => 1,
                            'lock'              => 0,
                            'status'            => 1,
                        ]
                    );

                    if (CustomerParentMapping::where('user_id', $insertedUser->id)->exists()) {
                        return ApiResponse::error([], 'Customer mapping already exists', 409);
                    }
                    CustomerParentMapping::firstOrCreate(
                        ['user_id' => $insertedUser->id],
                        [
                            'client_id'   => $customer->entity_id,
                            'client_name' => $customer->entity_name,
                        ]
                    );

                    return ApiResponse::success($insertedUser, 'customer', 200);
                }
            }
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }







    // public function Logout(Request $request)
    // {
    
    //     Auth::logout(); // Log the user out

    //     // Invalidate the session
    //     Session::invalidate();

    //     // Regenerate the session token
    //     Session::regenerateToken();

    //     return ApiResponse::success(null, "Logout successful", 200);
    // }
public function Logout(Request $request)
{
    $user = $request->user();

    if (!$user) {
        return ApiResponse::error([], "Unauthenticated", 401);
    }

    $user->tokens()->delete();

    return ApiResponse::success(null, "Logout successful", 200);
}

    public function changePassword(Request $request)
    {

        $user = User::find($request->id);

        if (!$user) {
            return ApiResponse::success([], "User not found", 404);
        }

        // if (!Hash::check($request->oldPassword, $user->password)) {
        //     return ApiResponse::success([], "Old password is incorrect", 200);
        // }

        $user->password = Hash::make($request->newPassword);
        $user->save();


      // Update password_change in user_profiles table
      $profile = UserProfile::where('user_id', $user->id)->first();

      if ($profile) {
          $profile->password_change = 0;
          $profile->save();
      }
        return ApiResponse::success([], "Password changed successfully", 200);
    }

    public function oracleTest()
    {
        $entitylist = DB::connection('oracle')->select("
        SELECT ENTITY_NAME AS entityname
        FROM ROL_LIVE.operational_entity_vw
        WHERE entity_type IN ('RESELLER','SUBRESELER')
        ORDER BY entityname");

        dd($entitylist);
        return $entitylist;
    }



    public function active()
    {
        try {
            $resource = DB::connection('maxim')->select("SELECT COUNT(customer_id) active
                from racemaxim.Home_Conn h, racemaxim.oms_users o, racemaxim.plans p
                where h.plan_id=p.id and o.id=h.user_id
                and h.STATUS IN ('0','1')
                AND LS = 0
                AND h.expiry_date >= TRUNC(SYSDATE) - INTERVAL '14' HOUR");

            return response()->json([
                'status' => true,
                'result' => $resource
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'result' => $th->getMessage()
            ]);
        }
    }


    public function test()
    {

        return $this->tokenServices->generateToken()['DhakaColo'];
        $RaceTokens = implode('', $this->tokenServices->generateToken()['Race']);
        $EarthTokens = implode('', $this->tokenServices->generateToken()['Earth']);
        $DhakaColoTokens = implode('', $this->tokenServices->generateToken()['DhakaColo']);



        $RaceLoggedIn = $this->tokenServices->loginWithToken($RaceTokens);
        $EarthColoLoggedIn = $this->tokenServices->loginWithToken($EarthTokens);
        $DhakaColoLoggedIn = $this->tokenServices->loginWithToken($DhakaColoTokens);


        $response = [
            'status' => 'success',
            'data' => [
                [
                    'service' => 'Race',
                    'access_token' => $RaceTokens,
                    'sessionid' => $RaceLoggedIn['Race'],
                ],
                [
                    'service' => 'Earth',
                    'access_token' => $EarthTokens,
                    'sessionid' => $EarthColoLoggedIn['Earth'],
                ],
                [
                    'service' => 'DhakaColo',
                    'access_token' => $DhakaColoTokens,
                    'sessionid' => $DhakaColoLoggedIn['DhakaColo'],
                ]
            ]
        ];

        return response()->json($response);
    }

    public function dhakaColoCustomers()
    {
        // $url = 'http://dhakacolo.prismerp.net/customer/api/get_all';
        $url = 'http://dhakacolo.prismerp.net/customer/api/combo/?rows=20000';


        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer 14ee0edecdca44e5ad11f2ae146ad404',
                'Accept' => 'application/json',
            ])->withCookies([
                'csrftoken' => 'ZA9hw26FO9gTFzVABv8Xyrz9vMtqDrHC',
                'sessionid' => '45vw6g3z07qfnj5xj7uhi3x2x978qifa',
            ], 'dhakacolo.prismerp.net')
                ->get($url);
               
            return $response->json();
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function dhakaColoCustomerDetails($id)
    {
        $url = "http://dhakacolo.prismerp.net/customer/api/{$id}/customer_data/";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer 14ee0edecdca44e5ad11f2ae146ad404',
                'Accept' => 'application/json',
            ])->withCookies([
                'csrftoken' => 'ZA9hw26FO9gTFzVABv8Xyrz9vMtqDrHC',
                'sessionid' => '45vw6g3z07qfnj5xj7uhi3x2x978qifa',
            ], 'dhakacolo.prismerp.net')
                ->get($url);

            return $response->json();
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function earthCustomers()
    {
        $url = 'http://earth.prismerp.net/customer/api/combo/?rows=20000';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer a58f63ad089a4c47b3a919dc186e55ca',
                'Accept' => 'application/json',
            ])->withCookies([
                'csrftoken' => 'Xrc8I7DhhquGI0VzItF9mxZAJqG7wk98',
                'sessionid' => 'lq3tto38sbrwo2qxqa3kfc30q6aacrc3',
            ], 'earth.prismerp.net')
                ->get($url);

           
            $data = $response->json();

            return $data;
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function earthCustomerDetails($id)
    {
        $url = "http://earth.prismerp.net/customer/api/{$id}/customer_data/";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer a58f63ad089a4c47b3a919dc186e55ca',
                'Accept' => 'application/json',
            ])->withCookies([
                'csrftoken' => 'Xrc8I7DhhquGI0VzItF9mxZAJqG7wk98',
                'sessionid' => 'lq3tto38sbrwo2qxqa3kfc30q6aacrc3',
            ], 'earth.prismerp.net')
                ->get($url);

            return $response->json();
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }



    public function raceCustomers()
    {
        // $url = 'http://dhakacolo.prismerp.net/customer/api/get_all';
        $url = 'http://race.prismerp.net/customer/api/combo/?rows=20000';

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer 5788e72f9c004906b9459a9f2e8f7af2',
                'Accept' => 'application/json',
            ])->withCookies([
                'sessionid' => 'qw13jw3pd33vgn4xg34vpcqt8cx06xj7',
            ], 'race.prismerp.net')
                ->get($url);

                CompareAndSyncClientsJob::dispatch(
                  5,
                  $response
              );

              
            return $response->json();
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function raceCustomerDetails($id)
    {
        $url = "http://race.prismerp.net/customer/api/{$id}/customer_data/";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer 5788e72f9c004906b9459a9f2e8f7af2',
                'Accept' => 'application/json',
            ])->withCookies([
                'sessionid' => 'qw13jw3pd33vgn4xg34vpcqt8cx06xj7',
            ], 'race.prismerp.net')
                ->get($url);

            return $response->json();
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
}

