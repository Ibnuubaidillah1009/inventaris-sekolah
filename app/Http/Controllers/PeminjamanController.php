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
    public function index(): JsonResponse
    {
        $data = Peminjaman::with([
            'peminjam',
            'detailPeminjaman.aset.masterBarang',
        ])->orderByDesc('nomor_peminjaman')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar peminjaman berhasil diambil.',
            'data'    => PeminjamanResource::collection($data),
        ]);
    }

    public function store(StorePeminjamanRequest $request): JsonResponse
    {
        $validated = $request->validated();

        try {
            $peminjaman = DB::transaction(function () use ($validated) {

                $peminjaman = Peminjaman::create([
                    'nomor_peminjaman'  => $validated['nomor_peminjaman'],
                    'tanggal_pinjam'    => $validated['tanggal_pinjam'],
                    'id_peminjam'       => $validated['id_peminjam'],
                    'nomor_telepon'     => $validated['nomor_telepon'] ?? null,
                    'lama_pinjam_hari'  => $validated['lama_pinjam_hari'],
                    'status_peminjaman' => 'Sedang Dipinjam',
                    'keterangan'        => $validated['keterangan'] ?? null,
                ]);

                $kodeBarangs = [];

                foreach ($validated['detail'] as $item) {
                    DetailPeminjaman::create([
                        'nomor_peminjaman' => $peminjaman->nomor_peminjaman,
                        'kode_barang'      => $item['kode_barang'],
                    ]);

                    $kodeBarangs[] = $item['kode_barang'];
                }

                Aset::whereIn('kode_barang', $kodeBarangs)->update([
                    'status_ketersediaan' => 'Dipinjam',
                ]);

                return $peminjaman;
            });

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
                ]);

                // Record di pengembalian berdasarkan skema db
                \DB::table('pengembalian')->insert([
                    'nomor_peminjaman' => $peminjaman->nomor_peminjaman,
                    'tanggal_kembali'  => now()->toDateString()
                ]);

                // Update status semua aset yang dipinjam → Tersedia
                $kodeBarangs = $peminjaman->detailPeminjaman->pluck('kode_barang')->toArray();

                Aset::whereIn('kode_barang', $kodeBarangs)->update([
                    'status_ketersediaan' => 'Tersedia',
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
                if ($peminjaman->status_peminjaman === 'Sedang Dipinjam') {
                    $kodeBarangs = $peminjaman->detailPeminjaman->pluck('kode_barang')->toArray();

                    Aset::whereIn('kode_barang', $kodeBarangs)->update([
                        'status_ketersediaan' => 'Tersedia',
                    ]);
                }

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
