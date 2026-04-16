<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePerbaikanRequest;
use App\Http\Resources\PerbaikanResource;
use App\Models\Aset;
use App\Models\Kerusakan;
use App\Models\Perbaikan;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PerbaikanController extends Controller
{
    /**
     * Tampilkan daftar semua perbaikan.
     * Eager load: kerusakan → aset → masterBarang
     */
    public function index(): JsonResponse
    {
        $data = Perbaikan::with([
            'kerusakan.aset.masterBarang',
        ])->orderByDesc('id_perbaikan')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar perbaikan berhasil diambil.',
            'data'    => PerbaikanResource::collection($data),
        ]);
    }

    /**
     * Simpan data perbaikan baru menggunakan DB::transaction().
     *
     * Proses:
     * 1. Insert data ke tabel perbaikan.
     * 2. Jika status_perbaikan = "Selesai":
     *    a. Update status_kerusakan menjadi "Selesai Diperbaiki".
     *    b. Update kondisi aset kembali menjadi "Baik" dan status menjadi "Tersedia".
     *
     * Validasi dilakukan di StorePerbaikanRequest::withValidator().
     */
    public function store(StorePerbaikanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $perbaikan = DB::transaction(function () use ($validated) {

                // ──────────────────────────────────────────────────────
                // 1. Insert data perbaikan
                // ──────────────────────────────────────────────────────
                $perbaikan = Perbaikan::create([
                    'id_kerusakan'      => $validated['id_kerusakan'],
                    'tanggal_perbaikan' => $validated['tanggal_perbaikan'],
                    'tanggal_selesai'   => $validated['tanggal_selesai'] ?? null,
                    'pelaksana'         => $validated['pelaksana'],
                    'biaya'             => $validated['biaya'] ?? null,
                    'status_perbaikan'  => $validated['status_perbaikan'],
                    'keterangan'        => $validated['keterangan'] ?? null,
                ]);

                // ──────────────────────────────────────────────────────
                // 2. Jika perbaikan selesai → update kerusakan & aset
                // ──────────────────────────────────────────────────────
                if ($validated['status_perbaikan'] === 'Selesai') {
                    // Update status kerusakan
                    $kerusakan = Kerusakan::findOrFail($validated['id_kerusakan']);
                    $kerusakan->update([
                        'status_kerusakan' => 'Selesai Diperbaiki',
                    ]);

                    // Update kondisi & status aset kembali normal
                    Aset::where('id_aset', $kerusakan->id_aset)->update([
                        'kondisi' => 'Baik',
                        'status'  => 'Tersedia',
                    ]);
                }

                return $perbaikan;
            });

            // Eager load untuk response
            $perbaikan->load([
                'kerusakan.aset.masterBarang',
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Data perbaikan berhasil disimpan.',
                'data'    => new PerbaikanResource($perbaikan),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menyimpan data perbaikan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan detail satu perbaikan.
     */
    public function show(string $id): JsonResponse
    {
        $perbaikan = Perbaikan::with([
            'kerusakan.aset.masterBarang',
        ])->find($id);

        if (!$perbaikan) {
            return response()->json([
                'status'  => false,
                'message' => 'Perbaikan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail perbaikan berhasil diambil.',
            'data'    => new PerbaikanResource($perbaikan),
        ]);
    }

    /**
     * Hapus data perbaikan.
     */
    public function destroy(string $id): JsonResponse
    {
        $perbaikan = Perbaikan::find($id);

        if (!$perbaikan) {
            return response()->json([
                'status'  => false,
                'message' => 'Perbaikan tidak ditemukan.',
            ], 404);
        }

        try {
            $perbaikan->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Data perbaikan berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus data perbaikan.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
