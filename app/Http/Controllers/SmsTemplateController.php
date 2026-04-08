<?php
namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Models\CloseTicket;
use App\Models\OpenTicket;
use App\Models\SmsTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SmsTemplateController extends Controller
{
    // GET settings/sms/show
    // public function index()
    // {
    //     try {
    //         $resources = DB::table('sms_templates as st')
    //             ->join('companies as c',        'c.id',      '=', 'st.business_entity_id')
    //             ->leftJoin('user_profiles as up', 'up.user_id', '=', 'st.client_id')
    //             ->leftJoin('event_tags as e',         'e.id',      '=', 'st.event_id')
    //             ->select(
    //                 'st.id',
    //                 'st.key',
    //                 'st.template_name',
    //                 'st.template',
    //                 'st.status',
    //                 'st.business_entity_id',
    //                 'c.company_name',
    //                 'st.client_id',
    //                 'up.fullname as client_name',
    //                 'st.event_id',
    //                 'e.event_name',
    //                 'st.created_at',
    //             )
    //             ->orderBy('st.created_at', 'desc')
    //             ->get();

    //         return ApiResponse::success($resources, "SMS templates fetched successfully.", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    public function index()
    {
        try {
            $resources = DB::table('sms_templates as st')
                ->join('companies as c', 'c.id', '=', 'st.business_entity_id')
                ->leftJoin('user_profiles as up', 'up.user_id', '=', 'st.client_id')
                ->leftJoin('event_tags as e', 'e.id', '=', 'st.event_id')
                ->leftJoinSub(
                    DB::table('sms_templates as st2')
                        ->selectRaw("
                            st2.id as template_id,
                            GROUP_CONCAT(upp.fullname SEPARATOR ', ') AS excluded_fullnames
                        ")
                        ->join(DB::raw("
                            JSON_TABLE(
                                COALESCE(st2.exclude_notify, '[]'),
                                '\$[*]' COLUMNS (exclude_user_id INT PATH '\$')
                            ) AS jt
                        "), DB::raw('1'), '=', DB::raw('1'))
                        ->leftJoin('user_profiles as upp', 'upp.user_id', '=', DB::raw('jt.exclude_user_id'))
                        ->groupBy('st2.id'),
                    'excl',
                    'excl.template_id',
                    '=',
                    'st.id'
                )
                ->select(
                    'st.id',
                    'st.key',
                    'st.template_name',
                    'st.template',
                    'st.status',
                    'st.business_entity_id',
                    'c.company_name',
                    'st.client_id',
                    'up.fullname as client_name',
                    'st.event_id',
                    'e.event_name',
                    'st.exclude_notify',
                    'excl.excluded_fullnames',
                    'st.created_at',
                )
                ->orderBy('st.id', 'desc')
                ->get()
                ->map(function ($item) {
                    $item->exclude_notify = $item->exclude_notify
                        ? json_decode($item->exclude_notify, true)
                        : [];
                    return $item;
                });

            return ApiResponse::success($resources, "SMS templates fetched successfully.", 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    // GET settings/sms/show/{id}
    // public function show($id)
    // {
    //     try {
    //         $resource = DB::table('sms_templates as st')
    //             ->join('companies as c',          'c.id',      '=', 'st.business_entity_id')
    //             ->leftJoin('user_profiles as up',  'up.user_id', '=', 'st.client_id')
    //             ->leftJoin('event_tags as e',          'e.id',      '=', 'st.event_id')
    //             ->select(
    //                 'st.id',
    //                 'st.key',
    //                 'st.template_name',
    //                 'st.template',
    //                 'st.status',
    //                 'st.business_entity_id',
    //                 'c.company_name',
    //                 'st.client_id',
    //                 'up.fullname as client_name',
    //                 'st.event_id',
    //                 'e.event_name',
    //                 'st.created_at',
    //             )
    //             ->where('st.id', $id)
    //             ->first();

    //         if (!$resource) {
    //             return ApiResponse::error("SMS template not found.", "Not Found", 404);
    //         }

    //         return ApiResponse::success($resource, "SMS template fetched successfully.", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }

    public function show($id)
    {
        try {
            $resource = DB::table('sms_templates as st')
                ->join('companies as c', 'c.id', '=', 'st.business_entity_id')
                ->leftJoin('user_profiles as up', 'up.user_id', '=', 'st.client_id')
                ->leftJoin('event_tags as e', 'e.id', '=', 'st.event_id')
                ->leftJoinSub(
                    DB::table('sms_templates as st2')
                        ->selectRaw("
                            st2.id as template_id,
                            GROUP_CONCAT(upp.fullname SEPARATOR ', ') AS excluded_fullnames
                        ")
                        ->join(DB::raw("
                            JSON_TABLE(
                                COALESCE(st2.exclude_notify, '[]'),
                                '\$[*]' COLUMNS (exclude_user_id INT PATH '\$')
                            ) AS jt
                        "), DB::raw('1'), '=', DB::raw('1'))
                        ->leftJoin('user_profiles as upp', 'upp.user_id', '=', DB::raw('jt.exclude_user_id'))
                        ->groupBy('st2.id'),
                    'excl',
                    'excl.template_id',
                    '=',
                    'st.id'
                )
                ->select(
                    'st.id',
                    'st.key',
                    'st.template_name',
                    'st.template',
                    'st.status',
                    'st.business_entity_id',
                    'c.company_name',
                    'st.client_id',
                    'up.fullname as client_name',
                    'st.event_id',
                    'e.event_name',
                    'st.exclude_notify',
                    'excl.excluded_fullnames',
                    'st.created_at',
                )
                ->where('st.id', $id)
                ->first();

            if (!$resource) {
                return ApiResponse::error("SMS template not found.", "Not Found", 404);
            }

            // Decode exclude_notify JSON string to array
            if ($resource->exclude_notify) {
                $resource->exclude_notify = json_decode($resource->exclude_notify, true);
            } else {
                $resource->exclude_notify = [];
            }

            return ApiResponse::success($resource, "SMS template fetched successfully.", 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            // Check duplicate — same name + same event
            $exist = SmsTemplate::where('template_name', $request->template_name)
                ->where('event_id', $request->event_id)
                ->first();

            if ($exist) {
                return ApiResponse::success($exist, "SMS template already exists!", 409);
            }

            // Auto-generate unique key from template name
            $key = $this->generateUniqueKey($request->template_name);

            $resource = SmsTemplate::create([
                'key'                => $key,
                'template_name'      => $request->template_name,
                'template'           => $request->template,
                'status'             => $request->status ?? 'Active',
                'event_id'           => $request->event_id,
                'business_entity_id' => $request->business_entity_id,
                'client_id'          => $request->client_id,
                'exclude_notify'     => $request->exclude_notify ?? null,
            ]);

            DB::commit();
            return ApiResponse::success($resource, "Successfully Inserted", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $template = SmsTemplate::find($id);

            if (!$template) {
                return ApiResponse::error("SMS template not found.", "Not Found", 404);
            }

            // Regenerate key only if template_name changed
            $key = $template->key;
            if ($request->template_name && $request->template_name !== $template->template_name) {
                $key = $this->generateUniqueKey($request->template_name, $id);
            }

            $template->update([
                'key'                => $key,
                'template_name'      => $request->template_name      ?? $template->template_name,
                'template'           => $request->template            ?? $template->template,
                'status'             => $request->status              ?? $template->status,
                'event_id'           => $request->event_id            ?? $template->event_id,
                'business_entity_id' => $request->business_entity_id  ?? $template->business_entity_id,
                'client_id'          => $request->client_id           ?? $template->client_id,
                'exclude_notify'     => $request->has('exclude_notify') ? $request->exclude_notify : $template->exclude_notify,
            ]);

            DB::commit();
            return ApiResponse::success($template->fresh(), "Successfully Updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    // ── Private Helper ─────────────────────────────────────────
    private function generateUniqueKey(string $name, ?int $excludeId = null): string
    {
        $base = Str::slug($name, '_');
        $key  = $base;
        $i    = 1;

        while (
            SmsTemplate::where('key', $key)
                ->when($excludeId, fn($q) => $q->where('id', '!=', $excludeId))
                ->exists()
        ) {
            $key = $base . '_' . $i++;
        }

        return $key;
    }



    public function checkExcludeNotify(Request $request)
    {
        try {
            $businessEntityId = $request->business_entity_id;
            $userId           = $request->user_id;

            $result = DB::table('sms_templates as st')
                ->select('st.id', 'st.template_name', 'st.business_entity_id', 'st.exclude_notify')
                ->where('st.business_entity_id', $businessEntityId)
                ->whereRaw("JSON_CONTAINS(st.exclude_notify, ?, '$')", ['"' . $userId . '"'])
                ->get();

            return ApiResponse::success($result, "Fetched successfully.", 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }



    

}