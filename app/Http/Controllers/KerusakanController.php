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
     * 2. Update kondisi aset menjadi "Rusak" dan status menjadi "Rusak".
     *
     * Validasi dilakukan di StoreKerusakanRequest::withValidator().
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
                    'kode_barang'           => $validated['kode_barang'],
                    'tanggal_kerusakan' => $validated['tanggal_kerusakan'],
                    'jenis_kerusakan'   => $validated['jenis_kerusakan'],
                    'deskripsi'         => $validated['deskripsi'],
                    'id_pelapor'        => $request->user()->id_pengguna,
                    'status_kerusakan'  => 'Dilaporkan',
                    'keterangan'        => $validated['keterangan'] ?? null,
                ]);

                // ──────────────────────────────────────────────────────
                // 2. Update kondisi & status aset menjadi "Rusak"
                // ──────────────────────────────────────────────────────
                Aset::where('kode_barang', $validated['kode_barang'])->update([
                    'kondisi' => 'Rusak',
                    'status'  => 'Rusak',
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
