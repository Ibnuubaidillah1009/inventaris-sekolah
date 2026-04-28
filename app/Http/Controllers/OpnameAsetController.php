<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOpnameAsetRequest;
use App\Http\Requests\UpdateOpnameAsetRequest;
use App\Http\Resources\OpnameAsetResource;
use App\Models\OpnameAset;
use Illuminate\Http\JsonResponse;

/**
 * ============================================================
 *  SCHEMA DEFINITIONS – Opname Aset Module
 * ============================================================
 *
 * @OA\Schema(
 *     schema="OpnameAsetInlineAset",
 *     type="object",
 *     description="Nested aset object di dalam OpnameAsetResource",
 *     @OA\Property(property="kode_barang", type="string", example="BRG-2026-001"),
 *     @OA\Property(property="master_barang", type="object", nullable=true,
 *         @OA\Property(property="id_master_barang", type="integer", example=1),
 *         @OA\Property(property="nama_barang", type="string", example="Laptop Lenovo ThinkPad")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="OpnameAsetResource",
 *     type="object",
 *     description="Representasi data opname aset",
 *     @OA\Property(property="id_opname_aset", type="integer", example=1),
 *     @OA\Property(property="kode_barang", type="string", example="BRG-2026-001"),
 *     @OA\Property(property="nama_barang", type="string", nullable=true, example="Laptop Lenovo ThinkPad"),
 *     @OA\Property(property="tanggal_opname", type="string", format="date", example="2026-04-27"),
 *     @OA\Property(property="kondisi_ditemukan", type="string", example="Baik"),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Kondisi sesuai data"),
 *     @OA\Property(property="id_pemeriksa", type="integer", nullable=true, example=1),
 *     @OA\Property(property="aset", ref="#/components/schemas/OpnameAsetInlineAset", nullable=true)
 * )
 *
 * @OA\Schema(
 *     schema="StoreOpnameAsetRequest",
 *     type="object",
 *     required={"kode_barang", "tanggal_opname", "kondisi_ditemukan"},
 *     description="Payload untuk menambah opname aset baru",
 *     @OA\Property(property="kode_barang", type="string", example="BRG-2026-001"),
 *     @OA\Property(property="tanggal_opname", type="string", format="date", example="2026-04-27"),
 *     @OA\Property(property="kondisi_ditemukan", type="string", enum={"Baik","Rusak Ringan","Rusak Berat","Hilang"}, example="Baik"),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Kondisi sesuai data")
 * )
 *
 * @OA\Schema(
 *     schema="UpdateOpnameAsetRequest",
 *     type="object",
 *     description="Payload untuk memperbarui opname aset",
 *     @OA\Property(property="kode_barang", type="string", example="BRG-2026-001"),
 *     @OA\Property(property="tanggal_opname", type="string", format="date", example="2026-04-27"),
 *     @OA\Property(property="kondisi_ditemukan", type="string", enum={"Baik","Rusak Ringan","Rusak Berat","Hilang"}, example="Rusak Ringan"),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Ada kerusakan minor")
 * )
 *
 * @OA\Schema(
 *     schema="OpnameAsetListResponse",
 *     type="object",
 *     description="Response wrapper untuk daftar opname aset",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Daftar opname aset berhasil diambil."),
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/OpnameAsetResource"))
 * )
 *
 * @OA\Schema(
 *     schema="OpnameAsetSingleResponse",
 *     type="object",
 *     description="Response wrapper untuk satu opname aset",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Detail opname aset berhasil diambil."),
 *     @OA\Property(property="data", ref="#/components/schemas/OpnameAsetResource")
 * )
 *
 * @OA\Schema(
 *     schema="OpnameAsetDeleteResponse",
 *     type="object",
 *     description="Response wrapper untuk penghapusan opname aset",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Opname aset berhasil dihapus.")
 * )
 */
