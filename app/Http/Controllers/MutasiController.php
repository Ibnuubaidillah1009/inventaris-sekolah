<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMutasiRequest;
use App\Http\Resources\MutasiResource;
use App\Models\Aset;
use App\Models\Mutasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(schema="MutasiResource", type="object",
 *     @OA\Property(property="id_mutasi", type="integer", example=1),
 *     @OA\Property(property="kode_barang", type="string", example="BRG-001"),
 *     @OA\Property(property="aset", ref="#/components/schemas/AsetResource", nullable=true),
 *     @OA\Property(property="id_ruang_asal", type="integer", example=1),
 *     @OA\Property(property="ruang_asal", ref="#/components/schemas/RuangResource", nullable=true),
 *     @OA\Property(property="id_ruang_tujuan", type="integer", example=3),
 *     @OA\Property(property="ruang_tujuan", ref="#/components/schemas/RuangResource", nullable=true),
 *     @OA\Property(property="tanggal_mutasi", type="string", format="date", example="2026-04-18"),
 *     @OA\Property(property="id_petugas", type="integer", example=1),
 *     @OA\Property(property="petugas", ref="#/components/schemas/PenggunaResource", nullable=true),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Pindah ke lab baru")
 * )
 * @OA\Schema(schema="StoreMutasiRequest", type="object", required={"kode_barang","id_ruang_tujuan","tanggal_mutasi"},
 *     @OA\Property(property="kode_barang", type="string", example="BRG-001"),
 *     @OA\Property(property="id_ruang_tujuan", type="integer", example=3),
 *     @OA\Property(property="tanggal_mutasi", type="string", format="date", example="2026-04-18"),
 *     @OA\Property(property="alasan_mutasi", type="string", nullable=true, example="Pindah ke lab baru")
 * )
 * @OA\Schema(schema="MutasiListResponse", type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Daftar mutasi aset berhasil diambil."),
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/MutasiResource"))
 * )
 * @OA\Schema(schema="MutasiSingleResponse", type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Detail mutasi berhasil diambil."),
 *     @OA\Property(property="data", ref="#/components/schemas/MutasiResource")
 * )
 * @OA\Schema(schema="MutasiDeleteResponse", type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Data mutasi berhasil dihapus.")
 * )
 */
class MutasiController extends Controller
{
    /**
     * @OA\Get(path="/mutasi", operationId="indexMutasi", tags={"Mutasi"}, summary="Daftar semua mutasi aset", security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/MutasiListResponse")),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        $data = Mutasi::with(['aset.masterBarang', 'ruangAsal.lokasi', 'ruangTujuan.lokasi', 'penanggungJawab'])->orderByDesc('id_mutasi')->get();
        return response()->json(['status' => true, 'message' => 'Daftar mutasi aset berhasil diambil.', 'data' => MutasiResource::collection($data)]);
    }

    /**
     * @OA\Post(path="/mutasi", operationId="storeMutasi", tags={"Mutasi"}, summary="Buat mutasi aset baru", security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StoreMutasiRequest")),
     *     @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/MutasiSingleResponse")),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Gagal menyimpan")
     * )
     */
    public function store(StoreMutasiRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $mutasi = DB::transaction(function () use ($validated, $request) {
                $aset = Aset::findOrFail($validated['kode_barang']);
                $idRuangAsal = $aset->id_ruang;
                $mutasi = Mutasi::create([
                    'kode_barang'         => $validated['kode_barang'],
                    'id_ruang_asal'       => $idRuangAsal,
                    'id_ruang_tujuan'     => $validated['id_ruang_tujuan'],
                    'tanggal_mutasi'      => $validated['tanggal_mutasi'],
                    'id_penanggung_jawab' => $request->user()->id_pengguna,
                    'alasan_mutasi'       => $validated['alasan_mutasi'] ?? null,
                ]);
                $aset->update(['id_ruang' => $validated['id_ruang_tujuan']]);
                return $mutasi;
            });
            $mutasi->load(['aset.masterBarang', 'ruangAsal.lokasi', 'ruangTujuan.lokasi', 'penanggungJawab']);
            return response()->json(['status' => true, 'message' => 'Mutasi aset berhasil disimpan.', 'data' => new MutasiResource($mutasi)], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menyimpan mutasi aset.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(path="/mutasi/{id}", operationId="showMutasi", tags={"Mutasi"}, summary="Detail mutasi aset", security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/MutasiSingleResponse")),
     *     @OA\Response(response=404, description="Tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $mutasi = Mutasi::with(['aset.masterBarang', 'ruangAsal.lokasi', 'ruangTujuan.lokasi', 'penanggungJawab'])->find($id);
        if (!$mutasi) { return response()->json(['status' => false, 'message' => 'Mutasi tidak ditemukan.'], 404); }
        return response()->json(['status' => true, 'message' => 'Detail mutasi berhasil diambil.', 'data' => new MutasiResource($mutasi)]);
    }

    /**
     * @OA\Delete(path="/mutasi/{id}", operationId="destroyMutasi", tags={"Mutasi"}, summary="Hapus data mutasi", security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/MutasiDeleteResponse")),
     *     @OA\Response(response=404, description="Tidak ditemukan"),
     *     @OA\Response(response=500, description="Gagal menghapus")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $mutasi = Mutasi::find($id);
        if (!$mutasi) { return response()->json(['status' => false, 'message' => 'Mutasi tidak ditemukan.'], 404); }
        try {
            $mutasi->delete();
            return response()->json(['status' => true, 'message' => 'Data mutasi berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus data mutasi.', 'error' => $e->getMessage()], 500);
        }
    }
}
