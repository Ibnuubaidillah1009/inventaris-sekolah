<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenggunaRequest;
use App\Http\Requests\UpdatePenggunaRequest;
use App\Http\Resources\PenggunaResource;
use App\Models\Pengguna;
use Illuminate\Http\JsonResponse;

class PenggunaController extends Controller
{
    /**
     * Tampilkan daftar semua pengguna.
     *
     * @OA\Get(
     *     path="/pengguna",
     *     operationId="indexPengguna",
     *     tags={"Pengguna"},
     *     summary="Daftar semua pengguna",
     *     description="Mengambil daftar semua pengguna beserta relasi peran, kelas, mapel, dan unit.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar pengguna berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar pengguna berhasil diambil."),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id_pengguna", type="integer", example=1),
     *                     @OA\Property(property="username", type="string", example="admin"),
     *                     @OA\Property(property="id_peran", type="integer", nullable=true, example=1),
     *                     @OA\Property(property="peran", type="object", nullable=true),
     *                     @OA\Property(property="id_kelas", type="integer", nullable=true, example=null),
     *                     @OA\Property(property="kelas", type="object", nullable=true),
     *                     @OA\Property(property="id_mapel", type="integer", nullable=true, example=null),
     *                     @OA\Property(property="mapel", type="object", nullable=true),
     *                     @OA\Property(property="id_unit", type="integer", nullable=true, example=null),
     *                     @OA\Property(property="unit", type="object", nullable=true)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — tidak memiliki hak akses")
     * )
     */
    public function index(): JsonResponse
    {
        $pengguna = Pengguna::with(['peran', 'kelas', 'mapel', 'unit'])->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar pengguna berhasil diambil.',
            'data'    => PenggunaResource::collection($pengguna),
        ]);
    }

    /**
     * Simpan pengguna baru.
     *
     * @OA\Post(
     *     path="/pengguna",
     *     operationId="storePengguna",
     *     tags={"Pengguna"},
     *     summary="Tambah pengguna baru",
     *     description="Menyimpan data pengguna baru ke dalam sistem.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","password","id_peran"},
     *             @OA\Property(property="username", type="string", maxLength=100, example="guru01"),
     *             @OA\Property(property="password", type="string", minLength=8, example="password123"),
     *             @OA\Property(property="id_peran", type="integer", example=2),
     *             @OA\Property(property="id_kelas", type="integer", nullable=true, example=1),
     *             @OA\Property(property="id_mapel", type="integer", nullable=true, example=3),
     *             @OA\Property(property="id_unit", type="integer", nullable=true, example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Pengguna berhasil ditambahkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pengguna berhasil ditambahkan."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_pengguna", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="guru01"),
     *                 @OA\Property(property="id_peran", type="integer", nullable=true, example=2),
     *                 @OA\Property(property="peran", type="object", nullable=true),
     *                 @OA\Property(property="id_kelas", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="kelas", type="object", nullable=true),
     *                 @OA\Property(property="id_mapel", type="integer", nullable=true, example=3),
     *                 @OA\Property(property="mapel", type="object", nullable=true),
     *                 @OA\Property(property="id_unit", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="unit", type="object", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — tidak memiliki hak akses"),
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
    public function store(StorePenggunaRequest $request): JsonResponse
    {
        $pengguna = Pengguna::create($request->validated());
        $pengguna->load(['peran', 'kelas', 'mapel', 'unit']);

        return response()->json([
            'status'  => true,
            'message' => 'Pengguna berhasil ditambahkan.',
            'data'    => new PenggunaResource($pengguna),
        ], 201);
    }

    /**
     * Tampilkan detail satu pengguna.
     *
     * @OA\Get(
     *     path="/pengguna/{id}",
     *     operationId="showPengguna",
     *     tags={"Pengguna"},
     *     summary="Detail pengguna",
     *     description="Mengambil detail satu pengguna berdasarkan ID, termasuk relasi peran, akses, kelas, mapel, dan unit.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID pengguna",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail pengguna berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail pengguna berhasil diambil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_pengguna", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="guru01"),
     *                 @OA\Property(property="id_peran", type="integer", nullable=true, example=2),
     *                 @OA\Property(property="peran", type="object", nullable=true),
     *                 @OA\Property(property="id_kelas", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="kelas", type="object", nullable=true),
     *                 @OA\Property(property="id_mapel", type="integer", nullable=true, example=3),
     *                 @OA\Property(property="mapel", type="object", nullable=true),
     *                 @OA\Property(property="id_unit", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="unit", type="object", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — tidak memiliki hak akses"),
     *     @OA\Response(
     *         response=404,
     *         description="Pengguna tidak ditemukan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Pengguna tidak ditemukan.")
     *         )
     *     )
     * )
     */
    public function show(string $id): JsonResponse
    {
        $pengguna = Pengguna::with(['peran.aksesList', 'kelas.rombel.jurusan', 'mapel', 'unit'])->find($id);

        if (!$pengguna) {
            return response()->json([
                'status'  => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail pengguna berhasil diambil.',
            'data'    => new PenggunaResource($pengguna),
        ]);
    }

    /**
     * Update data pengguna.
     *
     * @OA\Put(
     *     path="/pengguna/{id}",
     *     operationId="updatePengguna",
     *     tags={"Pengguna"},
     *     summary="Update pengguna",
     *     description="Memperbarui data pengguna berdasarkan ID. Password bersifat opsional.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID pengguna",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="username", type="string", maxLength=100, example="guru01_updated"),
     *             @OA\Property(property="password", type="string", nullable=true, minLength=8, example="newpassword123"),
     *             @OA\Property(property="id_peran", type="integer", example=2),
     *             @OA\Property(property="id_kelas", type="integer", nullable=true, example=1),
     *             @OA\Property(property="id_mapel", type="integer", nullable=true, example=3),
     *             @OA\Property(property="id_unit", type="integer", nullable=true, example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pengguna berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pengguna berhasil diperbarui."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_pengguna", type="integer", example=1),
     *                 @OA\Property(property="username", type="string", example="guru01_updated"),
     *                 @OA\Property(property="id_peran", type="integer", nullable=true, example=2),
     *                 @OA\Property(property="peran", type="object", nullable=true),
     *                 @OA\Property(property="id_kelas", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="kelas", type="object", nullable=true),
     *                 @OA\Property(property="id_mapel", type="integer", nullable=true, example=3),
     *                 @OA\Property(property="mapel", type="object", nullable=true),
     *                 @OA\Property(property="id_unit", type="integer", nullable=true, example=1),
     *                 @OA\Property(property="unit", type="object", nullable=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — tidak memiliki hak akses"),
     *     @OA\Response(response=404, description="Pengguna tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdatePenggunaRequest $request, string $id): JsonResponse
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'status'  => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        $data = $request->validated();

        // Jika password tidak diisi, jangan update password
        if (empty($data['password'])) {
            unset($data['password']);
        }

        $pengguna->update($data);
        $pengguna->load(['peran', 'kelas', 'mapel', 'unit']);

        return response()->json([
            'status'  => true,
            'message' => 'Pengguna berhasil diperbarui.',
            'data'    => new PenggunaResource($pengguna),
        ]);
    }

    /**
     * Hapus pengguna.
     *
     * @OA\Delete(
     *     path="/pengguna/{id}",
     *     operationId="destroyPengguna",
     *     tags={"Pengguna"},
     *     summary="Hapus pengguna",
     *     description="Menghapus data pengguna berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID pengguna",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Pengguna berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Pengguna berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — tidak memiliki hak akses"),
     *     @OA\Response(response=404, description="Pengguna tidak ditemukan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $pengguna = Pengguna::find($id);

        if (!$pengguna) {
            return response()->json([
                'status'  => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        $pengguna->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Pengguna berhasil dihapus.',
        ]);
    }
}
