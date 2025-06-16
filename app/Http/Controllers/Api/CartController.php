<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Kreait\Firebase\Factory;
use App\Models\Cart;
use App\Models\CartItem;

class CartController extends Controller
{
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
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)
                   ->where('status', 'active')
                   ->with('items.product')
                   ->first();

        if (!$cart) {
            return response()->json(['cart' => []]);
        }

        return response()->json(['cart' => $cart]);
    }

    /**
     * @OA\Post(
     *     path="/api/cart/add",
     *     tags={"Cart"},
     *     summary="Tambah produk ke keranjang",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id","quantity"},
     *             @OA\Property(property="product_id", type="integer"),
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
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = $request->user();
        
        // Cari atau buat cart aktif
        $cart = Cart::firstOrCreate([
            'user_id' => $user->id,
            'status' => 'active'
        ]);

        // Cek apakah produk sudah ada di cart
        $cartItem = CartItem::where('cart_id', $cart->id)
                           ->where('product_id', $request->product_id)
                           ->first();

        if ($cartItem) {
            $cartItem->quantity += $request->quantity;
            $cartItem->save();
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        // Simpan ke Firebase
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
        $database = $factory->createDatabase();
        $database->getReference('carts/'.$user->id)
            ->set($cart->load('items.product')->toArray());

        return response()->json(['message' => 'Added to cart', 'cart_item' => $cartItem]);
    }

    /**
     * @OA\Put(
     *     path="/api/cart/update/{item}",
     *     tags={"Cart"},
     *     summary="Update jumlah item di keranjang",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="item",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
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
    public function update(Request $request, $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($item);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Update Firebase
        $cart = $cartItem->cart;
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
        $database = $factory->createDatabase();
        $database->getReference('carts/'.$cart->user_id)
            ->set($cart->load('items.product')->toArray());

        return response()->json(['message' => 'Cart item updated', 'cart_item' => $cartItem]);
    }

    /**
     * @OA\Delete(
     *     path="/api/cart/remove/{item}",
     *     tags={"Cart"},
     *     summary="Hapus item dari keranjang",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="item",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Item dihapus"
     *     )
     * )
     */
    public function remove($item)
    {
        $cartItem = CartItem::findOrFail($item);
        $cart = $cartItem->cart;
        $cartItem->delete();

        // Update Firebase
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
        $database = $factory->createDatabase();
        $database->getReference('carts/'.$cart->user_id)
            ->set($cart->load('items.product')->toArray());

        return response()->json(['message' => 'Cart item removed']);
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
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)
                   ->where('status', 'active')
                   ->with('items.product')
                   ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        // Update status cart menjadi checked_out
        $cart->status = 'checked_out';
        $cart->save();

        // Simpan transaksi ke Firebase
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
        $database = $factory->createDatabase();
        $database->getReference('orders/'.$user->id.'/'.time())
            ->set([
                'cart_id' => $cart->id,
                'items' => $cart->items->toArray(),
                'checkout_at' => now()->toISOString(),
                'status' => 'pending'
            ]);

        // Hapus cart dari Firebase (karena sudah checkout)
        $database->getReference('carts/'.$user->id)->remove();

        return response()->json(['message' => 'Checkout successful', 'order_id' => $cart->id]);
    }
}
