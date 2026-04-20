<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRuangRequest;
use App\Http\Requests\UpdateRuangRequest;
use App\Http\Resources\RuangResource;
use App\Models\Ruang;
use Illuminate\Http\JsonResponse;

class RuangController extends Controller
{
    /**
     * @OA\Get(
     *     path="/ruang",
     *     operationId="indexRuang",
     *     tags={"Ruang"},
     *     summary="Daftar semua ruang",
     *     description="Mengambil daftar semua ruang beserta relasi lokasi.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar ruang berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar ruang berhasil diambil."),
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id_ruang", type="integer", example=1),
     *                     @OA\Property(property="nama_ruang", type="string", example="Lab Komputer 1"),
     *                     @OA\Property(property="id_lokasi", type="integer", example=1),
     *                     @OA\Property(property="lokasi", type="object", nullable=true,
     *                         @OA\Property(property="id_lokasi", type="integer", example=1),
     *                         @OA\Property(property="nama_lokasi", type="string", example="Gedung A"),
     *                         @OA\Property(property="alamat", type="string", nullable=true, example="Jl. Pendidikan No. 1")
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
        $data = Ruang::with('lokasi')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar ruang berhasil diambil.',
            'data'    => RuangResource::collection($data),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/ruang",
     *     operationId="storeRuang",
     *     tags={"Ruang"},
     *     summary="Tambah ruang baru",
     *     description="Menyimpan data ruang baru di dalam sebuah lokasi.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"nama_ruang","id_lokasi"},
     *             @OA\Property(property="nama_ruang", type="string", maxLength=100, example="Lab Komputer 1"),
     *             @OA\Property(property="id_lokasi", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Ruang berhasil ditambahkan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Ruang berhasil ditambahkan."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_ruang", type="integer", example=1),
     *                 @OA\Property(property="nama_ruang", type="string", example="Lab Komputer 1"),
     *                 @OA\Property(property="id_lokasi", type="integer", example=1),
     *                 @OA\Property(property="lokasi", type="object", nullable=true,
     *                     @OA\Property(property="id_lokasi", type="integer", example=1),
     *                     @OA\Property(property="nama_lokasi", type="string", example="Gedung A"),
     *                     @OA\Property(property="alamat", type="string", nullable=true, example="Jl. Pendidikan No. 1")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreRuangRequest $request): JsonResponse
    {
        $ruang = Ruang::create($request->validated());
        $ruang->load('lokasi');

        return response()->json([
            'status'  => true,
            'message' => 'Ruang berhasil ditambahkan.',
            'data'    => new RuangResource($ruang),
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/ruang/{id}",
     *     operationId="showRuang",
     *     tags={"Ruang"},
     *     summary="Detail ruang",
     *     description="Mengambil detail satu ruang beserta lokasi.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID ruang", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Detail ruang berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail ruang berhasil diambil."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_ruang", type="integer", example=1),
     *                 @OA\Property(property="nama_ruang", type="string", example="Lab Komputer 1"),
     *                 @OA\Property(property="id_lokasi", type="integer", example=1),
     *                 @OA\Property(property="lokasi", type="object", nullable=true,
     *                     @OA\Property(property="id_lokasi", type="integer", example=1),
     *                     @OA\Property(property="nama_lokasi", type="string", example="Gedung A"),
     *                     @OA\Property(property="alamat", type="string", nullable=true, example="Jl. Pendidikan No. 1")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Ruang tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $ruang = Ruang::with('lokasi')->find($id);

        if (!$ruang) {
            return response()->json([
                'status'  => false,
                'message' => 'Ruang tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail ruang berhasil diambil.',
            'data'    => new RuangResource($ruang),
        ]);
    }

    /**
     * @OA\Put(
     *     path="/ruang/{id}",
     *     operationId="updateRuang",
     *     tags={"Ruang"},
     *     summary="Update ruang",
     *     description="Memperbarui data ruang berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID ruang", @OA\Schema(type="string", example="1")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_ruang", type="string", maxLength=100, example="Lab Komputer 2"),
     *             @OA\Property(property="id_lokasi", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Ruang berhasil diperbarui",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Ruang berhasil diperbarui."),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id_ruang", type="integer", example=1),
     *                 @OA\Property(property="nama_ruang", type="string", example="Lab Komputer 2"),
     *                 @OA\Property(property="id_lokasi", type="integer", example=1),
     *                 @OA\Property(property="lokasi", type="object", nullable=true,
     *                     @OA\Property(property="id_lokasi", type="integer", example=1),
     *                     @OA\Property(property="nama_lokasi", type="string", example="Gedung A"),
     *                     @OA\Property(property="alamat", type="string", nullable=true, example="Jl. Pendidikan No. 1")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Ruang tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdateRuangRequest $request, string $id): JsonResponse
    {
        $ruang = Ruang::find($id);

        if (!$ruang) {
            return response()->json([
                'status'  => false,
                'message' => 'Ruang tidak ditemukan.',
            ], 404);
        }

        $ruang->update($request->validated());
        $ruang->load('lokasi');

        return response()->json([
            'status'  => true,
            'message' => 'Ruang berhasil diperbarui.',
            'data'    => new RuangResource($ruang),
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/ruang/{id}",
     *     operationId="destroyRuang",
     *     tags={"Ruang"},
     *     summary="Hapus ruang",
     *     description="Menghapus data ruang berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID ruang", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Ruang berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Ruang berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Ruang tidak ditemukan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $ruang = Ruang::find($id);

        if (!$ruang) {
            return response()->json([
                'status'  => false,
                'message' => 'Ruang tidak ditemukan.',
            ], 404);
        }

        $ruang->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Ruang berhasil dihapus.',
        ]);
    }
}
