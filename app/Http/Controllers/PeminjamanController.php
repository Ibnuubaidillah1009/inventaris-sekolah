<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeminjamanRequest;
use App\Http\Resources\PeminjamanResource;
use App\Models\Aset;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(schema="DetailPeminjamanResource", type="object",
 *     @OA\Property(property="id_detail_pinjam", type="integer", example=1),
 *     @OA\Property(property="nomor_peminjaman", type="string", example="PJM-2026-001"),
 *     @OA\Property(property="kode_barang", type="string", example="BRG-001"),
 *     @OA\Property(property="aset", type="object", nullable=true)
 * )
 * @OA\Schema(schema="PeminjamanResource", type="object",
 *     @OA\Property(property="nomor_peminjaman", type="string", example="PJM-2026-001"),
 *     @OA\Property(property="tanggal_pinjam", type="string", format="date", example="2026-04-18"),
 *     @OA\Property(property="id_peminjam", type="integer", example=5),
 *     @OA\Property(property="nomor_telepon", type="string", nullable=true, example="081234567890"),
 *     @OA\Property(property="lama_pinjam_hari", type="integer", example=7),
 *     @OA\Property(property="status_peminjaman", type="string", example="Sedang Dipinjam"),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Untuk kegiatan praktikum"),
 *     @OA\Property(property="peminjam", type="object", nullable=true),
 *     @OA\Property(property="detail_peminjaman", type="array", @OA\Items(ref="#/components/schemas/DetailPeminjamanResource"))
 * )
 * @OA\Schema(schema="StorePeminjamanRequest", type="object", required={"nomor_peminjaman","tanggal_pinjam","id_peminjam","lama_pinjam_hari","detail"},
 *     @OA\Property(property="nomor_peminjaman", type="string", example="PJM-2026-001"),
 *     @OA\Property(property="tanggal_pinjam", type="string", format="date", example="2026-04-18"),
 *     @OA\Property(property="id_peminjam", type="integer", example=5),
 *     @OA\Property(property="nomor_telepon", type="string", nullable=true, example="081234567890"),
 *     @OA\Property(property="lama_pinjam_hari", type="integer", example=7),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Untuk kegiatan praktikum"),
 *     @OA\Property(property="detail", type="array", @OA\Items(type="object", required={"kode_barang"}, @OA\Property(property="kode_barang", type="string", example="BRG-001")))
 * )
 * @OA\Schema(schema="PeminjamanListResponse", type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Daftar peminjaman berhasil diambil."),
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/PeminjamanResource"))
 * )
 * @OA\Schema(schema="PeminjamanSingleResponse", type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Detail peminjaman berhasil diambil."),
 *     @OA\Property(property="data", ref="#/components/schemas/PeminjamanResource")
 * )
 * @OA\Schema(schema="PeminjamanDeleteResponse", type="object",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Peminjaman berhasil dihapus.")
 * )
 */
class PeminjamanController extends Controller
{
    /**
     * @OA\Get(path="/peminjaman", operationId="indexPeminjaman", tags={"Peminjaman"}, summary="Daftar semua peminjaman", security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/PeminjamanListResponse")),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */
    public function index(): JsonResponse
    {
        $data = Peminjaman::with(['peminjam', 'detailPeminjaman.aset.masterBarang'])->orderByDesc('nomor_peminjaman')->get();
        return response()->json(['status' => true, 'message' => 'Daftar peminjaman berhasil diambil.', 'data' => PeminjamanResource::collection($data)]);
    }

