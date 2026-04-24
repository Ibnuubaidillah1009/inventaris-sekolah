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
     * Tampilkan daftar all master barang.
     *
     * @OA\Get(
     *     path="/master-barang",
     *     operationId="indexMasterBarang",
     *     tags={"Master Barang"},
     *     summary="Daftar master barang",
     *     description="Mengambil daftar master barang beserta kategori, merek, dan satuan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar master barang berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar master barang berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
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
     *     summary="Tambah master barang",
     *     description="Menyimpan data master barang baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"nama_barang","id_kategori","id_merek","id_satuan","jenis_barang","stok_minimal"},
     *             @OA\Property(property="nama_barang", type="string", example="Laptop Lenovo ThinkPad"),
     *             @OA\Property(property="id_kategori", type="integer", example=1),
     *             @OA\Property(property="id_merek", type="integer", example=1),
     *             @OA\Property(property="id_satuan", type="integer", example=1),
     *             @OA\Property(property="jenis_barang", type="string", enum={"Inventaris","Consumable"}, example="Inventaris"),
     *             @OA\Property(property="stok_minimal", type="integer", example=5),
     *             @OA\Property(property="keterangan", type="string", example="Stok untuk lab")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Master barang berhasil ditambahkan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreMasterBarangRequest $request): JsonResponse
    {
        $barang = MasterBarang::create($request->validated());
        return response()->json([
            'status'  => true,
            'message' => 'Master barang berhasil ditambahkan.',
            'data'    => new MasterBarangResource($barang),
        ], 201);
    }

    /**
     * Tampilkan detail master barang.
     *
     * @OA\Get(
     *     path="/master-barang/{id}",
     *     operationId="showMasterBarang",
     *     tags={"Master Barang"},
     *     summary="Detail master barang",
     *     description="Mengambil detail satu master barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID master barang", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Detail master barang berhasil diambil"),
     *     @OA\Response(response=404, description="Master barang tidak ditemukan")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $barang = MasterBarang::with(['kategori', 'merek', 'satuan'])->find($id);
        if (!$barang) {
            return response()->json(['status' => false, 'message' => 'Master barang tidak ditemukan.'], 404);
        }
        return response()->json([
            'status'  => true,
            'message' => 'Detail master barang berhasil diambil.',
            'data'    => new MasterBarangResource($barang),
        ]);
    }

    /**
     * Update master barang.
     *
     * @OA\Put(
     *     path="/master-barang/{id}",
     *     operationId="updateMasterBarang",
     *     tags={"Master Barang"},
     *     summary="Update master barang",
     *     description="Memperbarui data master barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID master barang", @OA\Schema(type="integer", example=1)),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_barang", type="string", example="Laptop Lenovo V14"),
     *             @OA\Property(property="keterangan", type="string", example="Update spek")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Master barang berhasil diperbarui"),
     *     @OA\Response(response=404, description="Master barang tidak ditemukan")
     * )
     */
    public function update(UpdateMasterBarangRequest $request, int $id): JsonResponse
    {
        $barang = MasterBarang::find($id);
        if (!$barang) {
            return response()->json(['status' => false, 'message' => 'Master barang tidak ditemukan.'], 404);
        }
        $barang->update($request->validated());
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
     *     description="Menghapus master barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID master barang", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Master barang berhasil dihapus")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $barang = MasterBarang::find($id);
        if (!$barang) {
            return response()->json(['status' => false, 'message' => 'Master barang tidak ditemukan.'], 404);
        }
        $barang->delete();
        return response()->json(['status' => true, 'message' => 'Master barang berhasil dihapus.']);
    }
}
