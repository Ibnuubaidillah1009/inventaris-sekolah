<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use Illuminate\Http\JsonResponse;

class UnitController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'status'  => true,
            'message' => 'Daftar unit berhasil diambil.',
            'data'    => UnitResource::collection(Unit::all()),
        ]);
    }

    public function store(StoreUnitRequest $request): JsonResponse
    {
        $unit = Unit::create($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Unit berhasil ditambahkan.',
            'data'    => new UnitResource($unit),
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'status'  => false,
                'message' => 'Unit tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status'  => true,
            'message' => 'Detail unit berhasil diambil.',
            'data'    => new UnitResource($unit),
        ]);
    }

    public function update(UpdateUnitRequest $request, string $id): JsonResponse
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'status'  => false,
                'message' => 'Unit tidak ditemukan.',
            ], 404);
        }

        $unit->update($request->validated());

        return response()->json([
            'status'  => true,
            'message' => 'Unit berhasil diperbarui.',
            'data'    => new UnitResource($unit),
        ]);
    }

    public function destroy(string $id): JsonResponse
    {
        $unit = Unit::find($id);

        if (!$unit) {
            return response()->json([
                'status'  => false,
                'message' => 'Unit tidak ditemukan.',
            ], 404);
        }

        $unit->delete();

        return response()->json([
            'status'  => true,
            'message' => 'Unit berhasil dihapus.',
        ]);
    }
}
