<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAksesRequest;
use App\Http\Requests\UpdateAksesRequest;
use App\Http\Resources\AksesResource;
use App\Models\Akses;
use Illuminate\Http\JsonResponse;

class AksesController extends Controller
{
    /**
     * Tampilkan daftar semua modul akses.
     */
    public function index(): JsonResponse
    {
        $akses = Akses::all();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar akses berhasil diambil.',
            'data'    => AksesResource::collection($akses),
        ]);
    }

    /**
     * Simpan akses/modul baru.
     */
    public function store(StoreAksesRequest $request): JsonResponse
    {
        $akses = Akses::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Akses berhasil ditambahkan.',
            'data'    => new AksesResource($akses),
        ], 201);
    }

    /**
     * Tampilkan detail satu akses.
     */
    public function show(string $id): JsonResponse
    {
        $akses = Akses::find($id);

        if (!$akses) {
            return response()->json([
                'status'  => false,
                'message' => 'Akses tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail akses berhasil diambil.',
            'data'    => new AksesResource($akses),
        ]);
    }

    /**
     * Update data akses.
     */
    public function update(UpdateAksesRequest $request, string $id): JsonResponse
    {
        $akses = Akses::find($id);

        if (!$akses) {
            return response()->json([
                'status'  => false,
                'message' => 'Akses tidak ditemukan.',
            ], 404);
        }

        $akses->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Akses berhasil diperbarui.',
            'data'    => new AksesResource($akses),
        ]);
    }

    /**
     * Hapus akses.
     */
    public function destroy(string $id): JsonResponse
    {
        $akses = Akses::find($id);

        if (!$akses) {
            return response()->json([
                'status'  => false,
                'message' => 'Akses tidak ditemukan.',
            ], 404);
        }

        $akses->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Akses berhasil dihapus.',
        ]);
    }
}
