<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAsetRequest;
use App\Http\Requests\UpdateAsetRequest;
use App\Http\Resources\AsetResource;
use App\Models\Aset;
use Illuminate\Http\JsonResponse;

/**
 * ============================================================
 *  SCHEMA DEFINITIONS – Aset Module
 * ============================================================
 *
 * @OA\Schema(
 *     schema="AsetResource",
 *     type="object",
 *     description="Representasi data aset inventaris",
 *     @OA\Property(property="kode_barang", type="string", example="BRG-001"),
 *     @OA\Property(property="id_master_barang", type="integer", example=1),
 *     @OA\Property(property="master_barang", ref="#/components/schemas/MasterBarangResource", nullable=true),
 *     @OA\Property(property="id_ruang", type="integer", nullable=true, example=1),
 *     @OA\Property(property="ruang", ref="#/components/schemas/RuangResource", nullable=true),
 *     @OA\Property(property="tanggal_registrasi", type="string", format="date", nullable=true, example="2026-01-15"),
 *     @OA\Property(property="id_kondisi", type="integer", nullable=true, example=1),
 *     @OA\Property(property="kondisi", ref="#/components/schemas/KondisiResource", nullable=true),
 *     @OA\Property(property="nilai_residu", type="number", nullable=true, example=500000),
 *     @OA\Property(property="id_status", type="integer", nullable=true, example=1),
 *     @OA\Property(property="status_barang", ref="#/components/schemas/StatusBarangResource", nullable=true),
 *     @OA\Property(property="gambar", type="string", nullable=true, example="https://example.com/image.jpg"),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Barang dalam kondisi terawat")
 * )
 *
 * @OA\Schema(
 *     schema="StoreAsetRequest",
 *     type="object",
 *     required={"kode_barang","id_master_barang","tanggal_registrasi","kondisi_barang","status_ketersediaan"},
 *     description="Payload untuk menambah aset baru",
 *     @OA\Property(property="kode_barang", type="string", maxLength=50, example="BRG-001"),
 *     @OA\Property(property="id_master_barang", type="integer", example=1),
 *     @OA\Property(property="id_ruang", type="integer", nullable=true, example=1),
 *     @OA\Property(property="tanggal_registrasi", type="string", format="date", example="2026-01-15"),
 *     @OA\Property(property="kondisi_barang", type="string", enum={"Baik","Rusak Ringan","Rusak Berat"}, example="Baik"),
 *     @OA\Property(property="nilai_residu", type="number", nullable=true, example=500000),
 *     @OA\Property(property="status_ketersediaan", type="string", enum={"Tersedia","Dipinjam","Non-Aktif","Dihapus"}, example="Tersedia"),
 *     @OA\Property(property="gambar", type="string", nullable=true, example="https://example.com/image.jpg"),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Barang dalam kondisi terawat")
 * )
 *
 * @OA\Schema(
 *     schema="UpdateAsetRequest",
 *     type="object",
 *     description="Payload untuk memperbarui aset",
 *     @OA\Property(property="kode_barang", type="string", maxLength=50, example="BRG-001"),
 *     @OA\Property(property="id_master_barang", type="integer", example=1),
 *     @OA\Property(property="id_ruang", type="integer", nullable=true, example=2),
 *     @OA\Property(property="tanggal_registrasi", type="string", format="date", example="2026-01-15"),
 *     @OA\Property(property="kondisi_barang", type="string", enum={"Baik","Rusak Ringan","Rusak Berat"}, example="Baik"),
 *     @OA\Property(property="nilai_residu", type="number", nullable=true, example=400000),
 *     @OA\Property(property="status_ketersediaan", type="string", enum={"Tersedia","Dipinjam","Non-Aktif","Dihapus"}, example="Tersedia"),
 *     @OA\Property(property="gambar", type="string", nullable=true, example="https://example.com/image.jpg"),
 *     @OA\Property(property="keterangan", type="string", nullable=true, example="Barang dalam kondisi terawat")
 * )
 *
 * @OA\Schema(
 *     schema="AsetListResponse",
 *     type="object",
 *     description="Response wrapper untuk daftar aset",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Daftar aset berhasil diambil."),
 *     @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AsetResource"))
 * )
 *
 * @OA\Schema(
 *     schema="AsetSingleResponse",
 *     type="object",
 *     description="Response wrapper untuk satu aset",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Detail aset berhasil diambil."),
 *     @OA\Property(property="data", ref="#/components/schemas/AsetResource")
 * )
 *
 * @OA\Schema(
 *     schema="AsetDeleteResponse",
 *     type="object",
 *     description="Response wrapper untuk penghapusan aset",
 *     @OA\Property(property="status", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Aset berhasil dihapus.")
 * )
 */
