<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermintaanRequest;
use App\Http\Resources\PermintaanResource;
use App\Models\DetailPermintaan;
use App\Models\Permintaan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermintaanController extends Controller
{
    /**
     * Tampilkan daftar semua permintaan.
     */
    public function index(): JsonResponse
    {
        $data = Permintaan::with([
            'pemohon',
            'penyetuju',
            'detailPermintaan.masterBarang',
        ])->orderByDesc('id_permintaan')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar permintaan berhasil diambil.',
            'data'    => PermintaanResource::collection($data),
        ]);
    }

    /**
     * Simpan permintaan baru menggunakan DB::transaction().
     */
    public function store(StorePermintaanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $permintaan = DB::transaction(function () use ($validated) {
                // Insert header permintaan
                $permintaan = Permintaan::create([
                    'tanggal_permintaan' => $validated['tanggal_permintaan'],
                    'id_pemohon'         => $validated['id_pemohon'],
                    'status_permintaan'  => 'Menunggu',
                    'keterangan'         => $validated['keterangan'] ?? null,
                ]);

                // Insert detail permintaan (multi-item)
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

            $permintaan->load([
                'pemohon',
                'detailPermintaan.masterBarang',
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Permintaan berhasil disimpan.',
                'data'    => new PermintaanResource($permintaan),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menyimpan permintaan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan detail satu permintaan.
     */
    public function show(string $id): JsonResponse
    {
        $permintaan = Permintaan::with([
            'pemohon',
            'penyetuju',
            'detailPermintaan.masterBarang',
        ])->find($id);

        if (!$permintaan) {
            return response()->json([
                'status'  => false,
                'message' => 'Permintaan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail permintaan berhasil diambil.',
            'data'    => new PermintaanResource($permintaan),
        ]);
    }

    /**
     * Setujui atau tolak permintaan.
     *
     * PUT /api/permintaan/{id}/keputusan
     * Body: { "status_permintaan": "Disetujui" | "Ditolak", "id_penyetuju": int }
     */
    public function keputusan(Request $request, string $id): JsonResponse
    {
        $permintaan = Permintaan::find($id);

        if (!$permintaan) {
            return response()->json([
                'status'  => false,
                'message' => 'Permintaan tidak ditemukan.',
            ], 404);
        }

        if ($permintaan->status_permintaan !== 'Menunggu') {
            return response()->json([
                'status'  => false,
                'message' => 'Permintaan ini sudah diproses sebelumnya.',
            ], 422);
        }

        $request->validate([
            'status_permintaan' => ['required', 'string', 'in:Disetujui,Ditolak'],
            'id_penyetuju'      => ['required', 'integer', 'exists:pengguna,id_pengguna'],
        ]);

        $permintaan->update([
            'status_permintaan' => $request->status_permintaan,
            'id_penyetuju'      => $request->id_penyetuju,
        ]);

        $permintaan->load([
            'pemohon',
            'penyetuju',
            'detailPermintaan.masterBarang',
        ]);

        return response()->json([
            'status'  => true,
            'message' => "Permintaan berhasil {$request->status_permintaan}.",
            'data'    => new PermintaanResource($permintaan),
        ]);
    }

    /**
     * Hapus permintaan (hanya jika masih Menunggu).
     */
    public function destroy(string $id): JsonResponse
    {
        $permintaan = Permintaan::find($id);

        if (!$permintaan) {
            return response()->json([
                'status'  => false,
                'message' => 'Permintaan tidak ditemukan.',
            ], 404);
        }

        if ($permintaan->status_permintaan !== 'Menunggu') {
            return response()->json([
                'status'  => false,
                'message' => 'Hanya permintaan berstatus "Menunggu" yang dapat dihapus.',
            ], 422);
        }

        DB::transaction(function () use ($permintaan) {
            $permintaan->detailPermintaan()->delete();
            $permintaan->delete();
        });

        return response()->json([
            'status'  => true,
            'message' => 'Permintaan berhasil dihapus.',
        ]);
    }
}
