<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMasterBarangRequest;
use App\Http\Requests\UpdateMasterBarangRequest;
use App\Http\Resources\MasterBarangResource;
use App\Models\MasterBarang;
use Illuminate\Http\JsonResponse;

class MasterBarangController extends Controller
{
    /**
     * Tampilkan daftar semua master barang beserta relasi.
     *
     * @OA\Get(
     *     path="/master-barang",
     *     operationId="indexMasterBarang",
     *     tags={"Master Barang"},
     *     summary="Daftar semua master barang",
     *     description="Mengambil daftar semua master barang beserta relasi kategori, merek, dan satuan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar master barang berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar master barang berhasil diambil."),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id_master_barang", type="integer", example=1),
     *                     @OA\Property(property="nama_barang", type="string", example="Laptop Lenovo ThinkPad"),
     *                     @OA\Property(property="id_kategori", type="integer", example=1),
     *                     @OA\Property(property="kategori", type="object", nullable=true,
     *                         @OA\Property(property="id_kategori", type="integer", example=1),
     *                         @OA\Property(property="nama_kategori", type="string", example="Elektronik")
     *                     ),
     *                     @OA\Property(property="id_merek", type="integer", example=1),
     *                     @OA\Property(property="merek", type="object", nullable=true,
     *                         @OA\Property(property="id_merek", type="integer", example=1),
     *                         @OA\Property(property="nama_merek", type="string", example="Lenovo")
     *                     ),
     *                     @OA\Property(property="id_satuan", type="integer", example=1),
     *                     @OA\Property(property="satuan", type="object", nullable=true,
     *                         @OA\Property(property="id_satuan", type="integer", example=1),
     *                         @OA\Property(property="nama_satuan", type="string", example="Unit")
     *                     )
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
        $barang = MasterBarang::with(['kategori', 'merek', 'satuan'])->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar master barang berhasil diambil.',
            'data'    => MasterBarangResource::collection($barang),
        ]);
    }

    /**
     * Simpan master barang baru.
     *
     * @OA\Post(
     *     path="/master-barang",
     *     operationId="storeMasterBarang",
     *     tags={"Master Barang"},
     *     summary="Tambah master barang baru",
     *     description="Menyimpan data master barang baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"nama_barang","id_kategori","id_merek","id_satuan"},
     *             @OA\Property(property="nama_barang", type="string", maxLength=255, example="Laptop Lenovo ThinkPad"),
     *             @OA\Property(property="id_kategori", type="integer", example=1),
     *             @OA\Property(property="id_merek", type="integer", example=1),
     *             @OA\Property(property="id_satuan", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Master barang berhasil ditambahkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Master barang berhasil ditambahkan."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_master_barang", type="integer", example=1),
     *                 @OA\Property(property="nama_barang", type="string", example="Laptop Lenovo ThinkPad"),
     *                 @OA\Property(property="id_kategori", type="integer", example=1),
     *                 @OA\Property(property="kategori", type="object", nullable=true,
     *                     @OA\Property(property="id_kategori", type="integer", example=1),
     *                     @OA\Property(property="nama_kategori", type="string", example="Elektronik")
     *                 ),
     *                 @OA\Property(property="id_merek", type="integer", example=1),
     *                 @OA\Property(property="merek", type="object", nullable=true,
     *                     @OA\Property(property="id_merek", type="integer", example=1),
     *                     @OA\Property(property="nama_merek", type="string", example="Lenovo")
     *                 ),
     *                 @OA\Property(property="id_satuan", type="integer", example=1),
     *                 @OA\Property(property="satuan", type="object", nullable=true,
     *                     @OA\Property(property="id_satuan", type="integer", example=1),
     *                     @OA\Property(property="nama_satuan", type="string", example="Unit")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreMasterBarangRequest $request): JsonResponse
    {
        $barang = MasterBarang::create($request->validated());
        $barang->load(['kategori', 'merek', 'satuan']);

        return response()->json([
            'status'  => true,
            'message' => 'Master barang berhasil ditambahkan.',
            'data'    => new MasterBarangResource($barang),
        ], 201);
    }

    /**
     * Tampilkan detail satu master barang.
     *
     * @OA\Get(
     *     path="/master-barang/{id}",
     *     operationId="showMasterBarang",
     *     tags={"Master Barang"},
     *     summary="Detail master barang",
     *     description="Mengambil detail satu master barang beserta kategori, merek, dan satuan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID master barang", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Detail master barang berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail master barang berhasil diambil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_master_barang", type="integer", example=1),
     *                 @OA\Property(property="nama_barang", type="string", example="Laptop Lenovo ThinkPad"),
     *                 @OA\Property(property="id_kategori", type="integer", example=1),
     *                 @OA\Property(property="kategori", type="object", nullable=true,
     *                     @OA\Property(property="id_kategori", type="integer", example=1),
     *                     @OA\Property(property="nama_kategori", type="string", example="Elektronik")
     *                 ),
     *                 @OA\Property(property="id_merek", type="integer", example=1),
     *                 @OA\Property(property="merek", type="object", nullable=true,
     *                     @OA\Property(property="id_merek", type="integer", example=1),
     *                     @OA\Property(property="nama_merek", type="string", example="Lenovo")
     *                 ),
     *                 @OA\Property(property="id_satuan", type="integer", example=1),
     *                 @OA\Property(property="satuan", type="object", nullable=true,
     *                     @OA\Property(property="id_satuan", type="integer", example=1),
     *                     @OA\Property(property="nama_satuan", type="string", example="Unit")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Master barang tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $barang = MasterBarang::with(['kategori', 'merek', 'satuan'])->find($id);

        if (!$barang) {
            return response()->json([
                'status'  => false,
                'message' => 'Master barang tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail master barang berhasil diambil.',
            'data'    => new MasterBarangResource($barang),
        ]);
    }

    /**
     * Update data master barang.
     *
     * @OA\Put(
     *     path="/master-barang/{id}",
     *     operationId="updateMasterBarang",
     *     tags={"Master Barang"},
     *     summary="Update master barang",
     *     description="Memperbarui data master barang berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID master barang", @OA\Schema(type="string", example="1")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_barang", type="string", maxLength=255, example="Laptop HP EliteBook"),
     *             @OA\Property(property="id_kategori", type="integer", example=1),
     *             @OA\Property(property="id_merek", type="integer", example=2),
     *             @OA\Property(property="id_satuan", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Master barang berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Master barang berhasil diperbarui."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_master_barang", type="integer", example=1),
     *                 @OA\Property(property="nama_barang", type="string", example="Laptop HP EliteBook"),
     *                 @OA\Property(property="id_kategori", type="integer", example=1),
     *                 @OA\Property(property="kategori", type="object", nullable=true,
     *                     @OA\Property(property="id_kategori", type="integer", example=1),
     *                     @OA\Property(property="nama_kategori", type="string", example="Elektronik")
     *                 ),
     *                 @OA\Property(property="id_merek", type="integer", example=2),
     *                 @OA\Property(property="merek", type="object", nullable=true,
     *                     @OA\Property(property="id_merek", type="integer", example=2),
     *                     @OA\Property(property="nama_merek", type="string", example="HP")
     *                 ),
     *                 @OA\Property(property="id_satuan", type="integer", example=1),
     *                 @OA\Property(property="satuan", type="object", nullable=true,
     *                     @OA\Property(property="id_satuan", type="integer", example=1),
     *                     @OA\Property(property="nama_satuan", type="string", example="Unit")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Master barang tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdateMasterBarangRequest $request, string $id): JsonResponse
    {
        $barang = MasterBarang::find($id);

        if (!$barang) {
            return response()->json([
                'status'  => false,
                'message' => 'Master barang tidak ditemukan.',
            ], 404);
        }

        $barang->update($request->validated());
        $barang->load(['kategori', 'merek', 'satuan']);

        return response()->json([
            'status'  => true,
            'message' => 'Master barang berhasil diperbarui.',
            'data'    => new MasterBarangResource($barang),
        ]);
    }

    /**
     * Hapus master barang.
     *
     * @OA\Delete(
     *     path="/master-barang/{id}",
     *     operationId="destroyMasterBarang",
     *     tags={"Master Barang"},
     *     summary="Hapus master barang",
     *     description="Menghapus data master barang berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID master barang", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Master barang berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Master barang berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Master barang tidak ditemukan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $barang = MasterBarang::find($id);

        if (!$barang) {
            return response()->json([
                'status'  => false,
                'message' => 'Master barang tidak ditemukan.',
            ], 404);
        }

        $barang->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Master barang berhasil dihapus.',
        ]);
    }
}