class AsetController extends Controller
{
    /**
     * Tampilkan daftar semua aset beserta relasi.
     *
     * @OA\Get(
     *     path="/aset",
     *     operationId="indexAset",
     *     tags={"Aset"},
     *     summary="Daftar semua aset",
     *     description="Mengambil daftar semua aset inventaris beserta relasi master barang, kategori, merek, satuan, ruang, dan lokasi.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar aset berhasil diambil",
     *         @OA\JsonContent(ref="#/components/schemas/AsetListResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(): JsonResponse
    {
        $aset = Aset::with([
            'masterBarang.kategori',
            'masterBarang.merek',
            'masterBarang.satuan',
            'ruang.lokasi',
            'kondisi',
            'statusBarang',
        ])->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar aset berhasil diambil.',
            'data'    => AsetResource::collection($aset),
        ]);
    }

    /**
     * Simpan aset baru.
     *
     * @OA\Post(
     *     path="/aset",
     *     operationId="storeAset",
     *     tags={"Aset"},
     *     summary="Tambah aset baru",
     *     description="Menyimpan data aset inventaris baru.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoreAsetRequest")
     *     ),
     *     @OA\Response(response=201, description="Aset berhasil ditambahkan",
     *         @OA\JsonContent(ref="#/components/schemas/AsetSingleResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(StoreAsetRequest $request): JsonResponse
    {
        $aset = Aset::create($request->validated());
        $aset->load([
            'masterBarang.kategori',
            'masterBarang.merek',
            'masterBarang.satuan',
            'ruang.lokasi',
            'kondisi',
            'statusBarang',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Aset berhasil ditambahkan.',
            'data'    => new AsetResource($aset),
        ], 201);
    }

    /**
     * Tampilkan detail satu aset termasuk data bangunan/tanah jika ada.
     *
     * @OA\Get(
     *     path="/aset/{id}",
     *     operationId="showAset",
     *     tags={"Aset"},
     *     summary="Detail aset",
     *     description="Mengambil detail satu aset termasuk master barang, ruang, lokasi, dan data bangunan/tanah jika ada.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="Kode barang (ID aset)", @OA\Schema(type="string", example="BRG-001")),
     *     @OA\Response(response=200, description="Detail aset berhasil diambil",
     *         @OA\JsonContent(ref="#/components/schemas/AsetSingleResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Aset tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $aset = Aset::with([
            'masterBarang.kategori',
            'masterBarang.merek',
            'masterBarang.satuan',
            'ruang.lokasi',
            'kondisi',
            'statusBarang',
            'asetBangunan',
            'asetTanah',
        ])->find($id);

        if (!$aset) {
            return response()->json([
                'status'  => false,
                'message' => 'Aset tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail aset berhasil diambil.',
            'data'    => new AsetResource($aset),
        ]);
    }

    /**
     * Update data aset.
     *
     * @OA\Put(
     *     path="/aset/{id}",
     *     operationId="updateAset",
     *     tags={"Aset"},
     *     summary="Update aset",
     *     description="Memperbarui data aset berdasarkan kode barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="Kode barang (ID aset)", @OA\Schema(type="string", example="BRG-001")),
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateAsetRequest")
     *     ),
     *     @OA\Response(response=200, description="Aset berhasil diperbarui",
     *         @OA\JsonContent(ref="#/components/schemas/AsetSingleResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Aset tidak ditemukan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function update(UpdateAsetRequest $request, string $id): JsonResponse
    {
        $aset = Aset::find($id);

        if (!$aset) {
            return response()->json([
                'status'  => false,
                'message' => 'Aset tidak ditemukan.',
            ], 404);
        }

        $aset->update($request->validated());
        $aset->load([
            'masterBarang.kategori',
            'masterBarang.merek',
            'masterBarang.satuan',
            'ruang.lokasi',
            'kondisi',
            'statusBarang',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Aset berhasil diperbarui.',
            'data'    => new AsetResource($aset),
        ]);
    }

    /**
     * Hapus aset.
     *
     * @OA\Delete(
     *     path="/aset/{id}",
     *     operationId="destroyAset",
     *     tags={"Aset"},
     *     summary="Hapus aset",
     *     description="Menghapus data aset berdasarkan kode barang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="Kode barang (ID aset)", @OA\Schema(type="string", example="BRG-001")),
     *     @OA\Response(response=200, description="Aset berhasil dihapus",
     *         @OA\JsonContent(ref="#/components/schemas/AsetDeleteResponse")
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Aset tidak ditemukan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $aset = Aset::find($id);

        if (!$aset) {
            return response()->json([
                'status'  => false,
                'message' => 'Aset tidak ditemukan.',
            ], 404);
        }

        $aset->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Aset berhasil dihapus.',
        ]);
    }
}
