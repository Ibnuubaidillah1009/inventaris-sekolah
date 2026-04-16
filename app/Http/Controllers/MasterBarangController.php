<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMasterBarangRequest;
use App\Http\Requests\UpdateMasterBarangRequest;
use App\Http\Resources\MasterBarangResource;
use App\Models\MasterBarang;
use Illuminate\Http\JsonResponse;

class MasterBarangController extends Controller
{
    /**
     * Tampilkan daftar semua master barang beserta relasi.
     */
    public function index(): JsonResponse
    {
        $barang = MasterBarang::with(['kategori', 'merek', 'satuan'])->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar master barang berhasil diambil.',
            'data'    => MasterBarangResource::collection($barang),
        ]);
    }

    /**
     * Simpan master barang baru.
     */
    public function store(StoreMasterBarangRequest $request): JsonResponse
    {
        $barang = MasterBarang::create($request->validated());
        $barang->load(['kategori', 'merek', 'satuan']);

        return response()->json([
            'status'  => true,
            'message' => 'Master barang berhasil ditambahkan.',
            'data'    => new MasterBarangResource($barang),
        ], 201);
    }

    /**
     * Tampilkan detail satu master barang.
     */
    public function show(string $id): JsonResponse
    {
        $barang = MasterBarang::with(['kategori', 'merek', 'satuan'])->find($id);

        if (!$barang) {
            return response()->json([
                'status'  => false,
                'message' => 'Master barang tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail master barang berhasil diambil.',
            'data'    => new MasterBarangResource($barang),
        ]);
    }

    /**
     * Update data master barang.
     */
    public function update(UpdateMasterBarangRequest $request, string $id): JsonResponse
    {
        $barang = MasterBarang::find($id);

        if (!$barang) {
            return response()->json([
                'status'  => false,
                'message' => 'Master barang tidak ditemukan.',
            ], 404);
        }

        $barang->update($request->validated());
        $barang->load(['kategori', 'merek', 'satuan']);

        return response()->json([
            'status'  => true,
            'message' => 'Master barang berhasil diperbarui.',
            'data'    => new MasterBarangResource($barang),
        ]);
    }

    /**
     * Hapus master barang.
     */
    public function destroy(string $id): JsonResponse
    {
        $barang = MasterBarang::find($id);

        if (!$barang) {
            return response()->json([
                'status'  => false,
                'message' => 'Master barang tidak ditemukan.',
            ], 404);
        }

        $barang->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Master barang berhasil dihapus.',
        ]);
    }
}
