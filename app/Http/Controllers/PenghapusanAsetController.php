<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePenghapusanAsetRequest;
use App\Http\Resources\PenghapusanAsetResource;
use App\Models\Aset;
use App\Models\PenghapusanAset;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PenghapusanAsetController extends Controller
{
    /**
     * Tampilkan daftar semua penghapusan aset.
     * Eager load: aset → masterBarang, penyetuju
     *
     * @OA\Get(
     *     path="/penghapusan-aset",
     *     operationId="indexPenghapusanAset",
     *     tags={"Penghapusan Aset"},
     *     summary="Daftar semua penghapusan aset",
     *     description="Mengambil daftar semua penghapusan aset beserta relasi aset dan penyetuju.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar penghapusan aset berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar penghapusan aset berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(): JsonResponse
    {
        $data = PenghapusanAset::with([
            'aset.masterBarang',
            'penyetuju',
        ])->orderByDesc('id_penghapusan')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar penghapusan aset berhasil diambil.',
            'data'    => PenghapusanAsetResource::collection($data),
        ]);
    }

    /**
     * Simpan data penghapusan aset menggunakan DB::transaction().
     *
     * Proses:
     * 1. Insert data ke tabel penghapusan_aset.
     * 2. Update status aset menjadi "Dihapus" sehingga aset tidak muncul
     *    di daftar aset aktif maupun pilihan peminjaman.
     *
     * Validasi dilakukan di StorePenghapusanAsetRequest::withValidator().
     *
     * @OA\Post(
     *     path="/penghapusan-aset",
     *     operationId="storePenghapusanAset",
     *     tags={"Penghapusan Aset"},
     *     summary="Simpan penghapusan aset baru",
     *     description="Menyimpan data penghapusan aset dari inventaris. Status aset otomatis diubah menjadi 'Dihapus'.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"kode_barang","tanggal_hapus","alasan_hapus","id_penyetuju"},
     *             @OA\Property(property="kode_barang", type="string", example="BRG-001"),
     *             @OA\Property(property="tanggal_hapus", type="string", format="date", example="2026-04-18"),
     *             @OA\Property(property="alasan_hapus", type="string", example="Sudah tidak layak pakai"),
     *             @OA\Property(property="id_penyetuju", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Penghapusan aset berhasil disimpan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Penghapusan aset berhasil disimpan."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Gagal menyimpan penghapusan aset")
     * )
     */
    public function store(StorePenghapusanAsetRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $penghapusan = DB::transaction(function () use ($validated) {

                // ──────────────────────────────────────────────────────
                // 1. Insert data penghapusan aset
                // ──────────────────────────────────────────────────────
                $penghapusan = PenghapusanAset::create([
                    'kode_barang'   => $validated['kode_barang'],
                    'tanggal_hapus' => $validated['tanggal_hapus'],
                    'alasan_hapus'  => $validated['alasan_hapus'],
                    'id_penyetuju'  => $validated['id_penyetuju'],
                ]);

                // ──────────────────────────────────────────────────────
                // 2. Update status aset menjadi "Dihapus"
                //    Aset dengan status ini tidak akan muncul di
                //    daftar aset aktif atau pilihan peminjaman.
                // ──────────────────────────────────────────────────────
                Aset::where('kode_barang', $validated['kode_barang'])->update([
                    'status_ketersediaan' => 'Dihapus',
                ]);

                return $penghapusan;
            });

            // Eager load untuk response
            $penghapusan->load([
                'aset.masterBarang',
                'penyetuju',
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Penghapusan aset berhasil disimpan.',
                'data'    => new PenghapusanAsetResource($penghapusan),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menyimpan penghapusan aset.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan detail satu penghapusan aset.
     *
     * @OA\Get(
     *     path="/penghapusan-aset/{id}",
     *     operationId="showPenghapusanAset",
     *     tags={"Penghapusan Aset"},
     *     summary="Detail penghapusan aset",
     *     description="Mengambil detail satu penghapusan aset beserta aset dan penyetuju.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID penghapusan", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Detail penghapusan aset berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail penghapusan aset berhasil diambil."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Data penghapusan aset tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $penghapusan = PenghapusanAset::with([
            'aset.masterBarang',
            'penyetuju',
        ])->find($id);

        if (!$penghapusan) {
            return response()->json([
                'status'  => false,
                'message' => 'Data penghapusan aset tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail penghapusan aset berhasil diambil.',
            'data'    => new PenghapusanAsetResource($penghapusan),
        ]);
    }

    /**
     * Hapus data penghapusan aset.
     *
     * @OA\Delete(
     *     path="/penghapusan-aset/{id}",
     *     operationId="destroyPenghapusanAset",
     *     tags={"Penghapusan Aset"},
     *     summary="Hapus data penghapusan aset",
     *     description="Menghapus record penghapusan aset berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID penghapusan", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Data penghapusan aset berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data penghapusan aset berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Data penghapusan aset tidak ditemukan"),
     *     @OA\Response(response=500, description="Gagal menghapus data penghapusan aset")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $penghapusan = PenghapusanAset::find($id);

        if (!$penghapusan) {
            return response()->json([
                'status'  => false,
                'message' => 'Data penghapusan aset tidak ditemukan.',
            ], 404);
        }

        try {
            $penghapusan->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data penghapusan aset berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus data penghapusan aset.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