    /**
     * @OA\Post(path="/peminjaman", operationId="storePeminjaman", tags={"Peminjaman"}, summary="Buat peminjaman baru", security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(ref="#/components/schemas/StorePeminjamanRequest")),
     *     @OA\Response(response=201, description="Created", @OA\JsonContent(ref="#/components/schemas/PeminjamanSingleResponse")),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Gagal menyimpan")
     * )
     */
    public function store(StorePeminjamanRequest $request): JsonResponse
    {
        $validated = $request->validated();
        try {
            $peminjaman = DB::transaction(function () use ($validated) {
                $peminjaman = Peminjaman::create([
                    'nomor_peminjaman'  => $validated['nomor_peminjaman'],
                    'tanggal_pinjam'    => $validated['tanggal_pinjam'],
                    'id_peminjam'       => $validated['id_peminjam'],
                    'nomor_telepon'     => $validated['nomor_telepon'] ?? null,
                    'lama_pinjam_hari'  => $validated['lama_pinjam_hari'],
                    'status_peminjaman' => 'Sedang Dipinjam',
                    'keterangan'        => $validated['keterangan'] ?? null,
                ]);
                $kodeBarangs = [];
                foreach ($validated['detail'] as $item) {
                    DetailPeminjaman::create(['nomor_peminjaman' => $peminjaman->nomor_peminjaman, 'kode_barang' => $item['kode_barang']]);
                    $kodeBarangs[] = $item['kode_barang'];
                }
                Aset::whereIn('kode_barang', $kodeBarangs)->update(['status_ketersediaan' => 'Dipinjam']);
                return $peminjaman;
            });
            $peminjaman->load(['peminjam', 'detailPeminjaman.aset.masterBarang']);
            return response()->json(['status' => true, 'message' => 'Peminjaman berhasil disimpan.', 'data' => new PeminjamanResource($peminjaman)], 201);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menyimpan peminjaman.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Get(path="/peminjaman/{id}", operationId="showPeminjaman", tags={"Peminjaman"}, summary="Detail peminjaman", security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", example="PJM-2026-001")),
     *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/PeminjamanSingleResponse")),
     *     @OA\Response(response=404, description="Tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $peminjaman = Peminjaman::with(['peminjam', 'detailPeminjaman.aset.masterBarang'])->find($id);
        if (!$peminjaman) { return response()->json(['status' => false, 'message' => 'Peminjaman tidak ditemukan.'], 404); }
        return response()->json(['status' => true, 'message' => 'Detail peminjaman berhasil diambil.', 'data' => new PeminjamanResource($peminjaman)]);
    }

    /**
     * @OA\Put(path="/peminjaman/{id}/kembalikan", operationId="kembalikanPeminjaman", tags={"Peminjaman"}, summary="Kembalikan peminjaman", security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", example="PJM-2026-001")),
     *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/PeminjamanSingleResponse")),
     *     @OA\Response(response=404, description="Tidak ditemukan"),
     *     @OA\Response(response=422, description="Sudah dikembalikan"),
     *     @OA\Response(response=500, description="Gagal proses")
     * )
     */
    public function kembalikan(string $id): JsonResponse
    {
        $peminjaman = Peminjaman::with('detailPeminjaman')->find($id);
        if (!$peminjaman) { return response()->json(['status' => false, 'message' => 'Peminjaman tidak ditemukan.'], 404); }
        if ($peminjaman->status_peminjaman === 'Dikembalikan') { return response()->json(['status' => false, 'message' => 'Peminjaman ini sudah dikembalikan sebelumnya.'], 422); }
        try {
            DB::transaction(function () use ($peminjaman) {
                $peminjaman->update(['status_peminjaman' => 'Dikembalikan']);
                \DB::table('pengembalian')->insert(['nomor_peminjaman' => $peminjaman->nomor_peminjaman, 'tanggal_kembali' => now()->toDateString()]);
                $kodeBarangs = $peminjaman->detailPeminjaman->pluck('kode_barang')->toArray();
                Aset::whereIn('kode_barang', $kodeBarangs)->update(['status_ketersediaan' => 'Tersedia']);
            });
            $peminjaman->load(['peminjam', 'detailPeminjaman.aset.masterBarang']);
            return response()->json(['status' => true, 'message' => 'Peminjaman berhasil dikembalikan.', 'data' => new PeminjamanResource($peminjaman)]);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal memproses pengembalian.', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * @OA\Delete(path="/peminjaman/{id}", operationId="destroyPeminjaman", tags={"Peminjaman"}, summary="Hapus peminjaman", security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="string", example="PJM-2026-001")),
     *     @OA\Response(response=200, description="OK", @OA\JsonContent(ref="#/components/schemas/PeminjamanDeleteResponse")),
     *     @OA\Response(response=404, description="Tidak ditemukan"),
     *     @OA\Response(response=500, description="Gagal menghapus")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $peminjaman = Peminjaman::with('detailPeminjaman')->find($id);
        if (!$peminjaman) { return response()->json(['status' => false, 'message' => 'Peminjaman tidak ditemukan.'], 404); }
        try {
            DB::transaction(function () use ($peminjaman) {
                if ($peminjaman->status_peminjaman === 'Sedang Dipinjam') {
                    $kodeBarangs = $peminjaman->detailPeminjaman->pluck('kode_barang')->toArray();
                    Aset::whereIn('kode_barang', $kodeBarangs)->update(['status_ketersediaan' => 'Tersedia']);
                }
                $peminjaman->detailPeminjaman()->delete();
                $peminjaman->delete();
            });
            return response()->json(['status' => true, 'message' => 'Peminjaman berhasil dihapus.']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Gagal menghapus peminjaman.', 'error' => $e->getMessage()], 500);
        }
    }
}
