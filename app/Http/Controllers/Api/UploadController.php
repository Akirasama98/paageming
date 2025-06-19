<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;

class UploadController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/upload/image",
     *      operationId="uploadImage",
     *      tags={"Upload"},
     *      summary="Upload image to Firebase Storage",
     *      description="Upload image file to Firebase Storage and return URL",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(
     *                      property="image",
     *                      type="string",
     *                      format="binary",
     *                      description="Image file to upload"
     *                  )
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Image uploaded successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="image_url", type="string"),
     *              @OA\Property(property="message", type="string")
     *          )
     *      )
     * )
     */
    public function uploadImage(Request $request)
    {
        // Enhanced validation with security checks
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // Max 5MB
        ]);

        $image = $request->file('image');
        
        // Security checks
        if (!$image->isValid()) {
            return response()->json(['message' => 'Invalid file upload'], 400);
        }

        // Check actual file type (not just extension)
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $image->getPathname());
        finfo_close($finfo);
        
        $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
        if (!in_array($mimeType, $allowedMimes)) {
            return response()->json(['message' => 'Invalid file type'], 400);
        }

        // Generate secure filename
        $extension = $image->getClientOriginalExtension();
        $imageName = 'img_' . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        
        // Store locally first
        $path = $image->storeAs('images', $imageName, 'public');
        
        try {
            // Upload to Firebase Storage
            $factory = (new Factory)
                ->withServiceAccount(config('firebase.credentials.file'));
            $storage = $factory->createStorage();
            $bucket = $storage->getBucket();
            
            $firebaseStoragePath = 'images/' . $imageName;
            $bucket->upload(
                fopen(storage_path('app/public/' . $path), 'r'),
                [
                    'name' => $firebaseStoragePath,
                    'metadata' => [
                        'contentType' => $mimeType,
                        'uploadedBy' => 'paageming-api',
                        'uploadedAt' => date('Y-m-d H:i:s')
                    ]
                ]
            );
            
            // Get public URL
            $object = $bucket->object($firebaseStoragePath);
            $object->update(['acl' => []], ['predefinedAcl' => 'publicRead']);
            
            $imageUrl = sprintf(
                'https://storage.googleapis.com/%s/%s',
                $bucket->name(),
                $firebaseStoragePath
            );
            
            // Delete local file (cleanup)
            Storage::disk('public')->delete($path);
            
            return response()->json([
                'image_url' => $imageUrl,
                'message' => 'Image uploaded successfully',
                'filename' => $imageName
            ]);
            
        } catch (\Exception $e) {
            // Cleanup local file on error
            Storage::disk('public')->delete($path);
            
            return response()->json([
                'message' => 'Failed to upload image: ' . $e->getMessage()
            ], 500);
        }
    }
}
