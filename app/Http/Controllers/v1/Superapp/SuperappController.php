<?php

namespace App\Http\Controllers\v1\Superapp;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Company;
use App\Models\TeamMappingForPartner;
use App\Models\TicketAssignTeamLog;
use App\Models\FirstResConfig;
use App\Models\FirstResSla;
use App\Models\FirstResSlaHistory;
use App\Models\SlaClientConfig;
use App\Models\SrvTimeClientSla;
use App\Models\SrvTimeClientSlaHistory;
use App\Models\SlaSubcatConfig;
use App\Models\SrvTimeSubcatSla;
use App\Models\SrvTimeSubcatSlaHistory;
use Illuminate\Support\Facades\Log;
use App\Logic\Client;
use App\Logic\ClientCreate;
use App\Logic\TicketInfo;
 use Illuminate\Support\Facades\Validator;

class SuperappController extends Controller
{
     /**
     * Show category
     */
    public function categories($company, $division)
{

    try {
      $companyId = $this->getCompanyId($company, $division);

    if (!$companyId) {
        return ApiResponse::error("Invalid entity mapping", "Error", 400);
    }

        $categories = DB::select("
            SELECT c.id, c.category_in_english, c.category_in_bangla
            FROM helpdesk.categories c
            LEFT JOIN helpdesk.category_entity_mappings cm
                ON c.id = cm.category_id
            LEFT JOIN helpdesk.companies co
                ON cm.company_id = co.id
            WHERE cm.company_id = ?
            AND cm.is_client_visible = 1
            ORDER BY c.category_in_english ASC
        ", [$companyId]);

        return ApiResponse::success($categories, "Success", 200);

    } catch (\Exception $e) {

        // Log::error($e->getMessage());

        return ApiResponse::error(
            "Internal server error",
            "Server Error",
            500
        );
    }
}


    /**
     * Get subcategories by category and entity
     */
    public function subcategories($company,$division, $category)
    {
        try {
            $companyId = $this->getCompanyId($company, $division);

            if (!$companyId) {
                return ApiResponse::error("Invalid entity mapping", "Error", 400);
            }
            $subCategories = DB::select("
                SELECT
                    sc.id,
                    sc.sub_category_in_english,
                    sc.sub_category_in_bangla
                FROM helpdesk.sub_categories sc
                INNER JOIN helpdesk.entity_category_subcategory_mappings ecsm
                    ON sc.id = ecsm.sub_category_id
                WHERE ecsm.is_client_visible = 1
                    AND ecsm.category_id = ?
                    AND ecsm.company_id = ?
                ORDER BY sc.sub_category_in_english ASC
            ", [$category, $companyId]);

            return ApiResponse::success($subCategories, 'Success', 200);

        } catch (\Exception $e) {
            // Log::error($e->getMessage());

            return ApiResponse::error(
                "Internal server error",
                "Server Error",
                500
            );
        }

    }


    public function priorities()
    {
        try {


            $priorities = DB::select(" SELECT id,priority_name FROM priorities ORDER BY id ASC
            ");

            return ApiResponse::success($priorities, 'Success', 200);

        } catch (\Exception $e) {
            // Log::error($e->getMessage());

            return ApiResponse::error(
                "Internal server error",
                "Server Error",
                500
            );
        }

    }




    /**
     * Create ticket from super app
     */



    public function storeTicket(Request $request)
    {
          try {



                  $validator = Validator::make($request->all(), [
                  // ✅ Required Fields
                  'platformId'         => 'required|integer',
                  'sid'                => 'required|string|max:50',
                  'mobileNumber'       => 'required|string|max:20',
                  'division'           => 'required|string|max:100',
                  'district'           => 'required|string|max:100',
                  'thana'              => 'required|string|max:100',
                  'companyId'          => 'required|string|max:50',
                  'businessDivisionId' => 'required|string',
                  'entityType'         => 'required|string|max:50',
                  'entityId'           => 'required|string|max:50',
                  'entityName'         => 'required|string|max:255',
                  'categoryId'         => 'required|integer',
                  'subCategoryId'      => 'required|integer',
                  'priorityId'         => 'required|integer|in:1,2,3',

                  // ✅ Optional Fields
                  'emailAddress'       => 'nullable|email|max:255',
                  'description'        => 'nullable|string',
                  'attachment'         => 'nullable|array',
                  'attachment.*' => 'file|max:1048576'

                  ], [

                  // ✅ Custom Error Messages (Professional)
                  'platformId.required' => 'Platform ID is required.',
                  'sid.required' => 'Subscriber ID (SID) is required.',
                  'mobileNumber.required' => 'Mobile number is required.',
                  'division.required' => 'Division is required.',
                  'district.required' => 'District is required.',
                  'thana.required' => 'Thana is required.',
                  'companyId.required' => 'Company ID is required.',
                  'businessDivisionId.required' => 'Business division is required.',
                  'entityType.required' => 'Entity type is required.',
                  'entityId.required' => 'Entity ID is required.',
                  'entityName.required' => 'Entity name is required.',
                  'categoryId.required' => 'Category is required.',
                  'subCategoryId.required' => 'Sub category is required.',
                  'priorityId.required' => 'Priority is required.',
                  'priorityId.in' => 'Priority must be Low(1), Medium(2), or High(3).',

                  'emailAddress.email' => 'Email must be a valid email address.',
                  'attachment.*.file' => 'Each attachment must be a valid file.',

                  ]);

                  if ($validator->fails()) {
                  return ApiResponse::error(
                  $validator->errors(),
                  'Validation Error',
                  422
                  );
            }


                $companyId = $this->getCompanyId($request->companyId, $request->businessDivisionId);

                if (!$companyId) {
                    return ApiResponse::error("Invalid entity mapping", "Error", 400);
                }

                $defaultRoleId = DB::table('helpdesk.role_helpdesks')
                            ->where('default_type', 'Customer')
                            ->value('id');

                $entityType = $request->businessDivisionId;
                $mapping = [
                    'OWN' => 9, // Own
                    'PARTNER' => 8, // Reseller
                    'FEEDER' => 8, // Feeder
                ];
                $defaultBusinessEntityId = $mapping[$entityType] ?? null;

              // ✅ Build clientInfo from API payload
              $clientInfo = [
                  'username'=> $request->sid,
                  'entityType' => $request->entityType,
                  'fullName' => $request->entityName,
                  'primaryEmail' => $request->emailAddress,
                  'secondaryEmail' => null,
                  'primaryPhone' => $request->mobileNumber,
                  'secondaryPhone' => null,
                  'client' => $request->entityId,
                  'clientName' => $request->entityName,
                  'status' => 1,
                  'userType' => "Customer",
                  'role' => $defaultRoleId,
                  'defaultBusinessEntity' => $defaultBusinessEntityId,
                  'businessEntity' => $companyId,
              ];

              // ✅ Create / Get Client
              $recentClientId = Client::createSuperAppClient($clientInfo);

              // return ApiResponse::success($clientInfo, "Success", 200);

              // ✅ Team Mapping
              $teamMapping = TeamMappingForPartner::where('company_id', $companyId)
                  ->where('subcategory_id', $request->subCategoryId)
                  ->where('is_active', true)
                  ->first();

              if ($teamMapping) {
                  $teamId = $teamMapping->team_id;
              } else {
                  return ApiResponse::error(
                      'No team mapping found.',
                      'Error',
                      422
                  );
              }

              // ✅ Generate Ticket Number
              $ticketNumber = TicketInfo::ticketNumberGenarate();

              // ✅ Attachments
              $attachments = $request->file('attachment', []);
              // return ApiResponse::success([$ticketNumber, $attachments, $teamId], "Success", 200);


              // ✅ Ticket Data
              $ticketData = [
                  'ticket_number'      => $ticketNumber,
                  'is_parent'          => 0,
                  'platform_id'        => $request->platformId,
                  'user_id'            => $recentClientId,
                  'status_updated_by'  => $recentClientId,
                  'business_entity_id' => $companyId,
                  'client_id_helpdesk' => $recentClientId,
                  'client_id_vendor'   => $request->entityId,
                  'cat_id'             => $request->categoryId,
                  'subcat_id'          => $request->subCategoryId,
                  'priority_name'      => $request->priorityId,
                  'status_id'          => 1,
                  'team_id'            => $teamId,
                  'note'               => $request->description,
                  'mobile_no'          => $request->mobileNumber,
              ];

              // ✅ Orbit Data (if needed)
              $orbitData = null;
              if (in_array($ticketData['business_entity_id'], [8, 9])) {
                  $orbitData = [
                      'ticket_number'      => $ticketNumber,
                      'client_type'        => $clientInfo['entityType'],
                      'client_id_helpdesk' => $recentClientId,
                      'client_id_vendor'   => $request->entityId,
                      'billing_source'     => 'SuperApp',
                      'sid_uid'            => $request->sid,
                      'fullname'           => $clientInfo['fullName'],
                      'phone'              => $clientInfo['primaryPhone'],
                  ];
              }

              $divisionData = [
                      'ticket_number'      => $ticketNumber,
                      'division'        => $request->division,
                      'district' => $request->district,
                      'thana'   => $request->thana,
                  ];



              // ✅ Assign Team Log
              TicketAssignTeamLog::create([
                  'ticket_number' => $ticketNumber,
                  'assigned_in'   => $teamId,
                  'assigned_out'  => null,
              ]);

              // ✅ First Response SLA
              $firstResConfig = FirstResConfig::where('team_id', $teamId)->first();
              if ($firstResConfig) {
                  FirstResSla::create([
                      'ticket_number'       => $ticketNumber,
                      'first_res_config_id' => $firstResConfig->id,
                      'sla_status'          => 2,
                  ]);

                  FirstResSlaHistory::create([
                      'ticket_number'       => $ticketNumber,
                      'first_res_config_id' => $firstResConfig->id,
                      'sla_status'          => 2,
                  ]);
              }

              // ✅ Service Time SLA
              $slaClientConfig = SlaClientConfig::where('client_id', $recentClientId)->first();

              if ($slaClientConfig) {
                  SrvTimeClientSla::create([
                      'ticket_number'        => $ticketNumber,
                      'sla_client_config_id' => $slaClientConfig->id,
                      'sla_status'           => 2,
                  ]);

                  SrvTimeClientSlaHistory::create([
                      'ticket_number'        => $ticketNumber,
                      'sla_client_config_id' => $slaClientConfig->id,
                      'sla_status'           => 2,
                  ]);
              } else {
                  $slaSubcatConfig = SlaSubcatConfig::where('business_entity_id', $companyId)
                      ->where('team_id', $teamId)
                      ->where('subcategory_id', $request->subCategoryId)
                      ->first();

                  if ($slaSubcatConfig) {
                      SrvTimeSubcatSla::create([
                          'ticket_number'        => $ticketNumber,
                          'sla_subcat_config_id' => $slaSubcatConfig->id,
                          'sla_status'           => 2,
                      ]);

                      SrvTimeSubcatSlaHistory::create([
                          'ticket_number'        => $ticketNumber,
                          'sla_subcat_config_id' => $slaSubcatConfig->id,
                          'sla_status'           => 2,
                      ]);
                  }
              }

              // ✅ FINAL CREATE
              return TicketInfo::ticketCreatedBySupperApp(
                  $ticketData,
                  $attachments,
                  $orbitData,
                  $divisionData
              );

          } catch (\Exception $e) {
              return ApiResponse::error($e->getMessage(), 'Error', 500);
          }


    }


    /**
     * Show ticket
     */
//     public function showTicket($sid)
//     {
//       $ticket = DB::select("SELECT
//     t.ticket_number,
//     cat.category_in_bangla,
//     cat.category_in_english,
//     sub.sub_category_in_english,
//     sub.sub_category_in_bangla,
//     st.status_name,
//     p.priority_name AS priority,
//     tb.sid_uid AS sid,

//     TIMESTAMPDIFF(SECOND, t.created_at, NOW()) AS ticket_age_seconds,

//     t.note AS description,
//     t.created_at,

//     ta.attachments,
//     tc.comments

// FROM (

//     SELECT * FROM helpdesk.open_tickets
//     UNION ALL
//     SELECT * FROM helpdesk.close_tickets

// ) t

// LEFT JOIN helpdesk.statuses st
//     ON t.status_id = st.id

// LEFT JOIN helpdesk.priorities p
//     ON t.priority_name = p.id

// LEFT JOIN helpdesk.categories cat
//     ON t.cat_id = cat.id

// LEFT JOIN helpdesk.sub_categories sub
//     ON t.subcat_id = sub.id

// LEFT JOIN helpdesk.ticket_orbits tb
//     ON tb.ticket_number = t.ticket_number

// /* attachments aggregation */
// LEFT JOIN (
//     SELECT
//         ticket_number,
//         JSON_ARRAYAGG(CONCAT('https://ticketstaging.race.net.bd/', url)) AS attachments
//     FROM helpdesk.ticket_attachments
//     GROUP BY ticket_number
// ) ta ON ta.ticket_number = t.ticket_number

// /* comments + comment attachments */
// LEFT JOIN (
//     SELECT
//         tc.ticket_number,
//         JSON_ARRAYAGG(
//             JSON_OBJECT(
//                 'comment', tc.comments,
//                 'created_at', tc.created_at,
//                 'attachments', (
//                     SELECT JSON_ARRAYAGG(
//                         CONCAT('https://ticketstaging.race.net.bd/', tca.url)
//                     )
//                     FROM helpdesk.ticket_comment_attachments tca
//                     WHERE tca.comment_id = tc.id
//                 )
//             )
//         ) AS comments
//     FROM helpdesk.ticket_comments tc
// 		WHERE is_internal = 0
//     GROUP BY tc.ticket_number
// ) tc ON tc.ticket_number = t.ticket_number

// WHERE tb.sid_uid = '$sid'");
//         return ApiResponse::success($ticket, "Success", 200);
//     }

public function showTicket(Request $request, $sid)
{
    $perPage = $request->get('per_page', 10);
    $currentPage = $request->get('page', 1);

    $ticket = DB::select("SELECT
    t.ticket_number,
    cat.category_in_bangla,
    cat.category_in_english,
    sub.sub_category_in_english,
    sub.sub_category_in_bangla,
    st.status_name,
    p.priority_name AS priority,
    tb.sid_uid AS sid,

    TIMESTAMPDIFF(SECOND, t.created_at, NOW()) AS ticket_age_seconds,

    t.note AS description,
    t.created_at,

    ta.attachments,
    tc.comments

FROM (

    SELECT * FROM helpdesk.open_tickets
    UNION ALL
    SELECT * FROM helpdesk.close_tickets

) t

LEFT JOIN helpdesk.statuses st
    ON t.status_id = st.id

LEFT JOIN helpdesk.priorities p
    ON t.priority_name = p.id

LEFT JOIN helpdesk.categories cat
    ON t.cat_id = cat.id

LEFT JOIN helpdesk.sub_categories sub
    ON t.subcat_id = sub.id

LEFT JOIN helpdesk.ticket_orbits tb
    ON tb.ticket_number = t.ticket_number

LEFT JOIN (
    SELECT
        ticket_number,
        JSON_ARRAYAGG(CONCAT('https://ticketstaging.race.net.bd/', url)) AS attachments
    FROM helpdesk.ticket_attachments
    GROUP BY ticket_number
) ta ON ta.ticket_number = t.ticket_number

LEFT JOIN (
    SELECT
        tc.ticket_number,
        JSON_ARRAYAGG(
            JSON_OBJECT(
                'comment', tc.comments,
                'created_at', tc.created_at,
                'attachments', (
                    SELECT JSON_ARRAYAGG(
                        CONCAT('https://ticketstaging.race.net.bd/', tca.url)
                    )
                    FROM helpdesk.ticket_comment_attachments tca
                    WHERE tca.comment_id = tc.id
                )
            )
        ) AS comments
    FROM helpdesk.ticket_comments tc
    WHERE is_internal = 0
    GROUP BY tc.ticket_number
) tc ON tc.ticket_number = t.ticket_number

WHERE tb.sid_uid = '$sid'");

    $collection = collect($ticket);

    $total = $collection->count();
    $items = $collection->forPage($currentPage, $perPage)->values();

    $data = [
      "status" => true,
        "data" => $items,
        "current_page" => (int)$currentPage,
        "per_page" => (int)$perPage,
        "total" => $total,
        "last_page" => ceil($total / $perPage),
        "from" => ($currentPage - 1) * $perPage + 1,
        "to" => min($currentPage * $perPage, $total),

    ];

    return ApiResponse::success($data, "Success", 200);
}
public function filterTickets(Request $request)
{
    $page = $request->page ?? 1;
    $perPage = $request->per_page ?? 10;
    $offset = ($page - 1) * $perPage;

    $baseSql = "
     FROM (
        SELECT * FROM helpdesk.open_tickets
        UNION ALL
        SELECT * FROM helpdesk.close_tickets
    ) t

    LEFT JOIN helpdesk.statuses st
        ON t.status_id = st.id

    LEFT JOIN helpdesk.priorities p
        ON t.priority_name = p.id

    LEFT JOIN helpdesk.categories cat
        ON t.cat_id = cat.id

    LEFT JOIN helpdesk.sub_categories sub
        ON t.subcat_id = sub.id

    LEFT JOIN helpdesk.ticket_orbits tb
        ON tb.ticket_number = t.ticket_number
    ";

    $bindings = [];
    $where = " WHERE 1=1 ";

    if ($request->sid) {
        $where .= " AND tb.sid_uid = ?";
        $bindings[] = $request->sid;
    }

    if ($request->ticket_number) {
        $where .= " AND t.ticket_number = ?";
        $bindings[] = $request->ticket_number;
    }

    if ($request->category) {
        $where .= " AND t.cat_id = ?";
        $bindings[] = $request->category;
    }

    if ($request->subcategory) {
        $where .= " AND t.subcat_id = ?";
        $bindings[] = $request->subcategory;
    }

    if ($request->status) {
        $where .= " AND t.status_id = ?";
        $bindings[] = $request->status;
    }

    if ($request->start_date && $request->end_date) {
        $where .= " AND DATE(t.created_at) BETWEEN ? AND ?";
        $bindings[] = $request->start_date;
        $bindings[] = $request->end_date;
    }

    /* total count */
    $countQuery = "SELECT COUNT(DISTINCT t.ticket_number) as total " . $baseSql . $where;
    $total = DB::select($countQuery, $bindings)[0]->total;

    /* main data query */
    $dataQuery = "
    SELECT
        t.ticket_number,
        cat.category_in_bangla,
        cat.category_in_english,
        sub.sub_category_in_english,
        sub.sub_category_in_bangla,
        st.status_name,
        p.priority_name AS priority,
        tb.sid_uid AS sid,
        TIMESTAMPDIFF(SECOND, t.created_at, NOW()) AS ticket_age_seconds,
        t.note AS description,
        t.created_at,

        ta.attachments,
        tc.comments
    " . $baseSql . "

    LEFT JOIN (
        SELECT
            ticket_number,
            JSON_ARRAYAGG(CONCAT('https://ticketstaging.race.net.bd/', url)) AS attachments
        FROM helpdesk.ticket_attachments
        GROUP BY ticket_number
    ) ta ON ta.ticket_number = t.ticket_number

    LEFT JOIN (
        SELECT
            tc.ticket_number,
            JSON_ARRAYAGG(
                JSON_OBJECT(
                    'comment', tc.comments,
                    'created_at', tc.created_at,
                    'attachments', (
                        SELECT JSON_ARRAYAGG(
                            CONCAT('https://ticketstaging.race.net.bd/', tca.url)
                        )
                        FROM helpdesk.ticket_comment_attachments tca
                        WHERE tca.comment_id = tc.id
                    )
                )
            ) AS comments
        FROM helpdesk.ticket_comments tc
        WHERE is_internal = 0
        GROUP BY tc.ticket_number
    ) tc ON tc.ticket_number = t.ticket_number

    " . $where . "

    ORDER BY t.created_at DESC
    LIMIT $perPage OFFSET $offset
    ";

    $data = DB::select($dataQuery, $bindings);

    $lastPage = ceil($total / $perPage);

    return response()->json([
        "status" => true,
        "data" => $data,
        "current_page" => (int)$page,
        "per_page" => (int)$perPage,
        "total" => (int)$total,
        "last_page" => (int)$lastPage,
        "from" => $offset + 1,
        "to" => $offset + count($data),
    ]);
}

    private function getCompanyId($cid, $division)
{
    switch ($cid) {

        case 'CID000001':
            switch ($division) {
                case 'OWN': // own
                    return 9; // Orbit OWN
                case 'PARTNER': // partner
                    return 8; // Orbit Partner
                case 'FEEDER': // feeder
                    return 11; // Race Partner
                default:
                    return null;
            }


        default:
            return null;
    }
}


}
