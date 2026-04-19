<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMutasiRequest;
use App\Http\Resources\MutasiResource;
use App\Models\Aset;
use App\Models\Mutasi;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MutasiController extends Controller
{
    /**
     * Tampilkan daftar semua mutasi aset.
     * Eager load: aset → masterBarang, ruangAsal → lokasi, ruangTujuan → lokasi, penanggungJawab
     *
     * @OA\Get(
     *     path="/mutasi",
     *     operationId="indexMutasi",
     *     tags={"Mutasi"},
     *     summary="Daftar semua mutasi aset",
     *     description="Mengambil daftar semua mutasi perpindahan aset antar ruang.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar mutasi aset berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar mutasi aset berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(): JsonResponse
    {
        $data = Mutasi::with([
            'aset.masterBarang',
            'ruangAsal.lokasi',
            'ruangTujuan.lokasi',
            'penanggungJawab',
        ])->orderByDesc('id_mutasi')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar mutasi aset berhasil diambil.',
            'data'    => MutasiResource::collection($data),
        ]);
    }

    /**
     * Simpan mutasi aset baru menggunakan DB::transaction().
     *
     * Proses:
     * 1. Ambil data aset untuk mendapatkan id_ruang asal saat ini.
     * 2. Insert log ke tabel mutasi.
     * 3. Update kolom id_ruang pada tabel aset menjadi ruang tujuan baru.
     *
     * Validasi ketersediaan aset dilakukan di StoreMutasiRequest::withValidator().
     *
     * @OA\Post(
     *     path="/mutasi",
     *     operationId="storeMutasi",
     *     tags={"Mutasi"},
     *     summary="Buat mutasi aset baru",
     *     description="Memindahkan aset dari ruang asal ke ruang tujuan. Ruang asal diambil otomatis dari data aset saat ini.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"kode_barang","id_ruang_tujuan","tanggal_mutasi"},
     *             @OA\Property(property="kode_barang", type="string", example="BRG-001"),
     *             @OA\Property(property="id_ruang_tujuan", type="integer", example=3),
     *             @OA\Property(property="tanggal_mutasi", type="string", format="date", example="2026-04-18"),
     *             @OA\Property(property="alasan_mutasi", type="string", nullable=true, example="Pindah ke lab baru")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Mutasi aset berhasil disimpan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Mutasi aset berhasil disimpan."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Gagal menyimpan mutasi aset")
     * )
     */
    public function store(StoreMutasiRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $mutasi = DB::transaction(function () use ($validated, $request) {

                // ──────────────────────────────────────────────────────
                // 1. Ambil data aset untuk mendapatkan ruang asal
                // ──────────────────────────────────────────────────────
                $aset = Aset::findOrFail($validated['kode_barang']);
                $idRuangAsal = $aset->id_ruang;

                // ──────────────────────────────────────────────────────
                // 2. Insert log mutasi
                // ──────────────────────────────────────────────────────
                $mutasi = Mutasi::create([
                    'kode_barang'         => $validated['kode_barang'],
                    'id_ruang_asal'       => $idRuangAsal,
                    'id_ruang_tujuan'     => $validated['id_ruang_tujuan'],
                    'tanggal_mutasi'      => $validated['tanggal_mutasi'],
                    'id_penanggung_jawab' => $request->user()->id_pengguna,
                    'alasan_mutasi'       => $validated['alasan_mutasi'] ?? null,
                ]);

                // ──────────────────────────────────────────────────────
                // 3. Update id_ruang pada aset menjadi ruang tujuan
                // ──────────────────────────────────────────────────────
                $aset->update([
                    'id_ruang' => $validated['id_ruang_tujuan'],
                ]);

                return $mutasi;
            });

            // Eager load untuk response
            $mutasi->load([
                'aset.masterBarang',
                'ruangAsal.lokasi',
                'ruangTujuan.lokasi',
                'penanggungJawab',
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Mutasi aset berhasil disimpan.',
                'data'    => new MutasiResource($mutasi),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menyimpan mutasi aset.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan detail satu mutasi aset.
     *
     * @OA\Get(
     *     path="/mutasi/{id}",
     *     operationId="showMutasi",
     *     tags={"Mutasi"},
     *     summary="Detail mutasi aset",
     *     description="Mengambil detail satu mutasi aset beserta relasi aset, ruang asal, ruang tujuan, dan penanggung jawab.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID mutasi", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Detail mutasi berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail mutasi berhasil diambil."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Mutasi tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $mutasi = Mutasi::with([
            'aset.masterBarang',
            'ruangAsal.lokasi',
            'ruangTujuan.lokasi',
            'penanggungJawab',
        ])->find($id);

        if (!$mutasi) {
            return response()->json([
                'status'  => false,
                'message' => 'Mutasi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail mutasi berhasil diambil.',
            'data'    => new MutasiResource($mutasi),
        ]);
    }

    /**
     * Hapus data mutasi.
     * Catatan: Penghapusan log mutasi ini TIDAK mengembalikan posisi aset ke ruang asal.
     *
     * @OA\Delete(
     *     path="/mutasi/{id}",
     *     operationId="destroyMutasi",
     *     tags={"Mutasi"},
     *     summary="Hapus data mutasi",
     *     description="Menghapus log data mutasi. Perhatian: penghapusan ini TIDAK mengembalikan posisi aset ke ruang asal.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID mutasi", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Data mutasi berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data mutasi berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Mutasi tidak ditemukan"),
     *     @OA\Response(response=500, description="Gagal menghapus data mutasi")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $mutasi = Mutasi::find($id);

        if (!$mutasi) {
            return response()->json([
                'status'  => false,
                'message' => 'Mutasi tidak ditemukan.',
            ], 404);
        }

        try {
            $mutasi->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data mutasi berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus data mutasi.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
