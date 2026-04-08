<?php

namespace App\Http\Controllers\v1\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponse;
use App\Models\ClientChildMapping;
use App\Models\Company;
use App\Models\User;
use App\Models\UserEntityMapping;
use App\Models\UserClientMapping;
use App\Models\UserTeamMapping;
use App\Models\UserProfile;


class UserRegisterController extends Controller
{

    public function index()
    {
        try {

            $clients = DB::select("SELECT u.id,u.username, c.company_name, uc.client_name,uc.business_entity_id,uc.created_at
                                    FROM helpdesk.users u, helpdesk.user_client_mappings uc ,helpdesk.companies c
                                    where u.id = uc.user_id
                                    and uc.business_entity_id = c.id");
            return ApiResponse::success($clients, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getAgent()
    {
        try {

            $agents = DB::select("SELECT u.id,u.username,up.fullname,up.email_primary,up.email_secondary,up.mobile_primary,up.mobile_secondary,u.password,up.role_id,r.name role_name,u.status,u.created_at,
                t.team_name,ut.team_id,t.department_id,d.department_name,t.division_id, di.division_name,up.default_entity_id,c.company_name
                FROM helpdesk.users u,helpdesk.user_team_mappings ut,helpdesk.teams t,helpdesk.departments d,
                helpdesk.divisions di,helpdesk.role_helpdesks r,helpdesk.companies c,helpdesk.user_profiles up
                where u.id = ut.user_id
                and up.role_id = r.id
                and u.id = up.user_id
                and ut.team_id = t.id
                and t.department_id = d.id
                and t.division_id = di.id
                and up.default_entity_id = c.id");


            $mergedData = [];

            foreach ($agents as $item) {
                $userId = $item->id;

                if (!isset($mergedData[$userId])) {
                    $mergedData[$userId] = [
                        'id' => $userId,
                        'username' => $item->username,
                        'fullname' => $item->fullname,
                        'email_primary' => $item->email_primary,
                        'email_secondary' => $item->email_secondary,
                        'mobile_primary' => $item->mobile_primary,
                        'mobile_secondary' => $item->mobile_secondary,
                        'role_id' => $item->role_id,
                        'role_name' => $item->role_name,
                        'default_entity_id' => $item->default_entity_id,
                        'company_name' => $item->company_name,
                        'department_id' => $item->department_id,
                        'department_name' => $item->department_name,
                        'division_id' => $item->division_id,
                        'division_name' => $item->division_name,
                        'status' => $item->status,
                        'teams' => []
                    ];
                }


                if ($item->team_id) {
                    $mergedData[$userId]['teams'][] = [
                        'team_id' => $item->team_id,
                        'team_name' => $item->team_name,

                    ];
                }
            }

            return ApiResponse::success(array_values($mergedData), "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getAgentOptions()
    {
        try {

            $agents = DB::select("SELECT u.id,u.username,up.fullname 
FROM users u
INNER JOIN user_profiles up
		ON up.user_id = u.id
where up.user_type = 'Agent'");

            return ApiResponse::success($agents, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    // public function getUserSerial($id)
    // {
    //     try {

    //         $businessEntity = Company::find($id);

    //         $prefix = $businessEntity->prefix;
    //         $lasRowId = User::select('id', 'username')->where('user_type', 'Client')->orderBy('created_at', 'desc')->first();
    //         $number = 1;
    //         $formattedNumber = sprintf("%05d", $number);
    //         if (empty($lasRowId)) {
    //             $resources = "{$prefix}-{$formattedNumber}";
    //             return ApiResponse::success($resources, "Success", 200);
    //         } else {
    //             $arrayConvert = explode("-", $lasRowId->username);
    //             $incrementNumber = intval($arrayConvert[1] + 1);
    //             $formattedNumber = sprintf("%05d", $incrementNumber);
    //             $resources = "{$prefix}-{$formattedNumber}";
    //             return ApiResponse::success($resources, "Success", 200);
    //         }
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    public function getUserSerial($id)
    {
        try {
            
            $businessEntity = Company::findOrFail($id);
            $prefix = $businessEntity->prefix;

            
            $lastUser = User::join('user_profiles as up', 'up.user_id', '=', 'users.id')
                ->where('up.user_type', 'Client')
                ->orderBy('users.created_at', 'desc')
                ->select('users.username')
                ->first();

            
            $number = 1;

            if ($lastUser && str_contains($lastUser->username, '-')) {
                $parts = explode('-', $lastUser->username);
                $number = intval($parts[1]) + 1;
            }

            $formattedNumber = sprintf("%05d", $number);
            $serial = "{$prefix}-{$formattedNumber}";

            return ApiResponse::success($serial, "Success", 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $businessEntityIds = $request->businessEntity;
            // $userClientIds = $request->client;
            $userClientIds = $request->defaultClient;
            $clients = $request->clientName;
            $userTeamIds = $request->teams;

            $clientChildIds = $request->childClient;
            $clientChildName = $request->childClientName;

            // return [$clientChildIds,$userClientIds];

            // $userStore = User::create([
            //     'user_type' => $request->userType,
            //     'username' => $request->userName,
            //     'fullname' => $request->fullName,
            //     'email_primary' => $request->primaryEmail,
            //     'email_secondary' => $request->secondaryEmail,
            //     'mobile_primary' => $request->primaryPhone,
            //     'mobile_secondary' => $request->secondaryPhone,
            //     'role_id' => $request->role,
            //     'default_entity_id' => $request->defaultBusinessEntity,
            //     'password' => bcrypt($request->password),
            //     'one_time_password' => $request->password,
            //     'lock' => $request->lock,
            //     'status' => $request->status,
            // ]);

            // $userId = $userStore->id;

            $user = User::create([
            'username' => $request->userName,
            'password' => bcrypt($request->password),
            'status' => $request->status
        ]);

        $userId = $user->id;

        // -----------------------------
        // user_profiles table insert
        // -----------------------------
        UserProfile::create([
            'user_id' => $userId,
            'user_type' => $request->userType,
            'fullname' => $request->fullName,
            'email_primary' => $request->primaryEmail,
            'email_secondary' => $request->secondaryEmail,
            'mobile_primary' => $request->primaryPhone,
            'mobile_secondary' => $request->secondaryPhone,
            'role_id' => $request->role,
            'default_entity_id' => $request->defaultBusinessEntity,
            'one_time_password' => $request->password,
        ]);

            if (is_array($userClientIds)) {
                $userClientIdsString = implode(',', $userClientIds);
                $businessEntityIdsString = implode(',', $businessEntityIds);
            } else {
                $userClientIdsString = $userClientIds;
                $businessEntityIdsString = $businessEntityIds; // Handle single value
            }

            if (is_array($businessEntityIds)) {
                $businessEntityIdsString = implode(',', $businessEntityIds);
            } else {
                $businessEntityIdsString = $businessEntityIds; // Handle single value
            }

                if ($businessEntityIds !== null) {
                    UserEntityMapping::create([
                        'business_entity_id' => $businessEntityIds,
                        'user_id' => $userId
                    ]);

                    UserClientMapping::create([
                        'client_id' => $userClientIds,
                        'client_name' => $clients,
                        'business_entity_id' => $businessEntityIds,
                        'user_id' => $userId
                    ]);


                    if (!empty($clientChildIds)) {
                        $flattenedClientChildIds = array_map(function ($item) {
                            return is_array($item) ? $item[0] : $item;
                        }, $clientChildIds);

                        foreach ($flattenedClientChildIds as $key => $clientChildId) {
                            if ($clientChildId == $userClientIds) {
                                continue;
                            }
                            ClientChildMapping::create([
                                'business_entity_id' => $request->businessEntity,
                                'client_id_helpdesk' => $userId,
                                'client_id_vendor' => $clientChildId,
                                'client_name' => $clientChildName[$key],
                            ]);
                        }
                    }

                }
          
            if ($userTeamIds !== null) {
                foreach ($userTeamIds as $userTeamId) {
                    UserTeamMapping::create([
                        'team_id' => $userTeamId,
                        'user_id' => $userId
                    ]);
                }
            }

            DB::commit();
            return ApiResponse::success($user, "Successfully Inserted", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e->getCode() === '23000') {
                preg_match("/Duplicate entry '([^']+)' for key '([^']+)'/", $e->getMessage(), $matches);
                if (count($matches) === 3) {
                    $duplicateValue = $matches[1];
                    return ApiResponse::error($e->getMessage(), "Duplicate entry {$duplicateValue}", 409);
                }
            }
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }



    public function insertClientNewEntity(Request $request)
    {
        try {

            $businessEntityId = $request->businessEntity;
            $defaultClientId = $request->defaultClient;
            $defaultClientName = $request->defaultClientName;
            $clientChildIds = $request->childClient;
            $clientChildNames = $request->childClientName;
            $userId = User::where('username', $request->userId)->first();

            $exists = UserClientMapping::where('client_id', $defaultClientId)
                ->where('business_entity_id', $businessEntityId)
                ->exists();
            if ($exists) {
                return ApiResponse::error('Error!', 'Default Client Already Exists', 500);
            }

            if (!empty($defaultClientId)) {

                UserEntityMapping::create([
                    'business_entity_id' => $businessEntityId,
                    'user_id' => $userId->id
                ]);

                UserClientMapping::create([
                    'business_entity_id' => $businessEntityId,
                    'user_id' => $userId->id,
                    'client_id' => $defaultClientId,
                    'client_name' => $defaultClientName,
                ]);
            }

            if (!empty($clientChildIds)) {
                foreach ($clientChildIds as $key => $clientChildId) {
                    ClientChildMapping::create([
                        'business_entity_id' => $businessEntityId,
                        'client_id_helpdesk' => $userId->id,
                        'client_id_vendor' => $clientChildId,
                        'client_name' => $clientChildNames[$key],
                    ]);
                }
            }
            return ApiResponse::success([], "Inserted Successfully", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }




    public function agentShow($id)
    {
        try {
            $agents = DB::select("SELECT u.id,up.user_type,u.username,up.fullname,up.email_primary,up.email_secondary,up.mobile_primary,up.mobile_secondary,u.password,up.role_id,r.name role_name,u.status,u.created_at,
            t.team_name,ut.team_id,
            t.department_id,d.department_name,
            t.division_id, di.division_name,
            up.default_entity_id,
            c.company_name
            FROM helpdesk.users u,
            helpdesk.user_profiles up,
            helpdesk.user_team_mappings ut,
            helpdesk.teams t,
            helpdesk.departments d,
            helpdesk.divisions di,
            helpdesk.role_helpdesks r,
            helpdesk.companies c
            where u.id = ut.user_id
            and up.role_id = r.id
            and u.id = up.user_id
            and ut.team_id = t.id
            and t.department_id = d.id
            and t.division_id = di.id
            and up.default_entity_id = c.id
            and u.id ='$id' ");


            $mergedData = [];

            foreach ($agents as $item) {
                $userId = $item->id;

                if (!isset($mergedData[$userId])) {
                    $mergedData[$userId] = [
                        'id' => $userId,
                        'user_type' => $item->user_type,
                        'username' => $item->username,
                        'fullname' => $item->fullname,
                        'email_primary' => $item->email_primary,
                        'email_secondary' => $item->email_secondary,
                        'mobile_primary' => $item->mobile_primary,
                        'mobile_secondary' => $item->mobile_secondary,
                        'role_id' => $item->role_id,
                        'role_name' => $item->role_name,
                        'default_entity_id' => $item->default_entity_id,
                        'company_name' => $item->company_name,
                        'department_id' => $item->department_id,
                        'department_name' => $item->department_name,
                        'division_id' => $item->division_id,
                        'division_name' => $item->division_name,
          
                        'status' => $item->status,
                        'teams' => []
                    ];
                }


                if ($item->team_id) {
                    $mergedData[$userId]['teams'][] = [
                        'team_id' => $item->team_id,
                        'team_name' => $item->team_name,

                    ];
                }
            }
            $responseObject = !empty($mergedData) ? array_values($mergedData)[0] : [];

            return ApiResponse::success($responseObject, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    public function clientShow($id)
    {
        try {
            $clients = DB::select("SELECT u.user_type,u.username,u.fullname,u.email_primary,u.email_secondary,u.mobile_primary,
                u.mobile_secondary,u.role_id,u.default_entity_id,u.password,u.one_time_password,u.password_change,
                u.lock,u.status, ue.business_entity_id,c.company_name, c.id,uc.client_id, uc.client_name,uc.business_entity_id, r.name role_name, uc.created_at
            FROM helpdesk.users u, helpdesk.user_client_mappings uc ,helpdesk.user_entity_mappings ue,
            helpdesk.companies c, helpdesk.role_helpdesks r
            where u.id = uc.user_id
            and u.id = ue.user_id
            and ue.business_entity_id = c.id
            and u.role_id = r.id
            and u.id = '$id'");


            $mergedData = [];

            foreach ($clients as $item) {
                $userId = $item->id;

                if (!isset($mergedData[$userId])) {
                    $mergedData[$userId] = [
                        'id' => $userId,
                        'user_type' => $item->user_type,
                        'username' => $item->username,
                        'fullname' => $item->fullname,
                        'email_primary' => $item->email_primary,
                        'email_secondary' => $item->email_secondary,
                        'mobile_primary' => $item->mobile_primary,
                        'mobile_secondary' => $item->mobile_secondary,
                        'role_id' => $item->role_id,
                        'role_name' => $item->role_name,
                        'default_entity_id' => $item->default_entity_id,
                        'company_name' => $item->company_name,
                        'lock' => $item->lock,
                        'status' => $item->status,
                        'businessEntity' => [],
                        'clients' => []
                    ];
                }
                if ($item->business_entity_id) {
                    $mergedData[$userId]['businessEntity'][] = [
                        'business_entity_id' => $item->business_entity_id,
                        'company_name' => $item->company_name,
                    ];
                }

                if ($item->client_id) {
                    $mergedData[$userId]['clients'][] = [
                        'client_id' => $item->client_id,
                        'client_name' => $item->client_name,
                    ];
                }
            }
            $responseObject = !empty($mergedData) ? array_values($mergedData)[0] : [];

            return ApiResponse::success($responseObject, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    // public function clientEdit($id)
    // {
    //     try {
    //         $clients = DB::select("SELECT u.*,c.company_name,ucm.user_id,ucm.client_id, ucm.client_name, ucm.created_at
    //         FROM helpdesk.user_client_mappings ucm, helpdesk.companies c, helpdesk.users u
    //         WHERE ucm.business_entity_id = c.id
    //         AND ucm.user_id = u.id
    //         and u.id = '$id'");

    //         return ApiResponse::success($clients, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }

    public function editClient($id, $businessId)
    {
        try {
            $responseObject = DB::select("SELECT u.user_type,u.username,u.fullname,u.email_primary,u.email_secondary,u.mobile_primary,
                u.mobile_secondary,u.role_id,u.default_entity_id,u.password,u.one_time_password,u.password_change,
                u.lock,u.status, c.company_name, ucm.user_id, ucm.client_id, ucm.client_name,ucm.business_entity_id, ucm.created_at
                FROM helpdesk.user_client_mappings ucm
                INNER JOIN helpdesk.companies c ON ucm.business_entity_id = c.id
                INNER JOIN helpdesk.users u ON ucm.user_id = u.id
                WHERE ucm.user_id ='$id' AND ucm.business_entity_id = '$businessId'");


            $responseEntity = DB::select("SELECT ccm.client_id_vendor, ccm.client_name
                                            FROM helpdesk.client_child_mappings ccm 
                                            INNER JOIN helpdesk.companies c ON ccm.business_entity_id = c.id
                                            where ccm.client_id_helpdesk = '$id'");


            $data = [
                'client_data' => $responseObject,
                'company_entities' => $responseEntity,
            ];

            return ApiResponse::success($data, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function updateClient($id, Request $request)
    {

        try {

            DB::beginTransaction();

            $clientChildIds = $request->childClient;
            $clientChildName = $request->childClientName;

            $clientCheck = UserClientMapping::where('user_id', $id)->where('business_entity_id', $request->businessEntity)
                ->first();

            if ($clientCheck) {

                $user = User::findOrFail($id);
                $user->update([
                    'fullname' => $request->fullName,
                    'email_primary' => $request->primaryEmail,
                    'email_secondary' => $request->secondaryEmail,
                    'mobile_primary' => $request->primaryPhone,
                    'mobile_secondary' => $request->secondaryPhone,
                    'role_id' => $request->role,
                    'default_entity_id' => $request->defaultBusinessEntity,
                    // 'password' => bcrypt($request->password),
                    'lock' => $request->lock,
                    'status' => $request->status,
                ]);



                //     $resource = UserClientMapping::where('user_id', $id)->where('business_entity_id', $request->businessEntity)
                // ->first();

                $resource = UserClientMapping::where('user_id', $id)->where('business_entity_id', $request->businessEntity)
                    ->delete();


                UserClientMapping::create([
                    'client_id' => $request->defaultClient,
                    'client_name' => $request->clientName,
                    'business_entity_id' => $request->businessEntity,
                    'user_id' => $user->id
                ]);


                ClientChildMapping::where('business_entity_id', $request->businessEntity)
                    ->where('client_id_helpdesk', $user->id)
                    ->delete();

                foreach ($clientChildIds as $key => $clientChildId) {

                    ClientChildMapping::create([
                        'business_entity_id' => $request->businessEntity,
                        'client_id_helpdesk' => $user->id,
                        'client_id_vendor' => $clientChildId,
                        'client_name' => $clientChildName[$key],
                    ]);
                }


                DB::commit();
                return ApiResponse::success(null, "Client and related user(s) updated successfully", 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }







    public function update(Request $request, $id)
    {

        DB::beginTransaction();
        try {

            $businessEntityIds = $request->businessEntity;
            $userClientIds = $request->client;
            $clients = $request->clientName;
            $userTeamIds = $request->teams;
            $user = User::findOrFail($id);

            $user->update([
                'user_type' => $request->userType,
                'username' => $request->userName,
                'fullname' => $request->fullName,
                'email_primary' => $request->primaryEmail,
                'email_secondary' => $request->secondaryEmail,
                'mobile_primary' => $request->primaryPhone,
                'mobile_secondary' => $request->secondaryPhone,
                'role_id' => $request->role,
                // 'default_entity_id' => $request->defaultBusinessEntity,
                // 'password' => bcrypt($request->password),
                // 'password_change' => 1,
                'lock' => $request->lock,
                'status' => $request->status,
            ]);



            if ($businessEntityIds !== null) {
                $resource = UserEntityMapping::where('user_id', $id)->delete();
                foreach ($businessEntityIds as $businessEntityId) {
                    UserEntityMapping::create([
                        'business_entity_id' => $businessEntityId,
                        'user_id' => $id
                    ]);

                    if ($userClientIds !== null) {
                        $resource = UserClientMapping::where('user_id', $id)->delete();

                        foreach ($userClientIds as $key => $userClientId) {
                            UserClientMapping::create([
                                'client_id' => $userClientId,
                                'client_name' => $clients[$key],
                                'business_entity_id' => $businessEntityId,
                                'user_id' => $id
                            ]);
                        }
                    }
                }
            }


            if ($userTeamIds !== null) {
                $resource = UserTeamMapping::where('user_id', $id)->delete();
                foreach ($userTeamIds as $userTeamId) {
                    UserTeamMapping::create([
                        'team_id' => $userTeamId,
                        'user_id' => $id
                    ]);
                }
            }

            DB::commit();
            return ApiResponse::success($user, "Successfully Updated", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($e->getCode() === '23000') {
                preg_match("/Duplicate entry '([^']+)' for key '([^']+)'/", $e->getMessage(), $matches);
                if (count($matches) === 3) {
                    $duplicateValue = $matches[1];
                    return ApiResponse::error($e->getMessage(), "Duplicate entry {$duplicateValue}", 409);
                }
            }
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getClientsByDefaultEntity(Request $request)
    {
        try {
            // $rowCount = UserClientMapping::where('user_id', $id)->count();
            // $clients = UserClientMapping::where('user_id', $id)
            //     ->select('client_id', 'client_name')
            //     ->get();

            $businessEntityIds = $request->businessEntity;
            $id = $request->userId;
            $userType = $request->userType;

            if ($userType === "Customer") {
                $rowCount = DB::table(DB::raw("(SELECT DISTINCT(cpm.client_id), cpm.client_name, cpm.user_id, dm.entity_type
                                FROM helpdesk.customer_parent_mappings cpm
                                JOIN helpdesk.user_dump_mq_to_locals dm
                                ON cpm.client_id = dm.entity_id
                                WHERE cpm.user_id = '$id') as combined"))
                    ->count();


                $clients = DB::select("SELECT DISTINCT(cpm.client_id),cpm.client_name,cpm.user_id, dm.entity_type
                                FROM helpdesk.customer_parent_mappings cpm, helpdesk.user_dump_mq_to_locals dm
                                WHERE cpm.client_id = dm.entity_id
                                AND cpm.user_id = '$id' ");


                $mergeData = [
                    'rowCount' => $rowCount,
                    'clients' => $clients
                ];
                return ApiResponse::success($mergeData, "Success", 200);
            } else {
                $rowCount = DB::table(DB::raw("(SELECT ccm.client_id_vendor AS client_id, ccm.client_name 
                            FROM helpdesk.client_child_mappings ccm 
                            WHERE ccm.client_id_helpdesk = '$id'
                            AND ccm.business_entity_id = '$businessEntityIds'

                             UNION

                                SELECT ucm.client_id, ucm.client_name 
                                FROM helpdesk.user_client_mappings ucm 
                                WHERE ucm.user_id = '$id'
                                AND ucm.business_entity_id = '$businessEntityIds') AS combined"))->count();

                $clients = DB::select("SELECT ucm.client_id, ucm.client_name 
                                    FROM helpdesk.user_client_mappings ucm 
                                    WHERE ucm.user_id = '$id'
                                    AND ucm.business_entity_id = '$businessEntityIds'
                                                
                                                

                                    UNION

                                    SELECT ccm.client_id_vendor AS client_id, ccm.client_name 
                                    FROM helpdesk.client_child_mappings ccm 
                                    WHERE ccm.client_id_helpdesk = '$id'
                                    AND ccm.business_entity_id = '$businessEntityIds' ");

                $mergeData = [
                    'rowCount' => $rowCount,
                    'clients' => $clients
                ];
                return ApiResponse::success($mergeData, "Success", 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getAgentByDefaultBusinessEntityTeam($id)
    {

        try {

            $slectedTeamId = Company::where('id', $id)->first();

            if ($slectedTeamId->team_id) {
                $defaultAgents = DB::select("SELECT u.id, up.fullname,utm.team_id FROM helpdesk.user_team_mappings utm ,helpdesk.users u,helpdesk.user_profiles up
                where up.user_id = u.id
                and u.id = utm.user_id
                and utm.team_id = '$slectedTeamId->team_id' ");
                return ApiResponse::success($defaultAgents, "Success", 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function getAgentByTeam($id)
    {
        try {

            $agents = DB::select("SELECT 
                u.id,
                up.fullname
            FROM helpdesk.user_team_mappings utm
            INNER JOIN users u 
                ON u.id = utm.user_id
            INNER JOIN user_profiles up 
                ON up.user_id = u.id
            WHERE utm.team_id = '$id' ");


            return ApiResponse::success($agents, "Success", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function DeleteUser($userId)
    {
        DB::beginTransaction();
        try {
            $existingUser = User::find($userId);

            if (!$existingUser) {
                return ApiResponse::error("User not found", 404);
            }

            UserEntityMapping::where('user_id', $userId)->delete();
            UserClientMapping::where('user_id', $userId)->delete();
            UserTeamMapping::where('user_id', $userId)->delete();

            $existingUser->delete();

            DB::commit();

            return ApiResponse::success(null, "User and related data deleted successfully", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    public function getAgentAll()
    {
        try {

            $agents = DB::select("SELECT u.id, u.username, up.fullname 
            FROM helpdesk.users u 
             INNER JOIN helpdesk.user_profiles up ON up.user_id = u.id
            WHERE up.user_type = 'Agent'");



            return ApiResponse::success($agents, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
}
