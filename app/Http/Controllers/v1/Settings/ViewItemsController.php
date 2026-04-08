<?php

namespace App\Http\Controllers\v1\Settings;

use App\Models\PageItem;
use App\Models\SettingsItem;
use App\Models\SidebarItem;
use App\Models\RoleHelpdesk;
use App\Models\RolePermission;
use App\Helpers\ApiResponse;


use App\Http\Controllers\Controller;
use App\Models\DashboardItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ViewItemsController extends Controller
{
    public function index()
    {
        try {
            $role = DB::select("SELECT * FROM helpdesk.role_helpdesks");

            return ApiResponse::success($role, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
    public function defaultClientRole()
    {
        try {
            $defaultRole = DB::select("SELECT * FROM helpdesk.role_helpdesks
            where default_type = 'Client'");

            return ApiResponse::success($defaultRole, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
    public function defaultAgentRole()
    {
        try {
            $defaultRole = DB::select("SELECT * FROM helpdesk.role_helpdesks
            where default_type = 'Agent'");

            return ApiResponse::success($defaultRole, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function fetchSidebarItems()
    {
        //        return 'hi to all';
        try {
            $showSidebarItems = SidebarItem::select('id', 'name')->get();

            return ApiResponse::success($showSidebarItems, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function fetchDashboardItems()
    {
        //        return 'hi to all';
        try {
            $showDashboardItems = DashboardItem::select('id', 'name')->get();

            return ApiResponse::success($showDashboardItems, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function fetchSettingItems()
    {
        //        return 'hi to all';

        //        dd('hello');
        try {
            $showSettingItems = SettingsItem::select('id', 'name')->orderBy('id')->get();

            return ApiResponse::success($showSettingItems, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function fetchPageDetails($id)
    {
        try {
            // Assuming sidebar_id is the field that relates to PermissionItem
            $showPageItems = PageItem::where('sidebar_id', $id)->orderBy('id')->get();
            return ApiResponse::success($showPageItems, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $existingRole = RoleHelpdesk::where('name', $request->roleName)->first();

            if ($existingRole) {
                return ApiResponse::success($existingRole, "Role already exists!", 409);
            }

            if ($request->defaultRole !== null) {
                $existingDefaultID = RoleHelpdesk::where('default_type', $request->defaultRole)->first();

                if ($existingDefaultID) {
                    return ApiResponse::success($existingDefaultID, "Default Role already exists for Business Entity!", 409);
                }
            }

            // Create role
            $role = RoleHelpdesk::create([
                'default_type' => $request->defaultRole,
                'name' => $request->roleName,
            ]);

            $roleId = $role->id;

            // Check if permissions and PageItems are arrays
            if (is_array($request->permissions) && is_array($request->pagePermissions)) {
                foreach ($request->permissions as $sidebarId) {
                    foreach ($request->pagePermissions as $permissionId) {
                        // Verify if the permission exists in the database
                        $permissionExists = PageItem::where('sidebar_id', $sidebarId)
                            ->where('id', $permissionId)
                            ->exists();

                        // If permission exists, create a RolePermission record
                        if ($permissionExists) {
                            RolePermission::create([
                                'role_id' => $roleId,
                                'sidebar_id' => $sidebarId,
                                'permission_id' => $permissionId,
                            ]);
                        } else {
                            // Log an error or return a message if the permission doesn't exist
                            // You can use Laravel's logging or debug tools for this
                            \Log::info("Permission not found: sidebar_id={$sidebarId}, permission_id={$permissionId}");
                        }
                    }
                }
            } else {
                return ApiResponse::error("Invalid data format for permissions or pagePermissions", 400);
            }

            DB::commit();
            return ApiResponse::success($role, "Successfully Inserted", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
}
