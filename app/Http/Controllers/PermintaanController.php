<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermintaanRequest;
use App\Http\Resources\PermintaanResource;
use App\Models\DetailPermintaan;
use App\Models\Permintaan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(schema="DetailPermintaanResource", type="object",
 *     @OA\Property(property="id_detail_permintaan", type="integer", example=1),
 *     @OA\Property(property="id_permintaan", type="integer", example=1),
 *     @OA\Property(property="id_master_barang", type="integer", example=1),
 *     @OA\Property(property="master_barang", type="object", nullable=true),
 *     @OA\Property(property="jumlah", type="integer", example=5),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Laptop")
 * )
 * @OA\Schema(schema="PermintaanResource", type="object",
 *     @OA\Property(property="id_permintaan", type="integer", example=1),
 *     @OA\Property(property="tanggal_permintaan", type="string", format="date", example="2026-04-18"),
 *     @OA\Property(property="id_pemohon", type="integer", example=5),
 *     @OA\Property(property="pemohon", type="object", nullable=true),
 *     @OA\Property(property="id_penyetuju", type="integer", nullable=true, example=null),
 *     @OA\Property(property="penyetuju", type="object", nullable=true),
 *     @OA\Property(property="status_permintaan", type="string", example="Menunggu"),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Permintaan alat praktik"),
 *     @OA\Property(property="detail_permintaan", type="array", @OA\Items(ref="#/components/schemas/DetailPermintaanResource"))
 * )
 * @OA\Schema(schema="StorePermintaanRequest", type="object", required={"tanggal_permintaan","id_pemohon","detail"},
 *     @OA\Property(property="tanggal_permintaan", type="string", format="date", example="2026-04-18"),
 *     @OA\Property(property="id_pemohon", type="integer", example=5),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Permintaan alat praktik"),
 *     @OA\Property(property="detail", type="array", @OA\Items(type="object", required={"id_master_barang","jumlah"},
 *         @OA\Property(property="id_master_barang", type="integer", example=1),
 *         @OA\Property(property="jumlah", type="integer", minimum=1, example=5),
 *         @OA\Property(property="keterangan", type="string", nullable=true, example="Laptop")
 *     ))
 * )
 * @OA\Schema(schema="KeputusanPermintaanRequest", type="object", required={"status_permintaan","id_penyetuju"},
 *     @OA\Property(property="status_permintaan", type="string", enum={"Disetujui","Ditolak"}, example="Disetujui"),
 *     @OA\Property(property="id_penyetuju", type="integer", example=1)
 * )
 * @OA\Schema(schema="PermintaanListResponse", type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Daftar permintaan berhasil diambil."),
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PermintaanResource"))
 * )
 * @OA\Schema(schema="PermintaanSingleResponse", type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Detail permintaan berhasil diambil."),
 *     @OA\Property(property="data", ref="#/components/schemas/PermintaanResource")
 * )
 * @OA\Schema(schema="PermintaanDeleteResponse", type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Permintaan berhasil dihapus.")
 * )
 */
