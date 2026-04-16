<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePeminjamanRequest;
use App\Http\Resources\PeminjamanResource;
use App\Models\Aset;
use App\Models\DetailPeminjaman;
use App\Models\Peminjaman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    /**
     * Tampilkan daftar semua peminjaman.
     * Eager load: peminjam, detailPeminjaman → aset → masterBarang
     */
    public function index(): JsonResponse
    {
        $data = Peminjaman::with([
            'peminjam',
            'detailPeminjaman.aset.masterBarang',
        ])->orderByDesc('id_peminjaman')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar peminjaman berhasil diambil.',
            'data'    => PeminjamanResource::collection($data),
        ]);
    }

    /**
     * Simpan peminjaman baru menggunakan DB::transaction().
     *
     * Proses:
     * 1. Insert ke tabel peminjaman.
     * 2. Insert ke tabel detail_peminjaman (multi-item dari array input).
     * 3. Update status setiap aset menjadi "Dipinjam".
     *
     * Validasi ketersediaan aset dilakukan di StorePeminjamanRequest::withValidator().
     */
    public function store(StorePeminjamanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $peminjaman = DB::transaction(function () use ($validated) {

                // ──────────────────────────────────────────────────────
                // 1. Insert data peminjaman (header)
                // ──────────────────────────────────────────────────────
                $peminjaman = Peminjaman::create([
                    'kode_peminjaman'   => $validated['kode_peminjaman'],
                    'tanggal_pinjam'    => $validated['tanggal_pinjam'],
                    'tanggal_kembali'   => $validated['tanggal_kembali'] ?? null,
                    'id_peminjam'       => $validated['id_peminjam'],
                    'status_peminjaman' => 'Dipinjam',
                    'keterangan'        => $validated['keterangan'] ?? null,
                ]);

                // ──────────────────────────────────────────────────────
                // 2. Insert detail peminjaman (multi-item)
                // ──────────────────────────────────────────────────────
                $idAsets = [];

                foreach ($validated['detail'] as $item) {
                    DetailPeminjaman::create([
                        'id_peminjaman' => $peminjaman->id_peminjaman,
                        'id_aset'       => $item['id_aset'],
                        'jumlah'        => $item['jumlah'],
                        'keterangan'    => $item['keterangan'] ?? null,
                    ]);

                    $idAsets[] = $item['id_aset'];
                }

                // ──────────────────────────────────────────────────────
                // 3. Update status aset menjadi "Dipinjam"
                // ──────────────────────────────────────────────────────
                Aset::whereIn('id_aset', $idAsets)->update([
                    'status' => 'Dipinjam',
                ]);

                return $peminjaman;
            });

            // Eager load untuk response
            $peminjaman->load([
                'peminjam',
                'detailPeminjaman.aset.masterBarang',
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Peminjaman berhasil disimpan.',
                'data'    => new PeminjamanResource($peminjaman),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menyimpan peminjaman.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Tampilkan detail satu peminjaman.
     */
    public function show(string $id): JsonResponse
    {
        $peminjaman = Peminjaman::with([
            'peminjam',
            'detailPeminjaman.aset.masterBarang',
        ])->find($id);

        if (!$peminjaman) {
            return response()->json([
                'status'  => false,
                'message' => 'Peminjaman tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail peminjaman berhasil diambil.',
            'data'    => new PeminjamanResource($peminjaman),
        ]);
    }

    /**
     * Proses pengembalian peminjaman.
     * Update status peminjaman → "Dikembalikan" dan status setiap aset → "Tersedia".
     *
     * PUT /api/peminjaman/{id}/kembalikan
     */
    public function kembalikan(string $id): JsonResponse
    {
        $peminjaman = Peminjaman::with('detailPeminjaman')->find($id);

        if (!$peminjaman) {
            return response()->json([
                'status'  => false,
                'message' => 'Peminjaman tidak ditemukan.',
            ], 404);
        }

        if ($peminjaman->status_peminjaman === 'Dikembalikan') {
            return response()->json([
                'status'  => false,
                'message' => 'Peminjaman ini sudah dikembalikan sebelumnya.',
            ], 422);
        }

        try {
            DB::transaction(function () use ($peminjaman) {
                // Update status peminjaman
                $peminjaman->update([
                    'status_peminjaman' => 'Dikembalikan',
                    'tanggal_kembali'   => now()->toDateString(),
                ]);

                // Update status semua aset yang dipinjam → Tersedia
                $idAsets = $peminjaman->detailPeminjaman->pluck('id_aset')->toArray();

                Aset::whereIn('id_aset', $idAsets)->update([
                    'status' => 'Tersedia',
                ]);
            });

            $peminjaman->load([
                'peminjam',
                'detailPeminjaman.aset.masterBarang',
            ]);

            return response()->json([
                'status'  => true,
                'message' => 'Peminjaman berhasil dikembalikan.',
                'data'    => new PeminjamanResource($peminjaman),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal memproses pengembalian.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Hapus peminjaman (hanya jika masih berstatus Dipinjam, akan mengembalikan status aset).
     */
    public function destroy(string $id): JsonResponse
    {
        $peminjaman = Peminjaman::with('detailPeminjaman')->find($id);

        if (!$peminjaman) {
            return response()->json([
                'status'  => false,
                'message' => 'Peminjaman tidak ditemukan.',
            ], 404);
        }

        try {
            DB::transaction(function () use ($peminjaman) {
                // Jika masih Dipinjam, kembalikan status aset
                if ($peminjaman->status_peminjaman === 'Dipinjam') {
                    $idAsets = $peminjaman->detailPeminjaman->pluck('id_aset')->toArray();

                    Aset::whereIn('id_aset', $idAsets)->update([
                        'status' => 'Tersedia',
                    ]);
                }

                // Hapus detail dan header
                $peminjaman->detailPeminjaman()->delete();
                $peminjaman->delete();
            });

            return response()->json([
                'status'  => true,
                'message' => 'Peminjaman berhasil dihapus.',
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Gagal menghapus peminjaman.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
