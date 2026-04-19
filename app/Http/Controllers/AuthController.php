<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\Pengguna;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Login — menggunakan username & password, mengembalikan Sanctum token.
     *
     * @OA\Post(
     *     path="/login",
     *     operationId="login",
     *     tags={"Auth"},
     *     summary="Login pengguna",
     *     description="Autentikasi pengguna menggunakan username dan password, mengembalikan token Sanctum.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","password"},
     *             @OA\Property(property="username", type="string", example="admin"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Login berhasil."),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="pengguna", type="object"),
     *                 @OA\Property(property="access_token", type="string", example="1|abc123tokenxyz")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Username atau password salah",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Username atau password salah.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validasi gagal",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validasi gagal."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $pengguna = Pengguna::where('username', $request->username)->first();

        if (!$pengguna || $request->password !== $pengguna->password) {
            return response()->json([
                'status'  => false,
                'message' => 'Username atau password salah.',
            ], 401);
        }

        // Buat token Sanctum
        $token = $pengguna->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil.',
            'data'    => [
                'pengguna'     => $pengguna->load(['peran', 'kelas', 'mapel', 'unit']),
                'access_token' => $token,
            ],
        ]);
    }

    /**
     * Logout — revoke token yang sedang digunakan.
     *
     * @OA\Post(
     *     path="/logout",
     *     operationId="logout",
     *     tags={"Auth"},
     *     summary="Logout pengguna",
     *     description="Revoke token Sanctum yang sedang digunakan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Logout berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Logout berhasil.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token tidak valid atau tidak diberikan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Me — mengembalikan data pengguna yang sedang login beserta relasinya.
     *
     * @OA\Get(
     *     path="/me",
     *     operationId="me",
     *     tags={"Auth"},
     *     summary="Profil pengguna yang sedang login",
     *     description="Mengembalikan data pengguna yang sedang login beserta relasi peran, akses, kelas, mapel, dan unit.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Data pengguna berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data pengguna berhasil diambil."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token tidak valid atau tidak diberikan",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
    public function me(Request $request): JsonResponse
    {
        $pengguna = $request->user()->load(['peran.aksesList', 'kelas.rombel.jurusan', 'mapel', 'unit']);

        return response()->json([
            'status'  => true,
            'message' => 'Data pengguna berhasil diambil.',
            'data'    => $pengguna,
        ]);
    }
}