class OpnameAsetController extends Controller
{
    /**
     * @OA\Get(path="/opname-aset", operationId="indexOpnameAset", tags={"Opname Aset"}, summary="Daftar opname aset", security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar opname aset berhasil diambil",
     *         @OA\JsonContent(ref="#/components/schemas/OpnameAsetListResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        $opname = OpnameAset::with('aset.masterBarang')->get();
        return response()->json([
            'status'  => true,
            'message' => 'Daftar opname aset berhasil diambil.',
            'data'    => OpnameAsetResource::collection($opname),
        ]);
    }

    /**
     * @OA\Post(path="/opname-aset", operationId="storeOpnameAset", tags={"Opname Aset"}, summary="Tambah opname aset", security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StoreOpnameAsetRequest")),
     *     @OA\Response(response=201, description="Opname aset berhasil ditambahkan",
     *         @OA\JsonContent(ref="#/components/schemas/OpnameAsetSingleResponse")
     *     ),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreOpnameAsetRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['tanggal_opname'] = $data['tanggal_opname'] ?? now()->toDateString();
        if (!isset($data['id_pemeriksa']) && $request->user()) {
            $data['id_pemeriksa'] = $request->user()->id_pengguna;
        }

        $opname = OpnameAset::create($data);
        $opname->load('aset.masterBarang');

        return response()->json([
            'status'  => true,
            'message' => 'Opname aset berhasil ditambahkan.',
            'data'    => new OpnameAsetResource($opname),
        ], 201);
    }

    /**
     * @OA\Get(path="/opname-aset/{id}", operationId="showOpnameAset", tags={"Opname Aset"}, summary="Detail opname aset", security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Detail opname aset berhasil diambil",
     *         @OA\JsonContent(ref="#/components/schemas/OpnameAsetSingleResponse")
     *     ),
     *     @OA\Response(response=404, description="Tidak ditemukan")
     * )
     */
    public function show($id): JsonResponse
    {
        $opname = OpnameAset::with('aset.masterBarang')->find($id);
        if (!$opname) {
            return response()->json(['status' => false, 'message' => 'Opname aset tidak ditemukan.'], 404);
        }
        return response()->json([
            'status'  => true,
            'message' => 'Detail opname aset berhasil diambil.',
            'data'    => new OpnameAsetResource($opname),
        ]);
    }

    /**
     * @OA\Put(path="/opname-aset/{id}", operationId="updateOpnameAset", tags={"Opname Aset"}, summary="Update opname aset", security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/UpdateOpnameAsetRequest")),
     *     @OA\Response(response=200, description="Opname aset berhasil diperbarui",
     *         @OA\JsonContent(ref="#/components/schemas/OpnameAsetSingleResponse")
     *     ),
     *     @OA\Response(response=404, description="Tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdateOpnameAsetRequest $request, int $id): JsonResponse
    {
        $opname = OpnameAset::find($id);
        if (!$opname) {
            return response()->json(['status' => false, 'message' => 'Opname aset tidak ditemukan.'], 404);
        }
        $opname->update($request->validated());
        $opname->load('aset.masterBarang');

        return response()->json([
            'status'  => true,
            'message' => 'Opname aset berhasil diperbarui.',
            'data'    => new OpnameAsetResource($opname),
        ]);
    }

    /**
     * @OA\Delete(path="/opname-aset/{id}", operationId="destroyOpnameAset", tags={"Opname Aset"}, summary="Hapus opname aset", security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer", example=1)),
     *     @OA\Response(response=200, description="Opname aset berhasil dihapus",
     *         @OA\JsonContent(ref="#/components/schemas/OpnameAsetDeleteResponse")
     *     ),
     *     @OA\Response(response=404, description="Tidak ditemukan")
     * )
     */
    public function destroy(int $id): JsonResponse
    {
        $opname = OpnameAset::find($id);
        if (!$opname) {
            return response()->json(['status' => false, 'message' => 'Opname aset tidak ditemukan.'], 404);
        }
        $opname->delete();
        return response()->json(['status' => true, 'message' => 'Opname aset berhasil dihapus.']);
    }
}
