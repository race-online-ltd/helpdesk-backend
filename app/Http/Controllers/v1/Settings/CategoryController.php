<?php

namespace App\Http\Controllers\v1\Settings;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryEntityMapping;
use App\Models\CategoryTeam;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function fetchDefaultBusinessEntity($id)
    {
        try {
            $categories = DB::select("SELECT 
    c.*,
    co.company_name
FROM categories c
INNER JOIN category_entity_mappings cem 
    ON cem.category_id = c.id
INNER JOIN companies co 
    ON co.id = cem.company_id
WHERE cem.company_id = '$id'
ORDER BY co.company_name");


            return ApiResponse::success($categories, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    public function uniqueCategory($id)
    {
        try {
            $categories = DB::select("SELECT c.id, c.category_in_english, c.category_in_bangla
                                        FROM categories c 
                                        JOIN category_entity_mappings m ON c.id = m.category_id
                                        WHERE m.company_id = '$id'
                                        ");


            return ApiResponse::success($categories, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    // public function index()
    // {
    //     try {
    //         $categories = DB::select("
    //         SELECT 
    //             c.id AS category_id,
    //             co.company_name,
    //             c.category_in_english,
    //             c.category_in_bangla,
    //             c.created_at AS category_created_at,
    //             t.id AS team_id,
    //             t.team_name,
    //             ct.created_at AS team_created_at
    //         FROM 
    //             helpdesk.categories c
    //         LEFT JOIN 
    //             helpdesk.companies co ON c.company_id = co.id
    //         LEFT JOIN 
    //             helpdesk.category_teams ct ON c.id = ct.category_id
    //         LEFT JOIN 
    //             helpdesk.teams t ON ct.team_id = t.id
    //         ORDER BY 
    //             co.company_name
    //     ");

    //         $mergedData = [];
    //         foreach ($categories as $item) {
    //             $categoryId = $item->category_id;
    //             if (!isset($mergedData[$categoryId])) {
    //                 $mergedData[$categoryId] = [
    //                     'id' => $categoryId,
    //                     'company_name' =>  $item->company_name,
    //                     'category_in_english' => $item->category_in_english,
    //                     'category_in_bangla' => $item->category_in_bangla,
    //                     'created_at' => $item->category_created_at,
    //                     'teams' => []
    //                 ];
    //             }
    //             if ($item->team_id) {
    //                 $mergedData[$categoryId]['teams'][] = [
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
                $categories = DB::select("
                    SELECT 
                        c.id AS category_id,
                        co.id AS company_id,
                        co.company_name,
                        c.category_in_english,
                        c.category_in_bangla,
                        c.created_at AS category_created_at,
                        cem.is_client_visible,
                        t.id AS team_id,
                        t.team_name,
                        ct.created_at AS team_created_at
                    FROM 
                        helpdesk.categories c
                    INNER JOIN 
                        helpdesk.category_entity_mappings cem ON c.id = cem.category_id
                    INNER JOIN 
                        helpdesk.companies co ON cem.company_id = co.id
                    LEFT JOIN 
                        helpdesk.category_teams ct ON c.id = ct.category_id
                    LEFT JOIN 
                        helpdesk.teams t ON ct.team_id = t.id
                    ORDER BY 
                        c.created_at DESC,
                        co.company_name
                ");
                
                $mergedData = [];
                
                foreach ($categories as $item) {
                    // Create unique key for category-company combination
                    $key = $item->category_id . '_' . ($item->company_id ?? 'null');
                    
                    // Initialize row if not exists
                    if (!isset($mergedData[$key])) {
                        $mergedData[$key] = [
                            'id' => $item->category_id,
                            'company_id' => $item->company_id,
                            'is_client_visible' => $item->is_client_visible,
                            'company_name' => $item->company_name ?? 'Unassigned',
                            'category_in_english' => $item->category_in_english,
                            'category_in_bangla' => $item->category_in_bangla,
                            'created_at' => $item->category_created_at,
                            'teams' => []
                        ];
                    }
                    
                    // Add team if exists and not already added
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

    //         $attributes = [
    //             'company_id' => $request->companyId,
    //             'category_in_english' => $request->categoryInEnglish,
    //             'category_in_bangla' => $request->categoryInBangla,
    //         ];
    //         $category = Category::firstOrCreate(
    //             $attributes,
    //             $attributes
    //         );

    //         if (!$category->wasRecentlyCreated) {
    //             return ApiResponse::success($category, "Category already exists!", 409);
    //         }

    //         $teams = $request->teamId;
    //         $dataToInsert = [];
    //         foreach ($teams as $team) {
    //             $dataToInsert[] = [
    //                 'category_id' => $category->id,
    //                 'team_id' => $team,
    //                 'created_at' => now(),
    //                 'updated_at' => now(),
    //             ];
    //         }

    //         CategoryTeam::insert($dataToInsert);

    //         DB::commit();
    //         return ApiResponse::success($team, "Successfully Inserted", 201);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    // public function store(Request $request)
    // {
    //     DB::beginTransaction();
    //     try {
            
    //         $companyIds = $request->companyIds ?? $request->companyId ?? [];
    //         $teamIds = $request->teamIds ?? $request->teamId ?? [];
            
           
    //         $companyIds = is_array($companyIds) ? $companyIds : [$companyIds];
    //         $teamIds = is_array($teamIds) ? $teamIds : [$teamIds];
            
            
    //         if (empty($companyIds) || empty($teamIds) || !$request->categoryInEnglish) {
    //             return ApiResponse::error("Required fields are missing", "Validation Error", 400);
    //         }
            
    //         $insertedCategories = [];
            
            
    //         foreach ($companyIds as $companyId) {
    //             $attributes = [
    //                 'company_id' => $companyId,
    //                 'category_in_english' => $request->categoryInEnglish,
    //                 'category_in_bangla' => $request->categoryInBangla,
    //             ];
                
                
    //             $existingCategory = Category::where($attributes)->first();
                
    //             if ($existingCategory) {
                   
    //                 $existingCategory->teams()->sync($teamIds);
    //                 $insertedCategories[] = $existingCategory;
    //                 continue;
    //             }
                
                
    //             $category = Category::create($attributes);
                
                
    //             $category->teams()->attach($teamIds);
    //             $insertedCategories[] = $category;
    //         }
            
    //         DB::commit();
    //         return ApiResponse::success($insertedCategories, "Successfully Inserted", 201);
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
            // $teamIds = $request->teamIds ?? $request->teamId ?? [];
            
            
            $companyIds = is_array($companyIds) ? $companyIds : [$companyIds];
            // $teamIds = is_array($teamIds) ? $teamIds : [$teamIds];
            
            
            if (empty($companyIds) || !$request->categoryInEnglish) {
                return ApiResponse::error("Required fields are missing", "Validation Error", 400);
            }
            
            
            $categoryAttributes = [
                'category_in_english' => $request->categoryInEnglish,
                'category_in_bangla' => $request->categoryInBangla,
            ];
            
            $category = Category::firstOrCreate($categoryAttributes, $categoryAttributes);
            
        
            $mappingsToInsert = [];
            foreach ($companyIds as $companyId) {
                
                $existingMapping = CategoryEntityMapping::where([
                    'category_id' => $category->id,
                    'company_id' => $companyId,
                ])->first();
                
                if (!$existingMapping) {
                    $mappingsToInsert[] = [
                        'category_id' => $category->id,
                        'company_id' => $companyId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
            
            
            if (!empty($mappingsToInsert)) {
                CategoryEntityMapping::insert($mappingsToInsert);
            }
            
            
            // $category->teams()->sync($teamIds);
            
            DB::commit();
            return ApiResponse::success([
                'category' => $category,
                'companies_mapped' => count($mappingsToInsert),
            ], "Successfully Inserted", 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }

    // public function show($id)
    // {
    //     try {
    //         $category = Category::findOrFail($id);
    //         $categoryTeam = CategoryTeam::where('category_id', $id)->get();
    //         $mergedData = [
    //             'category' => $category,
    //             'category_team' => $categoryTeam,
    //         ];
    //         return ApiResponse::success($mergedData, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    public function show($id)
    {
        try {
            $category = Category::findOrFail($id);
            
            // Get all company mappings for this category
            $categoryMappings = CategoryEntityMapping::where('category_id', $id)
                ->join('companies', 'category_entity_mappings.company_id', '=', 'companies.id')
                ->select('category_entity_mappings.company_id as company_id', 'companies.company_name')
                ->get();
            
            // Get all teams associated with this category
            // $categoryTeams = $category->teams()->get();
            
            // Extract company IDs for the frontend (cast to int)
            $companyIds = $categoryMappings->pluck('company_id')->map(fn($id) => (int)$id)->toArray();
            // $teamIds = $categoryTeams->pluck('id')->map(fn($id) => (int)$id)->toArray();
            
            $mergedData = [
                'category' => $category,
                'company_mappings' => $categoryMappings,
                // 'teams' => $categoryTeams,
                'companyIds' => $companyIds,
                // 'teamIds' => $teamIds,
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
            // $teamIds = $request->teamIds ?? $request->teamId ?? [];
            
            // Ensure they are arrays
            $companyIds = is_array($companyIds) ? $companyIds : [$companyIds];
            // $teamIds = is_array($teamIds) ? $teamIds : [$teamIds];
            
            // Validate required fields
            if (empty($companyIds) || !$request->categoryInEnglish) {
                return ApiResponse::error("Required fields are missing", "Validation Error", 400);
            }
            
            // Update category
            $category = Category::findOrFail($id);
            $category->update([
                'category_in_english' => $request->categoryInEnglish,
                'category_in_bangla' => $request->categoryInBangla,
            ]);
            
            // Delete existing company mappings
            CategoryEntityMapping::where('category_id', $category->id)->delete();
            
            // Create new company mappings
            $mappingsToInsert = [];
            foreach ($companyIds as $companyId) {
                $mappingsToInsert[] = [
                    'category_id' => $category->id,
                    'company_id' => $companyId, // Fixed: was 'company_id', should be 'company_id'
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            
            if (!empty($mappingsToInsert)) {
                CategoryEntityMapping::insert($mappingsToInsert);
            }
            
            // Sync teams
            // $category->teams()->sync($teamIds);
            
            DB::commit();
            return ApiResponse::success($category, "Successfully Updated", 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    // public function update(Request $request, $id)
    // {
    //     DB::beginTransaction();

    //     try {
    //         $category = Category::findOrFail($id);
    //         $category->update([
    //             'company_id' => $request->companyId,
    //             'category_in_english' => $request->categoryInEnglish,
    //             'category_in_bangla' => $request->categoryInBangla,
    //         ]);

    //         CategoryTeam::where('category_id', $category->id)->delete();

    //         $teams = $request->teamId;
    //         foreach ($teams as $team) {
    //             CategoryTeam::updateOrCreate(
    //                 [
    //                     'category_id' => $category->id,
    //                     'team_id' => $team
    //                 ],
    //                 ['updated_at' => now()]
    //             );
    //         }

    //         DB::commit();
    //         return ApiResponse::success($category, "Successfully Updated", 200);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    public function destroy($id)
    {
        $resource = Category::findOrFail($id);
        $resource->delete();
        return response()->json(null, 204);
    }


    public function fetchCategoryAll()
    {
        try {

            $categories = DB::select("SELECT s.id, s.category_in_english FROM helpdesk.categories s");



            return ApiResponse::success($categories, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    // public function fetchCategoryForPartner()
    // {
    //     try {

    //         $categories = DB::select("SELECT s.id, s.category_in_english FROM helpdesk.categories s 
    //         WHERE s.sub_category_in_english IN ('Complaint', 'Support Request', 'IPTSP')");



    //         return ApiResponse::success($categories, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }




    // public function fetchCategoryForPartner($id)
    // {
    //     try {
    //         $categories = DB::select("SELECT 
    //             c.*,
    //             co.company_name
    //         FROM 
    //             helpdesk.categories c
    //         LEFT JOIN helpdesk.category_entity_mappings cm ON c.id = cm.category_id
    //         LEFT JOIN helpdesk.companies co ON cm.company_id = co.id
	// 		WHERE c.category_in_english IN ('Complaint', 'OLT Support', 'IPTSP','Billing Issue','New activation','OTT Service','MAC Clear','REAL IP Request','Support Request')
	// 		AND cm.company_id = '$id'
    //         AND cm.is_client_visible = 1
    //         ORDER BY co.company_name");


    //         return ApiResponse::success($categories, "Success", 200);
    //     } catch (\Exception $e) {
    //         return ApiResponse::error($e->getMessage(), "Error", 500);
    //     }
    // }


    public function fetchCategoryForPartner($id)
    {
        try {
            $categories = DB::select("SELECT 
                c.*,
                co.company_name
            FROM 
                helpdesk.categories c
            LEFT JOIN helpdesk.category_entity_mappings cm ON c.id = cm.category_id
            LEFT JOIN helpdesk.companies co ON cm.company_id = co.id
			WHERE cm.company_id = '$id'
            AND cm.is_client_visible = 1
            ORDER BY co.company_name");


            return ApiResponse::success($categories, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }


    public function updateCategoryVisibility(Request $request)
    {
        try {
           $updated = CategoryEntityMapping::where('company_id', (string)$request->company_id)
            ->where('category_id', (string)$request->category_id)
            ->update([
                'is_client_visible' => (int)$request->is_client_visible === 1 ? 0 : 1,
            ]);

          if ($updated === 0) {
              return ApiResponse::error("No record found", "Error", 404);
          }

            return ApiResponse::success($updated, "Success", 200);
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage(), "Error", 500);
        }
    }
}
