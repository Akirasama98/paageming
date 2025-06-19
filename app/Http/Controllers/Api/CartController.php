<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private $firebase;
    private $database;

    public function __construct()
    {
        $this->firebase = (new Factory)
            ->withServiceAccount(storage_path('app/firebase-credentials.json'))
            ->withDatabaseUri(env('FIREBASE_DATABASE_URL'));
        
        $this->database = $this->firebase->createDatabase();
    }    private function validateCustomToken(Request $request)
    {
        $authHeader = $request->header('Authorization');
        if (!$authHeader || !str_starts_with($authHeader, 'Bearer ')) {
            return null;
        }

        $token = substr($authHeader, 7);
        
        try {
            // Decode base64 token from AuthController
            $decoded = json_decode(base64_decode($token), true);
            
            if (!$decoded || !isset($decoded['user_id']) || !isset($decoded['email'])) {
                return null;
            }
            
            // Check if token is expired
            if (isset($decoded['expires_at']) && $decoded['expires_at'] < time()) {
                return null;
            }
            
            return (object) [
                'id' => $decoded['user_id'],
                'email' => $decoded['email'],
                'name' => $decoded['name'] ?? '',
                'role' => $decoded['role'] ?? 'user'
            ];
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cart",
     *     tags={"Cart"},
     *     summary="Lihat keranjang user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Isi keranjang"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $this->validateCustomToken($request);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            $cart = $this->database->getReference('carts/' . $user->id)->getValue();
            
            if (!$cart) {
                return response()->json(['cart' => ['items' => [], 'total' => 0]]);
            }

            // Get product details for each cart item
            $cartItems = [];
            $total = 0;

            if (isset($cart['items'])) {
                foreach ($cart['items'] as $productId => $item) {
                    $product = $this->database->getReference('products/' . $productId)->getValue();
                    if ($product) {
                        $itemTotal = $product['price'] * $item['quantity'];
                        $cartItems[] = [
                            'product_id' => $productId,
                            'product' => $product,
                            'quantity' => $item['quantity'],
                            'price' => $product['price'],
                            'total' => $itemTotal
                        ];
                        $total += $itemTotal;
                    }
                }
            }

            return response()->json([
                'cart' => [
                    'items' => $cartItems,
                    'total' => $total,
                    'user_id' => $user->id
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Cart index error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to get cart'], 500);
        }
    }    /**
     * @OA\Post(
     *     path="/api/cart/add",
     *     tags={"Cart"},
     *     summary="Tambah produk ke keranjang",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id","quantity"},
     *             @OA\Property(property="product_id", type="string"),
     *             @OA\Property(property="quantity", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Produk ditambahkan ke keranjang"
     *     )
     * )
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $this->validateCustomToken($request);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Check if product exists
            $product = $this->database->getReference('products/' . $request->product_id)->getValue();
            if (!$product) {
                return response()->json(['error' => 'Product not found'], 404);
            }

            // Get current cart
            $cart = $this->database->getReference('carts/' . $user->id)->getValue();
            if (!$cart) {
                $cart = ['items' => []];
            }

            // Add or update item in cart
            if (isset($cart['items'][$request->product_id])) {
                $cart['items'][$request->product_id]['quantity'] += $request->quantity;
            } else {
                $cart['items'][$request->product_id] = [
                    'quantity' => $request->quantity,
                    'added_at' => now()->toISOString()
                ];
            }

            // Save to Firebase
            $this->database->getReference('carts/' . $user->id)->set($cart);

            return response()->json([
                'message' => 'Added to cart',
                'product_id' => $request->product_id,
                'quantity' => $cart['items'][$request->product_id]['quantity']
            ]);

        } catch (\Exception $e) {
            Log::error('Cart add error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to add to cart'], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/cart/update/{productId}",
     *     tags={"Cart"},
     *     summary="Update jumlah item di keranjang",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"quantity"},
     *             @OA\Property(property="quantity", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Item diupdate"
     *     )
     * )
     */
    public function update(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $this->validateCustomToken($request);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Get current cart
            $cart = $this->database->getReference('carts/' . $user->id)->getValue();
            if (!$cart || !isset($cart['items'][$productId])) {
                return response()->json(['error' => 'Item not found in cart'], 404);
            }

            // Update quantity
            $cart['items'][$productId]['quantity'] = $request->quantity;

            // Save to Firebase
            $this->database->getReference('carts/' . $user->id)->set($cart);

            return response()->json([
                'message' => 'Cart item updated',
                'product_id' => $productId,
                'quantity' => $request->quantity
            ]);

        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update cart'], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/cart/remove/{productId}",
     *     tags={"Cart"},
     *     summary="Hapus item dari keranjang",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="productId",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Item dihapus"
     *     )
     * )
     */
    public function remove($productId)
    {
        $request = request();
        $user = $this->validateCustomToken($request);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Get current cart
            $cart = $this->database->getReference('carts/' . $user->id)->getValue();
            if (!$cart || !isset($cart['items'][$productId])) {
                return response()->json(['error' => 'Item not found in cart'], 404);
            }

            // Remove item
            unset($cart['items'][$productId]);

            // Save to Firebase
            $this->database->getReference('carts/' . $user->id)->set($cart);

            return response()->json(['message' => 'Cart item removed']);

        } catch (\Exception $e) {
            Log::error('Cart remove error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to remove from cart'], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/api/cart/checkout",
     *     tags={"Cart"},
     *     summary="Checkout keranjang",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Checkout sukses"
     *     )
     * )
     */
    public function checkout(Request $request)
    {
        $user = $this->validateCustomToken($request);
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Get current cart
            $cart = $this->database->getReference('carts/' . $user->id)->getValue();
            if (!$cart || empty($cart['items'])) {
                return response()->json(['error' => 'Cart is empty'], 400);
            }

            // Calculate total and create order
            $orderItems = [];
            $total = 0;

            foreach ($cart['items'] as $productId => $item) {
                $product = $this->database->getReference('products/' . $productId)->getValue();
                if ($product) {
                    $itemTotal = $product['price'] * $item['quantity'];
                    $orderItems[] = [
                        'product_id' => $productId,
                        'product_name' => $product['name'],
                        'price' => $product['price'],
                        'quantity' => $item['quantity'],
                        'total' => $itemTotal
                    ];
                    $total += $itemTotal;
                }
            }

            // Create order ID
            $orderId = 'order_' . $user->id . '_' . time();

            // Save order to Firebase
            $order = [
                'id' => $orderId,
                'user_id' => $user->id,
                'items' => $orderItems,
                'total' => $total,
                'status' => 'pending',
                'created_at' => now()->toISOString()
            ];

            $this->database->getReference('orders/' . $orderId)->set($order);

            // Clear cart
            $this->database->getReference('carts/' . $user->id)->remove();

            return response()->json([
                'message' => 'Checkout successful',
                'order_id' => $orderId,
                'total' => $total
            ]);

        } catch (\Exception $e) {
            Log::error('Cart checkout error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to checkout'], 500);
        }
    }
}
