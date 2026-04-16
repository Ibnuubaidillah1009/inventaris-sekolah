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
                    'id_aset'              => $validated['id_aset'],
                    'tanggal_penghapusan'  => $validated['tanggal_penghapusan'],
                    'alasan'               => $validated['alasan'],
                    'metode_penghapusan'   => $validated['metode_penghapusan'],
                    'id_penyetuju'         => $validated['id_penyetuju'],
                    'dokumen_pendukung'    => $validated['dokumen_pendukung'] ?? null,
                    'keterangan'           => $validated['keterangan'] ?? null,
                ]);

                // ──────────────────────────────────────────────────────
                // 2. Update status aset menjadi "Dihapus"
                //    Aset dengan status ini tidak akan muncul di
                //    daftar aset aktif atau pilihan peminjaman.
                // ──────────────────────────────────────────────────────
                Aset::where('id_aset', $validated['id_aset'])->update([
                    'status' => 'Dihapus',
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
