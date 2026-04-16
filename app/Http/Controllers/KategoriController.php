<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKategoriRequest;
use App\Http\Requests\UpdateKategoriRequest;
use App\Http\Resources\KategoriResource;
use App\Models\Kategori;
use Illuminate\Http\JsonResponse;

class KategoriController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => 'Daftar kategori berhasil diambil.',
            'data'    => KategoriResource::collection(Kategori::all()),
        ]);
    }

    public function store(StoreKategoriRequest $request): JsonResponse
    {
        $kategori = Kategori::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Kategori berhasil ditambahkan.',
            'data'    => new KategoriResource($kategori),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'status'  => false,
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail kategori berhasil diambil.',
            'data'    => new KategoriResource($kategori),
        ]);
    }

    public function update(UpdateKategoriRequest $request, string $id): JsonResponse
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'status'  => false,
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        $kategori->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Kategori berhasil diperbarui.',
            'data'    => new KategoriResource($kategori),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $kategori = Kategori::find($id);

        if (!$kategori) {
            return response()->json([
                'status'  => false,
                'message' => 'Kategori tidak ditemukan.',
            ], 404);
        }

        $kategori->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Kategori berhasil dihapus.',
        ]);
    }
}
