<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;

class OrderController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/orders/{order}/confirm",
     *     tags={"Orders"},
     *     summary="Upload bukti pembayaran",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="payment_proof",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bukti pembayaran diupload"
     *     )
     * )
     */
    public function confirm(Request $request, $orderId)
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $order = Order::findOrFail($orderId);
        if ($order->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $image = $request->file('payment_proof');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $localPath = $image->storeAs('payment_proofs', $imageName, 'public');

        // Upload ke Firebase Storage
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
        $storage = $factory->createStorage();
        $bucket = $storage->getBucket();
        $firebaseStoragePath = 'payment_proofs/' . $imageName;
        $bucket->upload(
            fopen(storage_path('app/public/' . $localPath), 'r'),
            ['name' => $firebaseStoragePath]
        );
        $object = $bucket->object($firebaseStoragePath);
        $object->update(['acl' => []], ['predefinedAcl' => 'publicRead']);
        $firebaseImageUrl = sprintf(
            'https://storage.googleapis.com/%s/%s',
            $bucket->name(),
            $firebaseStoragePath
        );
        // Hapus file lokal
        Storage::disk('public')->delete($localPath);

        $order->payment_proof = $firebaseImageUrl;
        $order->status = 'waiting_confirmation';
        $order->save();

        // Simpan/update order ke Firebase Realtime Database
        $database = $factory->createDatabase();
        $database->getReference('orders/'.$order->user_id.'/'.$order->id)
            ->set($order->toArray());

        return response()->json([
            'message' => 'Payment proof uploaded, waiting for admin confirmation.',
            'order' => $order
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/orders/{order}/verify",
     *     tags={"Orders"},
     *     summary="Verifikasi pembayaran (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="order",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Order diverifikasi"
     *     )
     * )
     */
    public function verify($orderId)
    {
        $order = Order::findOrFail($orderId);
        $order->status = 'paid';
        $order->save();

        // Update order di Firebase Realtime Database
        $factory = (new Factory)->withServiceAccount(config('firebase.credentials.file'));
        $database = $factory->createDatabase();
        $database->getReference('orders/'.$order->user_id.'/'.$order->id)
            ->set($order->toArray());

        return response()->json(['message' => 'Order confirmed as paid.', 'order' => $order]);
    }
}