class PermintaanController extends Controller
{
    /**
     * @OA\Get(path="/permintaan", operationId="indexPermintaan", tags={"Permintaan"}, summary="Daftar semua permintaan", security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/PermintaanListResponse")),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        $data = Permintaan::with(['pemohon', 'penyetuju', 'detailPermintaan.masterBarang'])->orderByDesc('id_permintaan')->get();
        return response()->json(['status' => true, 'message' => 'Daftar permintaan berhasil diambil.', 'data' => PermintaanResource::collection($data)]);
    }

    /**
     * @OA\Post(path="/permintaan", operationId="storePermintaan", tags={"Permintaan"}, summary="Buat permintaan baru", security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StorePermintaanRequest")),
     *     @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/PermintaanSingleResponse")),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Gagal menyimpan")
     * )
     */
    public function store(StorePermintaanRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $permintaan = DB::transaction(function () use ($validated) {
                $permintaan = Permintaan::create([
                    'tanggal_permintaan' => $validated['tanggal_permintaan'],
                    'id_pemohon'         => $validated['id_pemohon'],
                    'status_permintaan'  => 'Menunggu',
                    'keterangan'         => $validated['keterangan'] ?? null,
                ]);
                foreach ($validated['detail'] as $item) {
                    DetailPermintaan::create([
                        'id_permintaan'    => $permintaan->id_permintaan,
                        'id_master_barang' => $item['id_master_barang'],
                        'jumlah'           => $item['jumlah'],
                        'keterangan'       => $item['keterangan'] ?? null,
                    ]);
                }
                return $permintaan;
            });
            $permintaan->load(['pemohon', 'detailPermintaan.masterBarang']);
            return response()->json(['status' => true, 'message' => 'Permintaan berhasil disimpan.', 'data' => new PermintaanResource($permintaan)], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menyimpan permintaan.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(path="/permintaan/{id}", operationId="showPermintaan", tags={"Permintaan"}, summary="Detail permintaan", security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/PermintaanSingleResponse")),
     *     @OA\Response(response=404, description="Tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $permintaan = Permintaan::with(['pemohon', 'penyetuju', 'detailPermintaan.masterBarang'])->find($id);
        if (!$permintaan) { return response()->json(['status' => false, 'message' => 'Permintaan tidak ditemukan.'], 404); }
        return response()->json(['status' => true, 'message' => 'Detail permintaan berhasil diambil.', 'data' => new PermintaanResource($permintaan)]);
    }

    /**
     * @OA\Put(path="/permintaan/{id}/keputusan", operationId="keputusanPermintaan", tags={"Permintaan"}, summary="Keputusan permintaan (setujui/tolak)", security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", example="1")),
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/KeputusanPermintaanRequest")),
     *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/PermintaanSingleResponse")),
     *     @OA\Response(response=404, description="Tidak ditemukan"),
     *     @OA\Response(response=422, description="Sudah diproses")
     * )
     */
    public function keputusan(Request $request, string $id): JsonResponse
    {
        $permintaan = Permintaan::find($id);
        if (!$permintaan) { return response()->json(['status' => false, 'message' => 'Permintaan tidak ditemukan.'], 404); }
        if ($permintaan->status_permintaan !== 'Menunggu') { return response()->json(['status' => false, 'message' => 'Permintaan ini sudah diproses sebelumnya.'], 422); }
        $request->validate(['status_permintaan' => ['required', 'string', 'in:Disetujui,Ditolak'], 'id_penyetuju' => ['required', 'integer', 'exists:pengguna,id_pengguna']]);
        $permintaan->update(['status_permintaan' => $request->status_permintaan, 'id_penyetuju' => $request->id_penyetuju]);
        $permintaan->load(['pemohon', 'penyetuju', 'detailPermintaan.masterBarang']);
        return response()->json(['status' => true, 'message' => "Permintaan berhasil {$request->status_permintaan}.", 'data' => new PermintaanResource($permintaan)]);
    }

    /**
     * @OA\Delete(path="/permintaan/{id}", operationId="destroyPermintaan", tags={"Permintaan"}, summary="Hapus permintaan", security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/PermintaanDeleteResponse")),
     *     @OA\Response(response=404, description="Tidak ditemukan"),
     *     @OA\Response(response=422, description="Tidak berstatus Menunggu")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $permintaan = Permintaan::find($id);
        if (!$permintaan) { return response()->json(['status' => false, 'message' => 'Permintaan tidak ditemukan.'], 404); }
        if ($permintaan->status_permintaan !== 'Menunggu') { return response()->json(['status' => false, 'message' => 'Hanya permintaan berstatus "Menunggu" yang dapat dihapus.'], 422); }
        DB::transaction(function () use ($permintaan) { $permintaan->detailPermintaan()->delete(); $permintaan->delete(); });
        return response()->json(['status' => true, 'message' => 'Permintaan berhasil dihapus.']);
    }
}
