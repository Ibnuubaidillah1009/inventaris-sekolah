<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMerekRequest;
use App\Http\Requests\UpdateMerekRequest;
use App\Http\Resources\MerekResource;
use App\Models\Merek;
use Illuminate\Http\JsonResponse;

class MerekController extends Controller
{
    /**
     * @OA\Get(
     *     path="/merek",
     *     operationId="indexMerek",
     *     tags={"Merek"},
     *     summary="Daftar semua merek",
     *     description="Mengambil daftar semua merek barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar merek berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar merek berhasil diambil."),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id_merek", type="integer", example=1),
     *                     @OA\Property(property="nama_merek", type="string", example="Lenovo")
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
            'message' => 'Daftar merek berhasil diambil.',
            'data'    => MerekResource::collection(Merek::all()),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/merek",
     *     operationId="storeMerek",
     *     tags={"Merek"},
     *     summary="Tambah merek baru",
     *     description="Menyimpan data merek barang baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"nama_merek"},
     *             @OA\Property(property="nama_merek", type="string", maxLength=100, example="Lenovo")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Merek berhasil ditambahkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Merek berhasil ditambahkan."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_merek", type="integer", example=1),
     *                 @OA\Property(property="nama_merek", type="string", example="Lenovo")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreMerekRequest $request): JsonResponse
    {
        $merek = Merek::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Merek berhasil ditambahkan.',
            'data'    => new MerekResource($merek),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/merek/{id}",
     *     operationId="showMerek",
     *     tags={"Merek"},
     *     summary="Detail merek",
     *     description="Mengambil detail satu merek berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID merek", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Detail merek berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail merek berhasil diambil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_merek", type="integer", example=1),
     *                 @OA\Property(property="nama_merek", type="string", example="Lenovo")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Merek tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $merek = Merek::find($id);

        if (!$merek) {
            return response()->json([
                'status'  => false,
                'message' => 'Merek tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail merek berhasil diambil.',
            'data'    => new MerekResource($merek),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/merek/{id}",
     *     operationId="updateMerek",
     *     tags={"Merek"},
     *     summary="Update merek",
     *     description="Memperbarui data merek berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID merek", @OA\Schema(type="string", example="1")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_merek", type="string", maxLength=100, example="HP")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Merek berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Merek berhasil diperbarui."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_merek", type="integer", example=1),
     *                 @OA\Property(property="nama_merek", type="string", example="HP")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Merek tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdateMerekRequest $request, string $id): JsonResponse
    {
        $merek = Merek::find($id);

        if (!$merek) {
            return response()->json([
                'status'  => false,
                'message' => 'Merek tidak ditemukan.',
            ], 404);
        }

        $merek->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Merek berhasil diperbarui.',
            'data'    => new MerekResource($merek),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/merek/{id}",
     *     operationId="destroyMerek",
     *     tags={"Merek"},
     *     summary="Hapus merek",
     *     description="Menghapus data merek berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID merek", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Merek berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Merek berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Merek tidak ditemukan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $merek = Merek::find($id);

        if (!$merek) {
            return response()->json([
                'status'  => false,
                'message' => 'Merek tidak ditemukan.',
            ], 404);
        }

        $merek->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Merek berhasil dihapus.',
        ]);
    }
}
