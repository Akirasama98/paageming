<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="register",
     *      tags={"Authentication"},
     *      summary="Register a new user",
     *      description="Register a new user account",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password123")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User registered successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="access_token", type="string"),
     *              @OA\Property(property="token_type", type="string", example="Bearer")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error"
     *      )
     * )
     */    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);

        // Generate user ID (timestamp)
        $userId = time();
        $user = [
            'id' => $userId,
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password, // simpan cleartext agar login mudah
            'role' => 'user',
            'created_at' => now()->toIso8601String(),
        ];

        // Simpan ke Firebase
        try {
            $factory = (new \Kreait\Firebase\Factory)
                ->withServiceAccount(config('firebase.credentials.file'))
                ->withDatabaseUri(config('firebase.database_url'));
            $database = $factory->createDatabase();
            $database->getReference('users/' . $userId)->set($user);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Register failed: cannot save to Firebase',
                'error' => $e->getMessage(),
            ], 500);
        }

        // Generate simple JWT-like token for testing
        $token = base64_encode(json_encode([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'expires_at' => time() + (24 * 60 * 60) // 24 hours
        ]));

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Login user (admin/user)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="admin@panenku.com"),
     *             @OA\Property(property="password", type="string", example="admin123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login sukses, token dikembalikan"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Login gagal"
     *     )
     * )
     */    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Hardcoded users for testing (since we're using Firebase for products)
        $testUsers = [
            [
                'id' => 1,
                'name' => 'Admin User',
                'email' => 'admin@paageming.com',
                'password' => 'admin123',
                'role' => 'admin'
            ],
            [
                'id' => 2,
                'name' => 'Test User',
                'email' => 'user@paageming.com',
                'password' => 'user123',
                'role' => 'user'
            ]
        ];

        $user = null;
        foreach ($testUsers as $testUser) {
            if ($testUser['email'] === $request->email && $testUser['password'] === $request->password) {
                $user = $testUser;
                break;
            }
        }

        // Jika tidak ditemukan di hardcoded, cek ke Firebase
        if (!$user) {
            try {
                $factory = (new \Kreait\Firebase\Factory)
                    ->withServiceAccount(config('firebase.credentials.file'))
                    ->withDatabaseUri(config('firebase.database_url'));
                $database = $factory->createDatabase();
                $usersRef = $database->getReference('users')->getValue();
                if ($usersRef) {
                    foreach ($usersRef as $firebaseUser) {
                        if (
                            isset($firebaseUser['email']) && $firebaseUser['email'] === $request->email &&
                            isset($firebaseUser['password']) && $firebaseUser['password'] === $request->password
                        ) {
                            $user = $firebaseUser;
                            break;
                        }
                    }
                }
            } catch (\Throwable $e) {
                return response()->json(['message' => 'Login failed: cannot connect to Firebase', 'error' => $e->getMessage()], 500);
            }
        }

        if (!$user) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Generate simple JWT-like token for testing
        $token = base64_encode(json_encode([
            'user_id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role'],
            'expires_at' => time() + (24 * 60 * 60) // 24 hours
        ]));

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role']
            ]
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/logout",
     *      operationId="logout",
     *      tags={"Authentication"},
     *      summary="Logout user",
     *      description="Logout and revoke current token",
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(
     *          response=200,
     *          description="Successfully logged out"
     *      )
     * )
     */    public function logout(Request $request)
    {
        // For testing purposes, just return success message
        // In real implementation, you would invalidate the token
        return response()->json(['message' => 'Successfully logged out']);
    }
}
