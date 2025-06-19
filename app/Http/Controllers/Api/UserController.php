<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     tags={"Users"},
     *     summary="List semua user (admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List user"
     *     )
     * )
     */
    public function index()
    {
        try {
            $factory = (new \Kreait\Firebase\Factory)
                ->withServiceAccount(config('firebase.credentials.file'))
                ->withDatabaseUri(config('firebase.database_url'));
            $database = $factory->createDatabase();
            $usersRef = $database->getReference('users')->getValue();
            $users = [];
            if ($usersRef) {
                foreach ($usersRef as $user) {
                    $users[] = $user;
                }
            }
            return response()->json([
                'data' => $users,
                'message' => 'Users retrieved successfully',
                'total' => count($users)
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'data' => [],
                'message' => 'Failed to fetch users from Firebase',
                'error' => $e->getMessage(),
                'total' => 0
            ], 500);
        }
    }
}
