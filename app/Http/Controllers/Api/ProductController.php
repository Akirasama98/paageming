<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    private $database;
    
    public function __construct()
    {
        $factory = (new Factory)
            ->withServiceAccount(config('firebase.credentials.file'))
            ->withDatabaseUri(config('firebase.database_url'));
        $this->database = $factory->createDatabase();    }    /**
     * @OA\Get(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="List semua produk",
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter produk berdasarkan kategori",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List produk"
     *     )
     * )
     */
    public function index(Request $request)
    {
        try {
            $products = $this->database->getReference('products')->getValue() ?? [];
            
            // Convert Firebase object to array with proper structure
            $productList = [];
            foreach ($products as $id => $product) {
                if (is_array($product)) {
                    $product['id'] = $id;
                    $productList[] = $product;
                }
            }
            
            // Filter by category if requested
            if ($request->has('category')) {
                $categoryId = $request->get('category');
                $productList = array_filter($productList, function($product) use ($categoryId) {
                    return isset($product['category_id']) && $product['category_id'] == $categoryId;
                });
            }
            
            return response()->json(array_values($productList));
        } catch (\Exception $e) {
            Log::error('Firebase get products error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     tags={"Products"},
     *     summary="Tambah produk (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","price","category_id"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="stock", type="integer"),
     *             @OA\Property(property="category_id", type="integer"),
     *             @OA\Property(property="image_url", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Produk berhasil ditambah"
     *     )
     * )
     */    public function store(Request $request)
    {
        // Cek role admin dari token
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token = substr($authHeader, 7);
        $payload = json_decode(base64_decode($token), true);
        if (!isset($payload['role']) || $payload['role'] !== 'admin') {
            return response()->json(['error' => 'Forbidden: admin only'], 403);
        }
        
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required', // Tidak dipaksa integer
            'stock' => 'integer|min:0',
            'description' => 'string'
        ]);
        
        try {
            // Generate new string ID (misal: prod_{timestamp}_{rand})
            $productId = 'prod_' . time() . '_' . rand(1000,9999);
            $newProductRef = $this->database->getReference('products/' . $productId);
            
            // Prepare product data
            $productData = [
                'id' => $productId,
                'name' => $request->name,
                'description' => $request->description ?? '',
                'price' => (int) $request->price,
                'stock' => (int) ($request->stock ?? 0),
                'category_id' => $request->category_id, // Bisa string atau int
                'image_url' => $request->image_url ?? null,
                'created_at' => now()->toIso8601String(),
                'updated_at' => now()->toIso8601String()
            ];
            
            // Save to Firebase
            $newProductRef->set($productData);
            
            return response()->json($productData, 201);
        } catch (\Exception $e) {
            Log::error('Firebase create product error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create product'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/products/{product}",
     *     tags={"Products"},
     *     summary="Detail produk",
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail produk"
     *     )
     * )
     */    public function show($id)
    {
        try {
            $product = $this->database->getReference('products/' . $id)->getValue();
            
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }
            
            $product['id'] = $id;
            return response()->json($product);
        } catch (\Exception $e) {
            Log::error('Firebase get product error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch product'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/products/{product}",
     *     tags={"Products"},
     *     summary="Update produk (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="price", type="number"),
     *             @OA\Property(property="stock", type="integer"),
     *             @OA\Property(property="category_id", type="string"),
     *             @OA\Property(property="image_url", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produk berhasil diupdate"
     *     )
     * )
     */    public function update(Request $request, $id)
    {
        // Cek role admin dari token
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token = substr($authHeader, 7);
        $payload = json_decode(base64_decode($token), true);
        if (!isset($payload['role']) || $payload['role'] !== 'admin') {
            return response()->json(['error' => 'Forbidden: admin only'], 403);
        }
        
        try {
            // Check if product exists
            $existingProduct = $this->database->getReference('products/' . $id)->getValue();
            if (!$existingProduct) {
                return response()->json(['error' => 'Product not found'], 404);
            }
            
            // Validate request
            $request->validate([
                'name' => 'string|max:255',
                'price' => 'numeric',
                'category_id' => 'string', // Boleh string
                'stock' => 'integer|min:0',
                'description' => 'string'
            ]);
            
            // Prepare updated data
            $updateData = array_filter([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price ? (int) $request->price : null,
                'stock' => $request->has('stock') ? (int) $request->stock : null,
                'category_id' => $request->category_id ?? null, // Tidak di-cast ke int
                'image_url' => $request->image_url,
                'updated_at' => now()->toIso8601String()
            ], function($value) {
                return $value !== null;
            });
            
            // Update in Firebase
            $this->database->getReference('products/' . $id)->update($updateData);
            
            // Get updated product
            $updatedProduct = $this->database->getReference('products/' . $id)->getValue();
            $updatedProduct['id'] = $id;
            
            return response()->json($updatedProduct);
        } catch (\Exception $e) {
            Log::error('Firebase update product error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update product'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/products/{product}",
     *     tags={"Products"},
     *     summary="Hapus produk (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="product",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Produk berhasil dihapus"
     *     )
     * )
     */    public function destroy($id)
    {
        // Cek role admin dari token
        $authHeader = request()->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $token = substr($authHeader, 7);
        $payload = json_decode(base64_decode($token), true);
        if (!isset($payload['role']) || $payload['role'] !== 'admin') {
            return response()->json(['error' => 'Forbidden: admin only'], 403);
        }
        
        try {
            // Check if product exists
            $existingProduct = $this->database->getReference('products/' . $id)->getValue();
            if (!$existingProduct) {
                return response()->json(['error' => 'Product not found'], 404);
            }
            
            // Delete from Firebase
            $this->database->getReference('products/' . $id)->remove();
            
            return response()->json(null, 204);
        } catch (\Exception $e) {
            Log::error('Firebase delete product error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to delete product'], 500);
        }
    }
}
