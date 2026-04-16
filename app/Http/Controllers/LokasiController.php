<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use App\Http\Resources\LokasiResource;
use App\Models\Lokasi;
use Illuminate\Http\JsonResponse;

class LokasiController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Lokasi::with('ruang')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar lokasi berhasil diambil.',
            'data'    => LokasiResource::collection($data),
        ]);
    }

    public function store(StoreLokasiRequest $request): JsonResponse
    {
        $lokasi = Lokasi::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Lokasi berhasil ditambahkan.',
            'data'    => new LokasiResource($lokasi),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $lokasi = Lokasi::with('ruang')->find($id);

        if (!$lokasi) {
            return response()->json([
                'status'  => false,
                'message' => 'Lokasi tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail lokasi berhasil diambil.',
            'data'    => new LokasiResource($lokasi),
        ]);
    }

    public function update(UpdateLokasiRequest $request, string $id): JsonResponse
    {
        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return response()->json([
                'status'  => false,
                'message' => 'Lokasi tidak ditemukan.',
            ], 404);
        }

        $lokasi->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Lokasi berhasil diperbarui.',
            'data'    => new LokasiResource($lokasi),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return response()->json([
                'status'  => false,
                'message' => 'Lokasi tidak ditemukan.',
            ], 404);
        }

        $lokasi->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Lokasi berhasil dihapus.',
        ]);
    }
}
