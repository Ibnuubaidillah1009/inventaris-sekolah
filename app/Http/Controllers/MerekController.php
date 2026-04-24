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
     * Tampilkan daftar all merek.
     *
     * @OA\Get(
     *     path="/merek",
     *     operationId="indexMerek",
     *     tags={"Merek"},
     *     summary="Daftar merek",
     *     description="Mengambil daftar all merek.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar merek berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar merek berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $merek = Merek::all();
        return response()->json([
            'status'  => true,
            'message' => 'Daftar merek berhasil diambil.',
            'data'    => MerekResource::collection($merek),
        ]);
    }

    /**
     * Simpan merek baru.
     *
     * @OA\Post(
     *     path="/merek",
     *     operationId="storeMerek",
     *     tags={"Merek"},
     *     summary="Tambah merek",
     *     description="Menyimpan data merek baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"nama_merek"},
     *             @OA\Property(property="nama_merek", type="string", example="Lenovo"),
     *             @OA\Property(property="keterangan", type="string", example="Merek Laptop")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Merek berhasil ditambahkan"),
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
     * Tampilkan detail merek.
     *
     * @OA\Get(
     *     path="/merek/{id}",
     *     operationId="showMerek",
     *     tags={"Merek"},
     *     summary="Detail merek",
     *     description="Mengambil detail satu merek.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID merek", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Detail merek berhasil diambil"),
     *     @OA\Response(response=404, description="Merek tidak ditemukan")
     * )
     */
    public function show(int $id): JsonResponse
    {
        $merek = Merek::find($id);
        if (!$merek) {
            return response()->json(['status' => false, 'message' => 'Merek tidak ditemukan.'], 404);
        }
        return response()->json([
            'status'  => true,
            'message' => 'Detail merek berhasil diambil.',
            'data'    => new MerekResource($merek),
        ]);
    }

    /**
     * Update merek.
     *
     * @OA\Put(
     *     path="/merek/{id}",
     *     operationId="updateMerek",
     *     tags={"Merek"},
     *     summary="Update merek",
     *     description="Memperbarui data merek.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID merek", @OA\Schema(type="integer", example=1)),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_merek", type="string", example="Lenovo Updated"),
     *             @OA\Property(property="keterangan", type="string", example="Updated info")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Merek berhasil diperbarui"),
     *     @OA\Response(response=404, description="Merek tidak ditemukan")
     * )
     */
    public function update(UpdateMerekRequest $request, int $id): JsonResponse
    {
        $merek = Merek::find($id);
        if (!$merek) {
            return response()->json(['status' => false, 'message' => 'Merek tidak ditemukan.'], 404);
        }
        $merek->update($request->validated());
        return response()->json([
            'status'  => true,
            'message' => 'Merek berhasil diperbarui.',
            'data'    => new MerekResource($merek),
        ]);
    }

    /**
     * Hapus merek.
     *
     * @OA\Delete(
     *     path="/merek/{id}",
     *     operationId="destroyMerek",
     *     tags={"Merek"},
     *     summary="Hapus merek",
     *     description="Menghapus merek.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID merek", @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Merek berhasil dihapus")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $merek = Merek::find($id);
        if (!$merek) {
            return response()->json(['status' => false, 'message' => 'Merek tidak ditemukan.'], 404);
        }
        $merek->delete();
        return response()->json(['status' => true, 'message' => 'Merek berhasil dihapus.']);
    }
}
