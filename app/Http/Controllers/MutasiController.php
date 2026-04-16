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
     * Eager load: aset → masterBarang, ruangAsal → lokasi, ruangTujuan → lokasi, petugas
     */
    public function index(): JsonResponse
    {
        $data = Mutasi::with([
            'aset.masterBarang',
            'ruangAsal.lokasi',
            'ruangTujuan.lokasi',
            'petugas',
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
     */
    public function store(StoreMutasiRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $mutasi = DB::transaction(function () use ($validated, $request) {

                // ──────────────────────────────────────────────────────
                // 1. Ambil data aset untuk mendapatkan ruang asal
                // ──────────────────────────────────────────────────────
                $aset = Aset::findOrFail($validated['id_aset']);
                $idRuangAsal = $aset->id_ruang;

                // ──────────────────────────────────────────────────────
                // 2. Insert log mutasi
                // ──────────────────────────────────────────────────────
                $mutasi = Mutasi::create([
                    'id_aset'         => $validated['id_aset'],
                    'id_ruang_asal'   => $idRuangAsal,
                    'id_ruang_tujuan' => $validated['id_ruang_tujuan'],
                    'tanggal_mutasi'  => $validated['tanggal_mutasi'],
                    'id_petugas'      => $request->user()->id_pengguna,
                    'keterangan'      => $validated['keterangan'] ?? null,
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
                'petugas',
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
     */
    public function show(string $id): JsonResponse
    {
        $mutasi = Mutasi::with([
            'aset.masterBarang',
            'ruangAsal.lokasi',
            'ruangTujuan.lokasi',
            'petugas',
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
