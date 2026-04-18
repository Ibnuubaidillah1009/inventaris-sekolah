<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeranRequest;
use App\Http\Requests\UpdatePeranRequest;
use App\Http\Resources\PeranResource;
use App\Models\Peran;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PeranController extends Controller
{
    /**
     * Tampilkan daftar semua peran.
     *
     * @OA\Get(
     *     path="/peran",
     *     operationId="indexPeran",
     *     tags={"Peran"},
     *     summary="Daftar semua peran",
     *     description="Mengambil daftar semua peran beserta relasi daftar akses.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar peran berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar peran berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — tidak memiliki hak akses")
     * )
     */
    public function index(): JsonResponse
    {
        $peran = Peran::with('aksesList')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar peran berhasil diambil.',
            'data'    => PeranResource::collection($peran),
        ]);
    }

    /**
     * Simpan peran baru.
     *
     * @OA\Post(
     *     path="/peran",
     *     operationId="storePeran",
     *     tags={"Peran"},
     *     summary="Tambah peran baru",
     *     description="Menyimpan data peran baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_peran"},
     *             @OA\Property(property="nama_peran", type="string", maxLength=100, example="Kepala Sekolah")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Peran berhasil ditambahkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Peran berhasil ditambahkan."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StorePeranRequest $request): JsonResponse
    {
        $peran = Peran::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Peran berhasil ditambahkan.',
            'data'    => new PeranResource($peran),
        ], 201);
    }

    /**
     * Tampilkan detail satu peran.
     *
     * @OA\Get(
     *     path="/peran/{id}",
     *     operationId="showPeran",
     *     tags={"Peran"},
     *     summary="Detail peran",
     *     description="Mengambil detail satu peran berdasarkan ID, termasuk daftar akses.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID peran",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detail peran berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail peran berhasil diambil."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Peran tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $peran = Peran::with('aksesList')->find($id);

        if (!$peran) {
            return response()->json([
                'status'  => false,
                'message' => 'Peran tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail peran berhasil diambil.',
            'data'    => new PeranResource($peran),
        ]);
    }

    /**
     * Update data peran.
     *
     * @OA\Put(
     *     path="/peran/{id}",
     *     operationId="updatePeran",
     *     tags={"Peran"},
     *     summary="Update peran",
     *     description="Memperbarui data peran berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID peran",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_peran", type="string", maxLength=100, example="Wakil Kepala Sekolah")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Peran berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Peran berhasil diperbarui."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Peran tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdatePeranRequest $request, string $id): JsonResponse
    {
        $peran = Peran::find($id);

        if (!$peran) {
            return response()->json([
                'status'  => false,
                'message' => 'Peran tidak ditemukan.',
            ], 404);
        }

        $peran->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Peran berhasil diperbarui.',
            'data'    => new PeranResource($peran),
        ]);
    }

    /**
     * Hapus peran.
     *
     * @OA\Delete(
     *     path="/peran/{id}",
     *     operationId="destroyPeran",
     *     tags={"Peran"},
     *     summary="Hapus peran",
     *     description="Menghapus data peran berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID peran",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Peran berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Peran berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Peran tidak ditemukan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $peran = Peran::find($id);

        if (!$peran) {
            return response()->json([
                'status'  => false,
                'message' => 'Peran tidak ditemukan.',
            ], 404);
        }

        $peran->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Peran berhasil dihapus.',
        ]);
    }

    /**
     * Sinkronisasi hak akses pada sebuah peran.
     * Menerima array id_akses untuk di-sync ke tabel pivot peran_akses.
     *
     * POST /api/peran/{id}/sync-akses
     * Body: { "id_akses": [1, 2, 3] }
     *
     * @OA\Post(
     *     path="/peran/{id}/sync-akses",
     *     operationId="syncAksesPeran",
     *     tags={"Peran"},
     *     summary="Sinkronisasi hak akses peran",
     *     description="Menyinkronkan daftar hak akses (modul) pada sebuah peran. Menimpa seluruh akses sebelumnya.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID peran",
     *         @OA\Schema(type="string", example="1")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"id_akses"},
     *             @OA\Property(
     *                 property="id_akses",
     *                 type="array",
     *                 @OA\Items(type="integer"),
     *                 example={1, 2, 3}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Hak akses berhasil disinkronisasi",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Hak akses peran berhasil disinkronisasi."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Peran tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function syncAkses(Request $request, string $id): JsonResponse
    {
        $peran = Peran::find($id);

        if (!$peran) {
            return response()->json([
                'status'  => false,
                'message' => 'Peran tidak ditemukan.',
            ], 404);
        }

        $request->validate([
            'id_akses'   => ['required', 'array'],
            'id_akses.*' => ['integer', 'exists:akses,id_akses'],
        ]);

        $peran->aksesList()->sync($request->id_akses);
        $peran->load('aksesList');

        return response()->json([
            'status'  => true,
            'message' => 'Hak akses peran berhasil disinkronisasi.',
            'data'    => new PeranResource($peran),
        ]);
    }
}
