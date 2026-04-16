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
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $pengguna = Pengguna::where('username', $request->username)->first();

        if (!$pengguna || !Hash::check($request->password, $pengguna->password)) {
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
                'token_type'   => 'Bearer',
            ],
        ]);
    }

    /**
     * Logout — revoke token yang sedang digunakan.
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
