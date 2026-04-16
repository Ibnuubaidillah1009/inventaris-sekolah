<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAsetRequest;
use App\Http\Requests\UpdateAsetRequest;
use App\Http\Resources\AsetResource;
use App\Models\Aset;
use Illuminate\Http\JsonResponse;

class AsetController extends Controller
{
    /**
     * Tampilkan daftar semua aset beserta relasi.
     */
    public function index(): JsonResponse
    {
        $aset = Aset::with([
            'masterBarang.kategori',
            'masterBarang.merek',
            'masterBarang.satuan',
            'ruang.lokasi',
        ])->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar aset berhasil diambil.',
            'data'    => AsetResource::collection($aset),
        ]);
    }

    /**
     * Simpan aset baru.
     */
    public function store(StoreAsetRequest $request): JsonResponse
    {
        $aset = Aset::create($request->validated());
        $aset->load([
            'masterBarang.kategori',
            'masterBarang.merek',
            'masterBarang.satuan',
            'ruang.lokasi',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Aset berhasil ditambahkan.',
            'data'    => new AsetResource($aset),
        ], 201);
    }

    /**
     * Tampilkan detail satu aset termasuk data bangunan/tanah jika ada.
     */
    public function show(string $id): JsonResponse
    {
        $aset = Aset::with([
            'masterBarang.kategori',
            'masterBarang.merek',
            'masterBarang.satuan',
            'ruang.lokasi',
            'asetBangunan',
            'asetTanah',
        ])->find($id);

        if (!$aset) {
            return response()->json([
                'status'  => false,
                'message' => 'Aset tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail aset berhasil diambil.',
            'data'    => new AsetResource($aset),
        ]);
    }

    /**
     * Update data aset.
     */
    public function update(UpdateAsetRequest $request, string $id): JsonResponse
    {
        $aset = Aset::find($id);

        if (!$aset) {
            return response()->json([
                'status'  => false,
                'message' => 'Aset tidak ditemukan.',
            ], 404);
        }

        $aset->update($request->validated());
        $aset->load([
            'masterBarang.kategori',
            'masterBarang.merek',
            'masterBarang.satuan',
            'ruang.lokasi',
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Aset berhasil diperbarui.',
            'data'    => new AsetResource($aset),
        ]);
    }

    /**
     * Hapus aset.
     */
    public function destroy(string $id): JsonResponse
    {
        $aset = Aset::find($id);

        if (!$aset) {
            return response()->json([
                'status'  => false,
                'message' => 'Aset tidak ditemukan.',
            ], 404);
        }

        $aset->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Aset berhasil dihapus.',
        ]);
    }
}
