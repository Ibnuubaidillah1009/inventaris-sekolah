<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKondisiRequest;
use App\Http\Requests\UpdateKondisiRequest;
use App\Http\Resources\KondisiResource;
use App\Models\Kondisi;
use Illuminate\Http\JsonResponse;

class AsetKondisiController extends Controller
{
    /**
     * Tampilkan daftar semua kondisi.
     *
     * @OA\Get(
     *     path="/aset-kondisi",
     *     operationId="indexKondisi",
     *     tags={"Kondisi"},
     *     summary="Daftar kondisi",
     *     description="Mengambil daftar semua data kondisi barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar kondisi berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar kondisi berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $kondisi = Kondisi::all();
        return response()->json([
            'status'  => true,
            'message' => 'Daftar kondisi berhasil diambil.',
            'data'    => KondisiResource::collection($kondisi),
        ]);
    }

    /**
     * Simpan kondisi baru.
     *
     * @OA\Post(
     *     path="/aset-kondisi",
     *     operationId="storeKondisi",
     *     tags={"Kondisi"},
     *     summary="Tambah kondisi",
     *     description="Menyimpan data kondisi baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"nama_kondisi"},
     *             @OA\Property(property="nama_kondisi", type="string", example="Baik"),
     *             @OA\Property(property="keterangan", type="string", example="Kondisi barang baik")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Kondisi berhasil ditambahkan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreKondisiRequest $request): JsonResponse
    {
        $kondisi = Kondisi::create($request->validated());
        return response()->json([
            'status'  => true,
            'message' => 'Kondisi berhasil ditambahkan.',
            'data'    => new KondisiResource($kondisi),
        ], 201);
    }

    /**
     * Tampilkan detail kondisi.
     *
     * @OA\Get(
     *     path="/aset-kondisi/{id}",
     *     operationId="showKondisi",
     *     tags={"Kondisi"},
     *     summary="Detail kondisi",
     *     description="Mengambil detail satu kondisi.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID kondisi", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Detail kondisi berhasil diambil"),
     *     @OA\Response(response=404, description="Kondisi tidak ditemukan")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $kondisi = Kondisi::find($id);
        if (!$kondisi) {
            return response()->json(['status' => false, 'message' => 'Kondisi tidak ditemukan.'], 404);
        }
        return response()->json([
            'status'  => true,
            'message' => 'Detail kondisi berhasil diambil.',
            'data'    => new KondisiResource($kondisi),
        ]);
    }

    /**
     * Update kondisi.
     *
     * @OA\Put(
     *     path="/aset-kondisi/{id}",
     *     operationId="updateKondisi",
     *     tags={"Kondisi"},
     *     summary="Update kondisi",
     *     description="Memperbarui data kondisi.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID kondisi", @OA\Schema(type="integer", example=1)),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_kondisi", type="string", example="Rusak Ringan"),
     *             @OA\Property(property="keterangan", type="string", example="Kondisi rusak ringan")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Kondisi berhasil diperbarui"),
     *     @OA\Response(response=404, description="Kondisi tidak ditemukan")
     * )
     */
    public function update(UpdateKondisiRequest $request, int $id): JsonResponse
    {
        $kondisi = Kondisi::find($id);
        if (!$kondisi) {
            return response()->json(['status' => false, 'message' => 'Kondisi tidak ditemukan.'], 404);
        }
        $kondisi->update($request->validated());
        return response()->json([
            'status'  => true,
            'message' => 'Kondisi berhasil diperbarui.',
            'data'    => new KondisiResource($kondisi),
        ]);
    }

    /**
     * Hapus kondisi.
     *
     * @OA\Delete(
     *     path="/aset-kondisi/{id}",
     *     operationId="destroyKondisi",
     *     tags={"Kondisi"},
     *     summary="Hapus kondisi",
     *     description="Menghapus kondisi.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID kondisi", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Kondisi berhasil dihapus")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $kondisi = Kondisi::find($id);
        if (!$kondisi) {
            return response()->json(['status' => false, 'message' => 'Kondisi tidak ditemukan.'], 404);
        }
        $kondisi->delete();
        return response()->json(['status' => true, 'message' => 'Kondisi berhasil dihapus.']);
    }
}
