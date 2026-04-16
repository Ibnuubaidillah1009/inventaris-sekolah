<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMapelRequest;
use App\Http\Requests\UpdateMapelRequest;
use App\Http\Resources\MapelResource;
use App\Models\Mapel;
use Illuminate\Http\JsonResponse;

class MapelController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => 'Daftar mapel berhasil diambil.',
            'data'    => MapelResource::collection(Mapel::all()),
        ]);
    }

    public function store(StoreMapelRequest $request): JsonResponse
    {
        $mapel = Mapel::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Mapel berhasil ditambahkan.',
            'data'    => new MapelResource($mapel),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $mapel = Mapel::find($id);

        if (!$mapel) {
            return response()->json([
                'status'  => false,
                'message' => 'Mapel tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail mapel berhasil diambil.',
            'data'    => new MapelResource($mapel),
        ]);
    }

    public function update(UpdateMapelRequest $request, string $id): JsonResponse
    {
        $mapel = Mapel::find($id);

        if (!$mapel) {
            return response()->json([
                'status'  => false,
                'message' => 'Mapel tidak ditemukan.',
            ], 404);
        }

        $mapel->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Mapel berhasil diperbarui.',
            'data'    => new MapelResource($mapel),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $mapel = Mapel::find($id);

        if (!$mapel) {
            return response()->json([
                'status'  => false,
                'message' => 'Mapel tidak ditemukan.',
            ], 404);
        }

        $mapel->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Mapel berhasil dihapus.',
        ]);
    }
}
