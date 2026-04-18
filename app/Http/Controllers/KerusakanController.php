<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKerusakanRequest;
use App\Http\Resources\KerusakanResource;
use App\Models\Aset;
use App\Models\Kerusakan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class KerusakanController extends Controller
{
    /**
     * Tampilkan daftar semua laporan kerusakan.
     * Eager load: aset → masterBarang, pelapor, perbaikan
     *
     * @OA\Get(
     *     path="/kerusakan",
     *     operationId="indexKerusakan",
     *     tags={"Kerusakan"},
     *     summary="Daftar semua laporan kerusakan",
     *     description="Mengambil daftar semua laporan kerusakan aset beserta relasi aset, pelapor, dan perbaikan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Daftar kerusakan berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Daftar kerusakan berhasil diambil."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden")
     * )
     */
    public function index(): JsonResponse
    {
        $data = Kerusakan::with([
            'aset.masterBarang',
            'pelapor',
            'perbaikan',
        ])->orderByDesc('id_kerusakan')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar kerusakan berhasil diambil.',
            'data'    => KerusakanResource::collection($data),
        ]);
    }

    /**
     * Simpan laporan kerusakan baru menggunakan DB::transaction().
     *
     * Proses:
     * 1. Insert data ke tabel kerusakan.
     * 2. Update kondisi aset menjadi "Rusak Berat" dan status menjadi "Non-Aktif".
     *
     * Validasi dilakukan di StoreKerusakanRequest::withValidator().
     *
     * @OA\Post(
     *     path="/kerusakan",
     *     operationId="storeKerusakan",
     *     tags={"Kerusakan"},
     *     summary="Buat laporan kerusakan baru",
     *     description="Menyimpan laporan kerusakan aset. Kondisi aset otomatis diubah menjadi 'Rusak Berat' dan status 'Non-Aktif'. ID pelapor diambil dari user yang login.",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true,
     *         @OA\JsonContent(
     *             required={"kode_barang","tanggal_lapor","deskripsi_kerusakan","tingkat_kerusakan"},
     *             @OA\Property(property="kode_barang", type="string", example="BRG-001"),
     *             @OA\Property(property="tanggal_lapor", type="string", format="date", example="2026-04-18"),
     *             @OA\Property(property="deskripsi_kerusakan", type="string", example="Layar monitor berkedip-kedip"),
     *             @OA\Property(property="tingkat_kerusakan", type="string", enum={"Ringan","Sedang","Berat"}, example="Ringan")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Laporan kerusakan berhasil disimpan",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Laporan kerusakan berhasil disimpan."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Gagal menyimpan laporan kerusakan")
     * )
     */
    public function store(StoreKerusakanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $kerusakan = DB::transaction(function () use ($validated, $request) {

                // ──────────────────────────────────────────────────────
                // 1. Insert data kerusakan
                // ──────────────────────────────────────────────────────
                $kerusakan = Kerusakan::create([
                    'kode_barang'         => $validated['kode_barang'],
                    'tanggal_lapor'       => $validated['tanggal_lapor'],
                    'deskripsi_kerusakan' => $validated['deskripsi_kerusakan'],
                    'tingkat_kerusakan'   => $validated['tingkat_kerusakan'],
                    'id_pelapor'          => $request->user()->id_pengguna,
                    'status_kerusakan'    => 'Menunggu Pemeriksaan',
                ]);

                // ──────────────────────────────────────────────────────
                // 2. Update kondisi & status aset
                // ──────────────────────────────────────────────────────
                Aset::where('kode_barang', $validated['kode_barang'])->update([
                    'kondisi_barang'      => 'Rusak Berat',
                    'status_ketersediaan' => 'Non-Aktif',
                ]);

                return $kerusakan;
            });

            // Eager load untuk response
            $kerusakan->load([
                'aset.masterBarang',
                'pelapor',
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Laporan kerusakan berhasil disimpan.',
                'data'    => new KerusakanResource($kerusakan),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menyimpan laporan kerusakan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan detail satu laporan kerusakan.
     *
     * @OA\Get(
     *     path="/kerusakan/{id}",
     *     operationId="showKerusakan",
     *     tags={"Kerusakan"},
     *     summary="Detail laporan kerusakan",
     *     description="Mengambil detail satu laporan kerusakan beserta aset, pelapor, dan data perbaikan.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID kerusakan", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Detail kerusakan berhasil diambil",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Detail kerusakan berhasil diambil."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Kerusakan tidak ditemukan")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $kerusakan = Kerusakan::with([
            'aset.masterBarang',
            'pelapor',
            'perbaikan',
        ])->find($id);

        if (!$kerusakan) {
            return response()->json([
                'status'  => false,
                'message' => 'Kerusakan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail kerusakan berhasil diambil.',
            'data'    => new KerusakanResource($kerusakan),
        ]);
    }

    /**
     * Hapus data kerusakan.
     *
     * @OA\Delete(
     *     path="/kerusakan/{id}",
     *     operationId="destroyKerusakan",
     *     tags={"Kerusakan"},
     *     summary="Hapus laporan kerusakan",
     *     description="Menghapus data laporan kerusakan berdasarkan ID.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, description="ID kerusakan", @OA\Schema(type="string", example="1")),
     *     @OA\Response(response=200, description="Data kerusakan berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Data kerusakan berhasil dihapus.")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden"),
     *     @OA\Response(response=404, description="Kerusakan tidak ditemukan"),
     *     @OA\Response(response=500, description="Gagal menghapus data kerusakan")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        $kerusakan = Kerusakan::find($id);

        if (!$kerusakan) {
            return response()->json([
                'status'  => false,
                'message' => 'Kerusakan tidak ditemukan.',
            ], 404);
        }

        try {
            $kerusakan->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data kerusakan berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus data kerusakan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
