<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TeamMappingForPartner;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamMappingController extends Controller
{
    /**
     * Fetch all team mappings with relationships
     */
   public function index()
    {
        try {
            $teamMappings = TeamMappingForPartner::with(['company', 'category', 'subcategory', 'team'])
                ->latest()
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Team mappings retrieved successfully',
                'data' => $teamMappings,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching team mappings: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Store a new team mapping
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'company_id' => 'required|integer|exists:companies,id',
                'category_id' => 'required|integer|exists:categories,id',
                'subcategory_id' => 'required|integer|exists:sub_categories,id',
                'team_id' => 'required|integer|exists:teams,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $teamMapping = TeamMappingForPartner::create([
                'company_id' => $request->company_id,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'team_id' => $request->team_id,
                'is_active' => true,
            ]);

            // Load relationships after creation
            $teamMapping->load(['company', 'category', 'subcategory', 'team']);

            return response()->json([
                'status' => true,
                'message' => 'Team mapping created successfully',
                'data' => $teamMapping,
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error creating team mapping: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Fetch a specific team mapping
     */
    public function show($id)
    {
        try {
            $teamMapping = TeamMappingForPartner::with(['company', 'category', 'subcategory', 'team'])
                ->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Team mapping retrieved successfully',
                'data' => $teamMapping,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Team mapping not found',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching team mapping: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Update a team mapping
     */
    public function update(Request $request, $id)
    {
        try {
            $teamMapping = TeamMappingForPartner::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'company_id' => 'sometimes|required|integer|exists:companies,id',
                'category_id' => 'sometimes|required|integer|exists:categories,id',
                'subcategory_id' => 'sometimes|required|integer|exists:sub_categories,id',
                'team_id' => 'sometimes|required|integer|exists:teams,id',
                'is_active' => 'sometimes|boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $teamMapping->update($request->only([
                'company_id',
                'category_id',
                'subcategory_id',
                'team_id',
                'is_active',
            ]));

            $teamMapping->load(['company', 'category', 'subcategory', 'team']);

            return response()->json([
                'status' => true,
                'message' => 'Team mapping updated successfully',
                'data' => $teamMapping,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Team mapping not found',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating team mapping: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Delete a team mapping
     */
    public function destroy($id)
    {
        try {
            $teamMapping = TeamMappingForPartner::findOrFail($id);
            $teamMapping->delete();

            return response()->json([
                'status' => true,
                'message' => 'Team mapping deleted successfully',
                'data' => null,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Team mapping not found',
                'data' => null,
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error deleting team mapping: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Fetch team mappings by company ID
     */
    public function getByCompanyId($companyId)
    {
        try {
            $teamMappings = TeamMappingForPartner::where('company_id', $companyId)
                ->with(['company', 'category', 'subcategory', 'team'])
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Team mappings retrieved successfully',
                'data' => $teamMappings,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching team mappings: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Fetch team mappings by category ID
     */
    public function getByCategoryId($categoryId)
    {
        try {
            $teamMappings = TeamMappingForPartner::where('category_id', $categoryId)
                ->with(['company', 'category', 'subcategory', 'team'])
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Team mappings retrieved successfully',
                'data' => $teamMappings,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching team mappings: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Fetch team mappings by subcategory ID
     */
    public function getBySubcategoryId($subcategoryId)
    {
        try {
            $teamMappings = TeamMappingForPartner::where('subcategory_id', $subcategoryId)
                ->with(['company', 'category', 'subcategory', 'team'])
                ->get();

            return response()->json([
                'status' => true,
                'message' => 'Team mappings retrieved successfully',
                'data' => $teamMappings,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching team mappings: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }



    // public function getSubCategoriesByCategory($categoryId)
    // {

    //     // return 'hi';
    //     try {
            
    //         $category = Category::findOrFail($categoryId);

            
    //         $subCategories = SubCategory::whereHas('categories', function ($query) use ($categoryId) {
    //             $query->where('categories.id', $categoryId);
    //         })
    //         ->select('id', 'sub_category_in_english', 'sub_category_in_bangla')
    //         ->get();

    //         return response()->json([
    //             'status' => true,
    //             'message' => 'Subcategories retrieved successfully',
    //             'data' => $subCategories,
    //         ], 200);

    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Category not found',
    //             'data' => null,
    //         ], 404);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'status' => false,
    //             'message' => 'Error fetching subcategories: ' . $e->getMessage(),
    //             'data' => null,
    //         ], 500);
    //     }
    // }


    public function getSubCategoriesByCategory($categoryId)
    {
        try {
            // Validate category exists
            $category = Category::findOrFail($categoryId);

            // Fetch subcategories using the belongsToMany relationship
            $subCategories = SubCategory::whereHas('categories', function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            })
            ->select('id', 'sub_category_in_english', 'sub_category_in_bangla')
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'Subcategories retrieved successfully',
                'data' => $subCategories,
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found',
                'data' => null,
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching subcategories: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function getSubCategoriesByCategoryDirect($categoryId)
    {
        try {
            
            $category = Category::findOrFail($categoryId);

            
            $subCategories = SubCategory::select(
                'sub_categories.id',
                'sub_categories.sub_category_in_english',
                'sub_categories.sub_category_in_bangla'
            )
            ->join(
                'entity_category_subcategory_mappings',
                'sub_categories.id',
                '=',
                'entity_category_subcategory_mappings.sub_category_id'
            )
            ->where('entity_category_subcategory_mappings.category_id', $categoryId)
            ->get();

            return response()->json([
                'status' => true,
                'message' => 'Subcategories retrieved successfully',
                'data' => $subCategories,
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Category not found',
                'data' => null,
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching subcategories: ' . $e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}