<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRuangRequest;
use App\Http\Requests\UpdateRuangRequest;
use App\Http\Resources\RuangResource;
use App\Models\Ruang;
use Illuminate\Http\JsonResponse;

class RuangController extends Controller
{
    public function index(): JsonResponse
    {
        $data = Ruang::with('lokasi')->get();

        return response()->json([
            'status'  => true,
            'message' => 'Daftar ruang berhasil diambil.',
            'data'    => RuangResource::collection($data),
        ]);
    }

    public function store(StoreRuangRequest $request): JsonResponse
    {
        $ruang = Ruang::create($request->validated());
        $ruang->load('lokasi');

        return response()->json([
            'status'  => true,
            'message' => 'Ruang berhasil ditambahkan.',
            'data'    => new RuangResource($ruang),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $ruang = Ruang::with('lokasi')->find($id);

        if (!$ruang) {
            return response()->json([
                'status'  => false,
                'message' => 'Ruang tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail ruang berhasil diambil.',
            'data'    => new RuangResource($ruang),
        ]);
    }

    public function update(UpdateRuangRequest $request, string $id): JsonResponse
    {
        $ruang = Ruang::find($id);

        if (!$ruang) {
            return response()->json([
                'status'  => false,
                'message' => 'Ruang tidak ditemukan.',
            ], 404);
        }

        $ruang->update($request->validated());
        $ruang->load('lokasi');

        return response()->json([
            'status'  => true,
            'message' => 'Ruang berhasil diperbarui.',
            'data'    => new RuangResource($ruang),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $ruang = Ruang::find($id);

        if (!$ruang) {
            return response()->json([
                'status'  => false,
                'message' => 'Ruang tidak ditemukan.',
            ], 404);
        }

        $ruang->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Ruang berhasil dihapus.',
        ]);
    }
}
