<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;

class UnitController extends Controller
{
    /**
     * @OA\Get(
     *     path="/unit",
     *     operationId="indexUnit",
     *     tags={"Unit"},
     *     summary="Daftar semua unit",
     *     description="Mengambil daftar semua unit sekolah.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Daftar unit berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar unit berhasil diambil."),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id_unit", type="integer", example=1),
     *                     @OA\Property(property="nama_unit", type="string", example="Unit Sarana Prasarana")
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
            'message' => 'Daftar unit berhasil diambil.',
            'data'    => UnitResource::collection(Unit::all()),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/unit",
     *     operationId="storeUnit",
     *     tags={"Unit"},
     *     summary="Tambah unit baru",
     *     description="Menyimpan data unit baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_unit"},
     *             @OA\Property(property="nama_unit", type="string", maxLength=100, example="Unit Sarana Prasarana")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Unit berhasil ditambahkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unit berhasil ditambahkan."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_unit", type="integer", example=1),
     *                 @OA\Property(property="nama_unit", type="string", example="Unit Sarana Prasarana")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreUnitRequest $request): JsonResponse
    {
        $unit = Unit::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Unit berhasil ditambahkan.',
            'data'    => new UnitResource($unit),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/unit/{id}",
     *     operationId="showUnit",
     *     tags={"Unit"},
     *     summary="Detail unit",
     *     description="Mengambil detail satu unit berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID unit", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Detail unit berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail unit berhasil diambil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_unit", type="integer", example=1),
     *                 @OA\Property(property="nama_unit", type="string", example="Unit Sarana Prasarana")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Unit tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'status'  => false,
                'message' => 'Unit tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail unit berhasil diambil.',
            'data'    => new UnitResource($unit),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/unit/{id}",
     *     operationId="updateUnit",
     *     tags={"Unit"},
     *     summary="Update unit",
     *     description="Memperbarui data unit berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID unit", @OA\Schema(type="string", example="1")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_unit", type="string", maxLength=100, example="Unit Kurikulum")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Unit berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unit berhasil diperbarui."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_unit", type="integer", example=1),
     *                 @OA\Property(property="nama_unit", type="string", example="Unit Kurikulum")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Unit tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdateUnitRequest $request, string $id): JsonResponse
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'status'  => false,
                'message' => 'Unit tidak ditemukan.',
            ], 404);
        }

        $unit->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Unit berhasil diperbarui.',
            'data'    => new UnitResource($unit),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/unit/{id}",
     *     operationId="destroyUnit",
     *     tags={"Unit"},
     *     summary="Hapus unit",
     *     description="Menghapus data unit berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID unit", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Unit berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Unit berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Unit tidak ditemukan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'status'  => false,
                'message' => 'Unit tidak ditemukan.',
            ], 404);
        }

        $unit->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Unit berhasil dihapus.',
        ]);
    }
}
