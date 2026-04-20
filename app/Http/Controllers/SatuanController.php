<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSatuanRequest;
use App\Http\Requests\UpdateSatuanRequest;
use App\Http\Resources\SatuanResource;
use App\Models\Satuan;
use Illuminate\Http\JsonResponse;

class SatuanController extends Controller
{
    /**
     * @OA\Get(
     *     path="/satuan",
     *     operationId="indexSatuan",
     *     tags={"Satuan"},
     *     summary="Daftar semua satuan",
     *     description="Mengambil daftar semua satuan barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar satuan berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar satuan berhasil diambil."),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id_satuan", type="integer", example=1),
     *                     @OA\Property(property="nama_satuan", type="string", example="Unit")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => 'Daftar satuan berhasil diambil.',
            'data'    => SatuanResource::collection(Satuan::all()),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/satuan",
     *     operationId="storeSatuan",
     *     tags={"Satuan"},
     *     summary="Tambah satuan baru",
     *     description="Menyimpan data satuan barang baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"nama_satuan"},
     *             @OA\Property(property="nama_satuan", type="string", maxLength=100, example="Unit")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Satuan berhasil ditambahkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Satuan berhasil ditambahkan."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_satuan", type="integer", example=1),
     *                 @OA\Property(property="nama_satuan", type="string", example="Unit")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreSatuanRequest $request): JsonResponse
    {
        $satuan = Satuan::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Satuan berhasil ditambahkan.',
            'data'    => new SatuanResource($satuan),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/satuan/{id}",
     *     operationId="showSatuan",
     *     tags={"Satuan"},
     *     summary="Detail satuan",
     *     description="Mengambil detail satu satuan berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID satuan", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Detail satuan berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail satuan berhasil diambil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_satuan", type="integer", example=1),
     *                 @OA\Property(property="nama_satuan", type="string", example="Unit")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Satuan tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $satuan = Satuan::find($id);

        if (!$satuan) {
            return response()->json([
                'status'  => false,
                'message' => 'Satuan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail satuan berhasil diambil.',
            'data'    => new SatuanResource($satuan),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/satuan/{id}",
     *     operationId="updateSatuan",
     *     tags={"Satuan"},
     *     summary="Update satuan",
     *     description="Memperbarui data satuan berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID satuan", @OA\Schema(type="string", example="1")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_satuan", type="string", maxLength=100, example="Buah")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Satuan berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Satuan berhasil diperbarui."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_satuan", type="integer", example=1),
     *                 @OA\Property(property="nama_satuan", type="string", example="Buah")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Satuan tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdateSatuanRequest $request, string $id): JsonResponse
    {
        $satuan = Satuan::find($id);

        if (!$satuan) {
            return response()->json([
                'status'  => false,
                'message' => 'Satuan tidak ditemukan.',
            ], 404);
        }

        $satuan->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Satuan berhasil diperbarui.',
            'data'    => new SatuanResource($satuan),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/satuan/{id}",
     *     operationId="destroySatuan",
     *     tags={"Satuan"},
     *     summary="Hapus satuan",
     *     description="Menghapus data satuan berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID satuan", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Satuan berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Satuan berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Satuan tidak ditemukan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $satuan = Satuan::find($id);

        if (!$satuan) {
            return response()->json([
                'status'  => false,
                'message' => 'Satuan tidak ditemukan.',
            ], 404);
        }

        $satuan->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Satuan berhasil dihapus.',
        ]);
    }
}
