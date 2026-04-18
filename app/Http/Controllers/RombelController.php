<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRombelRequest;
use App\Http\Requests\UpdateRombelRequest;
use App\Http\Resources\RombelResource;
use App\Models\Rombel;
use Illuminate\Http\JsonResponse;

class RombelController extends Controller
{
    /**
     * @OA\Get(
     *     path="/rombel",
     *     operationId="indexRombel",
     *     tags={"Rombel"},
     *     summary="Daftar semua rombel",
     *     description="Mengambil daftar semua rombongan belajar beserta relasi jurusan dan kelas.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar rombel berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar rombel berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(): JsonResponse
    {
        $data = Rombel::with(['jurusan', 'kelas'])->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar rombel berhasil diambil.',
            'data'    => RombelResource::collection($data),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/rombel",
     *     operationId="storeRombel",
     *     tags={"Rombel"},
     *     summary="Tambah rombel baru",
     *     description="Menyimpan data rombongan belajar baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_rombel","id_jurusan"},
     *             @OA\Property(property="nama_rombel", type="string", maxLength=100, example="X TKJ 1"),
     *             @OA\Property(property="id_jurusan", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rombel berhasil ditambahkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rombel berhasil ditambahkan."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreRombelRequest $request): JsonResponse
    {
        $rombel = Rombel::create($request->validated());
        $rombel->load('jurusan');

        return response()->json([
            'status'  => true,
            'message' => 'Rombel berhasil ditambahkan.',
            'data'    => new RombelResource($rombel),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/rombel/{id}",
     *     operationId="showRombel",
     *     tags={"Rombel"},
     *     summary="Detail rombel",
     *     description="Mengambil detail satu rombel beserta jurusan dan kelas.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID rombel", @OA\Schema(type="string", example="1")),
     *     @OA\Response(
     *         response=200,
     *         description="Detail rombel berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail rombel berhasil diambil."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Rombel tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $rombel = Rombel::with(['jurusan', 'kelas'])->find($id);

        if (!$rombel) {
            return response()->json([
                'status'  => false,
                'message' => 'Rombel tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail rombel berhasil diambil.',
            'data'    => new RombelResource($rombel),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/rombel/{id}",
     *     operationId="updateRombel",
     *     tags={"Rombel"},
     *     summary="Update rombel",
     *     description="Memperbarui data rombel berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID rombel", @OA\Schema(type="string", example="1")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_rombel", type="string", maxLength=100, example="XI TKJ 2"),
     *             @OA\Property(property="id_jurusan", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rombel berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rombel berhasil diperbarui."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Rombel tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdateRombelRequest $request, string $id): JsonResponse
    {
        $rombel = Rombel::find($id);

        if (!$rombel) {
            return response()->json([
                'status'  => false,
                'message' => 'Rombel tidak ditemukan.',
            ], 404);
        }

        $rombel->update($request->validated());
        $rombel->load('jurusan');

        return response()->json([
            'status'  => true,
            'message' => 'Rombel berhasil diperbarui.',
            'data'    => new RombelResource($rombel),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/rombel/{id}",
     *     operationId="destroyRombel",
     *     tags={"Rombel"},
     *     summary="Hapus rombel",
     *     description="Menghapus data rombel berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID rombel", @OA\Schema(type="string", example="1")),
     *     @OA\Response(
     *         response=200,
     *         description="Rombel berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rombel berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Rombel tidak ditemukan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $rombel = Rombel::find($id);

        if (!$rombel) {
            return response()->json([
                'status'  => false,
                'message' => 'Rombel tidak ditemukan.',
            ], 404);
        }

        $rombel->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Rombel berhasil dihapus.',
        ]);
    }
}
