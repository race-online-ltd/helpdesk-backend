<?php

namespace App\Http\Controllers\v1\Settings;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\SubCategory;
use App\Models\EntityCategorySubcategoryMapping;
use App\Models\SubCategoryTeam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubCategoryController extends Controller
{

    public function fetchSubcategoryByCategoryId($companyId,$categoryId)
    {
        try {
            $subCategories = DB::select("SELECT s.* FROM sub_categories s
            INNER JOIN entity_category_subcategory_mappings ecsm ON ecsm.sub_category_id = s.id
            WHERE ecsm.company_id = '$companyId'
            AND ecsm.category_id = '$categoryId'");

            return ApiResponse::success($subCategories, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
    // public function index()
    // {
    //     try {
    //         $subCategories = DB::select("SELECT 
    //                 sc.id AS sub_category_id,
    //                 co.company_name,
    //                 c.category_in_english,
    //                 c.category_in_bangla,
    //                 sc.sub_category_in_english,
    //                 sc.sub_category_in_bangla,
    //                 t.team_name,
    //                 t.id AS team_id,
    //                 t.created_at AS team_created_at,
    //                 sc.created_at AS sub_category_created_at
    //             FROM 
    //                 helpdesk.companies co
    //             JOIN 
    //                 helpdesk.categories c ON c.company_id = co.id
    //             JOIN 
    //                 helpdesk.sub_categories sc ON sc.category_id = c.id
    //             LEFT JOIN 
    //                 helpdesk.sub_category_teams sct ON sct.sub_category_id = sc.id
    //             LEFT JOIN 
    //                 helpdesk.teams t ON sct.team_id = t.id
    //             ORDER BY 
    //                 co.company_name, c.category_in_english
    //             ");

    //         $mergedData = [];
    //         foreach ($subCategories as $item) {
    //             $subCategoryId = $item->sub_category_id;
    //             if (!isset($mergedData[$subCategoryId])) {
    //                 $mergedData[$subCategoryId] = [
    //                     'id' => $subCategoryId,
    //                     'company_name' =>  $item->company_name,
    //                     'category_in_english' => $item->category_in_english,
    //                     'category_in_bangla' => $item->category_in_bangla,
    //                     'sub_category_in_english' => $item->sub_category_in_english,
    //                     'sub_category_in_bangla' => $item->sub_category_in_bangla,
    //                     'created_at' => $item->sub_category_created_at,
    //                     'teams' => []
    //                 ];
    //             }
    //             if ($item->team_id) {
    //                 $mergedData[$subCategoryId]['teams'][] = [
    //                     'id' => $item->team_id,
    //                     'team_name' => $item->team_name,
    //                     'created_at' => $item->team_created_at
    //                 ];
    //             }
    //         }

    //         return ApiResponse::success(array_values($mergedData), "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    public function index()
    {
        try {
            $subCategories = DB::select("
                SELECT 
                    sc.id AS sub_category_id,
                    co.id AS company_id,
                    co.company_name,
                    c.id AS category_id,
                    c.category_in_english,
                    c.category_in_bangla,
                    sc.sub_category_in_english,
                    sc.sub_category_in_bangla,
                    t.id AS team_id,
                    t.team_name,
                    ecsm.is_client_visible,
                    sct.created_at AS team_created_at,
                    sc.created_at AS sub_category_created_at
                FROM 
                    helpdesk.sub_categories sc
                INNER JOIN 
                    helpdesk.entity_category_subcategory_mappings ecsm ON sc.id = ecsm.sub_category_id
                INNER JOIN 
                    helpdesk.companies co ON ecsm.company_id = co.id
                INNER JOIN 
                    helpdesk.categories c ON ecsm.category_id = c.id
                LEFT JOIN 
                    helpdesk.sub_category_teams sct ON sct.sub_category_id = sc.id
                LEFT JOIN 
                    helpdesk.teams t ON sct.team_id = t.id
                ORDER BY 
                    sc.created_at DESC,
                    co.company_name, 
                    c.category_in_english
            ");
            
            $mergedData = [];
            
            foreach ($subCategories as $item) {
                
                $key = $item->sub_category_id . '_' . $item->company_id;
                
                
                if (!isset($mergedData[$key])) {
                    $mergedData[$key] = [
                        'id'                      => $item->sub_category_id,
                        'company_id'              => $item->company_id,       // ← ADD THIS
                        'category_id'             => $item->category_id,      // ← ADD THIS
                        'sub_category_id'         => $item->sub_category_id,  // ← ADD THIS (optional but cleaner)
                        'is_client_visible'       => $item->is_client_visible, // ← ADD THIS
                        'company_name'            => $item->company_name,
                        'category_in_english'     => $item->category_in_english,
                        'category_in_bangla'      => $item->category_in_bangla,
                        'sub_category_in_english' => $item->sub_category_in_english,
                        'sub_category_in_bangla'  => $item->sub_category_in_bangla,
                        'sub_category_created_at' => $item->sub_category_created_at,
                        'teams'                   => []
                    ];
                }
                
                
                if ($item->team_id && !in_array($item->team_id, array_column($mergedData[$key]['teams'], 'id'))) {
                    $mergedData[$key]['teams'][] = [
                        'id' => $item->team_id,
                        'team_name' => $item->team_name,
                        'created_at' => $item->team_created_at
                    ];
                }
            }
            
            return ApiResponse::success(array_values($mergedData), "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    // public function store(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {

    //         $attributesExist = [
    //             'company_id' => $request->companyId,
    //             'sub_category_in_english' => $request->subCategoryInEnglish,
    //             'sub_category_in_bangla' => $request->subCategoryInBangla,
    //         ];

    //         $attributes = [
    //             'company_id' => $request->companyId,
    //             'category_id' => $request->categoryInEnglishId,
    //             'sub_category_in_english' => $request->subCategoryInEnglish,
    //             'sub_category_in_bangla' => $request->subCategoryInBangla,
    //         ];
    //         $subCategory = SubCategory::firstOrCreate(
    //             $attributesExist,
    //             $attributes
    //         );

    //         if (!$subCategory->wasRecentlyCreated) {
    //             return ApiResponse::success($subCategory, "Sub-category already exists!", 409);
    //         }

    //         $teams = $request->teamId;
    //         $dataToInsert = [];
    //         foreach ($teams as $team) {
    //             $dataToInsert[] = [
    //                 'category_id' => $subCategory->category_id,
    //                 'sub_category_id' => $subCategory->id,
    //                 'team_id' => $team,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];
    //         }

    //         SubCategoryTeam::insert($dataToInsert);

    //         DB::commit();
    //         return ApiResponse::success($team, "Successfully Inserted", 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $companyIds = $request->companyIds ?? $request->companyId ?? [];
            $teamIds = $request->teamIds ?? $request->teamId ?? [];
            $categoryId = $request->categoryInEnglishId;
            
            
            $companyIds = is_array($companyIds) ? $companyIds : [$companyIds];
            $teamIds = is_array($teamIds) ? $teamIds : [$teamIds];
            
            
            if (empty($companyIds) || empty($teamIds) || !$categoryId || !$request->subCategoryInEnglish) {
                return ApiResponse::error("Required fields are missing", "Validation Error", 400);
            }
            
            
            $subCategoryAttributes = [
                'sub_category_in_english' => $request->subCategoryInEnglish,
                'sub_category_in_bangla' => $request->subCategoryInBangla,
            ];
            
            $subCategory = SubCategory::firstOrCreate($subCategoryAttributes, $subCategoryAttributes);
            
            
            $mappingsToInsert = [];
            foreach ($companyIds as $companyId) {
                
                $existingMapping = EntityCategorySubcategoryMapping::where([
                    'company_id' => $companyId,
                    'category_id' => $categoryId,
                    'sub_category_id' => $subCategory->id,
                ])->first();
                
                if (!$existingMapping) {
                    $mappingsToInsert[] = [
                        'company_id' => $companyId,
                        'category_id' => $categoryId,
                        'sub_category_id' => $subCategory->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            
            if (!empty($mappingsToInsert)) {
                EntityCategorySubcategoryMapping::insert($mappingsToInsert);
            }
            
            
            $teamMappingsToInsert = [];
            foreach ($teamIds as $teamId) {
                $existingTeamMapping = SubCategoryTeam::where([
                    'sub_category_id' => $subCategory->id,
                    'team_id' => $teamId,
                ])->first();
                
                if (!$existingTeamMapping) {
                    $teamMappingsToInsert[] = [
                        'category_id' => $categoryId,
                        'sub_category_id' => $subCategory->id,
                        'team_id' => $teamId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            if (!empty($teamMappingsToInsert)) {
                SubCategoryTeam::insert($teamMappingsToInsert);
            }
            
            DB::commit();
            return ApiResponse::success([
                'sub_category' => $subCategory,
                'mappings_created' => count($mappingsToInsert),
                'teams_mapped' => count($teamMappingsToInsert),
            ], "Successfully Inserted", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function show($id)
    {
        try {
            $subCategory = SubCategory::findOrFail($id);
            
            // Get all entity-category mappings for this sub-category
            $mappings = EntityCategorySubcategoryMapping::where('sub_category_id', $id)
                ->join('companies', 'entity_category_subcategory_mappings.company_id', '=', 'companies.id')
                ->select(
                    'entity_category_subcategory_mappings.company_id',
                    'entity_category_subcategory_mappings.category_id',
                    'entity_category_subcategory_mappings.is_client_visible',
                    'companies.company_name'
                )
                ->get();
            
            // Get all teams associated with this sub-category via SubCategoryTeam
            $subCategoryTeams = SubCategoryTeam::where('sub_category_id', $id)
                ->join('teams', 'sub_category_teams.team_id', '=', 'teams.id')
                ->select('sub_category_teams.team_id', 'teams.team_name')
                ->get();
            
            // Extract IDs for the frontend (cast to int)
            $companyIds = $mappings->pluck('company_id')->map(fn($id) => (int)$id)->unique()->toArray();
            $categoryId = $mappings->first()?->category_id ? (int)$mappings->first()->category_id : null;
            $teamIds = $subCategoryTeams->pluck('team_id')->map(fn($id) => (int)$id)->toArray();
            
            $mergedData = [
                'sub_category' => $subCategory,
                'mappings' => $mappings,
                'teams' => $subCategoryTeams,
                'companyIds' => $companyIds,
                'categoryId' => $categoryId,
                'teamIds' => $teamIds,
            ];
            
            return ApiResponse::success($mergedData, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $companyIds = $request->companyIds ?? $request->companyId ?? [];
            $teamIds = $request->teamIds ?? $request->teamId ?? [];
            $categoryId = $request->categoryInEnglishId;
            
            // Ensure they are arrays
            $companyIds = is_array($companyIds) ? $companyIds : [$companyIds];
            $teamIds = is_array($teamIds) ? $teamIds : [$teamIds];
            
            // Validate required fields
            if (empty($companyIds) || empty($teamIds) || !$categoryId || !$request->subCategoryInEnglish) {
                return ApiResponse::error("Required fields are missing", "Validation Error", 400);
            }
            
            // Update sub-category
            $subCategory = SubCategory::findOrFail($id);
            $subCategory->update([
                'sub_category_in_english' => $request->subCategoryInEnglish,
                'sub_category_in_bangla' => $request->subCategoryInBangla,
            ]);
            
            // Delete existing mappings
            EntityCategorySubcategoryMapping::where('sub_category_id', $subCategory->id)->delete();
            
            // Create new mappings
            $mappingsToInsert = [];
            foreach ($companyIds as $companyId) {
                $mappingsToInsert[] = [
                    'company_id' => $companyId,
                    'category_id' => $categoryId,
                    'sub_category_id' => $subCategory->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($mappingsToInsert)) {
                EntityCategorySubcategoryMapping::insert($mappingsToInsert);
            }
            
            // Delete existing team mappings
            SubCategoryTeam::where('sub_category_id', $subCategory->id)->delete();
            
            // Create new team mappings
            $teamMappingsToInsert = [];
            foreach ($teamIds as $teamId) {
                $teamMappingsToInsert[] = [
                    'category_id' => $categoryId,
                    'sub_category_id' => $subCategory->id,
                    'team_id' => $teamId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($teamMappingsToInsert)) {
                SubCategoryTeam::insert($teamMappingsToInsert);
            }
            
            DB::commit();
            return ApiResponse::success($subCategory, "Successfully Updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    // public function show($id)
    // {
    //     // try {
    //     //     $resource = DB::select("SELECT sub_categories.id,sub_categories.company_id,sub_categories.category_id,companies.company_name,categories.category_in_english,sub_categories.sub_category_in_english,sub_categories.sub_category_in_bangla,sub_categories.created_at FROM companies,categories,sub_categories
    //     // WHERE categories.company_id =companies.id
    //     // AND sub_categories.category_id = categories.id
    //     // AND sub_categories.id = $id");
    //     //     return response()->json([
    //     //         'status' => true,
    //     //         'message' => 'success',
    //     //         'result' => $resource
    //     //     ], 200);
    //     // } catch (\Exception $e) {
    //     //     return response()->json([
    //     //         'status' => false,
    //     //         'message' => 'Something went wrong!',
    //     //         'result' => $e->getMessage()
    //     //     ], 500);
    //     // }


    //     try {
    //         $subCategory = SubCategory::findOrFail($id);
    //         $subCategoryTeam = SubCategoryTeam::where('sub_category_id', $id)->get();
    //         $mergedData = [
    //             'sub_category' => $subCategory,
    //             'sub_category_team' => $subCategoryTeam,
    //         ];
    //         return ApiResponse::success($mergedData, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }

    // public function update(Request $request, $id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $subCategory = SubCategory::findOrFail($id);
    //         $subCategory->update([
    //             'company_id' => $request->companyId,
    //             'category_id' => $request->categoryInEnglishId,
    //             'sub_category_in_english' => $request->subCategoryInEnglish,
    //             'sub_category_in_bangla' => $request->subCategoryInBangla,
    //         ]);

    //         SubCategoryTeam::where('sub_category_id', $subCategory->id)->delete();

    //         $teams = $request->teamId;
    //         foreach ($teams as $team) {
    //             SubCategoryTeam::updateOrCreate(
    //                 [
    //                     'category_id' => $subCategory->category_id,
    //                     'sub_category_id' => $subCategory->id,
    //                     'team_id' => $team
    //                 ],
    //                 ['updated_at' => now()]
    //             );
    //         }

    //         DB::commit();
    //         return ApiResponse::success($subCategory, "Successfully Updated", 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }

    public function destroy($id)
    {
        $resource = SubCategory::findOrFail($id);
        $resource->delete();
        return response()->json(null, 204);
    }


    public function fetchSubcategoryAll()
    {
        try {

            $subCategories = DB::select("SELECT sc.id, sc.sub_category_in_english FROM helpdesk.sub_categories sc");



            return ApiResponse::success($subCategories, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    // public function fetchSubcategoryAllForPartner()
    // {
    //     try {

    //         $subCategories = DB::select("SELECT sc.id, sc.sub_category_in_english FROM helpdesk.sub_categories sc 
    //          WHERE sc.sub_category_in_english IN ('Link Down','Overall Slow','Browsing Problem','Upload and Download Issue','WhatsApp Messenger',
    //             'IMO Calling','Gaming Issue','Online Game - PUBG','Fast.com','FNA Bandwidth Problem', 'FB Reels Video','Audio/Video Calling Problem',
    //             'Youtube Slow','FTP server','Zoom issue','High latency-8.8.8.8','TikTok issue','Site Access Issue','Internet Bandwidth Problem',
    //             'BDIX Bandwidth Problem','Bandwidth Problem - Single User','No Internet - Single User','Latency Issue','P2P Loss','CC Camera',
    //             'ORBITALK Calling Problem','MAC Free','OLT Configure','Router Config Request','Support Request','TalkTime Request','Real IP',
    //             'Update PPPoE Info','Information Change'
    //         )");



    //         return ApiResponse::success($subCategories, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    // public function fetchSubcategoryAllForPartner($id)
    // {
    //     try {
    //         $subCategories = DB::select("SELECT * FROM helpdesk.sub_categories
    //             WHERE sub_category_in_english IN ('Link Down',
    //                 'Overall Slow',
    //                 'Browsing Problem',
    //                 'Upload and Download Issue',
    //                 'WhatsApp Messenger',
    //                 'IMO Calling',
    //                 'Gaming Issue',
    //                 'Online Game - PUBG',
    //                 'Fast.com',
    //                 'FNA Bandwidth Problem',
    //                 'FB Reels Video',
    //                 'Audio/Video Calling Problem',
    //                 'Youtube Slow',
    //                 'FTP server',
    //                 'Zoom issue',
    //                 'High latency-8.8.8.8',
    //                 'TikTok issue',
    //                 'Site Access Issue',
    //                 'Internet Bandwidth Problem',
    //                 'BDIX Bandwidth Problem',
    //                 'Bandwidth Problem - Single User',
    //                 'No Internet - Single User',
    //                 'Latency Issue',
    //                 'P2P Loss',
    //                 'CC Camera',
    //                 'ORBITALK Calling Problem',
    //                 'MAC Free',
    //                 'OLT Configure',
    //                 'Router Config Request',
    //                 'Support Request',
    //                 'TalkTime Request',
    //                 'Real IP.',
    //                 'Update PPPoE Info',
    //                 'Information Change',
    //                 'Bkash Payment',
    //                 'Nagad Payment',
    //                 'Online payment failure',
    //                 'Credit transfer',
    //                 'Payment Refund',
    //                 'Fractional Activation',
    //                 'Customer Information Change',
    //                 'Top-Up',
    //                 'Package Change',
    //                 'Suspend Request',
    //                 'Reactive Request',
    //                 'Disconnection',
    //                 'End Date Extension',
    //                 'Auto Renewal Issue',
    //                 'Number Replacement Request',
    //                 'New activation',
    //                 'Reactive Request (Bulk)',
    //                 'Package Change (Bulk)',
    //                 'Suspend Request (Bulk)',
    //                 'Payment Refund (Bulk)','OLT Support','MAC Clear','Online payment Request',
    //                 'BongoBD','Chorki','No Internet','Frequently Disconnection'
    //                 , 'Youtube Problem - Single User', 'Facebook Problem - Single User')
    //             AND category_id = '$id' ");



    //         return ApiResponse::success($subCategories, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    //  public function fetchSubcategoryAllForPartner($id)
    // {
    //     try {
    //         $subCategories = DB::select("SELECT * 
    //         FROM helpdesk.sub_categories sc
    //         INNER JOIN helpdesk.entity_category_subcategory_mappings ecsm ON sc.id = ecsm.sub_category_id
    //             WHERE ecsm.is_client_visible = 1
    //             AND ecsm.category_id = '$id' ");



    //         return ApiResponse::success($subCategories, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    public function fetchSubcategoryAllForPartner($categoryId, $entityId)
    {
        try {
            // Validate inputs
            if (empty($categoryId) || empty($entityId)) {
                return ApiResponse::error('Category ID and Entity ID are required', 'Validation Error', 400);
            }

            // Fetch subcategories using parameterized query
            $subCategories = DB::select("
                SELECT 
                    sc.id,
                    sc.sub_category_in_english,
                    sc.sub_category_in_bangla,
                    ecsm.category_id,
                    ecsm.company_id,
                    ecsm.is_client_visible
                FROM helpdesk.sub_categories sc
                INNER JOIN helpdesk.entity_category_subcategory_mappings ecsm 
                    ON sc.id = ecsm.sub_category_id
                WHERE ecsm.is_client_visible = 1
                    AND ecsm.category_id = ?
                    AND ecsm.company_id = ?
                ORDER BY sc.sub_category_in_english ASC
            ", [$categoryId, $entityId]);

            return ApiResponse::success($subCategories, 'Success', 200);

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), 'Error', 500);
        }
    }



    // public function updateSubCategoryVisibility(Request $request)
    // {

    // // return 'hi';
    //     try {
    //         $mapping = EntityCategorySubcategoryMapping::where('company_id', (string)$request->company_id)
    //             ->where('category_id', (string)$request->category_id)
    //             ->where('sub_category_id', (string)$request->sub_category_id)
    //             ->first();

    //         if (!$mapping) {
    //             return ApiResponse::error("No record found", "Error", 404);
    //         }

    //         $mapping->update([
    //             'is_client_visible' => $mapping->is_client_visible === 1 ? 0 : 1,
    //         ]);

    //         return ApiResponse::success($mapping, "Success", 200);

    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }



    public function updateSubCategoryVisibility(Request $request)
    {
        // TEMP DEBUG - remove after fix
        Log::info('Visibility Update Request:', $request->all());
        
        $records = EntityCategorySubcategoryMapping::where('company_id', (string)$request->company_id)
            ->where('category_id', (string)$request->category_id)
            ->get();
        
        Log::info('Records found by company+category:', $records->toArray());

        try {
            $mapping = EntityCategorySubcategoryMapping::where('company_id', (string)$request->company_id)
                ->where('category_id', (string)$request->category_id)
                ->where('sub_category_id', (string)$request->sub_category_id)
                ->first();
            
            Log::info('Final mapping:', $mapping ? $mapping->toArray() : 'NULL');
            if (!$mapping) {
                    return ApiResponse::error("No record found", "Error", 404);
                }

                $mapping->update([
                    'is_client_visible' => $mapping->is_client_visible === 1 ? 0 : 1,
                ]);

                return ApiResponse::success($mapping, "Success", 200);

            } catch (\Exception $e) {
                return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


}
