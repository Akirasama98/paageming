<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;

class CategoryController extends Controller
{
    private $database;    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database_url'));
        $this->database = $factory->createDatabase();
    }/**
     * @OA\Get(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="List semua kategori",
     *     @OA\Response(
     *         response=200,
     *         description="List kategori"
     *     )
     * )
     */    public function index()
    {
        try {
            $categoriesData = $this->database->getReference('categories')->getValue();
            
            // If no data in Firebase, return empty array
            if (!$categoriesData) {
                return response()->json([]);
            }
            
            // Format the data with IDs
            $categories = [];
            foreach ($categoriesData as $categoryId => $categoryData) {
                $categoryData['id'] = $categoryId;
                $categories[] = $categoryData;
            }
            
            return response()->json($categories);        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch categories: ' . $e->getMessage()
            ], 500);
        }
    }/**
     * @OA\Post(
     *     path="/api/categories",
     *     tags={"Categories"},
     *     summary="Tambah kategori (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Kategori berhasil ditambah"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            // Generate new category ID
            $newRef = $this->database->getReference('categories')->push();
            $categoryId = $newRef->getKey();
            
            $categoryData = [
                'id' => $categoryId,
                'name' => $request->name,
                'description' => $request->description ?? '',
                'created_at' => date('Y-m-d\TH:i:s.u\Z'),
                'updated_at' => date('Y-m-d\TH:i:s.u\Z')
            ];

            // Save to Firebase
            $this->database->getReference('categories/' . $categoryId)->set($categoryData);

            return response()->json($categoryData, 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create category: ' . $e->getMessage()
            ], 500);
        }
    }    /**
     * @OA\Get(
     *     path="/api/categories/{category}",
     *     tags={"Categories"},
     *     summary="Detail kategori",
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail kategori"
     *     )
     * )
     */
    public function show($categoryId)
    {
        try {
            $category = $this->database->getReference('categories/' . $categoryId)->getValue();
            
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }
            
            $category['id'] = $categoryId;
            return response()->json($category);        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch category: ' . $e->getMessage()
            ], 500);
        }
    }/**
     * @OA\Put(
     *     path="/api/categories/{category}",
     *     tags={"Categories"},
     *     summary="Update kategori (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Kategori berhasil diupdate"
     *     )
     * )
     */
    public function update(Request $request, $categoryId)
    {
        try {
            // Check if category exists
            $category = $this->database->getReference('categories/' . $categoryId)->getValue();
            
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }
            
            $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string'
            ]);
            
            // Update data
            $updateData = [];
            if ($request->has('name')) {
                $updateData['name'] = $request->name;
            }
            if ($request->has('description')) {
                $updateData['description'] = $request->description;
            }
            $updateData['updated_at'] = date('Y-m-d\TH:i:s.u\Z');
            
            // Update in Firebase
            $this->database->getReference('categories/' . $categoryId)->update($updateData);
            
            // Get updated data
            $updatedCategory = $this->database->getReference('categories/' . $categoryId)->getValue();
            $updatedCategory['id'] = $categoryId;
            
            return response()->json($updatedCategory);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update category: ' . $e->getMessage()
            ], 500);
        }
    }    /**
     * @OA\Delete(
     *     path="/api/categories/{category}",
     *     tags={"Categories"},
     *     summary="Hapus kategori (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="category",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Kategori berhasil dihapus"
     *     )
     * )
     */
    public function destroy($categoryId)
    {
        try {
            // Check if category exists
            $category = $this->database->getReference('categories/' . $categoryId)->getValue();
            
            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }
            
            // Delete from Firebase
            $this->database->getReference('categories/' . $categoryId)->remove();
            
            return response()->json(null, 204);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete category: ' . $e->getMessage()
            ], 500);
        }
    }
}
