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
     * Tampilkan daftar all satuan.
     *
     * @OA\Get(
     *     path="/satuan",
     *     operationId="indexSatuan",
     *     tags={"Satuan"},
     *     summary="Daftar satuan",
     *     description="Mengambil daftar all satuan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar satuan berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar satuan berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $satuan = Satuan::all();
        return response()->json([
            'status'  => true,
            'message' => 'Daftar satuan berhasil diambil.',
            'data'    => SatuanResource::collection($satuan),
        ]);
    }

    /**
     * Simpan satuan baru.
     *
     * @OA\Post(
     *     path="/satuan",
     *     operationId="storeSatuan",
     *     tags={"Satuan"},
     *     summary="Tambah satuan",
     *     description="Menyimpan data satuan baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"nama_satuan"},
     *             @OA\Property(property="nama_satuan", type="string", example="Unit"),
     *             @OA\Property(property="keterangan", type="string", example="Satuan barang")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Satuan berhasil ditambahkan"),
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
     * Tampilkan detail satuan.
     *
     * @OA\Get(
     *     path="/satuan/{id}",
     *     operationId="showSatuan",
     *     tags={"Satuan"},
     *     summary="Detail satuan",
     *     description="Mengambil detail satu satuan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID satuan", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Detail satuan berhasil diambil"),
     *     @OA\Response(response=404, description="Satuan tidak ditemukan")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $satuan = Satuan::find($id);
        if (!$satuan) {
            return response()->json(['status' => false, 'message' => 'Satuan tidak ditemukan.'], 404);
        }
        return response()->json([
            'status'  => true,
            'message' => 'Detail satuan berhasil diambil.',
            'data'    => new SatuanResource($satuan),
        ]);
    }

    /**
     * Update satuan.
     *
     * @OA\Put(
     *     path="/satuan/{id}",
     *     operationId="updateSatuan",
     *     tags={"Satuan"},
     *     summary="Update satuan",
     *     description="Memperbarui data satuan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID satuan", @OA\Schema(type="integer", example=1)),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_satuan", type="string", example="Unit Updated"),
     *             @OA\Property(property="keterangan", type="string", example="Updated info")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Satuan berhasil diperbarui"),
     *     @OA\Response(response=404, description="Satuan tidak ditemukan")
     * )
     */
    public function update(UpdateSatuanRequest $request, int $id): JsonResponse
    {
        $satuan = Satuan::find($id);
        if (!$satuan) {
            return response()->json(['status' => false, 'message' => 'Satuan tidak ditemukan.'], 404);
        }
        $satuan->update($request->validated());
        return response()->json([
            'status'  => true,
            'message' => 'Satuan berhasil diperbarui.',
            'data'    => new SatuanResource($satuan),
        ]);
    }

    /**
     * Hapus satuan.
     *
     * @OA\Delete(
     *     path="/satuan/{id}",
     *     operationId="destroySatuan",
     *     tags={"Satuan"},
     *     summary="Hapus satuan",
     *     description="Menghapus satuan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID satuan", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Satuan berhasil dihapus")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $satuan = Satuan::find($id);
        if (!$satuan) {
            return response()->json(['status' => false, 'message' => 'Satuan tidak ditemukan.'], 404);
        }
        $satuan->delete();
        return response()->json(['status' => true, 'message' => 'Satuan berhasil dihapus.']);
    }
}
