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
     * @OA\Get(
     *     path="/lokasi",
     *     operationId="indexLokasi",
     *     tags={"Lokasi"},
     *     summary="Daftar semua lokasi",
     *     description="Mengambil daftar semua lokasi beserta relasi ruang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar lokasi berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar lokasi berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(): JsonResponse
    {
        $data = Lokasi::with('ruang')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar lokasi berhasil diambil.',
            'data'    => LokasiResource::collection($data),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/lokasi",
     *     operationId="storeLokasi",
     *     tags={"Lokasi"},
     *     summary="Tambah lokasi baru",
     *     description="Menyimpan data lokasi / gedung baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"nama_lokasi"},
     *             @OA\Property(property="nama_lokasi", type="string", maxLength=255, example="Gedung A"),
     *             @OA\Property(property="alamat", type="string", nullable=true, example="Jl. Pendidikan No. 1")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Lokasi berhasil ditambahkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lokasi berhasil ditambahkan."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
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
     * @OA\Get(
     *     path="/lokasi/{id}",
     *     operationId="showLokasi",
     *     tags={"Lokasi"},
     *     summary="Detail lokasi",
     *     description="Mengambil detail satu lokasi beserta daftar ruang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID lokasi", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Detail lokasi berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail lokasi berhasil diambil."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Lokasi tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $lokasi = Lokasi::with('ruang')->find($id);

        if (!$lokasi) {
            return response()->json([
                'status'  => false,
                'message' => 'Lokasi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail lokasi berhasil diambil.',
            'data'    => new LokasiResource($lokasi),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/lokasi/{id}",
     *     operationId="updateLokasi",
     *     tags={"Lokasi"},
     *     summary="Update lokasi",
     *     description="Memperbarui data lokasi berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID lokasi", @OA\Schema(type="string", example="1")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_lokasi", type="string", maxLength=255, example="Gedung B"),
     *             @OA\Property(property="alamat", type="string", nullable=true, example="Jl. Pendidikan No. 2")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Lokasi berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lokasi berhasil diperbarui."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Lokasi tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdateLokasiRequest $request, string $id): JsonResponse
    {
        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return response()->json([
                'status'  => false,
                'message' => 'Lokasi tidak ditemukan.',
            ], 404);
        }

        $lokasi->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Lokasi berhasil diperbarui.',
            'data'    => new LokasiResource($lokasi),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/lokasi/{id}",
     *     operationId="destroyLokasi",
     *     tags={"Lokasi"},
     *     summary="Hapus lokasi",
     *     description="Menghapus data lokasi berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID lokasi", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Lokasi berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Lokasi berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Lokasi tidak ditemukan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return response()->json([
                'status'  => false,
                'message' => 'Lokasi tidak ditemukan.',
            ], 404);
        }

        $lokasi->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Lokasi berhasil dihapus.',
        ]);
    }
}
