<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use App\Http\Resources\LokasiResource;
use App\Models\Lokasi;
use Illuminate\Http\JsonResponse;

class LokasiController extends Controller
{
    /**
     * Tampilkan daftar all lokasi.
     *
     * @OA\Get(
     *     path="/lokasi",
     *     operationId="indexLokasi",
     *     tags={"Lokasi"},
     *     summary="Daftar lokasi",
     *     description="Mengambil daftar semua lokasi.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar lokasi berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar lokasi berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $lokasi = Lokasi::all();
        return response()->json([
            'status'  => true,
            'message' => 'Daftar lokasi berhasil diambil.',
            'data'    => LokasiResource::collection($lokasi),
        ]);
    }

    /**
     * Simpan lokasi baru.
     *
     * @OA\Post(
     *     path="/lokasi",
     *     operationId="storeLokasi",
     *     tags={"Lokasi"},
     *     summary="Tambah lokasi",
     *     description="Menyimpan data lokasi baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"nama_lokasi"},
     *             @OA\Property(property="nama_lokasi", type="string", example="Gedung A"),
     *             @OA\Property(property="alamat", type="string", example="Jl. Pendidikan No.1"),
     *             @OA\Property(property="keterangan", type="string", example="Utama")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Lokasi berhasil ditambahkan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreLokasiRequest $request): JsonResponse
    {
        $lokasi = Lokasi::create($request->validated());
        return response()->json([
            'status'  => true,
            'message' => 'Lokasi berhasil ditambahkan.',
            'data'    => new LokasiResource($lokasi),
        ], 201);
    }

    /**
     * Tampilkan detail lokasi.
     *
     * @OA\Get(
     *     path="/lokasi/{id}",
     *     operationId="showLokasi",
     *     tags={"Lokasi"},
     *     summary="Detail lokasi",
     *     description="Mengambil detail satu lokasi.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID lokasi", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Detail lokasi berhasil diambil"),
     *     @OA\Response(response=404, description="Lokasi tidak ditemukan")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $lokasi = Lokasi::with('ruang')->find($id);
        if (!$lokasi) {
            return response()->json(['status' => false, 'message' => 'Lokasi tidak ditemukan.'], 404);
        }
        return response()->json([
            'status'  => true,
            'message' => 'Detail lokasi berhasil diambil.',
            'data'    => new LokasiResource($lokasi),
        ]);
    }

    /**
     * Update lokasi.
     *
     * @OA\Put(
     *     path="/lokasi/{id}",
     *     operationId="updateLokasi",
     *     tags={"Lokasi"},
     *     summary="Update lokasi",
     *     description="Memperbarui data lokasi.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID lokasi", @OA\Schema(type="integer", example=1)),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_lokasi", type="string", example="Gedung A Updated"),
     *             @OA\Property(property="keterangan", type="string", example="Updated info")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Lokasi berhasil diperbarui"),
     *     @OA\Response(response=404, description="Lokasi tidak ditemukan")
     * )
     */
    public function update(UpdateLokasiRequest $request, int $id): JsonResponse
    {
        $lokasi = Lokasi::find($id);
        if (!$lokasi) {
            return response()->json(['status' => false, 'message' => 'Lokasi tidak ditemukan.'], 404);
        }
        $lokasi->update($request->validated());
        return response()->json([
            'status'  => true,
            'message' => 'Lokasi berhasil diperbarui.',
            'data'    => new LokasiResource($lokasi),
        ]);
    }

    /**
     * Hapus lokasi.
     *
     * @OA\Delete(
     *     path="/lokasi/{id}",
     *     operationId="destroyLokasi",
     *     tags={"Lokasi"},
     *     summary="Hapus lokasi",
     *     description="Menghapus lokasi.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID lokasi", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Lokasi berhasil dihapus")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $lokasi = Lokasi::find($id);
        if (!$lokasi) {
            return response()->json(['status' => false, 'message' => 'Lokasi tidak ditemukan.'], 404);
        }
        $lokasi->delete();
        return response()->json(['status' => true, 'message' => 'Lokasi berhasil dihapus.']);
    }
}
