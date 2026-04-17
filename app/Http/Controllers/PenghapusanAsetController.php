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
