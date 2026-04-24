<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStatusBarangRequest;
use App\Http\Requests\UpdateStatusBarangRequest;
use App\Http\Resources\StatusBarangResource;
use App\Models\StatusBarang;
use Illuminate\Http\JsonResponse;

class AsetStatusController extends Controller
{
    /**
     * Tampilkan daftar semua status barang.
     *
     * @OA\Get(
     *     path="/aset-status",
     *     operationId="indexStatusBarang",
     *     tags={"Status Barang"},
     *     summary="Daftar status barang",
     *     description="Mengambil daftar semua data status barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar status barang berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar status barang berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $status = StatusBarang::all();
        return response()->json([
            'status'  => true,
            'message' => 'Daftar status barang berhasil diambil.',
            'data'    => StatusBarangResource::collection($status),
        ]);
    }

    /**
     * Simpan status barang baru.
     *
     * @OA\Post(
     *     path="/aset-status",
     *     operationId="storeStatusBarang",
     *     tags={"Status Barang"},
     *     summary="Tambah status barang",
     *     description="Menyimpan data status barang baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"nama_status"},
     *             @OA\Property(property="nama_status", type="string", example="Tersedia"),
     *             @OA\Property(property="keterangan", type="string", example="Barang tersedia untuk dipinjam")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Status barang berhasil ditambahkan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreStatusBarangRequest $request): JsonResponse
    {
        $status = StatusBarang::create($request->validated());
        return response()->json([
            'status'  => true,
            'message' => 'Status barang berhasil ditambahkan.',
            'data'    => new StatusBarangResource($status),
        ], 201);
    }

    /**
     * Tampilkan detail status barang.
     *
     * @OA\Get(
     *     path="/aset-status/{id}",
     *     operationId="showStatusBarang",
     *     tags={"Status Barang"},
     *     summary="Detail status barang",
     *     description="Mengambil detail satu status barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID status barang", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Detail status barang berhasil diambil"),
     *     @OA\Response(response=404, description="Status barang tidak ditemukan")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $status = StatusBarang::find($id);
        if (!$status) {
            return response()->json(['status' => false, 'message' => 'Status barang tidak ditemukan.'], 404);
        }
        return response()->json([
            'status'  => true,
            'message' => 'Detail status barang berhasil diambil.',
            'data'    => new StatusBarangResource($status),
        ]);
    }

    /**
     * Update status barang.
     *
     * @OA\Put(
     *     path="/aset-status/{id}",
     *     operationId="updateStatusBarang",
     *     tags={"Status Barang"},
     *     summary="Update status barang",
     *     description="Memperbarui data status barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID status barang", @OA\Schema(type="integer", example=1)),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_status", type="string", example="Dipinjam"),
     *             @OA\Property(property="keterangan", type="string", example="Barang sedang dipinjam")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Status barang berhasil diperbarui"),
     *     @OA\Response(response=404, description="Status barang tidak ditemukan")
     * )
     */
    public function update(UpdateStatusBarangRequest $request, int $id): JsonResponse
    {
        $status = StatusBarang::find($id);
        if (!$status) {
            return response()->json(['status' => false, 'message' => 'Status barang tidak ditemukan.'], 404);
        }
        $status->update($request->validated());
        return response()->json([
            'status'  => true,
            'message' => 'Status barang berhasil diperbarui.',
            'data'    => new StatusBarangResource($status),
        ]);
    }

    /**
     * Hapus status barang.
     *
     * @OA\Delete(
     *     path="/aset-status/{id}",
     *     operationId="destroyStatusBarang",
     *     tags={"Status Barang"},
     *     summary="Hapus status barang",
     *     description="Menghapus status barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID status barang", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Status barang berhasil dihapus")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $status = StatusBarang::find($id);
        if (!$status) {
            return response()->json(['status' => false, 'message' => 'Status barang tidak ditemukan.'], 404);
        }
        $status->delete();
        return response()->json(['status' => true, 'message' => 'Status barang berhasil dihapus.']);
    }
}
